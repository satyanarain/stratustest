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
                <h3 class="m-b-less">Add Request for Information Review</h3>
                <?php $project_id = Request::segment(2); ?>
                <?php $request_id = Request::segment(4); ?>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="panel-body">
                                <div id="alert_message"></div>
<div class="col-md-12">
    <div class="row">
        <div class="form-group">
            <label for="company_name"></label>
            <header class="panel-heading">Contractor: 
                <span id="contractor_name">
                    <div class="loading_data" style="text-align: center;">
                       <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                    </div>
                </span>
            </header>
        </div>
        
        <div class="col-sm-12">
                        <section class="panel">
                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th width="25%">RFI #:</th>
                                        <td width="25%" id="rfi_number"></td>
                                        <th width="25%">Date of RFI: </th>
                                        <td width="25%" id="rfi_date"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">What is your request?</th>
                                        <td colspan="2" id="rfi_request"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">What is your proposed solution/suggestion?</th>
                                        <td colspan="2" id="rfi_suggestion"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Will this request result in additional costs?</th>
                                        <td colspan="2">
                                            <span id="rfi_additional_cost"></span>
                                            <span id="rfi_additional_currency"></span>
                                            <span id="rfi_additional_amount"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Will this request result in additional days added to the contract?</th>
                                        <td colspan="2" id="rfi_additional_days"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Additional Document</th>
                                        <td colspan="2" id="rfi_documents"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">RFI User Detail</th>
                                        <td colspan="2" id="rfi_user_detail"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </section>
                    </div>

                    <div class="col-sm-12">
                        <hr/>
                    </div>

                    <div class="col-sm-12">
                        <h2>RFI Review</h2>
                    </div>


                    <div class="form-group col-md-12">
                        <input type="hidden" id="respond_date" value="<?php echo date("Y-m-d"); ?>">
                        <input type="hidden" name="standard_upload" id="upload_project_id" value="<?php echo $project_id; ?>">
                        <label>Additional Information</label>
                        <input type="text" class="form-control" id="question_request">
                    </div>
                   
                    <div class="form-group col-md-12">
                        <label>Additional costs?</label><br/>
                        <label class="radio-inline">
                          <input type="radio" name="additonal_cost_type" id="additional_cost" value="yes"> Yes
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="additonal_cost_type" id="additional_cost" value="no"> No
                        </label><br/>
                        <div class="additonal_cost_div" style="display: none;">
                            <label>Additional Amount</label>
                            <!-- <input type="text" class="form-control" id="additional_cost_amount"> -->
                            <div class="input-group m-b-10">
                                <span class="input-group-addon project_currency"></span>
                                <input class="form-control" type="text" id="additional_cost_amount">
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <label>Additional days</label><br/>
                        <label class="radio-inline">
                          <input type="radio" name="additonal_day_type" id="additional_day" value="yes"> Yes
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="additonal_day_type" id="additional_day" value="no"> No
                        </label><br/>
                        <div class="additonal_day_div" style="display: none;">
                            <label>Additional days added</label>
                            <input type="text" class="form-control" id="additional_day_add">
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <label>Status</label><br/>
                        <select class="form-control" id="status">
                            <option value="response_provided">Response Provided</option>
                            <option value="additional_information_requested">Additional Information Requested</option>
                        </select>
                    </div>

                    <div class="clearfix"></div>
                    <div class="form-group col-md-12">
                        <label>Document</label>
                        <span id="review_document"></span>
                    </div>
            
        <div class="form-group col-md-12">
            <a data-href="{{ url('/dashboard/'.$project_id.'/req_for_info_review') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>
            <button type="submit" class="btn btn-info sub-btn" id="submit_req_review_form">Save</button>
            <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
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
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script type="text/javascript">
$("input[name='additonal_cost_type']").click(function(){
    if($('input:radio[name=additonal_cost_type]:checked').val() == "yes"){
        console.log('yes');
        $('.additonal_cost_div').show();
    }
    else {
        console.log('no');
        $('.additonal_cost_div').hide();  
    }
});

$("input[name='additonal_day_type']").click(function(){
    if($('input:radio[name=additonal_day_type]:checked').val() == "yes"){
        console.log('yes');
        $('.additonal_day_div').show();
    }
    else {
        console.log('no');
        $('.additonal_day_div').hide();  
    }
});
</script>
<script src="{{ url('/resources/assets/dist/req_for_info_review_update.js') }}"></script>

@include('include/footer')