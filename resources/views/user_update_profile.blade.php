
        @include('include/header')
        @include('include/sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">Update User</h3>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="loading_data" style="text-align: center;">
                                <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <div class="panel-body">
                            <div id="updateuserinfo" style="display: none"></div>
                                    <div class="row">
                                    <div class="form-group col-md-6 hide_user">
                                        <label for="uname">Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="uname" disabled>
                                    </div>
                                    <div class="form-group col-md-6 hide_user">
                                        <label for="email">Email Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="email" disabled>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="fname">First Name</label>
                                        <input type="text" class="form-control" id="fname">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="lname">Last Name</label>
                                        <input type="text" class="form-control" id="lname">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="cname">Company Name</label>
                                        <!-- <div class="loading_data" style="text-align: center;">
                                           <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                        </div> -->
                                        <select class="form-control" id="firm_name">
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="position">Position / Title</label>
                                        <input type="text" class="form-control" id="position">
                                    </div>
                                    <div class="clearfix"></div>
                                    <!-- <div class="form-group col-md-6">
                                        <label for="pnum">Phone Number</label>
                                        <input type="text" class="form-control" id="pnum">
                                    </div> -->
                                    <div class="input_fields_wrap">
                                    </div>

                                    <div class="form-group col-md-6"></div>
                                    <div class="clearfix"></div>

                                    <div class="form-group col-md-6">
                                        <label for="pass">Password</label>
                                        <input type="password" class="form-control" id="pass">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="cpass">Confirm Password</label>
                                        <input type="password" class="form-control" id="cpass">
                                    </div>


                                    <input type="hidden" class="form-control role_normal_hide user_role" id="role">
                                    <input type="hidden" class="form-control role_normal_hide user_status" id="status">
                                    <div class="col-md-6 hide_user role_admin_hide">
                                        <label>Status</label>
                                        <select class="form-control user_status" id="status">
                                            <option value="1">Verified</option>
                                            <option value="0">Unverifed</option>
                                            <option value="2">Suspended</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="standard_name">User Image <span class="text-danger">*</span><span id="old_image_link"></span><input type="hidden" id="old_image_path"></span> </label>
                                        <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                            <input type="hidden" name="document_path" value="/uploads/users/">
                                        </form>

                                        <div class="form-group">
                                            <input type="hidden" name="user_image_path" id="user_image_path" value="">
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-6 hide_user role_admin_hide">
                                        <!-- <label>User Type</label>
                                        <select class="form-control user_role" id="role">
                                            <option value="owner" class="owner_class">Owner</option>
                                            <option value="manager">Manager</option>
                                            <option value="contractor">Contractor</option>
                                            <option value="engineer">Engineer</option>
                                            <option value="surveyor">Surveyor</option>
                                        </select> -->
                                        <input type="hidden" class="form-control user_type_value" id="role">
                                    </div>
                                   <!--  <div class="col-md-6 hide_user">
                                        <label>Project</label>
                                        <div class="loading_data" style="text-align: center;">
                                           <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                        </div>
                                        <select class="form-control" id="project_name">
                                        </select>
                                    </div> -->


                                    <div class="form-group col-md-12">

                                     <!-- <a href="{{ url('/dashboard/users') }}" class="btn btn-info sub-btn">Back</a> -->

<!--                                     <a data-href="{{ url('/dashboard/users') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                     <a href="{{ url('/dashboard') }}" class="btn btn-info sub-btn btn_back1">Back</a>
                                        <button type="submit" id="update_profile_form" class="btn btn-info sub-btn">Save</button>
                                    </div>

                                    </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <!--body wrapper end-->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="http://code.jquery.com/jquery-latest.min.js?v=1.0"></script>
<!-- <script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script> -->
<script src="{{ url('/resources/assets/dist/api_url.js?v=1.0') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone_image.js') }}"></script>
<script src="{{ url('/resources/assets/dist/user_update.js?v=1.0') }}"></script>
@include('include/footer')
