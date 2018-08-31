  	$(document).ready(function() { 
     	// Get login user profile data
     	$("#update_submittal_review_form").hide();
	   // Get login user profile data
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		survey_id = url[ url.length - 2 ]; // projects
		project_id = url[ url.length - 4 ]; // projects
		console.log(survey_id);

		// Check View All Permission
		var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
		var check_permission = jQuery.inArray("survey_review_update", check_user_access );
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
	        window.agency_id = data.data[0].pna_contactor_name;
	        $("#company_name").val(parseInt(agency_id));

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
	                $('.loading_data').hide();
	                var f_name = data.data.f_name;
	                $('#contractor_name').text(f_name);
	        })
	    })
	    .fail(function(jqXHR, textStatus, errorThrown) {
	        console.log("HTTP Request Failed");
	        var response = jqXHR.responseJSON.code;
	        console.log(response);
	        if(response == 403){
	            // window.location.href = baseUrl + "403";
	        }
	        else if(response == 404){
	            // alert('faizan');
	            // window.location.href = baseUrl + "404";
	            $(".loading_data").hide();
	        }
	        else {
	            window.location.href = baseUrl + "500";
	        }
	    });


		jQuery.ajax({
		url: baseUrl + "survey-review/"+survey_id,
		    type: "GET",
		    headers: {
		      "Content-Type": "application/json",
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
		.done(function(data, textStatus, jqXHR) {
		    console.log(data.data.sr_review_status);
		    var status = data.data.sr_review_status;
			if(status == 'pending'){
	    	status = '<span class="label label-warning">PENDING</span>';
		    }
		    else if(status == 'past_due'){
	    	status = '<span class="label label-danger">PAST DUE</span>';
		    }
		    else {
		    	status = '<span class="label label-success">COMPLETED</span>';
		    }
		    $('#status').html(status);

		    var file_path = data.data.review_file_path;
		    if(file_path == null){
		  	 	var	file_path_value = '-';
		  	}
		  	else {
		  		var file_path_value = '<a href="'+baseUrl+data.data.review_file_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
		  	}

			// var date = new Date(data.data.sur_date.replace(' ', 'T'));
			// var day = date.getDate();
			// var month = date.getMonth();
			// var year = date.getFullYear();
			var survey_date = data.data.sur_date; // year + '-' + month + '-' + day;

			// var date = new Date(data.data.sur_request_completion_date.replace(' ', 'T'));
			// var day = date.getDate();
			// var month = date.getMonth();
			// var year = date.getFullYear();
                        var d = new Date(data.data.sur_request_completion_date.replace(' ', 'T'));
			//var sur_request_completion_date = data.data.sur_request_completion_date; // year + '-' + month + '-' + day;
                        var sur_request_completion_date = formatAMPM(d);

		    $('#survey_number').text(data.data.sur_number);
		    //$('#survey_request').val(data.data.sur_number);
		    $('#survey_date').text(survey_date);
		    $('#survey_description').text(data.data.sur_description);
		    $('#survey_req_date').text(sur_request_completion_date);
		    $('#survey_documents').html(file_path_value);
		    $('#survey_user_detail').html(data.data.sur_user_firstname+' '+data.data.sur_user_lastname+'<br/>'+data.data.sur_user_name+' <br/>'+data.data.sur_user_email);
		    $("#update_submittal_review_form").show();


		    $(".response_survey_div").hide();
		    $(".upload_survey_div").hide();
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


    $('#submit_survey_review_form').click(function(e) {
        e.preventDefault();
        $('.loading-submit').show();
        var survey_number         		= $('#survey_number').text();
        var response_survey             = $("input[name='response_survey']:checked"). val();
        var survey_request         		= $('#survey_request').val();
        var survey_respond_date         = $('#survey_respond_date').val();
        var doc_id           			= $('#upload_doc_id').val();
        var project_id              	= $('#upload_project_id').val();
	    var token                   	= localStorage.getItem('u_token');

     

        var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "survey-review/"+survey_id+"/update",
            type: "POST",
            data: {
         	    "survey_number"				: survey_number,
         	    "review_responsible"		: response_survey,
         	    "review_request"			: survey_request,
         	    "review_date"  				: survey_respond_date,
         	    "review_file_path"  		: doc_id,
                "project_id"        		: project_id
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
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Survey updated successfully!</div></div>';
            $("#alert_message").html(html);
            setTimeout(function(){
                $("#alert_message").hide();
            },5000)
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                $('.loading-submit').hide();
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                // console.log(responseText.data.currency_name);
                $("#alert_message").show();
            	html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                if(responseText.data.review_responsible != null){
                    html += '<li>the responsible person is required</li>';
                }
                if(responseText.data.review_file_path != null){
                    html += '<li>The cut sheet is required.</li>';
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