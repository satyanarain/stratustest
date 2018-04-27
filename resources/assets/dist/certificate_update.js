  	$(document).ready(function() {


     	// Get login user profile data
     	$("#update_certificate_form").hide();
	   // Get login user profile data
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		certificate_id = url[ url.length - 2 ]; // projects
		console.log(certificate_id);
		project_id = url[ url.length - 4 ]; // projects
		console.log(project_id);

        // Check Permission
        var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
        var check_permission = jQuery.inArray("certificate_update", check_user_access );
        console.log(check_permission);
        if(check_permission < 1){
            window.location.href = baseUrl + "403";
        }
        else {
            console.log('Yes Permission');
            $('.body-content .wrapper').show();
        }

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

                        var now_date = new Date();
                        var numberOfDaysToAdd = 0;
                        var nowdate = now_date.setDate(now_date.getDate() + numberOfDaysToAdd);

                        var general_date = new Date(data.data.liability_exp);
                        var general = general.setDate(general.getDate() + numberOfDaysToAdd);

                            if(general_date < nowdate){
                                document.getElementById('general_liability').style.display = 'block';
                            }

            $('#general_liability_cur_symbol').val(data.data.liability_currency);
            $('#general_liability_amount').val(data.data.liability_limit);
            $('#general_liability_date').val(data.data.liability_exp);
              $('#standard_doc_id_old').val(data.data.liability_cert_path);
              if ( data.data.liability_required_min = 'yes' ) {
                $( "#req_minimum_general" ).prop( "checked", true );
            }
            //$('#upload_doc_id_general').val(data.data.statu);

            $('#works_compensation_cur_symbol').val(data.data.work_comp_currency);
            $('#works_compensation_currency').val(data.data.work_comp_limit);
            $('#works_compensation_date').val(data.data.work_comp_exp);
            $('#upload_doc_id_work_old').val(data.data.work_comp_cert_path);

            if ( data.data.works_comp_required_min = 'yes' ) {
                $( "#req_minimum_work" ).prop( "checked", true );
            }


          //  $('#upload_doc_id_work').val(data.data.statu);
            $('#auto_compensation_cur_symbol').val(data.data.auto_liability_currency);
            $('#auto_compensation_currency').val(data.data.auto_liability_limit);
            $('#auto_compensation_date').val(data.data.auto_liability_exp);
             $('#auto_compensation_date').val(data.data.auto_liability_cert_path);

             if ( data.data.auto_liability_required_min = 'yes' ) {
                $( "#auto_req_minimum" ).prop( "checked", true );
            }

            $('#umbrella_liability_cur_symbol').val(data.data.umbrella_liability_currency);
            $('#umbrella_liability_currency').val(data.data.umbrella_liability_limit);
            $('#umbrella_liability_date').val(data.data.umbrella_liability_exp);
            $('#upload_doc_id_umbrella_old').val(data.data.umbrella_liability_cert_path);
           // $('#upload_doc_id_umbrella').val(auto_compensation_cur_symbol);

		    var status = data.data.status;
		    if(status == "active"){
		    	status = 'active';
		    }
		    else {
		    	status = "deactive";
		    }
		    $('#status').val(status);
		    $("#update_certificate_form").show();
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
    });


    $('#update_certificate_form').submit(function(e) {
      $('.loading-submit').show();
        e.preventDefault();
        var status               	= $('#status').val();
        var project_id              = $('#upload_project_id').val();
	    var token                   = localStorage.getItem('u_token');



        var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "certificate/"+certificate_id+"/update",
            type: "POST",
            data: {
         	    "status"            : status,
                "project_id"        : project_id
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
            .done(function(data, textStatus, jqXHR) {
            console.log(data);
                $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
                }, 'fast')
                $("#alert_message").show();
                $('.loading-submit').hide();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Certificate updated successfully!</div></div>';
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
                // console.log(responseText.data.currency_name);
                $("#alert_message").fadeIn(1000);
                $('.loading-submit').hide();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"  style="margin-top:50px;"><div class="toast toast-error"><ul>';
                if(responseText.data.project_id != null){
                    html += '<li>The project id field is required.</li>';
                }
                html += '</ul></div>';
                $("#alert_message").html(html);
                $("#alert_message").fadeOut(6000);
        })
    });
