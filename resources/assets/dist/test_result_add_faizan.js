$(document).ready(function() {
    // Get login user profile data
   $('.standard_doc_id').removeAttr('value');
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3]; // projects
    console.log(project_id);

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
        jQuery.each(data.data, function( i, val ) {
            if(val.f_status == 'active'){
                $(".company_name").append(
                    '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                )
            }else {

            }
        });
        var add_company_on_fly_permission = jQuery.inArray("project_add_company_on_fly", check_user_access );
        console.log(add_company_on_fly_permission+'company_fly');
        if(add_company_on_fly_permission>0 || role=="owner"){
        $(".company_name").append(
            '<option style="font-weight:bold;">Add New Company</option>'
        )}
        $(".loading_data").remove();
    })
    $('.company_name').change(function(){
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

$("#my-awesome-dropzone").click(function() {
  $("#upload_type").val("certificate_general_libility");
});
$("#my-awesome-dropzone1").click(function() {
  $("#upload_type").val("certificate_work_compensation");
});
$("#my-awesome-dropzone2").click(function() {
  $("#upload_type").val("certificate_auto_liability");
});

$('#compaction_yes').change(function() {
    if($(this).is(":checked")) {
        $('.upload_doc_compaction').css("display", "block");
    }
    else {
       $('.upload_doc_compaction').css("display", "none");
    }
});
$('#ppc_yes').change(function() {
    if($(this).is(":checked")) {
        $('.upload_doc_panel_payment').css("display", "block");
    }
    else {
       $('.upload_doc_panel_payment').css("display", "none");
    }
});
$('#etc_yes').change(function() {
    if($(this).is(":checked")) {
        $('.upload_doc_panel_maintenance').css("display", "block");
    }
    else {
       $('.upload_doc_panel_maintenance').css("display", "none");
    }
});

    $('.add_test_result_form').click(function(e) {
      $('.loading-submit').show();
        e.preventDefault();
        if($("#compaction_yes").is(':checked') || $("#ppc_yes").is(':checked') || $("#etc_yes").is(':checked')){
            // var compaction_firm_name            = $('#compaction_firm_name').val();
            // var compaction_date                 = $('#compaction_date').val();
            // var compaction_test                 = $('#compaction_test').val();
            // var compaction_location             = $('#compaction_location').val();
            // var compaction_result               = $("input[name='compaction_result']:checked").val();
            // var compaction_doc_id               = $('#upload_doc_id_general').val();
            // var pcc_firm_name                   = $('#pcc_firm').val();
            // var pcc_date                        = $('#pcc_date').val();
            // var pcc_test                        = $('#pcc_test').val();
            // var pcc_location                    = $('#pcc_description').val();
            // var pcc_result                      = $("input[name='pcc_result']:checked").val();
            // var pcc_doc_id                      = $('#upload_doc_id_work').val();
            // var etc_test_name                   = $('#etc_test_name').val();
            // var etc_firm_name                   = $('#etc_firm').val();
            // var etc_date                        = $('#etc_date').val();
            // var etc_test                        = $('#etc_test').val();
            // var etc_location                    = $('#etc_location').val();
            // var etc_result                      = $("input[name='etc_result']:checked").val();
            // var etc_doc_id                      = $('#upload_doc_id_auto').val();
            // var project_id                      = $('#upload_project_id').val();
            // var token                           = localStorage.getItem('u_token');

            var project_id                      = $('#upload_project_id').val();
            var token                           = localStorage.getItem('u_token');

            // Validation Certificate
            var is_error_name = false;
            var html;
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            if($("#compaction_yes").is(':checked')){

                var compaction_firm_name            = $('#compaction_firm_name').val();
                var compaction_date                 = $('#compaction_date').val();
                var compaction_test                 = $('#compaction_test').val();
                var compaction_location             = $('#compaction_location').val();
                var compaction_result               = $("input[name='compaction_result']:checked").val();
                var compaction_doc_id               = $('#upload_doc_id_general').val();

                if(compaction_firm_name == ''){
                    html += '<li>Compaction firm name is invalid.</li>';
                    is_error_name = true;
                }
                if(compaction_date == ''){
                    html += '<li>Compaction date is invalid.</li>';
                    is_error_name = true;
                }
                if(compaction_test == ''){
                    html += '<li>Compaction test number is invalid.</li>';
                    is_error_name = true;
                }
                if(compaction_location == ''){
                    html += '<li>Compaction location is invalid.</li>';
                    is_error_name = true;
                }
                if(compaction_result == null){
                    html += '<li>Compaction result is invalid.</li>';
                    is_error_name = true;
                }
                if(compaction_doc_id == ''){
                    html += '<li>Compaction Test / Report is invalid.</li>';
                    is_error_name = true;
                }
            }

            if($("#ppc_yes").is(':checked')){

                var pcc_firm_name                   = $('#pcc_firm').val();
                var pcc_date                        = $('#pcc_date').val();
                var pcc_test                        = $('#pcc_test').val();
                var pcc_location                    = $('#pcc_description').val();
                var pcc_result                      = $("input[name='pcc_result']:checked").val();
                var pcc_doc_id                      = $('#upload_doc_id_work').val();

                if(pcc_firm_name == ''){
                    html += '<li>PCC Strength firm name is invalid.</li>';
                    is_error_name = true;
                }
                if(pcc_date == ''){
                    html += '<li>PCC Strength date is invalid.</li>';
                    is_error_name = true;
                }
                if(pcc_test == ''){
                    html += '<li>PCC Strength test number is invalid.</li>';
                    is_error_name = true;
                }
                if(pcc_location == ''){
                    html += '<li>PCC Strength location is invalid.</li>';
                    is_error_name = true;
                }
                if(pcc_result == null){
                    html += '<li>PCC Strength result is invalid.</li>';
                    is_error_name = true;
                }
                if(pcc_doc_id == ''){
                    html += '<li>PCC Strength Test / Report is invalid.</li>';
                    is_error_name = true;
                }
            }

            if($("#etc_yes").is(':checked')){

                var etc_test_name                   = $('#etc_test_name').val();
                var etc_firm_name                   = $('#etc_firm').val();
                var etc_date                        = $('#etc_date').val();
                var etc_test                        = $('#etc_test').val();
                var etc_location                    = $('#etc_location').val();
                var etc_result                      = $("input[name='etc_result']:checked").val();
                var etc_doc_id                      = $('#upload_doc_id_auto').val();

                if(etc_test_name == ''){
                    html += '<li>Other test name is invalid.</li>';
                    is_error_name = true;
                }
                if(etc_firm_name == ''){
                    html += '<li>Other firm name is invalid.</li>';
                    is_error_name = true;
                }
                if(etc_date == ''){
                    html += '<li>Other date is invalid.</li>';
                    is_error_name = true;
                }
                if(etc_test == ''){
                    html += '<li>Other test number is invalid.</li>';
                    is_error_name = true;
                }
                if(etc_location == ''){
                    html += '<li>Other location is invalid.</li>';
                    is_error_name = true;
                }
                if(etc_result == null){
                    html += '<li>Other result is invalid.</li>';
                    is_error_name = true;
                }
                if(etc_doc_id == ''){
                    html += '<li>Other Test / Report is invalid.</li>';
                    is_error_name = true;
                }
            }
            html += '</ul></div>';
            if(is_error_name == true){
                $("#alert_message").html(html);
                $("#alert_message").show();
                $('.loading-submit').hide();
                $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
                }, 'fast')
                setTimeout(function(){
                    $("#alert_message").hide()
                    return false;
                },3000)
                return;
            }
            // console.log(compaction_firm_name);
            // console.log(compaction_date);
            // console.log(compaction_test);
            // console.log(compaction_location);
            // console.log(compaction_result);
            // console.log(compaction_doc_id);
            // console.log(pcc_firm_name);
            // console.log(pcc_date);
            // console.log(pcc_test);
            // console.log(pcc_location);
            // console.log(pcc_result);
            // console.log(pcc_doc_id);
            // console.log(etc_firm_name);
            // console.log(etc_date);
            // console.log(etc_test);
            // console.log(etc_location);
            // console.log(etc_result);
            // console.log(etc_doc_id);
            // return false;
            console.log(baseUrl + "test_result/add");
            jQuery.ajax({
                url: baseUrl + "test_result/add",
                type: "POST",
                data: {
                    "compaction_firm"       : compaction_firm_name,
                    "compaction_date"       : compaction_date,
                    "compaction_test_num"   : compaction_test,
                    "compaction_location"   : compaction_location,
                    "compaction_result"     : compaction_result,
                    "compaction_doc_id"     : compaction_doc_id,
                    "strength_firm"         : pcc_firm_name,
                    "strength_date"         : pcc_date,
                    "strength_test_num"     : pcc_test,
                    "strength_location"     : pcc_location,
                    "strength_result"       : pcc_result,
                    "strength_doc_id"       : pcc_doc_id,
                    "etc_test_name"         : etc_test_name,
                    "etc_firm"              : etc_firm_name,
                    "etc_date"              : etc_date,
                    "etc_test_num"          : etc_test,
                    "etc_location"          : etc_location,
                    "etc_result"            : etc_result,
                    "etc_doc_id"            : etc_doc_id,
                    "project_id"            : project_id
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
                $('input:checkbox').removeAttr('checked');
                $('#compaction_firm_name').prop('selectedIndex',0);
                $(".remove_file_drop").trigger("click");
                $('#compaction_date').removeAttr("value");
                $('#compaction_test').removeAttr("value");
                $('#compaction_location').removeAttr("value");
                $('input[name="compaction_result"]').attr('checked', false);
                $('#pcc_firm').prop('selectedIndex',0);
                $('#pcc_date').removeAttr("value");
                $('#pcc_test').removeAttr("value");
                $('#pcc_location').removeAttr("value");
                $('#pcc_description').removeAttr("value");
                $('input[name="pcc_result"]').attr('checked', false);
                $('#etc_date').removeAttr("value");
                $('#etc_test').removeAttr("value");
                $('#etc_test_name').removeAttr("value");
                $('#etc_firm').removeAttr("value");
                $('#etc_location').removeAttr("value");
                $('input[name="etc_result"]').attr('checked', false);
                $('.upload_doc_compaction').css("display", "none");
                $('.upload_doc_panel_payment').css("display", "none");
                $('.upload_doc_panel_maintenance').css("display", "none");
                $('#upload_doc_id_work').removeAttr('value');
                $('#upload_doc_id_auto').removeAttr('value');
                $('#upload_doc_id_general').removeAttr('value');
                $(".first_button").text('Save Another');
                $("#alert_message").show();
                $('.loading-submit').hide();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New test result added successfully!</div></div>';
                $("#alert_message").html(html);
                setTimeout(function()
                {
                    $("#alert_message").hide();

                },6000)
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                console.log(errorThrown);
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(responseText.data);
                $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
                }, 'fast')
                $("#alert_message").show();
                $('.loading-submit').hide();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
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
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            html += '<li>Please check atleast one test result type</li>';
            html += '</ul></div>';
            $("#alert_message").html(html);
            setTimeout(function(){
                $("#alert_message").hide();
            },6000);
        }
    });
