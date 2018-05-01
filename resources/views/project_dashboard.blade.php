
        @include('include/header')
        @include('include/project_sidebar')

        <?php echo $project_id = Request::segment(2); ?>

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')
            <!-- page head start-->
            <div class="page-head clearfix project-head">
                <h3 id="project_name"></h3>
                <div class="state-information clearfix">
                    <div class="progress left progress-striped active progress-sm m-b-20">
                        <!-- <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="75" style="width: 75%;">
                            <span class="sr-only">75% Complete</span>
                        </div> -->
                    </div>
                    <a href="{{ url('/dashboard') }}" class="btn btn-success right">Back</a>
                </div>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper">
                <div class="row">

                  <div class="col-md-12">
                    <section class="">
                      <div class="" style="margin-bottom: 30px;">
                        <div class="row">


                        <div class="state-overview">
                            <div class="loading_bar_project_contract_amount" id="loading_bar_project_sidebar" style="text-align: center;">
                                <img src="{{ url('/resources/assets/img/loading-sidebar.svg') }}" alt=""/>
                            </div>
                            <div class="col-md-4" id="contractor_name_box" style="display:none;">
                              <section class="blue">
                                <div class="symbol">
                                    <i class="fa fa-user"></i>
                                </div>
                                <div class="value white">
                                    <h4 id="contractor_name"></h4>
                                    <p>Contractor</p>
                                </div>
                              </section>
                            </div>
                        </div>
                        </div>
                      </div>
                    </section>
                  </div>

                  <div class="col-md-12">
                    <section class="">
                      <div class="" style="margin-bottom: 30px;">
                        <div class="row">


                        <div class="state-overview">
                            
                            <div class="col-md-4" id="" style="">
                              <section class="blue" style="height: 178px;">
                                <div class="symbol">
                                    <i class="fa fa-user"></i>
                                </div>
                                <div class="value white">
                                    <h4 id="" >Contract: Malhan Arcade</h4>
                                    <h4 id="" >Change Orders: (17) </h4>
                                    <h4 id="" >Total Contract: </h4>
                                    <h4 id="" >Pending CO: (31) </h4>
                                   
                                </div>
                              </section>
                            </div>
                        <div class="col-md-4"><section class="blue"><div class="symbol" style="font-size: 30px; font-weight: bold;">$ </div><div class="value white">
                           <h4 id="" >Billings To Date:</h4>
                          <h4 id="" >Contract Bal Remaining: : </h4>
                        </div></section></div>

                        <div class="col-md-4"><section class="blue"><div class="symbol" style="font-size: 30px; font-weight: bold;"><i class="fa fa-cube"></i></div><div class="value white">
                           <h4 id="" >Contracts Days (Org):</h4>
                          <h4 id="" >Contract Days Added: : </h4>
                           <h4 id="" >Contract Days (Rev):</h4>
                          <h4 id="" >Contract Days (Charged) : </h4>
                           <h4 id="" >Days Remaining : </h4>
                        </div></section></div></div>
                        </div>
                      </div>
                    </section>
                  </div>

                  <div class="col-md-12">
                    <section class="panel">
                      <div class="panel-body">
                        <div class="col-md-9 nopadleft text-center">
                            <ul class="status-parent pull-right">
                                <li><span class="bg-danger"></span>PAST DUE</li>
                                <li><span class="bg-warning"></span>DUE</li>
                                <li><span class="bg-success"></span>COMPLETED</li>
                            </ul>
                        </div>
                          <div class="progress-parent clearfix">
                              <div class="clearfix"></div>
                              <div class="loading_progress_bar" style="text-align: center;">
                                  <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                              </div>
                              <ul class="table-projects-progress clearfix">
                                <li class="hide_submittal_permission" style="display: none;">
                                    <div class="project-col">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/submittals_log">Submittals</a>
                                    </div>
                                    <div class="progress-col">
                                        <div class="progress progress-sm">
                                          <div class="progress-bar progress-bar-danger submittal_past_due_width">
                                              <span class="proj-amt"><a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/submittals_log" class="submittal_past_due"></a></span>
                                          </div>
                                          <div class="progress-bar progress-bar-warning submittal_upcoming_width">
                                              <span class="proj-amt"><a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/submittals_log" class="submittal_upcoming"></a></span>
                                          </div>
                                          <div class="progress-bar progress-bar-success submittal_complete_width">
                                              <span class="proj-amt"><a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/submittals_log"  class="submittal_complete"></a></span>
                                          </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="hide_rfi_permission" style="display: none;">
                                    <div class="project-col">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/req_for_info_log">RFIs</a>
                                    </div>
                                    <div class="progress-col">
                                        <div class="progress progress-sm">
                                          <div class="progress-bar progress-bar-danger rfi_past_due_width">
                                              <span class="proj-amt"><a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/req_for_info_log" class="rfi_past_due"></a></span>
                                          </div>
                                          <div class="progress-bar progress-bar-warning rfi_upcoming_width">
                                              <span class="proj-amt"><a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/req_for_info_log" class="rfi_upcoming"></a></span>
                                          </div>
                                          <div class="progress-bar progress-bar-success rfi_complete_width">
                                              <span class="proj-amt"><a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/req_for_info_log" class="rfi_complete"></a></span>
                                          </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="hide_survey_permission" style="display: none;">
                                    <div class="project-col">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/survey_log">Survey</a>
                                    </div>
                                    <div class="progress-col">
                                        <div class="progress progress-sm">
                                            <div class="progress-bar progress-bar-danger survey_past_due_width">
                                                <span class="proj-amt"><a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/survey_log" class="survey_past_due"></a></span>
                                            </div>
                                            <div class="progress-bar progress-bar-warning survey_upcoming_width">
                                                <span class="proj-amt"><a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/survey_log" class="survey_upcoming"></a></span>
                                            </div>
                                            <div class="progress-bar progress-bar-success survey_complete_width">
                                                <span class="proj-amt"><a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/survey_log" class=" survey_complete"></a></span>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="hide_cor_permission" style="display: none;">
                                    <div class="project-col">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/change_order_request_log">Change Orders</a>
                                    </div>
                                    <div class="progress-col">
                                        <div class="progress progress-sm">
                                          <div class="progress-bar progress-bar-danger r_cor_past_due_width">
                                              <span class="proj-amt"><a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/change_order_request_log" class="r_cor_past_due"></a></span>
                                          </div>
                                          <div class="progress-bar progress-bar-warning r_cor_upcoming_width">
                                              <span class="proj-amt"><a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/change_order_request_log" class="r_cor_upcoming"></a></span>
                                          </div>
                                          <div class="progress-bar progress-bar-success r_cor_complete_width">
                                              <span class="proj-amt"><a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/change_order_request_log" class="r_cor_complete"></a></span>
                                          </div>
                                        </div>
                                    </div>
                                </li>
                              </ul>
                          </div>
                      </div>
                    </section>
                  </div>

                  <div class="col-sm-6">
                    <section class="panel">
                      <header class="panel-heading head-border">
                          Project Details
                      </header>
                      <div class="panel-body w-setting green">
                          <ul class="team-list chat-list-side info statistics border-less-list setting-list">
                              <li>
                                <strong style="width: 150px;display: block;float: left;">Project Code: </strong><span id="project_code"></span>
                                <div class="loading_project_detail" style="text-align: center;">
                                  <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                </div>
                              </li>
                              <li>
                                <strong style="width: 150px;display: block;float: left;">Project Name: </strong><span id="project_name_1"></span>
                                <div class="loading_project_detail" style="text-align: center;">
                                  <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                </div>
                              </li>
                              <li>
                                <strong style="width: 150px;display: block;float: left;">Project Location: </strong><span id="project_location1"></span>
                                <div class="loading_project_detail" style="text-align: center;">
                                  <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                </div>
                              </li>
                              <li class="project_lead_agency_li">
                                <strong style="width: 150px;display: block;float: left;">Lead Agency: </strong><span id="project_lead_agency"></span>
                                <div class="loading_project_detail" style="text-align: center;">
                                  <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                </div>
                              </li>
                             <!--  <li>
                                <strong style="width: 150px;display: block;float: left;">Project Description: </strong><span id="project_description"></span>
                                <div class="loading_project_detail" style="text-align: center;">
                                  <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                </div>
                              </li>
                              <li>
                                <strong>Contractor Name: </strong><span id="contractor_name"></span>
                                <div class="loading_project_contractor_name" style="text-align: center;">
                                  <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                </div>
                              </li>
                              <li>
                                <strong>Project Type: </strong><span id="project_type"></span>
                                <div class="loading_project_project_type" style="text-align: center;">
                                  <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                </div>
                              </li> -->
                          </ul>
                      </div>
                    </section>
                  </div>

                  <div class="col-md-6">
                      <section class="panel post-wrap pro-box team-member pro-members">
                      <!--     <aside class="bg-primary v-align">
                              <div class="panel-body text-center">
                                  <div class="team-member-wrap">
                                      <div class="team-member-info">

                                          <div class="team-title">
                                              <a href="javascript:;" class="m-name owner_name" style="text-transform: capitalize;">
                                                  
                                              </a>
                                              <span class="sub-title"> Project Owner</span>
                                          </div>

                                          <div class="call-info">
                                              <img src="{{ url('/resources/assets/img/thumbnail.jpg') }}" alt=""/>
                                              </div>
                                  </div>
                              </div>
                              </div>
                          </aside> -->
                          <aside>
                              <header class="panel-heading head-border">
                                  Project Contacts
                              </header>
                              <div class="post-info" style="max-height: 180px;">
                                  <ul class="team-list cycle-pager external project_contacts_list" id='no-template-pager'>
                                      <li id="admin_contact_heading" style="background: #f5f5f5; padding: 10px; text-transform: uppercase; font-weight: 600; display: none;">Admin</li>
                                      <span id="admin_contact"></span>
                                      <li id="owner_contact_heading" style="background: #f5f5f5; padding: 10px; text-transform: uppercase; font-weight: 600; display: none;">Owner</li>
                                      <span id="owner_contact"></span>
                                      <li id="manager_contact_heading" style="background: #f5f5f5; padding: 10px; text-transform: uppercase; font-weight: 600; display: none;">Manager</li>
                                      <span id="manager_contact"></span>
                                      <li id="contractor_contact_heading" style="background: #f5f5f5; padding: 10px; text-transform: uppercase; font-weight: 600; display: none;">Contractor</li>
                                      <span id="contractor_contact"></span>
                                      <li id="engineer_contact_heading" style="background: #f5f5f5; padding: 10px; text-transform: uppercase; font-weight: 600; display: none;">Engineer</li>
                                      <span id="engineer_contact"></span>
                                      <li id="surveyor_contact_heading" style="background: #f5f5f5; padding: 10px; text-transform: uppercase; font-weight: 600; display: none;">Surveyor</li>
                                      <span id="surveyor_contact"></span>
                                      <li id="user_contact_heading" style="background: #f5f5f5; padding: 10px; text-transform: uppercase; font-weight: 600; display: none;">Users</li>
                                      <span id="user_contact"></span>
                                      <!-- 
                                      <li>
                                          <a href="javascript:;">
                                              <span class="thumb-small">
                                                  <img class="circle" src="{{ url('/resources/assets/img/thumbnail.jpg') }}" alt=""/>
                                                  <i class="online dot"></i>
                                              </span>
                                              <span class="name">Alison Jones</span>
                                          </a>
                                      </li>
                                      <li>
                                          <a href="javascript:;">
                                              <span class="thumb-small">
                                                  <img class="circle" src="{{ url('/resources/assets/img/thumbnail.jpg') }}" alt=""/>
                                                  <i class="away dot"></i>
                                              </span>
                                              <span class="name">Joliana Devis</span>
                                          </a>
                                      </li>
                                      
                                      <li>Contractor</li>
                                      <li>
                                          <a href="javascript:;">
                                              <span class="thumb-small">
                                                  <img class="circle" src="{{ url('/resources/assets/img/thumbnail.jpg') }}" alt=""/>
                                                  <i class="busy dot"></i>
                                              </span>
                                              <span class="name">David Alexzender</span>
                                          </a>
                                      </li>
                                      <li>
                                          <a href="javascript:;">
                                              <span class="thumb-small">
                                                  <img class="circle" src="{{ url('/resources/assets/img/thumbnail.jpg') }}" alt=""/>
                                                  <i class="offline dot"></i>
                                              </span>
                                              <span class="name">Emma Rose</span>
                                          </a>
                                      </li>
                                      
                                      <li>
                                          <a href="javascript:;">
                                              <span class="thumb-small">
                                                  <img class="circle" src="{{ url('/resources/assets/img/thumbnail.jpg') }}" alt=""/>
                                                  <i class="online dot"></i>
                                              </span>
                                              <span class="name">Jacqueline Jones</span>
                                          </a>
                                      </li>

                                      <li>
                                          <a href="javascript:;">
                                              <span class="thumb-small">
                                                  <img class="circle" src="{{ url('/resources/assets/img/thumbnail.jpg') }}" alt=""/>
                                                  <i class="online dot"></i>
                                              </span>
                                              <span class="name">Jacqueline Jones</span>
                                          </a>
                                      </li>

                                      <li>
                                          <a href="javascript:;">
                                              <span class="thumb-small">
                                                  <img class="circle" src="{{ url('/resources/assets/img/thumbnail.jpg') }}" alt=""/>
                                                  <i class="online dot"></i>
                                              </span>
                                              <span class="name">Jacqueline Jones</span>
                                          </a>
                                      </li>

                                      <li>
                                          <a href="javascript:;">
                                              <span class="thumb-small">
                                                  <img class="circle" src="{{ url('/resources/assets/img/thumbnail.jpg') }}" alt=""/>
                                                  <i class="online dot"></i>
                                              </span>
                                              <span class="name">Jacqueline Jones</span>
                                          </a>
                                      </li>

                                      <li>
                                          <a href="javascript:;">
                                              <span class="thumb-small">
                                                  <img class="circle" src="{{ url('/resources/assets/img/thumbnail.jpg') }}" alt=""/>
                                                  <i class="online dot"></i>
                                              </span>
                                              <span class="name">Jacqueline Jones</span>
                                          </a>
                                      </li> -->

                                  </ul>
                                  <!--<div class="add-more-member">
                                      <a href="javascript:;" class=" ">View All Member</a>
                                      <a href="javascript:;" class="add-btn pull-right">
                                          +
                                      </a>
                                  </div>-->
                              </div>
                          </aside>
                      </section>
                  </div>

                  <div class="col-md-12">
                        <section class="panel">
                            <header class="panel-heading">
                                Improvement Types
                            </header>
                            <div class="panel-body">
                                <ul class="todo-list-item" id="project_type">
                                </ul>
                            </div>
                        </section>
                    </div>
                    
                  
                    <div class="panel-group m-bot20" id="accordion">
                        <div class="panel panel-default">
                        <div class="col-sm-12">
                        <div class="loading_submittal_detail" style="text-align: center;">
                            <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                        </div>
                    <section class="panel hide_submittal_permission" style="display: none;">
                        <div class="panel-heading" style="">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" style="font-size: 26px;">
                                    SUBMITTALS
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false">
                            <div class="pro-dash-table">
                          <div class="project-table table-responsive">
                            <table class="table table-striped" id="submittal_data_log">
                              <thead style="display: none;">
                              <tr>
                                  <th>Submittal#</th>
                                  <th>Description</th>
                                  <th>Date of Submittal</th>
                                  <th>Date Submittal Returned by Reviewer</th>
                                  <th>No Exceptions</th>
                                  <th>Make Corrections Noted</th>
                                  <th>Revise &amp; Resubmit</th>
                                  <th>Rejected</th>
                                  <th>Status</th>
                                  <th>Responsible Party</th>
                              </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        </div>
                    </section>
                    </div>
                  </div>
                        
                        <div class="panel panel-default">
                        <div class="col-sm-12">
                        <div class="loading_rfi_detail" style="text-align: center;">
                            <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                        </div>
                        <section class="panel hide_rfi_permission" style="display: none;">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" style="font-size: 26px;">
                                        RFIs
                                    </a>
                                </h4>
                            </div>
                        <div id="collapseTwo" class="panel-collapse collapse" aria-expanded="false">
                        <div class="pro-dash-table">
                          <div class="project-table table-responsive">
                            <table class="table table-striped" id="rfi_data_log">
                              <thead style="display: none;">
                              <tr>
                                  <th>RFI #</th>
                                  <th>Description</th>
                                  <th>Generated By</th>
                                  <th>Approval Authority</th>
                                  <th>Date Submitted</th>
                                  <th>Date Responded</th>
                                  <th>Status</th>
                              </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        </div>
                    </section>
                      </div>
                  </div>
                        
                        <div class="panel panel-default">
                    <div class="col-sm-12">
                        <div class="loading_cor_detail" style="text-align: center;">
                            <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                        </div>
                    <section class="panel hide_cor_permission"  style="display: none;">
                        
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" style="font-size: 26px;">
                                    CHANGE ORDERS
                                </a>
                            </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse" aria-expanded="false">
                            <div class="pro-dash-table">
                          <div class="panel-body project-table table-responsive">
                            <table class="table table-striped" id="request_change_order">
                          <thead>
                          <tr>
                              <th>COR#</th>
                              <th>Generated By</th>
                              <th>Date Sent</th>
                              <th>Description</th>
                              <th>Approved by CM</th>
                              <th>Approved by OWNER</th>
                              <th>$</th>
                              <th>Days</th>
                              <th>Status</th>
                          </tr>
                          </thead>
                          <tbody>

                          </tbody>
                          </table>
                        </div>
                      </div>
                        </div>
                        
                    </section>
                    </div>
                </div>
                        
                        <div class="panel panel-default">
                  <div class="col-sm-12">
                    <div class="loading_cor_detail" style="text-align: center;">
                        <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                    </div>
                    <section class="panel hide_cor_permission"  style="display: none;">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" style="font-size: 26px;">
                                    POTENTIAL CHANGE ORDERS
                                </a>
                            </h4>
                        </div>
                        <div id="collapseFour" class="panel-collapse collapse" aria-expanded="false">
                      
                      <div class="pro-dash-table">
                          <div class="panel-body project-table table-responsive">
                            <table class="table table-striped" id="potential_change_order">
                          <thead>
                          <tr>
                              <th>Potential <br/>COR#</th>
                              <th>Generated By</th>
                              <th>Date Sent</th>
                              <th>Description</th>
                              <th>Approved by CM</th>
                              <th>Approved by OWNER</th>
                              <th style="min-width: 200px;">Referenced RFI</th>
                              <th>$</th>
                              <th>Days</th>
                              <th>Status</th>
                          </tr>
                          </thead>
                          <tbody>
                          </tbody>
                      </table>
                        </div>
                      </div>
                        </div>
                    </section>
                  </div>
                </div>
                 </div>     
                  
                      

                <!--   <div class="col-sm-12">
                    <div class="loading_survey_detail" style="text-align: center;">
                        <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                    </div>
                    <section class="panel hide_survey_permission" style="display: none;">
                        <header class="panel-heading head-border">
                            <strong>Survey</strong>
                        </header>
                        <div class="pro-dash-table">
                          <div class="panel-body project-table table-responsive">
                            <table class="table table-striped" id="survey_data_log">
                              <thead style="display: none;">
                              <tr>
                                <th>Survey #</th>
                                <th>Description</th>
                                <th>Date of Request</th>
                                <th>Date Survey Complete</th>
                                <th>Survey Requests</th>
                                <th>Survey Cut Sheets</th>
                                <th>Status</th>
                              </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>
                        </div>


                    </section>
                  </div> -->


                

                

            <!--body wrapper end-->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/project_dashboard.js') }}"></script>
@include('include/footer')
