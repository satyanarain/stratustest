        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
        <?php $project_id = Request::segment(2); ?>
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Lien Release Log </h3>
                 <div class="state-information">
                    <a href="{{ url('/dashboard/'.$project_id.'/lien_release_log') }}" class="btn btn-info">Back</a>
                </div>
            </div>
            <!-- page head end-->
            

            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="panel-body clearfix">
                                <div class="col-md-12 nopadleft">
                                    <div class="form-group clearfix">
                                        <label class="control-label" id="contractor_name_title"></label>
                                    </div>
                                </div>

                                <div class="col-md-12">  
                                    <h4>RELEASES</h4>
                                    <table class="table convert-data-table data-table mt-6 lien_release_list" id="">
                                      <thead>
                                        <tr>
                                            <th>LIEN NOTE</th>
                                            <th>BILLED THROUGH</th>
                                            <th>TYPE</th>
                                            <th>FILE</th>
                                        </tr>
                                      </thead>
                                      <tbody>
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
<script src="{{ url('/resources/assets/dist/lien_single.js') }}"></script>
@include('include/footer')