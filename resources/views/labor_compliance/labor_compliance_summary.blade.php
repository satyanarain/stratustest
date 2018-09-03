        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
        <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Labor Compliance Summary</h3>
                <div class="state-information hide_add_permission" style="display: none;">
                    <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/labor_compliance/add" class="btn btn-success"><i class="fa fa-plus"></i> Add New Labor Compliance Document</a>
                </div>
            </div>
            <!-- page head end-->
            

            <!--body wrapper start-->
            <div class="wrapper labor-comp-summry">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="panel-body clearfix">    
                                <section class="panel">
                                    <div class="loading_data" style="text-align: center;">
                                       <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                                    </div>
                                <div class="form-group clearfix">
                                    <label class="nopadleft col-xs-6 col-sm-12 control-label">Contractor: <span id="contractor_name" style="font-weight: bold;"></span></label>
                                </div>

                                <div class="form-group clearfix">
                                        <label class="nopadleft col-xs-6 col-sm-12 control-label">Wage Determination: <span id="wage_determination" style="font-weight: bold;"></span></label>
                                    </div>

                                <table class="table convert-data-table data-table custom-grid table-bordered table_scroll_x_axis" id="view_users_table">
                                  <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Company</th>
                                        <th>140 – <br/>PW Contractor Award Info</th>
                                        <th>142 – <br/>Request for Dispatch of Apprentice</th>
                                        <th>Fringe Benefit Statement</th>
                                        <th>CAC-2 <br/>(not required if Union)</th>
                                        <th>Weekly Certified Payroll Reports<br/>/Statement of Compliance</th>
                                        <th>Statement of Non-Performance</th>
                                        <th>Uploaded to <br/>DIR’s website</th>
                                        <th>Status</th>
                                        <th width="20%">Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                 <!--  <tr role="row" class="odd">
                                    <td class="sorting_1">1</td>
                                    <td>ABC Construction, Inc.</td>
                                    <td><a href="{{ url('/') }}/uploads/standard/97186_20161015.pdf" target="_blank"><img src="{{ url('/') }}/resources/assets/img/pdf_icon.png" alt="Sample Standard" width="40"></a></td>
                                    <td><a href="{{ url('/') }}/uploads/standard/97186_20161015.pdf" target="_blank"><img src="{{ url('/') }}/resources/assets/img/pdf_icon.png" alt="Sample Standard" width="40"></a></td>
                                    <td>2015-0901</td>
                                    <td>2015-0930</td>
                                    <td>2015-1011</td>
                                    <td>2015-1011</td>
                                    <td><label class="checkbox-custom check-success">
                                          <input type="checkbox" value=" " id="compaction_yes">
                                          <label for="compaction_yes"></label>
                                      </label></td>
                                    <td><a href="{{ url('/') }}/dashboard/0/labor_compliance/1/update" class="btn btn-primary btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>
                                    <a href="{{ url('/') }}/dashboard/0/labor_compliance/1" class="btn btn-success btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="View"><i class="fa fa-search"></i></a></td>
                                  </tr> -->
                                  
                                  </tbody>
                                </table>
                          
                                </section>
                                
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
<script src="{{ url('/resources/assets/dist/labor_compliance_view_all.js') }}"></script>
@include('include/footer')