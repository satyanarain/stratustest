  	$(document).ready(function() { 
     	// Get login user profile data
     	$("#view_users_table").hide();
		var role = localStorage.getItem('u_role');
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		project_id = url[ url.length - 2 ]; // projects
		// console.log(project_id);
		type = url[ url.length - 1 ]; // projects
		// console.log(type);

		// Check View All Permission
		var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
		var check_permission = jQuery.inArray( "labor_compliance_view_all", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
		}

		// Get Selected Agency
	    jQuery.ajax({
	    url: baseUrl + "/"+project_id+"/default_contractor",
	        type: "GET",
	        headers: {
	          "x-access-token": token
	        },
	        contentType: "application/json",
	        cache: false
	    })
	    .done(function(data, textStatus, jqXHR) {
	        window.agency_id = data.data[0].pna_contactor_name;
	        $("#company_name").val(parseInt(agency_id));
	        $(".loading_data").hide();
	        jQuery.ajax({
	            url: baseUrl + "firm-name/"+agency_id,
	                type: "GET",
	                headers: {
	                  "Content-Type": "application/json",
	                  "x-access-token": token
	                },
	                contentType: "application/json",
	                cache: false
	            })
	        .done(function(data, textStatus, jqXHR) {
	            // console.log(data.data.f_name);
	            $('#contractor_name').text(data.data.f_name);
	        })
	    })

	    // Get Wage Determination
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
		    // console.log(data);
		    $('#wage_determination').text(data.data.p_wage_determination);
                    if(data.data.p_term=="no")
                        $('.hide_add_permission').remove();
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
		    console.log("HTTP Request Failed");
		    var response = jqXHR.responseJSON.code;
		    if(response == 403){
		    	// window.location.href = baseUrl + "403";
		    	console.log("403");
		    }
		    else if(response == 404){
		    	console.log("404");
		    	// window.location.href = baseUrl + "404";
		    }
		    else {
		    	console.log("500");
		    	// window.location.href = baseUrl + "500";
		    }
		})
	 //    jQuery.ajax({
		// url: baseUrl+project_id+"/project_setting_get/project_wage_determination",
		//     type: "GET",
		//     headers: {
		//       "x-access-token": token
		//     },
		//     contentType: "application/json",
		//     cache: false
		// })
		// .done(function(data, textStatus, jqXHR) {
		//     console.log(data.data.pset_meta_value);
		//     var project_wage_determination = data.data.pset_meta_value;
		//     $('#wage_determination').text(project_wage_determination);
		// })

		jQuery.ajax({
		url: baseUrl +project_id+"/labor_compliance",
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
		    var specific_project_name = 'Labor Compliance for Project: ' + window.project_name;
		   	console.log(specific_project_name);
		    $('#view_users_table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    // {
                    //     extend: 'copyHtml5',
                    //     exportOptions: {
                    //         columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 9 ]
                    //     },
                    //     message: specific_project_name,
                    // },
                    {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 9 ]
                        },
                        message: specific_project_name,
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 9 ]
                        },
                        orientation: 'landscape',
            			pageSize: 'LEGAL',
                        message: specific_project_name,
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 9 ]
                        },
                        message: specific_project_name,
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 9 ]
                        },
                        orientation: 'landscape',
                		pageSize: 'LEGAL',
                        message: specific_project_name,
                    }
                ]
            });

		    $("#view_users_table_wrapper tbody tr").hide();
		    // Foreach Loop 
		    var count = 1;
			jQuery.each( data.data, function( i, val ) {
				// Check Update Permission
				var check_permission = jQuery.inArray("labor_compliance_update", check_user_access );
				console.log(check_permission);
				if(check_permission < 1){
					var update_permission = '';
				}
				else {
					var update_permission = '<a href="'+baseUrl+'dashboard/'+val.plc_project_id+'/labor_compliance/'+val.plc_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
				}

				var status = val.plc_status;
				if(status == "active"){
		    		status = '<span class="label label-success">Active</span>';
		    		var single_view = '<a href="'+baseUrl+'dashboard/'+val.plc_project_id+'/labor_compliance/'+val.plc_id+'" class="btn btn-success btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-search"></i></a>';
			    }
			    else {
			    	status = '<span class="label label-danger">Inactive</span>';
			    	var single_view = '<a href="'+baseUrl+'404" class="btn btn-success btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-search"></i></a>';
			    }
			  	
			  	// var doc_140 = val.doc_140;
			  	// var doc_140_value;
			  	// if(doc_140 == null){
			  	// 	doc_140_value = '<td></td>';
			  	// }
			  	// else {
			  	// 	if(val.plc_status == 'active'){
			  	// 		doc_140_value = '<td><a href="'+baseUrl+val.doc_140+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40" /></a></td>';
			  	// 	}
			  	// 	else {
			  	// 		doc_140_value = '<td><a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
			  	// 	}
			  	// }

			  	// var doc_142 = val.doc_142;
			  	// var doc_142_value;
			  	// if(doc_142 == null){
			  	// 	doc_142_value = '<td></td>';
			  	// }
			  	// else {
			  	// 	if(val.plc_status == 'active'){
			  	// 		doc_142_value = '<td><a href="'+baseUrl+val.doc_142+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40" /></a></td>';
			  	// 	}
			  	// 	else {
			  	// 		doc_142_value = '<td><a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
			  	// 	}
			  	// }

			  	if(val.plc_140_date == '0000-00-00' || val.plc_140_date == null){
			  		var plc_140_date = '';
			  	}
			  	else {
			  		var plc_140_date = val.plc_140_date;
			  	}

			  	if(val.plc_142_date == '0000-00-00' || val.plc_142_date == null){
			  		var plc_142_date = '';
			  	}
			  	else {
			  		var plc_142_date = val.plc_142_date;
			  	}

			  	if(val.plc_fringe_date == '0000-00-00' || val.plc_fringe_date == null){
			  		var plc_fringe_date = '';
			  	}
			  	else {
			  		var plc_fringe_date = val.plc_fringe_date;
			  	}

			  	if(val.plc_cac2_date == '0000-00-00' || val.plc_cac2_date == null){
			  		var plc_cac2_date = '';
			  	}
			  	else {
			  		var plc_cac2_date = val.plc_cac2_date;
			  	}

			  	if(val.plc_cpr_date == '0000-00-00' || val.plc_cpr_date == null){
			  		var plc_cpr_date = '';
			  	}
			  	else {
			  		var plc_cpr_date = val.plc_cpr_date;
			  	}

			  	if(val.plc_compliance_date == '0000-00-00' || val.plc_compliance_date == null){
			  		var plc_compliance_date = '';
			  	}
			  	else {
			  		var plc_compliance_date = val.plc_compliance_date;
			  	}

			  	if(val.plc_dir_upload == 'yes'){
			  		var dir_checkbox = '<label class="checkbox-custom check-success"><input type="checkbox" value=" " class="checkbox_dir" checked id="'+val.plc_id+'"><label for="'+val.plc_id+'"></label></label>';
			  	}
			  	else {
			  		var dir_checkbox = '<label class="checkbox-custom check-success"><input type="checkbox" value=" " class="checkbox_dir" id="'+val.plc_id+'"><label for="'+val.plc_id+'"></label></label>';
			  	}
                                var doc_140 = val.doc_140;
                                var doc_140_value;
                                if(doc_140 == null){
                                    doc_140_value = ' --- ';
                                }
                                else {
                                    doc_140_value = '<a href="'+baseUrl+val.doc_140+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40" /></a>';
                                }
                                var doc_142 = val.doc_142;
                                var doc_142_value;
                                if(doc_142 == null){
                                    doc_142_value = ' --- ';
                                }
                                else {
                                    doc_142_value = '<a href="'+baseUrl+val.doc_142+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40" /></a>';
                                }
                                var doc_fringe = val.fringe_doc;
                                var doc_fringe_value;
                                if(doc_fringe == null){
                                    doc_fringe_value = ' --- ';
                                }
                                else {
                                    doc_fringe_value = '<a href="'+baseUrl+val.fringe_doc+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40" /></a>';
                                }
                                var doc_cac2 = val.cac2_doc;
                                var doc_cac2_value;
                                if(doc_cac2 == null){
                                    doc_cac2_value = ' --- ';
                                }
                                else {
                                    doc_cac2_value = '<a href="'+baseUrl+val.cac2_doc+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40" /></a>';
                                }
                                var doc_cpr = val.cpr_doc;
                                var doc_cpr_value;
                                if(doc_cpr == null){
                                    doc_cpr_value = ' --- ';
                                }
                                else if(val.cpr_doc){
                                    doc_cpr_value = '<a href="'+baseUrl+val.cpr_doc+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40" /></a>';
                                }
                                var doc_compliance = val.compliance;
                                var doc_compliance_value;
                                if(doc_compliance == null){
                                    doc_compliance_value = ' --- ';
                                }
                                else if(val.compliance){
                                    doc_compliance_value = '<a href="'+baseUrl+val.compliance+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40" /></a>';
                                }
                                var doc_nonperformance_compliance = val.compliance_non_performance;
                                var doc_nonperformance_compliance_value;
                                if(doc_nonperformance_compliance == null){
                                    doc_nonperformance_compliance_value = ' --- ';
                                }
                                else {
                                    doc_nonperformance_compliance_value = '<a href="'+baseUrl+val.compliance_non_performance+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40" /></a>';
                                }
			  var t = $('#view_users_table').DataTable();
				t.row.add( [
		           count, // val.plc_id,
		           val.f_name,
		           //plc_140_date, // doc_140_value,
                           doc_140_value,
		           //plc_142_date, // doc_142_value,
                           doc_142_value,
                   //plc_fringe_date,
                   doc_fringe_value,
                   //plc_cac2_date,
                   doc_cac2_value,
                   //plc_cpr_date,
                   doc_cpr_value+doc_compliance_value,
                   //plc_compliance_date,
                   doc_nonperformance_compliance_value,
                   dir_checkbox,
		           status,
		           single_view+update_permission
		       	]).draw( false );
				count ++;
			});
		    // $( "h2" ).appendTo( $( ".container" ) );
		   
		    $("#view_users_table").show();
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
		    	$("#view_users_table").show();
		    	$(".loading_data").hide();
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		}); 


		setTimeout(function()
        {
			// Check Add Permission
			var check_permission = jQuery.inArray( "labor_compliance_add", check_user_access );
			console.log(check_permission);
			if(check_permission < 1){
				$('.hide_add_permission').remove();
			}
			else {
				$('.hide_add_permission').show();
			}
		},2000)



		setTimeout(function(){
	        $('body').delegate('.checkbox_dir', 'click', function (e) {
	            e.preventDefault();
	            $('.loading_data').show();
	            var token  = localStorage.getItem('u_token');
	            var id = $(this).attr("id");
	            // alert(id);
	            var r = confirm("Are you sure to check Uploaded to DIR’s website?");
	            if (r == true) {
	                jQuery.ajax({
	                	url: baseUrl + "labor_compliance/"+id+"/update_dir",
			            type: "POST",
			            data: {
			                "plc_id"        : id
			            },
			            headers: {
			              "x-access-token": token
			            },
			            contentType: "application/x-www-form-urlencoded",
			            cache: false
	                })
	                .done(function(data, textStatus, jqXHR) {
	                    console.log(data);
	                    if($('#'+id).is(':checked')){
	                    	$('#'+id).attr('checked', false);
				        }
				        else {
	                    	$('#'+id).attr('checked', true);
				        }  
				        $('.loading_data').hide();
	                })
	                .fail(function(jqXHR, textStatus, errorThrown) {
	                    console.log("HTTP Request Failed");
	                    // var response = jqXHR.responseJSON.code;
	                    console.log(jqXHR.responseJSON);
	                    $('.loading_data').hide();
	                }); 
	            } else {
	                return false;
	            }
	        });
	    },2000) 
    });