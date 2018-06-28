$(document).ready(function() {
    // Get login user profile data
    $("#update_swppp_form").hide();
    $("#company_name").hide();
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');

    // Get login user profile data
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    payapp_id = url[ url.length - 2 ]; // projects
    project_id = url[ url.length - 4 ]; // projects
    console.log(payapp_id);

    
    setTimeout(function()
    {   
        
    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("payment_application_update", check_user_access );
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
        url: baseUrl + "payment-application-detail-report/"+payapp_id,
        type: "GET",
        headers: {
            "Content-Type": "application/json",
            "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
        .done(function(data, textStatus, jqXHR) {
      
            if(data.data[0].ppa_invoice_date!='' && data.data[0].ppa_invoice_date!='0000-00-00')
                $('#ppa_invoice_date').val(data.data[0].ppa_invoice_date);
            if(data.data[0].ppa_invoice_no)
                $('#ppa_invoice_no').val(data.data[0].ppa_invoice_no);
            if(data.data[0].ppa_invoice_doc_id)
                $('#upload_single_doc_id').val(data.data[0].ppa_invoice_doc_id);
            if(data.data[0].ppa_month_name)
                $('#month_name').html(data.data[0].ppa_month_name);
            if(data.data[0].paid)
                $('#paid').val(data.data[0].paid);
            
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


$('#update_payment_application').click(function(e) {
  $('.loading-submit').show();
    e.preventDefault();
    var project_id              = $('#project_id').val();
    var token                   = localStorage.getItem('u_token');
    var token                   = localStorage.getItem('u_token');
    var ppa_invoice_date        = $("#ppa_invoice_date").val();
    var ppa_invoice_no          = $("#ppa_invoice_no").val();
    var ppa_invoice_doc_id      = $("#upload_single_doc_id").val();
    var paid                    = $("#paid").val();
    jQuery.ajax({
        url: baseUrl + "payment-application-report-name/"+payapp_id+"/update",
        type: "POST",
        data: {
            "project_id"        : project_id,
            "ppa_invoice_date"  : ppa_invoice_date,
            "ppa_invoice_no"    : ppa_invoice_no,
            "ppa_invoice_doc_id" : ppa_invoice_doc_id,
            "paid"              : paid,
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
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Payment application updated successfully.</div></div>';
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
            if(responseText.data.noc_project_id != null){
                html += '<li>The project id field is required.</li>';
            }
            html += '</ul></div>';
            $("#alert_message").html(html);
            $("#alert_message").hide(6000);
        })
});
