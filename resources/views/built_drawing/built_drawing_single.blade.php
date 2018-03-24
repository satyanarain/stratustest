        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
        <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">As Built Drawing</h3>
                 
            </div>
            <!-- page head end-->


            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <div class="panel-body clearfix">
                                <div class="col-md-12 nopadleft">
                                    <div class="form-group clearfix">
                                        <label class="nopadleft col-sm-6 control-label"><strong>Contractor :</strong></label>
                                        <div class="col-lg-6 nopadleft">
                                            <label class="control-label" id="contractor_name"></label>
                                        </div>
                                    </div>

                                    <div class="form-group clearfix">
                                        <label class="nopadleft col-sm-6 control-label"><strong>Description :</strong></label>
                                        <div class="col-lg-6 nopadleft">
                                            <label class="control-label" id="built_description"></label>
                                        </div>
                                    </div>

                                    <div class="form-group clearfix">
                                        <label class="nopadleft col-sm-6 control-label"><strong>Contractor’s Redlines :</strong></label>
                                        <div class="col-lg-6 nopadleft">
                                            <label class="control-label" id="built_contractor"></label>
                                        </div>
                                    </div>

                                    <div class="form-group clearfix">
                                        <label class="nopadleft col-sm-6 control-label"><strong>Engineers As Built:</strong></label>
                                        <div class="col-lg-6 nopadleft">
                                            <label class="control-label" id="built_engineer"></label>
                                        </div>
                                    </div>

                                    <div class="form-group clearfix">
                                        <label class="nopadleft col-sm-6 control-label"><strong>Changes to Plan? :</strong></label>
                                        <div class="col-lg-6 nopadleft">
                                            <label class="control-label" id="built_plan"></label>
                                        </div>
                                    </div>


                                    <div class="form-group clearfix">
                                        <label class="nopadleft col-sm-6 control-label"><strong>Status :</strong></label>
                                        <div class="col-lg-6 nopadleft">
                                            <label class="control-label" id="built_status"></label>
                                        </div>
                                    </div>


                                    <div class="form-group col-md-12">
                                        <h3>Drawing :</h3>
                                        <span id="review_document"></span>
                                    </div>



                                </div>
                                <div class="state-information">
                                    <a href="http://ec2-34-236-61-80.compute-1.amazonaws.com/dashboard/1/built_drawing" class="btn btn-success"> Back</a>
                                </div>


                            </div><!-- panel-body Close -->
                        </section>
                    </div>
                </div>
            </div>

        </div>
        <!--body wrapper end-->


<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js?v=1.0') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js?v=1.0') }}"></script>
<script src="{{ url('/resources/assets/dist/built_drawings_single.js?v=1.0') }}"></script>
@include('include/footer')
