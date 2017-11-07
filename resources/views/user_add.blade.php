        @include('include/header')
        @include('include/sidebar')
        <!-- body content start-->
        <div class="body-content" >
          @include('include/top_bar')
            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">Add User</h3>
            </div>
            <!-- page head end-->
            <!--body wrapper start-->
            <div class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div id="alert_message"></div>
                            <div id="updateuserinfo"></div>
                            <header class="panel-heading">
                                <i class="icon-user"></i> User Information
                            </header>
                            <div class="panel-body">

                                <form role="form" id="add_user_form">
                                    <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="uname">User Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="uname">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="cname">Company Name <span class="text-danger">*</span></label>
                                        <div class="loading_data" style="text-align: center;">
                                           <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                        </div>
                                        <select class="form-control" id="firm_name">
                                            <option>Select Company name</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="position">Position / Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="position">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="fname">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="fname">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="lname">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="lname">
                                    </div>
                                    <div class="input_fields_wrap">
                                    <div class="col-md-6 append">
                                        <div class="form-group">
                                            <label for="pnum">Phone Number <span class="text-danger">*</span></label>
                                            <a href="#" class="add_field_button btn btn-success m-b-10 pull-right">Add Phone</a>
                                            <select class="form-control" id="phone_type_1" name="phone_type[]">
                                                <option value="mobile">Mobile</option>
                                                <option value="home">Home</option>
                                                <option value="work">Work</option>
                                                <option value="work_fax">Work Fax</option>
                                                <option value="home_fax">Home Fax</option>
                                                <option value="skype">Skype</option>
                                                <option value="pager">Pager</option>
                                                <option value="work_email">Email</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="pnum_1" name="phone_num[]">
                                        </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                            <input type="hidden" class="form-control user_type_value" id="role">
                                        <!-- <label>User Type <span class="text-danger">*</span></label>
                                        <select class="form-control" id="role">
                                            <option value="owner" class="owner_class">Owner</option>
                                            <option value="manager">Manager</option>
                                            <option value="contractor">Contractor</option>
                                            <option value="engineer">Engineer</option>
                                            <option value="surveyor">Surveyor</option>
                                        </select> -->
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="form-group col-md-12">
                                        <!-- <a href="{{ url('/dashboard/users') }}" class="btn btn-info sub-btn">Back</a> -->

<!--                                        <a data-href="{{ url('/dashboard/users') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                        <a href="{{ url('/dashboard/users') }}" class="btn btn-info sub-btn btn_back" onclick="return checkFormFilled('btn_back')">Back</a>


                                        <button type="submit" class="first_button btn btn-info sub-btn">Save</button>
                                        <button type="submit" id="add_swppp_form" class="another_button btn btn-info sub-btn" style="display: none;">Save Another</button>
                                        <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
                                    </div>

                                    </div>
                                </form>

                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <!--body wrapper end-->
<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/user_add.js') }}"></script>

@include('include/footer')
