        @include('include/header')
        @include('include/project_sidebar')
        <!-- body content start-->
           

        <!-- body content start-->
        <div class="body-content" >
          <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Update Payment Application Invoice For Month : <span id="month_name"></span></h3>
                
            </div>
            <!-- page head end-->
            <!--body wrapper start-->
            <div class="wrapper labor-comp-summry" style="display: none;">
                <div class="row">
                     <section class="panel">
                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <div class="panel-body">
                            <div class="col-lg-12">
                            <div id="alert_message"></div>
                                    <div class="row">
                                   
                                        <input type="hidden" name="signed_noc_id" id="signed_noc_id" value="">
                                        <input type="hidden" name="standard_upload" id="upload_doc_meta" value="notice_completion">
                                        <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                                  
                                    <div class="form-group col-sm-6">
                                       
                                        <div class="col-md-12">
                                            <label class="upload_exist">Invoice Number</label>
                                            <input type="text" class="form-control" id="ppa_invoice_no">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="upload_exist">Invoice Date</label>
                                            <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">
                                                <input type="text" readonly="" value="" size="16" class="form-control"  id="ppa_invoice_date">
                                                 <span class="input-group-btn add-on">
                                                   <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                 </span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="upload_exist">Paid</label>
                                            <select class="form-control" id="paid">
                                                <option value="">Select Payment Status</option>
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select>
                                        </div>
                                         <div class="clearfix"></div>
                                     </div>
                                    <div class="col-sm-6">
                                       <label class="upload_exist" >Upload Invoice Document</label>
                                        <form id="my-awesome-dropzone1" action="{{ url('/document/uploadFiles') }}" class="dropzone" class="upload_exist"  style="display: block;">
                                            <input type="hidden" name="document_path" value="/uploads/payapps/">
                                        </form>
                                        <input type="hidden" name="upload_type" id="upload_type" value="single_upload">
                                        <input type="hidden" name="upload_single_doc_id" id="upload_single_doc_id" value="">
                                        
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group col-md-12">
                                        <input type="hidden" name="standard_upload" id="project_id" value="<?php echo $project_id; ?>">
                                    </div>

                                    <div class="form-group col-md-12 m-t-20">
<!--                                        <a data-href="{{ url('/dashboard/'.$project_id.'/notice_completion') }}" class="btn btn-info  back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                        <a href="{{ url('/dashboard/'.$project_id.'/payment_application_monthly') }}" class="btn btn-info btn_back1">Back</a>
                                        <button type="submit" class="btn btn-info sub-btn no-mar"  id="update_payment_application">Save</button>
                                        <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
                                    </div>

                                    </div>
                                
                                 </div>

                            </div>
                        </section>
                </div>
            </div>

        </div>
        <!--body wrapper end-->
        
<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone.js') }}"></script>
<script src="{{ url('/resources/assets/dist/payment_application_update.js') }}"></script>
@include('include/footer')
