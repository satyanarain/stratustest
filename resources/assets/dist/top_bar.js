  	$(document).ready(function() { 
     	// Get login user profile data
     	// $("#view_users_table_wrapper").hide();
		var role = localStorage.getItem('u_role');
		var token = localStorage.getItem('u_token');
		var user_id = localStorage.getItem('u_id');
		jQuery.ajax({
		url: baseUrl + "user_projects/"+user_id,
		    type: "GET",
		    headers: {
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
		.done(function(data, textStatus, jqXHR) {
		    // console.log(data.data);
		    // Foreach Loop 
			jQuery.each( data.data, function( i, val ) {
				if(val.p_status == 'active'){
					html = '<li>'+
                            // '<span class="label label-danger pull-right">33%</span>'+
                            '<p><a href="'+baseUrl+'dashboard/'+val.p_id+'/project"" style="color: #333; padding: 0px;">Project # : '+val.p_number+' <br/>'+val.p_name+'</a></p>'+
                            // '<div class="progress progress-xs">'+
                            //     '<div class="progress-bar progress-bar-success" style="width: 33%;">'+
                            //         '<span class="sr-only">33%</span>'+
                            //     '</div>'+
                            // '</div>'+
                        '</li>';
			        	$("#all_user_project").append(html);
				}
			});
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


		// Set Notification List in Notification Panel
		jQuery.ajax({
		url: baseUrl + "notification_get_short/"+user_id,
		    type: "GET",
		    headers: {
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
		.done(function(data, textStatus, jqXHR) {
	    // Foreach Loop 
			jQuery.each( data.data, function( i, val ) {
			  	var notification_link = baseUrl+val.pn_link;
			  	var noti_link = notification_link.replace('stratus//dashboard/', 'stratus/dashboard/');
			  	var noti_link = noti_link.replace('//dashboard/', '/dashboard/');
			 //    var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
				// var firstDate = new Date();
				// var secondDate = new Date(val.pn_timestamp);
				// var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)));
				// console.log(firstDate);
				// console.log(secondDate);
				// console.log(diffDays);
				// if(firstDate.getTime() > secondDate.getTime()){
				// 	console.log('less');
				// 	seconds = Math.floor((secondDate - (firstDate))/1000);
				// 	minutes = Math.floor(seconds/60);
				//     hours = Math.floor(minutes/60);
				//     days = Math.floor(hours/24);
					// console.log(hours);
					// hours1 = hours-(days*24);
					// minutes1 = minutes-(days*24*60)-(hours1*60);
			  //   	seconds = seconds-(days*24*60*60)-(hours*60*60)-(minutes1*60);
					// console.log(days);
					// console.log(hours1);
					// console.log(minutes1);
					// var status_time = diffDays +" Days " + hours1 + " Hours " + minutes1 + " Minutes";
				// }

				if(val.pn_status == '0'){
					var noti_status = '<span class="read tooltips" data-original-title="Mark as Unread" data-toggle="tooltip" data-placement="left">'+
	                                    '<i class="fa fa-circle-o"></i>'+
	                                '</span>';
				}
				else {
	                var noti_status = '<span class="un-read tooltips" data-original-title="Mark as Read" data-toggle="tooltip" data-placement="left">'+
				                        '<i class="fa fa-circle"></i>'+
				                    '</span>';
				}
				html = '<a href="'+noti_link+'" class="single-mail" id="'+val.pn_id+'">'+
                    '<span class="icon bg-primary">'+
                        '<i class="fa fa-envelope-o"></i>'+
                    '</span>'+
                    '<strong>'+val.pn_description+'</strong><br/>'+
                    // '<p><small style="padding:0px;">'+val.p_name+'</small></p>'+
                    '<p><small>'+val.pn_timestamp+'</small></p>'+
                    noti_status+
                '</a>';
		        $("#notification_list").append(html);
			});
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

		// Set Notification List in Notification Panel
		jQuery.ajax({
		url: baseUrl + "notification_get_unread/"+user_id,
		    type: "GET",
		    headers: {
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
		.done(function(data, textStatus, jqXHR) {
	    	console.log(data.data[0].unread_count);
	    	$('.unread_notification').text(data.data[0].unread_count);
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
		    	console.log('404 Found Unread Notification');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		}); 
		
		$('body').delegate( '#notification_list a', 'click', function () {
			var id = $(this).attr("id");
			// alert(id);
			jQuery.ajax({
			url: baseUrl + "change_notification_status/"+id,
			    type: "GET",
			    headers: {
			      "x-access-token": token
			    },
			    contentType: "application/json",
			    cache: false
			})
			.done(function(data, textStatus, jqXHR) {
		    	console.log(data.data);
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
			    console.log("HTTP Request Failed");
			    var response = jqXHR.responseJSON.code;
			    console.log(response);
			    if(response == 403){
			    	window.location.href = baseUrl + "403";
			    }
			    else if(response == 404){
			    	console.log('404 Found - Change Notification Status');
			    }
			    else {
			    	window.location.href = baseUrl + "500";
			    }
			}); 
			return;
		}); 

    });

