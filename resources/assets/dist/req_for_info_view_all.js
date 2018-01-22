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
		url: baseUrl +project_id+"/request-information",
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
		    var specific_project_name = 'Request for Information for Project: ' + window.project_name;
		   	console.log(specific_project_name);
		    $('#view_users_table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    // {
                    //     extend: 'copyHtml5',
                    //     exportOptions: {
                    //         columns: [ 0, 1, 3, 4 ]
                    //     },
                    //     message: specific_project_name,
                    // },
                    {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 3, 4 ]
                        },
                        message: specific_project_name,
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 3, 4 ]
                        },
                        message: specific_project_name,
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 3, 4 ]
                        },
                        message: specific_project_name,
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 0, 1, 3, 4 ]
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
				var check_permission = jQuery.inArray("rfi_update", check_user_access );
				console.log(check_permission);
				if(check_permission < 1){
					var update_permission = '';
				}
				else {
						var update_permission = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/req_for_info/'+val.ri_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
			  	}

				if(val.ri_request_status == 'active'){
			  		var view_option = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/req_for_info/'+val.ri_id+'" class="btn btn-info btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" style="margin-right:5px;"><i class="fa fa-search"></i></a>';
				}
		  		else {
		  			var view_option = '<a href="'+baseUrl+'404" class="btn btn-info btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" style="margin-right:5px;"><i class="fa fa-search"></i></a>';
		  		}


				var status = val.rir_review_status;
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
			    else {
			    	status = '<span class="label label-info">REVIEWED</span>';
			    }

			    var post_status = val.ri_request_status;
				if(post_status == 'active'){
		    	post_status = '<span class="label label-success">Active</span>';
			    }
			    else {
			    	post_status = '<span class="label label-danger">Inactive</span>';
			    }
			  	
			  	var req_info_path = val.file_path;
			  	var req_info_path_value;
			  	if(req_info_path == null){
			  		req_info_path_value = '-';
			  	}
			  	else {
			  		if(val.ri_request_status == 'active'){
			  			//req_info_path_value = '<a href="https://apps.groupdocs.com/document-viewer/embed/'+val.file_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
                                                req_info_path_value = '<a href="'+baseUrl+val.file_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  		else {
			  			req_info_path_value = '<a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  		
			  	}
			 
			  var date = new Date(val.ri_date.replace(' ', 'T'));
              var formatDate = $.datepicker.formatDate('yy-mm-dd', date);
			  var day = date.getDate();
			  var month = date.getMonth();
			  var year = date.getFullYear();

			  //var date_format = year + '-' + month + '-' + day;
			  var date_format = formatDate;

			  var t = $('#view_users_table').DataTable();
				t.row.add( [
		           count, // val.ri_id,
		           date_format,
		           req_info_path_value,
		           status,
		           post_status,
		           view_option + update_permission
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
			// Check Add Permission
			var check_permission = jQuery.inArray( "rfi_add", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_add_permission').remove();
			}
			else {
				$('.hide_add_permission').show();
			}
		},2000)


    });