        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
        <?php $project_id = Request::segment(2); ?>
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
                <h3 class="m-b-less">Add New Schedule</h3>
                <div class="state-information" style="width: 200px; ">
                    <!-- <div class="progress progress-striped active progress-sm m-b-20"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"><span class="sr-only">100% Complete</span></div></div> -->
                </div>
            </div>
            <!-- page head end-->


            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <div class="panel-body clearfix">
                                <div id="upload_error">
                                    <div id="toast-container" class="toast-top-right" aria-live="polite" role="alert">
                                        <div class="toast toast-error">
                                            <div class="toast-title">Error</div>
                                            <div class="toast-message">Upload only PDF format file</div>
                                        </div>
                                    </div>
                                </div>
                                <div id="upload_warning">
                                    <div id="toast-container" class="toast-top-right" aria-live="polite" role="alert">
                                        <div class="toast toast-warning">
                                            <div class="toast-title">Warning</div>
                                            <div class="toast-message">Not providing a report is risky, please provide if you have it</div>
                                        </div>
                                    </div>
                                </div>
                      <!--           <div id="upload_success">
                                    <div id="toast-container" class="toast-top-right" aria-live="polite" role="alert">
                                        <div class="toast toast-success">
                                            <div class="toast-title">Success</div>
                                            <div class="toast-message">Document uploaded</div>
                                        </div>
                                    </div>
                                </div> -->
                                <div id="alert_message"></div>
                                
                                
                                <div class="col-sm-6">
                                    <label>Schedule Date  <span class="text-danger">*</span></label>
                                    <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                            <input type="text" readonly="" value="" size="16" class="form-control"  id="date_of_schedule">
                                              <span class="input-group-btn add-on">
                                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                              </span>
                                        </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Upload New Schedule <span class="text-danger">*</span></label>
                                    <section class="panel" id="upload_performance">
                                        <div class="panel-body nopadleft">
                                            <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                <input type="hidden" name="document_path" value="/uploads/schedule/">
                                            </form>
                                        </div>
                                    </section>


                                <input type="hidden" name="upload_type" id="upload_type" value="single_uploada">
                                <input type="hidden" name="standard_doc_id" id="upload_doc_id" value="">
                                <input type="hidden" name="standard_upload" id="upload_doc_meta" value="schedule">
                                <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">

                                </div>
                                
                                <div class="form-group col-md-12 nopadleft">
<!--                                    <a data-href="{{ url('/dashboard/'.$project_id.'/schedule') }}" class="btn btn-info back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                    <a href="{{ url('/dashboard/'.$project_id.'/schedule') }}" class="btn btn-info btn_back" onclick="return checkFormFilled('btn_back')">Back</a>
                                    <button type="submit" class="add_schedule first_button btn btn-info no-mar">Save</button>
                                    <button type="submit" class="add_schedule another_button btn btn-info no-mar" style="display: none;">Save Another</button>
                                    <a href="{{ url('/dashboard/'.$project_id.'/notice_completion') }}" class="btn btn-info continue_button" onclick="return checkFormFilled('continue_button')">Next Screen</a>
                                    <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>

                                </div>

                            </div><!-- panel-body Close -->
                        </section>
                    </div>
                </div>
            </div>

        </div>
        <!--body wrapper end-->


<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js?v=1.0') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js?v=1.0') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone.js?v=1.0') }}"></script>
<script src="{{ url('/resources/assets/dist/schedule_add.js?v=1.0') }}"></script>
@include('include/footer')
