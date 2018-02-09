
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
            <?php $req_for_id = Request::segment(4); ?>
          @include('include/top_bar')

            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                        <h2 style="float: left">Request for Information # <span class="rfi_number"></span></h2>
                        <div class="state-information">
                            <a href="{{ url('/dashboard/'.$project_id.'/req_for_info_log') }}" class="btn btn-success sub-btn pull-right">Back</a>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <section class="panel">
                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th width="25%">RFI #</th>
                                        <td width="25%" class="rfi_number"></td>
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
                                        <td colspan="2" id="rfi_additional_cost"></td>
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
                                        <th colspan="2">Submittal User Detail</th>
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
                        <h2>Submittal Review</h2>
                    </div>

        
                    <div class="col-sm-12">
                        <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th width="25%">Review Status:</th>
                                        <td width="25%" id="review_status"></td>
                                        <th width="25%">Review Responded Date: </th>
                                        <td width="25%" id="review_responded_date"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Review Additional Info?</th>
                                        <td colspan="2" id="review_additional_info"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Review additional costs?</th>
                                        <td colspan="2" id="review_additional_cost"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Review additional days?</th>
                                        <td colspan="2" id="review_additional_days"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Submittal User Detail</th>
                                        <td colspan="2" id="review_user_detail"></td>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
            <!--body wrapper end-->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/req_for_info_single.js') }}"></script>
@include('include/footer')