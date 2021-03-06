$(document).ready(function() { 
    // Get login user profile data
    $('#upload_doc_id').removeAttr('value');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 2]; // projects
    console.log(project_id);

    var role = localStorage.getItem('u_role');
    var token  = localStorage.getItem('u_token');


    // Check View All Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray( "cor_review_view_all", check_user_access );
    console.log(check_permission);
    if(check_permission < 1){
        window.location.href = baseUrl + "403";
    }
    else {
        console.log('Yes Permission');
        $('.body-content .wrapper').show();
    }

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
            // console.log(data.data.cur_symbol);
            window.currency_symbol = data.data.cur_symbol;
            console.log(currency_symbol);
            $('.project_currency').text(data.data.cur_symbol+' ');
        })
    })

    // console.log(project_id);
    jQuery.ajax({
    url: baseUrl+project_id+"/change_order_request_item",
        type: "GET",
        headers: {
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        // console.log(data.data);
        $("#request_change_order").hide();
        // Foreach Loop 
        var count = 1;
        var counts = 1;
        jQuery.each( data.data, function( i, val ) {
            var status = val.pco_status;
            if(status == 'active'){
            status = '<span class="label label-success">Active</span>';
            }
            else {
                status = '<span class="label label-danger">Inactive</span>';
            }

            if(val.pcd_approved_by_cm == null || val.pcd_approved_by_cm == "0000-00-00"){
                var pcd_approved_by_cm = '<span class="label label-warning">PENDING</span>';
            }
            else {
                var pcd_approved_by_cm = val.pcd_approved_by_cm;
            }

            if(val.pcd_approved_by_owner == null || val.pcd_approved_by_owner == "0000-00-00"){
                var pcd_approved_by_owner = '<span class="label label-warning">PENDING</span>';
            }
            else {
                var pcd_approved_by_owner = val.pcd_approved_by_owner;
            }

            if(val.pcd_rfi == '[]'){
                var t = $('#request_change_order').DataTable();
                t.row.add([
                   count, // val.pcd_parent_cor,
                   val.agency_name,
                   val.pco_date,
                   val.pcd_description,
                   pcd_approved_by_cm,
                   pcd_approved_by_owner,
                   val.currency_symbol + val.pcd_price,
                   val.pcd_days,
                   status,
                   '<a href="'+baseUrl+'dashboard/'+project_id+'/change_order_request_review/'+val.pcd_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" style="display: none;"  data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>'
                ]).draw( false );  
                count++;
            }
            else {
                // console.log(val.pcd_rfi);

                jQuery.ajax({
                url: baseUrl+val.pcd_id+"/get_item_rfi",
                    type: "GET",
                    headers: {
                      "Content-Type": "application/json",
                      "x-access-token": token
                    },
                    contentType: "application/json",
                    cache: false
                })
                .done(function(data, textStatus, jqXHR) {
                    
                    // console.log(data.data);

                    window.rfi_final = '';
                    jQuery.each(data.data, function( i, val ) {
                        rfi_final += "RFI "+val.ri_id+" : "+ val.ri_question_request+"<br/>"; 
                        // console.log(rfi_final);
                        
                    });

                    var t = $('#potential_change_order').DataTable();
                    t.row.add([
                       counts, // val.pcd_parent_cor,
                       // val.agency_name,
                       // val.pco_date,
                       // val.pcd_description,
                       rfi_final,
                       val.currency_symbol + val.pcd_price,
                       val.pcd_days,
                       // status,
                       '<a href="'+baseUrl+'dashboard/'+project_id+'/change_order_request_review/'+val.pcd_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" style="display: none;" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>'
                    ]).draw( false );  
                    counts++;
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.log("HTTP Request Failed");
                    var response = jqXHR.responseJSON.code;
                    console.log(response);
                }) 

                
            }
        });
        // $( "h2" ).appendTo( $( ".container" ) );
       
        $("#request_change_order").show();
        $(".loading_data").hide();
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        console.log(response);
        if(response == 403){
            window.location.href = baseUrl + "403";
        }
        else if(response == 404){
            // window.location.href = baseUrl + "404";
            $("#request_change_order").show();
            $(".loading_data").hide();
        }
        else {
            window.location.href = baseUrl + "500";
        }
    }); 

    setTimeout(function()
    {
        // Check Add Permission
        var check_permission = jQuery.inArray( "cor_add", check_user_access );
        console.log(check_permission);
        if(check_permission < 1){
            $('.hide_add_permission').remove();
        }
        else {
            $('.hide_add_permission').show();
        }

        // Check Add Permission
        var check_permission = jQuery.inArray( "cor_order_review_update", check_user_access );
        console.log(check_permission);
        if(check_permission < 1){
            $('.hide_update_permission').remove();
        }
        else {
            $('.hide_update_permission').show();
        }
    },3000)
    
       
});