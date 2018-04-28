$(document).ready(function() { 


    // Get login user profile data
    $("#agency_name").hide();
    var role = localStorage.getItem('u_role');
    //console.log(role+'lll');
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    project_id  = url[ url.length - 3 ]; // project_id
    console.log(project_id);

    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("standard_add", check_user_access );
    if(check_permission < 1){
        window.location.href = baseUrl + "403";
    }
    else {
        console.log('Yes Permission');
        $('.body-content .wrapper').show();
    }
    fetchCompanyName(role,check_user_access);
     

    jQuery.ajax({
        url: baseUrl + "projects/"+project_id,
        type: "GET",
        headers: {
          "Content-Type": "application/json",
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        var project_name = data.data.p_name;
        $('#project_name_title').text("Project: " + project_name);
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        if(response == 403){
            // window.location.href = baseUrl + "403";
            console.log("403");
        }
        else if(response == 404){
            console.log("404");
            // window.location.href = baseUrl + "404";
        }
        else {
            // console.log("500");
            window.location.href = baseUrl + "500";
        }
    })


}); 

    

    $('.add_standard_form').click(function(e) {
        e.preventDefault();
        $('.loading-submit').show();
        var company_name        = $('#agency_name').val();
        var standard_name       = $('#standard_name').val();
        var standard_date       = $('#standard_date').val();
        var standard_link       = $('#standard_link').val();
        var applicable          = $('#applicable').is(':checked');
        if(applicable==true){
            applicable  = "yes";

            var r = confirm("Warning! if any information entered, will be lost and not saved.");
            if (r == true) {
                window.location.href = baseUrl +"dashboard/"+project_id+"/specifications";
            } else {
                $("#applicable").prop('checked', false);
                $('.loading-submit').hide();
                return false;
            }
        }
        if(applicable==false){
            applicable  = "no";
        }
        var standard_type       = $('#upload_doc_meta').val();
        var standard_file_path  = $('#upload_doc_id').val();
        var standard_project_id = $('#upload_project_id').val();

        // Validation Certificate
        var html;
        var is_error = false;
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
        if(company_name == ''){
            html += '<li>Agency name is invalid.</li>';
            is_error = true;
        }
        if(standard_name == ''){
            html += '<li>Standard name is invalid.</li>';
            is_error = true;
        }
        if(standard_date == ''){
            html += '<li>Standard date is invalid.</li>';
            is_error = true;
        }
         if(standard_file_path == ''){
             html += '<li>Standard document is invalid.</li>';
             is_error = true;
         }

        var urlregex = new RegExp(
           "^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/|www\.)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$");
        var standard_link_validation = urlregex.test(standard_link);

        if(standard_link != ''){
            if(standard_link_validation == true){
                // html += '<li>Standard link is valid.</li>';
                // alert("valid url");
                // is_error == true;
            }
            else {
                html += '<li>Standard link is invalid.</li>';
                is_error = true;
            }
        }
        
        html += '</ul></div>';
        // if(is_error = true){
        //     $("#alert_message").show();
        //     $('.loading-submit').hide();
        //     $('html, body').animate({
        //         scrollTop: $(".page-head").offset().top
        //     }, 'fast')
        //     setTimeout(function(){
        //         $("#alert_message").hide()
        //     },3000)
        //     return false;
        // }

        if(is_error == true){
            $("#alert_message").html(html);
            $("#alert_message").show();
            $('.loading-submit').hide();
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            setTimeout(function(){
                $("#alert_message").hide();
                return false;
            },5000)
            return;
        }
        else {
            // alert("valid213");
            var token               = localStorage.getItem('u_token');
            jQuery.ajax({
                url: baseUrl + "standards/add",
                type: "POST",
                data: {
                    "agency_name"       : company_name,
                    "name"              : standard_name,
                    "date"              : standard_date,
                    "url"               : standard_link,
                    "applicable"        : applicable,
                    "type"              : standard_type,
                    "upload_file"       : standard_file_path,
                    "project_id"        : standard_project_id
                },
                headers: {
                  "x-access-token": token
                },
                contentType: "application/x-www-form-urlencoded",
                cache: false
            })
            .done(function(data, textStatus, jqXHR)
                {
                    $('.loading-submit').hide();
                    $("#standard_name").removeAttr('value');
                    $("#standard_link").removeAttr('value');
                    $("#standard_date").removeAttr('value');
                    //$("#agency_name").removeAttr('value');
                    $("#agency_name").val('');
                    
                    $("#upload_doc_ids").removeAttr('value');
                    $("#upload_doc_id").removeAttr('value');
                    $("#applicable").prop('checked', false);
                    $(".remove_file_drop").trigger("click");
                    $(".first_button").text('Save Another');
                    $('html, body').animate({
                        scrollTop: $(".page-head").offset().top
                    }, 'fast')


                    $("#alert_message").show();
                    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Standard added successfully!</div></div>';
                    $("#alert_message").html(html);
                    setTimeout(function()
                    {
                        $("#alert_message").hide();
                    },6000)
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                    $('.loading-submit').hide();
                    console.log("HTTP Request Failed");
                    var responseText, html;
                    responseText = JSON.parse(jqXHR.responseText);
                    // console.log(responseText.data.currency_name);
                    $('html, body').animate({
                        scrollTop: $(".page-head").offset().top
                    }, 'fast')
                    $("#alert_message").show();
                    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                    if(responseText.data.agency_name != null){
                        html += '<li>The agency name is required.</li>';
                    }
                    if(responseText.data.name != null){
                        html += '<li>The standard name is required.</li>';
                    }
                    if(responseText.data.date != null){
                        html += '<li>The date is required.</li>';
                    }
                    if(responseText.data.upload_file != null){
                        html += '<li>The standard document is required.</li>';
                    }
                    if(responseText.data.project_id != null){
                        html += '<li>The project id is required.</li>';
                    }
                    html += '</ul></div>';
                    $("#alert_message").html(html);
                    setTimeout(function(){
                        $("#alert_message").hide();
                    },6000)
                 ;
            })
        }
    });
    $('.agency_name').change(function(){
        var company = $(this).val();
        if(company=="Add New Agency")
        {
            //map.clear();
//            info_Window = new google.maps.InfoWindow();
//            info_Window.close();
//            for (var i = 0; i < marker.length; i++) {
//                marker[i].setMap(null);
//            }
//            marker.length = 0;
//            for(var i=0;i<location.length;i++){
//                location[i].setMap(null);
//            }
//            location.length=0;
//            marker = [];
            $('#add-agency').modal('show');
            $('#add-agency').on('shown.bs.modal',function(){
                google.maps.event.trigger(map, "resize");
              });
            
        }
    })
    function fetchCompanyName(role,check_user_access)
    {
        jQuery.ajax({
        url: baseUrl+project_id+"/company_name_user_agency",
        type: "GET",
        headers: {
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
        })
        .done(function(data, textStatus, jqXHR) {
        // console.log(data);
        // Foreach Loop 
        $(".agency_name").append(
            '<option value="">Select Agency</option>'
        )
        jQuery.each(data.data, function( i, val ) {
            if(val.f_status === 'active'){
                
                $(".agency_name").append(
                    '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                )
            }else {

            }
        });
        var add_company_on_fly_permission = jQuery.inArray("project_add_company_on_fly", check_user_access );
        if(add_company_on_fly_permission>0 || role=="owner"){
        $(".agency_name").append(
            '<option style="font-weight:bold;">Add New Agency</option>'
        )}
        // $( "h2" ).appendTo( $( ".container" ) );
       
        $(".loading_data").remove();
        $("#agency_name").show();
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