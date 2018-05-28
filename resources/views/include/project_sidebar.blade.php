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
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/standards"><i>Project Standards</i></a>
                            </li>
                            <li style="display: none;" class="standard_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/specifications"><i>Project Specifications</i></a>
                            </li>
                            <li style="display: none;" class="plan_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/plans"><i>Project Plans</i></a>
                            </li>
                            <li style="display: none;" class="geotechnical_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/geo_reports"><i> Geotechnical Reports</i></a>
                            </li>
                            <li style="display: none;" class="swppp_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/swppp"><i>SWPPP / WPCP</i></a>
                            </li>
                            <li style="display: none;" class="bid_document_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/bid_documents"><i>Bid Documents</i></a>
                            </li>
                            <li style="display: none;" class="notice_award_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/notice_award"><i>Notice of Award</i></a>
                            </li>
                            <li style="display: none;" class="contract_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/contract"><i>Contracts</i></a>
                            </li>
                            <li style="display: none;" class="contract_item_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/contract_item"><i>Contract Items</i></a>
                            </li>
                            <li style="display: none;" class="certificate_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/certificate"><i>Certificates of Insurance</i></a>
                            </li>
                            <li style="display: none;" class="bond_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/bond"><i>Contractor Bonds</i></a>
                            </li>
                            <li style="display: none;" class="notice_proceed_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/notice_proceed"><i>Notice to Proceed</i></a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-list menu_step nav-hover" style="display:none;"">
                        <a href=""><i class="fa fa-battery-half"></i><span>DURING CONSTRUCTION</span></a>
                        <ul class="child-list">
                            <li class="menu-list-sub survey_list" style="display: none;">
                                <a href="">  <i>Preliminary Notices</i></a>
                                <ul class="child-list">
                                    <li style="display: none;" class="preliminary_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/preliminary_notice_log"><i>Preliminary Notice Log</i></a>
                                    </li>
                                    <li style="display: none;" class="survey_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/preliminary_notice/add"><i>Add Preliminary Notice</i></a>
                                    </li>
<!--                                    <li style="display: none;" class="survey_review_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/lien_release_log"><i>Lien Releases</i></a>
                                    </li>-->
                                </ul>
                            </li>
                            <li style="display: none;" class="service_alert_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/service_alert"><i>Underground Service Alerts</i></a>
                            </li>
                            <li class="menu-list-sub rfi_list" style="display: none;">
                                <a href="">  <i>RFIs</i></a>
                                <ul class="child-list">
                                    <li style="display: none;" class="rfi_log">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/req_for_info_log"><i>RFI Log</i></a>
                                    </li>
                                    <li style="display: none;" class="rfi_view_all">
<!--                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/req_for_info"><i>Add RFI</i></a>-->
                                            <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/req_for_info/add"><i>Add RFI</i></a>
                                    </li>
                                    <li style="display: none;" class="rfi_review_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/req_for_info_review"><i>RFI Review</i></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-list-sub submittal_list" style="display: none;">
                                <a href="">  <i>Submittals</i></a>
                                <ul class="child-list">
                                    <li style="display: none;" class="submittal_log">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/submittals_log"><i>Submittal Log</i></a>
                                    </li>
                                    <li style="display: none;" class="submittal_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/submittals"><i>View Submittals</i></a>
                                    </li>
                                    <li style="display: none;" class="submittal_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/submittals/add"><i>Add Submittals</i></a>
                                    </li>
                                    <li style="display: none;" class="submittal_review_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/submittal_review"><i>Submittals Review</i></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-list-sub survey_list" style="display: none;">
                                <a href="">  <i>Surveys</i></a>
                                <ul class="child-list">
                                    <li style="display: none;" class="survey_log">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/survey"><i>Survey Log</i></a>
                                    </li>
                                    <li style="display: none;" class="survey_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/survey/add"><i>Add Survey</i></a>
                                    </li>
                                    <li style="display: none;" class="survey_review_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/survey_review"><i>Survey Review</i></a>
                                    </li>
                                </ul>
                            </li>
                            <li style="display: none;" class="test_result_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/test_result"><i>Test Results</i></a>
                            </li>
                            <li style="display: none;" class="daily_construction_report_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/daily_construction_report"><i>Daily Construction Reports</i></a>
                            </li>
                            <li style="display: none;" class="meeting_minutes_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/minutes_meeting"><i>Meeting Minutes</i></a>
                            </li>
                            <li style="display: none;" class="meeting_minutes_view_all">
                                <a href="{{ url('/') }}/dashboard/"><i>Schedules</i></a>
                            </li>
                            <li style="display: none;" class="weekly_report_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/weekly_statement"><i>Weekly Statement of Contract Days</i></a>
                            </li>
                            <li style="display: none;" class="project_picture_video_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/picture_video"><i>Pictures / Videos</i></a>
                            </li>
                            <li style="display: none;" class="payment_quantity_verification_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/payment_quantity_verification_monthly"><i>Payment Quantity Verifications</i></a>
                            </li>
                            <li style="display: none;" class="menu-list-sub survey_list">
                                <a href=""><i>Pay Applications</i></a>
                                <ul class="child-list">
                                    <li style="display: none;" class="payment_application_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/payment_application_monthly"><i>Payment Application Log</i></a>
                                    </li>
                                    <li style="display: none;" class="survey_review_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/lien_release_log"><i>Lien Releases</i></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-list-sub cor_list" style="display: none;">
                                <a href="">  <i>Change Orders</i></a>
                                <ul class="child-list">
                                    <li style="display: none;" class="cor_log">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/change_order_request"><i>Change Order Request Log</i></a>
                                    </li>
                                    <li style="display: none;" class="cor_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/change_order_request/add"><i>Add Change Order Request</i></a>
                                    </li>
                                    <li style="display: none;" class="cor_review_view_all">
                                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/change_order_request_review"><i>Change Order Request Review</i></a>
                                    </li>
                                </ul>
                            </li>
                            <li style="display: none;" class="labor_compliance_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/labor_compliance"><i>Labor Compliance</i></a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-list menu_step" style="display:none;"">
                        <a href=""><i class="fa fa-battery-full"></i><span>POST CONSTRUCTION</span></a>
                        <ul class="child-list">
                            <li style="display: none;" class="unconditional_finals_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/unconditional_finals"><i>Unconditional Finals</i></a>
                            </li>
                            <li style="display: none;" class="drawing_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/built_drawing"><i>As Built Drawings</i></a>
                            </li>
                            <li style="display: none;" class="notice_completion_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/notice_completion"><i>Notice of Completion</i></a>
                            </li>
                            <li style="display: none;" class="agency_acceptance_view_all">
                                <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/acceptance_letter"><i>Agency Acceptance Documentation</i></a>
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
