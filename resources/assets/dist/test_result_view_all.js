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
		var check_permission = jQuery.inArray( "test_result_view_all", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
		}

		jQuery.ajax({
		url: baseUrl +project_id+"/test_result",
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
			    var specific_project_name = 'Test Result for Project: ' + window.project_name;
			   	console.log(specific_project_name);
			    $('#view_users_table').DataTable({
	                dom: 'Bfrtip',
	                buttons: [
	                    // {
	                    //     extend: 'copyHtml5',
	                    //     exportOptions: {
	                    //         columns: [ 0, 1, 2, 3, 4, 6, 7, 8, 9, 11, 12, 13, 14, 15, 17 ]
	                    //     },
	                    //     message: specific_project_name,
	                    // },
	                    {
	                        extend: 'csvHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 6, 7, 8, 9, 11, 12, 13, 14, 15, 17 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'pdfHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 6, 7, 8, 9, 11, 12, 13, 14, 15, 17 ]
	                        },
	                        orientation: 'landscape',
                			pageSize: 'LEGAL',
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'excelHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 6, 7, 8, 9, 11, 12, 13, 14, 15, 17 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'print',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 6, 7, 8, 9, 11, 12, 13, 14, 15, 17 ]
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
				var check_permission = jQuery.inArray("test_result_update", check_user_access );
				console.log(check_permission);
				if(check_permission < 1){
					var update_permission = '';
				}
				else {
					var update_permission = '<a href="'+baseUrl+'dashboard/'+val.tr_project_id+'/test_result/'+val.tr_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
				}

				var status = val.tr_status;
				if(status == 'active'){
		    	status = '<span class="label label-success">Active</span>';
			    }
			    else {
			    	status = '<span class="label label-danger">Inactive</span>';
			    }
			  	
			  	var compaction_doc = val.compaction_doc;
			  	var compaction_doc_value;
			  	if(compaction_doc == null){
			  		compaction_doc_value = '<td></td>';
			  	}
			  	else {
			  		if(val.tr_status == 'active'){
			  			compaction_doc_value = '<td><a href="'+baseUrl+val.compaction_doc+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
			  		}
			  		else {
			  			compaction_doc_value = '<td><a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
			  		}
			  	}

			  	var strength_doc = val.strength_doc;
			  	var strength_doc_value;
			  	if(strength_doc == null){
			  		strength_doc_value = '<td></td>';
			  	}
			  	else {
			  		if(val.tr_status == 'active'){
			  			strength_doc_value = '<td><a href="'+baseUrl+val.strength_doc+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
			  		}
			  		else {
			  			strength_doc_value = '<td><a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
			  		}
			  	}

			  	var etc_doc_id = val.etc_doc_id;
			  	var etc_doc_id_value;
			  	if(etc_doc_id == null){
			  		etc_doc_id_value = '<td></td>';
			  	}
			  	else {
			  		if(val.tr_status == 'active'){
			  			etc_doc_id_value = '<td><a href="'+baseUrl+val.etc_doc_id+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
			  		}
			  		else {
			  			etc_doc_id_value = '<td><a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
			  		}
			  	}

			  	if(val.tr_compaction_date == '0000-00-00'){
			  		var compaction_date = ' ';
			  	}
			  	else {
			  		var compaction_date = val.tr_compaction_date; //$.datepicker.formatDate('yy-mm-dd', new Date(val.tr_compaction_date.replace(' ', 'T')));
			  	}

			  	if(val.tr_strength_date == '0000-00-00'){
			  		var strength_date = ' ';
			  	}
			  	else {
			  		var strength_date = val.tr_strength_date; // $.datepicker.formatDate('yy-mm-dd', new Date(val.tr_strength_date.replace(' ', 'T')));
			  	}

			  	if(val.tr_etc_date == '0000-00-00'){
			  		var etc_date = ' ';
			  	}
			  	else {
			  		var etc_date = val.tr_etc_date; // $.datepicker.formatDate('yy-mm-dd', new Date(val.tr_etc_date.replace(' ', 'T')));
			  	}

			  	if(val.tr_compaction_test_num == 0){
			  		var tr_compaction_test_num = ''
			  	}
			  	else {
			  		var tr_compaction_test_num = val.tr_compaction_test_num;
			  	}

			  	if(val.tr_strength_test_num == 0){
			  		var tr_strength_test_num = ''
			  	}
			  	else {
			  		var tr_strength_test_num = val.tr_strength_test_num;
			  	}

			  	if(val.tr_etc_test_num == 0){
			  		var tr_etc_test_num = ''
			  	}
			  	else {
			  		var tr_etc_test_num = val.tr_etc_test_num;
			  	}

			  var t = $('#view_users_table').DataTable();
				t.row.add( [
		           count, // val.tr_id,
		           val.compaction_firm,
		           compaction_date,
		           tr_compaction_test_num,
		           val.tr_compaction_location,
		           compaction_doc_value,
		           val.strength_firm,
		           strength_date,
		           tr_strength_test_num,
		           val.tr_strength_location,
		           strength_doc_value,
		           val.tr_etc_test_name,
		           val.tr_etc_firm,
		           etc_date,
		           tr_etc_test_num,
		           val.tr_etc_location,
		           etc_doc_id_value,
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
			var check_permission = jQuery.inArray( "test_result_add", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_add_permission').remove();
			}
			else {
				$('.hide_add_permission').show();
			}
		},2000)


    });