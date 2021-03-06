$(document).ready(function() {
    // Get login user profile data
    $('#upload_doc_id').removeAttr('value');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 4]; // projects
    item_id = url[ url.length - 2]; // projects
    console.log(item_id);

    var role = localStorage.getItem('u_role');
    var token  = localStorage.getItem('u_token');

    // Check View All Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray( "cor_order_review_update", check_user_access );
    console.log(check_permission);
    if(check_permission < 1){
        window.location.href = baseUrl + "403";
    }
    else {
        console.log('Yes Permission');
        $('.body-content .wrapper').show();
    }
    jQuery.ajax({
    url: baseUrl+"/change_order_request_item/"+item_id,
        type: "GET",
        headers: {
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        console.log(data.data);
        var status = data.data.pco_status;
        if(status == 'active'){
        status = '<span class="label label-success">Active</span>';
        }
        else {
            status = '<span class="label label-danger">Inactive</span>';
        }
        if(data.data.is_potential==1)
            $("#is_potential").val(1);
        else
            $("#is_potential").val(0);
        $('#cor_number').text(data.data.pcd_number)
        $('#cor_generated_by').text(data.data.agency_name)
        $('#owner_rejection_comment,#owner_rejection_comment1').val(data.data.owner_rejection_comment);
        $('#cm_rejection_comment,#cm_rejection_comment1').val(data.data.cm_rejection_comment);
        $('#cor_date_sent').text(data.data.pco_date)
        $('#cor_description').html('<input class="form-control" type="text" name="change_order_desc" id="change_order_desc" value="'+data.data.pcd_description+'">');
        if(data.data.pcd_denied_by_cm == "0000-00-00" && data.data.pcd_approved_by_cm == "0000-00-00"){
            $('#cor_approved_cm').html('<span class="label label-warning">PENDING</span>')
        }
        else if(data.data.pcd_approved_by_cm != "0000-00-00"){
            $('#cor_approved_cm').text(data.data.pcd_approved_by_cm)
        }

        if(data.data.pcd_denied_by_owner == "0000-00-00" && data.data.pcd_approved_by_owner == "0000-00-00"){
            $('#cor_approved_owner').html('<span class="label label-warning">PENDING</span>')
        }
        else if(data.data.pcd_approved_by_owner != "0000-00-00"){
            $('#cor_approved_owner').text(data.data.pcd_approved_by_owner)
        }
        
        if(data.data.pcd_approved_by_cm == "0000-00-00" && data.data.pcd_denied_by_cm == "0000-00-00"){
            $('#cor_denied_cm').html('<span class="label label-warning">PENDING</span>')
        }
        else if(data.data.pcd_denied_by_cm != "0000-00-00"){
            $('#cor_denied_cm').text(data.data.pcd_denied_by_cm)
        }

        if(data.data.pcd_approved_by_owner == "0000-00-00" && data.data.pcd_denied_by_owner == "0000-00-00"){
            $('#cor_denied_owner').html('<span class="label label-warning">PENDING</span>')
        }
        else if(data.data.pcd_denied_by_owner != "0000-00-00"){
            $('#cor_denied_owner').text(data.data.pcd_denied_by_owner)
        }
        
        $('#cm_rejection_comment').text(data.data.cm_rejection_comment)
        $('#owner_rejection_comment').text(data.data.owner_rejection_comment)
        var cor_amount = data.data.pcd_price;
        //
        if(data.data.pcd_price)
        {
            $("#total_requested_cost").html('<input type="radio" class="total_requested_cost" name="total_requested_cost" id="total_requested_cost_price" checked value="price">Enter Price&nbsp;\n\
            <input type="radio" class="total_requested_cost" id="total_requested_cost_unit" name="total_requested_cost" value="unit">Enter Unit');
            $("#cor_amount").html('<input class="form-control" type="text" name="cor_amount" id="pcd_price" value="'+cor_amount+'">');
            if(data.data.pcd_unit_number)
                $('#cor_unit_number').text(data.data.pcd_unit_number)
            if(data.data.pcd_unit_price)
                $('#cor_unit_price').text(Number(data.data.pcd_unit_price).toFixed('2'))
        }else{
            $("#total_requested_cost").html('<input type="radio" class="total_requested_cost" name="total_requested_cost" id="total_requested_cost_price" value="price">Enter Price&nbsp;\n\
            <input checked type="radio" class="total_requested_cost" id="total_requested_cost_unit" name="total_requested_cost" value="unit">Enter Unit');
            if(data.data.pcd_unit_number)
                $("#cor_unit_number").html('<input class="form-control" type="text" name="pcd_unit_number" id="pcd_unit_number" value="'+data.data.pcd_unit_number+'">');
            else
                $("#cor_unit_number").html('<input class="form-control" type="text" name="pcd_unit_number" id="pcd_unit_number">');
            if(data.data.pcd_unit_price)
                $("#cor_unit_price").html('<input class="form-control" type="text" name="pcd_unit_price" id="pcd_unit_price" value="'+Number(data.data.pcd_unit_price).toFixed('2')+'">');
            else
                $("#cor_unit_price").html('<input class="form-control" type="text" name="pcd_unit_price" id="pcd_unit_price">');
            $('#cor_amount').text(parseInt(data.data.pcd_unit_number)*parseInt(data.data.pcd_unit_price))
        }
        //$('#cor_unit_number').text(data.data.pcd_unit_number)
        //$('#cor_unit_price').text(data.data.pcd_unit_price)
        $('#cor_day').html('<input class="form-control" type="text" name="change_order_day" id="change_order_day" value="'+data.data.pcd_days+'">');
        var request_bid_path = data.data.pcd_file_path;
        var request_bid_path_value;
        if(request_bid_path == null){
            request_bid_path_value = '';
            request_bid_iframe_value = '';
        }
        else {
            if(data.data.doc_path)
            {
            //request_bid_path_value = '<a href="https://apps.groupdocs.com/document-viewer/embed/'+data.data.doc_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
            request_bid_path_value = '<a href="'+data.data.doc_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
            var request_bid_iframe_value = '<iframe src="'+data.data.doc_path+'" frameborder="0" width="100%" height="800"></iframe>';
            //var request_bid_iframe_value = '<iframe src="https://apps.groupdocs.com/document-viewer/Embed/'+data.data.file_path+'?quality=50&use_pdf=False&download=False&print=False&signature=5Xpc7qsFKjmJoHfRcXxUus8Tqn0" frameborder="0" width="100%" height="800"></iframe>';
            }else{
                request_bid_path_value = '';
                request_bid_iframe_value = '';
            }
        }
        $("#review_document").html(request_bid_iframe_value);

        $('#cor_file').html(request_bid_path_value);
        $('#change_order_status').val(data.data.change_order_status)

        if(data.data.pcd_approved_by_owner != "0000-00-00"){
            //$("#approved_owner").attr('enabled');
            $("#approved_owner").attr("checked", "checked");
            $("#approved_owner").attr('disabled', true);
            $("#denied_owner").attr('disabled', true);
        }
//        else {
//            $("#approved_owner").attr("checked", "checked");
//            // $("#approved_owner").attr('disabled', true);
//        }
        if(data.data.pcd_denied_by_owner != "0000-00-00"){
            //$("#approved_owner").attr('enabled');
            $("#denied_owner").attr("checked", "checked");
             $('.additonal_cost_div1').show();
             $("#approved_owner").attr('disabled', true);
            $("#denied_owner").attr('disabled', true);
        }
        
        if(data.data.pcd_approved_by_cm != "0000-00-00"){
            //$("#approved_cm").is(":radio");
            $("#approved_cm").attr("checked", "checked");
            $("#approved_cm").attr('disabled', true);
            $("#denied_cm").attr('disabled', true);
        }
//        else {
//            $("#approved_cm").attr("checked", "checked");
//            // $("#approved_cm").attr('disabled', true);
//        }
        if(data.data.pcd_denied_by_cm != "0000-00-00"){
            //$("#approved_cm").is(":radio");
            $("#denied_cm").attr("checked", "checked");
             $('.additonal_cost_div').show();
             $("#approved_cm").attr('disabled', true);
            $("#denied_cm").attr('disabled', true);
        }
        
//        if(data.data.pcd_rfi == '[]'){
//            $('.rfi_available').hide();
//        }
        $('.total_requested_cost').click(function(){
            var sel_val = $(this).val();
            if(sel_val == "unit")
            {
                var cor_unit_number = $('#cor_unit_number').text()
                var cor_unit_price = $('#cor_unit_price').text()
                $("#cor_unit_number").html('<input class="form-control" type="text" name="pcd_unit_number" id="pcd_unit_number" value="'+cor_unit_number+'">');
                $("#cor_unit_price").html('<input class="form-control" type="text" name="pcd_unit_price" id="pcd_unit_price" value="'+cor_unit_price+'">');
                $("#cor_amount").text($('#pcd_price').val())
            }else{
                var cor_amount = $('#cor_amount').text()
                $("#cor_amount").html('<input class="form-control" type="text" name="cor_amount" id="pcd_price" value="'+cor_amount+'">');
                if($('#pcd_unit_number').val())
                    $('#cor_unit_number').text($('#pcd_unit_number').val())
                if($('#pcd_unit_price').val())
                $('#cor_unit_price').text($('#pcd_unit_price').val()) 
            }
        })
        var pcd_id = data.data.pcd_id;

        jQuery.ajax({
        url: baseUrl +project_id+"/request-information",
            type: "GET",
            headers: {
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            //console.log(data.data);
            jQuery.each(data.data, function( i, val ) {
                if(val.ri_request_status == 'active'){
                    $('#cor_rfi_detail').append(
                            '<input type="checkbox" class="rfi_'+val.ri_id+'" value="'+val.ri_id+'" name="rfi_detail[]">'+
                            '<label>RFI # '+val.ri_number+' : '+val.ri_question_request+'</label><br/>'
                    )
                }
            });
            if(parseInt(pcd_id))
            {
                jQuery.ajax({
                url: baseUrl+pcd_id+"/get_item_rfi",
                    type: "GET",
                    headers: {
                      "Content-Type": "application/json",
                      "x-access-token": token
                    },
                    contentType: "application/json",
                    cache: false
                })
                .done(function(data, textStatus, jqXHR) {
                    window.rfi_final = '';
                    jQuery.each(data.data, function( i, val ) {
                        $(".rfi_"+val.ri_id).attr("checked", "checked");
                    });
                })
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            console.log(response);
        });
        
        $("#request_change_order").show();
        $(".loading_data").hide();
        
        jQuery.ajax({
        url: baseUrl +"/check-reviewer-permission/"+project_id+'/'+item_id+'/change_order'+'/cm',
            type: "GET",
            headers: {
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            $(".cm_review_section").show();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            $(".cm_review_section").hide();
        });
        
        jQuery.ajax({
        url: baseUrl +"/check-reviewer-permission/"+project_id+'/'+item_id+'/change_order'+'/owner',
            type: "GET",
            headers: {
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            $(".owner_review_section").show();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            $(".owner_review_section").hide();
        });
        
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
            $("#request_change_order").show();
            $(".loading_data").hide();
        }
        else {
            window.location.href = baseUrl + "500";
        }
    });
    
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
            $('.loading_data').hide();
            var f_name = data.data.f_name;
            $('#contractor_name').text(f_name);
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
            // alert('faizan');
            // window.location.href = baseUrl + "404";
            $(".loading_data").hide();
        }
        else {
            window.location.href = baseUrl + "500";
        }
    });
    
});

function getFormattedPartTime(partTime){
        if (partTime<10)
           return "0"+partTime;
        return partTime;
    }



 $('#submit_cor_review_form').click(function(e) {
   $('.loading-submit').show();
        e.preventDefault();
        var total_requested_cost = $('input[name=total_requested_cost]:checked').val();
        if(total_requested_cost=="price")
        {
            var pcd_unit_number = '';
            var pcd_unit_price = '';
            var pcd_price = $('#pcd_price').val()
        }else if(total_requested_cost=="unit")
        {
            var pcd_unit_number = $('#pcd_unit_number').val()
            var pcd_unit_price = $('#pcd_unit_price').val()
            var pcd_price = '';
        }else{
            var pcd_unit_number = '';
            var pcd_unit_price = '';
            var pcd_price = '';
        }
        if($('#approved_cm').is(":checked")) {
            var date = new Date();
            var approved_by_cm = date.getFullYear() + "-" + getFormattedPartTime(date.getMonth()+1) + "-" + getFormattedPartTime(date.getDate());
            // alert(approved_by_cm);
            var denied_by_cm = null;
        }else if($('#denied_cm').is(":checked")) {
            var date = new Date();
            var denied_by_cm = date.getFullYear() + "-" + getFormattedPartTime(date.getMonth()+1) + "-" + getFormattedPartTime(date.getDate());
            // alert(approved_by_cm);
            var approved_by_cm = null;
        }else {
           var approved_by_cm = null;
           var denied_by_cm = null;
        }

        if($('#approved_owner').is(":checked")) {
            var date = new Date();
            var approved_by_owner = date.getFullYear() + "-" + getFormattedPartTime(date.getMonth()+1) + "-" + getFormattedPartTime(date.getDate());
            // alert(approved_by_owner);
            var denied_by_owner = null;
        }else if($('#denied_owner').is(":checked")) {
            var date = new Date();
            var denied_by_owner = date.getFullYear() + "-" + getFormattedPartTime(date.getMonth()+1) + "-" + getFormattedPartTime(date.getDate());
            // alert(approved_by_owner);
            var approved_by_owner = null;
        }else {
           var approved_by_owner = null;
           var denied_by_owner = null;
        }
        if(($('#approved_cm').is(":checked") || $('#denied_cm').is(":checked")) && ($('#approved_owner').is(":checked") || $('#denied_owner').is(":checked")))
        {
            var pcd_status = 'complete';
            var remove_potential = 1;
        }else{
            var pcd_status = 'pending';
            var remove_potential = 0;
        }
        var item_id = $('#item_id').val();
        var project_id = $('#project_id').val();
        var cor_description = $('#change_order_desc').val();
        //alert(cor_description);return false;
        var change_order_day = $('#change_order_day').val();
        var cm_rejection_comment = $("#cm_rejection_comment1").val();
        var owner_rejection_comment = $("#owner_rejection_comment1").val();
        //alert(cor_day);return false;
        console.log(item_id);
        console.log(approved_by_cm);
        console.log(approved_by_owner);
        
    
        var rfi_details = [];
        var rfi_detail = [];
        $('input[name^=rfi_detail]').each(function(){
            if($(this).context.checked == true) {
                rfi_detail.push($(this).val());
            }
        });
        rfi_details.push({
            "rfi_detail_item"     :  JSON.stringify(rfi_detail)
        });
        var is_potential = $("#is_potential").val();
        var change_order_status = $("#change_order_status").val();
        jQuery.ajax({
            url: baseUrl + "change_order_request_item/"+item_id+"/update",
            type: "POST",
            data: {
                "approved_by_cm"        : approved_by_cm,
                "approved_by_owner"     : approved_by_owner,
                "project_id"            : project_id,
                "change_order_day"      : change_order_day,
                "cor_description"       : cor_description,
                "pcd_unit_number"       : pcd_unit_number,
                "pcd_unit_price"        : pcd_unit_price,
                "pcd_price"             : pcd_price,
                "pco_number"            : $('#cor_number').text(),
                "remove_potential"      : remove_potential,
                "denied_by_cm"          : denied_by_cm,
                "denied_by_owner"       : denied_by_owner,
                "owner_rejection_comment":owner_rejection_comment,
                "cm_rejection_comment"  : cm_rejection_comment,
                "pcd_status"            : pcd_status,
                "order_rfi"             : rfi_details[0]['rfi_detail_item'],
                "is_potential"          : is_potential,
                "change_order_status"   : change_order_status,
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
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Change order request updated successfully!</div></div>';
            $("#alert_message").html(html);
                setTimeout(function()
                {
                    $("#alert_message").hide();
                },6000)
            // window.location.href = baseUrl + "dashboard/"+project_id+"/standards";
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(responseText.data.currency_name);
        //         $("#alert_message").show();
        //         html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Updated successfully!</div></div>';

        //         if(responseText.data.currency_name != null){
        //             html += '<li>'+responseText.data.currency_name+'</li>';
        //         }
        //         if(responseText.data.currency_symbol != null){
        //             html += '<li>'+responseText.data.currency_symbol+'</li>';
        //         }
        //         if(responseText.data.currency_status != null){
        //             html += '<li>'+responseText.data.currency_status+'</li>';
        //         }
        //         html += '</ul></div>';
        //         $("#alert_message").html(html);
        //         setTimeout(function()
        //         {
        //             $("#alert_message").hide();
        //         },4000)

        })
    });
