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
                <h3 class="m-b-less">Add Submittals</h3>
                <?php $project_id = Request::segment(2); ?>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper">
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
        <div class="col-md-12 check_submittal_type" style="margin-bottom:50px;">
            <label for="standard_link">Is this a new submittal or resubmittal? (check one)</label><br/>
            <label class="radio-inline">
              <input type="radio" name="check_submittal_type" id="upload" value="new"> New Submittal
            </label><br/>
            <label class="radio-inline">
              <input type="radio" name="check_submittal_type" id="upload" value="exist"> Resubmittal
            </label>
        </div>

        <div class="col-md-6 nopadleft">
            <div class="form-group col-md-12 submittal" style="display:none;">
                <label>Submittal #: <strong><input type="text" id="submittal_number" value=""></strong></label>
            </div>

            <div class="form-group col-md-12 resubmittal" style="display: none;">
                <label>What was the Original Submittal Number? (Select One)</label>
                <select class="form-control" id="submittal_number_exist">
                    <option value="" selected>Select Submittals</option>
                </select>
            </div>

            <div class="form-group col-md-12 resubmittal_box" style="display: none;">
                <label>Submittal #: <strong><span id="resubmittal_value"></span> R <input type="text" id="submittal_revision_number" value=""></strong></label>
            </div>

            <div class="form-group col-md-12 resubmittal" style="display: none;">
                <label>Reason for Resubmittal</label>
                <select class="form-control" id="resubmittal_reason">
                    <option value="make_corrections_noted">Make Corrections Noted</option>
                    <option value="revise_resubmit">Revise Resubmit</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>

            <div class="form-group col-md-12">
                <label for="name_of_report" style="padding-top: 10px;">Date of Submittal</label>
                <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" class="input-append date dpYears">
                    <input type="text" readonly="" value="<?php echo date("Y-m-d"); ?>" size="16" class="form-control"  id="date_of_submittal">
                    <span class="input-group-btn add-on"><button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button></span>
                </div>
            </div>
            
            <div class="form-group col-md-12">
                <label>Description of Submittal</label>
                <input type="text" class="form-control" id="submittal_description">
            </div>
            <div class="form-group col-md-12">
                <label>Applicable Spec Section</label>
                <input type="text" class="form-control" id="submittal_spec">
            </div>


            <div class="form-group col-md-12">
                <label>Additional Comments</label>
                <textarea class="form-control wysihtml5" id="submittal_comments" rows="10"></textarea>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <label class="checkbox-custom check-success">
                        <input type="checkbox" value=" " id="review_yes">
                        <label for="review_yes">Request Expedited Review</label>
                    </label>
                </div>
            </div>

            <div class="form-group col-md-12 request_expedited_review" style="display: none;">
                <textarea class="form-control wysihtml5" id="request_expedited_review" rows="10"></textarea>
            </div>
          
        </div>

        <div class="col-sm-6">
            <div class="notice_exist">
                <div class="form-group col-md-12">
                    <label for="name_of_report" style="padding-top: 15px;">Attach Additional Document</label>
                    <section class="panel upload_doc_panel_performance" id="upload_performance">
                        <div class="panel-body">
                            <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
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

        <div class="form-group col-md-12">
            <button type="submit" class="btn btn-info sub-btn submit_submittal_form">Save</button>
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
<script src="{{ url('/resources/assets/js/dropzone.js') }}"></script>
<script type="text/javascript">
$("input[name='check_submittal_type']").click(function(){
    if($('input:radio[name=check_submittal_type]:checked').val() == "exist"){
        console.log('exist');
        $('.resubmittal').show();
        $('.submittal').hide();
    }
    else {
        console.log('new');
        $('.submittal').show();   
        $('.resubmittal_box').hide();   
        $('.resubmittal').hide();   
    }
});

$('#review_yes').change(function() {
    if($(this).is(":checked")) {
        $('.request_expedited_review').css("display", "block");
    }
    else {
        $('.request_expedited_review').css("display", "none");
    }        
});

$('#submittal_number_exist').change(
    function() {
        var val1 = $('#submittal_number_exist option:selected').val();
        $('#resubmittal_value').text(val1);
        $('.resubmittal_box').show();
    }
);
</script>
<script src="{{ url('/resources/assets/dist/submittals_add.js') }}"></script>

@include('include/footer')