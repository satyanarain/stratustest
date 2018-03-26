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
		var check_permission = jQuery.inArray( "bid_document_view_all", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
		}

		jQuery.ajax({
		url: baseUrl +project_id+"/bid-documents",
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
			    var specific_project_name = 'Bid Documents for Project: ' + window.project_name;
			   	console.log(specific_project_name);
			    $('#view_users_table').DataTable({
	                dom: 'Bfrtip',
	                buttons: [
	                    // {
	                    //     extend: 'copyHtml5',
	                    //     exportOptions: {
	                    //         columns: [ 0, 1, 2, 3, 4 ]
	                    //     },
	                    //     message: specific_project_name,
	                    // },
	                    {
	                        extend: 'csvHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'pdfHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'excelHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'print',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4 ]
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
				var check_permission = jQuery.inArray( "bid_document_update", check_user_access );
				console.log(check_permission);
				if(check_permission < 1){
					var update_permission = '';
				}
				else {
					var update_permission = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/bid_documents/'+val.bd_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
				}

				if(val.bd_status == 'active'){
		  			var single_view = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/bid_documents/'+val.bd_id+'" target="_blank" class="btn btn-info btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" style="margin-right:5px;"><i class="fa fa-search"></i></a>';
		  		}
		  		else {
		  			var single_view = '<a href="'+baseUrl+'404" target="_blank" class="btn btn-info btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" style="margin-right:5px;"><i class="fa fa-search"></i></a>';
		  		}

				var status = val.bd_status;
				if(status == 'active'){
		    	status = '<span class="label label-success">Active</span>';
			    }
			    else {
			    	status = '<span class="label label-danger">Inactive</span>';
			    }

				if(val.bd_bid_advertisement_date == null || val.bd_bid_advertisement_date=='0000-00-00'){
                                    var bid_advertisement_date = ''
			  	}
			  	else {
                                    var bid_advertisement_date = $.datepicker.formatDate('yy-mm-dd', new Date(val.bd_bid_advertisement_date.replace(' ', 'T')));
			  	}			  	

			  	if(val.bd_invite_date == null || val.bd_invite_date=='0000-00-00'){
                                    var invite_date = ''
			  	}
			  	else {
                                    var invite_date = $.datepicker.formatDate('yy-mm-dd', new Date(val.bd_invite_date.replace(' ', 'T')));
			  	}

			  	if(val.bd_date_of_opening == null){
			  		var date_of_opening = '-'
			  	}
			  	else {
                   var date_of_opening = $.datepicker.formatDate('yy-mm-dd', new Date(val.bd_date_of_opening.replace(' ', 'T')));
			  	}

			  	var t = $('#view_users_table').DataTable();
                                if(val.low_bidder_name)
                                    var low_bidder_name = val.low_bidder_name;
                                else
                                    var low_bidder_name = '';
				t.row.add( [
		           count,
		           bid_advertisement_date,
		           invite_date,
		           date_of_opening,
                           low_bidder_name,
		           status,
		           single_view + update_permission
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
			var check_permission = jQuery.inArray( "bid_document_add", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_add_permission').remove();
			}
			else {
				$('.hide_add_permission').show();
			}
		},2000)
    });