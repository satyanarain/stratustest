$(document).ready(function() {
    // Get login user profile data
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 4]; // projects
    preliminary_id = url[ url.length - 2]; // projects
    //alert(preliminary_id);
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');

     // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("preliminary_add", check_user_access );
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
     
     

});

$('.add_preliminary_notice').click(function(e)
{
  $('.loading-submit').show();
    e.preventDefault();
    var project_id = $('#project_id').val();
    var pplr_preliminary_id = $('#preliminary_id').val();
    var date_of_billed_through =  $('#billed_through').val();
    var lien_release_note =$('#lien_release_note').val();
    var lien_release_path = $('#upload_single_doc_id').val();
    var pplr_type = $('input[name=pplr_type]:checked').val();
    // Validation Certificate
    var html;
    var is_error = false;
    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';

    if(pplr_preliminary_id == ''){
        html += '<li>Preliminary ID is invalid.</li>';
        is_error = true;
    }
    if(date_of_billed_through == ''){
        html += '<li>Date of Billed through is required.</li>';
        is_error = true;
    }
    if(lien_release_note == ''){
        html += '<li>Lien Release Note is required.</li>';
        is_error = true;
    }
    if(lien_release_path == ''){
        html += '<li>Document is required.</li>';
        is_error = true;
    }
    if(is_error == true){
        html += '</ul></div>';
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
    jQuery.ajax({
        url: baseUrl + "lien-release/"+preliminary_id+"/add",
        type: "POST",
        data: {
            "pplr_project_id": project_id,
            "pplr_preliminary_id": pplr_preliminary_id,
            "date_of_billed_through": date_of_billed_through,
            "lien_release_note"  : lien_release_note,
            "lien_release_path":lien_release_path,
            "pplr_type": pplr_type
    },
        headers: {
            "x-access-token": token
        },
        contentType: "application/x-www-form-urlencoded",
        cache: false
    }).done
    (function(data, textStatus, jqXHR) {
        // $("#project_id").removeAttr('value');
        $('#upload_doc_id').removeAttr('value');
        $('#billed_through').removeAttr('value');
        $('#lien_release_note').removeAttr('value');
        $("#alert_message").show();
        $('.loading-submit').hide();
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New Lien Release added successfully!</div></div>';
        $("#alert_message").html(html);
        $(".first_button").text('Save Another');
        $(".remove_file_drop").trigger("click");
        $('html, body').animate({
            scrollTop: $(".page-head").offset().top
        }, 'fast')
        setTimeout(function(){
            $("#alert_message").hide()
        },5000)
    })
    e.preventDefault();

})