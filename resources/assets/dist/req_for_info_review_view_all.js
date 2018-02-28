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
		var check_permission = jQuery.inArray("rfi_review_view_all", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
			$('.body-content .wrapper').show();
		}

		jQuery.ajax({
		url: baseUrl +project_id+"/request-review",
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
		    var specific_project_name = 'Request for Information Review for Project: ' + window.project_name;
		   	console.log(specific_project_name);
		    $('#view_users_table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    // {
                    //     extend: 'copyHtml5',
                    //     exportOptions: {
                    //         columns: [ 0, 1, 2, 3, 4, 5 ]
                    //     },
                    //     message: specific_project_name,
                    // },
                    {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5 ]
                        },
                        message: specific_project_name,
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5 ]
                        },
                        message: specific_project_name,
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5 ]
                        },
                        message: specific_project_name,
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5 ]
                        },
                        message: specific_project_name,
                    }
                ]
            });

		    $("#view_users_table_wrapper tbody tr").hide();
		    // Foreach Loop 
		    var count = 1;
			jQuery.each( data.data, function( i, val ) {
				console.log(val);
				// Check Update Permission
				var check_permission = jQuery.inArray("standard_update", check_user_access );
				console.log(check_permission);
				if(check_permission < 1){
					var update_permission = '';
				}
				else {
					var status = val.rir_review_status;
					if(status == 'response_due'){
			    		var update_permission = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/req_for_info_review/'+val.ri_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
				    }
				    else if(status == 'past_due'){
			    		var update_permission = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/req_for_info_review/'+val.ri_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
				    }
				    else if(status == 'response_provided'){
			    		var update_permission = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/req_for_info/'+val.ri_id+'" class="btn btn-info btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" style="margin-right:5px;"><i class="fa fa-search"></i></a>';
				    }
				    else if(status == 'additional_information_requested'){
			    		var update_permission = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/req_for_info/'+val.ri_id+'" class="btn btn-info btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" style="margin-right:5px;"><i class="fa fa-search"></i></a>';
				    }
				    else {
				    	var update_permission = '<a href="'+baseUrl+'404" class="btn btn-info btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" style="margin-right:5px;"><i class="fa fa-search"></i></a>';
				    }
					
				}

				var status = val.rir_review_status;
				if(status == 'response_due'){
		    	status = '<span class="label label-warning">RESPONSE DUE</span>';
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

				var date = new Date(val.ri_date.replace(' ', 'T'));
                var formatDate = $.datepicker.formatDate('yy-mm-dd', date);
				var day = date.getDate();
				var month = date.getMonth();
				var year = date.getFullYear();
				//var date_submitted = year + '-' + month + '-' + day;
				var date_submitted = formatDate;

				if(val.rir_review_respond == null){
					var date_responded = ' - ';
				}
				else {
					var date = new Date(val.rir_review_respond.replace(' ', 'T'));
                    var formatDate = $.datepicker.formatDate('yy-mm-dd', date);
					var day = date.getDate();
					var month = date.getMonth();
					var year = date.getFullYear();
					//var date_responded = year + '-' + month + '-' + day;
					var date_responded = formatDate;
				}

			  var t = $('#view_users_table').DataTable();
				t.row.add( [
		           count, // .ri_id,
		           val.ri_question_request,
		           val.company_name,
		           date_submitted,
		           val.rir_review_respond,
		           status,
		           update_permission
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


		setTimeout(function()
        {
			// Check Update Permission
			var check_permission = jQuery.inArray( "rfi_review_update", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_update_permission').remove();
			}
			else {
				$('.hide_update_permission').show();
			}
		},1000)



    });