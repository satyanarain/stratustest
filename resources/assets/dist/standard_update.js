  	$(document).ready(function() {
     	// Get login user profile data
	    $("#company_name").hide();
	    var role = localStorage.getItem('u_role');
	    var token = localStorage.getItem('u_token');

	    var url = $(location).attr('href').split( '/' );
	    project_id  = url[ url.length - 4 ]; // project_id
	    // console.log(project_id);

	    // Check Permission
	    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
	    var check_permission = jQuery.inArray("standard_update", check_user_access );
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
	    //         "permission_key"       : 'standard_update'
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
	    //         window.location.href = baseUrl + "500";
	    //     }
	    // });

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
     	$("#update_standards_form").hide();
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		standard_id = url[ url.length - 2 ]; // projects
		console.log(standard_id);
		project_id = url[ url.length - 4 ]; // projects
		console.log(project_id);

		jQuery.ajax({
		url: baseUrl + "standards/"+standard_id,
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
		    var standard_name = data.data.ps_name;
		    $('#standard_name').val(standard_name);
		    var standard_date = data.data.ps_date;
		    $('#standard_date').val(standard_date);
		    var standard_link = data.data.ps_url;
		    $('#standard_link').val(standard_link);


		    var status = data.data.ps_status;
		    if(status == "active"){
		    	status = 'active';
		    }
		    else {
		    	status = "deactive";
		    }
		    $('#status').val(status);
		    $('#applicable_' + data.data.ps_applicable).prop('checked',true);
		    $("#update_standards_form").show();
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


    $('#update_standards_form').submit(function(e) {
        e.preventDefault();
        $('.loading-submit').show();
        // var company_name        = $('#company_name').val();
        // var standard_name       = $('#standard_name').val();
        // var standard_date       = $('#standard_date').val();
        // var standard_link       = $('#standard_link').val();
        // var applicable          = $("input[name='applicable']:checked"). val();
        // var standard_type       = $('#upload_doc_meta').val();
        // var standard_file_path  = $('#upload_doc_id').val();
        var standard_project_id = $('#upload_project_id').val();
	    var standard_status 	= $('#status').val();


	    // console.log(standard_type);

        var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "standards/"+standard_id+"/update",
            type: "POST",
            data: {
                // "agency_name"       : company_name,
                // "name"              : standard_name,
                // "date"              : standard_date,
                // "url"               : standard_link,
                // "applicable"        : applicable,
                // "type"              : standard_type,
                "status"       		: standard_status,
                "project_id"        : standard_project_id
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
            $("#alert_message").fadeIn(1000);
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Standard updated successfully!</div></div>';
            $("#alert_message").html(html);
                setTimeout(function()
                {
                    $("#alert_message").hide();
                },6000)
            // window.location.href = baseUrl + "dashboard/"+project_id+"/standards";
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(responseText.data.currency_name);
                $("#alert_message").show();
                $('.loading-submit').hide();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Standard updated successfully!</div></div>';

                if(responseText.data.currency_name != null){
                	html += '<li>'+responseText.data.currency_name+'</li>';
                }
                if(responseText.data.currency_symbol != null){
                	html += '<li>'+responseText.data.currency_symbol+'</li>';
                }
                if(responseText.data.currency_status != null){
                	html += '<li>'+responseText.data.currency_status+'</li>';
                }
                html += '</ul></div>';
                $("#alert_message").html(html);
                setTimeout(function()
                {
                    $("#alert_message").hide();
                },4000)

        })
    });
