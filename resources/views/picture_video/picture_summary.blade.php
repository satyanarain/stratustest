        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less" id="project_name"></h3>
                <?php $project_id = Request::segment(2); ?>
                <?php $pic_id = Request::segment(4); ?>
                <div class="state-information">
                    <a href="{{ url('/dashboard/'.$project_id.'/picture_video') }}" class="btn btn-info">Back</a>
                </div>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <p><strong>Project:</strong> <span id="project_name1"></span></p>
                                        <p><strong>No. of Pictures:</strong> <span id="no_of_picture"></span></p>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <p><strong>Contractor:</strong> <span id="contractor_name"></span></p>
                                        <p><strong>No. of Videos:</strong> <span id="no_of_video"></span></p>
                                        <p><select name="ppv_sort_by" id="ppv_sort_by">
                                                <option value="">Sort By</option>
                                                <option value="ppv_taken_on asc">Taken On ASC</option>
                                                <option value="ppv_taken_on desc">Taken On DESC</option>
                                            </select></p>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <ul id="add_gallery_list"></ul>
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
<script src="{{ url('/resources/assets/dist/picture_video_summary.js') }}"></script>
@include('include/footer')
