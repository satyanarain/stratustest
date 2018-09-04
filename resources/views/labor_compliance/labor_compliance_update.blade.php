        @include('include/header')
        @include('include/project_sidebar')
        <!-- body content start-->
        <div class="body-content" >
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Update Labor Compliance Document</h3>
                <?php $project_id = Request::segment(2); ?>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <div class="panel-body">
                            <div id="alert_message"></div>

                                   
                                    <div class="row">

                                    <div class="col-md-4 form-group">
                                        <label><strong>Status</strong></label>
                                        <select class="form-control" id="status">
                                            <option value="active">Active</option>
                                            <option value="deactive">Inactive</option>
                                        </select>
                                    </div>
                                    
                                    
                                    <div class="col-sm-12 " id="140_div_show">
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
                                    
                                    <div class="col-sm-12" id="142_div_show">
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

                                    <div class="col-sm-12" id="fringe_div_show">
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

                                    <div class="col-sm-12" id="cac_div_show">
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

                                    <div class="col-sm-12" id="weekly_div_show">
                                        <label class="nopadleft control-label">
                                                <strong>Weekly Certified Payroll Reports/Statement of Compliance</strong>
                                            </label><br/>
                                        <div class="col-md-12 check_statement_compliance_type" style="margin-bottom:50px;display:none;">
                                            
                                            <label for="standard_link">Would you like to upload your own form or fill out and submit via docusign?</label><br/>
                                            <label class="radio-inline">
                                                <input type="radio" name="check_statement_compliance_type" checked="checked" id="signed_statement_compliance" value="exist"> Upload your own form</label><br/>
                                            <label class="radio-inline">
                                              <input type="radio" name="check_statement_compliance_type" id="unsigned_statement_compliance" value="new"> Submit via docusign</label>
                                        </div>
                                        <div class="signed_statement_compliance">
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

                                    <div class="col-sm-12" id="compliance_div_show">
                                        <label class="nopadleft control-label">
                                                <strong>Statement of Non-Performance </strong>
                                            </label><br/>
                                        <div class="col-md-12 check_statement_nonperformance_type" style="margin-bottom:50px;display:none;">
                                            
                                            <label for="standard_link">Would you like to upload your own form or fill out and submit via docusign?</label><br/>
                                            <label class="radio-inline">
                                                <input type="radio" name="check_statement_nonperformance_type" checked="checked" id="signed_statement_compliance" value="exist"> Upload your own form</label><br/>
                                            <label class="radio-inline">
                                              <input type="radio" name="check_statement_nonperformance_type" id="unsigned_statement_compliance" value="new"> Submit via docusign</label>
                                        </div>
                                        <div class="signed_statement_nonperformance">
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
                                    <div class="col-sm-6">
                                    <input type="hidden" name="standard_upload" id="upload_doc_meta" value="labor_compliance">
                                    <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">

                                </div>
                                    

                                    <div class="form-group col-md-12">
<!--                                        <a data-href="{{ url('/dashboard/'.$project_id.'/labor_compliance') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                        <a href="{{ url('/dashboard/'.$project_id.'/labor_compliance') }}" class="btn btn-info sub-btn btn_back1">Back</a>
                                        <button type="submit" id="update_labor_form" class="btn btn-info sub-btn">Save</button>
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
<script src="{{ url('/resources/assets/dist/labor_compliance_update.js') }}"></script>
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
</script>
@include('include/footer')
