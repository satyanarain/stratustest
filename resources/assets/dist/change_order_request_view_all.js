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
    var check_permission = jQuery.inArray( "cor_view_all", check_user_access );
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
        window.project_name = data.data[0].p_name;
        $('#project_name_title').text("Project: " + window.project_name);
        var specific_project_name = 'Change Order Request (COR) for Project: ' + window.project_name;
        console.log(specific_project_name);
        $('#request_change_order').DataTable().destroy();
        $('#request_change_order').DataTable({
            order: [['1', 'asc']],
            //ordering:false,
            dom: 'Bfrtip',
            buttons: [
                // {
                //     extend: 'copyHtml5',
                //     exportOptions: {
                //         columns: [ 0, 1, 2, 4, 6 ]
                //     },
                //     message: specific_project_name,
                // },
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2, 4, 6 ]
                    },
                    message: specific_project_name,
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2, 4, 6 ]
                    },
                    message: specific_project_name,
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2, 4, 6 ]
                    },
                    message: specific_project_name,
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [ 0, 1, 2, 4, 6 ]
                    },
                    message: specific_project_name,
                }
            ]
        });
        
        $("#request_change_order").hide();
        // Foreach Loop 
        var count = 1;
        var counts = 1;
        jQuery.each( data.data, function( i, val ) {

            // Check Update Permission
            var status_cm = '';
            var status_owner = '';
            if((val.pcd_approved_by_cm == null || val.pcd_approved_by_cm == "0000-00-00") && (val.pcd_denied_by_cm == null || val.pcd_denied_by_cm == "0000-00-00")){
                var pcd_approved_by_cm = '<span class="label label-warning">PENDING</span>';
                var pcd_denied_by_cm = '<span class="label label-warning">PENDING</span>';
                var status_cm = '<span class="label label-warning">PENDING CM REVIEW</span><br/>';
            }
            else if(val.pcd_approved_by_cm!="0000-00-00") {
                var pcd_approved_by_cm = val.pcd_approved_by_cm;
                var pcd_denied_by_cm = '';
            }
            else if(val.pcd_denied_by_cm!="0000-00-00") {
                var pcd_denied_by_cm = val.pcd_denied_by_cm;
                var pcd_approved_by_cm = '';
            }else{
                var pcd_denied_by_cm = '';
                var pcd_approved_by_cm = '';
            }
            if((val.pcd_approved_by_owner == null || val.pcd_approved_by_owner == "0000-00-00") && (val.pcd_denied_by_owner == null || val.pcd_denied_by_owner == "0000-00-00")){
                var pcd_approved_by_owner = '<span class="label label-warning">PENDING</span>';
                var pcd_denied_by_owner = '<span class="label label-warning">PENDING</span>';
                var status_owner = '<span class="label label-warning">PENDING OWNER REVIEW</span><br/>';
            }
            else if(val.pcd_approved_by_owner!="0000-00-00") {
                var pcd_approved_by_owner = val.pcd_approved_by_owner;
                var pcd_denied_by_owner = '';
            }else if(val.pcd_denied_by_owner!="0000-00-00"){
                var pcd_denied_by_owner = val.pcd_denied_by_owner;
                var pcd_approved_by_owner = '';
            }else{
                var pcd_denied_by_owner = '';
                var pcd_approved_by_owner = '';
            }
            var check_permission = jQuery.inArray("cor_order_review_update", check_user_access );
            console.log(check_permission+'tttt');
//            if(((val.pcd_approved_by_cm == null || val.pcd_approved_by_cm == "0000-00-00") && (val.pcd_denied_by_cm == null || val.pcd_denied_by_cm == "0000-00-00")) || ((val.pcd_approved_by_owner == null || val.pcd_approved_by_owner == "0000-00-00") && (val.pcd_denied_by_owner == null || val.pcd_denied_by_owner == "0000-00-00")))
//            {var editable_now = true;}else{var editable_now = false;}
            if(((val.pcd_approved_by_cm != null && val.pcd_approved_by_cm != "0000-00-00") || (val.pcd_denied_by_cm != null && val.pcd_denied_by_cm != "0000-00-00")) && ((val.pcd_approved_by_owner != null && val.pcd_approved_by_owner != "0000-00-00") || (val.pcd_denied_by_owner != null && val.pcd_denied_by_owner != "0000-00-00")))
            {var editable_now = false;}else{var editable_now = true;}
            if(check_permission < 1){
                var update_permission = '';
            }
            else if(editable_now==false) {
                var update_permission = '<a href="'+baseUrl+'dashboard/'+project_id+'/change_order_request_review/'+val.pcd_id+'/view" class="btn btn-success btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-search"></i></a>';
            }else{
                var update_permission = '<a href="'+baseUrl+'dashboard/'+project_id+'/change_order_request_review/'+val.pcd_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
            }

            var status = val.pco_status;
            if(status == 'active'){
            status = '<span class="label label-success">Active</span>';
            }
            else {
                status = '<span class="label label-danger">Inactive</span>';
            }

            if((val.pcd_approved_by_cm == null || val.pcd_approved_by_cm == "0000-00-00") && (val.pcd_denied_by_cm == null || val.pcd_denied_by_cm == "0000-00-00")){
                var pcd_approved_by_cm = '<span class="label label-warning">PENDING</span>';
            }else if(val.pcd_approved_by_cm!="0000-00-00"){
                var pcd_approved_by_cm = val.pcd_approved_by_cm;
                var pcd_denied_by_cm = '';
            }else if(val.pcd_denied_by_cm!="0000-00-00"){
                var pcd_denied_by_cm = val.pcd_denied_by_cm;
                var pcd_approved_by_cm = '';
            }else{
                var pcd_approved_by_cm = '';
                var pcd_denied_by_cm = '';
            }

            if((val.pcd_approved_by_owner == null || val.pcd_approved_by_owner == "0000-00-00") && (val.pcd_denied_by_owner == null || val.pcd_denied_by_owner == "0000-00-00")){
                var pcd_approved_by_owner = '<span class="label label-warning">PENDING</span>';
            }else if(val.pcd_approved_by_owner!="0000-00-00"){
                var pcd_approved_by_owner = val.pcd_approved_by_owner;
                var pcd_denied_by_owner = '';
            }else if(val.pcd_denied_by_owner!="0000-00-00"){
                var pcd_denied_by_owner = val.pcd_denied_by_owner;
                var pcd_approved_by_owner = '';
            }else{
                var pcd_approved_by_owner = '';
                var pcd_denied_by_owner = '';
            }
            
            if(val.pcd_price){var disp_price=val.pcd_price;}else{var disp_price=val.pcd_unit_number*val.pcd_unit_price;}
            if((val.pcd_approved_by_cm != null && val.pcd_approved_by_cm != "0000-00-00") && (val.pcd_approved_by_owner != null && val.pcd_approved_by_owner != "0000-00-00")){
                var approved_status = '<span class="label label-success">APPROVED</span>';
            }else if((val.pcd_denied_by_cm != null && val.pcd_denied_by_cm != "0000-00-00") && (val.pcd_denied_by_owner != null && val.pcd_denied_by_owner != "0000-00-00")){
                var approved_status = '<span class="label label-danger">DENIED</span>';
            }else{
                var approved_status = '';
            }
            var cm_rejection_comment = val.cm_rejection_comment;
            var owner_rejection_comment= val.owner_rejection_comment;
            if(parseInt(val.is_potential)==1)
            {
                var oneDay = 24*60*60*1000;
                var future_date = new Date(val.pcd_timestamp);
                var numberOfDaysToAdd = 5;
                var futuredate = future_date.setDate(future_date.getDate() + numberOfDaysToAdd); 
                var now_date = new Date();
                var numberOfDaysToAdd = 0;
                var nowdate = now_date.setDate(now_date.getDate() + numberOfDaysToAdd); 
                // console.log(future_date);
                // console.log(now_date);
                // console.log(futuredate);
                // console.log(nowdate);
                var diffDays = Math.round(Math.abs((future_date.getTime() - now_date.getTime())/(oneDay)));

                if(futuredate < nowdate){
                    // console.log('less');
                    var potential_status = '<span class="label label-danger">PAST DUE</span>';
                    var action_button = "";
                }
                else {
                    // console.log('greater');
                    seconds = Math.floor((futuredate - (nowdate))/1000);
                    minutes = Math.floor(seconds/60);
                    hours = Math.floor(minutes/60);
                    days = Math.floor(hours/24);
                    
                    hours1 = hours-(days*24);
                    minutes1 = minutes-(days*24*60)-(hours1*60);
                    seconds1 = seconds-(days*24*60*60)-(hours1*60*60)-(minutes1*60);

                    var potential_status = "<span class='label label-warning'>"+days +" Days " + hours1 + " Hours " + minutes1 + " Minutes Left to Respond</span>";
                    var action_button = update_permission ;
                }
                var t = $('#potential_change_order').DataTable();
                
                t.row.add([
                       //counts, // val.pcd_parent_cor,
                       val.pco_number,
                       val.agency_name,
                       val.pco_date,
                       val.pcd_description,
                       val.currency_symbol +' '+  ReplaceNumberWithCommas(val.pcd_price),
                       val.pcd_days,
                       status_cm + status_owner+potential_status,
                       action_button
                    ]).draw( false );  
                                                t.on( 'order.dt search.dt', function () {
                                t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                    cell.innerHTML = i+1;
                                } );
                            } ).draw();
                counts++;
            }else{
                if(val.pcd_rfi == '[]'){
                    rfi_final = '';
                    var t = $('#request_change_order').DataTable();

                    t.row.add([
                       //count, // val.pcd_parent_cor,
                       val.pco_number,
                       val.agency_name,
                       val.pco_date,
                       val.pcd_description,
                       rfi_final,
                       pcd_approved_by_cm,
                       pcd_approved_by_owner,
                       pcd_denied_by_cm,
                       cm_rejection_comment,
                       pcd_denied_by_owner,
                       owner_rejection_comment,
                       val.currency_symbol +' '+  ReplaceNumberWithCommas(disp_price),
                       val.pcd_days,
                       status_cm + status_owner+approved_status,
                       //status,
                       update_permission
                    ]).draw( false );
                                                t.on( 'order.dt search.dt', function () {
                                t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                    cell.innerHTML = i+1;
                                } );
                            } ).draw();
                    count++;
                }
                else {
                // console.log(val.pcd_rfi);

                var oneDay = 24*60*60*1000;
                var future_date = new Date(val.pcd_timestamp);
                var numberOfDaysToAdd = 5;
                var futuredate = future_date.setDate(future_date.getDate() + numberOfDaysToAdd); 
                var now_date = new Date();
                var numberOfDaysToAdd = 0;
                var nowdate = now_date.setDate(now_date.getDate() + numberOfDaysToAdd); 
                // console.log(future_date);
                // console.log(now_date);
                // console.log(futuredate);
                // console.log(nowdate);
                var diffDays = Math.round(Math.abs((future_date.getTime() - now_date.getTime())/(oneDay)));

                if(futuredate < nowdate && val.pcd_status!="complete"){
                    // console.log('less');
                    var potential_status = '<span class="label label-danger">PAST DUE</span>';
                    var action_button = update_permission;
                }else if(((val.pcd_approved_by_cm != null && val.pcd_approved_by_cm != "0000-00-00") || (val.pcd_denied_by_cm != null && val.pcd_denied_by_cm != "0000-00-00")) && ((val.pcd_approved_by_owner != null && val.pcd_approved_by_owner != "0000-00-00") || (val.pcd_denied_by_owner != null && val.pcd_denied_by_owner != "0000-00-00"))){
                    var potential_status = '';
                    var action_button = update_permission;
                }else {
                    // console.log('greater');
                    seconds = Math.floor((futuredate - (nowdate))/1000);
                    minutes = Math.floor(seconds/60);
                    hours = Math.floor(minutes/60);
                    days = Math.floor(hours/24);
                    
                    hours1 = hours-(days*24);
                    minutes1 = minutes-(days*24*60)-(hours1*60);
                    seconds1 = seconds-(days*24*60*60)-(hours1*60*60)-(minutes1*60);

                    var potential_status = "<span class='label label-warning'>"+days +" Days " + hours1 + " Hours " + minutes1 + " Minutes Left to Respond</span>";
                    var action_button = update_permission ;
                }

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
                    jQuery.each(data.data, function( i, val1 ) {
                        rfi_final += "RFI "+val1.ri_id+" : "+ val1.ri_question_request+"<br/>"; 
                        // console.log(rfi_final);
                    });
                    if(val.pcd_price)
                        var price = val.currency_symbol +' '+  ReplaceNumberWithCommas(val.pcd_price);
                    else
                        var price = '';
                    var t = $('#request_change_order').DataTable();
                    t.row.add([
                       //count, // val.pcd_parent_cor,
                       val.pco_number,
                       val.agency_name,
                       val.pco_date,
                       val.pcd_description,
                       rfi_final,
                       pcd_approved_by_cm,
                       pcd_approved_by_owner,
                        pcd_denied_by_cm,
                        cm_rejection_comment,
                        pcd_denied_by_owner,
                        owner_rejection_comment,
                       price,
                       val.pcd_days,
                       status_cm + status_owner+approved_status+potential_status,
                       action_button
                    ]).draw( false );  
//                                                t.on( 'order.dt search.dt', function () {
//                                t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
//                                    cell.innerHTML = i+1;
//                                } );
//                            } ).draw();
                    count++;
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.log("HTTP Request Failed");
                    var response = jqXHR.responseJSON.code;
                    console.log(response);
                }) 

                
            }
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
    },2000) 
    
       
});