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
                <h3 class="m-b-less">Daily Construction Management Report:

                <span id="report_name"></span></h3>
                <?php $project_id = Request::segment(2); ?>
                
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="loading_data" style="text-align: center;">
                                <img src="{{ url('/resources/assets/img/loading.svg') }}" alt=""/>
                            </div>
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
<!--        <div class="form-group">
             <label for="company_name"></label> 
            <header class="panel-heading nopadtop">Contractor:
                <span id="contractor_name">

                </span>
            </header>
        </div>-->

        <div class="col-md-12 nopadleft">
            <div class="form-group col-md-12">
                <label>Daily Report: <strong>#

                    <span id="report_number"></span>
                </label>
            </div>

            <div class="form-group col-md-12">
                <label>Date of Report : <strong><span id="report_date"></span></strong></label>
            </div>

            <div class="form-group col-md-12">
                <label style="float: left;">Project Weather :</label><!-- <strong><span id="project_weather"></span></strong> -->
                <input type="text" class="form-control" style="width: 100px; margin-left: 10px; display: inline-block; margin-top: -10px;" id="project_weather">
              <!--   <select class="form-control" name="" id="project_weather">
                    <option value="temperature">Temperature</option>
                    <option value="ground_conditions">Ground Conditions</option>
                    <option value="precipitation">Precipitation</option>
                </select> -->
            </div>

           <!--  <div class="form-group col-md-12">
                <label for="standard_link">Add Custom Field</label><br/>
                <label class="radio-inline">
                  <input type="radio" name="add_custom_field" id="add_custom_field" value="yes"> Yes
                </label>
                <label class="radio-inline">
                  <input type="radio" name="add_custom_field" id="add_custom_field" value="no"> No
                </label>
            </div> -->

            <div class="form-group col-md-12" id="add_custom_field_div" style="display: none;">
                <input type="text" class="form-control" id="custum_field">
            </div>


            <div class="form-group col-md-12">
                <label for="standard_link">Did the General Contractor perform work this day?</label><br/>
                <label class="radio-inline">
                  <input type="radio" name="perform_work_day" id="perform_work_day" value="yes"> Yes
                </label>
                <label class="radio-inline">
                  <input type="radio" name="perform_work_day" id="perform_work_day" value="no"> No
                </label>
            </div>

<div class="form-group col-md-12" id="subcontractor_div" style="display: none;">
    <label>Did Subcontractors perform any work this day?</label><br/>
    <label class="radio-inline">
        <input type="radio" name="subcontractor_field" id="subcontractor_field" value="yes"> Yes
    </label>
    <label class="radio-inline">
        <input type="radio" name="subcontractor_field" id="subcontractor_field" value="no"> No
    </label>
</div>
<div class="subcontractor_detail" style="display: none;">
    <div class="form-group col-md-12">
       <select class="form-control" name="" id="subcontractor_work_detail">
       </select>
    </div>
</div>

            <div class="form-group col-md-12 perform_work_day_div" style="display: none;">
                <label>What Contract Items were worked on? <input type="button" class="add_contract_items" value="Add New Contract Item"></label>
                <select class="form-control select2-multiple" id="contract_item_work" multiple placeholder="Select Contract Item">
                </select>
                <button class="btn btn-success addon-btn m-b-10" id="add_contract_item_work" style="margin-top: 15px; float:right;">
                    <i class="fa fa-forward pull-right"></i>NEXT
                </button>
            </div>

            <div class="form-group col-md-12" id="add_contract_item_work_data" style="display:none;">
                <label>How many quantities were completed for each?</label>
                <div class="">
                    <div class="col-sm-2" style="padding: 0px;">
                        <label><strong>Bid Item #</strong></label>
                    </div>
                    <div class="col-sm-4">
                        <label><strong>Description</strong></label>
                    </div>
                    <div class="col-sm-2">
                        <label><strong>Qty Completed This Day</strong></label>
                    </div>
                    <div class="col-sm-4" style="padding: 0px;">
                        <label><strong>Location/Additional Information</strong></label>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div id="contract_item_work_quantity">
                </div>
                <button class="btn btn-success addon-btn m-b-10" id="update_item_quantity" style="margin-top: 15px; float:right;">
                    <i class="fa fa-forward pull-right"></i>NEXT
                </button>
            </div>
            <div class="clearfix"></div>
            <div class="form-group col-md-12" id="add_resource_item_work_data" style="display: none;">
                <label>What Resources Were Used for Each Contract Item This Day?</label>
                <div class="clearfix"></div>
                <div class="col-xs-7" style="padding: 0px;">
                    <label><strong>Resource</strong></label>
                </div>
                <div class="col-sm-2">
                    <label><strong>Time</strong></label>
                </div>
                <div class="col-sm-3">
                    <label><strong>Time type</strong></label>
                </div>
                <div class="clearfix"></div>
                <div id="resource_item_div">
                    <!-- <div class="form-group">
                        <label>3. Furnish &amp; Install 18” RCP</label><br/>
                        <div class="div_item">
                            <div class="col-xs-7" style="padding:0px;">
                                <input type="text" class="form-control" name="resource_detail" value="">
                            </div>
                            <div class="col-xs-2">
                                <input type="text" class="form-control" name="resource_time" value="">
                                <input type="text" class="form-control" name="item_id" value="">
                            </div>
                            <div class="col-xs-2">
                                <select class="form-control" name="resource_time_type">
                                    <option value="mins">Mins</option>
                                    <option value="hours">Hour</option>
                                    <option value="day">Day</option>
                                    <option value="month">Month</option>
                                </select>
                            </div>
                            <div class="col-xs-1">
                                <a class="btn btn-success"> + </a>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="clearfix"></div>
                <button class="btn btn-success addon-btn m-b-10" id="update_resource_time" style="margin-top: 15px; float:right;">
                    <i class="fa fa-forward pull-right"></i>NEXT
                </button>
            </div>
            <div class="form-group col-md-12" id="material_delivered_div" style="display: none;">
                <label>Was any material delivered to the Site?</label><br/>
                <label class="radio-inline">
                    <input type="radio" name="material_delivered_field" id="material_delivered_field" value="yes"> Yes
                </label>
                <label class="radio-inline">
                    <input type="radio" name="material_delivered_field" id="material_delivered_field" value="no"> No
                </label>
            </div>

            <!-- <div class="form-group col-md-12" id="add_material_delivered_field" style="display: none;"> -->
            <div class="form-group col-md-12">
                <div class="col-sm-12">
                    <a id="add_material_row" class="btn btn-success" style="display: none; float: right;"> Add More Material Detail </a>
                </div>
<div class="material_delivered_all" style="display: none;">
    <div class="material_delivered_detail">
        <div class="col-sm-6 nopadleft">
            <label>What type of material was delivered?</label><br/>
            <input type="text" class="form-control" name="material_name[]">
            <label>How many units?</label><br/>
            <input type="text" class="form-control" name="material_unit[]" onkeypress="return isNumber(event)">
            <label>What type of units?</label><br/>
            <select class="form-control" name="material_unit_type[]">
                <option value="cy">CY</option>
            </select>
        </div>
        <div class="col-sm-6">
            <label>Upload Delivery Ticket</label><br/>
            <section class="panel upload_doc_panel" id="upload_div">
                <div class="panel-body dropzone-form" style="padding: 0px;">
                    <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                        <input type="hidden" class="" name="document_path" value="/uploads/daily_construction_report/material_delivered/">
                    </form>
                    <input type="hidden" name="upload_doc_id[]" class="upload_doc_id" id="upload_doc_id_1" value="">
                    <input type="hidden" name="upload_type" id="upload_type" value="multiple_upload">
                </div>
            </section>
        </div>
    </div><!-- material_delivered_detail close -->
</div><!-- material_delivered_all close -->
<div class="form-group col-sm-12">
    <button class="btn btn-success addon-btn m-b-10" id="add_material" style="display: none; margin-top: 15px; float:right;">
        <i class="fa fa-forward pull-right"></i>NEXT
    </button>
</div>
            </div>
            <div class="form-group col-md-12" id="milestone_div" style="display: none;">
                <label>Were any Schedule Milestones Completed this Day?</label><br/>
                <label class="radio-inline">
                    <input type="radio" name="milestone_field" id="milestone_field" value="yes"> Yes
                </label>
                <label class="radio-inline">
                    <input type="radio" name="milestone_field" id="milestone_field" value="no"> No
                </label>
            </div>
            <div class="form-group col-md-12" id="add_milestone_field" style="display: none;">
                <input type="text" class="form-control" id="milestone_detail">
            </div>
            <div class="form-group col-md-12" id="following_occur_div" style="display: none;">
                <label>Did any of the following events occur this day?</label><br/>
                <div class="col-sm-6 nopadleft">
                        <input type="checkbox" name="accur_detail[]" value="Changes_Requested_by_Engineer">
                        <label>Changes Requested by Engineer</label>
                 </div>
                <div class="col-sm-6 nopadleft">

                        <input type="checkbox" name="accur_detail[]" value="Accidents">
                        <label>Accidents</label>

                </div>
                <div class="col-sm-6 nopadleft">

                        <input type="checkbox" name="accur_detail[]" value="Changes_Requested_by_Owner">
                        <label>Changes Requested by Owner</label>

                </div>
                <div class="col-sm-6 nopadleft">

                        <input type="checkbox" name="accur_detail[]" value="Anticipated_Delays">
                        <label>Anticipated Delays</label>

                </div>
                <div class="col-sm-6 nopadleft">

                        <input type="checkbox" name="accur_detail[]" value="Additional_Construction_Time_Requested">
                        <label>Additional Construction Time Requested</label>

                </div>
                <div class="col-sm-6 nopadleft">

                        <input type="checkbox" name="accur_detail[]" value="Anticipated_Extras">
                        <label>Anticipated Extras</label>

                </div>
                <div class="col-sm-6 nopadleft">

                        <input type="checkbox" name="accur_detail[]" value="Plan_&_Spec._Clarification_Requested_of_Engineer">
                        <label>Plan &amp; Spec. Clarification Requested of Engineer</label>

                </div>
                <div class="col-sm-6 nopadleft">

                        <input type="checkbox" name="accur_detail[]" value="Subcontractor_Problems">
                        <label>Subcontractor Problems</label>

                </div>
                <div class="col-sm-6 nopadleft">

                        <input type="checkbox" name="accur_detail[]" value="Plan_&_Spec._Substitution_Requested_of_Engineer">
                        <label>Plan &amp; Spec. Substitution Requested of Engineer</label>

                </div>
                <div class="col-sm-6 nopadleft">

                        <input type="checkbox" name="accur_detail[]" value="Is_Construction_On_Schedule">
                        <label>Is Construction On Schedule</label>

                </div>
                <div class="col-sm-6 nopadleft">

                        <input type="checkbox" name="accur_detail[]" value="Design_Changes">
                        <label>Design Changes</label>

                </div>
                <div class="col-sm-6 nopadleft">

                        <input type="checkbox" name="accur_detail[]" value="Survey_Crew_On_Site">
                        <label>Survey Crew On Site</label>

                </div>
                <div class="col-sm-6 nopadleft">

                        <input type="checkbox" name="accur_detail[]" value="Non_Conformance_of_work_to_Plans_and_Specs.">
                        <label>Non Conformance of work to Plans and Specs.</label>

                </div>
                <div class="col-sm-6 nopadleft">

                        <input type="checkbox" name="accur_detail[]" value="Geotechnical_On_Site">
                        <label>Geotechnical On Site</label>

                </div>
                <div class="col-sm-6 nopadleft">

                        <input type="checkbox" name="accur_detail[]" value="Unsafe_Conditions">
                        <label>Unsafe Conditions</label>

                </div>
                <div class="col-sm-6 nopadleft">
                        <input type="checkbox" name="accur_detail[]" value="Requests_Made_by_Contractor">
                        <label>Requests Made by Contractor</label>
                </div>
                <div class="col-sm-6 nopadleft">

                        <input type="checkbox" name="accur_detail[]" value="Archeologist_On_Site">
                        <label>Archeologist On Site</label>

                </div>
                <div class="col-sm-6 nopadleft">

                        <input type="checkbox" name="accur_detail[]" value="County_Inspector_On_Site">
                        <label>County Inspector On Site</label>

                </div>
                <div class="col-sm-6 nopadleft">

                        <input type="checkbox" name="accur_detail[]" value="Paleontologist_On_Site">
                        <label>Paleontologist On Site</label>

                </div>
                <div class="col-sm-6 nopadleft">

                        <input type="checkbox" name="accur_detail[]" value="City_Inspector_On_Site">
                        <label>City Inspector On Site</label>

                </div>
                <div class="col-sm-6 nopadleft">

                        <input type="checkbox" name="accur_detail[]" value="Biologist_On_Site">
                        <label>Biologist On Site</label>

                </div>
                <div class="col-sm-6 nopadleft">

                        <input type="checkbox" name="accur_detail[]" value="Other_Inspector_On_Site">
                        <label>Other Inspector On Site</label>

                </div>
            </div>
            <div class="form-group col-md-12" id="general_note" style="display: none;">
                <label>General Notes Section</label><br/>
                <textarea class="form-control" id="general_note_detail"></textarea>
            </div>

<div class="form-group col-md-12" id="photo_video_div" style="display: none;">
    <label>Were any photos or videos taken this day?</label><br/>
    <label class="radio-inline">
        <input type="radio" name="photo_video_field" id="photo_video_field" value="yes"> Yes
    </label>
    <label class="radio-inline">
        <input type="radio" name="photo_video_field" id="photo_video_field" value="no"> No
    </label>
</div>
<div class="photo_video_taken_detail" style="display: none;">

    <div class="form-group col-md-12">
        <a id="add_photo_video_row" class="btn btn-success" style=" float: right;"> Add More Photos / Videos</a>
    </div>

    <div class="photo_video_single">
        <div class="col-sm-4">
            <label>Photo Description</label><br/>
            <input type="text" name="photo_description[]" class="form-control" value="">
        </div>
        <div class="col-sm-2">
            <label>Taken On</label><br/>
            <input type="text" name="taken_on[]" class="form-control default-date-picker1" value="">
        </div>
        <div class="col-sm-6">
            <label>Upload Photo / Video</label><br/>
            <section class="panel upload_doc_panel" id="upload_div">
                <div class="panel-body dropzone-form" style="padding: 0px;">
                    <form id="my-awesome-dropzone1" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                        <input type="hidden" class="" name="document_path" value="/uploads/daily_construction_report/video_photo/">
                    </form>
                    <input type="hidden" name="upload_photo_id[]" class="upload_doc_id" id="upload_doc_id_3" value="">
                    <input type="hidden" name="upload_type" id="upload_type" value="multiple_upload">
                </div>
            </section>
        </div>
    </div><!-- photo_video_single_close -->
</div><!-- photo_video_taken_detail close -->
<div class="form-group col-sm-12">
    <button class="btn btn-success addon-btn m-b-10" id="add_photo_video" style="display: none; margin-top: 15px; float:right;">
        <i class="fa fa-forward pull-right"></i>NEXT
    </button>
</div>

        <div class="col-sm-12">
            <div class="notice_exist">
                <div class="form-group col-md-12">
                    <input type="hidden" name="standard_upload" id="upload_doc_meta" value="daily_construction_report">
                    <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                    <div class="clearfix"></div>
                </div><!-- upload_doc_panel_payment close -->
            </div><!-- contract_exist close -->
            <div class="state-information">
                    <a href="{{ url('/dashboard/'.$project_id.'/daily_construction_report') }}" class="btn btn-info">Back</a>
                    <button type="submit" class="btn btn-info first_button submit_daily_report_form" id="submit_daily_report_form" style="display: none;">Save</button>
                </div>
        </div>

        <div class="form-group col-md-12">
            
            <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
        </div>
    </div><!-- Row Close -->
</div><!-- Col 6 Close -->
<div class="clearfix"></div>


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
<script src="{{ url('/resources/assets/js/dropzone_image_pdf.js') }}"></script>
<script type="text/javascript">
$("input[name='add_custom_field']").click(function(){
    if($('input:radio[name=add_custom_field]:checked').val() == "no"){
        $('#add_custom_field_div').css("display", "none");
    }
    else {
        $('#add_custom_field_div').css("display", "block");
    }
});


$("input[name='perform_work_day']").click(function(){
    if($('input:radio[name=perform_work_day]:checked').val() == "no"){
        $('.perform_work_day_div').css("display", "none");
        $('#subcontractor_div').css("display", "block");
    }
    else {
        $('.perform_work_day_div').css("display", "block");
        $('#subcontractor_div').css("display", "none");
        $('#material_delivered_div').css("display", "none");
        $('#milestone_div').css("display", "none");
        $('#following_occur_div').css("display", "none");
        $('#general_note').css("display", "none");
        $('#photo_video_div').css("display", "none");
        $('#add_photo_video').css("display", "none");
    }
});


$("input[name='material_delivered_field']").click(function(){
    if($('input:radio[name=material_delivered_field]:checked').val() == "no"){
        $('#add_material_delivered_field').css("display", "none");
        $('#add_material_row').css("display", "none");
        $('.material_delivered_all').css("display", "none");
        $('#add_material').css("display", "none");

        $('#milestone_div').show();
        $('#following_occur_div').show();
        $('#general_note').show();
        $('.submit_daily_report_form').show();
        $('#photo_video_div').show();
        $('#add_photo_video').show();
    }
    else {
        $('#add_material_delivered_field').css("display", "block");
        $('#add_material_row').css("display", "block");
        $('.material_delivered_all').css("display", "block");
        $('#add_material').css("display", "block");

        $('#milestone_div').css("display", "none");
        $('#following_occur_div').css("display", "none");
        $('#general_note').css("display", "none");
        $('.submit_daily_report_form').css("display", "none");
        $('#photo_video_div').css("display", "none");
        $('#add_photo_video').css("display", "none");
    }
});

$("input[name='milestone_field']").click(function(){
    if($('input:radio[name=milestone_field]:checked').val() == "no"){
        $('#add_milestone_field').css("display", "none");
    }
    else {
        $('#add_milestone_field').css("display", "block");
    }
});

$("input[name='photo_video_field']").click(function(){
    if($('input:radio[name=photo_video_field]:checked').val() == "no"){
        $('.photo_video_taken_detail').css("display", "none");
        $('#add_photo_video').css("display", "none");
        $('.submit_daily_report_form').show();
    }
    else {
        $('.photo_video_taken_detail').css("display", "block");
        $('.submit_daily_report_form').hide();
        $('#add_photo_video').css("display", "block");
    }
});

$("input[name='subcontractor_field']").click(function(){
    if($('input:radio[name=subcontractor_field]:checked').val() == "no"){
        $('#material_delivered_div').css("display", "block");
        $('.subcontractor_detail').css("display", "none");
    }
    else {
        $('.subcontractor_detail').css("display", "block");
        $('#material_delivered_div').css("display", "none");
        $('.submit_daily_report_form').css("display", "block");
    }
});

</script>
<script src="{{ url('/resources/assets/dist/daily_construction_report_update.js') }}"></script>

@include('include/footer')
