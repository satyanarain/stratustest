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
                <h3 class="m-b-less">Add Survey</h3>
                <?php $project_id = Request::segment(2); ?>
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
            <header class="panel-heading">Contractor:
                <span id="contractor_name">
                    <div class="loading_data" style="text-align: center;">
                       <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                    </div>
                </span>
            </header>
        </div>

        <div class="col-md-6 nopadleft">
            <div class="form-group col-md-12 submittal">
                <label>Survey Request # : <strong>
                    <div class="loading_data" style="text-align: center;">
                       <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                    </div>
                    <span id="survey_number"></span>
                </label>
            </div>

            <div class="form-group col-md-12 resubmittal">
                <label>Date of Request : <strong><span id="survey_date"><?php echo date("Y-m-d H:i"); ?></span></strong></label>
            </div>

            <div class="form-group col-md-12">
                <label>Description of Request <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="survey_description">
            </div>
            <div class="form-group col-md-12">
                <label>Requested Completion By <span class="text-danger">*</span></label>
                <div class='input-group date form_datetime' id='datetimepicker1'>
                    <input type='text' class="form-control" id="survey_completion_date" value="2018-01-18 16:13" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                
<!--                <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date="<?php echo date("Y-m-d"); ?>"  class="input-append date dpYears">
                    <input type="text" readonly="" value="<?php //echo date("Y-m-d"); ?>" size="16" class="form-control"  id="survey_completion_date">
                      <span class="input-group-btn add-on">
                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                      </span>
                </div>-->
            </div>

           <div class="form-group">
                <div class="col-sm-12">
                    <label class="checkbox-custom check-success">
                        <input type="checkbox" value=" " name="request_expedited_review" id="request_expedited_review">
                        <label for="request_expedited_review">Request Expedited Review</label>
                    </label>
                </div>
            </div>
        </div>



        <div class="col-sm-6">
            <div class="notice_exist">
                <div class="form-group col-md-12">
                    <input type="hidden" id="upload_doc_id" value="">
                    <input type="hidden" name="standard_upload" id="upload_doc_meta" value="survey">
                    <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                    <div class="clearfix"></div>
                </div><!-- upload_doc_panel_payment close -->
            </div><!-- contract_exist close -->
        </div>

        <div class="form-group col-md-12">
<!--            <a data-href="{{ url('/dashboard/'.$project_id.'/survey') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
            <a href="{{ url('/dashboard/'.$project_id.'/survey') }}" class="btn btn-info sub-btn btn_back" onclick="return checkFormFilled('btn_back')">Back</a>
            <a class="btn btn-info sub-btn first_button submit_survey_form">Save</a>
            <a class="btn btn-info sub-btn another_button submit_survey_form" style="display: none;">Save Another</a>
            <a href="{{ url('/dashboard/'.$project_id.'/daily_construction_report') }}" class="btn btn-info sub-btn continue_button" onclick="return checkFormFilled('continue_button')">Next Screen</a>
            <!-- <button type="submit" class="btn btn-info sub-btn submit_survey_form" style="display: none;">Submit</button> -->
            <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
        </div>
    </div><!-- Row Close -->
</div><!-- Col 6 Close -->
<div class="clearfix"></div>


<!--<div id="pdf_content" style="width:100%; display:;">
 <div  id="pdf_content" style="width:100%;"> 
    <h2>Survey</h2>
    <p><strong>Date and Time of request:</strong> <?php echo date("Y-m-d H:i:s"); ?></p>
    <p><strong>Company Name:</strong> <span id="pdf_gen_contractor_name"></span></p>
    <p><strong>Company Address:</strong> <span id="pdf_gen_contractor_address"></span></p>
    <p><strong>Project Name: </strong><span id="pdf_gen_project_name"></span></p>
    <p><strong>Project Description: </strong><span id="pdf_gen_project_description"></span></p>
    <p><strong>Improvement Type:</strong> <span id="pdf_gen_project_type"></span></p>
    <p><strong>Survey Contract Amount:</strong> <span id="pdf_gen_contract_amount"></span></p>
    <p><strong>Survey # :</strong> <span id="pdf_gen_survey_number"></span></p>
    <p><strong>Survey Description:</strong> <span id="pdf_gen_survey_description"></span></p>
    <p><strong>Survey Requested Completion By:</strong> <span id="pdf_gen_req_comp_date"></span></p>
</div>-->
<div id="pdf_content" style="display:;">
    <table width="90%" cellpadding="0" align="center" cellspacing="0" border="0" style="font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <tr>
       <td><img src="http://ec2-34-236-61-80.compute-1.amazonaws.com/resources/assets/img/pdf-logo.jpg" alt="" /></td>
       <td style="font-size:25px;"><b><i>Survey Request For:</i></b></td>
   </tr>
   
   <tr>
       <td valign="top">
           <table width="100%" cellpadding="5" cellspacing="0" border="0">
               <tr><td><b>1151 Duryea Ave.</b></td></tr>
               <tr><td><b>Irvine, CA 92614</b></td></tr>
               <tr><td><b>Tel: 949.988.3270</b></td></tr>
           </table>    
       </td>
       <td valign="top" width="40%">
           <table width="100%" cellpadding="10" cellspacing="0" border="0">
               <tr><td style="color:#F00; border-bottom:2px solid #000;"><b>Friday, May 09, 2014</b></td></tr>
               <tr><td>Date Service Needed</td></tr>
               <tr><td style="color:#F00; border-bottom:2px solid #000;"><b>12:00 AM</b></td></tr>
               <tr><td>Time Service Needed</td></tr>
           </table>
       </td>
   </tr>
   
   <tr><td colspan="2" valign="top">
	 <table width="100%" cellpadding="10" cellspacing="10" border="0">
           <tr>
               <td width="50" rowspan="4" valign="top"><b>From</b>:</td>
               <td width="70">Contact Person:</td>
               <td style="border-bottom:1px solid #000;"></td>    
               <td width="90">Day requested:</td>
               <td style="border-bottom:1px solid #000;">&nbsp;</td>
           </tr>
           <tr>
               <td>Company:</td>
               <td style="border-bottom:1px solid #000;">&nbsp;</td>
                <td>Time Requested</td>
               <td style="border-bottom:1px solid #000;">&nbsp;</td>
           </tr>
           <tr>
               <td>Project Name:</td>
               <td style="border-bottom:1px solid #000;">&nbsp;</td>              
               <td>Surveyors Job #:</td>
               <td style="border-bottom:1px solid #000;">&nbsp;</td>
           </tr>
           <tr>
               <td>Phone Numer:</td>
               <td style="border-bottom:1px solid #000;">&nbsp;</td>
               <td>JOBSITE FAX NO.:</td>
               <td style="border-bottom:1px solid #000;">&nbsp;</td>
           </tr>
           <tr><td colspan="5">&nbsp;</td></tr>
           
           <tr>
            <td rowspan="6" valign="top"><b>To:</b></td>
            <td colspan="4"><b>SURVEY COORDINATOR:</b></td>
          </tr>
            
            <tr>
              <td style="border-bottom:1px solid #000;" align="center">1</td>
              <td style="border-bottom:1px solid #000;" colspan="3">&nbsp;</td>
            </tr> 
            <tr>
              <td style="border-bottom:1px solid #000;" align="center">&nbsp;</td>
              <td style="border-bottom:1px solid #000;" colspan="3">&nbsp;</td>
            </tr> 
            <tr>
              <td style="border-bottom:1px solid #000;" align="center">&nbsp;</td>
              <td style="border-bottom:1px solid #000;" colspan="3">&nbsp;</td>
            </tr> 
            <tr>
              <td style="border-bottom:1px solid #000;" align="center">&nbsp;</td>
              <td style="border-bottom:1px solid #000;" colspan="3">&nbsp;</td>
            </tr> 
             
           <tr><td colspan="5" style="border-bottom:2px solid #000;">&nbsp;</td></tr>
           
          <tr>
              <td rowspan="6" valign="top"><b>CONFIRMED:</b></td>
              <td>Plans prepared by:</td>
              <td style="border-bottom:1px solid #000;"></td>    
              <td>Dated</td>
              <td style="border-bottom:1px solid #000;">&nbsp;</td>
          </tr>
          <tr>
               <td>Staked by:</td>
               <td style="border-bottom:1px solid #000;"></td>
			   <td>Dated:</td>
               <td style="border-bottom:1px solid #000;">&nbsp;</td>
           </tr>
           <tr>
               <td>Calc'ed By:</td>
               <td style="border-bottom:1px solid #000;"></td>
			   <td>Dated:</td>
               <td style="border-bottom:1px solid #000;">&nbsp;</td>
           </tr>
           <tr>
               <td>Comments:</td>
               <td style="border-bottom:1px solid #000;" colspan="3"></td>
           </tr>
           <tr>
               <td>Project Mgr.</td>
               <td style="border-bottom:1px solid #000;" colspan="3"></td>
           </tr>
           <tr>
               <td>Digital Files Used:</td>
               <td style="border-bottom:1px solid #000;" colspan="3"></td>
           </tr>
         </table>
   </td></tr>
   
  <tr><td colspan="2" style="border-bottom:2px solid #000;font-size:30px;"><b>NOTE: MINIMUM 48 HOUR NOTICE REQUIRED FOR ALL SURVEY REQUESTS</b></td></tr>  
</table>
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
<script src="{{ url('/resources/assets/dist/survey_add.js') }}"></script>

@include('include/footer')
