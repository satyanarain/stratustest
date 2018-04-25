$(document).ready(function() {
    // Get login user profile data
    $("#update_notice_form").hide();
    // Get login user profile data
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    notice_id = url[ url.length - 2 ]; // projects
    project_id = url[ url.length - 4 ]; // projects
    console.log(notice_id);

    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("notice_proceed_update", check_user_access );
    console.log(check_permission);
    if(check_permission < 1){
        window.location.href = baseUrl + "403";
    }
    else {
        console.log('Yes Permission');
        $('.body-content .wrapper').show();
    }

    jQuery.ajax({
        url: baseUrl + "notice-proceed/"+notice_id,
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
            var doc_path = data.data.notice_proceed_path;
            var doc_path_value;
            if(doc_path == null){
                doc_path_value = ' - ';
            }
            else {
                doc_path_value = '<a href="'+baseUrl+doc_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
            }
            $('#doc_file_path').html(doc_path_value);
            
            var doc_path = data.data.owner_sign_doc_path;
            var doc_path_value;
            if(doc_path == null){
                doc_path_value = ' - ';
                $(".before_review_owner").show();
            }
            else {
                $(".after_review_owner").show();
                doc_path_value = '<a href="'+baseUrl+doc_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
            }
            $('#document_owner').html(doc_path_value);
            $('#review_owner_detail').text(data.data.pnp_notice_review_owner);
            $('#notice_date').val(data.data.pnp_date);
            $('#notice_start_date').val(data.data.pnp_start_date);
            $('#liquidated_amount').val(data.data.pnp_liquidated_amount);
            $('#duration_days').val(data.data.pnp_duration);
            if(data.data.pnp_cal_day=="calendar_day")
            {
                $('#days_calendar').attr('checked', true);
            }else{
                $('#days_working').attr('checked', true);
            }
            var doc_path = data.data.contractor_sign_doc_path;
            var doc_path_value;
            if(doc_path == null){
                doc_path_value = ' - ';
                $(".before_review_contractor").show();
            }
            else {
                $(".after_review_contractor").show();
                doc_path_value = '<a href="'+baseUrl+doc_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
            }
            $('#document_contractor').html(doc_path_value);
            $('#review_contractor_detail').text(data.data.pnp_notice_review_contractor);
            
            var status = data.data.pnp_status;
            if(status == "active"){
                status = 'active';
            }
            else {
                status = "deactive";
            }
            $('#status').val(status);
            $("#update_notice_form").show();
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
                //	window.location.href = baseUrl + "404";
            }
            else {
                // console.log("500");
                window.location.href = baseUrl + "500";
            }
        })

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
});


$('#update_notice_form').submit(function(e) {
    e.preventDefault();
    $('.loading-submit').show();
    var notice_sign_owner           = $('#upload_doc_id_2').val();
    var notice_review_owner         = $('#review_owner').val();
    var notice_sign_contractor      = $('#upload_doc_id_3').val();
    var notice_review_contractor    = $('#review_contractor').val();
    var status               	= $('#status').val();
    var project_id              = $('#upload_project_id').val();
    var token                   = localStorage.getItem('u_token');

    var date = $("#notice_date").val();
    var start_date = $("#notice_start_date").val();
    var liquidated_amount = $("#liquidated_amount").val();
    var cal_day = $("input[name='days_working']:checked").val();
    var duration = $("#duration_days").val();
    var token = localStorage.getItem('u_token');
    jQuery.ajax({
        url: baseUrl + "notice-proceed/"+notice_id+"/update",
        type: "POST",
        data: {
            "notice_sign_owner"        : notice_sign_owner,
            "notice_review_owner"      : notice_review_owner,
            "notice_sign_contractor"   : notice_sign_contractor,
            "notice_review_contractor" : notice_review_contractor,
            "status"                   : status,
            "project_id"               : project_id,
            "date"                      : date,
            "start_date"            : start_date,
            "duration"              : duration,
            "cal_day"               : cal_day,
            "liquidated_amount"     : liquidated_amount,
        },
        headers: {
            "x-access-token": token
        },
        contentType: "application/x-www-form-urlencoded",
        cache: false
    })
        .done(function(data, textStatus, jqXHR) {
             $('.loading-submit').hide();
            console.log(data);
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')

            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Notice proceed updated successfully!</div></div>';
            $("#alert_message").html(html);
            setTimeout(function(){
                $("#alert_message").hide();
            },5000)
            //window.location.reload();

        })
        .fail(function(jqXHR, textStatus, errorThrown) {
             $('.loading-submit').hide();
            console.log("HTTP Request Failed");
            var responseText, html;
            responseText = JSON.parse(jqXHR.responseText);
            // console.log(responseText.data.currency_name);
            html = '<div class="alert alert-block alert-danger fade in"><ul>';
            if(responseText.data.project_id != null){
                html += '<li>The project id field is required.</li>';
            }
            html += '</ul></div>';
            $("#alert_message").html(html);
        })
});
