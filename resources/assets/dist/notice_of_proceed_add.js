$(document).ready(function() {

    // Get login user profile data
    $("#submit_new_btn").hide();
    $("#bid_amount").hide();
    $("#company_name").hide();
    $('#upload_doc_id').removeAttr('value');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3]; // projects
    console.log(project_id);

    // Check Permission
    // var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    // var check_permission = jQuery.inArray("notice_proceed_add", check_user_access );
    // console.log(check_permission);
    // if(check_permission < 1){
    //     window.location.href = baseUrl + "403";
    // }
    // else {
    //     console.log('Yes Permission');
    //     $('.body-content .wrapper').show();
    // }

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

    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');

    // Get All agencies Name
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
                    $("#company_name").append(
                        '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                    )
                }else {

                }
            });
            // $( "h2" ).appendTo( $( ".container" ) );

            // $(".loading_data").remove();
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
                console.log('Company name 404');
                // window.location.href = baseUrl + "404";
            }
            else {
                window.location.href = baseUrl + "500";
            }
        });

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
                // console.log(data);
                var f_name = data.data.f_name;
                $('#pdf_gen_contractor_name').text(f_name);
                $('#pdf_gen_contractor_name_1').text(f_name);
                $('#contractor_name').text(f_name);
                var firm_address = data.data.f_address;
                $('#pdf_gen_contractor_address').text(firm_address);
            })
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            console.log(response);
            if(response == 403){
                // window.location.href = baseUrl + "403";
                console.log('Contractor Name 403');
            }
            else if(response == 404){
                // alert('faizan');
                // window.location.href = baseUrl + "404";
                $(".loading_data").hide();
            }
            else {
                window.location.href = baseUrl + "500";
            }
        });

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
        $("#pdf_gen_contract_amount").text(data.data[0].total_amount);
        $("#pdf_gen_contract_amount").show();
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

    // Get Project User
    jQuery.ajax({
        url: baseUrl+"/"+project_id+"/check_project_user",
        type: "GET",
        headers: {
            "Content-Type": "application/json",
            "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        // console.log(data);
        // Foreach Loop
        jQuery.each(data, function( i, val ) {
            $(".contact_project").append(
                '<option value="'+val.id+'">'+val.first_name+' '+val.last_name+' - '+val.role+'</option>'
            )
        });
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        if(response == 403){
            console.log("Project User 403");
        }
        else if(response == 404){
             console.log("404");
        }
        else {
            window.location.href = baseUrl + "500";
        }
    })


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
                    console.log(data.data.cur_symbol);
                    $('.project_currency').text(data.data.cur_symbol+' ');
                    $('.pdf_gen_project_currency').text(data.data.cur_symbol+' ');
                })
        })


    // Selected Improvement Type
    jQuery.ajax({
        url: baseUrl +"/"+project_id+"/improvement-type",
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
            if(val.pt_status == 'active'){
                $("#pdf_gen_project_type").append(
                    val.pt_name+', '
                )
            }
        });
    });


    setInterval(function(){
        var src = $('#docusign_box iframe').find('body');
        // var src = 'https://www.docusign.com/developer-center?envelopeId=920d4580-c404-4060-9750-3ac6a21c24bb&event=Send';
        // console.log(src);
        // var url = $(location).attr('href').split( '/' );
        // project_id = url[ url.length - 3]; // projects
        // $(src+":contains('event=Send')").hide();
        // if(src.indexOf('?event=Send') != -1 || src.IndexOf('/eventSend') != -1) {
        //     $('#docusign_box').modal('hide');
        //     alert('yes');
        // }
    },5000)

});

$('.create_notice').click(function () {
    var check_award_type = $("input[name='check_award_type']:checked").val();
    console.log(check_award_type);
    if(check_award_type == 'new'){
        $('.loading-submit').show();
        // alert('new');
        var notice_start_date = $("#notice_start_date").val();
        $('#pdf_gen_start_date').text(notice_start_date);
        var duration_days = $("#duration_days").val();
        
        var html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
        var is_error = false;
        if(duration_days=='' ){
            html += '<li>The duration field is required   </li>';
            is_error = true;
        }
        $('#pdf_gen_working_days').text(duration_days);
        $('#pdf_gen_working_days_1').text(duration_days);

        var notice_date = $("#notice_start_date").val();
        if(notice_date == ''){
            html += '<li>Start date is invalid</li>';
            is_error = true;
        }

        var start_date = $("#notice_date").val();
        if(start_date == ''){
            html += '<li>Date of notice is invalid</li>';
            is_error = true;
        }

        var days_working = $("input[name='days_working']:checked").val();
        if(days_working == null){
            html += '<li>The day type field is invalid</li>';
            is_error = true;
        }

        var liquidated_amount = $("#liquidated_amount").val();
        if(liquidated_amount =='')
        {
            html += '<li>The liquidated damages field is required  </li>';
            is_error = true;
        }

        console.log(liquidated_amount);
        $('#pdf_gen_amount').text(liquidated_amount);
        $('.loading_data1').show();
        html += '</ul></div>';


        if(is_error == true){
            $('.loading-submit').hide();
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").html(html);
            $("#alert_message").show();
            setTimeout(function(){
                $("#alert_message").hide()
            },3000)
            return false;
        }

        var doc_meta            = $("#upload_doc_meta").val();
        var doc_project_id      = $("#upload_project_id").val();

        var document_generated  = $("#pdf_content").html();
        var document_path       = 'uploads/notice_proceed/';
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
                var token    = localStorage.getItem('u_token');
                var doc_path = data;
                setTimeout(function(){
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
                        var document_id = data.description;
                        var token    = localStorage.getItem('u_token');
                        // $("#upload_success").show();
                        $("#upload_doc_id").val(data.description);
                        $('#cmd').hide();
                        $(".first_button").show();
                        // $('.loading_data1').hide();
                        
                        var contractor_name         = agency_id;
                        var date                    = $('#notice_date').val();
                        var start_date              = $('#notice_start_date').val();
                        var duration                = $('#duration_days').val();
                        var cal_day                 = $("input[name='days_working']:checked").val();
                        var liquidated_amount       = $('#liquidated_amount').val();
                        var path                    = $('#upload_doc_id').val();
                        var project_id              = $('#upload_project_id').val();
                        var sign_owner_id           = $('#sign_owner_id').val();
                        var sign_contractor_id      = $('#sign_contractor_id').val();
                        var token                   = localStorage.getItem('u_token');
                        jQuery.ajax({
                            url: baseUrl + "notice-of-proceed/add",
                            type: "POST",
                            data: {
                                "check_award_type"      : check_award_type,
                                "contractor_name"       : contractor_name,
                                "date"                  : date,
                                "start_date"            : start_date,
                                "duration"              : duration,
                                "cal_day"               : cal_day,
                                "liquidated_amount"     : liquidated_amount,
                                "path"                  : path,
                                "project_id"            : project_id,
                                "sign_owner_id"         : sign_owner_id,
                                "sign_contractor_id"    : sign_contractor_id
                            },
                            headers: {
                                "x-access-token": token
                            },
                            contentType: "application/x-www-form-urlencoded",
                            cache: false
                        })
                        .done(function(data, textStatus, jqXHR) {
                            console.log(data);
                            $('#docusign_box').append('<iframe src="'+data.envelope_url+'" style="width: 100%; border: 0px; min-height: 500px;" id="docusing_link"></iframe>');
                            $("#docusign_box_open").trigger("click");

                            var src = $('#docusing_link').attr('src');
                            // alert(src);
                            $("#liquidated_amount").removeAttr('value');
                            $('#duration_days').removeAttr('value');
                            $('#upload_doc_id').removeAttr('value');
                            $('#notice_date').removeAttr('value');
                            $('#notice_start_date').removeAttr('value');
                            $('input[name="days_working"]').attr('checked', false);
                            $(".remove_file_drop").trigger("click");
                            // $("#alert_message").show();
                            $('.loading-submit').hide();
                            $(".another_button").show();
                            $(".first_button").hide();
                            // html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Added successfully!</div></div>';
                            // $("#alert_message").html(html);
                            // $('html, body').animate({
                            //     scrollTop: $(".page-head").offset().top
                            // }, 'fast')
                            // setTimeout(function(){
                            //     $("#alert_message").hide()
                            // },5000)
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
                            html = '<<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                            if(responseText.data.contractor_name != null){
                                html += '<li>The contract name is required.</li>';
                            }
                            if(responseText.data.date != null){
                                html += '<li>The date of notice is required.</li>';
                            }
                            if(responseText.data.start_date != null){
                                html += '<li>The start date is required.</li>';
                            }
                            if(responseText.data.duration != null){
                                html += '<li>The duration (in days) is required.</li>';
                            }
                            if(responseText.data.liquidated_amount != null){
                                html += '<li>The liquidated amount is required.</li>';
                            }
                            if(responseText.data.path != null){
                                html += '<li>The document is required.</li>';
                            }
                            if(responseText.data.project_id != null){
                                html += '<li>The project id field is required.</li>';
                            }
                            html += '</ul></div>';
                            $("#alert_message").html(html);
                            setTimeout(function(){
                                $("#alert_message").hide()
                            },5000)
                        })
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        console.log("HTTP Request Failed");
                        var responseText;
                        responseText = JSON.parse(jqXHR.responseText);
                        console.log(responseText.data);
                    })

                },5000)
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(responseText.data);
            })

    }
    else {
        $('.loading-submit').show();
        var contractor_name         = agency_id;
        var path                    = $('#upload_doc_id').val();
        var project_id              = $('#upload_project_id').val();
        var sign_owner_id           = $('#sign_owner_id').val();
        var sign_contractor_id      = $('#sign_contractor_id').val();
        var token                   = localStorage.getItem('u_token');
        console.log(contractor_name);
        console.log(path);
        console.log(project_id);
        console.log(sign_owner_id);
        console.log(sign_contractor_id);
        // return;
        jQuery.ajax({
            url: baseUrl + "notice-of-proceed/add",
            type: "POST",
            data: {
                "check_award_type"      : 'exist',
                "contractor_name"       : contractor_name,
                "path"                  : path,
                "project_id"            : project_id,
                "sign_owner_id"         : sign_owner_id,
                "sign_contractor_id"    : sign_contractor_id
            },
            headers: {
                "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            console.log(data);
            $('#docusign_box').append('<iframe src="'+data.envelope_url+'" style="width: 100%; border: 0px; min-height: 500px;" id="docusing_link"></iframe>');
            $("#docusign_box_open").trigger("click");

            $("#liquidated_amount").removeAttr('value');
            $('#duration_days').removeAttr('value');
            $('#upload_doc_id').removeAttr('value');
            $('input[name="days_working"]').attr('checked', false);
            $(".remove_file_drop").trigger("click");
            // $("#alert_message").show();
            $('.loading-submit').hide();
            $(".another_button").show();
            $(".first_button").hide();
            // html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Added successfully!</div></div>';
            // $("#alert_message").html(html);
            // $('html, body').animate({
            //     scrollTop: $(".page-head").offset().top
            // }, 'fast')
            // setTimeout(function(){
            //     $("#alert_message").hide()
            // },5000)
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var responseText, html;
            responseText = JSON.parse(jqXHR.responseText);
            console.log(jqXHR);
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            if(responseText.data.contractor_name != null){
                html += '<li>The contract name is required.</li>';
            }
            if(responseText.data.date != null){
                html += '<li>The date of notice is required.</li>';
            }
            if(responseText.data.start_date != null){
                html += '<li>The start date is required.</li>';
            }
            if(responseText.data.duration != null){
                html += '<li>The duration (in days) is required.</li>';
            }
            if(responseText.data.liquidated_amount != null){
                html += '<li>The liquidated amount is required.</li>';
            }
            if(responseText.data.path != null){
                html += '<li>The document is required.</li>';
            }
            if(responseText.data.project_id != null){
                html += '<li>The project id field is required.</li>';
            }
            html += '</ul></div>';
            $("#alert_message").html(html);
            setTimeout(function(){
                $("#alert_message").hide()
            },5000)
        })
    }
});
