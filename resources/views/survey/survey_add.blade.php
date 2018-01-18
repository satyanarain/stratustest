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
                    <input type='text' class="form-control" id="survey_completion_date" />
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
<div id="pdf_content" style="width:100%; display:;">
    <table cellspacing="0" border="0">
	<colgroup width="16"></colgroup>
	<colgroup width="25"></colgroup>
	<colgroup width="16"></colgroup>
	<colgroup width="97"></colgroup>
	<colgroup width="87"></colgroup>
	<colgroup width="93"></colgroup>
	<colgroup span="8" width="87"></colgroup>
	<colgroup width="33"></colgroup>
	<tr>
		<td height="40" align="left" valign=bottom><br></td>
		<td colspan=5 rowspan=3 align="left" valign=bottom><br><img src="http://ec2-34-236-61-80.compute-1.amazonaws.com/resources/assets/img/pdf-logo.jpg" width=222 height=96 hspace=49 vspace=2>
		</td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom colspan="4"><b><i><font face="DUTCH" size=6>Survey Request For:</font></i></b></td>
	</tr>
	<tr>
		<td height="31" align="left" valign=bottom><br></td>
		<td align="left" valign=middle><b><font face="DUTCH" size=5 color="#0070C0"> </font></b></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="27" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td style="border-bottom: 1px solid #000000" colspan=4 align="left" valign=middle sdval="41768" sdnum="1033;0;[$-F800]DDDD\, MMMM DD\, YYYY"><b><font color="#FF0000"><?php echo date('l, M y, Y');?></font></b></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="27" align="left" valign=bottom><br></td>
		<td align="left" valign=middle colspan="3"><b><font size=2>1151 Duryea Ave.</font></b></td>
		
		<td align="left" valign=bottom><font face="DUTCH"><br></font></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td style="border-top: 1px solid #000000" colspan=4 align="left" valign=middle><font size=2>Date Service Needed</font></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="27" align="left" valign=bottom><br></td>
		<td align="left" valign=middle colspan="3"><b><font size=2>Irvine, CA  92614</font></b></td>
		
		<td align="left" valign=bottom><b><font face="DUTCH" size=4><br></font></b></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td style="border-bottom: 1px solid #000000" colspan=4 align="left" valign=middle sdval="9" sdnum="1033;1033;[$-409]H:MM AM/PM;@"><b><font color="#FF0000"><?php echo date('h:i A');?></font></b></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="25" align="left" valign=bottom><br></td>
		<td align="left" valign=middle colspan="3"><b><font size=2>Tel: 949.988.3270</font></b></td>
		
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td style="border-top: 1px solid #000000" colspan=4 align="left" valign=middle><font size=2>Time Service Needed</font></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	
	<tr>
		<td height="27" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="27" align="left" valign=bottom><b>From:</b></td>
		<td align="left" valign=bottom><font size=4><br></font></td>
		<td align="left" valign=bottom><br></td>
                <td align="left" valign=bottom colspan="2">Contact Person:</td>
		
		<td style="border-bottom: 1px solid #000000" colspan=3 align="left" valign=middle></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom colspan="2">Day requested:</td>
		
                <td style="border-bottom: 1px solid #000000" colspan=3 align="center" valign=middle id="pdf_gen_req_comp_date"><b><font size=4><br></font></b></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="27" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="27" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom>Company:</td>
		<td align="left" valign=bottom><br></td>
                <td style="border-bottom: 1px solid #000000" colspan=3 align="left" valign=middle id="pdf_gen_contractor_name"></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom colspan="2">Time Requested</td>
		
                <td style="border-bottom: 1px solid #000000" colspan=3 align="center" valign=middle id="pdf_gen_req_comp_time"><b><font size=4><br></font></b></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="27" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="27" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom>Project Name:</td>
		<td align="left" valign=bottom><br></td>
                <td style="border-bottom: 1px solid #000000" colspan=3 align="left" valign=middle id="pdf_gen_project_name"></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom colspan="2">Surveyors  Job #:</td>
		
                <td style="border-bottom: 1px solid #000000" colspan=3 align="left" valign=middle sdnum="1033;0;0.0000_)" id="pdf_gen_survey_number"><font size=4> </font></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="27" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><b><font size=4><br></font></b></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="right" valign=bottom><br></td>
		<td align="right" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom sdnum="1033;0;0.0000_)"><b><font size=4><br></font></b></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="27" align="left" valign=bottom><font size=4><br></font></td>
		<td align="left" valign=bottom><font size=4><br></font></td>
		<td align="left" valign=bottom><font size=4><br></font></td>
		<td align="left" valign=bottom>Phone Numer:</td>
		<td align="left" valign=bottom><font size=4><br></font></td>
		<td style="border-bottom: 1px solid #000000" colspan=3 align="left" valign=middle><font size=4></font></td>
		<td align="left" valign=bottom><font size=4><br></font></td>
		<td align="left" valign=bottom colspan="2">JOBSITE FAX NO.:</td>
		
		<td style="border-bottom: 1px solid #000000" colspan=3 align="left" valign=middle sdnum="1033;0;0.0000_)"><font size=4></font></td>
		<td align="left" valign=bottom><font size=4><br></font></td>
	</tr>
	<tr>
		<td height="15" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="27" align="left" valign=bottom><b>To:</b></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
                <td align="left" valign=bottom colspan="3"><b> SURVEY COORDINATOR: </b></td>
		
		<td colspan=2 align="left" valign=bottom><b><u><font size=4><br></font></u></b></td>
		<td align="left" valign=bottom><b><font size=4><br></font></b></td>
		<td align="left" valign=bottom><b><font size=4><br></font></b></td>
		<td align="left" valign=bottom><b><font size=4><br></font></b></td>
		<td align="left" valign=bottom><font size=4><br></font></td>
		<td align="left" valign=bottom><font size=4><br></font></td>
		<td align="left" valign=bottom><font size=4><br></font></td>
		<td align="left" valign=bottom><font size=4><br></font></td>
	</tr>
	<tr>
		<td height="15" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><b><br></b></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="27" align="left" valign=bottom><b><u><br></u></b></td>
		<td align="left" valign=bottom><b><u><br></u></b></td>
		<td align="left" valign=bottom><b><br></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#D9D9D9"><b>Task No.</b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=10 align="center" valign=bottom bgcolor="#D9D9D9"><b>           DESCRIPTION OF SURVEY REQUIRED:  (Please prioritize &amp;  provide details) </b></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="20" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="33" align="left" valign=bottom><u><br></u></td>
		<td align="left" valign=bottom><b><u><br></u></b></td>
		<td align="left" valign=bottom><br></td>
		<td style="border-bottom: 1px solid #000000" align="center" valign=bottom sdnum="1033;0;@">1.</td>
		<td align="left" valign=bottom><br></td>
                <td style="border-bottom: 1px solid #000000" colspan=9 align="left" valign=middle id="pdf_gen_survey_description"></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="20" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="center" valign=bottom sdnum="1033;0;@"><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="33" align="left" valign=bottom><u><br></u></td>
		<td align="left" valign=bottom><b><u><br></u></b></td>
		<td align="left" valign=bottom><br></td>
		<td style="border-bottom: 1px solid #000000" align="center" valign=bottom sdnum="1033;0;@"></td>
		<td align="left" valign=bottom><br></td>
		<td style="border-bottom: 1px solid #000000" colspan=9 align="left" valign=middle></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="20" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="center" valign=bottom sdnum="1033;0;@"><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="33" align="left" valign=bottom><u><br></u></td>
		<td align="left" valign=bottom><b><u><br></u></b></td>
		<td align="left" valign=bottom><br></td>
		<td style="border-bottom: 1px solid #000000" align="center" valign=bottom sdnum="1033;0;@"></td>
		<td align="left" valign=bottom><br></td>
		<td style="border-bottom: 1px solid #000000" colspan=9 align="left" valign=middle></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="20" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="center" valign=bottom sdnum="1033;0;@"><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="33" align="left" valign=bottom><u><br></u></td>
		<td align="left" valign=bottom><b><u><br></u></b></td>
		<td align="left" valign=bottom><br></td>
		<td style="border-bottom: 1px solid #000000" align="center" valign=bottom sdnum="1033;0;@"></td>
		<td align="left" valign=bottom><br></td>
		<td style="border-bottom: 1px solid #000000" colspan=9 align="left" valign=middle></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="20" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="center" valign=bottom sdnum="1033;0;@"><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="33" align="left" valign=bottom><u><br></u></td>
		<td align="left" valign=bottom><b><u><br></u></b></td>
		<td align="left" valign=bottom><br></td>
		<td style="border-bottom: 1px solid #000000" align="center" valign=bottom sdnum="1033;0;@"></td>
		<td align="left" valign=bottom><br></td>
		<td style="border-bottom: 1px solid #000000" colspan=9 align="left" valign=middle></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="20" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="center" valign=bottom sdnum="1033;0;@"><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="33" align="left" valign=bottom><u><br></u></td>
		<td align="left" valign=bottom><b><u><br></u></b></td>
		<td align="left" valign=bottom><br></td>
		<td style="border-bottom: 1px solid #000000" align="center" valign=bottom sdnum="1033;0;@"></td>
		<td align="left" valign=bottom><br></td>
		<td style="border-bottom: 1px solid #000000" colspan=9 align="left" valign=middle></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="20" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="center" valign=bottom sdnum="1033;0;@"><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="33" align="left" valign=bottom><u><br></u></td>
		<td align="left" valign=bottom><b><u><br></u></b></td>
		<td align="left" valign=bottom><br></td>
		<td style="border-bottom: 1px solid #000000" align="center" valign=bottom sdnum="1033;0;@"></td>
		<td align="left" valign=bottom><br></td>
		<td style="border-bottom: 1px solid #000000" colspan=9 align="left" valign=middle></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="20" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="center" valign=bottom sdnum="1033;0;@"><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="33" align="left" valign=bottom><u><br></u></td>
		<td align="left" valign=bottom><b><u><br></u></b></td>
		<td align="left" valign=bottom><br></td>
		<td style="border-bottom: 1px solid #000000" align="center" valign=bottom sdnum="1033;0;@"></td>
		<td align="left" valign=bottom><br></td>
		<td style="border-bottom: 1px solid #000000" colspan=9 align="left" valign=middle></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="20" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="center" valign=bottom sdnum="1033;0;@"><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><u><br></u></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td style="border-bottom: 2px solid #000000" height="41" align="left" valign=middle><br></td>
		<td style="border-bottom: 2px solid #000000" align="left" valign=middle><br></td>
		<td style="border-bottom: 2px solid #000000" align="left" valign=middle><br></td>
		<td style="border-bottom: 2px solid #000000" align="left" valign=middle><font size=4><br></font></td>
		<td style="border-bottom: 2px solid #000000" align="left" valign=middle><br></td>
		<td style="border-bottom: 2px solid #000000" align="left" valign=bottom><br></td>
		<td style="border-bottom: 2px solid #000000" align="left" valign=middle><br></td>
		<td style="border-bottom: 2px solid #000000" align="left" valign=middle><br></td>
		<td style="border-bottom: 2px solid #000000" align="left" valign=middle><br></td>
		<td style="border-bottom: 2px solid #000000" align="left" valign=middle><br></td>
		<td style="border-bottom: 2px solid #000000" align="left" valign=middle><br></td>
		<td style="border-bottom: 2px solid #000000" align="left" valign=middle><br></td>
		<td style="border-bottom: 2px solid #000000" align="left" valign=middle><br></td>
		<td style="border-bottom: 2px solid #000000" align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
	</tr>
	<tr>
		<td height="20" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="32" align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="right" valign=middle>CONFIRMED:</td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
	</tr>
	<tr>
		<td height="32" align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
	</tr>
	<tr>
		<td height="32" align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		
                <td align="right" valign=middle colspan="3">Plans prepared by:</td>
		<td style="border-bottom: 1px solid #000000" colspan=5 align="left" valign=middle></td>
		<td align="right" valign=middle>Dated:</td>
		<td style="border-bottom: 1px solid #000000" colspan=3 align="left" valign=middle sdnum="1033;1033;[$-409]MMMM D\, YYYY;@"><br></td>
		<td align="left" valign=middle><br></td>
	</tr>
	<tr>
		<td height="16" align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="right" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="right" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
	</tr>
	<tr>
		<td height="32" align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="right" valign=middle>Staked by:</td>
		<td style="border-bottom: 1px solid #000000" colspan=5 align="left" valign=middle></td>
		<td align="right" valign=middle>Dated:</td>
		<td style="border-bottom: 1px solid #000000" colspan=3 align="left" valign=middle sdnum="1033;1033;[$-409]MMMM D\, YYYY;@"><br></td>
		<td align="left" valign=middle><br></td>
	</tr>
	<tr>
		<td height="16" align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="right" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="right" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
	</tr>
	<tr>
		<td height="32" align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="right" valign=middle>Calc'ed By:</td>
		<td style="border-bottom: 1px solid #000000" colspan=5 align="left" valign=middle></td>
		<td align="right" valign=middle>Dated:</td>
		<td style="border-bottom: 1px solid #000000" colspan=3 align="left" valign=middle sdnum="1033;1033;[$-409]MMMM D\, YYYY;@"><br></td>
		<td align="left" valign=middle><br></td>
	</tr>
	<tr>
		<td height="16" align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="right" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="right" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
	</tr>
	<tr>
		<td height="32" align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="right" valign=middle>Comments:</td>
		<td style="border-bottom: 1px solid #000000" colspan=5 align="left" valign=middle></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
	</tr>
	<tr>
		<td height="16" align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="right" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="right" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
	</tr>
	<tr>
		<td height="32" align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="right" valign=middle>Project Mgr.</td>
		<td style="border-bottom: 1px solid #000000" colspan=5 align="left" valign=middle></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
	</tr>
	<tr>
		<td height="16" align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="right" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="right" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
	</tr>
	<tr>
		<td height="32" align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		
		<td align="right" valign=middle colspan="3">Digital Files Used:</td>
		<td style="border-bottom: 1px solid #000000" colspan=5 align="left" valign=middle></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
		<td align="left" valign=middle><br></td>
	</tr>
	<tr>
		<td height="20" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr>
		<td height="33" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom colspan="12"><b><font size=5>NOTE:  MINIMUM 48 HOUR NOTICE REQUIRED FOR ALL SURVEY REQUESTS</font></b></td>
		
	</tr>
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
