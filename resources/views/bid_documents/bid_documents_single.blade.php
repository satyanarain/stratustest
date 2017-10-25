
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">View Bid Documents</h3>
                <div class="state-information">
                    <a href="{{ url('/dashboard/'.$project_id.'/bid_documents') }}" class="btn btn-success">Back</a>
                </div>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-sm-12">
                        <section class="panel">
                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt=""/>
                            </div>
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th scope="row">Bid Advertisement Date</th>
                                        <td id="bid_addvertisement_date"></td>
                                        <th scope="row">Bid Advertisement Document</th>
                                        <td id="addvertisement_bid_path_value"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Notice Inviting Bids Date</th>
                                        <td id="notice_invite_date"></td>
                                        <th scope="row">Notice Inviting Bids</th>
                                        <td id="notice_invite_bid_path"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Date of Bid Opening</th>
                                        <td id="date_bid_opening"></td>
                                        <th scope="row">Detailed Bid Results by Item</th>
                                        <td id="detail_result_path"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Low Bidder’s Name</th>
                                        <td id="low_bidder_name"></td>
                                        <th scope="row">Successful Bidder’s Proposal</th>
                                        <td id="sucessful_bidder_proposal_path"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </section>
                    </div>

                    <div class="col-sm-12">
                        <hr/>
                    </div>

                    <div class="col-sm-12">
                        <h2>Project Addendum</h2>
                    </div>


                    <div class="col-sm-12">
                        <table class="table table-striped" id="addendum_table">
                            <thead>
                                <th>Addendum Date</th>
                                <th>Addendum Number</th>
                                <th>Addendum File</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--body wrapper end-->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js?v=1.0') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js?v=1.0') }}"></script>
<script src="{{ url('/resources/assets/dist/bid_document_single.js?v=1.0') }}"></script>
@include('include/footer')
