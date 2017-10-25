  	$(document).ready(function() { 
     	// Get login user profile data
     	$("#view_users_table").hide();
		var role = localStorage.getItem('u_role');
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		project_id = url[ url.length - 2 ]; // projects
		// console.log(project_id);
		type = url[ url.length - 1 ]; // projects
		// console.log(type);

		// Check View All Permission
		var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
		var check_permission = jQuery.inArray( "geotechnical_view_all", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
		}

		jQuery.ajax({
		url: baseUrl +project_id+"/geo-report",
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
			    var specific_project_name = 'Geotechnical Reports for Project: ' + window.project_name;
			   	console.log(specific_project_name);
			    $('#view_users_table').DataTable({
	                dom: 'Bfrtip',
	                buttons: [
	                    // {
	                    //     extend: 'copyHtml5',
	                    //     exportOptions: {
	                    //         columns: [ 0, 1, 2, 3, 4, 6 ]
	                    //     },
	                    //     message: specific_project_name,
	                    // },
	                    {
	                        extend: 'csvHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 6 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'pdfHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 6 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'excelHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 6 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'print',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 6 ]
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
				var check_permission = jQuery.inArray("geotechnical_update", check_user_access );
				console.log(check_permission);
				if(check_permission < 1){
					var update_permission = '';
				}
				else {
					var update_permission = '<a href="'+baseUrl+'dashboard/'+val.geo_project_id+'/geo_reports/'+val.geo_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
				}

				var status = val.geo_status;
				if(status == "active"){
		    	status = '<span class="label label-success">Active</span>';
			    }
			    else {
			    	status = '<span class="label label-danger">Inactive</span>';
			    }
			  	
			  	var doc_path = val.doc_path;
			  	var doc_path_value;
			  	if(doc_path == null){
			  		doc_path_value = '<td></td>';
			  	}
			  	else {
			  		if(val.geo_status == 'active'){
			  			doc_path_value = '<td><a href="'+baseUrl+val.doc_path+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" alt="'+val.geo_name+'" width="40" /></a></td>';
			  		}
			  		else {
			  			doc_path_value = '<td><a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
			  		}
			  	}

			  	if(val.geo_application == 'yes'){
			  		var geo_application = 'Yes';
			  	}
			  	else {
			  		var geo_application = 'No';
			  	}

			  // $("#view_users_table_wrapper tbody").append(
			  // 	'<tr><td>'+val.geo_id+'</td>'+
			  // 	'<td>'+val.geo_name_of_report+'</td>'+
			  // 	'<td>'+val.geo_date_of_report+'</td>'+
			  // 	'<td>'+val.agency_name+'</td>'+
			  // 	'<td>'+val.geo_application+'</td>'+
			  // 	doc_path_value+
			  // 	'<td>'+status+
			  // 	'</td>'+
     //            '<td>'+
     //                // '<a href="'+baseUrl+'dashboard/users/'+val.pt_id+'" class="btn btn-success btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a>'+
     //                '<a href="'+baseUrl+'dashboard/'+val.geo_project_id+'/geo_reports/'+val.geo_id+'/update" class="btn btn-primary btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>'+
     //                // '<a href="" id="'+val.pt_id+'" class="btn btn-danger btn-xs tooltips user_suspend" data-placement="top" data-toggle="tooltip" data-original-title="Suspend"><i class="fa fa-times"></i></a>'+
     //            '</td></tr>'
			  // );

			  var t = $('#view_users_table').DataTable();
				t.row.add( [
		           count,
		           val.geo_name_of_report,
                   $.datepicker.formatDate('yy-mm-dd', new Date(val.geo_date_of_report.replace(' ', 'T'))),
		           val.agency_name,
		           geo_application,
		           doc_path_value,
		           status,
		           update_permission
		       	]).draw( false );
				count++;
			});
		    // $( "h2" ).appendTo( $( ".container" ) );
		   
		    $("#view_users_table").show();
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
		    	$("#view_users_table").show();
		    	$(".loading_data").hide();
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		}); 

		setTimeout(function()
        {
			// Check Add Permission
			var check_permission = jQuery.inArray( "geotechnical_add", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_add_permission').remove();
			}
			else {
				$('.hide_add_permission').show();
			}
		},2000)
    });