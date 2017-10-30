$(document).ready(function() { 

    // var url = $(location).attr('href').split( '/' );
    // project_id = url[ url.length - 2 ]; // projects
    // console.log(project_id);
    // var current_project_id = localStorage.getItem('current_project_id');
    // project_id = current_project_id;
    var sPageURL        = window.location.pathname,
        sURLVariables   = sPageURL.split('/');
        console.log(sURLVariables);
    var project_id      = sURLVariables[2];
    //alert(sURLVariables[1]);
    var u_id            = localStorage.getItem('u_id');
    console.log('Current Project ID '+ project_id);
   
    
    var project_currency      = sURLVariables[3];
    console.log(project_currency);
    if(project_currency != "project_currency"){
        // Get Project Currency
        jQuery.ajax({
        url: baseUrl+project_id+"/project_setting_get/project_currency",
            type: "GET",
            headers: {
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            console.log(data.data.pset_meta_value);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            console.log(response);
            if(response == 403){
                // window.location.href = baseUrl + "403";
                console.log('Project Currency 403');
            }
            else if(response == 404){
                console.log('Project Currency 404');
                var r = confirm("You can't perform any activity, Please set Project Currency!");
                if (r == true) {
                    window.location.href = baseUrl +"dashboard/"+project_id+"/project_currency/update";
                } else {
                    window.location.href = baseUrl +"dashboard/"+project_id+"/project_currency/update";
                }
            }
            else {
            console.log('Project Currency 500');
                // window.location.href = baseUrl + "500";
            }
        });
    }
    // var loc = window.location;
    // var pathName = loc.pathname.substring(19, loc.pathname.lastIndexOf('/') + 1);
    // var current_project_id = pathName.replace('/', ' '); 

    //   url = window.location.href;
    // name = name.replace(/[\[\]]/g, "\\$&");
    // var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
    //     results = regex.exec(url);
    // if (!results) return null;
    // if (!results[2]) return '';
    // var current_project_id =  decodeURIComponent(results[2].replace(/\+/g, " "));
    // console.log(current_project_id);

    // die();
    // console.log(u_id);
    // jQuery.ajax({
    //     url: baseUrl +project_id+"/check_single_project_permission/"+u_id,
    //     type: "GET",
    //     headers: {
    //       "Content-Type": "application/json",
    //       "x-access-token": token
    //     },
    //     contentType: "application/json",
    //     cache: false
    // })
    // .done(function(data, textStatus, jqXHR) {
    //     var count = 1;
    //     var access_permission = [];
    //     jQuery.each( data, function( i, val ) {
    //         // console.log(val.pup_permission_key);
    //         access_permission[count] = val.pup_permission_key;
    //         localStorage.setItem("access_permission", JSON.stringify(access_permission));
    //         count ++;
    //     });
    // })
    // .fail(function(jqXHR, textStatus, errorThrown) {
    //     console.log("HTTP Request Failed");
    //     var response = jqXHR.responseJSON.code;
    //     console.log(jqXHR);
    //     if(response == 403){
    //         console.log('Permission 403');
    //         // window.location.href = baseUrl + "403";
    //     }
    //     else if(response == 404){
    //         console.log('Permission 404');
    //         $('.loading_bar_project_sidebar').hide();
    //         // window.location.href = baseUrl + "404";
    //     }
    //     else {
    //         window.location.href = baseUrl + "500";
    //     }
    // }); 

    jQuery.ajax({
        url: baseUrl +project_id+"/check_single_project_permission/"+u_id,
        type: "GET",
        headers: {
          "Content-Type": "application/json",
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        var count = 1;
        var access_permission = [];
        jQuery.each( data, function( i, val ) {
            // console.log(val.pup_permission_key);
            access_permission[count] = val.pup_permission_key;
            localStorage.setItem("access_permission", JSON.stringify(access_permission));
            count ++;
        });
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        console.log(jqXHR);
        if(response == 403){
            console.log('Permission 403');
        }
        else if(response == 404){
            console.log('Permission 404');
        }
        else {
            window.location.href = baseUrl + "500";
        }
    });
    
    setTimeout(function(){ 
        // Get All Permissions
        var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    	jQuery.each( check_user_access, function( i, val ) {
        	// console.log(val);
        	$('.'+val).show();

            var project_info = false;
        	if(val == 'standard_view_all'){
        		project_info = true;
        		$('.project_info_list').show();
        	}
        	else if(val == 'plan_view_all'){
        		project_info = true;
        		$('.project_info_list').show();
        	}

        	var notice = false;
        	if(val == 'notice_proceed_view_all'){
        		notice = true;
        		$('.notice_list').show();
        	}
        	else if(val == 'notice_award_view_all'){
        		notice = true;
        		$('.notice_list').show();
        	}
        	else if(val == 'notice_completion_view_all'){
        		notice = true;
        		$('.notice_list').show();
        	}

        	var submittal = false;
        	if(val == 'submittal_log'){
        		submittal = true;
        		$('.submittal_list').show();
        	}
        	else if(val == 'submittal_view_all'){
        		submittal = true;
        		$('.submittal_list').show();
        	}
        	else if(val == 'submittal_review_view_all'){
        		submittal = true;
        		$('.submittal_list').show();
        	}

        	var rfi = false;
        	if(val == 'rfi_log'){
        		rfi = true;
        		$('.rfi_list').show();
        	}
        	else if(val == 'rfi_view_all'){
        		rfi = true;
        		$('.rfi_list').show();
        	}
        	else if(val == 'rfi_review_view_all'){
        		rfi = true;
        		$('.rfi_list').show();
        	}

        	var survey = false;
        	if(val == 'survey_log'){
        		survey = true;
        		$('.survey_list').show();
        	}
        	else if(val == 'survey_view_all'){
        		survey = true;
        		$('.survey_list').show();
        	}
        	else if(val == 'survey_review_view_all'){
        		survey = true;
        		$('.survey_list').show();
        	}

        	var cor = false;
        	if(val == 'cor_log'){
        		cor = true;
        		$('.cor_list').show();
        	}
        	else if(val == 'cor_view_all'){
        		cor = true;
        		$('.cor_list').show();
        	}

        	var payment_list = false;
        	if(val == 'payment_quantity_verification_view_all'){
        		payment_list = true;
        		$('.payment_list_menu').show();
        	}
        	else if(val == 'payment_application_view_all'){
        		payment_list = true;
        		$('.payment_list_menu').show();
        	}
            // $('.side-navigation .payment_list_menu').remove('.active');
            $('.loading_bar_project_sidebar').hide();
    	});
        $('.menu_step').show();
    }, 2000);
});