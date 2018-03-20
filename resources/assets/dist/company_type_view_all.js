  	$(document).ready(function() { 
     	// Get login user profile data
     	$("#view_users_table_wrapper").hide();
		var role = localStorage.getItem('u_role');
		var token = localStorage.getItem('u_token');
		jQuery.ajax({
		url: baseUrl + "company-type",
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
                    //         columns: [ 0, 1, 2 ]
                    //     },
                    //     message: specific_project_name,
                    // },
                    {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2 ]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2 ]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2 ]
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 0, 1, 2 ]
                        }
                    }
                ]
            });


		    $("#view_users_table_wrapper tbody tr").hide();
		    // Foreach Loop 
		    var count = 1;
			jQuery.each( data.data, function( i, val ) {
				var status = val.ct_status;
				if(status == 'active'){
		    	status = '<span class="label label-success">Activate</span>';
			    }
			    else {
			    	status = '<span class="label label-danger">Deactivate</span>';
			    }
			  
			  var t = $('#view_users_table').DataTable();
			  if(role == 'owner'){
			  	t.row.add( [
		           count,// val.ct_id,
		           val.ct_name,
		           status,
		           '<a href="'+baseUrl+'dashboard/company_type/'+val.ct_id+'/update" class="btn btn-primary btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>',
		       	]).draw( false );
		       	count++;
			  }else {
				t.row.add( [
		           count, // val.ct_id,
		           val.ct_name,
		           val.user_name,
		           status,
		           '<a href="'+baseUrl+'dashboard/company_type/'+val.ct_id+'/update" class="btn btn-primary btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>',
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