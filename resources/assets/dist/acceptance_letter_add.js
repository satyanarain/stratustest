$(document).ready(function() {
    // Get login user profile data
    $('#upload_doc_id').removeAttr('value');
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');

     var url = $(location).attr('href').split( '/' );
    project_id  = url[ url.length - 3 ]; // projects
    console.log(project_id);
    
     // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("agency_acceptance_add", check_user_access );
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
        $("#company_name").append(
            '<option value="">Select Agency Name</option>'
        )
        jQuery.each(data.data, function( i, val ) {
            if(val.f_status == 'active'){
                $("#company_name").append(
                    '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                )
            }else {

            }
        });
        // $( "h2" ).appendTo( $( ".container" ) );

        $(".loading_data").remove();
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
});


$('#add_acceptance_letter_form').click(function(e) {
  $('.loading-submit').show();
    e.preventDefault();
    var date                    = $('#date').val();
    var name                    = $('#company_name').val();
    var file_path               = $('#upload_doc_id').val();
    var project_id              = $('#upload_project_id').val();
    var token                   = localStorage.getItem('u_token');
    console.log(date);
    jQuery.ajax({
        url: baseUrl + "acceptance_letter/add",
        type: "POST",
        data: {
            "date"              : date,
            "name"              : name,
            "file_path"         : file_path,
            "project_id"        : project_id
        },
        headers: {
            "x-access-token": token
        },
        contentType: "application/x-www-form-urlencoded",
        cache: false
    })
        .done(function(data, textStatus, jqXHR) {
            console.log(data.description);
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New agency acceptance documentation added successfully!</div></div>';
            $("#alert_message").html(html);
            $("#date").removeAttr('value');
            $("#name").removeAttr('value');
            $("#company_name").val('');
            $("#upload_doc_id").removeAttr('value');
            $(".remove_file_drop").trigger("click");
            $(".first_button").text('Save Another');
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
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            if(responseText.data.name != null){
            html += '<li>The agency name field is invalid</li>';
            }
            if(responseText.data.file_path != null){
                html += '<li>The document is invalid</li>';
            }
            if(responseText.data.date != null){
                html += '<li>The date field is invalid</li>';
            }
            if(responseText.data.project_id != null){
                html += '<li>The project id field is invalid</li>';
            }

            html += '</ul></div>';
            $("#alert_message").html(html);
            setTimeout(function(){
                $("#alert_message").hide();
            },5000)
        })
});
