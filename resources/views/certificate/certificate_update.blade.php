
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
         <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Update Certificate</h3>
                <?php $project_id = Request::segment(2); ?>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div id="alert_message" style="display: none"></div>

                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <div class="panel-body">
                            <div id="alert_message"></div>

                                  

                                <div class="form-group col-md-12">
                                    <label for="company_name"></label>
                                    <h4>Contractor:
                                    <span id="contractor_name">
                                        <div class="loading_data" style="text-align: center;">
                                           <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                        </div>
                                    </span>
                                    </h4>
                                    <input type="hidden" id="company_name" value="">
                                    <hr/>
                                </div>

                                <div class="col-md-12">
                                    <h3>General Liability</h3>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name_of_report" style="padding-top: 15px;">General Liability Limit <span class="text-danger">*</span></label>
                                        <div class="clearfix"></div>
                                        <div class="col-xs-3" style="padding: 0px; display: none;">
                                            <div class="loading_data" style="text-align: center;">
                                               <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                            </div>
                                            <select class="form-control currency_symbol" name="general_liability_cur_symbol" id="general_liability_cur_symbol">
                                                <option value="1">$</option>
                                            </select>
                                        </div>
                                        <div class="col-xs-12 nopadleft">
                                            <input type="text" placeholder="$" class="form-control" id="general_liability_amount" onkeypress="return isNumber(event)">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="form-group">
                                        <label>Expiration Date <span class="text-danger">*</span></label>
                                        <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                            <input type="text" readonly="" value="" size="16" class="form-control"  id="general_liability_date">
                                              <span class="input-group-btn add-on">
                                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                              </span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="form-group">
                                        <label class="checkbox-custom check-success">
                                            <input type="checkbox" value=" " id="req_minimum_general">
                                            <label for="req_minimum_general">Required Minimum</label>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label class="checkbox-custom check-success">
                                            <input type="checkbox" value=" " id="general_liability_included">
                                            <label for="general_liability_included">Not Included</label>
                                        </label>
                                    </div>
                                </div><!-- Col 6 Close -->

                                <div class="col-sm-6">
                                    <section class="panel upload_doc_panel" style="margin-top: 20px;">
                                        
                                        <div class="panel-body">
                                            <label for="standard_name">Upload General Liability Document <span class="text-danger" style="float:right">*</span></label>
                                            <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                <input type="hidden" name="document_path" value="/uploads/certificate/">
                                            </form>
                                            <input type="hidden" name="upload_type" id="upload_type" value="">
                                            <input type="hidden" name="standard_upload" id="upload_doc_meta" value="certificate">
                                            <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                                            <input type="hidden" name="standard_doc_id" id="upload_doc_id_general" value="">


                                             <input type="hidden" name="standard_doc_id_old" id="standard_doc_id_old" value="">
                                        </div>
                                    </section>
                                </div><!-- Col 6 Close -->
                                <div class="col-sm-12">
                                    <hr/>
                                </div>

                                <div class="col-sm-12">
                                    <h3 style="margin-top:50px;">Workers Compensation Limit</h3>
                                </div><!-- Col 12 Close -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="name_of_report" style="padding-top: 15px;">Workers Compensation Limit <span class="text-danger">*</span></label>
                                        <div class="clearfix"></div>
                                        <div class="col-xs-3" style="padding: 0px; display: none;">
                                            <div class="loading_data" style="text-align: center;">
                                               <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                            </div>
                                            <select class="form-control currency_symbol" name="works_compensation_cur_symbol" id="works_compensation_cur_symbol">
                                            <option value="1">$</option>
                                            </select>
                                        </div>
                                        <div class="col-xs-12 nopadleft">
                                            <input type="text" placeholder="$" class="form-control" id="works_compensation_currency" onkeypress="return isNumber(event)">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label>Expiration Date <span class="text-danger">*</span></label>
                                        <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                                <input type="text" readonly="" value="" size="16" class="form-control"  id="works_compensation_date">
                                                  <span class="input-group-btn add-on">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                  </span>
                                            </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="form-group">
                                        <label class="checkbox-custom check-success">
                                            <input type="checkbox" value=" " id="req_minimum_work">
                                            <label for="req_minimum_work">Required Minimum</label>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label class="checkbox-custom check-success">
                                            <input type="checkbox" value=" " id="upload_work_above">
                                            <label for="upload_work_above">Included in General Liability Certificate</label>
                                        </label>
                                    </div>

                                    <div class="form-group upload_work">
                                        <label class="checkbox-custom check-success">
                                            <input type="checkbox" value=" " id="upload_work">
                                            <label for="upload_work">Not Included</label>
                                        </label>
                                    </div>
                                    <div class="clearfix"></div>
                                </div><!-- Col 6 Close -->
                                <div class="col-sm-6">
                                    <section class="panel upload_doc_panel_work" style="margin-top: 0px;">
                                        <div class="panel-body">
                                            <label for="standard_name">Upload Workers Compensation Document <span class="text-danger" style="float:right">*</span></label>
                                            <form id="my-awesome-dropzone1" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                <input type="hidden" class="certificate_work_compensation" name="document_path" value="/uploads/certificate/">
                                            </form>
                                            <input type="hidden" name="standard_doc_id" id="upload_doc_id_work" value="">
                                              <input type="hidden" name="standard_doc_id" id="upload_doc_id_work_old" value="">
                                        </div>
                                    </section>
                                    <div class="clearfix"></div>
                                </div><!-- Col 6 Close -->
                                <div class="col-sm-12">
                                    <hr/>
                                </div>
    





                                <div class="col-sm-12">
                                        <h3 style="margin-top:50px;">Auto Liability</h3>
                                </div><!-- Col 12 Close -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="name_of_report" style="padding-top: 15px;">Auto Liability Limit <span class="text-danger">*</span></label>
                                        <div class="clearfix"></div>
                                        <div class="col-xs-3" style="padding: 0px; display: none;">
                                            <div class="loading_data" style="text-align: center;">
                                               <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                            </div>
                                            <select class="form-control currency_symbol" name="auto_compensation_cur_symbol" id="auto_compensation_cur_symbol">
                                            <option value="1">$</option>
                                            </select>
                                        </div>
                                        <div class="col-xs-12 nopadleft">
                                            <input type="text" placeholder="$" class="form-control" id="auto_compensation_currency" onkeypress="return isNumber(event)">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label>Expiration Date <span class="text-danger">*</span></label>
                                        <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                                <input type="text" readonly="" value="" size="16" class="form-control"  id="auto_compensation_date">
                                                  <span class="input-group-btn add-on">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                  </span>
                                            </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="form-group">
                                        <label class="checkbox-custom check-success">
                                            <input type="checkbox" value=" " id="auto_req_minimum">
                                            <label for="auto_req_minimum">Required Minimum</label>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label class="checkbox-custom check-success">
                                            <input type="checkbox" value="" id="upload_auto_above">
                                            <label for="upload_auto_above">Included in General Liability Certificate</label>
                                        </label>
                                    </div>

                                    <div class="form-group upload_auto">
                                        <label class="checkbox-custom check-success">
                                            <input type="checkbox" value=" " id="upload_auto">
                                            <label for="upload_auto">Not Included</label>
                                        </label>
                                    </div>

                                    <div class="clearfix"></div>
                                </div><!-- Col 6 Close -->
                                <div class="col-sm-6">
                                    <section class="panel upload_doc_panel_auto">
                                        <div class="panel-body">
                                            <label for="standard_name">Upload Auto Liability Document <span class="text-danger" style="float:right">*</span></label>
                                            <form id="my-awesome-dropzone2" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                <input type="hidden" class="certificate_auto_liability" name="document_path" value="/uploads/certificate/">
                                            </form>
                                            <input type="hidden" name="standard_doc_id" id="upload_doc_id_auto" value="">
                                            <input type="hidden" name="standard_doc_id" id="upload_doc_id_auto_old" value="">
                                        </div>
                                    </section>
                                </div><!-- Col 6 Close -->
                                <div class="col-sm-12">
                                    <hr/>
                                </div>
        
    

                                <div id="add_umbrella_liability_div" style="">
                                    <div class="col-sm-10">
                                        <h3 style="margin: 0px;">Umbrella Liability</h3>
                                    </div><!-- Col 12 Close -->
                                    <div class="col-sm-2">
                                        <a id="delete_umbrella_liability_div" class="btn btn-danger btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Remove" style="float: right;"><i class="fa fa-times"></i></a>
                                    </div><!-- Col 12 Close -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name_of_report" style="padding-top: 15px;">Umbrella Liability Limit</label>
                                            <div class="clearfix"></div>
                                            <div class="col-xs-3" style="padding: 0px; display: none;">
                                                <div class="loading_data" style="text-align: center;">
                                                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                                </div>
                                                <select class="form-control currency_symbol" name="umbrella_liability_cur_symbol" id="umbrella_liability_cur_symbol">
                                                <option value="1">$</option>
                                                </select>
                                            </div>
                                            <div class="col-xs-12 nopadleft">
                                                <input type="text" placeholder="$" class="form-control" id="umbrella_liability_currency" value="" onkeypress="return isNumber(event)">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group">
                                            <label>Expiration Date</label>
                                            <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                                <input type="text" readonly="" value="" size="16" class="form-control"  id="umbrella_liability_date">
                                                  <span class="input-group-btn add-on">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                  </span>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>

                                        <div class="form-group">
                                            <label class="checkbox-custom check-success">
                                                <input type="checkbox" value=" " id="req_minimum_umbrella">
                                                <label for="req_minimum_umbrella">Required Minimum</label>
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <label class="checkbox-custom check-success">
                                                <input type="checkbox" value=" " id="upload_umbrella_above">
                                                <label for="upload_umbrella_above">Included in General Liability Certificate</label>
                                            </label>
                                        </div>

                                        <div class="form-group upload_umbrella">
                                            <label class="checkbox-custom check-success">
                                                <input type="checkbox" value=" " id="upload_umbrella">
                                                <label for="upload_umbrella">Not Included</label>
                                            </label>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div><!-- Col 6 Close -->
                                    <div class="col-sm-6">
                                        <section class="panel upload_doc_panel_umbrella">
                                            <div class="panel-body">
                                                <form id="my-awesome-dropzone3" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                    <input type="hidden" class="certificate_auto_umbrella" name="document_path" value="/uploads/certificate/">
                                                </form>
                                                <input type="hidden" name="standard_doc_id" id="upload_doc_id_umbrella" value="">
                                                  <input type="hidden" name="standard_doc_id" id="upload_doc_id_umbrella_old" value="">
                                            </div>
                                        </section>
                                    </div><!-- Col 6 Close -->
                                </div>


                                <div id="add_more_certificate_div" >
                                  <div class="col-sm-6">
                                        <h3>Custom Certificate</h3>
                                    </div>
                            
                                    <div class="clearfix"></div>
<div class="custom_certificate_all">
     <div class="custom_certificate_detail">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name_of_report" style="padding-top: 15px;">Certificate Liability Name</label>
                <input type="text" class="form-control" name="custom_certificate_name[]" id="custom_certificate_name">
                <div class="clearfix"></div>
                <label for="name_of_report" style="padding-top: 15px;">Certificate Liability Limit</label>
                <div class="clearfix"></div>
                <div class="col-xs-3" style="padding: 0px;">
                    <div class="loading_data" style="text-align: center;">
                       <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                    </div>
                    <select class="form-control currency_symbol" name="custom_certificate_cur_symbol[]" id="custom_certificate_cur_symbol">
                    </select>
                </div>
                <div class="col-xs-9">
                    <input type="text" class="form-control" name="custom_certificate_currency[]" id="custom_certificate_currency" onkeypress="return isNumber(event)">
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <label>Expiration Date</label>
                <div data-date-viewmode="years" data-date-format="yyyy-mm-dd"  class="input-append date dpYears">
                    <input type="text" readonly="" value="" size="16" class="form-control" name="custom_certificate_date[]">
                      <span class="input-group-btn add-on">
                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                      </span>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="col-sm-6">
            <div class="col-sm-12">
                <a class="btn btn-danger btn-xs tooltips remove_current_custom_certificate" data-placement="top" data-toggle="tooltip" data-original-title="Remove" style="float: right; margin-top: 15px;"><i class="fa fa-times"></i></a>
            </div>
            <section class="panel upload_doc_panel" id="upload_div" style="margin-top: 50px;">
                <div class="panel-body" style="padding: 0px;">
                    <form id="my-awesome-dropzone4" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                        <input type="hidden" class="" name="document_path" value="/uploads/certificate/">
                    </form>
                    <input type="hidden" name="upload_doc_id[]" class="upload_doc_id" id="upload_doc_id_1" value="">
                    <input type="hidden" name="upload_type" id="upload_type" value="multiple_upload">
                </div>
            </section>
        </div>
    </div><div class="clearfix"></div> <!-- custom_certificate_detail close -->
</div><!-- custom_certificate_all close -->
</div>
                                <div class="form-group col-md-12">
                                    <input type="hidden" name="standard_doc_id" id="upload_doc_id_certificate" value="">
<!--                                    <a data-href="{{ url('/dashboard/'.$project_id.'/certificate') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                    <a href="{{ url('/dashboard/'.$project_id.'/certificate') }}" class="btn btn-info sub-btn btn_back" onclick="return checkFormFilled('btn_back')">Back</a>
                                    <button type="submit" id="add_certificate_form" class="btn btn-info sub-btn first_button">Save</button>
                                    <a href="{{ url('/dashboard/'.$project_id.'/bond') }}" class="btn btn-info sub-btn continue_button" onclick="return checkFormFilled('continue_button')">Next Screen</a>
                                    <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
                                </div>
                                <div class="clearfix"></div>

<div style="display: none;">
<!-- <div> -->
    <div id="certificate_pdf_content" style="width:100%;">
        <style>
            .pdftable tr td{padding: 5px;}
        </style>
        <table style="width: 100%; padding: 5px;" class="pdftable" cellpadding="10" cellspacing="10">
            
            <tr><td colspan="2"><h1 style="color:green; text-align:center;">Certificates of Insurance Summary</h1></td></tr>
        
            <tr><td style="width:250px;"><strong>Contractor: </strong></td><td><span id="pdf_gen_contractor_name"></span><br><span id="pdf_gen_contractor_address"></span></td></tr>
            
        
        <tr><td><strong>Project: </strong></td><td><span id="pdf_gen_project_name"></span></td></tr>
        
        <tr><td><strong>General Liability Exp. Date: </strong></td><td><span id="pdf_gen_general_date"></span></td></tr>
        
        <tr><td><strong>General Liability Limit: </strong></td><td><span id="pdf_gen_general_limit"></span></td></tr>
        
        <tr><td><strong>Auto Liability Exp. Date: </strong></td><td><span id="pdf_gen_auto_date"></span></td></tr>
        
        <tr><td><strong>Auto Liability Limit: </strong></td><td><span id="pdf_gen_auto_limit"></span></td></tr>
        
        <tr><td><strong>Workers Comp Exp. Date: </strong></td><td><span id="pdf_gen_work_comp_date"></span></td></tr>
        
        <tr><td><strong>Workers Comp Limit: </strong></td><td><span id="pdf_gen_work_comp_limit"></span></td></tr>
        
        <tr><td><strong>Umbrella Liability Exp. Date: </strong></td><td><span id="pdf_gen_umbrella_date"></span></td></tr>
        
        <tr><td><strong>Umbrella Liability Limit: </strong></td><td><span id="pdf_gen_umbrella_limit"></span></td></tr>
        
        <tr><td colspan="2"><div id="custom_certificate_pdf" style="display: block;">

                </div></td></tr>
        
        </table>
<!--        <h1 style="color:green; text-align:center;">Certificates of Insurance Summary</h1>
        <div style="clear: both;"></div>
        <p style="width:70%;"><strong>Contractor: </strong><span id="pdf_gen_contractor_name"></span><br/>
            <span id="pdf_gen_contractor_address"></span></p>
        <div style="clear: both;"></div>
        <p><strong>Project: </strong><span id="pdf_gen_project_name"></span></p>
        <div style="clear: both;"></div>
        <p><strong>General Liability Exp. Date: </strong><span id="pdf_gen_general_date"></span></p>
        <div style="clear: both;"></div>
        <p><strong>General Liability Limit: </strong><span id="pdf_gen_general_limit"></span></p>
        <div style="clear: both;"></div>
        <p><strong>Auto Liability Exp. Date: </strong><span id="pdf_gen_auto_date"></span></p>
        <div style="clear: both;"></div>
        <p><strong>Auto Liability Limit: </strong><span id="pdf_gen_auto_limit"></span></p>
        <div style="clear: both;"></div>
        <p><strong>Workers Comp Exp. Date: </strong><span id="pdf_gen_work_comp_date"></span></p>
        <div style="clear: both;"></div>
        <p><strong>Workers Comp Limit: </strong><span id="pdf_gen_work_comp_limit"></span></p>
        <div style="clear: both;"></div>
        <p><strong>Umbrella Liability Exp. Date: </strong><span id="pdf_gen_umbrella_date"></span></p>
        <div style="clear: both;"></div>
        <p><strong>Umbrella Liability Limit: </strong><span id="pdf_gen_umbrella_limit"></span></p>
        <div style="clear: both;"></div>
        <div id="custom_certificate_pdf" style="display: block;">

        </div>
        <div style="clear: both;"></div>-->

    </div>
                                    <div class="row">
                                    <!-- <div class="form-group col-md-12">
                                        <label>Date</label>
                                        <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date="2016-09-01"  class="input-append date dpYears">
                                                <input type="text" readonly="" value="" size="16" class="form-control"  id="date_of_report">
                                                  <span class="input-group-btn add-on">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                  </span>
                                            </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="name_of_report">Document Name</label>
                                        <input type="text" class="form-control" id="name_of_report">
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group col-md-12">
                                        <label for="company_name">Company Name</label>
                                        <div class="loading_data" style="text-align: center;">
                                           <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                        </div>
                                        <select class="form-control" id="company_name">
                                        </select>
                                    </div>

                                    <div class=" col-md-12">
                                        <label for="standard_link" style="min-width: 200px;">Applicable</label>
                                        <label class="radio-inline">
                                          <input type="radio" name="applicable" id="applicable_yes" value="yes"> Yes
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="applicable" id="applicable_no" value="no"> No
                                        </label>
                                    </div>

                                    <div class=" col-md-12 hidden">
                                        <label for="standard_link" style="min-width: 200px;">Upload Document</label>
                                        <label class="radio-inline">
                                          <input type="radio" name="upload" id="upload_yes" value="yes"> Yes
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="upload" id="upload_no" value="no"> No
                                        </label>
                                    </div>

                                    <div class="col-md-12">
                                        <label>Type</label>
                                        <select class="form-control" id="type">
                                            <option value="swppp">SWPPP</option>
                                            <option value="wpcp">WPCP</option>
                                        </select>
                                    </div> -->

                                    <div class="col-md-6">
                                        <label>Status</label>
                                        <select class="form-control" id="status">
                                            <option value="active">Active</option>
                                            <option value="deactive">Inactive</option>
                                        </select>
                                    </div>


                                    <div class="form-group col-md-12">
                                        <!-- <input type="hidden" name="standard_upload" id="upload_doc_meta" value="swppp"> -->
                                        <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                                        <!-- <input type="hidden" name="standard_doc_id" id="upload_doc_id" value=""> -->
                                    </div>

                                    <div class="form-group col-md-12">
<!--                                        <a data-href="{{ url('/dashboard/'.$project_id.'/certificate') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                        <a href="{{ url('/dashboard/'.$project_id.'/certificate') }}" class="btn btn-info sub-btn btn_back1">Back</a>
                                        <button type="submit" class="btn btn-info sub-btn">Save</button>
                                        <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
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
<script src="{{ url('/resources/assets/dist/certificate_update.js') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone.js?v=1.0') }}"></script>
@include('include/footer')
