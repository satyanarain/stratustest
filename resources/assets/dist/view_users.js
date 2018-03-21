  	$(document).ready(function() { 
     	// Get login user profile data
     	$("#view_users_table_wrapper").hide();
		var role = localStorage.getItem('u_role');
		var token = localStorage.getItem('u_token');
		jQuery.ajax({
		url: baseUrl + "users",
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
                    //         columns: [ 0, 1, 2, 3, 4 ]
                    //     },
                    //     message: specific_project_name,
                    // },
                    {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ]
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ]
                        }
                    }
                ]
            });

		    $("#view_users_table_wrapper tbody tr").hide();
		    // Foreach Loop 
			jQuery.each( data.data, function( i, val ) {
				var account_status = val.status;
				if(account_status == 1){
		    	account_status = '<span class="label label-success">Verified</span>';
			    } 
			    else if(account_status == 0){
			    	account_status = '<span class="label label-warning">Unverified</span>';
			    }
			    else {
			    	account_status = '<span class="label label-danger">Disabled</span>';
			    }

			   
			    var position_title = val.position_title; 
			    if(position_title == null){
			    	position_title = '-';
			    }
			    else {
			    	position_title = position_title;
			    }
                        $('#view_users_table').DataTable().destroy();
                        var t = $('#view_users_table').DataTable({"ordering":false});

			  if(val.id == '1'){
			  	var action_btn = '<a href="'+baseUrl+'dashboard/users/'+val.id+'" class="btn btn-success btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a>'+'<a href="'+baseUrl+'dashboard/users/'+val.id+'/update" class="btn btn-primary btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
			  }
			  else {
			  	var action_btn = '<a href="'+baseUrl+'dashboard/users/'+val.id+'" class="btn btn-success btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a>'+'<a href="'+baseUrl+'dashboard/users/'+val.id+'/update" class="btn btn-primary btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>'+'<a href="" id="'+val.id+'" class="btn btn-danger btn-xs tooltips user_suspend" data-placement="top" data-toggle="tooltip" data-original-title="Suspend"><i class="fa fa-times"></i></a>';
			  }
                          console.log('kunal--'+val.email);
                          if(val.user_image_path!="")
                              var user_image_path = '<img src="'+baseUrl+val.user_image_path+'" width="50px">';
                          else
                              var user_image_path = ' ';
			  if(role == 'owner'){
			  	t.row.add( [
		           val.username,
                           user_image_path,
		           val.first_name+' '+val.last_name,
		           val.email,
		           val.agency_name,
		           position_title,
		           // val.role,
		           account_status,
		           // val.user_parent,
		           action_btn
		       	]).draw( false );
			  }else {
				t.row.add( [
		           val.username,
                           user_image_path,
		           val.first_name+' '+val.last_name,
		           val.email,
		           val.agency_name,
		           position_title,
		           val.role,
		           account_status,
		           val.user_parent,
		           action_btn
		       	]).draw( false );
			  }	 
			});
		    // $( "h2" ).appendTo( $( ".container" ) );
		   
		    $(".loading_data").hide();
		    $("#view_users_table_wrapper").show();
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
		    console.log("HTTP Request Failed");
		    var response = jqXHR.responseJSON.code;
		    console.log(response);
		    if(response == 403){
		    	console.log('user 403');
		    	window.location.href = baseUrl + "403";
		    }
		    else if(response == 404){
		    	console.log('user 404');
		    	// window.location.href = baseUrl + "404";
		    }
		    else {
		    	console.log('user 500');
		    	window.location.href = baseUrl + "500";
		    }
		}); 

		setTimeout(function(){
		  	$(".user_suspend").on('click', function(e){
		    	e.preventDefault();
		        var id = $(this).attr("id");
		        // alert(id);
		        
		        var r = confirm("Are you sure to suspend this user?");
				if (r == true) {
				    jQuery.ajax({
					url: baseUrl + "users/"+id+"/suspend",
					    type: "GET",
					    headers: {
					      "x-access-token": token
					    },
					    contentType: "application/json",
					    cache: false
					})
					    .done(function(data, textStatus, jqXHR) {
					    console.log(data.data);
					    window.location.reload();
					   
					})
					.fail(function(jqXHR, textStatus, errorThrown) {
					    console.log("HTTP Request Failed");
					    // var response = jqXHR.responseJSON.code;
					    console.log(jqXHR.responseJSON);
					}); 
				} else {
				    return false;
				}
		    });
   		},2000) 


    });

	