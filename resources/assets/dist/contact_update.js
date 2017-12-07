$(document).ready(function() {
    // Get login user profile data
    $("#firm_name").hide();
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    userid      = url[ url.length - 2 ]; // user_id
    project_id  = url[ url.length - 4 ]; // project_id

    // if(role == 'admin'){
    //     $('.user_type_value').val('owner');
    // }
    // else {
    //     $('.user_type_value').val('user');
    // }
    
    // console.log(userid);
    // Check View All Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray( "contact_update", check_user_access );
    // console.log(check_permission);
    if(check_permission < 1){
        window.location.href = baseUrl + "403";
    }
    else {
        console.log('Yes Permission');
        $('.body-content .wrapper').show();
    }

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
            console.log("Firm Name 404");
            // window.location.href = baseUrl + "404";
        }
        else {
            window.location.href = baseUrl + "500";
        }
    });



        setTimeout(function(){
        // Get user profile data
        $("#update_profile_form").hide();
        var token = localStorage.getItem('u_token');
        // console.log(userid);

    jQuery.ajax({
        url: baseUrl + "users/"+userid,
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
            jQuery.ajax({
                url: baseUrl + "users/get_user_info/"+userid,
                type: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "x-access-token": token
                },
                contentType: "application/json",
                cache: false
            })
            .done(function(data){
                // console.log(data);
                var wrapper         = $(".input_fields_wrap"); //Fields wrapper
                var add_button      = $(".add_field_button"); //Add button ID
                for(var i =0;i<data.data.length;i++)
                {
                    if(i==0){
                        $(wrapper).append('<div class="form-group col-md-6 append"><label for="pnum">Phone Number <span class="text-danger">*</span></label><a href="#" class="add_field_button btn btn-success m-b-10 pull-right">Add Phone</a><select class="form-control" id="phone_type_'+i+'" name="phone_type[]" ><option value="mobile">Mobile</option><option value="home">Home</option><option value="work">Work</option><option value="work_fax">Work Fax</option><option value="home_fax">Home Fax</option><option value="skype">Skype</option><option value="pager">Pager</option><option value="other">Other</option></select><input type="text" required="required" class="form-control" id="pnum_'+i+'" name="phone_num[]"></div>');
                        $("#phone_type_"+i).val(data.data[i].u_phone_type);
                        $("#pnum_"+i).val(data.data[i].u_phone_detail);

                    }
                    else{
                        $(wrapper).append('<div class="form-group col-md-6 append"><label for="pnum">Phone Number <span class="text-danger">*</span></label><a href="#" class="remove_field btn btn-danger m-b-10 pull-right">Remove</a><select class="form-control" id="phone_type_'+i+'" name="phone_type[]" ><option value="mobile">Mobile</option><option value="home">Home</option><option value="work">Work</option><option value="work_fax">Work Fax</option><option value="home_fax">Home Fax</option><option value="skype">Skype</option><option value="pager">Pager</option><option value="other">Other</option></select><input type="text" required="required" class="form-control" id="pnum_'+i+'" name="phone_num[]"></div>'); //add input box
                        $("#phone_type_"+i).val(data.data[i].u_phone_type);
                        $("#pnum_"+i).val(data.data[i].u_phone_detail);
                    }
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

            var company_name = data.data.company_id;
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
            $('#user_role').val(data.data.user_type);
            $(".loading_data").hide();
            $("#update_profile_form").show();
        })
    .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            if(response == 403){
                // window.location.href = baseUrl + "403";
                console.log("403");
            }
            else if(response == 404){
                console.log("User Detail 404");
                // window.location.href = baseUrl + "404";
            }
            else {
                // console.log("500");
                window.location.href = baseUrl + "500";
            }
        })
    },2000)


        // var permission_key_static = [];
        // var count = 0;
        // $('input[name^=permission_key]').each(function(){
        //    permission_key_static[count] =  $(this).val();
        //    count ++;
        // });
        // console.log(permission_key_static);

        jQuery.ajax({
        url: baseUrl + "contact/"+project_id+"/get_permission/"+userid,
            type: "GET",
            headers: {
              "Content-Type": "application/json",
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            // console.log(data.data.lenght);
            var count_permission = 0;
            jQuery.each( data.data, function( i, val )
            {
                // console.log(val.pup_permission_key);
                $('#'+val.pup_permission_key).prop('checked', true);
                // if(jQuery.inArray(val.pup_permission_key, permission_key_static)){
                //     console.log('True');
                // }
                // $('input[name=permission_key*]').find('')
                count_permission ++;
            });
            if(count_permission >= 96){
                $('#select_all').prop('checked', true);
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            if(response == 403){
                // window.location.href = baseUrl + "403";
                console.log("403 Permission Show");
            }
            else if(response == 404){
                console.log("404 User Permission");
                // window.location.href = baseUrl + "404";
            }
            else {
                // console.log("500");
                window.location.href = baseUrl + "500";
            }
        })
        
    jQuery.ajax({
        url: baseUrl + "contact/"+project_id+"/get_notification/"+userid,
            type: "GET",
            headers: {
              "Content-Type": "application/json",
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            // console.log(data.data.lenght);
            var count_notification = 0;
            jQuery.each( data.data, function( i, val )
            {
                // console.log(val.pup_permission_key);
                $('#'+val.pun_notification_key).prop('checked', true);
                // if(jQuery.inArray(val.pup_permission_key, permission_key_static)){
                //     console.log('True');
                // }
                // $('input[name=permission_key*]').find('')
                count_notification ++;
            });
            if(count_notification >= 96){
                $('#select_all').prop('checked', true);
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            if(response == 403){
                // window.location.href = baseUrl + "403";
                console.log("403 Permission Show");
            }
            else if(response == 404){
                console.log("404 User Permission");
                // window.location.href = baseUrl + "404";
            }
            else {
                // console.log("500");
                window.location.href = baseUrl + "500";
            }
        })

    $('#select_all').change(function() {
    if($(this).is(":checked")) {
        $("#user_permissions input[type='checkbox']").prop('checked', true);
    }
    else {
        $("#user_permissions input[type='checkbox']").prop('checked', false);
    }
});
});



$('#update_profile_form').click(function(e) {

    e.preventDefault();
    $('.loading-submit').show();
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
    // var role 			= $('#role').val();
    var position_title        = $('#position').val();
    // var user_role       = $('#user_role').val();
    // var project_id 		= $('#project_name').val();
    var status 			= $('#status').val();
    // console.log(project_id);
    // console.log(position);
    var token = localStorage.getItem('u_token');

    // Validation Certificate
    var html;
    var is_error = false;
    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';

    var inp_phone_num = document.getElementsByName('phone_num[]');
    for (var i = 0; i <inp_phone_num.length; i++) {
        var inp_phone_num_value =inp_phone_num[i];
        var phone_value = inp_phone_num_value.value;
        console.log(phone_value);
        if(phone_value == ''){
            html += '<li>Phone Number filed is invalid.</li>';
            is_error = true;
        }
    }

    if(company_name == 'Select Company name'){
        html += '<li>Company name filed is invalid.</li>';
        is_error = true;
    }
    if(position_title == ''){
        html += '<li>Position filed is invalid.</li>';
        is_error = true;
    }
    if(first_name == ''){
        html += '<li>First name filed is invalid.</li>';
        is_error = true;
    }
    if(last_name == ''){
        html += '<li>Last name filed is invalid.</li>';
        is_error = true;
    }
    // if(role == ''){
    //     html += '<li>User type filed is invalid.</li>';
    //     is_error = true;
    // }

    html += '</ul></div>';

    if(is_error == true){
        $("#alert_message").html(html);
        $("#alert_message").show();
        $('.loading-submit').hide();
        $('html, body').animate({
            scrollTop: $(".page-head").offset().top
        }, 'fast')
        setTimeout(function(){
            $("#alert_message").hide()
        },5000)
        return false;
    }

    jQuery.ajax({
        url: baseUrl + "users/"+userid+"/update",
        type: "POST",
        data: {
            "first_name" : first_name,
            "last_name" : last_name,
            "company_name" : company_name,
            "position_title" : position_title,
            "phone_number" : phone,
            "username" : uname,
            "email" : email,
            "confirm_password" : cpass,
            "password" : pass,
            // "role" : role,
            // "user_role" : user_role,
            // "project_id" : project_id,
            "status" : status,
        },
        headers: {
            "x-access-token": token
        },
        contentType: "application/x-www-form-urlencoded",
        cache: false
    })
        .done(function(data, textStatus, jqXHR) {
            $('.loading-submit').hide();
            jQuery.ajax({
                url: baseUrl + "users/delete_user_data",
                type: "POST",
                data:JSON.stringify(
                    {
                        "user_id":userid
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

            // Add User Permission
            var permission_key = {}; // note this
            var count = 0;
            $('input[name^=permission_key]:checked').each(function(){
               var per_value = $(this).val();
               permission_key[count] =  $(this).val();
               count ++;
            });
            console.log(permission_key);
            jQuery.ajax({
                url: baseUrl + "contact/"+project_id+"/add_permission/"+userid,
                type: "POST",
                data: {
                    "permission_key"    : permission_key
                },
                headers: {
                    "x-access-token": token
                },
                contentType: "application/x-www-form-urlencoded",
                cache: false
            })
            .done(function(data, textStatus, jqXHR) {
                console.log(data);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(jqXHR);
            })
            
            // Add User Notification
            var notification_key = {}; // note this
            var count = 0;
            $('input[name^=notification_key]:checked').each(function(){
               var per_value = $(this).val();
               notification_key[count] =  $(this).val();
               count ++;
            });
            console.log(notification_key);
            jQuery.ajax({
                url: baseUrl + "contact/"+project_id+"/add_notification/"+userid,
                type: "POST",
                data: {
                    "notification_key"    : notification_key
                },
                headers: {
                    "x-access-token": token
                },
                contentType: "application/x-www-form-urlencoded",
                cache: false
            })
            .done(function(data, textStatus, jqXHR) {
                console.log(data);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(jqXHR);
            })
            // html = '<div class="alert alert-block alert-success fade in">Profile updated!</div>';
            // $("#updateuserinfo").html(html);
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')

            $("#updateuserinfo").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Contact updated successfully!</div></div>';
            $("#alert_message").html(html);

            setTimeout(function(){
                $("#alert_message").hide();
            },5000)

            // var u_id = localStorage.getItem('u_id');
            // // localStorage.clear('access_permission');
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
            //     var count = 0;
            //     var access_permission = [];
            //     jQuery.each( data, function( i, val ) {
            //         // console.log(val.pup_permission_key);
            //         access_permission[count] = val.pup_permission_key;
            //         localStorage.setItem("access_permission", JSON.stringify(access_permission));
            //         count ++;
            //     });
            // })

        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            $('.loading-submit').hide();
            console.log("HTTP Request Failed");
            var responseText, html;
            responseText = JSON.parse(jqXHR.responseText);
            console.log(jqXHR);
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
            $(wrapper).append('<div class="form-group col-md-6 append"><label for="pnum">Phone Number <span class="text-danger">*</span></label><a href="#" class="remove_field btn btn-danger m-b-10 pull-right">Remove</a><select class="form-control" id="phone_type_'+x+'" name="phone_type[]" ><option value="mobile">Mobile</option><option value="home">Home</option><option value="work">Work</option><option value="work_fax">Work Fax</option><option value="home_fax">Home Fax</option><option value="skype">Skype</option><option value="pager">Pager</option><option value="work_fax">Email</option<option value="other">Other</option></select><input type="text" required="required" class="form-control" id="pnum_'+x+'" name="phone_num[]"></div>'); //add input box
        }
    });
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })

},5000)
