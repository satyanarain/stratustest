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
                <h3 class="m-b-less">Add Notice to Proceed</h3>
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
            <header class="panel-heading">Contractor: <span id="contractor_name"></span></header>
        </div>

        <div class="col-sm-12 nopadleft">
            <div class="col-md-12 check_award_type" style="margin-bottom:50px;">
                <label for="standard_link">Would you like to create a Notice to Proceed or has one already been created?</label><br/>
                <label class="radio-inline">
                    <input type="radio" name="check_award_type" id="upload" value="new">  Create Notice to Proceed
                </label><br/>
                <label class="radio-inline">
                    <input type="radio" name="check_award_type" id="upload" value="exist"> Upload existing Notice to Proceed
                </label>
            </div>

            <div class="newone newone col-md-12" id="newone" style="display: none;">
            <div class="form-group col-sm-6">
                <label for="name_of_report" style="">Date of Notice <span class="text-danger">*</span></label>
                <!-- <input type="text" class="form-control" id="notice_date" value="<?php echo date("Y-m-d"); ?>"> -->
                <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                    <input type="text" readonly="" value="" size="16" class="form-control"  id="notice_date">
                    <span class="input-group-btn add-on">
                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>
                <!-- <div class="form-group">
                  <div class='input-group date' id='datetimepicker6'>
                      <input type='text' readonly class="form-control" id="notice_date"/>
                      <span class="input-group-addon btn btn-primary">
                          <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                  </div>
                </div> -->
            </div>

            <div class="form-group col-sm-6">
                <label>Start Date <span class="text-danger">*</span></label>
                <!-- <input type="text" class="form-control" id="notice_start_date" value="<?php echo date("Y-m-d"); ?>"> -->
                <!-- <div class="form-group">
                  <div class='input-group date' id='datetimepicker7'>
                      <input type='text' readonly class="form-control" id="notice_start_date" />
                      <span class="input-group-addon btn btn-primary">
                          <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                    </div>
                </div> -->
                <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                    <input type="text" readonly="" value="" size="16" class="form-control"  id="notice_start_date">
                    <span class="input-group-btn add-on">
                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>
            </div>
            <div class="clearfix"></div>    
            <div class="form-group col-sm-6">
                <label>Duration (in days) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" min="1" id="duration_days" required="required">
            </div>

            <div class="form-group col-sm-6" style="padding-top: 20px;">
                <label class="radio-inline">
                    <input type="radio"   name="days_working" id="days_working" value="calendar_day" checked="checked">Calendar Days
                </label><br/>
                <label class="radio-inline">
                  <input type="radio"    name="days_working" id="days_working" value="working_day"> Working Days
                </label>
            </div>
            <div class="clearfix"></div>
            <div class="form-group col-sm-6">
                <label class="col-md-4 nopadleft" style="padding-top: 5px;">Liquidated Damages <span class="text-danger">*</span></label>
                <div class="col-md-4">
                    <!-- <input type="text" class="form-control" id="liquidated_amount"> -->
                    <div class="input-group m-b-10">
                        <span class="input-group-addon project_currency"></span>
                        <input class="form-control" type="number" min="1" id="liquidated_amount">
                    </div>
                </div>
                <label class="col-md-2" style="padding-top: 5px;">/ Day</label>
            </div>
            <div class="clearfix"></div>
            <input type="hidden" name="signatory_counter" id="signatory_counter" value="2">
            <div class="form-group col-md-12"><span class="label label-inverse"><b>Please fill out the fields below for Docusign integration.</b></span></div>
            <div class="clearfix"></div>  
            <div id="signatory_container">
                <div class="sign1">
                    <div class="form-group col-md-6">
                        <label for="">Owner: Contact Name</label>
                        <input class="form-control" name="signatory_name[]" type="text" id="">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Owner: Contact Email</label>
                        <input class="form-control" name="signatory_email[]" type="text" id="">
                    </div>
                    <div class="form-group col-md-2" style="padding-top: 25px; display: none;">
                            <a class="btn btn-success add_signatory" counter="1">+</a>&nbsp;
                    </div>
                </div>
                <div class="sign2">
                    <div class="form-group col-md-6">
                        <label for="">Contractor: Contact Name</label>
                        <input class="form-control" name="signatory_name[]" type="text" id="">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Contractor: Contact Email</label>
                        <input class="form-control" name="signatory_email[]" type="text" id="">
                    </div>
                    <div class="form-group col-md-2" style="padding-top: 25px; display: none;">
                            <a class="btn btn-success add_signatory" counter="1">+</a>&nbsp;
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group" style=" margin-top: 20px;">
              <!--   <div class="loading_data1" style="text-align: center; display: none;">
                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                </div> -->
                <!-- <button id="create_notice" class="btn btn-primary">Create Notice to Proceed</button><br/> -->

            </div>

        </div>
        </div>
        <div class="col-sm-6 nopadleft">
            <div class="notice_exist" style="display: none;">
                <div class="form-group col-md-12">
                    <label for="name_of_report" style="padding-top: 15px;">Upload Notice to Proceed <span class="text-danger">*</span></label>
                    <section class="panel upload_doc_panel_performance" id="upload_performance">
                        <div class="panel-body nopadleft">
                            <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                            <input type="hidden" name="document_path" value="/uploads/notice_proceed/">
                            </form>
                        </div>

                    </section>
                    <!-- <input type="hidden" name="upload_type" id="upload_type" value=""> -->
                    <input type="hidden" name="standard_doc_id" id="upload_doc_id" value="">
                    <input type="hidden" name="standard_upload" id="upload_doc_meta" value="notice_proceed">
                    <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                    <div class="clearfix"></div>
                </div><!-- upload_doc_panel_payment close -->
            </div><!-- contract_exist close -->
        </div>
        <div class="form-group col-md-12">





                    <input type="hidden" name="add_data_new" id="add_data_new" value="0">
<!--            <a data-href="{{ url('/dashboard/'.$project_id.'/notice_proceed') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
            <a href="{{ url('/dashboard/'.$project_id.'/notice_proceed') }}" class="btn btn-info sub-btn btn_back" onclick="return checkFormFilled('btn_back')">Back</a>
            <button type="submit" class="btn btn-info sub-btn submit_notice_add_form first_button create_notice" style="display: none;">Save</button>
            <!-- <button class="btn btn-info sub-btn submit_notice_add_form another_button create_notice" type="submit" style="display: none;">Add Revised Notice to Proceed</button> -->
            <a href="{{ url('/dashboard/'.$project_id.'/req_for_info_log') }}" class="btn btn-info sub-btn continue_button" onclick="return checkFormFilled('continue_button')">Next Screen</a>
            <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>

        </div>
    </div><!-- Row Close -->
</div><!-- Col 6 Close -->
<div class="clearfix"></div>

<div style="display: none;">
<!-- <div> -->
    <div id="pdf_content" style="width:100%;">
        <h1 style="color:green; text-align:center;">Notice to Proceed</h1>
        <p style="width:50%; float: left;"><strong>To:</strong> <span id="pdf_gen_contractor_name"></span><br/><span id="pdf_gen_contractor_address"></span></p>
        <p style="width:50%; float: left;"><strong>Date:</strong><span id="pdf_gen_ntp_date"> <?php //echo date("Y-m-d"); ?></span></p>
        <div style="clear: both;"></div>
        <hr/>
        <div style="clear: both;"></div>
<!--        <p style="width:30%; float: left;"><strong>Project Description:</strong></p>-->
        <p style="width:70%; float: left;">
            <strong>Project Name: </strong><span id="pdf_gen_project_name"></span><br/>
<!--            <strong>Murow|CM Ref No:</strong> (Insert Murow CM Ref No)<br/>-->
            <strong>Improvement Type:</strong> <span id="pdf_gen_project_type"></span>
        </p>
        <div style="clear: both;"></div>
        <hr/>
        <div style="clear: both;"></div>
        <p>Dear <span id="pdf_gen_contractor_name_1"></span></p>
        <p>In accordance with the contract documents for the above-referenced project, you are hereby notified to commence work on or before <strong><span id="pdf_gen_start_date"></span></strong>, and shall fully complete all of the work of said contract within <strong><span id="pdf_gen_working_days"></span></strong>  <span class="pdf_gen_working_days_type"></span> days thereafter.  The anticipated date of completion is therefore, <strong><span id="pdf_gen_working_days_1"></span></strong>.</p>
        <p>The contract provides for an assessment of the sum of <strong><span class="pdf_gen_project_currency"></span> <span id="pdf_gen_amount"></span></strong> as liquidated damages for each consecutive <span class="pdf_gen_working_days_type"></span> day after the established contract completion dates that the work remains incomplete. The Contractor shall furnish original copies of the above mentioned documents to both the OWNER and OWNER'S Construction Management representative.</p>
        <div style="clear: both;"></div>
        <div style="width:100%; float: right; text-align:right; ">
            <p style="margin: 0px;">By: ____________________________&nbsp;&nbsp;Title: _________________________</p>
<!--            <p style="font-style: italic;  margin: 0 25px 25px 0;">     Murow|CM Representative</p>-->
<!--            <p>Title: _________________________</p>-->
        </div>
        <div style="clear: both;"></div>
        <p><strong>ACCEPTANCE OF NOTICE:</strong></p>
        <p>Receipt of the above <strong>NOTICE TO PROCEED</strong> is hereby acknowledged by:</p>
        <p>Contractor: _____________________</p>
        <p>By: _____________________________</p>
        <p>Title: __________________________</p>
        <p>This ________ day of _____________, 20 _____</p>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.0.16/jspdf.plugin.autotable.js"></script> -->
<script src="{{ url('/resources/assets/js/FileSaver.js') }}"></script>
<script src="{{ url('/resources/assets/dist/notice_proceed_add.js') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone.js') }}"></script>

<script type="text/javascript">

$('#upload_notice').click(function(){
    $('.notice_exist').show();
    // $('#create_notice').hide();
    $('.submit_notice_add_form').show();
});

//$('#create_notice').click(function() {
//    $('.notice_exist').hide();
//    // $('#upload_notice').hide();
//    $('.submit_notice_add_form').show();
//});

// $(function () {
//     $('#notice_date').datetimepicker();
//     $('#notice_start_date').datetimepicker({
//         useCurrent: false //Important! See issue #1075
//     });
//     $("#notice_date").on("dp.change", function (e) {
//         $('#notice_start_date').data("DateTimePicker").minDate(e.date);
//     });
//     $("#notice_start_date").on("dp.change", function (e) {
//         $('#notice_date').data("DateTimePicker").maxDate(e.date);
//     });
// });


$("input[name='check_award_type']").click(function(){
    if($('input:radio[name=check_award_type]:checked').val() == "exist"){

        $('.notice_exist').show();

        $("#submit_new_btn").show();
        $('.newone').hide();
        $('.submit_notice_add_form').show();
        $('.another_button').hide();

    }
    else {
        $('.newone').show();
        $('.submit_notice_add_form').hide();
        $('.notice_exist').hide();
        $('.first_button').show();



    }
});


$(function () {
        $('#datetimepicker6').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: false
        });
        $('#datetimepicker7').datetimepicker({
            useCurrent: false,
            format: 'YYYY-MM-DD'
        });
        $("#datetimepicker6").on("dp.change", function (e) {
            $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
        });
        $("#datetimepicker7").on("dp.change", function (e) {
            $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
        });
    });



</script>
@include('include/footer')
