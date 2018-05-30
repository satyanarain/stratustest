  	$(document).ready(function() {
     	// Get login user profile data
     	$("#update_request_form").hide();
	   // Get login user profile data
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		req_info_id = url[ url.length - 2 ]; // projects
		console.log(req_info_id);
		project_id = url[ url.length - 4 ];

		// Check View All Permission
        var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
        var check_permission = jQuery.inArray("rfi_update", check_user_access );
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
		url: baseUrl + "request-information/"+req_info_id,
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
		    var status = data.data.ri_request_status;
		    if(status == "active"){
		    	status = 'active';
		    }
		    else {
		    	status = "deactive";
		    }
		    $('#status').val(status);
                    $("#rir_review_status").val(data.data.rir_review_status);
                    if(data.data.rir_review_status=="additional_information_requested")
                    {
                        $(".additional_information_container").show();
                    }
                    $('#rfi_number').text(data.data.ri_number);
		    $('.additional_information').val(data.data.additional_information);

		    $("#update_request_form").show();
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
    });


    $('#update_request_form').click(function(e) {
      $('.loading-submit').show();
        e.preventDefault();
        var status                  = $('#status').val();
        var project_id              = $('#upload_project_id').val();
        var token                   = localStorage.getItem('u_token');
        var additional_information  = $(".additional_information").val();
        var file_path               = $('#upload_doc_id').val();
        var rfi_number              = parseInt($("#rfi_number").html());
        var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "request-information/"+req_info_id+"/update",
            type: "POST",
            data: {
         	    "request_status"        : status,
                    "project_id"            : project_id,
                    "additional_document"   : file_path,
                    "additional_information": additional_information,
                    "rfi_number"            : rfi_number,
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
            .done(function(data, textStatus, jqXHR) {
            console.log(data);
            // html = '<div class="alert alert-block alert-success fade in">Update successfully!</div>';
            // $("#alert_message").html(html);
            $("#alert_message").fadeIn(1000);
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Request for information updated successfully!</div></div>';
            $("#alert_message").html(html);
            $("#alert_message").fadeOut(3000);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                // console.log(responseText.data.currency_name);
                $("#alert_message").fadeIn(1000);
                $('.loading-submit').hide();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                if(responseText.data.project_id != null){
                    html += '<li>The project id field is required.</li>';
                }
                html += '</ul></div>';
                $("#alert_message").html(html);
                $("#alert_message").fadeOut(6000);
        })
    });
