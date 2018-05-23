$(document).ready(function() {

    // Get login user profile data
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 4]; // projects
    report_id = url[ url.length - 2]; // report
    console.log(project_id);
    console.log(report_id);

    var complete_status = false;

    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');

    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("weekly_report_update", check_user_access );
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
    
    // Notice to Proceed
    jQuery.ajax({
    url: baseUrl +project_id+"/notice-proceed-single",
        type: "GET",
        headers: {
          "x-access-token": token
        },
         data: {
            "type"           : "calendar_day",
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        console.log(data.data);
        console.log(data.data.pnp_date);
        console.log("pnp call"+data.data.pnp_cal_day);
       if (data.data.pnp_cal_day == 'working_day') {
        document.getElementById('days_text').innerHTML = "WORKING DAYS";
       } else {
          document.getElementById('days_text').innerHTML = "CALENDAR DAYS";

       }
        // var date1 = data.data.pnp_date.replace(' 00:00:00', '');
        // console.log(date1);
        var f_name = data.data.contractor_name;
        $('#contractor_name').text(f_name);
        var project_name = data.data.p_name;
        $('#project_name').text(project_name);
        // var notice_to_proceed_date = $.datepicker.formatDate('yy-mm-dd', new Date(data.data.pnp_date.replace(' ', 'T')));
        var notice_to_proceed_date = data.data.pnp_date;
        // var notice_to_proceed_date = data.data.pnp_date;
        $('#notice_to_proceed_date').text(notice_to_proceed_date);
        var notice_to_proceed_start_date = data.data.pnp_start_date;
        // var notice_to_proceed_start_date = data.data.pnp_start_date;
        $('#notice_to_proceed_start_date').text(notice_to_proceed_start_date);
        var notice_to_proceed_duration_day = data.data.pnp_duration;
        $('#notice_to_proceed_duration_day').text(notice_to_proceed_duration_day);
        var result = new Date(notice_to_proceed_start_date);
        if(result.getFullYear())
        {
            result.setDate(result.getDate() + notice_to_proceed_duration_day);
            var computed_completion_date = $.datepicker.formatDate('yy-mm-dd', new Date(result));
            $('#computed_completion_date').text(computed_completion_date);
        }else{
            $('#computed_completion_date').text('');
        }
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
            console.log('404');
        }
        else {
            window.location.href = baseUrl + "500";
        }
    });


    // Weekly Statement
    jQuery.ajax({
    url: baseUrl + "weekly-report/"+report_id,
        type: "GET",
        headers: {
          "Content-Type": "application/json",
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        console.log('call week');
        console.log(data.data);
        console.log('end call week');
        if (data.data.days_this_report_app_calender !=0) {
            document.getElementById('days_this_report_app_calender').value = data.data.days_this_report_app_calender;
        }
         if (data.data.days_this_report_app_non_calender !=0) {
            document.getElementById('days_this_report_app_non_calender').value = data.data.days_this_report_app_non_calender;
        }
         if (data.data.days_previous_report_app_calender !=0) {
            document.getElementById('days_previous_report_app_calender').value = data.data.days_previous_report_app_calender;
        }
         if (data.data.days_previous_report_app_non_calender !=0) {
            document.getElementById('days_previous_report_app_non_calender').value = data.data.days_previous_report_app_non_calender;
        }

        if ( data.data.pwr_report_status == 'complete') {
            complete_status = true;
            document.getElementById('days_previous_report_app_calender').readOnly=true
            document.getElementById('days_previous_report_app_non_calender').readOnly=true
        }
        $('#report_id').text(report_id);
        var week_ending = data.data.pwr_week_ending;
        $('#week_ending').text(week_ending);
        $('#time_extension').val(data.data.pwr_time_extension);
        $('#remark_report').val(data.data.pwr_remarks);
        $('#type_name').val(data.data.pwr_type_name);
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


    // Weekly Statement days
    jQuery.ajax({
    url: baseUrl + "weekly-report-days/"+report_id,
        type: "GET",
        headers: {
          "Content-Type": "application/json",
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        // Foreach Loop
        window.pwrd_approved_calender_day = 0;
        window.pwrd_approved_non_calender_day = 0;
        window.pwrd_rain_day = 0;
        jQuery.each( data.data, function( i, val ) {
            // console.log(val);
             var sign_date = new Date(val.update_time);
            document.getElementById('sign_date').innerHTML = formatDate(sign_date);
            $('#calendar_week_days').append(
                '<tr>'+
                    '<td style="vertical-align: middle;">'+val.pwrd_date+'</td>'+
                    '<td><input type="hidden" class="form-control days_id" name="days_id[]" value="'+val.pwrd_id+'" id="">'+
                    '<input type="text" class="form-control days_weather" name="days_weather[]" value="'+val.pwrd_weather+'" id=""></td>'+
                    '<td><input type="number" min="0" max="1" class="form-control days_app_calender" onchange="checkvalue(this)" name="days_app_calender[]" value="'+val.pwrd_approved_calender_day+'" id="" required="required" pattern="(1|0)"></td>'+
                    '<td><input type="number" min="0" max="1" class="form-control days_app_non_calender" onchange="checkvalue(this)" name="days_app_non_calender[]" value="'+val.pwrd_approved_non_calender_day+'" id=""></td>'+
                    '<td><input type="number" min="0" max="1" class="form-control days_rainy_day" onchange="checkvalue(this)" name="days_rainy_day[]" value="'+val.pwrd_rain_day+'" id=""></td>'+
                '</tr>'
            );
            pwrd_approved_calender_day += parseInt(val.pwrd_approved_calender_day);
            pwrd_approved_non_calender_day += parseInt(val.pwrd_approved_non_calender_day);
            pwrd_rain_day += parseInt(val.pwrd_rain_day);
        });
            console.log(pwrd_approved_calender_day);
            console.log(pwrd_approved_non_calender_day);
            console.log(pwrd_rain_day);
        $('#calendar_days_app_calender').text(pwrd_approved_calender_day);
        $('#calendar_days_app_non_calender').text(pwrd_approved_non_calender_day);
        $('#calendar_days_app_raily_day').text(pwrd_rain_day);
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


    // Weekly Statement days
    jQuery.ajax({
    url: baseUrl + "weekly-report-days-count/"+project_id,
        type: "GET",
        headers: {
          "Content-Type": "application/json",
          "x-access-token": token
        },
          data: {
                    "report_id"               : report_id,
                },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {

        if (complete_status != true ) {

          
        console.log(data.data[0].pwrd_approved_calender_day);
        $('#days_previous_report_app_calender').val(data.data[0].pwrd_approved_calender_day);
       
        $('#days_previous_report_app_non_calender').val(data.data[0].pwrd_approved_non_calender_day);
        }

        if ( data.data[0].pwrd_approved_calender_day == null) {
             $('#calendar_previous_days_app_calender').text("0");
        }
         if ( data.data[0].pwrd_approved_non_calender_day == null) {
             $('#calendar_previous_days_app_non_calender').text("0");
        }
         if ( data.data[0].pwrd_rain_day == null) {
             $('#calendar_previous_days_app_raily_day').text("0");
        }
         
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

    setTimeout(function(){
        // Report Trigger Pass
        // var days_app_calender = 0;
        // $(this).find('.days_app_calender').each(function(){
        //     days_app_calender += parseInt($(this).val()); //<==== a catch  in here !! read below
        // });
        $('#calendar_days_app_calender').text(pwrd_approved_calender_day);
        // console.log(days_app_calender);
         if (complete_status != true ) {

             $('#days_this_report_app_calender').val(pwrd_approved_calender_day);
         }
       
        var calendar_previous_days_app_calender = $('#calendar_previous_days_app_calender').text();
        var calendar_previous_days_app_calender1 = parseInt(calendar_previous_days_app_calender);
        $('#calendar_total_days_app_calender').text(parseInt(pwrd_approved_calender_day+calendar_previous_days_app_calender1));
        // Total Day Approved
        if(parseInt($('#days_this_report_app_calender').val()))
            var days_this_report_app_calender1 = parseInt($('#days_this_report_app_calender').val());
        else
            var days_this_report_app_calender1 = 0;
        if(parseInt($('#days_previous_report_app_calender').val()))
            var days_previous_report_app_calender1 = parseInt($('#days_previous_report_app_calender').val());
        else
            var days_previous_report_app_calender1 = 0;
        if(parseInt(days_this_report_app_calender1)+parseInt(days_previous_report_app_calender1)!="NaN")
            $('#total_day_approved_app_calender').text(parseInt(days_this_report_app_calender1)+parseInt(days_previous_report_app_calender1));


        // var days_app_non_calender = 0;
        // $(this).find('.days_app_non_calender').each(function(){
        //     days_app_non_calender += parseInt($(this).val()); //<==== a catch  in here !! read below
        // });
        $('#calendar_days_app_non_calender').text(pwrd_approved_non_calender_day);
          if (complete_status != true ) {

             $('#days_this_report_app_non_calender').val(pwrd_approved_non_calender_day);
         }
        
        var calendar_previous_days_app_non_calender = $('#calendar_previous_days_app_non_calender').text();
        var calendar_previous_days_app_non_calender1 = parseInt(calendar_previous_days_app_non_calender);
        $('#calendar_total_days_app_non_calender').text(parseInt(pwrd_approved_non_calender_day+calendar_previous_days_app_non_calender1));
        // Total Day Approved
        var days_this_report_app_non_calender1 = parseInt($('#days_this_report_app_non_calender').val());
        var days_previous_report_app_non_calender1 = parseInt($('#days_previous_report_app_non_calender').val());
        $('#total_day_approved_app_non_calender').text(parseInt(days_this_report_app_non_calender1+days_previous_report_app_non_calender1));


        // var days_rainy_day = 0;
        // $(this).find('.days_rainy_day').each(function(){
        //     days_rainy_day += parseInt($(this).val()); //<==== a catch  in here !! read below
        // });
        $('#calendar_days_app_raily_day').text(pwrd_rain_day);
        var calendar_previous_days_app_raily_day = $('#calendar_previous_days_app_raily_day').text();
        var calendar_previous_days_app_raily_day1 = parseInt(calendar_previous_days_app_raily_day);
        $('#calendar_total_days_app_raily_day').text(parseInt(pwrd_rain_day+calendar_previous_days_app_raily_day1));

        // Revised Total Calender
        
        var notice_to_proceed_duration_day1 = parseInt($('#notice_to_proceed_duration_day').text());
        var total_day_approved_app_calender1 = parseInt($('#total_day_approved_app_calender').text());
        var total_day_approved_app_non_calender1 = parseInt($('#total_day_approved_app_non_calender').text());
        if(total_day_approved_app_non_calender1+notice_to_proceed_duration_day1+parseInt(total_day_approved_app_calender1))
            $('#revised_total_calender').text(total_day_approved_app_non_calender1+notice_to_proceed_duration_day1+total_day_approved_app_calender1);
    ///console.log(total_day_approved_app_non_calender1+notice_to_proceed_duration_day1+total_day_approved_app_calender1);

        // Calendar day charged
        $('#calendar_day_charged_app_calender').text(parseInt($('#calendar_total_days_app_calender').text()));
        $('#calendar_day_charged_app_non_calender').text(parseInt($('#calendar_total_days_app_non_calender').text()));

        // Revised Calendar Days Remaining in Contract
        var revised_total_calender1 = parseInt($('#revised_total_calender').text());
        var calendar_day_charged_app_calender1 = parseInt($('#calendar_day_charged_app_calender').text());
        var calendar_day_charged_app_non_calender1 = parseInt($('#calendar_day_charged_app_non_calender').text());
        $('#revised_calendar_day_remaining').text(revised_total_calender1-calendar_day_charged_app_calender1-calendar_day_charged_app_non_calender1);

        // Days due to Rain Days | Weather
        var calendar_total_days_app_raily_day1 = parseInt($('#calendar_total_days_app_raily_day').text());
        $('#day_due_to_rain').text(calendar_total_days_app_raily_day1);

        // Revised Computed Completion Date (Line 4 + Line 8 + Line 12)
        var date_final = $('#computed_completion_date').text();
        var total_day_approved_app_calender1 = parseInt($('#total_day_approved_app_calender').text());
        var total_day_approved_app_non_calender1 = parseInt($('#total_day_approved_app_non_calender').text());
        var day_due_to_rain1 = parseInt($('#day_due_to_rain').text());
        var total_days_plus = (parseInt(total_day_approved_app_calender1)+total_day_approved_app_non_calender1+day_due_to_rain1);
        var result = new Date(date_final);
        if(result.getDate())
        {
            result.setDate(result.getDate() + total_days_plus);
            var revised_completion_date = $.datepicker.formatDate('yy-mm-dd', new Date(result));
            //alert(revised_completion_date);
            if(revised_completion_date!="NaN-NaN-NaN")
                $('#revised_completion_date').text(revised_completion_date);
        }else{
            $('#revised_completion_date').text('');
        }
    }, 5000);


});


$('#calendar_week_days').on('input', function() {

    // var days_app_calender = 0;
    // $(this).find('.days_app_calender').each(function(){
    //     days_app_calender += parseInt($(this).val()); //<==== a catch  in here !! read below
    // });
    // $('#calendar_days_app_calender').text(days_app_calender);
    // $('#days_this_report_app_calender').text(days_app_calender);
    var calendar_previous_days_app_calender = $('#calendar_previous_days_app_calender').text();
    var calendar_previous_days_app_calender1 = parseInt(calendar_previous_days_app_calender);
    $('#calendar_total_days_app_calender').text(parseInt(pwrd_approved_calender_day+calendar_previous_days_app_calender1));
    // Total Day Approved
    var days_this_report_app_calender1 = parseInt($('#days_this_report_app_calender').val());
    var days_previous_report_app_calender1 = parseInt($('#days_previous_report_app_calender').val());
    $('#total_day_approved_app_calender').text(parseInt(days_this_report_app_calender1+days_previous_report_app_calender1));


    var days_app_non_calender = 0;
    $(this).find('.days_app_non_calender').each(function(){
        days_app_non_calender += parseInt($(this).val()); //<==== a catch  in here !! read below
    });
    $('#calendar_days_app_non_calender').text(days_app_non_calender);
    $('#days_this_report_app_non_calender').val(days_app_non_calender);
    var calendar_previous_days_app_non_calender = $('#calendar_previous_days_app_non_calender').text();
    var calendar_previous_days_app_non_calender1 = parseInt(calendar_previous_days_app_non_calender);
    $('#calendar_total_days_app_non_calender').text(parseInt(days_app_non_calender+calendar_previous_days_app_non_calender1));
    // Total Day Approved
    var days_this_report_app_non_calender1 = parseInt($('#days_this_report_app_non_calender').val());
    var days_previous_report_app_non_calender1 = parseInt($('#days_previous_report_app_non_calender').val());
    $('#total_day_approved_app_non_calender').text(parseInt(days_this_report_app_non_calender1)+parseInt(days_previous_report_app_non_calender1));


    var days_rainy_day = 0;
    $(this).find('.days_rainy_day').each(function(){
        days_rainy_day += parseInt($(this).val()); //<==== a catch  in here !! read below
    });
    $('#calendar_days_app_raily_day').text(days_rainy_day);
    var calendar_previous_days_app_raily_day = $('#calendar_previous_days_app_raily_day').text();
    var calendar_previous_days_app_raily_day1 = parseInt(calendar_previous_days_app_raily_day);
    $('#calendar_total_days_app_raily_day').text(parseInt(days_rainy_day+calendar_previous_days_app_raily_day1));

    // Revised Total Calender
    var notice_to_proceed_duration_day1 = parseInt($('#notice_to_proceed_duration_day').text());
    var total_day_approved_app_calender1 = parseInt($('#total_day_approved_app_calender').text());
    var total_day_approved_app_non_calender1 = parseInt($('#total_day_approved_app_non_calender').text());
    $('#revised_total_calender').text(parseInt(total_day_approved_app_non_calender1)+parseInt(notice_to_proceed_duration_day1)+parseInt(total_day_approved_app_calender1));

    // Calendar day charged
    $('#calendar_day_charged_app_calender').text(parseInt($('#calendar_total_days_app_calender').text()));
    $('#calendar_day_charged_app_non_calender').text(parseInt($('#calendar_total_days_app_non_calender').text()));

    // Revised Calendar Days Remaining in Contract
    var revised_total_calender1 = parseInt($('#revised_total_calender').text());
    var calendar_day_charged_app_calender1 = parseInt($('#calendar_day_charged_app_calender').text());
    var calendar_day_charged_app_non_calender1 = parseInt($('#calendar_day_charged_app_non_calender').text());
    $('#revised_calendar_day_remaining').text(revised_total_calender1-calendar_day_charged_app_calender1-calendar_day_charged_app_non_calender1);

    // Days due to Rain Days | Weather
    var calendar_total_days_app_raily_day1 = parseInt($('#calendar_total_days_app_raily_day').text());
    $('#day_due_to_rain').text(calendar_total_days_app_raily_day1);

    // Revised Computed Completion Date (Line 4 + Line 8 + Line 12)
    var date_final = $('#computed_completion_date').text();
    var total_day_approved_app_calender1 = parseInt($('#total_day_approved_app_calender').text());
    var total_day_approved_app_non_calender1 = parseInt($('#total_day_approved_app_non_calender').text());
    var day_due_to_rain1 = parseInt($('#day_due_to_rain').text());
    var total_days_plus = (total_day_approved_app_calender1+total_day_approved_app_non_calender1+day_due_to_rain1);
    var result = new Date(date_final);
    if(result.getFullYear())
    {
        result.setDate(result.getDate() + total_days_plus);
        var revised_completion_date = $.datepicker.formatDate('yy-mm-dd', new Date(result));
        $('#revised_completion_date').text(revised_completion_date);
    }else{
        $('#revised_completion_date').text('');
    }
});


    // alert(totalPoints);


$('#update_weekly_report').click(function(e) {
  $('.loading-submit').show();
    e.preventDefault();
    var project_id          = $('#project_id').val();
    var time_extension          = $('#time_extension').val();
    var remark_report           = $('#remark_report').val();
    var type_name               = $('#type_name').val();
    var token                   = localStorage.getItem('u_token');
    console.log(time_extension);
    console.log(remark_report);
    console.log(type_name);

    var is_error = false;

    var html;
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
    // Validation for bar
    var is_error_weather = false;
    $('input[name^=days_weather]').each(function(){
        if($(this).val()== '')
        {
             is_error_weather = true;
        }
    });
    if(is_error_weather == true){
        html += '<li> Day weather field is invalid </li>';
        is_error = true;
    }

    if(time_extension == ''){
        html += '<li>Time Extension / Change Orders field is invalid.</li>';
        is_error = true;
    }
    if(remark_report == ''){
        html += '<li>Remarks /  Notes field is invalid.</li>';
        is_error = true;
    }
    if(type_name == ''){
        html += '<li>Type name field is invalid.</li>';
        is_error = true;
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
    }

        var days_id = [];
        $('input[name^=days_id]').each(function(){
            days_id.push($(this).val());
        });

        var days_weather = [];
        $('input[name^=days_weather]').each(function(){
            days_weather.push($(this).val());
        });

        var days_app_calender = [];
        $('input[name^=days_app_calender]').each(function(){
            days_app_calender.push($(this).val());
        });

        var days_app_non_calender = [];
        $('input[name^=days_app_non_calender]').each(function(){
            days_app_non_calender.push($(this).val());
        });

        var days_rainy_day = [];
        $('input[name^=days_rainy_day]').each(function(){
                days_rainy_day.push($(this).val());
        });

        var item = {};
        item['days_id']                 = days_id;
        item['days_weather']            = days_weather;
        item['days_app_calender']       = days_app_calender;
        item['days_app_non_calender']   = days_app_non_calender;
        item['days_rainy_day']          = days_rainy_day;
        var item_lenght = $(days_id).length;
        item_final = [];
        for (i = 0; i < days_id.length; i++) {
               item_final.push({
                "days_id"               :  item['days_id'][i],
                "days_weather"          :  item['days_weather'][i],
                "days_app_calender"     :  item['days_app_calender'][i],
                "days_app_non_calender" :  item['days_app_non_calender'][i],
                "days_rainy_day"        :  item['days_rainy_day'][i],
            });
        }
        // console.log(item_final);

        // Update item in project_daily_quantity_completed
        jQuery.each(item_final, function(i, val) {
            console.log(val);
            var days_id                 = val.days_id;
            var days_weather            = val.days_weather;
            var days_app_calender       = val.days_app_calender;
            var days_app_non_calender   = val.days_app_non_calender;
            var days_rainy_day          = val.days_rainy_day;
            jQuery.ajax({
                url: baseUrl+"weekly-report-days-update/"+days_id,
                type: "POST",
                data: {
                    "days_id"               : days_id,
                    "days_weather"          : days_weather,
                    "days_app_calender"     : days_app_calender,
                    "days_app_non_calender" : days_app_non_calender,
                    "days_rainy_day"        : days_rainy_day
                },
                headers: {
                  "x-access-token": token
                },
                contentType: "application/x-www-form-urlencoded",
                cache: false
            })
            .done(function(data, textStatus, jqXHR) {
                console.log(data.data);
            })
        });

    jQuery.ajax({
        url: baseUrl + "weekly-report-update/"+report_id+"/update",
        type: "POST",
        data: {
            "project_id"           : project_id,
            "time_extension"       : time_extension,
            "days_this_report_app_calender"       : $('#days_this_report_app_calender').val(),
            "days_this_report_app_non_calender"       : $('#days_this_report_app_non_calender').val(),
            "days_previous_report_app_calender"       : $('#days_previous_report_app_calender').val(),
            "days_previous_report_app_non_calender"       : $('#days_previous_report_app_non_calender').val(),
            "remark_report"        : remark_report,
            "type_name"            : type_name
        },
        headers: {
            "x-access-token": token
        },
        contentType: "application/x-www-form-urlencoded",
        cache: false
    })
        .done(function(data, textStatus, jqXHR) {
            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New weekly report updated successfully!</div></div>';
            $("#alert_message").html(html);
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
            // $('html, body').animate({
            //     scrollTop: $(".page-head").offset().top
            // }, 'fast')
            // $("#alert_message").show();
            // html = '<<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            // if(responseText.data.contractor_name != null){
            //     html += '<li>The contract name is required.</li>';
            // }
            // if(responseText.data.date != null){
            //     html += '<li>The date of notice is required.</li>';
            // }
            // if(responseText.data.start_date != null){
            //     html += '<li>The start date is required.</li>';
            // }
            // if(responseText.data.duration != null){
            //     html += '<li>The duration (in days) is required.</li>';
            // }
            // if(responseText.data.liquidated_amount != null){
            //     html += '<li>The liquidated amount is required.</li>';
            // }
            // if(responseText.data.path != null){
            //     html += '<li>The document is required.</li>';
            // }
            // if(responseText.data.project_id != null){
            //     html += '<li>The project id field is required.</li>';
            // }
            // html += '</ul></div>';
            // $("#alert_message").html(html);
            // setTimeout(function(){
            //     $("#alert_message").hide()
            // },5000)
        })
});

$('#create_weekly_report').click(function () {

    var document_generated  = $("#pdf_gen_weekly_report").html();
    console.log(document_generated);
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
            console.log(data);
            var doc_path = data;

            jQuery.ajax({
                url: baseUrl + "document/add",
                type: "POST",
                data: {
                    "doc_path" : doc_path,
                    "doc_meta" : 'weekly_report',
                    "doc_project_id" : project_id
                },
                headers: {
                    "x-access-token": token
                },
                contentType: "application/x-www-form-urlencoded",
                cache: false
            })
                .done(function(data, textStatus, jqXHR) {
                    console.log(data);
                    $("#upload_success").fadeIn(1000).fadeOut(5000);
                    $("#upload_doc_id").val(data.description);
                    $('#cmd').hide();
                    $(".first_button").show();
                    $('.loading_data1').hide();

                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.log("HTTP Request Failed");
                    var responseText;
                    responseText = JSON.parse(jqXHR.responseText);
                    console.log(responseText.data);
                })
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var responseText;
            responseText = JSON.parse(jqXHR.responseText);
            console.log(responseText.data);
        })

});


function checkvalue( e ) {
        console.log(e);
      var max = parseInt($(e).attr('max'));
      var min = parseInt($(e).attr('min'));

       
     if ($(e).val() > max)
          {
              $(e).val(max);
          }
          else if ($(e).val() < min)
          {
              $(e).val(min);
          } 

 var dataList = document.querySelectorAll("."+e.classList[1]); 

  var sumvalue = 0;
 for (var i = 0; i < dataList.length; i++) {
     
     sumvalue = parseInt(sumvalue) + parseInt( dataList[i].value );

 }
 console.log(sumvalue);

 if (e.classList[1] == 'days_app_calender' ) {
    document.getElementById('calendar_days_app_calender').innerHTML=sumvalue;
    var app_calender = document.getElementById('calendar_previous_days_app_calender').innerHTML;
document.getElementById('calendar_total_days_app_calender').innerHTML = parseInt(app_calender)+parseInt(sumvalue);
document.getElementById('calendar_day_charged_app_calender').innerHTML = parseInt(app_calender)+parseInt(sumvalue);
 }

  if (e.classList[1] == 'days_app_non_calender' ) {
    document.getElementById('calendar_days_app_raily_day').innerHTML=sumvalue;
    var app_calender = document.getElementById('calendar_previous_days_app_non_calender').innerHTML;
document.getElementById('calendar_total_days_app_non_calender').innerHTML = parseInt(app_calender)+parseInt(sumvalue);
document.getElementById('calendar_day_charged_app_non_calender').innerHTML = parseInt(app_calender)+parseInt(sumvalue);
 }
 if (e.classList[1] == 'days_rainy_day' ) {
    document.getElementById('calendar_days_app_non_calender').innerHTML=sumvalue;
    var app_calender = document.getElementById('calendar_previous_days_app_raily_day').innerHTML;
document.getElementById('calendar_total_days_app_raily_day').innerHTML = parseInt(app_calender)+parseInt(sumvalue);
 }

}

function formatDate(date) {
  var monthNames = [
    "January", "February", "March",
    "April", "May", "June", "July",
    "August", "September", "October",
    "November", "December"
  ];

  var day = date.getDate();
  var monthIndex = date.getMonth();
  var year = date.getFullYear();

  return day + ' ' + monthNames[monthIndex] + ' ' + year;
}