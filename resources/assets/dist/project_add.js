$(document).ready(function() { 
    // Get login user profile data
    // $("#add_project_form").hide();
    $("#s2id_project_type").hide();
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    var project_id = localStorage.getItem('current_project_id');
//    for (var i = 0; i < localStorage.length; i++){
//        console.log(localStorage.key(i)+"="+localStorage.getItem(localStorage.key(i)));
//    }
    get_improvement_project();
    //alert("sds");
    jQuery.ajax({
        url: baseUrl+"/agency-name",
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
        $("#project_lead_agency").append(
            '<option value="">Select Agency Name</option>'
        )
        jQuery.each(data.data, function( i, val ) {
            if(val.f_status == 'active'){
                $("#project_lead_agency").append(
                    '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                )
            }else {

            }
        });
        $("#project_lead_agency").append(
            '<option style="font-weight:bold;">Add New Agency</option>'
        )
        // $( "h2" ).appendTo( $( ".container" ) );

        $(".loading_data").remove();
        $("#company_name").show();
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        console.log(response);
        if(response == 403){
            console.log('Company name 403');
            // window.location.href = baseUrl + "403";
        }
        else if(response == 404){
            $("#project_lead_agency").append(
                '<option value="">Select Agency Name</option>'
            )
            $("#project_lead_agency").append(
                '<option style="font-weight:bold;">Add New Agency</option>'
            )
            console.log('Company name 404');
            // window.location.href = baseUrl + "404";
        }
        else {
            window.location.href = baseUrl + "500";
        }
    });
    
    $('#project_lead_agency').change(function(){
        var company = $(this).val();
        if(company=="Add New Agency")
        {
            $('#add-agency').modal('show');
            $('#add-agency').on('shown.bs.modal',function(){
                google.maps.event.trigger(map, "resize");
                google.maps.event.trigger(map1, "resize");
              });
        }
    })
});


    $('#add_project_form').click(function(e) {
        e.preventDefault();
        $('.loading-submit').show();

        var project_terms ='';
        var check_box = document.getElementById("project_terms").checked;
        if(check_box ==true){
            project_terms = "yes";
        }
      
        else if(check_box==false){
            project_terms = 'no';
        }


        var project_number              = $('#project_number').val();
        var project_name                = $('#project_name').val();
        var project_type                = $('#project_type').val();
        var project_location            = $('#project_location').val();
        var project_lat                 = $('#project_latitude').val();
        var project_long                = $('#project_longitude').val();
        var project_description         = $('#project_description').val();
        var project_wage_determination  = $('#project_wage_determination').val();
        var project_lead_agency         = $('#project_lead_agency').val();
        var rfi_due_date                = $('#rfi_due_date').val();;
        var rfi_days_type               = $('#rfi_days_type').val();
        var submittal_days_type         = $('#submittal_days_type').val();
        var submittal_due_date          = $('#submittal_due_date').val();
        var change_order_due_date       = $('#change_order_due_date').val();
        var change_order_days_type      = $('#change_order_days_type').val();
        
        console.log(project_terms);

        if(project_type == null){
            // $("#alert_message").show();
            // html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            // html += '<li>Improvement type field is invalid</li>';
            // html += '</ul></div>';
            // $('html, body').animate({
            //     scrollTop: $(".page-head").offset().top
            // }, 'fast')
            // $("#alert_message").html(html);
            // setTimeout(function(){
            //     $("#alert_message").hide()
            // },3000)

            // return false;
            project_type = '';
        }
        else {
            project_type = JSON.stringify(project_type);
        }

        // var html;
        // var is_error = false;
        // html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
        
        // if(project_number == ''){
        //     html += '<li>Project number is invalid.</li>';
        //     is_error == true;
        // }
        // if(project_name == ''){
        //     html += '<li>Project name is invalid.</li>';
        //     is_error == true;
        // }
        // if(project_type == ''){
        //     html += '<li>Project type is invalid.</li>';
        //     is_error == true;
        // }
        // if(project_wage_determination == ''){
        //     html += '<li>Project wage determination is invalid.</li>';
        //     is_error == true;
        // }
        // else {
        //     project_type = JSON.stringify(project_type);
        // }
        // if(project_location == ''){
        //     html += '<li>Project location is invalid.</li>';
        //     is_error == true;
        // }
        // if(project_terms == 'yes'){
        //     if(project_lead_agency == ''){
        //         html += '<li>Project lead agency is invalid.</li>';
        //         is_error == true;
        //     }
        // }
        // // if(project_description == ''){
        // //     html += '<li>Project description is invalid.</li>';
        // //     is_error == true;
        // // }
        // // if(check_box ==true){
        // //     project_terms = "yes";
        // // }
        // // else if(check_box==false){
        // //     html += '<li>Project Term is invalid.</li>';
        // //     is_error == true;
        // // }

        // console.log(is_error);
        // html += '</ul></div>';
        // $("#alert_message").html(html);
        // if(is_error == true){
        //     $("#alert_message").show();
        //     $('html, body').animate({
        //         scrollTop: $(".page-head").offset().top
        //     }, 'fast')
        //     setTimeout(function(){
        //         $("#alert_message").hide()
        //         return;
        //     },5000)
        //     return;
        // }

        console.log(project_type);
        
        var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "projects/add",
            type: "POST",
            data: {
                "project_number" : project_number,
                "project_name" : project_name,
                "project_location" : project_location,
                "project_long" : project_long,
                "project_lat" : project_lat,
                "project_terms" : project_terms,
                "project_type" : project_type,
                "project_wage_determination" : project_wage_determination,
                "project_lead_agency" : project_lead_agency,
                "rfi_due_date" : rfi_due_date,
                "rfi_days_type" : rfi_days_type,
                "submittal_days_type" : submittal_days_type,
                "submittal_due_date" : submittal_due_date,
                "change_order_due_date" : change_order_due_date,
                "change_order_days_type" : change_order_days_type
                // "project_description" : project_description
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
        // console.log('faozan');
        .done(function(data, textStatus, jqXHR) {
            $('.loading-submit').hide();
            $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
            }, 'fast')
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New project added successfully!</div></div>';
                $("#alert_message").show();
                $("#alert_message").html(html);
                $("#project_number").removeAttr('value');
                $("#project_name").removeAttr('value');
                $("#project_wage_determination").removeAttr('value');
                $('#project_terms').attr('checked', false);
                $("#project_lead_agency").removeAttr('value');

                $("#rfi_due_date").removeAttr('value');
                $("#submittal_due_date").removeAttr('value');
                $("#change_order_due_date").removeAttr('value');
                //$("#project_location").removeAttr('value');
                $("#project_description").removeAttr('value');
                $(".select2-search-choice-close").trigger("click");
                $("#project_type option:selected").removeAttr("selected");
                setTimeout(function()
                {
                    $("#alert_message").hide();
                },5000)
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            $('.loading-submit').hide();
            var responseText, html;
            responseText = JSON.parse(jqXHR.responseText);
            console.log(responseText.data);
            console.log(responseText.data.p_number[0]);
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").show();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            if(responseText.data.p_number != null){
                if(responseText.data.p_number == "The p number has already been taken."){
                   html += '<li>Project number has already been taken.</li>'; 
                }
                else {
            	   html += '<li>Project number field is invalid.</li>'; 
                }
            }
            if(responseText.data.p_name != null){
                html += '<li>Project name field is invalid.</li>';
            }
            if(responseText.data.p_type != null){
                html += '<li>Improvement type field is invalid.</li>';
            }
            if(responseText.data.p_location != null){
                html += '<li>Project location field is invalid.</li>';
            }
            if(responseText.data.p_description != null){
                html += '<li>Project description field is invalid.</li>';
            }
            if(responseText.data.p_wage_determination != null){
                html += '<li>Project wage determination field is invalid.</li>';
            }
            if(responseText.data.p_lead_agency != null){
                html += '<li>Project lead agency field is invalid.</li>';
            }
            if(responseText.data.p_term != null){
                html += '<li>Project terms field is invalid.</li>';
            }
            if(responseText.data.rfi_due_date != null){
                html += '<li>RFI notification & past due configuration number is invalid.</li>';
            }
            if(responseText.data.submittal_due_date != null){
                html += '<li>Submittal notification & past due configuration number is invalid.</li>';
            }
            if(responseText.data.change_order_due_date != null){
                html += '<li>Change Order notification & past due configuration number is invalid.</li>';
            }
            html += '</ul></div>';
            $("#alert_message").html(html);
            setTimeout(function(){
                $("#alert_message").hide();
            },5000)
        })
    });

    function get_improvement_project()
    {
        $("#project_type").empty();
        jQuery.ajax({
            url: baseUrl + "improvement-type",
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
                $("#project_type").append(
                    '<option value="">Select Improvement Types</option>'
                )
                jQuery.each(data.data, function( i, val ) {
                    if(val.pt_status == 'active'){
                        $("#project_type").append(
                            '<option value="'+val.pt_id+'">'+val.pt_name+'</option>'
                        );
                        
                        //$(".select2-choices").append('<li class="select2-search-choice"><div>'+val.pt_name+'</div><a href="#" onclick="return false;" class="select2-search-choice-close" tabindex="-1"></a></li>');
                    }else {

                    }
                });
                $(".loading_data").remove();
                $("#s2id_project_type").show();

            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var response = jqXHR.responseJSON.code;
                console.log(response);
                if(response == 403){
                    window.location.href = baseUrl + "403";
                }
                else if(response == 404){
                    console.log('Improvement type 404');
                    alert("You can't add project, first add improvement type!");
                    // window.location.href = baseUrl + "404";
                }
                else {
                    window.location.href = baseUrl + "500";
                }
            });
}
$('#add_improvement_type').click(function(e)
{
    var imp_type = $('#imp_type').val();
    var token = localStorage.getItem('u_token');
    jQuery.ajax({
        url: baseUrl + "improvement-type/add",
        type: "POST",
        data: {
            "improvement_type" : imp_type,
        },
        headers: {
            "x-access-token": token
        },
        contentType: "application/x-www-form-urlencoded",
        cache: false
    })
        .done(function(data, textStatus, jqXHR) {
            console.log(data.description);
            $("#alert_message").show();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">'+data.description+'</div></div>';
            $("#alert_message").html(html);
            setTimeout(function(){
                $("#alert_message").hide();
            },5000)
            $("#imp_type").removeAttr('value');
            get_improvement_project();
            $('#diplay').hide();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var responseText, html;
            responseText = JSON.parse(jqXHR.responseText);
            // console.log(responseText.data.username);
            $("#alert_message").show();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            if(responseText.data.improvement_type != null){
                html += '<li>'+responseText.data.improvement_type+'</li>';
            }
            html += '</ul></div>';
            $("#alert_message").html(html);
            setTimeout(function(){
                $("#alert_message").hide();
            },5000)
        })
    e.preventDefault();
})

$("#add_more").click(function(e){
    $('#diplay').show();
    e.preventDefault();
})
$("#cancel_improvement_type").click(function(e){
    $("#imp_type").removeAttr('value');
    $('#diplay').hide();
    e.preventDefault();
})