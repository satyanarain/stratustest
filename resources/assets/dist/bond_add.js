$(document).ready(function() {
    // Get login user profile data
    $(".select2").hide();
    $(".currency_symbol").hide();
    $('#upload_doc_id').removeAttr('value');
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3]; // projects
    console.log(project_id);

    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("bond_add", check_user_access );
    console.log(check_permission);
    if(check_permission < 1){
        window.location.href = baseUrl + "403";
    }
    else {
        console.log('Yes Permission');
        $('.body-content .wrapper').show();
    }

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
        // console.log(data.data);
        // Foreach Loop
        $("#company_name").append(
            '<option value="">Select Company</option>'
        )
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
        if(add_company_on_fly_permission>0 || role=="owner"){
        $("#company_name").append(
            '<option style="font-weight:bold;">Add New Company</option>'
        )}
        // $( "h2" ).appendTo( $( ".container" ) );

        // $(".loading_data").remove();
        $(".select2").show();
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
            console.log('Company name 404');
            // window.location.href = baseUrl + "404";
        }
        else {
            window.location.href = baseUrl + "500";
        }
    });

    jQuery.ajax({
    url: baseUrl + "currency",
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
//        $(".currency_symbol").append(
//            '<option value="">Select Currency</option>'
//        )
        jQuery.each(data.data, function( i, val ) {
            if(val.cur_status == 'active'){
                $(".currency_symbol").append(
                    '<option value="'+val.cur_id+'">'+val.cur_symbol+'</option>'
                )
            }else {

            }
        });
        // $( "h2" ).appendTo( $( ".container" ) );

        $(".loading_data").remove();
        $(".currency_symbol").show();
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        console.log(response);
        if(response == 403){
        console.log('Currency 403');
            // window.location.href = baseUrl + "403";
        }
        else if(response == 404){
            console.log('Currency 404');
            // window.location.href = baseUrl + "404";
        }
        else {
            window.location.href = baseUrl + "500";
        }
    });

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

    // Bid Total Amount
    jQuery.ajax({
    url: baseUrl+"/"+project_id+"/bid-items-total",
        type: "GET",
        headers: {
          "Content-Type": "application/json",
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        $("#performance_bond_amount").val(data.data[0].total_amount);
        $("#payment_bond_amount").val(data.data[0].total_amount);
        $("#maintenance_bond_amount").val(data.data[0].total_amount);
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        if(response == 403){
            // window.location.href = baseUrl + "403";
            console.log("Bid total 403");
        }
        else if(response == 404){
             console.log("404");
        //  window.location.href = baseUrl + "404";
        }
        else {
            // console.log("500");
            window.location.href = baseUrl + "500";
        }
    })
    $('#company_name').change(function(){
        var company = $(this).val();
        if(company=="Add New Company")
        {
            $('#add-company').modal('show');
            $('#add-company').on('shown.bs.modal',function(){
                google.maps.event.trigger(map, "resize");
                //google.maps.event.trigger(map1, "resize");
              });
        }
    })
});


    $('#add_bond_form').click(function(e) {
      $('.loading-submit').show();
        e.preventDefault();

        if($("#performance_bond_yes").is(':checked') || $("#payment_bond_yes").is(':checked') || $("#maintenance_bond_yes").is(':checked')){
            var company_name                        = $('#company_name').val();
            var performance_bond_cur_symbol         = $('#performance_bond_cur_symbol').val();
            var performance_bond_amount             = $('#performance_bond_amount').val();
            var performance_bond_date               = $('#performance_bond_date').val();
            var performance_bond_number             = $('#performance_bond_number').val();
            var performance_bond_sign               = $("input[name='upload_performance']:checked").val();
            var performance_bond_path               = $('#upload_doc_id_general').val();

            var payment_bond_cur_symbol             = $('#payment_bond_cur_symbol').val();
            var payment_bond_amount                 = $('#payment_bond_amount').val();
            var payment_bond_date                   = $('#payment_bond_date').val();
            var payment_bond_number                 = $('#payment_bond_number'). val();
            var payment_bond_sign                   = $("input[name='upload_payment']:checked"). val();
            var payment_bond_path                   = $('#upload_doc_id_work').val();

            var maintenance_bond_cur_symbol         = $('#maintenance_bond_cur_symbol').val();
            var maintenance_bond_amount             = $('#maintenance_bond_amount').val();
            var maintenance_bond_date               = $('#maintenance_bond_date').val();
            var maintenance_bond_number             = $('#maintenance_bond_number'). val();
            var maintenance_bond_sign               = $("input[name='upload_maintenance']:checked"). val();
            var maintenance_bond_path               = $('#upload_doc_id_auto').val();

            var project_id                          = $('#upload_project_id').val();
            var token                               = localStorage.getItem('u_token');


            // Validation Certificate
            var html;
            var is_error = false;
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';

            if(company_name == ''){
                html += '<li>Contractor name field is invalid.</li>';
                is_error = true;
            }
            // Validation Performance Bond
            //alert(document.getElementById("upload").value)
                    //$("#upload").is(':checked')
            if($("#performance_bond_yes").is(':checked')){
                if(performance_bond_amount == ''){
                    html += '<li>Performance bond amount field is invalid.</li>';
                    is_error = true;
                }if(performance_bond_amount == ''){
                    html += '<li>Performance bond amount field is invalid.</li>';
                    is_error = true;
                }
                if(performance_bond_date == ''){
                    html += '<li>Performance bond date field is invalid.</li>';
                    is_error = true;
                }
                if(performance_bond_number == ''){
                    html += '<li>Performance bond number field is invalid.</li>';
                    is_error = true;
                }
                var upload_performance = $('input[name=upload_performance]:checked').val();
                //alert(upload_performance);
                if(upload_performance==undefined)
                {
                    html += '<li>Confirm all required signature fields signed by authorized representatives and notarized.</li>';
                    is_error = true;
                }else{
                    if(performance_bond_path == '' && upload_performance=="yes"){
                        html += '<li>Performance bond document field is invalid.</li>';
                        is_error = true;
                    }
                }
                
            }
            if($("#payment_bond_yes").is(':checked')){
                if(payment_bond_amount == ''){
                    html += '<li>Payment bond amount field is invalid.</li>';
                    is_error = true;
                }
                if(payment_bond_date == ''){
                    html += '<li>Payment bond date field is invalid.</li>';
                    is_error = true;
                }
                if(payment_bond_number == ''){
                    html += '<li>Payment bond number field is invalid.</li>';
                    is_error = true;
                }
                if(payment_bond_path == ''){
                    html += '<li>Payment bond document field is invalid.</li>';
                    is_error = true;
                }
            }
            if($("#maintenance_bond_yes").is(':checked')){
                if(maintenance_bond_amount == ''){
                    html += '<li>Maintenance bond amount field is invalid.</li>';
                    is_error = true;
                }
                if(maintenance_bond_date == ''){
                    html += '<li>Maintenance bond date field is invalid.</li>';
                    is_error = true;
                }
                if(maintenance_bond_number == ''){
                    html += '<li>Maintenance bond number field is invalid.</li>';
                    is_error = true;
                }
                if(maintenance_bond_path == ''){
                    html += '<li>Maintenance bond document field is invalid.</li>';
                    is_error = true;
                }
            }

            html += '</ul></div>';
            if(is_error == true){
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

            console.log(company_name);
            jQuery.ajax({
                url: baseUrl + "bond/add",
                type: "POST",
                data: {
                    "firm_name"                     : company_name,
                    "performance_bond_currency"     : performance_bond_cur_symbol,
                    "performance_bond_amount"       : performance_bond_amount,
                    "performance_bond_date"         : performance_bond_date,
                    "performance_bond_number"       : performance_bond_number,
                    "performance_bond_sign"         : performance_bond_sign,
                    "performance_bond_path"         : performance_bond_path,

                    "payment_bond_currency"         : payment_bond_cur_symbol,
                    "payment_bond_amount"           : payment_bond_amount,
                    "payment_bond_date"             : payment_bond_date,
                    "payment_bond_number"           : payment_bond_number,
                    "payment_bond_sign"             : maintenance_bond_sign,
                    "payment_bond_path"             : payment_bond_path,

                    "maintenance_bond_currency"     : maintenance_bond_cur_symbol,
                    "maintenance_bond_amount"       : maintenance_bond_amount,
                    "maintenance_bond_date"         : maintenance_bond_date,
                    "maintenance_bond_number"       : maintenance_bond_number,
                    "maintenance_bond_sign"         : maintenance_bond_sign,
                    "maintenance_bond_path"         : maintenance_bond_path,
                    "project_id"                    : project_id
                },
                headers: {
                  "x-access-token": token
                },
                contentType: "application/x-www-form-urlencoded",
                cache: false
            })
            .done(function(data, textStatus, jqXHR) {
              $('html, body').animate({
                        scrollTop: $(".page-head").offset().top
                    }, 'slow')
                console.log(data.description);
                // html = '<div class="alert alert-block alert-success fade in">'+data.description+'</div>';
                // $("#alert_message").html(html);
                    $('#performance_bond_number').removeAttr("value");
                    $('#payment_bond_number').removeAttr("value");
                    $('#maintenance_bond_number').removeAttr("value");
                    $('input:checkbox').removeAttr('checked');
                    $('.upload_doc_panel_payment').css("display", "none");
                    $('.upload_doc_panel_maintenance').css("display", "none");
                    $('.upload_doc_panel_performance').css("display", "none");
                    $('#standard_doc_id').removeAttr('value');
                    $('#performance_bond_date').removeAttr('value');
                    $("#company_name").val('');
                    $('#upload_doc_id_work').removeAttr('value');
                    $('#upload_doc_id_auto').removeAttr('value');
                    $('#payment_bond_date').removeAttr('value');
                    $('#maintenance_bond_date').removeAttr('value');
                    $('#upload_doc_id_general').removeAttr('value');
                    $(".remove_file_drop").trigger("click");
                    $(".first_button").text('Save Another');
                    $("#alert_message").show();
                    $('.loading-submit').hide();
                    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New bond added successfully!</div></div>';
                    $("#alert_message").html(html);
                    setTimeout(function()
                    {
                        $("#alert_message").hide();

                    },6000)
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                    console.log("HTTP Request Failed");
                    var responseText, html;
                    responseText = JSON.parse(jqXHR.responseText);
                    console.log(responseText.data);
                    $('html, body').animate({
                        scrollTop: $(".page-head").offset().top
                    }, 'fast')

                    $("#alert_message").show();
                    $('.loading-submit').hide();

                    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                    if(responseText.data.firm_name != null){
                        html += '<li>The Contractor field is required.</li>';
                    }
                    if(responseText.data.project_id != null){
                        html += '<li>The project id field is required.</li>';
                    }
                    html += '</ul></div>';
                    $("#alert_message").html(html);
                    setTimeout(function(){
                        $("#alert_message").hide();
                    },6000)

            })
        }
        else {


            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            html += '<li>Please check atleast one bond type</li>';
            html += '</ul></div>';
            $("#alert_message").html(html);
            setTimeout(function(){
                $("#alert_message").hide();
            },6000);
        }
    });
