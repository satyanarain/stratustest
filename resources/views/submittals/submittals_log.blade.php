
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Submittal Log</h3>
                <div class="state-information">
                  <div class="addnew-submittal-btn hide_add_permission" style="display: none;">
                      <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/submittals/add" class="btn btn-success"><i class="fa fa-plus"></i> Add New Submittal</a>
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
                        <table class="table convert-data-table data-table custom-grid table-bordered table_scroll_x_axis" id="view_users_table">
                        <thead>
                        <tr>
                            <th>S. No.</th>
                            <th>Submittal #</th>
                            <th width="25%">Description</th>
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

                        </table>
                        </div>
                        </section>
                    </div>
                    <div class="col-sm-12">
                        <a href="{{ url('/dashboard/'.$project_id.'/req_for_info_log') }}" class="btn btn-info sub-btn pull-right">Next</a>
                    </div>
                </div>
            </div>
            <!--body wrapper end-->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/submittals_log.js?v=1') }}"></script>

@include('include/footer')
