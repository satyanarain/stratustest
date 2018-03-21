$(document).ready(function() {
    // Get login user profile data
    $("#update_swppp_form").hide();
    $("#company_name").hide();
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    var u_new_role = window.localStorage.getItem("u_new_role");
     var url = $(location).attr('href').split( '/' );
    project_id  = url[ url.length - 4 ]; // project_id
    console.log(project_id);

    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("drawing_update", check_user_access );
    console.log(check_permission);
    if(check_permission < 1){
        window.location.href = baseUrl + "403";
    }
    else {
        console.log('Yes Permission');
        $('.body-content .wrapper').show();
    }

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

    // Get login user profile data
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 4 ]; // projects
    console.log(project_id);
    built_drawing_id = url[ url.length - 2 ]; // projects
    console.log(built_drawing_id);
    //var role            = localStorage.getItem('u_role');

    var role = u_new_role;
    if(role == 'contractor'){
        $('#engineer_hide').hide();
        console.log('hide engineer');
    }
    else if (role == 'engineer'){
        $('.contractor_hide').hide();
        console.log('hide contractor');
    }
    else {
        $('.contractor_hide').hide();
        console.log('hide contractor');
    }

    // Get Selected Agency
    jQuery.ajax({
    url: baseUrl + "/"+project_id+"/default_contractor",
        type: "GET",
        headers: {
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        // console.log(data.data);
        window.agency_id = data.data[0].pna_contactor_name;
        // console.log(agency_id);
        $("#company_name").val(parseInt(agency_id));
        $(".loading_data").hide();
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
            $('#contractor_name').text(data.data.f_name);
        })
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        console.log(response);
        if(response == 403){
            window.location.href = baseUrl + "403";
        }
        else if(response == 404){
           $(".loading_data").hide();
        }
        else {
            window.location.href = baseUrl + "500";
        }
    });

    jQuery.ajax({
        url: baseUrl + "build_drawings/"+built_drawing_id,
        type: "GET",
        headers: {
            "Content-Type": "application/json",
            "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
        .done(function(data, textStatus, jqXHR) {
            console.log(data);
//            var status = data.data.pbd_status;
//            if(status == "active"){
//                status = 'active';
//            }
//            else {
//                status = "deactive";
//            }
//            $('#status').val(status);

            $('#built_description').val(data.data.pbd_description);

            var custom_cert_path = data.data.doc_path;
            var custom_cert_path_value;
            if(custom_cert_path == null){
                custom_cert_path_value = '-';
            }
            else {
                custom_cert_path_value = '<a href="'+baseUrl+custom_cert_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf.svg" width="40"/></a>';
                var file_iframe_value = '<iframe src="http://apps.groupdocs.com/document-annotation2/embed/'+custom_cert_path+'" frameborder="0" width="100%" height="800"></iframe>';
            }
            $('#built_drawing').html(custom_cert_path_value);
            $('#review_document').html(file_iframe_value);

            if(data.data.pbd_engineer_redline == 'complete'){
                var engineer_redline = 'complete';
            }
            else if(data.data.pbd_engineer_redline == 'additional_info'){
                var engineer_redline = 'additional_info';
            }
            else if(data.data.pbd_engineer_redline == 'past_due'){
                var engineer_redline = 'past_due';
            }
            else {
                var engineer_redline = ' --- ';
            }
            $('#built_engineer').val(engineer_redline);

            if(data.data.pbd_contractor_redline == 'complete'){
                var contractor_redline = 'complete';
            }
            else if(data.data.pbd_contractor_redline == 'additional_info'){
                var contractor_redline = 'additional_info';
            }
            else if(data.data.pbd_contractor_redline == 'past_due'){
                var contractor_redline = 'past_due';
            }
            else if(data.data.pbd_contractor_redline == 'not_provided'){
                var contractor_redline = 'not_provided';
            }
            else {
                var contractor_redline = ' --- ';
            }
            $('#built_contractor').val(contractor_redline);

            if(data.data.pbd_change_plan == 'yes'){
                var change_plan = 'yes';
            }
            else if(data.data.pbd_change_plan == 'no'){
                var change_plan = 'no';
            }
            else {
                var change_plan = ' --- ';
            }
            $('#built_plan').val(change_plan);

            $("#update_swppp_form").show();
            $(".loading_data").hide();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            if(response == 403){
                window.location.href = baseUrl + "403";
                // console.log("403");
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


$('#update_built_form').click(function(e) {
  $('.loading-submit').show();
    e.preventDefault();
    //var built_contractor        = $('#built_contractor').val();
    var built_contractor        = $("input[name='built_contractor']:checked").val();
    var built_engineer          = $('#built_engineer').val();
    var built_plan              = $('#built_plan').val();
    //var status               	= $('#status').val();
    var project_id              = $('#upload_project_id').val();
    var token                   = localStorage.getItem('u_token');

    console.log(built_plan);
    console.log(built_contractor);
    //var role            = localStorage.getItem('u_role');
    var role = u_new_role;
    if(role == 'contractor'){
        // Validation Certificate
        var html;
        var is_error = false;
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';

        if(built_contractor == null){
            html += '<li>The Contractor Redline field is invalid.</li>';
            var is_error = true;
        }
        if(built_plan == null){
            html += '<li>The Change Plan field is invalid.</li>';
            var is_error = true;
        }
        html += '</ul></div>';
        if(is_error == true){
            $("#alert_message").show();
            $('.loading-submit').hide();
            $("#alert_message").html(html);
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            setTimeout(function(){
                $("#alert_message").hide()
                return false;
            },5000)
        }

        var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "build_drawings_status_contractor/"+built_drawing_id+"/update",
            type: "POST",
            data: {
                "contractor_redline"    : built_contractor,
                "built_plan"            : built_plan,
                //"status"                : status,
                "project_id"            : project_id
            },
            headers: {
                "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            console.log(data);
            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">As built updated successfully!</div></div>';
            $("#alert_message").html(html);
            setTimeout(function()
            {
                $("#alert_message").hide();
            },5000)

        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var responseText, html;
            responseText = JSON.parse(jqXHR.responseText);
            console.log(responseText);
            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            if(responseText.data.built_plan != null){
                html += '<li>The Change Plan field is invalid.</li>';
            }
            if(responseText.data.contractor_redline != null){
                html += '<li>The Contractor Redline field is invalid.</li>';
            }
//            if(responseText.data.status != null){
//                html += '<li>The Status field is invalid.</li>';
//            }
            if(responseText.data.project_id != null){
                html += '<li>The project id field is invalid.</li>';
            }
            html += '</ul></div>';
            $("#alert_message").html(html);
            $("#alert_message").hide();
        })
    }
    else {
        // Validation Certificate
        var html;
        var is_error = false;
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';

        if(built_engineer == null){
            html += '<li>The Engineer Redline field is invalid.</li>';
            var is_error = true;
        }
        if(built_plan == null){
            html += '<li>The Change Plan field is invalid.</li>';
            var is_error = true;
        }
        html += '</ul></div>';
        if(is_error == true){
            $("#alert_message").show();
            $('.loading-submit').hide();
            $("#alert_message").html(html);
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            setTimeout(function(){
                $("#alert_message").hide()
                return true;
            },5000)
        }

        var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "build_drawings_status_engineer/"+built_drawing_id+"/update",
            type: "POST",
            data: {
                "engineer_redline"    : built_engineer,
                "built_plan"            : built_plan,
                //"status"                : status,
                "project_id"            : project_id
            },
            headers: {
                "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            console.log(data);
            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">As built updated successfully!</div></div>';
            $("#alert_message").html(html);
            setTimeout(function()
            {
                $("#alert_message").hide();
            },5000)

        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var responseText, html;
            responseText = JSON.parse(jqXHR.responseText);
            console.log(responseText);
            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            if(responseText.data.built_plan != null){
                html += '<li>The Change Plan field is invalid.</li>';
            }
            if(responseText.data.engineer_redline != null){
                html += '<li>The Engineer Redline field is invalid.</li>';
            }
//            if(responseText.data.status != null){
//                html += '<li>The Status field is invalid.</li>';
//            }
            if(responseText.data.project_id != null){
                html += '<li>The project id field is invalid.</li>';
            }
            html += '</ul></div>';
            $("#alert_message").html(html);
            setTimeout(function()
            {
                $("#alert_message").hide();
            },5000)
        })
    }




});
