  	$(document).ready(function() { 
     	// Get login user profile data
     	// $("#view_users_table_wrapper").hide();
		var role = localStorage.getItem('u_role');
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		project_id = url[ url.length - 2 ]; // projects
		// console.log(project_id);
		type = url[ url.length - 1 ]; // projects
		// console.log(type);

		// Check View All Permission
		var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
		var check_permission = jQuery.inArray( "service_alert_view_all", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
		}

		jQuery.ajax({
		url: baseUrl+project_id+"/service-alert",
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
			    var specific_project_name = 'Service Alert for Project: ' + window.project_name;
			   	console.log(specific_project_name);
			    $('#view_users_table').DataTable({
	                dom: 'Bfrtip',
	                buttons: [
	                    // {
	                    //     extend: 'copyHtml5',
	                    //     exportOptions: {
	                    //         columns: [ 0, 1, 2, 3, 4, 5, 6 ]
	                    //     },
	                    //     message: specific_project_name,
	                    // },
	                    {
	                        extend: 'csvHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'pdfHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'excelHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'print',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
	                        },
	                        message: specific_project_name,
	                    }
	                ]
	            });

		    $("#view_users_table_wrapper").hide();
		    // Foreach Loop 
		    var count = 1;
			jQuery.each( data.data, function( i, val ) {
				// Check Update Permission
				var check_permission = jQuery.inArray("service_alert_update", check_user_access );
				console.log(check_permission);
				if(check_permission < 1){
					var update_permission = '';
				}
				else {
					var update_permission = '<a href="'+baseUrl+'dashboard/'+val.psa_project_id+'/service_alert/'+val.psa_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
				}

				var status = val.psa_status;
				if(status == 'active'){
		    	// status = '<span class="label label-success">ACTIVE</span>';
						// var someDate_now = new Date(val.psa_timestamp.replace(' ', 'T'));
						// var numberOfDaysToAdd = 0;
						// var date_now = someDate_now.setDate(someDate_now.getDate() + numberOfDaysToAdd); 
						// var someDate = new Date(val.psa_expire_date);
						// var numberOfDaysToAdd = 0;
						// var date_future = someDate.setDate(someDate.getDate() + numberOfDaysToAdd); 
						// // console.log(someDate_now);
						// // console.log(someDate);
						// seconds = Math.floor((date_future - (date_now))/1000);
					 //    minutes = Math.floor(seconds/60);
					 //    hours = Math.floor(minutes/60);
					 //    days = Math.floor(hours/24);
					    
					 //    hours = hours-(days*24);
					 //    minutes = minutes-(days*24*60)-(hours*60);
					 //    seconds = seconds-(days*24*60*60)-(hours*60*60)-(minutes*60);


					    var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
						var firstDate = new Date(val.psa_expire_date);
						var secondDate = new Date();
						var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)));
						console.log(firstDate.getTime());
						console.log(secondDate.getTime());
						console.log(diffDays);
						if(diffDays <= 5){
							if(firstDate.getTime() < secondDate.getTime()){
								console.log('less');
								status = '<span class="label label-danger">EXPIRED</span>';
							}
							else {
								console.log('greater');
								status = "<span class='label label-warning'>EXPIRING SOON</span>";
							}
						}
						else {
							status = "<span class='label label-success'>ACTIVE</span>";
						}
					    // var status = date_now +' '+ date_future;
			    }
			    else if(status == 'not_valid'){
		    	status = '<span class="label label-danger">NOT VALID</span>';
			    }
			    else {
			    	status = '<span class="label label-danger">EXPIRED</span>';
			    }

			    var status_completed = val.psa_work_completed;
				if(status_completed == 'yes'){
    		    	status_completed = '<span class="label label-success">YES</span>';
			    }
			    else {
			    	status_completed = '<span class="label label-danger">NO</span>';
			    }

				// var date = new Date(val.psa_date_called_in);
    //             var formatDate = $.datepicker.formatDate('yy-mm-dd', date);
				// var day = date.getDate();
				// var month = date.getMonth()+1;
				// var year = date.getFullYear();
				//var psa_date_called_in = year + '-' + month + '-' + day;
				var psa_date_called_in = val.psa_date_called_in; // $.datepicker.formatDate('yy-mm-dd', new Date(val.psa_date_called_in.replace(' ', 'T'))); // formatDate;
				psa_date_called_in = psa_date_called_in.replace('/', '-');
				psa_date_called_in = psa_date_called_in.replace('/', '-');
				// console.log(psa_date_called_in);

				// var date = new Date(val.psa_date_called_valid);
    //             var formatDate = $.datepicker.formatDate('yy-mm-dd', date);
				// var day = date.getDate();
				// var month = date.getMonth()+1;
				// var year = date.getFullYear();
				//var psa_date_called_valid = year + '-' + month + '-' + day;
				var psa_date_called_valid = val.psa_date_called_valid; // $.datepicker.formatDate('yy-mm-dd', new Date(val.psa_date_called_valid.replace(' ', 'T'))); // formatDate;
				psa_date_called_valid = psa_date_called_valid.replace('/', '-');
				psa_date_called_valid = psa_date_called_valid.replace('/', '-');
				// console.log(psa_date_called_valid);
				// var date = new Date(val.psa_expire_date);
    //             var formatDate = $.datepicker.formatDate('yy-mm-dd', date);
				// var day = date.getDate();
				// var month = date.getMonth()+1;
				// var year = date.getFullYear();
				//var psa_expire_date = year + '-' + month + '-' + day;
				var psa_expire_date = val.psa_expire_date; // $.datepicker.formatDate('yy-mm-dd', new Date(val.psa_expire_date.replace(' ', 'T'))); // formatDate;

			  	var t = $('#view_users_table').DataTable();
				t.row.add( [
				   count,
		           psa_date_called_in,
		           psa_date_called_valid,
		           val.psa_ticket_number,
		           val.psa_address,
		           psa_expire_date,
		           status,
		           status_completed,
		           update_permission,
		       	]).draw( false );
		       	count++;
			});

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
		    	// alert('faizan');
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
			var check_permission = jQuery.inArray( "service_alert_add", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_add_permission').remove();
			}
			else {
				$('.hide_add_permission').show();
			}
		},2000)
    });