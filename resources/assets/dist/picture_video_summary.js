  	$(document).ready(function() { 
     	// Get login user profile data
     	$("#view_users_table_wrapper").hide();
        var role = localStorage.getItem('u_role');
        var token = localStorage.getItem('u_token');
        var url = $(location).attr('href').split( '/' );
        project_id = url[ url.length - 3 ]; // projects
        console.log(project_id);

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

        // Get Selected Agency
    	jQuery.ajax({
	        url: baseUrl + "standards/"+project_id+"/standard",
            type: "GET",
            headers: {
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            // console.log(data.data);
            window.agency_id = data.data[0].ps_agency_name;
            console.log(agency_id);
            $("#company_name").val(parseInt(agency_id));
         
            // Select Company Detail
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
                // console.log(data);
                var f_name = data.data.f_name;
                $('#contractor_name').text(f_name);
            })
        })

        // No of Picture Count
        jQuery.ajax({
		url: baseUrl + "/"+project_id+"/picture/picture_count",
		    type: "GET",
		    headers: {
		      "Content-Type": "application/json",
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
        .done(function(data, textStatus, jqXHR) {
		    // console.log(data);
		    var no_of_picture = data.data;
		    $('#no_of_picture').text(no_of_picture);
		})

        // No of Video Count
        jQuery.ajax({
		url: baseUrl + "/"+project_id+"/picture/video_count",
		    type: "GET",
		    headers: {
		      "Content-Type": "application/json",
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
        .done(function(data, textStatus, jqXHR) {
		    // console.log(data);
		    var no_of_video = data.data;
		    $('#no_of_video').text(no_of_video);
		})

        // Select Project name
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
		    console.log(data);
		    var project_name = data.data.p_name;
		    $('#project_name').text(project_name);
		    $('#project_name1').text(project_name);
		})


       
        jQuery.ajax({
		url: baseUrl +project_id+"/picture",
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
            var doc_type = '';
                jQuery.each(data.data, function( i, val ) {
                        $('#add_gallery_list').append(
                '<li>'+
                '<p><strong>Title:</strong> '+val.ppv_name+'<br/>'+
                '<strong>Taken By:</strong> '+val.ppv_taken_by+'<br/>'+
                '<strong>Taken On:</strong> '+val.ppv_taken_on+'<br/>'+
                '<strong>Link:</strong> '+baseUrl+val.doc_path+'</p>'+
            '</li>'
            );

                $(".loading_data").hide();
                });
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


        setTimeout(function(e)
        {
            // Check Add Permission
            var check_permission = jQuery.inArray( "project_picture_video_add", check_user_access );
            console.log(check_permission);
            if(check_permission < 1){
                    $('.hide_add_permission').remove();
            }
            else {
                    $('.hide_add_permission').show();
            }
        },2000)
        
        jQuery("#ppv_sort_by").change(function(){
            alert("dd");
        });
    });