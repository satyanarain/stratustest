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
                <h3 class="m-b-less">Add New Labor Compliance Documents</h3>
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
                                <div id="upload_success">
                                    <div id="toast-container" class="toast-top-right" aria-live="polite" role="alert">
                                        <div class="toast toast-success">
                                            <div class="toast-title">Success</div>
                                            <div class="toast-message">Document uploaded</div>
                                        </div>
                                    </div>
                                </div>
                                <div id="alert_message"></div>
                                <div class="col-md-12 nopadleft">
                                    <div class="form-group clearfix">
                                        <label class="nopadleft col-xs-6 col-sm-12 control-label">Contractor: <span id="contractor_name" style="font-weight: bold;"></span></label>
                                    </div>


                                    <div class="form-group nopadleft">
                                        <label for="company_name col-md-6">Contractor Name <span class="text-danger">*</span></label>
                                        <!-- <div class="loading_data" style="text-align: center;">
                                           <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                        </div> -->
                                        <select class="form-control col-md-6" id="company_name" name="company_name">
                                            <option value="">Select Contractor Name</option>
                                        </select>
                                    </div>

                                    <div class="form-group clearfix">
                                        <label class="nopadleft col-sm-12 control-label mt-6">Document Type <span class="text-danger">*</span></label>
        <div class="form-group">
            <div class="col-sm-6">
                <label class="checkbox-custom check-success">
                    <input type="checkbox" value=" " id="140_option_show">
                    <label for="140_option_show">140 – PW Contractor Award Info </label>
                </label>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-6">
                <label class="checkbox-custom check-success">
                    <input type="checkbox" value=" " id="142_option_show">
                    <label for="142_option_show">142 – Request for Dispatch of Apprentice </label>
                </label>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-6">
                <label class="checkbox-custom check-success">
                    <input type="checkbox" value=" " id="fringe_option_show">
                    <label for="fringe_option_show">Fringe Benefit Statement </label>
                </label>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-6">
                <label class="checkbox-custom check-success">
                    <input type="checkbox" value=" " id="cac_option_show">
                    <label for="cac_option_show">CAC-2 </label>
                </label>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-6">
                <label class="checkbox-custom check-success">
                    <input type="checkbox" value=" " id="weekly_option_show">
                    <label for="weekly_option_show">Weekly Certified Payroll Reports/Statement of Compliance</label>
                </label>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-6">
                <label class="checkbox-custom check-success">
                    <input type="checkbox" value=" " id="non_performance_compliance_option_show">
                    <label for="non_performance_compliance_option_show">Statement of Non-Performance </label>
                </label>
            </div>
        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-12 nopadleft" id="140_div_show" style="display: none;">
                                        <div class="form-group col-sm-6 nopadleft clearfix">
                                            <label class="nopadleft control-label">
                                                <strong>140 – PW Contractor Award Info </strong>
                                            </label><br/>
                                            <label class="nopadleft control-label mt-6">Week Ending <span class="text-danger">*</span></label>
                                            <div class="nopadleft">
                                                <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                                    <input type="text" readonly="" value="" size="16" class="form-control"  id="date_140">
                                                    <span class="input-group-btn add-on">
                                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6 clearfix">
                                            <label class="nopadleft control-label">
                                                <strong>Upload 140 – PW Contractor Award Info <span class="text-danger">*</span></strong>
                                            </label><br/>
                                            <section class="panel upload_doc_panel" id="upload_div">
                                                <div class="panel-body" style="padding: 0px;">
                                                    <form id="my-awesome-dropzone" labor="upload_doc_id_1" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                        <input type="hidden" name="document_path" value="/uploads/labor_compliance/">
                                                    </form>
<input type="hidden" name="upload_type" id="upload_type" value="multiple_upload">
<input type="hidden" name="upload_doc_id[]" class="upload_doc_id" id="upload_doc_id_1" value="">
                                                </div>
                                            </section>
                                        </div>
                                    </div><!-- Close Panel -->

                                    <div class="col-sm-12 nopadleft" id="142_div_show" style="display: none;">
                                        <div class="form-group col-sm-6 nopadleft clearfix">
                                            <label class="nopadleft control-label">
                                                <strong>142 – Request for Dispatch of Apprentice </strong>
                                            </label><br/>
                                            <label class="nopadleft control-label mt-6">Week Ending <span class="text-danger">*</span></label>
                                            <div class="nopadleft">
                                                <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                                    <input type="text" readonly="" value="" size="16" class="form-control"  id="date_142">
                                                    <span class="input-group-btn add-on">
                                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6 clearfix">
                                            <label class="nopadleft control-label">
                                                <strong>Upload 142 – Request for Dispatch of Apprentice <span class="text-danger">*</span></strong>
                                            </label><br/>
                                            <section class="panel upload_doc_panel" id="upload_div">
                                                <div class="panel-body" style="padding: 0px;">
                                                    <form id="my-awesome-dropzone" labor="upload_doc_id_2" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                        <input type="hidden" name="document_path" value="/uploads/labor_compliance/">
                                                    </form>
<input type="hidden" name="upload_type" id="upload_type" value="multiple_upload">
<input type="hidden" name="upload_doc_id[]" class="upload_doc_id" id="upload_doc_id_2" value="">
                                                </div>
                                            </section>
                                        </div>
                                    </div><!-- Close Panel -->

                                    <div class="col-sm-12 nopadleft" id="fringe_div_show" style="display: none;">
                                        <div class="form-group col-sm-6 nopadleft clearfix">
                                            <label class="nopadleft control-label">
                                                <strong>Fringe Benefit Statement </strong>
                                            </label><br/>
                                            <label class="nopadleft control-label mt-6">Week Ending <span class="text-danger">*</span></label>
                                            <div class="nopadleft">
                                                <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                                    <input type="text" readonly="" value="" size="16" class="form-control"  id="fringe_date">
                                                    <span class="input-group-btn add-on">
                                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6 clearfix">
                                            <label class="nopadleft control-label">
                                                <strong>Upload Fringe Benefit Statement <span class="text-danger">*</span></strong>
                                            </label><br/>
                                            <section class="panel upload_doc_panel" id="upload_div">
                                                <div class="panel-body" style="padding: 0px;">
                                                    <form id="my-awesome-dropzone" labor="upload_doc_id_3" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                        <input type="hidden" name="document_path" value="/uploads/labor_compliance/">
                                                    </form>
<input type="hidden" name="upload_type" id="upload_type" value="multiple_upload">
<input type="hidden" name="upload_doc_id[]" class="upload_doc_id" id="upload_doc_id_3" value="">
                                                </div>
                                            </section>
                                        </div>
                                    </div><!-- Close Panel -->

                                    <div class="col-sm-12 nopadleft" id="cac_div_show" style="display: none;">
                                        <div class="form-group col-sm-6 nopadleft clearfix">
                                            <label class="nopadleft control-label">
                                                <strong>CAC-2 </strong>
                                            </label><br/>
                                            <label class="nopadleft control-label mt-6">Week Ending <span class="text-danger">*</span></label>
                                            <div class="nopadleft">
                                                <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                                    <input type="text" readonly="" value="" size="16" class="form-control"  id="cac2_date">
                                                    <span class="input-group-btn add-on">
                                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6 clearfix">
                                            <label class="nopadleft control-label">
                                                <strong>Upload CAC-2 <span class="text-danger">*</span></strong>
                                            </label><br/>
                                            <section class="panel upload_doc_panel" id="upload_div">
                                                <div class="panel-body" style="padding: 0px;">
                                                    <form id="my-awesome-dropzone" labor="upload_doc_id_4" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                        <input type="hidden" name="document_path" value="/uploads/labor_compliance/">
                                                    </form>
<input type="hidden" name="upload_type" id="upload_type" value="multiple_upload">
<input type="hidden" name="upload_doc_id[]" class="upload_doc_id" id="upload_doc_id_4" value="">
                                                </div>
                                            </section>
                                        </div>
                                    </div><!-- Close Panel -->

                                    <div class="col-sm-12 nopadleft" id="weekly_div_show" style="display: none;">
                                        <div class="col-md-12 check_statement_compliance_type" style="margin-bottom:50px;">
                                            <label class="nopadleft control-label">
                                                <strong>Weekly Certified Payroll Reports/Statement of Compliance</strong>
                                            </label><br/>
                                            <label for="standard_link">Would you like to upload your own form or fill out and submit via docusign?</label><br/>
                                            <label class="radio-inline">
                                              <input type="radio" name="check_statement_compliance_type" id="signed_statement_compliance" value="exist"> Upload your own form</label><br/>
                                            <label class="radio-inline">
                                              <input type="radio" name="check_statement_compliance_type" id="unsigned_statement_compliance" value="new"> Submit via docusign</label>
                                        </div>
                                        <div class="signed_statement_compliance" style="display: none;">
                                            <div class="form-group col-sm-6 nopadleft clearfix">
                                            
                                            <label class="nopadleft control-label mt-6">Week Ending <span class="text-danger">*</span></label>
                                            <div class="nopadleft">
                                                <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                                    <input type="text" readonly="" value="" size="16" class="form-control"  id="weekly_date">
                                                    <span class="input-group-btn add-on">
                                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                            <div class="form-group col-sm-6 clearfix">
                                            <label class="nopadleft control-label">
                                                <strong>Upload Weekly Certified Payroll Reports (CPR) <span class="text-danger">*</span></strong>
                                            </label><br/>
                                            <section class="panel upload_doc_panel" id="upload_div">
                                                <div class="panel-body" style="padding: 0px;">
                                                    <form id="my-awesome-dropzone" labor="upload_doc_id_5" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                        <input type="hidden" name="document_path" value="/uploads/labor_compliance/">
                                                    </form>
<input type="hidden" name="upload_type" id="upload_type" value="multiple_upload">
<input type="hidden" name="upload_doc_id[]" class="upload_doc_id" id="upload_doc_id_5" value="">
                                                </div>
                                            </section>
                                        </div>
                                            <div class="form-group col-sm-6 nopadleft clearfix">
                                            
                                            
                                        </div>
                                            <div class="form-group col-sm-6 clearfix">
                                            <label class="nopadleft control-label">
                                                <strong>Upload Statement of Compliance <span class="text-danger">*</span></strong>
                                            </label><br/>
                                            <section class="panel upload_doc_panel" id="upload_div">
                                                <div class="panel-body" style="padding: 0px;">
                                                    <form id="my-awesome-dropzone" labor="upload_doc_id_6" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                        <input type="hidden" name="document_path" value="/uploads/labor_compliance/">
                                                    </form>
                                            <input type="hidden" name="upload_type" id="upload_type" value="multiple_upload">
                                            <input type="hidden" name="upload_doc_id[]" class="upload_doc_id" id="upload_doc_id_6" value="">
                                                </div>
                                            </section>
                                        </div>
                                        </div>
                                        <div class="unsigned_statement_compliance" style="display: none;">
                                            <div class="form-group col-md-12 "><span class="label label-inverse"><b>Please fill out the fields below for Docusign integration.</b></span></div>
                                            <div class="clearfix"></div>  
                                            <div class="form-group col-md-6">
                                                <label for="">Contact Name</label>
                                                <input class="form-control" name="signatory_name[]" type="text" id="">
                                            </div>
                                            <div class="form-group col-md-6">
                                                 <label for="">Contact Email</label>
                                                 <input class="form-control" name="signatory_email[]" type="text" id="">
                                            </div>
                                        </div>
                                    </div><!-- Close Panel -->

                                    <div class="col-sm-12 nopadleft" id="compliance_div_show" style="display: none;">
                                        <div class="col-md-12 check_statement_nonperformance_type" style="margin-bottom:50px;">
                                            <label class="nopadleft control-label">
                                                <strong>Statement of Non-Performance </strong>
                                            </label><br/>
                                            <label for="standard_link">Would you like to upload your own form or fill out and submit via docusign?</label><br/>
                                            <label class="radio-inline">
                                              <input type="radio" name="check_statement_nonperformance_type" id="signed_statement_compliance" value="exist"> Upload your own form</label><br/>
                                            <label class="radio-inline">
                                              <input type="radio" name="check_statement_nonperformance_type" id="unsigned_statement_compliance" value="new"> Submit via docusign</label>
                                        </div>
                                        <div class="signed_statement_nonperformance" style="display: none;">
                                            <div class="form-group col-sm-6 nopadleft clearfix">
                                            <label class="nopadleft control-label mt-6">Week Ending <span class="text-danger">*</span></label>
                                            <div class="nopadleft">
                                                <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                                    <input type="text" readonly="" value="" size="16" class="form-control"  id="compliance_date">
                                                    <span class="input-group-btn add-on">
                                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                            <div class="form-group col-sm-6 clearfix">
                                            <label class="nopadleft control-label">
                                                <strong>Upload Statement of Non-Performance <span class="text-danger">*</span></strong>
                                            </label><br/>
                                            <section class="panel upload_doc_panel" id="upload_div">
                                                <div class="panel-body" style="padding: 0px;">
                                                    <form id="my-awesome-dropzone" labor="upload_doc_id_7" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                        <input type="hidden" name="document_path" value="/uploads/labor_compliance/">
                                                    </form>
                                            <input type="hidden" name="upload_type" id="upload_type" value="multiple_upload">
                                            <input type="hidden" name="upload_doc_id[]" class="upload_doc_id" id="upload_doc_id_7" value="">
                                                </div>
                                            </section>
                                        </div>
                                        </div>
                                        <div class="unsigned_statement_nonperformance" style="display: none;">
                                            <div class="form-group col-md-12 "><span class="label label-inverse"><b>Please fill out the fields below for Docusign integration.</b></span></div>
<div class="clearfix"></div>  
                                            <div class="form-group col-md-6">
                                                <label for="">Contact Name</label>
                                                <input class="form-control" name="performance_signatory_name[]" type="text" id="">
                                            </div>
                                            <div class="form-group col-md-6">
                                                 <label for="">Contact Email</label>
                                                 <input class="form-control" name="performance_signatory_email[]" type="text" id="">
                                            </div>
                                        </div>
                                    </div>




                                </div>
                                <input type="hidden" id="project_name">
                                <div class="col-sm-6">
                                    <input type="hidden" name="standard_upload" id="upload_doc_meta" value="labor_compliance">
                                    <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">

                                </div>
                                <div class="form-group col-md-12 nopadleft">
<!--                                    <a data-href="{{ url('/dashboard/'.$project_id.'/labor_compliance') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                    <a href="{{ url('/dashboard/'.$project_id.'/labor_compliance') }}" class="btn sub-btn btn-info btn_back" onclick="return checkFormFilled('btn_back')">Back</a>
                                    <button type="submit" id="add_labor_compliance" class="first_button btn btn-info sub-btn">Save</button>
                                    <!-- <button type="submit" id="add_labor_compliance" class="another_button btn btn-info sub-btn" style="display: none;">Save Another</button> -->
                                    <a href="{{ url('/dashboard/'.$project_id.'/unconditional_finals') }}" class="btn btn-info sub-btn continue_button" onclick="return checkFormFilled('continue_button')">Next Screen</a>
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
<script src="{{ url('/resources/assets/js/dropzone.js') }}"></script>
<script src="{{ url('/resources/assets/dist/labor_compliance_add.js') }}"></script>
<script type="text/javascript">
    $('body').delegate( '.upload_doc_panel', 'click', function () {
        var id = $(this).find(".upload_doc_id:first").attr("id");
        // console.log(id);
        window.localStorage.setItem("upload_doc_id", id);
        console.log(localStorage.getItem("upload_doc_id"));
        return;
    });
    $(".dropzone").on("drop", function(event) {
        var id = $(this).attr("labor");
        event.preventDefault();  
        event.stopPropagation();
        window.localStorage.setItem("upload_doc_id", id);
        console.log(localStorage.getItem("upload_doc_id"));
        //$("#upload_type").val("certificate_work_compensation");
        return;
    });
    $('#140_option_show').change(function() {
        if($(this).is(":checked")) {
            $('#140_div_show').css("display", "block");
        }
        else {
           $('#140_div_show').css("display", "none");
        }
    });

    $('#142_option_show').change(function() {
        if($(this).is(":checked")) {
            $('#142_div_show').css("display", "block");
        }
        else {
           $('#142_div_show').css("display", "none");
        }
    });

    $('#fringe_option_show').change(function() {
        if($(this).is(":checked")) {
            $('#fringe_div_show').css("display", "block");
        }
        else {
           $('#fringe_div_show').css("display", "none");
        }
    });

    $('#cac_option_show').change(function() {
        if($(this).is(":checked")) {
            $('#cac_div_show').css("display", "block");
        }
        else {
           $('#cac_div_show').css("display", "none");
        }
    });

    $('#weekly_option_show').change(function() {
        if($(this).is(":checked")) {
            $('#weekly_div_show').css("display", "block");
        }
        else {
           $('#weekly_div_show').css("display", "none");
        }
    });

    $('#non_performance_compliance_option_show').change(function() {
        if($(this).is(":checked")) {
            $('#compliance_div_show').css("display", "block");
        }
        else {
           $('#compliance_div_show').css("display", "none");
        }
    });
</script>
<script type="text/javascript">
$("input[name='check_statement_compliance_type']").click(function(){
    if($('input:radio[name=check_statement_compliance_type]:checked').val() == "exist"){
        $('.signed_statement_compliance').show();
        $('.unsigned_statement_compliance').hide();
    }
    else {
        $('.unsigned_statement_compliance').show();
        $('.signed_statement_compliance').hide();
    }
});
$("input[name='check_statement_nonperformance_type']").click(function(){
    if($('input:radio[name=check_statement_nonperformance_type]:checked').val() == "exist"){
        $('.signed_statement_nonperformance').show();
        $('.unsigned_statement_nonperformance').hide();
    }
    else {
        $('.unsigned_statement_nonperformance').show();
        $('.signed_statement_nonperformance').hide();
    }
});

</script>
@include('include/footer')
