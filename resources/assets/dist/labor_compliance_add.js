$(document).ready(function() {
    $('#upload_doc_id').removeAttr('value');
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3 ]; // projects
    console.log(project_id);

    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("labor_compliance_add", check_user_access );
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
        $('#project_name').val(project_name);
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
        jQuery.each(data.data, function( i, val ) {
            if(val.f_status == 'active'){
                $("#company_name").append(
                    '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                )
            }else {

            }
        });
        var add_company_on_fly_permission = jQuery.inArray("project_add_company_on_fly", check_user_access );
        if(add_company_on_fly_permission>0 || role=="owner"){
        $("#company_name").append(
            '<option style="font-weight:bold;">Add New Contractor</option>'
        )}
        // $( "h2" ).appendTo( $( ".container" ));
        $(".loading_data").remove();
        $(".select2").show();
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
        console.log(agency_id);
        $(".loading_data").hide();
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
            console.log(data.data.f_name);
            $("#company_name").val(data.data.f_id);
            $('#contractor_name').text(data.data.f_name);
        })
    })
});


$('#add_labor_compliance').click(function(e) {
  $('.loading-submit').show();
    e.preventDefault();
    if($("#140_option_show").is(':checked') || $("#142_option_show").is(':checked') || $("#fringe_option_show").is(':checked') || $("#cac_option_show").is(':checked') || $("#weekly_option_show").is(':checked') || $("#non_performance_compliance_option_show").is(':checked')){
    var company_name                = $('#company_name').val();
    var date_140                    = $('#date_140').val();
    var doc_140                     = $('#upload_doc_id_1').val();
    var date_142                    = $('#date_142').val();
    var doc_142                     = $('#upload_doc_id_2').val();
    var fringe_date                 = $('#fringe_date').val();
    var doc_fringe                  = $('#upload_doc_id_3').val();
    var cac2_date                   = $('#cac2_date').val();
    var doc_cac2                    = $('#upload_doc_id_4').val();
    var weekly_date                 = $('#weekly_date').val();
    var doc_weekly                  = $('#upload_doc_id_5').val();
    var compliance_date             = $('#compliance_date').val();
    var doc_compliance              = $('#upload_doc_id_6').val();
    var doc_nonperformance          = $('#upload_doc_id_7').val();
    var project_id                  = $('#upload_project_id').val();
    var token                       = localStorage.getItem('u_token');
    var performance_signatory_arr = [];
    var documents_counter = 0;
        // Validation Certificate
        var html;
        var is_error = false;
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
        if(company_name == null || company_name == ''){
            html += '<li>Contractor name is invalid.</li>';
            is_error = true;
        }
        signatory_arr = [];
        if($("#140_option_show").is(':checked')){
            if(date_140 == ''){
                html += '<li>140 date is invalid.</li>';
                is_error = true;
            }
            if(doc_140 == ''){
                html += '<li>140 document is invalid.</li>';
                is_error = true;
            }
            documents_counter++;
        }
        if($("#142_option_show").is(':checked')){
            if(date_142 == ''){
                html += '<li>142 date is invalid.</li>';
                is_error = true;
            }
            if(doc_142 == ''){
                html += '<li>142 document is invalid.</li>';
                is_error = true;
            }
            documents_counter++;
        }
        if($("#fringe_option_show").is(':checked')){
            if(fringe_date == ''){
                html += '<li>Fringe date is invalid.</li>';
                is_error = true;
            }
            if(doc_fringe == ''){
                html += '<li>Fringe document is invalid.</li>';
                is_error = true;
            }
            documents_counter++;
        }
        if($("#cac_option_show").is(':checked')){
            if(cac2_date == ''){
                html += '<li>CAC2 date is invalid.</li>';
                is_error = true;
            }
            if(doc_cac2 == ''){
                html += '<li>CAC2 document is invalid.</li>';
                is_error = true;
            }
            documents_counter++;
        }
        if($("#weekly_option_show").is(':checked')){
            if($('input:radio[name=check_statement_compliance_type]:checked').val() == "exist"){
                if(weekly_date == ''){
                    html += '<li>Weekly date is invalid.</li>';
                    is_error = true;
                }
                if(doc_weekly == ''){
                    html += '<li>Weekly document is invalid.</li>';
                    is_error = true;
                }
                if(doc_compliance == ''){
                    html += '<li>Compliance document is invalid.</li>';
                    is_error = true;
                }
                documents_counter++;
            }
            var signatory_name = [];
            $('input[name^=signatory_name]').each(function(){
                signatory_name.push($(this).val());
            });
            var signatory_email = [];
            $('input[name^=signatory_email]').each(function(){
                var email = $(this).val().trim();
                if(email != "" && /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)){
                    signatory_email.push(email);
                }else if($(this).val() != ""){
                    html += '<li>Signatory email is invalid.</li>';
                    is_error = true;
                }
            });
            var item = {};
            item['signatory_name'] 		= signatory_name;
            item['signatory_email']         = signatory_email;
            
            for (i = 0; i < signatory_email.length; i++) {
                signatory_arr.push({
                    "signatory_name"            :   item['signatory_name'][i],
                    "signatory_email"           :   item['signatory_email'][i],
                    "company_name"              :   $("#company_name option:selected").text(),
                    "project_name"              : $('#project_name').val(),
                });
            }
            
        }
        if($("#non_performance_compliance_option_show").is(':checked')){
            if($('input:radio[name=check_statement_compliance_type]:checked').val() == "exist"){
                if(compliance_date == ''){
                    html += '<li>Compliance Non Performance date is invalid.</li>';
                    is_error = true;
                }
                if(doc_nonperformance == ''){
                    html += '<li>Non Performance document is invalid.</li>';
                    is_error = true;
                }
                documents_counter++;
            }
            var performance_signatory_name = [];
            $('input[name^=performance_signatory_name]').each(function(){
                performance_signatory_name.push($(this).val());
            });
            var performance_signatory_email = [];
            $('input[name^=performance_signatory_email]').each(function(){
                var email = $(this).val().trim();
                if(email != "" && /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)){
                    performance_signatory_email.push(email);
                }else if($(this).val() != ""){
                    html += '<li>Non Performance Signatory email is invalid.</li>';
                    is_error = true;
                }
            });
            var item = {};
            item['performance_signatory_name']          = performance_signatory_name;
            item['performance_signatory_email']         = performance_signatory_email;
            
            for (i = 0; i < performance_signatory_email.length; i++) {
                performance_signatory_arr.push({
                    "performance_signatory_name"            :   item['performance_signatory_name'][i],
                    "performance_signatory_email"           :   item['performance_signatory_email'][i],
                    "company_name"              :   $("#company_name option:selected").text(),
                    "project_name"              : $('#project_name').val(),
                });
            }
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
                $("#alert_message").hide()
                return false;
            },3000)
            return;
        }

        jQuery.ajax({
            url: baseUrl + "labor_compliance/add",
            type: "POST",
            data: {
                "contactor_id"      : company_name,
                "140"               : doc_140,
                "140_date"          : date_140,
                "142"               : doc_142,
                "142_date"          : date_142,
                "fringe"            : doc_fringe,
                "fringe_date"       : fringe_date,
                "cac2"              : doc_cac2,
                "cac2_date"         : cac2_date,
                "cpr"               : doc_weekly,
                "cpr_date"          : weekly_date,
                "compliance"        : doc_compliance,
                "doc_nonperformance": doc_nonperformance,
                "compliance_date"   : compliance_date,
                "project_id"        : project_id,
                "signatory_arr"     : signatory_arr,
                "performance_signatory_arr" : performance_signatory_arr,
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
            if(parseInt(documents_counter)>1)
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New Labor compliance documents successfully uploaded!</div></div>';
            else
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New Labor compliance document successfully uploaded!</div></div>';
            $("#alert_message").html(html);
            $("#date_140").removeAttr('value');
            $('input[name^=signatory_name],input[name^=signatory_email]').each(function(){$(this).val('');});
            $('input[name^=performance_signatory_name],input[name^=performance_signatory_email]').each(function(){$(this).val('');});
            $("#date_142").removeAttr('value');
            $("#upload_doc_id_1").removeAttr('value');
            $("#date_143").removeAttr('value');
            $("#upload_doc_id_2").removeAttr('value');
            $("#fringe_date").removeAttr('value');
            $("#upload_doc_id_3").removeAttr('value');
            $("#cac2_date").removeAttr('value');
            $("#upload_doc_id_4").removeAttr('value');
            $("#weekly_date").removeAttr('value');
            $("#upload_doc_id_5").removeAttr('value');
            $("#compliance_date").removeAttr('value');
            $("#upload_doc_id_6").removeAttr('value');
            // $("#upload_project_id").removeAttr('value');
            $(".remove_file_drop").trigger("click");
            $('#140_div_show').hide();
            $("#company_name").val('');
            $('#142_div_show').hide();
            $('#cac_div_show').hide();
            $('#weekly_div_show').hide();
            $('#compliance_div_show').hide();
            $('#fringe_div_show').hide();
            $('#company_name').prop('selected', function() {
                return this.defaultSelected;
            });
            $('input[type="checkbox"]').attr('checked', false);
            $('input[name="upload"]').attr('checked', false);
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
            // console.log(responseText.data.currency_name);
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-error"><ul>';
            if(responseText.data != null){
                html += '<li>'+responseText.data+'</li>';
            }
            if(responseText.data.docusign != null){
                html += '<li>'+responseText.data.description+'</li>';
            }
            html += '</ul></div>';
            $("#alert_message").html(html);
            setTimeout(function()
            {
                $("#alert_message").hide();
            },5000)
        })
    }
    else {
        $('html, body').animate({
            scrollTop: $(".page-head").offset().top
        }, 'fast')
        $("#alert_message").fadeIn(1000);
        $('.loading-submit').hide();
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
        html += '<li>Please check atleast one document type</li>';
        html += '</ul></div>';
        $("#alert_message").html(html);
        setTimeout(function(){
            $("#alert_message").hide();
        },6000);
    }
});
$('#company_name').change(function(){
        var company = $(this).val();
        if(company=="Add New Contractor")
        {
            //map.clear();
//            info_Window = new google.maps.InfoWindow();
//            info_Window.close();
//            for (var i = 0; i < marker.length; i++) {
//                marker[i].setMap(null);
//            }
//            marker.length = 0;
//            for(var i=0;i<location.length;i++){
//                location[i].setMap(null);
//            }
//            location.length=0;
//            marker = [];
            $('#add-company').modal('show');
            $('#add-company').on('shown.bs.modal',function(){
                google.maps.event.trigger(map, "resize");
              });
            
        }
    })