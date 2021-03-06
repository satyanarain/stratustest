
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')
          <div class="loading_data_file" style="display: none;">
               <div class="block">
                   <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                   <br/><span class="loading-text">Please wait, file is uploading</span>
               </div>
            </div>

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Update Submittal Review</h3>
                <?php $project_id = Request::segment(2); ?>
                <?php $submittal_id = Request::segment(4); ?>
            </div>
            <!-- page head end-->
        
            <div id="alert_message"></div>
            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <div class="panel-body">
                                   
                                    <div class="row">
                                    

                                    <div class="form-group col-md-6">
<!--                                        <label for="name_of_report">Review</label>
                                        <textarea class="form-control" id="review_detail" rows="10"></textarea>-->
                                        <div class="notice_exist">
                                            <div class="form-group col-md-12">
                                                <label for="name_of_report" style="padding-top: 15px;">Submittal Response <span class="text-danger">*</span></label>
                                                <section class="panel upload_doc_panel_performance" id="upload_performance">
                                                    <div class="panel-body" style="padding: 0px;">
                                                        <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                        <input type="hidden" name="document_path" value="/uploads/submittals/">
                                                        </form>
                                                    </div>
                                                </section>
                                                <input type="hidden" name="standard_doc_id" id="upload_doc_id" value="">
                                                <input type="hidden" name="standard_upload" id="upload_doc_meta" value="submittals">
                                                <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                                                <div class="clearfix"></div>
                                            </div><!-- upload_doc_panel_payment close -->
                                        </div><!-- contract_exist close -->
                                    </div>
                                    <div class="col-md-6">
                                        <label>Status</label>
                                        <select class="form-control" id="status">
                                            <option value="no_exception">No Exception</option>
                                            <option value="make_corrections_noted">Make Corrections Noted</option>
                                            <option value="revise_resubmit">Revise & Resubmit</option>
                                            <option value="rejected">Rejected</option>
                                        </select>
                                    </div>
                                <input type="hidden" name="submittal_number" id="submittal_number">
                                
                                   <div class="clearfix"></div>
                                    <div class="form-group col-md-12">
                                        <label>Submittal Uploaded</label>
                                        <span id="review_document"></span>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <!-- <input type="hidden" name="standard_upload" id="upload_doc_meta" value="swppp"> -->
                                        <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                                        <input type="hidden" id="respond_date" value="<?php echo date('Y-m-d'); ?>">
                                        <input type="hidden" name="submittal_version_number" id="submittal_version_number">
                                        <!-- <input type="hidden" name="standard_doc_id" id="upload_doc_id" value=""> -->
                                    </div>

                                    <div class="form-group col-md-12">
<!--                                        <a data-href="{{ url('/dashboard/'.$project_id.'/submittal_review') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                        <a href="{{ url('/dashboard/'.$project_id.'/submittal_review') }}" class="btn btn-info sub-btn btn_back1">Back</a>
                                        <button id="submit_submittal_form" type="submit" class="btn btn-info sub-btn">Save</button>
                                        <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
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
<script src="{{ url('/resources/assets/js/dropzone.js') }}"></script>
<script src="{{ url('/resources/assets/dist/submittals_review_update.js') }}"></script>
@include('include/footer')