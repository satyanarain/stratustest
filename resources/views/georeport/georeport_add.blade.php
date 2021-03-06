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
                <h3 class="m-b-less">Add Geotechnical Report</h3>
                <?php $project_id = Request::segment(2); ?>
                <div class="state-information" style="width: 200px; ">
                    <!-- <div class="progress progress-striped active progress-sm m-b-20"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"><span class="sr-only">100% Complete</span></div></div> -->

                </div>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-lg-6">
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
                                    <!-- <div id="toast-container" class="toast-top-right" aria-live="polite" role="alert">
                                        <div class="toast toast-warning">
                                            <div class="toast-title">Warning</div>
                                            <div class="toast-message">Not providing a report is risky, please provide if you have it</div>
                                        </div>
                                    </div> -->
                                </div>
                                <div id="upload_success">
                               <!--      <div id="toast-container" class="toast-top-right" aria-live="polite" role="alert">
                                        <div class="toast toast-success">
                                            <div class="toast-title">Success</div>
                                            <div class="toast-message">Document uploaded</div>
                                        </div>
                                    </div> -->
                                </div>

                                <form role="form" id="add_georeport_form">
                                    <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Report Date  <span class="text-danger">*</span></label>
                                        <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                                <input type="text" readonly="" value="" size="16" class="form-control"  id="date_of_report">
                                                  <span class="input-group-btn add-on">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                  </span>
                                            </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="name_of_report">Report Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name_of_report">
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group col-md-12">
                                        <label for="company_name">Company Name</label>
                                        <div class="loading_data" style="text-align: center;">
                                           <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                        </div>
                                        <select class="form-control company_name" id="company_name">
                                        </select>
                                    </div>

                                    <div class=" col-md-12">
                                        <label for="standard_link" style="min-width: 200px;">Applicable</label>
                                        <label class="radio-inline">
                                            <input type="checkbox" name="applicable" id="applicable" value="yes"> 
                                        </label>
<!--                                        <label class="radio-inline">
                                          <input type="radio" name="applicable" id="applicable1" value="no"> No
                                        </label>-->
                                    </div>

                                    <div class=" col-md-12">
                                        <label for="standard_link" style="min-width: 200px;">Available</label>
                                        <label class="radio-inline">
                                            <input type="checkbox" name="upload" id="upload" value="yes"> 
                                        </label>
<!--                                        <label class="radio-inline">
                                          <input type="radio" name="upload" id="upload" value="no"> No
                                        </label>-->
                                    </div>

                                    <div class="form-group col-md-12">
                                        <input type="hidden" name="standard_upload" id="upload_doc_meta" value="geo_report">
                                        <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                                        <input type="hidden" name="standard_doc_id" id="upload_doc_id" value="">
                                    </div>

                                    <div class="form-group col-md-12">
<!--                                        <a data-href="{{ url('/dashboard/'.$project_id.'/geo_reports') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                        <a href="{{ url('/dashboard/'.$project_id.'/geo_reports') }}" class="btn btn-info sub-btn btn_back" onclick="return checkFormFilled('btn_back')">Back</a>
                                        <button type="submit" class="btn btn-info sub-btn first_button">Save</button>
                                        <button type="submit" class="another_button1 btn btn-info sub-btn" style="display: none;">Save Another</button>
                                        <a href="{{ url('/dashboard/'.$project_id.'/swppp') }}" class="btn btn-info sub-btn continue_button" onclick="return checkFormFilled('continue_button')">Next Screen</a>
                                        <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
                                    </div>

                                    </div>
                                </form>

                            </div>
                        </section>
                    </div>

                    <div class="col-lg-6">
                        <section class="panel upload_doc_panel">
                            <div class="panel-body">
                                <label for="name_of_report">Upload Geotechnical Report <span class="text-danger">*</span></label>
                                <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                    <input type="hidden" name="document_path" value="/uploads/geo_report/">
                                </form>
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

<script type="text/javascript">

$("input[name='upload']").click(function(){
        // alert($('input:radio[name=upload]:checked').val());
    if($('input:radio[name=upload]:checked').val() == "no"){
        $('.upload_doc_panel').css("display", "none");
        $('#upload_doc_id').removeAttr('value');
        $("#upload_warning").fadeIn(1000).fadeOut(7000);
        $(".remove_file_drop").trigger("click");
    }
    else {
        $('.upload_doc_panel').css("display", "block");
    }
});
</script>

<script src="{{ url('/resources/assets/dist/georeport_add.js') }}"></script>
@include('include/footer')
