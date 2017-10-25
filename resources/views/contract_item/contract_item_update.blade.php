
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Update Contract item</h3>
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
                                <form role="form" id="update_contract_item_form">
                                    <div class="row">
                                    <div class="form-group col-md-6">
            <label for="item_description">Item Description <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="item_description">
        </div>
        <div class="form-group col-md-6">
            <label>Unit of Measure</label>
             <select class="form-control" id="item_unit">
                <option value="EA">Each</option>
                <option value="LS">Lump Sum</option>
                <option value="SF">Square Footage</option>
                <option value="LF">Linear Feet</option>
                <option value="CY">Cubic Yard</option>
                <option value="SY">Square Yard</option>
                <option value="TN">Tons</option>
                <option value="MO">Months</option>
                <option value="YR">Year</option>
                <option value="DA">Days</option>
                <option value="LB">Pounds</option>
                <option value="HR">Hourly</option>
                <option value="AC">Acres</option>
                <option value="YD">Yard</option>
                <option value="GA">Gallon</option>
                <option value="Other">Other</option>
             </select>
            <div class="other_option" style="display: none;">
              <br/><input type="text" class="form-control" id="item_unit_other">
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="item_qty">Quantity <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="item_qty" onkeypress="return isNumber(event)">
        </div>
        <div class="form-group col-md-6">
            <label for="item_unit_price">Unit Price <span class="text-danger">*</span></label>
            <!-- <input type="text" class="form-control" id="item_unit_price"> -->
            <div class="input-group m-b-10">
                <span class="input-group-addon project_currency"></span>
                <input class="form-control" type="text" id="item_unit_price" onkeypress="return isNumber(event)">
            </div>
            <input type="hidden" class="form-control" id="project_id" value="<?php echo $project_id; ?>">
        </div>

                                    <div class="col-md-6">
                                        <label>Status</label>
                                        <select class="form-control" id="status">
                                            <option value="active">Activate</option>
                                            <option value="deactive">Deactivate</option>
                                        </select>
                                    </div>


                                    <div class="form-group col-md-12">
                                        <a data-href="{{ url('/dashboard/'.$project_id.'/contract_item') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>
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
<script src="{{ url('/resources/assets/dist/contract_item_update.js') }}"></script>
@include('include/footer')
