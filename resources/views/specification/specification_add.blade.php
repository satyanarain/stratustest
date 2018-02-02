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
                <h3 class="m-b-less">Add Specifications</h3>
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
                                <div id="alert_message"></div>
                                <div class="col-md-6">
                                    <form role="form" id="add_standard_form">
                                        <div class="row">
                                        <div class="form-group">
                                            <label for="company_name">Entity name</label>
                                            <div class="loading_data" style="text-align: center;">
                                               <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                            </div>
                                            <select class="form-control company_name" id="company_name">
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="standard_name">Specifications Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="standard_name">
                                        </div>
                                        <div class="form-group">
                                            <label>Specifications Date <span class="text-danger">*</span></label>
                                             <!-- <input type="text" class="form-control" id="standard_date"> -->
                                             <input type="text" id="standard_date" placeholder="YYYY-MM" data-mask="9999-99" class="form-control">
                                             <!-- <input type="text" class="form-control" id="standard_date"> -->
                                             <!-- <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                                <input type="text" readonly="" value="<?php echo date("Y-m-d"); ?>" size="16" class="form-control"  id="standard_date">
                                                <span class="input-group-btn add-on">
                                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                </span>
                                            </div> -->
                                        </div>
                                        <div class="form-group">
                                            <label for="standard_link">Specifications Link</label>
                                            <input type="url" class="form-control" id="standard_link" placeholder="www.example.com">
                                        </div>

                                        <!-- <div class="form-group">
                                            <label for="standard_link">Applicable</label><br/>
                                            <label class="radio-inline">
                                              <input type="radio" name="applicable" id="applicable" value="yes"> Yes
                                            </label>
                                            <label class="radio-inline">
                                              <input type="radio" name="applicable" id="applicable" value="no"> No
                                            </label>
                                        </div> -->

                                        <div class="form-group" style="display: none;">
                                            <label class="checkbox-custom check-success">
                                                <input type="checkbox" value="" id="applicable">
                                                <label for="applicable">NA</label>
                                            </label>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <input type="hidden" name="standard_upload" id="upload_doc_meta" value="specification">
                                            <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                                            <input type="hidden" name="standard_doc_id" id="upload_doc_id">
                                        </div>

                                        <div class="form-group col-md-12">
<!--                                            <a data-href="{{ url('/dashboard/'.$project_id.'/specifications') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                            <a href="{{ url('/dashboard/'.$project_id.'/specifications') }}" class="btn btn-info sub-btn btn_back" onclick="return checkFormFilled('btn_back')">Back</a>
                                            <button type="submit" class="add_standard_form first_button btn btn-info sub-btn">Save</button>
                                            <!-- <button type="submit" class="add_standard_form another_button btn btn-info sub-btn" style="display: none;">Save Another</button> -->
                                            <a href="{{ url('/dashboard/'.$project_id.'/plans') }}" class="btn btn-info sub-btn continue_button" onclick="return checkFormFilled('continue_button')">Next Screen</a>
                                            <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
                                        </div>

                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <label for="standard_name">Upload Specification Document</label>
                                    <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                        <input type="hidden" name="document_path" value="/uploads/standard/">
                                    </form>
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
<script src="{{ url('/resources/assets/js/dropzone.js') }}"></script>
<script src="{{ url('/resources/assets/js/bs-input-mask.min.js') }}"></script>
<script type="text/javascript">
// $('#applicable').change(function() {
//     if($(this).is(":checked")) {
//        $('#my-awesome-dropzone').css("display", "none");
//     }
//     else {
//         $('#my-awesome-dropzone').css("display", "block");
//     }
// });
</script>
<script src="{{ url('/resources/assets/dist/specifications_add.js') }}"></script>
@include('include/footer')
