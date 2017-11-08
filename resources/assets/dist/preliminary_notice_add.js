$(document).ready(function() {
    // Get login user profile data
    $("#company_name").hide();
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3]; // projects
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
    fetchCompanyName();
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
            // console.log(data.data);
            window.agency_id = data.data[0].ps_agency_name;
            console.log(agency_id);
            // $("#company_name").val(parseInt(agency_id));
            // $(".loading_data").hide();
            // // Select Company Detail for PDF
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
                console.log(data.data);
                $('.company_name').val(data.data.f_id);
                $('#contractor_name').text(data.data.f_name);
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
               $(".loading_data").hide();
            }
            else {
                window.location.href = baseUrl + "500";
            }
        });

});

$('#sub-btn').click(function(e)

{
    e.preventDefault();
    $('#save').hide();
    $('#p_n_f').text($('#company_name').val());
    $('#u_c_w').text($('#under_contract_with').val());
    $('#i_t_a_o').text($('#amount').val());
    if($('#amount').val()=='' ){
        $("#alert_message").show();
        $('.loading-submit').hide();
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
        html += '<li>The amount field is required   </li>';
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
    $('#d_c').text($('#direct_contractor').val());
    $('#d_o_n_w_s').text($('#date_of_notice_signed').val());
    $('#p_d').text($('#post_marked_date').val());
    var document_generated  = $("#notice_award_pdf_content").html();
    var document_path       = 'uploads/preliminary-notice/';
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
        .done(function(data, textStatus, jqXHR)
        {
            var doc_path = data;
            $('#upload_doc_id').val(doc_path);
            $('#save').show();
            $('#sub-btn').hide();
        });
    e.preventDefault();
})




$('.add_preliminary_notice').click(function(e)
{
  $('.loading-submit').show();
    e.preventDefault();
    var project_id = $('#project_id').val();
    var company_name =  $('#company_name').val();
    var under_contract_with = $('#under_contract_with').val();
    var amount = $('#amount').val();
    var direct_contractor =$('#direct_contractor').val();
    var date_of_notice_signed = $('#date_of_notice_signed').val();
    var post_marked_date =$('#post_marked_date').val();
    var preliminary_notice_path = $('#upload_single_doc_id').val();


    // Validation Certificate
    var html;
    var is_error = false;
    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';

    if(company_name == ''){
        html += '<li>Preliminary Notice From is invalid.</li>';
        is_error = true;
    }
    if(under_contract_with == ''){
        html += '<li>Under Contract with is invalid.</li>';
        is_error = true;
    }
    if(amount == ''){
        html += '<li>In the Amount of is invalid.</li>';
        is_error = true;
    }
    if(direct_contractor == ''){
        html += '<li>Direct Contractor is invalid.</li>';
        is_error = true;
    }
    if(date_of_notice_signed == ''){
        html += '<li>Date of Notice was Signed is invalid.</li>';
        is_error = true;
    }
    if(post_marked_date == ''){
        html += '<li>Postmarked Date is invalid.</li>';
        is_error = true;
    }
    if(preliminary_notice_path == ''){
        html += '<li>Document is invalid.</li>';
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
        url: baseUrl + "preliminary-notice/add",
        type: "POST",
        data: {
            "ppn_project_id"       :project_id,
            "company_name": company_name,
            "under_contract_with": under_contract_with,
            "amount"  : amount,
            "direct_contractor":direct_contractor,
            "date_of_notice_signed": date_of_notice_signed,
            "post_marked_date"     : post_marked_date,
            "preliminary_notice_path": preliminary_notice_path
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
        $('#amount').removeAttr('value');
        $("#alert_message").show();
        $('.loading-submit').hide();
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New preliminary notice added successfully!</div></div>';
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
