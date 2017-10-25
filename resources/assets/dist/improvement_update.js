  	$(document).ready(function() { 
     	// Get login user profile data
     	// var userid = localStorage.getItem('u_id');
		// var url = window.location.pathname;
       	// var userid = url.substring(url.lastIndexOf('/') + 1);
     	$("#update_improvement_form").hide();
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		improvement_id = url[ url.length - 2 ]; // projects
		console.log(improvement_id);

		jQuery.ajax({
		url: baseUrl + "improvement-type/"+improvement_id,
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
		    var pt_name = data.data.pt_name;
		    $('#imp_type').val(pt_name);

		    var status = data.data.pt_status;
		    if(status == "active"){
		    	status = 'active';
		    }
		    else {
		    	status = "deactive";
		    }
		    $('#status').val(status);
		    $("#update_improvement_form").show();
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


    $('#update_improvement_form').submit(function(e) {
        e.preventDefault();


        // var username = $('#uname').val();
	    // var email = $('#email').val();
	    var pt_name 		= $('#imp_type').val();
	    var pt_status 		= $('#status').val();
	    console.log(pt_name);

        var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "improvement-type/"+improvement_id+"/update",
            type: "POST",
            data: {
                "type_name" 	: pt_name,
                "type_status" 	: pt_status
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
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Improvement type updated successfully!</div></div>';
            $("#alert_message").html(html);
            $("#alert_message").fadeOut(5000);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(responseText.data.type_name);
                
                $("#alert_message").fadeIn(1000);
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                if(responseText.data.type_name != null){
                	html += '<li>'+responseText.data.type_name+'</li>';
                }
                if(responseText.data.type_status != null){
                	html += '<li>'+responseText.data.type_status+'</li>';
                }
                html += '</ul></div>';
                $("#alert_message").html(html);
                $("#alert_message").fadeOut(5000);
        })
    });