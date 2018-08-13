$(document).ready(function()
{
    // Get login user profile data
    $("#s2id_firm_name").hide();
    $("#s2id_project_name").hide();
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    if(role == 'owner'){
        $('.owner_class').remove();
    }

    if(role == 'admin'){
        $('.user_type_value').val('owner');
    }
    else {
        $('.user_type_value').val('user');
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
                $("#company_name").append(
                    '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                )
            }else {

            }
        });
        var add_company_on_fly_permission = jQuery.inArray("project_add_company_on_fly", check_user_access );
        console.log(add_company_on_fly_permission+'company_fly');
        if(add_company_on_fly_permission>0 || role=="owner" || role=="admin"){
        $("#company_name").append(
            '<option style="font-weight:bold;">Add New Company</option>'
        )
        }
        // $( "h2" ).appendTo( $( ".container" ) );

        $(".loading_data").remove();
        $("#s2id_firm_name").show();
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        // console.log(response);
        if(response == 403){
            window.location.href = baseUrl + "403";
        }
        else if(response == 404){
            console.log('Firm Name 404');
            var add_company_on_fly_permission = jQuery.inArray("project_add_company_on_fly", check_user_access );
            console.log(add_company_on_fly_permission+'company_fly');
            if(add_company_on_fly_permission>0 || role=="owner" || role=="admin"){
            $("#company_name").append(
                '<option style="font-weight:bold;">Add New Company</option>'
            )
            }
            $(".loading_data").remove();
            alert("You can't add a new user, first add a company name.");
            $("#s2id_project_name").show();
            // window.location.href = baseUrl + "404";
        }
        else {
            window.location.href = baseUrl + "500";
        }
    });

    jQuery.ajax({
    url: baseUrl + "projects",
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
            if(val.p_status == 'active'){
                $("#project_name").append(
                    '<option value="'+val.p_id+'">'+val.p_name+'</option>'
                )
            }else {

            }
        });
        // $( "h2" ).appendTo( $( ".container" ) );

        $(".loading_data").remove();
        $("#s2id_project_name").show();
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        console.log(response);
        if(response == 403){
            window.location.href = baseUrl + "403";
        }
        else if(response == 404){
            console.log('Project Name 404');
            // window.location.href = baseUrl + "404";
        }
        else {
            window.location.href = baseUrl + "500";
        }
    });

    $('#company_name').change(function(){
        var company = $(this).val();
        if(company=="Add New Company")
        {
            $('#add-company').modal('show');
            $('#add-company').on('shown.bs.modal',function(){
                google.maps.event.trigger(map, "resize");
              });
        }
    })
});
    $('.add_user_form').click(function(e) {
        e.preventDefault();
        $('.loading-submit').show();
        var username       = $('#uname').val().toLowerCase();
	    var email          = $('#email').val().toLowerCase();
        var first_name     = $('#fname').val();
	    var last_name      = $('#lname').val();
	    var company_name   = $('#company_name').val();
        var user_image_path =    $("#user_image_path").val();
        var phone          = $('#pnum').val();
        var position       = $('#position').val();
	var user_role       = $('#user_role').val();
        // var project_id      = $('#project_name').val();
	    var role           = $('#role').val();
        var token          = localStorage.getItem('u_token');
        var count          = $(".input_fields_wrap").children().length;

        console.log(company_name);

        // Validation Certificate
        var html;
        var is_error = false;
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';

        var inp_phone_num = document.getElementsByName('phone_num[]');
        var inp_phone_type = document.getElementsByName('phone_type[]');
        for (var i = 0; i <inp_phone_num.length; i++) {
            var inp_phone_type_value =inp_phone_type[i];
            var phone_type_value = inp_phone_type_value.value;
            var inp_phone_num_value =inp_phone_num[i];
            var phone_value = inp_phone_num_value.value;
            console.log(phone_value);
            if(phone_type_value==''){
                html += '<li>Phone Type filed is invalid.</li>';
                is_error = true;
            }
            if(phone_value == ''){
                html += '<li>Phone Number filed is invalid.</li>';
                is_error = true;
            }
        }

        if(username == ''){
            html += '<li>Username filed is invalid.</li>';
            is_error = true;
        }
        var faizan = username.trim();
        if(faizan.indexOf(" ") !== -1 ){
            html += '<li>Please enter a valid username (without space)</li>';
            is_error = true;
        }
        if(email == ''){
            html += '<li>Email filed is invalid.</li>';
            is_error = true;
        }
        if(company_name == 'Select Company name'){
            html += '<li>Company name filed is invalid.</li>';
            is_error = true;
        }
        if(position == ''){
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
        if(role == ''){
            html += '<li>User type filed is invalid.</li>';
            is_error = true;
        }

        html += '</ul></div>';

        if(is_error == true){
            $('.loading-submit').hide();
            $("#alert_message").html(html);
            $("#alert_message").show();
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            setTimeout(function(){
                $("#alert_message").hide()
            },5000)
            return false;
        }



        jQuery.ajax({
            url: baseUrl + "users/add",
            type: "POST",
            data: {
                "username"      : username,
                "email"         : email,
                "first_name"    : first_name,
                "last_name"     : last_name,
                "company_name"  : company_name,
                "phone_number"  : phone,
                "position"      : position,
                "role"          : role,
                // "project_id" : project_id,
                "user_role" : user_role,
                "user_image_path" : user_image_path
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
            .done(function(data, textStatus, jqXHR)
            {
                $('.loading-submit').hide();
                $(".remove_file_drop").trigger("click");
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
                            { "user_id":data.user_id,
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
                $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
                }, 'fast')
                $("#updateuserinfo").show();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New user added successfully!</div></div>';
                $("#updateuserinfo").html(html);
                $("#updateuserinfo").html(html);
                setTimeout(function(){
                    $("#updateuserinfo").fadeOut(1000)
                },5000)
                $("#uname").removeAttr('value');
                $("#email").removeAttr('value');
                $("#pnum").removeAttr('value');
                $("#user_role").removeAttr('value');
                $("#company_name").removeAttr('value');
                $("#fname").removeAttr('value');
                $("#lname").removeAttr('value');
                $("#position").removeAttr('value');
                $(".user_contact_no").removeAttr('value');
                
                // $("#role").removeAttr('value');
                $("#pnum_1").removeAttr('value');
                $(".new_append").remove();
                $(".first_button").hide();
                $(".another_button").show();

        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            $('.loading-submit').hide();
            console.log("HTTP Request Failed");
            var responseText, html;
            responseText = JSON.parse(jqXHR.responseText);
            // console.log(responseText.data.username);
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#updateuserinfo").show();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            if(responseText.data.username != null){
            	html += '<li>'+responseText.data.username+'</li>';
            }
            if(responseText.data.email != null){
            	html += '<li>'+responseText.data.email+'</li>';
            }
            if(responseText.data.role != null){
            	html += '<li>'+responseText.data.role+'</li>';
            }
            html += '</ul></div>';
            $("#updateuserinfo").html(html);
            setTimeout(function(){
                $("#updateuserinfo").fadeOut(1000)
            },5000)
        })
    });
    var max_fields      = 100; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment

            $(wrapper).append('<div class="form-group append new_append"><label for="pnum">Phone Number</label><a href="#" class="remove_field btn btn-danger m-b-10 pull-right">Remove</a><div class="form-group"><select class="form-control user_contact_no" id="phone_type_'+x+'" name="phone_type[]" ><option value="mobile">Mobile</option><option value="home">Home</option><option value="work">Work</option><option value="work_fax">Work Fax</option><option value="home_fax">Home Fax</option><option value="skype">Skype</option><option value="pager">Pager</option><option value="work_fax">Email</option<option value="other">Other</option></select></div><div class="form-group"><input type="text" class="form-control" id="pnum_'+x+'" name="phone_num[]" onkeypress="return isNumber(event)"></div></div>'); //add input box

        }
    });
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })
