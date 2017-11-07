        @include('include/header')
        @include('include/sidebar')
        <!-- body content start-->
        <script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
        <script type="text/javascript">
            $(window).load(function() {
                var role            = localStorage.getItem('u_role');
                if(role == 'admin' || role == 'owner'){
                    // alert('admin');
                    // alert(role);
                }
                else {
                    // alert(role);
                    window.location.href = baseUrl + "403";
                }
            });
        </script>
        <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">Add Project Improvement Type</h3>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="panel-body">
                                <div id="alert_message"></div>
                                <form role="form" id="add_improvement_form">
                                    <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="imp_type">Improvement Type <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="imp_type">
                                    </div>
                                    <div class="form-group col-md-12 m-t-20">
                                        <!-- <a href="{{ url('/dashboard/improvement') }}" class="btn btn-info">Back</a> -->

<!--                                        <a data-href="{{ url('/dashboard/improvement') }}" class="btn btn-info back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                        <a href="{{ url('/dashboard/improvement') }}" class="btn btn-info btn_back" onclick="return checkFormFilled('btn_back')">Back</a>

                                        <button type="submit" class="btn btn-info no-mar">Submit</button>
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
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/improvement_add.js') }}"></script>
@include('include/footer')
