<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Template">
    <meta name="keywords" content="admin dashboard, admin, flat, flat ui, ui kit, app, web app, responsive">
    <link rel="shortcut icon" href="img/ico/favicon.png">
    <title>Stratus | Login</title>

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

      <h2 class="form-heading">login</h2>
      <div class="container log-row">
              <div class="login-wrap">
                <form class="form-signin" id="loginSubmit">
                  <div class="loading_bar_login" id="loading_bar_login" style="text-align: center; display: none;">
                      <img src="{{ url('/resources/assets/img/loading-sidebar.svg') }}" alt=""/>
                  </div>

                  <span id="loginFail"></span>
                  <input type="text" class="form-control" placeholder="Username" name="username" autofocus >
                  <span id="user_required"></span>
                  <input type="password" class="form-control" placeholder="Password" name="password" >
                  <span id="pass_required"></span>
                  <button class="btn btn-lg btn-success btn-block" type="submit">LOGIN</button>
                  <label class="checkbox-custom check-success" style="width: 100%;">
                      <!-- <input type="checkbox" value="remember-me" id="checkbox1"> <label for="checkbox1">Remember me</label> -->
                      <a class="pull-left" data-toggle="modal" href="#forgotuname"> Forgot Username</a>
                      <a class="pull-right" data-toggle="modal" href="#forgotPass"> Forgot Password</a>
                  </label>
                </form>
              </div>

              <!-- FORGOT PASS MODAL -->
              <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="forgotPass" class="modal fade">
                  <div class="modal-dialog">
                      <div class="modal-content">
                        <form id="forgetPassword">
                          <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                              <h4 class="modal-title">Forgot Password?</h4>
                          </div>
                          <div class="modal-body">
                              <span id="resetPasswordFail"></span>
                              <p>Enter your username below to reset your password.</p>
                              <input type="text" name="email" id="forget_pass" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">

                          </div>
                          <div class="modal-footer">
                              <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                              <button class="btn btn-success" type="submit">Submit</button>
                          </div>
                        </form>
                      </div>
                  </div>
              </div>
              <!-- modal -->

              <!-- FORGOT USERNAME MODAL -->
              <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="forgotuname" class="modal fade">
                  <div class="modal-dialog">
                      <div class="modal-content">
                        <form id="forgetUsername">
                          <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                              <h4 class="modal-title">Forgot Username?</h4>
                          </div>
                          <div class="modal-body">
                              <span id="resetUsernameFail"></span>
                              <p>Enter your email below to receive your username.</p>
                              <input type="text" name="email" id="forget_user" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">

                          </div>
                          <div class="modal-footer">
                              <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                              <button class="btn btn-success" type="submit">Submit</button>
                          </div>
                        </form>
                      </div>
                  </div>
              </div>
              <!-- modal -->

          </form>
      </div>


      <!--jquery-1.10.2.min-->
      <script src="{{ url('/resources/assets/js/jquery-1.11.1.min.js') }}"></script>
      <!--Bootstrap Js-->
      <script src="{{ url('/resources/assets/dist/login.js') }}"></script>
      <script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
      <script src="{{ url('/resources/assets/js/bootstrap.min.js') }}"></script>
      <script src="{{ url('/resources/assets/js/respond.min.js') }}"></script>

  </body>
</html>
