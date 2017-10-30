$(document).ready(function() {
    $(".select2").hide();
    $('#upload_doc_id').removeAttr('value');
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    project_id  = url[ url.length - 3 ]; // projects
    console.log(project_id);

    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("geotechnical_add", check_user_access );
    console.log(check_permission);
    if(check_permission < 1){
        window.location.href = baseUrl + "403";
    }
    else {
        console.log('Yes Permission');
        $('.body-content .wrapper').show();
    }

    jQuery.ajax({
        url: baseUrl+project_id+"/company_name_user",
        type: "GET",
        headers: {
            "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        // console.log(data.data);
        // Foreach Loop
        jQuery.each(data.data, function( i, val ) {
            if(val.f_status == 'active'){
                $("#company_name").append(
                    '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                )
            }else {

            }
        });
        // $( "h2" ).appendTo( $( ".container" ));
        $(".loading_data").remove();
        $(".select2").show();
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        console.log(response);
        if(response == 403){
            console.log('Company name 403');
            // window.location.href = baseUrl + "403";
        }
        else if(response == 404){
            console.log('Company name 404');
            // window.location.href = baseUrl + "404";
        }
        else {
            window.location.href = baseUrl + "500";
        }
    });

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


$('#add_georeport_form').submit(function(e) {
    $('.loading-submit').show();
    e.preventDefault();
    var date_of_report          = $('#date_of_report').val();
    var name_of_report          = $('#name_of_report').val();
    var company_name            = $('#company_name').val();
    var standard_link           = $('#standard_link').val();
    var applicable              = $("input[name='applicable']:checked"). val();
    var upload                  = $("input[name='upload']:checked"). val();
    var georeport_file_path     = $('#upload_doc_id').val();
    var georeport_project_id    = $('#upload_project_id').val();
    var token                   = localStorage.getItem('u_token');
    console.log(date_of_report);


    // Validation Certificate
        var html;
        var is_error = false;
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
        if(date_of_report == ''){
            html += '<li>Report Date is invalid.</li>';
            is_error = true;
        }
        if(name_of_report == ''){
            html += '<li>Report Name is invalid.</li>';
            is_error = true;
        }

        if(applicable == "" || applicable == "undefined" || applicable == null){
            html += '<li>Applicable is invalid.</li>';
            is_error = true;
        }
        
        if(upload == "" || upload == "undefined" || upload == null){
            html += '<li>Available is invalid.</li>';
            is_error = true;
        }
        else {
            if($('input:radio[name=upload]:checked').val() == "yes"){
                $('.upload_doc_panel').css("display", "block");
                if(georeport_file_path == ''){
                    html += '<li>Document is invalid.</li>';
                    is_error = true;
                }
            }
        }
        html += '</ul></div>';

        if(is_error == true){
            $("#alert_message").html(html);
            $("#alert_message").show();
            $('.loading-submit').hide();
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            setTimeout(function(){
                $("#alert_message").hide();
                return false;
            },5000)
            return;
        } // if close
        else {
            jQuery.ajax({
                url: baseUrl + "geo-report/add",
                type: "POST",
                data: {
                    "date_of_report"    : date_of_report,
                    "name_of_report"    : name_of_report,
                    "name_of_firm"      : company_name,
                    "applicable"        : applicable,
                    "upload"            : upload,
                    "file_path"         : georeport_file_path,
                    "project_id"        : georeport_project_id
                },
                headers: {
                    "x-access-token": token
                },
                contentType: "application/x-www-form-urlencoded",
                cache: false
            })
            .done(function(data, textStatus, jqXHR) {
                console.log(data.description);
                // html = '<div class="alert alert-block alert-success fade in">'+data.description+'</div>';
                // $("#alert_message").html(html);
                $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
                }, 'fast')
                $("#alert_message").show();
                $('.loading-submit').hide();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New Geotechnical report added successfully!</div></div>';
                $("#alert_message").html(html);
                $("#name_of_report").removeAttr('value');
                $("#date_of_report").removeAttr('value');
                $('#upload_doc_id').removeAttr('value');
                $(".remove_file_drop").trigger("click");
                $('#company_name').prop('selected', function() {
                    return this.defaultSelected;
                });
                $('input[name="applicable"]').attr('checked', false);
                $('input[name="upload"]').attr('checked', false);
                $(".another_button").show();
                $(".first_button").hide();
                setTimeout(function()
                {
                    $("#alert_message").hide();
                },5000)
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                // console.log(responseText.data.currency_name);
                $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
                }, 'fast')
                $("#alert_message").show();
                $('.loading-submit').hide();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-error"><ul>';
                if(responseText.data.date_of_report != null){
                    html += '<li>The date of report field is required.</li>';
                }
                if(responseText.data.name_of_report != null){
                    html += '<li>The report name field is required.</li>';
                }
                if(responseText.data.name_of_firm != null){
                    html += '<li>The company name field is required.</li>';
                }
                if(responseText.data.applicable != null){
                    html += '<li>The applicable field is required.</li>';
                }
                if(responseText.data.available != null){
                    html += '<li>The document field is required.</li>';
                }
                if(responseText.data.project_id != null){
                    html += '<li>The project id field is required.</li>';
                }
                html += '</ul></div>';
                $("#alert_message").html(html);
                setTimeout(function()
                {
                    $("#alert_message").hide();
                },5000)
            })
        }// else close 

    
        
});
