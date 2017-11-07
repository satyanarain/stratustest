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
                <h3 class="m-b-less">Add Test Results</h3>
                <?php $project_id = Request::segment(2); ?>
                <div class="state-information">
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
                                            <div class="toast-message">Please contact their supervisor</div>
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
<div class="col-md-12">
    <div class="row">
        <div class="form-group">
            <label>Are any of the following tests required? (check all that apply)</label>
        </div>
        <div class="form-group">
            <div class="col-lg-12 nopadleft">
                <label class="checkbox-custom check-success">
                    <input type="checkbox" value=" " id="compaction_yes">
                    <label for="compaction_yes">Compaction</label>
                </label>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12 nopadleft">
                <label class="checkbox-custom check-success">
                    <input type="checkbox" value=" " id="ppc_yes">
                    <label for="ppc_yes">PCC Strength</label>
                </label>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12 nopadleft">
                <label class="checkbox-custom check-success">
                    <input type="checkbox" value=" " id="etc_yes">
                    <label for="etc_yes">Other</label>
                </label>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<div class="col-md-12 nopadleft">
    <div class="row">
        <div class="upload_doc_compaction" style="display:none;">
            <header class="panel-heading">Compaction</header>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name_of_report" style="padding-top: 15px;">Testing Firm</label>
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="padding: 0px;">
                        <div class="loading_data" style="text-align: center;">
                           <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                        </div>
                        <select class="form-control company_name" id="compaction_firm_name">
                            <option value="">Select Company Name</option>
                        </select>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    <label style="padding-top: 15px;">Date of Test <span class="text-danger">*</span></label>
                    <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                            <input type="text" readonly="" value="" size="16" class="form-control"  id="compaction_date">
                              <span class="input-group-btn add-on">
                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                              </span>
                        </div>
                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    <label># of Tests <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="compaction_test">
                </div>
                <div class="form-group">
                    <label>Description/Location <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="compaction_location">
                </div>
                <div class="form-group">
                  <label class="col-lg-12 nopadleft">Result <span class="text-danger">*</span></label>
                    <label class="radio-inline">
                      <input type="radio" name="compaction_result" id="compaction_result" value="Pass"> Pass
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="compaction_result" id="compaction_result" value="fail"> Fail
                    </label>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="col-sm-6">
                <section class="panel" id="upload_performance">
                    <label style="padding-top: 15px;">Upload Test/Report <span class="text-danger">*</span></label>
                    <div class="panel-body" style="padding: 15px 0px;">
                        <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                            <input type="hidden" name="document_path" value="/uploads/test_report/">
                        </form>
                    <input type="hidden" name="standard_doc_id" id="upload_doc_id_general" value="">
                    </div>
                </section>
                <input type="hidden" name="upload_type" id="upload_type" value="">
                <input type="hidden" name="standard_upload" id="upload_doc_meta" value="test_result">
                <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
            </div>
        </div><!-- upload_doc_compaction close -->
    </div>
</div>
<div class="clearfix"></div>

<div class="col-md-12 nopadleft">
    <div class="row">
        <div class="upload_doc_panel_payment" style="display:none;">
            <header class="panel-heading" style="margin-top:20px;">PCC Strength</header>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name_of_report" style="padding-top: 15px;">Testing Firm</label>
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="padding: 0px;">
                        <div class="loading_data" style="text-align: center;">
                           <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                        </div>
                        <select class="form-control company_name" id="pcc_firm">
                            <option value="">Select Company Name</option>
                        </select>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    <label style="padding-top: 15px;">Date of Test <span class="text-danger">*</span></label>
                    <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                        <input type="text" readonly="" value="" size="16" class="form-control"  id="pcc_date">
                        <span class="input-group-btn add-on">
                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    <label># of Tests <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="pcc_test">
                </div>
                <div class="form-group">
                    <label>Description/Location <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="pcc_description">
                </div>
                <div class="form-group">
                  <label class="col-lg-12 nopadleft">Result <span class="text-danger">*</span></label>
                    <label class="radio-inline">
                      <input type="radio" name="pcc_result" id="pcc_result" value="Pass"> Pass
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="pcc_result" id="pcc_result" value="fail"> Fail
                    </label>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="col-sm-6">
                <section class="panel" id="upload_payment">
                    <label style="padding-top: 15px;">Upload Test/Report <span class="text-danger">*</span></label>
                    <div class="panel-body" style="padding: 15px 0px;">
                        <form id="my-awesome-dropzone1" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                        <input type="hidden" class="certificate_work_compensation" name="document_path" value="/uploads/test_report/">
                        </form>
                        <input type="hidden" name="standard_doc_id" id="upload_doc_id_work" value="">
                    </div>
                </section>
            </div>
        </div><!-- upload_doc_panel_payment close -->
    </div>
</div>
<div class="clearfix"></div>


<div class="col-md-12 nopadleft">
    <div class="row">
        <div class="upload_doc_panel_maintenance" style="display:none;">
             <header class="panel-heading" style="margin-top:100px;">Other Test</header>
             <div class="col-sm-6">
                <div class="form-group">
                    <label style="padding-top: 15px;">Name of Test <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="etc_test_name" placeholder="Enter other test name">
                </div>
                <div class="form-group">
                    <label for="name_of_report">Testing Firm <span class="text-danger">*</span></label>
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="padding: 0px;">
                        <div class="loading_data" style="text-align: center;">
                           <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                        </div>
                        <input type="text" class="form-control" id="etc_firm" placeholder="Enter Firm Name">
                        <!-- <select class="form-control company_name" id="etc_firm"></select> -->
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    <label style="padding-top: 15px;">Date of Test <span class="text-danger">*</span></label>
                    <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                        <input type="text" readonly="" value="" size="16" class="form-control"  id="etc_date">
                        <span class="input-group-btn add-on">
                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    <label># of Tests <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="etc_test">
                </div>
                <div class="form-group">
                    <label>Description/Location <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="etc_location">
                </div>
                <div class="col-md-12 upload_maintenance nopadleft">
                    <label class="col-lg-12 nopadleft">Result <span class="text-danger">*</span></label>
                    <label class="radio-inline">
                      <input type="radio" name="etc_result" id="etc_result" value="Pass"> Pass
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="etc_result" id="etc_result" value="fail"> Fail
                    </label>
                </div>
            </div>
            <div class="col-sm-6">
                 <section class="panel" id="upload_maintenance">
                    <label style="padding-top: 15px;">Upload Test/Report <span class="text-danger">*</span></label>
                    <div class="panel-body" style="padding: 15px 0px;">
                        <form id="my-awesome-dropzone2" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                            <input type="hidden" class="certificate_auto_liability" name="document_path" value="/uploads/test_report/">
                        </form>
                        <input type="hidden" name="standard_doc_id" id="upload_doc_id_auto" value="">
                    </div>
                </section>
            </div>
        </div><!-- upload_doc_panel_maintenance close -->
    </div>
</div>
<div class="form-group col-md-12 nopadleft">
<!--    <a data-href="{{ url('/dashboard/'.$project_id.'/test_result') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
    <a href="{{ url('/dashboard/'.$project_id.'/test_result') }}" class="btn btn-info sub-btn btn_back" onclick="return checkFormFilled('btn_back')">Back</a>
    <button type="submit" class="btn btn-info sub-btn first_button add_test_result_form">Save</button>
    <!-- <button type="submit" class="btn btn-info sub-btn another_button add_test_result_form" style="display: none;">Save Another</button> -->
    <a data-href="{{ url('/dashboard/'.$project_id.'/preliminary_notice_log') }}" class="btn btn-info sub-btn continue_button" data-toggle="modal" data-target="#confirm-continue">Next Screen</a>
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
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/test_result_add_faizan.js') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone.js') }}"></script>
@include('include/footer')
