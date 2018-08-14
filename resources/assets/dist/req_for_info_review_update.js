  	$(document).ready(function() { 
     	// Get login user profile data
     	$("#update_submittal_review_form").hide();
	   // Get login user profile data
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		req_for_info_id = url[ url.length - 2]; // projects
		console.log(req_for_info_id);
		project_id = url[ url.length - 4]; // projects
		console.log(project_id);

		// Check View All Permission
		var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
		var check_permission = jQuery.inArray("rfi_review_update", check_user_access );
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
	        // console.log(data.data);
	        window.agency_id = data.data[0].pna_contactor_name;
	        // console.log(agency_id);
	        $("#company_name").val(parseInt(agency_id));
	        $(".loading_data").hide();
	        // Select Company Detail for PDF
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
	            $('#contractor_name').text(data.data.f_name);
	        })
	    })
	    .fail(function(jqXHR, textStatus, errorThrown) {
	        console.log("HTTP Request Failed");
	        var response = jqXHR.responseJSON.code;
	        console.log(response);
	        if(response == 403){
	            window.location.href = baseUrl + "403";
	        }
	        else if(response == 404){
	           $(".loading_data").hide();
	        }
	        else {
	            window.location.href = baseUrl + "500";
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
	        // console.log(data.data);
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
	            $('#rfi_additional_currency').text(data.data.cur_symbol+' ');
	        })
	    })

		jQuery.ajax({
		url: baseUrl + "request-information/"+req_for_info_id,
		    type: "GET",
		    headers: {
		      "Content-Type": "application/json",
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
		    .done(function(data, textStatus, jqXHR) {
                    if(data.data.rir_review_status=="response_provided" || data.data.rir_review_status=="additional_information_requested")
                    {
                        window.location.href = baseUrl + "dashboard/"+project_id+"/req_for_info/"+req_for_info_id;
                    }
		    console.log(data);
		    var file_path = data.data.file_path;
		    if(file_path == null){
		  	 	var	file_path_value = '-';
		  	}
		  	else {
		  		var file_path_value = '<a href="'+data.data.file_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
		  		var file_iframe_value = '<iframe src="'+data.data.file_path+'" frameborder="0" width="100%" height="800"></iframe>';
		  		// var file_path_value = '<iframe src="https://apps.groupdocs.com/document-viewer/Embed/'+data.data.file_path+'?quality=50&use_pdf=False&download=False&print=False&signature=5Xpc7qsFKjmJoHfRcXxUus8Tqn0" frameborder="0" width="100%" height="800"></iframe>';
		  	}

		  	$("#review_document").html(file_iframe_value);

			var date = new Date(data.data.ri_date.replace(' ', 'T'));
			var day = date.getDate();
			var month = date.getMonth();
			var year = date.getFullYear();
			var rfi_date = year + '-' + (month+1) + '-' + day;
	    	$('#rfi_date').text(rfi_date);

	    	if(data.data.ri_additional_cost == null){
	    		var ri_additional_cost = '';
	    	}else if(data.data.ri_additional_cost=="no"){
                    var ri_additional_cost = '<span class="label label-default">No</span>';
                }else {
	    		var ri_additional_cost = '<span class="label label-default">Yes</span>';
	    	}
	    	if(data.data.ri_additional_cost_amount == null || ri_additional_cost=="no"){
	    		var ri_additional_cost_amount = '';
	    	}
	    	else {
	    		var ri_additional_cost_amount = data.data.ri_additional_cost_amount;
	    	}
	    	if(data.data.ri_additional_day == null){
	    		var ri_additional_day = '';
	    	}
	    	else if(data.data.ri_additional_day=="no"){
	    		var ri_additional_day = '<span class="label label-default">No</span>';
	    	}else{
                    var ri_additional_day = '<span class="label label-default">Yes</span>';
                }
	    	if(data.data.ri_additional_day_add == null || ri_additional_day=="no"){
	    		var ri_additional_day_add = '';
	    	}
	    	else {
	    		var ri_additional_day_add = data.data.ri_additional_day_add;
	    	}

		    $('#rfi_number').text(data.data.ri_number);
		    $('#rfi_request').text(data.data.ri_question_request);
		    $('#rfi_suggestion').text(data.data.ri_question_proposed);
		    $('#rfi_additional_cost').html(ri_additional_cost);
		    $('#rfi_additional_amount').text(ReplaceNumberWithCommas(ri_additional_cost_amount));
		    $('#rfi_additional_days').html(ri_additional_day +' '+ri_additional_day_add);
		    $('#rfi_documents').html(file_path_value);
		    $('#rfi_user_detail').html(data.data.rfi_user_firstname+' '+data.data.rfi_user_lastname+'<br/>'+data.data.rfi_user_email+'<br/>'+data.data.rfi_user_role);

		    $("#update_submittal_review_form").show();
		    $(".loading_data").hide();
                    jQuery.ajax({
                    url: baseUrl +"/check-reviewer-permission/"+project_id+'/'+req_for_info_id+'/rfi'+'/cm',
                        type: "GET",
                        headers: {
                          "x-access-token": token
                        },
                        contentType: "application/json",
                        cache: false
                    })
                    .done(function(data, textStatus, jqXHR) {
                        $(".cm_review_section").show();
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        $(".cm_review_section").hide();
                    });
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
		    console.log("HTTP Request Failed");
		    var response = jqXHR.responseJSON.code;
		    if(response == 403){
		    	window.location.href = baseUrl + "403";
		    	// console.log("403");
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
    });



     $('#submit_req_review_form').click(function(e) {
        e.preventDefault();
        $('.loading-submit').show();
        var rfi_number               	= $('#rfi_number').text();
        var respond_date               	= $('#respond_date').val();
        var question_request            = $('#question_request').val();
        var additional_cost             = $("input[name='additonal_cost_type']:checked").val();
        var additional_cost_amount      = $('#additional_cost_amount').val();
        var additional_day              = $("input[name='additonal_day_type']:checked").val();
        var additional_day_add          = $('#additional_day_add').val();
        var status          		= $('#status').val();
        var project_id                  = $('#upload_project_id').val();
	var token                   	= localStorage.getItem('u_token');

     
	    // alert(rfi_number);
        var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "request-review/"+req_for_info_id+"/update",
            type: "POST",
            data: {
         	    "rfi_number"            : rfi_number,
         	    "review_parent"         : req_for_info_id,
         	    "review_respond"        : respond_date,
                "additional_info"           : question_request,
                "additional_cost"           : additional_cost,
                "additional_cost_amount"    : additional_cost_amount,
                "additional_day"            : additional_day,
                "additional_day_add"        : additional_day_add,
                "status"                    : status,
                "project_id"                : project_id
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            console.log(data);
            $('.loading-submit').hide();
            $("#alert_message").show();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Request for information review updated successfully!</div></div>';
            $("#alert_message").html(html);
            setTimeout(function(){
                $("#alert_message").hide()
            },5000)
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                $('.loading-submit').hide();
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(responseText.data);
                $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
                }, 'fast')
                $("#alert_message").show();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"  style="margin-top:50px;"><div class="toast toast-error"><ul>';
                if(responseText.data.additional_cost != null){
                    html += '<li>The additional cost select required.</li>';
                }
                if(responseText.data.additional_cost_amount != null){
                    html += '<li>The additional cost amount required.</li>';
                }
                if(responseText.data.additional_day != null){
                    html += '<li>The additional day select required.</li>';
                }
                if(responseText.data.additional_day_add != null){
                    html += '<li>The additional day add required.</li>';
                }
                if(responseText.data.additional_info != null){
                    html += '<li>The additional info required.</li>';
                }
                if(responseText.data.project_id != null){
                    html += '<li>The project id field is required.</li>';
                }
                html += '</ul></div>';
                $("#alert_message").html(html);
                setTimeout(function(){
                    $("#alert_message").hide()
                },5000)
        })
    });