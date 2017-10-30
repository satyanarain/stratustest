
@include('include/header')
@include('include/project_sidebar')

<!-- body content start-->
<div class="body-content" >
    <?php $project_id = Request::segment(2); ?>
    @include('include/top_bar')

    <!-- page head start-->
    <div class="page-head">
        <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
        <h3 class="m-b-less">Payment Quantity Verification Monthly Summaries</h3>
        <div class="state-information">
            <a href="{{ url('/dashboard/'.$project_id.'/payment_quantity_verification_complete') }}" class="btn btn-info">View Complete Report</a>
        </div>
    </div>
    <!-- page head end-->

    <!--body wrapper start-->
    <div class="wrapper" style="display: none;">
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <div class="loading_data" style="text-align: center;">
                        <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                    </div>
                    <div class="table-parent">
                        <table class="table convert-data-table data-table table-bordered" id="view_users_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Report Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
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
    <script src="{{ url('/resources/assets/dist/payment_quantity_verification_view_all.js') }}"></script>

    @include('include/footer')
