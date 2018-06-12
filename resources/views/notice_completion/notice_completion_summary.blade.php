        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
          <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Notice of Completion Summary</h3>
                <div class="state-information hide_add_permission" style="display: none;">
                    <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/notice_completion/add" class="btn btn-success"><i class="fa fa-plus"></i> Add Notice of Completion</a>
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
                                  
                                      <!-- <div class="tab-parent" style="overflow-x:scroll;"> -->
                                      <table style="width: 100%;" class="table table-striped m-t-20 custom-grid table-bordered" id="view_users_table">
                                          <thead>
                                          <tr>
                                              <th></th>
                                              <th></th>
                                              <th></th>
                                              <th style="text-align: center" colspan="2">NOC-Signed</th>
                                              <th style="text-align: center" colspan="2">NOC-Recorded</th>
                                              <th></th>
                                              <th></th>
                                          </tr>
                                          <tr>
                                              <th>No #</th>
                                              <th style="width: 10%;">Improvement<br> Type</th>
                                              <th style="width: 15%;">Date Project Complete/<br>Substantially Complete</th>
                                              <th>Date</th>
                                              <th>PDF</th>
                                              <th>Date</th>
                                              <th>PDF</th>
                                              <th>Status</th>
                                              <th>Action</th>
                                          </tr>
                                          </thead>
                                          <tbody>
                                            <!-- <td>1</td>
                                            <td>Example text</td>
                                            <td><a href="javascript:;" target="_blank"><img src="{{ url('/') }}/resources/assets/img/pdf_icon.png" alt="Sample Standard" width="40"></a></td>
                                            <td><span class="label label-success">Activate</span></td>
                                            <td><a href="{{ url('/') }}/dashboard/{{$project_id}}/notice_completion/update" class="btn btn-primary btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>
                                            </td> -->
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
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/notice_completion_view_all.js') }}"></script>
@include('include/footer')