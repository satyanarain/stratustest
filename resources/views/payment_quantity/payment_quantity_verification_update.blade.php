        @include('include/header')
        @include('include/project_sidebar')
        <!-- body content start-->
           

        <!-- body content start-->
        <div class="body-content" >
          <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Update Payment Quantity Verification Report Month : <span id="month_name"></span></h3>
                <div class="state-information">
                    <a href="{{ url('/dashboard/'.$project_id.'/payment_quantity_verification_monthly') }}" class="btn btn-info">Back</a>
                </div>
            </div>
            <!-- page head end-->
            <!--body wrapper start-->
            <div class="wrapper labor-comp-summry" style="display: none;">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="loading_data" style="text-align: center;">
                                <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <div class="panel-body clearfix">
                                <div class="col-sm-12 no-pad">
                                  <section class="panel">
                                      <div id="alert_message"></div>
                                  <h4>Contractor: <span id="contractor_name"></span></h4>
                                  <h4 class="no-mar">Adjust Quantities As Necessary (Adjust all quantities that apply)</h4>

                                      <div class="tab-parent" style="overflow-x:scroll;">
                                      <table class="table table-striped m-t-20 custom-grid">


                                      <table class="table table-striped convert-data-table data-table  m-t-20 table-bordered"  id="view_users_table">

                                          <thead>
                                          <tr>
                                              <th>Item</th>
                                              <th>Description</th>
                                              <th>Unit</th>
                                              <th>Contract QTY</th>
                                              <th>QTY to Date</th>
                                              <th>Previous QTY</th>
                                              <th>QTY this Period</th>
                                              <th>Action</th>
                                          </tr>
                                          </thead>
                                          <tbody>
                                          </tbody>
                                      </table>
                                      </div>
                                  </section>
                              </div>
                                <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>

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
<script src="{{ url('/resources/assets/dist/payment_quantity_verification_update.js') }}"></script>
@include('include/footer')
