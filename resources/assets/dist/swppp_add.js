$(document).ready(function() {
    // Get login user profile data
    $("#company_name").hide();
    $('#upload_doc_id').removeAttr('value');
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');

    var url = $(location).attr('href').split( '/' );
    project_id  = url[ url.length - 3 ]; // projects
    console.log(project_id);

    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("swppp_add", check_user_access );
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
    fetchCompanyName();
    jQuery('#wdid_no').keyup(function() {
        var raw_text =  jQuery(this).val();
        var return_text = raw_text.replace(/[^a-zA-Z0-9 _]/g,'');
        jQuery(this).val(return_text);
    });
});


$('.add_swppp_form').click(function(e) {
    e.preventDefault();
    $('.loading-submit').show();
    var date_of_report          = $('#date_of_report').val();
    var name_of_report          = $('#name_of_report').val();
    var company_name            = $('#company_name').val();
    var applicable              = $("input[name='applicable']:checked"). val();
    var upload                  = $("input[name='upload']:checked"). val();
    var type                    = $('#type').val();
    var file_path               = $('#upload_doc_id').val();
    var project_id              = $('#upload_project_id').val();
    var wdid_no                 = $('#wdid_no').val();
    var wdid_expiration_date    = $('#wdid_expiration_date').val();
    var token                   = localStorage.getItem('u_token');
    console.log(date_of_report);
    jQuery.ajax({
        url: baseUrl + "swppp/add",
        type: "POST",
        data: {
            "date"              : date_of_report,
            "name"              : name_of_report,
            "firm_name"         : company_name,
            "applicable"        : applicable,
            "available"         : upload,
            "type"              : type,
            "file_path"         : file_path,
            "project_id"        : project_id,
            "wdid_expiration_date": wdid_expiration_date,
            "wdid_no"           : wdid_no
        },
        headers: {
            "x-access-token": token
        },
        contentType: "application/x-www-form-urlencoded",
        cache: false
    })
        .done(function(data, textStatus, jqXHR) {
            console.log(data.description);
            $('.loading-submit').hide();
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").show();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New SWPPP / WPCP added successfully!</div></div>';
            $("#alert_message").html(html);
            $("#date_of_report").removeAttr('value');
            $("#name_of_report").removeAttr('value');
            $("#company_name").removeAttr('value');
            $("#wdid_expiration_date").removeAttr('value');
            $("#wdid_no").removeAttr('value');
            $('input[name="applicable"]').attr('checked', false);
            $('input[name="upload"]').attr('checked', false);
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
            $('.loading-submit').hide();
            var responseText, html;
            responseText = JSON.parse(jqXHR.responseText);
            console.log(responseText.data);

            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").show();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>'; if(responseText.data.date != null){
                html += '<li>The date field is required.</li>';
            }
            if(responseText.data.name != null){
                html += '<li>The document name field is required.</li>';
            }
            if(responseText.data.firm_name != null){
                html += '<li>The company name field is required.</li>';
            }
            if(responseText.data.applicable != null){
                html += '<li>The applicable field is required.</li>';
            }
            if(responseText.data.available != null){
                html += '<li>The available field is required.</li>';
            }
            if(responseText.data.file_path != null){
                html += '<li>The document is required.</li>';
            }
            if(responseText.data.type != null){
                html += '<li>The type field is required.</li>';
            }
            if(responseText.data.project_id != null){
                html += '<li>The project id field is required.</li>';
            }
            if(responseText.data.wdid_no != null){
                html += '<li>The WDID Number field is required.</li>';
            }
            if(responseText.data.wdid_expiration_date != null){
                html += '<li>The WDID Expiration Date field is required.</li>';
            }
            html += '</ul></div>';
            $("#alert_message").html(html);
            setTimeout(function(){
                $("#alert_message").hide();
            },5000)
        })
});
$('.company_name').change(function(){
        var company = $(this).val();
        if(company=="Add New Company")
        {
            $('#add-company').modal('show');
            $('#add-company').on('shown.bs.modal',function(){
                google.maps.event.trigger(map, "resize");
              });
        }
    })
function fetchCompanyName()
{
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
        // console.log(data);
        // Foreach Loop 
        $(".company_name").append(
            '<option value="">Select Company</option>'
        )
        jQuery.each(data.data, function( i, val ) {
            if(val.f_status == 'active'){
                $(".company_name").append(
                    '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                )
            }else {

            }
        });
        $(".company_name").append(
            '<option>Add New Company</option>'
        )
        // $( "h2" ).appendTo( $( ".container" ) );
       
        $(".loading_data").remove();
        $(".company_name").show();
    })
        .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        console.log(jqXHR);
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
    }