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
                <h3 class="m-b-less">Add Bid Documents</h3>
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

<div class="col-md-12 bid_document_form">
    <!-- <form role="form" id="add_bond_form"> -->
        <div class="row">
            <div class="form-group col-md-6">
                <label for="project_type">Type of Improvement</label><br/>
                <div class="loading_data" style="text-align: center;">
                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                </div>
                <span id="project_type"></span><br/>
                <span class="sub-title">Select improvement types if you want to change</span>
                <button class="label label-warning add-impvtypes" style="margin-bottom: 5px;">Add New Improvement Type</button>
                <div class="loading_data" style="text-align: center;">
                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                </div>
                <select class="form-control select2-multiple" name="project_type_dropdown" id="project_type_dropdown" multiple placeholder="Select Improvement Types">
                    
                </select>
            </div>

            <div class="form-group col-md-6">
                <label for="company_name">Lead agency</label><br/>
                <div class="loading_data" style="text-align: center;">
                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                </div>
                <div id="company_name_lead" style="min-height:44px;"></div>
                <span class="sub-title">Select lead agency if you want to change</span>
                <select class="form-control" name="company_name" id="agency_name" style="margin-top:7px;">
                <!-- <select class="form-control" id="company_name"> -->
                </select>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <hr/>
                </div>
            </div>

            <div class="form-group col-md-6">
                <label>Bid Advertisement Date <span class="text-danger">*</span></label>
                <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears bid_advertisement_date">
                    <input type="text" readonly="" value="" size="16" class="form-control"  id="bid_advertisement_date">
                    <span class="input-group-btn add-on"><button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button></span>
                </div>
                <div class="form-group">
                    <label class="checkbox-custom check-success">
                        <input type="checkbox" value="no" id="bid_advertisement_yes">
                        <label for="bid_advertisement_yes">Not Applicable</label>
                    </label>
                </div>
            </div>

            <div class="form-group col-md-6">
<section class="panel upload_doc_panel_performance"  id="upload_performance">
    <div class="panel-body" style="padding: 0px;">
        <label>Advertisement for Bids <span class="text-danger">*</span></label><br/>
        <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
            <input type="hidden" name="document_path" value="/uploads/bid_document/">
        </form>
        <input type="hidden" name="standard_doc_id" id="upload_doc_id_adv_bid" value="">
    </div>
</section>
        <input type="hidden" name="upload_type" id="upload_type" value="">
        <input type="hidden" name="standard_upload" id="upload_doc_meta" value="bid_document">
        <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">


            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <hr/>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Notice Inviting Bids Date <span class="text-danger">*</span></label>
                    <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears notice_invite_bid_date">
                        <input type="text" readonly="" value="" size="16" class="form-control"  id="notice_invite_bid_date">
                        <span class="input-group-btn add-on"><button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="checkbox-custom check-success">
                        <input type="checkbox" value="no" id="notice_invite_bid_yes">
                        <label for="notice_invite_bid_yes">Not Applicable</label>
                    </label>
                </div>
            </div>

            <div class="form-group col-md-6">
                <label>Notice Inviting Bids <span class="text-danger">*</span></label><br/>
                <!-- <button data-toggle="button" class="btn btn-primary">
                    <i class="fa fa-cloud-upload"></i>
                    Upload Notice
                </button> -->
<section class="panel upload_doc_panel_payment" id="upload_payment">
    <div class="panel-body" style="padding: 0px;">
        <form id="my-awesome-dropzone1" action="{{ url('/document/uploadFiles') }}" class="dropzone">
            <input type="hidden" class="certificate_work_compensation" name="document_path" value="/uploads/bid_document/">
        </form>
        <input type="hidden" name="standard_doc_id" id="upload_doc_id_notice_invite" value="">
    </div>
</section>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <hr/>
                </div>
            </div>

            <div class="form-group col-md-6">
                    <label>Date of Bid Opening <span class="text-danger">*</span></label>
                    <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                        <input type="text" readonly="" value="" size="16" class="form-control"  id="date_of_bid_opening">
                        <span class="input-group-btn add-on"><button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button></span>
                    </div>
            </div>
            <div class="form-group col-md-6">
                <label>Detailed Bid Results by Item <span class="text-danger">*</span></label>
                <!-- <button data-toggle="button" class="btn btn-primary">
                    <i class="fa fa-cloud-upload"></i>
                    Upload Results
                </button> -->
<section class="panel upload_doc_panel_payment" id="upload_payment">
    <div class="panel-body" style="padding: 0px;">
        <form id="my-awesome-dropzone2" action="{{ url('/document/uploadFiles') }}" class="dropzone">
            <input type="hidden" class="certificate_work_compensation" name="document_path" value="/uploads/bid_document/">
        </form>
        <input type="hidden" name="standard_doc_id" id="upload_doc_id_bid_result" value="">
    </div>
</section>

            </div>
            <div class-="clearfix"></div>
            <div class="form-group">
                <div class="col-md-12">
                    <hr/>
                </div>
            </div>

          <!--   <div class="form-group col-md-6">
            </div>

            <div class="form-group col-md-6">
            </div>

             <div class="form-group">
                <div class="col-md-12">
                    <hr/>
                </div>
            </div> -->

            <div class="col-sm-6" style="padding:0px;">
                <label for="project_latitude">Low Bidder’s Name</label>
                <div class="loading_data" style="text-align: center;">
                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                </div>
                <select class="form-control" name="company_name_two" id="company_name_two">
                </select>
            </div>

            <div class="form-group col-md-6">
                <label>Successful Bidder’s Proposal <span class="text-danger">*</span></label><br/>
                <!-- <button data-toggle="button" class="btn btn-primary">
                    <i class="fa fa-cloud-upload"></i>
                    Upload Proposal
                </button> -->
<section class="panel upload_doc_panel_payment" id="upload_payment">
    <div class="panel-body" style="padding: 0px;">
        <form id="my-awesome-dropzone3" action="{{ url('/document/uploadFiles') }}" class="dropzone">
            <input type="hidden" class="certificate_work_compensation" name="document_path" value="/uploads/bid_document/">
        </form>
        <input type="hidden" name="standard_doc_id" id="upload_doc_id_success_bidder" value="">
    </div>
</section>
            </div>

        <div class="form-group col-md-12">
<!--            <a data-href="{{ url('/dashboard/'.$project_id.'/bid_documents') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
            <a href="{{ url('/dashboard/'.$project_id.'/bid_documents') }}" class="btn btn-info sub-btn btn_back" onclick="return checkFormFilled('btn_back')">Back</a>
            <button type="submit" id="add_bond_form" class="btn btn-success addon-btn pull-right first_button"><i class="fa fa-forward pull-right"></i>Continue</button>
            <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
        </div>

                                        </div>
</div><!-- bid_document_form Close -->

                            </div>
                        </section>
                    </div>


                </div>
            </div>
            <!--body wrapper end-->
<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js?v=1.0') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js?v=1.0') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone.js?v=1.0') }}"></script>

<script type="text/javascript">




$("#my-awesome-dropzone").click(function() {
  $("#upload_type").val("upload_doc_id_adv_bid");
});
$("#my-awesome-dropzone").on("drop", function(event) {
    event.preventDefault();  
    event.stopPropagation();
    $("#upload_type").val("upload_doc_id_adv_bid");
});
$("#my-awesome-dropzone1").click(function() {
  $("#upload_type").val("upload_doc_id_notice_invite");
});
$("#my-awesome-dropzone1").on("drop", function(event) {
    event.preventDefault();  
    event.stopPropagation();
    $("#upload_type").val("upload_doc_id_notice_invite");
});
$("#my-awesome-dropzone2").click(function() {
  $("#upload_type").val("upload_doc_id_bid_result");
});
$("#my-awesome-dropzone2").on("drop", function(event) {
    event.preventDefault();  
    event.stopPropagation();
    $("#upload_type").val("upload_doc_id_bid_result");
});
$("#my-awesome-dropzone3").click(function() {
  $("#upload_type").val("upload_doc_id_success_bidder");
});
$("#my-awesome-dropzone3").on("drop", function(event) {
    event.preventDefault();  
    event.stopPropagation();
    $("#upload_type").val("upload_doc_id_success_bidder");
});

// $('#bid_advertisement_yes').change(function() {
//     if($(this).is(":checked")) {
//         $('.bid_advertisement_date').css("display", "none");
//     }
//     else {
//         $('.bid_advertisement_date').css("display", "block");
//     }
// });

// $('#notice_invite_bid_yes').change(function() {
//     if($(this).is(":checked")) {
//        $('.notice_invite_bid_date').css("display", "none");
//     }
//     else {
//         $('.notice_invite_bid_date').css("display", "block");
//     }
// });
</script>

<script src="{{ url('/resources/assets/dist/bid_document_add.js?v=1.0') }}"></script>
@include('include/footer')
