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
                <h3 class="m-b-less">Update Survey Review</h3>
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
                                <div id="alert_message"></div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <header class="panel-heading nopadtop nopadleft">Contractor: 
                                                    <span id="contractor_name">
                                                        
                                                    </span>
                                                </header>
                                            </div>
                                            
                                            <div class="col-sm-12 nopadleft">
                                                <section class="panel">
                                                    <!-- <div class="loading_data" style="text-align: center;">
                                                       <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                                                    </div> -->
                                                    <div class="tab-parent" style="overflow-x:scroll;">
                                                    <table class="table table-striped custom-grid">
                                                        <tbody>
                                                            <tr>
                                                                <th colspan="2">Survey #</th>
                                                                <td colspan="2" id="survey_number"></td>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="2">Date of Request:</th>
                                                                <td colspan="2" id="survey_date"></td>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="2">Description of Request</th>
                                                                <td colspan="2" id="survey_description"></td>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="2">Requested Completion By</th>
                                                                <td colspan="2" id="survey_req_date"></td>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="2">Additional Document</th>
                                                                <td colspan="2" id="survey_documents"></td>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="2">Survey User Detail</th>
                                                                <td colspan="2" id="survey_user_detail"></td>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="2">Survey Status</th>
                                                                <td colspan="2" id="status"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    </div>
                                                </section>
                                            </div>

                                                        <div class="col-sm-12">
                                                            <h2>Survey Review</h2>
                                                        </div>
                                                            
                                                        <div class="col-md-6 hide_loading">
                                                            <div class="form-group">
                                                                <label>Who is responsible for Survey</label><br/>
                                                                <label class="radio-inline">
                                                                  <input type="radio" name="response_survey" id="response_survey" value="owner"> Owner
                                                                </label>
                                                                <label class="radio-inline">
                                                                  <input type="radio" name="response_survey" id="response_survey" value="contractor"> Contractor
                                                                </label>
                                                            </div>

                                                            <div class="response_survey_div">
                                                                <div class="form-group">
                                                                    <label>Hours worked by Survey Crew : </label>
                                                                    <input type="text" class="form-control" id="survey_request">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Date of Cut Sheet:  </label>
                                                                    <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                                                        <input type="text" value="<?php echo date("Y-m-d"); ?>" size="16" class="form-control"  id="survey_respond_date">
                                                                          <span class="input-group-btn add-on">
                                                                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                                          </span>
                                                                    </div>
                                                                    
<!--                                                                    <input type="text" class="form-control" id="survey_respond_date" value="<?php echo date("Y-m-d"); ?>" disabled>-->
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group col-md-12 upload_survey_div">
                                                                <label for="name_of_report" style="padding-top: 15px;">Attach Cut Sheet</label>
                                                                <section class="panel upload_doc_panel_performance" id="upload_performance">
                                                                    <div class="panel-body" style="padding: 0px;">
                                                                        <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                                        <input type="hidden" name="document_path" value="/uploads/survey/">
                                                                        </form> 
                                                                    </div>
                                                                </section>
                                                                <!-- <input type="hidden" name="upload_type" id="upload_type" value=""> -->
                                                                <input type="hidden" name="standard_doc_id" id="upload_doc_id" value="">
                                                                <input type="hidden" name="standard_upload" id="upload_doc_meta" value="survey_cut_sheet">
                                                                <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                                                                <div class="clearfix"></div>
                                                            </div><!-- upload_doc_panel_payment close -->
                                                        </div>

                                                        

                                                   
                                            <div class="form-group col-md-12">
<!--                                                <a data-href="{{ url('/dashboard/'.$project_id.'/survey_review') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                                <a href="{{ url('/dashboard/'.$project_id.'/survey_review') }}" class="btn btn-info sub-btn btn_back1">Back</a>
                                                <button type="submit" class="btn btn-info sub-btn" id="submit_survey_review_form">Save</button>
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
<script src="{{ url('/resources/assets/js/dropzone.js') }}"></script>
<script type="text/javascript">
$("input[name='response_survey']").click(function(){
    if($('input:radio[name=response_survey]:checked').val() == "owner"){
        console.log('owner');
        $('.response_survey_div').show();
        $('.upload_survey_div').show();
    }
    else {
        console.log('contractor');
        $('.response_survey_div').hide();  
        $('.upload_survey_div').show();  
    }
});
</script>
<script src="{{ url('/resources/assets/dist/survey_review_update.js') }}"></script>

@include('include/footer')