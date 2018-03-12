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
		var check_permission = jQuery.inArray( "meeting_minutes_view_all", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
		}

		jQuery.ajax({
		url: baseUrl +project_id+"/minutes-meeting",
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
		    var specific_project_name = 'Meeting Minutes for Project: ' + window.project_name;
		   	console.log(specific_project_name);
		    $('#view_users_table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    // {
                    //     extend: 'copyHtml5',
                    //     exportOptions: {
                    //         columns: [ 0, 1, 2, 3, 7 ]
                    //     },
                    //     message: specific_project_name,
                    // },
                    {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 7 ]
                        },
                        message: specific_project_name,
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 7 ]
                        },
                        message: specific_project_name,
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 7 ]
                        },
                        message: specific_project_name,
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 7 ]
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
				var check_permission = jQuery.inArray("meeting_minutes_update", check_user_access );
				// console.log(check_permission);
				if(check_permission < 1){
					var update_permission = '';
				}
				else {
					var update_permission = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/minutes_meeting/'+val.pm_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
				}

				var status = val.pm_status;
				if(status == 'active'){
		    	status = '<span class="label label-success">Active</span>';
			    }
			    else {
			    	status = '<span class="label label-danger">Inactive</span>';
			    }
			  	
			  	var agenda_path = val.agenda_path;
			  	var agenda_path_value;
			  	if(agenda_path == null){
			  		agenda_path_value = '-';
			  	}
			  	else {
			  		if(val.pm_status == 'active'){
			  			agenda_path_value = '<a href="'+baseUrl+val.agenda_path+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  		else {
			  			agenda_path_value = '<td><a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
			  		}
			  	}

			  	var signin_sheet_path = val.signin_sheet_path;
			  	var signin_sheet_path_value;
			  	if(signin_sheet_path == null){
			  		signin_sheet_path_value = '-';
			  	}
			  	else {
			  		if(val.pm_status == 'active'){
			  			signin_sheet_path_value = '<a href="'+baseUrl+val.signin_sheet_path+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  		else {
			  			signin_sheet_path_value = '<td><a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
			  		}
			  	}

			  	var meeting_minutes_path = val.meeting_minutes_path;
			  	var meeting_minutes_path_value;
			  	if(meeting_minutes_path == null){
			  		meeting_minutes_path_value = '-';
			  	}
			  	else {
			  		if(val.pm_status == 'active'){
			  			meeting_minutes_path_value = '<a href="'+baseUrl+val.meeting_minutes_path+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  		else {
			  			meeting_minutes_path_value = '<td><a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
			  		}
			  	}
                                var pm_special='';
                                var objJSON = JSON.parse(val.pm_special); // this is how you parse a string into JSON 
                                for (var i = 0, len = objJSON.length; i < len; i++)
                                    pm_special = pm_special+objJSON[i].value+',';
                                pm_special = pm_special.substring(0, pm_special.length - 1); // "12345.0"
                                //pm_special = obj.value;
                                //alert(obj.value);
//			  	var pm_special = [val.pm_special];
//			 	var newHTML = [];
//				    console.log(pm_special.length);
//				for (var i = 0; i < pm_special.length; i++) {
//			  		pm_special[i] = pm_special[i].replace('[{"value":"', '');
//			  		pm_special[i] = pm_special[i].replace('"},', ',');
//					
//				    // newHTML.push('<span>' + pm_special[i] + '</span>');
//				    newHTML.push(pm_special[i]);
//				}
				//console.log(newHTML.join(""));
			  // pm_special = pm_special.replace(']', '');
			  // pm_special = pm_special.replace('{', '');
			  // pm_special = pm_special.replace('}', '');
			  // console.log(pm_special);

			  var t = $('#view_users_table').DataTable();
				t.row.add( [
		           count, // val.pm_id,
		           val.contractor_name,
                   val.pm_date, // $.datepicker.formatDate('yy-mm-dd', new Date(val.pm_date.replace(' ', 'T'))),
		           val.pm_description,
		           pm_special,
		           agenda_path_value,
		           signin_sheet_path_value,
		           meeting_minutes_path_value,
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
			var check_permission = jQuery.inArray( "meeting_minutes_add", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_add_permission').remove();
			}
			else {
				$('.hide_add_permission').show();
			}
		},2000)


    });