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

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Update Change Order Request (COR) Review</h3>
                <?php $project_id = Request::segment(2); ?>
                <?php $item_id = Request::segment(4); ?>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                             <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt=""/>
                            </div>
                            <div class="panel-body">
                                <div id="alert_message"></div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="form-group">
                                                <header class="panel-heading nopadtop nopadleft">Contractor:
                                                    <span id="contractor_name">

                                                    </span>
                                                </header>
                                            </div>

                                            <div class="col-sm-12 nopadleft">
                                                <section class="panel">
                                                    <!-- <div class="loading_data" style="text-align: center;">
                                                       <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                                                    </div> -->
                                                    <div class="tab-parent" style="overflow-x:scroll;">
                                                        <table class="table table-striped custom-grid">
                                                            <tbody>
                                                                <tr>
                                                                    <th>COR #:</th>
                                                                    <td id="cor_number"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Generated By</th>
                                                                    <td id="cor_generated_by"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Date Sent</th>
                                                                    <td id="cor_date_sent"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Description</th>
                                                                    <td id="cor_description"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Approved by CM</th>
                                                                    <td id="cor_approved_cm"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Approved by Owner</th>
                                                                    <td id="cor_approved_owner"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Total Requested Costs</th>
                                                                    <td id="total_requested_cost"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>$</th>
                                                                    <td id="cor_amount"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Unit Number</th>
                                                                    <td id="cor_unit_number"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Unit Price</th>
                                                                    <td id="cor_unit_price"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Days</th>
                                                                    <td id="cor_day"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Item File</th>
                                                                    <td id="cor_file"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Status</th>
                                                                    <td id="cor_status"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>


                                                    <div class="tab-parent rfi_available" style="overflow-x:scroll;">
                                                        <table class="table table-striped custom-grid">
                                                            <tbody>
                                                                <tr>
                                                                    <th>RFI Detail:</th>
                                                                    <td id="cor_rfi_detail"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </section>
                                            </div>

                                                        <div class="col-sm-12">
                                                            <h2>COR Review</h2>
                                                        </div>

                                                        <div class="col-md-12 hide_loading">
        <div class="form-group">
            <div class="col-lg-12">
                <!-- <label class="checkbox-custom check-success"> -->
                    <input type="checkbox" value=" " id="approved_cm">
                    <label>Approved by CM</label>
                <!-- </label> -->
            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-12">
                <!-- <label class="checkbox-custom check-success"> -->
                    <input type="checkbox" value=" " id="approved_owner">
                    <label>Approved by Owner</label>
                <!-- </label> -->
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="form-group col-md-12">
            <h3>Document</h3>
            <span id="review_document"></span>
        </div>
                    <input type="hidden" value="<?php echo $project_id; ?>" id="project_id">
        <input type="hidden" value="<?php echo $item_id; ?>" id="item_id">
                                                        </div>





                                            <div class="form-group col-md-12">
<!--                                               <a data-href="{{ url('/dashboard/'.$project_id.'/change_order_request') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                               <a href="{{ url('/dashboard/'.$project_id.'/change_order_request') }}" class="btn btn-info sub-btn btn_back1">Back</a>
                                                
                                            </div>
                                        </div><!-- Row Close -->
                                    </div><!-- Col 6 Close -->
<div class="clearfix"></div>

                            </div>
                        </section>
                    </div>


                </div>
            </div>
            <!--body wrapper end-->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js?v=1.0') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js?v=1.0') }}"></script>
<script src="{{ url('/resources/assets/dist/change_order_request_review_view.js?v=1.0') }}"></script>

@include('include/footer')