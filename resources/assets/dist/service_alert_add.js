$(document).ready(function() {

    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3 ]; // projects

    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("service_alert_add", check_user_access );
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
});

    $('#update_service_alert_form').submit(function(e) {
      $('.loading-submit').show();
        e.preventDefault();
        var status                  = $('#status').val();
        var work_complete           = $('#work_complete').val();
        var project_id              = $('#upload_project_id').val();


        // console.log(standard_type);

        var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "service-alert/"+service_alert_id+"/update",
            type: "POST",
            data: {
                "work_completed"        : work_complete,
                "status"                : status,
                "project_id"            : project_id,
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
            .done(function(data, textStatus, jqXHR) {
            console.log(data);
            // html = '<div class="alert alert-block alert-success fade in">Update successfully!</div>';
            // $("#alert_message").html(html);
            $("#alert_message").fadeIn(1000);
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Updated successfully!</div></div>';
            $("#alert_message").html(html);
            $("#alert_message").fadeOut(3000);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                // console.log(responseText.data.currency_name);
                $("#alert_message").fadeIn(1000);
                $('.loading-submit').hide();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"  style="margin-top:50px;"><div class="toast toast-error"><ul>';
                if(responseText.data.status != null){
                    html += '<li>The status field is required.</li>';
                }
                if(responseText.data.work_completed != null){
                    html += '<li>The work completed field is required.</li>';
                }
                if(responseText.data.project_id != null){
                    html += '<li>The project id field is required.</li>';
                }
                html += '</ul></div>';
                $("#alert_message").html(html);
                $("#alert_message").fadeOut(3000);
        })
    });


    $('#add_service_alert_form').click(function(e) {
      $('.loading-submit').show();
        e.preventDefault();
        var date_called_in          = $('#date_called_in').val();
        var date_ticket_valid       = $('#date_ticket_valid').val();
        var ticket_number           = $('#ticket_number').val();
        var ticket_location         = $('#ticket_location').val();
        var expiration_date         = $('#expiration_date').val();
        var project_id              = $('#project_id').val();
	    var token                   = localStorage.getItem('u_token');

        console.log(date_called_in);
        console.log(date_ticket_valid);

        jQuery.ajax({
            url: baseUrl + "service-alert/add",
            type: "POST",
            data: {
                "date_called_in"        : date_called_in,
                "date_called_valid"     : date_ticket_valid,
                "ticket_number"         : ticket_number,
                "ticket_location"       : ticket_location,
                "expire_date"           : expiration_date,
                "project_id"            : project_id
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
             }, 'fast');
            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Underground service alert added successfully.</div></div>';
            $("#alert_message").html(html);
            $("#alert_message").fadeOut(3000);
            $('#date_called_in').removeAttr('value');
             $('#date_ticket_valid').removeAttr('value');
             $('#ticket_number').removeAttr('value');
             $('#ticket_location').removeAttr('value');
             $('#expiration_date').removeAttr('value');
             $(".first_button").text('Save Another');
             setTimeout(function()
            {
                $("#alert_message").fadeOut(1000);
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
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"  style="margin-top:50px;"><div class="toast toast-error"><ul>';
                if(responseText.data.date_called_in != null){
                	html += '<li>The Date called in is invalid.</li>';
                }
                if(responseText.data.date_called_valid != null){
                    html += '<li>The date ticket is invalid.</li>';
                }
                if(responseText.data.ticket_number != null){
                    html += '<li>The ticket number is invalid.</li>';
                }
                if(responseText.data.ticket_location != null){
                    html += '<li>The ticket location is invalid.</li>';
                }
                if(responseText.data.expire_date != null){
                    html += '<li>The expire date is invalid.</li>';
                }
                if(responseText.data.project_id != null){
                    html += '<li>The project id is invalid.</li>';
                }
                $("#alert_message").html(html);
                setTimeout(function(){
                    $("#alert_message").hide();
                },5000)
        })
    });
