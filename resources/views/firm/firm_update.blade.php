
        @include('include/header')
        @include('include/sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">Update Company</h3>
            </div>
            <!-- page head end-->

            <style>
               #map {
                     height: 300px;
                     /* position: relative !important;
                      overflow: inherit !important;*/
                      margin-top: 40px !important;
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

              /*#project_location {
                display: inline-block !important;
                z-index: 9999 !important;
                position: relative !important;
                left: 0px !important;
                top: -50px !important;
              }*/

              #project_location {
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
                            <div id="alert_message" style="display: none"></div>
                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <div class="panel-body">

                                <form role="form" id="update_firm_form">
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
                                            <label for="firm_type">Company Type</label>
                                            <div class="loading_data" style="text-align: center;display: none;">
                                               <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                            </div>
                                            <select class="form-control" id="firm_type">
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Status</label>
                                            <select class="form-control" id="status">
                                                <option value="active">Activate</option>
                                                <option value="deactive">Deactivate</option>
                                            </select>
                                        </div>
                                        <input type="hidden" class="form-control" id="project_latitude" >
                                        <input type="hidden" class="form-control" id="project_longitude">
                                    <div class="form-group col-md-6">
                                        <label for="company_type">Firm/Agency <span class="text-danger">*</span></label>
                                        <select class="form-control" id="company_type">
                                            <option value="">Firm/Agency</option>
                                            <option value="f">Firm/Company</option>
                                            <option value="a">Lead Agency</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="firm_address">Company Address <span class="text-danger">*</span></label>
                                        <div class="clearfix"></div>
                                        <input type="text" class="form-control" id="project_location" style="display: block;">
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group col-md-12">
                                        <div class="map-wrapper"><div id="map"></div></div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="form-group col-md-12">
                                        <!-- <a href="{{ url('/dashboard/firms') }}" class="btn btn-info sub-btn">Back</a> -->
                                        
<!--                                        <a data-href="{{ url('/dashboard/firms') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                        <a href="{{ url('/dashboard/firms') }}" class="btn btn-info sub-btn btn_back1">Back</a>

                                        <button type="submit" id="update_firm_form_btn" class="btn btn-info sub-btn">Submit</button>
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
<!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDIoFv2pgyZCk4whduCYs5Ol3ziTuo9-sI&libraries=places&callback=initMap" async defer></script>-->
<script src="{{ url('/resources/assets/dist/firmname_update.js') }}"></script>

<script>

$(document).ready(function() {
    // $('.wysihtml5').wysihtml5();

    // $('.summernote').summernote({
    //     height: 200,                 // set editor height
    //     minHeight: null,             // set minimum height of editor
    //     maxHeight: null,             // set maximum height of editor
    //     focus: true                 // set focus to editable area after initializing summernote
    // });

    // $('#project_location').css("display", "inline-block");
    // $('#project_location').css("z-index", "9999");
    // $('#project_location').css("position", "relative");
    // $('#project_location').css("left", "0px");
    // $('#project_location').css("top", "-50px");
});



    var script = document.createElement('script');
    script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyDIoFv2pgyZCk4whduCYs5Ol3ziTuo9-sI&libraries=places&callback=initMap";
    setTimeout(function(){
        document.body.appendChild(script);
    },4000);

    $(document).ready(function() {
        // $('.wysihtml5').wysihtml5();

        // $('.summernote').summernote({
        //     height: 200,                 // set editor height
        //     minHeight: null,             // set minimum height of editor
        //     maxHeight: null,             // set maximum height of editor
        //     focus: true                 // set focus to editable area after initializing summernote
        // });

        // $('#project_location').css("display", "inline-block");
        // $('#project_location').css("z-index", "9999");
        // $('#project_location').css("position", "relative");
        // $('#project_location').css("left", "0px");
        // $('#project_location').css("top", "-50px");
    });




    function initMap()
    {

        var lat =  parseFloat($('#project_latitude').val());
        var long =  parseFloat($('#project_longitude').val());
        var the_position = { // ADDED THIS
            lat: 0,
            lng: 0
        };
        var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: lat, lng: long},
            mapTypeId: google.maps.MapTypeId.TERRAIN,
            zoom: 7,
            scrollwheel: false
        });
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, long),
            map: map,
            label: document.getElementById('project_location').value
        });
        marker.setVisible(true);

        var input = /** @type {!HTMLInputElement} */(
            document.getElementById('project_location'));

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
            $("#project_longitude").val(place.geometry.location.lng())


            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
            infowindow.open(map, marker);
        });

//        marker = new google.maps.Marker({
//            position: new google.maps.LatLng(-33.8688, 151.2195),
//            map: map
//        });
//
//
//        marker.setVisible(true);
//        marker.setMap(map);
//        infowindow.open(map, marker);



//        var address = $("#project_location").val();
//
//        var geocoder = new google.maps.Geocoder();
//        geocoder.geocode({
//                'address': address,
//            },
//            function(results, status) {
//                if(status == google.maps.GeocoderStatus.OK) {
//                    new google.maps.Marker({
//                        position: results[0].geometry.location,
//                        map: map,
//                        infowindow: true
//                    });
//                    map.setCenter(results[0].geometry.location);
//                }
//            })
    }

    setTimeout(function(){
        document.getElementById('project_location').style.display='block';
    },8000);

 </script>
@include('include/footer')
