        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Payment Application Complete Report</h3>
                <div class="state-information">
                    <a href="{{ url('/dashboard/'.$project_id.'/payment_application_monthly') }}" class="btn btn-info">Back</a>
                </div>
            </div>
            <!-- page head end-->


            <!--body wrapper start-->
            <div class="wrapper labor-comp-summry" style="display: none;">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="panel-body clearfix">
                                <div class="col-sm-12 no-pad">
                                  <section class="panel">
                                  <h4 class="no-mar">Payment Application</h4>
                                      <div class="table-parent">
                                        <table class="table table-striped convert-data-table data-table  m-t-20 table-bordered"  id="view_users_table">

                                          <thead>
                                          <tr>
                                              <th>Contract Item</th>
                                              <th>Description</th>
                                              <th>Unit</th>
                                              <th>QTY</th>
                                              <th>Unit Price</th>
                                              <th>Extended Price</th>
                                              <th>QTY this Time</th>
                                              <th>Extnd this Time</th>
                                          </tr>
                                          </thead>
                                          <tbody>


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
<script src="{{ url('/resources/assets/dist/payment_application_complete.js') }}"></script>
@include('include/footer')
