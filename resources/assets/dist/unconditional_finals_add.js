$(document).ready(function() {
    // Get login user profile data
    $("#name_claimant").hide();
    $("#name_customer").hide();
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
        url: baseUrl + "/"+project_id+"/default_contractor",
            type: "GET",
            headers: {
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
    .done(function(data, textStatus, jqXHR) {
            // console.log(data.data);
            window.agency_id = data.data[0].pna_contactor_name;
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
                //$('.company_name').val(data.data.f_id);
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
     fetchCompanyName(role,check_user_access);
     fetchAgencyName(role,check_user_access);
     
     $('#disputed_claim_amount_yes').change(function() {
        if($(this).is(":checked")) {
            $('.disputed_claim_amount').css("display", "block");
            $('#disputed_claim_amount').val('');
        }
        else {
            $('.disputed_claim_amount').css("display", "none");
            $('#disputed_claim_amount').val('0');
        }
    });
 
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
   
    if($('#disputed_claim_amount_yes').is(":checked"))
        var disputed_claim_amount   = $('#disputed_claim_amount').val();
    else
        var disputed_claim_amount = '0';
    // Validation Certificate
    var html;
    var is_error = false;
    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';

    var signatory_name = [];
    $('input[name^=signatory_name]').each(function(){
        signatory_name.push($(this).val());
    });
    var signatory_email = [];
    $('input[name^=signatory_email]').each(function(){
        var email = $(this).val().trim();
        if(email != "" && /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)){
            signatory_email.push(email);
        }else if($(this).val() != ""){
            html += '<li>Signatory email is invalid.</li>';
            is_error = true;
        }
    });
    var item = {};
    item['signatory_name'] 		= signatory_name;
    item['signatory_email']         = signatory_email;
    signatory_arr = [];
    for (i = 0; i < signatory_email.length; i++) {
        signatory_arr.push({
                        "signatory_name"            :   item['signatory_name'][i],
                        "signatory_email"           :   item['signatory_email'][i],
                        "name_of_claimant"          :   $("#name_claimant option:selected").text(),
                        "name_of_customer"          :   $("#name_customer option:selected").text(),
                        "job_location"              :   $("#job_location").val(),
                        "owner"                     :   $("#owner_name option:selected").text(),
                        "disputed_claim_amount"     :   $("#disputed_claim_amount").val(),
                    });
    }
        
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
//    if(unconditional_finals == '' || unconditional_finals == null){
//        html += '<li>Document is invalid.</li>';
//        is_error = true;
//    }

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
            "project_id"            : project_id,
            "disputed_claim_amount" : disputed_claim_amount,
            "signatory_arr"         : signatory_arr,
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
        $('#date_of_signed').removeAttr('value');
        $('input[name="disputed_claim_amount_yes"]').attr('checked', false);
        $('.disputed_claim_amount').css("display", "none");
        $('input[name^=signatory_name],input[name^=signatory_email]').each(function(){$(this).val('');});
        $('#disputed_claim_amount').removeAttr('value');
        $("#alert_message").show();
        $(".remove_file_drop").trigger("click");
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New unconditional final added successfully!</div></div>';
        $("#alert_message").html(html);
        $("#name_claimant").val('');
        $("#name_customer").val('');
        $("#owner_name").val('');
        $(".first_button").text('Save Another');
        $('html, body').animate({
            scrollTop: $(".page-head").offset().top
        }, 'fast')
        setTimeout(function(){
            $("#alert_message").hide()
        },5000)
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
                    console.log("HTTP Request Failed");
                    var responseText, html;
                    responseText = JSON.parse(jqXHR.responseText);
                        $('html, body').animate({
                            scrollTop: $(".page-head").offset().top
                        }, 'fast')
                    $("#alert_message").show();
                    $('.loading-submit').hide();
                    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                    if(responseText.data.docusign != null){
                        html += '<li>'+responseText.data.description+'</li>';
                    }
                    html += '</ul></div>';
                    $("#alert_message").html(html);
                    setTimeout(function(){
                        $("#alert_message").hide()
                    },5000)
                    console.log(responseText);
                })
    e.preventDefault();

})

$('#name_claimant,#name_customer,#owner_name').change(function(){
        var company = $(this).val();
        if(company=="Add New Company" || company=="Add New Agency")
        {
            $('#add-company').modal('show');
            $('#add-company').on('shown.bs.modal',function(){
                google.maps.event.trigger(map, "resize");
              });
        }
    })
    function fetchCompanyName(role,check_user_access)
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
        $("#name_claimant").append(
            '<option value="">Select Company</option>'
        )
        $("#name_customer").append(
            '<option value="">Select Company</option>'
        )
        jQuery.each(data.data, function( i, val ) {
            if(val.f_status == 'active'){
                $("#name_claimant").append(
                    '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                )
                $("#name_customer").append(
                    '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                )
            }else {

            }
        });
        var add_company_on_fly_permission = jQuery.inArray("project_add_company_on_fly", check_user_access );
        console.log(add_company_on_fly_permission+'company_fly');
        if(add_company_on_fly_permission>0 || role=="owner"){
        $("#name_claimant").append(
            '<option style="font-weight:bold;">Add New Company</option>'
        )
        $("#name_customer").append(
            '<option style="font-weight:bold;">Add New Company</option>'
        )}
        // $( "h2" ).appendTo( $( ".container" ) );
       
        $(".loading_data").remove();
        $("#name_claimant").show();
        $("#name_customer").show();
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
    
    function fetchAgencyName(role,check_user_access)
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
        $("#owner_name").append(
            '<option value="">Select Company</option>'
        )
        jQuery.each(data.data, function( i, val ) {
            if(val.f_status == 'active'){
                $("#owner_name").append(
                    '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                )
            }else {

            }
        });
        var add_company_on_fly_permission = jQuery.inArray("project_add_company_on_fly", check_user_access );
        console.log(add_company_on_fly_permission+'company_fly');
        if(add_company_on_fly_permission>0 || role=="owner"){
        $("#owner_name").append(
            '<option style="font-weight:bold;">Add New Company</option>'
        )}
        // $( "h2" ).appendTo( $( ".container" ) );
       
        $(".loading_data").remove();
        $("#owner_name").show();
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
