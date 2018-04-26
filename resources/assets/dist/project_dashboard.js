$(document).ready(function() {

	var token = localStorage.getItem('u_token');
	var url = $(location).attr('href').split( '/' );
	project_id = url[ url.length - 2 ]; // projects
	var change_order_days_type =1;
	var change_order_due_date = 0;
		var rfi_days_type =1;
	var rfi_due_date = 0;
		var submittal_days_type =1;
	var submittal_due_date = 0;
 	console.log(project_id);
	localStorage.setItem("current_project_id", project_id);
	var u_id = localStorage.getItem('u_id');
	console.log(u_id);
	// localStorage.clear('access_permission');
	var role 			= localStorage.getItem('u_role');
        var user_id 			= localStorage.getItem('u_id');
        var u_company_name              = localStorage.getItem('u_company_name');
	// console.log(role);
	// jQuery.ajax({
 //        url: baseUrl +project_id+"/check_single_project_permission/"+u_id,
 //        type: "GET",
 //        headers: {
 //          "Content-Type": "application/json",
	//       "x-access-token": token
 //        },
 //        contentType: "application/json",
 //        cache: false
 //    })
 //    .done(function(data, textStatus, jqXHR) {
 //    	var count = 1;
 //        var access_permission = [];
	// 	jQuery.each( data, function( i, val ) {
 //        	// console.log(val.pup_permission_key);
	// 		access_permission[count] = val.pup_permission_key;
	// 		localStorage.setItem("access_permission", JSON.stringify(access_permission));
	// 		count ++;
	// 	});

	// 	 // Get All Permissions
	// 	// var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
	// 	// jQuery.each( check_user_access, function( i, val ) {
	//  //    	console.log(val);
	//  //    	$('.'+val).show();
	// 	// });
 //    })
 //    .fail(function(jqXHR, textStatus, errorThrown) {
 //        console.log("HTTP Request Failed");
 //        var response = jqXHR.responseJSON.code;
 //        console.log(jqXHR);
 //        if(response == 403){
 //            console.log('Permission 403');
 //            // window.location.href = baseUrl + "403";
 //        }
 //        else if(response == 404){
 //            console.log('Permission 404');
 //            // window.location.href = baseUrl + "404";
 //        }
 //        else {
 //            window.location.href = baseUrl + "500";
 //        }
 //    });


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
		     console.log(data);
		    var project_id = data.data.p_id;
		    $('#project_number').text(project_id);
		    var project_number = data.data.p_number;
		    $('#project_code').text(project_number);
		    var project_name = data.data.p_name;
		    $('#project_name').text(project_name);
		    $('#project_name_1').text(project_name);
		    var project_location = data.data.p_location;
		    $('#project_location').text(project_location);
		    $('#project_location1').text(project_location);
		    var project_description = data.data.p_description;
		    $('#project_description').text(project_description);

		      change_order_days_type = data.data.change_order_days_type;
              change_order_due_date = data.data.change_order_due_date;
			  rfi_days_type =data.data.rfi_days_type;
			  rfi_due_date = data.data.rfi_due_date;
		      submittal_days_type =data.data.submittal_days_type;
			  submittal_due_date = data.data.submittal_due_date;
                    
                    if(data.data.f_name ==null)
                        $('.project_lead_agency_li').remove();
                    else
                        $('#project_lead_agency').text(data.data.f_name);
                        
		    var status = data.data.p_status;
		    if(status == "active"){
		    	status = '<span class="label label-success">Active</span>';
		    }
		    else {
		    	status = '<span class="label label-danger">Inactive</span>';
		    }
		    $('#project_status').html(status);
		    $('.loading_project_detail').remove();
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
		    console.log("HTTP Request Failed");
		    var response = jqXHR.responseJSON.code;
		    if(response == 403){
		    	// window.location.href = baseUrl + "403";
		    	console.log("403");
		    	$('.loading_project_detail').remove();
		    }
		    else if(response == 404){
		    	console.log("404");
		    	$('.loading_project_detail').remove();
		    	// window.location.href = baseUrl + "404";
		    }
		    else {
		    	console.log("500");
		    	$('.loading_project_detail').remove();
		    	// window.location.href = baseUrl + "500";
		    }
		})


		// Selected Improvement Type
	    jQuery.ajax({
	    url: baseUrl +"/"+project_id+"/improvement-type",
	        type: "GET",
	        headers: {
	          "x-access-token": token
	        },
	        contentType: "application/json",
	        cache: false
	    })
	    .done(function(data, textStatus, jqXHR) {
	        // Foreach Loop
//                $("#project_type").append(
//                    '<option value="">Select Improvement Types</option>'
//                )
	        jQuery.each(data.data, function( i, val ) {
	            if(val.pt_status == 'active'){
	                $("#project_type").append(
	                	'<li class="clearfix"><p class="todo-title">'+val.pt_name+'</p></li>'
	                    // '<span class="label label-inverse" style="display: inline-block; font-size: 14px; margin: 0px 15px 0px 0px; padding: 5px 15px;">'+val.pt_name+'</span>'
	                )
	            }
	        });
	        $(".loading_project_project_type").remove();
	    })
	    .fail(function(jqXHR, textStatus, errorThrown) {
		    console.log("HTTP Request Failed");
		    var response = jqXHR.responseJSON.code;
		    if(response == 403){
		    	// window.location.href = baseUrl + "403";
		    	console.log("403");
		    	$(".loading_project_project_type").remove();
		    }
		    else if(response == 404){
		    	console.log("404");
		    	$(".loading_project_project_type").remove();
		    	// window.location.href = baseUrl + "404";
		    }
		    else {
		    	console.log("500");
		    	$(".loading_project_project_type").remove();
		    	// window.location.href = baseUrl + "500";
		    }
		})

	    setTimeout(function()
        {
		    // Select Contractor Name
		    jQuery.ajax({
	        url: baseUrl + "/"+project_id+"/default_contractor",
	            type: "GET",
	            headers: {
	              "Content-Type": "application/json",
	              "x-access-token": token
	            },
	            contentType: "application/json",
	            cache: false
	        })
	        .done(function(data, textStatus, jqXHR) {
	        	$('#contractor_name').text(data.data[0].agency_name);
	        	$('.loading_project_contractor_name').remove();
	        })
	        .fail(function(jqXHR, textStatus, errorThrown) {
			    console.log("HTTP Request Failed");
			    var response = jqXHR.responseJSON.code;
			    if(response == 403){
			    	// window.location.href = baseUrl + "403";
			    	console.log("403");
			    	$(".loading_project_contractor_name").remove();
			    }
			    else if(response == 404){
			    	console.log("404");
	        		$('#contractor_name').text('-');
			    	$(".loading_project_contractor_name").remove();
			    	// window.location.href = baseUrl + "404";
			    }
			    else {
			    	console.log("500");
			    	$(".loading_project_contractor_name").remove();
			    	// window.location.href = baseUrl + "500";
			    }
			})
	    },2000)

        setTimeout(function()
        {
	        // Get Project Currency
			jQuery.ajax({
			url: baseUrl+project_id+"/project_setting_get/project_currency",
			    type: "GET",
			    headers: {
			      "x-access-token": token
			    },
			    contentType: "application/json",
			    cache: false
			})
			.done(function(data, textStatus, jqXHR) {
				// console.log(data.data.pset_meta_value);
			    // $('.loading_bar_project_contract_amount').remove();
			    var project_currency_id = data.data.pset_meta_value;
			    jQuery.ajax({
				url: baseUrl + "currency/"+project_currency_id,
				    type: "GET",
				    headers: {
				      "Content-Type": "application/json",
				      "x-access-token": token
				    },
				    contentType: "application/json",
				    cache: false
				})
				.done(function(data, textStatus, jqXHR) {
				    // console.log(data.data.cur_symbol);
				    // $('.loading_bar_project_contract_amount').remove();
				    // $('.project_currency').text(data.data.cur_symbol+' ');
				    var currency_icon = data.data.cur_symbol+' ';
				    // alert(currency_icon);
				    // setTimeout(function(){
		                // Bid Total Amount
					    jQuery.ajax({
					    url: baseUrl+"/"+project_id+"/bid-items-total",
					        type: "GET",
					        headers: {
					          "Content-Type": "application/json",
					          "x-access-token": token
					        },
					        contentType: "application/json",
					        cache: false
					    })
					    .done(function(data, textStatus, jqXHR) {
					    	// console.log(data);
					        // $(".project_amount").text(data.data[0].total_amount);
					        $('.loading_bar_project_contract_amount').remove();
					        $('#contractor_name_box').show();
					        // var contract_amount = data.data[0].total_amount;
					        if(data.data[0].total_amount == null){
					        	var contract_amount = 0;
					        }
					        else {
					        	var contract_amount = ReplaceNumberWithCommas(data.data[0].total_amount);
					        }
					        var contract_item = data.data[0].total_item;
					        $('.state-overview').append('<div class="col-md-4"><section class="blue">'+
		                          '<div class="symbol" style="font-size: 30px; font-weight: bold;">'+currency_icon+'</div>'+
		                          '<div class="value white">'+
		                              '<h1 class="timer" data-from="0" data-to="'+contract_amount+'" data-speed="1000">'+contract_amount+'</h1>'+
		                              '<p>Contract Amount</p>'+
		                          '</div>'+
		                      '</section></div>'+
		                      '<div class="col-md-4"><section class="blue">'+
		                          '<div class="symbol" style="font-size: 30px; font-weight: bold;"><i class="fa fa-cube"></i></div>'+
		                          '<div class="value white">'+
		                              '<h1 class="timer" data-from="0" data-to="'+contract_item+'" data-speed="1000">'+contract_item+'</h1>'+
		                              '<p>Contract Items</p>'+
		                          '</div>'+
		                      '</section></div>');
					    })
					    .fail(function(jqXHR, textStatus, errorThrown) {
					        console.log("HTTP Request Failed");
					        var response = jqXHR.responseJSON.code;
					        if(response == 403){
						    	console.log("403");
					        	$('.loading_bar_project_contract_amount').remove();
						    	$('#contractor_name_box').show();
						    	$('.state-overview').append('<div class="col-md-4"><section class="green">'+
		                          '<div class="symbol" style="font-size: 30px; font-weight: bold;"> - </div>'+
		                          '<div class="value white">'+
		                              '<h1 class="timer" data-from="0" data-to="0" data-speed="1000">0</h1>'+
		                              '<p>Contract Amount</p>'+
		                          '</div>'+
		                      	'</section></div>'+
		                      '<div class="col-md-4"><section class="panel green">'+
		                          '<div class="symbol" style="font-size: 30px; font-weight: bold;"><i class="fa fa-cube"></i></div>'+
		                          '<div class="value white">'+
		                              '<h1 class="timer" data-from="0" data-to="0" data-speed="1000">0</h1>'+
		                              '<p>Contract Item</p>'+
		                          '</div>'+
		                      '</section></div>');
						    }
						    else if(response == 404){
						    	console.log("404");
						    	$('.loading_bar_project_contract_amount').remove();
						    	$('#contractor_name_box').show();
						    	$('.state-overview').append('<div class="col-md-4"><section class="green">'+
		                          '<div class="symbol" style="font-size: 30px; font-weight: bold;"> - </div>'+
		                          '<div class="value white">'+
		                              '<h1 class="timer" data-from="0" data-to="0" data-speed="1000">0</h1>'+
		                              '<p>Contract Amount</p>'+
		                          '</div>'+
		                      	'</section></div>'+
		                      '<div class="col-md-4"><section class="panel green">'+
		                          '<div class="symbol" style="font-size: 30px; font-weight: bold;"><i class="fa fa-cube"></i></div>'+
		                          '<div class="value white">'+
		                              '<h1 class="timer" data-from="0" data-to="0" data-speed="1000">0</h1>'+
		                              '<p>Contract Item</p>'+
		                          '</div>'+
		                      '</section></div>');
						    }
						    else {
						    	console.log("500");
						    }
					    })
					    $('.loading_bar_project_contract_amount').remove();
					    $('#contractor_name_box').show();
		            // },100)
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
			        console.log("HTTP Request Failed 1");
			        var response = jqXHR.responseJSON.code;
			        console.log(response);
			        if(response == 403){
				    	console.log("403 project_currency1");
				    }
				    else if(response == 404){
				    	console.log("404 project_currency1");
				    }
				    else {
				    	console.log("500 project_currency1");
				    }
			    })
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
		        console.log("HTTP Request Failed 1");
		        var response = jqXHR.responseJSON.code;
		        console.log(response);
		        if(response == 403){
			    	console.log("403 project_currency");
		        	$('.loading_bar_project_contract_amount').remove();
		        	$('#contractor_name_box').show();
			    	$('.state-overview').append('<div class="col-md-4"><section class="green">'+
	                  '<div class="symbol" style="font-size: 30px; font-weight: bold;"> - </div>'+
	                  '<div class="value white">'+
	                      '<h1 class="timer" data-from="0" data-to="0" data-speed="1000">0</h1>'+
	                      '<p>Contract Amount</p>'+
	                  '</div>'+
	              	'</section></div>'+
		           		'<div class="col-md-4"><section class="panel green">'+
		                  '<div class="symbol" style="font-size: 30px; font-weight: bold;"><i class="fa fa-cube"></i></div>'+
		                  '<div class="value white">'+
		                      '<h1 class="timer" data-from="0" data-to="0" data-speed="1000">0</h1>'+
		                      '<p>Contract Item</p>'+
		                  '</div>'+
		              '</section></div>');
			    }
			    else if(response == 404){
			    	console.log("404 project_currency");
			    	$('.loading_bar_project_contract_amount').remove();
			    	$('#contractor_name_box').show();
			    	$('.state-overview').append('<div class="col-md-4"><section class="green">'+
	                  '<div class="symbol" style="font-size: 30px; font-weight: bold;"> - </div>'+
	                  '<div class="value white">'+
	                      '<h1 class="timer" data-from="0" data-to="0" data-speed="1000">0</h1>'+
	                      '<p>Contract Amount</p>'+
	                  '</div>'+
	              	'</section></div>'+
		           		'<div class="col-md-4"><section class="panel green">'+
	                  '<div class="symbol" style="font-size: 30px; font-weight: bold;"><i class="fa fa-cube"></i></div>'+
	                  '<div class="value white">'+
	                      '<h1 class="timer" data-from="0" data-to="0" data-speed="1000">0</h1>'+
	                      '<p>Contract Item</p>'+
	                  '</div>'+
	              '</section></div>');
			    }
			    else {
			    	console.log("500 project_currency");
			    	$('#contractor_name_box').show();
			    }
		    })
		},2000)


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
		    // console.log(data.data);
		    // Foreach Loop
		    var admin_contact = false;
        	var manager_contact = false;
        	var contractor_contact = false;
        	var engineer_contact = false;
        	var owner_contact = false;
        	var surveyor_contact = false;
        	var user_contact = false; 
			jQuery.each( data.data, function( i, val )
            {	
            	// console.log(val);
                if(val.user_image_path!="")
                    var user_image_path = baseUrl+val.user_image_path;
                else
                    var user_image_path = baseUrl+'/resources/assets/img/thumbnail.jpg';
            	if(val.role == 'admin'){
                	admin_contact = true;
                	$('#admin_contact').append(
                	'<li>'+
                      '<a href="'+baseUrl+'dashboard/'+val.p_id+'/contact/'+val.id+'">'+
                          	'<span class="thumb-small">'+
                            	'<img class="circle" src="'+user_image_path+'" alt=""/>'+
                          	'</span>'+
                          '<span class="name">'+val.first_name+' '+val.last_name+'</span>'+
                          '<span class="name">'+val.position_title+', '+val.company_name+'</span>'+
                      '</a>'+
                  	'</li>'
                  	);	
                }
                else if(val.role == 'manager'){
                	manager_contact = true;
                	$('#manager_contact').append(
                	'<li>'+
                      '<a href="'+baseUrl+'dashboard/'+val.p_id+'/contact/'+val.id+'">'+
                          	'<span class="thumb-small">'+
                            	'<img class="circle" src="'+user_image_path+'" alt=""/>'+
                          	'</span>'+
                          '<span class="name">'+val.first_name+' '+val.last_name+'<br>'+val.position_title+' - '+val.company_name+'</span>'+
                      '</a>'+
                  	'</li>'
                  	);
                }
                else if(val.role == 'contractor'){
                	contractor_contact = true;
                	$('#contractor_contact').append(
                	'<li>'+
                      '<a href="'+baseUrl+'dashboard/'+val.p_id+'/contact/'+val.id+'">'+
                          	'<span class="thumb-small">'+
                            	'<img class="circle" src="'+user_image_path+'" alt=""/>'+
                          	'</span>'+
                          '<span class="name">'+val.first_name+' '+val.last_name+'<br>'+val.position_title+' - '+val.company_name+'</span>'+
                      '</a>'+
                  	'</li>'
                  	);
                }
                else if(val.role == 'engineer'){
                	engineer_contact = true;
                	$('#engineer_contact').append(
                	'<li>'+
                      '<a href="'+baseUrl+'dashboard/'+val.p_id+'/contact/'+val.id+'">'+
                          	'<span class="thumb-small">'+
                            	'<img class="circle" src="'+user_image_path+'" alt=""/>'+
                          	'</span>'+
                          '<span class="name">'+val.first_name+' '+val.last_name+'<br>'+val.position_title+' - '+val.company_name+'</span>'+
                      '</a>'+
                  	'</li>'
                  	);
                }
                else if(val.role == 'owner'){
                	owner_contact = true;
                	$('#owner_contact').append(
                	'<li>'+
                      '<a href="'+baseUrl+'dashboard/'+val.p_id+'/contact/'+val.id+'">'+
                          	'<span class="thumb-small">'+
                            	'<img class="circle" src="'+user_image_path+'" alt=""/>'+
                          	'</span>'+
                          '<span class="name">'+val.first_name+' '+val.last_name+'<br>'+val.position_title+' - '+val.company_name+'</span>'+
                          
                      '</a>'+
                  	'</li>'
                  	);
                }
                else if(val.role == 'surveyor'){
                	surveyor_contact = true;
                	$('#surveyor_contact').append(
                	'<li>'+
                      '<a href="'+baseUrl+'dashboard/'+val.p_id+'/contact/'+val.id+'">'+
                          	'<span class="thumb-small">'+
                            	'<img class="circle" src="'+user_image_path+'" alt=""/>'+
                          	'</span>'+
                          '<span class="name">'+val.first_name+' '+val.last_name+'<br>'+val.position_title+' - '+val.company_name+'</span>'+
                      '</a>'+
                  	'</li>'
                  	);
                }
                else if(val.role == 'user'){
                	user_contact = true;
                	$('#user_contact').append(
                	'<li>'+
                      '<a href="'+baseUrl+'dashboard/'+val.p_id+'/contact/'+val.id+'">'+
                          	'<span class="thumb-small">'+
                            	'<img class="circle" src="'+user_image_path+'" alt=""/>'+
                          	'</span>'+
                          '<span class="name">'+val.first_name+' '+val.last_name+'<br>'+val.position_title+' - '+val.company_name+'</span>'+
                      '</a>'+
                  	'</li>'
                  	);
                }
                else {
                	user_contact = true;
                	$('#user_contact').append(
                	'<li>'+
                      '<a href="'+baseUrl+'dashboard/'+val.p_id+'/contact/'+val.id+'">'+
                          	'<span class="thumb-small">'+
                            	'<img class="circle" src="'+user_image_path+'" alt=""/>'+
                          	'</span>'+
                          '<span class="name">'+val.first_name+' '+val.last_name+'<br>'+val.position_title+' - '+val.company_name+'</span>'+
                      '</a>'+
                  	'</li>'
                  	);
                }
			});

				if(admin_contact == true){
                	$('#admin_contact_heading').show();
                }
                if(manager_contact == true){
                	$('#manager_contact_heading').show();
                }
                if(contractor_contact == true){
                	$('#contractor_contact_heading').show();
                }
                if(engineer_contact == true){
                	$('#engineer_contact_heading').show();
                }
                if(owner_contact == true){
                	$('#owner_contact_heading').show();
                }
                if(surveyor_contact == true){
                	$('#surveyor_contact_heading').show();
                }
                if(user_contact == true){
                	$('#user_contact_heading').show();
                }
		    $(".loading_data").hide();

		})
		.fail(function(jqXHR, textStatus, errorThrown) {
		    console.log("HTTP Request Failed");
		    var response = jqXHR.responseJSON.code;
		    console.log(response);
		    if(response == 403){
		    	// window.location.href = baseUrl + "403";
		    	console.log('Contact view 403');
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

		// SUBMITTAL LOG
		setTimeout(function()
        {
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
			    $("#view_users_table_wrapper tbody tr").hide();
			    // Foreach Loop
				var submittal_complete = 0;
				var submittal_past_due = 0;
				var submittal_upcoming = 0;

				jQuery.each( data.data, function( i, val ) {
					var status = val.sr_review_type;
					if(status == 'no_exception'){
			    		status = '<span class="label label-success">No Exception</span>';
			    		submittal_complete++;
				    }
				    else if(status == 'make_corrections_noted'){
			    		status = '<span class="label label-success">Make Corrections</span>';
			    		submittal_complete++;
				    }
				    else if(status == 'revise_resubmit'){
			    		status = '<span class="label label-success">Revise & Resubmit</span>';
			    		submittal_complete++;
				    }
				    else if(status == 'rejected'){
			    		status = '<span class="label label-success">Rejected</span>';
			    		submittal_complete++;
				    }
				    else if(status == 'pending'){
				    	if(val.sub_review_type == 'yes'){
			    			status = '<span class="label label-danger" style="background:#ea6c00">Pending - Expedite Review</span>';
				    		submittal_upcoming++;
				    	}
				    	else {
			    			status = '<span class="label label-warning">Pending</span>';
			    			submittal_upcoming++;
				    	}
				    }
				    else if(status == 'past_due'){
				    	if(val.sub_review_type == 'yes'){
			    			status = '<span class="label label-danger">Overdue - Expedite Review</span>';
			    			submittal_past_due++;
				    	}
				    	else {
			    			status = '<span class="label label-danger">Past due</span>';
			    			submittal_past_due++;
				    	}
				    }
				    else {
				    	status = '<span class="label label-danger">Past due</span>';
				    	submittal_past_due++;
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

				  var date = new Date(val.sr_timestamp.replace(' ', 'T'));
	              var formatDate = $.datepicker.formatDate('yy-mm-dd', date);
				  var day = date.getDate();
				  var month = date.getMonth();
				  var year = date.getFullYear();

				  //var date_format = year + '-' + month + '-' + day;
				  var date_format = formatDate;

				  if(val.sr_respond_date == null){
				  		var respond_date = ' ';
				  }
				  else {
					  var date = new Date(val.sr_respond_date.replace(' ', 'T'));
	                  var formatDate = $.datepicker.formatDate('yy-mm-dd', date);
					  var day = date.getDate();
					  var month = date.getMonth();
					  var year = date.getFullYear();
					  //var respond_date = year + '-' + month + '-' + day;
					  var respond_date = formatDate;
				  }

				  $("#submittal_data_log tbody").append(
				  	'<tr><td>'+sub_num+'</td>'+
				  	'<td><a href="'+baseUrl+'dashboard/'+val.sub_project_id+'/submittals/'+val.sub_id+'">'+val.sub_description+'</a></td>'+
				  	'<td>'+date_format+'</td>'+
				  	'<td>'+respond_date+'</td>'+
				  	'<td>'+no_exception+'</td>'+
				  	'<td>'+make_corrections_noted+'</td>'+
				  	'<td>'+revise_resubmit+'</td>'+
				  	'<td>'+rejected+'</td>'+
				  	'<td>'+status+'</td>'+
				  	'<td>'+val.f_name+'</td>'+
	                '</tr>'
				  );

				})
		    	$("#submittal_data_log thead").show();
		    	$(".loading_submittal_detail").remove();
		    	// console.log(submittal_complete);
		    	// console.log(submittal_past_due);
		    	// console.log(submittal_upcoming);
		    	var bar_height = submittal_complete + submittal_past_due + submittal_upcoming;
		    	var bar_height1 = 100 / bar_height;
		    	// console.log(bar_height1);
		    	var bar_height_1 = bar_height1 * submittal_complete;
		    	$('.submittal_complete_width').css('width', bar_height_1+"%");
		    	$('.submittal_complete').text(submittal_complete);
		    	// console.log(bar_height_1);
		    	var bar_height_2 = bar_height1 * submittal_past_due;
		    	$('.submittal_past_due_width').css('width', bar_height_2+"%");
		    	$('.submittal_past_due').text(submittal_past_due);
		    	// console.log(bar_height_2);
		    	var bar_height_3 = bar_height1 * submittal_upcoming;
		    	$('.submittal_upcoming_width').css('width', bar_height_3+"%");
		    	$('.submittal_upcoming').text(submittal_upcoming);
		    	// console.log(bar_height_3);
		    	var bar_height_4 = bar_height_1 + bar_height_2 + bar_height_3;
		    	// sconsole.log(bar_height_4);

			})
			.fail(function(jqXHR, textStatus, errorThrown) {
			    console.log("HTTP Request Failed");
			    var response = jqXHR.responseJSON.code;
			    console.log(response);
			    if(response == 403){
			    	console.log("403")
			    	$(".loading_submittal_detail").remove();
			    	$("#submittal_data_log thead").show();
			    	$("#submittal_data_log tbody").append(
					  	'<tr><td colspan="10">You have no access</td></tr>'
					);
			    }
			    else if(response == 404){
			    	console.log("404")
			    	$(".loading_submittal_detail").remove();
			    	$("#submittal_data_log thead").show();
			    	$("#submittal_data_log tbody").append(
					  	'<tr><td colspan="10">No Submittals available</td></tr>'
					);
			    }
			    else {
			    	console.log("500")
			    	$(".loading_submittal_detail").remove();
			    	$("#submittal_data_log thead").show();
			    	$("#submittal_data_log tbody").append(
					  	'<tr><td colspan="10">System Error</td></tr>'
					);
			    }
			});
        },2000)

		// RFI LOG
		setTimeout(function()
        {
            jQuery.ajax({
			url: baseUrl +project_id+"/get-request-information-log",
			    type: "GET",
			    headers: {
			      "x-access-token": token
			    },
			    contentType: "application/json",
			    cache: false
			})
			    .done(function(data, textStatus, jqXHR) {
			    // Foreach Loop
			    var rfi_complete = 0;
				var rfi_past_due = 0;
				var rfi_upcoming = 0;

				jQuery.each( data.data, function( i, val ) {
			    	// console.log(val);
					var status = val.rir_review_status;
					if(status == 'response_due'){
						console.log("ri_date : "+val.ri_date);
						if(val.rir_review_status == 'response_due' && val.rir_review_respond == null){
							var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
							var future_date = new Date(val.ri_date);
							var numberOfDaysToAdd = 10;

							var futuredate = '';
							if (  rfi_days_type == 1 ) {
								//console.log("cal 1");
						        futuredate = future_date.setDate(future_date.getDate() + rfi_due_date); 
							}
							else {
								//console.log("cal 2");
                                futuredate = add_business_days(rfi_due_date , val.pcd_timestamp);
                                var updated_f = new Date(futuredate);
                               futuredate = updated_f.setDate(updated_f.getDate() + 0); 
							}
						//	var futuredate = future_date.setDate(future_date.getDate() + numberOfDaysToAdd);
							var now_date = new Date();
							var numberOfDaysToAdd = 0;
							var nowdate = now_date.setDate(now_date.getDate() + numberOfDaysToAdd);
							var diffDays = Math.round(Math.abs((future_date.getTime() - now_date.getTime())/(oneDay)));

							if(futuredate < nowdate){
								// console.log('less');
								var status = '<span class="label label-danger">PAST DUE</span>';
								rfi_past_due++;
							}
							else {
								rfi_upcoming++;
								// console.log('greater');
								seconds = Math.floor((futuredate - (nowdate))/1000);
							    minutes = Math.floor(seconds/60);
							    hours = Math.floor(minutes/60);
							    days = Math.floor(hours/24);

							    hours1 = hours-(days*24);
							    minutes1 = minutes-(days*24*60)-(hours1*60);
							    seconds1 = seconds-(days*24*60*60)-(hours1*60*60)-(minutes1*60);
						    	var status = "<span class='label label-warning'>"+days +" Days " + hours1 + " Hours " + minutes1 + " Minutes Left to Respond</span>";
							}
						}
						else {
								var status = ' - '
								rfi_upcoming++;
						}
				    }
				    else if(status == 'past_due'){
			    		status = '<span class="label label-danger">PAST DUE</span>';
			    		rfi_past_due++;
				    }
				    else if(status == 'response_provided'){
			    		status = '<span class="label label-success">RESPONSE PROVIDED</span>';
			    		rfi_complete++;
				    }
				    else if(status == 'additional_information_requested'){
			    		status = '<span class="label label-success">ADDTIONAL INFORMATION REQUESTED</span>';
			    		rfi_complete++;
				    }
				    else {
				    	status = '<span class="label label-info">REVIEWED</span>';
				    	rfi_complete++;
				    }

				  var date = new Date(val.ri_date.replace(' ', 'T'));
				  var day = date.getDate();
				  var month = date.getMonth()+1;
				  var year = date.getFullYear();
				  var submitted_date = year + '-' + month + '-' + day;
				  if(val.rir_review_respond == null){
				  		var respond_date = ' ';
				  }
				  else {
					  var date = new Date(val.rir_review_respond.replace(' ', 'T'));
					  var day = date.getDate();
					  var month = date.getMonth();
					  var year = date.getFullYear();
					  var respond_date = year + '-' + month + '-' + day;
				  }

				  $("#rfi_data_log tbody").append(
				  	'<tr><td>'+val.ri_id+'</td>'+
				  	'<td><a href="'+baseUrl+'dashboard/'+val.ri_project_id+'/req_for_info/'+val.ri_id+'">'+val.ri_question_request+'</a></td>'+
				  	'<td>'+val.rfi_user_company+'</td>'+
				  	'<td>'+val.review_user_company+'</td>'+
				  	'<td>'+submitted_date+'</td>'+
				  	'<td>'+respond_date+'</td>'+
				  	'<td>'+status+'</td>'+
	                '</tr>'
				  );
				});
			    $("#rfi_data_log thead").show();
			    $(".loading_rfi_detail").remove();
			    // console.log(rfi_complete);
		    	// console.log(rfi_past_due);
		    	// console.log(rfi_upcoming);
		    	var rfi_bar_height = rfi_complete + rfi_past_due + rfi_upcoming;
		    	var rfi_bar_height1 = 100 / rfi_bar_height;
		    	// console.log(rfi_bar_height1);
		    	var rfi_bar_height_1 = rfi_bar_height1 * rfi_complete;
		    	$('.rfi_complete_width').css('width', rfi_bar_height_1+"%");
		    	$('.rfi_complete').text(rfi_complete);
		    	// console.log(rfi_bar_height_1);
		    	var rfi_bar_height_2 = rfi_bar_height1 * rfi_past_due;
		    	$('.rfi_past_due_width').css('width', rfi_bar_height_2+"%");
		    	$('.rfi_past_due').text(rfi_past_due);
		    	// console.log(rfi_bar_height_2);
		    	var rfi_bar_height_3 = rfi_bar_height1 * rfi_upcoming;
		    	$('.rfi_upcoming_width').css('width', rfi_bar_height_3+"%");
		    	$('.rfi_upcoming').text(rfi_upcoming);
		    	// console.log(rfi_bar_height_3);
		    	var rfi_bar_height_4 = rfi_bar_height_1 + rfi_bar_height_2 + rfi_bar_height_3;
		    	// console.log(rfi_bar_height_4);

			})
			.fail(function(jqXHR, textStatus, errorThrown) {
			    console.log("HTTP Request Failed");
			    var response = jqXHR.responseJSON.code;
			    console.log(response);
			    if(response == 403){
			    	console.log("403")
			    	$(".loading_rfi_detail").remove();
			    	$("#rfi_data_log thead").show();
			    	$("#rfi_data_log tbody").append(
					  	'<tr><td colspan="7">You have no access</td></tr>'
					);
			    }
			    else if(response == 404){
			    	console.log("404")
			    	$(".loading_rfi_detail").remove();
			    	$("#rfi_data_log thead").show();
			    	$("#rfi_data_log tbody").append(
					  	'<tr><td colspan="7">No RFIs available</td></tr>'
					);
			    }
			    else {
			    	console.log("500")
			    	$(".loading_rfi_detail").remove();
			    	$("#rfi_data_log thead").show();
			    	$("#rfi_data_log tbody").append(
					  	'<tr><td colspan="7">System Error</td></tr>'
					);
			    }
			});
        },2000)


		// setTimeout(function()
  //       {
  //          jQuery.ajax({
		// 	url: baseUrl +project_id+"/get-survey-log",
		// 	    type: "GET",
		// 	    headers: {
		// 	      "x-access-token": token
		// 	    },
		// 	    contentType: "application/json",
		// 	    cache: false
		// 	})
		// 	.done(function(data, textStatus, jqXHR) {
		// 	    // console.log(data.data);
		// 	    // Foreach Loop
		// 	    var survey_complete = 0;
		// 		var survey_past_due = 0;
		// 		var survey_upcoming = 0;

		// 		jQuery.each( data.data, function( i, val ) {
		// 			// console.log(val);
		// 			var status = val.sr_review_status;
		// 			if(status == 'pending'){
		// 	    		if(val.sr_review_status == 'pending' && val.rir_review_respond == null){
		// 					var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
		// 					var firstDate = new Date();
		// 					var secondDate = new Date(val.sur_request_completion_date);
		// 					var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)));
		// 					if(firstDate.getTime() < secondDate.getTime()){
		// 						// console.log('less');
		// 						seconds = Math.floor((secondDate - (firstDate))/1000);
		// 						minutes = Math.floor(seconds/60);
		// 					    hours = Math.floor(minutes/60);
		// 					    days = Math.floor(hours/24);
		// 						// console.log(hours);
		// 						hours1 = hours-(days*24);
		// 						minutes1 = minutes-(days*24*60)-(hours1*60);
		// 				    	seconds = seconds-(days*24*60*60)-(hours*60*60)-(minutes1*60);
		// 						// console.log(hours1);
		// 						var status = "<span class='label label-warning'>"+days +" Days " + hours1 + " Hours " + minutes1 + " Minutes Left to Respond</span>";
		// 						survey_upcoming++;
		// 					}
		// 					else {
		// 						// console.log('greater');
		// 						var status = '<span class="label label-danger">PAST DUE</span>';
		// 						survey_past_due++;
		// 					}
		// 				}
		// 				else {
		// 						var status = ' - '
		// 						survey_upcoming++;
		// 				}
		// 		    }
		// 		    else if(status == 'past_due'){
		// 	    		status = '<span class="label label-danger">PAST DUE</span>';
		// 	    		survey_past_due++;
		// 		    }
		// 		    else {
		// 		    	status = '<span class="label label-success">COMPLETED</span>';
		// 		    	survey_complete++;
		// 		    }
		// 		  var date = new Date(val.sur_date.replace(' ', 'T'));
	 //              var formatDate = $.datepicker.formatDate('yy-mm-dd', date);
		// 		  var day = date.getDate();
		// 		  var month = date.getMonth();
		// 		  var year = date.getFullYear();
		// 		  //var submitted_date = year + '-' + month + '-' + day;
		// 		  var submitted_date = formatDate;

		// 		  if(val.sur_request_completion_date == null){
		// 		  		var respond_date = ' ';
		// 		  }
		// 		  else {
		// 			  var date = new Date(val.sur_request_completion_date.replace(' ', 'T'));
	 //                  var formatDate = $.datepicker.formatDate('yy-mm-dd', date);
		// 			  var day = date.getDate();
		// 			  var month = date.getMonth();
		// 			  var year = date.getFullYear();
		// 			  //var respond_date = year + '-' + month + '-' + day;
		// 			  var respond_date = formatDate;
		// 		  }

		// 		   	var survey_req_path = val.survey_req_path;
		// 		    if(survey_req_path == null){
		// 		  	 	var	survey_req_path_value = '-';
		// 		  	}
		// 		  	else {
		// 		  		var survey_req_path_value = '<a href="'+baseUrl+val.survey_req_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
		// 		  	}

		// 		  	var survey_rew_path = val.survey_rew_path;
		// 		    if(survey_rew_path == null){
		// 		  	 	var	survey_rew_path_value = '-';
		// 		  	}
		// 		  	else {
		// 		  		var survey_rew_path_value = '<a href="'+baseUrl+val.survey_rew_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
		// 		  	}

		// 		  	$("#survey_data_log tbody").append(
		// 		  	'<tr><td>'+val.sur_id+'</td>'+
		// 		  	'<td><a href="'+baseUrl+'dashboard/'+val.sur_project_id+'/survey/'+val.sur_id+'">'+val.sur_description+'</a></td>'+
		// 		  	'<td>'+submitted_date+'</td>'+
		// 		  	'<td>'+respond_date+'</td>'+
		// 		  	'<td>'+survey_req_path_value+'</td>'+
		// 		  	'<td>'+survey_rew_path_value+'</td>'+
		// 		  	'<td>'+status+'</td>'+
	 //                '</tr>'
		// 		  );
		// 		});
		// 	    $("#survey_data_log thead").show();
		// 	    $(".loading_survey_detail").remove();
		// 	    // console.log(survey_complete);
		//     	// console.log(survey_past_due);
		//     	// console.log(survey_upcoming);
		//     	var survey_bar_height = survey_complete + survey_past_due + survey_upcoming;
		//     	var survey_bar_height1 = 100 / survey_bar_height;
		//     	// console.log(survey_bar_height1);
		//     	var survey_bar_height_1 = survey_bar_height1 * survey_complete;
		//     	$('.survey_complete_width').css('width', survey_bar_height_1+"%");
		//     	$('.survey_complete').text(survey_complete);
		//     	// console.log(survey_bar_height_1);
		//     	var survey_bar_height_2 = survey_bar_height1 * survey_past_due;
		//     	$('.survey_past_due_width').css('width', survey_bar_height_2+"%");
		//     	$('.survey_past_due').text(survey_past_due);
		//     	// console.log(survey_bar_height_2);
		//     	var survey_bar_height_3 = survey_bar_height1 * survey_upcoming;
		//     	$('.survey_upcoming_width').css('width', survey_bar_height_3+"%");
		//     	$('.survey_upcoming').text(survey_upcoming);
		//     	// console.log(survey_bar_height_3);
		//     	var survey_bar_height_4 = survey_bar_height_1 + survey_bar_height_2 + survey_bar_height_3;
		//     	// console.log(survey_bar_height_4);
		// 	})
		// 	.fail(function(jqXHR, textStatus, errorThrown) {
		// 	    console.log("HTTP Request Failed");
		// 	    var response = jqXHR.responseJSON.code;
		// 	    console.log(response);
		// 	    if(response == 403){
		// 	    	console.log("403")
		// 	    	$(".loading_survey_detail").remove();
		// 	    	$("#survey_data_log thead").show();
		// 	    	$("#survey_data_log tbody").append(
		// 			  	'<tr><td colspan="7">You have no access</td></tr>'
		// 			);
		// 	    }
		// 	    else if(response == 404){
		// 	    	console.log("404")
		// 	    	$(".loading_survey_detail").remove();
		// 	    	$("#survey_data_log thead").show();
		// 	    	$("#survey_data_log tbody").append(
		// 			  	'<tr><td colspan="7">No Survey available</td></tr>'
		// 			);
		// 	    }
		// 	    else {
		// 	    	console.log("500")
		// 	    	$(".loading_survey_detail").remove();
		// 	    	$("#survey_data_log thead").show();
		// 	    	$("#survey_data_log tbody").append(
		// 			  	'<tr><td colspan="7">System Error</td></tr>'
		// 			);
		// 	    }
		// 	});
  //       },2000)

		

		// COR
		setTimeout(function()
		{
			jQuery.ajax({
			url: baseUrl+project_id+"/change_order_request_item",
			    type: "GET",
			    headers: {
			      "x-access-token": token
			    },
			    contentType: "application/json",
			    cache: false
			})
			.done(function(data, textStatus, jqXHR) {
			    console.log(data.data);

			    var r_cor_complete = 0;
				var r_cor_past_due = 0;
				var r_cor_upcoming = 0;

			    $("#request_change_order thead").show();
			    $(".loading_cor_detail").remove();
			    // Foreach Loop 
			    var count = 1;
			    var counts = 1;
			    jQuery.each( data.data, function( i, val ) {
			        var status_cm = '';
			        var status_owner = '';
			        if(val.pcd_approved_by_cm == null || val.pcd_approved_by_cm == "0000-00-00"){
			            var pcd_approved_by_cm = '<span class="label label-warning">PENDING</span>';
			            var status_cm = '<span class="label label-warning">PENDING CM REVIEW</span><br/>';
			            r_cor_upcoming++;
			        }
			        else {
			            var pcd_approved_by_cm = val.pcd_approved_by_cm;
			            r_cor_complete++;
			        }

			        if(val.pcd_approved_by_owner == null || val.pcd_approved_by_owner == "0000-00-00"){
			            var pcd_approved_by_owner = '<span class="label label-warning">PENDING</span>';
			            var status_owner = '<span class="label label-warning">PENDING OWNER REVIEW</span><br/>';
			            r_cor_upcoming++;
			        }
			        else {
			            var pcd_approved_by_owner = val.pcd_approved_by_owner;
			            r_cor_complete++;
			        }

			        if(val.pcd_rfi == '[]'){
			            var t = $('#request_change_order').DataTable();
			            t.row.add([
			               count, // val.pcd_parent_cor,
			               val.agency_name,
			               val.pco_date,
			               '<a href="'+baseUrl+'dashboard/'+project_id+'/change_order_request_review/'+val.pcd_id+'/update">'+val.pcd_description+'</a>',
			               pcd_approved_by_cm,
			               pcd_approved_by_owner,
			               val.pcd_price,
			               val.pcd_days,
			               status_cm + status_owner,
			            ]).draw( false );
			            count++;  
			        }
			        else {
			            console.log(val.pcd_approved_by_cm);
			            console.log(val.pcd_approved_by_owner);
			            console.log("change order !!");
			            console.log(val);
			            if(val.pcd_approved_by_cm == null || val.pcd_approved_by_cm == "0000-00-00" || val.pcd_approved_by_owner == null || val.pcd_approved_by_owner == "0000-00-00"){
			                var oneDay = 24*60*60*1000;
			                change_order_days_type 
			                console.log('change order :' + change_order_days_type);
			                console.log('change order date :' + change_order_due_date);
              
			                var future_date = new Date(val.pcd_timestamp);
			                console.log("future Date !!");
			                console.log(future_date);
			                var numberOfDaysToAdd = 10;
                            var futuredate = '';
							if ( change_order_days_type == 1 ) {
								//console.log("cal 1");
						        futuredate = future_date.setDate(future_date.getDate() + change_order_due_date); 
							}
							else {
								//console.log("cal 2");
                                futuredate = add_business_days(change_order_due_date , val.pcd_timestamp);
                                var updated_f = new Date(futuredate);
                               futuredate = updated_f.setDate(updated_f.getDate() + 0); 
							}
                              //console.log("updated future Date !!");

								//console.log("updated : "+ futuredate);
			              //  var futuredate = future_date.setDate(future_date.getDate() + numberOfDaysToAdd); 
			                //console.log("updated : "+ futuredate);
			                var now_date = new Date();
			                var numberOfDaysToAdd = 0;
			                var nowdate = now_date.setDate(now_date.getDate() + numberOfDaysToAdd); 
			                // console.log(future_date);
			                // console.log(now_date);
			               //  console.log("updated last "+futuredate);
			                 //console.log("now "+nowdate);
			                var diffDays = Math.round(Math.abs((future_date.getTime() - now_date.getTime())/(oneDay)));

			                if(futuredate < nowdate){
			                    // console.log('less');
			                    var potential_status = '<span class="label label-danger">PAST DUE</span>';
			                    r_cor_past_due++;
			                }
			                else {
			                    // console.log('greater');
			                    seconds = Math.floor((futuredate - (nowdate))/1000);
			                    minutes = Math.floor(seconds/60);
			                    hours = Math.floor(minutes/60);
			                    days = Math.floor(hours/24);
			                    
			                    hours1 = hours-(days*24);
			                    minutes1 = minutes-(days*24*60)-(hours1*60);
			                    seconds1 = seconds-(days*24*60*60)-(hours1*60*60)-(minutes1*60);

			                    var potential_status = "<span class='label label-warning'>"+days +" Days " + hours1 + " Hours " + minutes1 + " Minutes Left to Respond</span>";
			                    r_cor_upcoming++;
			                }
			            }
			            else {
			                    var potential_status = '<span class="label label-success">APPROVED</span>';
			                    r_cor_complete++;
			            }

			            

			            jQuery.ajax({
			            url: baseUrl+val.pcd_id+"/get_item_rfi",
			                type: "GET",
			                headers: {
			                  "Content-Type": "application/json",
			                  "x-access-token": token
			                },
			                contentType: "application/json",
			                cache: false
			            })
			            .done(function(data, textStatus, jqXHR) {
			                // console.log(data.data);
			                window.rfi_final = '';
			                jQuery.each(data.data, function( i, val ) {
			                    rfi_final += "RFI "+val.ri_id+" : "+ val.ri_question_request+"<br/>"; 
			                    // console.log(rfi_final);
			                });

			                var t = $('#potential_change_order').DataTable();
			                t.row.add([
			                   counts, // val.pcd_parent_cor,
			                   val.agency_name,
			                   val.pco_date,
			                   '<a href="'+baseUrl+'dashboard/'+project_id+'/change_order_request_review/'+val.pcd_id+'/update">'+val.pcd_description+'</a>',
			                   pcd_approved_by_cm,
			                   pcd_approved_by_owner,
			                   rfi_final,
			                   val.pcd_price,
			                   val.pcd_days,
			                   potential_status,
			                ]).draw( false );  
			                counts++;

			            })
			            .fail(function(jqXHR, textStatus, errorThrown) {
			                console.log("HTTP Request Failed");
			                var response = jqXHR.responseJSON.code;
			                console.log(response);
			            }) 
			        }
			    });
			    // $( "h2" ).appendTo( $( ".container" ) );
			   
			    $("#request_change_order").show();
			    $(".loading_data").hide();
			    $(".hide_r_cor_permission").show();
			    // console.log(r_cor_complete);
		    	// console.log(r_cor_past_due);
		    	// console.log(r_cor_upcoming);
		    	var r_cor_bar_height = r_cor_complete + r_cor_past_due + r_cor_upcoming;
		    	var r_cor_bar_height1 = 100 / r_cor_bar_height;
		    	// console.log(r_cor_bar_height1);
		    	var r_cor_bar_height_1 = r_cor_bar_height1 * r_cor_complete;
		    	$('.r_cor_complete_width').css('width', r_cor_bar_height_1+"%");
		    	$('.r_cor_complete').text(r_cor_complete);
		    	console.log(r_cor_bar_height_1);
		    	var r_cor_bar_height_2 = r_cor_bar_height1 * r_cor_past_due;
		    	$('.r_cor_past_due_width').css('width', r_cor_bar_height_2+"%");
		    	$('.r_cor_past_due').text(r_cor_past_due);
		    	console.log(r_cor_bar_height_2);
		    	var r_cor_bar_height_3 = r_cor_bar_height1 * r_cor_upcoming;
		    	$('.r_cor_upcoming_width').css('width', r_cor_bar_height_3+"%");
		    	$('.r_cor_upcoming').text(r_cor_upcoming);
		    	console.log(r_cor_bar_height_3);
		    	var r_cor_bar_height_4 = r_cor_bar_height_1 + r_cor_bar_height_2 + r_cor_bar_height_3;
		    	console.log(r_cor_bar_height_4);

			})
			.fail(function(jqXHR, textStatus, errorThrown) {
			    console.log("HTTP Request Failed");
			    var response = jqXHR.responseJSON.code;
			    console.log(response);
			    // if(response == 403){
			    //     window.location.href = baseUrl + "403";
			    // }
			    // else if(response == 404){
			    //     // window.location.href = baseUrl + "404";
			    //     $("#request_change_order").show();
			    //     $(".loading_data").hide();
			    // }
			    // else {
			    //     window.location.href = baseUrl + "500";
			    // }


			    if(response == 403){
			    	console.log("403")
			    	$(".loading_cor_detail").remove();
			    	$("#request_change_order thead").show();
			    	$("#potential_change_order thead").show();
			    	$("#request_change_order tbody").append(
					  	'<tr><td colspan="9">You have no access</td></tr>'
					);
					$("#potential_change_order tbody").append(
					  	'<tr><td colspan="9">You have no access</td></tr>'
					);
			    }
			    else if(response == 404){
			    	console.log("404")
			    	$(".loading_cor_detail").remove();
			    	$("#request_change_order thead").show();
			    	$("#potential_change_order thead").show();
			    	$("#request_change_order tbody").append(
					  	'<tr><td colspan="9">No Change Order Requests available</td></tr>'
					);
					$("#potential_change_order tbody").append(
					  	'<tr><td colspan="10">No Potential Change Order Requests available</td></tr>'
					);
			    }
			    else {
			    	console.log("500")
			    	$(".loading_cor_detail").remove();
			    	$("#request_change_order thead").show();
			    	$("#potential_change_order thead").show();
			    	$("#request_change_order tbody").append(
					  	'<tr><td colspan="7">System Error</td></tr>'
					);
					$("#potential_change_order tbody").append(
					  	'<tr><td colspan="7">System Error</td></tr>'
					);
			    }
			});
		},3000)


		setTimeout(function()
        {
            // Check Submittal Permission
            var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
			var check_permission = jQuery.inArray("submittal_log", check_user_access );
			// console.log(check_permission);
			if(check_permission < 1){
				$('.hide_submittal_permission').remove();
			}
			else {
				$('.hide_submittal_permission').show();
			}

			// Check RFI Permission
			var check_permission = jQuery.inArray("rfi_log", check_user_access );
			// console.log(check_permission);
			if(check_permission < 1){
				$('.hide_rfi_permission').remove();
			}
			else {
				$('.hide_rfi_permission').show();
			}

			// // Check Survey Permission
			// var check_permission = jQuery.inArray("survey_log", check_user_access );
			// console.log(check_permission);
			// if(check_permission < 1){
			// 	$('.hide_survey_permission').remove();
			// }
			// else {
			// 	$('.hide_survey_permission').show();
			// }

			// Check Submittal Permission
            var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
			var check_permission = jQuery.inArray("cor_log", check_user_access );
			// console.log(check_permission);
			if(check_permission < 1){
				$('.hide_cor_permission').remove();
			}
			else {
				$('.hide_cor_permission').show();
			}

			$('.loading_progress_bar').remove();
        },3000)
        
        jQuery.ajax({
           
            url: baseUrl + "users/get_user_new_role",
            type: "POST",
            data:{"user_id":user_id,"u_company_id":u_company_name,"project_id":project_id},
            headers: {
                "x-access-token": token
              },
              contentType: "application/x-www-form-urlencoded",
            cache: false
        })
        .done(function(data, textStatus, jqXHR)
        {
            window.localStorage.setItem("u_new_role", data.new_role);
            console.log(data.new_role);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            // var response = jqXHR.responseJSON.code;
            console.log(jqXHR.responseJSON);
        });
});


function add_business_days(days , date) {
  var now = new Date(date);
  var dayOfTheWeek = now.getDay();
  var calendarDays = days;
  var deliveryDay = dayOfTheWeek + days;
  if (deliveryDay >= 5) {
    //deduct this-week days
    days -= 5 - dayOfTheWeek;
    //count this coming weekend
    calendarDays += 2;
    //how many whole weeks?
    deliveryWeeks = Math.floor(days / 5);
    //two days per weekend per week
    calendarDays += deliveryWeeks * 2;
  }
  now.setTime(now.getTime() + calendarDays * 24 * 60 * 60 * 1000);
  return now;
}