        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
        <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Labor Compliance Documents</h3>
                 <div class="state-information">
                    <a href="{{ url('/dashboard/'.$project_id.'/labor_compliance') }}" class="btn btn-info">Back</a>
                </div>
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
                            <div class="panel-body clearfix">
                                <div class="col-md-12 nopadleft">
                                    <div class="form-group clearfix">
                                        <label class="nopadleft col-xs-6 col-sm-12 control-label">Contractor: <span id="contractor_name" style="font-weight: bold;"></span></label>
                                    </div>
                                  
                                <!--     <div class="form-group clearfix">
                                        <label class="nopadleft col-sm-6 control-label"><strong>Document Type :</strong></label>
                                        <div class="col-lg-6 nopadleft">
                                            <label class="control-label">Fringe Benefit Statement</label>
                                        </div>
                                    </div>  -->

                                   <!--  <div class="form-group clearfix">
                                        <label class="nopadleft col-sm-6 control-label"><strong>Week Ending :</strong></label>
                                        <div class="col-lg-6 nopadleft">
                                            <label class="control-label">16-jan-2017</label>
                                        </div>
                                    </div> -->

                                    

                                </div>

                               
                                <div class="col-md-12 no-pad">
                                    <table class="table convert-data-table data-table mt-6" id="">
                                      <thead>
                                        <tr>
                                            <th>Document Name</th>
                                            <th>Document Date</th>
                                            <th>Document</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                            <td>140 – PW Contractor Award Info</td>
                                            <td id="date_140"></td>
                                            <td id="doc_140"></td>
                                        </tr>
                                        <tr>
                                            <td>142 – Request for Dispatch of Apprentice</td>
                                            <td id="date_142"></td>
                                            <td id="doc_142"></td>
                                        </tr>
                                        <tr>
                                            <td>Fringe Benefit Statement</td>
                                            <td id="date_fringe"></td>
                                            <td id="doc_fringe"></td>
                                        </tr>
                                        <tr>
                                            <td>CAC-2 Training Fund Contribution</td>
                                            <td id="date_cac2"></td>
                                            <td id="doc_cac2"></td>
                                        </tr>
                                        <tr>
                                            <td>CPR - Weekly Certified Payroll Reports/Statement of Compliance</td>
                                            <td id="date_cpr"></td>
                                            <td><span id="doc_cpr"></span><span id="doc_compliance"></span></td>
                                        </tr>
                                        <tr>
                                            <td>Statement of Non-Performance</td>
                                            <td id="date_compliance"></td>
                                            <td id="doc_non_performance"></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                </div>
                            </div><!-- panel-body Close -->
                        </section>
                    </div>
                </div>
            </div>

        </div>
        <!--body wrapper end-->

            
<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/labor_compliance_single.js') }}"></script>
@include('include/footer')