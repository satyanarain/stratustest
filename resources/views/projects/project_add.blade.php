        @include('include/header')
        @include('include/sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">Add Project</h3>
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
        display: inline-block !important;
        z-index: 9999 !important;
        position: relative !important;
        left: 0px !important;
        top: -138px !important;
      }

    </style>

            <!--body wrapper start-->
            <div class="wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">

                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <div class="panel-body">
                                <div id="alert_message"></div>
                                <form role="form">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="project_number">Project Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="project_number">
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
                                            <div class="loading_data" style="text-align: center;display: none;">
                                               <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                            </div>
                                            <select class="form-control select2-multiple" multiple id="project_type" placeholder="Select Improvement Types">
                                            </select>
                                        </div>

                                            <div class="form-group col-md-6">
                                                <label class="sub-btn">&nbsp;</label>
                                                <button type="submit" id="add_more" class="btn btn-info sub-btn pull-right">Add New Improvement Type</button>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div id="diplay" style="display: none">
                                            <div class="form-group col-md-6">
                                                <label>Add New Improvement Type</label>
                                              <input type="text" class="form-control " id="imp_type">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>&nbsp;</label>
                                                <button type="submit" id="add_improvement_type" class="btn btn-info sub-btn">Add Improvement Type in list</button>
                                            </div>
                                        </div>

                                        <div class="clearfix"></div>
                                        <div class="form-group col-md-12">
                                            <label for="project_location">Location / Closest Intersection <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control " id="project_location"  style="display: block;">
                                            <!-- <input id="pac-input" class="controls" type="text" placeholder="Enter a location"> -->
                                        </div>

                                            <input type="hidden" class="form-control" id="project_latitude">

                                            <input type="hidden" class="form-control" id="project_longitude">

                                    <div class="clearfix"></div>
                                    <div class="form-group col-md-12">
                                    <!-- <input id="pac-input" class="controls" type="text" placeholder="Enter a location"> -->
                                        <div class="map-wrapper"><div id="map"></div></div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="form-group col-md-12" style="display: none;">
                                        <label for="project_description">Project Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="project_description" rows="10"></textarea>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="checkbox-custom check-success">
                                                <input type="checkbox" name="project_terms" value="" id="project_terms">
                                                <label for="project_terms">Will any portion of this improvement be reimbursed with public funds? <span class="text-danger">*</span></label>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group col-md-6 lead_agency_class" style="display: none;">
                                        <label for="project_name">Lead Agency<span class="text-danger">*</span></label>
                                        <select class="form-control" id="project_lead_agency">
                                        </select>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="form-group col-md-12">
                                        
<!--                                        <a data-href="{{ url('/dashboard') }}" class="btn btn-info  back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                        <a href="{{ url('/dashboard') }}" class="btn btn-info btn_back" onclick="return checkFormFilled('btn_back')">Back</a>
                                        <button type="submit" id="add_project_form" class="btn btn-info no-mar">Submit</button>
                                        <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
                                        <!-- <button type="submit" class="btn btn-lg btn-success addon-btn  pull-right">
                                            <i class="fa fa-forward pull-right"></i>
                                            Continue
                                        </button> -->
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
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDIoFv2pgyZCk4whduCYs5Ol3ziTuo9-sI&libraries=places&callback=initMap" async defer></script> -->
<script src="{{ url('/resources/assets/dist/project_add.js') }}"></script>
<script>
var script = document.createElement('script');
    script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyDIoFv2pgyZCk4whduCYs5Ol3ziTuo9-sI&libraries=places&callback=initMap";
    setTimeout(function(){
        document.body.appendChild(script);
    },1000);

$(document).ready(function() {
    // $('.wysihtml5').wysihtml5();

    // $('.summernote').summernote({
    //     height: 200,                 // set editor height
    //     minHeight: null,             // set minimum height of editor
    //     maxHeight: null,             // set maximum height of editor
    //     focus: true                 // set focus to editable area after initializing summernote
    // });

    $('#project_location').css("display", "inline-block");
    $('#project_location').css("z-index", "9999");
    $('#project_location').css("position", "relative");
    $('#project_location').css("left", "0px");
    $('#project_location').css("top", "-50px");
});

  function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 36.443796, lng: -119.369653},
          zoom: 7,
          scrollwheel: false
        });
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
          return false;

        });
      }



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
