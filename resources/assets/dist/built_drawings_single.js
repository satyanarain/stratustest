  	$(document).ready(function() { 
     	// Get login user profile data
     	$("#view_users_table_wrapper").hide();
		var role = localStorage.getItem('u_role');
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		project_id = url[ url.length - 3 ]; // projects
		console.log(project_id);
		built_drawing_id = url[ url.length - 1 ]; // projects
		console.log(built_drawing_id);
		
		// Check Permission
	    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
	    var check_permission = jQuery.inArray("drawing_view_all", check_user_access );
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
	        // console.log(data.data);
	        window.agency_id = data.data[0].pna_contactor_name;
	        // console.log(agency_id);
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
	            $('#contractor_name').text(data.data.f_name);
	        })
	    })
	    .fail(function(jqXHR, textStatus, errorThrown) {
	        console.log("HTTP Request Failed");
	        var response = jqXHR.responseJSON.code;
	        console.log(response);
	        if(response == 403){
	            window.location.href = baseUrl + "403";
	        }
	        else if(response == 404){
	           $(".loading_data").hide();
	        }
	        else {
	            window.location.href = baseUrl + "500";
	        }
	    }); 




	    // Select Single Record
		jQuery.ajax({
		url: baseUrl +"build_drawings/"+built_drawing_id,
		    type: "GET",
		    headers: {
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
		.done(function(data, textStatus, jqXHR) {
		    console.log(data.data);
		    // Foreach Loop 
		    	$('#built_description').text(data.data.pbd_description);
				var status = data.data.pbd_status;
				if(status == 'active'){
		    	status = '<span class="label label-success">Activated</span>';
			    }
			    else {
			    	status = '<span class="label label-danger">Deactivated</span>';
			    }
			    $('#built_status').html(status);

			  	if(data.data.pbd_engineer_redline == 'complete'){
			  		var engineer_redline = 'Complete';
			  	}
			  	else if(data.data.pbd_engineer_redline == 'additional_info'){
			  		var engineer_redline = 'Additional Info';
			  	}
			  	else if(data.data.pbd_engineer_redline == 'past_due'){
			  		var engineer_redline = 'Past Due';
			  	}
			  	else {
			  		var engineer_redline = ' --- ';
			  	}
			  	$('#built_engineer').text(engineer_redline);

			  	if(data.data.pbd_contractor_redline == 'complete'){
			  		var contractor_redline = 'Complete';
			  	}
			  	else if(data.data.pbd_contractor_redline == 'additional_info'){
			  		var contractor_redline = 'Additional Info';
			  	}
			  	else if(data.data.pbd_contractor_redline == 'past_due'){
			  		var contractor_redline = 'Past Due';
			  	}
			  	else if(data.data.pbd_contractor_redline == 'not_provided'){
			  		var contractor_redline = 'Not Provided';
			  	}
			  	else {
			  		var contractor_redline = ' --- ';
			  	}
			  	$('#built_contractor').text(contractor_redline);

			  	if(data.data.pbd_change_plan == 'yes'){
			  		var change_plan = 'Yes';
			  	}
			  	else if(data.data.pbd_change_plan == 'no'){
			  		var change_plan = 'No';
			  	}
			  	else {
			  		var change_plan = ' --- ';
			  	}
			  	$('#built_plan').text(change_plan);

			  	var custom_cert_path = data.data.doc_path;
                var custom_cert_path_value;
                if(custom_cert_path == null){
                    custom_cert_path_value = '-';
                }
                else {
                    custom_cert_path_value = '<a href="http://apps.groupdocs.com/document-viewer/embed/'+custom_cert_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf.svg" width="40"/></a>';
                	  var file_iframe_value = '<iframe src="http://apps.groupdocs.com/document-annotation2/embed/'+custom_cert_path+'" frameborder="0" width="100%" height="800"></iframe>';
                	 //var file_iframe_value = '<iframe src="https://apps.groupdocs.com/document-viewer/Embed/'+custom_cert_path+'?quality=50&use_pdf=False&download=False&print=False&signature=5Xpc7qsFKjmJoHfRcXxUus8Tqn0" frameborder="0" width="100%" height="800"></iframe>';
                }
                $('#built_drawing').html(custom_cert_path_value);
                $('#review_document').html(file_iframe_value);
			  	
		  
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
		    	$("#view_users_table_wrapper").show();
		    	$(".loading_data").hide();
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		}); 
    });