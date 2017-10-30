  	$(document).ready(function() {

  		var url = $(location).attr('href').split( '/' );
	    project_id = url[ url.length - 3 ]; // projects
	    console.log(project_id);

	    // Check View All Permission
	    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
	    var check_permission = jQuery.inArray( "project_setting", check_user_access );
	    console.log(check_permission);
	    if(check_permission < 1){
	        window.location.href = baseUrl + "403";
	    }
	    else {
	        console.log('Yes Permission');
	        $('.body-content .wrapper').show();
	    }

     	// Get login user profile data
     	$("#update_project_currency_form").hide();
		var role = localStorage.getItem('u_role');
		var token = localStorage.getItem('u_token');
		jQuery.ajax({
		url: baseUrl + "currency",
		    type: "GET",
		    headers: {
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
		.done(function(data, textStatus, jqXHR) {
		    // console.log(data.data);
		    jQuery.each(data.data, function( i, val ) {
	            if(val.cur_status == 'active'){
	                $("#project_currency").append(
	                    '<option value="'+val.cur_id+'">'+val.cur_symbol+' - '+val.cur_name+'</option>'
	                )
	            }else {

	            }
	        });
		    $("#update_project_currency_form").show();
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
		    	$("#update_project_currency_form").show();
		    	$(".loading_data").hide();
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
		    console.log(data.data.pset_meta_value);
		    var project_currency = data.data.pset_meta_value;
		    $('#project_currency').val(project_currency);

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
		    	$("#update_project_currency_form").show();
		    	$(".loading_data").hide();
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});
    });


    $('#update_project_currency_form').submit(function(e) {
      $('.loading-submit').show();
        e.preventDefault();

	    var meta_value 		= $('#project_currency').val();

	    var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl+"project_setting_add",
            type: "POST",
            data: {
                "project_id" 		: project_id,
                "meta_key" 			: 'project_currency',
                "meta_value" 		: meta_value
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
            $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
            }, 'fast')
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Currency updated successfully!</div></div>';
            $("#alert_message").show();
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
                if(responseText.data.pset_meta_key != null){
                	html += '<li>Meta key required field</li>';
                }
                if(responseText.data.pset_meta_value != null){
                	html += '<li>Meta value required field</li>';
                }
                if(responseText.data.pset_project_id != null){
                	html += '<li>Project id required field</li>';
                }
                html += '</ul></div>';
                $("#alert_message").html(html);
                $('.loading-submit').hide();
        })
    });
