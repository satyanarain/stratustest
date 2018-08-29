<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/', function () {
    return view('login');
});
//Route::get('/', 'Users\UserController@abc');

Route::get('dashboard', function () {
    return view('/dashboard');
});

Route::get('users/password_confirmation/{user_name}', function () {
    return view('/password_confirmation');
});

Route::get('users/reset_password', function () {
    return view('/reset_password');
});

Route::get('users/email_verification/{user_id}', function () {
    return view('/email_verification');
});

Route::get('users/update_password/{user_id}', function () {
    return view('/update_password');
});
Route::post('users/update_password', 'Users\UserController@update_password');


/**** DASHBOARD VIEW ****/
Route::get('dashboard/users/{userid}', function () { 
    return view('/user_detail');   
});
Route::get('dashboard/change-password', function () { 
    return view('/change-password');   
});
Route::get('dashboard/users', function () {
    return view('/view_users');
});
Route::get('dashboard/users/{userid}/update', function () {
    return view('/user_update');
});
Route::get('dashboard/users/{userid}/updateprofile', function () {
    return view('/user_update_profile');
});

Route::get('users/add', function () {
    return view('/user_add');
});

Route::get('dashboard/{project_id}/groupdoc', function () {
    return view('/groupdoc_view_single');
});

Route::post('users/authenticate', 'Users\AuthenticateController@authenticate');
// Route::get('logout', 'Users\UserController@logout');

Route::get('cronnotifinder', 'Projects\NotificationController@send_notification');

/*** Forget Username and Password ***/
Route::post('users/request-forgot-username', 'Users\UserController@forget_username');
Route::post('users/request-forgot-password-link', 'Users\UserController@forget_password');
Route::post('users/reset-password', 'Users\UserController@password_change');
// Route::get('users/forgot-password/{user_name}', 'Users\UserController@password_confirmation');
// Route::post('users/forgot-password', 'Users\UserController@password_change');

/*** Email Verification ***/
Route::get('users/unverified_user', 'Users\UserController@unverified_user');
Route::get('users/unverified_user_expire', 'Users\UserController@unverified_user_expire');
Route::post('users/email_verification', 'Users\UserController@email_verification');
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('users/send-email-verification', 'Users\UserController@send_email_verification');
});

/*** User ***/
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::get('users/{user_id}', 'Users\UserController@get_single_user_details');
    Route::post('users/get_user_details', 'Users\UserController@get_user_details');
    Route::post('users/insert_user_data', 'Users\UserController@insert_user_data');
    Route::get('users/get_user_info/{id}', 'Users\UserController@get_user_info');
    Route::post('users/delete_user_data/', 'Users\UserController@delete_user_data');
    Route::post('users/get_user_new_role', 'Users\UserController@get_user_new_role');
    Route::post('users/change_password', 'Users\UserController@change_password');
    Route::post('get_site_logo', 'Users\UserController@get_site_logo');
    Route::post('upload_site_logo', 'Users\UserController@update_site_logo');
});
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::get('users/{userid}', 'Users\UserController@get_profile');
    Route::get('users_profile/{userid}', 'Users\UserController@get_profile');
    Route::post('users/{userid}/update', 'Users\UserController@update_user');
    Route::post('users/{userid}/updateprofile', 'Users\UserController@update_user_profile');
    Route::get('users/{userid}/suspend', 'Users\UserController@user_suspend');
    Route::post('users/add', 'Users\UserController@add_user');
    Route::get('users', 'Users\UserController@get_users');
    Route::get('{project_id}/survey_contact_person', 'Users\UserController@survey_contact_person');
    Route::get('check-reviewer-permission/{project_id}/{item_id}/{type}/{designation}', 'Projects\ChangeOrderRequestController@check_reviewer_permission');
});

/*  --------------------------------------------------------------------------
    DOCUMENTS API'S
    -------------------------------------------------------------------------- */
    Route::post('document/uploadFiles', 'Projects\DocumentController@uploadFiles');
    Route::post('document/uploadgroupdocFiles', 'Projects\DocumentController@upload_group_doc_files');
    Route::post('document/CreateUploadFiles', 'Projects\DocumentController@CreateUploadFiles');
    Route::post('document/GeneratePdfFiles', 'Projects\DocumentController@GeneratePdfFiles');
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('document/add', 'Projects\DocumentController@add_document');
    Route::post('document/{document_id}/update', 'Projects\DocumentController@update_document');
    Route::get('document/{document_id}', 'Projects\DocumentController@get_document_single');
    Route::get('document/{project_id}/{document_meta}', 'Projects\DocumentController@get_document');

    Route::get('document_single/{document_id}', 'Projects\DocumentController@get_user_single_document');
    Route::get('document_single/{document_id}/delete', 'Projects\DocumentController@delete_user_single_document');
    Route::post('document_single/add', 'Projects\DocumentController@add_user_single_document');
});
/**** PROJECTS VIEW ****/
// Route::get('dashboard/projects', function () {
//     return view('/projects/project_view_all');
// });
Route::get('dashboard/document/add', function () {
    return view('/documents/document_add');
});
Route::get('dashboard/{project_id}/document', function () {
    return view('/documents/document_view_all');
});
// Route::get('dashboard/projects/{project_id}/update', function () {
//     return view('/projects/project_update');
// });

/*  --------------------------------------------------------------------------
    PROJECTS API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('projects/add', 'Projects\ProjectController@add_project');
    Route::post('projects/{project_id}/update', 'Projects\ProjectController@update_project');
    Route::get('projects/{project_id}', 'Projects\ProjectController@get_project_single');
    Route::get('projects', 'Projects\ProjectController@get_projects');
    Route::get('user_projects/{user_id}', 'Projects\ProjectController@get_user_projects');
});
/**** PROJECTS VIEW ****/
Route::get('dashboard/{project_id}/project', function () {
    return view('/project_dashboard');
});
Route::get('dashboard/projects', function () {
    return view('/projects/project_view_all');
});
Route::get('dashboard/projects/add', function () {
    return view('/projects/project_add');
});
Route::get('dashboard/projects/{project_id}/update', function () {
    return view('/projects/project_update');
});

/*  --------------------------------------------------------------------------
    CONTACT API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::get('{project_id}/contact', 'Projects\ContactController@get_contact');
    Route::get('contact/{project_id}/{user_id}', 'Projects\ContactController@add_contact_project');
    Route::get('contact/{project_id}', 'Projects\ContactController@get_contact_project');
    Route::get('contact/{project_id}/delete/{user_id}', 'Projects\ContactController@user_delete_project');
    Route::post('contact/{project_id}/add_permission/{user_id}', 'Projects\ContactController@add_user_permission');
    Route::get('contact/{project_id}/get_permission/{user_id}', 'Projects\ContactController@get_user_permission');
    Route::post('{project_id}/get_user_permission_key', 'Projects\ContactController@get_user_permission_key');
    // Route::post('contact/{project_id}/delete_permission/{user_id}', 'Projects\ContactController@delete_user_permission');
    Route::post('contact/{project_id}/add_notification/{user_id}', 'Projects\ContactController@add_user_notification');
    Route::get('contact/{project_id}/get_notification/{user_id}', 'Projects\ContactController@get_user_notification');
});
/**** CONTACT VIEW ****/
Route::get('dashboard/{project_id}/contact', function () {
    return view('/contact/contact_view_all');
});
Route::get('dashboard/{project_id}/contact/add', function () {
    return view('/contact/contact_add');
});
Route::get('dashboard/{project_id}/contact/{user_id}/update', function () {
    return view('/contact/contact_update');
});

    Route::get('added', 'Projects\CertificateController@groupdoc');
/*  --------------------------------------------------------------------------
    STANDARD API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('standards/add', 'Projects\StandardController@add_standards');
    Route::post('standards/{spec_id}/update', 'Projects\StandardController@update_standards');
    Route::get('standards/{spec_id}', 'Projects\StandardController@get_standards_single');
    Route::get('standards/{project_id}/{spec_type}', 'Projects\StandardController@get_standards_project');
});
/**** STANDARDS VIEW ****/
Route::get('dashboard/{project_id}/standards', function () {
    return view('/standard/standard_view_all');
});
Route::get('dashboard/{project_id}/standards/add', function () {
    return view('/standard/standard_add');
});
Route::get('dashboard/{project_id}/standards/{standard_id}/update', function () {
    return view('/standard/standard_update');
});
/*  --------------------------------------------------------------------------
    SPECIFICATIONS API'S
    -------------------------------------------------------------------------- */
/**** SPECIFICATIONS VIEW ****/
Route::get('dashboard/{project_id}/specifications', function () {
    return view('/specification/specification_view_all');
});
Route::get('dashboard/{project_id}/specifications/add', function () {
    return view('/specification/specification_add');
});
Route::get('dashboard/{project_id}/specifications/{specification_id}/update', function () {
    return view('/specification/specification_update');
});

/*  --------------------------------------------------------------------------
    GEO TECHNICAL REPORTS API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('geo-report/add', 'Projects\GeoreportController@add_geo_reports');
    Route::post('geo-report/{geo_id}/update', 'Projects\GeoreportController@update_geo_reports');
    Route::get('geo-report/{geo_id}', 'Projects\GeoreportController@get_geo_reports_single');
    Route::get('{project_id}/geo-report', 'Projects\GeoreportController@get_geo_reports_project');
});
/**** GEO TECHNICAL VIEW ****/
Route::get('dashboard/{project_id}/geo_reports', function () {
    return view('/georeport/georeport_view_all');
});
Route::get('dashboard/{project_id}/geo_reports/add', function () {
    return view('/georeport/georeport_add');
});
Route::get('dashboard/{project_id}/geo_reports/{geo_report_id}/update', function () {
    return view('/georeport/georeport_update');
});
/*  --------------------------------------------------------------------------
    PROJECT PLANS REPORTS API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('project-plan/add', 'Projects\PlansController@add_plans');
    Route::post('project-plan/{plan_id}/update', 'Projects\PlansController@update_plans');
    Route::get('project-plan/{plan_id}', 'Projects\PlansController@get_plans_single');
    Route::get('{project_id}/project-plan', 'Projects\PlansController@get_plans_project');
});
/**** PROJECT CERTIFICATE VIEW ****/
Route::get('dashboard/{project_id}/plans', function () {
    return view('/plans/plans_view_all');
});
Route::get('dashboard/{project_id}/plans/add', function () {
    return view('/plans/plans_add');
});
Route::get('dashboard/{project_id}/plans/{plans_id}/update', function () {
    return view('/plans/plans_update');
});

/*  --------------------------------------------------------------------------
    SWPPP / WPCP API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('swppp/add', 'Projects\SwpppController@add_swppp');
    Route::post('swppp/{plan_id}/update', 'Projects\SwpppController@update_swppp');
    Route::get('swppp/{plan_id}', 'Projects\SwpppController@get_swppp_single');
    Route::get('{project_id}/swppp', 'Projects\SwpppController@get_swppp_project');
});
/**** SWPPP / WPCP VIEW ****/
Route::get('dashboard/{project_id}/swppp', function () {
    return view('/swppp/swppp_view_all');
});
Route::get('dashboard/{project_id}/swppp/add', function () {
    return view('/swppp/swppp_add');
});
Route::get('dashboard/{project_id}/swppp/{swppp_id}/update', function () {
    return view('/swppp/swppp_update');
});
/*  --------------------------------------------------------------------------
    BID DOCUMENTS API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('bid-documents/add', 'Projects\BiddocumentController@add_bid_documents');
    Route::post('bid-documents/{plan_id}/update', 'Projects\BiddocumentController@update_bid_documents');
    Route::get('bid-documents/{plan_id}', 'Projects\BiddocumentController@get_bid_documents_single');
    Route::get('{project_id}/bid-documents', 'Projects\BiddocumentController@get_bid_documents_project');
    Route::post('bid-documents/add-addendum', 'Projects\BiddocumentController@add_addendum');
    Route::get('bid-documents/add-addendum/{bid_document_id}', 'Projects\BiddocumentController@get_addendum_bid_document');
});
/**** BID DOCUMENTS VIEW ****/
Route::get('dashboard/{project_id}/bid_documents', function () {
    return view('/bid_documents/bid_documents_view_all');
});
Route::get('dashboard/{project_id}/bid_documents/add', function () {
    return view('/bid_documents/bid_documents_add');
});
Route::get('dashboard/{project_id}/bid_documents/{bid_documents_id}/update', function () {
    return view('/bid_documents/bid_documents_update');
});
Route::get('dashboard/{project_id}/bid_documents/{bid_documents_id}', function () {
    return view('/bid_documents/bid_documents_single');
});

Route::get('dashboard/{project_id}/bid_documents/{bid_documents_id}/add-addendum', function () {
    return view('/bid_documents/addendum_add');
});

/*  --------------------------------------------------------------------------
    Notice of Award API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('notice-award/add', 'Projects\NoticeAwardController@add_notice_award');
    Route::post('notice-award/{notice_id}/update', 'Projects\NoticeAwardController@update_notice_award');
    Route::get('notice-award/{notice_id}', 'Projects\NoticeAwardController@get_notice_award_single');
    Route::get('{project_id}/notice-award', 'Projects\NoticeAwardController@get_notice_award_project');
    Route::get('{project_id}/default_contractor', 'Projects\NoticeAwardController@get_default_contractor_project');
    Route::get('{project_id}/check_project_user', 'Projects\PermissionController@check_project_user');
    Route::get('{project_id}/check_single_project_permission/{user_id}', 'Projects\PermissionController@check_all_user_permission');
});

/**** NOTICE OF AWARD VIEW ****/
Route::get('dashboard/{project_id}/notice_award', function () {
    return view('/notice_award/notice_award_view_all');
});
Route::get('dashboard/{project_id}/notice_award/add', function () {
    return view('/notice_award/notice_award_add');
});
Route::get('dashboard/{project_id}/notice_award/{notice_award_id}/update', function () {
    return view('/notice_award/notice_award_update');
});

/*  --------------------------------------------------------------------------
    NOTICE OF COMPLETION API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('notice-completion/add', 'Projects\NoticeCompletionController@add_notice_completion');
    Route::post('notice-completion/{noc_id}/update', 'Projects\NoticeCompletionController@update_notice_completion');
    Route::get('notice-completion/{noc_id}', 'Projects\NoticeCompletionController@get_notice_completion');
    Route::get('{project_id}/notice-completion', 'Projects\NoticeCompletionController@get_notice_completion_project');
});
Route::get('send_overdue_noc_notification', 'Projects\NoticeCompletionController@send_overdue_noc_notification');
Route::get('send_pay_quantity_verification_notification', 'Projects\NotificationController@send_pay_quantity_verification_notification');
/**** NOTICE OF COMPLETION ****/
Route::get('dashboard/{project_id}/notice_completion/add', function () {
    return view('/notice_completion/notice_completion_add');
});

Route::get('dashboard/{project_id}/notice_completion', function () {
    return view('/notice_completion/notice_completion_summary');
});

Route::get('dashboard/{project_id}/notice_completion/{noc_id}/update', function () {
    return view('/notice_completion/notice_completion_update');
});



/*  --------------------------------------------------------------------------
    PROJECT CERTIFICATE API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('certificate/add', 'Projects\CertificateController@add_certificate');
    Route::post('certificate/{cur_id}/update', 'Projects\CertificateController@update_certificate');
    Route::post('update_certificate_pdf/{ci_id}/update', 'Projects\CertificateController@update_certificate_pdf');
    Route::get('certificate/{cur_id}', 'Projects\CertificateController@get_certificate_single');
    Route::get('{project_id}/certificate', 'Projects\CertificateController@get_certificate_project');
    
    Route::post('custom_certificate/add', 'Projects\CertificateController@add_custom_certificate');
    Route::get('{project_id}/custom_certificate/{cur_id}', 'Projects\CertificateController@get_custom_certificate_all');
});
/**** PROJECT CERTIFICATE VIEW ****/
Route::get('dashboard/{project_id}/certificate', function () {
    return view('/certificate/certificate_view_all');
});
Route::get('dashboard/{project_id}/certificate/add', function () {
    return view('/certificate/certificate_add');
});
Route::get('dashboard/{project_id}/certificate/{certificate_id}/update', function () {
    return view('/certificate/certificate_update');
});
Route::get('dashboard/{project_id}/certificate/{certificate_id}', function () {
    return view('/certificate/certificate_single');
});

/*  --------------------------------------------------------------------------
    PROJECT CONTRACT BONDS API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('bond/add', 'Projects\BondController@add_bond');
    Route::post('bond/{pb_id}/update', 'Projects\BondController@update_bond');
    Route::get('bond/{pb_id}', 'Projects\BondController@get_bond_single');
    Route::get('{project_id}/bond', 'Projects\BondController@get_bond_project');
});
/**** PROJECT BOND VIEW ****/
Route::get('dashboard/{project_id}/bond', function () {
    return view('/bond/bond_view_all');
});
Route::get('dashboard/{project_id}/bond/add', function () {
    return view('/bond/bond_add');
});
Route::get('dashboard/{project_id}/bond/{bond_id}/update', function () {
    return view('/bond/bond_update');
});
/*  --------------------------------------------------------------------------
    PROJECT CONTRACT API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('contract/add', 'Projects\ContractController@add_contract');
    Route::post('contract/{con_id}/update', 'Projects\ContractController@update_contract');
    Route::get('contract/{con_id}', 'Projects\ContractController@get_contract_single');
    Route::get('{project_id}/contract', 'Projects\ContractController@get_contract_project');
});

/**** CONTRACT VIEW ****/
Route::get('dashboard/{project_id}/contract', function () {
    return view('/contract/contract_view_all');
});
Route::get('dashboard/{project_id}/contract/add', function () {
    return view('/contract/contract_add');
});
Route::get('dashboard/{project_id}/contract/{contract_id}/update', function () {
    return view('/contract/contract_update');
});
/*  --------------------------------------------------------------------------
    PROJECT NOTICE PROCEED API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('notice-proceed/add', 'Projects\NoticeProceedController@add_notice_proceed');
    Route::post('notice-proceed/{con_id}/update', 'Projects\NoticeProceedController@update_notice_proceed');
    Route::get('notice-proceed/{con_id}', 'Projects\NoticeProceedController@get_notice_proceed_single');
    Route::get('{project_id}/notice-proceed', 'Projects\NoticeProceedController@get_notice_proceed_project');
    Route::get('{project_id}/notice-proceed-single', 'Projects\NoticeProceedController@get_notice_proceed_project_single');
});

/**** NOTICE PROCEED VIEW ****/
Route::get('dashboard/{project_id}/notice_proceed', function () {
    return view('/notice_proceed/notice_proceed_view_all');
});
Route::get('dashboard/{project_id}/notice_proceed/add', function () {
    return view('/notice_proceed/notice_proceed_add');
});
Route::get('dashboard/{project_id}/notice_proceed/{notice_proceed_id}/update', function () {
    return view('/notice_proceed/notice_proceed_update');
});


/*  --------------------------------------------------------------------------
    PROJECT NOTICE PROCEED API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('notice-of-proceed/add', 'Projects\NoticeofProceedController@add_notice_proceed');
//     Route::post('notice-proceed/{con_id}/update', 'Projects\NoticeofProceedController@update_notice_proceed');
    Route::get('notice-of-proceed/{con_id}', 'Projects\NoticeofProceedController@get_notice_of_proceed_single');
    Route::get('{project_id}/notice-of-proceed', 'Projects\NoticeofProceedController@get_notice_of_proceed_project');
//     Route::get('{project_id}/notice-proceed-single', 'Projects\NoticeofProceedController@get_notice_proceed_project_single');
    Route::get('get_envelop_status/{envelop_id}', 'Projects\NoticeofProceedController@get_envelop_status');
    Route::post('get_document_docusign/{envelop_id}', 'Projects\NoticeofProceedController@get_document_docusign');
});

/**** NOTICE PROCEED VIEW ****/
Route::get('dashboard/{project_id}/notice_of_proceed', function () {
    return view('/notice_of_proceed/notice_of_proceed_view_all');
});
Route::get('dashboard/{project_id}/notice_of_proceed/add', function () {
    return view('/notice_of_proceed/notice_of_proceed_add');
});
Route::get('dashboard/{project_id}/notice_of_proceed/{notice_id}', function () {
    return view('/notice_of_proceed/notice_of_proceed_single');
});
Route::get('redirect_docusign', function () {
    return view('redirect_docusign');
});
// Route::get('dashboard/{project_id}/notice_proceed/{notice_proceed_id}/update', function () {
//     return view('/notice_proceed/notice_proceed_update');
// });

/*  --------------------------------------------------------------------------
    MINUTES OF MEETINGS API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('minutes-meeting/add', 'Projects\MinutesMeetingController@add_minutes_meeting');
    Route::post('minutes-meeting/{pm_id}/update', 'Projects\MinutesMeetingController@update_minutes_meeting');
    Route::get('minutes-meeting/{pm_id}', 'Projects\MinutesMeetingController@get_minutes_meeting_single');
    Route::get('{project_id}/minutes-meeting', 'Projects\MinutesMeetingController@get_minutes_meeting_project');
});

/**** MINUTES OF MEETING VIEW ****/
Route::get('dashboard/{project_id}/minutes_meeting', function () {
    return view('/minutes_meeting/minutes_meeting_view_all');
});
Route::get('dashboard/{project_id}/minutes_meeting/add', function () {
    return view('/minutes_meeting/minutes_meeting_add');
});
Route::get('dashboard/{project_id}/minutes_meeting/{minutes_meeting_id}/update', function () {
    return view('/minutes_meeting/minutes_meeting_update');
});


/*  --------------------------------------------------------------------------
    SUBMITTAL API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
Route::get('{project_id}/get-submittal-log', 'Projects\SubmittalsController@get_submittal_log');
Route::post('submittal/add', 'Projects\SubmittalsController@add_submittal');
Route::post('submittal/{sub_id}/update', 'Projects\SubmittalsController@update_submittal');
Route::get('submittal/{sub_id}', 'Projects\SubmittalsController@get_submittal_single');
Route::get('{project_id}/submittal', 'Projects\SubmittalsController@get_submittal_project');
Route::get('{project_id}/submittal_new_number', 'Projects\SubmittalsController@get_new_submittal_number');
Route::get('{project_id}/resubmittal_new_number/{submittal_id}', 'Projects\SubmittalsController@get_new_resubmittal_number');
});


/**** SUBMITTALS VIEW ****/
Route::get('dashboard/{project_id}/submittals', function () {
    return view('/submittals/submittals_view_all');
});
Route::get('dashboard/{project_id}/submittals/add', function () {
    return view('/submittals/submittals_add');
});
Route::get('dashboard/{project_id}/submittals/{submittals_id}', function () {
    return view('/submittals/submittals_single');
});
Route::get('dashboard/{project_id}/submittals/{submittals_id}/update', function () {
    return view('/submittals/submittals_update');
});
Route::get('dashboard/{project_id}/submittals_log', function () {
    return view('/submittals/submittals_log');
});

/*  --------------------------------------------------------------------------
    SUBMITTAL REVIEW API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
Route::post('submittal-review/add', 'Projects\SubmittalsController@add_submittal_review');
Route::post('submittal-review/{sr_id}/update', 'Projects\SubmittalsController@update_submittal_review');
Route::get('submittal-review/{sr_id}', 'Projects\SubmittalsController@get_submittal_review_single');
Route::get('{project_id}/submittal-review', 'Projects\SubmittalsController@get_submittal_review_project');
});
Route::get('check_review_response', 'Projects\SubmittalsController@check_review_response');

Route::get('dashboard/{project_id}/submittal_review/{sr_id}/update', function () {
    return view('/submittals/submittals_review_update');
});
Route::get('dashboard/{project_id}/submittal_review', function () {
    return view('/submittals/submittals_review_view_all');
});

/*  --------------------------------------------------------------------------
    REQUEST FOR INFORMATION API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::get('{project_id}/get-request-information-log', 'Projects\RequestInfoController@get_request_information_log');
    Route::post('request-information/add', 'Projects\RequestInfoController@add_request_information');
    Route::post('request-information/{ri_id}/update', 'Projects\RequestInfoController@update_request_information');
    Route::get('request-information/{ri_id}', 'Projects\RequestInfoController@get_request_information_single');
    Route::get('{project_id}/request-information', 'Projects\RequestInfoController@get_request_information_project');
    Route::get('{project_id}/ref-for-info-new-number', 'Projects\RequestInfoController@get_new_request_number');
});

/**** REQUEST FOR INFORMATION VIEW ****/
Route::get('dashboard/{project_id}/req_for_info', function () {
    return view('/req_for_info/req_for_info_view_all');
});
Route::get('dashboard/{project_id}/req_for_info/add', function () {
    return view('/req_for_info/req_for_info_add');
});
Route::get('dashboard/{project_id}/req_for_info/{req_for_info_id}', function () {
    return view('/req_for_info/req_for_info_single');
});
Route::get('dashboard/{project_id}/req_for_info/{req_for_info_id}/update', function () {
    return view('/req_for_info/req_for_info_update');
});
Route::get('dashboard/{project_id}/req_for_info_log', function () {
    return view('/req_for_info/req_for_info_log');
});

/*  --------------------------------------------------------------------------
    REQUEST FOR INFORMATION REVIEW API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    // Route::post('request-review/add', 'Projects\RequestInfoController@add_request_review');
    Route::post('request-review/{rir_id}/update', 'Projects\RequestInfoController@update_request_review');
    Route::get('request-review/{rir_id}', 'Projects\RequestInfoController@get_request_review_single');
    Route::get('{request_id}/request-review', 'Projects\RequestInfoController@get_request_review_project');
});
Route::get('check_request_review_response', 'Projects\RequestInfoController@check_request_review_response');

Route::get('dashboard/{project_id}/req_for_info_review/{sr_id}/update', function () {
    return view('/req_for_info/req_for_info_review_update');
});
Route::get('dashboard/{project_id}/req_for_info_review', function () {
    return view('/req_for_info/req_for_info_review_view_all');
});

/*  --------------------------------------------------------------------------
    SURVEY API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
Route::get('{project_id}/get-survey-log', 'Projects\SurveyController@get_survey_log');
Route::post('survey/add', 'Projects\SurveyController@add_survey');
Route::post('survey/{sur_id}/update', 'Projects\SurveyController@update_survey');
Route::get('survey/{sur_id}', 'Projects\SurveyController@get_survey_single');
Route::get('{project_id}/survey', 'Projects\SurveyController@get_survey_project');
Route::get('{project_id}/survey-new-number', 'Projects\SurveyController@get_new_survey_number');
});

/**** SURVEY VIEW ****/
Route::get('dashboard/{project_id}/survey', function () {
    return view('/survey/survey_view_all');
});
Route::get('dashboard/{project_id}/survey/add', function () {
    return view('/survey/survey_add');
});
Route::get('dashboard/{project_id}/survey/{survey_id}', function () {
    return view('/survey/survey_single');
});
Route::get('dashboard/{project_id}/survey/{survey_id}/update', function () {
    return view('/survey/survey_update');
});
Route::get('dashboard/{project_id}/survey_log', function () {
    return view('/survey/survey_log');
});


/*  --------------------------------------------------------------------------
    SURVEY REVIEW API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('survey-review/add', 'Projects\SurveyController@add_survey_review');
    Route::post('survey-review/{sr_id}/update', 'Projects\SurveyController@update_survey_review');
    Route::get('survey-review/{sr_id}', 'Projects\SurveyController@get_survey_review_single');
    Route::get('{project_id}/survey-review', 'Projects\SurveyController@get_survey_review_project');
});
Route::get('check_survey_review_response', 'Projects\SurveyController@check_survey_review_response');

/**** SURVEY VIEW ****/
Route::get('dashboard/{project_id}/survey_review', function () {
    return view('/survey/survey_review_view_all');
});
Route::get('dashboard/{project_id}/survey_review/{survey_id}/update', function () {
    return view('/survey/survey_review_update');
});

/*  --------------------------------------------------------------------------
    SERVICE ALERT TICKET API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('service-alert/add', 'Projects\ServicealertController@add_service_alert');
    Route::post('service-alert/{psa_id}/update', 'Projects\ServicealertController@update_service_alert');
    Route::get('service-alert/{psa_id}', 'Projects\ServicealertController@get_service_alert_single');
    Route::get('{project_id}/service-alert', 'Projects\ServicealertController@get_service_alert_project');
}); 

/**** BID VIEW ****/
Route::get('dashboard/{project_id}/service_alert', function () {
    return view('/service_alert/service_alert_view_all');
});
Route::get('dashboard/{project_id}/service_alert/add', function () {
    return view('/service_alert/service_alert_add');
});
Route::get('dashboard/{project_id}/service_alert/{service_alert_id}/update', function () {
    return view('/service_alert/service_alert_update');
});

/*  --------------------------------------------------------------------------
    BID ITEMS API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('bid-items/add', 'Projects\ContractitemController@add_bid_items');
    Route::post('bid-items/{pbi_id}/update', 'Projects\ContractitemController@update_bid_items');
    Route::get('bid-items/{pbi_id}', 'Projects\ContractitemController@get_bid_items_single');
    Route::get('{project_id}/bid-items', 'Projects\ContractitemController@get_bid_items_project');
    Route::get('{project_id}/bid-items-total', 'Projects\ContractitemController@get_total_amount_project');
    Route::get('{project_id}/bid-items-total', 'Projects\ContractitemController@get_total_amount_project');
    Route::post('{project_id}/bid-items-qty', 'Projects\ContractitemController@add_bid_items_qty');
    Route::get('{project_id}/get-bid-items-qty', 'Projects\ContractitemController@get_bid_items_qty');
    Route::get('{project_id}/get_dashboard_info', 'Projects\ProjectController@get_dashboard_info');
}); 
Route::get('{project_id}/get_dashboard_info', 'Projects\ProjectController@get_dashboard_info');
/**** BID VIEW ****/
Route::get('dashboard/{project_id}/contract_item', function () {
    return view('/contract_item/contract_item_view_all');
});
Route::get('dashboard/{project_id}/contract_item/add', function () {
    return view('/contract_item/contract_item_add');
});
Route::get('dashboard/{project_id}/contract_item/{contract_item_id}/update', function () {
    return view('/contract_item/contract_item_update');
});
Route::get('dashboard/{project_id}/contract_item_qty', function () {
    return view('/contract_item/contract_item_qty');
});

/*  --------------------------------------------------------------------------
    PROJECT DAILY REPORT API'S
    -------------------------------------------------------------------------- */
Route::get('daily-cron-report-generate', 'Projects\DailyReportController@daily_cron_report_generate');
Route::get('docusign-download-document', 'Projects\DocusignController@download_documents');
Route::post('download_docusign_documents', 'Projects\DocusignController@download_docusign_documents');

Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('daily-report/add', 'Projects\DailyReportController@add_daily_report');
    Route::post('daily-report/{pdr_id}/update', 'Projects\DailyReportController@update_daily_report');
    Route::get('daily-report/{pdr_id}', 'Projects\DailyReportController@get_daily_report_single');
    Route::get('daily-report-from-log/{pdr_id}', 'Projects\DailyReportController@get_daily_report_single_log');
    Route::get('{project_id}/daily-report', 'Projects\DailyReportController@get_daily_report_project');
    Route::get('{project_id}/daily-report-logs/{pdr_id}', 'Projects\DailyReportController@get_daily_report_logs_project');
    Route::post('{project_id}/daily-quantity-complete', 'Projects\DailyReportController@add_daily_quantity_complete');
    Route::post('daily-quantity-complete/{item_id}/{report_id}/update', 'Projects\DailyReportController@update_daily_quantity_complete');
    Route::post('daily-quantity-complete/{project_id}/add', 'Projects\DailyReportController@add_daily_resource_used');
    Route::post('daily-material-delivered/{project_id}/add', 'Projects\DailyReportController@add_material_delivered');
    Route::post('daily-photo-video/{project_id}/add', 'Projects\DailyReportController@add_photo_video');
    Route::get('{report_id}/daily-quantity-complete', 'Projects\DailyReportController@get_daily_quantity_complete');
    Route::post('dashboard/{project_id}/daily_construction_report/{report_id}/get_new_report_id', 'Projects\DailyReportController@get_new_report_id');
    Route::get('{report_id}/daily-resource-used', 'Projects\DailyReportController@get_daily_resource_used');
    Route::get('{report_id}/daily-material-delivered', 'Projects\DailyReportController@get_daily_material_delivered');
    Route::get('{report_id}/daily-video-photo', 'Projects\DailyReportController@get_daily_video_photo');
    Route::get('{report_id}/subcontractor-work-detail', 'Projects\DailyReportController@subcontractor_work_detail');
});
    Route::get('get_weather', 'Projects\DailyReportController@get_weather');

/**** DAILY REPORT VIEW ****/
Route::get('dashboard/{project_id}/daily_construction_report', function () {
    return view('/daily_construction_report/daily_construction_report_view_all');
});
Route::get('dashboard/{project_id}/daily_construction_report/{daily_construction_report_id}/serial/{serial_id}', function () {
    return view('/daily_construction_report/daily_construction_report_single');
});
Route::get('dashboard/{project_id}/daily_construction_report/{daily_construction_report_id}/serial/{serial_id}/update', function () {
    return view('/daily_construction_report/daily_construction_report_update');
});
Route::get('dashboard/{project_id}/daily_construction_report/{daily_construction_report_id}/logs', function () {
    return view('/daily_construction_report/daily_construction_report_logs');
});

/*  --------------------------------------------------------------------------
    WEEKLY STATEMENT OF CONTRACT DAYS API'S
    -------------------------------------------------------------------------- */
Route::get('weekly-cron-report-generate', 'Projects\WeeklyReportController@weekly_cron_report_generate');
Route::group(['middleware' => ['jwt-auth']], function () {
Route::get('weekly-report/{pwr_id}', 'Projects\WeeklyReportController@get_weekly_report_single');
Route::get('weekly-report-days/{report_id}', 'Projects\WeeklyReportController@get_weekly_report_single_days');
Route::get('weekly-report-days-count/{project_id}', 'Projects\WeeklyReportController@get_weekly_report_single_days_count');
Route::post('weekly-report-days-update/{days_id}', 'Projects\WeeklyReportController@update_day_quantity_complete');
Route::post('weekly-report-days-update_week/{days_id}', 'Projects\WeeklyReportController@update_day_quantity_complete_week');
Route::post('weekly-report-update/{report_id}/update', 'Projects\WeeklyReportController@update_weekly_report');
Route::get('{project_id}/get-weekly-report', 'Projects\WeeklyReportController@get_all_weekly_report');
}); 

/**** WEEKLY STATEMENT OF CONTRACT DAYS VIEW ****/
Route::get('dashboard/{project_id}/weekly_statement', function () {
    return view('/weekly_statement/weekly_statement_view_all');
});
Route::get('dashboard/{project_id}/weekly_statement/{weekly_statement_id}', function () {
    return view('/weekly_statement/weekly_statement_single');
});

Route::get('dashboard/{project_id}/weekly_statement_week/{weekly_statement_id}', function () {
    return view('/weekly_statement/weekly_statement_single_week');
});
Route::get('dashboard/{project_id}/weekly_statement/{weekly_statement_id}/update', function () {
    return view('/weekly_statement/weekly_statement_update');
});

Route::get('dashboard/{project_id}/weekly_statement_week/{weekly_statement_id}/update', function () {
    return view('/weekly_statement/weekly_statement_update_week');
});

/*  --------------------------------------------------------------------------
    Picture / Video API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('picture/add', 'Projects\PictureVideoController@add_picture');
    Route::get('picture/{pic_id}/update', 'Projects\PictureVideoController@update_picture');
    Route::get('{project_id}/picture', 'Projects\PictureVideoController@get_project_picture');
    Route::get('picture/{pic_id}', 'Projects\PictureVideoController@get_picture_single');
    Route::get('picture/{pic_id}/parent_id', 'Projects\PictureVideoController@get_picture_parent_id_single');
    Route::get('{project_id}/picture/picture_count', 'Projects\PictureVideoController@get_project_count_picture');
    Route::get('{project_id}/picture/video_count', 'Projects\PictureVideoController@get_project_count_video');
}); 

/**** WEEKLY STATEMENT OF CONTRACT DAYS VIEW ****/
Route::get('dashboard/{project_id}/picture_video', function () {
    return view('/picture_video/picture_view_all');
});
Route::get('dashboard/{project_id}/picture_video/{psa_id}', function () {
    return view('/picture_video/picture_view_single');
});
Route::get('dashboard/{project_id}/picture/summary', function () {
    return view('/picture_video/picture_summary');
});

/*  --------------------------------------------------------------------------
    PROJECT CONTRACT TEST RESULT API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('test_result/add', 'Projects\TestResultController@add_text_result');
    Route::post('test_result/{tr_id}/update', 'Projects\TestResultController@update_text_result');
    Route::get('test_result/{tr_id}', 'Projects\TestResultController@get_test_result_single');
    Route::get('{project_id}/test_result', 'Projects\TestResultController@get_test_result_project');
});
/**** PROJECT BOND VIEW ****/
Route::get('dashboard/{project_id}/test_result', function () {
    return view('/test_result/test_result_view_all');
});
Route::get('dashboard/{project_id}/test_result/add', function () {
    return view('/test_result/test_result_add');
});
Route::get('dashboard/{project_id}/test_result/{tr_id}/update', function () {
    return view('/test_result/test_result_update');
});

/*  --------------------------------------------------------------------------
    AGENCY ACCEPTANCE LETTERS API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('acceptance_letter/add', 'Projects\AcceptanceLetterController@add_acceptance_letter');
    Route::post('acceptance_letter/{al_id}/update', 'Projects\AcceptanceLetterController@update_acceptance_letter');
    Route::get('acceptance_letter/{al_id}', 'Projects\AcceptanceLetterController@get_acceptance_letter');
    Route::get('{project_id}/acceptance_letter', 'Projects\AcceptanceLetterController@get_acceptance_letter_project');
});
/**** AGENCY ACCEPTANCE LETTERS VIEW ****/
Route::get('dashboard/{project_id}/acceptance_letter', function () {
    return view('/acceptance_letter/acceptance_letter_view_all');
});
Route::get('dashboard/{project_id}/acceptance_letter/add', function () {
    return view('/acceptance_letter/acceptance_letter_add');
});
Route::get('dashboard/{project_id}/acceptance_letter/{acceptance_letter_id}/update', function () {
    return view('/acceptance_letter/acceptance_letter_update');
});

/*  --------------------------------------------------------------------------
    LABOR COMPLIANCE API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('labor_compliance/add', 'Projects\LaborComplianceController@add_labor_compliance');
    Route::post('labor_compliance/{plc_id}/update', 'Projects\LaborComplianceController@update_labor_compliance');
    Route::post('labor_compliance/{plc_id}/update_dir', 'Projects\LaborComplianceController@update_labor_dir');
    Route::get('labor_compliance/{plc_id}', 'Projects\LaborComplianceController@get_labor_compliance_single');
    Route::get('{project_id}/labor_compliance', 'Projects\LaborComplianceController@get_labor_compliance');
});
/**** LABOR COMPLIANCE VIEW ****/
Route::get('dashboard/{project_id}/labor_compliance', function () {
    return view('labor_compliance/labor_compliance_summary');
});
Route::get('dashboard/{project_id}/labor_compliance/add', function () {
    return view('labor_compliance/labor_compliance_add');
});
Route::get('dashboard/{project_id}/labor_compliance/{acceptance_letter_id}/update', function () {
    return view('labor_compliance/labor_compliance_update');
});
Route::get('dashboard/{project_id}/labor_compliance/{acceptance_letter_id}', function () {
    return view('labor_compliance/labor_compliance_single');
});

/*  --------------------------------------------------------------------------
    BUILT DRAWING API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('build_drawings/add', 'Projects\BuiltDrawingsController@add_built_drawing');
    Route::post('build_drawings/{pbd_id}/update', 'Projects\BuiltDrawingsController@update_built_drawing');
    Route::get('build_drawings/{pbd_id}', 'Projects\BuiltDrawingsController@get_built_drawing');
    Route::get('{project_id}/build_drawings', 'Projects\BuiltDrawingsController@get_built_drawing_project');
    Route::post('build_drawings_status_contractor/{pbd_id}/update', 'Projects\BuiltDrawingsController@update_built_drawing_status_contractor');
    Route::post('build_drawings_status_engineer/{pbd_id}/update', 'Projects\BuiltDrawingsController@update_built_drawing_status_engineer');
});
/**** BUILT DRAWING VIEW ****/
Route::get('dashboard/{project_id}/built_drawing', function () {
    return view('built_drawing/built_drawing_summary');
});
Route::get('dashboard/{project_id}/built_drawing/add', function () {
    return view('built_drawing/built_drawing_add');
});
Route::get('dashboard/{project_id}/built_drawing/{acceptance_letter_id}/update', function () {
    return view('built_drawing/built_drawing_update');
});
Route::get('dashboard/{project_id}/built_drawing/{acceptance_letter_id}', function () {
    return view('built_drawing/built_drawing_single');
});

/*  --------------------------------------------------------------------------
    SCHEDULE API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('schedule/add', 'Projects\ScheduleController@add_schedule');
    Route::post('schedule/{schedule_id}/update', 'Projects\ScheduleController@update_schedule');
    Route::get('schedule/{pbd_id}', 'Projects\ScheduleController@get_schedule');
    Route::get('{project_id}/schedule', 'Projects\ScheduleController@get_schedule_project');
});
/**** BUILT DRAWING VIEW ****/
Route::get('dashboard/{project_id}/schedule', function () {
    return view('schedule/schedule_summary');
});
Route::get('dashboard/{project_id}/schedule/add', function () {
    return view('schedule/schedule_add');
});
Route::get('dashboard/{project_id}/schedule/{acceptance_letter_id}/update', function () {
    return view('schedule/schedule_update');
});
Route::get('dashboard/{project_id}/schedule/{acceptance_letter_id}', function () {
    return view('schedule/schedule_single');
});
/*  --------------------------------------------------------------------------
    BUILT DRAWING API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('unconditional_finals/add', 'Projects\UnconditionalFinalsController@add_unconditional_finals');
    Route::post('unconditional_finals/{puf_id}/update', 'Projects\UnconditionalFinalsController@update_unconditional_finals');
    Route::get('unconditional_finals/{puf_id}', 'Projects\UnconditionalFinalsController@get_unconditional_finals_single');
    Route::get('{project_id}/unconditional_finals', 'Projects\UnconditionalFinalsController@get_unconditional_finals');
});
// /**** BUILT DRAWING VIEW ****/
Route::get('dashboard/{project_id}/unconditional_finals', function () {
    return view('unconditional_finals/unconditional_finals_view');
});
Route::get('dashboard/{project_id}/unconditional_finals/add', function () {
    return view('unconditional_finals/unconditional_finals_add');
});
Route::get('dashboard/{project_id}/unconditional_finals/{puf_id}/update', function () {
    return view('unconditional_finals/unconditional_finals_update');
});
Route::get('dashboard/{project_id}/unconditional_finals/{puf_id}', function () {
    return view('unconditional_finals/unconditional_finals_single');
});

/*  --------------------------------------------------------------------------
    PAYMENT APPLICATION API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    // Route::get('quantity-verification-report/{pqv_report_id}', 'Projects\PaymentApplicationController@get_quantity_verification_detail_report');
    Route::get('{project_id}/payment-application-report', 'Projects\PaymentApplicationController@get_payment_application_report');
    Route::get('{project_id}/payment-application-complete-report', 'Projects\PaymentApplicationController@get_payment_application_complete_report');
    Route::get('payment-application-detail-report/{report_id}', 'Projects\PaymentApplicationController@get_payment_application_detail_report');
    Route::get('payment-application-report-name/{report_id}', 'Projects\PaymentApplicationController@get_payment_application_report_name');
    Route::post('payment-application-report-name/{report_id}/update', 'Projects\PaymentApplicationController@update_payment_application');
});
/**** PAYMENT APPLICATION VIEW ****/
Route::get('dashboard/{project_id}/payment_application_monthly', function () {
    return view('payment_application/payment_application_monthly_view');
});
Route::get('dashboard/{project_id}/payment_application_complete', function () {
    return view('payment_application/payment_application_complete_view');
});
Route::get('dashboard/{project_id}/payment_application/{report_id}', function () {
    return view('payment_application/payment_application_single');
});
Route::get('dashboard/{project_id}/payment_application/{report_id}/update', function () {
    return view('/payment_application/payment_application_update');
});

/*  --------------------------------------------------------------------------
    PAYMENT QUANTITY API'S
    -------------------------------------------------------------------------- */
    Route::get('payment-quantity-verification-report-generate', 'Projects\PaymentQuantityVerificationController@payment_quantity_verification_report_generate');
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::get('quantity-verification-report/{pqv_report_id}', 'Projects\PaymentQuantityVerificationController@get_quantity_verification_detail_report');
    Route::get('{project_id}/quantity-verification-report', 'Projects\PaymentQuantityVerificationController@get_quantity_verification_report');
    Route::get('{project_id}/quantity-verification-complete-report', 'Projects\PaymentQuantityVerificationController@get_quantity_verification_complete_report');
    Route::get('quantity-verification-report-name/{report_id}', 'Projects\PaymentQuantityVerificationController@get_quantity_verification_report_name');
    Route::post('dashboard/{project_id}/payment_quantity_verification/{report_id}/update_approval_status', 'Projects\PaymentQuantityVerificationController@update_approval_status');
    Route::post('dashboard/{project_id}/payment_quantity_verification/{report_id}/update', 'Projects\PaymentQuantityVerificationController@update_payment_quantity_verification');
    //Route::get('dashboard/{project_id}/payment_quantity_verification/{report_id}', 'Projects\PaymentApplicationController@get_payment_application_detail_report');
});
/**** PAYMENT QUANTITY VIEW ****/
Route::get('dashboard/{project_id}/payment_quantity_verification_monthly', function () {
    return view('payment_quantity/payment_quantity_monthly_view');
});
Route::get('dashboard/{project_id}/payment_quantity_verification_complete', function () {
    return view('payment_quantity/payment_quantity_complete_view');
});
Route::get('dashboard/{project_id}/payment_quantity_verification/{report_id}', function () {
    return view('payment_quantity/payment_quantity_single');
});
Route::get('dashboard/{project_id}/payment_quantity_verification/{report_id}/update', function () {
    return view('/payment_quantity/payment_quantity_verification_update');
});
/*  --------------------------------------------------------------------------
    CHANGE ORDER REQUEST API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('change_order_request/add', 'Projects\ChangeOrderRequestController@add_change_order_request');
    Route::post('change_order_request_item/add', 'Projects\ChangeOrderRequestController@add_change_order_request_item');
    Route::post('docusign_change_order_request_send', 'Projects\ChangeOrderRequestController@docusign_change_order_request_send');
    Route::post('change_order_request/{pco_id}/update', 'Projects\ChangeOrderRequestController@update_change_order_request');
    Route::post('change_order_request_item/{pcd_id}/update', 'Projects\ChangeOrderRequestController@update_change_order_request_item');
    Route::get('change_order_request/{pco_id}', 'Projects\ChangeOrderRequestController@get_change_order_request_single');
     Route::get('change_order_request_weekly/{project_id}', 'Projects\ChangeOrderRequestController@get_change_order_request_weeklyreport');
    Route::get('change_order_request_item/{pcd_id}', 'Projects\ChangeOrderRequestController@get_change_order_request_item_single');
    Route::get('{cor_id}/change_order_request_item', 'Projects\ChangeOrderRequestController@get_all_change_order_request_item');
    Route::get('{project_id}/change_order_request', 'Projects\ChangeOrderRequestController@get_all_change_order_request');
    Route::get('{project_id}/change_order_request_new_number', 'Projects\ChangeOrderRequestController@get_new_change_order_number');
    Route::get('{item_id}/get_item_rfi', 'Projects\ChangeOrderRequestController@get_item_rfi');
});

Route::get('update_change_order_review_status', 'Projects\ChangeOrderRequestController@update_change_order_review_status');

/**** CHANGE ORDER REQUEST VIEW ****/
Route::get('dashboard/{project_id}/change_order_request_log', function () {
    return view('change_order_request/change_order_request_summary');
});

Route::get('dashboard/{project_id}/change_order_request', function () {
    return view('change_order_request/change_order_request');
});
Route::get('dashboard/{project_id}/change_order_request/add', function () {
    return view('change_order_request/change_order_request_add');
});
Route::get('dashboard/{project_id}/change_order_request/{coi_id}/update', function () {
    return view('change_order_request/change_order_request_update');
});

Route::get('dashboard/{project_id}/change_order_request_review', function () {
    return view('change_order_request/change_order_request_review');
});
Route::get('dashboard/{project_id}/change_order_request_review/{item_id}/update', function () {
    return view('change_order_request/change_order_request_review_update');
});
Route::get('dashboard/{project_id}/change_order_request_review/{item_id}/view', function () {
    return view('change_order_request/change_order_request_review_view');
});


/*  --------------------------------------------------------------------------
    Admin Section Project Improvement Type API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::get('improvement-type', 'Projects\ImprovementController@get_improvement');
    Route::get('{project_id}/improvement-type-by-owner', 'Projects\ImprovementController@get_improvement_by_owner');
    Route::get('{project_id}/improvement-type', 'Projects\ImprovementController@get_project_improvement');
    Route::post('improvement-type/add', 'Projects\ImprovementController@add_improvement');
    Route::post('improvement-type/{type_id}/update', 'Projects\ImprovementController@update_improvement');
    Route::get('improvement-type/{type_id}', 'Projects\ImprovementController@get_improvement_single');
});
/**** IMPROVEMENT VIEW ****/
Route::get('dashboard/improvement', function () {
    return view('/type_improvement/improvement_view_all');
});
Route::get('dashboard/improvement/add', function () {
    return view('/type_improvement/improvement_add');
});
Route::get('dashboard/improvement/{improvementid}/update', function () {
    return view('/type_improvement/improvement_update');
});

/*  --------------------------------------------------------------------------
    Admin Section Firm API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('firm-name/add', 'Projects\FirmController@add_firm_name');
    Route::post('firm-name/{firm_id}/update', 'Projects\FirmController@update_firm_name');
    Route::get('firm-name/{firm_id}', 'Projects\FirmController@get_firm_name_single');
    Route::get('firm-name', 'Projects\FirmController@get_firm_name');
    Route::get('company-name', 'Projects\FirmController@get_company_name');
    Route::get('agency-name', 'Projects\FirmController@get_agency_name');
    Route::get('{project_id}/company_name_user', 'Projects\FirmController@get_project_firm_name');
    Route::get('{project_id}/company_name_user_agency', 'Projects\FirmController@get_project_firm_name_agency');
});

/**** FIRMS VIEW ****/
Route::get('dashboard/firms', function () {
    return view('/firm/firm_view_all');
});
Route::get('dashboard/firms/add', function () {
    return view('/firm/firm_add');
});
Route::get('dashboard/firms/{firm_id}/update', function () {
    return view('/firm/firm_update');
});

/*  --------------------------------------------------------------------------
    Admin Section Currency API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('currency/add', 'Projects\CurrencyController@add_currency');
    Route::post('currency/{ci_id}/update', 'Projects\CurrencyController@update_currency');
    Route::get('currency/{ci_id}', 'Projects\CurrencyController@get_currency_single');
    Route::get('currency', 'Projects\CurrencyController@get_currency');
});
/**** CURRENCY VIEW ****/
Route::get('dashboard/currency', function () {
    return view('/currency/currency_view_all');
});
Route::get('dashboard/currency/add', function () {
    return view('/currency/currency_add');
});
Route::get('dashboard/currency/{cur_id}/update', function () {
    return view('/currency/currency_update');
});

/*  --------------------------------------------------------------------------
    Admin Section Company Type API'S
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('company-type/add', 'Projects\CompanytypeController@add_company_type');
    Route::post('company-type/{company_type_id}/update', 'Projects\CompanytypeController@update_company_type');
    Route::get('company-type/{company_type_id}', 'Projects\CompanytypeController@get_company_type_single');
    Route::get('company-type', 'Projects\CompanytypeController@get_company_type');
});

/**** CURRENCY VIEW ****/
Route::get('dashboard/company_type', function () {
    return view('/company_type/company_type_view_all');
});
Route::get('dashboard/company_type/add', function () {
    return view('/company_type/company_type_add');
});
Route::get('dashboard/company_type/{cur_id}/update', function () {
    return view('/company_type/company_type_update');
});

/*  --------------------------------------------------------------------------
    Admin Section Project Setting API
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('project_setting_add', 'Projects\ProjectSettingController@project_setting_add');
    Route::get('/{project_id}/project_setting_get/{meta_key}', 'Projects\ProjectSettingController@project_setting_get');
});

/**** PROJECT CURRENCY ****/
Route::get('dashboard/{project_id}/project_currency/update', function () {
    return view('/project_settings/project_currency_update');
});
Route::get('dashboard/{project_id}/project_wage_determination/update', function () {
    return view('/project_settings/project_wage_determination_update');
});

/*  --------------------------------------------------------------------------
    Notification API
    -------------------------------------------------------------------------- */
Route::group(['middleware' => ['jwt-auth']], function () {
    Route::get('notification_add', 'Projects\NotificationController@add_notification');
    Route::get('notification_get_short/{user_id}', 'Projects\NotificationController@get_notification_short');
    Route::get('notification_get_all/{user_id}', 'Projects\NotificationController@get_notification_all');
    Route::get('notification_get_unread/{user_id}', 'Projects\NotificationController@get_notification_unread');
    Route::get('change_notification_status/{pn_id}', 'Projects\NotificationController@change_notification_status');
});
Route::get('dashboard/notification', function () {
    return view('/notifications/notification_view_all');
});


Route::get('dashboard/page12', function () {
    return view('/tanveer/page12');
});
Route::get('dashboard/page15', function () {
    return view('/tanveer/page15');
});
Route::get('dashboard/page16', function () {
    return view('/tanveer/page16');
});
Route::get('dashboard/page17', function () {
    return view('/tanveer/page17');
});




/**** Tanveer URL ****/
Route::get('resetpass', function () {
    return view('/resetpass');
});

Route::get('404', function () {
    return view('/404');
});

Route::get('500', function () {
    return view('/500');
});

Route::get('403', function () {
    return view('/403');
});

Route::get('tanveer1', function () {
    return view('/tanveer1');
});


Route::get('tanveer3', function () {
    return view('/tanveer3');
});

Route::get('tanveer4', function () {
    return view('/tanveer4');
});

Route::get('datepicker', function () {
    return view('/datepicker');
});

// Labor Compliance

Route::get('labor_compliance_summry', function () {
    return view('labor_compliance/labor_compliance_summry');
});

Route::get('labor_compliance_upload', function () {
    return view('labor_compliance/labor_compliance_upload');
});

Route::get('labor_compliance_view', function () {
    return view('labor_compliance/labor_compliance_view');
});

// Change Order Request

// Route::get('change_order_request', function () {
//     return view('/change_order_request');
// });

Route::get('change_order_request', function () {
    return view('/change_order_summry');
});


Route::get('document', function () {
    return view('/document');
});



Route::get('dashboard/{project_id}/contact/{user_id}', function () {
    return view('/contact/contact_view');
});



Route::group(['middleware' => ['jwt-auth']], function () {
    Route::post('preliminary-notice/add', 'Projects\PreliminaryNoticeController@add_preliminary_notice');
    Route::post('preliminary-notice/{preliminary_id}/update', 'Projects\PreliminaryNoticeController@update_preliminary');
    Route::post('lien-release/{preliminary_id}/add', 'Projects\PreliminaryNoticeController@add_lienrelease');
    Route::get('preliminary-notice/{preliminary_id}', 'Projects\PreliminaryNoticeController@get_preliminary_single');
    Route::get('{project_id}/preliminary-notices', 'Projects\PreliminaryNoticeController@get_preliminary_notices');
    Route::get('{project_id}/lien-releases', 'Projects\PreliminaryNoticeController@get_lien_releases');
    Route::post('dashboard/{project_id}/importExcel', 'Projects\ExcelimportController@importExcel');
});

Route::get('dashboard/{project_id}/preliminary_notice_log', function () {
    return view('/project_preliminary_notice/project_preliminary_notice_view');
});
Route::get('dashboard/{project_id}/lien_release_log', function () {
    return view('/project_preliminary_notice/lien_release_view');
});
Route::get('dashboard/{project_id}/preliminary_notice/add', function () {
    return view('/project_preliminary_notice/project_preliminary_notice_add');
});
Route::get('dashboard/{project_id}/preliminary_notice/{pre_id}/update', function () {
    return view('/project_preliminary_notice/project_preliminary_notice_update');
});
Route::get('dashboard/{project_id}/lien_release/{pre_id}/add', function () {
    return view('/project_preliminary_notice/lien_release_add');
});
Route::get('dashboard/{project_id}/preliminary_notice/{pre_id}', function () {
    return view('/project_preliminary_notice/project_preliminary_notice_single');
});

Route::get('dashboard/{project_id}/lien_release/{pre_id}', function () {
    return view('/project_preliminary_notice/lien_release_single');
});

Route::get('dashboard/{project_id}/importExport', 'Projects\ExcelimportController@importExport');
Route::get('dashboard/{project_id}/downloadExcel/{type}', 'Projects\ExcelimportController@downloadExcel');


