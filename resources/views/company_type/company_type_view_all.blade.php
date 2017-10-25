
        @include('include/header')
        @include('include/sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">View Company Types</h3>
                <div class="state-information">
                    <a href="{{ url('/dashboard/company_type/add') }}" class="btn btn-success">Add New Company Type</a>
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
                        <table class="table convert-data-table data-table custom-grid table-bordered" id="view_users_table">
                        <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                Company Type
                            </th>
                            <th class="hide_owner">
                                User
                            </th>
                            <th>
                                Status
                            </th>
                            <th>
                                Action
                            </th>
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
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js?v=1.0') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js?v=1.0') }}"></script>
<script src="{{ url('/resources/assets/dist/company_type_view_all.js?v=1.0') }}"></script>
@include('include/footer')
