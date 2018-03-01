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
		var check_permission = jQuery.inArray("submittal_view_all", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
			$('.body-content .wrapper').show();
		}

		jQuery.ajax({
		url: baseUrl +project_id+"/submittal",
		    type: "GET",
		    headers: {
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		}) 
		    .done(function(data, textStatus, jqXHR) {
		    // console.log(data.data);
		    $('#project_name_title').text(data.data[0].p_number+' - '+data.data[0].p_name)
		    window.project_name = data.data[0].p_name;
		    	$('#project_name_title').text("Project: " + window.project_name);
			    var specific_project_name = 'Submittal for Project: ' + window.project_name;
			   	console.log(specific_project_name);
			    $('#view_users_table').DataTable({
	                dom: 'Bfrtip',
	                buttons: [
	                    // {
	                    //     extend: 'copyHtml5',
	                    //     exportOptions: {
	                    //         columns: [ 0, 1, 2, 4]
	                    //     },
	                    //     message: specific_project_name,
	                    // },
	                    {
	                        extend: 'csvHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 4]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'pdfHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 4]
	                        },
	                      	orientation: 'landscape',
                			pageSize: 'LEGAL',
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'excelHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 4]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'print',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 4]
	                        },
	                      	message: specific_project_name,
	                    }
	                ]
	            });

		    $("#view_users_table_wrapper tbody tr").hide();
		    // Foreach Loop 
			jQuery.each( data.data, function( i, val ) {
				
				// Check Update Permission
				var check_permission = jQuery.inArray("submittal_update", check_user_access );
				// console.log(check_permission);
				if(check_permission < 1){
					var update_permission = '';
				}
				else {
					var update_permission = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/submittals/'+val.sub_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
				}

				var status = val.sub_status;
				if(status == 'active'){
		    	status = '<span class="label label-success">Active</span>';
			    }
			    else {
			    	status = '<span class="label label-danger">Inactive</span>';
			    }
			  	
			  	var submittal_path = val.submittal_path;
			  	 console.log(submittal_path);
			  	var sub_additional_path_value;
			  	if(submittal_path == null){
			  		sub_additional_path_value = '-';
			  	}
			  	else {
			  		if(val.sub_status == 'active'){
			  			sub_additional_path_value = '<a href="'+baseUrl+val.submittal_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  		else {
			  			sub_additional_path_value = '<a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  	}

			  	if(val.sub_status == 'active'){
		  			var single_view = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/submittals/'+val.sub_id+'" class="btn btn-info btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" style="margin-right:5px;"><i class="fa fa-search"></i></a>';
		  		}
		  		else {
		  			var single_view = '<a href="'+baseUrl+'404" class="btn btn-info btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" style="margin-right:5px;"><i class="fa fa-search"></i></a>';
		  		}
			  	

			  var sub_num = '';
			  if(val.sub_type == 'new'){
			  	sub_num = val.sub_number;
			  }
			  else {
			  	sub_num = val.sub_exist_parent+' R '+ val.sub_rev_number;
			  }

			  // var date = new Date(val.sub_date.replace(' ', 'T'));
     //          var formatDate = $.datepicker.formatDate('yy-mm-dd', date);
			  // var day = date.getDate();
			  // var month = date.getMonth();
			  // var year = date.getFullYear();

			  //var date_format = year + '-' + month + '-' + day;
			  var date_format = val.sub_date; // formatDate;

			  var t = $('#view_users_table').DataTable();
				if(val.sub_review_type == 'yes'){
                                    var cus_css = '';
                                    if(val.sr_review_type=="pending")
                                        var cus_css = 'color:#F00;';
			  		t.row.add( [
			           // val.sub_id,
                                   //i+1,
			           '<span style="'+cus_css+'">'+sub_num+'</span>',
                                   '<span style="'+cus_css+'">'+date_format+'</span>',
			           '<span style="'+cus_css+'">'+val.sub_description+'</span>',
			           sub_additional_path_value,
			           status,
			           single_view + update_permission
			       	]).draw( false );
			  	}
			  	else {
					t.row.add( [
			           // val.sub_id,
                                   //i+1,
			           sub_num,
			           date_format,
			           val.sub_description,
			           sub_additional_path_value,
			           status,
			           single_view + update_permission
			       	]).draw( false );
			  	}
//                                t.on( 'order.dt search.dt', function () {
//                                t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
//                                    cell.innerHTML = i+1;
//                                } );
//                            } ).draw();
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
			var check_permission = jQuery.inArray( "submittal_add", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_add_permission').remove();
			}
			else {
				$('.hide_add_permission').show();
			}
		},3000)

    });