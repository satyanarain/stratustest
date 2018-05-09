$(document).ready(function() {

    // Get login user profile data
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3]; // projects
    report_id = url[ url.length - 1]; // report
    console.log(project_id);
    console.log(report_id);

    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');


    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("weekly_report_view_all", check_user_access );
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
        // console.log(data.data);
        var f_name = data.data.contractor_name;
        $('#contractor_name').text(f_name);
        var project_name = data.data.p_name;
        $('#project_name').text(project_name);
        var notice_to_proceed_date = $.datepicker.formatDate('yy-mm-dd', new Date(data.data.pnp_date.replace(' ', 'T')));
        $('#notice_to_proceed_date').text(notice_to_proceed_date);
        var notice_to_proceed_start_date = $.datepicker.formatDate('yy-mm-dd', new Date(data.data.pnp_start_date.replace(' ', 'T')));
        $('#notice_to_proceed_start_date').text(notice_to_proceed_start_date);
        var notice_to_proceed_duration_day = data.data.pnp_duration;
        $('#notice_to_proceed_duration_day').text(notice_to_proceed_duration_day);
        var result = new Date(notice_to_proceed_start_date);
        result.setDate(result.getDate() + notice_to_proceed_duration_day);
        var computed_completion_date = $.datepicker.formatDate('yy-mm-dd', new Date(result));
        $('#computed_completion_date').text(computed_completion_date);
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        console.log(response);
        if(response == 403){
            window.location.href = baseUrl + "403";
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
        console.log(data.data);

          if (data.data.days_this_report_app_calender !=0) {
            document.getElementById('days_this_report_app_calender').innerHTML = data.data.days_this_report_app_calender;
        }
         if (data.data.days_this_report_app_non_calender !=0) {
            document.getElementById('days_this_report_app_non_calender').innerHTML = data.data.days_this_report_app_non_calender;
        }
         if (data.data.days_previous_report_app_calender !=0) {
            document.getElementById('days_previous_report_app_calender').innerHTML = data.data.days_previous_report_app_calender;
        }
         if (data.data.days_previous_report_app_non_calender !=0) {
            document.getElementById('days_previous_report_app_non_calender').innerHTML = data.data.days_previous_report_app_non_calender;
        }

        $('#report_id').text(report_id);
        var week_ending = data.data.pwr_week_ending;
        $('#week_ending').text(week_ending);
        var time_extension = data.data.pwr_time_extension;
        $('#time_extension').text(time_extension);
        var remark_report = data.data.pwr_remarks;
        $('#remark_report').text(remark_report);
        var type_name = data.data.pwr_type_name;
        $('#type_name').text(type_name);
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        if(response == 403){
            window.location.href = baseUrl + "403";
            // console.log("403");
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
        var days_app_calender = 0;
        var days_app_non_calender = 0;
        var days_rainy_day = 0;
        jQuery.each( data.data, function( i, val ) {
            // console.log(val);
             days_app_calender += parseInt(val.pwrd_approved_calender_day);
             days_app_non_calender += parseInt(val.pwrd_approved_non_calender_day);
             days_rainy_day += parseInt(val.pwrd_rain_day); 

              var sign_date = new Date(val.update_time);
            document.getElementById('sign_date').innerHTML = formatDate(sign_date);

            $('#calendar_week_days').append(
                '<tr>'+
                    '<td style="vertical-align: middle;">'+val.pwrd_date+'</td>'+
                    '<td style="text-align:center; vertical-align: middle;" class="days_weather">'+val.pwrd_weather+'</td>'+
                    '<td style="text-align:center; vertical-align: middle;" class="days_app_calender">'+val.pwrd_approved_calender_day+'</td>'+
                    '<td style="text-align:center; vertical-align: middle;" class="days_app_non_calender">'+val.pwrd_approved_non_calender_day+'</td>'+
                    '<td style="text-align:center; vertical-align: middle;" class="days_rainy_day">'+val.pwrd_rain_day+'</td>'+
                '</tr>'
            );
        });
        console.log(days_app_calender);
        $('#calendar_days_app_calender').text(parseInt(days_app_calender));
       // $('#days_this_report_app_calender').text(parseInt(days_app_calender));

        console.log(days_app_non_calender);
        $('#calendar_days_app_non_calender').text(parseInt(days_app_non_calender));
     //   $('#days_this_report_app_non_calender').text(parseInt(days_app_non_calender));

        console.log(days_rainy_day);
        $('#calendar_days_app_raily_day').text(parseInt(days_rainy_day));
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        if(response == 403){
            window.location.href = baseUrl + "403";
            // console.log("403");
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
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        // console.log(data);
          if ( data.data[0].pwrd_approved_calender_day == null) {
             $('#calendar_previous_days_app_calender').text("0");
        }
         if ( data.data[0].pwrd_approved_non_calender_day == null) {
             $('#calendar_previous_days_app_non_calender').text("0");
        }
         if ( data.data[0].pwrd_rain_day == null) {
             $('#calendar_previous_days_app_raily_day').text("0");
        }

             if ( data.data[0].pwrd_approved_calender_day == null) {
             //$('#days_previous_report_app_calender').text("0");
        }

             if ( data.data[0].pwrd_approved_non_calender_day == null) {
           //  $('#days_previous_report_app_non_calender').text("0");
        }
        //$('#calendar_previous_days_app_raily_day').text(data.data[0].pwrd_rain_day);
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        if(response == 403){
            window.location.href = baseUrl + "403";
            // console.log("403");
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
        
        var days_app_calender = parseInt($('#calendar_days_app_calender').text());
        var calendar_previous_days_app_calender = $('#calendar_previous_days_app_calender').text();
        var calendar_previous_days_app_calender1 = parseInt(calendar_previous_days_app_calender);
        $('#calendar_total_days_app_calender').text(parseInt(days_app_calender+calendar_previous_days_app_calender1));
        // Total Day Approved 
        var days_this_report_app_calender1 = parseInt($('#days_this_report_app_calender').text());
        var days_previous_report_app_calender1 = parseInt($('#days_previous_report_app_calender').text());
        $('#total_day_approved_app_calender').text(parseInt(days_this_report_app_calender1+days_previous_report_app_calender1));


        var days_app_non_calender = parseInt($('#calendar_days_app_non_calender').text());
        var calendar_previous_days_app_non_calender = $('#calendar_previous_days_app_non_calender').text();
        var calendar_previous_days_app_non_calender1 = parseInt(calendar_previous_days_app_non_calender);
        $('#calendar_total_days_app_non_calender').text(parseInt(days_app_non_calender+calendar_previous_days_app_non_calender1));
        // Total Day Approved 
        var days_this_report_app_non_calender1 = parseInt($('#days_this_report_app_non_calender').text());
        var days_previous_report_app_non_calender1 = parseInt($('#days_previous_report_app_non_calender').text());
        $('#total_day_approved_app_non_calender').text(parseInt(days_this_report_app_non_calender1+days_previous_report_app_non_calender1));
        
        var days_rainy_day = parseInt($('#calendar_days_app_raily_day').text());
        var calendar_previous_days_app_raily_day = $('#calendar_previous_days_app_raily_day').text();
        var calendar_previous_days_app_raily_day1 = parseInt(calendar_previous_days_app_raily_day);
        $('#calendar_total_days_app_raily_day').text(parseInt(days_rainy_day+calendar_previous_days_app_raily_day1));

        // Revised Total Calender
        var notice_to_proceed_duration_day1 = parseInt($('#notice_to_proceed_duration_day').text());
        var total_day_approved_app_calender1 = parseInt($('#total_day_approved_app_calender').text());
        var total_day_approved_app_non_calender1 = parseInt($('#total_day_approved_app_non_calender').text());
        $('#revised_total_calender').text(total_day_approved_app_non_calender1+notice_to_proceed_duration_day1+total_day_approved_app_calender1);
        // console.log(total_day_approved_app_non_calender1+notice_to_proceed_duration_day1+total_day_approved_app_calender1);
        
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
        if ( Object.prototype.toString.call(result) === "[object Date]" ) {
            // it is a date
            if ( isNaN( result.getTime() ) ) {  // d.valueOf() could also work
                // date is not valid
                var revised_completion_date = '';
            }
            else {
                // date is valid
                result.setDate(result.getDate() + parseInt(total_days_plus));
                var revised_completion_date = $.datepicker.formatDate('yy-mm-dd', new Date(result));
            }
        }
        else {
            // not a date
            var revised_completion_date = '';
        }
        
        $('#revised_completion_date').text(revised_completion_date);
    }, 3000);
    

});


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