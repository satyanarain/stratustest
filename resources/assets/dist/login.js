$(document).ready(function() {

    $('#loginSubmit').submit(function(e) {
        e.preventDefault();
        $(".loading_bar_login").show();
        var username, password;
        username = $('input[name=username]').val();
        password = $('input[name=password]').val();
        // console.log(username);
        // console.log(password);
        if (username == null || username == "") {
            $(".loading_bar_login").hide();
            $("#user_required").html('<p style="color:#f00;">Please enter username</p>');
            return false;
        }
        else if (password == null || password == "") {
            $(".loading_bar_login").hide();
            $("#pass_required").html('<p style="color:#f00;">Please enter password</p>');
            return false;
        }
        else {
            jQuery.ajax({
                url: baseUrl + "users/authenticate",
                type: "POST",
                data: {
                    "username": username,
                    "password": password
                },
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                contentType: "application/x-www-form-urlencoded",
                cache: false
            })
            .done(function(data, textStatus, jqXHR) {
                $("#user_required").hide();
                $("#pass_required").hide();
                console.log(data.token);
                var token = data.token;
                window.localStorage.setItem("u_token", data.token);
                
                jQuery.ajax({
                        url: baseUrl + "users/get_user_details",
                        type: "POST",
                        headers: {
                            "x-access-token": token
                        },
                        contentType: "application/x-www-form-urlencoded",
                        cache: false

                    }).done(function(data, textStatus, jqXHR) {
                        $(".loading_bar_login").hide();
                        // console.log(data.data.company_name);
                        window.localStorage.setItem("u_id", data.data.id);
                        window.localStorage.setItem("u_username", data.data.username);
                        window.localStorage.setItem("u_email", data.data.email);
                        window.localStorage.setItem("u_first_name", data.data.first_name);
                        window.localStorage.setItem("u_last_name", data.data.last_name);
                        window.localStorage.setItem("u_company_name", data.data.company_name);
                        window.localStorage.setItem("u_phone_number", data.data.phone_number);
                        window.localStorage.setItem("u_status", data.data.status);
                        window.localStorage.setItem("u_role", data.data.role);
                        if(data.data.role == 'admin'){
                            var uri = 'dashboard/users';
                            window.location.assign(uri);
                        }
                        else {
                            var uri = 'dashboard';
                            window.location.assign(uri);
                        }
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        $(".loading_bar_login").hide();
                        var html;
                        var responseText = jQuery.parseJSON(jqXHR.responseText);
                        console.log("HTTP Request Failed");
                        html = '<div class="alert alert-block alert-danger fade in">'+responseText.description+'</div>';
                        $("#loginFail").html(html);
                    })

            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                    $(".loading_bar_login").hide();
                    var responseText, html;
                    console.log("HTTP Request Failed");
                    responseText = JSON.parse(jqXHR.responseText);
                    html = '<div class="alert alert-block alert-danger fade in">Invalid credentials!</div>';
                    $("#loginFail").html(html);
            })
        }
    });


    $('#forgetUsername').submit(function(e) {
        e.preventDefault();
        
        var email = $('#forget_user').val();      
        console.log(email);

        jQuery.ajax({
            url: baseUrl + "users/request-forgot-username",
            type: "POST",
            data: {
                "email": email
            },
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            console.log(data.code);
            html = '<div class="alert alert-block alert-success fade in">Your username has been sent to your email. Kindly check your inbox.</div>';
            $("#resetUsernameFail").html(html);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                html = '<div class="alert alert-block alert-danger fade in">'+responseText.email+'</div>';
                console.log(jqXHR);
                $("#resetUsernameFail").html(html);
        })
    });

    $('#forgetPassword').submit(function(e) {
        e.preventDefault();
        var email = $('#forget_pass').val();        
        console.log(email);

        jQuery.ajax({
            url: baseUrl + "users/request-forgot-password-link",
            type: "POST",
            data: {
                "email": email
            },
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
            .done(function(data, textStatus, jqXHR) {
            console.log(data.description);
            html = '<div class="alert alert-block alert-success fade in">'+data.description+'</div>';
            $("#resetPasswordFail").html(html);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(responseText.email);
                html = '<div class="alert alert-block alert-danger fade in">'+responseText.email+'</div>';
                $("#resetPasswordFail").html(html);
        })
    });

    $('#rpasswordSubmit').submit(function(e) {
        e.preventDefault();
        var username = $('#username').val();        
        var verified_code = $('#verified_code').val();        
        var password = $('#password').val();        
        var password_confirmation = $('#password_confirmation').val();        
        if (verified_code == null || verified_code == "") {
            $(".verified_code").html('<div class="alert alert-block alert-danger fade in">Please Enter Verification Code!</div>');
            return false;
        }
        else if (password == null || password == "") {
            $(".password").html('<div class="alert alert-block alert-danger fade in">Please Enter Password!</div>');
            return false;
        }
        else if (password != password_confirmation) {
            $(".password_confirmation").html('<div class="alert alert-block alert-danger fade in">Password and confirm password are not Match!</div>');
            return false;
        }
        else {
                jQuery.ajax({
                    url: baseUrl + "users/reset-password",
                    type: "POST",
                    data: {
                        "username"      : username,
                        "verified_code" : verified_code,
                        "password"      : password
                    },
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    contentType: "application/x-www-form-urlencoded",
                    cache: false
                })
                    .done(function(data, textStatus, jqXHR) { 
                    console.log(data);
                    html = '<div class="alert alert-block alert-success fade in">'+data.description+'</div>';
                    $("#resetPasswordError").html(html);
                    setTimeout(function(){
                        window.location.href = baseUrl;
                    },2000) 
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                        console.log("HTTP Request Failed");
                        var responseText, html;
                        responseText = JSON.parse(jqXHR.responseText);
                        console.log(responseText.data);
                        $("#resetPasswordError").html(responseText);
                        
                })
        }
    });
    
    if(localStorage.getItem('site_logo'))
    {
        $('.login-logo img').attr('src', baseUrl+localStorage.getItem('site_logo'));
    }else{
        jQuery.ajax({
            url: baseUrl + "get_site_logo",
            type: "POST",
            data: '',
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            //console.log(baseUrl+data.data[0].ws_value);
            $('.login-logo img').attr('src', baseUrl+data.data[0].ws_value);
            localStorage.setItem('site_logo',data.data[0].ws_value);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                html = '<div class="alert alert-block alert-danger fade in">'+responseText.email+'</div>';
        })
    }
});