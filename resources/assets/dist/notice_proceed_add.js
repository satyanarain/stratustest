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
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("notice_proceed_add", check_user_access );
    console.log(check_permission);
    if(check_permission < 1){
        window.location.href = baseUrl + "403";
    }
    else {
        console.log('Yes Permission');
        $('.body-content .wrapper').show();
    }

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
            var count_data = data.data.length;
            // console.log(count_data);
            var count = 1;
            jQuery.each(data.data, function( i, val ) {
                if(count == count_data){
                    if(val.pt_status == 'active'){
                        $("#pdf_gen_project_type").append(
                            val.pt_name+' '
                        )
                    }            
                }
                else {
                    if(val.pt_status == 'active'){
                        $("#pdf_gen_project_type").append(
                            val.pt_name+', '
                        )
                    }
                }
                count++;
            });
        });




});


$('.submit_notice_add_form').click(function(e) {
  $('.loading-submit').show();
    e.preventDefault();
    // var contractor_name         = agency_id;
    // var date                    = $('#notice_date').val();
    // var start_date              = $('#notice_start_date').val();
    // var duration                = $('#duration_days').val();
    // var cal_day                 = $("input[name='days_working']:checked").val();
    // var liquidated_amount       = $('#liquidated_amount').val();
    // var path                    = $('#upload_doc_id').val();
    // var project_id              = $('#upload_project_id').val();
    // var token                   = localStorage.getItem('u_token');
    // // console.log(cal_day);
    // jQuery.ajax({
    //     url: baseUrl + "notice-proceed/add",
    //     type: "POST",
    //     data: {
    //         "contractor_name"       : contractor_name,
    //         "date"                  : date,
    //         "start_date"            : start_date,
    //         "duration"              : duration,
    //         "cal_day"               : cal_day,
    //         "liquidated_amount"     : liquidated_amount,
    //         "path"                  : path,
    //         "project_id"            : project_id
    //     },
    //     headers: {
    //         "x-access-token": token
    //     },
    //     contentType: "application/x-www-form-urlencoded",
    //     cache: false
    // })
    // .done(function(data, textStatus, jqXHR) {
    //     $("#liquidated_amount").removeAttr('value');
    //     $('#duration_days').removeAttr('value');
    //     $('#upload_doc_id').removeAttr('value');
    //     $(".remove_file_drop").trigger("click");
    //     $("#alert_message").show();
    //     html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Added successfully!</div></div>';
    //     $("#alert_message").html(html);
    //     $(".another_button").show();
    //     $(".first_button").hide();
    //     $('html, body').animate({
    //         scrollTop: $(".page-head").offset().top
    //     }, 'fast')
    //     setTimeout(function(){
    //         $("#alert_message").hide()
    //     },5000)
    // })
    // .fail(function(jqXHR, textStatus, errorThrown) {
    //     console.log("HTTP Request Failed");
    //     var responseText, html;
    //     responseText = JSON.parse(jqXHR.responseText);
    //     console.log(responseText.data);
    //     $('html, body').animate({
    //         scrollTop: $(".page-head").offset().top
    //     }, 'fast')
    //     $("#alert_message").show();
    //     html = '<<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
    //     if(responseText.data.contractor_name != null){
    //         html += '<li>The contract name is required.</li>';
    //     }
    //     if(responseText.data.date != null){
    //         html += '<li>The date of notice is required.</li>';
    //     }
    //     if(responseText.data.start_date != null){
    //         html += '<li>The start date is required.</li>';
    //     }
    //     if(responseText.data.duration != null){
    //         html += '<li>The duration (in days) is required.</li>';
    //     }
    //     if(responseText.data.liquidated_amount != null){
    //         html += '<li>The liquidated amount is required.</li>';
    //     }
    //     if(responseText.data.path != null){
    //         html += '<li>The document is required.</li>';
    //     }
    //     if(responseText.data.project_id != null){
    //         html += '<li>The project id field is required.</li>';
    //     }
    //     html += '</ul></div>';
    //     $("#alert_message").html(html);
    //     setTimeout(function(){
    //         $("#alert_message").hide()
    //     },5000)
    // })
});

$('.create_notice').click(function () {
    var check_award_type = $("input[name='check_award_type']:checked").val();
    console.log(check_award_type);
    if(check_award_type == 'new'){
        // alert('new');
        var notice_start_date = $("#notice_start_date").val();
        $('#pdf_gen_start_date').text(notice_start_date);
        var duration_days = $("#duration_days").val();
        if(duration_days=='' ){
            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            html += '<li>The duration field is required   </li>';
            // html += '</ul></div>';
            // $('html, body').animate({
            //     scrollTop: $(".page-head").offset().top
            // }, 'fast')
            // $("#alert_message").html(html);
            // setTimeout(function(){
            //     $("#alert_message").hide()
            // },3000)
            // return false;
        }
        $('#pdf_gen_working_days').text(duration_days);
        $('.pdf_gen_working_days_type').text($("input[name='days_working']:checked").val());
    
        var notice_date = $("#notice_start_date").val();
        var invite_date = $.datepicker.formatDate('yy-mm-dd', new Date(notice_date));
        if($("input[name='days_working']:checked").val()=="working_day")
        {
            var invite_date1 = $.datepicker.formatDate('mm/dd/yy', new Date(notice_date));
            //alert(invite_date1);return false;
            var invite_date = add_business_days(invite_date1,parseInt(duration_days));
            //alert(today);return false;
            var invite_date = new Date(invite_date);
            var dateMsg = invite_date.getFullYear()+'-'+(invite_date.getMonth()+1)+'-'+(invite_date.getDate());
        }else{
            var invite_date = new Date(invite_date);
            var today = new Date(invite_date.getFullYear(), invite_date.getMonth(), invite_date.getDate());
            //today1 = addBusinessDays(today, duration_days);
            //alert(today1);
            invite_date.setDate(invite_date.getDate() + parseInt(duration_days));
            var dateMsg = invite_date.getFullYear()+'-'+(invite_date.getMonth()+1)+'-'+(invite_date.getDate()-1);
        }
        
        //alert(dateMsg);return false;
        
        $('#pdf_gen_working_days_1').text(dateMsg);
        
        if(notice_date == ''){
            html += '<li>Start date is invalid</li>';
        }

        var start_date = $("#notice_date").val();
        if(start_date == ''){
            html += '<li>Date of notice is invalid</li>';
        }

        var days_working = $("input[name='days_working']:checked").val();
        if(days_working == null){
            // $("#alert_message").show();
            // html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            html += '<li>The day type field is invalid</li>';
            // html += '</ul></div>';
            // $('html, body').animate({
            //     scrollTop: $(".page-head").offset().top
            // }, 'fast')
            // $("#alert_message").html(html);
            // setTimeout(function(){
            //     $("#alert_message").hide()
            // },3000)
            // return false;
        }

        var liquidated_amount = $("#liquidated_amount").val();
        if(liquidated_amount=='')
        {
            // $("#alert_message").show();
            // html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            html += '<li>The liquidated damages field is required  </li>';
            html += '</ul></div>';
            $("#alert_message").html(html);
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            setTimeout(function(){
                $("#alert_message").hide()
            },3000)
            return false;
        }

        console.log(liquidated_amount);
        $('#pdf_gen_amount').text(ReplaceNumberWithCommas(liquidated_amount));
        $('.loading_data1').show();

        var doc_meta = $("#upload_doc_meta").val();
        var doc_project_id = $("#upload_project_id").val();


        var document_generated  = $("#pdf_content").html();//return false;
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
                var token = localStorage.getItem('u_token');
                // console.log(data);
                // console.log(doc_meta);
                // console.log(doc_project_id);
                // console.log(token);
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
                        console.log(data);
                        // $("#upload_success").show();
                        $("#upload_doc_id").val(data.description);
                        $('#cmd').hide();
                        $(".first_button").show();
                        $('.loading_data1').hide();

                        var contractor_name         = agency_id;
                        var date                    = $('#notice_date').val();
                        var start_date              = $('#notice_start_date').val();
                        var duration                = $('#duration_days').val();
                        var cal_day                 = $("input[name='days_working']:checked").val();
                        var liquidated_amount       = $('#liquidated_amount').val();
                        var path                    = $('#upload_doc_id').val();
                        var project_id              = $('#upload_project_id').val();
                        var token                   = localStorage.getItem('u_token');
                        var add_data_new            = $("#add_data_new").val();
                        var signatory_name = [];
                        $('input[name^=signatory_name]').each(function(){
                            signatory_name.push($(this).val());
                        });
                        if(cal_day=="calendar_day")
                            var days_type = "calendar";
                        else
                            var days_type = "working";
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
                        var signatory_role = [];
                        signatory_role.push('owner');
                        signatory_role.push('contractor');
                        var item = {};
                        item['signatory_name'] 		= signatory_name;
                        item['signatory_email']         = signatory_email;
                        item['signatory_role']          = signatory_role;
                        signatory_arr = [];
                        for (i = 0; i < signatory_email.length; i++) {
                            signatory_arr.push({
                                            "signatory_name" 		:   item['signatory_name'][i],
                                            "signatory_email" 		:   item['signatory_email'][i],
                                            "signatory_role"            :   item['signatory_role'][i],
                                            "pdf_gen_contractor_name"   :   $('#pdf_gen_contractor_name').html(),
                                            "pdf_gen_contractor_address":   $('#pdf_gen_contractor_address').html(),
                                            "pdf_gen_ntp_date"          :   $('#pdf_gen_ntp_date').html(),
                                            "pdf_gen_project_name"      :   $('#pdf_gen_project_name').html(),
                                            "pdf_gen_project_type"      :   $('#pdf_gen_project_type').html(),
                                            "pdf_gen_contractor_name_1" :   $('#pdf_gen_contractor_name_1').html(),
                                            "pdf_gen_start_date"        :   $('#pdf_gen_start_date').html(),
                                            "pdf_gen_working_days"      :   $('#pdf_gen_working_days').html(),
                                            "pdf_gen_working_days_1"    :   $('#pdf_gen_working_days_1').html(),
                                            "pdf_gen_amount"            :   $('#pdf_gen_amount').html(),
                                            "days_type"                 :   days_type,
                                        });
                        }
                        console.log(cal_day);
                        jQuery.ajax({
                            url: baseUrl + "notice-proceed/add",
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
                                "signatory_arr"         : signatory_arr,
                            },
                            headers: {
                                "x-access-token": token
                            },
                            contentType: "application/x-www-form-urlencoded",
                            cache: false
                        })
                        .done(function(data, textStatus, jqXHR) {
                            $("#liquidated_amount").removeAttr('value');
                            $('#duration_days').removeAttr('value');
                            $('#upload_doc_id').removeAttr('value');
                            $('#notice_date').removeAttr('value');
                            $('#notice_start_date').removeAttr('value');
                            $('input[name="days_working"]').attr('checked', false);
                            $('input[name="signatory_name"]').attr('value', '');
                            $('input[name^=signatory_name],input[name^=signatory_email]').each(function(){$(this).val('');});
                            $(".remove_file_drop").trigger("click");
                            $("#alert_message").show();
                            $('.loading-submit').hide();
                            if(add_data_new == 0){
                                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New notice to proceed added successfully!</div></div>';
                            }
                            else {
                                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New revised notice to proceed added successfully!</div></div>';
                            }
                            $('#add_data_new').val(1);
                            $("#alert_message").html(html);
                            $(".first_button").text('Add Revised Notice to Proceed');
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
        var contractor_name         = agency_id;
        // var date                    = $('#notice_date').val();
        // var start_date              = $('#notice_start_date').val();
        // var duration                = $('#duration_days').val();
        // var cal_day                 = $("input[name='days_working']:checked").val();
        // var liquidated_amount       = $('#liquidated_amount').val();
        var path                    = $('#upload_doc_id').val();
        var project_id              = $('#upload_project_id').val();
        var token                   = localStorage.getItem('u_token');
        var add_data_new            = $("#add_data_new").val();
        console.log(path);
        jQuery.ajax({
            url: baseUrl + "notice-proceed/add",
            type: "POST",
            data: {
                "check_award_type"      : check_award_type,
                "contractor_name"       : contractor_name,
                // "date"                  : date,
                // "start_date"            : start_date,
                // "duration"              : duration,
                // "cal_day"               : cal_day,
                // "liquidated_amount"     : liquidated_amount,
                "path"                  : path,
                "project_id"            : project_id
            },
            headers: {
                "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            $("#liquidated_amount").removeAttr('value');
            $('#duration_days').removeAttr('value');
            $('#upload_doc_id').removeAttr('value');
            $('input[name="days_working"]').attr('checked', false);
            $(".remove_file_drop").trigger("click");
            $("#alert_message").show();
            $('.loading-submit').hide();
            if(add_data_new == 0){
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New notice to proceed added successfully!</div></div>';
            }
            else {
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New revised notice to proceed added successfully!</div></div>';
            }
            $('#add_data_new').val(1);
            $("#alert_message").html(html);
            $(".first_button").text('Add Revised Notice to Proceed');
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
            console.log(responseText.data);
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
    return false;





    // var doc = new jsPDF();
    // var specialElementHandlers = {
    //     'DIV to be rendered out': function(element, renderer){
    //     return true;
    //     }
    // };

    // // var html=$("#notice_award_pdf_content").html();
    // doc.fromHTML($('#pdf_content').get(0), 15, 15, {
    // // doc.fromHTML(html,15,15, {
    //     'width': 170,
    //     'elementHandlers': specialElementHandlers
    // });

    // var pdf =doc.output(); //returns raw body of resulting PDF returned as a string as per the plugin documentation.
    // var data = new FormData();
    // data.append("document_generated" , pdf);
    // data.append("document_path" , 'uploads/notice_proceed/');
    // var xhr = new XMLHttpRequest();
    // xhr.open( 'post', baseUrl+'document/CreateUploadFiles', true ); //Post to php Script to save to server
    // xhr.send(data);
    // xhr.onreadystatechange = function() {
    //     if (xhr.readyState == XMLHttpRequest.DONE) {
    //         var document_response = xhr.responseText;
    //         var document_response = document_response.replace(/\\/g, '');;
    //         var document_response = document_response.replace('"', '');;
    //         var document_response = document_response.replace('"', '');;

    //         var doc_path = document_response;

    //         // console.log(doc_path);
    //         // console.log(doc_meta);
    //         // console.log(doc_project_id);
    //         // $('#create_notice').hide();
    //         $("#submit_notice_add_form").show();

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
    //             .done(function(data, textStatus, jqXHR) {
    //             console.log(data);
    //             $("#upload_success").fadeIn(1000).fadeOut(5000);
    //             $("#upload_doc_id").val(data.description);
    //         })
    //         .fail(function(jqXHR, textStatus, errorThrown) {
    //                 console.log("HTTP Request Failed");
    //                 var responseText;
    //                 responseText = JSON.parse(jqXHR.responseText);
    //                 console.log(responseText.data);
    //         })
    //     }
    // }
});

function addWorkDays(startDate, days) {
    // Get the day of the week as a number (0 = Sunday, 1 = Monday, .... 6 = Saturday)
    var dow = startDate.getDay();
    var daysToAdd = parseInt(days);
    // If the current day is Sunday add one day
    if (dow == 0)
        daysToAdd++;
    // If the start date plus the additional days falls on or after the closest Saturday calculate weekends
    if (dow + daysToAdd >= 6) {
        //Subtract days in current working week from work days
        var remainingWorkDays = daysToAdd - (5 - dow);
        //Add current working week's weekend
        daysToAdd += 2;
        if (remainingWorkDays > 5) {
            //Add two days for each working week by calculating how many weeks are included
            daysToAdd += 2 * Math.floor(remainingWorkDays / 5);
            //Exclude final weekend if remainingWorkDays resolves to an exact number of weeks
            if (remainingWorkDays % 5 == 0)
                daysToAdd -= 2;
        }
    }
    startDate.setDate(startDate.getDate() + daysToAdd);
    return startDate;
}
function addBusinessDays(d,n) {
    d = new Date(d.getTime());
    var day = d.getDay();
    d.setDate(d.getDate() + n + (day === 6 ? 2 : +!day) + (Math.floor((n - 1 + (day % 6 || 1)) / 5) * 2));
    return d;
}

function add_business_days(now,days) {
  var now = new Date(now);
  var dayOfTheWeek = now.getDay();
  var calendarDays = days;
  var deliveryDay = dayOfTheWeek + days;
  if (deliveryDay >= 6) {
    //deduct this-week days
    days -= 6 - dayOfTheWeek;
    //count this coming weekend
    calendarDays += 2;
    //how many whole weeks?
    deliveryWeeks = Math.floor(days / 5);
    //two days per weekend per week
    calendarDays += deliveryWeeks * 2;
  }
  now.setTime((now.getTime() + calendarDays * 24 * 60 * 60 * 1000)-(24 * 60 * 60 * 1000));
  return now;
}