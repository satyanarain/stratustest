  	$(document).ready(function() { 
     	// Get login user profile data
     	// $("#update_submittal_review_form").hide();
	   // Get login user profile data
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		submittal_id = url[ url.length - 1]; // projects
		project_id = url[ url.length - 3]; // projects
		console.log(project_id);

		// Check View All Permission
		var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
		var check_permission = jQuery.inArray("submittal_view_all", check_user_access );
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
		url: baseUrl + "submittal/"+submittal_id,
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
	    	status = '<span class="label label-success">No Exception</span>';
		    }
		    else if(status == 'make_corrections_noted'){
	    	status = '<span class="label label-success">Make Corrections</span>';
		    }
		    else if(status == 'revise_resubmit'){
	    	status = '<span class="label label-success">Revise & Resubmit</span>';
		    }
		    else if(status == 'rejected'){
	    	status = '<span class="label label-success">Rejected</span>';
		    }
		    else if(status == 'pending'){
	    	status = '<span class="label label-warning">Pending</span>';
		    }
		    else if(status == 'expedited_review_pending'){
	    	status = '<span class="label label-danger">Expedited Review - Pending</span>';
		    }
		    else if(status == 'expedited_review_overdue'){
	    	status = '<span class="label label-danger">Expedited Review - Overdue</span>';
		    }
		    else {
		    	status = '<span class="label label-warning">Pending</span>';
		    }
		    $('#review_status').html(status);
		    if(data.data.sr_review == null){
		    	$('#review').text('');
		    }
		    else {
		    	$('#review').text(data.data.sr_review);	
		    }

		    if(data.data.sr_respond_date == null){
		    	$('#review_date').text('-');
		    }
		    else {
				// var date = new Date(data.data.sr_respond_date.replace(' ', 'T'));
				// var day = date.getDate();
				// var month = date.getMonth();
				// var year = date.getFullYear();

				// var date_format = year + '-' + month + '-' + day;
		    	$('#review_date').text(data.data.sr_respond_date);
		    }

		    if(data.data.review_email == null){
		    	$('#review_user').text('-');
		    }
		    else {
		    	$('#review_user').html('Name: '+data.data.review_firstname+' '+data.data.review_lastname+'<br/>'+'Email: '+data.data.review_email+'<br/>'+'Position / Title: '+data.data.review_positiontitle);
		    }

		    // console.log(data.data.sub_type);
		    if(data.data.sub_type == "new"){
		    	console.log('new');
		    	$('#submittal_number').text(data.data.sub_number);
		    }
		    else {
		    	console.log('exist');
		    	$('#submittal_number').text(data.data.sub_exist_parent+' R '+ data.data.sub_rev_number);
		    }
		   	
		    var document_link = data.data.submittal_path;
		    if(document_link == null){
			  	 	var	document_link_value = '-';
			  	}
			  	else {
			  		var document_link_value = '<a href="https://apps.groupdocs.com/document-viewer/embed/'+data.data.submittal_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  	}

			// var date = new Date(data.data.sub_date.replace(' ', 'T'));
			// var day = date.getDate();
			// var month = date.getMonth();
			// var year = date.getFullYear();
			// var date_format = year + '-' + month + '-' + day;
	    	$('#submittal_date').text(data.data.sub_date);

		    $('#submittal_description').text(data.data.sub_description);
		    $('#submittal_specification').text(data.data.sub_specification);
		    $('#submittal_comments').html(data.data.sub_additional_comments);
		    $('#submittal_expedited').html(data.data.sub_request_expedited_review);
		    $('#submittal_documents').html(document_link_value);
		    $('#submittal_user_detail').html(data.data.user_firstname+' '+data.data.user_lastname+'<br/>'+data.data.user_email+'<br/>'+data.data.user_role);

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