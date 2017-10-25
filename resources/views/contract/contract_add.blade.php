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
                <h3 class="m-b-less">Add Contract</h3>
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
                            <div id="alert_message" style="display:none;"></div>
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
                                <!-- <div id="upload_success">
                                    <div id="toast-container" class="toast-top-right" aria-live="polite" role="alert">
                                        <div class="toast toast-success">
                                            <div class="toast-title">Success</div>
                                            <div class="toast-message">Document uploaded</div>
                                        </div>
                                    </div>
                                </div> -->

<div class="col-md-6">
    <div class="row">
        <div class="form-group col-md-12">
            <label for="company_name"></label>
        </div>

       <!--  <div class="col-md-12 check_contract_type" style="margin-bottom:50px;">
            <label for="standard_link">Would you like to create a contract or has one already been created?</label><br/>
            <label class="radio-inline">
              <input type="radio" name="check_contract_type" id="upload" value="new"> Create Contract
            </label><br/>
            <label class="radio-inline">
              <input type="radio" name="check_contract_type" id="upload" value="exist"> Already have Contract
            </label>
        </div>

        <div class="contract_new" style="display: none;">
            <header class="panel-heading">Create Contract</header>
            <div class="form-group col-md-12">
                <label for="company_name" style="padding-top: 15px;">Contractor’s Name</label>
                <div class="loading_data" style="text-align: center;">
                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                </div>

                <select class="form-control" id="company_name">
                </select>
            </div>

            <div class="form-group col-md-12">
                <label for="name_of_report">Bid/Contract Amount</label>
                <div class="loading_data" style="text-align: center;">
                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                </div>
                <input type="text" class="form-control" id="bid_amount">
            </div>
            <div class="clearfix"></div>
            <div class="form-group col-md-12">
                <label>Contract Date</label>
                <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date="2016-09-01"  class="input-append date dpYears">
                    <input type="text" readonly="" value="<?php echo date("Y-m-d"); ?>" size="16" class="form-control"  id="award_date">
                    <span class="input-group-btn add-on">
                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>
            </div>
            <div class="form-group col-md-12">
                <div class="loading_data1" style="text-align: center; display: none" >
                    <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                </div>
                <button type="submit" id="submit_new_btn" class="btn btn-info sub-btn submit_notice_add_form">Submit</button>
                <button  class="btn btn-info sub-btn" id="cmd">Create Contract</button>
            </div>
            <div class="clearfix"></div>
        </div> -->

        <div class="contract_exist">
            <header class="panel-heading hidden">Already have Contract</header>

            <div class="form-group col-md-12">
                <label>Contract Date</label>
                <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                    <input type="text" readonly="" value="<?php echo date("Y-m-d"); ?>" size="16" class="form-control"  id="award_date">
                    <span class="input-group-btn add-on">
                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>
            </div>

            <div class="form-group col-md-12">
                <input type="hidden" class="form-control" id="company_name">
                <input type="hidden" class="form-control" id="bid_amount">
                <!-- <input type="hidden" value="<?php echo date("Y-m-d"); ?>" class="form-control"  id="award_date"> -->

                <label for="name_of_report" style="padding-top: 15px;">Upload Contract <span class="text-danger">*</span></label>
                <section class="panel upload_doc_panel_performance" id="upload_performance">
                    <div class="panel-body" style="padding:0px;">
                        <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                            <input type="hidden" name="document_path" value="/uploads/contract/">
                        </form>
                    </div>
                </section>
                <!-- <input type="text" name="upload_type" id="upload_type" value=""> -->
                <input type="hidden" name="standard_doc_id" id="upload_doc_id" value="">
                <input type="hidden" name="standard_upload" id="upload_doc_meta" value="contract">
                <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                <div class="clearfix"></div>
            </div><!-- upload_doc_panel_payment close -->
            <div class="form-group col-md-12">
                <a data-href="{{ url('/dashboard/'.$project_id.'/contract') }}" class="btn btn-info back_button" data-toggle="modal" data-target="#confirm-back">Back</a>
                <button type="submit" class="submit_notice_add_form first_button btn btn-info no-mar">Save</button>
                <!-- <button type="submit" class="submit_notice_add_form another_button btn btn-info no-mar" style="display: none;">Save Another</button> -->
                <a data-href="{{ url('/dashboard/'.$project_id.'/contract_item') }}" class="btn btn-info continue_button" data-toggle="modal" data-target="#confirm-continue">Next Screen</a>
                <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
            </div>
        </div><!-- contract_exist close -->
    </div><!-- Row Close -->
</div><!-- Col 6 Close -->
<div class="clearfix"></div>

<div style="display: none;">
    <div id="notice_award_pdf_content" style="width:100%;">
        <h1 style="color:green; text-align:center;">CONTRACT</h1>
        <ul>
            <li>
                <p><strong>Project:</strong> <span id="pdf_gen_project_name"></span></p>
            </li>
            <li>
                <p><strong>Contractor:</strong> <span id="pdf_gen_contractor_name"></span></p>
            </li>
            <li>
                <p><strong>Contract Amount:</strong> <span id="pdf_gen_contract_amount"></span></p>
            </li>
            <li>
                <p><strong>Date of Contract:</strong> <span><?php echo date("Y-m-d"); ?></span></p>
            </li>
        </ul>

        <p style="color: #1c86e0; font-weight: 900; font-style: italic; text-decoration: underline; margin-top: 300px;">SPECIAL CIRCUMSTANCE(S):</p>
        <ul>
            <li>(na)</li>
        </ul>
        <hr/>
        <p><strong>Definition:</strong> A <strong>Contract</strong> is a written agreement between the Owner and Contractor that is intended to be enforceable by law, for said improvement of work.</p>
        <p><strong>Purpose:</strong> The purpose of a <strong>Contract</strong> is to (1) allocate the duties between the parties, (2) recognize and allocate the risk to the different parties, and (3) reduce the uncertainty surrounding the project which avoids costly corrections and allow the parties to plan for the project and the future.</p>
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
<script src="{{ url('/resources/assets/dist/contract_add.js') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone.js') }}"></script>

<script type="text/javascript">

$("input[name='check_contract_type']").click(function(){
    if($('input:radio[name=check_contract_type]:checked').val() == "exist"){
        console.log('exist');
        $('.contract_exist').show();
        $('.contract_new').hide();
    }
    else {
        console.log('new');
        $('.contract_new').show();
        $('.contract_exist').hide();
    }
});
</script>
@include('include/footer')
