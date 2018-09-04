  	$(document).ready(function() {
     	// Get login user profile data
     	$("#update_georeport_form").hide();
	    $("#company_name").hide();
	    var role = localStorage.getItem('u_role');
	    var token = localStorage.getItem('u_token');

	    // Get login user profile data
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		labor_id = url[ url.length - 2 ]; // projects
		project_id = url[ url.length - 4 ]; // projects
		console.log(labor_id);

		// Check Permission
	    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
	    var check_permission = jQuery.inArray("labor_compliance_update", check_user_access );
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
		url: baseUrl + "labor_compliance/"+labor_id,
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

		    var status = data.data.plc_status;
		    if(status == "active"){
		    	status = "active";
		    }
		    else {
		    	status = "deactive";
		    }
                    if(data.data.plc_140_date!='0000-00-00')
                        $('#date_140').val(data.data.plc_140_date);
                    $('#upload_doc_id_1').val(data.data.plc_140);
                    if(data.data.plc_142_date!='0000-00-00')
                        $('#date_142').val(data.data.plc_142_date);
                    $('#upload_doc_id_2').val(data.data.plc_142);
                    if(data.data.plc_fringe_date!='0000-00-00')
                        $('#fringe_date').val(data.data.plc_fringe_date);
                    $('#upload_doc_id_3').val(data.data.plc_fringe);
                    if(data.data.plc_cac2_date!='0000-00-00')
                        $('#cac2_date').val(data.data.plc_cac2_date);
                    $('#upload_doc_id_4').val(data.data.plc_cac2);
                    if(data.data.plc_cpr_date!='0000-00-00')
                        $('#weekly_date').val(data.data.plc_cpr_date);
                    $('#upload_doc_id_5').val(data.data.plc_cpr);
                    if(data.data.plc_compliance_date!='0000-00-00')
                        $('#compliance_date').val(data.data.plc_compliance_date);
                    $('#upload_doc_id_6').val(data.data.plc_compliance);
                    $('#upload_doc_id_7').val(data.data.plc_compliance_non_performance);
		    $('#status').val(status);
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
		    	// console.log("404");
		    	window.location.href = baseUrl + "404";
		    }
		    else {
		    	// console.log("500");
		    	window.location.href = baseUrl + "500";
		    }
		})
    });


    $('#update_labor_form').click(function(e) {
        var date_140                    = $('#date_140').val();
        var doc_140                     = $('#upload_doc_id_1').val();
        var date_142                    = $('#date_142').val();
        var doc_142                     = $('#upload_doc_id_2').val();
        var fringe_date                 = $('#fringe_date').val();
        var doc_fringe                  = $('#upload_doc_id_3').val();
        var cac2_date                   = $('#cac2_date').val();
        var doc_cac2                    = $('#upload_doc_id_4').val();
        var weekly_date                 = $('#weekly_date').val();
        var doc_weekly                  = $('#upload_doc_id_5').val();
        var compliance_date             = $('#compliance_date').val();
        var doc_compliance              = $('#upload_doc_id_6').val();
        var doc_nonperformance          = $('#upload_doc_id_7').val();
      $('.loading-submit').show();
        e.preventDefault();
        var project_id    	= $('#upload_project_id').val();
	    var status 			= $('#status').val();

        var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "labor_compliance/"+labor_id+"/update",
            type: "POST",
            data: {
                "project_id"        : project_id,
                "status"            : status,
                "140"               : doc_140,
                "140_date"          : date_140,
                "142"               : doc_142,
                "142_date"          : date_142,
                "fringe"            : doc_fringe,
                "fringe_date"       : fringe_date,
                "cac2"              : doc_cac2,
                "cac2_date"         : cac2_date,
                "cpr"               : doc_weekly,
                "cpr_date"          : weekly_date,
                "compliance"        : doc_compliance,
                "doc_nonperformance": doc_nonperformance,
                "compliance_date"   : compliance_date,
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
            .done(function(data, textStatus, jqXHR) {
            console.log(data);
                $("#alert_message").show();
                $('.loading-submit').hide();
                $("#date_140").removeAttr('value');
                $("#date_142").removeAttr('value');
                $("#upload_doc_id_1").removeAttr('value');
                $("#date_143").removeAttr('value');
                $("#upload_doc_id_2").removeAttr('value');
                $("#fringe_date").removeAttr('value');
                $("#upload_doc_id_3").removeAttr('value');
                $("#cac2_date").removeAttr('value');
                $("#upload_doc_id_4").removeAttr('value');
                $("#weekly_date").removeAttr('value');
                $("#upload_doc_id_5").removeAttr('value');
                $("#compliance_date").removeAttr('value');
                $("#upload_doc_id_6").removeAttr('value');
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Labor compliance updated successfully!</div></div>';
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
                console.log(responseText.data.currency_name);
                $("#alert_message").fadeIn(1000);
                $('.loading-submit').hide();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-error"><ul>';
                if(responseText.data.status != null){
                    html += '<li>The status field is required.</li>';
                }
                if(responseText.data.project_id != null){
                    html += '<li>The project id field is required.</li>';
                }
                html += '</ul></div>';
                $("#alert_message").html(html);
                $("#alert_message").fadeOut(6000);
        })
    });
