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

		// // Get All Permissions
		// var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
		// jQuery.each( check_user_access, function( i, val ) {
  //       	console.log(val);
  //       	$('.'+val).show();
		// });

		// Check View All Permission
		var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
		var check_permission = jQuery.inArray( "swppp_view_all", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
		}

		jQuery.ajax({
		url: baseUrl +project_id+"/swppp",
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
			    var specific_project_name = 'SWPPP / WPCP for Project: ' + window.project_name;
			   	console.log(specific_project_name);
			    $('#view_users_table').DataTable({
	                dom: 'Bfrtip',
	                buttons: [
	                    // {
	                    //     extend: 'copyHtml5',
	                    //     exportOptions: {
	                    //         columns: [ 0, 1, 2, 3, 4, 5, 7 ]
	                    //     },
	                    //     message: specific_project_name,
	                    // },
	                    {
	                        extend: 'csvHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 5, 7 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'pdfHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 5, 7 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'excelHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 5, 7 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'print',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 5, 7 ]
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
				var check_permission = jQuery.inArray( "swppp_update", check_user_access );
				console.log(check_permission);
				if(check_permission < 1){
					var update_permission = '';
				}
				else {
					var update_permission = '<a href="'+baseUrl+'dashboard/'+val.sw_project_id+'/swppp/'+val.sw_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
				}

				var status = val.sw_status;
				if(status == 'active'){
		    	status = '<span class="label label-success">Active</span>';
			    }
			    else {
			    	status = '<span class="label label-danger">Inactive</span>';
			    }
			  	
			  	var doc_path = val.sw_file_path;
			  	var doc_path_value;
			  	if(doc_path == null || doc_path == '0'){
			  		doc_path_value = '<td> - </td>';
			  	}
			  	else {
			  		if(val.sw_status == 'active'){
			  			doc_path_value = '<td><a href="'+baseUrl+val.doc_path+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" alt="'+val.sw_name+'" width="40"/></a></td>';
			  		}
			  		else {
			  			doc_path_value = '<td><a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
			  		}
			  	}

			  	if(val.sw_applicable == 'yes'){
			  		var sw_applicable = "Yes";
			  	}
			  	else {
			  		var sw_applicable = "No";
			  	}

			  	if(val.sw_type == 'wpcp'){
			  		var sw_type = "WPCP";
			  	}
			  	else {
			  		var sw_type = "SWPPP";
			  	}

			  	var t = $('#view_users_table').DataTable();
				t.row.add( [
		           count,
		           val.sw_name,
                           val.wdid_no,
                           val.wdid_expiration_date,
                           val.sw_date,
		           val.f_name,
		           sw_applicable,
		           sw_type,
		           doc_path_value,
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
			// Check Add Permission
			var check_permission = jQuery.inArray( "swppp_add", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_add_permission').remove();
			}
			else {
				$('.hide_add_permission').show();
			}

			// Check Update Permission
			var check_permission = jQuery.inArray( "swppp_update", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_update_permission').remove();
			}
			else {
				$('.hide_update_permission').show();
			}
		},1000)

		// // Hide add Functionality
		// jQuery.ajax({
	 //        url: baseUrl +project_id+"/get_user_permission_key",
	 //        type: "POST",
	 //        data: {
	 //            "permission_key"       : 'swppp_add'
	 //        },
	 //        headers: {
	 //          "x-access-token": token
	 //        },
	 //        contentType: "application/x-www-form-urlencoded",
	 //        cache: false
	 //    })
	 //    .done(function(data, textStatus, jqXHR) {
	 //        console.log(data.data);
	 //        $('.hide_add_permission').show();
	 //    })
	 //    .fail(function(jqXHR, textStatus, errorThrown) {
	 //        console.log("HTTP Request Failed");
	 //        var response = jqXHR.responseJSON.code;
	 //        console.log(jqXHR.responseText);
	 //        $('.hide_add_permission').remove();
	 //    }); 

	 //    // Hide add Functionality
		// jQuery.ajax({
	 //        url: baseUrl +project_id+"/get_user_permission_key",
	 //        type: "POST",
	 //        data: {
	 //            "permission_key"       : 'swppp_update'
	 //        },
	 //        headers: {
	 //          "x-access-token": token
	 //        },
	 //        contentType: "application/x-www-form-urlencoded",
	 //        cache: false
	 //    })
	 //    .done(function(data, textStatus, jqXHR) {
	 //        console.log(data.data);
	 //        $('.hide_update_permission').show();
	 //    })
	 //    .fail(function(jqXHR, textStatus, errorThrown) {
	 //        console.log("HTTP Request Failed");
	 //        var response = jqXHR.responseJSON.code;
	 //        console.log(jqXHR.responseText);
	 //        $('.hide_update_permission').remove();
	 //    }); 
    });