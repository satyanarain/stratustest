  	$(document).ready(function() { 
     	// Get login user profile data
     	// var userid = localStorage.getItem('u_id');
		// var url = window.location.pathname;
       	// var userid = url.substring(url.lastIndexOf('/') + 1);
     	$("#update_currency_form").hide();
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		cur_id = url[ url.length - 2 ]; // projects
		console.log(cur_id);

		jQuery.ajax({
		url: baseUrl + "currency/"+cur_id,
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
		    var cur_name = data.data.cur_name;
		    $('#currency_name').val(cur_name);
		    var cur_symbol = data.data.cur_symbol;
		    $('#currency_symbol').val(cur_symbol);

		    var status = data.data.cur_status;
		    if(status == "active"){
		    	status = 'active';
		    }
		    else {
		    	status = "deactive";
		    }
		    $('#status').val(status);
		    $("#update_currency_form").show();
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


    $('#update_currency_form').submit(function(e) {
        e.preventDefault();


        // var username = $('#uname').val();
	    // var email = $('#email').val();
	    var cur_name 		= $('#currency_name').val();
	    var cur_symbol 	= $('#currency_symbol').val();
	    var cur_status 	= $('#status').val();
	    console.log(cur_status);

        var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "currency/"+cur_id+"/update",
            type: "POST",
            data: {
                "currency_name" 	: cur_name,
                "currency_symbol" 	: cur_symbol,
                "currency_status" 	: cur_status
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
            .done(function(data, textStatus, jqXHR) {
            console.log(data);
            html = '<div class="alert alert-block alert-success fade in">Update successfully!</div>';
            $("#alert_message").html(html);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(responseText.data.currency_name);
                html = '<div class="alert alert-block alert-danger fade in"><ul>';
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
        })
    });