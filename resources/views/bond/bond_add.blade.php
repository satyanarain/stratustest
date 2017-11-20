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
                <h3 class="m-b-less">Add Bonds</h3>
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
                                            <div class="toast-message">Please contact your supervisor</div>
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
            <label for="company_name">Contractor</label>
            <div class="loading_data" style="text-align: center;">
               <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
            </div>
            <!-- <select class="form-control select2" id="company_name"> -->
            <select class="form-control" id="company_name">
            </select>
        </div>

        <div class="form-group">
            <label for="company_name">Are any of the following contractor bonds required? <span class="text-danger">*</span></label>
        </div>
        <div class="form-group">
            <div class="col-lg-10">
                <label class="checkbox-custom check-success">
                    <input type="checkbox" value=" " id="performance_bond_yes">
                    <label for="performance_bond_yes">Performance Bond</label>
                </label>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-10">
                <label class="checkbox-custom check-success">
                    <input type="checkbox" value=" " id="payment_bond_yes">
                    <label for="payment_bond_yes">Payment Bond (Labor &amp; Material Bond)</label>
                </label>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-10">
                <label class="checkbox-custom check-success">
                    <input type="checkbox" value=" " id="maintenance_bond_yes">
                    <label for="maintenance_bond_yes">Maintenance Bond</label>
                </label>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<div class="col-md-12">
    <div class="row">
        <div class="upload_doc_panel_performance" style="display:none;">
            <h3 style="padding-left: 15px;">Performance Bond</h3>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name_of_report" style="padding-top: 15px;">Amount of Performance Bond</label>
                    <div class="clearfix"></div>
                    <div class="col-xs-3" style="padding: 0px;">
                        <div class="loading_data" style="text-align: center;">
                           <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                        </div>
                        <select class="form-control currency_symbol" id="performance_bond_cur_symbol">
                        </select>
                    </div>
                    <div class="col-xs-9">
                        <input type="text" name="performance_bond_amount" class="form-control" id="performance_bond_amount" onkeypress="return isNumber(event)">
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    <label>Date of Executed Bond <span class="text-danger">*</span></label>
                    <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                            <input type="text" readonly="" value="" size="16" class="form-control"  id="performance_bond_date">
                              <span class="input-group-btn add-on">
                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                              </span>
                        </div>
                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    <label>Bond Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="performance_bond_number" onkeypress="return isNumber(event)">
                </div>
                <div class="form-group upload_performance" style="padding: 0px;">
                    <label for="standard_link" style="min-width: 200px;">Are all required signature fields signed by authorized representatives and notarized? <span class="text-danger">*</span></label>
                    <label class="radio-inline">
                      <input type="radio" name="upload_performance" id="upload" value="yes" required=""> Yes
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="upload_performance" id="upload" value="no"> No
                    </label>
                </div>
            </div>

            <div class="col-sm-6">
                <section class="panel" style="display:none;" id="upload_performance">
                    <div class="panel-body">
                        <label>Upload Performance Bond <span class="text-danger">*</span></label>
                        <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                            <input type="hidden" name="document_path" value="/uploads/bond/">
                        </form>
                    <input type="hidden" name="standard_doc_id" id="upload_doc_id_general" value="">
                    </div>
                </section>
                <input type="hidden" name="upload_type" id="upload_type" value="">
                <input type="hidden" name="standard_upload" id="upload_doc_meta" value="certificate">
                <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
            </div>
        </div><!-- upload_doc_panel_performance close -->
    </div>
</div>
<div class="clearfix"></div>

<div class="col-md-12">
    <div class="row">
        <div class="upload_doc_panel_payment" style="display:none;">
            <hr/>
            <h3 style="padding-left: 15px; margin-top: 25px;">Payment Bond</h3>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name_of_report" style="padding-top: 15px;">Amount of Payment Bond</label>
                    <div class="clearfix"></div>
                    <div class="col-xs-3" style="padding: 0px;">
                        <div class="loading_data" style="text-align: center;">
                           <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                        </div>
                        <select class="form-control currency_symbol" id="payment_bond_cur_symbol">
                        </select>
                    </div>
                    <div class="col-xs-9">
                        <input type="text" class="form-control" name="payment_bond_amount" id="payment_bond_amount" onkeypress="return isNumber(event)">
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    <label>Date of Executed Bond <span class="text-danger">*</span></label>
                    <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                        <input type="text" readonly="" value="" size="16" class="form-control"  id="payment_bond_date">
                        <span class="input-group-btn add-on">
                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    <label>Bond Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="payment_bond_number" onkeypress="return isNumber(event)">
                </div>
                <div class="col-md-12 upload_payment" style="padding: 0px;">
                    <label for="standard_link" style="min-width: 200px;">Are all required signature fields signed by authorized representatives and notarized? <span class="text-danger">*</span></label>
                    <label class="radio-inline">
                      <input type="radio" name="upload_payment" id="upload" value="yes"> Yes
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="upload_payment" id="upload" value="no"> No
                    </label>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="col-sm-6">
                <section class="panel" style="display:none;" id="upload_payment">
                    <div class="panel-body">
                        <label>Upload Payment Bond <span class="text-danger">*</span></label>
                        <form id="my-awesome-dropzone1" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                        <input type="hidden" class="certificate_work_compensation" name="document_path" value="/uploads/bond/">
                        </form>
                        <input type="hidden" name="standard_doc_id" id="upload_doc_id_work" value="">
                    </div>
                </section>
            </div>
        </div><!-- upload_doc_panel_payment close -->
    </div>
</div>
<div class="clearfix"></div>


<div class="col-md-12">
    <div class="row">
        <div class="upload_doc_panel_maintenance" style="display:none;">
            <hr/>
             <h3 style="padding-left: 15px; margin-top: 25px;">Maintenance Bond</h3>
             <div class="col-sm-6">
                <div class="form-group">
                    <label for="name_of_report" style="padding-top: 15px;">Amount of Maintenance Bond</label>
                    <div class="clearfix"></div>
                    <div class="col-xs-3" style="padding: 0px;">
                        <div class="loading_data" style="text-align: center;">
                           <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                        </div>
                        <select class="form-control currency_symbol" id="maintenance_bond_cur_symbol">
                        </select>
                    </div>
                    <div class="col-xs-9">
                        <input type="text" class="form-control" name="maintenance_bond_amount" id="maintenance_bond_amount" onkeypress="return isNumber(event)">
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    <label>Date of Executed Bond <span class="text-danger">*</span></label>
                    <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                        <input type="text" readonly="" value="" size="16" class="form-control"  id="maintenance_bond_date">
                        <span class="input-group-btn add-on">
                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    <label>Bond Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="maintenance_bond_number" onkeypress="return isNumber(event)">
                </div>
                <div class="col-md-12 upload_maintenance" style="padding: 0px;">
                    <label for="standard_link" style="min-width: 200px;">Are all required signature fields signed by authorized representatives and notarized? <span class="text-danger">*</span></label>
                    <label class="radio-inline">
                      <input type="radio" name="upload_maintenance" id="upload" value="yes"> Yes
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="upload_maintenance" id="upload" value="no"> No
                    </label>
                </div>
            </div>
            <div class="col-sm-6">
                 <section class="panel" style="display:none;" id="upload_maintenance">
                    <div class="panel-body">
                        <label>Upload Maintenance Bond <span class="text-danger">*</span></label>
                        <form id="my-awesome-dropzone2" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                            <input type="hidden" class="certificate_auto_liability" name="document_path" value="/uploads/bond/">
                        </form>
                        <input type="hidden" name="standard_doc_id" id="upload_doc_id_auto" value="">
                    </div>
                </section>
            </div>
        </div><!-- upload_doc_panel_maintenance close -->
    </div>
</div>
<div class="form-group col-md-12">
<!--    <a data-href="{{ url('/dashboard/'.$project_id.'/bond') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
    <a href="{{ url('/dashboard/'.$project_id.'/bond') }}" class="btn btn-info sub-btn btn_back" onclick="return checkFormFilled('btn_back')">Back</a>
    <button type="submit" id="add_bond_form" class="btn btn-info sub-btn first_button">Save</button>
    <a data-href="{{ url('/dashboard/'.$project_id.'/notice_proceed') }}" class="btn btn-info sub-btn continue_button" data-toggle="modal" data-target="#confirm-continue">Next Screen</a>
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

<script type="text/javascript">

$("input[name='upload_performance']").click(function(){
        // alert($('input:radio[name=upload_performance]:checked').val());
    if($('input:radio[name=upload_performance]:checked').val() == "no"){
        $('#upload_performance').css("display", "none");
        $('#upload_doc_id_general').removeAttr('value');
        $("#upload_warning").fadeIn(1000).fadeOut(7000);
    }
    else {
        $('#upload_performance').css("display", "block");
    }
});

$("input[name='upload_payment']").click(function(){
    if($('input:radio[name=upload_payment]:checked').val() == "no"){
        $('#upload_payment').css("display", "none");
        $('#upload_doc_id_work').removeAttr('value');
        $("#upload_warning").fadeIn(1000).fadeOut(7000);
    }
    else {
        $('#upload_payment').css("display", "block");
    }
});
$("input[name='upload_maintenance']").click(function(){
    if($('input:radio[name=upload_maintenance]:checked').val() == "no"){
        $('#upload_maintenance').css("display", "none");
        $('#upload_doc_id_auto').removeAttr('value');
        $("#upload_warning").fadeIn(1000).fadeOut(7000);
    }
    else {
        $('#upload_maintenance').css("display", "block");
    }
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

$('#performance_bond_yes').change(function() {
    if($(this).is(":checked")) {
        $('.upload_doc_panel_performance').css("display", "block");
        // $('#upload_performance').css("display", "block");
    }
    else {
       $('#upload_performance').css("display", "none");
       $('.upload_doc_panel_performance').css("display", "none");
    }
});

$('#payment_bond_yes').change(function() {
    if($(this).is(":checked")) {
        $('.upload_doc_panel_payment').css("display", "block");
        // $('#upload_payment').css("display", "block");
    }
    else {
       $('#upload_payment').css("display", "none");
       $('.upload_doc_panel_payment').css("display", "none");
    }
});

$('#maintenance_bond_yes').change(function() {
    if($(this).is(":checked")) {
        $('.upload_doc_panel_maintenance').css("display", "block");
        // $('#upload_maintenance').css("display", "block");
    }
    else {
       $('#upload_maintenance').css("display", "none");
       $('.upload_doc_panel_maintenance').css("display", "none");
    }
});
</script>

<script src="{{ url('/resources/assets/dist/bond_add.js?v=1.0') }}"></script>
@include('include/footer')
