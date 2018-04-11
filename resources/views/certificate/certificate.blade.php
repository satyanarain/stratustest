        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
    
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Add Certificate of Insurance</h3>
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
                                <div class="col-md-9">
                                    
                                        <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="company_name">Contractor</label>
                                            <div class="loading_data" style="text-align: center;">
                                               <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                            </div>
                                            <select class="form-control select2" id="company_name">
                                            <!-- <select class="form-control" id="company_name"> -->
                                            </select>
                                        </div>

                                        <header class="panel-heading">General Liability</header>

                                        <div class="form-group col-md-12">
                                            <label for="name_of_report" style="padding-top: 15px;">General Liability Limit</label>
                                            <div class="clearfix"></div>
                                            <div class="col-xs-3" style="padding: 0px; display: none;">
                                                <div class="loading_data" style="text-align: center;">
                                                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                                </div>
                                                <select class="form-control currency_symbol" id="general_liability_cur_symbol">
                                                    <option value="1">$</option>
                                                </select>
                                            </div>
                                            <div class="col-xs-12 nopadleft">
                                                <input type="text" class="form-control" id="general_liability_amount">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-md-12">
                                            <label>Expiration Date</label>
                                            <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date="2016-09-01"  class="input-append date dpYears">
                                                    <input type="text" readonly="" value="" size="16" class="form-control"  id="general_liability_date">
                                                      <span class="input-group-btn add-on">
                                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                      </span>
                                                </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class=" col-md-12">
                                            <label for="standard_link" style="min-width: 200px;">Required Minimum</label>
                                            <label class="radio-inline">
                                              <input type="radio" name="general_liability_req_minimum" id="req_minimum" value="yes"> Yes
                                            </label>
                                            <label class="radio-inline">
                                              <input type="radio" name="general_liability_req_minimum" id="req_minimum" value="no"> No
                                            </label>
                                        </div>

                                        <section class="panel upload_doc_panel" style="margin-top: 40px;">
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



                                        <header class="panel-heading" style="margin-top:50px;">Works Compensation</header>

                                        <div class="form-group col-md-12">
                                            <label for="name_of_report" style="padding-top: 15px;">Works Compensation Limit</label>
                                            <div class="clearfix"></div>
                                            <div class="col-xs-3" style="padding: 0px;">
                                                <div class="loading_data" style="text-align: center;">
                                                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                                </div>
                                                <select class="form-control currency_symbol" id="works_compensation_cur_symbol">
                                                </select>
                                            </div>
                                            <div class="col-xs-9">
                                                <input type="text" class="form-control" id="works_compensation_currency">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-md-12">
                                            <label>Expiration Date</label>
                                            <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date="2016-09-01"  class="input-append date dpYears">
                                                    <input type="text" readonly="" value="" size="16" class="form-control"  id="works_compensation_date">
                                                      <span class="input-group-btn add-on">
                                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                      </span>
                                                </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-md-12">
                                            <label for="standard_link" style="min-width: 200px;">Required Minimum</label>
                                            <label class="radio-inline">
                                              <input type="radio" name="works_compensation_req_minimum" id="req_minimum" value="yes"> Yes
                                            </label>
                                            <label class="radio-inline">
                                              <input type="radio" name="works_compensation_req_minimum" id="req_minimum" value="no"> No
                                            </label>
                                        </div>
                                        
                                        <div class="form-group col-md-12">
                                            <label for="standard_link" style="min-width: 200px;">Included in Above Cert.</label>
                                            <label class="radio-inline">
                                              <input type="radio" name="upload_work_above" id="upload" value="yes"> Yes
                                            </label>
                                            <label class="radio-inline">
                                              <input type="radio" name="upload_work_above" id="upload" value="no"> No
                                            </label>
                                        </div>

                                        <div class="form-group col-md-12 upload_work">
                                            <label for="standard_link" style="min-width: 200px;">Not Included</label>
                                            <label class="radio-inline">
                                              <input type="radio" name="upload_work" id="upload" value="yes"> Yes
                                            </label>
                                            <label class="radio-inline">
                                              <input type="radio" name="upload_work" id="upload" value="no"> No
                                            </label>
                                        </div>

                                        <div class="clearfix"></div>

                                        <section class="panel upload_doc_panel_work" style="margin-top: 0px;">
                                        <div class="panel-body">
                                            <form id="my-awesome-dropzone1" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                <input type="hidden" class="certificate_work_compensation" name="document_path" value="/uploads/certificate/">
                                            </form> 
                                            <input type="hidden" name="standard_doc_id" id="upload_doc_id_work" value="">
                                        </div>
                                    </section>

                                        <div class="clearfix"></div>
    
                                        <header class="panel-heading" style="margin-top:50px;">Auto Liability</header>

                                        <div class="form-group col-md-12">
                                            <label for="name_of_report" style="padding-top: 15px;">Auto Liability Limit</label>
                                            <div class="clearfix"></div>
                                            <div class="col-xs-3" style="padding: 0px;">
                                                <div class="loading_data" style="text-align: center;">
                                                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                                </div>
                                                <select class="form-control currency_symbol" id="auto_compensation_cur_symbol">
                                                </select>
                                            </div>
                                            <div class="col-xs-9">
                                                <input type="text" class="form-control" id="auto_compensation_currency">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-md-12">
                                            <label>Expiration Date</label>
                                            <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date="2016-09-01"  class="input-append date dpYears">
                                                    <input type="text" readonly="" value="" size="16" class="form-control"  id="auto_compensation_date">
                                                      <span class="input-group-btn add-on">
                                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                      </span>
                                                </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-md-12">
                                            <label for="standard_link" style="min-width: 200px;">Required Minimum</label>
                                            <label class="radio-inline">
                                              <input type="radio" name="auto_compensation_req_minimum" id="req_minimum" value="yes"> Yes
                                            </label>
                                            <label class="radio-inline">
                                              <input type="radio" name="auto_compensation_req_minimum" id="req_minimum" value="no"> No
                                            </label>
                                        </div>
                                        
                                        <div class="form-group col-md-12">
                                            <label for="standard_link" style="min-width: 200px;">Included in Above Cert.</label>
                                            <label class="radio-inline">
                                              <input type="radio" name="upload_auto_above" id="upload" value="yes"> Yes
                                            </label>
                                            <label class="radio-inline">
                                              <input type="radio" name="upload_auto_above" id="upload" value="no"> No
                                            </label>
                                        </div>

                                        <div class="form-group col-md-12 upload_auto">
                                            <label for="standard_link" style="min-width: 200px;">Not Included</label>
                                            <label class="radio-inline">
                                              <input type="radio" name="upload_auto" id="upload" value="yes"> Yes
                                            </label>
                                            <label class="radio-inline">
                                              <input type="radio" name="upload_auto" id="upload" value="no"> No
                                            </label>
                                        </div>
                                        <div class="clearfix"></div>

                                        <section class="panel upload_doc_panel_auto" style="margin-top: 10px;">
                                        <div class="panel-body">
                                            <form id="my-awesome-dropzone2" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                <input type="hidden" class="certificate_auto_liability" name="document_path" value="/uploads/certificate/">
                                            </form> 
                                            <input type="hidden" name="standard_doc_id" id="upload_doc_id_auto" value="">
                                        </div>
                                    </section>


                                    
                                        <!-- <div class="form-group col-md-12">
                                            <input type="text" name="standard_upload" id="upload_doc_meta" value="swppp">
                                            <input type="text" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                                            <input type="text" name="standard_doc_id" id="upload_doc_id" value="">
                                        </div> -->

                                        <div class="form-group col-md-12">
                                            <button type="submit" class="btn btn-info sub-btn">Submit</button>
                                        </div>
                                        
                                        </div>
                                    
                                </div><!-- Col 6 Close -->
                                <div class="col-md-6">
                                    <!-- <section class="panel upload_doc_panel" style="margin-top: 125px;">
                                        <div class="panel-body">
                                            <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                <input type="hidden" name="document_path" value="/uploads/certificate/">
                                            </form> 
                                            <input type="hidden" name="upload_type" id="upload_type" value="">
                                            <input type="hidden" name="standard_upload" id="upload_doc_meta" value="certificate">
                                            <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                                            <input type="hidden" name="standard_doc_id" id="upload_doc_id_general" value="">
                                        </div>
                                    </section> -->


                                    <!-- <section class="panel upload_doc_panel_work" style="margin-top: 125px;">
                                        <div class="panel-body">
                                            <form id="my-awesome-dropzone1" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                <input type="hidden" class="certificate_work_compensation" name="document_path" value="/uploads/certificate/">
                                            </form> 
                                            <input type="hidden" name="standard_doc_id" id="upload_doc_id_work" value="">
                                        </div>
                                    </section> -->

                                    <!-- <section class="panel upload_doc_panel_auto" style="margin-top: 125px;">
                                        <div class="panel-body">
                                            <form id="my-awesome-dropzone2" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                <input type="hidden" class="certificate_auto_liability" name="document_path" value="/uploads/certificate/">
                                            </form> 
                                            <input type="hidden" name="standard_doc_id" id="upload_doc_id_auto" value="">
                                        </div>
                                    </section> -->
                                </div><!-- Col 6 Close -->
                            </div>
                        </section>
                    </div>

                   
                </div>
            </div>
            <!--body wrapper end-->
<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>

<script type="text/javascript">

$("input[name='upload']").click(function(){
        // alert($('input:radio[name=upload]:checked').val());
    if($('input:radio[name=upload]:checked').val() == "no"){
        $('.upload_doc_panel').css("display", "none");
        $('#upload_doc_id').removeAttr('value');
        $("#upload_warning").fadeIn(1000).fadeOut(7000);
    }
    else {
        $('.upload_doc_panel').css("display", "block");   
    }
});

$("input[name='upload_work']").click(function(){
    if($('input:radio[name=upload_work]:checked').val() == "no"){
        $('.upload_doc_panel_work').css("display", "none");
        $('#upload_doc_id').removeAttr('value');
        $("#upload_warning").fadeIn(1000).fadeOut(7000);
    }
    else {
        $('.upload_doc_panel_work').css("display", "block");   
    }
});
$("input[name='upload_work_above']").click(function(){
    if($('input:radio[name=upload_work_above]:checked').val() == "yes"){
        $('.upload_doc_panel_work').css("display", "none");
        $('.upload_work').css("display", "none");
        $('#upload_doc_id').removeAttr('value');
        // $("#upload_warning").fadeIn(1000).fadeOut(7000);
    }
    else {
        $('.upload_doc_panel_work').css("display", "block");   
        $('.upload_work').css("display", "block");
    }
});

$("input[name='upload_auto']").click(function(){
    if($('input:radio[name=upload_auto]:checked').val() == "no"){
        $('.upload_doc_panel_auto').css("display", "none");
        $('#upload_doc_id').removeAttr('value');
        $("#upload_warning").fadeIn(1000).fadeOut(7000);
    }
    else {
        $('.upload_doc_panel_auto').css("display", "block");   
    }
});
$("input[name='upload_auto_above']").click(function(){
    if($('input:radio[name=upload_auto_above]:checked').val() == "yes"){
        $('.upload_doc_panel_auto').css("display", "none");
        $('.upload_auto').css("display", "none");
        $('#upload_doc_id').removeAttr('value');
        // $("#upload_warning").fadeIn(1000).fadeOut(7000);
    }
    else {
        $('.upload_doc_panel_auto').css("display", "block");   
        $('.upload_auto').css("display", "block");
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
</script>

<script src="{{ url('/resources/assets/dist/certificate_add.js') }}"></script>
@include('include/footer')