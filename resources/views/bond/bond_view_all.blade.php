
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">View Contractor Bonds</h3>
                <div class="state-information hide_add_permission" style="display: none;">
                    <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/bond/add" class="btn btn-success"><i class="fa fa-plus"></i> Add New Contractor Bonds</a>
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
                        <table class="table convert-data-table data-table custom-grid table_scroll_x_axis  table-bordered" id="view_users_table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Contractor Name</th>
                            <th>Performance Bond Amount</th>
                            <th>Performance Bond Date</th>
                            <th>Performance Bond Number</th>
                            <th>Performance Bond Certificate</th>
                            <th>Payment Bond Amount</th>
                            <th>Payment Bond&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Date</th>
                            <th>Payment Bond Number</th>
                            <th>Payment Bond Certificate</th>
                            <th>Maintenance Bond Amount</th>
                            <th>Maintenance Bond Date</th>
                            <th>Maintenance Bond Number</th>
                            <th>Maintenance Bond Certificate</th>
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
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js?v=1.0') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js?v=1.0') }}"></script>
<script src="{{ url('/resources/assets/dist/bond_view_all.js?v=1.0') }}"></script>

@include('include/footer')
