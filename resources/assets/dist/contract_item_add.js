$(document).ready(function() {
    // Get login user profile data
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3 ]; // projects

    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("contract_item_add", check_user_access );
    console.log(check_permission);
    if(check_permission < 1){
        window.location.href = baseUrl + "403";
    }
    else {
        console.log('Yes Permission');
        $('.body-content .wrapper').show();
    }

    // Get Project Currency
    jQuery.ajax({
        url: baseUrl+project_id+"/project_setting_get/project_currency",
        type: "GET",
        headers: {
            "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
        .done(function(data, textStatus, jqXHR) {
            // console.log(data.data.pset_meta_value);
            var project_currency_id = data.data.pset_meta_value;
            jQuery.ajax({
                url: baseUrl + "currency/"+project_currency_id,
                type: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "x-access-token": token
                },
                contentType: "application/json",
                cache: false
            })
                .done(function(data, textStatus, jqXHR) {
                    console.log(data.data.cur_symbol);
                    $('.project_currency').text(data.data.cur_symbol+' ');
                })
        })


    $('#item_unit').on("change", function(e) {   
        var str = $('option:selected', this).attr('value'); 
        if(str == 'Other'){
            // alert(str);
            $('.other_option').show();
        }
        else {
            $('.other_option').hide();
            $("#item_unit_other").removeAttr('value');
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
});

function isPrice(evt)
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if(charCode==46)
        return true;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
$('#add_contract_item_form').submit(function(e) {
  $('.loading-submit').show();
    e.preventDefault();
    var item_description        = $('#item_description').val();
    var item_unit               = $('#item_unit').val();
    var item_unit_other         = $('#item_unit_other').val();
    var item_qty                = $('#item_qty').val();
    var item_unit_price         = $('#item_unit_price').val();
    var project_id              = $('#project_id').val();
    var token                   = localStorage.getItem('u_token');
    console.log(item_unit_other);
    jQuery.ajax({
        url: baseUrl + "bid-items/add",
        type: "POST",
        data: {
            "item_description"      : item_description,
            "item_unit"             : item_unit,
            "item_unit_other"       : item_unit_other,
            "item_qty"              : item_qty,
            "item_unit_price"       : item_unit_price,
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
            var html;
            // html = '<div class="alert alert-block alert-success fade in">'+data.description+'</div>';
            // $("#alert_message").html(html);
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New contract item added successfully!</div></div>';
            $("#alert_message").html(html);

            setTimeout(function(){
                $("#alert_message").hide();
            },5000)
            $("#item_description").removeAttr('value');
            $("#item_qty").removeAttr('value');
            $("#item_unit_other").removeAttr('value');
            $("#item_unit_price").removeAttr('value');
            $(".first_button").hide();
            $(".another_button").show();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var responseText, html;
            responseText = JSON.parse(jqXHR.responseText);
            // console.log(responseText.data.currency_name);
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            if(responseText.data.item_description != null){
                html += '<li>The item description field is required.</li>';
            }
            if(responseText.data.item_unit != null){
                html += '<li>The item unit field is required.</li>';
            }
            if(responseText.data.item_qty != null){
                html += '<li>The item quantity field is required.</li>';
            }
            if(responseText.data.item_unit_price != null){
                html += '<li>The item unit price field is required.</li>';
            }
            if(responseText.data.project_id != null){
                html += '<li>The project id field is required.</li>';
            }
            html += '</ul></div>';
            $("#alert_message").html(html);
            setTimeout(function(){
                $("#alert_message").hide();
            },5000)
        })
});
