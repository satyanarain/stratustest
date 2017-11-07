        @include('include/header')
        @include('include/project_sidebar')
        <!-- body content start-->
        <div class="body-content" >
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Update Notice of Completion</h3>
                <?php $project_id = Request::segment(2); ?>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">

                        <section class="panel">
                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <div class="panel-body">
                            <div class="col-lg-12">
                            <div id="alert_message"></div>

                                   <form role="form">
                                    <div class="row">

                                    <div class="col-md-4">
                                        <label>Status</label>
                                        <select class="form-control" id="status">
                                            <option value="active">Pending</option>
                                            <option value="deactive">Complete</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Date Filed on <span class="text-danger">*</span></label>
                                        <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                                <input type="text" readonly="" value="" size="16" class="form-control"  id="date_noc_filed">
                                                  <span class="input-group-btn add-on">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                  </span>
                                            </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="project_type">Improvement Type <span class="text-danger">*</span></label>
                                        <div id="project_type_selected" style=""></div>
                                        <select class="form-control" id="project_type_dropdown" placeholder="Select Improvement Type">
                                            <option>Select Improvement Type</option>
                                        </select>
                                    </div>        

                                    <div class="form-group col-md-12">
                                        <input type="hidden" name="standard_upload" id="project_id" value="<?php echo $project_id; ?>">
                                    </div>

                                    <div class="form-group col-md-12 m-t-20">
<!--                                        <a data-href="{{ url('/dashboard/'.$project_id.'/notice_completion') }}" class="btn btn-info  back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                        <a href="{{ url('/dashboard/'.$project_id.'/notice_completion') }}" class="btn btn-info btn_back1">Back</a>
                                        <button type="submit" class="btn btn-info sub-btn no-mar"  id="update_notice_completion_form">Save</button>
                                        <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
                                    </div>

                                    </div>
                                </form>
                                 </div>

                            </div>
                        </section>

                </div>
            </div>
            <!--body wrapper end-->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/notice_completion_update.js') }}"></script>
@include('include/footer')
