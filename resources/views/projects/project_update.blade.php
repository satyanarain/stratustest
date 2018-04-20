
@include('include/header')
@include('include/sidebar')

<!-- body content start-->
<div class="body-content" >

@include('include/top_bar')

<!-- page head start-->
<div class="page-head">
    <h3 class="m-b-less">Update Project</h3>
    <div class="state-information">
        <!-- <div class="progress progress-striped active progress-sm m-b-20"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="50" style="width: 50%;"><span class="sr-only">50% Complete</span></div></div> -->
    </div>
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

    /*  .controls {
        margin-top: 10px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }*/

    /*#pac-input {
      background-color: #fff;
      font-family: Roboto;
      font-size: 15px;
      font-weight: 300;
      margin-left: 12px;
      padding: 0 11px 0 13px;
      text-overflow: ellipsis;
      width: 300px;
    }

    #pac-input:focus {
      border-color: #4d90fe;
    }

    .pac-container {
      font-family: Roboto;
    }*/

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

     #project_location {
        z-index: 9999 !important;
        position: relative !important;
        left: 0px !important;
        top: -117px !important;
        box-shadow: rgba(255, 255, 255, 1) 0px 0px 15px 30px;
        border-radius: 0;
      }

    .map-wrapper{overflow: visible; padding-top: 0;}

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
                                <label for="project_number">Project Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="project_number" disabled>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="project_name">Project Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="project_name">
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-md-6">
                                <label for="project_name">Project Wage Determination<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="project_wage_determination">
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-md-6">
                                <label for="project_type">Improvement Types <span class="text-danger">*</span></label>
                                <div class="loading_data" style="text-align: center;display:none;">
                                    <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                </div>
                                <div id="project_type_selected" style="margin-bottom: 10px;"></div>
                                <select class="form-control select2-multiple" multiple id="project_type_dropdown" placeholder="Select Improvement Types">
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>&nbsp;</label>
                                <button type="submit" id="add_more" class="btn btn-info sub-btn">Add New Improvement Type</button>
                            </div>
                            <div class="clearfix"></div>
                            <div id="diplay" style="display: none">
                                <div class="form-group col-md-6">
                                    <label>Add New Improvement Type</label>
                                    <input type="text" class="form-control " id="imp_type">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>&nbsp;</label>
                                    <button type="submit" id="add_improvement_type" class="btn btn-info sub-btn">Add</button>
                                </div>
                            </div>


                            <div class="form-group col-md-12">
                                <label for="project_location">Location / Closest Intersection <span class="text-danger">*</span></label>
                                <!-- <input type="text" class="form-control" id="project_location"> -->
                                <input type="text" class="form-control" id="project_location" style="display: none;">
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-md-12" style="overflow: hidden;padding-top: 80px">
                                <!-- <input id="pac-input" class="controls" type="text" placeholder="Enter a location"> -->
                                <div class="map-wrapper"><div id="map"></div></div>
                            </div>
                            <div class="clearfix"></div>
<!--                        <div class="form-group col-md-6">-->
<!--                                <label for="project_latitude">Project Latitude</label>-->
                                <input type="hidden" class="form-control" id="project_latitude" >
<!--                            </div>-->
<!--                            <div class="form-group col-md-6">-->
<!--                                <label for="project_longitude">Project Longitude</label>-->
                                <input type="hidden" class="form-control" id="project_longitude">
<!--                            </div>-->
                            <div class="form-group col-md-12"  style="display: none;">
                                <label for="project_description">Project Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="project_description" rows="10"></textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="project_status">Project Status</label>
                                <select class="form-control" id="status">
                                    <option value="active">Activate</option>
                                    <option value="deactive">Deactivate</option>
                                </select>
                            </div>
                            <div class="clearfix"></div>

                             <div class="clearfix"></div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="checkbox-custom check-success">
                                                <input type="checkbox" name="project_terms" value="" id="project_terms">
                                                <label for="project_terms">Does this improvement type involve public funds and/or a public works improvement? <span class="text-danger">*</span></label>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group col-md-6 lead_agency_class" style="display: none;">
                                        <label for="project_name">Lead Agency<span class="text-danger">*</span></label>
                                        <select class="form-control" id="project_lead_agency">
                                        </select>
                                    </div>

                            <!-- <div class="form-group">
                                <div class="col-md-12">
                                    <label class="checkbox-custom check-success">
                                        <input type="checkbox" value=" " id="project_terms">
                                        <label for="project_terms">Will any portion of this improvement be reimbursed with RBBD, TUMF, CFD, or other public funds?</label>
                                    </label>
                                </div>
                            </div> -->
                            <div class="clearfix"></div>

                            <div class="form-group col-md-12">

<!--                                <a data-href="{{ url('/dashboard/') }}" class="btn btn-info back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                <a href="{{ url('/dashboard') }}" class="btn btn-info btn_back1">Back</a>

                                <button type="submit" id="update_project_form" class="btn btn-info addon-btn no-mar">Submit</button>
                            </div>

                        </div>
                    </form>

                </div>
            </section>
        </div>
    </div>
</div>
<!--body wrapper end-->

<!-- Modal for add new Agency -->
<div class="modal add-agency fade" id="add-agency" tabindex="-3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-md">
         <div class="modal-content text-center">
             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <!-- page head start-->
<div class="page-head">
    <h3 class="m-b-less">Add Agency</h3>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;    </button>
</div>
<!-- page head end-->
<style>
  #map1 {
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
  .pac-container { z-index: 100000; }
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
                            <label for="firm_name">Agency Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="firm_name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="firm_description">Agency Description <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="firm_description">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="firm_type">Agency Type <span class="text-danger">*</span></label>
                            <div class="loading_data" style="text-align: center;display: none">
                               <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                            </div>
                            <select class="form-control" id="firm_type">
                            </select>
                        </div>
                            <div class="form-group col-md-6" style="display:none;">
                            <label for="company_type">Company/Agency <span class="text-danger">*</span></label>
                            <select class="form-control" id="company_type">
                                <option value="">Select</option>
                                <option value="f">Company</option>
                                <option value="a" selected="selected">Agency</option>
                            </select>
                        </div>
                            <input type="hidden" id="project_latitude1" value="33.7174708">
                            <input type="hidden" id="project_longitude1" value="-117.83114280000001">
                        <div class="clearfix"></div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" id="firm_address" style="display: block;" value="Orange County, CA, USA">
                            <label for="firm_address">Agency Address <span class="text-danger">*</span></label>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-md-12">
                            <div class="map-wrapper"><div id="map1"></div></div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-md-12">
                            <!-- <a href="{{ url('/dashboard/firms') }}" class="btn btn-info sub-btn">Back</a> -->

                            <button class="close-add-firm-btn btn btn-info sub-btn back_button modal_btn_back" onclick="return checkFormFilled('modal_btn_back')">Back</button>

                            <button class="add_firm_form btn btn-info sub-btn first_button1">Submit</button>
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


         </div>
     </div>
 </div>
<!-- end of modal of add new agency-->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>

<script src="{{ url('/resources/assets/dist/project_update.js') }}"></script>


<!-- <script src="{{ url('/resources/assets/dist/project_add.js') }}"></script> -->
<!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEf-8-SoRe54t6wZL8_rkiuNIAhwgffIU&libraries=places&callback=initMap" async defer></script>-->

<script>





    var script = document.createElement('script');
    script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyDEf-8-SoRe54t6wZL8_rkiuNIAhwgffIU&libraries=places&callback=initMap";
    setTimeout(function(){
        document.body.appendChild(script);
    },8000);


   // setTimeout(function () {
   //      document.getElementById('project_location').style.display='block';
   //  }, 6000);



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

  $('#project_terms').change(function() {
    if($(this).is(":checked")) {
        $('.lead_agency_class').css("display", "block");
    }
    else {
        $('.lead_agency_class').css("display", "none");
    }
  });

</script>

@include('include/footer')
