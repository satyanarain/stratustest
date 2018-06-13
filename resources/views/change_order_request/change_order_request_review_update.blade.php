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
                                                                    <th>Denied by CM</th>
                                                                    <td id="cor_denied_cm"></td>
                                                                </tr>
                                                                <tr class="cm_review_section">
                                                                    <th>CM Rejection Comment</th>
                                                                    <td id="cm_rejection_comment"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Denied by Owner</th>
                                                                    <td id="cor_denied_owner"></td>
                                                                </tr>
                                                                <tr class="owner_review_section">
                                                                    <th>Owner Rejection Comment</th>
                                                                    <td id="owner_rejection_comment"></td>
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
                                                                <tr class="rfi_available">
                                                                    <th>RFI Detail:</th>
                                                                    <td id="cor_rfi_detail">
                                                                        
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>


<!--                                                    <div class="tab-parent rfi_available" style="overflow-x:scroll;">
                                                        <table class="table table-striped custom-grid">
                                                            <tbody>
                                                                <tr>
                                                                    <th>RFI Detail:</th>
                                                                    <td id="cor_rfi_detail"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>-->
                                                </section>
                                            </div>

           

                                                        <div class="col-md-12 hide_loading">
        <div class="form-group cm_review_section" style="display: none;">
            <div class="col-lg-12">
                <h2>COR Review by CM</h2>
                <!-- <label class="checkbox-custom check-success"> -->
                <input type="radio" name="cm_approval" value="yes" id="approved_cm">
                <label for="approved_cm">Approved by CM</label>
                <input type="radio" name="cm_approval" value="no" id="denied_cm">
                <label for="denied_cm">Denied by CM</label>
                <div class="additonal_cost_div" style="display: none;">
                    <label>Reason</label>
                    <div class="input-group m-b-10">
                        <span class="input-group-addon project_currency"></span>
                        <input class="form-control" type="text" id="cm_rejection_comment1">
                    </div>
                </div>
                <!-- </label> -->
            </div>
        </div>
        <input type="hidden" id="is_potential" value="0" name="is_potential">                                                    
        <div class="form-group owner_review_section" style="display: none;">
            <div class="col-lg-12">
                <h2>COR Review by Owner</h2>
                <!-- <label class="checkbox-custom check-success"> -->
                <input type="radio" name="owner_approval" value="yes" id="approved_owner">
                <label for="approved_owner">Approved by Owner</label>
                    <input type="radio" name="owner_approval" value="no" id="denied_owner">
                    <label for="denied_owner">Denied by Owner</label>
                    <div class="additonal_cost_div1" style="display: none;">
                    <label>Reason</label>
                    <div class="input-group m-b-10">
                        <span class="input-group-addon project_currency"></span>
                        <input class="form-control" type="text" id="owner_rejection_comment1">
                    </div>
                </div>
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
                                                <button type="submit" class="btn btn-info sub-btn" id="submit_cor_review_form">Save</button>
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
<script src="{{ url('/resources/assets/dist/change_order_request_review_update.js?v=1.0') }}"></script>

@include('include/footer')
<script type="text/javascript">
$("input[name='cm_approval']").click(function(){
    if($('input:radio[name=cm_approval]:checked').val() == "no"){
        $('.additonal_cost_div').show();
    }else {
        $('.additonal_cost_div').hide();
        $("#cm_rejection_comment").val('');
    }
});
$("input[name='owner_approval']").click(function(){
    if($('input:radio[name=owner_approval]:checked').val() == "no"){
        $('.additonal_cost_div1').show();
    }else {
        $('.additonal_cost_div1').hide();
        $("#owner_rejection_comment").val('');
    }
});
</script>