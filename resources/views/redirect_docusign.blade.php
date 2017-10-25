<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="author" content="Mosaddek" />
    <meta name="keyword" content="slick, flat, dashboard, bootstrap, admin, template, theme, responsive, fluid, retina" />
    <meta name="description" content="" />
    <link rel="shortcut icon" href="javascript:;" type="image/png">

    <title>Stratus</title>
</head>
<body>

<h1>Send Successfully</h1>

    <script src="{{ url('/resources/assets/js/jquery-1.11.1.min.js') }}"></script>
    <script src="{{ url('/resources/assets/js/jquery-migrate.js') }}"></script>
    <script src="{{ url('/resources/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('/resources/assets/js/modernizr.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() { 
            var $body = $(window.frameElement).parents('body'),
                dlg = $body.find('#docusing_link');
                $body.find('#myModal2').hide();
                $body.find('.modal-backdrop.in').hide();
            dlg.remove();
        });

        $(window).load(function(){
            window.close();
            $('#myModal2').modal('hide');
            $('#myModal2').hide();
            $('.modal-backdrop.in').hide();

            var url = $(location).attr('href')
            // alert(url);

            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');
                    console.log(sParameterName);

                if (sParameterName[0] === 'event') {
                    sParameterName[1] === 'Send';
                    // alert('send');
                    window.close();
                    $('#myModal2').modal('hide');
                    $('#myModal2').hide();
                    $('.modal-backdrop.in').hide();
                }
            }

            var $body = $(window.frameElement).parents('body'),
                dlg = $body.find('#docusing_link');
                $body.find('#myModal2').hide();
                $body.find('.modal-backdrop.in').hide();
            dlg.remove();

            //window.parent.document.getElementById("docusing_link").remove();
            // var a1 = window.parent.document.getElementById("docusing_link");
            // console.log(a1);
            // // a1.remove();
            // a1.parent.getElementById("myModal2").remove();
            // a1.parent().parent().parent().parent().parent().remove();
            // a1.parent().parent().parent().parent().parent().remove();
            // window.parent.parent.parent.parent.parent.document.getElementById("myModal2").style.display = 'none';
            // window.parent.document.getelementsbyclassname("modal-backdrop.in").style.opacity = "0";
            // event=Send
        });
    </script>
  </body>
</html>
