  	$(document).ready(function() { 
     	// Get login user profile data
     	$("#view_users_table_wrapper").hide();
		var role = localStorage.getItem('u_role');
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		project_id = url[ url.length - 2 ]; // projects
		console.log(project_id);
		// type = url[ url.length - 1 ]; // projects
		// console.log(type);

		// Check View All Permission
		var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
		var check_permission = jQuery.inArray("submittal_log", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
			$('.body-content .wrapper').show();
		}

		jQuery.ajax({
		url: baseUrl +project_id+"/get-submittal-log",
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
		    var specific_project_name = 'Submittal Log for Project: ' + window.project_name;
		   	console.log(specific_project_name);
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
                        orientation: 'landscape',
            			pageSize: 'LEGAL',
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

		    $("#view_users_table_wrapper tbody tr").hide();
		    // Foreach Loop 
			jQuery.each( data.data, function( i, val ) {
				var status = val.sr_review_type;
				if(status == 'no_exception'){
		    	status = '<span class="label label-success">No Exception</span>';
			    }
			    else if(status == 'make_corrections_noted'){
		    	status = '<span class="label label-success">Make Corrections</span>';
			    }
			    else if(status == 'revise_resubmit'){
		    	status = '<span class="label label-success">Revise & Resubmit</span>';
			    }
			    else if(status == 'rejected'){
		    	status = '<span class="label label-success">Rejected</span>';
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
			  	
			  	var submittal_path = val.submittal_path;
			  	var sub_additional_path_value;
			  	if(submittal_path == null){
			  		sub_additional_path_value = '-';
			  	}
			  	else {
			  		sub_additional_path_value = '<a href="'+baseUrl+val.submittal_path+'"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  	}

			  var sub_num = '';
			  if(val.sub_type == 'new'){
			  	sub_num = val.sub_number;
			  }
			  else {
			  	sub_num = val.sub_exist_parent+' R '+ val.sub_rev_number;
			  }

			  var no_exception = '';
			  if(val.sr_review_type == 'no_exception'){
			  	no_exception = '<span style="text-align: center;"><i class="fa fa-check-square"></i></span>';
			  }
			  else {
			  	no_exception = ' ';
			  }

			  var make_corrections_noted = '';
			  if(val.sr_review_type == 'make_corrections_noted'){
			  	make_corrections_noted = '<span style="text-align: center;"><i class="fa fa-check-square"></i></span>';
			  }
			  else {
			  	make_corrections_noted = ' ';
			  }

			  var revise_resubmit = '';
			  if(val.sr_review_type == 'revise_resubmit'){
			  	revise_resubmit = '<span style="text-align: center;"><i class="fa fa-check-square"></i></span>';
			  }
			  else {
			  	revise_resubmit = ' ';
			  }

			  var rejected = '';
			  if(val.sr_review_type == 'rejected'){
			  	rejected = '<span style="text-align: center;"><i class="fa fa-check-square"></i></span>';
			  }
			  else {
			  	rejected = ' ';
			  }

			  // var date = new Date(val.sr_timestamp.replace(' ', 'T'));
     		  // var formatDate = $.datepicker.formatDate('yy-mm-dd', date);
			  // var day = date.getDate();
			  // var month = date.getMonth();
			  // var year = date.getFullYear();

			  //var date_format = year + '-' + month + '-' + day;
			  var date_format = val.sub_date; //formatDate;

			  if(val.sr_respond_date == null){
			  		var respond_date = ' ';
			  }
			  else {
				  // var date = new Date(val.sr_respond_date.replace(' ', 'T'));
      //             var formatDate = $.datepicker.formatDate('yy-mm-dd', date);
				  // var day = date.getDate();
				  // var month = date.getMonth();
				  // var year = date.getFullYear();
				  //var respond_date = year + '-' + month + '-' + day;
				  var respond_date = val.sr_respond_date; // formatDate;
			  }

			  var t = $('#view_users_table').DataTable();
			  	if(val.sub_review_type == 'yes'){
                                    var cus_css = '';
                                    if(val.sr_review_type=="pending")
                                        var cus_css = 'color:#F00;';
			  		t.row.add( [
			           //'<span style="'+cus_css+'">'+sub_num+'</span>',
                                   '<span style="'+cus_css+'">'+(i+1)+'</span>',
			           '<span style="'+cus_css+'">'+val.sub_description+'</span>',
			           '<span style="'+cus_css+'">'+date_format+'</span>',
			           '<span style="'+cus_css+'">'+respond_date+'</span>',
			           no_exception,
			           make_corrections_noted,
			           revise_resubmit,
			           rejected,
			           status,
			           '<span style="color:#F00;">'+val.f_name+'</span>'
			       	]).draw( false );
			  	}
			  	else {
					t.row.add( [
			           sub_num,
			           val.sub_description,
			           date_format,
			           respond_date,
			           no_exception,
			           make_corrections_noted,
			           revise_resubmit,
			           rejected,
			           status,
			           val.f_name
			       	]).draw( false );
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


		setTimeout(function()
        {
            // Check Add Permission
			var check_permission = jQuery.inArray("submittal_add", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_add_permission').remove();
			}
			else {
				$('.hide_add_permission').show();
			}
        },2000)


    });