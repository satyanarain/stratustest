  	$(document).ready(function() { 
     	// Get login user profile data
     	$("#view_users_table_wrapper").hide();
		var role = localStorage.getItem('u_role');
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		project_id = url[ url.length - 2 ]; // projects
		// console.log(project_id);
		// type = url[ url.length - 1 ]; // projects
		// console.log(type);

		// Check View All Permission
		var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
		var check_permission = jQuery.inArray("rfi_log", check_user_access );
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
	        window.project_name = data.data.p_name;
	        $('#project_name_title').text("Project: " + window.project_name);
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
			jQuery.ajax({
			url: baseUrl +project_id+"/get-request-information-log",
			    type: "GET",
			    headers: {
			      "x-access-token": token
			    },
			    contentType: "application/json",
			    cache: false
			})
			    .done(function(data, textStatus, jqXHR) {
			    // console.log(data.data);
			    var specific_project_name = 'Request for Information Log for Project: ' + window.project_name;
			   	console.log(specific_project_name);
			    $('#view_users_table').DataTable({
	                dom: 'Bfrtip',
	                buttons: [
	                    // {
	                    //     extend: 'copyHtml5',
	                    //     message: specific_project_name,
	                    // },
	                    {
	                        extend: 'csvHtml5',
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'pdfHtml5',
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'excelHtml5',
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'print',
	                        message: specific_project_name,
	                    }
	                ]
	            });

			    $("#view_users_table_wrapper tbody tr").hide();
			    // Foreach Loop 
			    var count = 1;
				jQuery.each( data.data, function( i, val ) {
					var status = val.rir_review_status;
					if(status == 'response_due'){
			    	// status = '<span class="label label-warning">RESPONSE DUE</span>';

						if(val.rir_review_status == 'response_due' && val.rir_review_respond == null){
							var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
							var future_date = new Date(val.ri_date);
							var numberOfDaysToAdd = 10;
							var futuredate = future_date.setDate(future_date.getDate() + numberOfDaysToAdd); 
							var now_date = new Date();
							var numberOfDaysToAdd = 0;
							var nowdate = now_date.setDate(now_date.getDate() + numberOfDaysToAdd); 
							var diffDays = Math.round(Math.abs((future_date.getTime() - now_date.getTime())/(oneDay)));
							// console.log(future_date);
							// console.log(now_date);
							// console.log(diffDays);

							if(futuredate < nowdate){
								console.log('less');
								var status = '<span class="label label-danger">PAST DUE</span>';
							}
							else {
								console.log('greater');
								seconds = Math.floor((futuredate - (nowdate))/1000);
							    minutes = Math.floor(seconds/60);
							    hours = Math.floor(minutes/60);
							    days = Math.floor(hours/24);
							    
							    hours1 = hours-(days*24);
							    minutes1 = minutes-(days*24*60)-(hours1*60);
							    seconds1 = seconds-(days*24*60*60)-(hours1*60*60)-(minutes1*60);

							 // 	console.log(someDate_now);
								// console.log(date_future);
							 // 	console.log(date_now);
						    	var status = "<span class='label label-warning'>"+days +" Days " + hours1 + " Hours " + minutes1 + " Minutes Left to Respond</span>";
							}
						}
						else {
								var status = ' - '
						}
				    }
				    else if(status == 'past_due'){
			    	status = '<span class="label label-danger">PAST DUE</span>';
				    }
				    else if(status == 'response_provided'){
			    	status = '<span class="label label-success">RESPONSE PROVIDED</span>';
				    }
				    else if(status == 'additional_information_requested'){
			    	status = '<span class="label label-success">ADDTIONAL INFORMATION REQUESTED</span>';
				    }
				    else {
				    	status = '<span class="label label-info">REVIEWED</span>';
				    }
				var req_info_path = val.file_path;
			  	var req_info_path_value;
			  	if(req_info_path == null){
			  		req_info_path_value = '-';
			  	}
			  	else {
			  		if(val.ri_request_status == 'active'){
			  			//req_info_path_value = '<a href="https://apps.groupdocs.com/document-viewer/embed/'+val.file_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
                                                req_info_path_value = '<a href="'+val.file_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  		else {
			  			req_info_path_value = '<a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  		
			  	}  	
				  var date = new Date(val.ri_date.replace(' ', 'T'));
				  var day = date.getDate();
				  var month = date.getMonth()+1;
				  var year = date.getFullYear();
				  var submitted_date = year + '-' + month + '-' + day;
				  if(val.rir_review_respond == null){
				  		var respond_date = ' ';
				  }
				  else {
					  var date = new Date(val.rir_review_respond.replace(' ', 'T'));
					  var day = date.getDate();
					  var month = date.getMonth();
					  var year = date.getFullYear();
					  var respond_date = year + '-' + month + '-' + day;
				  }

				  

				  var t = $('#view_users_table').DataTable();
					t.row.add( [
			           count, // val.ri_id,
			           val.rfi_user_company,
			           val.ri_question_request,
			           val.review_user_company,
			           submitted_date,
                                   req_info_path_value,
			           respond_date,
			           // date_status,
			           status,
			       	]).draw( false );
			       	count++;
				});
			    // $( "h2" ).appendTo( $( ".container" ) );
			   



			    $("#view_users_table_wrapper").show();
			    $(".loading_data").hide();

			})
			.fail(function(jqXHR, textStatus, errorThrown) {
			    console.log("HTTP Request Failed");
			    var response = jqXHR.responseJSON.code;
			    console.log(response);
			    if(response == 403){
			    	window.location.href = baseUrl + "403";
			    }
			    else if(response == 404){
			    	// window.location.href = baseUrl + "404";
			    	$("#view_users_table_wrapper").show();
			    	$(".loading_data").hide();
			    }
			    else {
			    	window.location.href = baseUrl + "500";
			    }
			}); 
		},2000)

		setTimeout(function()
        {
            // Check Add Permission
			var check_permission = jQuery.inArray("rfi_add", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_add_permission').remove();
			}
			else {
				$('.hide_add_permission').show();
			}
        },2000)


    });