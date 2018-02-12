
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">View Test Results</h3>
                <div class="state-information hide_add_permission">
                    <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/test_result/add" class="btn btn-success"><i class="fa fa-plus"></i> Add New Test Results</a>
                </div>
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
                        <table class="table convert-data-table data-table view-firms custom-grid dataTable no-footer table_scroll_x_axis table-bordered" id="view_users_table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Compaction Firm</th>
                            <th>Compaction Date</th>
                            <th># of Compaction Tests</th>
                            <th width="20%">Compaction Location</th>
                            <th>Compaction Document</th>
                            <th>PCC Strength Firm</th>
                            <th>PCC Strength Date</th>
                            <th>PCC Strength # of Test</th>
                            <th>PCC Strength Location</th>
                            <th>PCC Strength Document</th>
                            <th>Other Test Name</th>
                            <th>Other Test Firm</th>
                            <th>Other Test Date</th>
                            <th>Other # of Tests</th>
                            <th>Other Test Location</th>
                            <th>Other Test Document</th>
                            <th>Status</th>
                            <th>Action</th>
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
<script src="{{ url('/resources/assets/dist/test_result_view_all.js') }}"></script>

@include('include/footer')
