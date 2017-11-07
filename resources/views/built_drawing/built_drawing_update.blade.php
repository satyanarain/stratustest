        @include('include/header')
        @include('include/project_sidebar')
        <!-- body content start-->
        <div class="body-content" >
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Update As Built Drawing</h3>
                <?php $project_id = Request::segment(2); ?>
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
                            <div class="panel-body">
                            <div id="alert_message"></div>

                                   <form role="form" id="">
                                    <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group clearfix">
                                            <label class="nopadleft col-sm-6 control-label"><strong>Contractor :</strong></label>
                                            <div class="col-lg-6 nopadleft">
                                                <label class="control-label" id="contractor_name"></label>
                                            </div>
                                        </div>

                                        <div class="form-group clearfix">
                                            <label class="nopadleft col-sm-6 control-label"><strong>Description :</strong></label>
                                            <div class="col-lg-6 nopadleft">
                                                <input type="text" disabled="" class="control-label form-control" id="built_description">
                                            </div>
                                        </div>

                                        <div class="form-group clearfix" id="contractor_hide">
                                            <label class="nopadleft col-sm-6 control-label"><strong>Contractor’s Redlines :</strong></label>
                                            <div class="col-lg-6 nopadleft">
                                                <select name="" id="built_contractor" class="form-control">
                                                    <option value="complete">Complete</option>
                                                    <option value="additional_info">Addional info requested</option>
                                                    <option value="past_due">Past Due</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group clearfix" id="engineer_hide">
                                            <label class="nopadleft col-sm-6 control-label"><strong>Engineer's As built:</strong></label>
                                            <div class="col-lg-6 nopadleft">
                                                <select id="built_engineer" class="form-control">
                                                    <option value="complete">Complete</option>
                                                    <option value="past_due">Past Due</option>
                                                    <option value="additional_info">Additional Info Requested</option>
                                                    <option value="not_provided">Not Provided</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group clearfix">
                                            <label class="nopadleft col-sm-6 control-label"><strong>Changes to Plans? :</strong></label>
                                            <div class="col-lg-6 nopadleft">
                                                <select name="" id="built_plan" class="form-control">
                                                    <option value="yes">Yes</option>
                                                    <option value="no">No</option>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group clearfix">
                                            <label class="nopadleft col-sm-6 control-label"><strong>Status</strong></label>
                                            <div class="col-lg-6 nopadleft">
                                                <select class="form-control" id="status">
                                                    <option value="active">Activate</option>
                                                    <option value="deactive">Deactivate</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <h3>Drawing :</h3>
                                        <span id="review_document"></span>
                                    </div>

                                    <div class="form-group col-md-12">
<!--                                        <a data-href="{{ url('/dashboard/'.$project_id.'/built_drawing') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                        <a href="{{ url('/dashboard/'.$project_id.'/built_drawing') }}" class="btn btn-info sub-btn btn_back1">Back</a>
                                        <button type="submit" class="btn btn-info sub-btn" id="update_built_form">Save</button>
                                        <a data-href="{{ url('/dashboard/'.$project_id.'/notice_completion') }}" class="btn btn-info sub-btn continue_button" data-toggle="modal" data-target="#confirm-continue">Next Screen</a>
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
<script src="{{ url('/resources/assets/dist/built_drawings_update.js') }}"></script>
@include('include/footer')
