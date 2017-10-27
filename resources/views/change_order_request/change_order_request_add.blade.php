        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

          <div class="loading_data_file" style="display: none;">
               <div class="block">
                   <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                   <br/><span class="loading-text">Please wait, file is uploading</span>
               </div>
            </div>

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Change Order Request</h3>
                <div class="state-information" style="width: 200px; ">
                    <!-- <div class="progress progress-striped active progress-sm m-b-20"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"><span class="sr-only">100% Complete</span></div></div> -->
                </div>
            </div>
            <!-- page head end-->

            <div id="alert_message"></div>
            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="panel-body clearfix">
                                <div class="col-md-12 nopadleft">
                                    <div class="form-group clearfix">
                                        <label class="nopadleft col-xs-12 control-label">Contractor: <span id="contractor_name"></span></label>
                                    </div>

                                    <div class="form-group clearfix">
                                        <label class="nopadleft col-sm-12 control-label">Change Order Request: <strong><span class="cor_new_number">
                                                <div class="loading_data" style="text-align: center;">
                                                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                                </div>
                                            </span></strong></label>
                                        
                                    </div>

                                    <div class="form-group clearfix">
                                        <label class="nopadleft col-sm-12 control-label">Date of Request: <span id="cor_date"><?php echo date("Y-m-d"); ?></span></label>
                                            <input type="hidden" id="cor_date_today" value="<?php echo date("Y-m-d"); ?>">
                                    </div>
                                </div>


<div class="material_delivered_all">
    <div class="col-md-12 nopadleft material_delivered_detail">
        <div class="col-sm-12">
            <a id="add_more" class="btn btn-success" style="float: right;"> Add Another Item </a>
        </div>
        <div class="col-md-6 nopadleft">
            <div class="form-group clearfix">
                <label class="nopadleft col-sm-6 control-label mt-6">Description <span class="text-danger">*</span></label>
                <div class="col-sm-6 nopadleft">
                    <input type="text" class="form-control" name="item_description[]" placeholder="">
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="nopadleft col-sm-12 control-label mt-6">Total Requested Costs <span class="text-danger">*</span></label>
                <div class="col-lg-6 nopadleft">
                    <div class="check-box">
                        <label>
                            <input type="radio" name="select_type" class="item_price_1" id="price" value="price">
                            Enter Price
                        </label>
                    </div>
                </div>
                <div class="col-lg-6 nopadleft">
                    <div class="check-box">
                        <label>
                            <input type="radio" name="select_type" class="item_price_1" id="unit" value="unit">
                            Enter Unit
                        </label>
                    </div>
                </div>
                <div id="item_price_1">
                    <div class="item_price" style="display: none;">
                        <div class="col-lg-12 nopadleft">
                            <input type="text" class="form-control m-b-10" name="item_price[]" placeholder="$X"  onkeypress="return isNumber(event)">
                        </div>
                    </div>
                    <!-- <span class="nopadleft col-lg-1 control-label mt-6 text-center">OR</span> -->
                    <div class="item_unit" style="display: none;">
                        <div class="col-lg-5 nopadleft">
                            <input type="text" class="form-control m-b-10" name="item_unit_quantity[]" placeholder="# of Units"  onkeypress="return isNumber(event)">
                        </div>
                        <span class="nopadleft col-lg-2 control-label mt-6 text-center">at</span>
                        <div class="col-lg-5 nopadleft">
                            <input type="text" class="form-control" name="item_unit_price[]" placeholder="$/Unit"  onkeypress="return isNumber(event)">
                        </div>
                    </div>
                </div><!-- close -->
            </div>

            <div class="form-group clearfix m-b-40">
                <label class="nopadleft col-sm-6 control-label mt-6">Total Requested Time in Days <span class="text-danger">*</span></label>
                <div class="col-sm-6 nopadleft">
                    <input type="text" class="form-control" name="item_request_time[]" placeholder="Days"  onkeypress="return isNumber(event)">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12 rfi_yes">
                    <label class="checkbox-custom check-success">
                        <input type="checkbox" class="rfi_add" value=" " id="rfi_add_1">
                        <label for="rfi_add_1"> Additional costs for RFIs will be stored here</label>
                    </label>

                    <div class="col-sm-12 form-group rfi_add_div" id="rfi_add_div_1" style="display: none;">
                        <!-- <label class="checkbox-custom check-success">
                            <input type="checkbox" value=" " id="performance_bond_yes">
                            <label for="performance_bond_yes">Performance Bond</label>
                        </label> -->
                    </div>
                </div>
            </div>

        </div>

        <div class="col-sm-6 m-b-30">
            <label>Upload Change Order Request <span class="text-danger">*</span></label>
            <section class="panel upload_doc_panel" id="upload_div">
                <div class="panel-body" style="padding: 0px;">
                    <form id="my-awesome-dropzone" action="{{ url('/group_doc/index.php') }}" class="dropzone">
                        <input type="hidden" name="document_path" value="/uploads/cor/">
                    </form>
                    <input type="hidden" name="upload_doc_id[]" class="upload_doc_id" id="upload_doc_id_1" value="">
                    <input type="hidden" name="upload_type" id="upload_type" value="multiple_upload">
                </div>
            </section>
        </div>
    </div> <!-- material_delivered_detail close -->
</div><!-- material_delivered_all clse -->


                                <div class="form-group col-md-12 nopadleft">

                                    <input type="hidden" name="standard_upload" id="upload_doc_meta" value="cor">
                                    <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                                    <input type="hidden" name="standard_upload" id="cor_new_number" value="">
                                    <a data-href="{{ url('/dashboard/'.$project_id.'/change_order_request_log') }}" class="btn btn-info  back_button" data-toggle="modal" data-target="#confirm-back">Back</a>
                                    <button type="submit" class="add_cor btn btn-info sub-btn first_button no-mar">Save</button>
                                    <button type="submit" class="add_cor btn btn-info sub-btn another_button no-mar" style="display: none;">Save Another</button>
                                    <a data-href="{{ url('/dashboard/'.$project_id.'/minutes_meeting') }}" class="btn btn-info  continue_button" data-toggle="modal" data-target="#confirm-continue">Next Screen</a>
                                    <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
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
<script src="{{ url('/resources/assets/dist/change_order_request_add.js?v=1.0') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone_groupdoc.js?v=1.0') }}"></script>

<script type="text/javascript">
    $('body').delegate( "input[type='radio']", "click", function () {
        $("input[type='radio']").click(function(){
            // alert('faizan');
            if($('input:radio[name=select_type]:checked').val() == "price"){
                // alert('Price');
                var id = $(this).attr("class");
                // alert(id);
                $('#'+id+' .item_unit').hide();
                $('#'+id+' .item_price').show();
            }
            else {
                // alert('Unit');
                var id = $(this).attr("class");;
                // alert(id);
                $('#'+id+' .item_price').hide();
                $('#'+id+' .item_unit').show();
            }
        });
    });
</script>
@include('include/footer')
