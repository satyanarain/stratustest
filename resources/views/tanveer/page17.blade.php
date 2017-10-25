        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
    
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">Preconstruction Meeting</h3>
                <?php $project_id = Request::segment(2); ?>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="panel-body">
                                <div class="upload_doc_panel_performance">    
                                    <header class="panel-heading">Contractor: ABC Construction, Inc.</header>
                                    <div id="weather"></div>
                                    <div class="col-md-6 nopadleft">
                                        <div class="form-group col-md-12">
                                            <label for="name_of_report" style="padding-top: 10px;">Date of Preconstruction Meeting</label>
                                            <input type="text" class="form-control" id="performance_bond_amount">
                                        </div>
                                        
                                        <div class="form-group col-md-12">
                                            <label>Project Description</label>
                                            <input type="text" class="form-control" id="performance_bond_amount">
                                        </div>
                                        
                                        <div class="form-group col-md-12">
                                            <label>Special Considerations</label>
                                            <input type="text" class="form-control" id="performance_bond_number">
                                        </div>

                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Add Another</button>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <section class="panel upload_doc_panel_payment" id="upload_payment">
                                            <div class="panel-body form-group">
                                            <label>Upload your PDF file</label>
                                                <form id="my-awesome-dropzone1" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                    <input type="hidden" class="certificate_work_compensation" name="document_path" value="/uploads/bond/">
                                                </form> 
                                                <input type="hidden" name="standard_doc_id" id="upload_doc_id_work" value="">
                                            </div>
                                        </section>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <button class="btn btn-info sub-btn">Create Agenda</button>
                                        <button class="btn btn-info sub-btn">Create Sign-In Sheet</button>
                                    </div>



                                    
                                </div><!-- upload_doc_panel_performance close -->
                            </div>
                        </section>
                    </div><!-- Col 6 Close -->
                </div>
            </div>

                   
        </div>
            <!--body wrapper end-->
<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>

 <script type="text/javascript">
                                        var zipCode = 90210;

$.ajax({
    dataType: "json",
    headers:  { "Accept": "application/json; odata=verbose" },
    url: "https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20geo.places%20where%20text%3D"+zipCode+"%20limit%201&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys",
    beforeSend: function(xhr){xhr.setRequestHeader('Accept', 'application/json; odata=verbose');},
    success: function(data){
        $.getJSON("https://query.yahooapis.com/v1/public/yql?callback=?", {
            q: "select * from xml where url=\"https://weather.yahooapis.com/forecastrss?w="+data.query.results.place.locality1.woeid+"\"",
            format: "json"
        },function (data) {
            console.log(data);
          var weather = data.query.results.rss.channel;
          var html = '<div><span class="temperature">'+weather.item.condition.temp+'<span class="degree">&deg;</span><sup>'+weather.units.temperature+'</sup></span><br><span class="wind-chill">Feels like: '+weather.wind.chill+'<span class="degree">&deg;</span></span></div></a>';
          $("#weather").html(html);
        });
    },
});
                                        
                                    </script>

<script src="{{ url('/resources/assets/dist/certificate_add.js') }}"></script>
@include('include/footer')