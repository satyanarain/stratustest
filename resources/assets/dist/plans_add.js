$(document).ready(function() {
    var role    = localStorage.getItem('u_role');
    var token   = localStorage.getItem('u_token');
    var url     = $(location).attr('href').split( '/' );
    project_id  = url[ url.length - 3 ]; // projects
    console.log(project_id);

    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("plan_add", check_user_access );
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



    $('.add_plans_form').click(function(e) {
        e.preventDefault();
        $('.loading-submit').show();
        var name_of_plan                        = $('#name_of_plan').val();
        var date_of_plans                       = $('#date_of_plans').val();
        var plan_approved                       = $("input[name='plan_approved']:checked"). val();
        var upload_doc_id                       = $('#upload_doc_id').val();
        var project_id                          = $('#upload_project_id').val();
	    var token                               = localStorage.getItem('u_token');
        console.log(plan_approved);
        jQuery.ajax({
            url: baseUrl + "project-plan/add",
            type: "POST",
            data: {
                "date"            : date_of_plans,
                "name"            : name_of_plan,
                "approval"        : plan_approved,
                "file_path"       : upload_doc_id,
                "project_id"      : project_id
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
            .done(function(data, textStatus, jqXHR)
            {
                $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
                }, 'fast')
                $("#alert_message").show();
                $('.loading-submit').hide();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New plan added successfully!</div></div>';
                $("#alert_message").html(html);
                $("#name_of_plan").removeAttr('value');
                $("#date_of_plans").removeAttr('value');
                $("#plan_approved").removeAttr('value');
                $('#upload_doc_id').removeAttr('value');
                $(".remove_file_drop").trigger("click");
                $(".first_button").text('Save Another');
                setTimeout(function()
                {
                    $("#alert_message").hide();
                },5000)
            })
        .fail(function(jqXHR, textStatus, errorThrown)
            {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(responseText.date);

                $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
                }, 'fast')
                $("#alert_message").show();
                $('.loading-submit').hide();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                if(responseText.data.date != null){
                	html += '<li>The date field is required.</li>';
                }
                if(responseText.data.name != null){
                    html += '<li>The name field is required.</li>';
                }
                if(responseText.data.approval != null){
                    html += '<li>The approval field is required.</li>';
                }
                if(responseText.data.file_path != null){
                    html += '<li>The document is required.</li>';
                }
                if(responseText.data.project_id != null){
                    html += '<li>The project id field is required.</li>';
                }
                html += '</ul></div>';
                $("#alert_message").html(html);
                setTimeout(function(){
                    $("#alert_message").hide();
                },5000)
        })
    });
