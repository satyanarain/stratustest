  	$(document).ready(function() { 
     	// Get login user profile data
     	$("#view_users_table_wrapper").hide();
		var role = localStorage.getItem('u_role');
		var token = localStorage.getItem('u_token');
		jQuery.ajax({
		url: baseUrl + "firm-name",
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
                    //     exportOptions: {
                    //         columns: [ 0, 1, 2, 3, 4, 5 ]
                    //     },
                    //     message: specific_project_name,
                    // },
                    {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5 ]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5 ]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5 ]
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5 ]
                        }
                    }
                ]
            });
		    $("#view_users_table_wrapper tbody tr").hide();
		    // Foreach Loop 
		    var count = 1;
			jQuery.each( data.data, function( i, val ) {
				var status = val.f_status;
				if(status == 'active'){
		    	status = '<span class="label label-success">Activate</span>';
			    }
			    else {
			    	status = '<span class="label label-danger">Deactivate</span>';
			    }
			  // $("#view_users_table_wrapper tbody").append(
			  // 	'<tr><td>'+val.f_id+'</td>'+
			  // 	'<td>'+val.f_name+'</td>'+
			  // 	'<td>'+val.f_detail+'</td>'+
			  // 	'<td>'+status+
			  // 	'</td>'+
     //            '<td>'+
     //                // '<a href="'+baseUrl+'dashboard/users/'+val.pt_id+'" class="btn btn-success btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a>'+
     //                '<a href="'+baseUrl+'dashboard/firms/'+val.f_id+'/update" class="btn btn-primary btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>'+
     //                // '<a href="" id="'+val.pt_id+'" class="btn btn-danger btn-xs tooltips user_suspend" data-placement="top" data-toggle="tooltip" data-original-title="Suspend"><i class="fa fa-times"></i></a>'+
     //            '</td></tr>'
			  // );

			  // var f_type = val.company_name; 
			  //   if(f_type == null){
			  //   	f_type = '-';
			  //   }
			  //   else {
			  //   	f_type = f_type;
			  //   }

			  var t = $('#view_users_table').DataTable();
			  if(role == 'owner'){
			  	t.row.add( [
		           count, // val.f_id,
		           val.f_name,
		           val.f_detail,
		           val.f_address,
		           val.company_name,
		           status,
		           '<a href="'+baseUrl+'dashboard/firms/'+val.f_id+'/update" class="btn btn-primary btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>'
		       	]).draw( false );
		       	count++;
			  }else {
				t.row.add( [
		           count, // val.f_id,
		           val.f_name,
		           val.f_detail,
		           val.f_address,
		           val.company_name,
		           val.user_name,
		           status,
		           '<a href="'+baseUrl+'dashboard/firms/'+val.f_id+'/update" class="btn btn-primary btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>'
		       	]).draw( false );
		       	count++;
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