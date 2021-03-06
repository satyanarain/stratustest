
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">View Certificates of Insurance</h3>
                <div class="state-information hide_add_permission">
                    <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/certificate/add" class="btn btn-success"><i class="fa fa-plus"></i> Add New Certificates of Insurance</a>
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
                        <table class="table convert-data-table data-table custom-grid table_scroll_x_axis table-bordered" id="view_users_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th style="text-align: center" colspan="3">General Liability</th>
                                <th style="text-align: center" colspan="3">Workers Comp </th>
                                <th style="text-align: center" colspan="3">Auto Liability </th>
                                <th style="text-align: center" colspan="3">Umbrella Liability </th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>    
                        <tr>
                            <th>#</th>
                            <th>Contractor Name</th>
                            <th> Amount</th>
                            <th>Date</th>
                            <th>Certificate</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Certificate</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Certificate</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Certificate</th>
                            <th>Insurance Summary</th>
                            <th>Status</th>
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
<script src="{{ url('/resources/assets/dist/certificate_view_all.js') }}"></script>
@include('include/footer')
