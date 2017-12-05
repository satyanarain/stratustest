$(document).ready(function() {
    // Get login user profile data
    $(".select2").hide();
    $(".currency_symbol").hide();
    $('#upload_doc_id').removeAttr('value');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3]; // projects
    console.log(project_id);
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');

    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("certificate_add", check_user_access );
    console.log(check_permission);
    if(check_permission < 1){
        window.location.href = baseUrl + "403";
    }
    else {
        console.log('Yes Permission');
        $('.body-content .wrapper').show();
    }

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
        // console.log(agency_id);
        $("#company_name").val(parseInt(agency_id));
        $(".loading_data").hide();
        // Select Company Detail for PDF
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
            $('#contractor_name').text(data.data.f_name);
            $('#pdf_gen_contractor_name').text(data.data.f_name);
            $('#company_name').val(data.data.f_id);
        })
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        console.log(response);
        if(response == 403){
            window.location.href = baseUrl + "403";
        }
        else if(response == 404){
           $(".loading_data").hide();
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


        $(".currency_symbol").show();
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        console.log(response);
        if(response == 403){
            // window.location.href = baseUrl + "403";
        }
        else if(response == 404){
            // window.location.href = baseUrl + "404";
        }
        else {
            // window.location.href = baseUrl + "500";
        }
    });

    // Select Project Name
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
        // console.log(data.data.p_name);
        var project_name = data.data.p_name;
        $('#pdf_gen_project_name').text(project_name);
    })


    // Get Project Currency
    jQuery.ajax({
    url: baseUrl+project_id+"/project_setting_get/project_currency",
        type: "GET",
        headers: {
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        // console.log(data.data.pset_meta_value);
        var project_currency_id = data.data.pset_meta_value;
        jQuery.ajax({
        url: baseUrl + "currency/"+project_currency_id,
            type: "GET",
            headers: {
              "Content-Type": "application/json",
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            console.log(data.data.cur_id);
            //$('.currency_symbol').val(data.data.cur_id);
        })
    })
});

// // Add Multiple Certificate
// setTimeout(function(){
//     $('body').delegate( '#add_more_cert', 'click', function () {
//         alert('faizan');
//         var randum_number = Math.floor(Math.random()*(99-11+1)+11);
//         var html = '<div class="custom_certificate_detail">'+
//         '<div class="col-sm-6">'+
//             '<div class="form-group">'+
//                 '<label for="name_of_report" style="padding-top: 15px;">Certificate Liability Name</label>'+
//                 '<input type="text" class="form-control" name="custom_certificate_name[]" id="custom_certificate_name">'+
//                 '<div class="clearfix"></div>'+
//                 '<label for="name_of_report" style="padding-top: 15px;">Certificate Liability Limit</label>'+
//                 '<div class="clearfix"></div>'+
//                 '<div class="col-xs-3" style="padding: 0px;">'+
//                     '<div class="loading_data" style="text-align: center;">'+
//                        '<img src="'+baseUrl+'/resources/assets/img/loading_bar.gif" alt=""/>'+
//                     '</div>'+
//                     '<select class="form-control currency_symbol_more" name="custom_certificate_cur_symbol[]" id="custom_certificate_cur_symbol">'+
//                     '</select>'+
//                 '</div>'+
//                 '<div class="col-xs-9">'+
//                     '<input type="text" class="form-control" name="custom_certificate_currency[]" id="custom_certificate_currency" onkeypress="return isNumber(event)">'+
//                 '</div>'+
//             '</div>'+
//             '<div class="clearfix"></div>'+
//             '<div class="form-group">'+
//                 '<label>Expiration Date</label>'+
//                 '<div data-date-viewmode="years" data-date-format="yyyy-mm-dd" data-date=""  class="input-append date dpYears">'+
//                     '<input type="text" readonly="" value="" size="16" class="form-control"  id="umbrella_liability_date">'+
//                       '<span class="input-group-btn add-on">'+
//                         '<button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>'+
//                       '</span>'+
//                 '</div>'+
//                 // '<input type="date" value="" class="form-control" name="custom_certificate_date[]">'+
//             '</div>'+
//             '<div class="clearfix"></div>'+
//         '</div>'+
//         '<div class="col-sm-6">'+
//             '<div class="col-sm-12">'+
//                 '<a class="btn btn-danger btn-xs tooltips remove_current_custom_certificate" data-placement="top" data-toggle="tooltip" data-original-title="Remove" style="float: right; margin-top: 15px;"><i class="fa fa-times"></i></a>'+
//             '</div>'+
//             '<section class="panel upload_doc_panel" id="upload_div" style="margin-top: 50px;">'+
//                 '<div class="panel-body" style="padding: 0px;">'+
//                     '<form id="my-awesome-dropzone'+randum_number+'" action="" class="dropzone dz-clickable my-awesome-dropzone4">'+
//                         '<input type="text" name="document_path" value="/uploads/certificate/">'+
//                         // '<div class="dz-default dz-message"><span>Drop files here to upload</span></div>'+
//                     '</form>'+
//                     '<input type="text" name="upload_doc_id[]" class="upload_doc_id" id="upload_doc_id_'+randum_number+'" value="">'+
//                     '<input type="text" name="upload_type" id="upload_type" value="multiple_upload">'+
//                 '</div>'+
//             '</section>'+
//         '</div>'+
//     '</div><div class="clearfix"></div>';
//             $(".custom_certificate_all").append(html);
//             $('.custom_certificate_detail:last .upload_doc_panel .panel-body form').dropzone({url: baseUrl+'document/uploadFiles'});
//             jQuery.ajax({
//             url: baseUrl + "currency",
//                 type: "GET",
//                 headers: {
//                   "x-access-token": token
//                 },
//                 contentType: "application/json",
//                 cache: false
//             })
//             .done(function(data, textStatus, jqXHR) {
//                 jQuery.each(data.data, function( i, val ) {
//                     if(val.cur_status == 'active'){
//                         $(".currency_symbol_more").append(
//                             '<option value="'+val.cur_id+'">'+val.cur_symbol+'</option>'
//                         )
//                     }else {

//                     }
//                 });
//                 $(".loading_data").remove();
//                 $(".currency_symbol_more").show();
//             })
//             return;
//     });
// }, 5000);

    $('#add_certificate_form').click(function(e) {
      $('.loading-submit').show();
        e.preventDefault();


        var check_box = document.getElementById("general_liability_included").checked;
        if(check_box == true){
            var general_liability_included = "yes";
        }
        else {
            var general_liability_included = 'no';
        }
        console.log(general_liability_included);

        var check_box = document.getElementById("req_minimum_general").checked;
        if(check_box == true){
            var req_minimum_general = "yes";
        }
        else {
            var req_minimum_general = 'no';
        }
        console.log(req_minimum_general);

        var check_box = document.getElementById("req_minimum_work").checked;
        if(check_box == true){
            var req_minimum_work = "yes";
        }
        else {
            var req_minimum_work = 'no';
        }
        var check_box = document.getElementById("upload_work_above").checked;
        if(check_box == true){
            var upload_work_above = "yes";
        }
        else {
            var upload_work_above = 'no';
        }
        var check_box = document.getElementById("upload_work").checked;
        if(check_box == true){
            var upload_work = "yes";
        }
        else {
            var upload_work = 'no';
        }


        var check_box = document.getElementById("auto_req_minimum").checked;
        if(check_box == true){
            var auto_req_minimum = "yes";
        }
        else {
            var auto_req_minimum = 'no';
        }
        // console.log(auto_req_minimum);
        var check_box = document.getElementById("upload_auto_above").checked;
        if(check_box == true){
            var upload_auto_above = "yes";
        }
        else {
            var upload_auto_above = 'no';
        }
        // console.log(upload_auto_above);
        var check_box = document.getElementById("upload_auto").checked;
        if(check_box == true){
            var upload_auto = "yes";
        }
        else {
            var upload_auto = 'no';
        }
        // console.log(upload_auto);


        var check_box = document.getElementById("req_minimum_umbrella").checked;
        if(check_box == true){
            var req_minimum_umbrella = "yes";
        }
        else {
            var req_minimum_umbrella = 'no';
        }
        console.log(req_minimum_umbrella);
        var check_box = document.getElementById("upload_umbrella_above").checked;
        if(check_box == true){
            var upload_umbrella_above = "yes";
        }
        else {
            var upload_umbrella_above = 'no';
        }
        console.log(upload_umbrella_above);
        var check_box = document.getElementById("upload_umbrella").checked;
        if(check_box == true){
            var upload_umbrella = "yes";
        }
        else {
            var upload_umbrella = 'no';
        }
        console.log(upload_umbrella);

        var company_name                        = $('#company_name').val();
        var general_liability_cur_symbol        = $('#general_liability_cur_symbol').val();
        var general_liability_amount            = $('#general_liability_amount').val();
        var general_liability_date              = $('#general_liability_date').val();
        var general_liability_req_min           = req_minimum_general;
        var general_liability_doc_path          = $('#upload_doc_id_general').val();

        var works_compensation_cur_symbol       = $('#works_compensation_cur_symbol').val();
        var works_compensation_currency         = $('#works_compensation_currency').val();
        var works_compensation_date             = $('#works_compensation_date').val();
        var works_compensation_req_minimum      = req_minimum_work;
        var works_compensation_upload_above     = upload_work_above;
        var works_compensation_upload_work      = upload_work;
        var works_compensation_doc_path         = $('#upload_doc_id_work').val();

        var auto_compensation_cur_symbol        = $('#auto_compensation_cur_symbol').val();
        var auto_compensation_currency          = $('#auto_compensation_currency').val();
        var auto_compensation_date              = $('#auto_compensation_date').val();
        var auto_compensation_req_minimum       = auto_req_minimum;
        var auto_compensation_upload_above      = upload_auto_above;
        var auto_compensation_upload_auto       = upload_auto;
        var auto_compensation_doc_path          = $('#upload_doc_id_auto').val();

        var umbrella_cur_symbol                 = $('#umbrella_liability_cur_symbol').val();
        var umbrella_currency                   = $('#umbrella_liability_currency').val();
        var umbrella_date                       = $('#umbrella_liability_date').val();
        var umbrella_req_minimum                = req_minimum_umbrella;
        var umbrella_upload_above               = upload_umbrella_above;
        var umbrella_upload_auto                = upload_umbrella;
        var umbrella_doc_path                   = $('#upload_doc_id_umbrella').val();

        var project_id                          = $('#upload_project_id').val();
        var token                               = localStorage.getItem('u_token');

            // ADD VARIABLE IN PDF FILE OPEN
            $('#pdf_gen_general_date').text(general_liability_date);
            jQuery.ajax({
            url: baseUrl + "currency/"+general_liability_cur_symbol,
                type: "GET",
                headers: {
                  "Content-Type": "application/json",
                  "x-access-token": token
                },
                contentType: "application/json",
                cache: false
            })
            .done(function(data, textStatus, jqXHR) {
               $('#pdf_gen_general_limit').text(data.data.cur_symbol+' '+ ReplaceNumberWithCommas(general_liability_amount));
            })

            $('#pdf_gen_auto_date').text(auto_compensation_date);
            jQuery.ajax({
            url: baseUrl + "currency/"+works_compensation_cur_symbol,
                type: "GET",
                headers: {
                  "Content-Type": "application/json",
                  "x-access-token": token
                },
                contentType: "application/json",
                cache: false
            })
            .done(function(data, textStatus, jqXHR) {
               $('#pdf_gen_auto_limit').text(data.data.cur_symbol+' '+ ReplaceNumberWithCommas(auto_compensation_currency));
            })

            $('#pdf_gen_work_comp_date').text(works_compensation_date);
            jQuery.ajax({
            url: baseUrl + "currency/"+auto_compensation_cur_symbol,
                type: "GET",
                headers: {
                  "Content-Type": "application/json",
                  "x-access-token": token
                },
                contentType: "application/json",
                cache: false
            })
            .done(function(data, textStatus, jqXHR) {
               $('#pdf_gen_work_comp_limit').text(data.data.cur_symbol+' '+ ReplaceNumberWithCommas(works_compensation_currency));
            })

            $('#pdf_gen_umbrella_date').text(umbrella_date);
            jQuery.ajax({
            url: baseUrl + "currency/"+umbrella_cur_symbol,
                type: "GET",
                headers: {
                  "Content-Type": "application/json",
                  "x-access-token": token
                },
                contentType: "application/json",
                cache: false
            })
            .done(function(data, textStatus, jqXHR) {
               $('#pdf_gen_umbrella_limit').text(data.data.cur_symbol+' '+ ReplaceNumberWithCommas(umbrella_currency));
            })
            // ADD VARIABLE IN PDF FILE CLOSE

        // Validation Certificate
        var html;
        var is_error = false;
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';

        if(general_liability_amount == ''){
            html += '<li>General liability amount is invalid.</li>';
            is_error = true;
        }
        if(general_liability_date == ''){
            html += '<li>General liability expiration date is invalid.</li>';
            is_error = true;
        }

        if(general_liability_included == 'no'){
            if(general_liability_doc_path == ''){
                html += '<li>General liability document is invalid.</li>';
                is_error = true;
            }
        }
        if(works_compensation_currency == ''){
            html += '<li>Workers compensation amount is invalid.</li>';
            is_error = true;
        }
        if(works_compensation_date == ''){
            html += '<li>Workers compensation expiration date is invalid.</li>';
            is_error = true;
        }
        if(works_compensation_upload_above == 'no' && upload_work == 'no'){
            if(works_compensation_doc_path == ''){
                html += '<li>Workers compensation document is invalid.</li>';
                is_error = true;
            }
        }
        if(auto_compensation_currency == ''){
            html += '<li>Auto liability amount is invalid.</li>';
            is_error = true;
        }
        if(auto_compensation_date == ''){
            html += '<li>Auto liability expiration date is invalid.</li>';
            is_error = true;
        }
        console.log(auto_compensation_upload_above);
        if(auto_compensation_upload_above == 'no' && upload_auto == 'no'){
            if(auto_compensation_doc_path == ''){
                html += '<li>Auto liability document is invalid.</li>';
                is_error = true;
            }
        }

        // FOr Custom Certificate
        var is_error_name = false;
        $('input[name^=custom_certificate_name]').each(function(){
            if($(this).val()== '')
            {
                 is_error_name = true;
            }
        });
        if(is_error_name == true){
            html += '<li> Custom certificate name field is invalid </li>';
            is_error = true;
        }


        var is_error_currency = false;
        $('input[name^=custom_certificate_currency]').each(function(){
            if($(this).val()== ''){
                is_error_currency = true;
            }
        });
        if(is_error_currency == true){
            html += '<li> Custom certificate limit field is invalid </li>';
            is_error = true;
        }


        var is_error_date = false;
        $('input[name^=custom_certificate_date]').each(function(){
            if($(this).val()== '')
            {
                is_error_date = true;
            }
        });
        if(is_error_date == true){
            html += '<li> Custom certificate date field is invalid </li>';
            is_error = true;
        }


        // var is_error_upload = false;
        // $('input[name^=upload_doc_id]').each(function(){
        //     if($(this).val()== '')
        //     {
        //         is_error_upload = true;
        //     }
        // });
        // if(is_error_upload == true){
        //     html += '<li> Custom certificate document field is invalid </li>';
        // }

        // Validation for Umbrella Certificate
        var umbrella_cert_on = localStorage.getItem('umbrella_cert_on');
        if(umbrella_cert_on == 'yes'){
           if(umbrella_currency == '')
            {
                html += '<li> Umbrella certificate amount field is invalid </li>';
                is_error = true;
            }
            if(umbrella_date == '')
            {
                html += '<li> Umbrella certificate date field is invalid </li>';
                is_error = true;
            }
            if(umbrella_upload_above == 'no' && upload_umbrella == 'no'){
                if(umbrella_doc_path == '')
                {
                    html += '<li> Umbrella document name field is invalid </li>';
                    is_error = true;
                }
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
            },3000)
            return false;
        }
        
        setTimeout(function(){
            var upload_doc_id_certificate           = 0; // $('#upload_doc_id_certificate').val();
            console.log(upload_doc_id_certificate);
            jQuery.ajax({
                url: baseUrl + "certificate/add",
                type: "POST",
                data: {
                    "company_name"                  : company_name,
                    "liability_currency"            : general_liability_cur_symbol,
                    "liability_limit"               : general_liability_amount,
                    "liability_exp"                 : general_liability_date,
                    "liability_required_min"        : general_liability_req_min,
                    "liability_cert_path"           : general_liability_doc_path,

                    "work_comp_currency"            : works_compensation_cur_symbol,
                    "work_comp_limit"               : works_compensation_currency,
                    "work_comp_exp"                 : works_compensation_date,
                    "works_comp_required_min"       : works_compensation_req_minimum,
                    "works_comp_include_above"      : works_compensation_upload_above,
                    "works_comp_not_include"        : works_compensation_upload_work,
                    "work_comp_cert_path"           : works_compensation_doc_path,

                    "auto_liability_currency"       : auto_compensation_cur_symbol,
                    "auto_liability_limit"          : auto_compensation_currency,
                    "auto_liability_exp"            : auto_compensation_date,
                    "auto_liability_required_min"   : auto_compensation_req_minimum,
                    "auto_include_above"            : auto_compensation_upload_above,
                    "auto_liability_not_include"    : auto_compensation_upload_auto,
                    "auto_liability_cert_path"      : auto_compensation_doc_path,

                    "umbrella_currency"             : umbrella_cur_symbol,
                    "umbrella_limit"                : umbrella_currency,
                    "umbrella_exp"                  : umbrella_date,
                    "umbrella_cert_path"            : umbrella_doc_path,
                    "upload_doc_id_certificate"     : upload_doc_id_certificate,

                    "project_id"                    : project_id
                },
                headers: {
                  "x-access-token": token
                },
                contentType: "application/x-www-form-urlencoded",
                cache: false
            })
            .done(function(data, textStatus, jqXHR) {
                var last_insert_id = data.description;
                console.log(last_insert_id);


                // Validation Custom Certificate
                var custom_certificate_name = [];
                $('input[name^=custom_certificate_name]').each(function(){
                    if($(this).val()== '')
                    {
                        $("#alert_message").show();
                        $('.loading-submit').hide();
                        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                        html += '<li> Custom certificate name field is required </li>';
                        html += '</ul></div>';
                        $("#alert_message").html(html);
                        setTimeout(function(){
                            $("#alert_message").hide()
                        },3000)
                        return false;
                    }
                    custom_certificate_name.push($(this).val());
                });
                var custom_certificate_cur_symbol = [];
                $('select[name^=custom_certificate_cur_symbol]').each(function(){
                    custom_certificate_cur_symbol.push($(this).val());
                });
                var custom_certificate_currency = [];
                $('input[name^=custom_certificate_currency]').each(function(){
                    if($(this).val()== '')
                    {
                        $("#alert_message").show();
                        $('.loading-submit').hide();
                        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                        html += '<li> Custom certificate limit field is required </li>';
                        html += '</ul></div>';
                        $("#alert_message").html(html);
                        setTimeout(function(){
                            $("#alert_message").hide()
                        },3000)
                        return false;
                    }
                    custom_certificate_currency.push($(this).val());
                });
                var custom_certificate_date = [];
                $('input[name^=custom_certificate_date]').each(function(){
                    if($(this).val()== '')
                    {
                        $("#alert_message").show();
                        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                        html += '<li> Custom certificate date field is required </li>';
                        html += '</ul></div>';
                        $("#alert_message").html(html);
                        setTimeout(function(){
                            $("#alert_message").hide()
                        },3000)
                        return false;
                    }
                    custom_certificate_date.push($(this).val());
                });
                var upload_doc_id = [];
                $('input[name^=upload_doc_id]').each(function(){
                   upload_doc_id.push($(this).val());
                });
                var item = {};
                item['custom_certificate_name']             = custom_certificate_name;
                item['custom_certificate_cur_symbol']       = custom_certificate_cur_symbol;
                item['custom_certificate_currency']         = custom_certificate_currency;
                item['custom_certificate_date']             = custom_certificate_date;
                item['upload_doc_id']                       = upload_doc_id;
                var item_lenght = $(custom_certificate_name).length;
                item_final = [];
                for (i = 0; i < custom_certificate_name.length; i++) {
                    item_final.push({
                        "custom_certificate_name"           :  item['custom_certificate_name'][i],
                        "custom_certificate_cur_symbol"     :  item['custom_certificate_cur_symbol'][i],
                        "custom_certificate_currency"       :  item['custom_certificate_currency'][i],
                        "custom_certificate_date"           :  item['custom_certificate_date'][i],
                        "upload_doc_id"                     :  item['upload_doc_id'][i],
                    });
                }
                console.log(item_final);

                // // Add Custom Certificate
                jQuery.each(item_final, function(i, val) {
                    console.log(val);
                    var custom_certificate_name             = val.custom_certificate_name;
                    var custom_certificate_cur_symbol       = val.custom_certificate_cur_symbol;
                    var custom_certificate_currency         = val.custom_certificate_currency;
                    var custom_certificate_date             = val.custom_certificate_date;
                    var upload_doc_id                       = val.upload_doc_id;

                    var custom_certificate_date_pdf         = custom_certificate_date;
                    jQuery.ajax({
                    url: baseUrl + "currency/"+custom_certificate_cur_symbol,
                        type: "GET",
                        headers: {
                          "Content-Type": "application/json",
                          "x-access-token": token
                        },
                        contentType: "application/json",
                        cache: false
                    })
                    .done(function(data, textStatus, jqXHR) {
                        $("#custom_certificate_pdf").append(
                            '<p style="width:100%;"><strong>'+custom_certificate_name+' Exp. Date: </strong><span>'+custom_certificate_date_pdf+'</span></p>'+
                            '<div style="clear: both;"></div>'+
                            '<p style="width:100%;"><strong>'+custom_certificate_name+' Limit: </strong><span>'+data.data.cur_symbol+' '+ReplaceNumberWithCommas(custom_certificate_currency)+'</span></p>'+
                            '<div style="clear: both;"></div>'
                        )
                    })

                    jQuery.ajax({
                       url: baseUrl+"custom_certificate/add",
                        type: "POST",
                        data: {
                            "pcm_name"          : custom_certificate_name,
                            "pcm_currency"      : custom_certificate_cur_symbol,
                            "pcm_limit"         : custom_certificate_currency,
                            "pcm_exp"           : custom_certificate_date,
                            "pcm_cert_path"     : upload_doc_id,
                            "pcm_parent_id"     : last_insert_id,
                            "project_id"        : project_id
                        },
                        headers: {
                          "x-access-token": token
                        },
                        contentType: "application/x-www-form-urlencoded",
                        cache: false
                    })
                    .done(function(data, textStatus, jqXHR) {
                        console.log(data);
                    })
                });

                // AGAIN CREATE PDF FILE FUNCTION - AFTER THAT SAVE CUSTOM CERTIFICATE
                setTimeout(function(){
                    var doc_meta            = $("#upload_doc_meta").val();
                    var doc_project_id      = $("#upload_project_id").val();
                    var document_generated  = $("#certificate_pdf_content").html();
                    var document_path       = 'uploads/certificate/';
                    jQuery.ajax({
                        url: baseUrl + "document/GeneratePdfFiles",
                        type: "POST",
                        data: {
                            "document_generated" : document_generated,
                            "document_path" : document_path
                        },
                        headers: {
                          "x-access-token": token
                        },
                        contentType: "application/x-www-form-urlencoded",
                        cache: false
                    })
                    .done(function(data, textStatus, jqXHR) {
                        console.log(data);
                        var doc_path = data;
                        jQuery.ajax({
                            url: baseUrl + "document/add",
                            type: "POST",
                            data: {
                                "doc_path" : doc_path,
                                "doc_meta" : doc_meta,
                                "doc_project_id" : doc_project_id
                            },
                            headers: {
                              "x-access-token": token
                            },
                            contentType: "application/x-www-form-urlencoded",
                            cache: false
                        })
                        .done(function(data, textStatus, jqXHR) {
                            console.log(data.description);
                            // alert(data.description);
                            jQuery.ajax({
                                url: baseUrl + "update_certificate_pdf/"+last_insert_id+"/update",
                                type: "POST",
                                data: {
                                    "doc_id_certificate" : data.description,
                                },
                                headers: {
                                  "x-access-token": token
                                },
                                contentType: "application/x-www-form-urlencoded",
                                cache: false
                            })
                            .done(function(data, textStatus, jqXHR) {
                                console.log(data);
                            })
                        })
                        // CLOSE FUNCTION CREATE PDF
                    })

                    $("#alert_message").show();
                    $('.loading-submit').hide();
                    $(".remove_file_drop").trigger("click");
                    $(".remove_current_custom_certificate").trigger("click");
                    $(".first_button").text('Save Another');
                    $('#general_liability_amount').removeAttr('value');
                    $('#general_liability_date').removeAttr('value');
                    $('#req_minimum_general').attr('checked', false);
                    // alert('faizan');
                    $('#works_compensation_currency').removeAttr('value');
                    $('#works_compensation_date').removeAttr('value');
                    $('#umbrella_liability_currency').removeAttr('value');
                    $('#umbrella_liability_date').removeAttr('value');
                    $('#auto_compensation_currency').removeAttr('value');
                    $('#auto_compensation_date').removeAttr('value');
                    $('#upload_doc_id_general').removeAttr('value');
                    $('#upload_doc_id_auto').removeAttr('value');
                    $('#upload_doc_id_work').removeAttr('value');
                    $('#upload_doc_id_umbrella').removeAttr('value');
                    $('#umbrella_liability_currency').removeAttr('value');
                    $('#umbrella_liability_date').removeAttr('value');
                    // console.log('final');
                    window.localStorage.setItem("umbrella_cert_on", "no");

                    $('html, body').animate({
                        scrollTop: $(".page-head").offset().top
                    }, 'slow')
                    
                    $("#alert_message").show();
                    $('.loading-submit').hide();
                    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New certificate added successfully!</div></div>';
                    $("#alert_message").html(html);
                    setTimeout(function()
                    {
                        $("#alert_message").hide();

                    },1000)
                },3000)
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                $('#loading_data112').hide();
                console.log(responseText.data);
            })
        },6000)
        return;
    });


    //     setTimeout(function(){
    //         // CREATE PDF FILE FUNCTION - AFTER THAT RETURN DOC ID
    //         var doc_meta            = $("#upload_doc_meta").val();
    //         var doc_project_id      = $("#upload_project_id").val();

    //         var document_generated  = $("#certificate_pdf_content").html();
    //         var document_path       = 'uploads/certificate/';
    //         jQuery.ajax({
    //             url: baseUrl + "document/GeneratePdfFiles",
    //             type: "POST",
    //             data: {
    //                 "document_generated" : document_generated,
    //                 "document_path" : document_path
    //             },
    //             headers: {
    //               "x-access-token": token
    //             },
    //             contentType: "application/x-www-form-urlencoded",
    //             cache: false
    //         })
    //         .done(function(data, textStatus, jqXHR) {
    //             console.log(data);
    //             var doc_path = data;

    //             jQuery.ajax({
    //                 url: baseUrl + "document/add",
    //                 type: "POST",
    //                 data: {
    //                     "doc_path" : doc_path,
    //                     "doc_meta" : doc_meta,
    //                     "doc_project_id" : doc_project_id
    //                 },
    //                 headers: {
    //                   "x-access-token": token
    //                 },
    //                 contentType: "application/x-www-form-urlencoded",
    //                 cache: false
    //             })
    //             .done(function(data, textStatus, jqXHR) {
    //                 console.log(data);

    //                 // $('html, body').animate({
    //                 //     scrollTop: $(".page-head").offset().top
    //                 // }, 'fast')
    //                 // $("#upload_success").fadeIn(1000).fadeOut(2000);
    //                 $("#upload_doc_id_certificate").val(data.description);
    //                 var upload_doc_id_certificate           = 0; // $('#upload_doc_id_certificate').val();
    //                 console.log(upload_doc_id_certificate);

    //                 jQuery.ajax({
    //                     url: baseUrl + "certificate/add",
    //                     type: "POST",
    //                     data: {
    //                         "company_name"                  : company_name,
    //                         "liability_currency"            : general_liability_cur_symbol,
    //                         "liability_limit"               : general_liability_amount,
    //                         "liability_exp"                 : general_liability_date,
    //                         "liability_required_min"        : general_liability_req_min,
    //                         "liability_cert_path"           : general_liability_doc_path,

    //                         "work_comp_currency"            : works_compensation_cur_symbol,
    //                         "work_comp_limit"               : works_compensation_currency,
    //                         "work_comp_exp"                 : works_compensation_date,
    //                         "works_comp_required_min"       : works_compensation_req_minimum,
    //                         "works_comp_include_above"      : works_compensation_upload_above,
    //                         "works_comp_not_include"        : works_compensation_upload_work,
    //                         "work_comp_cert_path"           : works_compensation_doc_path,

    //                         "auto_liability_currency"       : auto_compensation_cur_symbol,
    //                         "auto_liability_limit"          : auto_compensation_currency,
    //                         "auto_liability_exp"            : auto_compensation_date,
    //                         "auto_liability_required_min"   : auto_compensation_req_minimum,
    //                         "auto_include_above"            : auto_compensation_upload_above,
    //                         "auto_liability_not_include"    : auto_compensation_upload_auto,
    //                         "auto_liability_cert_path"      : auto_compensation_doc_path,

    //                         "umbrella_currency"             : umbrella_cur_symbol,
    //                         "umbrella_limit"                : umbrella_currency,
    //                         "umbrella_exp"                  : umbrella_date,
    //                         "umbrella_cert_path"            : umbrella_doc_path,
    //                         "upload_doc_id_certificate"     : upload_doc_id_certificate,

    //                         "project_id"                    : project_id
    //                     },
    //                     headers: {
    //                       "x-access-token": token
    //                     },
    //                     contentType: "application/x-www-form-urlencoded",
    //                     cache: false
    //                 })
    //                 .done(function(data, textStatus, jqXHR) {
    //                     var last_insert_id = data.description;
    //                     console.log(last_insert_id);
    //                     console.log('faizan');

    // // Validation Custom Certificate
    // var custom_certificate_name = [];
    // $('input[name^=custom_certificate_name]').each(function(){
    //     if($(this).val()== '')
    //     {
    //         $("#alert_message").show();
    //         html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
    //         html += '<li> Custom certificate name field is required </li>';
    //         html += '</ul></div>';
    //         $("#alert_message").html(html);
    //         setTimeout(function(){
    //             $("#alert_message").hide()
    //         },3000)
    //         return false;
    //     }
    //     custom_certificate_name.push($(this).val());
    // });
    // var custom_certificate_cur_symbol = [];
    // $('select[name^=custom_certificate_cur_symbol]').each(function(){
    //     custom_certificate_cur_symbol.push($(this).val());
    // });
    // var custom_certificate_currency = [];
    // $('input[name^=custom_certificate_currency]').each(function(){
    //     if($(this).val()== '')
    //     {
    //         $("#alert_message").show();
    //         html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
    //         html += '<li> Custom certificate limit field is required </li>';
    //         html += '</ul></div>';
    //         $("#alert_message").html(html);
    //         setTimeout(function(){
    //             $("#alert_message").hide()
    //         },3000)
    //         return false;
    //     }
    //     custom_certificate_currency.push($(this).val());
    // });
    // var custom_certificate_date = [];
    // $('input[name^=custom_certificate_date]').each(function(){
    //     if($(this).val()== '')
    //     {
    //         $("#alert_message").show();
    //         html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
    //         html += '<li> Custom certificate date field is required </li>';
    //         html += '</ul></div>';
    //         $("#alert_message").html(html);
    //         setTimeout(function(){
    //             $("#alert_message").hide()
    //         },3000)
    //         return false;
    //     }
    //     custom_certificate_date.push($(this).val());
    // });
    // var upload_doc_id = [];
    // $('input[name^=upload_doc_id]').each(function(){
    //     upload_doc_id.push($(this).val());
    // });
    // var item = {};
    // item['custom_certificate_name']             = custom_certificate_name;
    // item['custom_certificate_cur_symbol']       = custom_certificate_cur_symbol;
    // item['custom_certificate_currency']         = custom_certificate_currency;
    // item['custom_certificate_date']             = custom_certificate_date;
    // item['upload_doc_id']                       = upload_doc_id;
    // var item_lenght = $(custom_certificate_name).length;
    // item_final = [];
    // for (i = 0; i < custom_certificate_name.length; i++) {
    //     item_final.push({
    //         "custom_certificate_name"           :  item['custom_certificate_name'][i],
    //         "custom_certificate_cur_symbol"     :  item['custom_certificate_cur_symbol'][i],
    //         "custom_certificate_currency"       :  item['custom_certificate_currency'][i],
    //         "custom_certificate_date"           :  item['custom_certificate_date'][i],
    //         "upload_doc_id"                     :  item['upload_doc_id'][i],
    //     });
    // }
    // console.log(item_final);

    // // // Add Custom Certificate
    // jQuery.each(item_final, function(i, val) {
    //     console.log(val);
    //     var custom_certificate_name             = val.custom_certificate_name;
    //     var custom_certificate_cur_symbol       = val.custom_certificate_cur_symbol;
    //     var custom_certificate_currency         = val.custom_certificate_currency;
    //     var custom_certificate_date             = val.custom_certificate_date;
    //     var upload_doc_id                       = val.upload_doc_id;

    //     var custom_certificate_date_pdf         = $.datepicker.formatDate('yy-mm-dd', new Date(custom_certificate_date));
    //     jQuery.ajax({
    //     url: baseUrl + "currency/"+custom_certificate_cur_symbol,
    //         type: "GET",
    //         headers: {
    //           "Content-Type": "application/json",
    //           "x-access-token": token
    //         },
    //         contentType: "application/json",
    //         cache: false
    //     })
    //     .done(function(data, textStatus, jqXHR) {
    //         $("#custom_certificate_pdf").append(
    //             '<p style="width:100%;"><strong>'+custom_certificate_name+' Exp. Date: </strong><span>'+custom_certificate_date_pdf+'</span></p>'+
    //             '<div style="clear: both;"></div>'+
    //             '<p style="width:100%;"><strong>'+custom_certificate_name+' Limit: </strong><span>'+data.data.cur_symbol+' '+custom_certificate_currency+'</span></p>'+
    //             '<div style="clear: both;"></div>'
    //         )
    //     })

    //     jQuery.ajax({
    //        url: baseUrl+"custom_certificate/add",
    //         type: "POST",
    //         data: {
    //             "pcm_name"          : custom_certificate_name,
    //             "pcm_currency"      : custom_certificate_cur_symbol,
    //             "pcm_limit"         : custom_certificate_currency,
    //             "pcm_exp"           : custom_certificate_date,
    //             "pcm_cert_path"     : upload_doc_id,
    //             "pcm_parent_id"     : last_insert_id,
    //             "project_id"        : project_id
    //         },
    //         headers: {
    //           "x-access-token": token
    //         },
    //         contentType: "application/x-www-form-urlencoded",
    //         cache: false
    //     })
    //     .done(function(data, textStatus, jqXHR) {
    //         console.log(data);
    //     })
    // });

    // // AGAIN CREATE PDF FILE FUNCTION - AFTER THAT SAVE CUSTOM CERTIFICATE
    // setTimeout(function(){
    //     var doc_meta            = $("#upload_doc_meta").val();
    //     var doc_project_id      = $("#upload_project_id").val();
    //     var document_generated  = $("#certificate_pdf_content").html();
    //     var document_path       = 'uploads/certificate/';
    //     jQuery.ajax({
    //         url: baseUrl + "document/GeneratePdfFiles",
    //         type: "POST",
    //         data: {
    //             "document_generated" : document_generated,
    //             "document_path" : document_path
    //         },
    //         headers: {
    //           "x-access-token": token
    //         },
    //         contentType: "application/x-www-form-urlencoded",
    //         cache: false
    //     })
    //     .done(function(data, textStatus, jqXHR) {
    //         console.log(data);
    //         var doc_path = data;
    //         jQuery.ajax({
    //             url: baseUrl + "document/add",
    //             type: "POST",
    //             data: {
    //                 "doc_path" : doc_path,
    //                 "doc_meta" : doc_meta,
    //                 "doc_project_id" : doc_project_id
    //             },
    //             headers: {
    //               "x-access-token": token
    //             },
    //             contentType: "application/x-www-form-urlencoded",
    //             cache: false
    //         })
    //         .done(function(data, textStatus, jqXHR) {
    //             console.log(data.description);
    //             // alert(data.description);
    //             jQuery.ajax({
    //                 url: baseUrl + "update_certificate_pdf/"+last_insert_id+"/update",
    //                 type: "POST",
    //                 data: {
    //                     "doc_id_certificate" : data.description,
    //                 },
    //                 headers: {
    //                   "x-access-token": token
    //                 },
    //                 contentType: "application/x-www-form-urlencoded",
    //                 cache: false
    //             })
    //             .done(function(data, textStatus, jqXHR) {
    //                 console.log(data);
    //             })
    //         })
    //         // CLOSE FUNCTION CREATE PDF
    //     })
    // },3000)

    //                     $('html, body').animate({
    //                         scrollTop: $(".page-head").offset().top
    //                     }, 'slow')
    //                     $('#loading_data112').hide();
    //                     $("#alert_message").show();
    //                     $('#general_liability_amount').removeAttr('value');
    //                     $('#works_compensation_currency').removeAttr('value');
    //                     $('#general_liability_date').removeAttr('value');
    //                     $('#req_minimum_general').attr('checked', false);
    //                     $('#works_compensation_date').removeAttr('value');
    //                     $('#umbrella_liability_currency').removeAttr('value');
    //                     $('#umbrella_liability_date').removeAttr('value');
    //                     $('#auto_compensation_currency').removeAttr('value');
    //                     $('#auto_compensation_date').removeAttr('value');
    //                     $('#upload_doc_id_general').removeAttr('value');
    //                     $('#upload_doc_id_auto').removeAttr('value');
    //                     $('#upload_doc_id_work').removeAttr('value');
    //                     $('#upload_doc_id_umbrella').removeAttr('value');
    //                     $('#umbrella_liability_currency').removeAttr('value');
    //                     $('#umbrella_liability_date').removeAttr('value');
    //                     $(".remove_file_drop").trigger("click");
    //                     $(".remove_current_custom_certificate").trigger("click");

    //                     $("#alert_message").show();
    //                     html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Added successfully!</div></div>';
    //                     $("#alert_message").html(html);
    //                     setTimeout(function()
    //                     {
    //                         $("#alert_message").hide();

    //                     },9000)





    //                 })
    //                 .fail(function(jqXHR, textStatus, errorThrown) {
    //                     console.log("HTTP Request Failed");
    //                     var responseText, html;
    //                     responseText = JSON.parse(jqXHR.responseText);
    //                     $('#loading_data112').hide();
    //                     console.log(responseText.data);
    //                     $('html, body').animate({
    //                         scrollTop: $(".page-head").offset().top
    //                     }, 'fast')

    //                     $("#alert_message").show();
    //                     html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';

    //                     if(responseText.data.company_name != null){
    //                         html += '<li>The Contractor field is required.</li>';
    //                     }
    //                     if(responseText.data.liability_limit != null){
    //                         html += '<li>General liability amount field is invalid.</li>';
    //                     }
    //                     if(responseText.data.liability_required_min != null){
    //                         html += '<li>General liability required min field is invalid.</li>';
    //                     }
    //                     if(responseText.data.liability_exp != null){
    //                         html += '<li>General liability expiration date is invalid.</li>';
    //                     }
    //                     if(responseText.data.liability_cert_path != null){
    //                         html += '<li>General liability document is invalid.</li>';
    //                     }
    //                     if(responseText.data.work_comp_limit != null){
    //                         html += '<li>Workers compensation amount field is invalid.</li>';
    //                     }
    //                     if(responseText.data.work_comp_exp != null){
    //                         html += '<li>Workers Compensation expiration date is invalid.</li>';
    //                     }
    //                     if(responseText.data.work_comp_cert_path != null){
    //                         html += '<li>Workers Compensation document is invalid.</li>';
    //                     }
    //                     if(responseText.data.auto_liability_limit != null){
    //                         html += '<li>Auto liability amount field is invalid.</li>';
    //                     }
    //                     if(responseText.data.auto_liability_exp != null){
    //                         html += '<li>Auto liability expiration date is invalid.</li>';
    //                     }
    //                     if(responseText.data.auto_liability_cert_path != null){
    //                         html += '<li>Auto liability document is invalid.</li>';
    //                     }
    //                     if(responseText.data.umbrella_limit != null){
    //                         html += '<li>Umbrella liability amount field is invalid.</li>';
    //                     }
    //                     if(responseText.data.umbrella_exp != null){
    //                         html += '<li>Umbrella liability expiration date is invalid.</li>';
    //                     }
    //                     if(responseText.data.umbrella_cert_path != null){
    //                         html += '<li>Umbrella liability document is invalid.</li>';
    //                     }
    //                     if(responseText.data.project_id != null){
    //                         html += '<li>The project id field is invalid.</li>';
    //                     }
    //                     html += '</ul></div>';
    //                     $("#alert_message").html(html);
    //                     setTimeout(function(){
    //                         $("#alert_message").hide();
    //                     },6000)
    //                 })
    //             })
    //             .fail(function(jqXHR, textStatus, errorThrown) {
    //                     console.log("HTTP Request Failed");
    //                     var responseText;
    //                     responseText = JSON.parse(jqXHR.responseText);
    //                     console.log(responseText.data);
    //             })
    //         })
    //         .fail(function(jqXHR, textStatus, errorThrown) {
    //                 console.log("HTTP Request Failed");
    //                 var responseText;
    //                 responseText = JSON.parse(jqXHR.responseText);
    //                 console.log(responseText.data);
    //         })
    //         // CLOSE FUNCTION CREATE PDF
    //     },5000)
