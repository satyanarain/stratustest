  	$(document).ready(function() {

     	// Get login user profile data
     	// $("#update_contract_item_form").hide();
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		project_id = url[ url.length - 2 ]; // projects
		console.log(project_id);

        // Check Permission
        var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
        var check_permission = jQuery.inArray("contract_item_add", check_user_access );
        console.log(check_permission);
        if(check_permission < 1){
            window.location.href = baseUrl + "403";
        }
        else {
            console.log('Yes Permission');
            $('.body-content .wrapper').show();
        }

        jQuery.ajax({
		    url: baseUrl+project_id+"/get-bid-items-qty",
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
		    var item_qty = data.description.piq_qty;
		    $('#item_qty').val(item_qty);
		  
		    $(".loading_data").hide();
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
                $('#item_qty').val("0");
                // window.location.href = baseUrl + "404";
            }
            else {
                console.log("500");
                $('#item_qty').val("0");
		    	// window.location.href = baseUrl + "500";
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
    });


    $('#update_contract_item_form').submit(function(e) {
      $('.loading-submit').show();
        e.preventDefault();
        var item_qty        = $('#item_qty').val();
        var project_id      = $('#project_id').val();


	    // console.log(standard_type);

        var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl+project_id+"/bid-items-qty",
            type: "POST",
            data: {
                "item_qty"              : item_qty,
                "project_id"            : project_id
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            console.log(data);
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Contract item updated successfully!</div></div>';
            $("#alert_message").html(html);
            setTimeout(function(){
                $("#alert_message").hide();
            },5000)
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var responseText, html;
            responseText = JSON.parse(jqXHR.responseText);
            // console.log(responseText.data.currency_name);
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            if(responseText.data.piq_qty != null){
                html += '<li>The item quantity field is required.</li>';
            }
            if(responseText.data.piq_project_id != null){
                html += '<li>The project id field is required.</li>';
            }
            html += '</ul></div>';
            $("#alert_message").html(html);
            setTimeout(function(){
                $("#alert_message").hide();
            },5000)
        })
    });
