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
                        var general_date = general_date.setDate(general_date.getDate() + numberOfDaysToAdd);

                            if(general_date < nowdate){
                                document.getElementById('general_liability').style.display = 'block';
                            }

                            var work_comp = new Date(data.data.work_comp_exp);
                        var work_comp = work_comp.setDate(work_comp.getDate() + numberOfDaysToAdd);

                            if(work_comp < nowdate){
                                document.getElementById('workers_comp').style.display = 'block';
                            }

                            var auto_comp = new Date(data.data.auto_liability_exp);
                        var auto_comp = auto_comp.setDate(auto_comp.getDate() + numberOfDaysToAdd);

                            if(auto_comp < nowdate){
                                document.getElementById('auto_laibility').style.display = 'block';
                            }

                            var umbrella_date = new Date(data.data.umbrella_liability_exp);
                        var umbrella_date = umbrella_date.setDate(umbrella_date.getDate() + numberOfDaysToAdd);

                            if(umbrella_date < nowdate){
                                document.getElementById('add_umbrella_liability_div').style.display = 'block';
                            }

            $('#general_liability_cur_symbol').val(data.data.liability_currency);
            $('#general_liability_amount').val(data.data.liability_limit);
            $('#general_liability_date').val(data.data.liability_exp);
              $('#upload_doc_id_general_old').val(data.data.liability_cert_path_id);
              if ( data.data.liability_required_min = 'yes' ) {
                $( "#req_minimum_general" ).prop( "checked", true );
            }
            //$('#upload_doc_id_general').val(data.data.statu);

            $('#works_compensation_cur_symbol').val(data.data.work_comp_currency);
            $('#works_compensation_currency').val(data.data.work_comp_limit);
            $('#works_compensation_date').val(data.data.work_comp_exp);
            $('#upload_doc_id_work_old').val(data.data.work_comp_cert_path_id);

            if ( data.data.works_comp_required_min = 'yes' ) {
                $( "#req_minimum_work" ).prop( "checked", true );
            }

                if ( data.data.ci_works_comp_include_above = 'yes' ) {
                $( "#upload_work_above" ).prop( "checked", true );
            }

                if ( data.data.ci_works_comp_not_include = 'yes' ) {
                $( "#upload_work" ).prop( "checked", true );
            }


          //  $('#upload_doc_id_work').val(data.data.statu);
            $('#auto_compensation_cur_symbol').val(data.data.auto_liability_currency);
            $('#auto_compensation_currency').val(data.data.auto_liability_limit);
            $('#auto_compensation_date').val(data.data.auto_liability_exp);
             $('#upload_doc_id_auto_old').val(data.data.auto_liability_cert_path_id);

             if ( data.data.auto_liability_required_min = 'yes' ) {
                $( "#auto_req_minimum" ).prop( "checked", true );
            }


                if ( data.data.ci_auto_include_above = 'yes' ) {
                $( "#upload_auto_above" ).prop( "checked", true );
            }

                if ( data.data.ci_auto_liability_not_include = 'yes' ) {
                $( "#upload_auto" ).prop( "checked", true );
            }

            $('#umbrella_liability_cur_symbol').val(data.data.umbrella_liability_currency);
            $('#umbrella_liability_currency').val(data.data.umbrella_liability_limit);
            $('#umbrella_liability_date').val(data.data.umbrella_liability_exp);
            $('#upload_doc_id_umbrella_old').val(data.data.umbrella_liability_cert_path_id);
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


    $('#update_certificate_form').click(function(e) {
      $('.loading-submit').show();
        e.preventDefault();

          var check_box = document.getElementById("general_liability_included").checked;
        if(check_box == true){
            var general_liability_included = "yes";
        }
        else {
            var general_liability_included = 'no';
        }
        console.log(general_liability_included);

        var check_box = document.getElementById("req_minimum_general").checked;
        if(check_box == true){
            var req_minimum_general = "yes";
        }
        else {
            var req_minimum_general = 'no';
        }
        console.log(req_minimum_general);

        var check_box = document.getElementById("req_minimum_work").checked;
        if(check_box == true){
            var req_minimum_work = "yes";
        }
        else {
            var req_minimum_work = 'no';
        }
        var check_box = document.getElementById("upload_work_above").checked;
        if(check_box == true){
            var upload_work_above = "yes";
        }
        else {
            var upload_work_above = 'no';
        }
        var check_box = document.getElementById("upload_work").checked;
        if(check_box == true){
            var upload_work = "yes";
        }
        else {
            var upload_work = 'no';
        }


        var check_box = document.getElementById("auto_req_minimum").checked;
        if(check_box == true){
            var auto_req_minimum = "yes";
        }
        else {
            var auto_req_minimum = 'no';
        }
        // console.log(auto_req_minimum);
        var check_box = document.getElementById("upload_auto_above").checked;
        if(check_box == true){
            var upload_auto_above = "yes";
        }
        else {
            var upload_auto_above = 'no';
        }
        // console.log(upload_auto_above);
        var check_box = document.getElementById("upload_auto").checked;
        if(check_box == true){
            var upload_auto = "yes";
        }
        else {
            var upload_auto = 'no';
        }
        // console.log(upload_auto);


        var check_box = document.getElementById("req_minimum_umbrella").checked;
        if(check_box == true){
            var req_minimum_umbrella = "yes";
        }
        else {
            var req_minimum_umbrella = 'no';
        }
        console.log(req_minimum_umbrella);
        var check_box = document.getElementById("upload_umbrella_above").checked;
        if(check_box == true){
            var upload_umbrella_above = "yes";
        }
        else {
            var upload_umbrella_above = 'no';
        }
        console.log(upload_umbrella_above);
        var check_box = document.getElementById("upload_umbrella").checked;
        if(check_box == true){
            var upload_umbrella = "yes";
        }
        else {
            var upload_umbrella = 'no';
        }
        console.log(upload_umbrella);

        var company_name                        = $('#company_name').val();
        var genreal_doc_check =true;
        var work_doc_check =true;
        var auto_doc_check =true;
        var umbrella_doc_check =true;
        var general_liability_cur_symbol        = $('#general_liability_cur_symbol').val();
        var general_liability_amount            = $('#general_liability_amount').val();
        var general_liability_date              = $('#general_liability_date').val();
        var general_liability_req_min           = req_minimum_general;
        var general_liability_doc_path          = $('#upload_doc_id_general').val();

         if(general_liability_doc_path=""){
              general_liability_doc_path = $("#upload_doc_id_general_old").val();  
              genreal_doc_check = false;

         }
       

        var works_compensation_cur_symbol       = $('#works_compensation_cur_symbol').val();
        var works_compensation_currency         = $('#works_compensation_currency').val();
        var works_compensation_date             = $('#works_compensation_date').val();
        var works_compensation_req_minimum      = req_minimum_work;
        var works_compensation_upload_above     = upload_work_above;
        var works_compensation_upload_work      = upload_work;
        var works_compensation_doc_path         = $('#upload_doc_id_work').val();

           if(works_compensation_doc_path=""){
              works_compensation_doc_path = $("#upload_doc_id_work_old").val();  
              work_doc_check = false;

         }

        var auto_compensation_cur_symbol        = $('#auto_compensation_cur_symbol').val();
        var auto_compensation_currency          = $('#auto_compensation_currency').val();
        var auto_compensation_date              = $('#auto_compensation_date').val();
        var auto_compensation_req_minimum       = auto_req_minimum;
        var auto_compensation_upload_above      = upload_auto_above;
        var auto_compensation_upload_auto       = upload_auto;
        var auto_compensation_doc_path          = $('#upload_doc_id_auto').val();



           if(auto_compensation_doc_path=""){
              auto_compensation_doc_path = $("#upload_doc_id_auto_old").val();  
              auto_doc_check = false;

         }
        var umbrella_cur_symbol                 = $('#umbrella_liability_cur_symbol').val();
        var umbrella_currency                   = $('#umbrella_liability_currency').val();
        var umbrella_date                       = $('#umbrella_liability_date').val();
        var umbrella_req_minimum                = req_minimum_umbrella;
        var umbrella_upload_above               = upload_umbrella_above;
        var umbrella_upload_auto                = upload_umbrella;
        var umbrella_doc_path                   = $('#upload_doc_id_umbrella').val();

           if(umbrella_doc_path=""){
              umbrella_doc_path = $("#upload_doc_id_umbrella_old").val();  
              umbrella_doc_check = false;

         }

        var status               	= $('#status').val();
        var project_id              = $('#upload_project_id').val();
	    var token                   = localStorage.getItem('u_token');

             $('#pdf_gen_general_date').text(' '+general_liability_date);
            jQuery.ajax({
            url: baseUrl + "currency/"+general_liability_cur_symbol,
                type: "GET",
                headers: {
                  "Content-Type": "application/json",
                  "x-access-token": token
                },
                contentType: "application/json",
                cache: false
            })
            .done(function(data, textStatus, jqXHR) {
                if(general_liability_amount)
                    $('#pdf_gen_general_limit').text(' '+data.data.cur_symbol+ReplaceNumberWithCommas(general_liability_amount));
            })

            $('#pdf_gen_auto_date').text(' '+auto_compensation_date);
            jQuery.ajax({
            url: baseUrl + "currency/"+works_compensation_cur_symbol,
                type: "GET",
                headers: {
                  "Content-Type": "application/json",
                  "x-access-token": token
                },
                contentType: "application/json",
                cache: false
            })
            .done(function(data, textStatus, jqXHR) {
                if(auto_compensation_currency)
                    $('#pdf_gen_auto_limit').text(' '+data.data.cur_symbol+ ReplaceNumberWithCommas(auto_compensation_currency));
            })

            $('#pdf_gen_work_comp_date').text(' '+works_compensation_date);
            jQuery.ajax({
            url: baseUrl + "currency/"+auto_compensation_cur_symbol,
                type: "GET",
                headers: {
                  "Content-Type": "application/json",
                  "x-access-token": token
                },
                contentType: "application/json",
                cache: false
            })
            .done(function(data, textStatus, jqXHR) {
                if(works_compensation_currency)
                    $('#pdf_gen_work_comp_limit').text(' '+data.data.cur_symbol+ ReplaceNumberWithCommas(works_compensation_currency));
            })

            $('#pdf_gen_umbrella_date').text(' '+umbrella_date);
            jQuery.ajax({
            url: baseUrl + "currency/"+umbrella_cur_symbol,
                type: "GET",
                headers: {
                  "Content-Type": "application/json",
                  "x-access-token": token
                },
                contentType: "application/json",
                cache: false
            })
            .done(function(data, textStatus, jqXHR) {
               if(umbrella_currency) 
                $('#pdf_gen_umbrella_limit').text(' '+data.data.cur_symbol+ ReplaceNumberWithCommas(umbrella_currency));
            });


                var html;
        var is_error = false;
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';

        if(general_liability_amount == ''){
            html += '<li>General liability amount is invalid.</li>';
            is_error = true;
        }
        if(general_liability_date == ''){
            html += '<li>General liability expiration date is invalid.</li>';
            is_error = true;
        }

    
        if(works_compensation_currency == ''){
            html += '<li>Workers compensation amount is invalid.</li>';
            is_error = true;
        }
        if(works_compensation_date == ''){
            html += '<li>Workers compensation expiration date is invalid.</li>';
            is_error = true;
        }
  
        if(auto_compensation_currency == ''){
            html += '<li>Auto liability amount is invalid.</li>';
            is_error = true;
        }
        if(auto_compensation_date == ''){
            html += '<li>Auto liability expiration date is invalid.</li>';
            is_error = true;
        }
        console.log(auto_compensation_upload_above);
     

    var umbrella_cert_on = localStorage.getItem('umbrella_cert_on');
        if(umbrella_cert_on == 'yes'){
           if(umbrella_currency == '')
            {
                html += '<li> Umbrella certificate amount field is invalid </li>';
                is_error = true;
            }
            if(umbrella_date == '')
            {
                html += '<li> Umbrella certificate date field is invalid </li>';
                is_error = true;
            }
         
        }

          html += '</ul></div>';
        if(is_error == true){
            $("#alert_message").show();
            $('.loading-submit').hide();
            $("#alert_message").html(html);
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            setTimeout(function(){
                $("#alert_message").hide()
            },3000)
            return false;
        }

        jQuery.ajax({
            url: baseUrl + "certificate/"+certificate_id+"/update",
            type: "POST",
            data: {
                    "status"            : status,
                    "project_id"        : project_id,
                    "genreal_doc_check" : genreal_doc_check,
                    "work_doc_check":work_doc_check,
                    "auto_doc_check" :auto_doc_check,
                    "umbrella_doc_check":umbrella_doc_check,
                    "liability_currency"            : general_liability_cur_symbol,
                    "liability_limit"               : general_liability_amount,
                    "liability_exp"                 : general_liability_date,
                    "liability_required_min"        : general_liability_req_min,
                    "liability_cert_path"           : general_liability_doc_path,

                    "work_comp_currency"            : works_compensation_cur_symbol,
                    "work_comp_limit"               : works_compensation_currency,
                    "work_comp_exp"                 : works_compensation_date,
                    "works_comp_required_min"       : works_compensation_req_minimum,
                    "works_comp_include_above"      : works_compensation_upload_above,
                    "works_comp_not_include"        : works_compensation_upload_work,
                    "work_comp_cert_path"           : works_compensation_doc_path,

                    "auto_liability_currency"       : auto_compensation_cur_symbol,
                    "auto_liability_limit"          : auto_compensation_currency,
                    "auto_liability_exp"            : auto_compensation_date,
                    "auto_liability_required_min"   : auto_compensation_req_minimum,
                    "auto_include_above"            : auto_compensation_upload_above,
                    "auto_liability_not_include"    : auto_compensation_upload_auto,
                    "auto_liability_cert_path"      : auto_compensation_doc_path,

                    "umbrella_currency"             : umbrella_cur_symbol,
                    "umbrella_limit"                : umbrella_currency,
                    "umbrella_exp"                  : umbrella_date,
                    "umbrella_cert_path"            : umbrella_doc_path,
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
