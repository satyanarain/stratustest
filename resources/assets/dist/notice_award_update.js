  	$(document).ready(function() { 
     	// Get login user profile data
     	$("#update_notice_form").hide();
	   // Get login user profile data
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		notice_id = url[ url.length - 2 ]; // projects
		console.log(notice_id);
        project_id = url[ url.length - 4]; // projects
        console.log(project_id);

        // Check Permission
        var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
        var check_permission = jQuery.inArray("notice_award_update", check_user_access );
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
		url: baseUrl +"notice-award/"+notice_id,
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

            var doc_path = data.data.doc_path;
            var doc_path_value;
            if(doc_path == null){
                doc_path_value = ' - ';
            }
            else {
                doc_path_value = '<a href="'+baseUrl+doc_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
            }
            //$('#doc_file_path').html(doc_path_value);
            $("#bid_amount").val(data.data.pna_contact_amount);
            $("#award_date").val(data.data.pna_award_date);
            var pna_contactor_name = data.data.pna_contactor_name;
            var pna_improvement_type = data.data.pna_improvement_type;
            // Get All agencies Name
            jQuery.ajax({
            url: baseUrl+project_id+"/company_name_user",
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
                $("#company_name").append(
                    '<option value="">Select Contractor Name</option>'
                )
                jQuery.each(data.data, function( i, val ) {
                    if(val.f_status == 'active'){
                        $("#company_name").append(
                            '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                        )
                    }else {

                    }
                });
                $("#company_name").val(pna_contactor_name);
                
                // $( "h2" ).appendTo( $( ".container" ) );

                // $(".loading_data").remove();
                $("#company_name").show();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
//        console.log("HTTP Request Failed");
//        var response = jqXHR.responseJSON.code;
//        console.log(response);
//        if(response == 403){
//        console.log('Company name 403');
//            // window.location.href = baseUrl + "403";
//        }
//        else if(response == 404){
//            console.log('Company name 404');
//            // window.location.href = baseUrl + "404";
//        }
//        else {
//            window.location.href = baseUrl + "500";
//        }
    });
            jQuery.ajax({
            url: baseUrl + project_id +"/improvement-type-by-owner",
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
                $(".project_type_dropdown").append(
                    '<option value="">Select Improvement Type</option>'
                )
                jQuery.each(data.data, function( i, val ) {
                    if(val.pt_status == 'active'){
                        $(".project_type_dropdown").append(
                            '<option value="'+val.pt_id+'">'+val.pt_name+'</option>'
                        )
                    }
                });
                // $( "h2" ).appendTo( $( ".container" ) );
               $("#project_type_dropdown_new").val(pna_improvement_type);
                $(".loading_data").remove();
                $("#s2id_project_type_dropdown").show();
                // $("#add_project_form").show();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        console.log(response);
        if(response == 403){
        console.log('All Improvement 403');
            // window.location.href = baseUrl + "403";
        }
        else if(response == 404){
            console.log('All Improvement 404');
            // window.location.href = baseUrl + "404";
        }
        else {
            window.location.href = baseUrl + "500";
        }
    }); 
            
            var doc_path = data.data.owner_sign_doc_path;
            var doc_path_value;
            if(doc_path == null){
                doc_path_value = ' - ';
                $(".before_review_owner").show();
            }
            else {
                $(".after_review_owner").show();
                doc_path_value = '<a href="'+baseUrl+doc_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
            }
            $('#document_owner').html(doc_path_value);
            $('#review_owner_detail').text(data.data.pna_notice_review_owner);

            var doc_path = data.data.contractor_sign_doc_path;
            var doc_path_value;
            if(doc_path == null){
                doc_path_value = ' - ';
                $(".before_review_contractor").show();
            }
            else {
                $(".after_review_contractor").show();
                doc_path_value = '<a href="'+baseUrl+doc_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
            }
            $('#document_contractor').html(doc_path_value);
            $('#review_contractor_detail').text(data.data.pna_notice_review_contractor);

		    var status = data.data.pna_status;
		    if(status == "active"){
		    	status = 'active';
		    }
		    else {
		    	status = "deactive";
		    }
		    $('#status').val(status);
		    $("#update_notice_form").show();
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
		    //	window.location.href = baseUrl + "404";
		    }
		    else {
		    	// console.log("500");
		    	window.location.href = baseUrl + "500";
		    }
		})
        
       
    
    
    // All Improvement Type
    
    
    
    
    
    
    
    });


    $('#update_notice_form').click(function(e) {
        e.preventDefault();
        $('.loading-submit').show();
        var notice_sign_owner           = $('#upload_doc_id_2').val();
        var notice_review_owner         = $('#review_owner').val();
        var notice_sign_contractor      = $('#upload_doc_id_3').val();
        var notice_review_contractor    = $('#review_contractor').val();
        var status               	    = $('#status').val();
        var project_id                  = $('#upload_project_id').val();
        var project_type_dropdown_new = $('#project_type_dropdown_new').val();
        var company_name = $("#company_name").val();
        var bid_amount              = $('#bid_amount').val();
        var award_date              = $('#award_date').val();
	    var token                       = localStorage.getItem('u_token');

        var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "notice-award/"+notice_id+"/update",
            type: "POST",
            data: {
                "notice_sign_owner"        : notice_sign_owner,
                "notice_review_owner"      : notice_review_owner,
                "notice_sign_contractor"   : notice_sign_contractor,
                "notice_review_contractor" : notice_review_contractor,
         	    "status"                   : status,
                "project_id"               : project_id,
                "improvement_type": project_type_dropdown_new,
                "contactor_name"             : company_name,
                "contact_amount"                : bid_amount,
                "award_date"                : award_date,
                
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
            .done(function(data, textStatus, jqXHR) {
            $('.loading-submit').hide();
            console.log(data);
            // html = '<div class="alert alert-block alert-success fade in">Update successfully!</div>';
            // $("#alert_message").html(html);
            $("#alert_message").show();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Notice award update successfully!</div></div>';
            $("#alert_message").html(html);
            setTimeout(function()
            {
                $("#alert_message").hide();
            },5000)
            //window.location.reload();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            $('.loading-submit').hide();
            console.log("HTTP Request Failed");
            var responseText, html;
            responseText = JSON.parse(jqXHR.responseText);
            // console.log(responseText.data.currency_name);
            $("#alert_message").fadeIn(1000);
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-error"><ul>';
            if(responseText.data.project_id != null){
                html += '<li>The project id field is required.</li>';
            }
            html += '</ul></div>';
            $("#alert_message").html(html);
            $("#alert_message").fadeOut(6000);
        })
    });