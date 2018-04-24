$(document).ready(function() {
    // Get login user profile data
    $("#update_swppp_form").hide();
    $("#company_name").hide();
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    
    // Get login user profile data
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    puf_id = url[ url.length - 1 ]; // projects
    project_id = url[ url.length - 3 ]; // projects
    console.log(puf_id);

    // Check View All Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray( "unconditional_finals_view_all", check_user_access );
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
        url: baseUrl + "unconditional_finals/"+puf_id,
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
            $(".loading_data").hide();
            var puf_date_of_signature = data.data.puf_date_of_signature;
            $('.date_signature').text(puf_date_of_signature);

            var name_of_claimant = data.data.name_of_claimant;
            $('.name_claimant').text(name_of_claimant);

            var name_of_customer = data.data.name_of_customer;
            $('.name_customer').text(name_of_customer);

            var puf_job_location = data.data.puf_job_location;
            $('.job_location').text(puf_job_location);

            var owner_name = data.data.owner_name;
            $('.owner_name').text(owner_name);
            if(parseInt(data.data.disputed_claim_amount)>0)
                    $('.disputed_claim_amount').text('$ '+ ReplaceNumberWithCommas(data.data.disputed_claim_amount));
            var file_path = data.data.doc_path;
            var file_path_value;
            if(file_path == null){
                file_path_value = '-';
            }
            else {
                file_path_value = '<a href="'+baseUrl+data.data.doc_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
            }
            $('.document').html(file_path_value);
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