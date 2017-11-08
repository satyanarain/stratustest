$(document).ready(function() {
    // Get login user profile data
    $("#update_swppp_form").hide();
    $("#company_name").hide();
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');

    // Get login user profile data
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    noc_id = url[ url.length - 2 ]; // projects
    project_id = url[ url.length - 4 ]; // projects
    console.log(noc_id);

    setTimeout(function()
    {
        // Selected Improvement Type
        jQuery.ajax({
        url: baseUrl +"/"+project_id+"/improvement-type",
            type: "GET",
            headers: {
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            // Foreach Loop
            jQuery.each(data.data, function( i, val ) {
                if(val.pt_status == 'active'){
                    $("#project_type_dropdown").append(
                         '<option value="'+val.pt_name+'">'+val.pt_name+'</option>'
                     )
                    
                }
            });
            $(".loading_data").remove();
        })

    },1000);
    setTimeout(function()
    {   
        
    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("notice_completion_update", check_user_access );
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

    jQuery.ajax({
        url: baseUrl + "notice-completion/"+noc_id,
        type: "GET",
        headers: {
            "Content-Type": "application/json",
            "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
        .done(function(data, textStatus, jqXHR) {
            console.log(data.data.noc_status);
            var status = data.data.noc_status;
            if(status == "active"){
                status = 'active';
            }
            else {
                status = "deactive";
            }
            $('#status').val(status);
            if(data.data.date_noc_filed!='' && data.data.date_noc_filed!='0000-00-00')
            $('#date_noc_filed').val(data.data.date_noc_filed);
            //alert(data.data.improvement_type);
            $("select#project_type_dropdown").val(data.data.improvement_type);
            

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
        
    },2000);
});


$('#update_notice_completion_form').click(function(e) {
  $('.loading-submit').show();
    e.preventDefault();
    var status               	= $('#status').val();
    var project_id              = $('#project_id').val();
    var token                   = localStorage.getItem('u_token');
    var token = localStorage.getItem('u_token');
    var improvement_type    = $("#project_type_dropdown").val();
    var date_noc_filed      = $("#date_noc_filed").val();
    jQuery.ajax({
        url: baseUrl + "notice-completion/"+noc_id+"/update",
        type: "POST",
        data: {
            "status"            : status,
            "project_id"        : project_id,
            "date_noc_filed" : date_noc_filed,
            "improvement_type" : improvement_type
        },
        headers: {
            "x-access-token": token
        },
        contentType: "application/x-www-form-urlencoded",
        cache: false
    })
        .done(function(data, textStatus, jqXHR) {
            console.log(data);
            $("#alert_message").show(1000);
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Notice of completion updated successfully.</div></div>';
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
            // console.log(responseText.data.currency_name);
            $("#alert_message").show(1000);
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            if(responseText.data.noc_status != null){
                html += '<li>The status field is required.</li>';
            }
            if(responseText.data.noc_project_id != null){
                html += '<li>The project id field is required.</li>';
            }
            html += '</ul></div>';
            $("#alert_message").html(html);
            $("#alert_message").hide(6000);
        })
});
