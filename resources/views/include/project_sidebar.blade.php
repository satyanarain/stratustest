<!-- sidebar left start-->
        <div class="sidebar-left">
            <!--responsive view logo start-->
            <div class="logo dark-logo-bg visible-xs-* visible-sm-*">
                <a href="{{ url('/dashboard') }}">
                    <img src="{{ url('/resources/assets/img/logo-icon.png') }}" alt="">
                    <!--<i class="fa fa-maxcdn"></i>-->
                    <span class="brand-name">Stratus</span>
                </a>
            </div>
            <!--responsive view logo end-->
            <?php echo $project_id = Request::segment(2); ?>
            <div class="sidebar-left-info">
                <!-- visible small devices start-->
                <div class=" search-field">  </div>
                <!-- visible small devices end-->

                <!--sidebar nav start-->
                <ul class="nav nav-pills nav-stacked side-navigation project-sidebar">
                    <li>
                        <h3 class="navigation-title">Navigation</h3>
                    </li>
                    <li class="active"><a href="{{ url('/dashboard') }}"><i class="fa fa-inbox"></i> <span>Projects</span></a></li>
                    <div class="loading_bar_project_sidebar" id="loading_bar_project_sidebar" style="text-align: center;">
                        <img src="{{ url('/resources/assets/img/loading-sidebar.svg') }}" alt=""/>
                    </div>
                    <li style="display: none;" class="contact_view_permission_all">
                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/contact"><i class="fa fa-address-book"></i><span>Contacts</span></a>
                    </li>
                    <li class="menu-list menu_step" style="display:none;"">
                        <a href=""><i class="fa fa-battery-empty"></i><span>PRE-CONSTRUCTION</span></a>
                        <ul class="child-list">
                            <li style="display: none;" class="standard_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/standards"><span>Project Standards</span></a>
                            </li>
                            <li style="display: none;" class="standard_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/specifications"><span>Project Specifications</span></a>
                            </li>
                            <li style="display: none;" class="plan_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/plans"><span>Project Plans</span></a>
                            </li>
                            <li style="display: none;" class="geotechnical_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/geo_reports"><span> Geotechnical Reports</span></a>
                            </li>
                            <li style="display: none;" class="swppp_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/swppp"><span>SWPPP / WPCP</span></a>
                            </li>
                            <li style="display: none;" class="bid_document_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/bid_documents"><span>Bid Documents</span></a>
                            </li>
                            <li style="display: none;" class="notice_award_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/notice_award"><span>Notice of Award</span></a>
                            </li>
                            <li style="display: none;" class="contract_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/contract"><span>Contracts</span></a>
                            </li>
                            <li style="display: none;" class="contract_item_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/contract_item"><span>Contract Items</span></a>
                            </li>
                            <li style="display: none;" class="certificate_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/certificate"><span>Certificates of Insurance</span></a>
                            </li>
                            <li style="display: none;" class="bond_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/bond"><span>Contractor Bonds</span></a>
                            </li>
                            <li style="display: none;" class="notice_proceed_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/notice_proceed"><span>Notice to Proceed</span></a>
                            </li>
                        </ul>
                    </li>


                    <li class="menu-list menu_step nav-hover" style="display:none;"">
                        <a href=""><i class="fa fa-battery-half"></i><span>DURING CONSTRUCTION</span></a>
                        <ul class="child-list">
                            <li class="menu-list-sub rfi_list" style="display: none;">
                                <a href="">  <span>RFIs</span></a>
                                <ul class="child-list">
                                    <li style="display: none;" class="rfi_log">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/req_for_info_log"><span>RFI Log</span></a>
                                    </li>
                                    <li style="display: none;" class="rfi_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/req_for_info"><span>Add RFI</span></a>
                                    </li>
                                    <li style="display: none;" class="rfi_review_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/req_for_info_review"><span>RFI Review</span></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-list-sub submittal_list" style="display: none;">
                                <a href="">  <span>Submittals</span></a>
                                <ul class="child-list">
                                    <li style="display: none;" class="submittal_log">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/submittals_log"><span>Submittal Log</span></a>
                                    </li>
                                    <li style="display: none;" class="submittal_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/submittals"><span>Add Submittals</span></a>
                                    </li>
                                    <li style="display: none;" class="submittal_review_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/submittal_review"><span>Submittals Review</span></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-list-sub survey_list" style="display: none;">
                                <a href="">  <span>Surveys</span></a>
                                <ul class="child-list">
                                    <li style="display: none;" class="survey_log">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/survey"><span>Survey Log</span></a>
                                    </li>
                                    <li style="display: none;" class="survey_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/survey/add"><span>Add Survey</span></a>
                                    </li>
                                    <li style="display: none;" class="survey_review_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/survey_review"><span>Survey Review</span></a>
                                    </li>
                                </ul>
                            </li>
                            <li style="display: none;" class="daily_construction_report_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/daily_construction_report"><span>Daily Construction Reports</span></a>
                            </li>
                            <li style="display: none;" class="weekly_report_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/weekly_statement"><span>Weekly Statement of Contract Days</span></a>
                            </li>
                            <li style="display: none;" class="service_alert_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/service_alert"><span>Underground Service Alerts</span></a>
                            </li>
                            <li style="display: none;" class="test_result_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/test_result"><span>Test Results</span></a>
                            </li>
                            <li class="menu-list-sub survey_list" style="display: none;">
                                <a href="">  <span>Preliminary Notices</span></a>
                                <ul class="child-list">
                                    <li style="display: none;" class="preliminary_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/preliminary_notice_log"><span>Preliminary Notice Log</span></a>
                                    </li>
                                    <li style="display: none;" class="survey_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/preliminary_notice/add"><span>Add Preliminary Notice</span></a>
                                    </li>
                                    <li style="display: none;" class="survey_review_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/lien_release_log"><span>Lien Releases</span></a>
                                    </li>
                                </ul>
                            </li>
                            
                            <li style="display: none;" class="project_picture_video_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/picture_video"><span>Pictures / Videos</span></a>
                            </li>
                            <li style="display: none;" class="payment_quantity_verification_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/payment_quantity_verification_monthly"><span>Payment Quantity Verifications</span></a>
                            </li>
                            <li style="display: none;" class="payment_application_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/payment_application_monthly"><span>Payment Applications</span></a>
                            </li>
                            <li class="menu-list-sub cor_list" style="display: none;">
                                <a href="">  <span>Change Orders</span></a>
                                <ul class="child-list">
                                    <li style="display: none;" class="cor_log">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/change_order_request"><span>Change Order Request Log</span></a>
                                    </li>
                                    <li style="display: none;" class="cor_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/change_order_request/add"><span>Add Change Order Request</span></a>
                                    </li>
                                    <li style="display: none;" class="cor_review_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/change_order_request_review"><span>Change Order Request Review</span></a>
                                    </li>
                                </ul>
                            </li>
                            <li style="display: none;" class="meeting_minutes_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/minutes_meeting"><span>Meeting Minutes</span></a>
                            </li>
                            <li style="display: none;" class="labor_compliance_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/labor_compliance"><span>Labor Compliance</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-list menu_step" style="display:none;"">
                        <a href=""><i class="fa fa-battery-full"></i><span>POST CONSTRUCTION</span></a>
                        <ul class="child-list">
                            <li style="display: none;" class="unconditional_finals_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/unconditional_finals"><span>Unconditional Finals</span></a>
                            </li>
                            <li style="display: none;" class="drawing_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/built_drawing"><span>As Built Drawings</span></a>
                            </li>
                            <li style="display: none;" class="notice_completion_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/notice_completion"><span>Notice of Completion</span></a>
                            </li>
                            <li style="display: none;" class="agency_acceptance_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/acceptance_letter"><span>Agency Acceptance Documentation</span></a>
                            </li>
                        </ul>
                    </li>

<!--                    <li style="display: none;" class="project_setting">
                        <h3 class="navigation-title">Project Setting</h3>
                    </li>
                    <li style="display: none;" class="project_setting">
                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/project_currency/update"><i class="fa fa-usd"></i><span>Project Currency</span></a>
                    </li>-->
                  <!--   <li style="display: none;" class="project_setting">
                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/project_wage_determination/update"><span>Project Wage Determination</span></a>
                    </li> -->
                </ul>
            </div>
        </div>
        <!-- sidebar left end-->
<script src="http://code.jquery.com/jquery-latest.min.js"></script>

<script src="{{ url('/resources/assets/dist/project_sidebar.js') }}"></script>
