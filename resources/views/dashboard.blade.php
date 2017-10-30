
        @include('include/header')
        @include('include/sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')
            <!-- Email Unverified Popup -->
            <span id="email_unverified_error" style="display:none; margin: 0px 10px;">
                <?php $user_id = Session::get('user.id'); ?>
                <div class="alert alert-block alert-danger fade in"><button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button><strong>Account not verified! </strong><a href="{{ url('/users/email-verification/') }}/<?php echo $user_id; ?>">click to verify your account</a></div>
            </span>
            <!-- page head start-->
            <div class="page-head clearfix">
                <h3>Projects</h3>
                <a href="{{ url('/dashboard/projects/add') }}" class="btn btn-success right hide_permission" style="display: none;"><i class="fa fa-plus"></i> Add New Project</a>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper">
                <div class="row">
                    <div class="col-md-12">
                        <section class="panel">
                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt=""/>
                            </div>
                            <table class="table convert-data-table data-table custom-grid" id="view_users_table">
                                <thead>
                                <tr>  
                                    <th>#</th>
                                    <th>Project No / Name</th>
                                    <th class="hide_owner_user">Owner</th>
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
<script src="{{ url('/resources/assets/dist/dashboard.js' )}}"></script>
@include('include/footer')