        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

            <?php $project_id = Request::segment(2); ?>
            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Pictures / Videos</h3>
                <!-- <div class="state-information">
                    <a href="{{ url('/dashboard/'.$project_id.'/picture/summary') }}" class="btn btn-info">View Pictures / Videos Summary </a>
                </div> -->
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper no-pad" style="display: none;">
            <div id="upload_error">
                <div id="toast-container" class="toast-top-right" aria-live="polite" role="alert">
                    <div class="toast toast-error">
                        <div class="toast-title">Error</div>
                        <div class="toast-message">Upload only Image (PNG, JPG) / Video (MP3, MP4, AVI, MKV) format file</div>
                    </div>
                </div>
            </div>
            <div id="alert_message"></div>

            <div class="profile-desk">
            <aside class="p-aside">
                <div class="row">
                    <!-- <div class="form-group col-md-12">
                        <button id="show_all">All</button>
                        <button id="show_only_images">Picture</button>
                        <button id="show_only_video">VIdeo</button>
                    </div> -->
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
                    <!-- <div class="form-group col-md-12">
                        <button class="btn btn-success" id="show_all">Show All</button>
                        <button class="btn btn-success" id="show_only_video">Show Images</button>
                        <button class="btn btn-success" id="show_only_images">Show Video</button>
                    </div> -->
                    <div class="form-group col-md-12" style="display: none;">
                        <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search.." class="col-md-12">
                    </div>
                   <!--  <div class="form-group col-md-12">
                        <ul id="add_gallery_list"></ul>
                    </div> -->
                </div>

                <!-- <ul class="gallery gallery-pic-vid" id="add_gallery_grid"> -->
                    <!--
                    <li>
                        <a href="#">
                            <img src="{{ url('/resources/assets/img/gallery/1.jpg') }}" alt=""/>
                        </a>
                    </li>
                    -->
                <!-- </ul> -->

                <table class="table convert-data-table data-table" id="add_gallery_grid">
                        <thead>
                        <tr style="display: none;">
                            <th>#</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        </table>

            </aside>
            <aside class="p-short-info">
                <div class="widget hide_add_permission" style="display: none;">

                    <div class="title">
                        <h1>Attachment Details</h1>
                    </div>

                    <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                        <input type="hidden" name="document_path" value="/uploads/picture_video/">
                    </form>

                    <div class="form-group">
                        <input type="hidden" name="standard_upload" id="upload_doc_meta" value="picture_video">
                        <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                        <input type="hidden" name="standard_doc_id" id="upload_doc_id">
                    </div>


                    <div class="form-group">
                        <label for="g-title">Taken By <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="taken_by" placeholder="">
                    </div>
                    <div class="form-group">
                        <label>Taken On <span class="text-danger">*</span></label>
                        <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                <input type="text" readonly="" value="<?php echo date('Y-m-d')?>" size="16" class="form-control"  id="ppv_taken_on">
                                  <span class="input-group-btn add-on">
                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                  </span>
                            </div>
                    </div>
                    <div class="form-group">
                        <label for="g-desk">Description <span class="text-danger">*</span></label>
                        <div class="">
                            <textarea name="" class="form-control" id="description" cols="30" rows="3"></textarea>
                        </div>
                    </div>

                    <button type="submit" id="upload_image_submit" class="btn btn-info first_button">Upload</button>
                    <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>


                </div>

            </aside>
            </div>

            </div>
            <!--body wrapper end-->


<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone_image_video.js') }}"></script>
<script type="text/javascript">
    function myFunction() {
        var $rows = $('#add_gallery_grid li');
        $('#myInput').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
    }
</script>
<!-- <script src="{{ url('/resources/assets/dist/picture_video_view_all.js') }}"></script> -->
<script src="{{ url('/resources/assets/dist/picture_video_add.js') }}"></script>
@include('include/footer')
