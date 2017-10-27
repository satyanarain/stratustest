        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
        <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">As Built Drawings</h3>
                <div class="state-information hide_add_permission" style="display: none;">
                    <a href="{{ url('/') }}/dashboard/<?php echo $project_id; ?>/built_drawing/add" class="btn btn-success"><i class="fa fa-plus"></i> Add As Built Drawings</a>
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
                                    <!-- <div class="tab-parent" style="overflow-x:scroll;"> -->
                                        <table class="table convert-data-table custom-grid data-table m-t-10 table-bordered" id="view_users_table">
                                          <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Description</th>
                                                <th>Contractor’s Redlines</th>
                                                <th>Engineer’s As Built</th>
                                                <th>Changes to Plans?</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                          <!-- <tr role="row" class="odd">
                                            <td class="sorting_1">1</td>
                                            <td>Cover Sheet</td>
                                            <td>PROVIDED</td>
                                            <td>COMPLETE</td>
                                            <td>YES</td>
                                            <td><span class="label label-success">Activated</span></td>
                                            <td><a href="{{ url('/') }}/dashboard/{{$project_id}}/built_drawing/1/update" class="btn btn-primary btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>

                                            <a href="{{ url('/') }}/dashboard/{{$project_id}}/built_drawing/1" class="btn btn-success btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="View"><i class="fa fa-search"></i></a></td>
                                          </tr>
                                          -->
                                          </tbody>
                                        </table>
                                    <!-- </div> -->
                                </section>

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
<script src="{{ url('/resources/assets/dist/built_drawings_view_all.js?v=1.0') }}"></script>
@include('include/footer')
