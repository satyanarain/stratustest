        @include('include/header')
        @include('include/sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">Add Currency</h3>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div id="alert_message" style="display: none"></div>
                            <div class="panel-body">
                                <form role="form" id="add_currency_form">
                                    <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="currency_name">Currency <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="" id="currency_name">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="currency_symbol">Currency Symbol <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="" id="currency_symbol">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <!-- <a href="{{ url('/dashboard/currency') }}" class="btn btn-info sub-btn">Back</a> -->

                                        <a data-href="{{ url('/dashboard/currency') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>
                                        
                                        <button type="submit" class="btn btn-info sub-btn">Submit</button>
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
<script src="{{ url('/resources/assets/dist/currency_add.js') }}"></script>
@include('include/footer')
