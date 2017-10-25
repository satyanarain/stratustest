  	$(document).ready(function() { 
  		// Get login user profile data
     	$(".document_folder_list").hide();
		var role = localStorage.getItem('u_role');
		var token = localStorage.getItem('u_token');
		var url = $(location).attr('href').split( '/' );
		project_id = url[ url.length - 2 ]; // projects
		$('.doc-list-parent .loading_data').show();
		// Certificate
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/certificate",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f9").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f9").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty Certificate Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		}); 

		// Standard
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/standard",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f1").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f1").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty standard Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		}); 

		// Specification
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/specification",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f2").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f2").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty specification Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		}); 

		// Project Plan
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/project_plan",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f3").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f3").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty project_plan Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		}); 

		// geo_report
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/geo_report",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f4").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f4").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty geo report Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		}); 

		// swppp
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/swppp",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f5").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f5").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty swppp Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});

		// bid_document
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/bid_document",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f6").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f6").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty bid_document Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});

		// contract
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/contract",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f7").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f7").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty contract Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});

		// notice_award
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/notice_award",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f8").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f8").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty notice_award Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});

		// certificate
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/certificate",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f9").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f9").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty certificate Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});

		// bond
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/bond",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f10").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f10").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty bond Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});

		// contract
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/contract",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f11").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f11").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty contract Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});

		// notice_proceed
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/notice_proceed",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f12").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f12").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty notice_proceed Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});

		// minutes_meeting
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/minutes_meeting",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f13").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f13").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty minutes_meeting Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});

		// minutes_meeting
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/minutes_meeting",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f13").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f13").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty minutes_meeting Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});

		// test_report
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/test_report",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f14").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f14").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty test_report Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});

		// picture_video
		// jQuery.ajax({
		// url: baseUrl + "document/"+project_id+"/picture_video",
		//     type: "GET",
		//     headers: {
		//       "x-access-token": token
		//     },
		//     contentType: "application/json",
		//     cache: false
		// })
		// .done(function(data, textStatus, jqXHR) {
		//     // console.log(data.data);
		//     // Foreach Loop 
		// 	jQuery.each( data.data, function( i, val ) {
		// 		if(val.doc_name == ''){
		// 			$(".f15").append(
		// 			  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
		// 			);
		// 		}
		// 		else {
		// 			$(".f15").append(
		// 			  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
		// 			);
		// 		}
		// 	});
		// })
		// .fail(function(jqXHR, textStatus, errorThrown) {
		//     console.log("HTTP Request Failed");
		//     var response = jqXHR.responseJSON.code;
		//     console.log(response);
		//     if(response == 403){
		//     	window.location.href = baseUrl + "403";
		//     }
		//     else if(response == 404){
		//     	// window.location.href = baseUrl + "404";
		//     	console.log('Empty picture_video Folder');
		//     }
		//     else {
		//     	window.location.href = baseUrl + "500";
		//     }
		// });

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
		    // console.log(data.data);
		    // Foreach Loop 
		    var doc_type = '';
			jQuery.each(data.data, function( i, val ) {
			    $(".f15").append(
				  	'<li><a href="#" id="'+val.ppv_doc_id+'"><i class="fa fa-file"></i> '+val.ppv_name+' (Taken By: '+val.ppv_taken_by+')</a></li>'
				);
			$(".loading_data").hide();
			});
		})

		// submittals
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/submittals",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f16").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f16").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty submittals Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});

		// rfi
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/rfi",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f17").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f17").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty rfi Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});


		// survey
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/survey",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f18").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f18").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty survey Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});

		// preliminary_notice
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/preliminary_notice",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f19").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f19").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty preliminary_notice Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});


		// labor_compliance
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/labor_compliance",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f20").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f20").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty labor_compliance Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});


		// built_drawing
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/built_drawing",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f21").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f21").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty built_drawing Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});


		// notice_completion
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/notice_completion",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f22").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f22").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty notice_completion Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});


		// acceptance_letter
		jQuery.ajax({
		url: baseUrl + "document/"+project_id+"/acceptance_letter",
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
			jQuery.each( data.data, function( i, val ) {
				if(val.doc_name == ''){
					$(".f23").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_meta+'</a></li>'
					);
				}
				else {
					$(".f23").append(
					  	'<li><a href="#" id="'+val.doc_id+'"><i class="fa fa-file"></i> '+val.doc_name+'</a></li>'
					);
				}
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
		    	console.log('Empty acceptance_letter Folder');
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});


		$('.document_folder_list').show();
		$('.doc-list-parent .loading_data').hide();
    });



  	setTimeout(function(){
	    $('.document_folder_list li a').click(function(e) {
	        e.preventDefault();
	        var id = $(this).attr('id');
	        $('.document_folder_list ul').hide();
	        $('.document_folder_list li a i').removeClass('fa-folder-open');
	        $('#'+id+' i').addClass('fa-folder-open');
	        $('.'+id).show();
	        $('#'+id).closest('ul').show();
	        console.log(id);
	    })

	    $('.document_folder_list ul li a').click(function(e) {
	    	$('.loading_data').show();
	    
	        e.preventDefault();
	        var id = $(this).attr('id');
	        // alert(id);

	        var id1 = $("#"+id).closest('ul').attr('class');
	        $('#'+id1+' i').addClass('fa-folder-open');
	        console.log(id);
	        // $("#alert_message").hide();
	        // $('.document_detail_panel').hide();
	        // $(".loading_data").show();

	        select_single_document_role(id);

	        get_project_contact();


	        jQuery.ajax({
			url: baseUrl + "document/"+id,
			    type: "GET",
			    headers: {
			      "x-access-token": token
			    },
			    contentType: "application/json",
			    cache: false
			})
			.done(function(data, textStatus, jqXHR) {
			    // console.log(data.data);
			    $('.loading_data').hide();

			    var doc_type = data.data.doc_meta;
			    if(doc_type == "picture_video"){
			    	// alert("picture_video");
			    	jQuery.ajax({
					url: baseUrl + "picture/"+id+"/parent_id",
					    type: "GET",
					    headers: {
					      "x-access-token": token
					    },
					    contentType: "application/json",
					    cache: false
					})
					.done(function(data, textStatus, jqXHR) {
					    // console.log(data.data[0]);
					    $('#doc_type').text(data.data[0].ppv_type);
					    var file_name = data.data[0].ppv_name+' (Taken By: '+data.data[0].ppv_taken_by+')';
					    $('#doc_file_name').html(file_name);

					    var doc_type = '';
					    doc_type = data.data[0].ppv_type;
						if(doc_type == 'image/png' || doc_type == 'image/jpg' || doc_type == 'image/jpeg'){
			              var doc_type_pic = '<img src="'+baseUrl+data.data[0].doc_path+'" alt="'+data.data[0].ppv_name+'" width="200"/>';
			            }
			            else {
			            	var doc_type_pic = 	'<video width="100%" controls>'+
							  						'<source src="'+baseUrl+data.data[0].doc_path+'" type="video/mp4">'+
												'</video>';
			            }
			            var file_link = '<a href="'+baseUrl+data.data[0].doc_path+'" target="_blank">'+
			                doc_type_pic+
			            '</a>';
			            $('#doc_path_value').html(file_link);

					})
					$('#doc_id').val(data.data.doc_id);
			    	var date = new Date(data.data.doc_timestamp);
				    $('#doc_update_time').text($.datepicker.formatDate('dd-mm-yy', date));

				    var user_detail = '<a href="">'+data.data.user_firstname+' '+data.data.user_lastname+' ('+data.data.f_name+')</a>';
				    $('#doc_created_by').html(user_detail);

				    var status = data.data.doc_status;
				    if(status == "active"){
				    	status = 'active';
				    }
				    else {
				    	status = "deactive";
				    }
				    $('#status').val(status);
			    }
			    else {
				    $('#doc_id').val(data.data.doc_id);
				    $('#doc_file_name').text(data.data.doc_name);
				    var date = new Date(data.data.doc_timestamp);
				    $('#doc_update_time').text($.datepicker.formatDate('dd-mm-yy', date));

				    var user_detail = '<a href="">'+data.data.user_firstname+' '+data.data.user_lastname+' ('+data.data.f_name+')</a>';
				    $('#doc_created_by').html(user_detail);

				   	var doc_path_value = '<a href="'+baseUrl+data.data.doc_path+'" style="text-align: center;" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="200"/></a>';
				    $('#doc_path_value').html(doc_path_value);

				    var status = data.data.doc_status;
				    if(status == "active"){
				    	status = 'active';
				    }
				    else {
				    	status = "deactive";
				    }
				    $('#status').val(status);
			    }

			   
			 
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
			    	console.log('No File Available');
			    }
			    else {
			    	window.location.href = baseUrl + "500";
			    }
			}); 

	    })

	    $("body").delegate( ".delete_user", "click", function(e) {
		  	e.preventDefault();
	  	    var id = $(this).attr('id');
	        // alert(id);

	        var r = confirm("Are you sure to delete permission this user?");
			if (r == true) {
	        	$(this).parent().parent('tr').remove();
			    jQuery.ajax({
				url: baseUrl + "document_single/"+id+"/delete",
				    type: "GET",
				    headers: {
				      "x-access-token": token
				    },
				    contentType: "application/json",
				    cache: false
				})
				    .done(function(data, textStatus, jqXHR) {
				    console.log(data.data);
				    // window.location.reload();
				    console.log("Deleted successfully");
				    // select_single_document_role(id);
				    // get_project_contact();
				   	
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
				    console.log("HTTP Request Failed");
				    // var response = jqXHR.responseJSON.code;
				    console.log(jqXHR.responseJSON);
				}); 
			} else {
			    return false;
			}
		});

		$("body").delegate( ".add_user_permission", "click", function(e) {
		  	e.preventDefault();
	  	    var id 			= $(this).attr('id');
	        var doc_id 		= $('#doc_id').val();
	        // alert(id);
	    	// alert(doc_id);

	        var r = confirm("Are you sure to add permission this user?");
			if (r == true) {
	        	$(this).parent().parent('tr').remove();
			    jQuery.ajax({
			    	url: baseUrl + "document_single/add",
		            type: "POST",
		            data: {
		                "user_id" 		: id,
		                "resource_id" 	: doc_id
		            },
		            headers: {
		              "x-access-token": token
		            },
		            contentType: "application/x-www-form-urlencoded",
		            cache: false
				})
				.done(function(data, textStatus, jqXHR) {
				    // window.location.reload();
				    console.log("Added successfully");
				   	select_single_document_role(doc_id);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
				    console.log("HTTP Request Failed");
				    // var response = jqXHR.responseJSON.code;
				    console.log(jqXHR);
				}); 
			} else {
			    return false;
			}
		});

		$('.adduser-toggle').click(function(){
			$(this).parent().next('table.adduser-tbl').slideToggle();
			//alert('test');
		})

  	}, 5000);  //document ready ends

  	
  	$('#save_file_status').click(function(e) {
        e.preventDefault();
        var token = localStorage.getItem('u_token');
		
		var doc_status 	= $('#status').val();
	    var doc_id 		= $('#doc_id').val();
	    console.log(doc_id);

        jQuery.ajax({
            url: baseUrl + "document/"+doc_id+"/update",
            type: "POST",
            data: {
                "doc_status" 	: doc_status
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            console.log(data);
            html = '<div class="alert alert-block alert-success fade in">Updated successfully!</div>';
            $("#alert_message").html(html);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(responseText.data);
                html = '<div class="alert alert-block alert-danger fade in"><ul>';
                if(responseText.data.doc_status != null){
                	html += '<li>'+responseText.data.doc_status+'</li>';
                }
                html += '</ul></div>';
                $("#alert_message").html(html);
        })

        	


    });

	var documents_user = [];
  	function select_single_document_role(doc_id) {
	  	jQuery.ajax({
		url: baseUrl + "document_single/"+doc_id,
		// url: baseUrl + "document_single/"+doc_id,
		    type: "GET",
		    headers: {
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
	    .done(function(data, textStatus, jqXHR) {
		    console.log(data.data);
		    $("#user_permission_table tr").remove();
		    // Foreach Loop 
			jQuery.each(data.data, function( i, val ) {
				documents_user.push(val.dr_user_id);

				var status = val.dr_permission;
				if(status == 'allow'){
		    	status = '<span class="label label-success">Allow</span>';
			    }
			    else {
			    	status = '<span class="label label-danger">Denies</span>';
			    }
			  $("#user_permission_table").append(
			  	'<tr><td>'+val.dr_user_id+'</td>'+
			  	'<td>'+val.user_firstname+' '+val.user_lastname+'<br/>'+val.user_email+'<br/>'+val.f_name+'</td>'+
			  	'<td>'+status+
			  	'</td>'+
	            '<td>'+
	               '<a href="#" id="'+val.dr_id+'" class="btn btn-danger btn-xs tooltips delete_user"><i class="fa fa-times"></i></a>'+
	            '</td></tr>'
			  );
			});
		    // $( "h2" ).appendTo( $( ".container" ) );
		   
		    $("#user_permission_table tr").show();
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
		    	$("#user_permission_table tr").remove();
		    	$(".loading_data").hide();
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		}); 
  	}

	
  	function delete_single_document_role(doc_id) {
	  	jQuery.ajax({
		url: baseUrl + "document_single/"+doc_id+"/delete",
		// url: baseUrl + "document_single/"+doc_id,
		    type: "GET",
		    headers: {
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
	    .done(function(data, textStatus, jqXHR) {
		    console.log(data.data);
		    $("#user_permission_table tr").remove();
		    // Foreach Loop 
			select_single_document_role(id);
		    $("#user_permission_table tr").show();
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
		    	$("#user_permission_table tr").remove();
		    	$(".loading_data").hide();
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		}); 
  	}


  	function get_project_contact() {
	  	jQuery.ajax({
		url: baseUrl +"/contact/"+project_id,
		    type: "GET",
		    headers: {
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
		.done(function(data, textStatus, jqXHR) {
	        // console.log(data.data);
	        // console.log(documents_user);
		    $("#user_add_permission_table tr").remove();
		    // Foreach Loop 
			jQuery.each( data.data, function( i, val )
	        {
	            if(jQuery.inArray(val.id, documents_user) !== -1){
	            	console.log("user already exist");
	            }
	            else {
	            	//console.log(val.user_detail.length);
		            var check_length = val.user_detail.length;
		            var complete='';
		            if(check_length > 0)
		            {
		                for(var i=0; i<check_length; i++)
		                {
		                    teststr= val.user_detail[i].u_phone_type;
		                    teststr+=' : ';
		                    teststr+= val.user_detail[i].u_phone_detail;
		                    teststr+='</br>';
		                    complete += teststr;
		                }
		            }
		            else{
		                complete ='---';
		            }

		            if(val.position_title == null){
		            	var position_title = ' - ';
		            }

		            $("#user_add_permission_table").append(
					  	'<tr><td>'+val.id+'</td>'+
					  	'<td>'+val.first_name+' '+val.last_name+' <br/>'+val.email+' <br/>'+val.company_name+'</td>'+
					  	'<td>'+val.position_title+'</td>'+
					  	'<td>'+val.ct_name+'</td>'+
					  	'<td>'+complete+'</td>'+
			            '<td>'+
			               '<a href="#" id="'+val.id+'" class="btn btn-success btn-xs tooltips add_user_permission" data-placement="top" data-toggle="tooltip" data-original-title="View"><i class="fa fa-plus"></i> Add User</a>'+
			            '</td></tr>'
					);
	            }
	            
			});
		    $("#user_add_permission_table tr").show();
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
		    	$("#user_add_permission_table").show();
		    	$(".loading_data").hide();
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		}); 
	}