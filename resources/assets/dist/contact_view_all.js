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
		var check_permission = jQuery.inArray( "contact_view_permission_all", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
			$('.body-content .wrapper').show();
		}

        jQuery.ajax({
            url: baseUrl + "projects/"+project_id,
            type: "GET",
            headers: {
                "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
            .done(function(data, textStatus, jqXHR)
            {
                $('#p_name').text("Project: "+data.data.p_name)
             	console.log(data.data.p_name);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                // var response = jqXHR.responseJSON.code;
                console.log(jqXHR.responseJSON);
            });
        jQuery.ajax({
			url: baseUrl +"/contact/"+project_id,
		    type: "GET",
		    headers: {
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
		.done(function(data, textStatus, jqXHR) {
                $(".loading_data").hide();
		    console.log(data.data);

		    	window.project_name = data.data[0].p_name;
			    var specific_project_name = 'Contact for Project: ' + window.project_name;
			   	console.log(specific_project_name);
			    $('#view_users_table').DataTable({
	                dom: 'Bfrtip',
	                buttons: [
	                    // {
	                    //     extend: 'copyHtml5',
	                    //     exportOptions: {
	                    //         columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
	                    //     },
	                    //     message: specific_project_name,
	                    // },
	                    {
	                        extend: 'csvHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'pdfHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'excelHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'print',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
	                        },
	                        message: specific_project_name,
	                    }
	                ]
	            });

		    $("#view_users_table_wrapper tbody tr").hide();
		    // Foreach Loop 
		    var count = 1;
			jQuery.each( data.data, function( i, val )
            {	
            	// Check Update Permission
				var check_permission = jQuery.inArray("contact_update", check_user_access );
				console.log(check_permission);
				if(check_permission < 1){
					var update_permission = '';
				}
				else {
					var update_permission = '<a href="'+baseUrl+'dashboard/'+project_id+'/contact/'+val.id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i> Edit User</a>';
				}

				// Check Remove Permission
				var check_permission = jQuery.inArray( "contact_remove", check_user_access );
				console.log(check_permission);
				if(check_permission < 1){
					var remove_permission = '';
				}
				else {
					var remove_permission = '<a href="" id="'+val.id+'" class="btn btn-danger btn-xs tooltips user_suspend hide_remove_permission" data-placement="top" data-toggle="tooltip" data-original-title="Suspend" style="float:left"><i class="fa fa-times"></i> Remove User</a>';
				}

				// Check View Permission
				var check_permission = jQuery.inArray( "contact_view_permission_all", check_user_access );
				console.log(check_permission);
				if(check_permission < 1){
					var view_permission = '';
				}
				else {
					var view_permission = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/contact/'+val.id+'" class="btn btn-success btn-xs tooltips hide_view_permission" data-placement="top" data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i> View User</a>';
				}

                console.log(val.id);
                if(val.id == val.p_user_id){
                	return true;
                }
                else {
                	var check_length = val.user_detail.length;
	                var complete='';
	                if(check_length > 0)
	                {
	                    for(var i=0; i<check_length; i++)
	                    {
	                        teststr= val.user_detail[i].u_phone_type;
	                        teststr+=' : ';
	                        teststr+= val.user_detail[i].u_phone_detail;
	                        teststr+='</br>';
	                        complete += teststr;
	                    }
	                }
	                else{
	                    complete ='---';
	                }
				   	var t = $('#view_users_table').DataTable();
					t.row.add( [
			           count,
			           val.first_name+' '+val.last_name,
	                   val.email,
			           val.position_title,
			           val.company_name,
	                   val.ct_name,
	                   complete,
	                   '<span style="text-transform: capitalize;">'+val.role+'</span>',
			           remove_permission+
	                   view_permission+
	                   update_permission
	                ]).draw( false );
	                count++;
                }
			});

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

		// $('.user_suspend').click(function(e) {
	 //        e.preventDefault();
	 //        var href = $(this).attr('id');
	 //        alert(id);
	 //        $('.confirm_next_button_alert').attr('href', href);
	 //    });

		setTimeout(function(){
		  	// $(".user_suspend").on('click', function(e){
		  	$("body").delegate(".user_suspend", "click", function(e) {
		    	e.preventDefault();
		        var id = $(this).attr("id");
		        // alert(id);
		        
		        var r = confirm("Are you sure to Inactive this user?");
				if (r == true) {
				    jQuery.ajax({
					url: baseUrl + "contact/"+project_id+"/delete/"+id,
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


   		setTimeout(function()
        {
			// Check Add Permission
			var check_permission = jQuery.inArray( "contact_add", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_add_permission').remove();
			}
			else {
				$('.hide_add_permission').show();
			}

			// Check Update Permission
			// var check_permission = jQuery.inArray( "contact_update", check_user_access );
			// console.log(check_permission);
			// if(check_permission < 1){
			// 	$('.hide_update_permission').remove();
			// }
			// else {
			// 	$('.hide_update_permission').show();
			// }

			// Check Remove Permission
			// var check_permission = jQuery.inArray( "contact_remove", check_user_access );
			// console.log(check_permission);
			// if(check_permission < 1){
			// 	$('.hide_remove_permission').remove();
			// }
			// else {
			// 	$('.hide_remove_permission').show();
			// }

			// Check View Permission
			// var check_permission = jQuery.inArray( "contact_view_permission_all", check_user_access );
			// console.log(check_permission);
			// if(check_permission < 1){
			// 	$('.hide_view_permission').remove();
			// }
			// else {
			// 	$('.hide_view_permission').show();
			// }
		},1000)
    });