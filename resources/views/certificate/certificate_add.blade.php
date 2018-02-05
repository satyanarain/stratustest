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
                <h3 class="m-b-less">Add Certificates of Insurance</h3>
                <?php $project_id = Request::segment(2); ?>
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
                         <div id="alert_message"></div>
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


                                <div class="form-group col-md-12">
                                    <label for="company_name"></label>
                                    <h4>Contractor:
                                    <span id="contractor_name">
                                        <div class="loading_data" style="text-align: center;">
                                           <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                        </div>
                                    </span>
                                    </h4>
                                    <input type="hidden" id="company_name" value="">
                                    <hr/>
                                </div>

                                <div class="col-md-12">
                                    <h3>General Liability</h3>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name_of_report" style="padding-top: 15px;">General Liability Limit <span class="text-danger">*</span></label>
                                        <div class="clearfix"></div>
                                        <div class="col-xs-3" style="padding: 0px;">
                                            <div class="loading_data" style="text-align: center;">
                                               <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                            </div>
                                            <select class="form-control currency_symbol" name="general_liability_cur_symbol" id="general_liability_cur_symbol">
                                            </select>
                                        </div>
                                        <div class="col-xs-9">
                                            <input type="text" class="form-control" id="general_liability_amount" onkeypress="return isNumber(event)">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="form-group">
                                        <label>Expiration Date <span class="text-danger">*</span></label>
                                        <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                            <input type="text" readonly="" value="" size="16" class="form-control"  id="general_liability_date">
                                              <span class="input-group-btn add-on">
                                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                              </span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="form-group">
                                        <label class="checkbox-custom check-success">
                                            <input type="checkbox" value=" " id="req_minimum_general">
                                            <label for="req_minimum_general">Required Minimum</label>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label class="checkbox-custom check-success">
                                            <input type="checkbox" value=" " id="general_liability_included">
                                            <label for="general_liability_included">Not Included</label>
                                        </label>
                                    </div>
                                </div><!-- Col 6 Close -->

                                <div class="col-sm-6">
                                    <section class="panel upload_doc_panel" style="margin-top: 20px;">
                                        <div class="panel-body">
                                            <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                <input type="hidden" name="document_path" value="/uploads/certificate/">
                                            </form>
                                            <input type="hidden" name="upload_type" id="upload_type" value="">
                                            <input type="hidden" name="standard_upload" id="upload_doc_meta" value="certificate">
                                            <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                                            <input type="hidden" name="standard_doc_id" id="upload_doc_id_general" value="">
                                        </div>
                                    </section>
                                </div><!-- Col 6 Close -->
                                <div class="col-sm-12">
                                    <hr/>
                                </div>

                                <div class="col-sm-12">
                                    <h3 style="margin-top:50px;">Workers Compensation Limit</h3>
                                </div><!-- Col 12 Close -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="name_of_report" style="padding-top: 15px;">Workers Compensation Limit <span class="text-danger">*</span></label>
                                        <div class="clearfix"></div>
                                        <div class="col-xs-3" style="padding: 0px;">
                                            <div class="loading_data" style="text-align: center;">
                                               <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                            </div>
                                            <select class="form-control currency_symbol" name="works_compensation_cur_symbol" id="works_compensation_cur_symbol">
                                            </select>
                                        </div>
                                        <div class="col-xs-9">
                                            <input type="text" class="form-control" id="works_compensation_currency" onkeypress="return isNumber(event)">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label>Expiration Date <span class="text-danger">*</span></label>
                                        <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                                <input type="text" readonly="" value="" size="16" class="form-control"  id="works_compensation_date">
                                                  <span class="input-group-btn add-on">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                  </span>
                                            </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="form-group">
                                        <label class="checkbox-custom check-success">
                                            <input type="checkbox" value=" " id="req_minimum_work">
                                            <label for="req_minimum_work">Required Minimum</label>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label class="checkbox-custom check-success">
                                            <input type="checkbox" value=" " id="upload_work_above">
                                            <label for="upload_work_above">Included in General Liability Certificate</label>
                                        </label>
                                    </div>

                                    <div class="form-group upload_work">
                                        <label class="checkbox-custom check-success">
                                            <input type="checkbox" value=" " id="upload_work">
                                            <label for="upload_work">Not Included</label>
                                        </label>
                                    </div>
                                    <div class="clearfix"></div>
                                </div><!-- Col 6 Close -->
                                <div class="col-sm-6">
                                    <section class="panel upload_doc_panel_work" style="margin-top: 0px;">
                                        <div class="panel-body">
                                            <form id="my-awesome-dropzone1" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                <input type="hidden" class="certificate_work_compensation" name="document_path" value="/uploads/certificate/">
                                            </form>
                                            <input type="hidden" name="standard_doc_id" id="upload_doc_id_work" value="">
                                        </div>
                                    </section>
                                    <div class="clearfix"></div>
                                </div><!-- Col 6 Close -->
                                <div class="col-sm-12">
                                    <hr/>
                                </div>
    





                                <div class="col-sm-12">
                                        <h3 style="margin-top:50px;">Auto Liability</h3>
                                </div><!-- Col 12 Close -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="name_of_report" style="padding-top: 15px;">Auto Liability Limit <span class="text-danger">*</span></label>
                                        <div class="clearfix"></div>
                                        <div class="col-xs-3" style="padding: 0px;">
                                            <div class="loading_data" style="text-align: center;">
                                               <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                            </div>
                                            <select class="form-control currency_symbol" name="auto_compensation_cur_symbol" id="auto_compensation_cur_symbol">
                                            </select>
                                        </div>
                                        <div class="col-xs-9">
                                            <input type="text" class="form-control" id="auto_compensation_currency" onkeypress="return isNumber(event)">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label>Expiration Date <span class="text-danger">*</span></label>
                                        <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                                <input type="text" readonly="" value="" size="16" class="form-control"  id="auto_compensation_date">
                                                  <span class="input-group-btn add-on">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                  </span>
                                            </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="form-group">
                                        <label class="checkbox-custom check-success">
                                            <input type="checkbox" value=" " id="auto_req_minimum">
                                            <label for="auto_req_minimum">Required Minimum</label>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label class="checkbox-custom check-success">
                                            <input type="checkbox" value="" id="upload_auto_above">
                                            <label for="upload_auto_above">Included in General Liability Certificate</label>
                                        </label>
                                    </div>

                                    <div class="form-group upload_auto">
                                        <label class="checkbox-custom check-success">
                                            <input type="checkbox" value=" " id="upload_auto">
                                            <label for="upload_auto">Not Included</label>
                                        </label>
                                    </div>

                                    <div class="clearfix"></div>
                                </div><!-- Col 6 Close -->
                                <div class="col-sm-6">
                                    <section class="panel upload_doc_panel_auto">
                                        <div class="panel-body">
                                            <form id="my-awesome-dropzone2" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                <input type="hidden" class="certificate_auto_liability" name="document_path" value="/uploads/certificate/">
                                            </form>
                                            <input type="hidden" name="standard_doc_id" id="upload_doc_id_auto" value="">
                                        </div>
                                    </section>
                                </div><!-- Col 6 Close -->
                                <div class="col-sm-12">
                                    <hr/>
                                </div>
        
                                <div class="form-group col-md-12">
                                    <button class="btn btn-success addon-btn m-b-10" id="add_umbrella_liability" style="float: right; width: 200px;">
                                        <i class="fa fa-plus"></i>
                                        Add Umbrella Liability
                                    </button>
                                </div>

                                <div id="add_umbrella_liability_div" style="display:none;">
                                    <div class="col-sm-10">
                                        <h3 style="margin: 0px;">Umbrella Liability</h3>
                                    </div><!-- Col 12 Close -->
                                    <div class="col-sm-2">
                                        <a id="delete_umbrella_liability_div" class="btn btn-danger btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Remove" style="float: right;"><i class="fa fa-times"></i></a>
                                    </div><!-- Col 12 Close -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name_of_report" style="padding-top: 15px;">Umbrella Liability Limit</label>
                                            <div class="clearfix"></div>
                                            <div class="col-xs-3" style="padding: 0px;">
                                                <div class="loading_data" style="text-align: center;">
                                                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                                </div>
                                                <select class="form-control currency_symbol" name="umbrella_liability_cur_symbol" id="umbrella_liability_cur_symbol">
                                                </select>
                                            </div>
                                            <div class="col-xs-9">
                                                <input type="text" class="form-control" id="umbrella_liability_currency" value="" onkeypress="return isNumber(event)">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group">
                                            <label>Expiration Date</label>
                                            <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                                <input type="text" readonly="" value="" size="16" class="form-control"  id="umbrella_liability_date">
                                                  <span class="input-group-btn add-on">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                  </span>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>

                                        <div class="form-group">
                                            <label class="checkbox-custom check-success">
                                                <input type="checkbox" value=" " id="req_minimum_umbrella">
                                                <label for="req_minimum_umbrella">Required Minimum</label>
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <label class="checkbox-custom check-success">
                                                <input type="checkbox" value=" " id="upload_umbrella_above">
                                                <label for="upload_umbrella_above">Included in General Liability Certificate</label>
                                            </label>
                                        </div>

                                        <div class="form-group upload_umbrella">
                                            <label class="checkbox-custom check-success">
                                                <input type="checkbox" value=" " id="upload_umbrella">
                                                <label for="upload_umbrella">Not Included</label>
                                            </label>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div><!-- Col 6 Close -->
                                    <div class="col-sm-6">
                                        <section class="panel upload_doc_panel_umbrella">
                                            <div class="panel-body">
                                                <form id="my-awesome-dropzone3" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                    <input type="hidden" class="certificate_auto_umbrella" name="document_path" value="/uploads/certificate/">
                                                </form>
                                                <input type="hidden" name="standard_doc_id" id="upload_doc_id_umbrella" value="">
                                            </div>
                                        </section>
                                    </div><!-- Col 6 Close -->
                                </div>

                                <div class="form-group col-md-12">
                                    <button class="btn btn-success addon-btn m-b-10" id="add_more_certificate" style="float: right; width: 200px;">
                                        <i class="fa fa-plus"></i>
                                        Add Another Certificate
                                    </button>
                                </div>

                                <div id="add_more_certificate_div" style="display: none;">
                                    <!-- <div class="col-sm-6">
                                        <h3>Custom Certificate</h3>
                                    </div>
                                    <div class="col-sm-6">
                                        <button class="btn btn-success addon-btn m-b-10" id="add_more_cert" style="float: right;">
                                        <i class="fa fa-plus"></i>
                                            Add Certificate
                                        </button>
                                    </div> -->
                                    <div class="clearfix"></div>
<div class="custom_certificate_all">
    <!-- <div class="custom_certificate_detail">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name_of_report" style="padding-top: 15px;">Certificate Liability Name</label>
                <input type="text" class="form-control" name="custom_certificate_name[]" id="custom_certificate_name">
                <div class="clearfix"></div>
                <label for="name_of_report" style="padding-top: 15px;">Certificate Liability Limit</label>
                <div class="clearfix"></div>
                <div class="col-xs-3" style="padding: 0px;">
                    <div class="loading_data" style="text-align: center;">
                       <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                    </div>
                    <select class="form-control currency_symbol" name="custom_certificate_cur_symbol[]" id="custom_certificate_cur_symbol">
                    </select>
                </div>
                <div class="col-xs-9">
                    <input type="text" class="form-control" name="custom_certificate_currency[]" id="custom_certificate_currency" onkeypress="return isNumber(event)">
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <label>Expiration Date</label>
                <div data-date-viewmode="years" data-date-format="yyyy-mm-dd"  class="input-append date dpYears">
                    <input type="text" readonly="" value="" size="16" class="form-control" name="custom_certificate_date[]">
                      <span class="input-group-btn add-on">
                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                      </span>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="col-sm-6">
            <div class="col-sm-12">
                <a class="btn btn-danger btn-xs tooltips remove_current_custom_certificate" data-placement="top" data-toggle="tooltip" data-original-title="Remove" style="float: right; margin-top: 15px;"><i class="fa fa-times"></i></a>
            </div>
            <section class="panel upload_doc_panel" id="upload_div" style="margin-top: 50px;">
                <div class="panel-body" style="padding: 0px;">
                    <form id="my-awesome-dropzone4" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                        <input type="hidden" class="" name="document_path" value="/uploads/certificate/">
                    </form>
                    <input type="hidden" name="upload_doc_id[]" class="upload_doc_id" id="upload_doc_id_1" value="">
                    <input type="hidden" name="upload_type" id="upload_type" value="multiple_upload">
                </div>
            </section>
        </div>
    </div><div class="clearfix"></div> --><!-- custom_certificate_detail close -->
</div><!-- custom_certificate_all close -->
</div>
                                <div class="form-group col-md-12">
                                    <input type="hidden" name="standard_doc_id" id="upload_doc_id_certificate" value="">
<!--                                    <a data-href="{{ url('/dashboard/'.$project_id.'/certificate') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                    <a href="{{ url('/dashboard/'.$project_id.'/certificate') }}" class="btn btn-info sub-btn btn_back" onclick="return checkFormFilled('btn_back')">Back</a>
                                    <button type="submit" id="add_certificate_form" class="btn btn-info sub-btn first_button">Save</button>
                                    <a href="{{ url('/dashboard/'.$project_id.'/bond') }}" class="btn btn-info sub-btn continue_button" onclick="return checkFormFilled('continue_button')">Next Screen</a>
                                    <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
                                </div>
                                <div class="clearfix"></div>

<div style="display: none;">
<!-- <div> -->
    <div id="certificate_pdf_content" style="width:100%;">
        <h1 style="color:green; text-align:center;">Certificates of Insurance Summary</h1>
        <div style="clear: both;"></div>
        <p style="width:70%;"><strong>Contractor: </strong><span id="pdf_gen_contractor_name"></span><br/>
            <span id="pdf_gen_contractor_address"></span></p>
        <div style="clear: both;"></div>
        <p><strong>Project: </strong><span id="pdf_gen_project_name"></span></p>
        <div style="clear: both;"></div>
        <p><strong>General Liability Exp. Date: </strong><span id="pdf_gen_general_date"></span></p>
        <div style="clear: both;"></div>
        <p><strong>General Liability Limit: </strong><span id="pdf_gen_general_limit"></span></p>
        <div style="clear: both;"></div>
        <p><strong>Auto Liability Exp. Date: </strong><span id="pdf_gen_auto_date"></span></p>
        <div style="clear: both;"></div>
        <p><strong>Auto Liability Limit: </strong><span id="pdf_gen_auto_limit"></span></p>
        <div style="clear: both;"></div>
        <p><strong>Workers Comp Exp. Date: </strong><span id="pdf_gen_work_comp_date"></span></p>
        <div style="clear: both;"></div>
        <p><strong>Workers Comp Limit: </strong><span id="pdf_gen_work_comp_limit"></span></p>
        <div style="clear: both;"></div>
        <p><strong>Umbrella Liability Exp. Date: </strong><span id="pdf_gen_umbrella_date"></span></p>
        <div style="clear: both;"></div>
        <p><strong>Umbrella Liability Limit: </strong><span id="pdf_gen_umbrella_limit"></span></p>
        <div style="clear: both;"></div>
        <div id="custom_certificate_pdf" style="display: block;">

        </div>
        <div style="clear: both;"></div>

    </div>
</div>
                            </div>
                        </section>
                    </div>


                </div>
            </div>
            <!--body wrapper end-->
<!-- Placed js at the end of the document so the pages load faster -->
<!-- <script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script> -->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js?v=1.0"></script>
<script src="{{ url('/resources/assets/dist/api_url.js?v=1.0') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js?v=1.0"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.0.16/jspdf.plugin.autotable.js"></script> -->
<script src="{{ url('/resources/assets/js/FileSaver.js?v=1.0') }}"></script>

<script type="text/javascript">
$('#general_liability_included').change(function() {
    if($(this).is(":checked")) {
        $('.upload_doc_panel').css("display", "none");
    }
    else {
        $('.upload_doc_panel').css("display", "block");
    }
});

$('#upload_work_above').change(function() {
    if($(this).is(":checked")) {
        $('.upload_work').css("display", "none");
        $('.upload_doc_panel_work').css("display", "none");
        $('#upload_doc_id').removeAttr('value');
    }
    else {
        $('.upload_work').css("display", "block");
        $('.upload_doc_panel_work').css("display", "block");
    }
});

$("#upload_work").change(function() {
    if($(this).is(":checked")) {
        $('.upload_doc_panel_work').css("display", "none");
        $('#upload_doc_id').removeAttr('value');
        $("#upload_warning").show();
        setTimeout(function()
        {
            $("#upload_warning").hide();

        },3000)
    }
    else {
        $('.upload_doc_panel_work').css("display", "block");
    }
});

$('#upload_auto_above').change(function() {
    if($(this).is(":checked")) {
        $('.upload_auto').css("display", "none");
        $('.upload_doc_panel_auto').css("display", "none");
        $('#upload_doc_id').removeAttr('value');
    }
    else {
        $('.upload_auto').css("display", "block");
        $('.upload_doc_panel_auto').css("display", "block");
    }
});

$("#upload_auto").change(function() {
    if($(this).is(":checked")) {
        $('.upload_doc_panel_auto').css("display", "none");
        $('#upload_doc_id').removeAttr('value');
        $("#upload_warning").show();
        setTimeout(function()
        {
            $("#upload_warning").hide();

        },3000)
    }
    else {
        $('.upload_doc_panel_auto').css("display", "block");
    }
});

$('#upload_umbrella_above').change(function() {
    if($(this).is(":checked")) {
        $('.upload_umbrella').css("display", "none");
        $('.upload_doc_panel_umbrella').css("display", "none");
        $('#upload_doc_id_umbrella').removeAttr('value');
    }
    else {
        $('.upload_umbrella').css("display", "block");
        $('.upload_doc_panel_umbrella').css("display", "block");
    }
});

$("#upload_umbrella").change(function() {
    if($(this).is(":checked")) {
        $('.upload_doc_panel_umbrella').css("display", "none");
        $('#upload_doc_id_umbrella').removeAttr('value');
        $("#upload_warning").show();
        setTimeout(function()
        {
            $("#upload_warning").hide();

        },3000)
    }
    else {
        $('.upload_doc_panel_umbrella').css("display", "block");
    }
});


$('body').delegate( '.upload_doc_panel', 'click', function () {
    var id = $(this).find(".upload_doc_id:first").attr("id");
    // console.log(id);
    window.localStorage.setItem("upload_doc_id", id);
    console.log(localStorage.getItem("upload_doc_id"));
    return;
});

$("#delete_umbrella_liability_div").click(function() {

  $("#req_minimum_umbrella").prop('checked', false);
  $("#upload_umbrella_above").prop('checked', false);
  $("#upload_umbrella").prop('checked', false);
  $('#umbrella_liability_currency').removeAttr('value');
  $('#umbrella_liability_date').removeAttr('value');
  // alert('faizan');
  $("#add_umbrella_liability_div").hide();
  window.localStorage.setItem("umbrella_cert_on", "no");
});

$('body').delegate('.remove_current_custom_certificate', 'click', function (e) {
    e.preventDefault();
    $(this).parent().parent().parent().remove();
});

$("#add_umbrella_liability").click(function() {
  $("#add_umbrella_liability_div").show();
  window.localStorage.setItem("umbrella_cert_on", "yes");
});
$("#add_more_certificate").click(function() {
  $("#add_more_certificate_div").show();
  var randum_number = Math.floor(Math.random()*(99-11+1)+11);
        var html = '<div class="custom_certificate_detail">'+
        '<div class="col-sm-6">'+
            '<div class="form-group">'+
                '<label for="name_of_report" style="padding-top: 15px;">Policy Type</label>'+
                '<input type="text" class="form-control" name="custom_certificate_name[]" id="custom_certificate_name">'+
                '<div class="clearfix"></div>'+
                '<label for="name_of_report" style="padding-top: 15px;">Policy Limit</label>'+
                '<div class="clearfix"></div>'+
                '<div class="col-xs-3" style="padding: 0px;">'+
                    '<div class="loading_data" style="text-align: center;">'+
                       '<img src="'+baseUrl+'/resources/assets/img/loading_bar.gif" alt=""/>'+
                    '</div>'+
                    '<select class="form-control currency_symbol_more" name="custom_certificate_cur_symbol[]" id="custom_certificate_cur_symbol">'+
                    '</select>'+
                '</div>'+
                '<div class="col-xs-9">'+
                    '<input type="text" class="form-control" name="custom_certificate_currency[]" id="custom_certificate_currency" onkeypress="return isNumber(event)">'+
                '</div>'+
            '</div>'+
            '<div class="clearfix"></div>'+
            '<div class="form-group">'+
                '<label>Expiration Date</label>'+
                // '<div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">'+
                //     '<input type="text" readonly="" value="" size="16" class="form-control" name="custom_certificate_date[]">'+
                //       '<span class="input-group-btn add-on">'+
                //         '<button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>'+
                //       '</span>'+
                // '</div>'+
                '<input type="date" value="" class="form-control" name="custom_certificate_date[]">'+
            '</div>'+
            '<div class="clearfix"></div>'+
        '</div>'+
        '<div class="col-sm-6">'+
            '<div class="col-sm-12">'+
                '<a class="btn btn-danger btn-xs tooltips remove_current_custom_certificate" data-placement="top" data-toggle="tooltip" data-original-title="Remove" style="float: right; margin-top: 15px;"><i class="fa fa-times"></i></a>'+
            '</div>'+
            '<section class="panel upload_doc_panel" id="upload_div" style="margin-top: 50px;">'+
                '<div class="panel-body" style="padding: 0px;">'+
                    '<form action="" class="dropzone dz-clickable my-awesome-dropzone-multiple">'+
                        '<input type="hidden" name="document_path" value="/uploads/certificate/">'+
                        // '<div class="dz-default dz-message"><span>Drop files here to upload</span></div>'+
                    '</form>'+
                    '<input type="hidden" name="upload_doc_id[]" class="upload_doc_id" id="upload_doc_id_'+randum_number+'" value="">'+
                    // '<input type="text" name="upload_type" id="upload_type" value="multiple_upload">'+
                '</div>'+
            '</section>'+
        '</div>'+
    '</div><div class="clearfix"></div>';
    $(".custom_certificate_all").append(html);
    $('.custom_certificate_detail:last .upload_doc_panel .panel-body form').dropzone({url: baseUrl+'document/uploadFiles'});
    jQuery.ajax({
    url: baseUrl + "currency",
        type: "GET",
        headers: {
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        jQuery.each(data.data, function( i, val ) {
            if(val.cur_status == 'active'){
                $(".currency_symbol_more").append(
                    '<option value="'+val.cur_id+'">'+val.cur_symbol+'</option>'
                )
            }else {

            }
        });
        $(".loading_data").remove();
        $(".currency_symbol_more").show();
    })
    return;
});

$("#my-awesome-dropzone").click(function() {
  $("#upload_type").val("certificate_general_libility");
});
$("#my-awesome-dropzone1").click(function() {
  $("#upload_type").val("certificate_work_compensation");
});
$("#my-awesome-dropzone2").click(function() {
  $("#upload_type").val("certificate_auto_liability");
});
$("#my-awesome-dropzone3").click(function() {
  $("#upload_type").val("certificate_auto_umbrella");
});
// $("#my-awesome-dropzone4").click(function() {
//   $("#upload_type").val("multiple_upload");
// });
$('body').delegate( '.my-awesome-dropzone-multiple', 'click', function () {
  $("#upload_type").val("multiple_upload");
  return;
});
// $(".my-awesome-dropzone-multiple").click(function() {
//     alert('Click');
//   $("#upload_type").val("multiple_upload");
// });
</script>

<script src="{{ url('/resources/assets/dist/certificate_add.js?v=1.0') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone.js?v=1.0') }}"></script>
@include('include/footer')
