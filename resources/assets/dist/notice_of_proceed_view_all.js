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
		// var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
		// var check_permission = jQuery.inArray( "notice_proceed_view_all", check_user_access );
		// console.log(check_permission);
		// if(check_permission < 1){
		// 	window.location.href = baseUrl + "403";
		// }
		// else {
		// 	console.log('Yes Permission');
		// }

		jQuery.ajax({
		url: baseUrl +project_id+"/notice-of-proceed",
		    type: "GET",
		    headers: {
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
		    .done(function(data, textStatus, jqXHR) {
		    // console.log(data.data);
		    $("#view_users_table_wrapper tbody tr").hide();
		    // Foreach Loop 
			jQuery.each( data.data, function( i, val ) {

				// // Check Update Permission
				// var check_permission = jQuery.inArray("notice_proceed_update", check_user_access );
				// console.log(check_permission);
				// if(check_permission < 1){
				// 	var update_permission = '';
				// }
				// else {
					var update_permission = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/notice_of_proceed/'+val.pnp_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a><a href="'+baseUrl+'dashboard/'+val.p_id+'/notice_of_proceed/'+val.pnp_id+'" class="btn btn-success btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-search"></i></a>';
				// }

				var status = val.pnp_status;
				if(status == 'active'){
		    	status = '<span class="label label-success">Active</span>';
			    }
			    else {
			    	status = '<span class="label label-danger">Inactive</span>';
			    }
			  	
			  	var pnp_path = val.notice_proceed_path;
			  	var pnp_path_value;
			  	if(pnp_path == null){
			  		pnp_path_value = '-';
			  	}
			  	else {
			  		pnp_path_value = '<a href="'+baseUrl+val.notice_proceed_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  	}

			  	var pnp_cal_day = val.pnp_cal_day;
			  	var pnp_cal_day_value;
			  	if(pnp_cal_day == 'calendar_day'){
			  		pnp_cal_day_value = 'Calender Day';
			  	}
			  	else {
			  		pnp_cal_day_value = 'Working Day';
			  	}

			  var t = $('#view_users_table').DataTable();
				t.row.add( [
		           val.pnp_id,
		           val.contractor_name,
                   // $.datepicker.formatDate('yy-mm-dd', new Date(val.pnp_date.replace(' ', 'T'))),
                   // $.datepicker.formatDate('yy-mm-dd', new Date(val.pnp_start_date.replace(' ', 'T'))),
                   val.pnp_date,
                   val.pnp_start_date,
		           val.pnp_duration,
		           pnp_cal_day_value,
		           val.pnp_liquidated_amount,
		           pnp_path_value,
		           status,
		           update_permission
		       	]).draw( false );
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

		// setTimeout(function()
  //       {
		// 	// Check Add Permission
		// 	var check_permission = jQuery.inArray( "notice_proceed_add", check_user_access );
		// 	console.log(check_permission);
		// 	if(check_permission < 1){
		// 		$('.hide_add_permission').remove();
		// 	}
		// 	else {
		// 		$('.hide_add_permission').show();
		// 	}
		// },2000)

    });