        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Change Order Request Log</h3>
                <div class="state-information hide_add_permission" style="display: none;">
                    <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/change_order_request/add" class="btn btn-success"><i class="fa fa-plus"></i> Add Change Order Request</a>
                </div>
            </div>
            <!-- page head end-->


            <!--body wrapper start-->
            <div class="wrapper labor-comp-summry">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="panel-body clearfix">
                              <div class="loading_data" style="text-align: center;">
                                 <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                              </div>
                                <div class="col-sm-12 no-pad">
                                  <section class="panel">
                                      <header class="panel-heading head-border m-b-10 nopadtop nopadleft">
                                          <h4 class="no-mar">Requested Change Orders</h4>
                                      </header>
                                      <!-- <div class="tab-parent" style="overflow-x:scroll;"> -->
                                      <table class="table convert-data-table custom-grid data-table m-t-10 dataTable no-footer table-bordered table_scroll_x_axis" id="request_change_order">
                                          <thead>
                                          <tr>
                                              <th>Requested <br/>COR #</th>
                                              <th>Generated By</th>
                                              <th>Date Sent</th>
                                              <th>Description</th>
                                              <th>Approved by CM</th>
                                              <th>Approved by OWNER</th>
                                              <th>$</th>
                                              <th>Days</th>
                                              <th>Status</th>
                                          </tr>
                                          </thead>
                                          <tbody>

                                          </tbody>
                                      </table>
                                      <!-- </div> -->
                                  </section>
                              </div>

                              <div class="col-sm-12 no-pad m-t-40">
                                  <section class="panel">
                                      <header class="panel-heading head-border m-b-10 nopadtop nopadleft">
                                          <h4 class="no-mar">Potential Change Orders</h4>
                                      </header>
                                      <!-- <div class="tab-parent" style="overflow-x:scroll;"> -->
                                      <table class="table convert-data-table custom-grid data-table m-t-10 dataTable no-footer table_scroll_x_axis" id="potential_change_order">
                                          <thead>
                                          <tr>
                                              <th>Potential <br/>COR #</th>
                                              <th>Generated By</th>
                                              <th>Date Sent</th>
                                              <th>Description</th>
                                              <th>Approved by CM</th>
                                              <th>Approved by OWNER</th>
                                              <th style="min-width: 200px;">Referenced RFI</th>
                                              <th>$</th>
                                              <th>Days</th>
                                              <th>Status</th>
                                          </tr>
                                          </thead>
                                          <tbody>
                                          </tbody>
                                      </table>
                                      <!-- </div> -->
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
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js?v=1.0') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js?v=1.0') }}"></script>
<!-- <script src="{{ url('/resources/assets/dist/user_add.js') }}"></script> -->
<script src="{{ url('/resources/assets/dist/change_order_request_log.js?v=1.0') }}"></script>
@include('include/footer')
