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
            $('#role').text(role);


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