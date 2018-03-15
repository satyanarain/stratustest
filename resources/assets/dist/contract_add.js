$(document).ready(function() {

    // Get login user profile data
    $("#submit_new_btn").hide();
    $("#bid_amount").hide();
    $("#company_name").hide();
    $('#upload_doc_id').removeAttr('value');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3]; // projects
    console.log(project_id);

    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');

    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("contract_add", check_user_access );
    console.log(check_permission);
    if(check_permission < 1){
        window.location.href = baseUrl + "403";
    }
    else { 
        console.log('Yes Permission');
        $('.body-content .wrapper').show();
    }

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
            var agency_id = data.data[0].pna_contactor_name;
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
            // console.log(data.data[0].total_amount);
            $("#bid_amount").val(data.data[0].total_amount);
            $("#pdf_gen_contract_amount").text(data.data[0].total_amount);

            $(".loading_data").hide();
            $("#bid_amount").show();
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




});


    $('.submit_notice_add_form').click(function(e) {
      $('.loading-submit').show();
        e.preventDefault();
        var check_contract_type     = 'exist'; //$("input[name='check_contract_type']:checked"). val();
        var company_name            = $('#company_name').val();
        var bid_amount              = $('#bid_amount').val();
        var award_date              = $('#award_date').val();
        var upload_doc_id           = $('#upload_doc_id').val();
        var project_id              = $('#upload_project_id').val();
	    var token                   = localStorage.getItem('u_token');

        console.log(bid_amount);
        jQuery.ajax({
            url: baseUrl + "contract/add",
            type: "POST",
            data: {
                "contract_status"         : check_contract_type,
                "contractor_name"        : company_name,
                "contract_amount"        : bid_amount,
                "contract_date"            : award_date,
                "contract_path"           : upload_doc_id,
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
            // html = '<div class="alert alert-block alert-success fade in">'+data.description+'</div>';
            // $("#alert_message").html(html);

                $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
                }, 'fast')
                $(".remove_file_drop").trigger("click");
                $('#upload_doc_id').removeAttr('value');
                $('#award_date').removeAttr('value');
                $("#alert_message").show();
                $(".first_button").text('Save Another');
                // $(".another_button").show();
                $('.loading-submit').hide();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New contract added successfully!</div></div>';
                $("#alert_message").html(html);
                $("#alert_message").show();
                $('.loading-submit').hide();

                setTimeout(function()
                {
                    $("#alert_message").hide();
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
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-error"><ul>';

                if(responseText.data.contract[0] != null){
                    html += '<li>The contract file is required.</li>';
                }

                html += '</ul></div>';
                $("#alert_message").html(html);
                $("#alert_message").show();
                $('.loading-submit').hide();
                setTimeout(function()
                {
                    $("#alert_message").hide();
                },5000)
        })
    });

    $('#cmd').click(function () {
        console.log('faizan');

        $('.loading_data1').show();
        var doc_meta = $("#upload_doc_meta").val();
        var doc_project_id = $("#upload_project_id").val();

        var document_generated  = $("#notice_award_pdf_content").html();
        var document_path       = 'uploads/contract/';
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
                console.log(data);
                $('.loading_data1').hide();
                $("#upload_success").fadeIn(1000).fadeOut(3000);
                $("#upload_doc_id").val(data.description);
                $('#cmd').hide();
                $("#submit_new_btn").show();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                    $('.loading_data1').hide();
                    console.log("HTTP Request Failed");
                    var responseText;
                    responseText = JSON.parse(jqXHR.responseText);
                    console.log(responseText.data);
            })
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                $('.loading_data1').hide();
                console.log("HTTP Request Failed");
                var responseText;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(responseText.data);
        })

    });
