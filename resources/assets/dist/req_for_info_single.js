  	$(document).ready(function() { 
     	// Get login user profile data
     	// $("#update_submittal_review_form").hide();
	   // Get login user profile data
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		req_for_info_id = url[ url.length - 1]; // projects
		project_id = url[ url.length - 3]; // projects
		console.log(project_id);

		// Check View All Permission
	    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
	    var check_permission = jQuery.inArray("rfi_view_all", check_user_access );
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
	    
	    setTimeout(function()
        {
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
		            console.log(data.data.cur_symbol);
		            window.symbol = data.data.cur_symbol;
		            // $('.project_currency').text(data.data.cur_symbol+' ');
		        })
		    })
	    },1000)
	    setTimeout(function()
        {
			jQuery.ajax({
			url: baseUrl + "request-information/"+req_for_info_id,
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
			    var file_path = data.data.ri_file_path;
			    if(file_path == null){
			  	 	var	file_path_value = '-';
			  	}
			  	else {
			  		var file_path_value = '<a href="'+baseUrl+data.data.file_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		// var file_path_value = '<iframe src="https://apps.groupdocs.com/document-viewer/Embed/'+data.data.file_path+'?quality=50&use_pdf=False&download=False&print=False&signature=5Xpc7qsFKjmJoHfRcXxUus8Tqn0" frameborder="0" width="100%" height="800"></iframe>';
			  	}

				var date = new Date(data.data.ri_date);
				var day = date.getDate();
				var month = date.getMonth();
				var year = date.getFullYear();
				var rfi_date = year + '-' + parseInt(month+1) + '-' + day;
		    	$('#rfi_date').text(rfi_date);

		    	if(data.data.ri_additional_cost == null){
		    		var ri_additional_cost = ' - ';
		    	}
		    	else {
		    		var ri_additional_cost = data.data.ri_additional_cost;
		    	}
		    	if(data.data.ri_additional_cost_amount == null || ri_additional_cost=="no"){
		    		var ri_additional_cost_amount = '';
		    	}
		    	else {
		    		var ri_additional_cost_amount = window.symbol+' '+ReplaceNumberWithCommas(data.data.ri_additional_cost_amount);
		    	}
		    	if(data.data.ri_additional_day == null){
		    		var ri_additional_day = ' - ';
		    	}
		    	else {
		    		var ri_additional_day = data.data.ri_additional_day;
		    	}
		    	if(data.data.ri_additional_day_add == null || ri_additional_day=="no"){
		    		var ri_additional_day_add = '';
		    	}
		    	else {
		    		var ri_additional_day_add = data.data.ri_additional_day_add;
		    	}
			    $('.rfi_number').text(data.data.ri_number);
			    $('#rfi_request').text(data.data.ri_question_request);
			    $('#rfi_suggestion').text(data.data.ri_question_proposed);
			    $('#rfi_additional_cost').html('<span class="label label-default">'+ri_additional_cost+'</span>'+' '+ri_additional_cost_amount);
			    $('#rfi_additional_days').html('<span class="label label-default">'+ri_additional_day+'</span>'+' '+ri_additional_day_add);
			    $('#rfi_documents').html(file_path_value);
			    $('#rfi_user_detail').html(data.data.rfi_user_firstname+' '+data.data.rfi_user_lastname+'<br/>'+data.data.rfi_user_email+'<br/>'+data.data.rfi_user_role);


			    // Review 
		    	var status = data.data.rir_review_status;
				if(status == 'response_due'){
		    	status = '<span class="label label-warning">RESPONSE DUE</span>';
			    }
			    else if(status == 'past_due'){
		    	status = '<span class="label label-danger">PAST DUE</span>';
			    }
			    else if(status == 'change_order_pending'){
		    	status = '<span class="label label-default">CHANGE ORDER PENDING</span>';
			    }
			    else if(status == 'additional_contract_time_required'){
		    	status = '<span class="label label-default">ADDTIONAL CONTRACT TIME REQUIRED</span>';
			    }
                            else if(status == 'additional_information_requested'){
		    	status = '<span class="label label-default">REVIEWED (ADDTIONAL INFORMATIONMATION REQUESTED)</span>';
			    }
			    else {
			    	status = '<span class="label label-info">REVIEWED</span>';
			    }
			    $('#review_status').html(status)

			    if(data.data.rir_review_respond == null){
			    	$('#review_responded_date').text(' - ');
			    }
			    else {
				    var date = new Date(data.data.rir_review_respond);
					var day = date.getDate();
					var month = date.getMonth();
					var year = date.getFullYear();
					var review_date = year + '-' + (month+1) + '-' + day;
			    	$('#review_responded_date').text(data.data.rir_review_respond);
			    }

		    	if(data.data.rir_additional_info == null){
			    	$('#review_additional_info').text(' - ');
		    	}
		    	else {
			    	$('#review_additional_info').text(data.data.rir_additional_info);
		    	}

			    if(data.data.rir_additional_cost == null){
		    		var rir_additional_cost = '  ';
		    	}
		    	else {
		    		var rir_additional_cost = data.data.rir_additional_cost;
		    	}
		    	if(data.data.rir_additional_cost_amount == null || rir_additional_cost=="no"){
		    		var rir_additional_cost_amount = '  ';
		    	}
		    	else {
		    		var rir_additional_cost_amount = window.symbol+' '+ReplaceNumberWithCommas(data.data.rir_additional_cost_amount);
		    	}
		    	if(data.data.rir_additional_day == null){
		    		var rir_additional_day = '  ';
		    	}
		    	else {
		    		var rir_additional_day = data.data.rir_additional_day;
		    	}
		    	if(data.data.rir_additional_day_add == null || rir_additional_day=="no"){
		    		var rir_additional_day_add = '  ';
		    	}
		    	else {
		    		var rir_additional_day_add = data.data.rir_additional_day_add;
		    	}

			    $('#review_additional_cost').html('<span class="label label-default">'+rir_additional_cost+'</span>'+' '+rir_additional_cost_amount);
			    $('#review_additional_days').html('<span class="label label-default">'+rir_additional_day+'</span>'+' '+rir_additional_day_add);
			    if(data.data.review_user_name == null){
			    	$('#review_user_detail').html(' - ');
			    }
			    else {
			    	$('#review_user_detail').html(data.data.review_user_firstname+' '+data.data.review_user_lastname+'<br/>'+data.data.review_user_email+'<br/>'+data.data.review_user_role);
			    }
			    // $("#update_submittal_review_form").show();
			    $(".loading_data").hide();
                            
                            jQuery.ajax({
                            url: baseUrl +"/check-reviewer-permission/"+project_id+'/'+req_for_info_id+'/rfi'+'/cm',
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
		},3000)
    });