        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">Notice of Completion</h3>
                <div class="state-information" style="width: 200px; ">
                    <div class="progress progress-striped active progress-sm m-b-20"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"><span class="sr-only">100% Complete</span></div></div>
                </div>
            </div>
            <!-- page head end-->
            

            <!--body wrapper start-->
            <div class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="panel-body clearfix">
                                <div class="col-sm-6 nopadleft">
                                  <label>Which would you like to do:?</label>
                                    <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                        <input type="hidden" name="document_path" value="/uploads/test_report/">
                                    </form> 
                                    <input type="hidden" name="standard_doc_id" id="upload_doc_id_general" value="">
                                    
                                    <input type="hidden" name="upload_type" id="upload_type" value="">
                                    <input type="hidden" name="standard_upload" id="upload_doc_meta" value="test_result">

                                    <div class="form-group">
                                    <a href="http://sw.ai/staging/stratus/dashboard/1/test_result" class="btn btn-info sub-btn">Print Blank NOC FORM</a>
                                    <button type="submit" class="btn btn-info sub-btn first_button add_test_result_form">Complete NOC FORM</button>
                                </div>
                                    
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-sm-6 nopadleft m-t-20">
                                  <div class="form-group clearfix">
                                      <label class="nopadleft col-lg-12 col-sm-12 control-label mt-6">Would you like to provide a copy of the NOC to all
potential claimants?</label>
                                      <label class="checkbox-custom check-success">
                                          <input type="checkbox" value=" " id="compaction_yes">
                                          <label for="compaction_yes">No</label>
                                      </label>

                                      <label class="checkbox-custom check-success">
                                          <input type="checkbox" value=" " id="compaction_yes">
                                          <label for="compaction_yes">Yes</label>
                                      </label>
                                  </div>
                                
                                
                            </div><!-- panel-body Close -->
                        </section>
                    </div>
                </div>
            </div>

        </div>
        <!--body wrapper end-->

            
<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<!-- <script src="{{ url('/resources/assets/dist/user_add.js') }}"></script> -->

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script src="{{ url('/resources/assets/js/gmaps.js') }}"></script>
<script src="{{ url('/resources/assets/js/gmaps-init.js') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone.js') }}"></script>
<script src="{{ url('/resources/assets/dist/test_result_add_faizan.js') }}"></script>
<script>
    jQuery(document).ready(function() {
        GoogleMaps.init();
    });
</script>
@include('include/footer')