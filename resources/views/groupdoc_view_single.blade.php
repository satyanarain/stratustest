
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">Group Doc</h3>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper">
                <div class="row">
                    <div class="col-sm-6 complete_box">
                        <div class="notice_exist">
                            <div class="form-group col-md-12">
                                <label for="name_of_report" style="padding-top: 15px;">Attach Additional Document</label>
                                <section class="panel upload_doc_panel_performance" id="upload_performance">
                                    <div class="panel-body" style="padding: 0px;">
                                        <form id="my-awesome-dropzone" action="{{ url('/group_doc/index.php') }}" class="dropzone" enctype="multipart/form-data">
                                        <input type="text" name="document_path" value="/uploads/submittals/">
                                        </form> 
                                    </div>
                                </section>
                                <input type="text" name="standard_doc_id" id="upload_doc_id" value="">
                                <input type="text" name="standard_upload" id="upload_doc_meta" value="submittals">
                                <input type="text" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                                <div class="clearfix"></div>
                            </div><!-- upload_doc_panel_payment close -->
                        </div><!-- contract_exist close -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <iframe src="http://apps.groupdocs.com/document-annotation2/embed/c58dd8499ef58fde3768627ea68b88469798e5beea159cb9be75bba44101465d" frameborder="0" width="100%" height="800"></iframe>
                    </div>
                </div>
            </div>
            <!--body wrapper end-->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone_groupdoc.js') }}"></script>
<!-- <script src="{{ url('/resources/assets/dist/standard_view_all.js') }}"></script> -->
@include('include/footer')