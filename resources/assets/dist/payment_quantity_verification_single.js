$(document).ready(function() {
    // Get login user profile data
    $("#view_users_table_wrapper").hide();
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3 ]; // projects
    console.log(project_id);
    report_id = url[ url.length - 1 ]; // Report_id
    console.log(report_id);

    // Check View All Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray( "payment_quantity_verification_view_all", check_user_access );
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
        window.agency_id = data.data[0].ps_agency_name;
        $("#company_name").val(parseInt(agency_id));
        $(".loading_data").hide();
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
            console.log(data.data.f_name);
            $('#contractor_name').text(data.data.f_name);
        })
    })

    // Get Selected Agency
    jQuery.ajax({
    url: baseUrl + "quantity-verification-report-name/"+report_id,
        type: "GET",
        headers: {
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        // console.log(data.data[0].ppq_month_name);
        $('#month_name').text(data.data[0].ppq_month_name);
    })


    jQuery.ajax({
        url: baseUrl+"quantity-verification-report/"+report_id,
        type: "GET",
        headers: {
            "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
        .done(function(data, textStatus, jqXHR) {
            // console.log(data.data);
            $("#view_users_table_wrapper tbody tr").hide();
            // Foreach Loop
            var count = 1;
            jQuery.each( data.data, function( i, val ) {
                var total = val.pbi_item_qty - val.pqv_latest_qty;
                console.log(total);
                if(total >= 0){
                    var total_use_qty = val.pqv_latest_qty;
                }
                else {
                    var total_use_qty = '<span style="color:#f00; font-weight:bold;">'+val.pqv_latest_qty+'</span>';
                }

                var t = $('#view_users_table').DataTable();
                t.row.add([
                    count, //val.pqv_item_id,
                    val.pbi_item_description,
                    val.pbi_item_unit,
                    val.pbi_item_qty,
                    total_use_qty,
                    val.pqv_previous_qty,
                    val.pqv_month_qty
                ]).draw( false );
                count++; 
            });
            $("#view_users_table_wrapper").show();
            $(".loading_data").hide();

        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            console.log(response);
            if(response == 403){
                // window.location.href = baseUrl + "403";
            }
            else if(response == 404){
                // window.location.href = baseUrl + "404";
                $("#view_users_table_wrapper").show();
                $(".loading_data").hide();
            }
            else {
                window.location.href = baseUrl + "500";
            }
        });

});