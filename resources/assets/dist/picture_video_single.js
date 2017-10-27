  	$(document).ready(function() { 
     	// Get login user profile data
     	$("#update_survey_form").hide();
	   // Get login user profile data
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		project_id = url[ url.length - 3 ]; // projects
		picture_id = url[ url.length - 1 ]; // projects
		console.log(picture_id);

		// Check View All Permission
		var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
		var check_permission = jQuery.inArray( "project_picture_video_view_all", check_user_access );
		console.log(check_permission);
		if(check_permission < 1){
			window.location.href = baseUrl + "403";
		}
		else {
			console.log('Yes Permission');
			$('.body-content .wrapper').show();
		}

		jQuery.ajax({
		url: baseUrl + "picture/"+picture_id,
		    type: "GET",
		    headers: {
		      "Content-Type": "application/json",
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
		.done(function(data, textStatus, jqXHR) {
		    console.log(data.data);
		    console.log(data.data[0].ppv_name);
		    var status = data.data[0].ppv_status;
		    if(status == "active"){
		    	status = 'active';
		    }
		    else {
		    	status = "deactive";
		    }

		    var user_detail = data.data[0].user_firstname+' '+data.data[0].user_lastname+' ('+data.data[0].user_role+')';
		    console.log(user_detail);
		    // $('#status').val(status);
		    $('#picture_date').text(data.data[0].ppv_date);
		    $('#picture_name').text(data.data[0].ppv_name);
		    $('#picture_description').text(data.data[0].ppv_description);
		    $('#picture_taken_by').text(data.data[0].ppv_taken_by);
                    $('#picture_taken_on').text(data.data[0].ppv_taken_on);
		    $('#picture_user_detail').html(user_detail);

		    var doc_type = '';
		    doc_type = data.data[0].ppv_type;
			if(doc_type == 'image/png' || doc_type == 'image/jpg' || doc_type == 'image/jpeg'){
              var doc_type_pic = '<img src="'+baseUrl+data.data[0].doc_path+'" alt="'+data.data[0].ppv_name+'" width="200"/>';
            }
            else {
            	var doc_type_pic = 	'<video width="100%" controls>'+
				  						'<source src="'+baseUrl+data.data[0].doc_path+'" type="video/mp4">'+
									'</video>';
              // var doc_type_pic = '<img src="'+baseUrl+'resources/assets/img/pdf_icon.png" alt="'+data.data[0].ppv_name+'" width="200"/>';
            }


            var file_link = '<a href="'+baseUrl+data.data[0].doc_path+'" target="_blank">'+
                doc_type_pic+
            '</a>';
            $('#file_path').html(file_link);


		    // $("#update_survey_form").show();
		    // $(".loading_data").hide();
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