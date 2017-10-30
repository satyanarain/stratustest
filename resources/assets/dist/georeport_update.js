  	$(document).ready(function() {
     	// Get login user profile data
     	$("#update_georeport_form").hide();
	    $("#company_name").hide();
	    var token = localStorage.getItem('u_token');
	    var url = $(location).attr('href').split( '/' );
	    project_id  = url[ url.length - 4 ]; // projects
	    console.log(project_id);

	    // Check Permission
	    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
	    var check_permission = jQuery.inArray("geotechnical_update", check_user_access );
	    console.log(check_permission);
	    if(check_permission < 1){
	        window.location.href = baseUrl + "403";
	    }
	    else {
	        console.log('Yes Permission');
	        $('.body-content .wrapper').show();
	    }
	    // jQuery.ajax({
	    //     url: baseUrl +project_id+"/get_user_permission_key",
	    //     type: "POST",
	    //     data: {
	    //         "permission_key"       : 'geotechnical_update'
	    //     },
	    //     headers: {
	    //       "x-access-token": token
	    //     },
	    //     contentType: "application/x-www-form-urlencoded",
	    //     cache: false
	    // })
	    // .done(function(data, textStatus, jqXHR) {
	    //     console.log(data.data);
	    // })
	    // .fail(function(jqXHR, textStatus, errorThrown) {
	    //     console.log("HTTP Request Failed");
	    //     var response = jqXHR.responseJSON.code;
	    //     console.log(jqXHR);
	    //     if(response == 403){
	    //         window.location.href = baseUrl + "403";
	    //     }
	    //     else if(response == 404){
	    //         window.location.href = baseUrl + "404";
	    //     }
	    //     else {
	    //     	window.location.href = baseUrl + "500";
	    //     }
	    // });

	    var role = localStorage.getItem('u_role');
	    var token = localStorage.getItem('u_token');
	    jQuery.ajax({
	    url: baseUrl + "firm-name",
	        type: "GET",
	        headers: {
	          "x-access-token": token
	        },
	        contentType: "application/json",
	        cache: false
	    })
	        .done(function(data, textStatus, jqXHR) {
	        // console.log(data.data);
	        // Foreach Loop
	        jQuery.each(data.data, function( i, val ) {
	            if(val.f_status == 'active'){
	                $("#company_name").append(
	                    '<option value="'+val.f_id+'">'+val.f_name+'</option>'
	                )
	            }else {

	            }
	        });
	        // $( "h2" ).appendTo( $( ".container" ) );

	        $(".loading_data").remove();
	        $("#company_name").show();
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
	        }
	        else {
	        	window.location.href = baseUrl + "500";
	        }
	    });

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

     	// Get login user profile data
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		geo_report_id = url[ url.length - 2 ]; // projects
		console.log(geo_report_id);

		jQuery.ajax({
		url: baseUrl + "geo-report/"+geo_report_id,
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
		    var date_of_report = data.data.geo_date_of_report;
		    $('#date_of_report').val(date_of_report);
		    var name_of_report = data.data.geo_name_of_report;
		    $('#name_of_report').val(name_of_report);
		    var company_name = data.data.geo_name_of_firm;
		    $('#company_name').val(company_name);


		    var status = data.data.geo_status;
		    if(status == "active"){
		    	status = "active";
		    }
		    else {
		    	status = "deactive";
		    }
		    $('#status').val(status);
		    $('#applicable_' + data.data.geo_application).prop('checked',true);
		    $('#upload_' + data.data.geo_available).prop('checked',true);
		    $("#update_georeport_form").show();
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


    $('#update_georeport_form').submit(function(e) {
      $('.loading-submit').show();
        e.preventDefault();
        // var date_of_report          = $('#date_of_report').val();
        // var name_of_report          = $('#name_of_report').val();
        // var company_name            = $('#company_name').val();
        // var standard_link           = $('#standard_link').val();
        // var applicable              = $("input[name='applicable']:checked"). val();
        // var upload                  = $("input[name='upload']:checked"). val();
        // var georeport_file_path     = $('#upload_doc_id').val();
        var georeport_project_id    = $('#upload_project_id').val();
	    var georeport_status 		= $('#status').val();



        var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "geo-report/"+geo_report_id+"/update",
            type: "POST",
            data: {
                // "date_of_report"    : date_of_report,
                // "name_of_report"    : name_of_report,
                // "name_of_firm"      : company_name,
                // "applicable"        : applicable,
                // "upload"            : upload,
                // "file_path"         : georeport_file_path,
                "project_id"        : georeport_project_id,
                "status"       		: georeport_status
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
            .done(function(data, textStatus, jqXHR) {
            console.log(data);
            // html = '<div class="alert alert-block alert-success fade in">Update successfully!</div>';
            // $("#alert_message").html(html);

                $("#alert_message").show();
                $('.loading-submit').hide();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Geotechnical report updated successfully!</div></div>';
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
                if(responseText.data.date_of_report != null){
                	html += '<li>The date of report field is required.</li>';
                }
                if(responseText.data.name_of_report != null){
                    html += '<li>The report name field is required.</li>';
                }
                if(responseText.data.name_of_firm != null){
                    html += '<li>The company name field is required.</li>';
                }
                if(responseText.data.applicable != null){
                    html += '<li>The applicable field is required.</li>';
                }
                // if(responseText.data.available != null){
                //     html += '<li>The upload field is required.</li>';
                // }
                if(responseText.data.project_id != null){
                    html += '<li>The project id field is required.</li>';
                }
                html += '</ul></div>';
                $("#alert_message").html(html);
                $("#alert_message").fadeOut(6000);
        })
    });
