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
    var check_permission = jQuery.inArray( "payment_application_view_all", check_user_access );
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
    url: baseUrl + "/"+project_id+"/default_contractor",
        type: "GET",
        headers: {
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        window.agency_id = data.data[0].pna_contactor_name;
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

    // Get Project Currency
    jQuery.ajax({
    url: baseUrl+project_id+"/project_setting_get/project_currency",
        type: "GET",
        headers: {
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        // console.log(data.data.pset_meta_value);
        var project_currency_id = data.data.pset_meta_value;
        jQuery.ajax({
        url: baseUrl + "currency/"+project_currency_id,
            type: "GET",
            headers: {
              "Content-Type": "application/json",
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            console.log(data.data.cur_symbol);
            $('.project_currency').text(data.data.cur_symbol+' ');
        })
    })


    // Get Selected Agency
    jQuery.ajax({
    url: baseUrl + "payment-application-report-name/"+report_id,
        type: "GET",
        headers: {
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        console.log(data.data[0]);
        $('#month_name').text(data.data[0].ppa_month_name);
    })


    jQuery.ajax({
        url: baseUrl+"payment-application-detail-report/"+report_id,
        type: "GET",
        headers: {
            "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
        .done(function(data, textStatus, jqXHR) {
            console.log(data.data);
            $("#view_users_table_wrapper tbody tr").hide();
            // Foreach Loop
            var count = 1;
            jQuery.each( data.data, function( i, val ) {
                var previous_total = val.ppd_previous_qty * val.pbi_item_unit_price;
                var monthly_total = val.ppd_month_qty * val.pbi_item_unit_price;
                
                var t = $('#view_users_table').DataTable();
                t.row.add([
                    count, //val.ppd_item_id,
                    val.pbi_item_description,
                    val.pbi_item_unit,
                    val.pbi_item_qty,
                    ReplaceNumberWithCommas(val.pbi_item_unit_price),
                    '<span class="project_currency"></span>'+ReplaceNumberWithCommas(val.pbi_item_total_price),
                    val.ppd_previous_qty,
                    '<span class="project_currency"></span>'+ReplaceNumberWithCommas(previous_total),
                    val.ppd_month_qty,
                    '<span class="project_currency"></span>'+ReplaceNumberWithCommas(monthly_total)
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