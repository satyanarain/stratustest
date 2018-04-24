        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
        <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Unconditional Final</h3>
                 
            </div>
            <!-- page head end-->
            

            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="panel-body clearfix">
                                <div class="col-md-6">  
                                    <table class="table convert-data-table data-table mt-6" id="">
                                      <tbody>
                                        <tr>
                                            <td>Date of Signature</td>
                                            <td class="date_signature">
                                                <div class="loading_data" style="text-align: center;">
                                                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Name of Claimant</td>
                                            <td class="name_claimant">
                                                <div class="loading_data" style="text-align: center;">
                                                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Name of Customer</td>
                                            <td class="name_customer">
                                                <div class="loading_data" style="text-align: center;">
                                                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Job Location</td>
                                            <td class="job_location">
                                                <div class="loading_data" style="text-align: center;">
                                                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Owner</td>
                                            <td class="owner_name">
                                                <div class="loading_data" style="text-align: center;">
                                                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Disputed Claim Amount</td>
                                            <td class="disputed_claim_amount">
                                                <div class="loading_data" style="text-align: center;">
                                                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Document</td>
                                            <td class="document">
                                                <div class="loading_data" style="text-align: center;">
                                                   <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                                </div>
                                            </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                </div>

                                
                            </div><!-- panel-body Close -->
                        </section>
                        <div class="state-information">
                            <a href="{{ url('/dashboard/'.$project_id.'/unconditional_finals') }}" class="btn btn-info">Back</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!--body wrapper end-->

            
<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/unconditional_finals_single.js') }}"></script>
@include('include/footer')