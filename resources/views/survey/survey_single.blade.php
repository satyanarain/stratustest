
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
                        <h2 style="float: left;">Survey # <span class="survey_number"></span></h2>
                        <div class="state-information pull-right">
                            <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/survey" class="btn btn-success sub-btn"> Back</a>
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
                                        <th width="25%">Survey #</th>
                                        <td width="25%" class="survey_number"></td>
                                        <th width="25%">Date of Request: </th>
                                        <td width="25%" id="survey_date"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Description of Request</th>
                                        <td colspan="2" id="survey_description"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Requested Completion By</th>
                                        <td colspan="2" id="survey_req_date"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Additional Document</th>
                                        <td colspan="2" id="survey_documents"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Survey User Detail</th>
                                        <td colspan="2" id="survey_user_detail"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Survey Status</th>
                                        <td colspan="2" id="status"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </section>
                    </div>

                    <div class="col-sm-12">
                        <hr/>
                    </div>

                    <div class="col-sm-12">
                        <h2>Survey Review</h2>
                    </div>

        
                    <div class="col-sm-12">
                        <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th colspan="2" width="50%">Who is responsible for Survey</th>
                                        <td colspan="2" width="50%" id="review_responsible"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Survey Request # </th>
                                        <td colspan="2" id="review_request_number"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Date of Cut Sheet: </th>
                                        <td colspan="2" id="review_response_date"></td>
                                    </tr>
<!--                                    <tr>
                                        <th colspan="2">Attach Cut Sheet</th>
                                        <td colspan="2" id="review_cut_sheet"></td>
                                    </tr>-->
                                    <tr>
                                        <th colspan="2">Review Status</th>
                                        <td colspan="2" id="review_status"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Review User Detail</th>
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
<script src="{{ url('/resources/assets/dist/survey_single.js') }}"></script>
@include('include/footer')