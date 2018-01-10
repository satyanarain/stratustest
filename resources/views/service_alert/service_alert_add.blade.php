        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

          <div class="loading_data_file" style="display: none;">
               <div class="block">
                   <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                   <br/><span class="loading-text">Please wait, file is uploading</span>
               </div>
            </div>

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                <h3 class="m-b-less">Add Underground Service Alert</h3>
                <?php $project_id = Request::segment(2); ?>
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

  /*#ticket_location {
    display: inline-block !important;
    z-index: 9999 !important;
    position: relative !important;
    left: 0px !important;
    top: -50px !important;
  }*/

  #ticket_location {
        z-index: 9999 !important;
        position: relative !important;
        left: 0px !important;
        top: -117px !important;
        box-shadow: rgba(255, 255, 255, 1) 0px 0px 15px 30px;
        border-radius: 0;
      }


</style>
            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="panel-body">
                                <div id="alert_message"></div>
                                   <div class="row">
        <!-- <div class="form-group col-md-6">
            <label for="item_description">Date Called In</label>
            <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date="<?php echo date("Y-m-d"); ?>"  class="input-append date dpYears">
                <input type="text" value="" size="16" class="form-control"  id="date_called_in">
                <span class="input-group-btn add-on"><button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button></span>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label>Date Ticket is Valid</label>
            <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date="<?php echo date("Y-m-d"); ?>"  class="input-append date dpYears">
                <input type="text" value="" size="16" class="form-control"  id="date_ticket_valid">
                <span class="input-group-btn add-on"><button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button></span>
            </div>
        </div> -->
        <div class="container">
          <div class='col-md-5 nopadleft'>
          <label for="item_description" style="float: left;">Date and Time Called In <span class="text-danger">*</span></label>
              <div class="form-group" style="float: right; width: 300px;">
                  <div class='input-group date' id='datetimepicker6'>
                      <input type='text' class="form-control" id="date_called_in" />
                      <span class="input-group-addon btn btn-primary">
                          <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                  </div>
              </div>
          </div>
          <div class='col-md-5 nopadleft'>
          <label  style="float: left;">Date and Time Ticket is Valid <span class="text-danger">*</span></label>
              <div class="form-group">
                  <div class='input-group date' id='datetimepicker7' style="float: right; width: 250px;">
                      <input type='text' class="form-control" id="date_ticket_valid" />
                      <span class="input-group-addon btn btn-primary">
                          <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                  </div>
              </div>
          </div>
      </div>

        <div class="form-group col-md-12">
            <label for="item_qty">Ticket Number <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="ticket_number">
        </div>

        <div class="form-group col-md-12">
            <label for="ticket_location">Location / Closest Intersection <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="ticket_location" style="display: none;">
        </div>
        <div class="clearfix"></div>
        <div class="form-group col-md-12">
            <div class="map-wrapper"><div id="map"></div></div>
        </div>
        <div class="clearfix"></div>
        <div class="form-group col-md-6">
            <label for="item_unit_price">Expiration Date <span class="text-danger">*</span></label>
            <div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date="<?php echo date("Y-m-d"); ?>"  class="input-append date dpYears">
                <input type="text" readonly value="" size="16" class="form-control"  id="expiration_date">
                <span class="input-group-btn add-on"><button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button></span>
            </div>
            <input type="hidden" class="form-control" id="project_id" value="<?php echo $project_id; ?>">
        </div>



                                        <div class="form-group col-md-12">
<!--                                            <a data-href="{{ url('/dashboard/'.$project_id.'/service_alert') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                            <a href="{{ url('/dashboard/'.$project_id.'/service_alert') }}" class="btn btn-info sub-btn btn_back" onclick="return checkFormFilled('btn_back')">Back</a>
                                            <button type="submit" class="btn btn-info sub-btn first_button" id="add_service_alert_form">Save</button>
                                            <a href="{{ url('/dashboard/'.$project_id.'/test_result') }}" class="btn btn-info sub-btn continue_button" onclick="return checkFormFilled('continue_button')">Next Screen</a>
                                            <p class="loading-submit" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
                                        </div>

                                    </div>

                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <!--body wrapper end-->
<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEf-8-SoRe54t6wZL8_rkiuNIAhwgffIU&libraries=places&callback=initMap" async defer></script> -->
<script src="{{ url('/resources/assets/dist/service_alert_add.js') }}"></script>
<script>

var script = document.createElement('script');
    script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyDEf-8-SoRe54t6wZL8_rkiuNIAhwgffIU&libraries=places&callback=initMap";
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



    $(function () {
        $('#datetimepicker6').datetimepicker();
        $('#datetimepicker7').datetimepicker({
            useCurrent: false //Important! See issue #1075
        });
        $("#datetimepicker6").on("dp.change", function (e) {
            $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
        });
        $("#datetimepicker7").on("dp.change", function (e) {
            $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
        });
    });


    // $('#ticket_location').css("display", "inline-block");
    // $('#ticket_location').css("z-index", "9999");
    // $('#ticket_location').css("position", "relative");
    // $('#ticket_location').css("left", "0px");
    // $('#ticket_location').css("top", "-50px");

});

  function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 36.443796, lng: -119.369653},
          zoom: 7,
          scrollwheel: false
        });
        var input = /** @type {!HTMLInputElement} */(
            document.getElementById('ticket_location'));

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

          infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
          infowindow.open(map, marker);
        });
      }
      setTimeout(function(){
        document.getElementById('ticket_location').style.display='block';
    },7000);
</script>
@include('include/footer')
