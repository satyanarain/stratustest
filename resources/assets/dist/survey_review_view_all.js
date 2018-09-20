  	$(document).ready(function() { 
     	// Get login user profile data
     	$("#view_users_table_wrapper").hide();
		var role = localStorage.getItem('u_role');
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		project_id = url[ url.length - 2 ]; // projects
		// console.log(project_id);
		type = url[ url.length - 1 ]; // projects
		// console.log(type);

		// Check View All Permission
		var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
		var check_permission = jQuery.inArray("survey_review_view_all", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
			$('.body-content .wrapper').show();
		}

		jQuery.ajax({
		url: baseUrl +project_id+"/survey-review",
		    type: "GET",
		    headers: {
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
		    .done(function(data, textStatus, jqXHR) {
		    console.log(data.data);

		    	window.project_name = data.data[0].p_name;
		    	$('#project_name_title').text("Project: " + window.project_name);
			    var specific_project_name = 'Survey Review for Project: ' + window.project_name;
			   	console.log(specific_project_name);
			    $('#view_users_table').DataTable({
	                dom: 'Bfrtip',
	                buttons: [
	                    // {
	                    //     extend: 'copyHtml5',
	                    //     exportOptions: {
	                    //         columns: [ 0, 1, 2, 3 ]
	                    //     },
	                    //     message: specific_project_name,
	                    // },
	                    {
	                        extend: 'csvHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'pdfHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'excelHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'print',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3 ]
	                        },
	                        message: specific_project_name,
	                    }
	                ]
	            });

		    $("#view_users_table_wrapper tbody tr").hide();
		    // Foreach Loop 
		    var count = 1;
			jQuery.each( data.data, function( i, val ) {
				// Check Update Permission
				var check_permission = jQuery.inArray("survey_review_update", check_user_access );
				console.log(check_permission);
				if(check_permission < 1){
					var update_permission = '';
				}
				else {
					if(val.sur_req_status == 'deactive'){
						var update_permission = '<a href="'+baseUrl+'404" class="btn btn-info btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" style="margin-right:5px;"><i class="fa fa-search"></i></a>';
					}
					else {
						if(val.sr_review_status == "completed"){
							var update_permission = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/survey/'+val.sr_survey_id+'" class="btn btn-info btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" style="margin-right:5px;"><i class="fa fa-search"></i></a>';
						}
						else {
							var update_permission = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/survey_review/'+val.sur_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
						}
					}
				}

				var status = val.sr_review_status;
				var check_expedited = val.sur_request_expedited;
                                alert(val.rir_review_respond);
				if(status == 'pending'){
//					if(check_expedited == 'yes'){
//						status = '<span class="label label-danger">PENDING – EXPEDITED REVIEW</span>';
//					}
//					else {
//		    			status = '<span class="label label-warning">PENDING</span>';
//					}
                                        if(val.sr_review_status == 'pending' && val.rir_review_respond == null){
						var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
						var firstDate = new Date();
						var secondDate = new Date(val.sur_request_completion_date);
						var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)));
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
							//var status = "<span class='label label-warning'>"+days +" Days " + hours1 + " Hours " + minutes1 + " Minutes Left to Respond</span>";
							if(check_expedited == 'yes'){
                                                            status = '<span class="label label-danger">PENDING – EXPEDITED REVIEW</span>';
                					}
                					else {
                                                            status = '<span class="label label-warning">PENDING</span>';
                					}
						}
						else {
							console.log('greater');
							//var status = '<span class="label label-danger">PAST DUE</span>';
                                                        var status = '<span class="label label-danger">PAST DUE</span>';
						}

						// var status = ' pending '
					}
			    }
			    else if(status == 'past_due'){
			    	if(check_expedited == 'yes'){
						status = '<span class="label label-danger">OVERDUE – EXPEDITED REVIEW</span>';
					}
					else {
		    			status = '<span class="label label-danger">PAST DUE</span>';
					}
			    }
			    else {
			    	status = '<span class="label label-success">COMPLETED</span>';
			    }

			    if(val.sr_request == null){
			    	var sr_request = ' - ';
			    }
			    else {
			    	var sr_request = val.sr_request;
			    }

			  var t = $('#view_users_table').DataTable();
				if(val.sur_request_expedited == 'yes'){
					t.row.add( [
			           '<span style="color:#F00;">'+count+'</span>', // val.sur_id,
			           '<span style="color:#F00;">'+val.sur_description+'</span>',
			           status,
			           '<span style="color:#F00;">'+sr_request+'</span>',
			           update_permission
			       	]).draw( false );
				}
				else {
					t.row.add( [
			           count, // val.sur_id,
			           val.sur_description,
			           status,
			           sr_request,
			           update_permission
			       	]).draw( false );
				}
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

    });