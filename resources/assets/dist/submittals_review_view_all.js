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
                                order: [],
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
				if(check_permission < 1 || val.sr_review_type!="pending"){
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
		    	status = '<span class="label label-warning">Revise & Resubmit</span>';
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
                            var submittal_path = val.submittal_path;
                            var sub_additional_path_value;
                            if(submittal_path == null){
                                    sub_additional_path_value = '-';
                            }
                            else {
                                    sub_additional_path_value = '<a target="_blank" href="'+baseUrl+val.submittal_path+'"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
                            }
                            var submittal_reviewed = val.submittal_reviewed;
                            var sub_reviewed_path_value;
                            if(submittal_reviewed == null){
                                    sub_reviewed_path_value = '-';
                            }
                            else {
                                    sub_reviewed_path_value = '<a target="_blank" href="'+baseUrl+val.submittal_reviewed+'"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
                            }
                            var d = new Date(val.sr_timestamp.replace(' ', 'T'));
//                            var formatDate = $.datepicker.formatDate('yy-mm-dd', date);
//                            var day = date.getDate();
//                            var month = date.getMonth()+1;
//                            var year = date.getFullYear();
//                            var respond_date = year + '-' + month + '-' + day;
                            var respond_date = formatAMPM(d);
			  var t = $('#view_users_table').DataTable();
				if(val.sub_review_type == 'yes'){
                                    var cus_css = '';
                                    if(val.sr_review_type=="pending")
                                        var cus_css = 'color:#F00;';
			  		t.row.add( [
//                                    '<span style="'+cus_css+'">'+(i+1)+'</span>',
                                    '<span style="'+cus_css+'">'+sub_num+'</span>',
                                    
                                    '<span style="'+cus_css+'">'+val.sub_description+'</span>',
                                    respond_date,
                                    sub_additional_path_value,
                                    sub_reviewed_path_value,
			           status,
			           update_permission
			       	]).draw( false );
			  	}
			  	else {
		            t.row.add( [
			           //i+1,
                                   sub_num,
                                   val.sub_description,
                                   respond_date,
                                   sub_additional_path_value,
                                   sub_reviewed_path_value,
			           status,
			           update_permission
			       	]).draw( false );
			  	}
//                            t.on( 'order.dt search.dt', function () {
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

    });
    
    function formatAMPM(d) {
  var hours = d.getHours();
  var minutes = d.getMinutes();
  var ampm = hours >= 12 ? 'pm' : 'am';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
  var strTime = ("0" + hours).slice(-2) + ':' + ("0" + minutes).slice(-2) + ' ' + ampm;
  return d.getFullYear() + "-" +("0"+(d.getMonth()+1)).slice(-2) + "-" +("0" + d.getDate()).slice(-2) + "  "
     +strTime;
}