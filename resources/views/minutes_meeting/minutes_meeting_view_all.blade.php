
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">View Meeting Minutes</h3>
                <div class="state-information hide_add_permission" style="display: none;">
                    <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/minutes_meeting/add" class="btn btn-success"><i class="fa fa-plus"></i> Add Meeting Minutes</a>
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
                            <th>Date</th>
                            <th>Project Description</th>
                            <!-- <th>Special Considerations</th> -->
                            <th>Agenda</th>
                            <th>Sign-in Sheet</th>
                            <th>Meeting Minutes</th>
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
<script src="{{ url('/resources/assets/dist/minutes_meeting_view_all.js') }}"></script>

@include('include/footer')