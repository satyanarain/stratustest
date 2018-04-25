  	$(document).ready(function() { 
     	// Get login user profile data
     	// $("#view_users_table_wrapper").hide();
		var role = localStorage.getItem('u_role');
		var token = localStorage.getItem('u_token');
		jQuery.ajax({
		url: baseUrl + "projects",
		    type: "GET",
		    headers: {
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
		    .done(function(data, textStatus, jqXHR) {
		    // console.log(data.data);
		    $('#view_users_table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    // {
                    //     extend: 'copyHtml5',
                    //     message: specific_project_name,
                    // },
                    {
                        extend: 'csvHtml5',
                        message: specific_project_name,
                    },
                    {
                        extend: 'pdfHtml5',
                        message: specific_project_name,
                    },
                    {
                        extend: 'excelHtml5',
                        message: specific_project_name,
                    },
                    {
                        extend: 'print',
                        message: specific_project_name,
                    }
                ]
            });

		    // $("#view_users_table_wrapper tbody tr").hide();
		    // Foreach Loop 
		    var count = 1;
			jQuery.each( data.data, function( i, val ) {
				var status = val.p_status;
				if(status == 'active'){
		    	status = '<span class="label label-success">Active</span>';
			    }
			    else {
			    	status = '<span class="label label-danger">Inactive</span>';
			    }
			    
			    var t = $('#view_users_table').DataTable();
				t.row.add( [
		           count, // val.p_id,
		           val.p_number,
		           '<a href="'+baseUrl+'dashboard/'+val.p_id+'/project">'+val.p_name+'</a>',
		           val.p_location,
		           // val.p_type,
		           val.p_description,
		           status,
		           '<div class="progress progress-striped active progress-sm m-b-20">'+
                        '<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">'+
                            '<span class="sr-only">75% Complete</span>'+
                        '</div>'+
                    '</div>',
		           '<a href="'+baseUrl+'dashboard/projects/'+val.p_id+'/update" class="btn btn-primary btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>',
		       	]).draw( false );
				count++;
			  // $("#view_users_table_wrapper tbody").append(
			  // 	'<tr><td>'+val.p_id+'</td>'+
			  // 	'<td>'+val.p_number+'</td>'+
			  // 	'<td><a href="'+baseUrl+'dashboard/'+val.p_id+'/project">'+val.p_name+'</a></td>'+
			  // 	'<td>'+val.p_location+'</td>'+
			  // 	'<td>'+val.pt_name+'</td>'+
			  // 	'<td>'+val.p_description+'</td>'+
			  // 	'<td>'+status+
			  // 	'</td>'+
			  // 	'<td><div class="progress progress-striped active progress-sm m-b-20">'+
     //                    '<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">'+
     //                        '<span class="sr-only">75% Complete</span>'+
     //                    '</div>'+
     //                '</div></td>'+
     //            '<td>'+
     //                // '<a href="'+baseUrl+'dashboard/users/'+val.pt_id+'" class="btn btn-success btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a>'+
     //                '<a href="'+baseUrl+'dashboard/projects/'+val.p_id+'/update" class="btn btn-primary btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>'+
     //                // '<a href="" id="'+val.pt_id+'" class="btn btn-danger btn-xs tooltips user_suspend" data-placement="top" data-toggle="tooltip" data-original-title="Suspend"><i class="fa fa-times"></i></a>'+
     //            '</td></tr>'
			  // );
			});
		    // $( "h2" ).appendTo( $( ".container" ) );
		   
		    // $("#view_users_table_wrapper").show();
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