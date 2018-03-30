
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
                                    <div class="form-group col-md-6">
                                        <label for="pass">Password</label>
                                        <input type="password" class="form-control" id="pass">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="cpass">Confirm Password</label>
                                        <input type="password" class="form-control" id="cpass">
                                    </div>
                                    <div class="form-group col-md-12">

                                     <!-- <a href="{{ url('/dashboard/users') }}" class="btn btn-info sub-btn">Back</a> -->

<!--                                     <a data-href="{{ url('/dashboard/users') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                    <a href="{{ url('/dashboard') }}" class="btn btn-info sub-btn btn_back1">Back</a>
                                    <button type="submit" id="update_profile_form" class="btn btn-info sub-btn">Update</button>
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
<script src="{{ url('/resources/assets/dist/change_password.js?v=1.0') }}"></script>
@include('include/footer')
