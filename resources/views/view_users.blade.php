
        @include('include/header')
        @include('include/sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">View Users</h3>
                <div class="state-information">
                    <a href="{{ url('/users/add') }}" class="btn btn-success"><i class="fa fa-plus"></i> Add New User</a>
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
                        <table class="table convert-data-table data-table view-users custom-grid  table_scroll_x_axis" id="view_users_table">
                        <thead>
                        <tr>
                            <th>
                                Username
                            </th>
                            <th>
                                Image
                            </th>
                            <th>
                                First Name / Last Name
                            </th>
                            <th>
                                Email
                            </th>
                            <th>
                                Company Name
                            </th>
                            <th>
                                Position/Title
                            </th>
                            <th class="hide_owner">
                                Role
                            </th>
                            <th>
                                Status
                            </th>
                            <th class="hide_owner">
                                User Parent
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
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/view_users.js?v=1.0') }}"></script>
@include('include/footer')