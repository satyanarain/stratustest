  	$(document).ready(function() { 
     	// Get login user profile data
     	// $("#view_users_table_wrapper").hide();
		var role = localStorage.getItem('u_role');
		var token = localStorage.getItem('u_token');
		var user_id = localStorage.getItem('u_id');
		// Set Notification List in Notification Panel
		jQuery.ajax({
		url: baseUrl + "notification_get_all/"+user_id,
		    type: "GET",
		    headers: {
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
		.done(function(data, textStatus, jqXHR) {
	    // Foreach Loop 
	    	var count = 1;
			jQuery.each( data.data, function( i, val ) {
			    var notification_link = baseUrl+val.pn_link;
			  	var noti_link = notification_link.replace('stratus//dashboard/', 'stratus/dashboard/');
			  	var noti_link = noti_link.replace('//dashboard/', '/dashboard/');
			    // console.log(noti_link);
			    if(val.pn_sender_user_id == 0){
			    	var user_link = ' Created By: SYSTEM ';
			    }
			    else {
			    	var user_link = 'Created By: <a href="'+baseUrl+'dashboard/'+val.pn_sender_user_id+'/view-contact" id="'+val.pn_id+'" class="green-color" style="color:#fff;">'+val.user_firstname+' '+val.user_lastname+'</a>';
			    }

				if (i % 2 === 0) { 
					html = '<li style="display:none; list-style:none;"><article class="time-line-row" style="display:flex;">'+
	                            '<div class="time-line-info">'+
	                                '<div class="panel">'+
	                                    '<div class="panel-body">'+
	                                        '<span class="arrow"></span>'+
	                                        '<span class="time-line-ico-box green"></span>'+
	                                        '<span class="time-line-subject green"> <i class="fa fa-clock-o"></i> '+user_link+'</span>'+
	                                        '<div class="title">'+
	                                            '<h1>'+val.pn_timestamp+'</h1>'+
	                                        '</div>'+
	                                        '<h1><a href="'+noti_link+'" id="'+val.pn_id+'" style="color: #91918E; font-weight: 500;">'+val.pn_description+'</h1>'+
	                                    '</div>'+
	                                '</div>'+
	                            '</div>'+
	                        '</article></li>';
				}
				else {
					html = '<li style="display:none; list-style:none;"><article class="time-line-row alt" style="display:flex;">'+
	                            '<div class="time-line-info">'+
	                                '<div class="panel">'+
	                                    '<div class="panel-body">'+
	                                        '<span class="arrow-alt"></span>'+
	                                        '<span class="time-line-ico-box green"></span>'+
	                                        '<span class="time-line-subject green"> <i class="fa fa-clock-o"></i> '+user_link+'</span>'+
	                                        '<div class="title">'+
	                                            '<h1>'+val.pn_timestamp+'</h1>'+
	                                        '</div>'+
	                                        '<h1><a href="'+noti_link+'" id="'+val.pn_id+'" style="color: #91918E; font-weight: 500;">'+val.pn_description+'</h1>'+
	                                    '</div>'+
	                                '</div>'+
	                            '</div>'+
	                        '</article></li>';
				}
		        $("#notification_list_page").append(html);
		        count ++;
			});

			setTimeout(function()
	        {
				size_li = $("#notification_list_page li").size();
				console.log(size_li);
			    x=3;
			    $('#notification_list_page li:lt('+x+')').show();
			    $('.loading_bar').hide();
			},3000)
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
		// 	$(".loading_data").hide();
		// 	console.log('stop loading');
		// },3000)

		$('body').delegate( '#notification_list_page a', 'click', function () {
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

		$('#loadMore').click(function (e) {
			e.preventDefault();
	        x = (x+5 <= size_li) ? x+5 : size_li;
	        $('#notification_list_page li:lt('+x+')').show();
	        if(x >= size_li){
	        	$('#loadMore').remove();
	        }
	    });
    });