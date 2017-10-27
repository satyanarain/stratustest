$(document).ready(function() {
    // Get login user profile data
    $("#company_name").hide();
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3]; // projects
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');

    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("drawing_add", check_user_access );
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
        // console.log(data.data);
        window.agency_id = data.data[0].ps_agency_name;
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
           // console.log(data.data.f_name);
            $('#contractor_name').text(data.data.f_name);
            $(".loading_data").hide();
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
           $(".loading_data").hide();
        }
        else {
            window.location.href = baseUrl + "500";
        }
    }); 

});

$('.add_built_drawing').click(function(e) {
    e.preventDefault();
    $('.loading-submit').show();
    var built_description       = $('#built_description').val();
    var file_path               = $('#upload_doc_id').val();
    var project_id              = $('#upload_project_id').val();
    var token                   = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "build_drawings/add",
            type: "POST",
            data: {
                "description"       : built_description,
                "file_path"         : file_path,
                "project_id"        : project_id
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
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").show();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New as built added successfully!</div></div>';
            $("#alert_message").html(html);
            $("#built_description").removeAttr('value');
            $("#upload_doc_id").removeAttr('value');
            $(".remove_file_drop").trigger("click");
            $(".first_button").hide();
            $(".another_button").show();
            setTimeout(function()
            {
                $("#alert_message").hide();
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
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>'; 
            if(responseText.data.description != null){
            html += '<li>The description field is invalid</li>';
            }
            if(responseText.data.file_path != null){
                html += '<li>The document is invalid</li>';
            }
            if(responseText.data.project_id != null){
                html += '<li>The project id field is invalid</li>';
            } 
            
            html += '</ul></div>';
            $("#alert_message").html(html);
            setTimeout(function(){
                $("#alert_message").hide();
            },5000)
        })
});