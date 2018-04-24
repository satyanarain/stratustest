$(document).ready(function() {
    $("#user_detail_view").hide();
    var url = window.location.pathname;

    var userid = url.substring(url.lastIndexOf('/') + 1);



    var token = localStorage.getItem('u_token');
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
            console.log(data);
            var username = data.data.username;
            $('#username').text(username);
            var user_image_path = data.data.user_image_path;
            if(user_image_path!='')
                $('#user_image_path').html('<a href="'+baseUrl+user_image_path+'" target="_blank"><img src="'+baseUrl+user_image_path+'" width="100px;"></a>');
            var email = data.data.email;
            $('#email').text(email);

            var lastname = data.data.last_name;
            $('#lastname').text(lastname);

            var firstname = data.data.first_name;
            $('#firstname').text(firstname);

            var position = data.data.position_title;
            $('#position').text(position);

            var company_name = data.data.company_name;
            $('#cname').text(company_name);

            var phone = data.data.phone_number;
            $('#phone').text(phone);

            var role = data.data.role;
            $('#role').text('App '+role);


            var position_title = data.data.position_title;
            if(position_title == null){
                position_title = '-';
            }
            else {

                position_title = position_title;
            }

            // var position_title = data.data.position_title;
            $('#position_title').text(position_title);

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
            $('#status').text(status);
            $("#user_detail_view").show();


            $('#status').text(status);
            $("#user_detail_view").show();
            var check_length = data.data.user_detail.length;
            console.log(check_length);
            if(check_length !='0')
            {
                var length1 = Object.keys(data.data.user_detail[0]).length;
                for(var i =0 ;i<check_length;i++)
                {
                    $('#phone_setting').append('<span style="text-transform: capitalize;"><strong style="min-width: 60px; display: inline-block;">'+data.data.user_detail[i].u_phone_type+' : </strong>'+data.data.user_detail[i].u_phone_detail+'</span><br/>')

                }
            }
            else
            {

                $('#phone_setting').append("-----")
            }

            $(".loading_data").hide();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {


            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            return false;
//		    if(response == 403){
//		    	window.location.href = baseUrl + "403";
//		    }
//		    else if(response == 404){
//		    	window.location.href = baseUrl + "404";
//		    }
//		    else {
//		    	window.location.href = baseUrl + "500";
//		    }
        })
});

function openProfileUpdate() {

    $('#profile_update').modal('show');

            var token = localStorage.getItem('u_token');
            var url = window.location.pathname;

            var userid = url.substring(url.lastIndexOf('/') + 1);
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
                $('#user_role').val(data.data.user_type);
                
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
            }); 
    
}