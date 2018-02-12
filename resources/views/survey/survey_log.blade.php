
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head survy-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                 <span class="title-submittallog"><h3 class="m-b-less">Survey Log</h3></span>
                <div class="state-information">
                    <!-- <div class="progress progress-striped active progress-sm m-b-20"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"><span class="sr-only">100% Complete</span></div></div> -->
                    <div class="addnew-submittal-btn hide_add_permission" style="display: none;">
                        <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/survey/add" class="btn btn-success"><i class="fa fa-plus"></i> Add New Survey</a>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper">
                <div class="row">
                    <div class="col-sm-12">
                        <section class="panel">
                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                        <div class="table-parent">
                        <table class="table convert-data-table data-table custom-grid table-bordered " id="view_users_table">
                        <thead>
                        <tr>
                            <th>Survey #</th>
                            <th width="30%">Description</th>
                            <th>Date of Request</th>
                            <th>Date Survey Complete</th>
                            <th>Survey Requests</th>
                            <th>Survey Cut Sheets</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        
                        </table>
                        </div>
                        </section>
                    </div>
                </div>
            </div>
            <!--body wrapper end-->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/survey_log.js') }}"></script>

@include('include/footer')