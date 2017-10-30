  	$(document).ready(function() { 
     	// Get login user profile data
     	$("#view_users_table_wrapper").hide();
		var role = localStorage.getItem('u_role');
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		project_id = url[ url.length - 2 ]; // projects
		console.log(project_id);
		// type = url[ url.length - 1 ]; // projects
		// console.log(type);

		// Check View All Permission
		var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
		var check_permission = jQuery.inArray("survey_log", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
			$('.body-content .wrapper').show();
		}

		jQuery.ajax({
		url: baseUrl +project_id+"/get-survey-log",
		    type: "GET",
		    headers: {
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
		    .done(function(data, textStatus, jqXHR) {
		    // console.log(data.data);

		    	window.project_name = data.data[0].p_name;
		    	$('#project_name_title').text("Project: " + window.project_name);
			    var specific_project_name = 'Survey Log for Project: ' + window.project_name;
			   	console.log(specific_project_name);
			    $('#view_users_table').DataTable({
	                dom: 'Bfrtip',
	                buttons: [
	                    // {
	                    //     extend: 'copyHtml5',
	                    //     exportOptions: {
	                    //         columns: [ 0, 1, 2, 3, 6 ]
	                    //     },
	                    //     message: specific_project_name,
	                    // },
	                    {
	                        extend: 'csvHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 6 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'pdfHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 6 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'excelHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 6 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'print',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 6 ]
	                        },
	                        message: specific_project_name,
	                    }
	                ]
	            });

		    $("#view_users_table_wrapper tbody tr").hide();
		    // Foreach Loop 
		    var counter = 1;
			jQuery.each( data.data, function( i, val ) {
				var status = val.sr_review_status;
				if(status == 'pending'){
		    		// status = '<span class="label label-warning">PENDING</span>';
		    		if(val.sr_review_status == 'pending' && val.rir_review_respond == null){
						// 	var someDate_now = new Date(val.sr_timestamp.replace(' ', 'T'));
						// 	var numberOfDaysToAdd = 0;
						// 	var date_now = someDate_now.setDate(someDate_now.getDate() + numberOfDaysToAdd); 
						// 	var someDate = new Date();
						// 	var numberOfDaysToAdd = 10;
						// 	var date_future = someDate.setDate(someDate.getDate() + numberOfDaysToAdd); 

						// 	console.log(date_now);
						// 	console.log(date_future);

						// seconds = Math.floor((date_future - (date_now))/1000);
					 //    minutes = Math.floor(seconds/60);
					 //    hours = Math.floor(minutes/60);
					 //    days = Math.floor(hours/24);
					    
					 //    hours = hours-(days*24);
					 //    minutes = minutes-(days*24*60)-(hours*60);
					 //    seconds = seconds-(days*24*60*60)-(hours*60*60)-(minutes*60);

					    // var status = "<span class='label label-warning'>"+days +" Days " + hours + " Hours " + minutes + " Minutes Left to Respond</span>";
						
						var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
						var firstDate = new Date();
						var secondDate = new Date(val.sur_request_completion_date);
						var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)));
						// console.log(firstDate);
						// console.log(secondDate);
						// console.log(diffDays);
						if(firstDate.getTime() < secondDate.getTime()){
							console.log('less');
							seconds = Math.floor((secondDate - (firstDate))/1000);
							minutes = Math.floor(seconds/60);
						    hours = Math.floor(minutes/60);
						    days = Math.floor(hours/24);
							// console.log(hours);
							hours1 = hours-(days*24);
							minutes1 = minutes-(days*24*60)-(hours1*60);
					    	seconds = seconds-(days*24*60*60)-(hours*60*60)-(minutes1*60);
							// console.log(hours1);
							var status = "<span class='label label-warning'>"+days +" Days " + hours1 + " Hours " + minutes1 + " Minutes Left to Respond</span>";
							// var status = '<span class="label label-danger">PAST DUE LESS</span>';
						}
						else {
							console.log('greater');
							var status = '<span class="label label-danger">PAST DUE</span>';
						}

						// var status = ' pending '
					}
					else {
							var status = ' - '
					}
			    }
			    else if(status == 'past_due'){
		    		status = '<span class="label label-danger">PAST DUE</span>';
			    }
			    else {
			    	status = '<span class="label label-success">COMPLETED</span>';
			    }
			  	
			  // var date = new Date(val.sur_date.replace(' ', 'T'));
     //          var formatDate = $.datepicker.formatDate('yy-mm-dd', date);
			  // var day = date.getDate();
			  // var month = date.getMonth();
			  // var year = date.getFullYear();
			  //var submitted_date = year + '-' + month + '-' + day;
			  var submitted_date = val.sur_date; // formatDate;

			  if(val.sur_request_completion_date == null){
			  		var respond_date = ' ';
			  }
			  else {
				  // var date = new Date(val.sur_request_completion_date.replace(' ', 'T'));
      //             var formatDate = $.datepicker.formatDate('yy-mm-dd', date);
				  // var day = date.getDate();
				  // var month = date.getMonth();
				  // var year = date.getFullYear();
				  //var respond_date = year + '-' + month + '-' + day;
				  var respond_date = val.sur_request_completion_date; // formatDate;
			  }

			   	var survey_req_path = val.survey_req_path;
			    if(survey_req_path == null){
			  	 	var	survey_req_path_value = '-';
			  	}
			  	else {
			  		var survey_req_path_value = '<a href="'+baseUrl+val.survey_req_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  	}

			  	var survey_rew_path = val.survey_rew_path;
			    if(survey_rew_path == null){
			  	 	var	survey_rew_path_value = '-';
			  	}
			  	else {
			  		var survey_rew_path_value = '<a href="'+baseUrl+val.survey_rew_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  	}


			  var t = $('#view_users_table').DataTable();
				if(val.sur_request_expedited == 'yes'){
					t.row.add( [
			           '<span style="color:#F00;">'+counter+'</span>', // val.sur_id,
			           '<span style="color:#F00;">'+val.sur_description+'</span>',
			           '<span style="color:#F00;">'+submitted_date+'</span>',
			           '<span style="color:#F00;">'+respond_date+'</span>',
			           survey_req_path_value,
			           survey_rew_path_value,
			           status,
			       	]).draw( false );
		       		counter++;
				}
				else {
					t.row.add( [
			           counter, // val.sur_id,
			           val.sur_description,
			           submitted_date,
			           respond_date,
			           survey_req_path_value,
			           survey_rew_path_value,
			           status,
			       	]).draw( false );
		       		counter++;
				}
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


		setTimeout(function()
        {
            // Check Add Permission
			var check_permission = jQuery.inArray("survey_add", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_add_permission').remove();
			}
			else {
				$('.hide_add_permission').show();
			}
        },2000)


    });