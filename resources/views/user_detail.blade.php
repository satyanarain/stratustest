@include('include/header')

       @include('include/sidebar')

       <!-- body content start-->
       <div class="body-content" >

         @include('include/top_bar')

           <!-- page head start-->
           <div class="page-head">
               <h3 class="m-b-less"><i class="icon-user"></i> User Profile</h3>
           </div>
           <!-- page head end-->

           <!--body wrapper start-->
           <div class="wrapper">
               <div class="row">
                   <div class="col-lg-12">
                       <section class="panel user-profile">
                           <div class="panel-body">
                               <div class="loading_data" style="text-align: center;">
                                   <!-- <div class="pulse pulse3 bg-primary light-color"> Loading </div> -->
                                   <!-- <img src="{{ url('/resources/assets/img/loading1.gif') }}" alt="" /> -->
                               </div>
                               <div class="clearfix"></div>
                                   <div class="row">
                                       <div class="form-group col-md-12">
                                           <div class="table-responsive">
                                             <table class="table table-bordered">
                                               <tbody>
                                                   <tr><th>Username</th><td><span id="username"></span></td></tr>
                                                   <tr><th>Email Address</th><td><span id="email"></span></td></tr>
                                                   <tr><th>First Name</th><td><span id="firstname"></span></td></tr>
                                                   <tr><th>Last Name</th><td><span id="lastname"></span></td></tr>
                                                   <tr><th>Company Name</th><td><span id="cname"></span></td></tr>
                                                   <tr><th>Position / Title</th><td><span id="position"></span></td></tr>
                                                   <tr><th>Role</th><td><span id="role"></span></td></tr>
                                                   <tr><th>Account Status</th><td><span id="status"></span></td></tr>
                                                   <tr><th>User Contacts</th><td><span id="phone_setting"></span></td></tr>
                                                   <tr><th>User Image</th><td><span id="user_image_path"></span></td></tr>
                                               </tbody>
                                             </table>
                                           </div>
                                       </div>
                                        <div class="form-group col-md-12">
                                          <a href="{{ url('/dashboard') }}" class="btn btn-info sub-btn">Back</a>
                                             <a href="#" class="btn btn-info sub-btn" onclick="openProfileUpdate()">Update User</a>
                                        </div>
                                   </div>

                           </div>
                       </section>
                   </div>
                     <!-- Modal -->
  <div class="modal fade" id="profile_update" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Updat User Profile </h4>
        </div>
        <div class="modal-body">
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

                                    </div>
                            </div>
                        </section>
                    </div>
                </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
      

               </div>

           </div>
           <!--body wrapper end-->


<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/user_detail.js') }}"></script>


@include('include/footer')

