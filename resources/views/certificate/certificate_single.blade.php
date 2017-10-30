        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
    
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">View Certificate of Insurance</h3>
                <?php $project_id = Request::segment(2); ?>
                <div class="state-information">
                    <a href="{{ url('/dashboard/'.$project_id.'/certificate') }}" class="btn btn-info">Back</a>
                </div>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                         <div id="alert_message"></div>
                            <div class="panel-body">

                                <div class="col-md-12">
                                    <h1 style="color:green; text-align:center;">CERTIFICATES OF INSURANCE</h1>
                                    <div style="clear: both;"></div>
                                    <p style="width:70%;"><strong>Contractor: </strong><span id="contractor_name"></span><br/>
                                        <span id="pdf_gen_contractor_address"></span></p>
                                    <div style="clear: both;"></div>

                                    <div class="col-md-3 nopadleft text-center">
                                        <h3>General Liability</h3>
                                        <p><strong>General Liability Exp. Date: </strong><span id="general_liability_date"></span></p>
                                        <p><strong>General Liability Limit: </strong><span id="general_liability_amount"></span></p>
                                        <p><strong>General Liability Doc Link: </strong><span id="general_liability_doc_link"></span></p>       
                                    </div>
                                    
                                    
                                    <div class="col-md-3 nopadleft text-center">
                                        <h3>Auto Liability</h3>
                                        <p><strong>Auto Liability Exp. Date: </strong><span id="auto_liability_date"></span></p>
                                        <p><strong>Auto Liability Limit: </strong><span id="auto_liability_amount"></span></p>
                                        <p><strong>Auto Liability Doc Link: </strong><span id="auto_liability_doc_link"></span></p>
                                    </div>
                                    
                                    <div class="col-md-3 nopadleft text-center">
                                        <h3>Workers Comp</h3>
                                        <p><strong>Workers Comp Exp. Date: </strong><span id="work_comp_date"></span></p>
                                        <p><strong>Workers Comp Limit: </strong><span id="work_comp_amount"></span></p>
                                        <p><strong>Workers Comp Doc Link: </strong><span id="work_comp_doc_link"></span></p>
                                    </div>
                                    
                                    <div class="col-md-3 nopadleft text-center">
                                        <h3>Umbrella Liability</h3>
                                        <p><strong>Umbrella Liability Exp. Date: </strong><span id="umbrella_liability_date"></span></p>
                                        <p><strong>Umbrella Liability Limit: </strong><span id="umbrella_liability_amount"></span></p>
                                        <p><strong>Umbrella Liability Doc Link: </strong><span id="umbrella_liability_doc_link"></span></p>
                                    </div>
                                    
                                    <div id="custom_certificate_pdf" style="display: block;"></div>
                                    
                                </div>
                                
                                <div class="clearfix"></div>

                            </div>
                        </section>
                    </div>

                   
                </div>
            </div>
            <!--body wrapper end-->
<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/certificate_single.js') }}"></script>
@include('include/footer')