
        @include('include/header')
        @include('include/sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
            @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">Notifications</h3>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper">
                <!--timeline start-->
                <section class="panel-timeline">
                    <div class="time-line-wrapper" id="notification_list_page">
                        <div class="time-line-caption">
                            <h3 class="time-line-title">Notifications</h3>
                        </div>
                        <!-- <article class="time-line-row">
                            <div class="time-line-info">
                                <div class="panel">
                                    <div class="panel-body">
                                        <span class="arrow"></span>
                                        <span class="time-line-ico-box green"></span>
                                        <span class="time-line-subject green"> <i class="fa fa-clock-o"></i> Appointment</span>
                                        <div class="title">
                                            <h1>23 June</h1>
                                            <small class="text-muted">sunday 2014, 2:30 pm</small>
                                        </div>
                                        <p>Appointment with vectorlab CEO <a href="#" class="green-color">John Doe</a></p>
                                    </div>
                                </div>
                            </div>
                        </article>
                        <article class="time-line-row alt">
                            <div class="time-line-info">
                                <div class="panel">
                                    <div class="panel-body">
                                        <span class="arrow-alt"></span>
                                        <span class="time-line-ico-box green"></span>
                                        <span class="time-line-subject green"> <i class="fa fa-clock-o"></i> Meeting</span>
                                        <div class="title">
                                            <h1>23 June</h1>
                                            <small class="text-muted">Sunday 2014, 12:00 pm</small>
                                        </div>
                                        <p>
                                            Monthly Evulution meeting with <a href="#" class="green-color"> John Doe </a> , <a href="#" class="green-color"> Adam Smith</a> and <a href="#" class="green-color"> Pett Huu</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </article> -->
                    </div>
                    <div class="col-sm-12" style="text-align: center; margin: 20px 0px;">
                        <div class="loading_bar" id="loading_bar" style="text-align: center;">
                            <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                        </div>
                        <button class="btn btn-success" id="loadMore">Load more</button>
                    </div>
                </section>
            </div>
            <!--body wrapper end-->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/notification_page.js') }}"></script>

@include('include/footer')