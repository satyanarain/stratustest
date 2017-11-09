$(document).ready(function() {
    // Get login user profile data
    $(".company_name").hide();
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3]; // projects
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');

     // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("unconditional_finals_add", check_user_access );
    console.log(check_permission);
    if(check_permission < 1){
        window.location.href = baseUrl + "403";
    }
    else {
        console.log('Yes Permission');
        $('.body-content .wrapper').show();
    }

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

    // Get Selected Agency
    jQuery.ajax({
        url: baseUrl + "standards/"+project_id+"/standard",
            type: "GET",
            headers: {
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
    .done(function(data, textStatus, jqXHR) {
            // console.log(data.data);
            window.agency_id = data.data[0].ps_agency_name;
            console.log(agency_id);
            // $("#company_name").val(parseInt(agency_id));
            // $(".loading_data").hide();
            // // Select Company Detail for PDF
            jQuery.ajax({
                url: baseUrl + "firm-name/"+agency_id,
                    type: "GET",
                    headers: {
                      "Content-Type": "application/json",
                      "x-access-token": token
                    },
                    contentType: "application/json",
                    cache: false
                })
            .done(function(data, textStatus, jqXHR) {
                // console.log(data.data);
                $('.company_name').val(data.data.f_id);
                $('#contractor_name').text(data.data.f_name);
            })
        })
    .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            console.log(response);
            if(response == 403){
                // window.location.href = baseUrl + "403";
            }
            else if(response == 404){
               $(".loading_data").hide();
            }
            else {
                window.location.href = baseUrl + "500";
            }
        });
     fetchCompanyName();   
});

$('.add_unconditional_finals').click(function(e)
{
  $('.loading-submit').show();
    e.preventDefault();
    var project_id              = $('#upload_project_id').val();
    var date_of_signed          = $('#date_of_signed').val();
    var name_claimant           = $('#name_claimant').val();
    var name_customer           = $('#name_customer').val();
    var job_location            = $('#job_location').val();
    var owner_name              = $('#owner_name').val();
    var unconditional_finals    = $('#upload_single_doc_id').val();

    // Validation Certificate
    var html;
    var is_error = false;
    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';

    if(date_of_signed == '' || date_of_signed == null){
        html += '<li>Date of signature is invalid.</li>';
        is_error = true;
    }
    if(name_claimant == '' || name_claimant == null){
        html += '<li>Name of claimant with is invalid.</li>';
        is_error = true;
    }
    if(name_customer == '' || name_customer == null){
        html += '<li>Name of customer is invalid.</li>';
        is_error = true;
    }
    if(job_location == '' || job_location == null){
        html += '<li>Job location is invalid.</li>';
        is_error = true;
    }
    if(owner_name == '' || owner_name == null){
        html += '<li>Owner name is invalid.</li>';
        is_error = true;
    }
    if(unconditional_finals == '' || unconditional_finals == null){
        html += '<li>Document is invalid.</li>';
        is_error = true;
    }

    if(is_error == true){
        html += '</ul></div>';
        $("#alert_message").show();
        $('.loading-submit').hide();
        $("#alert_message").html(html);
        $('html, body').animate({
            scrollTop: $(".page-head").offset().top
        }, 'fast')
        setTimeout(function(){
            $("#alert_message").hide()
            return false;
        },3000)
        return;
    }



    jQuery.ajax({
        url: baseUrl + "unconditional_finals/add",
        type: "POST",
        data: {
            "date_of_signature"     : date_of_signed,
            "name_of_claimant"      : name_claimant,
            "name_of_customer"      : name_customer,
            "job_location"          : job_location,
            "owner"                 : owner_name,
            "file_path"             : unconditional_finals,
            "project_id"            : project_id
    },
        headers: {
            "x-access-token": token
        },
        contentType: "application/x-www-form-urlencoded",
        cache: false
    }).done
    (function(data, textStatus, jqXHR) {
        $('.loading-submit').hide();
        $("#upload_single_doc_id").removeAttr('value');
        $('#job_location').removeAttr('value');
        $("#alert_message").show();
        $(".remove_file_drop").trigger("click");
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New unconditional finals added successfully!</div></div>';
        $("#alert_message").html(html);
        $(".first_button").text('Save Another');
        $('html, body').animate({
            scrollTop: $(".page-head").offset().top
        }, 'fast')
        setTimeout(function(){
            $("#alert_message").hide()
        },5000)
    })
    e.preventDefault();

})

$('.company_name').change(function(){
        var company = $(this).val();
        if(company=="Add New Entity" || company=="Add New Company")
        {
            $('#add-company').modal('show');
            $('#add-company').on('shown.bs.modal',function(){
                google.maps.event.trigger(map, "resize");
              });
        }
    })
    function fetchCompanyName()
    {
        jQuery.ajax({
        url: baseUrl+project_id+"/company_name_user",
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
        $(".company_name").append(
            '<option value="">Select Company</option>'
        )
        jQuery.each(data.data, function( i, val ) {
            if(val.f_status == 'active'){
                $(".company_name").append(
                    '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                )
            }else {

            }
        });
        $(".company_name").append(
            '<option>Add New Entity</option>'
        )
        // $( "h2" ).appendTo( $( ".container" ) );
       
        $(".loading_data").remove();
        $(".company_name").show();
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
