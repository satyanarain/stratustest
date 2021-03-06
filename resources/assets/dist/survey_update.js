  	$(document).ready(function() {
     	// Get login user profile data
     	$("#update_survey_form").hide();
	   // Get login user profile data
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		survey_id = url[ url.length - 2 ]; // projects
		project_id = url[ url.length - 4 ]; // projects
		console.log(survey_id);

		// Check View All Permission
	    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
	    var check_permission = jQuery.inArray("survey_update", check_user_access );
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
		url: baseUrl + "survey/"+survey_id,
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
		    var status = data.data.sur_req_status;
		    if(status == "active"){
		    	status = 'active';
		    }
		    else {
		    	status = "deactive";
		    }
		    $('#status').val(status);
		    $('#survey_number').text(data.data.sur_number);
                    var d = new Date(data.data.sur_request_completion_date.replace(' ', 'T'));
                    
                    $('#survey_completion_date').val(formatAMPM(d));
		    $("#update_survey_form").show();
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
		    //	window.location.href = baseUrl + "404";
		    }
		    else {
		    	// console.log("500");
		    	window.location.href = baseUrl + "500";
		    }
		})
    });


    $('#update_survey_form').submit(function(e) {
      $('.loading-submit').show();
        e.preventDefault();
        var status               	= $('#status').val();
        var project_id              = $('#upload_project_id').val();
	var token                   = localStorage.getItem('u_token');
        var survey_completion_date = $('#survey_completion_date').val();
        if(survey_completion_date == '')
        {
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-error"><ul>';
            html += '<li>The requested completion field is required </li>';
            html += '</ul></div>';
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").html(html);
            $("#alert_message").show();
            setTimeout(function()
            {
                $("#alert_message").hide();
            },5000);
            return false;
        }

        var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "survey/"+survey_id+"/update",
            type: "POST",
            data: {
         	    "sur_req_status"        : status,
                    "project_id"        	: project_id,
                    "survey_request_completion_date"    : survey_completion_date,
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
            .done(function(data, textStatus, jqXHR) {
            console.log(data);
            html = '<div class="alert alert-block alert-success fade in">Survey update successfully!</div>';
            $("#alert_message").html(html);
            $('.loading-submit').hide();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                // console.log(responseText.data.currency_name);
                html = '<div class="alert alert-block alert-danger fade in"><ul>';
                if(responseText.data.project_id != null){
                    html += '<li>The project id field is required.</li>';
                }
                html += '</ul></div>';
                $("#alert_message").html(html);
        })
    });
    
    function formatAMPM(d) {
  var hours = d.getHours();
  var minutes = d.getMinutes();
  var ampm = hours >= 12 ? 'pm' : 'am';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
  var strTime = ("0" + hours).slice(-2) + ':' + ("0" + minutes).slice(-2) + ' ' + ampm;
  return d.getFullYear() + "-" +("0"+(d.getMonth()+1)).slice(-2) + "-" +("0" + d.getDate()).slice(-2) + "  "
     +strTime;
} 
