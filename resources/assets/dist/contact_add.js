  	$(document).ready(function() { 
     	// Get login user profile data
     	$("#view_users_table_wrapper").hide();
		var role = localStorage.getItem('u_role');
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		project_id = url[ url.length - 3 ]; // projects
		console.log(project_id);

		// Check View All Permission
		var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
		var check_permission = jQuery.inArray( "contact_add", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
			$('.body-content .wrapper').show();
		}

		setTimeout(function(){
		  	// $("body .add_contact_project").on('click', function(e){
		  	$("body").delegate(".add_contact_project", "click", function(e) {
		    	e.preventDefault();
		        var id = $(this).attr("id");
                        var contact_name = $(this).attr("contact_name");
		        //alert(id);return false;    
		        var r = confirm("Are you sure you want to add "+contact_name+" into project "+window.project_name+"?");
				if (r == true) {
					$('.loading_data').show();
					jQuery.ajax({
					url: baseUrl + "contact/"+project_id+"/"+id,
					    type: "GET",
					    headers: {
					      "x-access-token": token
					    },
					    contentType: "application/json",
					    cache: false
					})
					.done(function(data, textStatus, jqXHR) {
					    console.log(data);
					    // $('#'.id).parent("tr").hide();
					    // $('.loading_data').hide();
					    // console.log(window.location.reload);
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
		},1000) 

		jQuery.ajax({
			url: baseUrl + "projects/"+project_id,
		    type: "GET",
		    headers: {
		      "Content-Type": "application/json",
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
		.done(function(data, textStatus, jqXHR) {
		    window.project_name = data.data.p_name;
		    $('#project_name_title').text("Project: " + window.project_name);
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
		    console.log("HTTP Request Failed");
		    var response = jqXHR.responseJSON.code;
		    if(response == 403){
		    	// window.location.href = baseUrl + "403";
		    	console.log("403");
		    }
		    else if(response == 404){
		    	console.log("404");
		    	// window.location.href = baseUrl + "404";
		    }
		    else {
		    	// console.log("500");
		    	window.location.href = baseUrl + "500";
		    }
		})
			setTimeout(function()
        	{

			    jQuery.ajax({
				url: baseUrl +"/"+project_id+"/contact",
				    type: "GET",
				    headers: {
				      "x-access-token": token
				    },
				    contentType: "application/json",
				    cache: false
				})
			    .done(function(data, textStatus, jqXHR) {
				    // console.log(data.data[0]);

				    // window.project_name = data.data[0].p_name;
				    var specific_project_name = 'Contacts for Project: ' + window.project_name;
				   	console.log(specific_project_name);
				    $('#view_users_table').DataTable({
		                dom: 'Bfrtip',
		                buttons: [
		                    // {
		                    //     extend: 'copyHtml5',
		                    //     exportOptions: {
		                    //         columns: [ 0, 1, 2, 3 ]
		                    //     },
		                    //     message: specific_project_name,
		                    // },
		                    {
		                        extend: 'csvHtml5',
		                        exportOptions: {
		                            columns: [ 0, 1, 2, 3 ]
		                        },
		                        message: specific_project_name,
		                    },
		                    {
		                        extend: 'pdfHtml5',
		                        exportOptions: {
		                            columns: [ 0, 1, 2, 3 ]
		                        },
		                        message: specific_project_name,
		                    },
		                    {
		                        extend: 'excelHtml5',
		                        exportOptions: {
		                            columns: [ 0, 1, 2, 3 ]
		                        },
		                        message: specific_project_name,
		                    },
		                    {
		                        extend: 'print',
		                        exportOptions: {
		                            columns: [ 0, 1, 2, 3 ]
		                        },
		                        message: specific_project_name,
		                    }
		                ]
		            });
				    $("#view_users_table_wrapper tbody tr").hide();
				    // Foreach Loop 
				    var count = 1;
					jQuery.each(data.data, function( i, val ) {
					  var t = $('#view_users_table').DataTable();
					  // console.log(val);
					  if(val.phone_number == null){
					  	var phone = ''
					  }
					  else {
					  	var phone = val.phone_number;
					  }
					  // if(project_users.id != val.id){
						t.row.add( [
				           count, // val.id,
				           val.first_name+' '+val.last_name,
				           val.email+'<br/>'+ phone,
				           val.company_name,
				           '<a id="'+val.id+'" contact_name="'+val.first_name+' '+val.last_name+'" href="" class="btn btn-primary btn-xs tooltips add_contact_project" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-plus"></i> Add to project</a>'
				        ]).draw( false );
				        count++;
					  // }
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
			},1000)


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
		},1000)
    });