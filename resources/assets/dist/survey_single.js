  	$(document).ready(function() { 
     	// Get login user profile data
     	// $("#update_submittal_review_form").hide();
	   // Get login user profile data
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		survey_id = url[ url.length - 1]; // projects
		project_id = url[ url.length - 3]; // projects
		console.log(survey_id);

		// Check View All Permission
	    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
	    var check_permission = jQuery.inArray("survey_view_all", check_user_access );
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
		url: baseUrl + "survey-review/"+survey_id,
		    type: "GET",
		    headers: {
		      "Content-Type": "application/json",
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
		    .done(function(data, textStatus, jqXHR) {
		    console.log(data.data);
		    var status = data.data.sr_review_status;
			if(status == 'pending'){
	    	status = '<span class="label label-warning">PENDING</span>';
		    }
		    else if(status == 'past_due'){
	    	status = '<span class="label label-danger">PAST DUE</span>';
		    }
		    else {
		    	status = '<span class="label label-success">COMPLETED</span>';
		    }
		    $('#status').html(status);

		    var file_path = data.data.survey_file_path;
		    if(file_path == null){
		  	 	var	file_path_value = '-';
		  	}
		  	else {
		  		var file_path_value = '<a href="'+baseUrl+data.data.survey_file_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
		  	}

		  	var review_file_path = data.data.review_file_path;
		    if(review_file_path == null){
		  	 	var	review_file_path_value = '-';
		  	}
		  	else {
		  		var review_file_path_value = '<a href="'+baseUrl+data.data.review_file_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
		  	}

		  	console.log(data.data.file_path);
		  	console.log(data.data.review_file_path);

			// var date = new Date(data.data.sur_date.replace(' ', 'T'));
			// var day = date.getDate();
			// var month = date.getMonth();
			// var year = date.getFullYear();
			var survey_date = data.data.sur_date; // year + '-' + month + '-' + day;

			// var date = new Date(data.data.sur_request_completion_date.replace(' ', 'T'));
			// var day = date.getDate();
			// var month = date.getMonth();
			// var year = date.getFullYear();
			var sur_request_completion_date = data.data.sur_request_completion_date; //year + '-' + month + '-' + day;

		    $('.survey_number').text(data.data.sur_number);
		    $('#survey_date').text(survey_date);
		    $('#survey_description').text(data.data.sur_description);
		    $('#survey_req_date').text(sur_request_completion_date);
		    $('#survey_documents').html(file_path_value);
		    $('#survey_user_detail').html(data.data.sur_user_firstname+' '+data.data.sur_user_lastname+'<br/>'+data.data.sur_user_name+' <br/>'+data.data.sur_user_email);
		    
		    var status = data.data.sr_review_status;
			if(status == 'pending'){
	    	status = '<span class="label label-warning">PENDING</span>';
		    }
		    else if(status == 'past_due'){
	    	status = '<span class="label label-danger">PAST DUE</span>';
		    }
		    else {
		    	status = '<span class="label label-success">COMPLETED</span>';
		    }

		    var review_file_path = data.data.review_file_path;
		    if(review_file_path == null){
		  	 	var	review_file_path_value = '-';
		  	}
		  	else {
		  		var review_file_path_value = '<a href="'+baseUrl+data.data.review_file_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
		  	}

		    // var date = new Date(data.data.sr_date.replace(' ', 'T'));
		    console.log(data.data.sr_date);
		    if(data.data.sr_date == null){
		    	var review_response_date = '';
		    }
		    else {
			 //    var date = new Date(data.data.sr_date);
				// var day = date.getDate();
				// var month = date.getMonth();
				// var year = date.getFullYear();
				var review_response_date = data.data.sr_date; // year + '-' + month + '-' + day;
		    }

		    $('#review_responsible').text(data.data.sr_responsible);
		    if(data.data.sr_request == null){
		    	var review_request_number = '-';
		    }
		    else {
		    	var review_request_number = data.data.sr_request;
		    }
		    $('#review_request_number').text(review_request_number);
		    $('#review_response_date').text(review_response_date);
		    $('#review_cut_sheet').html(review_file_path_value);
		    $('#review_status').html(status);

		    if(data.data.review_user_email == null){
		    	var review_user_detail = '-';
		    }
		    else {
		    	var review_user_detail = data.data.review_user_firstname+' '+data.data.review_user_lastname+'<br/>'+data.data.review_user_name+' <br/>'+data.data.review_user_email;
		    }
		    $('#review_user_detail').html(review_user_detail);

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