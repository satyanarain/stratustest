<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stratus | Email Verification</title>

    <!-- Base Styles -->
    <link href="{{ url('/resources/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ url('/resources/assets/css/style-responsive.css') }}" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.min.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->


</head>

  <body class="login-body">

      <div class="login-logo">
          <img src="{{ url('/resources/assets/img/logo.png') }}" alt=""/>
      </div>
      <h2 class="form-heading update_pass_container">Change your Password</h2>
      <div class="container log-row update_pass_container">
              <div class="login-wrap">
                <form class="form-signin" id="loginSubmit">
                  <div class="loading_bar_login" id="loading_bar_login" style="text-align: center; display: none;">
                      <img src="{{ url('/resources/assets/img/loading-sidebar.svg') }}" alt=""/>
                  </div>

                    <div id="loginFail" style="font-color:red;color: red;"></div>
                  <label>Username</label>
                  <input type="text" class="form-control" placeholder="Username" name="username" autofocus >
                  <label>Old Password</label>
                  <input type="password" class="form-control" placeholder="Old Password" name="password" autofocus >
                  <label>New Password</label>
                  <input type="password" class="form-control" placeholder="New Password" name="new_password" >
                  <label>Confirm New Password</label>
                  <input type="password" class="form-control" placeholder="Confirm New Password" name="confirm_new_password" >
                  <button class="btn btn-lg btn-success btn-block" type="submit">Change Password</button>
                  
                </form>
              </div>

              

          </form>
      </div>
      <div id="email_verified" style="display: none;">
        <h2 class="form-heading">YOUR PASSWORD HAS BEEN SUCCESSFULLY CHANGED.</h2>
        <div class="container log-row">
            <form class="form-signin">
                <div class="login-wrap">
                    <a href="{{ url('/') }}" class="btn btn-lg btn-success btn-block">Back to Login</a>
                </div>
            </form>
        </div>
      </div>


      <!--jquery-1.10.2.min-->
      <script src="{{ url('/resources/assets/js/jquery-1.11.1.min.js') }}"></script>
      <script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
      <script type="text/javascript">
        $(document).ready(function() {
            var url = window.location.pathname;
            var email_verification = url.substring(url.lastIndexOf('/') + 1);
            if(email_verification == null){
              window.location.href = baseUrl;
            }
            //alert(email_verification);
            $('#loginSubmit').submit(function(e) {
            e.preventDefault();
            $(".loading_bar_login").show();
            var password,new_password,confirm_new_password;
            var username = $('input[name=username]').val();
            password = $('input[name=password]').val();
            new_password = $('input[name=new_password]').val();
            confirm_new_password = $('input[name=confirm_new_password]').val();
            if (username == null || username == "") {
                $(".loading_bar_login").hide();
                $("#loginFail").html('<p style="color:#f00;">Please enter your Username.</p>');
                return false;
            }else if (password == null || password == "") {
                $(".loading_bar_login").hide();
                $("#loginFail").html('<p style="color:#f00;">Please enter your Password.</p>');
                return false;
            }else if (new_password == null || new_password == "" || new_password != confirm_new_password) {
                $(".loading_bar_login").hide();
                $("#loginFail").html('<p style="color:#f00;">Password and Confirm Password not match.</p>');
                return false;
            }
            else {

                jQuery.ajax({
                    url: baseUrl + "users/update_password",
                    type: "POST",
                    data: {
                        "username": username,
                        "email_verification": email_verification,
                        "new_password": new_password,
                        "password": password
                    },
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    contentType: "application/x-www-form-urlencoded",
                    cache: false
                })
                .done(function(data, textStatus, jqXHR) {
                    $('#email_verified').show();
                    $('.update_pass_container').hide();
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
          
           
      });
      </script>
      <!--Bootstrap Js-->
      <script src="{{ url('/resources/assets/js/bootstrap.min.js') }}"></script>
      <script src="{{ url('/resources/assets/js/respond.min.js') }}"></script>


  </body>
</html>
