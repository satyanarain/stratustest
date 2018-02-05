$(document).ready(function() {
    // Get login user profile data
    $("#submit_new_btn").hide();
    // $("#bid_amount").hide();
    //$('#upload_doc_id').removeAttr('value');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3]; // projects
    // console.log(project_id);

    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');

    // Check View All Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("rfi_add", check_user_access );
    console.log(check_permission);
    if(check_permission < 1){
        window.location.href = baseUrl + "403";
    }
    else {
        console.log('Yes Permission');
        $('.body-content .wrapper').show();
    }

    // Get Project Currency
    jQuery.ajax({
    url: baseUrl+project_id+"/project_setting_get/project_currency",
        type: "GET",
        headers: {
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        // console.log(data.data.pset_meta_value);
        var project_currency_id = data.data.pset_meta_value;
        jQuery.ajax({
        url: baseUrl + "currency/"+project_currency_id,
            type: "GET",
            headers: {
              "Content-Type": "application/json",
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            console.log(data.data.cur_symbol);
            $('.project_currency').text(data.data.cur_symbol+' ');
        })
    })

    // Get Selected Agency
    jQuery.ajax({
        url: baseUrl + "standards/"+project_id+"/standard",
            type: "GET",
            headers: {
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            window.agency_id = data.data[0].ps_agency_name;
            $("#company_name").val(parseInt(agency_id));

            // Select Company Detail for PDF
            jQuery.ajax({
            url: baseUrl + "firm-name/"+agency_id,
                type: "GET",
                headers: {
                  "Content-Type": "application/json",
                  "x-access-token": token
                },
                contentType: "application/json",
                cache: false
            })
            .done(function(data, textStatus, jqXHR) {
                $('.loading_data').hide();
                var f_name = data.data.f_name;
                $('#contractor_name').text(f_name);
            })
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            console.log(response);
            if(response == 403){
                // window.location.href = baseUrl + "403";
            }
            else if(response == 404){
                // alert('faizan');
                // window.location.href = baseUrl + "404";
                $(".loading_data").hide();
            }
            else {
                window.location.href = baseUrl + "500";
            }
        });
        get_new_rfi_number();


        jQuery.ajax({
            url: baseUrl + "projects/"+project_id,
            type: "GET",
            headers: {
              "Content-Type": "application/json",
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            var project_name = data.data.p_name;
            $('#project_name_title').text("Project: " + project_name);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            if(response == 403){
                // window.location.href = baseUrl + "403";
                console.log("403");
            }
            else if(response == 404){
                console.log("404");
                // window.location.href = baseUrl + "404";
            }
            else {
                // console.log("500");
                window.location.href = baseUrl + "500";
            }
        })
});

    function get_new_rfi_number(){
        // Get New Submittal Number
        jQuery.ajax({
        url: baseUrl+"/"+project_id+"/ref-for-info-new-number",
            type: "GET",
            headers: {
              "Content-Type": "application/json",
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            console.log(data.data);
            var ri_id = parseInt(data.data)+1;
            $('#request_number').text(ri_id);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            console.log(response);
            if(response == 403){
                window.location.href = baseUrl + "403";
            }
            else if(response == 404){
                // alert('faizan');
                $('#request_number').text(1);
                // window.location.href = baseUrl + "404";
                // $(".loading_data").hide();
            }
            else {
                window.location.href = baseUrl + "500";
            }
        });
    }

    $('.submit_submittal_form').click(function(e) {
        e.preventDefault();
        $('.loading-submit').show();
        var request_number              = $('#request_number').text();
        var request_date                = $('#request_date').text();
        var question_request            = $('#question_request').val();
        var question_proposed           = $('#question_proposed').val();
        var additional_cost             = $("input[name='additonal_cost_type']:checked").val();
        var additional_cost_amount      = $('#additional_cost_amount').val();
        var additional_day              = $("input[name='additonal_day_type']:checked").val();
        var additional_day_add          = $('#additional_day_add').val();
        var file_path                   = $('#upload_doc_id').val();
        var project_id                  = $('#upload_project_id').val();
	    var token                       = localStorage.getItem('u_token');

        console.log(request_number);
        console.log(request_date);
        console.log(question_request);
        console.log(question_proposed);
        console.log(additional_cost);
        console.log(additional_cost_amount);
        console.log(additional_day);
        console.log(additional_day_add);
        console.log(file_path);

        // Validation Certificate
        var html;
        var is_error = false;
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
        if(question_request == ''){
            html += '<li>The question request is invalid.</li>';
            is_error = true;
        }
        if(question_proposed == ''){
            html += '<li>The question question proposed is invalid.</li>';
            is_error = true;
        }
        if(file_path == ''){
            html += '<li>Document is invalid.</li>';
            is_error = true;
        }
        if(additional_cost == null || additional_cost == 'undefined'){
            html += '<li>The additional cost is invalid.</li>';
            is_error = true;
        }
        if(additional_cost == 'yes'){
            if(additional_cost_amount == ''){
                html += '<li>The additional cost amount is invalid.</li>';
                is_error = true;
            }
        }
        if(additional_day == null || additional_day == 'undefined'){
            html += '<li>The additional day is invalid.</li>';
            is_error = true;
        }
        if(additional_day == 'yes'){
            if(additional_day_add == ''){
                html += '<li>The Estimated Days is invalid.</li>';
                is_error = true;
            }
        }

        html += '</ul></div>';
        if(is_error == true){
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").html(html);
            $('.loading-submit').hide();
            $("#alert_message").show();
            setTimeout(function(){
                $("#alert_message").hide()
            },3000)
            return true;
        }
      
        jQuery.ajax({
            url: baseUrl + "request-information/add",
            type: "POST",
            data: {
                "request_number"            : request_number,
                "request_date"              : request_date,
                "question_request"          : question_request,
                "question_proposed"         : question_proposed,
                "additional_cost"           : additional_cost,
                "additional_cost_amount"    : additional_cost_amount,
                "additional_day"            : additional_day,
                "additional_day"            : additional_day,
                "additional_day_add"        : additional_day_add,
                "file_path"                 : file_path,
                "project_id"                : project_id
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            $('.loading-submit').hide();
            console.log(data.description);
            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New request for information added successfully!</div></div>';
            $("#alert_message").html(html);
            $("#alert_message").fadeOut(3000);
            $('#question_request').removeAttr('value');
            $('#question_proposed').removeAttr('value');
            $('#additional_cost_amount').removeAttr('value');
            $('#additional_day_add').removeAttr('value');
            $('.additonal_day_div').hide();
            $('.additonal_cost_div').hide();
            $('.submit_submittal_form').text('add another');
            $('input[name="additonal_day_type"]').attr('checked', false);
            $('input[name="additonal_cost_type"]').attr('checked', false);
            $("#upload_doc_id").removeAttr('value');
            $(".remove_file_drop").trigger("click");
            get_new_rfi_number();
             $('html, body').animate({
                scrollTop: $(".page-head").offset().top
             }, 'fast');
            setTimeout(function(){
                $("#alert_message").hide()
            },5000)
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                $('.loading-submit').hide();
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(responseText.data);
                $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
                }, 'fast')
                $("#alert_message").show();
                $('.loading-submit').hide();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                if(responseText.data.request_date != null){
                    html += '<li>The date is required.</li>';
                }
                if(responseText.data.question_request != null){
                    html += '<li>The request question is required.</li>';
                }
                if(responseText.data.question_proposed != null){
                    html += '<li>The proposed question is required.</li>';
                }
                if(responseText.data.additional_cost != null){
                    html += '<li>The Will this request result in additional costs? is required.</li>';
                }
                if(responseText.data.additional_day != null){
                    html += '<li>The Will this request result in additional days added to the contract? is required.</li>';
                }
                if(responseText.data.project_id != null){
                    html += '<li>The project id field is required.</li>';
                }
                html += '</ul></div>';
                html += '</ul></div>';
                $("#alert_message").html(html);
                setTimeout(function(){
                    $("#alert_message").hide()
                },5000)
        })
    });
