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

                                
                                    <div class="row">

                                    <div class="col-md-4">
                                        <label>Status</label>
                                        <select class="form-control" id="status">
                                            <option value="active">Pending</option>
                                            <option value="deactive">Complete</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Date Project Complete/Substantially Complete<span class="text-danger">*</span></label>
                                        <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                                <input type="text" readonly="" value="" size="16" class="form-control"  id="project_completion_date">
                                                  <span class="input-group-btn add-on">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                  </span>
                                            </div>
                                    </div>
<!--                                    <div class="form-group col-md-4">
                                        <label>Date Filed on <span class="text-danger">*</span></label>
                                        <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                                <input type="text" readonly="" value="" size="16" class="form-control"  id="date_noc_filed">
                                                  <span class="input-group-btn add-on">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                  </span>
                                            </div>
                                    </div>    -->
                                    <div class="form-group col-md-4">
                                        <label for="project_type">Improvement Type <span class="text-danger">*</span></label>
                                        <div id="project_type_selected" style=""></div>
                                        <select class="form-control" id="project_type_dropdown" placeholder="Select Improvement Type">
                                            <option>Select Improvement Type</option>
                                        </select>
                                    </div>        
                                    
                                    
                                    
                                   <div class="form-group col-sm-6">
                                       <div class="form-group col-sd-12"><span class="label label-inverse"><b>Signed Notice of Completion</b></span></div>
                                       <div class="form-group col-sd-12">
                                        <label class="upload_exist">Date signed</label>
                                        <input type="text" readonly="" size="16" class="form-control"  id="date_signed">
<!--                                         <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                               <input type="text" readonly="" value="" size="16" class="form-control"  id="date_signed">
                                                 <span class="input-group-btn add-on">
                                                   <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                 </span>
                                           </div>-->
                                       </div>
                                            <div class="clearfix"></div>
                                           <div class="form-group col-sd-12"><span class="label label-inverse"><b>Recorded Notice of Completion</b></span></div>
                                           <div class="col-sd-12">
                                                <label class="upload_exist">Date Recorded</label>
                                                 <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                                       <input type="text" readonly="" value="" size="16" class="form-control"  id="date_recorded">
                                                         <span class="input-group-btn add-on">
                                                           <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                         </span>
                                                   </div>

                                             </div>
                                     </div>
                                    <div class="form-group col-sm-6">
<!--                                       <label class="upload_exist" >Upload Signed Notice of Completion</label>
                                        <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone" class="upload_exist"  style="display: block;">
                                            <input type="hidden" name="document_path" value="/uploads/notice_completion/">
                                        </form>-->
                                   
                                        <input type="hidden" name="signed_noc_id" id="signed_noc_id" value="">
                                        <input type="hidden" name="standard_upload" id="upload_doc_meta" value="notice_completion">
                                        <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                                        
                                        <label class="upload_exist" >Upload Recorded Notice of Completion</label>
                                        <form id="my-awesome-dropzone1" action="{{ url('/document/uploadFiles') }}" class="dropzone" class="upload_exist"  style="display: block;">
                                            <input type="hidden" name="document_path" value="/uploads/notice_completion/">
                                        </form>
                                        <input type="hidden" name="upload_type" id="upload_type" value="single_upload">
                                        <input type="hidden" name="recorded_doc_id" id="recorded_doc_id" value="">
                                    </div>
                                    
                                    
                                    
                                    <div class="col-sm-6">
                                       
                                        
                                    </div>
                                    <div class="clearfix"></div>
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
                                
                                 </div>

                            </div>
                        </section>

                </div>
            </div>
            <!--body wrapper end-->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone.js') }}"></script>
<script type="text/javascript">
$("#my-awesome-dropzone").click(function() {
  $("#upload_type").val("signed_noc_id");
});
$("#my-awesome-dropzone1").click(function() {
  $("#upload_type").val("recorded_doc_id");
});
</script>
<script src="{{ url('/resources/assets/dist/notice_completion_update.js') }}"></script>
@include('include/footer')
