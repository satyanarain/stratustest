
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">Update Contact</h3>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div id="alert_message"></div>
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="loading_data" style="text-align: center;">
                                <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <div class="panel-body">
                            <div id="updateuserinfo" style="display: none"></div>
                                <form role="form" id="">

<div class="panel-group m-bot20" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading" style="background-color: #f3f3f3;">
            <h4 class="panel-title">
                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" style="font-size: 26px;">
                    USER DETAILS
                </a>
            </h4>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse" aria-expanded="false">
            <div class="panel-body">
                <div class="row">
                    <div class="form-group col-md-6 hide_user">
                        <label for="uname">Username</label>
                        <input type="text" class="form-control" id="uname" disabled>
                    </div>
                    <div class="form-group col-md-6 hide_user">
                        <label for="email">Email Address</label>
                        <input type="text" class="form-control" id="email" disabled>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="fname">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="fname">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="lname">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="lname">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="cname">Company Name <span class="text-danger">*</span></label>
                        <!-- <div class="loading_data" style="text-align: center;">
                           <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                        </div> -->
                        <select class="form-control" id="firm_name">
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="position">Position / Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="position">
                    </div>
                    <div class="clearfix"></div>
                    <!-- <div class="form-group col-md-6">
                        <label for="pnum">Phone Number</label>
                        <input type="text" class="form-control" id="pnum">
                    </div> -->
                    <div class="input_fields_wrap">
                    </div>

                    <div class="form-group col-md-6"></div>
                    <div class="clearfix"></div>

                    <div class="form-group col-md-6 role_admin_hide">
                        <label for="pass">Password</label>
                        <input type="password" class="form-control" id="pass">
                    </div>

                    <div class="form-group col-md-6 role_admin_hide">
                        <label for="cpass">Confirm Password</label>
                        <input type="password" class="form-control" id="cpass">
                    </div>

                    <input type="hidden" class="form-control role_normal_hide user_status" id="status">
                    <div class="col-md-6 hide_user role_admin_hide">
                        <label>Status <span class="text-danger">*</span></label>
                        <select class="form-control user_status" id="status">
                            <option value="1">Verified</option>
                            <option value="0">Unverifed</option>
                            <option value="2">Suspended</option>
                        </select>
                    </div>

                              <div class="col-sm-6">
                                        <label for="standard_name">User Image <span class="text-danger">*</span><span id="old_image_link"></span><input type="hidden" id="old_image_path"> </label>
                                        <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                            <input type="hidden" name="document_path" value="/uploads/users/">
                                        </form>

                                        <div class="form-group">
                                            <input type="hidden" name="user_image_path" id="user_image_path" value="">
                                            
                                        </div>
                                    </div>

                    
                    <div class="col-md-6 hide_user role_admin_hide">
                        <!-- <label>User Type <span class="text-danger">*</span></label>
                        <select class="form-control user_role" id="role">
                            <option value="owner">Owner</option>
                            <option value="manager">Manager</option>
                            <option value="contractor">Contractor</option>
                            <option value="engineer">Engineer</option>
                            <option value="surveyor">Surveyor</option>
                        </select> -->
                        <input type="hidden" class="form-control user_type_value" id="role">
                    </div>
                   <!--  <div class="col-md-6 hide_user">
                        <label>Project</label>
                        <div class="loading_data" style="text-align: center;">
                           <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                        </div>
                        <select class="form-control" id="project_name">
                        </select>
                    </div> -->

                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading" style="background-color: #f3f3f3;">
            <h4 class="panel-title">
                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" style="font-size: 26px;">
                    USER PERMISSIONS
                </a>
            </h4>
        </div>
        <div id="collapseThree" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
            <div class="panel-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <span style="background-color: #69c2fe; padding: 3px 15px 0px; color: #fff; font-weight: bold; float: right; border-radius: 3px;">
                            <input type="checkbox" id="select_all" value="select_all">
                            <label>SELECT ALL</label>
                        </span>
                        &nbsp;
                        <span style="background-color: #69c2fe; padding: 3px 15px 0px; color: #fff; font-weight: bold; float: right; border-radius: 3px; margin-right: 10px;">
                            <input type="checkbox" id="select_all_read_only" value="select_all_read_only">
                            <label>Select All: Read-Only</label>
                        </span>
                        &nbsp;
                        <span style="background-color: #69c2fe; padding: 3px 15px 0px; color: #fff; font-weight: bold; float: right; border-radius: 3px; margin-right: 10px;">
                            <input type="checkbox" id="select_all_non_read_only" value="select_all_non_read_only">
                            <label>Select All: Non Read-Only</label>
                        </span>
                    </div>
                    <div class="form-group col-md-12" id="user_permissions">
                        <h3 style="border-bottom:1px solid red">Pre-Construction</h3>
                        <h4>Project Contact</h4>
                        <div class="col-sm-3 nopadleft">
                            <input type="checkbox" class="read_only" name="permission_key[]" id="contact_view_permission_all" value="contact_view_permission_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="contact_update" value="contact_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="contact_add" value="contact_add">
                                <label>Add</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="contact_remove" value="contact_remove">
                                <label>Remove</label>
                        </div>
                        <div class="clearfix"></div>

                        <h4>Project Standard / Specification </h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="standard_view_all" value="standard_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="standard_update" value="standard_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="standard_add" value="standard_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>
                        <!--
                        <h4>Project Specification</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" name="permission_key[]" id="specification_view_all" value="specification_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" name="permission_key[]" id="specification_update" value="specification_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" name="permission_key[]" id="specification_add" value="specification_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div> -->

                        <h4>Project Plan</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="plan_view_all" value="plan_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="plan_update" value="plan_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="plan_add" value="plan_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>

                        <h4>Geotechnical Report</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="geotechnical_view_all" value="geotechnical_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="geotechnical_update" value="geotechnical_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="geotechnical_add" value="geotechnical_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>

                        <h4>SWPPP / WPCP</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="swppp_view_all" value="swppp_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="swppp_update" value="swppp_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="swppp_add" value="swppp_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>

                        <h4>Bid Documents</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="bid_document_view_all" value="bid_document_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="bid_document_update" value="bid_document_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="bid_document_add" value="bid_document_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>

                        <h4>Notice of Award</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="notice_award_view_all" value="notice_award_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="notice_award_update" value="notice_award_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="notice_award_add" value="notice_award_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>

                        <h4>Contracts</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="contract_view_all" value="contract_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="contract_update" value="contract_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="contract_add" value="contract_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>

                        <h4>Contract Item</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="contract_item_view_all" value="contract_item_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="contract_item_update" value="contract_item_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="contract_item_add" value="contract_item_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>

                        <h4>Certificate of Insurance</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="certificate_view_all" value="certificate_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="certificate_update" value="certificate_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="certificate_add" value="certificate_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>

                        <h4>Contractor Bond</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="bond_view_all" value="bond_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="bond_update" value="bond_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="bond_add" value="bond_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>

                        <h4>Notice of Proceed</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="notice_proceed_view_all" value="notice_proceed_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="notice_proceed_update" value="notice_proceed_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="notice_proceed_add" value="notice_proceed_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>
                        <h3 style="border-bottom:1px solid red">During Construction</h3>
                        <h4>Request for Information</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="rfi_view_all" value="rfi_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="rfi_update" value="rfi_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="rfi_add" value="rfi_add">
                                <label>Add</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="rfi_log" value="rfi_log">
                                <label>RFI Log</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="rfi_review_view_all" value="rfi_review_view_all">
                                <label>Review View All</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="rfi_review_update" value="rfi_review_update">
                                <label>Review Edit</label>
                        </div>
                        <div class="clearfix"></div>

                        <h4>Submittal</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="submittal_view_all" value="submittal_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="submittal_update" value="submittal_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="submittal_add" value="submittal_add">
                                <label>Add</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="submittal_log" value="submittal_log">
                                <label>Submittal Log</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="submittal_review_view_all" value="submittal_review_view_all">
                                <label>Review View All</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="submittal_review_update" value="submittal_review_update">
                                <label>Review Edit</label>
                        </div>
                        <div class="clearfix"></div>

                        <h4>Survey</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="survey_view_all" value="survey_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="survey_update" value="survey_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="survey_add" value="survey_add">
                                <label>Add</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="survey_log" value="survey_log">
                                <label>Survey Log</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="survey_review_view_all" value="survey_review_view_all">
                                <label>Review View All</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="survey_review_update" value="survey_review_update">
                                <label>Review Edit</label>
                        </div>
                        <div class="clearfix"></div>

                         <h4>Daily Construction Report</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="daily_construction_report_view_all" value="daily_construction_report_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="daily_construction_report_update" value="daily_construction_report_update">
                                <label>Edit</label>
                        </div>
                     <!--    <div class="col-sm-3 nopadleft">
                                <input type="checkbox" name="permission_key[]" id="daily_construction_report_add" value="daily_construction_report_add">
                                <label>Add</label>
                        </div> -->
                        <div class="clearfix"></div>

                        <h4>Weekly Report</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="weekly_report_view_all" value="weekly_report_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="weekly_report_update" value="weekly_report_update">
                                <label>Edit</label>
                        </div>
                      <!--   <div class="col-sm-3 nopadleft">
                                <input type="checkbox" name="permission_key[]" id="weekly_report_add" value="weekly_report_add">
                                <label>Add</label>
                        </div> -->
                        <div class="clearfix"></div>

                        <h4>Underground Service Alert</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="service_alert_view_all" value="service_alert_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="service_alert_update" value="service_alert_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="service_alert_add" value="service_alert_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>

                        <h4>Test Result</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="test_result_view_all" value="test_result_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="test_result_update" value="test_result_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="test_result_add" value="test_result_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>

                        <h4>Preliminary Notice</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="preliminary_view_all" value="preliminary_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="preliminary_update" value="preliminary_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="preliminary_add" value="preliminary_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>

                        <h4>Picture / Video</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="project_picture_video_view_all" value="project_picture_video_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="project_picture_video_remove" value="project_picture_video_remove">
                                <label>Remove</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="project_picture_video_add" value="project_picture_video_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>
 
                        <h4>Payment Quantity Verification</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="payment_quantity_verification_view_all" value="payment_quantity_verification_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="clearfix"></div>

                        <h4>Payment Application</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="payment_application_view_all" value="payment_application_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="clearfix"></div>

                        <h4>Change Order Request (COR)</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="cor_log" value="cor_log">
                                <label>COR Log</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="cor_view_all" value="cor_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="cor_add" value="cor_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>
                       <!--  <div class="col-sm-3 nopadleft">
                                <input type="checkbox" name="permission_key[]" id="cor_review_view_all" value="cor_review_view_all">
                                <label>Review View All</label>
                        </div> -->
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="cor_order_review_update" value="cor_order_review_update">
                                <label>Review Edit</label>
                        </div>
                        <div class="clearfix"></div>

                         <h4>Meeting Minutes</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="meeting_minutes_view_all" value="meeting_minutes_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="meeting_minutes_update" value="meeting_minutes_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="meeting_minutes_add" value="meeting_minutes_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>

                        <h4>Labor Compliance</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="labor_compliance_view_all" value="labor_compliance_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="labor_compliance_update" value="labor_compliance_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="labor_compliance_add" value="labor_compliance_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>
                        <h3 style="border-bottom:1px solid red">Post Construction</h3>
                        <h4>Unconditional Finals</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="unconditional_finals_view_all" value="unconditional_finals_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="unconditional_finals_update" value="unconditional_finals_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="unconditional_finals_add" value="unconditional_finals_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>

                        <h4>Build Drawing</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="drawing_view_all" value="drawing_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="drawing_update" value="drawing_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="drawing_add" value="drawing_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>

                        <h4>Notice of Completion</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="notice_completion_view_all" value="notice_completion_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="notice_completion_update" value="notice_completion_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="notice_completion_add" value="notice_completion_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>
                        <h4>Agency Acceptance Letter</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="read_only" name="permission_key[]" id="agency_acceptance_view_all" value="agency_acceptance_view_all">
                                <label>Read only</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="agency_acceptance_update" value="agency_acceptance_update">
                                <label>Edit</label>
                        </div>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="agency_acceptance_add" value="agency_acceptance_add">
                                <label>Add</label>
                        </div>
                        <div class="clearfix"></div>
                        <h3 style="border-bottom:1px solid red">Miscellaneous</h3>
                        

                        <h4>Project Setting</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="project_setting" value="project_setting">
                                <label>Update Project Settings</label>
                        </div>
                        <div class="clearfix"></div>
                        <h4>Add Company On Fly</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="project_add_company_on_fly" value="project_add_company_on_fly">
                                <label for="project_add_company_on_fly">Add Company On Fly</label>
                        </div>
                        <div class="clearfix"></div>
                        <h4>Add Improvement Type On Fly</h4>
                        <div class="col-sm-3 nopadleft">
                                <input type="checkbox" class="non_read_only" name="permission_key[]" id="project_add_impvtype_on_fly" value="project_add_impvtype_on_fly">
                                <label for="project_add_impvtype_on_fly">Add Improvement Type On Fly</label>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading" style="background-color: #f3f3f3;">
            <h4 class="panel-title">
                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" style="font-size: 26px;">
                    NOTIFICATIONS
                </a>
            </h4>
        </div>
        <div id="collapseFour" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
            <div class="panel-body">
                <div class="row" id="user_notifications">
                    <div class="form-group col-md-12">
                        <span style="background-color: #69c2fe; padding: 3px 15px 0px; color: #fff; font-weight: bold; float: right; border-radius: 3px;">
                            <input type="checkbox" id="select_all_notification" value="select_all">
                            <label>SELECT ALL</label>
                        </span>
                    </div>
                    <?php
                    $notification_types = array(
                        //"project_setup"=>"Project setup",
                        "notice_of_award_upload"=>"Notice of award",
                        "contract_upload"=>"Contracts",
                        "certificates_of_insurance"=>"Certificates of Insurance",
                        "bidding"=>"Bidding",
                        "rfi"=>"RFIs",
                        "submittals_eor"=>"Submittals",
                        "survey_cut_sheet"=>"Surveys",
                        "weekly_statement_of_contract_days"=>"Weekly Statement of Contract Days",
                        "pay_applications"=>"Pay Applications",
                        "change_orders"=>"Change Orders",
                        "labor_compliance"=>"Labor Compliance",
                        "meeting_minutes"=>"Meeting Minutes",
                        "unconditional_finals"=>"Unconditional Finals",
                        "as_built_drawings"=>"As Built Drawings",
                        "notice_of_completion"=>"Notice of Completion",
                        "standards"=>"Standards",
                        "swppp"=>"SWPPP/WPCP",
                        "underground_service_alert"=>"Underground service alert"
                    );
                            
                    //foreach ($notification_types as $key=>$notification_type){
                    ?>
                    <h3 style="border-bottom:1px solid red">Pre-Construction</h3>
                    <div class="form-group col-md-3">
                        <label for="standards">Standards</label><br>
                        <input type="checkbox" name="notification_key[]" id="standards" value="standards">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="standards">Plans</label><br>
                        <input type="checkbox" name="notification_key[]" id="plans" value="plans">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="swppp">SWPPP/WPCP</label><br>
                        <input type="checkbox" name="notification_key[]" id="swppp" value="swppp">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="bidding">Bidding</label><br>
                        <input type="checkbox" name="notification_key[]" id="bidding" value="bidding">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="notice_of_award_upload">Notice of award</label><br>
                        <input type="checkbox" name="notification_key[]" id="notice_of_award_upload" value="notice_of_award_upload">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="contract_upload">Contracts</label><br>
                        <input type="checkbox" name="notification_key[]" id="contract_upload" value="contract_upload">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="certificates_of_insurance">Certificates of Insurance</label><br>
                        <input type="checkbox" name="notification_key[]" id="certificates_of_insurance" value="certificates_of_insurance">
                    </div>
                    <div class="clearfix"></div>
                    <h3 style="border-bottom:1px solid red">During Construction</h3>
                    
                    <div class="form-group col-md-3">
                        <label for="rfi">RFIs</label><br>
                        <input type="checkbox" name="notification_key[]" id="rfi" value="rfi">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="submittals_eor">Submittals</label><br>
                        <input type="checkbox" name="notification_key[]" id="submittals_eor" value="submittals_eor">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="survey_cut_sheet">Surveys</label><br>
                        <input type="checkbox" name="notification_key[]" id="survey_cut_sheet" value="survey_cut_sheet">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="weekly_statement_of_contract_days">Weekly Statement of Contract Days</label><br>
                        <input type="checkbox" name="notification_key[]" id="weekly_statement_of_contract_days" value="weekly_statement_of_contract_days">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="underground_service_alert">Underground service alert</label><br>
                        <input type="checkbox" name="notification_key[]" id="underground_service_alert" value="underground_service_alert">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="pay_applications">Pay Applications</label><br>
                        <input type="checkbox" name="notification_key[]" id="pay_applications" value="pay_applications">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="change_orders">Change Orders</label><br>
                        <input type="checkbox" name="notification_key[]" id="change_orders" value="change_orders">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="labor_compliance">Labor Compliance</label><br>
                        <input type="checkbox" name="notification_key[]" id="labor_compliance" value="labor_compliance">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="meeting_minutes">Meeting Minutes</label><br>
                        <input type="checkbox" name="notification_key[]" id="meeting_minutes" value="meeting_minutes">
                    </div>
                    <div class="clearfix"></div>
                    <h3 style="border-bottom:1px solid red">Post Construction</h3>
                    <div class="form-group col-md-3">
                        <label for="unconditional_finals">Unconditional Finals</label><br>
                        <input type="checkbox" name="notification_key[]" id="unconditional_finals" value="unconditional_finals">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="as_built_drawings">As Built Drawings</label><br>
                        <input type="checkbox" name="notification_key[]" id="as_built_drawings" value="as_built_drawings">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="notice_of_completion">Notice of Completion</label><br>
                        <input type="checkbox" name="notification_key[]" id="notice_of_completion" value="notice_of_completion">
                    </div>
                    
                    
                    
                    <?php //}?>
                   

                </div>
            </div>
        </div>
    </div>
</div>


                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <!-- <a href="{{ url('/dashboard/'.$project_id.'/contact') }}" class="btn btn-info sub-btn">Back</a> -->

<!--                                            <a data-href="{{ url('/dashboard/'.$project_id.'/contact') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                            <a href="{{ url('/dashboard/'.$project_id.'/contact') }}" class="btn btn-info sub-btn btn_back1">Back</a>


                                            <button type="submit" id="update_profile_form" class="btn btn-info sub-btn">Submit</button>
                                            <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <!--body wrapper end-->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="http://code.jquery.com/jquery-latest.min.js?v=1.0"></script>
<!-- <script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script> -->
<script src="{{ url('/resources/assets/dist/api_url.js?v=1.0') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone_image.js') }}"></script>
<script src="{{ url('/resources/assets/dist/contact_update.js?v=1.0') }}"></script>
@include('include/footer')
