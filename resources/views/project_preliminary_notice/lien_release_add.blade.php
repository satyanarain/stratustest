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
        <h3 class="m-b-less"> Add Lien Release</h3>
        <?php $project_id = Request::segment(2); ?>
        <?php $preliminary_id = Request::segment(4); ?>
        <input type="hidden" id="project_id" value="<?php echo $project_id ?>">
        <input type="hidden" id="preliminary_id" value="<?php echo $preliminary_id ?>">
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
                    <div class="panel-body">
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
                        <div id="upload_success">
                            <div id="toast-container" class="toast-top-right" aria-live="polite" role="alert">
                                <div class="toast toast-success">
                                    <div class="toast-title">Success</div>
                                    <div class="toast-message">Document uploaded</div>
                                </div>
                            </div>
                        </div>
                        <div id="alert_message"></div>
                        <div class="col-md-6">
                            <form role="form" id="add_plans_form">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="name_of_report">  Billed Through <span class="text-danger">*</span></label>
                                        <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                            <input type="text" readonly="" value="<?php //echo date("Y-m-d"); ?>" size="16" class="form-control"  id="billed_through">
                                                <span class="input-group-btn add-on">
                                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                </span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label for="lien_note">Note <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="lien_note" id="lien_release_note"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="radio-custom check-success">
                                            <input type="radio" value="partial" name="pplr_type" id="pply_partial" checked="checked">
                                            <label for="pply_partial">Partial</label>
                                        
                                            <input type="radio" value="full" name="pplr_type" id="pply_full">
                                            <label for="pply_full">Full</label>
                                        </label>
                                    </div>
                                    
                                    <input type="hidden" id="upload_doc_id">
                                    <div class="form-group col-md-12">
<!--                                        <a data-href="{{ url('/dashboard/'.$project_id.'/lien_release_log') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                        <a href="{{ url('/dashboard/'.$project_id.'/lien_release_log') }}" class="btn btn-info sub-btn btn_back" onclick="return checkFormFilled('btn_back')">Back</a>
                                        <!-- <button type="submit" class="btn btn-info sub-btn" id="sub-btn">Upload Preliminary</button> -->
                                        <!-- <button type="submit" class="add_preliminary_notice another_button btn btn-info sub-btn" id="add_preliminary_notice" style="display: none;">Save Another</button> -->

                                        <button type="submit" class="add_preliminary_notice first_button btn btn-info sub-btn">Save</button>
                                        <a data-href="{{ url('/dashboard/'.$project_id.'/picture_video') }}" class="btn btn-info sub-btn continue_button" data-toggle="modal" data-target="#confirm-continue">Next Screen</a>
                                        <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
                                    </div>

                                </div>
                            </form>
                        </div>

                        <div class="col-lg-6">
                          <label class="m-b-2">Upload Lien Release <span class="text-danger">*</span></label>
                            <section class="panel upload_doc_panel_performance" id="upload_performance">
                                <div class="panel-body" style="padding:0px;">
                                    <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                        <input type="hidden" name="document_path" value="/uploads/lien_release/">
                                    </form>
                                </div>
                            </section>
                            <input type="hidden" name="upload_type" id="upload_type" value="single_upload">
                            <input type="hidden" name="standard_doc_id" id="upload_single_doc_id" value="">
                            <input type="hidden" name="standard_upload" id="upload_doc_meta" value="lien_release">
                            <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $preliminary_id; ?>">
                            <div class="clearfix"></div>
                        </div>

                    </div>
                </section>
            </div>



            <div style="display: none;">
                <div id="notice_award_pdf_content" style="width:100%;">
                    <h1 style="color:green; text-align:center;">Preliminary Notices</h1>
                    <strong>Preliminary Notice From: </strong><span id="p_n_f"></span><br/>
                    <strong>Under Contract with: </strong><span id="u_c_w"></span><br/>
                    <strong>In the Amount of: </strong><span id="i_t_a_o"></span><br/>
                    <strong>Direct Contractor: </strong><span id="d_c"></span><br/>
                    <strong>Date of Notice was Signed: </strong><span id="d_o_n_w_s"></span><br/>
                    <strong>Postmarked Date: </strong><span id="p_d"></span><br/>

                </div>
            </div>


        </div>
    </div>
    <!--body wrapper end-->
    <!-- Placed js at the end of the document so the pages load faster -->
    <script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
    <script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
    <script src="{{ url('/resources/assets/js/dropzone.js') }}"></script>
    <script src="{{ url('/resources/assets/dist/lien_release_add.js') }}"></script>
    @include('include/footer')
