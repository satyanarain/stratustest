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
		var check_permission = jQuery.inArray("submittal_review_view_all", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
			$('.body-content .wrapper').show();
		}

		jQuery.ajax({
		url: baseUrl +project_id+"/submittal-review",
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
			    var specific_project_name = 'Submittal Review for Project: ' + window.project_name;
			   	console.log(specific_project_name);
			    $('#view_users_table').DataTable({
	                dom: 'Bfrtip',
	                buttons: [
	                    // {
	                    //     extend: 'copyHtml5',
	                    //     exportOptions: {
	                    //         columns: [ 0, 1, 2 ]
	                    //     },
	                    //     message: specific_project_name,
	                    // },
	                    {
	                        extend: 'csvHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'pdfHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'excelHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'print',
	                        exportOptions: {
	                            columns: [ 0, 1, 2 ]
	                        },
	                        message: specific_project_name,
	                    }
	                ]
	            });

		    $("#view_users_table_wrapper tbody tr").hide();
		    // Foreach Loop 
			jQuery.each( data.data, function( i, val ) {
				// Check Update Permission
				var check_permission = jQuery.inArray("submittal_review_update", check_user_access );
				console.log(check_permission);
				if(check_permission < 1){
					var update_permission = '';
				}
				else {
					var update_permission = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/submittal_review/'+val.sr_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
				}

				var status = val.sr_review_type;
				if(status == 'no_exception'){
		    	status = '<span class="label label-success">No Exception</span>';
			    }
			    else if(status == 'make_corrections_noted'){
		    	status = '<span class="label label-success">Make Corrections</span>';
			    }
			    else if(status == 'revise_resubmit'){
		    	status = '<span class="label label-success">Revise & Resubmit</span>';
			    }
			    else if(status == 'rejected'){
		    	status = '<span class="label label-danger">Rejected</span>';
			    }
			    else if(status == 'pending'){
			    	if(val.sub_review_type == 'yes'){
		    			status = '<span class="label label-danger">Pending - Expedite Review</span>';
			    	}
			    	else {
		    			status = '<span class="label label-warning">Pending</span>';
			    	}
			    }
			    else if(status == 'past_due'){
			    	if(val.sub_review_type == 'yes'){
		    			status = '<span class="label label-danger">Overdue - Expedite Review</span>';
			    	}
			    	else {
		    			status = '<span class="label label-danger">Past due</span>';
			    	}
			    }
			    else {
			    	status = '<span class="label label-danger">Past due</span>';
			    }

			    var sub_num = '';
				  if(val.sub_type == 'new'){
				  	sub_num = val.sub_number;
				  }
				  else {
				  	sub_num = val.sub_exist_parent+' R '+ val.sub_rev_number;
				  }
			  
			  var t = $('#view_users_table').DataTable();
				if(val.sub_review_type == 'yes'){
                                    var cus_css = '';
                                    if(val.sr_review_type=="pending")
                                        var cus_css = 'color:#F00;';
			  		t.row.add( [
			           //'<span style="color:#F00;">'+sub_num+'</span>',
                                   '<span style="'+cus_css+'">'+(i+1)+'</span>',
			           '<span style="'+cus_css+'">'+val.sub_description+'</span>',
			           status,
			           update_permission
			       	]).draw( false );
			  	}
			  	else {
		            t.row.add( [
			           //sub_num,
                                   i+1,
			           val.sub_description,
			           status,
			           update_permission
			       	]).draw( false );
			  	}
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

    });