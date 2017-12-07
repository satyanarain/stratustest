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
                <h3 class="m-b-less">Add Request for Information</h3>
                <?php $project_id = Request::segment(2); ?>
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
<div class="col-md-12">
    <div class="row">
        <div class="form-group">
            <label for="company_name"></label>
            <header class="panel-heading">Contractor:
                <span id="contractor_name">
                    <div class="loading_data" style="text-align: center;">
                       <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                    </div>
                </span>
            </header>
        </div>

        <div class="col-md-6 nopadleft">
            <div class="form-group col-md-12 submittal">
                <label>RFI : # <strong><span id="request_number"></span></label>
            </div>

            <div class="form-group col-md-12 resubmittal">
                <label>Date of RFI : <strong><span id="request_date"><?php echo date("Y-m-d"); ?></span></strong></label>
            </div>

            <div class="form-group col-md-12">
                <label>What is your request? <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="question_request">
            </div>
            <div class="form-group col-md-12">
                <label>What is your proposed solution/suggestion? <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="question_proposed">
            </div>


            <div class="form-group col-md-12">
                <label>Will this request result in additional costs? <span class="text-danger">*</span></label><br/>
                <label class="radio-inline">
                  <input type="radio" name="additonal_cost_type" id="additional_cost" value="yes"> Yes
                </label>
                <label class="radio-inline">
                  <input type="radio" name="additonal_cost_type" id="additional_cost" value="no"> No
                </label><br/>
                <div class="additonal_cost_div" style="display: none;">
                    <label>Estimated Value <span class="text-danger">*</span></label>
                    <!-- <input type="text" class="form-control" id="additional_cost_amount"> -->
                    <div class="input-group m-b-10">
                        <span class="input-group-addon project_currency"></span>
                        <input class="form-control" onkeypress="return isNumber(event)" type="text" id="additional_cost_amount">
                    </div>
                </div>
            </div>

            <div class="form-group col-md-12">
                <label>Will this request result in additional days added to the contract? <span class="text-danger">*</span></label><br/>
                <label class="radio-inline">
                  <input type="radio" name="additonal_day_type" id="additional_day" value="yes"> Yes
                </label>
                <label class="radio-inline">
                  <input type="radio" name="additonal_day_type" id="additional_day" value="no"> No
                </label><br/>
                <div class="additonal_day_div" style="display: none;">
                    <label>Estimated Days <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" onkeypress="return isNumber(event)" id="additional_day_add">
                </div>
            </div>

        </div>

        <div class="col-sm-6">
            <div class="notice_exist">
                <div class="form-group col-md-12">
                    <label for="name_of_report" style="padding-top: 15px;">Upload Request for Information <span class="text-danger">*</span></label>
                    <section class="panel upload_doc_panel_performance" id="upload_performance">
                        <div class="panel-body" style="padding: 0px;">
                            <form id="my-awesome-dropzone" action="{{ url('/group_doc/index.php') }}" class="dropzone">
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
<!--            <a data-href="{{ url('/dashboard/'.$project_id.'/req_for_info') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
            <a href="{{ url('/dashboard/'.$project_id.'/req_for_info') }}" class="btn btn-info sub-btn btn_back" onclick="return checkFormFilled('btn_back')">Back</a>
            <button type="submit" class="btn btn-info sub-btn submit_submittal_form">Save</button>
            <a href="{{ url('/dashboard/'.$project_id.'/submittals_log') }}" class="btn btn-info sub-btn continue_button" onclick="return checkFormFilled('continue_button')">Next Screen</a>
            <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
        </div>
    </div><!-- Row Close -->
</div><!-- Col 6 Close -->
<div class="clearfix"></div>

                            </div>
                        </section>
                    </div>


                </div>
            </div>
            <!--body wrapper end-->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone_groupdoc.js') }}"></script>
<script type="text/javascript">
$("input[name='additonal_cost_type']").click(function(){
    if($('input:radio[name=additonal_cost_type]:checked').val() == "yes"){
        console.log('yes');
        $('.additonal_cost_div').show();
    }
    else {
        console.log('no');
        $('.additonal_cost_div').hide();
    }
});

$("input[name='additonal_day_type']").click(function(){
    if($('input:radio[name=additonal_day_type]:checked').val() == "yes"){
        console.log('yes');
        $('.additonal_day_div').show();
    }
    else {
        console.log('no');
        $('.additonal_day_div').hide();
    }
});
</script>
<script src="{{ url('/resources/assets/dist/req_for_info_add.js') }}"></script>

@include('include/footer')
