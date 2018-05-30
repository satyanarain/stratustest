
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Update Submittal</h3>
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

                                   <form role="form" id="update_submittal_form">
                                    <div class="row">
                                    <!-- <div class="form-group col-md-12">
                                        <label>Date</label>
                                        <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date="2016-09-01"  class="input-append date dpYears">
                                                <input type="text" readonly="" value="" size="16" class="form-control"  id="date_of_report">
                                                  <span class="input-group-btn add-on">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                  </span>
                                            </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="name_of_report">Document Name</label>
                                        <input type="text" class="form-control" id="name_of_report">
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group col-md-12">
                                        <label for="company_name">Company Name</label>
                                        <div class="loading_data" style="text-align: center;">
                                           <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                        </div>
                                        <select class="form-control" id="company_name">
                                        </select>
                                    </div>

                                    <div class=" col-md-12">
                                        <label for="standard_link" style="min-width: 200px;">Applicable</label>
                                        <label class="radio-inline">
                                          <input type="radio" name="applicable" id="applicable_yes" value="yes"> Yes
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="applicable" id="applicable_no" value="no"> No
                                        </label>
                                    </div>

                                    <div class=" col-md-12 hidden">
                                        <label for="standard_link" style="min-width: 200px;">Upload Document</label>
                                        <label class="radio-inline">
                                          <input type="radio" name="upload" id="upload_yes" value="yes"> Yes
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="upload" id="upload_no" value="no"> No
                                        </label>
                                    </div>

                                    <div class="col-md-12">
                                        <label>Type</label>
                                        <select class="form-control" id="type">
                                            <option value="swppp">SWPPP</option>
                                            <option value="wpcp">WPCP</option>
                                        </select>
                                    </div> -->

                                    <div class="col-md-6">
                                        <label>Status</label>
                                        <select class="form-control" id="status">
                                            <option value="active">Active</option>
                                            <option value="deactive">Inactive</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="submittal_number" id="submittal_number">

                                    <div class="form-group col-md-12">
                                        <!-- <input type="hidden" name="standard_upload" id="upload_doc_meta" value="swppp"> -->
                                        <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                                        <!-- <input type="hidden" name="standard_doc_id" id="upload_doc_id" value=""> -->
                                    </div>

                                    <div class="form-group col-md-12">
<!--                                        <a data-href="{{ url('/dashboard/'.$project_id.'/submittals') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                        <a href="{{ url('/dashboard/'.$project_id.'/submittals') }}" class="btn btn-info sub-btn btn_back1">Back</a>
                                        <button type="submit" class="btn btn-info sub-btn">Save</button>
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
<script src="{{ url('/resources/assets/dist/submittals_update.js') }}"></script>
@include('include/footer')
