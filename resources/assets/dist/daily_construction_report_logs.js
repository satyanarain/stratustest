$(document).ready(function() { 

    // Get login user profile data
    $("#view_users_table_wrapper").hide();
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    pdr_report_id = url[ url.length - 2 ]; // projects
    // console.log(project_id);
    type = url[ url.length - 1 ]; // job type
    project_id = url[ url.length - 4]; // report id
    // console.log(type);
    
    //alert(type);
    //alert(project_id);
    //alert(pdr_report_id);return false;
    // Check View All Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray( "daily_construction_report_view_all", check_user_access );
    console.log(check_permission);
    if(check_permission < 1){
            window.location.href = baseUrl + "403";
    }
    else {
            console.log('Yes Permission');
    }

    jQuery.ajax({
		url: baseUrl +project_id+"/daily-report-logs/"+pdr_report_id,
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
			    var specific_project_name = 'Daily Construction Report for Project: ' + window.project_name;
			   	console.log(specific_project_name);
			    $('#view_users_table').DataTable({
	                dom: 'Bfrtip',
	                buttons: [
	                    // {
	                    //     extend: 'copyHtml5',
	                    //     exportOptions: {
	                    //         columns: [ 0, 1]
	                    //     },
	                    //     message: specific_project_name,
	                    // },
	                    {
	                        extend: 'csvHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'pdfHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'excelHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'print',
	                        exportOptions: {
	                            columns: [ 0, 1]
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
				var check_permission = jQuery.inArray("daily_construction_report_update", check_user_access );
				console.log(check_permission);
				if(check_permission < 1){
					var update_permission = '';
				}
				else {
					var update_permission = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/daily_construction_report/'+val.pdrl_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
				}

				var status = val.pdr_status;
				if(status == 'complete'){
                                    status = '<span class="label label-success">COMPLETE</span>';
                                    //var action = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/daily_construction_report/'+val.pdr_id+'" class="btn btn-info btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" style="margin-right:5px;"><i class="fa fa-search"></i></a>'
                                    
                                }
                                else {
                                    status = '<span class="label label-danger">INCOMPLETE</span>';
                                    //var action = update_permission;
                                }
			  	var action = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/daily_construction_report/'+val.pdrl_id+'" class="btn btn-info btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" style="margin-right:5px;"><i class="fa fa-search"></i></a>';
			  	// var submittal_path = val.submittal_path;
			  	// var sub_additional_path_value;
			  	// if(submittal_path == null){
			  	// 	sub_additional_path_value = '-';
			  	// }
			  	// else {
			  	// 	sub_additional_path_value = '<a href="'+baseUrl+val.submittal_path+'"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  	// }

			  // var date = new Date(val.pdr_date);
     //          var formatDate = $.datepicker.formatDate('yy-mm-dd', date);
			  // var day = date.getDate();
			  // var month = date.getMonth()+1;
			  // var year = date.getFullYear();

			  //var report_date = year + '-' + month + '-' + day;
			  var report_date = val.pdr_date; //$.datepicker.formatDate('yy-mm-dd', new Date(val.pdr_date.replace(' ', 'T'))); // formatDate;
			   var t = $('#view_users_table').DataTable();
				t.row.add( [
		           count,
		           'Daily Report ' + report_date,
		           status,
		           action
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


		
    });