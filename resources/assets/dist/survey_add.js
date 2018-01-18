$(document).ready(function() {
    // Get login user profile data
    $("#submit_new_btn").hide();
    // $("#bid_amount").hide();
    $('#upload_doc_id').removeAttr('value');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3]; // projects
    // console.log(project_id);

    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');

    // Check View All Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("survey_add", check_user_access );
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
    url: baseUrl + "standards/"+project_id+"/standard",
        type: "GET",
        headers: {
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        window.agency_id = data.data[0].ps_agency_name;
        $("#company_name").val(parseInt(agency_id));

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
            $('.loading_data').hide();
            var f_name = data.data.f_name;
            $('#pdf_gen_contractor_name').text(f_name);
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
            // console.log("403");
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
        var project_name = data.data.p_name;
        $('#pdf_gen_project_name').text(project_name);

        var project_description = data.data.p_description;
       $('#pdf_gen_project_description').text(project_description);
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
                        val.pt_name+'. '
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

    new_survey_number();
});

    function new_survey_number(){
        // Get New Submittal Number
        jQuery.ajax({
        url: baseUrl+"/"+project_id+"/survey-new-number",
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
            var sur_id = parseInt(data.data)+1;
            $('#survey_number').text(sur_id);
            $(".loading_data").hide();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            console.log(response);
            if(response == 403){
                window.location.href = baseUrl + "403";
            }
            else if(response == 404){
                // alert('faizan');
                $('#survey_number').text(1);
                // window.location.href = baseUrl + "404";
                $(".loading_data").hide();
            }
            else {
                window.location.href = baseUrl + "500";
            }
        });
    }

    // $('.submit_survey_form').click(function(e) {
    //   $('.loading-submit').show();
    //     e.preventDefault();

    //     var survey_number               = $('#survey_number').text();
    //     var survey_date                 = $('#survey_date').text();
    //     var survey_description          = $('#survey_description').val();
    //     var survey_completion_date      = $('#survey_completion_date').val();
    //     var request_expedited_review    = $("input[name='request_expedited_review']:checked").val();
    //     var file_path                   = $('#upload_doc_id').val();
    //     var project_id                  = $('#upload_project_id').val();
	   //  var token                       = localStorage.getItem('u_token');

    //     var request_expedited_review ='';
    //     var check_box = document.getElementById("request_expedited_review").checked;
    //     if(check_box ==true){
    //         request_expedited_review = "yes";
    //     }
    //     else if(check_box==false){
    //         request_expedited_review = 'no';
    //     }

    //     // console.log(survey_number);
    //     // console.log(survey_date);
    //     // console.log(survey_description);
    //     // console.log(survey_completion_date);
    //     console.log(request_expedited_review);
    //     // console.log(file_path);
    //     // console.log(project_id);
    //     jQuery.ajax({
    //         url: baseUrl + "survey/add",
    //         type: "POST",
    //         data: {
    //             "survey_date"                       : survey_date,
    //             "survey_description"                : survey_description,
    //             "survey_request_completion_date"    : survey_completion_date,
    //             "survey_request_expedited"          : request_expedited_review,
    //             "survey_request_path"               : file_path,
    //             "project_id"                        : project_id
    //         },
    //         headers: {
    //           "x-access-token": token
    //         },
    //         contentType: "application/x-www-form-urlencoded",
    //         cache: false
    //     })
    //     .done(function(data, textStatus, jqXHR) {
    //         console.log(data.description);
    //         // html = '<div class="alert alert-block alert-success fade in">'+data.description+'</div>';
    //         // $("#alert_message").html(html);
    //         $("#alert_message").fadeIn(1000);
    //         $('.loading-submit').hide();
    //         html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">'+data.description+'</div></div>';
    //         $("#alert_message").html(html);
    //         $("#alert_message").fadeOut(3000);
    //         $("#survey_description").removeAttr('value');
    //         $("#survey_completion_date").removeAttr('value');
    //         $('input[name="request_expedited_review"]').attr('checked', false);
    //         $("#upload_doc_id").removeAttr('value');
    //         $(".first_button").hide();
    //         $(".another_button").show();
    //         $('html, body').animate({
    //             scrollTop: $(".page-head").offset().top
    //         }, 'fast')
    //         setTimeout(function()
    //         {
    //             $("#alert_message").hide();
    //         },5000)
    //         new_survey_number();
    //     })
    //     .fail(function(jqXHR, textStatus, errorThrown) {
    //         console.log("HTTP Request Failed");
    //         var responseText, html;
    //         responseText = JSON.parse(jqXHR.responseText);
    //         console.log(responseText.data);
    //         $("#alert_message").fadeIn(1000);
    //         $('.loading-submit').hide();
    //         html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
    //         if(responseText.data.survey_date != null){
    //             html += '<li>The date is required.</li>';
    //         }
    //         if(responseText.data.survey_description != null){
    //             html += '<li>The description is required.</li>';
    //         }
    //         if(responseText.data.survey_request_completion_date != null){
    //             html += '<li>The survey request completion date is required.</li>';
    //         }
    //         if(responseText.data.survey_request_expedited != null){
    //             html += '<li>The survey request expedited is required.</li>';
    //         }
    //         if(responseText.data.project_id != null){
    //             html += '<li>The project id field is required.</li>';
    //         }
    //         html += '</ul></div>';
    //         $("#alert_message").html(html);
    //         $("#alert_message").fadeOut(4000);
    //     })
    // });





    $('.submit_survey_form').click(function () {
        $('.loading-submit').show();
        var survey_number = $('#survey_number').text();
        $('#pdf_gen_survey_number').text(survey_number);

        var survey_description = $('#survey_description').val()
        if(survey_description == '')
        {
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-error"><ul>';
            html += '<li>The description of request field is required</li>';
            html += '</ul></div>';
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").html(html);
            $("#alert_message").show();
            setTimeout(function()
            {
                $("#alert_message").hide();
            },5000);
            return false;
        }
        $('#pdf_gen_survey_description').text(survey_description);

        var survey_completion_date = $('#survey_completion_date').val();
        if(survey_completion_date == '')
        {
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-error"><ul>';
            html += '<li>The requested completion field is required </li>';
            html += '</ul></div>';
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").html(html);
            $("#alert_message").show();
            setTimeout(function()
            {
                $("#alert_message").hide();
            },5000);
            return false;
        }
        //var str = survey_completion_date;
        var date = new Date(survey_completion_date);
        var completion_day = date.getDate(); //Date of the month: 2 in our example
        var completion_month = date.getMonth(); //Month of the Year: 0-based index, so 1 in our example
        var completion_year = date.getFullYear(); //Year: 2013
        var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var dayName = days[date.getDay()];
        var completion_hour = date.getHours(); 
        var completion_time = date.getMinutes()
        $('#pdf_gen_req_comp_date').text(dayName+', '+completion_month+'-'+completion_day+'-'+completion_year);
        $('#pdf_gen_req_comp_time').text(completion_hour+':'+completion_time);

        var doc_meta = $("#upload_doc_meta").val();
        var doc_project_id = $("#upload_project_id").val();


        var document_generated  = $("#pdf_content").html();
        var document_path       = 'uploads/survey/';
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
            var doc_path = data;return false;
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

                // $("#upload_success").fadeIn(1000).fadeOut(5000);
                $("#upload_doc_id").val(data.description);
                // $(".first_button").hide();
                // $(".submit_survey_form").show();
                // $('.loading_data1').hide();
                setTimeout(function()
                {
                    var survey_number               = $('#survey_number').text();
                    var survey_date                 = $('#survey_date').text();
                    var survey_description          = $('#survey_description').val();
                    var survey_completion_date      = $('#survey_completion_date').val();
                    var request_expedited_review    = $("input[name='request_expedited_review']:checked").val();
                    var file_path                   = $('#upload_doc_id').val();
                    var project_id                  = $('#upload_project_id').val();
                    var token                       = localStorage.getItem('u_token');

                    var request_expedited_review ='';
                    var check_box = document.getElementById("request_expedited_review").checked;
                    if(check_box ==true){
                        request_expedited_review = "yes";
                    }
                    else if(check_box==false){
                        request_expedited_review = 'no';
                    }
                    jQuery.ajax({
                        url: baseUrl + "survey/add",
                        type: "POST",
                        data: {
                            "survey_number"                     : survey_number,
                            "survey_date"                       : survey_date,
                            "survey_description"                : survey_description,
                            "survey_request_completion_date"    : survey_completion_date,
                            "survey_request_expedited"          : request_expedited_review,
                            "survey_request_path"               : file_path,
                            "project_id"                        : project_id
                        },
                        headers: {
                          "x-access-token": token
                        },
                        contentType: "application/x-www-form-urlencoded",
                        cache: false
                    })
                    .done(function(data, textStatus, jqXHR) {
                        console.log(data.description);
                        $("#alert_message").fadeIn(1000);
                        $('.loading-submit').hide();
                        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New survey added successfully!</div></div>';
                        $("#alert_message").html(html);
                        $("#alert_message").fadeOut(3000);
                        $("#survey_description").removeAttr('value');
                        $("#survey_completion_date").removeAttr('value');
                        $('input[name="request_expedited_review"]').attr('checked', false);
                        $("#upload_doc_id").removeAttr('value');
                        $(".first_button").hide();
                        $(".another_button").show();
                        $('html, body').animate({
                            scrollTop: $(".page-head").offset().top
                        }, 'fast')
                        setTimeout(function()
                        {
                            $("#alert_message").hide();
                        },5000)
                        new_survey_number();
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        console.log("HTTP Request Failed");
                        var responseText, html;
                        responseText = JSON.parse(jqXHR.responseText);
                        console.log(responseText.data);
                        $("#alert_message").fadeIn(1000);
                        $('.loading-submit').hide();
                        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                        if(responseText.data.survey_date != null){
                            html += '<li>The date is required.</li>';
                        }
                        if(responseText.data.survey_description != null){
                            html += '<li>The description is required.</li>';
                        }
                        if(responseText.data.survey_request_completion_date != null){
                            html += '<li>The survey request completion date is required.</li>';
                        }
                        if(responseText.data.survey_request_expedited != null){
                            html += '<li>The survey request expedited is required.</li>';
                        }
                        if(responseText.data.project_id != null){
                            html += '<li>The project id field is required.</li>';
                        }
                        html += '</ul></div>';
                        $("#alert_message").html(html);
                        $("#alert_message").fadeOut(4000);
                    })



                },3000);
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
