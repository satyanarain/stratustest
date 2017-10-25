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
		var check_permission = jQuery.inArray( "contract_view_all", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
		}

		    jQuery.ajax({
			url: baseUrl +project_id+"/contract",
			    type: "GET",
			    headers: {
			      "x-access-token": token
			    },
			    contentType: "application/json",
			    cache: false
			})
			.done(function(data, textStatus, jqXHR) {
			    // console.log(data.data[0].p_id);
	    	  	window.project_name = data.data[0].p_name;
	    	  	$('#project_name_title').text("Project: " + window.project_name);
			    var specific_project_name = 'Contract for Project: ' + window.project_name;
			   	console.log(specific_project_name);
			    $('#view_users_table').DataTable({
	                dom: 'Bfrtip',
	                buttons: [
	                    // {
	                    //     extend: 'copyHtml5',
	                    //     exportOptions: {
	                    //         columns: [ 0, 1, 2, 3, 5 ]
	                    //     },
	                    //     message: specific_project_name,
	                    // },
	                    {
	                        extend: 'csvHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 5 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'pdfHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 5 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'excelHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 5 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'print',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 5 ]
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
					var check_permission = jQuery.inArray("contract_update", check_user_access );
					// console.log(check_permission);
					if(check_permission < 1){
						var update_permission = '';
					}
					else {
						var update_permission = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/contract/'+val.con_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
					}

					var status = val.con_status;
					if(status == 'active'){
			    	status = '<span class="label label-success">Active</span>';
				    }
				    else {
				    	status = '<span class="label label-danger">Inactive</span>';
				    }
				  	
				  	var contract_path = val.contract_path;
				  	var con_contract_path_value;
				  	if(contract_path == null){
				  		con_contract_path_value = '-';
				  	}
				  	else {
				  		if(val.con_status == 'active'){
				  			con_contract_path_value = '<a href="'+baseUrl+val.contract_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
				  		}
				  		else {
				  			con_contract_path_value = '<td><a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
				  		}
				  	}

				  var t = $('#view_users_table').DataTable();
					t.row.add([
			           count,
			           val.f_name,
			           val.currency_symbol+' '+ ReplaceNumberWithCommas(val.con_contract_amount),
	                   val.con_contract_date,
			           con_contract_path_value,
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
			var check_permission = jQuery.inArray( "contract_add", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_add_permission').remove();
			}
			else {
				$('.hide_add_permission').show();
			}
		},2000)


    });