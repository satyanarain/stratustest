        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')
          <div class="loading_data_file" style="display: none;">
               <div class="block">
                   <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                   <br/><span class="loading-text">Please wait, file is uploading</span>
               </div>
            </div>
    
                <?php $project_id = Request::segment(2); ?>
            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Import Contract Items</h3>
                <div class="state-information hide_add_permission" style="display: ;">
                    <a href="{{ url('/') }}/contract-items.xlsx" class="btn btn-success"><i class="fa fa-plus"></i> Download Sample File</a>
                </div>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
           
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">

                            <div class="panel-body">
                                <div id="alert_message"></div>
                                <div class="row">
                                    <form action="#" id="document_upload" class="form-horizontal" method="post" enctype="multipart/form-data">
                                    <div class="col-sm-6">
                                      <label for="standard_name">Upload Contract Items Document </label>
                                      
                                      <input type="file" id="import_file" name="import_file" style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;">
                                      
                                    </div>
                                  
                                      <div class="col-sm-6">
                                          <div class="form-group col-md-12">
                                              <input type="hidden" name="standard_upload" id="upload_doc_meta" value="standard">
                                              <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                                              <input type="hidden" name="standard_doc_id" id="upload_doc_id">
                                          </div>

                                          <div class="form-group col-md-12">
                                                <a href="{{ url('/dashboard/'.$project_id.'/contract_item') }}" class="btn btn-info btn_back" onclick="return checkFormFilled('btn_back')">Back</a>
                                                <button type="submit" class="btn btn-primary btn-info import_contract_items">Import Files</button>
                                                <a href="{{ url('/dashboard/'.$project_id.'/contract_item') }}" class="btn btn-info continue_button" onclick="return checkFormFilled('continue_button')">Next Screen</a>
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
<script src="{{ url('/resources/assets/dist/excel_import.js') }}"></script>
@include('include/footer')
