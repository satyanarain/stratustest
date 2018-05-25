
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
                <h3 class="m-b-less">Update Request for Information # <span id="rfi_number"></span></h3>
                <?php $project_id = Request::segment(2); ?>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div id="alert_message"></div>
                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <div class="panel-body">

                                   
                                    <div class="row">
                                    <div class="form-group col-md-6">
                                        <div class="form-group col-md-12">
                                            <label>Status</label>
                                            <select class="form-control" id="status">
                                                <option value="active">Active</option>
                                                <option value="deactive">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="clearfix"></div>  
                                        <div class="form-group col-md-12 additional_information_container" style="display: none;">
                                            <label>Additional Information</label>
                                            <textarea class="form-control additional_information" name="additional_information" rows="6"></textarea>
                                        </div>
                                    </div>    
                                        <input type="hidden" name="rir_review_status" id="rir_review_status" value="">
                                        <div class="col-sm-6 additional_information_container" style="display: none;">
                                        <div class="notice_exist">
                                            <div class="form-group col-md-12">
                                                <label for="name_of_report" style="padding-top: 15px;">Upload Information</label>
                                                <section class="panel upload_doc_panel_performance" id="upload_performance">
                                                    <div class="panel-body" style="padding: 0px;">
                                                        <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                        <input type="hidden" name="document_path" value="/uploads/request_info/">
                                                        </form>
                                                    </div>
                                                </section>
                                                <input type="hidden" name="standard_doc_id" id="upload_doc_id" value="">
                                                <input type="hidden" name="standard_upload" id="upload_doc_meta" value="request_info">
                                                <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                                                <div class="clearfix"></div>
                                            </div><!-- upload_doc_panel_payment close -->
                                        </div><!-- contract_exist close -->
                                    </div>

                                    <div class="form-group col-md-12">
                                        <!-- <input type="hidden" name="standard_upload" id="upload_doc_meta" value="swppp"> -->
                                        <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                                        <!-- <input type="hidden" name="standard_doc_id" id="upload_doc_id" value=""> -->
                                    </div>

                                    <div class="form-group col-md-12">
<!--                                        <a data-href="{{ url('/dashboard/'.$project_id.'/req_for_info') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                        <a href="{{ url('/dashboard/'.$project_id.'/req_for_info') }}" class="btn btn-info sub-btn btn_back1">Back</a>
                                        <button type="submit" class="btn btn-info sub-btn" id="update_request_form">Save</button>
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
<script src="{{ url('/resources/assets/dist/req_for_info_update.js') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone.js') }}"></script>
@include('include/footer')
