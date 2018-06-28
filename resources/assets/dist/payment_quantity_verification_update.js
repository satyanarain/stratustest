$(document).ready(function() {
    // Get login user profile data
    $("#update_swppp_form").hide();
    $("#company_name").hide();
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');

    // Get login user profile data
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    report_id = url[ url.length - 2 ]; // projects
    project_id = url[ url.length - 4 ]; // projects
    console.log(report_id);
    //alert(report_id);
    //alert(project_id);
    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("payment_quantity_verification_update", check_user_access );
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
        url: baseUrl + "quantity-verification-report-name/"+report_id,
        type: "GET",
        headers: {
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        if(data.data[0].approval_status=="Approved")
                window.location.href = baseUrl + "dashboard/"+project_id+"/payment_quantity_verification_monthly";
        $('#month_name').text(data.data[0].ppq_month_name);
        if(data.data[0].approval_status=="Approved")
            $("#approval_status").attr('checked',true);
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
                var quantity_upto_date = val.pqv_previous_qty+val.pqv_month_qty;
                var total = val.pbi_item_qty - quantity_upto_date;
                console.log(total);
                
                if(total >= 0){
                    //var total_use_qty = '<input type="text" value="'+val.pqv_latest_qty+'" name="pqv_latest_qty" id="pqv_latest_qty'+val.pqv_id+'">';
                    var total_use_qty = '<span class="total_use_qty'+val.pqv_id+'">'+quantity_upto_date+'</span>';
                }
                else {
                    //var total_use_qty = '<span style="color:#f00; font-weight:bold;">'+val.pqv_latest_qty+'</span>';
                    //var total_use_qty = '<input style="color:#f00;" type="text" value="'+val.pqv_latest_qty+'" name="pqv_latest_qty" id="pqv_latest_qty'+val.pqv_id+'">';
                    var total_use_qty = '<span class="total_use_qty'+val.pqv_id+'" style="color:#f00; font-weight:bold;">'+quantity_upto_date+'</span>';
                }

                var t = $('#view_users_table').DataTable();
                t.row.add([
                    count, //val.pqv_item_id,
                    val.pbi_item_description,
                    val.pbi_item_unit,
                    '<span class="total_contract_qty'+val.pbi_item_qty+'">'+parseInt(val.pbi_item_qty)+'</span>',
                    total_use_qty,
                    val.pqv_previous_qty+'<input type="hidden" value="'+val.pqv_previous_qty+'" name="pqv_previous_qty" id="pqv_previous_qty'+val.pqv_id+'">',
                    '<input type="text" value="'+val.pqv_month_qty+'" name="pqv_month_qty" id="pqv_month_qty'+val.pqv_id+'">',
                    '<input type="button" class="btn update_report" report_id="'+val.pqv_id+'" value="Update">'
                ]).draw( false );
                count++; 
            });
            $(".update_report").click(function() {
                var pqv_id = $(this).attr('report_id');
                $('.loading-submit').show();
                //var pqv_latest_qty        = $('#pqv_latest_qty'+pqv_id).val();
                var pqv_previous_qty        = $('#pqv_previous_qty'+pqv_id).val();
                var pqv_month_qty           = $('#pqv_month_qty'+pqv_id).val();
                var total_contract_qty      = $('.total_contract_qty'+pqv_id).html();
                //alert(total_contract_qty);
                var token                   = localStorage.getItem('u_token');
                jQuery.ajax({
                    url: baseUrl + "dashboard/"+project_id+"/payment_quantity_verification/"+pqv_id+"/update",
                    type: "POST",
                    data: {
                        //"pqv_latest_qty"    : pqv_latest_qty,
                        "pqv_previous_qty"  : pqv_previous_qty,
                        "pqv_month_qty"     : pqv_month_qty,
                        "project_id"        : project_id
                    },
                    headers: {
                        "x-access-token": token
                    },
                    contentType: "application/x-www-form-urlencoded",
                    cache: false
                })
                .done(function(data, textStatus, jqXHR) {
                    console.log(data);
                    $("#alert_message").fadeIn(1000);
                    $('.loading-submit').hide();
                    var total_today = parseInt(pqv_previous_qty)+parseInt(pqv_month_qty);
                    $(".total_use_qty"+pqv_id).html(total_today);
                    if(parseInt(total_contract_qty)<total_today)
                    {
                        $(".total_use_qty"+pqv_id).css("color","#f00").css("font-weight","bold");
                    }else{
                        $(".total_use_qty"+pqv_id).css("color","#323232").css("font-weight","normal");
                    }
                    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Payment quantity verification report updated successfully!</div></div>';
                    $("#alert_message").html(html);
                    setTimeout(function()
                    {
                        $("#alert_message").hide();
                    },5000)
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    var responseText, html;
                    responseText = JSON.parse(jqXHR.responseText);
                    // console.log(responseText.data.currency_name);
                    $("#alert_message").fadeIn(1000);
                    $('.loading-submit').hide();
                    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                    if(responseText.data.status != null){
                        html += '<li>The status field is required.</li>';
                    }
                    if(responseText.data.project_id != null){
                        html += '<li>The project id field is required.</li>';
                    }
                    html += '</ul></div>';
                    $("#alert_message").html(html);
                    $("#alert_message").fadeOut(6000);
                })
            })
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
    
    
    
//alert("136");
    
//    jQuery.ajax({
//        url: baseUrl + "dashboard/"+project_id+"/payment_quantity_verification/"+report_id,
//        type: "GET",
//        headers: {
//            "Content-Type": "application/json",
//            "x-access-token": token
//        },
//        contentType: "application/json",
//        cache: false
//    })
//    .done(function(data, textStatus, jqXHR) {
//            console.log(data);
//            var status = data.data.status;
//            if(status == "active"){
//                status = 'active';
//            }
//            else {
//                status = "deactive";
//            }
//            $('#status').val(status);
//
//            $("#update_swppp_form").show();
//            $(".loading_data").hide();
//        })
//    .fail(function(jqXHR, textStatus, errorThrown) {
//            console.log("HTTP Request Failed");
//            var response = jqXHR.responseJSON.code;
//            if(response == 403){
//                window.location.href = baseUrl + "403";
//                // console.log("403");
//            }
//            else if(response == 404){
//                // console.log("404");
//                window.location.href = baseUrl + "404";
//            }
//            else {
//                // console.log("500");
//                window.location.href = baseUrl + "500";
//            }
//        })



    $('#approval_status').click(function () {
        //e.preventDefault();
        $('.loading_data').show();
        var token  = localStorage.getItem('u_token');
        if($(this).prop("checked"))
            var approval_status = "Approved";
        else
            var approval_status = "Pending";
        //console.log(id);
        var r = confirm("Are you sure to approve this report?");
        if (r == true) {
            jQuery.ajax({
                url: baseUrl + "dashboard/"+project_id+"/payment_quantity_verification/"+report_id+"/update_approval_status",
                type: "POST",
                data: {
                    "report_id"         : report_id,
                    "approval_status"   : approval_status,
                },
                headers: {
                  "x-access-token": token
                },
                contentType: "application/x-www-form-urlencoded",
                cache: false
            })
            .done(function(data, textStatus, jqXHR) {
                console.log(data);
                $('.loading_data').hide();
                $("#alert_message").fadeIn(1000);
                $('.loading-submit').hide();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Payment quantity verification report updated successfully!</div></div>';
                $("#alert_message").html(html);
                setTimeout(function()
                {
                    $("#alert_message").hide();
                    window.location.href = baseUrl + "dashboard/"+project_id+"/payment_quantity_verification_monthly";
                },5000)
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                // var response = jqXHR.responseJSON.code;
                console.log(jqXHR.responseJSON);
                $('.loading_data').hide();
            }); 
        } else {
            $('.loading_data').hide();
            return false;
        }
        
    });

});
