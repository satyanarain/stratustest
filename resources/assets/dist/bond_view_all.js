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
		var check_permission = jQuery.inArray( "bond_view_all", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
		}


		jQuery.ajax({
		url: baseUrl +project_id+"/bond",
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
			    var specific_project_name = 'Bonds for Project: ' + window.project_name;
			   	console.log(specific_project_name);
			    $('#view_users_table').DataTable({
	                dom: 'Bfrtip',
	                buttons: [
	                    // {
	                    //     extend: 'copyHtml5',
	                    //     exportOptions: {
	                    //         columns: [ 0, 1, 2, 3, 4, 6, 7, 8, 10, 11, 12, 14 ]
	                    //     },
	                    //     message: specific_project_name,
	                    // },
	                    {
	                        extend: 'csvHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 6, 7, 8, 10, 11, 12, 14 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'pdfHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 6, 7, 8, 10, 11, 12, 14 ]
	                        },
	                        orientation: 'landscape',
                			pageSize: 'LEGAL',
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'excelHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 6, 7, 8, 10, 11, 12, 14 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'print',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 6, 7, 8, 10, 11, 12, 14 ]
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
				var check_permission = jQuery.inArray("bond_update", check_user_access );
				console.log(check_permission);
				if(check_permission < 1){
					var update_permission = '';
				}
				else {
					var update_permission = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/bond/'+val.pb_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
				}

				var status = val.status;
				if(status == 'active'){
		    	status = '<span class="label label-success">Active</span>';
			    }
			    else {
			    	status = '<span class="label label-danger">Inactive</span>';
			    }
			  	
			  	var performance_bond_path = val.performance_bond_path;
			  	var performance_bond_path_value;
			  	if(performance_bond_path == null){
			  		performance_bond_path_value = '<td></td>';
			  	}
			  	else {
			  		if(val.status == 'active'){
			  			performance_bond_path_value = '<td><a href="'+baseUrl+val.performance_bond_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
			  		}
			  		else {
			  			performance_bond_path_value = '<td><a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
			  		}
			  	}

			  	var payment_bond_path = val.payment_bond_path;
			  	var payment_bond_path_value;
			  	if(payment_bond_path == null){
			  		payment_bond_path_value = '<td></td>';
			  	}
			  	else {
			  		if(val.status == 'active'){
			  			payment_bond_path_value = '<td><a href="'+baseUrl+val.payment_bond_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
			  		}
			  		else {
			  			payment_bond_path_value = '<td><a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
			  		}
			  	}

			  	var maintenance_bond_path = val.maintenance_bond_path;
			  	var maintenance_bond_path_value;
			  	if(maintenance_bond_path == null){
			  		maintenance_bond_path_value = '<td></td>';
			  	}
			  	else {
			  		if(val.status == 'active'){
			  			maintenance_bond_path_value = '<td><a href="'+baseUrl+val.maintenance_bond_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
			  		}
			  		else {
			  			maintenance_bond_path_value = '<td><a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
			  		} 
			  	}

			  	var performance_bond_date = val.performance_bond_date;
			  	if(val.performance_bond_date == '0000-00-00' || val.performance_bond_date == null){
			  		var performance_bond_date = ' --- '; 
			  	}

			  	var payment_bond_date = val.payment_bond_date;
			  	if(payment_bond_date == '0000-00-00' || payment_bond_date == null){
			  		payment_bond_date = ' --- '; 
			  	}
			  	var maintenance_bond_date = val.maintenance_bond_date;
			  	if(maintenance_bond_date == '0000-00-00' || maintenance_bond_date == null){
			  		maintenance_bond_date = ' --- '; 
			  	}
			  var t = $('#view_users_table').DataTable();
				t.row.add( [
		           count,
		           val.agency_name,
		           val.performance_bond_currency+' '+ ReplaceNumberWithCommas(val.performance_bond_amount),
                   performance_bond_date,
		           val.performance_bond_number,
		           performance_bond_path_value,
		           val.payment_bond_currency+' '+ ReplaceNumberWithCommas(val.payment_bond_amount),
                   payment_bond_date,
		           val.payment_bond_number,
		           payment_bond_path_value,
		           val.maintenance_bond_currency+' '+ ReplaceNumberWithCommas(val.maintenance_bond_amount),
                   maintenance_bond_date,
		           val.maintenance_bond_number,
		           maintenance_bond_path_value,
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
			var check_permission = jQuery.inArray( "bond_add", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_add_permission').remove();
			}
			else {
				$('.hide_add_permission').show();
			}

			// Check Update Permission
			var check_permission = jQuery.inArray( "bond_update", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_update_permission').remove();
			}
			else {
				$('.hide_update_permission').show();
			}
		},2000)


    });