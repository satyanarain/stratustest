
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">View Underground Service Alerts</h3>
                <div class="state-information hide_add_permission" style="display: none; width: 250px;">
                    <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/service_alert/add" class="btn btn-success"><i class="fa fa-plus"></i> Add New Underground Service Alert</a>
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
                        <table class="table convert-data-table data-table custom-grid table-bordered table_scroll_x_axis" id="view_users_table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Date Called In</th>
                            <th>Date Ticket is Valid</th>
                            <th>Ticket Number</th>
                            <th>Location</th>
                            <th>Expiration Date</th>
                            <th>Status</th>
                            <th>Associated Work Complete</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        
                        </tbody>
                        </table>
                        </section>
                    </div>
                </div>
            </div>
            <!--body wrapper end-->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/service_alert_view_all.js') }}"></script>
@include('include/footer')