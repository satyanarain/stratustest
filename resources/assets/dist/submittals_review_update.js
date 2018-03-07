  	$(document).ready(function() { 
     	// Get login user profile data
     	$(".hide_loading").hide();
     	$("#update_submittal_review_form").hide();
	   // Get login user profile data
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		submittal_id = url[ url.length - 2 ]; // projects
		project_id = url[ url.length - 4 ]; // projects
		console.log(submittal_id);

		// Check View All Permission
		var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
		var check_permission = jQuery.inArray("submittal_review_update", check_user_access );
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
		url: baseUrl + "submittal-review/"+submittal_id,
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
		    var status = data.data.sr_review_type;
		    if(status == 'no_exception'){
	    	status = 'no_exception';
		    }
		    else if(status == 'make_corrections_noted'){
	    	status = 'make_corrections_noted';
		    }
		    else if(status == 'revise_resubmit'){
	    	status = 'revise_resubmit';
		    }
		    else if(status == 'rejected'){
	    	status = 'rejected';
		    }
		    else if(status == 'pending'){
	    	status = 'pending';
		    }
		    else if(status == 'expedited_review_pending'){
	    	status = 'expedited_review_pending';
		    }
		    else if(status == 'expedited_review_overdue'){
	    	status = 'expedited_review_overdue';
		    }
		    $('#status').val(status);
                    $('#submittal_version_number').val(data.data.sub_number);
		    $('#review_detail').val(data.data.sr_review);

		    var document_link = data.data.submittal_path;
		    if(document_link == null){
		  	 	var	document_link_value = '-';
		  	}
		  	else {
		  		//var document_link_value = '<iframe src="http://apps.groupdocs.com/document-annotation2/embed/'+data.data.submittal_path+'" frameborder="0" width="100%" height="800"></iframe>';
                                var document_link_value = '<br><a href="'+data.data.submittal_path+'" target="_blank">View Document</a>';
		  	}

		  	$("#review_document").html(document_link_value);

		    $(".hide_loading").show();
		    $("#update_submittal_review_form").show();
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


    $('#update_submittal_review_form').submit(function(e) {
        e.preventDefault();
        $('.loading-submit').show();
        var status               	= $('#status').val();
        var review_detail           = $('#review_detail').val();
        var respond_date           	= $('#respond_date').val();
        var project_id              = $('#upload_project_id').val();
        var submittal_version_number = $('#submittal_version_number').val();
	    var token                   = localStorage.getItem('u_token');

     

        var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "submittal-review/"+submittal_id+"/update",
            type: "POST",
            data: {
                "submittal_id"				: submittal_id,
                "submittal_review_type"		: status,
                "submittal_review"  		: review_detail,
                "respond_date"  		: respond_date,
                "project_id"        		: project_id,
                "submittal_version_number"      : submittal_version_number,
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            console.log(data);
            $('.loading-submit').hide();
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')


            $("#alert_message").show();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Submittal review updated successfully!</div></div>';
            $("#alert_message").html(html);
            setTimeout(function()
            {
                $("#alert_message").hide();
            },6000)
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                // console.log(responseText.data.currency_name);
                $("#alert_message").show();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                if(responseText.data.submittal_review_type != null){
                    html += '<li>The status is required.</li>';
                }
                if(responseText.data.submittal_review != null){
                    html += '<li>The submittal review is required.</li>';
                }
                if(responseText.data.project_id != null){
                    html += '<li>The project id field is required.</li>';
                }
                html += '</ul></div>';
                $("#alert_message").html(html);
                $("#alert_message").hide();
        })
    });