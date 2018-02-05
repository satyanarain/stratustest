
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">View Submittal</h3>
                <div class="state-information">
                    <a href="{{ url('/dashboard/'.$project_id.'/submittals') }}" class="btn btn-success"> Back</a>
                </div>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-sm-12">
                        <section class="panel">
                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th width="25%">Submittal #:</th>
                                        <td width="25%" id="submittal_number"></td>
                                        <th width="25%">Date of Submittal</th>
                                        <td width="25%" id="submittal_date"></td>
                                    </tr>
                                    <tr>
                                        <th width="25%">Description of Submittal</th>
                                        <td width="25%" id="submittal_description"></td>
                                        <th width="25%">Applicable Spec Section</th>
                                        <td width="25%" id="submittal_specification"></td>
                                    </tr>
                                    <tr>
                                        <th width="25%" colspan="2">Additional Comments</th>
                                        <td width="75%" colspan="2" id="submittal_comments"></td>
                                    </tr>
                                    <tr>
                                        <th width="25%" colspan="2">Reason for Expedited Request</th>
                                        <td width="75%" colspan="2" id="submittal_expedited"></td>
                                    </tr>
                                    <tr>
                                        <th width="25%" colspan="2">Additional Document</th>
                                        <td width="75%" colspan="2" id="submittal_documents"></td>
                                    </tr>
                                    <tr>
                                        <th width="25%" colspan="2">Submittal User Detail</th>
                                        <td width="75%" colspan="2" id="submittal_user_detail"></td>
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
                        <table class="table table-striped" id="review_table">
                            <thead>
                                <th>Review</th>
                                <th>Review Status</th>
                                <th>Review Date</th>
                                <th>User</th>
                            </thead>
                            <tbody>
                                <td id="review"></td>
                                <td id="review_status"></td>
                                <td id="review_date"></td>
                                <td id="review_user"></td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--body wrapper end-->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/submittals_single.js') }}"></script>
@include('include/footer')