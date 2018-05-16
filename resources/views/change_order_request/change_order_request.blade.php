        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Change Order Request (COR)</h3>
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
                                                   <img src="{{ url('/resources/assets/img/loading.svg') }}" alt=""/>
                                                </div>
                                <div class="col-sm-12 no-pad">
                                    <section class="panel" style="overflow: auto">
                                      <header class="panel-heading head-border m-b-10 nopadtop nopadleft">
<!--                                          <h4 class="no-mar">Change Order Request</h4>-->
                                      </header>
                                      <!-- <div class="tab-parent" style="overflow-x:scroll;"> -->
                                      <table class="table convert-data-table custom-grid data-table m-t-10 dataTable no-footer" id="request_change_order" style="width: 100%;">
                                          <thead>
                                          <tr>
<!--                                              <th>Sl.No.</th>-->
                                              <th>Requested <br/>COR #</th>
                                              <th>Generated By</th>
                                              <th style="width: 10%">Date Sent</th>
                                              <th>Description</th>
                                              <th>Referenced RFI</th>
                                              <th>Approved by CM</th>
                                              <th>Approved by OWNER</th>
                                              <th>Denied by CM</th>
<!--                                              <th>CM Rejection Comment</th>-->
                                              <th>Denied by OWNER</th>
<!--                                              <th>Owner Rejection Comment</th>-->
                                              <th class="project_currency" style="width: 10%"></th>
                                              <th>Days</th>
                                              <th>Status</th>
                                              <th>Action</th>
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
                                      <table class="table convert-data-table custom-grid data-table m-t-10 dataTable no-footer" id="potential_change_order" style="width: 100%;">
                                          <thead>
                                          <tr>
<!--                                              <th>Sl.No.</th>-->
                                              <th>Potential <br/>COR #</th>
                                              <th>Generated By</th>
                                              <th style="width: 10%">Date Sent</th>
                                              <th>Description</th>
<!--                                              <th>Referenced RFI</th>-->
                                              <th class="project_currency" style="width: 10%"></th>
                                              <th>Days</th>
                                              <th>Status</th>
                                              <th>Action</th>
                                          </tr>
                                          </thead>
                                          <tbody>
                                         <!--  <tr>
                                              <td>1</td>
                                              <td>ABC Construction, Inc.</td>
                                              <td>16-jan-2017</td>
                                              <td>XXXXXXXXX</td>
                                              <td>RFI #X</td>
                                              <td>$X</td>
                                              <td>X</td>
                                              <td><span class="label label-success">Activate</span></td>
                                              <td>
                                                <a href="{{ url('/') }}/dashboard/{{$project_id}}/change_order_request/1/update" class="btn btn-primary btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>
                                              </td>
                                          </tr> -->
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
<script src="{{ url('/resources/assets/dist/change_order_request_view_all.js?v=1.0') }}"></script>
<!-- <script src="{{ url('/resources/assets/dist/test_result_add_faizan.js') }}"></script> -->
@include('include/footer')
