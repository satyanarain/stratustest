
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less" style="text-transform: none;">Add Contact in Project</h3>
                <div class="state-information hide_add_permission" style="display: none;">
                    <a href="{{ url('/users/add') }}" class="btn btn-success"><i class="fa fa-plus"></i> Add New Contact</a>
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
                        <table class="table convert-data-table data-table add-contacts custom-grid table_scroll_x_axis" id="view_users_table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email / Phone</th>
                            <th>Company</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        </table>

                            <a href="{{ url('/dashboard/'.$project_id.'/contact') }}" class="btn btn-info sub-btn">Back</a>

                        </section>
                      </div>

                </div>
            </div>
            <!--body wrapper end-->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/contact_add.js') }}"></script>
@include('include/footer')
