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
                <h3 class="m-b-less">Add Notice of Award</h3>
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
        <div class="form-group col-md-12">
            <label for="company_name"></label>
        </div>

        <div class="col-md-12 check_award_type" style="margin-bottom:50px;">
            <label for="standard_link">Would you like to create a notice of award or has one already been created?</label><br/>
            <label class="radio-inline">
              <input type="radio" name="check_award_type" id="upload" value="new"> Create Notice of Award
            </label><br/>
            <label class="radio-inline">
              <input type="radio" name="check_award_type" id="upload" value="exist"> Already have Notice of Award
            </label>
        </div>

        <div class="notice_award_new" style="display:none;">
            <header class="panel-heading">Create Notice of Award</header>

            <div class="form-group col-md-6">
<!--                <label for="project_type">Project Improvement Type</label><br/>-->
                <div class="loading_data" style="text-align: center;">
                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                </div>
                <span class="project_type"></span><br/>
                <span class="sub-title">Select improvement type if you want to change</span>
                <button class="label label-warning add-impvtypes" style="margin-bottom: 5px;">Add New Improvement Type</button>
                <div class="loading_data" style="text-align: center;">
                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                </div>
                <select class="form-control project_type_dropdown" name="notice_award_project_type_dropdown" id="project_type_dropdown_new" placeholder="Select Improvement Types">
                </select>
            </div>

            <div class="form-group col-md-6">
                <label for="company_name" style="padding-top: 15px;">Contractor’s Name</label>
                <div class="loading_data" style="text-align: center;">
                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                </div>
                <!-- <select class="form-control select2" id="company_name"> -->
                <select class="form-control" name="notice_award_company_name" id="company_name">
                </select>
            </div>
            <div class="clearfix"></div>
            <div class="form-group col-md-6">
                <label for="name_of_report">Bid/Award Amount</label>
                <div class="loading_data" style="text-align: center;">
                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                </div>
                <!-- <input type="text" class="form-control" id="bid_amount"> -->
                <div class="input-group m-b-10">
                    <span class="input-group-addon project_currency"></span>
                    <input class="form-control" name="notice_award_bid_amount" type="text" id="bid_amount" onkeypress="return isNumber(event)">
                </div>
            </div>
            
            <div class="form-group col-md-6">
                <label>Notice of Award Date</label>
                <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                    <input type="text" name="notice_award_date" readonly="" value="<?php echo date("Y-m-d"); ?>" size="16" class="form-control"  id="award_date">
                    <span class="input-group-btn add-on">
                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>
            </div>
            <div class="clearfix"></div>
            <input type="hidden" name="signatory_counter" id="signatory_counter" value="1">
            <div id="signatory_container">
                <div class="sign1">
                    <div class="form-group col-md-5">
                        <label for="">Contractor Name</label>
                        <input class="form-control" name="signatory_name[]" type="text" id="">
                    </div>
                    <div class="form-group col-md-5">
                        <label for="">Contractor Email</label>
                        <input class="form-control" name="signatory_email[]" type="text" id="">
                    </div>
                    <div class="form-group col-md-2" style="padding-top: 25px; display: none;">
                            <a class="btn btn-success add_signatory" counter="1">+</a>&nbsp;
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group col-md-12">
<!--                <a data-href="{{ url('/dashboard/'.$project_id.'/notice_award') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                <a href="{{ url('/dashboard/'.$project_id.'/notice_award') }}" class="btn btn-info sub-btn btn_back" onclick="return checkFormFilled('btn_back')">Back</a>
                <button type="submit" id="submit_new_btn" class="btn btn-info sub-btn submit_notice_add_form">Save</button>
                <button  class="btn btn-info sub-btn" id="cmd">Save</button>
<!--                <a data-href="{{ url('/dashboard/'.$project_id.'/contract') }}" class="btn btn-info sub-btn continue_button" data-toggle="modal" data-target="#confirm-continue">Next Screen</a>-->
                    <a href="{{ url('/dashboard/'.$project_id.'/contract') }}" class="btn btn-info sub-btn continue_button" onclick="return checkFormFilled('continue_button')">Next Screen</a>
                <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
            </div>
            <div class="clearfix"></div>
        </div><!-- notice_award_new close -->

        <div class="notice_award_exist" style="display: none;">
            <header class="panel-heading">Already have Notice of Award</header>
            <div class="form-group col-md-6">
<!--                <label for="project_type">Project Improvement Type</label><br/>-->
                <div class="loading_data" style="text-align: center;">
                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                </div>
                <span class="project_type"></span><br/>
                <span class="sub-title">Select improvement type if you want to change</span>
                <button class="label label-warning add-impvtypes" style="margin-bottom: 5px;">Add New Improvement Type</button>
                <div class="loading_data" style="text-align: center;">
                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                </div>
                <select name="notice_award_improvement_type" class="form-control project_type_dropdown" id="project_type_dropdown_old" placeholder="Select Improvement Types">
                </select>
            </div>

            <div class="form-group col-md-6">
                <label for="name_of_report" style="padding-top: 15px;">Upload Notice of Award <span class="text-danger">*</span></label>
                <section class="panel upload_doc_panel_performance" id="upload_performance">
                    <div class="panel-body" style="padding: 0px;">
                        <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                            <input type="hidden" name="document_path" value="/uploads/notice_award/">
                        </form>
                    </div>
                </section>
                <!-- <input type="hidden" name="upload_type" id="upload_type" value=""> -->
                <input type="hidden" name="standard_doc_id" id="upload_doc_id" value="">
                <input type="hidden" name="standard_upload" id="upload_doc_meta" value="notice_award">
                <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                <div class="clearfix"></div>
            </div><!-- upload_doc_panel_payment close -->
            <div class="form-group col-md-12">


<!--                <a data-href="{{ url('/dashboard/'.$project_id.'/notice_award') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                <a href="{{ url('/dashboard/'.$project_id.'/notice_award') }}" class="btn btn-info sub-btn btn_back" onclick="return checkFormFilled('btn_back')">Back</a>
                <button type="submit" class="btn btn-info sub-btn submit_notice_add_form">Save</button>
<!--                <a data-href="{{ url('/dashboard/'.$project_id.'/contract') }}" class="btn btn-info sub-btn continue_button" data-toggle="modal" data-target="#confirm-continue">Next Screen</a>-->
                <a href="{{ url('/dashboard/'.$project_id.'/contract') }}" class="btn btn-info sub-btn continue_button" onclick="return checkFormFilled('continue_button')">Next Screen</a>
                <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
            </div>
        </div><!-- notice_award_exist close -->
    </div><!-- Row Close -->
</div><!-- Col 6 Close -->
<div class="clearfix"></div>

<div style="display: none;">
<!-- <div> -->
    <div id="notice_award_pdf_content" style="width:100%;">
        <h1 style="color:green; text-align:center;">NOTICE OF AWARD</h1>
        <p style="width:50%; float: left;">
        <strong>To:</strong> <span id="pdf_gen_contractor_name"></span><br/>
            <span id="pdf_gen_contractor_address"></span></p>
        <p style="width:50%; float: right;"><strong>Date:</strong><span id="pdf_gen_noa_date"><?php echo date("Y-m-d"); ?></span></p>
        <div style="clear: both;"></div>
<!--        <p style="width:30%; float: left;"><strong>Project Description:</strong></p>-->
        <p style="width:70%; float: left;">
        <strong>Project Name: </strong><span id="pdf_gen_project_name"></span><br/>
        <strong>Improvement Type:</strong> <span id="pdf_gen_project_type"></span><br/>
<!--        <strong>Murow|CM Ref No:</strong> (Insert Murow CM Ref No)-->
        </p>
        <div style="clear: both;"></div>
        <p style="width:100%;">The OWNER has considered the bid submitted by you for the above described WORK in response to its Bid Advertisement, dated <strong><span id="pdf_gen_bid_advertisement_date"></span></strong>. You are hereby notified that your submitted bid has been accepted for items in the amount of <strong><span class="pdf_gen_project_currency"></span><span id="pdf_gen_contract_amount"></span></strong>.</p>
        <p>You are hereby required by the Information for Bidders to execute the Agreement and furnish the required Contractor’s Performance Bond, Payment Bond and Certificate of Insurance within seven (7) calendar days from the contract date. The Contractor shall furnish original copies of the above mentioned documents to both the OWNER and OWNER’S Construction Management representative.</p>
        <p>If you fail to execute said Agreement and to furnish said bonds as well as certificates of insurance within seven (7) calendar days from the date of this Notice, said OWNER will be entitled to consider all your rights arising out of the OWNER’s acceptance of your bid as abandoned and as a forfeiture of your bid bond. The OWNER will be entitled to such other rights as may be granted by law.</p>
        <br/>
        <p style="width:100%;"><strong>ACCEPTANCE OF NOTICE:</strong></p>
        <p style="width:100%;">Receipt of the above <strong>NOTICE OF AWARD</strong> is hereby acknowledged.</p>
        <p style="width:100%;">By:    __________________________ </p>
        <p style="width:100%;">Title: __________________________ </p>
        <p style="width:100%;">Date:  __________________________ </p>
        <div id="editor"></div>
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
<script src="{{ url('/resources/assets/dist/notice_award_add.js') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone.js') }}"></script>

<script type="text/javascript">

$("input[name='check_award_type']").click(function(){
    if($('input:radio[name=check_award_type]:checked').val() == "exist"){
        console.log('exist');
        $('.notice_award_exist').show();
        $('.notice_award_new').hide();
    }
    else {
        console.log('new');
        $('.notice_award_new').show();
        $('.notice_award_exist').hide();
    }
});
</script>
@include('include/footer')
