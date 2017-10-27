
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">View Contract Items</h3>
                <div class="state-information hide_add_permission" style="display: none;">
                    <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/contract_item/add" class="btn btn-success"><i class="fa fa-plus"></i> Add New Contract Items</a>
                </div>
                <div class="state-information hide_add_permission" style="display: none; margin-right: 10px;">
                    <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/contract_item_qty" class="btn btn-success"><i class="fa fa-plus"></i> Number of Contract Items</a>
                </div>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-danger alert-block fade in" id="exceeded_qty" style="display: none;">
                            <h4>
                                You have exceeded the limit of number of contacts which was <span id="actual_qty"></span> and you have entered <span id="enter_qty"></span> already.
                            </h4>
                        </div>
                        <div class="alert alert-success alert-block fade in">
                            <h4>
                                Contract Amount is: <span class="project_currency"></span> <span class="project_amount"></span>
                            </h4>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <section class="panel">
                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                        <table class="table convert-data-table data-table custom-grid table-bordered" id="view_users_table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th>Unit of Measure</th>
                            <th>QTY</th>
                            <th>Unit Price</th>
                            <th>Extended Price</th>
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
<script src="{{ url('/resources/assets/dist/contract_item_view_all.js') }}"></script>
@include('include/footer')