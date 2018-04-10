$(document).ready(function() {

   // Get Project Type project_type_improvement table
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 2 ]; // projects
    setTimeout(function()
    {
        // Selected Improvement Type
        jQuery.ajax({
        //url: baseUrl +"/"+project_id+"/improvement-type",
        url: baseUrl +"/"+project_id+"/improvement-type-by-owner",
            type: "GET",
            headers: {
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            // Foreach Loop
            jQuery.each(data.data, function( i, val ) {
                if(val.pt_status == 'active'){
                    $("#project_type_dropdown").append(
                         '<option value="'+val.pt_name+'">'+val.pt_name+'</option>'
                     )
                    
                }
            });
            $(".loading_data").remove();
        })

	},1000);

    // Get login user profile data
    $("#submit_new_btn").hide();
    $("#company_name").hide();
    $('#upload_doc_id').removeAttr('value');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3]; // projects
    console.log(project_id);

    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');

    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("notice_completion_add", check_user_access );
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
    var project_add_impvtype_on_fly = jQuery.inArray("project_add_impvtype_on_fly", check_user_access );
    if(project_add_impvtype_on_fly>0 || role=="owner"){
        $('.add-impvtypes').click(function(){
            $('#add-impvtypes').modal('show');
        }) 
    }else{
        $('.add-impvtypes').hide();
    }
});


    $('#add_noc').click(function(e) {
        $('.loading-submit').show();
        var noc_type            = $('input:radio[name=noc_type]:checked').val();
        var noc_potential       = $('input:radio[name=noc_potential]:checked').val();
        var upload_doc_id       = $("#upload_single_doc_id").val();
        var noc_rec_text        = $("#noc_rec_text").val();
        var noc_rec_name        = $("#noc_rec_name").val();
        var noc_rec_street      = $("#noc_rec_street").val();
        var noc_rec_adress      = $("#noc_rec_adress").val();
        var noc_notice_text_1      = $("#noc_notice_text_1").val();
        var noc_notice_text_2      = $("#noc_notice_text_2").val();
        var noc_notice_text_3      = $("#noc_notice_text_3").val();
        var noc_notice_text_4      = $("#noc_notice_text_4").val();
        var noc_notice_text_5      = $("#noc_notice_text_5").val();
        var noc_notice_text_6      = $("#noc_notice_text_6").val();
        var noc_notice_text_7      = $("#noc_notice_text_7").val();
        var noc_notice_text_8      = $("#noc_notice_text_8").val();
        var noc_notice_text_9      = $("#noc_notice_text_9").val();
        var noc_notice_text_10      = $("#noc_notice_text_10").val();
        var noc_notice_text_11      = $("#noc_notice_text_11").val();
        var noc_notice_text_12      = $("#noc_notice_text_12").val();
        var noc_notice_text_13      = $("#noc_notice_text_13").val();
        var noc_notice_text_14      = $("#noc_notice_text_14").val();
        var noc_notice_text_15      = $("#noc_notice_text_15").val();
        var noc_notice_text_16      = $("#noc_notice_text_16").val();
        var noc_notice_text_17      = $("#noc_notice_text_17").val();
        var noc_notice_text_18      = $("#noc_notice_text_18").val();
        var noc_notice_text_19      = $("#noc_notice_text_19").val();
        var noc_notice_text_20      = $("#noc_notice_text_20").val();
        var noc_notice_text_21      = $("#noc_notice_text_21").val();
        var noc_notice_text_22      = $("#noc_notice_text_22").val();
        var noc_ver_text_1      = $("#noc_ver_text_1").val();
        var noc_ver_text_2      = $("#noc_ver_text_2").val();
        var noc_ver_text_3      = $("#noc_ver_text_3").val();
        var noc_ver_text_4      = $("#noc_ver_text_4").val();
        var noc_ver_text_5      = $("#noc_ver_text_5").val();
        var noc_ver_text_6      = $("#noc_ver_text_6").val();
        var noc_ver_text_7      = $("#noc_ver_text_7").val();
        var noc_ser_text_1      = $("#noc_ser_text_1").val();
        var noc_ser_text_2      = $("#noc_ser_text_2").val();
        var noc_ser_text_3      = $("#noc_ser_text_3").val();
        var noc_ser_text_4      = $("#noc_ser_text_4").val();
        var noc_ser_text_5      = $("#noc_ser_text_5").val();
        var noc_ser_text_6      = $("#noc_ser_text_6").val();
        var noc_ser_text_7      = $("#noc_ser_text_7").val();
        var noc_ser_text_8      = $("#noc_ser_text_8").val();
        var noc_ser_text_9      = $("#noc_ser_text_9").val();
        var noc_ser_text_10      = $("#noc_ser_text_10").val();
        var noc_ser_text_11      = $("#noc_ser_text_11").val();
        var noc_ser_text_12      = $("#noc_ser_text_12").val();
        var noc_ser_text_13      = $("#noc_ser_text_13").val();
        var noc_ser_text_14      = $("#noc_ser_text_14").val();
        var noc_ser_text_15      = $("#noc_ser_text_15").val();
        var noc_ser_text_16      = $("#noc_ser_text_16").val();
        var noc_ser_text_17      = $("#noc_ser_text_17").val();
        var noc_con_text_1      = $("#noc_con_text_1").val();
        var noc_con_text_2      = $("#noc_con_text_2").val();
        var noc_con_text_3      = $("#noc_con_text_3").val();
        var noc_con_text_4      = $("#noc_con_text_4").val();
        var noc_con_text_5      = $("#noc_con_text_5").val();
        var noc_con_text_6      = $("#noc_con_text_6").val();
        var upload_project_id   = $("#upload_project_id").val();
        var improvement_type    = $("#project_type_dropdown").val();
        //alert(improvement_type);return false;
        var date_noc_filed      = $("#date_noc_filed").val();
        // Validation Certificate
        var html = '';
        var is_error = false;
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
        // Check Validation
        var signatory_name = [];
        $('input[name^=signatory_name]').each(function(){
            signatory_name.push($(this).val());
        });
        var signatory_email = [];
        $('input[name^=signatory_email]').each(function(){
            if($(this).val() != "" && /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($(this).val())){
                signatory_email.push($(this).val());
            }else if($(this).val() != ""){
                html += '<li>Signatory email is invalid.</li>';
                is_error = true;
            }
        });
        var signatory_role = [];
        signatory_role.push('owner');
        signatory_role.push('contractor');
        var item = {};
        item['signatory_name'] 		= signatory_name;
        item['signatory_email']         = signatory_email;
        item['signatory_role']          = signatory_role;
        signatory_arr = [];
        for (i = 0; i < signatory_email.length; i++) {
            signatory_arr.push({
                            "signatory_name" 		:   item['signatory_name'][i],
                            "signatory_email" 		:   item['signatory_email'][i],
                            "signatory_role"            :   item['signatory_role'][i],
                        });
        }
        if(noc_type == "complete_form_noc"){
            // alert('full form');
            if(noc_rec_text == ''){
                html += '<li>Recording Requested By is invalid.</li>';
                is_error = true;
            }
            if(noc_rec_name == ''){
                html += '<li>Recording Mail To Name is invalid.</li>';
                is_error = true;
            }
            if(noc_rec_street == ''){
                html += '<li>Recording Mail To Street Address is invalid.</li>';
                is_error = true;
            }
            if(noc_rec_adress == ''){
                html += '<li>Recording Mail To City & State is invalid.</li>';
                is_error = true;
            }
        }
        else if(noc_type == "upload_form_noc"){
            // alert('upload form');
            if(upload_doc_id == ''){
                html += '<li>Document is invalid.</li>';
                is_error = true;
            }
            if(date_noc_filed == ''){
                html += '<li>Please select NOC filed date.</li>';
                is_error = true;
            }
            if(improvement_type == '' || improvement_type == 'Select Improvement Type'){
                html += '<li>Please select Improvement Type.</li>';
                is_error = true;
            }
        }
        else {
            html += '<li>Please Choose NOC Type.</li>';
            is_error = true;
        }

        if(noc_potential == 'yes'){
            noc_potential = 'yes';
        }
        else if(noc_potential = 'no'){
            noc_potential = 'no';
        }
        else {
            html += '<li>Provide a copy field is invalid.</li>';
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
                $("#alert_message").hide()
                return false;
            },3000)
            $('.loading_data1').hide();
            return;
        }

        if(noc_type == "complete_form_noc"){
            // Add data to PDF
            $('#pdf_noc_rec_text').text(noc_rec_text);
            $('#pdf_noc_rec_name').text(noc_rec_name);
            $('#pdf_noc_rec_street').text(noc_rec_street);
            $('#pdf_noc_rec_adress').text(noc_rec_adress);
            $('#pdf_noc_notice_text_1').text(noc_notice_text_1);
            $('#pdf_noc_notice_text_2').text(noc_notice_text_2);
            $('#pdf_noc_notice_text_3').text(noc_notice_text_3);
            $('#pdf_noc_notice_text_4').text(noc_notice_text_4);
            $('#pdf_noc_notice_text_5').text(noc_notice_text_5);
            $('#pdf_noc_notice_text_6').text(noc_notice_text_6);
            var check_box = document.getElementById("noc_notice_text_7").checked;
            if(check_box == true){
                var noc_notice_text_7 = '<input type="checkbox" id="noc_notice_text_7" name="" class="inline-block" style="margin-top: 0;" checked>';
            }
            else {
                var noc_notice_text_7 = '<input type="checkbox" id="noc_notice_text_7" name="" class="inline-block" style="margin-top: 0;">'
            }
            console.log(noc_notice_text_7);
            $('#pdf_noc_notice_text_7').html(noc_notice_text_7);
            var check_box = document.getElementById("noc_notice_text_8").checked;
            if(check_box == true){
                var noc_notice_text_8 = '<input type="checkbox" id="noc_notice_text_8" name="" class="inline-block" style="margin-top: 0;" checked>';
            }
            else {
                var noc_notice_text_8 = '<input type="checkbox" id="noc_notice_text_8" name="" class="inline-block" style="margin-top: 0;">'
            }
            console.log(noc_notice_text_8);
            $('#pdf_noc_notice_text_8').html(noc_notice_text_8);
            $('#pdf_noc_notice_text_9').text(noc_notice_text_9);
            $('#pdf_noc_notice_text_10').text(noc_notice_text_10);
            $('#pdf_noc_notice_text_11').text(noc_notice_text_11);
            $('#pdf_noc_notice_text_12').text(noc_notice_text_12);
            $('#pdf_noc_notice_text_13').text(noc_notice_text_13);
            $('#pdf_noc_notice_text_14').text(noc_notice_text_14);
            $('#pdf_noc_notice_text_15').text(noc_notice_text_15);
            $('#pdf_noc_notice_text_16').text(noc_notice_text_16);
            $('#pdf_noc_notice_text_17').text(noc_notice_text_17);
            $('#pdf_noc_notice_text_18').text(noc_notice_text_18);
            $('#pdf_noc_notice_text_19').text(noc_notice_text_19);
            $('#pdf_noc_notice_text_20').text(noc_notice_text_20);
            $('#pdf_noc_notice_text_21').text(noc_notice_text_21);
            $('#pdf_noc_notice_text_22').text(noc_notice_text_22);
            $('#pdf_noc_ver_text_1').text(noc_ver_text_1);
            $('#pdf_noc_ver_text_2').text(noc_ver_text_2);
            $('#pdf_noc_ver_text_3').text(noc_ver_text_3);
            $('#pdf_noc_ver_text_4').text(noc_ver_text_4);
            $('#pdf_noc_ver_text_5').text(noc_ver_text_5);
            $('#pdf_noc_ver_text_6').text(noc_ver_text_6);
            $('#pdf_noc_ver_text_7').text(noc_ver_text_7);
            $('#pdf_noc_ser_text_1').text(noc_ser_text_1);
            var check_box = document.getElementById("noc_ser_text_2").checked;
            if(check_box == true){
                var noc_ser_text_2 = '<input type="checkbox" id="noc_ser_text_2" name="" class="inline-block" style="margin-top: 0;" checked>';
            }
            else {
                var noc_ser_text_2 = '<input type="checkbox" id="noc_ser_text_2" name="" class="inline-block" style="margin-top: 0;">'
            }
            console.log(noc_ser_text_2);
            $('#pdf_noc_ser_text_2').html(noc_ser_text_2);
            $('#pdf_noc_ser_text_3').text(noc_ser_text_3);
            $('#pdf_noc_ser_text_4').text(noc_ser_text_4);
            $('#pdf_noc_ser_text_5').text(noc_ser_text_5);
            $('#pdf_noc_ser_text_6').text(noc_ser_text_6);
            $('#pdf_noc_ser_text_7').text(noc_ser_text_7);
            $('#pdf_noc_ser_text_8').text(noc_ser_text_8);
            var check_box = document.getElementById("noc_ser_text_9").checked;
            if(check_box == true){
                var noc_ser_text_9 = '<input type="checkbox" id="noc_ser_text_9" name="" class="inline-block" style="margin-top: 0;" checked>';
            }
            else {
                var noc_ser_text_9 = '<input type="checkbox" id="noc_ser_text_9" name="" class="inline-block" style="margin-top: 0;">'
            }
            console.log(noc_ser_text_9);
            $('#pdf_noc_ser_text_9').html(noc_ser_text_9);
            $('#pdf_noc_ser_text_10').text(noc_ser_text_10);
            $('#pdf_noc_ser_text_11').text(noc_ser_text_11);
            $('#pdf_noc_ser_text_12').text(noc_ser_text_12);
            var check_box = document.getElementById("noc_ser_text_13").checked;
            if(check_box == true){
                var noc_ser_text_13 = '<input type="checkbox" id="noc_ser_text_13" name="" class="inline-block" style="margin-top: 0;" checked>';
            }
            else {
                var noc_ser_text_13 = '<input type="checkbox" id="noc_ser_text_13" name="" class="inline-block" style="margin-top: 0;">'
            }
            console.log(noc_ser_text_13);
            $('#pdf_noc_ser_text_13').html(noc_ser_text_13);
            $('#pdf_noc_ser_text_14').text(noc_ser_text_14);
            $('#pdf_noc_ser_text_15').text(noc_ser_text_15);
            $('#pdf_noc_ser_text_16').text(noc_ser_text_16);
            $('#pdf_noc_ser_text_17').text(noc_ser_text_17);
            $('#pdf_noc_con_text_1').text(noc_con_text_1);
            $('#pdf_noc_con_text_2').text(noc_con_text_2);
            $('#pdf_noc_con_text_3').text(noc_con_text_3);
            $('#pdf_noc_con_text_4').text(noc_con_text_4);
            $('#pdf_noc_con_text_5').text(noc_con_text_5);
            $('#pdf_noc_con_text_6').text(noc_con_text_6);

            var doc_meta            = $("#upload_doc_meta").val();
            var doc_project_id      = $("#upload_project_id").val();

            var document_generated  = $("#notice-addForm-values").html();
            var document_path       = 'uploads/notice_completion/';
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

                    $("#upload_success").show();
                    $("#upload_single_doc_id").val(data.description);
                    var upload_doc_id = data.description;
                    console.log(upload_doc_id);
                    console.log(upload_project_id);
                    console.log(noc_potential);

                    setTimeout(function()
                    {
                        jQuery.ajax({
                            url: baseUrl + "notice-completion/add",
                            type: "POST",
                            data: {
                                "noc_rec_text" : noc_rec_text,
                                "noc_rec_name" : noc_rec_name,
                                "noc_rec_street" : noc_rec_street,
                                "noc_rec_adress" : noc_rec_adress,
                                "noc_notice_text_1" : noc_notice_text_1,
                                "noc_notice_text_2" : noc_notice_text_2,
                                "noc_notice_text_3" : noc_notice_text_3,
                                "noc_notice_text_4" : noc_notice_text_4,
                                "noc_notice_text_5" : noc_notice_text_5,
                                "noc_notice_text_6" : noc_notice_text_6,
                                "noc_notice_text_7" : noc_notice_text_7,
                                "noc_notice_text_8" : noc_notice_text_8,
                                "noc_notice_text_9" : noc_notice_text_9,
                                "noc_notice_text_10" : noc_notice_text_10,
                                "noc_notice_text_11" : noc_notice_text_11,
                                "noc_notice_text_12" : noc_notice_text_12,
                                "noc_notice_text_13" : noc_notice_text_13,
                                "noc_notice_text_14" : noc_notice_text_14,
                                "noc_notice_text_15" : noc_notice_text_15,
                                "noc_notice_text_16" : noc_notice_text_16,
                                "noc_notice_text_17" : noc_notice_text_17,
                                "noc_notice_text_18" : noc_notice_text_18,
                                "noc_notice_text_19" : noc_notice_text_19,
                                "noc_notice_text_20" : noc_notice_text_20,
                                "noc_notice_text_21" : noc_notice_text_21,
                                "noc_notice_text_22" : noc_notice_text_22,
                                "noc_ver_text_1" : noc_ver_text_1,
                                "noc_ver_text_2" : noc_ver_text_2,
                                "noc_ver_text_3" : noc_ver_text_3,
                                "noc_ver_text_4" : noc_ver_text_4,
                                "noc_ver_text_5" : noc_ver_text_5,
                                "noc_ver_text_6" : noc_ver_text_6,
                                "noc_ver_text_7" : noc_ver_text_7,
                                "noc_ser_text_1" : noc_ser_text_1,
                                "noc_ser_text_2" : noc_ser_text_2,
                                "noc_ser_text_3" : noc_ser_text_3,
                                "noc_ser_text_4" : noc_ser_text_4,
                                "noc_ser_text_5" : noc_ser_text_5,
                                "noc_ser_text_6" : noc_ser_text_6,
                                "noc_ser_text_7" : noc_ser_text_7,
                                "noc_ser_text_8" : noc_ser_text_8,
                                "noc_ser_text_9" : noc_ser_text_9,
                                "noc_ser_text_10" : noc_ser_text_10,
                                "noc_ser_text_11" : noc_ser_text_11,
                                "noc_ser_text_12" : noc_ser_text_12,
                                "noc_ser_text_13" : noc_ser_text_13,
                                "noc_ser_text_14" : noc_ser_text_14,
                                "noc_ser_text_15" : noc_ser_text_15,
                                "noc_ser_text_16" : noc_ser_text_16,
                                "noc_ser_text_17" : noc_ser_text_17,
                                "noc_con_text_1" : noc_con_text_1,
                                "noc_con_text_2" : noc_con_text_2,
                                "noc_con_text_3" : noc_con_text_3,
                                "noc_con_text_4" : noc_con_text_4,
                                "noc_con_text_5" : noc_con_text_5,
                                "noc_con_text_6" : noc_con_text_6,
                                "noc_potential" : noc_potential,
                                "noc_project_id" : upload_project_id,
                                "noc_file_path" : upload_doc_id
                            },
                            headers: {
                                "x-access-token": token
                            },
                            contentType: "application/x-www-form-urlencoded",
                            cache: false
                        })
                        .done(function(data, textStatus, jqXHR) {
                            console.log(data.description);

                    $('#notice-addForm').hide();
                    $("#my-awesome-dropzone").hide();
                    $("#potential_claimants").hide();
                    $(".remove_file_drop").trigger("click");
                    $('input:radio:checked').prop('checked', false);
                    $('input:checkbox:checked').prop('checked', false);
                    $("#upload_single_doc_id").removeAttr('value');
                    $("#noc_rec_text").removeAttr('value');
                    $("#noc_rec_name").removeAttr('value');
                    $("#noc_rec_street").removeAttr('value');
                    $("#noc_rec_adress").removeAttr('value');
                    $("#noc_notice_text_1").removeAttr('value');
                    $("#noc_notice_text_2").removeAttr('value');
                    $("#noc_notice_text_3").removeAttr('value');
                    $("#noc_notice_text_4").removeAttr('value');
                    $("#noc_notice_text_5").removeAttr('value');
                    $("#noc_notice_text_6").removeAttr('value');
                    $("#noc_notice_text_7").removeAttr('value');
                    $("#noc_notice_text_8").removeAttr('value');
                    $("#noc_notice_text_9").removeAttr('value');
                    $("#noc_notice_text_10").removeAttr('value');
                    $("#noc_notice_text_11").removeAttr('value');
                    $("#noc_notice_text_12").removeAttr('value');
                    $("#noc_notice_text_13").removeAttr('value');
                    $("#noc_notice_text_14").removeAttr('value');
                    $("#noc_notice_text_15").removeAttr('value');
                    $("#noc_notice_text_16").removeAttr('value');
                    $("#noc_notice_text_17").removeAttr('value');
                    $("#noc_notice_text_18").removeAttr('value');
                    $("#noc_notice_text_19").removeAttr('value');
                    $("#noc_notice_text_20").removeAttr('value');
                    $("#noc_notice_text_21").removeAttr('value');
                    $("#noc_notice_text_22").removeAttr('value');
                    $("#noc_ver_text_1").removeAttr('value');
                    $("#noc_ver_text_2").removeAttr('value');
                    $("#noc_ver_text_3").removeAttr('value');
                    $("#noc_ver_text_4").removeAttr('value');
                    $("#noc_ver_text_5").removeAttr('value');
                    $("#noc_ver_text_6").removeAttr('value');
                    $("#noc_ver_text_7").removeAttr('value');
                    $("#noc_ser_text_1").removeAttr('value');
                    $("#noc_ser_text_2").removeAttr('value');
                    $("#noc_ser_text_3").removeAttr('value');
                    $("#noc_ser_text_4").removeAttr('value');
                    $("#noc_ser_text_5").removeAttr('value');
                    $("#noc_ser_text_6").removeAttr('value');
                    $("#noc_ser_text_7").removeAttr('value');
                    $("#noc_ser_text_8").removeAttr('value');
                    $("#noc_ser_text_9").removeAttr('value');
                    $("#noc_ser_text_10").removeAttr('value');
                    $("#noc_ser_text_11").removeAttr('value');
                    $("#noc_ser_text_12").removeAttr('value');
                    $("#noc_ser_text_13").removeAttr('value');
                    $("#noc_ser_text_14").removeAttr('value');
                    $("#noc_ser_text_15").removeAttr('value');
                    $("#noc_ser_text_16").removeAttr('value');
                    $("#noc_ser_text_17").removeAttr('value');
                    $("#noc_con_text_1").removeAttr('value');
                    $("#noc_con_text_2").removeAttr('value');
                    $("#noc_con_text_3").removeAttr('value');
                    $("#noc_con_text_4").removeAttr('value');
                    $("#noc_con_text_5").removeAttr('value');
                    $("#noc_con_text_6").removeAttr('value');
                    $(".first_button").text('Save Another');


                            $('.loading_data1').hide();
                            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Notice of Completion uploaded successfully.</div></div>';
                            $("#alert_message").html(html);
                            $("#alert_message").show();
                            $('html, body').animate({
                                scrollTop: $(".page-head").offset().top
                            }, 'fast')
                            $(".loading-submit").hide();
                            setTimeout(function()
                            {
                                $("#alert_message").hide();
                            },6000)
                            // window.location.href = baseUrl +"dashboard/"+ project_id +"/notice_completion";
                        })
                        .fail(function(jqXHR, textStatus, errorThrown) {
                            console.log("HTTP Request Failed");
                            var responseText, html;
                            responseText = JSON.parse(jqXHR.responseText);
                            console.log(responseText);
                        })
                    },5000)
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
        }
        else {

            setTimeout(function()
            {
                jQuery.ajax({
                    url: baseUrl + "notice-completion/add",
                    type: "POST",
                    data: {
                        "noc_rec_text" : noc_rec_text,
                        "noc_rec_name" : noc_rec_name,
                        "noc_rec_street" : noc_rec_street,
                        "noc_rec_adress" : noc_rec_adress,
                        "noc_notice_text_1" : noc_notice_text_1,
                        "noc_notice_text_2" : noc_notice_text_2,
                        "noc_notice_text_3" : noc_notice_text_3,
                        "noc_notice_text_4" : noc_notice_text_4,
                        "noc_notice_text_5" : noc_notice_text_5,
                        "noc_notice_text_6" : noc_notice_text_6,
                        "noc_notice_text_7" : noc_notice_text_7,
                        "noc_notice_text_8" : noc_notice_text_8,
                        "noc_notice_text_9" : noc_notice_text_9,
                        "noc_notice_text_10" : noc_notice_text_10,
                        "noc_notice_text_11" : noc_notice_text_11,
                        "noc_notice_text_12" : noc_notice_text_12,
                        "noc_notice_text_13" : noc_notice_text_13,
                        "noc_notice_text_14" : noc_notice_text_14,
                        "noc_notice_text_15" : noc_notice_text_15,
                        "noc_notice_text_16" : noc_notice_text_16,
                        "noc_notice_text_17" : noc_notice_text_17,
                        "noc_notice_text_18" : noc_notice_text_18,
                        "noc_notice_text_19" : noc_notice_text_19,
                        "noc_notice_text_20" : noc_notice_text_20,
                        "noc_notice_text_21" : noc_notice_text_21,
                        "noc_notice_text_22" : noc_notice_text_22,
                        "noc_ver_text_1" : noc_ver_text_1,
                        "noc_ver_text_2" : noc_ver_text_2,
                        "noc_ver_text_3" : noc_ver_text_3,
                        "noc_ver_text_4" : noc_ver_text_4,
                        "noc_ver_text_5" : noc_ver_text_5,
                        "noc_ver_text_6" : noc_ver_text_6,
                        "noc_ver_text_7" : noc_ver_text_7,
                        "noc_ser_text_1" : noc_ser_text_1,
                        "noc_ser_text_2" : noc_ser_text_2,
                        "noc_ser_text_3" : noc_ser_text_3,
                        "noc_ser_text_4" : noc_ser_text_4,
                        "noc_ser_text_5" : noc_ser_text_5,
                        "noc_ser_text_6" : noc_ser_text_6,
                        "noc_ser_text_7" : noc_ser_text_7,
                        "noc_ser_text_8" : noc_ser_text_8,
                        "noc_ser_text_9" : noc_ser_text_9,
                        "noc_ser_text_10" : noc_ser_text_10,
                        "noc_ser_text_11" : noc_ser_text_11,
                        "noc_ser_text_12" : noc_ser_text_12,
                        "noc_ser_text_13" : noc_ser_text_13,
                        "noc_ser_text_14" : noc_ser_text_14,
                        "noc_ser_text_15" : noc_ser_text_15,
                        "noc_ser_text_16" : noc_ser_text_16,
                        "noc_ser_text_17" : noc_ser_text_17,
                        "noc_con_text_1" : noc_con_text_1,
                        "noc_con_text_2" : noc_con_text_2,
                        "noc_con_text_3" : noc_con_text_3,
                        "noc_con_text_4" : noc_con_text_4,
                        "noc_con_text_5" : noc_con_text_5,
                        "noc_con_text_6" : noc_con_text_6,
                        "noc_potential" : noc_potential,
                        "noc_project_id" : upload_project_id,
                        "noc_file_path" : upload_doc_id,
                        "date_noc_filed" : date_noc_filed,
                        "improvement_type" : improvement_type,
                        "signatory_arr"    : signatory_arr,
                    },
                    headers: {
                        "x-access-token": token
                    },
                    contentType: "application/x-www-form-urlencoded",
                    cache: false
                })
                .done(function(data, textStatus, jqXHR) {
                    console.log(data.description);
                    $('.loading_data1').hide();

                    //$('#notice-addForm').hide();
                    //$("#my-awesome-dropzone").hide();
                    //$("#potential_claimants").hide();
                    $(".remove_file_drop").trigger("click");
                    $('input:radio:checked').prop('checked', false);
                    $('input:checkbox:checked').prop('checked', false);
                    $("#upload_single_doc_id").removeAttr('value');
                    $("#noc_rec_text").removeAttr('value');
                    $("#noc_rec_name").removeAttr('value');
                    $("#noc_rec_street").removeAttr('value');
                    $("#noc_rec_adress").removeAttr('value');
                    $("#noc_notice_text_1").removeAttr('value');
                    $("#noc_notice_text_2").removeAttr('value');
                    $("#noc_notice_text_3").removeAttr('value');
                    $("#noc_notice_text_4").removeAttr('value');
                    $("#noc_notice_text_5").removeAttr('value');
                    $("#noc_notice_text_6").removeAttr('value');
                    $("#noc_notice_text_7").removeAttr('value');
                    $("#noc_notice_text_8").removeAttr('value');
                    $("#noc_notice_text_9").removeAttr('value');
                    $("#noc_notice_text_10").removeAttr('value');
                    $("#noc_notice_text_11").removeAttr('value');
                    $("#noc_notice_text_12").removeAttr('value');
                    $("#noc_notice_text_13").removeAttr('value');
                    $("#noc_notice_text_14").removeAttr('value');
                    $("#noc_notice_text_15").removeAttr('value');
                    $("#noc_notice_text_16").removeAttr('value');
                    $("#noc_notice_text_17").removeAttr('value');
                    $("#noc_notice_text_18").removeAttr('value');
                    $("#noc_notice_text_19").removeAttr('value');
                    $("#noc_notice_text_20").removeAttr('value');
                    $("#noc_notice_text_21").removeAttr('value');
                    $("#noc_notice_text_22").removeAttr('value');
                    $("#noc_ver_text_1").removeAttr('value');
                    $("#noc_ver_text_2").removeAttr('value');
                    $("#noc_ver_text_3").removeAttr('value');
                    $("#noc_ver_text_4").removeAttr('value');
                    $("#noc_ver_text_5").removeAttr('value');
                    $("#noc_ver_text_6").removeAttr('value');
                    $("#noc_ver_text_7").removeAttr('value');
                    $("#noc_ser_text_1").removeAttr('value');
                    $("#noc_ser_text_2").removeAttr('value');
                    $("#noc_ser_text_3").removeAttr('value');
                    $("#noc_ser_text_4").removeAttr('value');
                    $("#noc_ser_text_5").removeAttr('value');
                    $("#noc_ser_text_6").removeAttr('value');
                    $("#noc_ser_text_7").removeAttr('value');
                    $("#noc_ser_text_8").removeAttr('value');
                    $("#noc_ser_text_9").removeAttr('value');
                    $("#noc_ser_text_10").removeAttr('value');
                    $("#noc_ser_text_11").removeAttr('value');
                    $("#noc_ser_text_12").removeAttr('value');
                    $("#noc_ser_text_13").removeAttr('value');
                    $("#noc_ser_text_14").removeAttr('value');
                    $("#noc_ser_text_15").removeAttr('value');
                    $("#noc_ser_text_16").removeAttr('value');
                    $("#noc_ser_text_17").removeAttr('value');
                    $("#noc_con_text_1").removeAttr('value');
                    $("#noc_con_text_2").removeAttr('value');
                    $("#noc_con_text_3").removeAttr('value');
                    $("#noc_con_text_4").removeAttr('value');
                    $("#noc_con_text_5").removeAttr('value');
                    $("#noc_con_text_6").removeAttr('value');
                    $("#date_noc_filed").removeAttr('value');
                    $("select#project_type_dropdown")[0].selectedIndex = 0;
                    $(".first_button").text('Save Another');





                    html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Notice of Completion uploaded successfully.</div></div>';
                    $("#alert_message").html(html);
                    $("#alert_message").show();
                    $('html, body').animate({
                        scrollTop: $(".page-head").offset().top
                    }, 'fast')
                    $(".loading-submit").hide();
                    setTimeout(function()
                    {
                        $("#alert_message").hide();
                    },6000)
                    // window.location.href = baseUrl +"dashboard/"+ project_id +"/notice_completion";
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.log("HTTP Request Failed");
                    var responseText, html;
                    responseText = JSON.parse(jqXHR.responseText);
                    console.log(responseText);
                })
            },5000)

        }
    });
    
    