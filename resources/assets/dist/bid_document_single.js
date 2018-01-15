  	$(document).ready(function() { 
     	// Get login user profile data
     	$("#update_bond_form").hide();
	   // Get login user profile data
            var token = localStorage.getItem('u_token');
            var url = $(location).attr('href').split( '/' );
            bd_id = url[ url.length - 1 ]; // projects
            console.log(bd_id);
                
            project_id = url[ url.length - 3 ]; // projects
	    console.log(project_id);
            var add_addendum_link = baseUrl + "dashboard/"+project_id+"/bid_documents/"+bd_id+"/add-addendum";
            $(".add-extra-addendum").attr("href",add_addendum_link);
	    // Check View All Permission
		var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
		var check_permission = jQuery.inArray( "bid_document_view_all", check_user_access );
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
		url: baseUrl + "bid-documents/"+bd_id,
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
		    var addvertisement_bid_path = data.data.addvertisement_bid_path;
		  	var addvertisement_bid_path_value;
		  	if(addvertisement_bid_path == null){
		  		addvertisement_bid_path_value = '';
		  	}
		  	else {
		  		addvertisement_bid_path_value = '<a href="'+baseUrl+data.data.addvertisement_bid_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
		  	}

		  	var notice_invite_bid_path = data.data.notice_invite_bid_path;
		  	var notice_invite_bid_path_value;
		  	if(notice_invite_bid_path == null){
		  		notice_invite_bid_path_value = '';
		  	}
		  	else {
		  		notice_invite_bid_path_value = '<a href="'+baseUrl+data.data.notice_invite_bid_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
		  	}

		  	var detail_result_path = data.data.detail_result_path;
		  	var detail_result_path_value;
		  	if(detail_result_path == null){
		  		detail_result_path_value = '';
		  	}
		  	else {
		  		detail_result_path_value = '<a href="'+baseUrl+data.data.detail_result_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
		  	}

		  	var sucessful_bidder_proposal_path = data.data.sucessful_bidder_proposal_path;
		  	var sucessful_bidder_proposal_path_value;
		  	if(sucessful_bidder_proposal_path == null){
		  		sucessful_bidder_proposal_path_value = '';
		  	}
		  	else {
		  		sucessful_bidder_proposal_path_value = '<a href="'+baseUrl+data.data.sucessful_bidder_proposal_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
		  	}

		  	if(data.data.bd_date_of_opening == null){
		  		var date_of_opening = '-'
		  	}
		  	else {
               var date_of_opening = data.data.bd_date_of_opening;
		  	}

		  	if(data.data.bd_bid_advertisement_date == null){
		  		var bd_bid_advertisement_date = '-'
		  	}
		  	else {
               var bd_bid_advertisement_date = data.data.bd_bid_advertisement_date;
		  	}

		  	if(data.data.bd_invite_date == null){
		  		var bd_invite_date = '-'
		  	}
		  	else {
               var bd_invite_date = data.data.bd_invite_date;
		  	}

		  	$('#bid_addvertisement_date').text(bd_bid_advertisement_date);
		    $('#notice_invite_date').text(bd_invite_date);
		    $('#date_bid_opening').text(date_of_opening);
		    $('#low_bidder_name').text(data.data.low_bidder_name);
		    $('#addvertisement_bid_path_value').html(addvertisement_bid_path_value);
		    $('#notice_invite_bid_path').html(notice_invite_bid_path_value);
		    $('#detail_result_path').html(detail_result_path_value);
		    $('#sucessful_bidder_proposal_path').html(sucessful_bidder_proposal_path_value);
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
		    	// console.log("404");
		    	window.location.href = baseUrl + "404";
		    }
		    else {
		    	// console.log("500");
		    	window.location.href = baseUrl + "500";
		    }
		}) 



		jQuery.ajax({
		url: baseUrl + "bid-documents/add-addendum/"+bd_id,
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

		    jQuery.each( data.data, function( i, val ) {
			
				var doc_path = val.doc_path;
			  	var doc_path_value;
			  	if(doc_path == null){
			  		doc_path_value = '';
			  	}
			  	else {
			  		doc_path_value = '<a href="'+baseUrl+val.doc_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
			  	}

			  $("#addendum_table tbody").append(
			  	'<tr><td>'+ val.pbd_issue_date+'</td>'+
			  	'<td>'+val.pbd_addendum+'</td>'+
			  	'<td>'+doc_path_value+'</td></tr>'
			  );

			});
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