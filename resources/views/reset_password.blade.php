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

      <h2 class="form-heading">Reset Password</h2>
      <div class="container log-row">
          <form class="form-signin" id="rpasswordSubmit">
              <div class="login-wrap">
                  <span id="resetPasswordError"></span>
                  <input type="text" class="form-control" placeholder="Validation Code" name="verified_code" autofocus >
                  <input type="hidden" id="r_username" class="form-control" name="username" >
                  <input type="text" class="form-control" placeholder="Password" name="password" >
                  <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" >
                  <button class="btn btn-lg btn-success btn-block" type="submit">Submit</button>
              </div>

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
