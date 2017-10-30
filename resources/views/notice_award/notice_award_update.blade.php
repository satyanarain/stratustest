
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Update Notice of Award</h3>
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
                                
                                   <form role="form">
                                    <div class="row">
                                    
<div class="col-md-6" style="display: none;">
    <section class="panel upload_doc_panel faizan1">
        <div class="panel-body" style="padding: 0px;">
            <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                <input type="text" name="document_path" value="/uploads/notice_award/">
            </form>
            <input type="text" name="upload_type" id="upload_type" value="multiple_upload">
            <input type="text" name="upload_doc_id[]" class="upload_doc_id" id="upload_doc_id_1" value="">
        </div>
    </section>
</div>
<div class="clearfix"></div>
<div class="form-group col-md-6">
    <label>Download Notice of Award</label>
</div>
<div class="form-group col-md-6" id="doc_file_path">
    
</div>
<div class="form-group col-md-12">
    <hr/>
</div>
<div class="form-group col-md-6">
    <label for="name_of_report">Review &amp; E-sign by Owner/Owner Rep</label>
    <br/>
    <div class="before_review_owner" style="display: none;">
        <label for="name_of_report" style="padding-top: 15px;">Upload document after signing</label>
        <section class="panel upload_doc_panel faizan3">
            <div class="panel-body" style="padding: 0px;">
                <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                    <input type="hidden" name="document_path" value="/uploads/notice_award/">
                </form>
                <input type="hidden" name="upload_type" id="upload_type" value="multiple_upload">
                <input type="hidden" name="upload_doc_id[]" class="upload_doc_id" id="upload_doc_id_2" value="">
            </div>
        </section>
        <textarea class="form-control" id="review_owner" placeholder="Enter Review" rows="5"></textarea>
    </div>

    <div class="after_review_owner" style="display: none;">
        <p><strong>Signed Document: </strong><div id="document_owner"></div></p> 
        <br/>
        <p><strong>Owner Review: </strong><span id="review_owner_detail"></span></p>
    </div>
</div>

<div class="form-group col-md-6">
    <label for="company_name">Review &amp; E-sign /Accept by Contractor</label>
    <br/>
    <div class="before_review_contractor" style="display: none;">
        <label for="name_of_report" style="padding-top: 15px;">Upload document after signing</label>
        <section class="panel upload_doc_panel faizan2">
            <div class="panel-body" style="padding: 0px;">
                <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                    <input type="hidden" name="document_path" value="/uploads/notice_award/">
                </form>
                <input type="hidden" name="upload_type" id="upload_type" value="multiple_upload">
                <input type="hidden" name="upload_doc_id[]" class="upload_doc_id" id="upload_doc_id_3" value="">
            </div>
        </section>
        <textarea class="form-control" id="review_contractor" placeholder="Enter Review" rows="5"></textarea>
    </div>
    <div class="after_review_contractor" style="display: none;">
        <p><strong>Signed Document: </strong><div id="document_contractor"></div></p> 
        <br/>
        <p><strong>Contractor Review: </strong><span id="review_contractor_detail"></span></p>
    </div>
</div>
<div class="clearfix"></div>

                                    <div class="col-md-6">
                                        <label>Status</label>
                                        <select class="form-control" id="status">
                                            <option value="active">Activate</option>
                                            <option value="deactive">Deactivate</option>
                                        </select>
                                    </div>
                                    <div class="clearfix"></div>


                                    <div class="form-group col-md-12">
                                        <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                                        <input type="hidden" name="standard_upload" id="upload_doc_meta" value="notice_award">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <a data-href="{{ url('/dashboard/'.$project_id.'/notice_award') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>
                                        <button type="submit" class="btn btn-info sub-btn first_button" id="update_notice_form">Save</button>
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
<script src="{{ url('/resources/assets/dist/notice_award_update.js') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone.js') }}"></script>

<script type="text/javascript">
    $('body').delegate( '.upload_doc_panel', 'click', function () {
        var id = $(this).find(".upload_doc_id:first").attr("id");
        console.log(id);
        window.localStorage.setItem("upload_doc_id", id);
        console.log(localStorage.getItem("upload_doc_id"));
        return;
    });
</script>

@include('include/footer')