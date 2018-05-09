$(document).ready(function() {
    // Get login user profile data
    $("#submit_new_btn").hide();
    // $("#bid_amount").hide();
    $("#submittal_number_exist").hide();
    $('#upload_doc_id').removeAttr('value');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3]; // projects
    console.log(project_id);

    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');

    // Check View All Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("submittal_add", check_user_access );
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
        url: baseUrl + "/"+project_id+"/default_contractor",
            type: "GET",
            headers: {
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            window.agency_id = data.data[0].pna_contactor_name;
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
                $('#contractor_name').text(f_name);
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
                // alert('faizan');
                // window.location.href = baseUrl + "404";
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

    get_new_submittal_data();
});


    function get_new_submittal_data(){
        // Select Rejected Submittal Name
        jQuery.ajax({
        url: baseUrl +"/"+project_id+"/get-submittal-log",
            type: "GET",
            headers: {
              "Content-Type": "application/json",
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            // console.log(data.data);
            $("#submittal_number_exist").empty();
            jQuery.each(data.data, function( i, val ) {
                // console.log(val);
                if(val.sub_exist_parent == 0){
                    //var sub_number = val.sub_id;
                    var sub_number = val.sub_number;
                }
                else {
                    //var sub_number = val.sub_exist_parent;
                    var sub_number = val.sub_number;
                }

                if(val.sr_review_type == 'make_corrections_noted' || val.sr_review_type == 'revise_resubmit' || val.sr_review_type == 'rejected'){
                    $("#submittal_number_exist").append(
                        '<option value="'+val.sr_exist_parent+'">'+sub_number+' - '+val.sub_description+'</option>'
                    )
                }
                else {
                }
            });
            $("#submittal_number_exist").show();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            console.log(response);
            if(response == 403){
                window.location.href = baseUrl + "403";
            }
            else if(response == 404){
                $("#resubmittal").hide();
                // window.location.href = baseUrl + "404";
                // $(".loading_data").hide();
            }
            else {
                window.location.href = baseUrl + "500";
            }
        });



        // Get New Submittal Number
        jQuery.ajax({
        url: baseUrl+"/"+project_id+"/submittal_new_number",
            type: "GET",
            headers: {
              "Content-Type": "application/json",
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            console.log(data.data.sub_number);
            var sub_number = data.data.sub_number;
            $('#submittal_number').val(parseInt(sub_number) + 1);
            $('#submittal_number_text').text(parseInt(sub_number) + 1);
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
                $('#submittal_number').val(1);
                $('#submittal_number_text').text(1);
                // window.location.href = baseUrl + "404";
                // $(".loading_data").hide();
            }
            else {
                window.location.href = baseUrl + "500";
            }
        });

        $('#submittal_number_exist').change(
            function() {
                var select_resub = $(this).val();
                // console.log(select_resub);
                $('#resubmittal_value').text(select_resub);
                // Get New Submittal Number
                jQuery.ajax({
                url: baseUrl+"/"+project_id+"/resubmittal_new_number/"+select_resub,
                    type: "GET",
                    headers: {
                      "Content-Type": "application/json",
                      "x-access-token": token
                    },
                    contentType: "application/json",
                    cache: false
                })
                .done(function(data, textStatus, jqXHR) {
                    // console.log(data.data.sub_rev_number);
                    var sub_rev_number = data.data.sub_rev_number;
                    $('#submittal_revision_number').text(parseInt(sub_rev_number)+1);
                    $(".loading_data1").hide();
                    $('.resubmittal_box').show();
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.log("HTTP Request Failed");
                    var response = jqXHR.responseJSON.code;
                    console.log(response);
                    if(response == 403){
                        window.location.href = baseUrl + "403";
                    }
                    else if(response == 404) {
                        // alert('faizan');
                        $('#submittal_revision_number').text(1);
                        $('.resubmittal_box').show();
                        $(".loading_data1").hide();
                        // window.location.href = baseUrl + "404";
                    }
                    else {
                        window.location.href = baseUrl + "500";
                    }
                });
            }
        );
    }

    $('#submit_submittal_form').click(function(e) {
      $('.loading-submit').show();
        e.preventDefault();

        var sub_type                        = $("input[name='check_submittal_type']:checked").val();
        var sub_number                      = $('#submittal_number').val();
        var sub_exist_parent                = $('#submittal_number_exist').val();
        var sub_rev_number                  = $('#submittal_revision_number').text();
        // var sub_submittal_type              = $('#resubmittal_reason').val();
        var sub_date                        = $('#date_of_submittal').val();
        var sub_description                 = $('#submittal_description').val();
        var sub_specification               = $('#submittal_spec').val();
        var sub_additional_comments         = $('#submittal_comments').val();
        var sub_request_expedited_review    = $('#request_expedited_review').val();
        var sub_additional_path             = $('#upload_doc_id').val();
        var project_id                      = $('#upload_project_id').val();
	    var token                           = localStorage.getItem('u_token');


        var check_box = document.getElementById("review_yes").checked;
        if(check_box ==true){
            var review_type = "Yes";
        }
        else {
            var review_type = 'No';
        }

        console.log(review_type);

        if(sub_type == 'exist'){
            sub_number = sub_exist_parent+ ' R '+sub_rev_number;
        }
        console.log(sub_type);
        console.log(sub_number);
        console.log(sub_exist_parent);
        console.log(sub_rev_number);
        console.log(sub_date);
        console.log(sub_description);
        console.log(sub_specification);
        console.log(sub_additional_comments);
        console.log(sub_request_expedited_review);
        console.log(sub_additional_path);
        console.log(review_type);
        console.log(project_id);

        // return true;
        jQuery.ajax({
           url: baseUrl + "submittal/add",
            type: "POST",
            data: {
                "submittal_type"                    : sub_type,
                "submittal_number"                  : sub_number,
                "submittal_exist_parent"            : sub_exist_parent,
                "submittal_rev_number"              : sub_rev_number,
                // "sub_submittal_type"                : sub_submittal_type,
                "submittal_date"                    : sub_date,
                "submittal_description"             : sub_description,
                "submittal_specification"           : sub_specification,
                "submittal_additional_comments"     : sub_additional_comments,
                "submittal_request_expedited_review": sub_request_expedited_review,
                "submittal_additional_path"         : sub_additional_path,
                "submittal_review_type"             : review_type,
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
            if(sub_type == 'exist'){
                $(".preliminary_notice_button").show();
            }
            // html = '<div class="alert alert-block alert-success fade in">'+data.description+'</div>';
            // $("#alert_message").html(html);
            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New submittal added successfully!</div></div>';
            $("#alert_message").html(html);
            $('input[name="check_submittal_type"]').attr('checked', false);
            $('input[name="review_yes"]').attr('checked', false);
            $("#submittal_number").removeAttr('value');
            $("#date_of_submittal").removeAttr('value');
            $("#submittal_description").removeAttr('value');
            $("#submittal_spec").removeAttr('value');
            $("#submittal_comments").val('');
            // $('#submittal_comments').data("wysihtml5").editor.clear();
            // $('#request_expedited_review').data("wysihtml5").editor.clear();
            $("#upload_doc_id").removeAttr('value');
            $(".remove_file_drop").trigger("click");
            // $(".first_button").hide();
            // $(".another_button").show();
            $(".first_button").text('Save Another');
            $('.complete_box').hide();
            $('.expedited_review_checkbox').hide();
            $('.request_expedited_review').hide();
            $('#request_expedited_review').val('');

            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            setTimeout(function(){
                $("#alert_message").hide()
            },5000)
            get_new_submittal_data();

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
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"  style="margin-top:50px;"><div class="toast toast-error"><ul>';
                if(responseText.data.submittal_type != null){
                    html += '<li>The submittal type is required.</li>';
                }
                if(responseText.data.submittal_number != null){
                    html += '<li>The submittal number is required.</li>';
                }
                if(responseText.data.submittal_date != null){
                    html += '<li>The submittal date is required.</li>';
                }
                if(responseText.data.submittal_description != null){
                    html += '<li>Description field is invalid.</li>';
                }
                if(responseText.data.submittal_specification != null){
                    html += '<li>Specification field is invalid.</li>';
                }
                if(responseText.data.submittal_additional_path != null){
                    html += '<li>Document field is invalid.</li>';
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
    });
