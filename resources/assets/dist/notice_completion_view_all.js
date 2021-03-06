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
		var check_permission = jQuery.inArray( "notice_completion_view_all", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
			$('.body-content .wrapper').show();
		}

		jQuery.ajax({
		url: baseUrl +project_id+"/notice-completion",
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
		    var specific_project_name = 'Notice of Completion for Project: ' + window.project_name;
		   	console.log(specific_project_name);
		    $('#view_users_table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    // {
                    //     extend: 'copyHtml5',
                    //     exportOptions: {
                    //         columns: [ 0, 2 ]
                    //     },
                    //     message: specific_project_name,
                    // },
                    {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 2 ]
                        },
                        message: specific_project_name,
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 2 ]
                        },
                        message: specific_project_name,
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 2 ]
                        },
                        message: specific_project_name,
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 0, 2 ]
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
				var check_permission = jQuery.inArray("notice_completion_update", check_user_access );
				console.log(check_permission);
				if(check_permission < 1){
					var update_permission = '';
				}
				else {
					var update_permission = '<a href="'+baseUrl+'dashboard/'+val.noc_project_id+'/notice_completion/'+val.noc_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
				}

				var status = val.noc_status;
				if(status == 'active'){
                                    status = '<span class="label label-success">Pending</span>';
                                }
                                else {
                                    status = '<span class="label label-danger">Complete</span>';
                                }
			  	
			  	var noc_path = val.doc_path;
			  	var noc_path_value;
			  	if(noc_path == null){
			  		noc_path_value = '-';
			  	}
			  	else {
			  		//if(val.noc_status == 'active'){
			  			noc_path_value = '<a href="'+baseUrl+val.doc_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
//			  		}
//			  		else {
//			  			noc_path_value = '<td><a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
//			  		}
			  	}
                                var recorded_doc = val.recorded_doc;
			  	var recorded_doc_value;
			  	if(recorded_doc == null){
			  		recorded_doc_value = '-';
			  	}
			  	else {
        	  			recorded_doc_value = '<a href="'+baseUrl+val.recorded_doc+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
        		  	}
                            if(val.date_noc_filed!='' && val.date_noc_filed!='0000-00-00')    
                                var date_noc_filed = val.date_noc_filed;
                            else
                                var date_noc_filed = '';
                            
                            if(val.project_completion_date!='' && val.project_completion_date!='0000-00-00')    
                                var project_completion_date = val.project_completion_date;
                            else
                                var project_completion_date = '';
                            if(val.date_signed!='' && val.date_signed!='0000-00-00')    
                                var date_signed = val.date_signed;
                            else
                                var date_signed = '';
                            if(val.date_recorded!='' && val.date_recorded!='0000-00-00')    
                                var date_recorded = val.date_recorded;
                            else
                                var date_recorded = '';
                            
			  var t = $('#view_users_table').DataTable();
				t.row.add( [
		           count, // val.noc_id,
                           val.improvement_type,
                           //date_noc_filed,
                           project_completion_date,
		           date_signed,
                           noc_path_value,
                           date_recorded,
                           recorded_doc_value,
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
			var check_permission = jQuery.inArray( "notice_completion_add", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_add_permission').remove();
			}
			else {
				$('.hide_add_permission').show();
			}
		},2000)


    });