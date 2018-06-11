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
    schedule_id = url[ url.length - 2 ]; // projects
    console.log(schedule_id);
    //var role            = localStorage.getItem('u_role');

    var role = u_new_role;
    

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
        url: baseUrl + "schedule/"+schedule_id,
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
            if(data.data.schedule_status=="active")
                $("#built_contractor").val('active');
            else
                $("#built_contractor").val('deactive');
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
    var status       = $('#built_contractor').val();
    var project_id   = $('#upload_project_id').val();
    var token        = localStorage.getItem('u_token');
   
    var token = localStorage.getItem('u_token');
    jQuery.ajax({
        url: baseUrl + "schedule/"+schedule_id+"/update",
        type: "POST",
        data: {
            "status"                : status,
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
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Schedule updated successfully!</div></div>';
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
        $('.loading-submit').hide();
    })
});
