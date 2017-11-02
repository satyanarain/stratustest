$(document).ready(function() {
    // Get login user profile data
    $("#update_swppp_form").hide();
    $("#company_name").hide();
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    
    // Get login user profile data
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    ppn_id = url[ url.length - 1 ]; // projects
    project_id = url[ url.length - 3 ]; // projects
    console.log(ppn_id);

    // Check View All Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray( "preliminary_view_all", check_user_access );
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
        url: baseUrl + "preliminary-notice/"+ppn_id,
        type: "GET",
        headers: {
            "Content-Type": "application/json",
            "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
        .done(function(data, textStatus, jqXHR) {
            //console.log(data);
            $('#contractor_name_title').text("Contractor: " + data.data.preliminary_notice_firm);
            jQuery.each(data.data.liens, function( i, val ) {
                var pplr_path_value = '';
                if(val.doc_path != ''){
                    pplr_path_value = '<a href="'+baseUrl+val.doc_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
                }
                $(".lien_release_list").append(
                    '<tr><td>'+val.lien_release_note+'</td><td>'+val.date_of_billed_through+'</td>\n\
                    <td>'+capitalize(val.pplr_type)+'</td><td>'+pplr_path_value+'</td></tr>'
                )
            });
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

function capitalize(s)
{
    return s[0].toUpperCase() + s.slice(1);
}