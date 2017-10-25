  	$(document).ready(function() {
     	// Get login user profile data
     	$("#update_bond_form").hide();
	   // Get login user profile data
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		bid_document_id = url[ url.length - 2 ]; // projects
		console.log(bid_document_id);

		project_id = url[ url.length - 4 ]; // projects
	    console.log(project_id);

	    // Check Permission
	    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
	    var check_permission = jQuery.inArray("bid_document_update", check_user_access );
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
		url: baseUrl + "bid-documents/"+bid_document_id,
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
		    var status = data.data.bd_status;
		    if(status == "active"){
		    	status = 'active';
		    }
		    else {
		    	status = "deactive";
		    }
		    $('#status').val(status);
		    $("#update_bond_form").show();
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


    $('#update_bond_form').submit(function(e) {
        e.preventDefault();
        $('.loading-submit').show();
        var status               	= $('#status').val();
        var project_id              = $('#upload_project_id').val();
	    var token                   = localStorage.getItem('u_token');



        var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "bid-documents/"+bid_document_id+"/update",
            type: "POST",
            data: {
         	    "status"            : status,
                "project_id"        : project_id
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {

            console.log(data);
            $("#alert_message").fadeIn(1000);
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Bid document Updated successfully!</div></div>';
            $("#alert_message").html(html);
            $("#alert_message").fadeOut(3000);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {

            console.log("HTTP Request Failed");
            var responseText, html;
            responseText = JSON.parse(jqXHR.responseText);
            // console.log(responseText.data.currency_name);
            $("#alert_message").fadeIn(1000);
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-error"><ul>';
            if(responseText.data.project_id != null){
                html += '<li>The project id field is required.</li>';
            }
            html += '</ul></div>';
            $("#alert_message").html(html);
            $("#alert_message").fadeOut(6000);
        })
    });
