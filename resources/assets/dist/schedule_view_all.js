  	$(document).ready(function() { 
     	// Get login user profile data
     	$("#view_users_table_wrapper").hide();
        var role = localStorage.getItem('u_role');
        var token = localStorage.getItem('u_token');
        var url = $(location).attr('href').split( '/' );
        var u_new_role = window.localStorage.getItem("u_new_role");
        project_id = url[ url.length - 2 ]; // projects
        console.log(project_id);
        type = url[ url.length - 1 ]; // projects
        // console.log(type);

        // Check View All Permission
        var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
        var check_permission = jQuery.inArray( "schedule_view_all", check_user_access );
        //alert(check_permission);
        console.log(check_permission);
        if(check_permission < 1){
                window.location.href = baseUrl + "403";
        }
        else {
                console.log('Yes Permission');
        }

		jQuery.ajax({
		url: baseUrl +project_id+"/schedule",
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
		    var specific_project_name = 'As Built Drawing for Project: ' + window.project_name;
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
				// Check Update Permission
				var check_permission = jQuery.inArray( "schedule_update", check_user_access );
				console.log(check_permission);
				if(check_permission < 1){
					var update_permission = '';
				}
				else {
					var update_permission = '<a href="'+baseUrl+'dashboard/'+val.schedule_project_id+'/schedule/'+val.schedule_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
				}
                                //alert(data.data.doc_path);
                                var custom_cert_path = val.doc_path;
                                var custom_cert_path_value;
                                if(custom_cert_path == null){
                                    custom_cert_path_value = '-';
                                }
                                else {
                                    custom_cert_path_value = '<a href="'+custom_cert_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
                                }
				var status = val.schedule_status;
				if(status == 'active'){
                                    status = '<span class="label label-success">Active</span>';
                                }
                                else {
                                    status = '<span class="label label-danger">Inactive</span>';
                                }

			  	if(val.schedule_status == 'active'){
		  			var view_single = '<a href="'+baseUrl+'dashboard/'+val.schedule_project_id+'/schedule/'+val.schedule_id+'" class="btn btn-success btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-search"></i></a>';
		  		}
		  		else {
		  			var view_single = '<a href="'+baseUrl+'404" class="btn btn-success btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-search"></i></a>';
		  		}
			  	if(val.schedule_filed_on!=="0000-00-00")
                                    var schedule_filed_on = val.schedule_filed_on;
                                else
                                    schedule_filed_on = '';
                                if(val.date_of_schedule!=="0000-00-00")
                                    var date_of_schedule = val.date_of_schedule;
                                else
                                    date_of_schedule = '';
                                var t = $('#view_users_table').DataTable();
				t.row.add( [
		           count, // val.schedule_id,
                           date_of_schedule,
                           custom_cert_path_value,
                           status,
		           update_permission + view_single
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
            var check_permission = jQuery.inArray( "schedule_add", check_user_access );
            console.log(check_permission);
            if(check_permission < 1){
                    $('.hide_add_permission').remove();
            }
            else {
                    $('.hide_add_permission').show();
            }
        },2000)

    });