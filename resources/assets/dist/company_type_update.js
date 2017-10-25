  	$(document).ready(function() { 
     	// Get login user profile data
     	// var userid = localStorage.getItem('u_id');
		// var url = window.location.pathname;
       	// var userid = url.substring(url.lastIndexOf('/') + 1);
     	$("#update_company_type_form").hide();
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		company_type_id = url[ url.length - 2 ]; // projects
		// console.log(company_type_id);
		jQuery.ajax({
		url: baseUrl + "company-type/"+company_type_id,
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
		    var company_type = data.data.ct_name;
		    $('#company_type').val(company_type);
		    
		    var status = data.data.ct_status;
		    if(status == "active"){
		    	status = 'active';
		    }
		    else {
		    	status = "deactive";
		    }
		    $('#status').val(status);
		    $("#update_company_type_form").show();
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


    $('#update_company_type_form').submit(function(e) {
        e.preventDefault();
        // var username = $('#uname').val();
	    // var email = $('#email').val();
	    var ct_name 		= $('#company_type').val();
	    var ct_status 		= $('#status').val();
	    // console.log(f_status);

        var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "company-type/"+company_type_id+"/update",
            type: "POST",
            data: {
                "company_type_name" 	: ct_name,
                "status" 				: ct_status
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
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Company type updated successfully!</div></div>';
                $("#alert_message").html(html);
                setTimeout(function()
                {
                    $("#alert_message").fadeOut(1000);
                },5000)

        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(responseText.data.firm_name);
                $("#alert_message").show();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                if(responseText.data.company_type_name != null){
                	html += '<li>'+responseText.data.company_type_name+'</li>';
                }
                if(responseText.data.status != null){
                	html += '<li>'+responseText.data.status+'</li>';
                }
                html += '</ul></div>';
                $("#alert_message").html(html);
                setTimeout(function(){
                    $("#alert_message").fadeOut(1000);
                },5000)


        })
    });