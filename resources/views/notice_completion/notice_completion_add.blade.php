        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
          <?php $project_id = Request::segment(2); ?>
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
                <h3 class="m-b-less">Add Notice of Completion</h3>
                <div class="state-information" style="width: 200px; ">
                    <!-- <div class="progress progress-striped active progress-sm m-b-20"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"><span class="sr-only">100% Complete</span></div></div> -->
                </div>
            </div>
            <!-- page head end-->


            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-lg-12">
                      <div id="alert_message"></div>
                        <section class="panel">
                            <div class="panel-body clearfix">
<div class="col-sm-6 nopadleft">
  <div class="form-group">
<!--    <label>Which would you like to do?</label><br/>-->
<!--    <label class="radio-inline">
      <input type="radio" name="noc_type" id="noc_type" value="complete_form_noc"> Complete NOC FORM
    </label><br/>-->
    <label class="radio-inline">
        <input checked="checked" type="radio" name="noc_type" id="noc_type" value="upload_form_noc"> Upload Recorded NOC
    </label>
  </div>
</div>
<div class="col-sm-6 nopadleft">
  <a href="{{ url('/resources/assets/pdf/Notice_of_Completion_Blank_Template.pdf') }}" class="btn btn-info sub-btn" target="_blank" style="float: right;">Print Blank NOC FORM</a>
</div>
<div class="clearfix"></div>
                              <div class="col-sm-6 nopadleft">
                                   <label class="upload_exist" style="display: none;">Upload Notice of Completion <span class="text-danger">*</span></label>
                                  <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone" class="upload_exist"  style="display: block;">
                                      <input type="hidden" name="document_path" value="/uploads/notice_completion/">
                                  </form>
                                  <input type="hidden" name="upload_type" id="upload_type" value="single_upload">
                                  <input type="hidden" name="standard_doc_id" id="upload_single_doc_id" value="">
                                  <input type="hidden" name="standard_upload" id="upload_doc_meta" value="notice_completion">
                                  <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                              </div>
                              <div class="clearfix"></div>


                            <div class="clearfix"></div>
                            <div id="notice-addForm" style="display: none;">
                              <div class="col-md-5 leftcol nopadleft">
                                <div class="col-md-12 m-b-30">
                                  <label class="clearfix col-md-12 nopadleft">Recording Requested By: <span class="text-danger">*</span></label>
                                  <input type="text" class="clearfix" id="noc_rec_text">
                                  <div class="clearfix"></div>
                                </div>
                                <div class="col-md-12">
                                  <div class="col-md-12 nopadleft">
                                    <p>When Recorded Mail To:</p>
                                  </div>
                                </div>
                                <div class="col-md-12 m-b-10">
                                  <label for="" class="col-md-4 col-xs-12 nopadleft">Name <span class="text-danger">*</span></label>
                                  <input type="text" id="noc_rec_name" value="" class="col-md-8 col-xs-12">
                                </div>
                                <div class="col-md-12 m-b-10">
                                  <label for="" class="col-md-4 col-xs-12 nopadleft">Street Address <span class="text-danger">*</span></label>
                                  <input type="text" id="noc_rec_street" value="" class="col-md-8 col-xs-12">
                                </div>
                                <div class="col-md-12 m-b-10">
                                  <label for="" class="col-md-4 col-xs-12 nopadleft">City & State <span class="text-danger">*</span></label>
                                  <input type="text" id="noc_rec_adress" value="" class="col-md-8 col-xs-12">
                                </div>
                                <div class="clearfix"></div>
                              </div> <!--  ..leftcol   -->
                              <div class="col-md-7 rightcol"></div>
                              <diiv class="row">
                                <div class="col-md-12">
                                  <hr>
                                  <div class="col-md-6 col-xs-12 pull-right">
                                    <p class="text-center capitalize m-b-0">Space Above This Line For Recorders Use</p>
                                  </div>
                                </div>
                              </diiv>
                              <div class="row">
                                <div class="col-md-12">
                                  <h2 class="text-center capitalize m-b-0 m-t-0"><strong>Notice Of Completion</strong></h2>
                                  <p class="text-center">(CA Civil Code §§ 8180-8190, 8100-8118, 9200-9208)</p>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <p class="capitalize font-19">Notice is here by given that:</p>
                                </div>
                              </div>
                              <div class="row">
                                <ol class="list-notices">
                                  <li>
                                    <p>The undersigned is an owner of an interest of estate in the hereinafter described real property, the nature of which interest or estate is:</p>
                                    <div class="col-md-8 col-xs-12 nopadleft">
                                      <input type="text" id="noc_notice_text_1" value="" class="col-xs-12 pdf-inputfield">
                                    </div>
                                    <div class="col-md-4">(e.g. fee, leasehold, joint tenancy, etc.)</div>
                                  </li>
                                  <li>
                                    <p>The full name and address of the undersigned owner or reputed owner and of all co-owners or reputed co-owners are:</p>
                                    <div class="col-md-7 col-xs-12 nopadleft">
                                      <span class="col-md-1 col-xs-12 col-sm-1 nopadleft">Name</span>
                                      <input type="text" id="noc_notice_text_2" value="" class="col-md-4 col-xs-12 col-sm-3 pdf-inputfield">
                                      <span class="col-md-3 col-xs-12 col-sm-3 lebel-md">Street and No.</span>
                                      <input type="text" id="noc_notice_text_3" value="" class="col-md-4 col-xs-12 col-sm-3 pdf-inputfield">
                                    </div>
                                    <div class="col-md-5 col-xs-12 nopadleft">
                                      <span class="col-md-1 col-xs-12 col-sm-1 nopadleft topspace">City</span>
                                      <input type="text"  id="noc_notice_text_4" value="" class="col-md-4 col-xs-12 col-sm-3 topspace pdf-inputfield">
                                      <span class="col-md-3 col-xs-12 col-sm-3 lebel-md topspace">State</span>
                                      <input type="text"  id="noc_notice_text_5" value="" class="col-md-4 col-xs-12 col-sm-3 topspace pdf-inputfield">
                                    </div>
                                      <!-- <div class="col-md-12 nopadleft">
                                        <input type="text" name="" value="" class="col-xs-12 topspace-bigfield pdf-inputfield">
                                      </div>
                                      <div class="col-md-12 nopadleft">
                                        <input type="text" name="" value="" class="col-xs-12 topspace-bigfield pdf-inputfield">
                                      </div> -->
                                  </li>
                                  <li>
                                    <p>The name and address of the direct contractor for the work of improvement as a whole is:</p>
                                      <div class="col-md-12 nopadleft">
                                        <input type="text" id="noc_notice_text_6" value="" class="col-xs-12 pdf-inputfield">
                                      </div>
                                  </li>
                                  <li>
                                    <p>This notice is given for (check one):</p>
                                    <div class="col-xs-12 nopadleft">
                                      <input type="checkbox"  id="noc_notice_text_7" name="" class="inline-block" style="margin-top: 0;">
                                      <label for="" class="inline-block m-b-0">Completion of the work of improvement as a whole.</label>
                                    </div>
                                    <div class="col-xs-12 nopadleft">
                                      <input type="checkbox"  id="noc_notice_text_8" name="" class="inline-block" style="margin-top: 0;">
                                      <label for="" class="inline-block m-b-0">Completion of a contract for a particular portion of the work of improvement (per CA Civ. Code § 8186).</label>
                                    </div>
                                  </li>
                                  <li>
                                    <p>If this notice is given only of completion of a contract for a particular portion of the work of improvement (as provided in CA Civ. Code § 8186), the name and address of the direct contractor under that contract is:</p>
                                    <div class="col-md-12 nopadleft">
                                        <input type="text"  id="noc_notice_text_9" value="" class="col-xs-12 m-b-10 pdf-inputfield">
                                    </div>
                                  </li>
                                  <li>
                                    <p>The name and address of the construction lender, if any, is:</p>
                                    <div class="col-md-12 nopadleft">
                                        <input type="text" id="noc_notice_text_10" value="" class="col-xs-12 m-b-10 pdf-inputfield">
                                    </div>
                                  </li>
                                  <li>
                                    On the <span class="inline-block day"><input type="text" id="noc_notice_text_11" value="" class="col-xs-12 pdf-inputfield"></span> day of <span class="inline-block day"><input type="text" id="noc_notice_text_12" value="" class="col-xs-12 pdf-inputfield"></span>,20<span class="inline-block year"><input type="text" name="" value="" class="col-xs-12 pdf-inputfield" id="noc_notice_text_13"></span>
                                    there was completed upon the herein described property a work of improvement as a whole (or a particular portion of the work of improvement as provided in CA Civ. Code § 8186) a general description of the work provided:
                                    <div class="col-md-12 nopadleft">
                                        <input type="text" name="" value="" class="col-xs-12 m-b-10 pdf-inputfield" id="noc_notice_text_14">
                                    </div>
                                  </li>
                                  <li>
                                    The real property herein referred to is situated in the City of
                                    <span class="inline-block">
                                      <input type="text" name="" value="" class="col-xs-12 m-b-10 pdf-inputfield" id="noc_notice_text_15">
                                    </span>, Country of <span class="inline-block">
                                      <input type="text" name="" value="" class="col-xs-12 m-b-10 pdf-inputfield" id="noc_notice_text_16">
                                    </span>
                                    State of California, and is described as follows:
                                    <div class="col-md-12 nopadleft">
                                        <input type="text" name="" value="" class="col-xs-12 m-b-10 pdf-inputfield" id="noc_notice_text_17">
                                    </div>
                                  </li>
                                  <li>
                                    The street address of said property is:
                                    <div class="col-md-12 nopadleft">
                                        <input type="text" name="" value="" class="col-xs-12 m-b-10 pdf-inputfield" id="noc_notice_text_18">
                                    </div>
                                  </li>
                                  <li>
                                    If this Notice of Completion is signed by the owner’s successor in interest, the name and address of the successor’s transferor is:
                                    <div class="col-md-12 nopadleft">
                                        <input type="text" name="" value="" class="col-xs-12 m-b-10 pdf-inputfield" id="noc_notice_text_19">
                                    </div>
                                    <p>I certify (or declare) under penalty of perjury under the laws of the State of California that the foregoing is true and correct.</p>
                                  </li>
                                </ol>
                                <div class="clearfix"></div>
                                <div class="col-md-5 col-xs-12">
                                  Date: <span class="inline-block form-date">
                                        <input type="text" name="" value="" class="col-xs-12 m-b-12 pdf-inputfield" id="noc_notice_text_20">
                                      </span>
                                </div>
                                <div class="col-md-7">
                                  By: <span class="inline-block form-by">
                                        <input type="text" name="" value="" class="col-xs-12 m-b-12 pdf-inputfield" id="noc_notice_text_21">
                                      </span>
                                      <div class="clearfix"></div>
                                      <span class="text-center col-xs-12">
                                        Signature of Owner of Owner’s Authorized Agent
                                      </span>
                                      <span class="inline-block form-by print-name">
                                        <input type="text" name="" value="" class="col-xs-12 m-b-12 pdf-inputfield" id="noc_notice_text_22">
                                      </span>
                                      <div class="clearfix"></div>
                                      <span class="text-center">
                                        <span class="print-name-title">Print Name</span>
                                      </span>
                                </div>
                              </div>
                              <div class="row">
                                <h3 class="text-center capitalize m-b-20"><strong>Verification</strong></h3>
                              </div>
                              <div class="col-xs-12">
                                I, <span class="inline-block">
                                      <input type="text" name="" value="" class="col-xs-12 m-b-0 pdf-inputfield" id="noc_ver_text_1">
                                   </span>
                                   , state: I am the <span class="inline-block">
                                      <input type="text" name="" value="" class="col-xs-12 m-b-0 pdf-inputfield"  id="noc_ver_text_2">
                                   </span> (“Owner”, “President”, “Authorized Agent”, “Partner”, etc.) of the Owner identified in the foregoing Notice of Completion. I have read said Notice of Completion and know the contents thereof; the same is true of my own knowledge.
                                   <p>I declare under penalty of perjury under the laws of the State of California that the foregoing is true and correct.</p>
                              </div>
                              <div class="col-xs-12">
                                Executed on
                                <span class="inline-block">
                                    <input type="text"  id="noc_ver_text_3" value="" class="col-xs-12 m-b-0 pdf-inputfield">
                                </span>,<span class="inline-block">
                                    <input type="text" id="noc_ver_text_4" value="" class="col-xs-12 m-b-0 pdf-inputfield">
                                </span>(date), at
                                <span class="inline-block">
                                    <input type="text" id="noc_ver_text_5" value="" class="col-xs-12 m-b-0 pdf-inputfield">
                                </span>(City),
                                <span class="inline-block">
                                    <input type="text" id="noc_ver_text_6" value="" class="col-xs-12 m-b-0 pdf-inputfield">
                                </span>(State).
                              </div>
                              <div class="col-xs-12 m-t-20">
                                <div class="pull-right col-md-6 text-center">
                                  <input type="text" id="noc_ver_text_7" value="" class="col-xs-12 m-b-0 pdf-inputfield">
                                  <span class="">Signature of Owner or Owner’s Authorized Agent</span>
                                </div>
                              </div>
                              <div class="col-xs-12 m-t-30 m-b-20">
                                <h3 class="text-center capitalize m-b-20">
                                  <strong>Proof Of Service Declaration</strong>
                                </h3>
                              </div>
                              <div class="col-xs-12">
                                I, <span class="inline-block proofdec-field">
                                      <input type="text" name="" value="" class="col-xs-12 m-b-0 pdf-inputfield" id="noc_ser_text_1">
                                   </span>, declare that I served copies of the above <strong class="capitalize">Notice Of Completion</strong>, (Check appropriate box):
                                   <ul class="lower-alpha">
                                     <li class="col-xs-12 col-md-12 nopadleft">
                                      <div class="col-md-1">a.</div>
                                      <div class="col-xs-12 col-md-2">
                                        <input type="checkbox"  id="noc_ser_text_2" value="">
                                      </div>
                                      <div class="col-xs-12 col-md-9">
                                        By personally delivering copies to
                                        <span class="inline-block deliver-field-a">
                                          <input type="text"  id="noc_ser_text_3" value="" class="col-xs-12 m-b-0 pdf-inputfield">
                                        </span>
                                        (name(s) and title(s)

                                        of person served) at
                                        <span class="inline-block deliver-field-b">
                                          <input type="text"  id="noc_ser_text_4" value="" class="col-xs-12 m-b-0 pdf-inputfield">
                                        </span> (address),
                                         <p>on
                                          <span class="inline-block deliver-field-c">
                                            <input type="text"  id="noc_ser_text_5" value="" class="col-xs-12 m-b-0 pdf-inputfield">
                                          </span>,<span class="inline-block deliver-field-d">
                                            <input type="text"  id="noc_ser_text_6" value="" class="col-xs-12 m-b-0 pdf-inputfield">
                                          </span>(date), at
                                          <span class="inline-block deliver-field-e">
                                            <input type="text"  id="noc_ser_text_7" value="" class="col-xs-12 m-b-0 pdf-inputfield">
                                          </span>,
                                          <span class="inline-block">
                                            <input type="text"  id="noc_ser_text_8" value="" class="col-xs-12 m-b-0 pdf-inputfield">
                                          </span>.m. (time)</p>
                                        </div>
                                     </li>
                                     <li class="col-xs-12 col-md-12 nopadleft">
                                       <div class="col-md-1">b.</div>
                                       <div class="col-xs-12 col-md-2"><input type="checkbox"  id="noc_ser_text_9" value=""></div>
                                        <div class="col-xs-12 col-md-9">
                                          By Registered or Certified Mail, Express Mail or Overnight Delivery by an express service carrier, addressed to each of the parties at the address shown above on
                                           <span class="inline-block deliver-field-c">
                                              <input type="text"  id="noc_ser_text_10" value="" class="col-xs-12 m-b-0 pdf-inputfield">
                                            </span>,<span class="inline-block deliver-field-d">
                                              <input type="text"  id="noc_ser_text_11" value="" class="col-xs-12 m-b-0 pdf-inputfield">
                                            </span>(date).
                                        </div>
                                      </li>
                                     <li class="col-xs-12 col-md-12 nopadleft">
                                       <div class="col-md-1">c.</div>
                                       <div class="col-xs-12 col-md-2"><input type="checkbox"  id="noc_ser_text_12" value=""></div>
                                        <div class="col-xs-12 col-md-9">
                                          By leaving the notice and mailing a copy in the manner provided in § 415.20 of the California Code of Civil Procedure for service of Summons and Complaint in a Civil Action.
                                        </div>
                                      </li>
                                   </ul>
                              </div>
                              <div class="clearfix"></div>
                              <div class="col-md-12">
                                I declare under penalty of perjury under the laws of the State of California that the foregoing is true and correct.
                              </div>
                              <div class="clearfix">  </div>
                              <div class="col-xs-12 col-md-12">
                                Executed on
                                <span class="inline-block">
                                    <input type="text"  id="noc_ser_text_13" value="" class="col-xs-12 m-b-0 pdf-inputfield">
                                </span>,<span class="inline-block">
                                    <input type="text"  id="noc_ser_text_14" value="" class="col-xs-12 m-b-0 pdf-inputfield">
                                </span>(date), at
                                 <span class="inline-block">
                                    <input type="text"  id="noc_ser_text_15" value="" class="col-xs-12 m-b-0 pdf-inputfield">
                                </span> (City),
                                <span class="inline-block">
                                    <input type="text"  id="noc_ser_text_16" value="" class="col-xs-12 m-b-0 pdf-inputfield">
                                </span> (State).
                              </div>
                              <div class="clearfix"></div>
                              <div class="col-md-6 pull-right m-t-30">
                                <input type="text"  id="noc_ser_text_17" value="" class="col-xs-12 m-b-0 pdf-inputfield">
                                <p class="text-center">(Signature of Person Making Service)</p>
                              </div>
                              <div class="clearfix"></div>
                              <div class="col-xs-12 col-md-12 m-t-30">
                                <div class="col-xs-12 col-md-4">
                                  <div class="square-box-pdf"></div>
                                </div>
                                <div class="col-md-8 nopadright">
                                  <span class="title-contry capitalize">
                                    State of California <br> County Of
                                  </span>
                                  <span class="inline-block country-name">
                                      <input type="text" id="noc_con_text_1" value="" class="col-xs-12 m-b-0 pdf-inputfield">
                                  </span>
                                  <div class="clearfix"></div>
                                  <div class="col-md-12">
                                      On <span class="inline-block">
                                            <input type="text" id="noc_con_text_2" value="" class="col-xs-12 m-b-0 pdf-inputfield" size="60">
                                          </span>,<span class="inline-block deliver-field-d">
                                            <input type="text" id="noc_con_text_3" value="" class="col-xs-12 m-b-0 pdf-inputfield">
                                          </span>(date),  before me,
                                          <span class="inline-block">
                                            <input type="text" id="noc_con_text_4" value="" class="col-xs-12 m-b-0 pdf-inputfield" size="50">
                                         </span>, Notary Public (name and title of officer) personally appeared <span class="inline-block">
                                            <input type="text" id="noc_con_text_5" value="" class="col-xs-12 m-b-0 pdf-inputfield" size="30">
                                         </span> who proved to me on the basis of satisfactory evidence to be the person(s) whose name(s) is/are subscribed to the within instrument and acknowledged to me that he/she/they executed the same in his/her/their authorized capacity(ies), and that by his/her/their signature(s) on the instrument the person(s), or the entity upon behalf of which the person(s) acted, executed the instrument.
                                         <br>
                                        <p class="m-t-10">I certify under PENALTY OF PURJURY under the laws of the State of California that the foregoing paragraph is true and correct.</p>
                                        <p class="m-t-10">Witness my hand and official seal.</p>
                                        <div class="clearfix"></div>
                                        <div class="pull-right m-t-0">
                                          <input type="text" id="noc_con_text_6" value="" class="col-xs-12 m-b-0 pdf-inputfield" size="50">
                                          <p>Signature</p>
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </div><!--      ##notice-addForm       -->
                            <div class="clearfix"></div>

                            <div class="col-sm-12 nopadleft m-t-20" id="potential_claimants" style="display: block;">
                              <div class="form-group clearfix">
                                  <label class="nopadleft col-lg-12 col-sm-12 control-label mt-6">
                                  Would you like to provide a copy of the NOC to all potential claimants?
                                  </label>
    <label class="radio-inline">
      <input type="radio" name="noc_potential" id="noc_potential" value="yes"> Yes
    </label><br/>
    <label class="radio-inline">
      <input type="radio" name="noc_potential" id="noc_potential" value="no"> No
    </label>
                              </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Date Filed on <span class="text-danger">*</span></label>
                                <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                        <input type="text" readonly="" value="" size="16" class="form-control"  id="date_noc_filed">
                                          <span class="input-group-btn add-on">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                          </span>
                                    </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="project_type">Improvement Type <span class="text-danger">*</span></label><button class="label label-warning add-impvtypes" style="margin-bottom: 5px;">Add New Improvement Type</button>
                                <div id="project_type_selected" style=""></div>
                                <select class="form-control project_type_dropdown" id="project_type_dropdown" placeholder="Select Improvement Types">
                                    <option>Select Improvement Types</option>
                                </select>
                            </div>
                            <div class="clearfix"></div>    
                            <div class="form-group col-md-12 nopadleft">

<!--                              <a data-href="{{ url('/dashboard/'.$project_id.'/notice_completion') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                              <a href="{{ url('/dashboard/'.$project_id.'/notice_completion') }}" class="btn btn-info sub-btn btn_back" onclick="return checkFormFilled('btn_back')">Back</a>
                              <button type="submit" id="add_noc" class="first_button btn btn-info sub-btn">Save</button>
                              <a href="{{ url('/dashboard/'.$project_id.'/acceptance_letter') }}" class="btn btn-info sub-btn continue_button" onclick="return checkFormFilled('continue_button')">Next Screen</a>
                              <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
                            </div>

                            <hr>
                            <hr>





                            <div id="notice-addForm-values" class="pdf-field-w-values" style="display: none;">
                            <!-- <div id="notice-addForm-values" class="pdf-field-w-values"> -->
                              <div class="col-md-5 leftcol">
                                <div class="col-md-12 m-b-30">
                                  <label class="clearfix col-md-12 nopadleft">Recording Requested By:</label>
                                  <strong><span class="col-md-6 clearfix" id="pdf_noc_rec_text"></span></strong>
                                  <div class="clearfix"></div>
                                </div>
                                <div class="col-md-12"">
                                  <div class="col-md-12 nopadleft">
                                    <p>When Recorded Mail To:</p>
                                  </div>
                                </div>
                                <div class="col-md-12 m-b-10">
                                  <label for="" class="col-md-4 col-xs-12 nopadleft">Name</label>
                                  <strong><span  class="col-md-8 col-xs-12" id="pdf_noc_rec_name"></span></strong>
                                </div>
                                <div class="col-md-12 m-b-10"">
                                  <label for="" class="col-md-4 col-xs-12 nopadleft">Street Address</label>
                                  <strong><span class="col-md-8 col-xs-12" id="pdf_noc_rec_street"></span></strong>
                                </div>
                                <div class="col-md-12 m-b-10"">
                                  <label for="" class="col-md-4 col-xs-12 nopadleft">City &amp; State</label>
                                  <strong><span class="col-md-8 col-xs-12" id="pdf_noc_rec_adress"></span></strong>
                                </div>
                                <div class="clearfix"></div>
                              </div> <!--  ..leftcol   -->
                              <div class="col-md-7 rightcol"></div>
                              <diiv class="row">
                                <div class="col-md-12">
                                  <hr>
                                  <div class="col-md-6 col-xs-12 pull-right">
                                    <p class="text-center capitalize m-b-0">Space Above This Line For Recorders Use</p>
                                  </div>
                                </div>
                              </diiv>
                              <div class="row">
                                <div class="col-md-12">
                                  <h2 class="text-center capitalize m-b-0 m-t-0"><strong>Notice Of Completion</strong></h2>
                                  <p class="text-center">(CA Civil Code §§ 8180-8190, 8100-8118, 9200-9208)</p>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <p class="capitalize font-19">Notice is here by given that:</p>
                                </div>
                              </div>
                              <div class="row">
                                <ol class="list-notices">
                                  <li>
                                    <p>The undersigned is an owner of an interest of estate in the hereinafter described real property, the nature of which interest or estate is:</p>
                                    <div class="col-md-8 col-xs-12 nopadleft">
                                      <strong><span class="col-xs-12 pdf-inputfield" id="pdf_noc_notice_text_1"></span></strong>
                                    </div>
                                    <div class="col-md-4">(e.g. fee, leasehold, joint tenancy, etc.)</div>
                                  </li>
                                  <li>
                                    <p>The full name and address of the undersigned owner or reputed owner and of all co-owners or reputed co-owners are:</p>
                                    <div class="col-md-7 col-xs-12 nopadleft">
                                      <span class="col-md-1 col-xs-12 col-sm-1 nopadleft">Name</span>
                                      <strong><span class="col-md-4 col-xs-12 col-sm-3 pdf-inputfield"  id="pdf_noc_notice_text_2"></span></strong>
                                      <span class="col-md-3 col-xs-12 col-sm-3 lebel-md">Street and No.</span>
                                      <strong><span class="col-md-4 col-xs-12 col-sm-3 pdf-inputfield"  id="pdf_noc_notice_text_3"></span></strong>
                                    </div>
                                    <div class="col-md-5 col-xs-12 nopadleft">
                                      <span class="col-md-1 col-xs-12 col-sm-1 nopadleft topspace">City</span>
                                      <strong><span class="col-md-4 col-xs-12 col-sm-3 topspace pdf-inputfield" id="pdf_noc_notice_text_4"></span></strong>
                                      <span class="col-md-2 col-xs-12 col-sm-3 lebel-md topspace">State</span>
                                      <strong><span class="col-md-4 col-xs-12 col-sm-3 topspace pdf-inputfield" id="pdf_noc_notice_text_5"></span></strong>
                                    </div>
                                  </li>
                                  <li>
                                    <p>The name and address of the direct contractor for the work of improvement as a whole is:</p>
                                      <div class="col-md-12 nopadleft">
                                        <strong><span class="col-xs-12 pdf-inputfield" id="pdf_noc_notice_text_6"></span></strong>
                                      </div>
                                  </li>
                                  <li>
                                    <p>This notice is given for (check one):</p>
                                    <div class="col-xs-12 nopadleft">
                                      <strong><span class="inline-block" style="margin-top: 0;" id="pdf_noc_notice_text_7"></span></strong>
                                      <label for="" class="inline-block m-b-0">Completion of the work of improvement as a whole.</label>
                                    </div>
                                    <div class="col-xs-12 nopadleft">
                                      <strong><span class="inline-block" style="margin-top: 0;" id="pdf_noc_notice_text_8"></span></strong>
                                      <label for="" class="inline-block m-b-0">Completion of a contract for a particular portion of the work of improvement (per CA Civ. Code § 8186).</label>
                                    </div>
                                  </li>
                                  <li>
                                    <p>If this notice is given only of completion of a contract for a particular portion of the work of improvement (as provided in CA Civ. Code § 8186), the name and address of the direct contractor under that contract is:</p>
                                    <div class="col-md-12 nopadleft">
                                        <strong><span class="col-xs-12 m-b-10 pdf-inputfield" id="pdf_noc_notice_text_9"></span></strong>
                                    </div>
                                  </li>
                                  <li>
                                    <p>The name and address of the construction lender, if any, is:</p>
                                    <div class="col-md-12 nopadleft">
                                        <strong><span class="col-xs-12 m-b-10 pdf-inputfield" id="pdf_noc_notice_text_10"></span></strong>
                                    </div>
                                  </li>
                                  <li>
                                    On the <div class="inline-block day">
                                    <strong><span class="col-xs-12 pdf-inputfield" id="pdf_noc_notice_text_11"></span></strong>
                                    </div> day of <div class="inline-block day"><strong><span class="col-xs-12 pdf-inputfield" id="pdf_noc_notice_text_12"></span></strong></div>,20<div class="inline-block year"><strong><span class="col-xs-12 pdf-inputfield" id="pdf_noc_notice_text_13"></span></strong></div>
                                    there was completed upon the herein described property a work of improvement as a whole (or a particular portion of the work of improvement as provided in CA Civ. Code § 8186) a general description of the work provided:
                                    <div class="col-md-12 nopadleft">
                                        <strong><span class="col-xs-12 m-b-10 pdf-inputfield" id="pdf_noc_notice_text_14"></span></strong>
                                    </div>
                                  </li>
                                  <li>
                                    The real property herein referred to is situated in the City of
                                    <span class="inline-block">
                                      <strong><span class="col-xs-12 m-b-10 pdf-inputfield field1p8" id="pdf_noc_notice_text_15"></span>
                                      </strong>
                                    </span>, Country of <span class="inline-block">
                                      <strong><span class="col-xs-12 m-b-10 pdf-inputfield" id="pdf_noc_notice_text_16"></span>
                                      </strong>
                                    </span>
                                    State of California, and is described as follows:
                                    <div class="col-md-12 nopadleft">
                                        <strong><span class="col-xs-12 m-b-10 pdf-inputfield" id="pdf_noc_notice_text_17"></span></strong>
                                    </div>
                                  </li>
                                  <li>
                                    The street address of said property is:
                                    <div class="col-md-12 nopadleft">
                                        <strong><span class="col-xs-12 m-b-10 pdf-inputfield" id="pdf_noc_notice_text_18"></span></strong>
                                    </div>
                                  </li>
                                  <li>
                                    If this Notice of Completion is signed by the owner’s successor in interest, the name and address of the successor’s transferor is:
                                    <div class="col-md-12 nopadleft">
                                        <strong><span class="col-xs-12 m-b-10 pdf-inputfield" id="pdf_noc_notice_text_19"></span></strong>
                                    </div>
                                    <p>I certify (or declare) under penalty of perjury under the laws of the State of California that the foregoing is true and correct.</p>
                                  </li>
                                </ol>
                                <div class="clearfix"></div>
                                <div class="col-md-5 col-xs-12">
                                  Date: <span class="inline-block form-date">
                                        <strong><span class="col-xs-12 m-b-12 pdf-inputfield" id="pdf_noc_notice_text_20"></span></strong>
                                      </span>
                                </div>
                                <div class="col-md-7">
                                  By: <span class="inline-block form-by">
                                        <strong><span class="col-xs-12 m-b-12 pdf-inputfield" id="pdf_noc_notice_text_21"></span></strong>
                                      </span>
                                      <div class="clearfix"></div>
                                      <span class="text-center col-xs-12">
                                        Signature of Owner of Owner’s Authorized Agent
                                      </span>
                                      <span class="inline-block form-by print-name">
                                        <strong><span class="col-xs-12 m-b-12 pdf-inputfield" id="pdf_noc_notice_text_22"></span></strong>
                                      </span>
                                      <div class="clearfix"></div>
                                      <span class="text-center">
                                        <span class="print-name-title">Print Name</span>
                                      </span>
                                </div>
                              </div>
                              <div class="row">
                                <h3 class="text-center capitalize m-b-20"><strong>Verification</strong></h3>
                              </div>
                              <div class="col-xs-12">
                                I, <span class="inline-block">
                                      <strong><span class="col-xs-12 m-b-0 pdf-inputfield" id="pdf_noc_ver_text_1"></span>
                                      </strong>
                                   </span>
                                   , state: I am the <span class="inline-block">
                                      <strong><span class="col-xs-12 m-b-0 pdf-inputfield" id="pdf_noc_ver_text_2"></span>
                                      </strong>
                                   </span> (“Owner”, “President”, “Authorized Agent”, “Partner”, etc.) of the Owner identified in the foregoing Notice of Completion. I have read said Notice of Completion and know the contents thereof; the same is true of my own knowledge.
                                   <p>I declare under penalty of perjury under the laws of the State of California that the foregoing is true and correct.</p>
                              </div>
                              <div class="col-xs-12">
                                Executed on
                                <span class="inline-block">
                                    <strong><span class="col-xs-12 m-b-0 pdf-inputfield field1p8" id="pdf_noc_ver_text_3"></span>
                                    </strong>
                                </span>,<span class="inline-block">
                                    <strong><span class="col-xs-12 m-b-0 pdf-inputfield field1p8" id="pdf_noc_ver_text_4"></span>
                                    </strong>
                                </span>(date), at
                                <span class="inline-block">
                                    <strong><span class="col-xs-12 m-b-0 pdf-inputfield field1p8" id="pdf_noc_ver_text_5"></span>
                                    </strong>
                                </span>(City),
                                <span class="inline-block">
                                    <strong><span class="col-xs-12 m-b-0 pdf-inputfield field1p8" id="pdf_noc_ver_text_6"></span>
                                    </strong>
                                </span>(State).
                              </div>
                              <div class="col-xs-12 m-t-20">
                                <div class="pull-right col-md-6 text-center">
                                  <strong><span class="col-xs-12 m-b-0 pdf-inputfield" id="pdf_noc_ver_text_7"></span>
                                  </strong>
                                  <span class="">Signature of Owner or Owner’s Authorized Agent</span>
                                </div>
                              </div>
                              <div class="col-xs-12 m-t-30 m-b-20">
                                <h3 class="text-center capitalize m-b-20">
                                  <strong>Proof Of Service Declaration</strong>
                                </h3>
                              </div>
                              <div class="col-xs-12">
                                I, <span class="inline-block proofdec-field">
                                      <strong><span class="col-xs-12 m-b-0 pdf-inputfield" id="pdf_noc_ser_text_1"></span>
                                      </strong>
                                   </span>, declare that I served copies of the above <strong class="capitalize"> Notice Of Completion</strong>, (Check appropriate box):
                                   <ul class="lower-alpha">
                                     <li class="col-xs-12 col-md-12 nopadleft">
                                      <div class="col-md-1">a.</div>
                                      <div class="col-xs-12 col-md-2">
                                        <span id="pdf_noc_ser_text_2"></span>
                                      </div>
                                      <div class="col-xs-12 col-md-9">
                                        By personally delivering copies to
                                        <span class="inline-block deliver-field-a">
                                          <strong><span class="col-xs-12 m-b-0 pdf-inputfield" id="pdf_noc_ser_text_3"></span></strong>
                                        </span>
                                        (name(s) and title(s) of person served) at
                                        <span class="inline-block deliver-field-b">
                                          <strong><span class="col-xs-12 m-b-0 pdf-inputfield" id="pdf_noc_ser_text_4"></span></strong>
                                        </span> (address),
                                         <p>on
                                          <span class="inline-block deliver-field-c">
                                            <strong><span class="col-xs-12 m-b-0 pdf-inputfield" id="pdf_noc_ser_text_5"></span></strong>
                                          </span>,<span class="inline-block deliver-field-d">
                                            <strong><span class="col-xs-12 m-b-0 pdf-inputfield" id="pdf_noc_ser_text_6"></span></strong>
                                          </span>(date), at
                                          <span class="inline-block deliver-field-e">
                                            <strong><span class="col-xs-12 m-b-0 pdf-inputfield" id="pdf_noc_ser_text_7"></span></strong>
                                          </span>,
                                          <span class="inline-block">
                                            <strong><span  class="col-xs-12 m-b-0 pdf-inputfield" id="pdf_noc_ser_text_8"></span></strong>
                                          </span>.m. (time)</p>
                                        </div>
                                     </li>
                                     <li class="col-xs-12 col-md-12 nopadleft">
                                       <div class="col-md-1">b.</div>
                                       <div class="col-xs-12 col-md-2"><span id="pdf_noc_ser_text_9"></span></div>
                                        <div class="col-xs-12 col-md-9">
                                          By Registered or Certified Mail, Express Mail or Overnight Delivery by an express service carrier, addressed to each of the parties at the address shown above on
                                           <span class="inline-block deliver-field-c">
                                              <strong><span class="col-xs-12 m-b-0 pdf-inputfield" id="pdf_noc_ser_text_10"></span></strong>
                                            </span>,<span class="inline-block deliver-field-d">
                                              <strong><span class="col-xs-12 m-b-0 pdf-inputfield" id="pdf_noc_ser_text_11"></span></strong>
                                            </span>,<span class="inline-block deliver-field-d">
                                              <strong><span class="col-xs-12 m-b-0 pdf-inputfield" id="pdf_noc_ser_text_12"></span></strong>
                                            </span>(date).
                                        </div>
                                      </li>
                                     <li class="col-xs-12 col-md-12 nopadleft">
                                       <div class="col-md-1">c.</div>
                                       <div class="col-xs-12 col-md-2"><span id="pdf_noc_ser_text_13"></span></div>
                                        <div class="col-xs-12 col-md-9">
                                          By leaving the notice and mailing a copy in the manner provided in § 415.20 of the California Code of Civil Procedure for service of Summons and Complaint in a Civil Action.
                                        </div>
                                      </li>
                                   </ul>
                              </div>
                              <div class="clearfix"></div>
                              <div class="col-md-12">
                                I declare under penalty of perjury under the laws of the State of California that the foregoing is true and correct.
                              </div>
                              <div class="clearfix">  </div>
                              <div class="col-xs-12 col-md-12">
                                Executed on
                                <span class="inline-block">
                                    <strong><span class="col-xs-12 m-b-0 pdf-inputfield" id="pdf_noc_ser_text_13"></span></strong>
                                </span>,<span class="inline-block">
                                    <strong><span class="col-xs-12 m-b-0 pdf-inputfield" id="pdf_noc_ser_text_14"></span></strong>
                                </span>(date), at
                                 <span class="inline-block">
                                    <strong><span class="col-xs-12 m-b-0 pdf-inputfield" id="pdf_noc_ser_text_15"></span></strong>
                                </span> (City),
                                <span class="inline-block">
                                    <strong><span class="col-xs-12 m-b-0 pdf-inputfield" id="pdf_noc_ser_text_16"></span></strong>
                                </span> (State).
                              </div>
                              <div class="clearfix"></div>
                              <div class="col-md-6 pull-right m-t-30">
                                <strong><span class="col-xs-12 m-b-0 pdf-inputfield" id="pdf_noc_ser_text_17"></span></strong>
                                <p class="text-center">(Signature of Person Making Service)</p>
                              </div>
                              <div class="clearfix"></div>
                              <div class="col-xs-12 col-md-12 m-t-30">
                                <div class="col-xs-12 col-md-4">
                                  <div class="square-box-pdf"></div>
                                </div>
                                <div class="col-md-8 nopadright">
                                  <span class="title-contry capitalize">
                                    State of California <br> County Of
                                  </span>
                                  <div class="clearfix"></div>
                                  <span class="inline-block country-name">
                                      <strong><span class="col-xs-12 m-b-0 pdf-inputfield" id="pdf_noc_con_text_1"></span></strong>
                                  </span>
                                  <div class="clearfix"></div>
                                  <div class="col-md-12">
                                      On <span class="inline-block">
                                            <strong><span class="col-xs-12 m-b-0 pdf-inputfield fielda" id="pdf_noc_con_text_2"></span></strong>
                                          </span>,<span class="inline-block deliver-field-d">
                                            <strong><span class="col-xs-12 m-b-0 pdf-inputfield fieldb" id="pdf_noc_con_text_3"></span></strong>
                                          </span>(date),  before me,
                                          <span class="inline-block">
                                            <strong><span class="col-xs-12 m-b-0 pdf-inputfield fieldc" id="pdf_noc_con_text_4"></span></strong>
                                         </span>, Notary Public (name and title of officer) personally appeared <span class="inline-block">
                                            <strong><span  class="col-xs-12 m-b-0 pdf-inputfield fieldd" id="pdf_noc_con_text_5"></span></strong>
                                         </span> who proved to me on the basis of satisfactory evidence to be the person(s) whose name(s) is/are subscribed to the within instrument and acknowledged to me that he/she/they executed the same in his/her/their authorized capacity(ies), and that by his/her/their signature(s) on the instrument the person(s), or the entity upon behalf of which the person(s) acted, executed the instrument.
                                         <br>
                                        <p class="m-t-10">I certify under PENALTY OF PURJURY under the laws of the State of California that the foregoing paragraph is true and correct.</p>
                                        <p class="m-t-10">Witness my hand and official seal.</p>
                                        <div class="clearfix"></div>
                                        <div class="pull-right m-t-0">
                                          <strong><span  class="col-xs-12 m-b-0 pdf-inputfield fieldc" id="pdf_noc_con_text_6"></span></strong>
                                          <p>Signature</p>
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </div><!--      ##notice-addForm-values       -->

                          </div><!-- panel-body Close -->
                        </section>
                    </div>
                </div>
            </div>

        </div>
        <!--body wrapper end-->


<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone.js') }}"></script>
<script src="{{ url('/resources/assets/dist/notice_completion_add.js') }}"></script>
<script type="text/javascript">
    // $(".complete_form_noc").click(function() {
    //   $("#my-awesome-dropzone").hide();
    //   $("#notice-addForm").show();
    //   $("#potential_claimants").show();
    // });

    // $(".upload_form_noc").click(function() {
    //   $("#my-awesome-dropzone").show();
    //   $("#notice-addForm").hide();
    //   $("#potential_claimants").show();
    // });


    $("input[name='noc_type']").click(function(){
//        if($('input:radio[name=noc_type]:checked').val() == "complete_form_noc"){
//            console.log('complete_form_noc');
//            $("#my-awesome-dropzone").hide();
//            $(".upload_exist").hide();
//            $("#notice-addForm").show();
//            $("#potential_claimants").show();
//        }
//        else {
            console.log('upload_form_noc');
            $(".upload_exist").show();
            $("#my-awesome-dropzone").show();
            $("#notice-addForm").hide();
            $("#potential_claimants").show();
        //}
    });
</script>
@include('include/footer')
