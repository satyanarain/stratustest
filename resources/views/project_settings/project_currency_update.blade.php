
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Project Currency</h3>
            </div>
            <?php $project_id = Request::segment(2); ?>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <div class="panel-body">
                            <div id="alert_message"></div>
                                <form role="form" id="update_project_currency_form">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Project Currency</label>
                                            <select class="form-control" id="project_currency">
                                            </select>
                                        </div>

                                        <div class="form-group col-md-12">
                                             <a data-href="{{ url('/dashboard/'.$project_id.'/project') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>
                                            <button type="submit" class="btn btn-info sub-btn">Save</button>
                                            <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <!--body wrapper end-->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/project_currency.js') }}"></script>
@include('include/footer')
