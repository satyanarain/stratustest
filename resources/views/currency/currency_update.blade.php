
        @include('include/header')
        @include('include/sidebar')

        <!-- body content start-->
        <div class="body-content" >
          @include('include/top_bar')
            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">Update Currency</h3>
            </div>
            <!-- page head end-->
            <!--body wrapper start-->
            <div class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div id="alert_message" style="display: none"></div>
                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <div class="panel-body">

                                <form role="form" id="update_currency_form">
                                    <div class="row">
                                        <div class="form-group col-md-4 col-sm-4">
                                            <label for="currency_name">Currency <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="currency_name">
                                        </div>
                                        <div class="form-group col-md-4 col-sm-4">
                                            <label for="currency_symbol">Currency Symbol <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="currency_symbol">
                                        </div>
                                        <div class="form-group col-md-4 col-sm-4">
                                            <label>Status</label>
                                            <select class="form-control" id="status">
                                                <option value="active">Active</option>
                                                <option value="deactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <!-- <a href="{{ url('/dashboard/currency') }}" class="btn btn-info sub-btn no-mar">Back</a> -->

<!--                                             <a data-href="{{ url('/dashboard/currency') }}" class="btn btn-info back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                             <a href="{{ url('/dashboard/currency') }}" class="btn btn-info btn_back1">Back</a>

                                            <button type="submit" class="btn btn-info sub-btn no-mar">Submit</button>
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
<script src="{{ url('/resources/assets/dist/currency_update.js') }}"></script>
@include('include/footer')
