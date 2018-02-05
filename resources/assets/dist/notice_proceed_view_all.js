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
		var check_permission = jQuery.inArray( "notice_proceed_view_all", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
		}

		jQuery.ajax({
		url: baseUrl +project_id+"/notice-proceed",
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
			    var specific_project_name = 'Notice to Proceed for Project: ' + window.project_name;
			   	console.log(specific_project_name);
			    $('#view_users_table').DataTable({
	                dom: 'Bfrtip',
	                buttons: [
	                    // {
	                    //     extend: 'copyHtml5',
	                    //     exportOptions: {
	                    //         columns: [ 0, 1, 2, 3, 4, 5, 6, 8 ]
	                    //     },
	                    //     message: specific_project_name,
	                    // },
	                    {
	                        extend: 'csvHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 5, 6, 8 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'pdfHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 5, 6, 8 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'excelHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 5, 6, 8 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'print',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 5, 6, 8 ]
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
				var check_permission = jQuery.inArray("notice_proceed_update", check_user_access );
				console.log(check_permission);
				if(check_permission < 1){
					var update_permission = '';
				}
				else {
					var update_permission = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/notice_proceed/'+val.pnp_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
				}

				var status = val.pnp_status;
				if(status == 'active'){
		    	status = '<span class="label label-success">Active</span>';
			    }
			    else {
			    	status = '<span class="label label-danger">Inactive</span>';
			    }
			  	
			  	var pnp_path = val.notice_proceed_path;
			  	var pnp_path_value;
			  	if(pnp_path == null){
			  		pnp_path_value = '-';
			  	}
			  	else {
			  		if(val.pnp_status == 'active'){
			  			pnp_path_value = '<a href="'+baseUrl+val.notice_proceed_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  		else {
			  			pnp_path_value = '<a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  	}

			  	var pnp_cal_day = val.pnp_cal_day;
			  	var pnp_cal_day_value;
			  	if(pnp_cal_day == 'calendar_day' && val.pnp_type=="new"){
			  		pnp_cal_day_value = 'Calendar Days';
			  	}
			  	else if(pnp_cal_day == 'working_day' && val.pnp_type=="new"){
			  		pnp_cal_day_value = 'Working Days';
			  	}
			  	else {
			  		pnp_cal_day_value = ' ';
			  	}


			  	if(val.pnp_type == "exist"){
			  		var pnp_date = ' ';
			  		var pnp_start_date = ' ';
			  		var liquidated_amount = " ";
			  		var pnp_duration = " ";
			  	}
			  	else {
			  		var pnp_date = val.pnp_date;
			  		var pnp_start_date = val.pnp_start_date;
			  		var liquidated_amount = val.currency_symbol+' '+ ReplaceNumberWithCommas(val.pnp_liquidated_amount);
			  		var pnp_duration = val.pnp_duration;
			  	}

			  var t = $('#view_users_table').DataTable();
				t.row.add( [
		           count,
		           val.contractor_name,
                   pnp_date,
                   pnp_start_date,
		           pnp_duration,
		           pnp_cal_day_value,
		           liquidated_amount,
		           pnp_path_value,
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

  		// Get Project Currency
	    // jQuery.ajax({
	    // url: baseUrl+project_id+"/project_setting_get/project_currency",
	    //     type: "GET",
	    //     headers: {
	    //       "x-access-token": token
	    //     },
	    //     contentType: "application/json",
	    //     cache: false
	    // })
	    // .done(function(data, textStatus, jqXHR) {
	    //     // console.log(data.data.pset_meta_value);
	    //     var project_currency_id = data.data.pset_meta_value;
	    //     jQuery.ajax({
	    //     url: baseUrl + "currency/"+project_currency_id,
	    //         type: "GET",
	    //         headers: {
	    //           "Content-Type": "application/json",
	    //           "x-access-token": token
	    //         },
	    //         contentType: "application/json",
	    //         cache: false
	    //     })
	    //     .done(function(data, textStatus, jqXHR) {
	    //         console.log(data.data.cur_symbol);
	    //         window.symbol = data.data.cur_symbol;
	    //         $('.project_currency').text(data.data.cur_symbol+' ');
	    //     })
	    // })

		setTimeout(function()
        {
			// Check Add Permission
			var check_permission = jQuery.inArray( "notice_proceed_add", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_add_permission').remove();
			}
			else {
				$('.hide_add_permission').show();
			}
		},2000)

    });