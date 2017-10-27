        @include('include/header')
        @include('include/sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">Add Company</h3>
            </div>
            <!-- page head end-->
            <style>
              #map {
                height: 300px;
                 /* position: relative !important;
                  overflow: inherit !important;*/
                  margin-top: 20px !important;
                  overflow:visible !important;
              }

                .gm-style {
                overflow: inherit !important;
                 }

              #type-selector {
                color: #fff;
                background-color: #4d90fe;
                padding: 5px 11px 0px 11px;
              }

              #type-selector label {
                font-family: Roboto;
                font-size: 13px;
                font-weight: 300;
              }

              /*#firm_address {
                display: inline-block !important;
                z-index: 9999 !important;
                position: relative !important;
                left: 0px !important;
                top: -50px !important;
              }*/

               #firm_address {
                z-index: 9999 !important;
                position: relative !important;
                left: 0px !important;
                top: -117px !important;
                box-shadow: rgba(255, 255, 255, 1) 0px 0px 15px 30px;
                border-radius: 0;
              }

            </style>

            <!--body wrapper start-->
            <div class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div id="alert_message"></div>
                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <div class="panel-body">
                                <form role="form">
                                    <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="firm_name">Company Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="firm_name">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="firm_description">Company Description <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="firm_description">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="firm_type">Company Type <span class="text-danger">*</span></label>
                                        <div class="loading_data" style="text-align: center;display: none">
                                           <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                        </div>
                                        <select class="form-control" id="firm_type">
                                        </select>
                                    </div>
                                        <input type="hidden" id="project_latitude">
                                        <input type="hidden" id="project_longitude">
                                    <div class="clearfix"></div>
                                    <div class="form-group col-md-6">
                                        <input type="text" class="form-control" id="firm_address" style="display: block;">
                                        <label for="firm_address">Company Address <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group col-md-12">
                                        <div class="map-wrapper"><div id="map"></div></div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group col-md-12">
                                        <!-- <a href="{{ url('/dashboard/firms') }}" class="btn btn-info sub-btn">Back</a> -->

                                        <a data-href="{{ url('/dashboard/firms') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>
                                        
                                        <button class="add_firm_form btn btn-info sub-btn first_button">Submit</button>
                                        <button class="add_firm_form btn btn-info sub-btn another_button" style="display: none;">Add Another</button>
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
<!-- <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script> -->
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDIoFv2pgyZCk4whduCYs5Ol3ziTuo9-sI&libraries=places&callback=initMap" async defer></script>
<script src="{{ url('/resources/assets/dist/firmname_add.js') }}"></script>
<script>
$(document).ready(function() {
    // $('.wysihtml5').wysihtml5();

    // $('.summernote').summernote({
    //     height: 200,                 // set editor height
    //     minHeight: null,             // set minimum height of editor
    //     maxHeight: null,             // set maximum height of editor
    //     focus: true                 // set focus to editable area after initializing summernote
    // });

    $('#firm_address').css("display", "inline-block");
    $('#firm_address').css("z-index", "9999");
    $('#firm_address').css("position", "relative");
    $('#firm_address').css("left", "0px");
    $('#firm_address').css("top", "-50px");
});

  function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 36.443796, lng: -119.369653},
          zoom: 7,
          scrollwheel: false
        });
        var input = /** @type {!HTMLInputElement} */(
            document.getElementById('firm_address'));

        var types = document.getElementById('type-selector');
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow();
        var marker = new google.maps.Marker({
          map: map,
          anchorPoint: new google.maps.Point(0, -29)
        });

        autocomplete.addListener('place_changed', function() {
          infowindow.close();
          marker.setVisible(false);
          var place = autocomplete.getPlace();
          if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
          }

          // If the place has a geometry, then present it on a map.
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);  // Why 17? Because it looks good.
          }
          marker.setIcon(/** @type {google.maps.Icon} */({
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(35, 35)
          }));
          marker.setPosition(place.geometry.location);
          marker.setVisible(true);

          var address = '';
          if (place.address_components) {
            address = [
              (place.address_components[0] && place.address_components[0].short_name || ''),
              (place.address_components[1] && place.address_components[1].short_name || ''),
              (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
          }
            $("#project_latitude").val(place.geometry.location.lat());
            $("#project_longitude").val(place.geometry.location.lng());
          infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
          infowindow.open(map, marker);
        });
      }
</script>
@include('include/footer')
