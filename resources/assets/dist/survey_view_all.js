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
		url: baseUrl +project_id+"/survey",
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
			    var specific_project_name = 'Survey for Project: ' + window.project_name;
			   	console.log(specific_project_name);
			    $('#view_users_table').DataTable({
	                dom: 'Bfrtip',
	                buttons: [
	                    // {
	                    //     extend: 'copyHtml5',
	                    //     exportOptions: {
	                    //         columns: [ 0, 1, 2, 3, 5, 6 ]
	                    //     },
	                    //     message: specific_project_name,
	                    // },
	                    {
	                        extend: 'csvHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 5, 6 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'pdfHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 5, 6 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'excelHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 5, 6 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'print',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 5, 6 ]
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
				var check_permission = jQuery.inArray("survey_update", check_user_access );
				console.log(check_permission);
				if(check_permission < 1){
					var update_permission = '';
				}
				else {
					var update_permission = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/survey/'+val.sur_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
				}

				var status = val.sr_review_status;
				if(status == 'pending'){
		    	// status = '<span class="label label-warning">PENDING</span>';
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
							// console.log(hours1);
							var status = "<span class='label label-warning'>"+days +" Days " + hours1 + " Hours " + minutes1 + " Minutes Left to Respond</span>";
							// var status = '<span class="label label-danger">PAST DUE LESS</span>';
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
                                //status = '<span class="label label-danger">PAST DUE</span>';
                                status = '<span class="label label-danger">PAST DUE</span>';
			    }
			    else if(status == 'completed'){
		    	status = '<span class="label label-success">COMPLETED</span>'
			    }
			    else {
			    	status = '-';
			    }

			    var sr_status = val.sur_req_status;
				if(sr_status == 'active'){
		    		sr_status = '<span class="label label-success">ACTIVE</span>'
			    }
			    else {
		    		sr_status = '<span class="label label-danger">INACTIVE</span>';
			    }
			   
			   	var file_path = val.file_path;
			  	var file_path_value;
			  	if(file_path == null){
			  		file_path_value = '-';
			  	}
			  	else {
			  		if(val.sur_req_status == 'active'){
			  			file_path_value = '<a href="'+baseUrl+val.file_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  		else {
			  			file_path_value = '<a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  	}

			  	if(val.sur_req_status == 'active'){
		  			var single_view = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/survey/'+val.sur_id+'" class="btn btn-info btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" style="margin-right:5px;"><i class="fa fa-search"></i></a>';
		  		}
		  		else {
		  			var single_view = '<a href="'+baseUrl+'404" class="btn btn-info btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" style="margin-right:5px;"><i class="fa fa-search"></i></a>';
		  		}
			  	
			 
				// var date = new Date(val.sur_date.replace(' ', 'T'));
    //             var formatDate = $.datepicker.formatDate('yy-mm-dd', date);
				// var day = date.getDate();
				// var month = date.getMonth();
				// var year = date.getFullYear();
				//var request_date = year + '-' + month + '-' + day;
				var request_date = val.sur_date; // formatDate;
                                
				 var d = new Date(val.sur_request_completion_date.replace(' ', 'T'));
                                 //alert(formatAMPM(d));
                                 //var formatDate = $.datepicker.formatDate('yy-mm-dd', date);
				 //var day = date.getDate();
				 //var month = date.getMonth()+1;
				 //var year = date.getFullYear();
				//var completion_date = year + '-' + month + '-' + day;
                                var completion_date = formatAMPM(d);
				//var completion_date = val.sur_request_completion_date; //formatDate;
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
			           //'<span style="color:#F00;">'+val.sur_number+'</span>', //val.sur_id,
                                   count,
			           '<span style="color:#F00;">'+request_date+'</span>',
			           '<span style="color:#F00;">'+val.sur_description+'</span>',
			           '<span style="color:#F00;">'+completion_date+'</span>',
			           file_path_value,
                                   survey_rew_path_value,
			           status,
			           sr_status,
			           single_view + update_permission
			       	]).draw( false );
				}
				else {
					t.row.add( [
			           //val.sur_number,
                                   count,
			           request_date,
			           val.sur_description,
			           completion_date,
			           file_path_value,
                                   survey_rew_path_value,
			           status,
			           sr_status,
			           single_view + update_permission
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

		setTimeout(function()
        {
			// Check Add Permission
			var check_permission = jQuery.inArray( "survey_add", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_add_permission').remove();
			}
			else {
				$('.hide_add_permission').show();
			}
		},2000)


    });
    
 function formatAMPM(d) {
  var hours = d.getHours();
  var minutes = d.getMinutes();
  var ampm = hours >= 12 ? 'pm' : 'am';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
  var strTime = ("0" + hours).slice(-2) + ':' + ("0" + minutes).slice(-2) + ' ' + ampm;
  return d.getFullYear() + "-" +("0"+(d.getMonth()+1)).slice(-2) + "-" +("0" + d.getDate()).slice(-2) + "  "
     +strTime;
}   