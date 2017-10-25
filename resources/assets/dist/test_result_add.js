$(document).ready(function() {
    alert('faizan');
    // Get login user profile data
   $('.standard_doc_id').removeAttr('value');
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3]; // projects
    console.log(project_id);
    console.log('Faizan');

    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("test_result_add", check_user_access );
    console.log(check_permission);
    if(check_permission < 1){
        window.location.href = baseUrl + "403";
    }
    else {
        console.log('Yes Permission');
        $('.body-content .wrapper').show();
    }

    jQuery.ajax({
    url: baseUrl + "firm-name",
        type: "GET",
        headers: {
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
        .done(function(data, textStatus, jqXHR) {
        console.log(data.data);
        alert('faizan1');
        // Foreach Loop
        jQuery.each(data.data, function( i, val ) {
            if(val.f_status == 'active'){
                $("#performance_bond_cur_symbol").append(
                    '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                )
            }else {

            }
        });
        // $( "h2" ).appendTo( $( ".container" ) );

        // $(".loading_data").remove();
        $(".select2").show();
    })


    $('#add_bond_form').click(function(e) {

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
                    $('#upload_doc_id_work').removeAttr('value');
                    $('#upload_doc_id_auto').removeAttr('value');
                    $('#upload_doc_id_general').removeAttr('value');
                    $(".remove_file_drop").trigger("click");
                    $("#alert_message").show();
                    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Added successfully!</div></div>';
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
            $("#alert_message").fadeIn(1000);
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            html += '<li>Please check atleast one bond type</li>';
            html += '</ul></div>';
            $("#alert_message").html(html);
            setTimeout(function(){
                $("#alert_message").hide();
            },6000);
        }
    });
});
