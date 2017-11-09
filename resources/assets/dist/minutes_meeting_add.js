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
    var check_permission = jQuery.inArray("meeting_minutes_add", check_user_access );
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
            console.log(agency_id);
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
                $('.pdf_gen_contractor_name').text(f_name);
                $('#contractor_name').text(f_name);
                var firm_address = data.data.f_address;
                $('.pdf_gen_contractor_address').text(firm_address);
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
            $(".pdf_gen_contract_amount").text(data.data[0].total_amount);
            $(".pdf_gen_contract_amount").show();
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
            var project_name = data.data.p_name;
            $('.pdf_gen_project_name').text(project_name);

            var project_description = data.data.p_description;
            $('#project_description').val(project_description);
            $('.pdf_gen_project_description').val(project_description);
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
                    $(".pdf_gen_project_type").append(
                        val.pt_name+', '
                    )
                }
            });
        });

});

    $('#add_another_special').click(function(e) {
        console.log('add another');
        $('#special_considerations_panel').append(
            '<div class="form-group col-md-12">'+
                '<label>Special Considerations</label>'+
                '<a class="btn btn-danger btn-xs remove_another_special" style="float: right;"><i class="fa fa-minus"></i> Remove</a>'+
                '<textarea class="form-control special_considerations" name="special_considerations[]"></textarea>'+
            '</div>'
        );
    });




    $('.submit_notice_add_form').click(function(e) {
        e.preventDefault();
        $('.loading-submit').show();

        var agenda_path             = $('#upload_doc_id_auto').val();
        var signin_sheet_path       = $('#upload_doc_id_work').val();
        var meeting_minutes_path    = $('#upload_doc_id_general').val();
        var special_considerations  = $(".special_considerations").val();

        // Validation Certificate
        var html;
        var is_error = false;
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';

        if(meeting_minutes_path == ''){
            html += '<li>Meeting Minutes document field is invalid.</li>';
            is_error = true;
        }
        if(signin_sheet_path == ''){
            html += '<li>Sign-in Sheet document field is invalid.</li>';
            is_error = true;
        }
        if(agenda_path == ''){
            html += '<li>Agenda document field is invalid.</li>';
            is_error = true;
        }
        if(special_considerations == ''){
            html += '<li>Special Considerations field is invalid.</li>';
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
                $("#alert_message").hide();
                $('.loading_data1').hide();
            },5000)
            return false;
        }

        var special_considerations = $(".special_considerations").val();
        if(special_considerations == ''){
            var special_considerations = '';
        }
        else {
            var internal_ad = [];
            var internal_required = []
            $(".special_considerations").each(function() {
                internal_ad.push({value:$(this).val()});
                internal_required = $(this).val()
            });
            var special_considerations = JSON.stringify(internal_ad);
            $('.pdf_gen_special_considerations').text(special_considerations);
            console.log(special_considerations);
        }

        // setTimeout(function(){
        //     // Minutes of meeting pdf generate
        //     if($('input:radio[name=minute_type]:checked').val() == "exist"){
        //         var meeting_minutes_path    = $('#upload_doc_id_general').val();
        //         console.log(meeting_minutes_path);
        //     }
        //     else {
        //         console.log('new minutes');
        //         var min_of_meeting_text = $("#minutes_new").val();
        //         if(min_of_meeting_text == ''){
        //             var meeting_minutes_path = '';
        //         }
        //         else {
        //             $('.pdf_gen_min_of_meeting_text').text(min_of_meeting_text);
        //             console.log(min_of_meeting_text);
        //             var date_meeting = $("#meeting_date").val();
        //             $('.pdf_gen_date_meeting').text(date_meeting);
        //             var project_description = $("#project_description").val();
        //             $('.pdf_gen_project_description').text(project_description);

        //             $('.loading_data1').show();
        //             var doc_meta = $("#upload_doc_meta").val();
        //             var doc_project_id = $("#upload_project_id").val();
        //             var doc = new jsPDF();
        //             var specialElementHandlers = {
        //                 'DIV to be rendered out': function(element, renderer){
        //                 return true;
        //                 }
        //             };

        //             // var html=$("#notice_award_pdf_content").html();
        //             doc.fromHTML($('#pdf_content_minutes').get(0), 15, 15, {
        //             // doc.fromHTML(html,15,15, {
        //                 'width': 170,
        //                 'elementHandlers': specialElementHandlers
        //             });

        //             var pdf =doc.output(); //returns raw body of resulting PDF returned as a string as per the plugin documentation.
        //             var data = new FormData();
        //             data.append("document_generated" , pdf);
        //             data.append("document_path" , 'uploads/minutes_meeting/');
        //             var xhr = new XMLHttpRequest();
        //             xhr.open( 'post', baseUrl+'document/CreateUploadFiles', true ); //Post to php Script to save to server
        //             xhr.send(data);
        //             xhr.onreadystatechange = function() {
        //                 if (xhr.readyState == XMLHttpRequest.DONE) {
        //                     var document_response = xhr.responseText;
        //                     var document_response = document_response.replace(/\\/g, '');;
        //                     var document_response = document_response.replace('"', '');;
        //                     var document_response = document_response.replace('"', '');;
        //                     var doc_path = document_response;
        //                     jQuery.ajax({
        //                        url: baseUrl + "document/add",
        //                         type: "POST",
        //                         data: {
        //                             "doc_path" : doc_path,
        //                             "doc_meta" : doc_meta,
        //                             "doc_project_id" : doc_project_id
        //                         },
        //                         headers: {
        //                           "x-access-token": token
        //                         },
        //                         contentType: "application/x-www-form-urlencoded",
        //                         cache: false
        //                     })
        //                     .done(function(data, textStatus, jqXHR) {
        //                         console.log(data);
        //                         $("#upload_doc_id_general").val(data.description);
        //                    })
        //                     .fail(function(jqXHR, textStatus, errorThrown) {
        //                         console.log("HTTP Request Failed");
        //                         var responseText;
        //                         responseText = JSON.parse(jqXHR.responseText);
        //                         console.log(responseText.data);
        //                     })
        //                 }
        //             }
        //         } // minutes of meeting pdf generate close
        //     }//  // minutes of meeting else close

            // setTimeout(function(){
            //     // Signin sheet pdf generate
            //     if($('input:radio[name=signinsheet_type]:checked').val() == "exist"){
            //         var singin_path    = $('#upload_doc_id_work').val();
            //         console.log(singin_path);
            //     }
            //     else {
            //         console.log('new signin');
            //         var signin_text = $("#signinsheet_new").val();
            //         if(signin_text == ''){
            //             var singin_path = '';
            //         }
            //         else {
            //             $('.pdf_gen_signin_text').text(signin_text);
            //             console.log(signin_text);
            //             var date_meeting = $("#meeting_date").val();
            //             $('.pdf_gen_date_meeting').text(date_meeting);
            //             var project_description = $("#project_description").val();
            //             $('.pdf_gen_project_description').text(project_description);

            //             $('.loading_data1').show();
            //             var doc_meta = $("#upload_doc_meta").val();
            //             var doc_project_id = $("#upload_project_id").val();
            //             var doc = new jsPDF();
            //             var specialElementHandlers = {
            //                 'DIV to be rendered out': function(element, renderer){
            //                 return true;
            //                 }
            //             };

            //             // var html=$("#notice_award_pdf_content").html();
            //             doc.fromHTML($('#pdf_content_signin').get(0), 15, 15, {
            //             // doc.fromHTML(html,15,15, {
            //                 'width': 170,
            //                 'elementHandlers': specialElementHandlers
            //             });

            //             var pdf =doc.output(); //returns raw body of resulting PDF returned as a string as per the plugin documentation.
            //             var data = new FormData();
            //             data.append("document_generated" , pdf);
            //             data.append("document_path" , 'uploads/minutes_meeting/');
            //             var xhr = new XMLHttpRequest();
            //             xhr.open( 'post', baseUrl+'document/CreateUploadFiles', true ); //Post to php Script to save to server
            //             xhr.send(data);
            //             xhr.onreadystatechange = function() {
            //                 if (xhr.readyState == XMLHttpRequest.DONE) {
            //                     var document_response = xhr.responseText;
            //                     var document_response = document_response.replace(/\\/g, '');;
            //                     var document_response = document_response.replace('"', '');;
            //                     var document_response = document_response.replace('"', '');;
            //                     var doc_path = document_response;
            //                     jQuery.ajax({
            //                        url: baseUrl + "document/add",
            //                         type: "POST",
            //                         data: {
            //                             "doc_path" : doc_path,
            //                             "doc_meta" : doc_meta,
            //                             "doc_project_id" : doc_project_id
            //                         },
            //                         headers: {
            //                           "x-access-token": token
            //                         },
            //                         contentType: "application/x-www-form-urlencoded",
            //                         cache: false
            //                     })
            //                     .done(function(data, textStatus, jqXHR) {
            //                         console.log(data);
            //                         $("#upload_doc_id_work").val(data.description);
            //                         // $('.loading_data1').hide();
            //                     })
            //                     .fail(function(jqXHR, textStatus, errorThrown) {
            //                         console.log("HTTP Request Failed");
            //                         var responseText;
            //                         responseText = JSON.parse(jqXHR.responseText);
            //                         console.log(responseText.data);
            //                     })
            //                 }
            //             }
            //         } // sign-in sheet pdf generate close
            //     }//  // sign-in sheet else close

                // setTimeout(function(){
                //     // Agenda PDF Generate Open
                //     var date_meeting = $("#meeting_date").val();
                //     $('.pdf_gen_date_meeting').text(date_meeting);
                //     var project_description = $("#project_description").val();
                //     $('.pdf_gen_project_description').text(project_description);

                //     var doc_meta = $("#upload_doc_meta").val();
                //     var doc_project_id = $("#upload_project_id").val();

                //     var doc = new jsPDF();
                //     var specialElementHandlers = {
                //         'DIV to be rendered out': function(element, renderer){
                //         return true;
                //         }
                //     };

                //     // var html=$("#notice_award_pdf_content").html();
                //     doc.fromHTML($('#pdf_content_agenda').get(0), 15, 15, {
                //     // doc.fromHTML(html,15,15, {
                //         'width': 170,
                //         'elementHandlers': specialElementHandlers
                //     });

                //     var pdf =doc.output(); //returns raw body of resulting PDF returned as a string as per the plugin documentation.
                //     var data = new FormData();
                //     data.append("document_generated" , pdf);
                //     data.append("document_path" , 'uploads/minutes_meeting/');
                //     var xhr = new XMLHttpRequest();
                //     xhr.open( 'post', baseUrl+'document/CreateUploadFiles', true ); //Post to php Script to save to server
                //     xhr.send(data);
                //     xhr.onreadystatechange = function() {
                //         if (xhr.readyState == XMLHttpRequest.DONE) {
                //             var document_response = xhr.responseText;
                //             var document_response = document_response.replace(/\\/g, '');
                //             var document_response = document_response.replace('"', '');
                //             var document_response = document_response.replace('"', '');
                //             var doc_path = document_response;
                //             jQuery.ajax({
                //                url: baseUrl + "document/add",
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
                //                 // $("#upload_success").show()
                //                 $("#upload_agenda_id").val(data.description);
                //             })
                //             .fail(function(jqXHR, textStatus, errorThrown) {
                //                 console.log("HTTP Request Failed");
                //                 var responseText;
                //                 responseText = JSON.parse(jqXHR.responseText);
                //                 console.log(responseText.data);
                //             })
                //         }
                //     }

                    setTimeout(function(){
                        var contractor_name         = agency_id;
                        var date                    = $('#meeting_date').val();
                        var description             = $('#project_description').val();
                        var special                 = special_considerations;
                        var agenda_path             = $('#upload_doc_id_auto').val();
                        var signin_sheet_path       = $('#upload_doc_id_work').val();
                        var meeting_minutes_path    = $('#upload_doc_id_general').val();
                        var project_id              = $('#upload_project_id').val();
                        var token                   = localStorage.getItem('u_token');

                        // console.log(cal_day);
                        jQuery.ajax({
                            url: baseUrl + "minutes-meeting/add",
                            type: "POST",
                            data: {
                                "contractor_id"         : contractor_name,
                                "date"                  : date,
                                "description"           : description,
                                "special"               : special,
                                "agenda_path"           : agenda_path,
                                "signin_sheet_path"     : signin_sheet_path,
                                "meeting_minutes_path"  : meeting_minutes_path,
                                "project_id"            : project_id
                            },
                            headers: {
                              "x-access-token": token
                            },
                            contentType: "application/x-www-form-urlencoded",
                            cache: false
                        })
                        .done(function(data, textStatus, jqXHR) {
                            console.log(data.description);
                            // $(".dz-file-preview").remove();
                            // $('#project_description').removeAttr('value');
                            $('#special_considerations').removeAttr('value');
                            $('#minutes_new').removeAttr('value');
                            $('#signinsheet_new').removeAttr('value');
                            $("#upload_doc_id_auto").removeAttr('value');
                            $("#upload_doc_id_work").removeAttr('value');
                            $("#upload_doc_id_general").removeAttr('value');
                            $('input[name="minute_type"]').attr('checked', false);
                            $('input[name="signinsheet_type"]').attr('checked', false);
                            $(".first_button").text('Save Another');
                            $(".remove_file_drop").trigger("click");
                            // $(".minutes_already").hide();
                            // $(".signinsheet_already").hide();
                            // $(".agenda_type_already").hide();
                            $(".new_append").remove();
                            // setTimeout(function(){
                            //     $("#alert_message").hide()
                            //     window.location.reload();
                            // },5000)


                            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New meeting minutes added successfully!</div></div>';
                            $("#alert_message").html(html);
                            $('html, body').animate({
                                scrollTop: $(".page-head").offset().top
                            }, 'fast')
                            $(".loading-submit").hide();
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

                            $('.loading-submit').hide();
                            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"  style="margin-top:50px;"><div class="toast toast-error"><ul>';
                            if(responseText.data.contractor_id != null){
                                html += '<li>The contract name is required.</li>';
                            }
                            if(responseText.data.date != null){
                                html += '<li>The date of notice is required.</li>';
                            }
                            if(responseText.data.description != null){
                                html += '<li>The project description is required.</li>';
                            }
                            if(responseText.data.special != null){
                                html += '<li>The special considerations is required.</li>';
                            }
                            if(responseText.data.meeting_minutes_path != null){
                                html += '<li>The minutes of meeting document is required.</li>';
                            }
                            if(responseText.data.pm_agenda_path != null){
                                html += '<li>The agenda of meeting document is required.</li>';
                            }
                            if(responseText.data.project_id != null){
                                html += '<li>The project id field is required.</li>';
                            }
                            html += '</ul></div>';

                            $('html, body').animate({
                                scrollTop: $(".page-head").offset().top
                            }, 'fast')
                            $("#alert_message").html(html);
                            $("#alert_message").show();
                            setTimeout(function(){
                                $("#alert_message").hide()
                                $(".loading_data1").hide();
                            },3000)
                        })
                    },3000) // FINAL INSERT CODE
                // },3000) // Agenda PDF Generate Close
            // },3000) // Signin Sheet PDF Generate Close
        // },3000)  // Meeting PDF Generate Close




    });

    $('#create_agenda').click(function () {



        // var check = $('.special_considerations').val()
        // if(check=='')
        // {
        //     $("#alert_message").show();
        //     html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
        //     html += '<li>The special considerations field is required     </li>';
        //     html += '</ul></div>';
        //     $('html, body').animate({
        //         scrollTop: $(".page-head").offset().top
        //     }, 'fast')
        //     $("#alert_message").html(html);
        //     setTimeout(function(){
        //         $("#alert_message").hide()
        //     },3000)

        //     return false;

        // }
        // var date_meeting = $("#meeting_date").val();
        // $('.pdf_gen_date_meeting').text(date_meeting);
        // var project_description = $("#project_description").val();
        // $('.pdf_gen_project_description').text(project_description);

        // var internal_ad = [];
        // var  internal_required = []
        // $(".special_considerations").each(function() {
        //     internal_ad.push($(this).val());
        //     internal_required = $(this).val()
        // });
        // $('.pdf_gen_special_considerations').text(internal_ad);
        // var special_considerations = JSON.stringify(internal_ad);
        // console.log(special_considerations);

        // $('.loading_data1').show();

        // var doc_meta = $("#upload_doc_meta").val();
        // var doc_project_id = $("#upload_project_id").val();

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
        // data.append("document_path" , 'uploads/minutes_meeting/');
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

        //         console.log(doc_path);
        //         console.log(doc_meta);
        //         console.log(doc_project_id);
        //         jQuery.ajax({
        //            url: baseUrl + "document/add",
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
        //             $("#upload_success").show()
        //             $("#upload_agenda_id").val(data.description);

        //                 $('html, body').animate({
        //                     scrollTop: $(".page-head").offset().top
        //                 }, 'fast')
        //                 setTimeout(function(){
        //                     $("#upload_success").hide()
        //                 },5000)

        //             $('.loading_data1').hide();


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



    $('#create_signin_sheet').click(function () {
        var check = $('.special_considerations').val()
        if(check=='')
        {

            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            html += '<li>The special considerations field is required     </li>';
            html += '</ul></div>';
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").html(html);
            setTimeout(function(){
                $("#alert_message").hide()
            },3000)
            return false;

        }
        var date_meeting = $("#meeting_date").val();
        $('.pdf_gen_date_meeting').text(date_meeting);
        var project_description = $("#project_description").val();
        $('.pdf_gen_project_description').text(project_description);

        var internal_ad = [];
        var  internal_required = []
        $(".special_considerations").each(function() {
            internal_ad.push($(this).val());
            internal_required = $(this).val()
        });
        $('.pdf_gen_special_considerations').text(internal_ad);
        var special_considerations = JSON.stringify(internal_ad);

        var doc_meta = $("#upload_doc_meta").val();
        var doc_project_id = $("#upload_project_id").val();

        $('.loading_data1').show();

        var doc = new jsPDF();
        var specialElementHandlers = {
            'DIV to be rendered out': function(element, renderer){
            return true;
            }
        };

        // var html=$("#notice_award_pdf_content").html();
        doc.fromHTML($('#pdf_content').get(0), 15, 15, {
        // doc.fromHTML(html,15,15, {
            'width': 170,
            'elementHandlers': specialElementHandlers
        });

        var pdf =doc.output(); //returns raw body of resulting PDF returned as a string as per the plugin documentation.
        var data = new FormData();
        data.append("document_generated" , pdf);
        data.append("document_path" , 'uploads/minutes_meeting/');
        var xhr = new XMLHttpRequest();
        xhr.open( 'post', baseUrl+'document/CreateUploadFiles', true ); //Post to php Script to save to server
        xhr.send(data);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                var document_response = xhr.responseText;
                var document_response = document_response.replace(/\\/g, '');;
                var document_response = document_response.replace('"', '');;
                var document_response = document_response.replace('"', '');;

                var doc_path = document_response;

                console.log(doc_path);
                console.log(doc_meta);
                console.log(doc_project_id);
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
                    $("#upload_signin_sheet_id").val(data.description);
                    $('.loading_data1').hide();
                        $("#upload_success").show();
                        $('html, body').animate({
                            scrollTop: $(".page-head").offset().top
                        }, 'fast')
                        setTimeout(function(){
                            $("#upload_success").hide()
                        },5000)

                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                        console.log("HTTP Request Failed");
                        var responseText;
                        responseText = JSON.parse(jqXHR.responseText);
                        console.log(responseText.data);
                })
            }
        }
    });





    $('.remove_another_special').on('click', function(e) {
        alert("t3")
        $(this).parents('.form-group').remove();
        e.preventDefault();
    });




var max_fields      = 100; //maximum input boxes allowed
var wrapper         = $(".input_fields_wrap"); //Fields wrapper
var add_button      = $(".add_field_button"); //Add button ID
var x = 1; //initlal text box count
$(add_button).click(function(e){ //on add input button click
    e.preventDefault();
    if(x < max_fields){ //max input box allowed
        x++; //text box increment
        $(wrapper).append(
            '<div class="form-group col-md-12 new_append">'+
                '<label>Special Considerations  <span class="text-danger">*</span></label>'+
                '<a class="btn btn-danger btn-xs remove_field" style="float: right;"><i class="fa fa-minus"></i> Remove</a>'+
                '<textarea class="form-control special_considerations" name="special_considerations[]"></textarea>'+
                '</div>'); //add input box
    }
});
$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
    e.preventDefault(); $(this).parent('div').remove(); x--;
})
