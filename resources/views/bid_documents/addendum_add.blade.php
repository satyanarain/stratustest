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
                <h3 class="m-b-less">Add New Addendum</h3>
                <?php $project_id = Request::segment(2); ?>
                <?php $bid_document_id = Request::segment(4); ?>
                <div class="state-information" style="width: 200px; ">
                    <!-- <div class="progress progress-striped active progress-sm m-b-20"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"><span class="sr-only">100% Complete</span></div></div> -->

                </div>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper">
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



<!-- <div class="col-md-12 add_addendum" style="display:none;"> -->
<div class="col-md-12 add_addendum">
    <div class="row">
         <div class="col-sm-6">
            <div class="form-group col-md-12">
                <input type="hidden" name="addendum_bid_id" id="addendum_bid_id" value="<?php echo $bid_document_id; ?>">
                <label for="name_of_report">Addendum Number</label>
                <input id="demo2"
               type="text"
               value="0"
               name="demo2"
               data-bts-min="0"
               data-bts-max="100"
               data-bts-init-val=""
               data-bts-step="1"
               data-bts-decimal="0"
               data-bts-step-interval="100"
               data-bts-force-step-divisibility="round"
               data-bts-step-interval-delay="500"
               data-bts-prefix=""
               data-bts-postfix=""
               data-bts-prefix-extra-class=""
               data-bts-postfix-extra-class=""
               data-bts-booster="true"
               data-bts-boostat="10"
               data-bts-max-boosted-step="false"
               data-bts-mousewheel="true"
               data-bts-button-down-class="btn btn-default"
               data-bts-button-up-class="btn btn-default"
                />
            </div>

            <div class="form-group col-md-12">
                <label>Addendum Date <span class="text-danger">*</span></label>
                <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                    <input type="text" readonly="" value="" size="16" class="form-control"  id="addendum_date">
                    <span class="input-group-btn add-on">
                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <section class="panel upload_doc_panel">
                <div class="panel-body">
                    <label>Upload Addendum <span class="text-danger">*</span></label>
                    <form id="my-awesome-dropzone4" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                        <input type="hidden" name="document_path" value="/uploads/bid_document/addendum/">
                    </form>
                </div>
            </section>
            <input type="hidden" name="standard_upload" id="upload_doc_meta" value="bid_document_addendum">
            <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
            <input type="hidden" name="standard_doc_id" id="upload_doc_id" value="">
        </div>

        <div class="clearfix"></div>

        <div class="form-group col-md-12">
            <a data-href="{{ url('/dashboard/'.$project_id.'/bid_documents') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>
            <button type="submit" id="add_addendum" class="btn btn-primary addon-btn pull-right"><i class="fa fa-plus pull-right"></i>Save and add another addendum</button>
            <a data-href="{{ url('/dashboard/'.$project_id.'/contract_item') }}" class="btn btn-info sub-btn continue_button" data-toggle="modal" data-target="#confirm-continue">Next Screen</a>
            <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
        </div>

    </div>
</div><!-- add_addendum close -->



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
</script>

<script src="{{ url('/resources/assets/dist/addendum_add.js?v=1.0') }}"></script>
@include('include/footer')
