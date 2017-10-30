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
      
      <div id="email_verified" style="display: none;">
        <h2 class="form-heading">Your Email Successfully Verified</h2>
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
          // $("#email_verified").hide();
          setTimeout(function(){
            var url = window.location.pathname;
            var user_id = url.substring(url.lastIndexOf('/') + 1);
            if(user_id == null){
              window.location.href = baseUrl;
            }
            jQuery.ajax({
            url: baseUrl + "users/email_verification",
                  type: "POST",
                  data: {
                      "user_id": user_id
                  },
                  contentType: "application/x-www-form-urlencoded",
                  cache: false
              })
                  .done(function(data, textStatus, jqXHR) {
                  console.log(data);
                  $("#email_verified").show();
              })
              .fail(function(jqXHR, textStatus, errorThrown) {
                  console.log("HTTP Request Failed");
                  console.log(jqXHR);

                  // window.location.href = baseUrl + "404";
              })
           },1000) 
      });
      </script>
      <!--Bootstrap Js-->
      <script src="{{ url('/resources/assets/js/bootstrap.min.js') }}"></script>
      <script src="{{ url('/resources/assets/js/respond.min.js') }}"></script>


  </body>
</html>
