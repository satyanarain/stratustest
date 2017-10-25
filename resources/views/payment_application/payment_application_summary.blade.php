        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Payment Application Monthly Reports</h3>
                <div class="state-information">
                    <div class="progress progress-striped active progress-sm m-b-20"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"><span class="sr-only">100% Complete</span></div></div>
                </div>
            </div>
            <!-- page head end-->


            <!--body wrapper start-->
            <div class="wrapper labor-comp-summry">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="panel-body clearfix">
                                <div class="col-sm-12 no-pad">
                                  <section class="panel">
                                  <h4 class="no-mar">Payment Application</h4>
                                      <div class="tab-parent" style="overflow-x:scroll;">
                                      <table class="table table-striped m-t-20 custom-grid table-bordered">
                                          <thead>
                                          <tr>
                                              <th>Bid Item</th>
                                              <th>Description</th>
                                              <th>Unit</th>
                                              <th>QTY</th>
                                              <th>Unit Price</th>
                                              <th>Extended Price</th>
                                              <th>Previous QTY</th>
                                              <th>Prv Extended</th>
                                              <th>QTY this Inv</th>
                                              <th>Extnd this inv</th>
                                          </tr>
                                          </thead>
                                          <tbody>
                                            <tr>
                                              <td>1</td>
                                              <td>Mobilization</td>
                                              <td>LS</td>
                                              <td>1</td>
                                              <td>50,000.00</td>
                                              <td>50,000.00</td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                            </tr>

                                            <tr>
                                              <td>1</td>
                                              <td>Mobilization</td>
                                              <td>LS</td>
                                              <td>1</td>
                                              <td>50,000.00</td>
                                              <td>50,000.00</td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                            </tr>

                                            <tr>
                                              <td>1</td>
                                              <td>Mobilization</td>
                                              <td>LS</td>
                                              <td>1</td>
                                              <td>50,000.00</td>
                                              <td>50,000.00</td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                            </tr>

                                            <tr>
                                              <td>1</td>
                                              <td>Mobilization</td>
                                              <td>LS</td>
                                              <td>1</td>
                                              <td>50,000.00</td>
                                              <td>50,000.00</td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                            </tr>


                                            <tr>
                                              <td>&nbsp;</td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                            </tr>

                                            <tr>
                                              <td></td>
                                              <td><strong>Change Orders / Extras</strong></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                            </tr>

                                            <tr>
                                              <td>1</td>
                                              <td>Mobilization</td>
                                              <td>LS</td>
                                              <td>1</td>
                                              <td>50,000.00</td>
                                              <td>50,000.00</td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                            </tr>


                                          </tbody>
                                      </table>
                                      </div>
                                  </section>
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
<script src="{{ url('/resources/assets/dist/test_result_add_faizan.js') }}"></script>
@include('include/footer')
