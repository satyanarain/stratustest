
        @include('include/header')
        @include('include/sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">Update Project Improvement Type</h3>
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
                            <div id="alert_message"></div>
                                <form role="form" id="update_improvement_form">
                                    <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="imp_type">Improvement Type <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="imp_type">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Status</label>
                                        <select class="form-control" id="status">
                                            <option value="active">Active</option>
                                            <option value="deactive">Inactive</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12 m-t-20">
                                        <!-- <a href="{{ url('/dashboard/improvement') }}" class="btn btn-info">Back</a> -->

<!--                                        <a data-href="{{ url('/dashboard/improvement') }}" class="btn btn-info back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                        <a href="{{ url('/dashboard/improvement') }}" class="btn btn-info btn_back1">Back</a>

                                        
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
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/improvement_update.js') }}"></script>
@include('include/footer')
