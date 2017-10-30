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

     	// Get Project Currency
		jQuery.ajax({
		url: baseUrl+project_id+"/project_setting_get/project_wage_determination",
		    type: "GET",
		    headers: {
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
		.done(function(data, textStatus, jqXHR) {
		    console.log(data.data.pset_meta_value);
		    var project_wage_determination = data.data.pset_meta_value;
		    $('#project_wage_determination').val(project_wage_determination);
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
    });


    $('#update_project_currency_form').submit(function(e) {
      $('.loading-submit').show();
        e.preventDefault();

	    var meta_value 		= $('#project_wage_determination').val();

	    var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl+"project_setting_add",
            type: "POST",
            data: {
                "project_id" 		: project_id,
                "meta_key" 			: 'project_wage_determination',
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
            html = '<div class="alert alert-block alert-success fade in">Project wage determination update successfully!</div>';
            $("#alert_message").html(html);
            $('.loading-submit').hide();
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
