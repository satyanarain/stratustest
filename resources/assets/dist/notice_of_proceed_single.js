  	$(document).ready(function() { 
     	// Get login user profile data
     	$("#update_bond_form").hide();
	   // Get login user profile data
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		bd_id = url[ url.length - 1 ]; // projects
		console.log(bd_id);

		project_id = url[ url.length - 3 ]; // projects
	    console.log(project_id);

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
		url: baseUrl + "notice-of-proceed/"+bd_id,
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
		    var token = localStorage.getItem('u_token');
			$('#contractor_name').text(data.data.contractor_name);
		    $('#date_of_notice').text(data.data.pnp_date);
		    $('#start_date').text(data.data.pnp_start_date);
		    $('#duration_days').text(data.data.pnp_duration);
		    $('#days_working').text(data.data.pnp_cal_day);
		    $('#liquidated_amount').text(data.data.pnp_liquidated_amount);

		    // Docusign Parameter passing by docusign API
		    var doc_name = data.data.notice_proceed_path;
		    var doc_real_path = data.data.notice_proceed_path;
		    doc_name = doc_name.replace('uploads/notice_proceed/', '');
		    console.log(doc_name);
		    var doc_path = 'uploads/notice_proceed/';
		    var envelop_id = data.data.pnp_envelope_id;
		    // var envelop_id = 'c31c349b-749d-4295-a21b-467db9778ae6'; // Waiting
		    // var envelop_id = '430455a3-f16e-40ab-9574-a88450c521da'; // Completed
		    // setTimeout(function(){
			    jQuery.ajax({
				url: baseUrl + "get_envelop_status/"+envelop_id,
				    type: "GET",
				    headers: {
				      "Content-Type": "application/json",
				      "x-access-token": token
				    },
				    contentType: "application/json",
				    cache: false
				})
				.done(function(data, textStatus, jqXHR) {
					// console.log(data);
					$('.loading_signature_status').remove();
				    count = 1;
				    var status_1;
				    var status_2;
				    jQuery.each(data.signers, function( i, val ) {
				    	if(val.status == 'sent'){
				    		var sign_status = 'Pending Signature';
				    	}
				    	else if(val.status == 'completed'){
				    		var sign_status = 'Completed'; 
				    	}
				    	else {
				    		var sign_status = val.status;
				    	}
				    	html = '<div class="form-group col-md-6">'+
				    	            '<label><strong>Role:</strong> '+val.roleName+'</label><br/>'+
				    	            '<label><strong>Name:</strong> '+val.name+'</label><br/>'+
				    	            '<label><strong>Email:</strong> '+val.email+'</label><br/>'+
				    	            '<label><strong>Signature Status:</strong> '+sign_status+'</label>'+
				    	        '</div>';
				        $("#sign_status_list").append(html);

				        if(count == 1){
				    		status_1 = val.status;
				    	}
				    	else if(count == 2){
				    		status_2 = val.status;
				    	}
				    	else {
				    		status = val.status;
				    	}
				    	count++;
					});
				    console.log(status_1);
				    console.log(status_2);
				    // status_1 = 'completed';
				    // status_2 = 'completed';

				    if(status_1 == 'completed' && status_2 == 'completed'){
				    	console.log('completed - completed');
				    	var file_iframe_value = '<iframe src="'+baseUrl+doc_real_path+'" frameborder="0" width="100%" height="800"></iframe>';
						$('#sign_document').html(file_iframe_value);
				    }
				    else if(status_1 == 'completed' && status_2 == 'sent') {
				    	jQuery.ajax({
				            url: baseUrl + "get_document_docusign/"+envelop_id,
				            type: "POST",
				            data: {
				                "doc_path" 	: doc_path,
				                "doc_name" 	: doc_name
				                // "doc_name" 	: '63419_20170404.pdf'
				            },
				            headers: {
				              "x-access-token": token
				            },
				            contentType: "application/x-www-form-urlencoded",
				            cache: false
				        })
						.done(function(data, textStatus, jqXHR) {
						    console.log('Document Updated');
						    console.log(data);
						    jQuery.ajax({
							url: baseUrl + "notice-of-proceed/"+bd_id,
							    type: "GET",
							    headers: {
							      "Content-Type": "application/json",
							      "x-access-token": token
							    },
							    contentType: "application/json",
							    cache: false
							})
							.done(function(data, textStatus, jqXHR) {
								$('.loading_document').remove();
							    console.log('Notice Single Data');
							    console.log(data);
							    var file_iframe_value = '<iframe src="'+baseUrl+data.data.notice_proceed_path+'" frameborder="0" width="100%" height="800"></iframe>';
								$('#sign_document').html(file_iframe_value);
							})
						})
						.fail(function(jqXHR, textStatus, errorThrown) {
						    console.log("HTTP Request Failed");
						    var response = jqXHR.responseJSON.code;
						    if(response == 403){
						    	console.log("document 403");
						    }
						    else if(response == 404){
						    	console.log("document 404");
						    }
						    else {
						    	console.log("document 500");
						    }
						})
				    }
				    else if(status_1 == 'sent' && status_2 == 'completed') {
				    	jQuery.ajax({
				            url: baseUrl + "get_document_docusign/"+envelop_id,
				            type: "POST",
				            data: {
				                "doc_path" 	: doc_path,
				                "doc_name" 	: doc_name
				                // "doc_name" 	: '63419_20170404.pdf'
				            },
				            headers: {
				              "x-access-token": token
				            },
				            contentType: "application/x-www-form-urlencoded",
				            cache: false
				        })
						.done(function(data, textStatus, jqXHR) {
						    console.log('Document Updated');
						    console.log(data);
						    jQuery.ajax({
							url: baseUrl + "notice-of-proceed/"+bd_id,
							    type: "GET",
							    headers: {
							      "Content-Type": "application/json",
							      "x-access-token": token
							    },
							    contentType: "application/json",
							    cache: false
							})
							.done(function(data, textStatus, jqXHR) {
								$('.loading_document').remove();
							    console.log('Notice Single Data');
							    console.log(data);
							    var file_iframe_value = '<iframe src="'+baseUrl+data.data.notice_proceed_path+'" frameborder="0" width="100%" height="800"></iframe>';
								$('#sign_document').html(file_iframe_value);
							})
						})
						.fail(function(jqXHR, textStatus, errorThrown) {
						    console.log("HTTP Request Failed");
						    var response = jqXHR.responseJSON.code;
						    if(response == 403){
						    	console.log("document 403");
						    }
						    else if(response == 404){
						    	console.log("document 404");
						    }
						    else {
						    	console.log("document 500");
						    }
						})
				    }
				    else if(status_1 == 'sent' && status_2 == 'sent') {
				    	jQuery.ajax({
				            url: baseUrl + "get_document_docusign/"+envelop_id,
				            type: "POST",
				            data: {
				                "doc_path" 	: doc_path,
				                "doc_name" 	: doc_name
				                // "doc_name" 	: '63419_20170404.pdf'
				            },
				            headers: {
				              "x-access-token": token
				            },
				            contentType: "application/x-www-form-urlencoded",
				            cache: false
				        })
						.done(function(data, textStatus, jqXHR) {
						    console.log('Document Updated');
						    console.log(data);
						    jQuery.ajax({
							url: baseUrl + "notice-of-proceed/"+bd_id,
							    type: "GET",
							    headers: {
							      "Content-Type": "application/json",
							      "x-access-token": token
							    },
							    contentType: "application/json",
							    cache: false
							})
							.done(function(data, textStatus, jqXHR) {
								$('.loading_document').remove();
							    console.log('Notice Single Data');
							    console.log(data);
							    var file_iframe_value = '<iframe src="'+baseUrl+data.data.notice_proceed_path+'" frameborder="0" width="100%" height="800"></iframe>';
								$('#sign_document').html(file_iframe_value);
							})
						})
						.fail(function(jqXHR, textStatus, errorThrown) {
						    console.log("HTTP Request Failed");
						    var response = jqXHR.responseJSON.code;
						    if(response == 403){
						    	console.log("document 403");
						    }
						    else if(response == 404){
						    	console.log("document 404");
						    }
						    else {
						    	console.log("document 500");
						    }
						})
				    }
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
				    console.log("HTTP Request Failed");
				    var response = jqXHR.responseJSON.code;
				    if(response == 403){
				    	console.log("Envelop 403");
				    }
				    else if(response == 404){
				    	console.log("Envelop 404");
				    }
				    else {
				    	console.log("Envelop 500");
				    }
				})
            // },5000)
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