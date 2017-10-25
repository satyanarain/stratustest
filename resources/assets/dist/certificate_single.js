  	$(document).ready(function() {
     	// Get login user profile data
     	$("#update_certificate_form").hide();
	   // Get login user profile data
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		certificate_id = url[ url.length - 1 ]; // projects
		console.log(certificate_id);
		project_id = url[ url.length - 3 ]; // projects
		console.log(project_id);

        // Check Permission
        var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
        var check_permission = jQuery.inArray("certificate_view_all", check_user_access );
        console.log(check_permission);
        if(check_permission < 1){
            window.location.href = baseUrl + "403";
        }
        else {
            console.log('Yes Permission');
            $('.body-content .wrapper').show();
        }
        
        // Get Selected Agency
        jQuery.ajax({
        url: baseUrl + "standards/"+project_id+"/standard",
            type: "GET",
            headers: {
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            window.agency_id = data.data[0].ps_agency_name;
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
                console.log(data.data.f_name);
                $('#contractor_name').text(data.data.f_name);
            })
        })

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
		url: baseUrl + "certificate/"+certificate_id,
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
		    
            var amount = data.data.liability_currency+' '+ ReplaceNumberWithCommas(data.data.liability_limit);
            $('#general_liability_amount').text(amount);
            $('#general_liability_date').text(data.data.liability_exp);
            var liability_cert_path = data.data.liability_cert_path;
            var liability_cert_path_value;
            if(liability_cert_path == null){
                liability_cert_path_value = '-';
            }
            else {
                liability_cert_path_value = '<a href="'+baseUrl+liability_cert_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf.svg" width="40"/></a>';
            }
		    $('#general_liability_doc_link').html(liability_cert_path_value);

            var amount = data.data.auto_liability_currency+' '+ ReplaceNumberWithCommas(data.data.auto_liability_limit);
            $('#auto_liability_amount').text(amount);
            $('#auto_liability_date').text(data.data.auto_liability_exp);
            var auto_liability_cert_path = data.data.auto_liability_cert_path;
            var auto_liability_cert_path_value;
            if(auto_liability_cert_path == null){
                auto_liability_cert_path_value = '-';
            }
            else {
                auto_liability_cert_path_value = '<a href="'+baseUrl+auto_liability_cert_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf.svg" width="40"/></a>';
            }
            $('#auto_liability_doc_link').html(auto_liability_cert_path_value);

            var amount = data.data.work_comp_currency+' '+ ReplaceNumberWithCommas(data.data.work_comp_limit);
            $('#work_comp_amount').text(amount);
            $('#work_comp_date').text(data.data.work_comp_exp);
            var work_comp_cert_path = data.data.work_comp_cert_path;
            var work_comp_cert_path_value;
            if(work_comp_cert_path == null){
                work_comp_cert_path_value = '-';
            }
            else {
                work_comp_cert_path_value = '<a href="'+baseUrl+work_comp_cert_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf.svg" width="40"/></a>';
            }
            $('#work_comp_doc_link').html(work_comp_cert_path_value);

            var amount = data.data.umbrella_liability_currency+' '+ ReplaceNumberWithCommas(data.data.umbrella_liability_limit);
            console.log(amount);
            $('#umbrella_liability_amount').text(amount);
            $('#umbrella_liability_date').text(data.data.umbrella_liability_exp);
            var umbrella_liability_cert_path = data.data.umbrella_liability_cert_path;
            var umbrella_liability_cert_path_value;
            if(umbrella_liability_cert_path == null){
                umbrella_liability_cert_path_value = '-';
            }
            else {
                umbrella_liability_cert_path_value = '<a href="'+baseUrl+umbrella_liability_cert_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf.svg" width="40"/></a>';
            }
            $('#umbrella_liability_doc_link').html(umbrella_liability_cert_path_value);


            jQuery.ajax({
            url: baseUrl+"/"+project_id+"/custom_certificate/"+certificate_id,
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
                jQuery.each(data.data, function(i, val) {
                    var custom_cert_path = val.doc_cert_path;
                    var custom_cert_path_value;
                    if(custom_cert_path == null){
                        custom_cert_path_value = '-';
                    }
                    else {
                        custom_cert_path_value = '<a href="'+baseUrl+custom_cert_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf.svg" width="40"/></a>';
                    }

                    $("#custom_certificate_pdf").append(
                        '<div class="col-md-3 nopadleft text-center">'+
                            '<h3>'+val.pcm_name+'</h3>'+
                        '<p><strong>'+val.pcm_name+' Exp. Date: </strong>'+val.pcm_exp+'</p>'+
                        '<p><strong>'+val.pcm_name+' Limit: </strong>'+val.cur_symbol+' '+ ReplaceNumberWithCommas(val.pcm_limit)+'</p>'+
                        '<p><strong>'+val.pcm_name+' Doc Link: </strong>'+custom_cert_path_value+'</p>'+
                        '</div>'
                    )
                });
            })
            $(".loading_data").hide();
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
		    	// window.location.href = baseUrl + "404";
		    }
		    else {
		    	// console.log("500");
		    	window.location.href = baseUrl + "500";
		    }
		}) 
    });