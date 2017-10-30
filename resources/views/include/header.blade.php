<!DOCTYPE html>
<html lang="en">
<head>
    <script type="text/javascript">
        var token = localStorage.getItem('u_token');
        if (token){
            console.log('Valid User');
        }
        else {
            window.location.href = "{{ url('/403') }}";
        }
    </script>
    <meta charset="utf-8">

    <!-- no cache headers -->
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Cache-Control" content="no-cache">
    <!-- end no cache headers -->

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="author" content="" />
    <meta name="keyword" content="" />
    <meta name="description" content="" />
    <link rel="shortcut icon" href="javascript:;" type="image/png">
    <title>StratusCM</title>
    <!--easy pie chart-->
    <link href="{{ url('/resources/assets/js/jquery-easy-pie-chart/jquery.easy-pie-chart.css') }}" rel="stylesheet" type="text/css" media="screen" />

    <!--vector maps -->
    <link rel="stylesheet" href="{{ url('/resources/assets/js/vector-map/jquery-jvectormap-1.1.1.css') }}">

    <!--right slidebar-->
    <link href="{{ url('/resources/assets/css/slidebars.css') }}" rel="stylesheet">

    <!--switchery-->
    <link href="{{ url('/resources/assets/js/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css" media="screen" />

    <!--fuelux tree-->
    <link href="{{ url('/resources/assets/js/fuelux/css/tree-style.css') }}" rel="stylesheet" type="text/css" />

    <!--jquery-ui-->
    <!-- <link href="{{ url('/resources/assets/js/jquery-ui/jquery-ui-1.10.1.custom.min.css') }}" rel="stylesheet" /> -->

    <!--iCheck-->
    <link href="{{ url('/resources/assets/js/icheck/skins/all.css') }}" rel="stylesheet">

    <link href="{{ url('/resources/assets/css/owl.carousel.css') }}" rel="stylesheet">

    <!--toastr-->
    <link href="{{ url('/resources/assets/js/toastr-master/toastr.css') }}" rel="stylesheet" type="text/css" />

    <!--  summernote -->
    <link href="{{ url('/resources/assets/js/summernote/dist/summernote.css') }}" rel="stylesheet">

    <!--bootstrap-wysihtml5-->
    <link rel="stylesheet" type="text/css" href="{{ url('/resources/assets/js/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" />

    <!--Data Table-->
  <!--   <link href="{{ url('/resources/assets/js/data-table/css/jquery.dataTables.css') }}" rel="stylesheet">
    <link href="{{ url('/resources/assets/js/data-table/css/dataTables.tableTools.css') }}" rel="stylesheet">
    <link href="{{ url('/resources/assets/js/data-table/css/dataTables.colVis.min.css') }}" rel="stylesheet">
    <link href="{{ url('/resources/assets/js/data-table/css/dataTables.responsive.css') }}" rel="stylesheet">
    <link href="{{ url('/resources/assets/js/data-table/css/dataTables.scroller.css') }}" rel="stylesheet"> -->
    
<!-- <link href="{{ url('/resources/assets/css/jquery.dataTables.min.css') }}"> -->
<!-- <link href="{{ url('/resources/assets/css/buttons.dataTables.min.css') }}"> -->

    <!--tagsinput-->
    <!-- <link href="{{ url('/resources/assets/js/data-table/css/tagsinput.css') }}" rel="stylesheet"> -->

    <!--dropzone-->
    <link href="{{ url('/resources/assets/css/dropzone.css') }}" rel="stylesheet">

    <!--Select2-->
    <link href="{{ url('/resources/assets/css/select2.css') }}" rel="stylesheet">
    <link href="{{ url('/resources/assets/css/select2-bootstrap.css') }}" rel="stylesheet">

    <!--bootstrap-fileinput-master-->
    <link rel="stylesheet" type="text/css" href="{{ url('/resources/assets/js/bootstrap-fileinput-master/css/fileinput.css') }}" />

     <!--bootstrap picker-->
    <link rel="stylesheet" type="text/css" href="{{ url('/resources/assets/js/bootstrap-datepicker/css/datepicker.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('/resources/assets/js/bootstrap-datetimepicker/css/datetimepicker.css') }}" />
    
    <link rel="stylesheet" type="text/css" href="{{ url('/resources/assets/js/bootstrap-daterangepicker/daterangepicker-bs3.css') }}"/>
    
    <link rel="stylesheet" type="text/css" href="{{ url('/resources/assets/js/bootstrap-timepicker/compiled/timepicker.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('/resources/assets/js/bootstrap-colorpicker/css/colorpicker.css') }}"/>

    <!-- common style -->
    <link href="{{ url('/resources/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ url('/resources/assets/css/style-responsive.css') }}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->

    <!-- custom scrollbar -->
    <link href="{{ url('/resources/assets/css/jquery.jscrollpane.css') }}" rel="stylesheet">

    
</head>
<body class="sticky-header">

    <section>
    