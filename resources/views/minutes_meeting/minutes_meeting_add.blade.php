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
                <h3 class="m-b-less">Add Meeting Minutes</h3>
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
                                <div id="alert_message"></div>
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

        <div class="col-md-6 nopadleft">
            <div class="form-group col-md-12">
                <label for="name_of_report" style="padding-top: 10px;">Date of Preconstruction Meeting</label>
                <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                    <input type="text" readonly="" value="<?php //echo date('Y-m-d'); ?>" size="16" class="form-control"  id="meeting_date">
                    <span class="input-group-btn add-on"><button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button></span>
                </div>
            </div>

            <div class="form-group col-md-12">
                <label>Project Description</label>
                <input type="text" class="form-control" id="project_description">
            </div>

            <!-- <div class="col-md-12" style="margin:25px 0px;">
                <label>Would you like to create a minutes of meeting or has one already been created?</label><br/>
                <label class="radio-inline">
                  <input type="radio" name="minute_type" id="upload" value="new"> Create Minutes of Meeting
                </label><br/>
                <label class="radio-inline">
                  <input type="radio" name="minute_type" id="upload" value="exist"> Upload Minutes of Meeting
                </label>
            </div> -->
            <div class="col-md-12 form-group ">
                <div class="minutes_new" style="display: none;">
                    <textarea id="minutes_new" class="form-control" rows="10"></textarea>
                </div>

                <div class="minutes_already">
                    <label for="name_of_report" style="padding-top: 15px;">Upload Meeting Minutes <span class="text-danger">*</span></label>
                    <section class="panel upload_doc_panel" id="upload_div">
                        <div class="panel-body">
                            <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                            <input type="hidden" name="document_path" value="/uploads/minutes_meeting/">
                            </form>
                        </div>
                    </section>
                    <input type="hidden" name="upload_type" id="upload_type" value="">
                    <input type="hidden" name="standard_doc_id" id="upload_doc_id_general" value="">
                    <input type="hidden" name="standard_upload" id="upload_doc_meta" value="minutes_meeting">
                    <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                    <div class="clearfix"></div>
                </div>
            </div><!-- minute meeting close -->

           <!--  <div class="col-md-12" style="margin:25px 0px;">
                <label>Would you like to create a sign-in sheet or has one already been created?</label><br/>
                <label class="radio-inline">
                  <input type="radio" name="signinsheet_type" id="upload" value="new"> Create Sign-In Sheet
                </label><br/>
                <label class="radio-inline">
                  <input type="radio" name="signinsheet_type" id="upload" value="exist"> Upload Sign-In Sheet
                </label>
            </div> -->
            <div class="col-md-12 form-group ">
                <div class="signinsheet_new" style="display: none;">
                    <textarea id="signinsheet_new" class="form-control" rows="10"></textarea>
                </div>

                <div class="signinsheet_already">
                    <label for="name_of_report" style="padding-top: 15px;">Upload Sign-in Sheet <span class="text-danger">*</span></label>
                    <section class="panel upload_doc_panel" id="upload_div">
                        <div class="panel-body">
                            <form id="my-awesome-dropzone1" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                            <input type="hidden" name="document_path" value="/uploads/minutes_meeting/">
                            </form>
                        </div>
                    </section>
                    <input type="hidden" name="upload_type" id="upload_type" value="">
                    <input type="hidden" name="standard_doc_id" id="upload_doc_id_work" value="">
                    <input type="hidden" name="standard_upload" id="upload_doc_meta" value="minutes_meeting">
                    <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                    <div class="clearfix"></div>
                </div>
            </div><!-- minute meeting close -->


           <!--  <div class="col-md-12" style="margin:25px 0px;">

                <label class="radio-inline">
                  <input type="radio" name="agenda_type" id="upload" value="exist"> Upload Agenda Sheet
                </label>
            </div> -->
            <div class="col-md-12 form-group ">
                <div class="agenda_type_new" style="display: none;">
                    <textarea id="agenda_new" class="form-control" rows="10"></textarea>
                </div>

                <div class="agenda_type_already">
                    <label for="name_of_report" style="padding-top: 15px;">Upload Agenda Sheet <span class="text-danger">*</span></label>
                    <section class="panel upload_doc_panel" id="upload_div">
                        <div class="panel-body">
                            <form id="my-awesome-dropzone2" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                            <input type="hidden" name="document_path" value="/uploads/minutes_meeting/">
                            </form>
                        </div>
                    </section>
                    <input type="hidden" name="upload_type" id="upload_type" value="">
                    <input type="hidden" name="standard_doc_id" id="upload_doc_id_auto" value="">
                    <input type="hidden" name="standard_upload" id="upload_doc_meta" value="minutes_meeting">
                    <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                    <div class="clearfix"></div>
                </div>
            </div><!-- minute meeting close -->

        </div>

        <div class="col-sm-6">
            <div id="special_considerations_panel">
                <div class="input_fields_wrap">
                    <div class="form-group col-md-12 append">
                        <label>Special Considerations  <span class="text-danger">*</span></label>
                        <a href="#" class="add_field_button btn btn-success m-b-10 pull-right btn-xs"><i class="fa fa-plus"></i>
                            Add Another</a>
                        <textarea id="special_considerations" class="form-control special_considerations" name="special_considerations[]"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group col-md-12">
            <div class="loading_data1" style="text-align: center; display: none;">
               <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
            </div>
            <!-- <a class="btn btn-primary" id="create_agenda">Create Agenda</a> -->
            <!-- <input type="text" id="upload_agenda_id" value=""> -->
           <!--  <a class="btn btn-primary" id="create_signin_sheet">Create Sign-In Sheet</a>
            <input type="hidden" id="upload_signin_sheet_id" value=""> -->
        </div>


        <div class="form-group col-md-12">
<!--            <a data-href="{{ url('/dashboard/'.$project_id.'/minutes_meeting') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
            <a href="{{ url('/dashboard/'.$project_id.'/minutes_meeting') }}" class="btn btn-info sub-btn btn_back" onclick="return checkFormFilled('btn_back')">Back</a>
            <button type="submit" class="btn btn-info sub-btn submit_notice_add_form first_button">Save</button>
            <!-- <button type="submit" class="btn btn-info sub-btn submit_notice_add_form another_button" style="display: none;">Save Another</button> -->
            <a href="{{ url('/dashboard/'.$project_id.'/labor_compliance') }}" class="btn btn-info sub-btn continue_button" onclick="return checkFormFilled('continue_button')">Next Screen</a>
            <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>

        </div>
    </div><!-- Row Close -->
</div><!-- Col 6 Close -->
<div class="clearfix"></div>

<div id="pdf_content_agenda" style="width:100%; display: none;">
        <h2>Agenda</h2>
        <p><strong>Date:</strong> <?php echo date("Y-m-d"); ?></p>
        <p><strong>Company Name:</strong> <span class="pdf_gen_contractor_name"></span></p>
        <p><strong>Company Address:</strong> <span class="pdf_gen_contractor_address"></span></p>
        <p><strong>Project Name: </strong><span class="pdf_gen_project_name"></span></p>
        <p><strong>Project Description: </strong><span class="pdf_gen_project_description"></span></p>
        <p><strong>Improvement Type:</strong> <span class="pdf_gen_project_type"></span></p>
        <p><strong>Contract Amount:</strong> <span class="pdf_gen_contract_amount"></span></p>
        <p><strong>Date of Preconstruction Meeting:</strong> <span class="pdf_gen_date_meeting"></span></p>
        <p><strong>Special Considerations:</strong> <span class="pdf_gen_special_considerations"></span></p>
</div>

<div id="pdf_content_signin" style="width:100%; display: none;">
        <h2>Sign-In Sheet</h2>
        <p><strong>Date:</strong> <?php echo date("Y-m-d"); ?></p>
        <p><strong>Company Name:</strong> <span class="pdf_gen_contractor_name"></span></p>
        <p><strong>Company Address:</strong> <span class="pdf_gen_contractor_address"></span></p>
        <p><strong>Project Name: </strong><span class="pdf_gen_project_name"></span></p>
        <p><strong>Project Description: </strong><span class="pdf_gen_project_description"></span></p>
        <p><strong>Improvement Type:</strong> <span class="pdf_gen_project_type"></span></p>
        <p><strong>Contract Amount:</strong> <span class="pdf_gen_contract_amount"></span></p>
        <p><strong>Sign-In Sheet:</strong> <span class="pdf_gen_signin_text"></span></p>
        <p><strong>Date of Preconstruction Meeting:</strong> <span class="pdf_gen_date_meeting"></span></p>
        <p><strong>Special Considerations:</strong> <span class="pdf_gen_special_considerations"></span></p>
</div>

<!-- <div class="wrapper meeting-sheet" style="width:100%; display: none !important;">
    <div class="col-md-12 text-center nopadleft">
        <img src="{{ url('/resources/assets/img/pdf-logo.jpg') }}" alt="*" >
        <h4>MEETING ATTENDANCE RECORD</h4>
    </div>
    <div class="col-md-6 nopadleft">
        <p><span>Project:</span></p>
        <p><span>Purpose:</span></p>
        <p><span>Location:</span></p>
    </div>
    <div class="col-md-6 nopadleft">
        <p><span>Project No:</span></p>
        <p><span>Date:</span></p>
        <p><span>Time:</span></p>
    </div>

    <div class="tab-parent" style="overflow-x:scroll;" style="width:100%; display: none;">
        <table class="table custom-grid">
            <thead>
                <tr>
                    <th>Initials</th>
                    <th>Name</th>
                    <th>Organization / Address</th>
                    <th>Contact Information</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>test</td>
                    <td>test project</td>
                    <td>123456789</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>test</td>
                    <td>test project</td>
                    <td>123456789</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>test</td>
                    <td>test project</td>
                    <td>123456789</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>test</td>
                    <td>test project</td>
                    <td>123456789</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>test</td>
                    <td>test project</td>
                    <td>123456789</td>
                </tr>
            </tbody>
        </table>
    </div>
</div> -->


<div id="pdf_content_minutes" style="width:100%; display: none;">
        <h2>Minutes of Meeting</h2>
        <p><strong>Date:</strong> <?php echo date("Y-m-d"); ?></p>
        <p><strong>Company Name:</strong> <span class="pdf_gen_contractor_name"></span></p>
        <p><strong>Company Address:</strong> <span class="pdf_gen_contractor_address"></span></p>
        <p><strong>Project Name: </strong><span class="pdf_gen_project_name"></span></p>
        <p><strong>Project Description: </strong><span class="pdf_gen_project_description"></span></p>
        <p><strong>Improvement Type:</strong> <span class="pdf_gen_project_type"></span></p>
        <p><strong>Contract Amount:</strong> <span class="pdf_gen_contract_amount"></span></p>
        <p><strong>Minutes of Meeting:</strong> <span class="pdf_gen_min_of_meeting_text"></span></p>
        <p><strong>Date of Preconstruction Meeting:</strong> <span class="pdf_gen_date_meeting"></span></p>
        <p><strong>Special Considerations:</strong> <span class="pdf_gen_special_considerations"></span></p>
</div>

<div class="col-md-12 pdf-view" style="width:100%; display: none;">
    <div class="pdf-logo text-center">
        <img src="{{ url('/resources/assets/img/pdf-logo.jpg') }}" alt="*" >
    </div>
    <div class="pdf-head">
        <h2 class="text-center">(Project Name)<span>PROJECT COORDINATION MEETING AGENDA</span></h2>
        <ul>
            <li>Location: <p></p></li>
            <li>Date & Time: <p></p></li>
        </ul>
    </div>
    <div class="col-md-12 nopadleft point-list cmp-name">
        <h3>Attendees:</h3>
        <ul>
            <li>Name | Company</li>
            <li>Name | Company</li>
            <li>Name | Company</li>
        </ul>
    </div>

    <div class="col-md-12 nopadleft point-list">
        <h3 class="text-center">MEETING AGENDA:</h3>
        <ul>
            <li><h4>1) (Subject)</h4></li>
            <li>(insert)</li>
            <li>(insert)</li>
            <li>(insert)</li>
        </ul>

        <ul>
            <li><h4>2) (Subject)</h4></li>
            <li>(insert)</li>
            <li>(insert)</li>
            <li>(insert)</li>
        </ul>

        <ul>
            <li><h4>3) (Subject)</h4></li>
            <li>(insert)</li>
            <li>(insert)</li>
            <li>(insert)</li>
        </ul>
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
<script src="{{ url('/resources/assets/dist/minutes_meeting_add.js') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone.js') }}"></script>

<script type="text/javascript">

$("input[name='minute_type']").click(function(){
    if($('input:radio[name=minute_type]:checked').val() == "exist"){
        console.log('exist');
        $('.minutes_already').show();
        $('.minutes_new').hide();
    }
    else {
        console.log('new');
        $('.minutes_new').show();
        $('.minutes_already').hide();
    }
});

$("input[name='signinsheet_type']").click(function(){
    if($('input:radio[name=signinsheet_type]:checked').val() == "exist"){
        console.log('exist');
        $('.signinsheet_already').show();
        $('.signinsheet_new').hide();
    }
    else {
        console.log('new');
        $('.signinsheet_new').show();
        $('.signinsheet_already').hide();
    }
});

$("input[name='agenda_type']").click(function(){
    if($('input:radio[name=agenda_type]:checked').val() == "exist"){
        console.log('exist');
        $('.agenda_type_already').show();
        // $('.signinsheet_new').hide();
    }
//     else {
//         console.log('new');
//         $('.signinsheet_new').show();
//         $('.signinsheet_already').hide();
//     }
});

$("#my-awesome-dropzone").click(function() {
    console.log("certificate_general_libility");
  $("#upload_type").val("certificate_general_libility");
});
$("#my-awesome-dropzone").on("drop", function(event) {
    event.preventDefault();  
    event.stopPropagation();
    $("#upload_type").val("certificate_general_libility");
});
$("#my-awesome-dropzone1").click(function() {
  $("#upload_type").val("certificate_work_compensation");
    console.log("certificate_work_compensation");
});
$("#my-awesome-dropzone1").on("drop", function(event) {
    event.preventDefault();  
    event.stopPropagation();
    $("#upload_type").val("certificate_work_compensation");
});
$("#my-awesome-dropzone2").click(function() {
    console.log("certificate_auto_liability");
  $("#upload_type").val("certificate_auto_liability");
});
$("#my-awesome-dropzone2").on("drop", function(event) {
    event.preventDefault();  
    event.stopPropagation();
    $("#upload_type").val("certificate_auto_liability");
});
$('#upload_notice').click(function(){
    $('.notice_exist').show();
    // $('#create_notice').hide();
    $('.submit_notice_add_form').show();
});

$('#create_notice').click(function() {
    $('.notice_exist').hide();
    // $('#upload_notice').hide();
    $('.submit_notice_add_form').show();
});
</script>
@include('include/footer')
