  	$(document).ready(function() {

     	// Get login user profile data
     	// $("#view_users_table_wrapper").hide();
		var role = localStorage.getItem('u_role');
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		project_id = url[ url.length - 2 ]; // projects
		// console.log(project_id);
		type = url[ url.length - 1 ]; // projects
		// console.log(type);

		// Check View All Permission
		var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
		var check_permission = jQuery.inArray( "contract_item_view_all", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
		}

		// Bid Total Amount 
	    jQuery.ajax({
	    url: baseUrl+project_id+"/bid-items-total",
	        type: "GET",
	        headers: {
	          "Content-Type": "application/json",
	          "x-access-token": token
	        },
	        contentType: "application/json",
	        cache: false
	    })
	    .done(function(data, textStatus, jqXHR) {
	    	console.log(data.data[0].total_item);
	        // $(".project_amount").text(data.data[0].total_amount);
	        $(".project_amount").text(ReplaceNumberWithCommas(data.data[0].total_amount));

	        var total_item = data.data[0].total_item;
	        // Actual Bid Total Quantity 
		    jQuery.ajax({
		    url: baseUrl+project_id+"/get-bid-items-qty",
		        type: "GET",
		        headers: {
		          "Content-Type": "application/json",
		          "x-access-token": token
		        },
		        contentType: "application/json",
		        cache: false
		    })
		    .done(function(data, textStatus, jqXHR) {
		    	console.log(data.description.piq_qty);
		    	var actual_item_qty = data.description.piq_qty;
		    	$("#actual_qty").text(actual_item_qty);
		        $("#enter_qty").text(total_item);
		    	var diff_qty = actual_item_qty - total_item;
		    	console.log(diff_qty);
		    	if(diff_qty < 0){
		    		$('#exceeded_qty').show();
		    	}
		    	else {
		    		$('#exceeded_qty').hide();
		    	}
		    })
		    .fail(function(jqXHR, textStatus, errorThrown) {
		        console.log("HTTP Request Failed");
		        var response = jqXHR.responseJSON.code;
		        if(response == 403){
		        	console.log("403");
		            // window.location.href = baseUrl + "403";
		        }
		        else if(response == 404){
		             console.log("404");
		        }
		        else {
		             console.log("Bid Actual Quantity Total 500");
		            // window.location.href = baseUrl + "500";
		        }
		    }) 
	    })
	    .fail(function(jqXHR, textStatus, errorThrown) {
	        console.log("HTTP Request Failed");
	        var response = jqXHR.responseJSON.code;
	        if(response == 403){
	        	console.log("403");
	            // window.location.href = baseUrl + "403";
	        }
	        else if(response == 404){
	             console.log("404");
	        }
	        else {
	        	console.log("Bid Item Total 500");
	            // window.location.href = baseUrl + "500";
	        }
	    }) 

	        jQuery.ajax({
			url: baseUrl+project_id+"/bid-items",
			    type: "GET",
			    headers: {
			      "x-access-token": token
			    },
			    contentType: "application/json",
			    cache: false
			})
		    .done(function(data, textStatus, jqXHR) {

		    	window.project_name = data.data[0].p_name;
		    	$('#project_name_title').text("Project: " + window.project_name);
			    var specific_project_name = 'Contract item for Project: ' + window.project_name;
			   	console.log(specific_project_name);
			    $('#view_users_table').DataTable({
	                dom: 'Bfrtip',
	                buttons: [
	                    // {
	                    //     extend: 'copyHtml5',
	                    //     exportOptions: {
	                    //         columns: [ 0, 1, 2, 3, 4, 5, 6 ]
	                    //     },
	                    //     message: specific_project_name,
	                    // },
	                    {
	                        extend: 'csvHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'pdfHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'excelHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'print',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
	                        },
	                        message: specific_project_name,
	                    }
	                ]
	            });

			    // console.log(data.data);
			    $("#view_users_table_wrapper").hide();
			    // Foreach Loop 
			    var count = 1;
				jQuery.each( data.data, function( i, val ) {

					// Check Update Permission
					var check_permission = jQuery.inArray("contract_item_update", check_user_access );
					console.log(check_permission);
					if(check_permission < 1){
						var update_permission = '';
					}
					else {
						var update_permission = '<a href="'+baseUrl+'dashboard/'+val.pbi_project_id+'/contract_item/'+val.pbi_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
					}

					var status = val.pbi_status;
					if(status == 'active'){
			    	status = '<span class="label label-success">Active</span>';
				    }
				    else {
				    	status = '<span class="label label-danger">Inactive</span>';
				    }
				  	
				  	var t = $('#view_users_table').DataTable();
					t.row.add( [
			           count,
			           val.pbi_item_description,
			           val.pbi_item_unit,
			           val.pbi_item_qty,
			           val.currency_symbol+' '+ ReplaceNumberWithCommas(val.pbi_item_unit_price),
			           val.currency_symbol+' '+ ReplaceNumberWithCommas(val.pbi_item_total_price),
			           status,
			           update_permission
			       	]).draw( false );
					count++;
				});
			    $("#view_users_table_wrapper").show();
			    $(".loading_data").hide();
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
			    console.log("HTTP Request Failed");
			    var response = jqXHR.responseJSON.code;
			    console.log(response);
			    if(response == 403){
			    	// window.location.href = baseUrl + "403";
			    	console.log("403");
			    }
			    else if(response == 404){
			    	// alert('faizan');
			    	// window.location.href = baseUrl + "404";
			    	$("#view_users_table_wrapper").show();
			    	$(".loading_data").hide();
			    }
			    else {
			    	console.log("Bid Item 500");
			    	// window.location.href = baseUrl + "500";
			    }
			}); 

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
			    console.log(data.data.cur_symbol);
	            $('.project_currency').text(data.data.cur_symbol+' ');
			})
		})

		setTimeout(function()
        {
			// Check Add Permission
			var check_permission = jQuery.inArray( "contract_item_add", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_add_permission').remove();
			}
			else {
				$('.hide_add_permission').show();
			}

		},2000)
    });