
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">View Notice to Proceed</h3>
                <div class="state-information hide_add_permission">
                    <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/notice_of_proceed/add" class="btn btn-success"><i class="fa fa-plus"></i> Add Notice to Proceed</a>
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
                        <table class="table convert-data-table data-table custom-grid table-bordered" id="view_users_table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Contactor Name</th>
                            <th>Date of Notice</th>
                            <th>Start Date</th>
                            <th>Duration in Days</th>
                            <th>Calendar</th>
                            <th>Liquidated Damages</th>
                            <th>Notice of Proceed</th>
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
<script src="{{ url('/resources/assets/dist/notice_of_proceed_view_all.js') }}"></script>

@include('include/footer')