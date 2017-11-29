  	$(document).ready(function() { 
     	// Get login user profile data
     	// var userid = localStorage.getItem('u_id');
		// var url = window.location.pathname;
       	// var userid = url.substring(url.lastIndexOf('/') + 1);

       	$("#firm_type").hide();
        var role = localStorage.getItem('u_role');
        var token = localStorage.getItem('u_token');
        jQuery.ajax({
        url: baseUrl + "company-type",
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
                if(val.ct_status == 'active'){
                    $("#firm_type").append(
                        '<option value="'+val.ct_id+'">'+val.ct_name+'</option>'
                    )
                }else {

                }
            });
            // $( "h2" ).appendTo( $( ".container" ) );
           
            // $(".loading_data").remove();
            $("#firm_type").show();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            console.log(response);
            if(response == 403){
                window.location.href = baseUrl + "403";
            }
            else if(response == 404){
                window.location.href = baseUrl + "404";
            }
            else {
                window.location.href = baseUrl + "500";
            }
        }); 



     	$("#update_firm_form").hide();
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		firm_id = url[ url.length - 2 ]; // projects
		console.log(firm_id);

		jQuery.ajax({
		url: baseUrl + "firm-name/"+firm_id,
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
		    var f_name = data.data.f_name;
		    $('#firm_name').val(f_name);
		    var f_detail = data.data.f_detail;
		    $('#firm_description').val(f_detail);
		    var firm_address = data.data.f_address;
		    $('#project_location').val(firm_address);
            var project_long = data.data.p_long;
            $('#project_longitude').val(project_long);
            var project_lat = data.data.p_lat;
            $('#project_latitude').val(project_lat);
            console.log(firm_address);
		    var status = data.data.f_status;
		    if(status == "active"){
		    	status = 'active';
		    }
		    else {
		    	status = "deactive";
		    }
		    $('#status').val(status);
		    $('#firm_type').val(data.data.f_type);
                    $('#company_type').val(data.data.company_type);
		    $("#update_firm_form").show();
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


    $('#update_firm_form_btn').click(function(e) {
        e.preventDefault();

        // var username = $('#uname').val();
	    // var email = $('#email').val();
	    var f_name 		= $('#firm_name').val();
	    var f_detail 	= $('#firm_description').val();
	    var f_address 	= $('#project_location').val();
	    var f_type 		= $('#firm_type').val();
	    var f_status 	= $('#status').val();
            var lat =  $("#project_latitude").val();
            var long =  $("#project_longitude").val();
            var company_type = $("#company_type").val();
	    console.log(f_status);

        var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "firm-name/"+firm_id+"/update",
            type: "POST",
            data: {
                "firm_name" 	: f_name,
                "firm_detail" 	: f_detail,
                "firm_address" 	: f_address,
                "firm_type" 	: f_type,
                "firm_status" 	: f_status,
                "project_long" :long,
                "project_lat":lat,
                "company_type":company_type
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

               html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Firm name updated successfully!</div></div>';
                $("#alert_message").show();
                $("#alert_message").html(html);
                setTimeout(function(){
                    $("#alert_message").fadeOut(1000);
                },5000)

        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(responseText);

                $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
                }, 'fast')

                $("#alert_message").show();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-error"><ul>';
                if(responseText.data.firm_name != null){
                    html += '<li>Company name field is Invalid</li>';
                }
                if(responseText.data.firm_detail != null){
                    html += '<li>Company description field is Invalid</li>';
                }
                if(responseText.data.firm_address != null){
                    html += '<li>Company address field is Invalid</li>';
                }
                if(responseText.data.firm_type != null){
                    html += '<li>Company type field is Invalid</li>';
                }
                html += '</ul></div></div>';
                $("#alert_message").html(html);
                setTimeout(function(){
                    $("#alert_message").fadeOut(1000);
                },5000)
        })
    });