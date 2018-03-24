  	$(document).ready(function() { 
     	// Get login user profile data
     	// $("#update_submittal_review_form").hide();
	   // Get login user profile data
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		report_id = url[ url.length - 1]; // report_id
		project_id = url[ url.length - 3]; // report_id
		console.log(report_id);

		 // Check Permission
	    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
	    var check_permission = jQuery.inArray("daily_construction_report_view_all", check_user_access );
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
		url: baseUrl + "daily-report/"+report_id,
		    type: "GET",
		    headers: {
		      "Content-Type": "application/json",
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
		    .done(function(data, textStatus, jqXHR) {
		    console.log(data.data.sr_review_status);
		    var status = data.data.sr_review_status;
			if(status == 'incomplete'){
	    	status = '<span class="label label-warning">INCOMPLETE</span>';
		    }
		    else {
		    	status = '<span class="label label-success">COMPLETED</span>';
		    }
		    $('#status').html(status);

			var report_date = data.data.pdr_date;//var report_date = $.datepicker.formatDate('yy-mm-dd', new Date(data.data.pdr_date.replace(' ', 'T')));
		    $('#report_date').text(report_date);
		    // $('#report_weather_detail').html(data.data.pdr_weather + "<sup>o</sup> c");
		    $('#report_weather_detail').html(data.data.pdr_weather);
		    $('#report_custom_detail').text(data.data.pdr_custom_field);

		    var perform_work_day = data.data.pdr_perform_work_day;
		    if(perform_work_day == 'no'){
	    	perform_work_day = '<span class="label label-danger">No</span>';
		    }
		    else if (perform_work_day == 'yes'){
		    	perform_work_day = '<span class="label label-success">Yes</span>';
		    }
		    else {
		    }
		    $('#report_work_day').html(perform_work_day);
		    
		    var material_delivery = data.data.pdr_material_delivery;
		    if(material_delivery == 'no'){
	    	material_delivery = '<span class="label label-danger">No</span>';
		    }
		    else if (material_delivery == 'yes'){
		    	material_delivery = '<span class="label label-success">Yes</span>';
		    }
		    else {
		    }
		    $('#report_material_delivered').html(material_delivery);

		    var milestone_completed = data.data.pdr_milestone_completed;
		    if(milestone_completed == 'no'){
	    	milestone_completed = '<span class="label label-danger">No</span>';
		    }
		    else if (milestone_completed == 'yes'){
		    	milestone_completed = '<span class="label label-success">Yes</span>';
		    }
		    else {
		    }
		    $('#milestone').html(milestone_completed); 
		    $('#milestone_detail').text(data.data.pdr_milestone_detail);

		    var occur_detail = data.data.pdr_occur_detail;
	          occur_detail = occur_detail.replace('[', '');
	          occur_detail = occur_detail.replace(']', '');
	          occur_detail = occur_detail.replace(/_/g, ' ');
	          occur_detail = occur_detail.replace(/"/g, '');
	          occur_detail = occur_detail.replace(/,/g, ', ');
	          console.log(occur_detail);
	          occur_detail = '<p>'+occur_detail+'</p>';
		    $('#report_occur_type').html(occur_detail);
		    $('#report_general_note').text(data.data.pdr_general_notes);

		    var sub_contractor_work = data.data.pdr_sub_contractor_work;
		    if(sub_contractor_work == 'no'){
                        sub_contractor_work = '<span class="label label-danger">No</span>';
		    }
		    else if (sub_contractor_work == 'yes'){
		    	sub_contractor_work = '<span class="label label-success">Yes</span>';
		    }
		    else {
		    }
		    $('#subcontractor_work_day').html(sub_contractor_work);
		    $('#subcontractor_work_detail').text(data.data.pdr_sub_contractor_work_detail);
                    $('#subcontractor_work_detail_comment').text(data.data.pdr_sub_contractor_work_detail_comment);
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



		jQuery.ajax({
		url: baseUrl+report_id+"/daily-quantity-complete",
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
			jQuery.each(data.data, function( i, val ) {
				$("#contract_item_qty").append(
					'<tr><td>'+val.pbi_id+'</td>'+
					'<td>'+val.pbi_item_description+'</td>'+
					'<td>'+val.pdq_qty_complete_this_day+'</td>'+
	                '<td>'+val.pdq_location_additional_information+'</td></tr>'
				);
			});

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
		url: baseUrl+report_id+"/daily-resource-used",
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
			jQuery.each(data.data, function( i, val ) {
				$("#contract_item_resource").append(
					'<tr><td>'+val.pbi_item_description+'</td>'+
					'<td>'+val.pdu_resourse_detail+'</td>'+
					'<td>'+val.pdu_time+'</td>'+
	                '<td>'+val.pdu_time+'</td></tr>'
				);
			});

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
		url: baseUrl+report_id+"/daily-material-delivered",
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
			jQuery.each(data.data, function( i, val ) {
				 var file_path = val.doc_path;
			    if(file_path == null){
			  	 	var	file_path_value = '-';
			  	}
			  	else {
			  		var file_path_value = '<a href="'+baseUrl+val.doc_path+'"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  	}

				$("#material_type").append(
					'<tr><td>'+val.pdm_item_id+'</td>'+
					'<td>'+val.pdm_unit+'</td>'+
					'<td>'+val.pdm_unit_type+'</td>'+
	                '<td>'+file_path_value+'</td></tr>'
				);
			});

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
		url: baseUrl+report_id+"/daily-video-photo",
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
			jQuery.each(data.data, function( i, val ) {
				 var file_path = val.doc_path;
			    if(file_path == null){
			  	 	var	file_path_value = '-';
			  	}
			  	else {
			  		var file_path_value = '<a href="'+baseUrl+val.doc_path+'"><img src="'+baseUrl+val.doc_path+'" width="40"/></a>';
			  	}

				$("#daily_photo_video").append(
					'<tr><td colspan="2">'+val.pdp_description+'</td>'+
	                '<td colspan="2">'+file_path_value+'</td></tr>'
				);
			});

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