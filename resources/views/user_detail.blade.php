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
               </div>
           </div>
           <!--body wrapper end-->

           <!-- Modal -->
<div id="profile_update" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Profile Update </h3>
    </div>
    <div class="modal-body">
        <p>modal body</p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-primary">Save changes</button>
    </div>
</div>

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/user_detail.js') }}"></script>
@include('include/footer')