        @include('include/header')
        @include('include/sidebar')
        <!-- body content start-->
        <div class="body-content" >
          @include('include/top_bar')
            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">Add User</h3>
            </div>
            <!-- page head end-->
            <!--body wrapper start-->
            <div class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div id="alert_message"></div>
                            <div id="updateuserinfo"></div>
                            <header class="panel-heading">
                                <i class="icon-user"></i> User Information
                            </header>
                            <div class="panel-body">

                                
                                    <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="uname">Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="uname">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="cname">Company Name <span class="text-danger">*</span></label>
                                        <div class="loading_data" style="text-align: center;">
                                           <img src="{{ url('/resources/assets/img/loading_bar.gif') }}" alt=""/>
                                        </div>
                                        <select class="form-control" id="company_name">
                                            <option value="">Select Company name</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="position">Position / Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="position">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="fname">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="fname">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="lname">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="lname">
                                    </div>
                                    <div class="input_fields_wrap">
                                    <div class="col-md-6 append">
                                        <div class="form-group">
                                            <label for="pnum">Phone Number <span class="text-danger">*</span></label>
                                            <a href="#" class="add_field_button btn btn-success m-b-10 pull-right">Add Phone</a>
                                            <select class="form-control user_contact_no" id="phone_type_1" name="phone_type[]">
                                                <option value="">Select</option>
                                                <option value="mobile">Mobile</option>
                                                <option value="home">Home</option>
                                                <option value="work">Work</option>
                                                <option value="work_fax">Work Fax</option>
                                                <option value="home_fax">Home Fax</option>
                                                <option value="skype">Skype</option>
                                                <option value="work_email">Email</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="pnum_1" name="phone_num[]">
                                        </div>
                                        </div>
                                    <div class="col-sm-6">
                                        <label for="standard_name">Upload User Image <span class="text-danger">*</span> </label>
                                        <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                            <input type="hidden" name="document_path" value="/uploads/users/">
                                        </form>

                                        <div class="form-group">
                                            <input type="hidden" name="user_image_path" id="user_image_path" value="">
                                            
                                        </div>
                                    </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                            <input type="hidden" class="form-control user_type_value" id="role">
                                        <!-- <label>User Type <span class="text-danger">*</span></label>
                                        <select class="form-control" id="role">
                                            <option value="owner" class="owner_class">Owner</option>
                                            <option value="manager">Manager</option>
                                            <option value="contractor">Contractor</option>
                                            <option value="engineer">Engineer</option>
                                            <option value="surveyor">Surveyor</option>
                                        </select> -->
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="form-group col-md-12">
                                        <!-- <a href="{{ url('/dashboard/users') }}" class="btn btn-info sub-btn">Back</a> -->

<!--                                        <a data-href="{{ url('/dashboard/users') }}" class="btn btn-info sub-btn back_button" data-toggle="modal" data-target="#confirm-back">Back</a>-->
                                        <a href="{{ url('/dashboard/users') }}" class="btn btn-info sub-btn btn_back" onclick="return checkFormFilled('btn_back')">Back</a>


                                        <button type="submit" id="add_user_form" class="add_user_form first_button btn btn-info sub-btn">Save</button>
                                        <button type="submit" id="add_swppp_form" class="add_user_form another_button btn btn-info sub-btn" style="display: none;">Save Another</button>
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
<script src="{{ url('/resources/assets/js/dropzone_image.js') }}"></script>
<script src="{{ url('/resources/assets/dist/user_add.js') }}"></script>

<div class="modal add-company fade" id="add-company" tabindex="-3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-md">
         <div class="modal-content text-center">
             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
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
                        <div class="form-group col-md-6">
                            <label for="company_type">Company/Agency <span class="text-danger">*</span></label>
                            <select class="form-control" id="company_type">
                                <option value="">Select</option>
                                <option value="f">Company</option>
                                <option value="a">Agency</option>
                            </select>
                        </div>
                            <input type="hidden" id="project_latitude" value="33.7174708">
                            <input type="hidden" id="project_longitude" value="-117.83114280000001">
                        <div class="clearfix"></div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" id="firm_address" style="display: block;" value="Orange County, CA, USA">
                            <label for="firm_address">Company Address <span class="text-danger">*</span></label>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-md-12">
                            <div class="map-wrapper"><div id="map"></div></div>
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
<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<!-- <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script> -->
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEf-8-SoRe54t6wZL8_rkiuNIAhwgffIU&libraries=places&callback=initMap" async defer></script>


<script>
$(document).ready(function() {
    var curr_url = $(location).attr('href')
    $(".close-add-firm-btn").attr("href",curr_url);
    //alert();return false;
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
    
    $("#s2id_firm_type").hide();
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    jQuery.ajax({
        url: baseUrl + "company-type",
            type: "GET",
            headers: {
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
    .done(function(data, textStatus, jqXHR) {
            // console.log(data.data);
            // Foreach Loop 
            $("#firm_type").append('<option value="">Select Company Type</option>');
            jQuery.each(data.data, function( i, val ) {
                if(val.ct_status == 'active'){
                    $("#firm_type").append(
                        '<option value="'+val.ct_id+'">'+val.ct_name+'</option>'
                    )
                }else {

                }
            });
            // $( "h2" ).appendTo( $( ".container" ) );
            $(".loading_data").remove();
            $("#s2id_firm_type").show();
        })
    .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            console.log(response);
            if(response == 403){
                window.location.href = baseUrl + "403";
            }
            else if(response == 404){
                console.log('Company Type 404');
                alert("You can't add company, first add company type!");
                // window.location.href = baseUrl + "404";
            }
            else {
                window.location.href = baseUrl + "500";
            }
        });
        
    $('.add_firm_form').click(function(e) {
        e.preventDefault();
        var token = localStorage.getItem('u_token');
        var firm_name = $('#firm_name').val();
        var firm_description = $('#firm_description').val();
        var firm_address = $('#firm_address').val();
        var firm_type = $('#firm_type').val();
        var lat =  $("#project_latitude").val();
        var long =  $("#project_longitude").val();
        var company_type = $("#company_type").val();
        jQuery.ajax({
            url: baseUrl + "firm-name/add",
            type: "POST",
            data:
            {
                "firm_name"     : firm_name,
                "firm_detail"   : firm_description,
                "firm_address"  : firm_address,
                "firm_type"     : firm_type,
                "company_type"  : company_type,
                "project_long" :long,
                "project_lat":lat
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            console.log(data.description);
            $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
            }, 'fast')
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-success">New firm added successfully!</div></div>';
            $("#alert_message").html(html);
            $(".first_button1").hide();
            
            $("#project_latitude").removeAttr('value');
            $("#project_longitude").removeAttr('value');
            $("#firm_type").val('');
            $("#company_type").val('');
            $(".another_button").show();
            setTimeout(function()
                {
                    $("#toast-container").fadeOut(1000);
                },5000)
            $("#toast-container").fadeOut(10000);
            $("#firm_name").removeAttr('value');
            $("#firm_description").removeAttr('value');
            $("#firm_address").removeAttr('value');
            jQuery.ajax({
                url: baseUrl+"firm-name",
                type: "GET",
                headers: {
                  "x-access-token": token
                },
                contentType: "application/json",
                cache: false
                })
            .done(function(data, textStatus, jqXHR) {
                if($("#company_name").length > 0) {
                    var ele_name = '#company_name';
                    $(ele_name).empty();
                    jQuery.each(data.data, function( i, val ) {
                        if(val.f_status == 'active'){
                            $(ele_name).append(
                                '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                            )
                        }else {

                        }
                    });
                    var add_company_on_fly_permission = jQuery.inArray("project_add_company_on_fly", check_user_access );
                    console.log(add_company_on_fly_permission+'company_fly');
                    if(add_company_on_fly_permission>0 || role=="owner" || role=="admin"){
                        $(ele_name).append(
                            '<option style="font-weight:bold;">Add New Company</option>'
                        )
                    }
                }
                $(".loading_data").remove();
                $("#company_name").show();
                $('#add-company').modal('hide');
                $('#firm_address').val("");
                $('#project_latitude').val("");
                $('#project_longitude').val("");
                $('#firm_address').css("display", "inline-block");
                $('#firm_address').css("z-index", "9999");
                $('#firm_address').css("position", "relative");
                $('#firm_address').css("left", "0px");
                $('#firm_address').css("top", "-50px");
                //map.clear();
                info_Window = new google.maps.InfoWindow();
                info_Window.close();
                for (var i = 0; i < marker.length; i++) {
                    marker[i].setMap(null);
                }
                marker.length = 0;
                for(var i=0;i<location.length;i++){
                    location[i].setMap(null);
                }
                location.length=0;
                marker = [];
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var response = jqXHR.responseJSON.code;
                console.log(jqXHR);
                if(response == 403){
                    console.log('Company name 403');
                    // window.location.href = baseUrl + "403";
                }
                else if(response == 404){
                    console.log('Company name 404');
                    // window.location.href = baseUrl + "404";
                }
                else {
                    window.location.href = baseUrl + "500";
                }
            });
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(responseText.data);
                $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
                }, 'fast')
                $("#alert_message").fadeIn(1000);
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-error"><ul>';
                if(responseText.data.firm_name != null){
                	html += '<li>Company name field is Invalid</li>';
                }
                if(responseText.data.firm_detail != null){
                    html += '<li>Company description field is Invalid</li>';
                }
                if(responseText.data.firm_address != null){
                    html += '<li>Company address field is Invalid</li>';
                }
                if(responseText.data.firm_type != null){
                    html += '<li>Company type field is Invalid</li>';
                }
                html += '</ul></div></div>';
                $("#alert_message").html(html);
                $("#toast-container").fadeOut(10000);

        })
    });
            
    $('.close-add-firm-btn').click(function(){
        if(isFilled===true)
        {
            $('#confirm-back').modal('show');
        }else{
            jQuery.ajax({
        url: baseUrl+"firm-name",
        type: "GET",
        headers: {
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
        })
            .done(function(data, textStatus, jqXHR) {
        $('#company_name').empty();
        jQuery.each(data.data, function( i, val ) {
            if(val.f_status == 'active'){
                $("#company_name").append(
                    '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                )
            }else {

            }
        });
        var add_company_on_fly_permission = jQuery.inArray("project_add_company_on_fly", check_user_access );
        console.log(add_company_on_fly_permission+'company_fly');
        if(add_company_on_fly_permission>0 || role=="owner" || role=="admin"){
            $("#company_name").append(
                '<option style="font-weight:bold;">Add New Company</option>'
            )
        }
        // $( "h2" ).appendTo( $( ".container" ) );
       
        $(".loading_data").remove();
        $("#company_name").show();
        $('#add-company').modal('hide');
        $('#firm_address').val("");
        $('#project_latitude').val("");
        $('#project_longitude').val("");
        
//        var map = new google.maps.Map(document.getElementById('map'), {
//          center: {lat: 36.443796, lng: -119.369653},
//          zoom: 7,
//          scrollwheel: false
//        });
        $('#firm_address').css("display", "inline-block");
    $('#firm_address').css("z-index", "9999");
    $('#firm_address').css("position", "relative");
    $('#firm_address').css("left", "0px");
    $('#firm_address').css("top", "-50px");
        map.clear();
        info_Window = new google.maps.InfoWindow();
            info_Window.close();
            for (var i = 0; i < marker.length; i++) {
                marker[i].setMap(null);
            }
            marker.length = 0;
            for(var i=0;i<location.length;i++){
                location[i].setMap(null);
            }
            location.length=0;
            marker = [];
    })
            .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        console.log(jqXHR);
        if(response == 403){
            console.log('Company name 403');
            // window.location.href = baseUrl + "403";
        }
        else if(response == 404){
            console.log('Company name 404');
            // window.location.href = baseUrl + "404";
        }
        else {
            window.location.href = baseUrl + "500";
        }
    });
        }
        
    })
  })  
  function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 33.7174708, lng: -117.83114280000001},
          zoom: 11,
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


         </div>
     </div>
 </div>

@include('include/footer')
