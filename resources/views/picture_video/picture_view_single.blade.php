        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
    
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less" id="picture_name"></h3>
                <?php $project_id = Request::segment(2); ?>
                <?php $pic_id = Request::segment(4); ?>
                <div class="state-information">
                    <a href="{{ url('/dashboard/'.$project_id.'/picture_video') }}" class="btn btn-info">Back</a>
                </div>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-lg-6">
                        <section class="panel">
                            <div class="panel-body">
                                <div id="alert_message"></div>
                                <div class="row">
                                    <div class="form-group" style="margin:0px 15px;">
                                        <span id="file_path" class="picture_video_single"></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="col-lg-6">
                        <section class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group" style="padding: 15px;">
                                        <p><strong>Date:</strong> <span id="picture_date"></span></p>
                                        <p><strong>Taken By:</strong> <span id="picture_taken_by"></span></p>
                                        <p><strong>Taken On:</strong> <span id="picture_taken_on"></span></p>
                                        <p class="picture-video-add"><strong style="float: left; margin-right: 5px; ">Description:</strong> <span id="picture_description" class="desc-text"></span></p>
                                        <p style="display: none;"><strong>User Detail:</strong> <span id="picture_user_detail"></span></p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <!--body wrapper end-->
<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/picture_video_single.js') }}"></script>
@include('include/footer')