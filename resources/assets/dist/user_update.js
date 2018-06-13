$('#update_profile_form').click(function(e) {
    e.preventDefault();
    // console.log('faizan');
    // console.log(userid);
    // var username = $('#uname').val();
    // var email = $('#email').val();
    var last_name 		= $('#lname').val();
    var first_name 		= $('#fname').val();
    var company_name 	= $('#firm_name').val();
    var phone 			= $('#pnum').val();
    var uname 			= $('#uname').val();
    var email 			= $('#email').val();
    var pass 			= $('#pass').val();
    var cpass 			= $('#cpass').val();
    var role 			= $('#role').val();
    var position_title        = $('#position').val();
    var user_role               = $('#user_role').val();
    // var project_id 		= $('#project_name').val();
    var user_image_path =    $("#user_image_path").val();
    if(user_image_path!="")
        $("#old_image_link").html('');
    else
        user_image_path = $("#old_image_path").val();
    var status 			= $('#status').val();
    // console.log(project_id);
    // console.log(position_title);
    var token = localStorage.getItem('u_token');
    jQuery.ajax({
        url: baseUrl + "users/"+userid+"/update",
        type: "POST",
        data: {
            "first_name" : first_name,
            "last_name" : last_name,
            "company_name" : company_name,
            "phone_number" : phone,
            "username" : uname,
            "email" : email,
            "confirm_password" : cpass,
            "password" : pass,
            "role" : role,
            "position_title" : position_title,
            "user_role" : user_role,
            // "project_id" : project_id,
            "status" : status,
            "user_image_path" : user_image_path,
        },
        headers: {
            "x-access-token": token
        },
        contentType: "application/x-www-form-urlencoded",
        cache: false
    })
        .done(function(data, textStatus, jqXHR) {

            jQuery.ajax({
                url: baseUrl + "users/delete_user_data",
                type: "POST",
                data:JSON.stringify(
                    { "user_id":userid

                    }
                ),
                headers: {
                    "x-access-token": token
                },
                contentType: 'application/json',
                cache: true
            })
            var inp_phone_num = document.getElementsByName('phone_num[]');
            var inp_phone_type = document.getElementsByName('phone_type[]');
            for (var i = 0; i <inp_phone_num.length; i++) {
                var inp_phone_type_value=inp_phone_type[i];
                var inp_phone_num_value =inp_phone_num[i];
                var phone_type =  inp_phone_type_value.value;
                var phone_value = inp_phone_num_value.value;
                console.log(phone_type);
                console.log(phone_value);
                jQuery.ajax({
                    url: baseUrl + "users/insert_user_data",
                    type: "POST",
                    data:JSON.stringify(
                        { "user_id":userid,
                            "phone_type": phone_type,
                            "phone_detail": phone_value
                        }
                    ),
                    headers: {
                        "x-access-token": token
                    },
                    contentType: 'application/json',
                    cache: true
                })
            }

            // html = '<div class="alert alert-block alert-success fade in">Profile updated!</div>';
            // $("#updateuserinfo").html(html);
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')

            $("#updateuserinfo").show();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">User updated successfully!</div></div>';
            $("#updateuserinfo").html(html);
            setTimeout(function(){
                $("#updateuserinfo").hide();
            },5000)


        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var responseText, html;
            responseText = JSON.parse(jqXHR.responseText);
            console.log(responseText.data.phone_number);
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#updateuserinfo").show();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-error"><ul>';
            if(responseText.data.phone_number != null){
                html += '<li>'+responseText.data.phone_number+'</li>';
            }
            if(responseText.data.username != null){
                html += '<li>'+responseText.data.username+'</li>';
            }
            if(responseText.data.email != null){
                html += '<li>'+responseText.data.email+'</li>';
            }
            if(responseText.data.password != null){
                html += '<li>'+responseText.data.password+'</li>';
            }
            if(responseText.data.confirm_password != null){
                html += '<li>'+responseText.data.confirm_password+'</li>';
            }
            if(responseText.data.company_name != null){
                html += '<li>'+responseText.data.company_name+'</li>';
            }
            html += '</ul></div>';
            $("#updateuserinfo").html(html);
            setTimeout(function(){
                $("#updateuserinfo").hide();
            },5000)
        })
});




setTimeout(function(){

    var max_fields      = 100; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div class="form-group col-md-6 append"><label for="pnum">Phone Number</label><a href="#" class="remove_field btn btn-danger m-b-10 pull-right">Remove</a><select class="form-control" id="phone_type_'+x+'" name="phone_type[]" ><option value="mobile">Mobile</option><option value="home">Home</option><option value="work">Work</option><option value="work_fax">Work Fax</option><option value="home_fax">Home Fax</option><option value="skype">Skype</option><option value="pager">Pager</option><option value="work_email">Email</option<option value="other">Other</option></select><input type="text" required="required" class="form-control" id="pnum_'+x+'" name="phone_num[]"></div>'); //add input box
        }
    });
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })

},5000)

$(document).ready(function() {
     	// Get login user profile data
	    $("#firm_name").hide();
	    var role = localStorage.getItem('u_role');
	    var token = localStorage.getItem('u_token');

        if(role == 'owner'){
            $('.owner_class').remove();
        }


        // if(role == 'admin'){
        //     $('.user_type_value').val('owner');
        // }
        // else {
        //     $('.user_type_value').val('user');
        // }
        

	    jQuery.ajax({
	    url: baseUrl + "firm-name",
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
	            if(val.f_status == 'active'){
	                $("#firm_name").append(
	                    '<option value="'+val.f_id+'">'+val.f_name+'</option>'
	                )
	            }else {

	            }
	        });
	        // $( "h2" ).appendTo( $( ".container" ) );  
	        // $(".loading_data").remove();
	        $("#firm_name").show();
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
                console.log("Company name 404");
	        }
	        else {
	            window.location.href = baseUrl + "500";
	        }
	    });


	    // jQuery.ajax({
		   //  url: baseUrl + "projects",
		   //      type: "GET",
		   //      headers: {
		   //        "x-access-token": token
		   //      },
		   //      contentType: "application/json",
		   //      cache: false
		   //  })
	    //     .done(function(data, textStatus, jqXHR) {
	    //     // console.log(data.data);
	    //     // Foreach Loop 
	    //     jQuery.each(data.data, function( i, val ) {
	    //         if(val.p_status == 'active'){
	    //             $("#project_name").append(
	    //                 '<option value="'+val.p_id+'">'+val.p_name+'</option>'
	    //             )
	    //         }else {

	    //         }
	    //     });
	    //     // $( "h2" ).appendTo( $( ".container" ) );
	       
	    //     $(".loading_data").remove();
	    //     $("#project_name").show();
	    // })
	    // .fail(function(jqXHR, textStatus, errorThrown) {
	    //     console.log("HTTP Request Failed");
	    //     var response = jqXHR.responseJSON.code;
	    //     console.log(response);
	    //     if(response == 403){
	    //         window.location.href = baseUrl + "403";
	    //     }
	    //     else if(response == 404){
	    //         window.location.href = baseUrl + "404";
	    //     }
	    //     else {
	    //         window.location.href = baseUrl + "500";
	    //     }
	    // }); 

	    setTimeout(function(){
	     	// Get user profile data
	     	$("#update_profile_form").hide();
			var token = localStorage.getItem('u_token');
			var url = $(location).attr('href').split( '/' );
			userid = url[ url.length - 2 ]; // projects
			// console.log(userid);
            jQuery.ajax({
			url: baseUrl + "users_profile/"+userid,
			    type: "GET",
			    headers: {
			      "Content-Type": "application/json",
			      "x-access-token": token
			    },
			    contentType: "application/json",
			    cache: false
			})
			    .done(function(data, textStatus, jqXHR) {
                                if(data.data.user_image_path!=''){
                                    var user_image_path = '<img src="'+baseUrl+data.data.user_image_path+'" width="50px">';
                                    $("#old_image_path").val(data.data.user_image_path);
                                }else{
                                    var user_image_path = '';
                                }
                                $("#old_image_link").html(user_image_path);
                    jQuery.ajax({
                        url: baseUrl + "users/get_user_info/"+userid,
                        type: "GET",
                        headers: {
                            "Content-Type": "application/json",
                            "x-access-token": token
                        },
                        contentType: "application/json",
                        cache: false
                    }).done(function(data)
                    {
                        console.log(data);
                        var wrapper         = $(".input_fields_wrap"); //Fields wrapper
                        var add_button      = $(".add_field_button"); //Add button ID
                        if(data.data.length)
                        {
                            for(var i =0;i<data.data.length;i++)
                            {
                                    if(i==0)
                                    {
                                        $(wrapper).append('<div class="form-group col-md-6 append"><label for="pnum">Phone Number</label><a href="#" class="add_field_button btn btn-success m-b-10 pull-right">Add Phone</a><select class="form-control" id="phone_type_'+i+'" name="phone_type[]" ><option value="mobile">Mobile</option><option value="home">Home</option><option value="work">Work</option><option value="work_fax">Work Fax</option><option value="home_fax">Home Fax</option><option value="skype">Skype</option><option value="pager">Pager</option><option value="work_email">Email</option><option value="other">Other</option></select><input type="text" required="required" class="form-control" id="pnum_'+i+'" name="phone_num[]"></div>');
                                        $("#phone_type_"+i).val(data.data[i].u_phone_type);
                                        $("#pnum_"+i).val(data.data[i].u_phone_detail);

                                    }
                                else{
                                        $(wrapper).append('<div class="form-group col-md-6 append"><label for="pnum">Phone Number</label><a href="#" class="remove_field btn btn-danger m-b-10 pull-right">Remove</a><select class="form-control" id="phone_type_'+i+'" name="phone_type[]" ><option value="mobile">Mobile</option><option value="home">Home</option><option value="work">Work</option><option value="work_fax">Work Fax</option><option value="home_fax">Home Fax</option><option value="skype">Skype</option><option value="pager">Pager</option><option value="work_email">Email</option><option value="other">Other</option></select><input type="text" required="required" class="form-control" id="pnum_'+i+'" name="phone_num[]"></div>'); //add input box
                                        $("#phone_type_"+i).val(data.data[i].u_phone_type);
                                        $("#pnum_"+i).val(data.data[i].u_phone_detail);
                                    }
    //                            alert(data.data[i].u_phone_type);
    //                            alert(data.data[i].u_phone_detail)
                            }
                        }
                        else{
                            $(wrapper).append('<div class="form-group col-md-6 append"><label for="pnum">Phone Number</label><a href="#" class="add_field_button btn btn-success m-b-10 pull-right">Add Phone</a><select class="form-control" id="phone_type_'+i+'" name="phone_type[]" ><option value="mobile">Mobile</option><option value="home">Home</option><option value="work">Work</option><option value="work_fax">Work Fax</option><option value="home_fax">Home Fax</option><option value="skype">Skype</option><option value="pager">Pager</option><option value="work_email">Email</option><option value="other">Other</option></select><input type="text" required="required" class="form-control" id="pnum_'+i+'" name="phone_num[]"></div>');
                        }
                    })

			    var username = data.data.username;
			    $('#uname').val(username);

			    var email = data.data.email;
			    $('#email').val(email);

			    var lastname = data.data.last_name;
			    $('#lname').val(lastname);

			    var firstname = data.data.first_name;
			    $('#fname').val(firstname);

			    var company_name = data.data.company_name;
			    $('#firm_name').val(company_name);

			    var phone = data.data.phone_number;
			    $('#pnum').val(phone);

			    var position_title = data.data.position_title;
			    $('#position').val(position_title);

			    var role = data.data.role;
			    $('.user_type_value').val(role);
                // console.log(data.data.role);
                if(data.data.role == "admin"){
                    $('.role_admin_hide').remove();
                }
                else {
                    $('.role_normal_hide').remove();
                }

			    var status = data.data.status;
			    if(status == 1){
			    	status = 'Verified';
			    }
			    else if(status == 0){
			    	status = 'Unverified';
			    }
			    else {
			    	status = "Account Disable";
			    }
			    $('.user_status').val(data.data.status);
                // $('#project_name').val(data.data.project_id);
                //alert(data.data.user_role);
                $('#user_role').val(data.data.user_role);
                
                $(".loading_data").hide();
                $("#update_profile_form").show();


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
		},1000)






    });

//    jQuery.ajax({
//        url: baseUrl + "users/"+userid,
//        type: "GET",
//        headers: {
//            "Content-Type": "application/json",
//            "x-access-token": token
//        },
//        contentType: "application/json",
//        cache: false
//    })
//        .done(function(data, textStatus, jqXHR) {
//            console.log(data);
//            var username = data.data.username;
//            $('#uname').val(username);
//
//            var email = data.data.email;
//            $('#email').val(email);
//
//            var lastname = data.data.last_name;
//            $('#lname').val(lastname);
//
//            var firstname = data.data.first_name;
//            $('#fname').val(firstname);
//
//            var company_name = data.data.company_name;
//            $('#firm_name').val(company_name);
//
//            var phone = data.data.phone_number;
//            $('#pnum').val(phone);
//
//            var position_title = data.data.position_title;
//            $('#position').val(position_title);
//
//            var role = data.data.role;
//            $('#role').val(role);
//
//            var status = data.data.status;
//            if(status == 1){
//                status = 'Verified';
//            }
//            else if(status == 0){
//                status = 'Unverified';
//            }
//            else {
//                status = "Account Disable";
//            }
//            $('#status').val(data.data.status);
//            // $('#project_name').val(data.data.project_id);
//            $('#user_role').val(data.data.user_type);
//            $(".loading_data").hide();
//            $("#update_profile_form").show();
//        })













