        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">View Notice to Proceed</h3>
                <?php $project_id = Request::segment(2); ?>
                <div class="state-information" style="width: 200px; ">
                    <!-- <div class="progress progress-striped active progress-sm m-b-20"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"><span class="sr-only">100% Complete</span></div></div> -->
                </div>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper">
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
        <div class="form-group col-md-6">
            <label>Contractor</label>
        </div>
        <div class="form-group col-md-6">
            <span id="contractor_name"></span>
        </div>
        <div class="clearfix"></div>

        <div class="form-group col-md-6">
            <label>Date of Notice</label>
        </div>
        <div class="form-group col-md-6">
            <span id="date_of_notice"></span>
        </div>
        <div class="clearfix"></div>

        <div class="form-group col-md-6">
            <label>Start Date</label>
        </div>
        <div class="form-group col-md-6">
            <span id="start_date"></span>
        </div>
        <div class="clearfix"></div>

        <div class="form-group col-md-6">
            <label>Duration (in days)</label>
        </div>
        <div class="form-group col-md-6">
            <span id="duration_days"></span>
        </div>
        <div class="clearfix"></div>

        <div class="form-group col-md-6">
            <label>Day Working</label>
        </div>
        <div class="form-group col-md-6">
            <span id="days_working"></span>
        </div>
        <div class="clearfix"></div>

        <div class="form-group col-md-6">
            <label>Liquidated Damages</label>
        </div>
        <div class="form-group col-md-6">
            <span id="liquidated_amount"></span>
        </div>
        <div class="clearfix"></div>

        <div class="form-group col-md-12">
            <h3>Signature Status</h3>
            <div class="clearfix"></div>
            <div id="sign_status_list">
                <div class="loading_signature_status" style="text-align: center;">
                    <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="form-group col-md-12">
            <h3>Document</h3>
            <div class="clearfix"></div>
            <div id="sign_document">
                <div class="loading_document" style="text-align: center;">
                 <img src="{{ url('/resources/assets/img/loading-sidebar.svg') }}" alt=""/>
              </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
        

        <div class="form-group col-md-12">
            <a data-href="{{ url('/dashboard/'.$project_id.'/notice_proceed') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>
            <a data-href="{{ url('/dashboard/'.$project_id.'/minutes_meeting') }}" class="btn btn-info sub-btn continue_button" data-toggle="modal" data-target="#confirm-continue">Continue</a>
            <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
        </div>
    </div><!-- Row Close -->
</div><!-- Col 6 Close -->
<div class="clearfix"></div>

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/notice_of_proceed_single.js') }}"></script>
@include('include/footer')
