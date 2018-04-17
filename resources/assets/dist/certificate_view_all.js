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
		var check_permission = jQuery.inArray( "certificate_view_all", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
		}

		// // Get Project Currency
	 //    jQuery.ajax({
	 //    url: baseUrl+project_id+"/project_setting_get/project_currency",
	 //        type: "GET",
	 //        headers: {
	 //          "x-access-token": token
	 //        },
	 //        contentType: "application/json",
	 //        cache: false
	 //    })
	 //    .done(function(data, textStatus, jqXHR) {
	 //        // console.log(data.data.pset_meta_value);
	 //        var project_currency_id = data.data.pset_meta_value;
	 //        jQuery.ajax({
	 //        url: baseUrl + "currency/"+project_currency_id,
	 //            type: "GET",
	 //            headers: {
	 //              "Content-Type": "application/json",
	 //              "x-access-token": token
	 //            },
	 //            contentType: "application/json",
	 //            cache: false
	 //        })
	 //        .done(function(data, textStatus, jqXHR) {
	 //            console.log(data.data.cur_symbol);
	 //            $('.project_currency').text(data.data.cur_symbol+'');
	 //        })
	 //    })


		jQuery.ajax({
		url: baseUrl +project_id+"/certificate",
		    type: "GET",
		    headers: {
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
        })
		    .done(function(data, textStatus, jqXHR) {
		    console.log(data.data);
		    	window.project_name = data.data[0].p_name;
		    	$('#project_name_title').text("Project: " + window.project_name);
			    var specific_project_name = 'Certificate for Project: ' + window.project_name;
			   	console.log(specific_project_name);
			    $('#view_users_table').DataTable({
	                dom: 'Bfrtip',
	                buttons: [
	                    // {
	                    //     extend: 'copyHtml5',
	                    //     exportOptions: {
	                    //         columns: [ 0, 1, 2, 3, 5, 6, 8, 9, 11, 12, 15 ]
	                    //     },
	                    //     message: specific_project_name,
	                    // },
	                    {
	                        extend: 'csvHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 5, 6, 8, 9, 11, 12, 15 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'pdfHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 5, 6, 8, 9, 11, 12, 15 ]
	                        },
	                        orientation: 'landscape',
                			pageSize: 'LEGAL',
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'excelHtml5',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 5, 6, 8, 9, 11, 12, 15 ]
	                        },
	                        message: specific_project_name,
	                    },
	                    {
	                        extend: 'print',
	                        exportOptions: {
	                            columns: [ 0, 1, 2, 3, 5, 6, 8, 9, 11, 12, 15 ]
	                        },
	                        orientation: 'landscape',
                			pageSize: 'LEGAL',
	                        header : false,
	                        message: specific_project_name,
	                    }
	                ]
	            });

		    $("#view_users_table_wrapper tbody tr").hide();
		    // Foreach Loop 
		    var count = 1;
			jQuery.each( data.data, function( i, val ) {

				// Check Update Permission
				var check_permission = jQuery.inArray("certificate_update", check_user_access );
				console.log(check_permission);
				if(check_permission < 1){
					var update_permission = '';
				}
				else {
					var update_permission = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/certificate/'+val.ci_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
				}

				var status = val.status;
				if(status == 'active'){
		    	status = '<span class="label label-success">Active</span>';
			    }
			    else {
			    	status = '<span class="label label-danger">Inactive</span>';
			    }
			  	
			  	var liability_cert_path = val.liability_cert_path;
			  	var liability_cert_path_value;
			  	if(liability_cert_path == null){
			  		liability_cert_path_value = 'N/A';
			  	}
			  	else {
			  		if(val.status == 'active'){
			  			liability_cert_path_value = '<a href="'+baseUrl+val.liability_cert_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  		else {
			  			liability_cert_path_value = '<a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  	}

			  	var work_comp_cert_path = val.work_comp_cert_path;
			  	var work_cert_path_value;
			  	if(work_comp_cert_path == null){
			  		work_cert_path_value = 'N/A';
			  	}
			  	else {
			  		if(val.status == 'active'){
			  			work_cert_path_value = '<a href="'+baseUrl+val.work_comp_cert_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  		else {
			  			work_cert_path_value = '<a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  	}

			  	var auto_liability_cert_path = val.auto_liability_cert_path;
			  	var auto_cert_path_value;
			  	if(auto_liability_cert_path == null){
			  		auto_cert_path_value = 'N/A';
			  	}
			  	else {
			  		if(val.status == 'active'){
			  			auto_cert_path_value = '<a href="'+baseUrl+val.auto_liability_cert_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  		else {
			  			auto_cert_path_value = '<a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  	}

			  	var umbrella_liability_cert_path = val.umbrella_liability_cert_path;
			  	var umbrella_liability;
			  	if(umbrella_liability_cert_path == null){
			  		umbrella_liability = 'N/A';
			  	}
			  	else {
			  		if(val.status == 'active'){
			  			umbrella_liability = '<a href="'+baseUrl+val.umbrella_liability_cert_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  		else {
			  			umbrella_liability = '<a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  	}
			  	
			  	var doc_cert_path = val.doc_cert_path;
			  	var doc_cert_path;
			  	if(doc_cert_path == null){
			  		doc_cert = 'N/A';
			  	}
			  	else {
			  		if(val.status == 'active'){
			  			doc_cert = '<a href="'+baseUrl+val.doc_cert_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  		else {
			  			doc_cert = '<a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  		}
			  	}

			  	if(val.status == 'active'){
		  			var view_single = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/certificate/'+val.ci_id+'" class="btn btn-success btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-search"></i></a>';
		  		}
		  		else {
		  			var view_single = '<a href="'+baseUrl+'404" class="btn btn-success btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-search"></i></a>';
		  		}


		  		if(val.liability_exp == '0000-00-00' || val.liability_exp == null){
					var liability_exp = 'N/A';
				}
				else {
					var someDate_now = new Date(val.liability_exp);
					var numberOfDaysToAdd = 0;
					var date_liability_exp = someDate_now.setDate(someDate_now.getDate() + numberOfDaysToAdd); 
					var someDate = new Date();
					var numberOfDaysToAdd = 0;
					var date_now = someDate.setDate(someDate.getDate() + numberOfDaysToAdd); 
					console.log(date_liability_exp);
					console.log(date_now);
					seconds = Math.floor((date_now - (date_liability_exp))/1000);
				    minutes = Math.floor(seconds/60);
				    hours = Math.floor(minutes/60);
				    days = Math.floor(hours/24);
					if(date_liability_exp  < date_now){
						var liability_exp = "<span class='label label-danger'>"+val.liability_exp+"</span>";
					}
					else {
						var liability_exp = "<span class='label label-warning'>"+val.liability_exp+"</span>";
					}
				}

				if(val.work_comp_exp == '0000-00-00' || val.work_comp_exp == null){
					var work_comp_exp = 'N/A';
				}
				else {
					var someDate_now = new Date(val.work_comp_exp);
					var numberOfDaysToAdd = 0;
					var date_work_comp_exp = someDate_now.setDate(someDate_now.getDate() + numberOfDaysToAdd); 
					var someDate = new Date();
					var numberOfDaysToAdd = 0;
					var date_now = someDate.setDate(someDate.getDate() + numberOfDaysToAdd); 
					console.log(date_work_comp_exp);
					console.log(date_now);
					seconds = Math.floor((date_now - (date_work_comp_exp))/1000);
				    minutes = Math.floor(seconds/60);
				    hours = Math.floor(minutes/60);
				    days = Math.floor(hours/24);
					if(date_work_comp_exp  < date_now){
						var work_comp_exp = "<span class='label label-danger'>"+val.work_comp_exp+"</span>";
					}
					else {
						var work_comp_exp = "<span class='label label-warning'>"+val.work_comp_exp+"</span>";
					}
				}

				if(val.auto_liability_exp == '0000-00-00' || val.auto_liability_exp == null){
					var auto_liability_exp = 'N/A';
				}
				else {
					var someDate_now = new Date(val.auto_liability_exp);
					var numberOfDaysToAdd = 0;
					var date_auto_liability_exp = someDate_now.setDate(someDate_now.getDate() + numberOfDaysToAdd); 
					var someDate = new Date();
					var numberOfDaysToAdd = 0;
					var date_now = someDate.setDate(someDate.getDate() + numberOfDaysToAdd); 
					console.log(date_auto_liability_exp);
					console.log(date_now);
					seconds = Math.floor((date_now - (date_auto_liability_exp))/1000);
				    minutes = Math.floor(seconds/60);
				    hours = Math.floor(minutes/60);
				    days = Math.floor(hours/24);
					if(date_auto_liability_exp  < date_now){
						var auto_liability_exp = "<span class='label label-danger'>"+val.auto_liability_exp+"</span>";
					}
					else {
						var auto_liability_exp = "<span class='label label-warning'>"+val.auto_liability_exp+"</span>";
					}
				}

				if(val.umbrella_liability_exp == '0000-00-00' || val.umbrella_liability_exp == null){
					var umbrella_liability_exp = 'N/A';
				}
				else {
					var someDate_now = new Date(val.umbrella_liability_exp);
					var numberOfDaysToAdd = 0;
					var date_umbrella_liability_exp = someDate_now.setDate(someDate_now.getDate() + numberOfDaysToAdd); 
					var someDate = new Date();
					var numberOfDaysToAdd = 0;
					var date_now = someDate.setDate(someDate.getDate() + numberOfDaysToAdd); 
					console.log(date_umbrella_liability_exp);
					console.log(date_now);
					seconds = Math.floor((date_now - (date_umbrella_liability_exp))/1000);
				    minutes = Math.floor(seconds/60);
				    hours = Math.floor(minutes/60);
				    days = Math.floor(hours/24);
					if(date_umbrella_liability_exp  < date_now){
						var umbrella_liability_exp = "<span class='label label-danger'>"+val.umbrella_liability_exp+"</span>";
					}
					else {
						var umbrella_liability_exp = "<span class='label label-warning'>"+val.umbrella_liability_exp+"</span>";
					}
				}
                                if(val.liability_currency=="" || val.liability_currency==null)
                                    var liability_currency = '';        
                                else
                                    var liability_currency = val.liability_currency;
				if(val.liability_limit == 0 || val.liability_limit == null){
					var liability_limit = 'N/A';
				}
				else {
					var liability_limit = liability_currency+''+ ReplaceNumberWithCommas(val.liability_limit);
				}
                                if(val.umbrella_liability_symbol=="" || val.umbrella_liability_symbol==null)
                                    var umbrella_liability_symbol = 'N/A';        
                                else
                                    var umbrella_liability_symbol = val.umbrella_liability_symbol;
				if(val.work_comp_limit == 0 || val.work_comp_limit == null){
					var work_comp_limit = 'N/A';
				}
				else {
					var work_comp_limit = umbrella_liability_symbol+''+ ReplaceNumberWithCommas(val.work_comp_limit);
				}
                                
				
				if(val.auto_liability_limit == 0 || val.auto_liability_limit == null){
					var auto_liability_limit = 'N/A';
				}
				else {
					var auto_liability_limit = liability_currency+''+ ReplaceNumberWithCommas(val.auto_liability_limit);
				}


				if(val.umbrella_liability_limit == 0 || val.umbrella_liability_limit == null){
					var umbrella_liability_limit = 'N/A';
				}
				else {
					var umbrella_liability_limit = val.umbrella_liability_symbol+''+ ReplaceNumberWithCommas(val.umbrella_liability_limit);
				}

			  var t = $('#view_users_table').DataTable();
				t.row.add( [
		           count,
		           val.agency_name,
		           liability_limit,
                   liability_exp,
		           liability_cert_path_value,
		           work_comp_limit,
                   work_comp_exp,
		           work_cert_path_value,
		           auto_liability_limit,
                   auto_liability_exp,
		           auto_cert_path_value,
		           umbrella_liability_limit,
                   umbrella_liability_exp,
		           umbrella_liability,
		           doc_cert,
		           status,
		           view_single + update_permission
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
			var check_permission = jQuery.inArray( "certificate_add", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_add_permission').remove();
			}
			else {
				$('.hide_add_permission').show();
			}
		},2000)
    });