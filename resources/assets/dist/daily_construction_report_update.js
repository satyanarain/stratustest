$(document).ready(function() {
    $('.add_contract_items').click(function(){
        $('#add-contractitems').modal('show');
    }) 
    // $('#resource_item_div .add_bar_resource').click(function(e){
    // 	e.preventDefault();
    // 	console.log('click');
    // });

    // Get login user profile data
    $("#update_survey_form").hide();
    // Get login user profile data
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 4 ]; // projects
    report_id = url[ url.length - 2 ]; // projects
    console.log(project_id);
    //alert(report_id);
    jQuery.ajax({
        url: baseUrl + "dashboard/"+project_id+"/daily_construction_report/"+report_id+"/get_new_report_id",
        type: "POST",
        data: {
            "report_id"  		: report_id
        },
        headers: {
          "x-access-token": token
        },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            report_id = data.new_report_id;
        })
        //alert(report_id);
    //return false;
    //alert(baseUrl + "dashboard/"+project_id+"/daily_construction_report/"+report_id+"/get_new_report_id");return false;
		 // Check Permission
	    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
	    var check_permission = jQuery.inArray("daily_construction_report_update", check_user_access );
	    console.log(check_permission);
	    if(check_permission < 1){
	        window.location.href = baseUrl + "403";
	    }
	    else {
	        console.log('Yes Permission');
	        $('.body-content .wrapper').show();
	    }

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
	        var project_name = data.data.p_name;
	        $('#project_name_title').text("Project: " + project_name);
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
	            // console.log("500");
	            window.location.href = baseUrl + "500";
	        }
	    })
	    
		jQuery.ajax({
		url: baseUrl + "daily-report-from-log/"+report_id,
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
		    var status = data.data.pdr_status;
			if(status == 'complete'){
	    	status = '<span class="label label-success">COMPLETE</span>';
		    }
		    else {
		    	status = '<span class="label label-danger">INCOMPLETE</span>';
		    }
		    $('#status').val(status);

			var date = new Date(data.data.pdr_date.replace(' ', 'T'));
			var day = date.getDate();
			var month = date.getMonth()+1;
			var year = date.getFullYear();
			//var report_date = year + '-' + month + '-' + day;
                        var report_date = data.data.pdr_date;//var report_date = $.datepicker.formatDate('yy-mm-dd', new Date(data.data.pdr_date.replace(' ', 'T')));
		    $('#report_name').text(report_date);
		    $('#report_number').text(data.data.pdrl_id);
		    $('#report_date').text(report_date);
		    $('#project_weather').html(data.data.pdr_weather + "<sup>o</sup> c");

		    $("#update_survey_form").show();
		    $(".loading_data").hide();
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
		    //	window.location.href = baseUrl + "404";
		    }
		    else {
		    	// console.log("500");
		    	window.location.href = baseUrl + "500";
		    }
		})

//alert(project_id);
		// Fetch All bid item for given project_id
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
	        jQuery.each(data.data, function( i, val ) {
	            if(val.pbi_status == 'active'){
	                $("#contract_item_work").append(
	                    '<option value="'+val.pbi_id+'">'+val.pbi_item_description+'</option>'
	                )
	            }else {

	            }
	        });
		});


		// Fetch All bit item for given project_id
		jQuery.ajax({
		url: baseUrl + "company-name",
		    type: "GET",
		    headers: {
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
	    .done(function(data, textStatus, jqXHR) {
                $("#subcontractor_work_detail").append(
                    '<option value="">Select Subcontractor</option>'
                )
	        jQuery.each(data.data, function( i, val ) {
	            if(val.f_status == 'active'){
	                $("#subcontractor_work_detail").append(
	                    '<option value="'+val.f_id+'">'+val.f_name+'</option>'
	                )
	            }else {

	            }
	        });
                var add_company_on_fly_permission = jQuery.inArray("project_add_company_on_fly", check_user_access );
                if(add_company_on_fly_permission>0 || role=="owner"){
                $("#subcontractor_work_detail").append(
                    '<option style="font-weight:bold;">Add New Subcontractor</option>'
                )}
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
                    $("#subcontractor_work_detail").append(
                        '<option value="">Select Subcontractor</option>'
                    )
                    var add_company_on_fly_permission = jQuery.inArray("project_add_company_on_fly", check_user_access );
                    if(add_company_on_fly_permission>0 || role=="owner"){
                    $("#subcontractor_work_detail").append(
                        '<option style="font-weight:bold;">Add New Subcontractor</option>'
                    )}
	            // window.location.href = baseUrl + "404";
	        }
	        else {
	            // console.log("500");
	            window.location.href = baseUrl + "500";
	        }
	    })


		$('#add_contract_item_work').click(function(e)

        {
          // $('.loading-submit').show();

		    e.preventDefault();
		    $('#loading_data_add_contract_item_work').show();
		    var contract_item_work 			= $('#contract_item_work').val();
		  	console.log(contract_item_work);


            if(contract_item_work== null)
            {
                $('#loading_data_add_contract_item_work').hide();
                $("#alert_message").show();
                $('.loading-submit').hide();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                html += '<li>Contract items field is required </li>';
                html += '</ul></div>';
                $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
                }, 'fast')
                $("#alert_message").html(html);
                setTimeout(function(){
                    $("#alert_message").hide()
                },3000)
                return false;

            }

		  	var count = 1;
		  	jQuery.each(contract_item_work, function(i, val) {
                var item_id = val;
                // console.log(item_id);
                jQuery.ajax({
			        url: baseUrl+project_id+"/daily-quantity-complete",
			        type: "POST",
			        data: {
			     	    "report_id"  	: report_id,
			     	    "item_id"  		: item_id
			        },
			        headers: {
			          "x-access-token": token
			        },
			        contentType: "application/x-www-form-urlencoded",
			        cache: false
			    })
			    .done(function(data, textStatus, jqXHR) {
			        // console.log(data.description);
			        var contract_item_id = data.description;
                   	jQuery.ajax({
					url: baseUrl + "bid-items/"+contract_item_id,
					    type: "GET",
					    headers: {
					      "Content-Type": "application/json",
					      "x-access-token": token
					    },
					    contentType: "application/json",
					    cache: false
					})
					.done(function(data, textStatus, jqXHR) {
						console.log(data.data);
				    var item_id = data.data.pbi_id;
                                    var pbi_item_no = data.data.pbi_item_no;
                                    var item_name = data.data.pbi_item_description;
						html = '<div class="form-group">'+
		                    '<div class="col-sm-2 form-group" style="padding: 0px;">'+
		                        '<input type="hidden" class="form-control item_id" name="item_id[]" value="'+item_id+'" disabled>'+
                                        '<input type="text" class="form-control pbi_item_no" name="pbi_item_no[]" value="'+pbi_item_no+'" disabled>'+
		                    '</div>'+
		                    '<div class="col-sm-4 form-group">'+
		                        '<input type="text" class="form-control item_name" name="item_name[]" value="'+item_name+'" disabled>'+
		                    '</div>'+
		                    '<div class="col-sm-2 form-group">'+
		                        '<input type="text" class="form-control item_qty" name="item_qty[]" required="required" value="" onkeypress="return isNumber(event)">'+
		                    '</div>'+
		                    '<div class="col-sm-4 form-group" style="padding: 0px;">'+
		                        '<input type="text" class="form-control item_description" name="item_description[]"  required="required" value="">'+
		                    '</div>'+
		                '</div>';
			        	$("#contract_item_work_quantity").append(html);

						html1 = '<div class="form-group">'+
						'<label>'+item_id+'. '+item_name+'</label><br/>'+
						'<div class="div_item form-group clearfix res_itm'+count+'" id="'+item_id+'abcd'+count+'">'+
	                        '<div class="col-xs-6" style="padding:0px;">'+
	                            '<input type="text" class="form-control" name="resource_detail" value="">'+
	                        '</div>'+
	                        '<div class="col-xs-2">'+
	                            '<input type="text" class="form-control" name="resource_time" value="" onkeypress="return isNumber(event)">'+
	                            '<input type="hidden" class="form-control" name="resource_item_id" value="'+item_id+'">'+
	                        '</div>'+
	                        '<div class="col-xs-2">'+
	                            '<select class="form-control" name="resource_time_type">'+
	                                '<option value="mins">Mins</option>'+
	                                '<option value="hours">Hour</option>'+
	                                '<option value="day">Day</option>'+
	                                '<option value="month">Month</option>'+
	                            '</select>'+
	                        '</div>'+
	                        '<div class="col-xs-2">'+
	                            '<a id="'+item_id+'abc'+count+'" class="btn btn-success add_bar_resource add_res"> + </a> '+
                                    ' <a id="'+item_id+'abc'+count+'" class="btn btn-success add_bar_resource remove_res"> - </a>'+
	                        '</div>'+
	                    '</div><div class="clearfix"></div>'+
	                    '</div>';
				        $("#resource_item_div").append(html1);
					})
			    })
			    count++;
	        });
	        $('#loading_data_add_contract_item_work').hide();
	        $('#add_contract_item_work_data').show();
		}); // add_contract_item_work END

				        setTimeout(function(){
					  		$('#resource_item_div').delegate( 'a.remove_res', 'click', function () {
                                                            if(parseInt($('#resource_item_div .div_item').length)<=1)
                                                                return false;
                                                            //alert("sdfsd");return false;
							    var id = $(this).attr("id");
							    var id1 = $(this).parents('.div_item').attr("id");
                                                            $(this).parents('.div_item').last().remove();
							    //$(this).parents('.div_item').clone().insertAfter('#'+id1+':last');
							    return;
							});
                                                        $('#resource_item_div').delegate( 'a.add_res', 'click', function () {
							    var id = $(this).attr("id");
							    var id1 = $(this).parents('.div_item').attr("id");

							    $(this).parents('.div_item').clone().insertAfter('#'+id1+':last');
							    return;
							});
                                                        
				  		}, 5000);

		$('#update_item_quantity').click(function(e) {
            var doSubmit = true;
			var item_id_new = [];
	        $('input[name^=item_id]').each(function(){
	            item_id_new.push($(this).val());
	        });
	        // console.log(item_id);
	        var item_name = [];
	        $('input[name^=item_name]').each(function(){
	            item_name.push($(this).val());
	        });
	        // console.log(item_name);

	        // console.log(item_qty);
	        var item_description = [];
	        $('input[name^=item_description]').each(function(){

	            item_description.push($(this).val());
	        });



            var item_qty = [];
            $('input[name^=item_qty]').each(function()
            {
                if($(this).val()== '')
                {
                    $('#loading_data_add_contract_item_work').hide();
                    $("#alert_message").show();
                    $('.loading-submit').hide();
                    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                    html += '<li> Qty completed field is required </li>';
                    html += '</ul></div>';
                    $('html, body').animate({
                        scrollTop: $(".page-head").offset().top
                    }, 'fast')
                    $("#alert_message").html(html);
                    setTimeout(function(){
                        $("#alert_message").hide()
                    },3000)

                    doSubmit = false;
                    return false;
                }
                else
                {
                    item_qty.push($(this).val());
                }
            });
            if(!doSubmit) return false;
	        // console.log(item_description);
	        var item = {};
	        item['item_id_new'] 		= item_id_new;
	        item['item_name'] 			= item_name;
	        item['item_qty'] 			= item_qty;
	        item['item_description'] 	= item_description;
			var item_lenght = $(item_id_new).length;
			// console.log(item_lenght);
			// console.log(item);
		    item_final = [];
		 	for (i = 0; i < item_id_new.length; i++) {
				// console.log(item['item_id'][i]);
				// console.log(item['item_name'][i]);
				// console.log(item['item_qty'][i]);
				// console.log(item['item_description'][i]);
				item_final.push({
		            "item_id_new" 		:  item['item_id_new'][i],
		            "item_name" 		:  item['item_name'][i],
		            "item_qty" 			:  item['item_qty'][i],
		            "item_description" 	:  item['item_description'][i],
		        });
			}
			console.log(item_final);

			// Update item in project_daily_quantity_completed
			jQuery.each(item_final, function(i, val) {
                // console.log(val);
                var qty_complete_day 		= val.item_qty;
                var item_id 				= val.item_id_new;
                var additional_information 	= val.item_description;
                jQuery.ajax({
			       url: baseUrl+"daily-quantity-complete/"+item_id+"/"+report_id+"/update",
			        type: "POST",
			        data: {
			     	    "qty_complete_day"  		: qty_complete_day,
			     	    "additional_information" 	: additional_information
			        },
			        headers: {
			          "x-access-token": token
			        },
			        contentType: "application/x-www-form-urlencoded",
			        cache: false
			    })
			    .done(function(data, textStatus, jqXHR) {
			        // console.log(data);
			    })
	        });
	        $('#add_resource_item_work_data').show();
	        return false;
		}); // add_contract_item_work END





		$('#update_resource_time').click(function(e) {


            var doSubmit = true;

			var resource_detail = [];
	        $('input[name^=resource_detail]').each(function(){
	            resource_detail.push($(this).val());

                if($(this).val()== '')
                {
                    $('#loading_data_add_contract_item_work').hide();
                    $("#alert_message").show();
                    $('.loading-submit').hide();
                    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                    html += '<li> Resource field is required </li>';
                    html += '</ul></div>';
                    $('html, body').animate({
                        scrollTop: $(".page-head").offset().top
                    }, 'fast')
                    $("#alert_message").html(html);
                    setTimeout(function(){
                        $("#alert_message").hide()
                    },3000)

                    doSubmit = false;
                    return false;
                }


	        });
            if(!doSubmit) return false;
	        var resource_time = [];
	        $('input[name^=resource_time]').each(function(){


                if($(this).val()== '')
                {
                    $('#loading_data_add_contract_item_work').hide();
                    $("#alert_message").show();
                    $('.loading-submit').hide();
                    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                    html += '<li> Time field is required </li>';
                    html += '</ul></div>';
                    $('html, body').animate({
                        scrollTop: $(".page-head").offset().top
                    }, 'fast')
                    $("#alert_message").html(html);
                    setTimeout(function(){
                        $("#alert_message").hide()
                    },3000)
                    doSubmit = false;
                    return false;
                }

	            resource_time.push($(this).val());
	        });
	        console.log(resource_time);


            if(!doSubmit) return false;

	        var resource_item_id = [];
	        $('input[name^=resource_item_id]').each(function(){
	            resource_item_id.push($(this).val());
	        });
	        console.log(resource_item_id);
	        var resource_time_type = [];
	        $('select[name^=resource_time_type]').each(function(){
	            resource_time_type.push($(this).val());
	        });
	        console.log(resource_time_type);
	        var item = {};
	        item['resource_detail'] 		= resource_detail;
	        item['resource_time'] 			= resource_time;
	        item['resource_item_id'] 		= resource_item_id;
	        item['resource_time_type'] 		= resource_time_type;
			var item_lenght = $(resource_detail).length;
			// console.log(item_lenght);
			// console.log(item);
		    item_final = [];
		 	for (i = 0; i < resource_detail.length; i++) {
				// console.log(item['item_id'][i]);
				// console.log(item['resource_time'][i]);
				// console.log(item['resource_item_id'][i]);
				// console.log(item['resource_time_type'][i]);
				item_final.push({
		            "resource_detail" 		:  item['resource_detail'][i],
		            "resource_time" 		:  item['resource_time'][i],
		            "resource_item_id" 		:  item['resource_item_id'][i],
		            "resource_time_type" 	:  item['resource_time_type'][i],
		        });
			}
			console.log(item_final);

			// Update item in project_daily_quantity_completed
			jQuery.each(item_final, function(i, val) {
                // console.log(val);
                var item_id 			= val.resource_item_id;
                var resourse_detail 	= val.resource_detail;
                var time 				= val.resource_time;
                var time_type 			= val.resource_time_type;
                jQuery.ajax({
			        url: baseUrl+"daily-quantity-complete/"+project_id+"/add",
			        type: "POST",
			        data: {
			     	    "report_id"  		: report_id,
			     	    "item_id" 			: item_id,
			     	    "resourse_detail" 	: resourse_detail,
			     	    "time" 				: time,
			     	    "time_type" 		: time_type
			        },
			        headers: {
			          "x-access-token": token
			        },
			        contentType: "application/x-www-form-urlencoded",
			        cache: false
			    })
			    .done(function(data, textStatus, jqXHR) {
			        // console.log(data);
			    })
	        });
	        $('#material_delivered_div').show();
	        return false;
		}); // add_contract_item_work END



		$('#add_material').click(function(e) {

            var doSubmit = true;

			var material_name = [];
	        $('input[name^=material_name]').each(function(){
	            material_name.push($(this).val());

                if($(this).val()== '')
                {
                    $('#loading_data_add_contract_item_work').hide();
                    $("#alert_message").show();
                    $('.loading-submit').hide();
                    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                    html += '<li> Type of material field is required </li>';
                    html += '</ul></div>';
                    $('html, body').animate({
                        scrollTop: $(".page-head").offset().top
                    }, 'fast')
                    $("#alert_message").html(html);
                    setTimeout(function(){
                        $("#alert_message").hide()
                    },3000)

                    $('#milestone_div').hide();
                    $('#following_occur_div').hide();

                    doSubmit = false;
                    return false;
                }


	        });

	        var material_unit = [];
	        $('input[name^=material_unit]').each(function(){



                if($(this).val()== '')
                {
                    $('#loading_data_add_contract_item_work').hide();
                    $("#alert_message").show();
                    $('.loading-submit').hide();
                    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                    html += '<li> Units field is required </li>';
                    html += '</ul></div>';
                    $('html, body').animate({
                        scrollTop: $(".page-head").offset().top
                    }, 'fast')
                    $("#alert_message").html(html);
                    setTimeout(function(){
                        $("#alert_message").hide()
                    },3000)

                    doSubmit = false;
                    return false;
                }

                material_unit.push($(this).val());
	        });

	        var material_unit_type = [];
	        $('select[name^=material_unit_type]').each(function(){
	            material_unit_type.push($(this).val());
	        });

	        var upload_doc_id = [];
	        $('input[name^=upload_doc_id]').each(function(){
	            upload_doc_id.push($(this).val());
	        });
	        console.log(upload_doc_id);
	        var item = {};
	        item['material_name'] 			= material_name;
	        item['material_unit'] 			= material_unit;
	        item['material_unit_type'] 		= material_unit_type;
	        item['upload_doc_id'] 			= upload_doc_id;
			var item_lenght = $(material_name).length;
			// console.log(item_lenght);
			// console.log(item);
		    item_final = [];
		 	for (i = 0; i < material_name.length; i++) {
				item_final.push({
		            "material_name" 		:  item['material_name'][i],
		            "material_unit" 		:  item['material_unit'][i],
		            "material_unit_type" 	:  item['material_unit_type'][i],
		            "upload_doc_id" 		:  item['upload_doc_id'][i],
		        });
			}
			console.log(item_final);

			// Update item in project_daily_quantity_completed
			jQuery.each(item_final, function(i, val) {
                // console.log(val);

                var material_name 			= val.material_name;
                var material_unit 			= val.material_unit;
                var material_unit_type 		= val.material_unit_type;
                var upload_doc_id 			= val.upload_doc_id;
                jQuery.ajax({
			        url: baseUrl+"daily-material-delivered/"+project_id+"/add",
			        type: "POST",
			        data: {
			     	    "report_id"  			: report_id,
			     	    "material_name" 		: material_name,
			     	    "material_unit" 		: material_unit,
			     	    "material_unit_type" 	: material_unit_type,
			     	    "upload_id" 			: upload_doc_id
			        },
			        headers: {
			          "x-access-token": token
			        },
			        contentType: "application/x-www-form-urlencoded",
			        cache: false
			    })
                .done(function(data, textStatus, jqXHR) {
			        // console.log(data);
			    })
	        });
	        $('#milestone_div').show();
	        $('#following_occur_div').show();
	        $('#general_note').show();
	        $('#photo_video_div').show();
	        return false;
		}); // add_material END


		$('body').delegate( '.upload_doc_panel', 'click', function () {
		    var id = $(this).find(".upload_doc_id:first").attr("id");
		    // console.log(id);
		    window.localStorage.setItem("upload_doc_id", id);
		    console.log(localStorage.getItem("upload_doc_id"));
		    return;
		});


		setTimeout(function(){
  			$('body').delegate( '#add_material_row', 'click', function () {
				var randum_number = Math.floor(Math.random()*(99-11+1)+11);
			    var html = '<div class="material_delivered_detail">'+
	                    '<div class="col-sm-6">'+
	                        '<label>What type of material was delivered?</label><br/>'+
	                        '<input type="text" class="form-control" name="material_name[]">'+
	                        '<label>How many units?</label><br/>'+
	                        '<input type="text" class="form-control" name="material_unit[]" onkeypress="return isNumber(event)">'+
	                        '<label>What type of units?</label><br/>'+
	                        '<select class="form-control" name="material_unit_type[]">'+
	                            '<option value="cy">CY</option>'+
	                        '</select>'+
	                    '</div>'+
	                    '<div class="col-sm-6">'+
	                        '<label>Upload Delivery Ticket</label><br/>'+
	'<section class="panel upload_doc_panel" id="upload_div">'+
	    '<div class="panel-body dropzone-form" style="padding: 0px;">'+
	    	'<form id="my-awesome-dropzone'+randum_number+'" action="'+baseUrl+'document/uploadFiles" class="dropzone dz-clickable">'+
				'<input class="" name="document_path" value="/uploads/daily_construction_report/material_delivered/" type="hidden">'+
				'<div class="dz-default dz-message"><span>Upload only Image (PNG, JPG)/PDF format file ONLY</span></div>'+
			'</form>'+
	        '<input type="hidden" name="upload_doc_id[]" class="upload_doc_id" id="upload_doc_id_'+randum_number+'" value="">'+
	        '<input type="hidden" name="upload_type" id="upload_type" value="multiple_upload">'+
	    '</div>'+
	'</section>'+
	                    '</div>'+
	                '</div><script src="'+baseUrl+'resources/assets/js/dropzone_image_pdf.js"></script>';
	                $(".material_delivered_all").append(html);
	                $('.material_delivered_detail:last .upload_doc_panel .dropzone-form form').dropzone({url: baseUrl+'document/uploadFiles'});
			    return;
			});
		}, 5000);


		setTimeout(function(){
  			$('body').delegate( '#add_photo_video_row', 'click', function () {
				var randum_number = Math.floor(Math.random()*(99-11+1)+11);
			    var html = 	'<div class="photo_video_single">'+
			    			'<div class="col-sm-4">'+
						        '<label>Photo Description</label><br/>'+
						        '<input type="text" name="photo_description[]" class="form-control" value="">'+
						    '</div>'+
                                                '<div class="col-sm-2">'+
                                                    '<label>Taken On</label><br/>'+
                                                    '<input type="text" name="taken_on[]" class="form-control default-date-picker1" value="">'+
                                                '</div>'+
	                    '<div class="col-sm-6">'+
	                        '<label>Upload Photo / Video</label><br/>'+
	'<section class="panel upload_doc_panel" id="upload_div">'+
	    '<div class="panel-body dropzone-form" style="padding: 0px;">'+
	    	'<form id="my-awesome-dropzone'+randum_number+'" action="'+baseUrl+'document/uploadFiles" class="dropzone dz-clickable">'+
				'<input class="" name="document_path" value="/uploads/daily_construction_report/video_photo/" type="hidden">'+
				'<div class="dz-default dz-message"><span>Upload only Image (PNG, JPG)/PDF format file ONLY</span></div>'+
			'</form>'+
	        '<input type="hidden" name="upload_photo_id[]" class="upload_doc_id" id="upload_doc_id_'+randum_number+'" value="">'+
	        '<input type="hidden" name="upload_type" id="upload_type" value="multiple_upload">'+
	    '</div>'+
	'</section>'+
	                    '</div>'+
	                '</div></div><script src="'+baseUrl+'resources/assets/js/dropzone_image_pdf.js"></script>';
	                $(".photo_video_taken_detail").append(html);
                        $('.default-date-picker1').datepicker({
                            format: 'yyyy-mm-dd',
                            autoclose: true
                        });
	                $('.photo_video_single:last .upload_doc_panel .panel-body form').dropzone({url: baseUrl+'document/uploadFiles'});
			    return;
			});
		}, 5000);
    $('#subcontractor_work_detail').change(function(){
        var company = $(this).val();
        if(company=="Add New Subcontractor")
        {
            $('#add-company').modal('show');
            $('#add-company').on('shown.bs.modal',function(){
                google.maps.event.trigger(map, "resize");
              });
            
        }
    })
    });


  	$('#add_photo_video').click(function(e) {
			console.log('faizan');
			var photo_description = [];
                        var taken_on = [];
	        $('input[name^=photo_description]').each(function(){
	            photo_description.push($(this).val());
	        });
                $('input[name^=taken_on]').each(function(){
	            taken_on.push($(this).val());
	        });
	        console.log(photo_description);
	        var upload_doc_id = [];
	        $('input[name^=upload_photo_id]').each(function(){
	            upload_doc_id.push($(this).val());
	        });
	        console.log(upload_doc_id);
	        var item = {};
	        item['description'] 			= photo_description;
                item['taken_on'] 			= taken_on;
	        item['upload_doc_id'] 			= upload_doc_id;
			var item_lenght 				= $(upload_doc_id).length;
			// console.log(item_lenght);
			// console.log(item);
		    item_final = [];
		 	for (i = 0; i < upload_doc_id.length; i++) {
				item_final.push({
		            "description" 		:  item['description'][i],
                            "taken_on"                  : item['taken_on'][i],
		            "upload_doc_id" 	:  item['upload_doc_id'][i],
		        });
			}
			console.log(item_final);

			// Update item in project_daily_quantity_completed
			jQuery.each(item_final, function(i, val) {
                // console.log(val);

                var description 			= val.description;
                var upload_doc_id 			= val.upload_doc_id;
                jQuery.ajax({
			        url: baseUrl+"daily-photo-video/"+project_id+"/add",
			        type: "POST",
			        data: {
			     	    "report_id"  			: report_id,
			     	    "description" 			: description,
			     	    "upload_id" 			: upload_doc_id
			        },
			        headers: {
			          "x-access-token": token
			        },
			        contentType: "application/x-www-form-urlencoded",
			        cache: false
			    })
			    .done(function(data, textStatus, jqXHR) {
			        var taken_by                = localStorage.getItem('u_first_name');
                                //var description             = val.description;
                                //var upload_doc_id           = upload_doc_id;
                                //var project_id              = project_id;
                                var ppv_taken_on            = val.taken_on;
                                var pic_type                = localStorage.getItem('upload_type');
                                var token                   = localStorage.getItem('u_token');
                                jQuery.ajax({
                                    url: baseUrl + "picture/add",
                                    type: "POST",
                                    data: {
                                        "taken_by"              : taken_by,
                                        "pic_type"              : pic_type,
                                        "doc_id"                : upload_doc_id,
                                        "description"           : description,
                                        "project_id"            : project_id,
                                        "ppv_taken_on"          : ppv_taken_on,
                                    },
                                    headers: {
                                        "x-access-token": token
                                    },
                                    contentType: "application/x-www-form-urlencoded",
                                    cache: false
                                })
                                .done(function(data, textStatus, jqXHR) {
//                                    console.log(data.description);
//                                    $('html, body').animate({
//                                        scrollTop: $(".page-head").offset().top
//                                    }, 'fast')
//                                    $("#alert_message").show();
//                                    $('.loading-submit').hide();
//                                    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New photo / video added successfully!</div></div>';
//                                    $("#alert_message").html(html);
//                                    $("#upload_doc_id").removeAttr('value');
//                                    $("#taken_by").removeAttr('value');
//                                    var today = new Date();
//                                    var dd = today.getDate();
//                                    var mm = today.getMonth()+1; //January is 0!
//                                    var yyyy = today.getFullYear();
//                                    $("#ppv_taken_on").val(yyyy+'-'+mm+'-'+dd);
//                                    $("#description").removeAttr('value');
//                                    $(".remove_file_drop").trigger("click");
//                                    setTimeout(function()
//                                    {
//                                        $("#alert_message").hide();
//                                    },5000)
//                                    $('#add_gallery_grid tbody tr').remove();

                                })
                                .fail(function(jqXHR, textStatus, errorThrown) {
//                                    console.log("HTTP Request Failed");
//                                    var responseText, html;
//                                    responseText = JSON.parse(jqXHR.responseText);
//                                    // console.log(responseText.data);
//
//                                    $('html, body').animate({
//                                        scrollTop: $(".page-head").offset().top
//                                    }, 'fast')
//                                    $("#alert_message").show();
//                                    $('.loading-submit').hide();
//                                    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
//
//
//                                    html += '</ul></div>';
//                                    $("#alert_message").html(html);
//                                    setTimeout(function(){
//                                        $("#alert_message").hide();
//                                    },5000)
                                })
			    })
	        });
	        $('.submit_daily_report_form').show();
	        // return false;
		}); // add_material END


    $('#submit_daily_report_form').click(function(e) {
      $('.loading-submit').show();
        e.preventDefault();


        // var username = $('#uname').val();
	    // var email = $('#email').val();
	    var project_weather 		= $('#project_weather').val();
	    var custum_field 			= $('#custum_field').val();
	    var perform_work_day 		= $("input[name='perform_work_day']:checked"). val();
	    var material_delivered 		= $("input[name='material_delivered_field']:checked"). val();
	    var milestone_completed 	= $("input[name='milestone_field']:checked"). val();
	    var milestone_detail 		= $('#milestone_detail').val();
	    var accur_detail = [];
        $('input[name^=accur_detail]:checked').each(function(){
            accur_detail.push($(this).val());
        });
        var accur_details = JSON.stringify(accur_detail);
	    var general_note 				= $("#general_note_detail").val();
	    var photo_video 	 			= $("input[name='photo_video_field']:checked"). val();
	    var subcontractor 				= $("input[name='subcontractor_field']:checked"). val();
	    var subcontractor_work_detail 	= $("#subcontractor_work_detail"). val();
            var pdr_sub_contractor_work_detail_comment = $("#pdr_sub_contractor_work_detail_comment").val();
	    var project_id 					= $('#upload_project_id').val();
	    console.log(accur_details);

        var token = localStorage.getItem('u_token');
        jQuery.ajax({
           url: baseUrl + "daily-report/"+report_id+"/update",
            type: "POST",
            data: {
                "report_weather" 					: project_weather,
                "report_custum_field" 				: custum_field,
                "report_perform_work_day" 			: perform_work_day,
                "report_material_delivery" 			: material_delivered,
                "report_milestone_completed" 		: milestone_completed,
                "report_milestone_detail" 			: milestone_detail,
                "report_occur_detail" 				: accur_details,
                "report_general_notes" 				: general_note,
                "report_picture_video" 				: photo_video,
                "report_subcontractor_work_day" 	: subcontractor,
                "report_subcontractor_work_detail" 	: subcontractor_work_detail,
                "pdr_sub_contractor_work_detail_comment": pdr_sub_contractor_work_detail_comment,
                "project_id" 						: project_id
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
            .done(function(data, textStatus, jqXHR) {
            console.log(data);
            $("#alert_message").fadeIn(1000);
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Daily report updated successfully!</div></div>';
            $("#alert_message").html(html);
            setTimeout(function()
            {
                $("#alert_message").hide();
            },5000)

        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(responseText.data.firm_name);
                html = '<div class="alert alert-block alert-danger fade in"><ul>';
                if(responseText.data.project_id != null){
                	html += '<li> Project id is required</li>';
                }
                html += '</ul></div>';
                $("#alert_message").html(html);
        })
    });
