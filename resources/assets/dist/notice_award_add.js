var role = localStorage.getItem('u_role');
var token = localStorage.getItem('u_token');
var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
var check_permission = jQuery.inArray("notice_award_add", check_user_access );
$(document).ready(function() {


    // Get login user profile data
    $("#submit_new_btn").hide();
    $("#company_name").hide();
    $('#upload_doc_id').removeAttr('value');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3]; // projects
    console.log(project_id);

    

    // Check Permission
    
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
        // console.log(data.data);
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
            // console.log(data.data.cur_symbol);
            $('.project_currency').text(data.data.cur_symbol+' ');
            $('.pdf_gen_project_currency').text(data.data.cur_symbol+' ');
        })
    })

    // Selected Improvement Type
    jQuery.ajax({
    //url: baseUrl +project_id+"/improvement-type",
    url: baseUrl +project_id+"/improvement-type",
        type: "GET",
        headers: {
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        console.log(data);
        window.improvement_type_array = [];
        window.latest_improvement_type_array = [];
        // Foreach Loop
//        $("#project_type_dropdown_new").append(
//            '<option value="">Select Improvement Type</option>'
//        )
        jQuery.each(data.data, function( i, val ) {
            if(val.pt_status == 'active'){
                $(".project_type").append(
                    '<span class="label label-inverse" style="display: inline-block; font-size: 14px; margin: 10px 15px 10px 0px; padding: 5px 15px;">'+val.pt_name+'</span>'
                )

//                $("#project_type_dropdown_new").append(
//                    '<option value="'+val.pt_id+'">'+val.pt_name+'</option>'
//                )
//                $("#project_type_dropdown_old").append(
//                    '<option value="'+val.pt_id+'">'+val.pt_name+'</option>'
//                )
                improvement_type_array.push(val.pt_id);
                latest_improvement_type_array.push(val.pt_name);
            }else {

            }
        });
        $(".loading_data").remove();
        $("#project_type_dropdown_new").show();
        $("#project_type_dropdown_old").show();
        $(".project_type").show();
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        console.log(response);
        if(response == 403){
            // window.location.href = baseUrl + "403";
        }
        else if(response == 404){
            $("#s2id_project_type").show();
            //window.location.href = baseUrl + "404";
        }
        else {
            window.location.href = baseUrl + "500";
        }
    });

    // All Improvement Type
    jQuery.ajax({
    url: baseUrl + project_id +"/improvement-type-by-owner",
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
        $(".project_type_dropdown").append(
            '<option value="">Select Improvement Type</option>'
        )
        jQuery.each(data.data, function( i, val ) {
            if(val.pt_status == 'active'){
                $(".project_type_dropdown").append(
                    '<option value="'+val.pt_id+'">'+val.pt_name+'</option>'
                )
            }
        });
        // $( "h2" ).appendTo( $( ".container" ) );
       
        $(".loading_data").remove();
        $("#s2id_project_type_dropdown").show();
        // $("#add_project_form").show();
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        console.log(response);
        if(response == 403){
        console.log('All Improvement 403');
            // window.location.href = baseUrl + "403";
        }
        else if(response == 404){
            console.log('All Improvement 404');
            // window.location.href = baseUrl + "404";
        }
        else {
            window.location.href = baseUrl + "500";
        }
    }); 
    
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
        $("#company_name").append(
            '<option value="">Select Contractor Name</option>'
        )
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
            console.log(data.data[0].total_amount);
            // $("#pdf_gen_contract_amount").text(ReplaceNumberWithCommas(data.data[0].total_amount));
            $("#bid_amount").val(data.data[0].total_amount);
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


        jQuery.ajax({
        url: baseUrl +project_id+"/bid-documents",
            type: "GET",
            headers: {
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            console.log(data.data[0].bd_bid_advertisement_date);
            $('#pdf_gen_bid_advertisement_date').text(data.data[0].bd_bid_advertisement_date);
        })

    setTimeout(function(){
        $('#signatory_container').delegate( 'a.remove_signatory', 'click', function () {
            var id = $(this).attr("counter");
            //alert(id);
            $('.sign'+id).remove();
            return;
        });
        $('#signatory_container').delegate( 'a.add_signatory', 'click', function () {
            var signatory_counter = $("#signatory_counter").val();
            signatory_counter++;
            var html = '<div class="sign'+signatory_counter+'">\n\
                        <div class="form-group col-md-5">\n\
                            <label for="">Signatory Name</label>\n\
                            <input class="form-control" name="signatory_name[]" type="text" id="">\n\
                        </div>\n\
                        <div class="form-group col-md-5">\n\
                            <label for="">Signatory Email</label>\n\
                            <input class="form-control" name="signatory_email[]" type="text" id="">\n\
                        </div>\n\
                        <div class="form-group col-md-2" style="padding-top: 25px;">\n\
                            <a class="btn btn-success add_signatory" counter="'+signatory_counter+'">+</a>&nbsp;\n\
                            <a class="btn btn-success remove_signatory" counter="'+signatory_counter+'">-</a>\n\
                        </div>\n\
                    </div>';
       
            $("#signatory_container").append(html);
            $("#signatory_counter").val(signatory_counter);
            return;
        });

    }, 1000);   

});


    $('.submit_notice_add_form').click(function(e) {
        $('.loading-submit').show();
        e.preventDefault();
        setTimeout(function()
        {
            add_notice_award_data();
        },5000)
    });

    $('#cmd').click(function (e) {
        var is_error = false;
        e.preventDefault();
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
        if($('#company_name').val()=="")
        {
            html += '<li>Contractor name is required.</li>';
            is_error = true;
        }
        html += '</ul></div>';
        if(is_error == true){
            $("#alert_message").html(html);
            $("#alert_message").show();
            $('.loading-submit').hide();
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            setTimeout(function(){
                $("#alert_message").hide();
                return false;
            },5000)
            return;
        }
        $('.loading-submit').show();
        var doc_meta = $("#upload_doc_meta").val();
        var doc_project_id = $("#upload_project_id").val();

        // Add Improvement Type in PDF
        var improvement_type = $("#project_type_dropdown_new").val();
        var project_type_dropdown_new = $('#project_type_dropdown_new').val();
        var project_type_dropdown_old = $('#project_type_dropdown_old').val();
        var update_impv_type = 0;
        if(project_type_dropdown_new){
            var improvement_type    = project_type_dropdown_new;
            var update_impv_type = 1;
        }
        else if(project_type_dropdown_old) {
            var improvement_type    = project_type_dropdown_old;
            var update_impv_type = 1;
        }
        jQuery.ajax({
        url: baseUrl + "improvement-type/"+improvement_type,
            type: "GET",
            headers: {
              "Content-Type": "application/json",
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            console.log(data.data.pt_name);
            //alert(data.data[0].pt_name);
            
            //$('#pdf_gen_project_type').text(data.data.pt_name);
            if(update_impv_type==1)
                $('#pdf_gen_project_type').text(data.data.pt_name);
            else
                $('#pdf_gen_project_type').text(latest_improvement_type_array.join(','));
            var bid_total_amount = $("#bid_amount").val();
            $("#pdf_gen_contract_amount").text(ReplaceNumberWithCommas(bid_total_amount));
        })


        // Select Company Detail for PDF
        var company_name = $("#company_name").val();
        jQuery.ajax({
        url: baseUrl + "firm-name/"+company_name,
            type: "GET",
            headers: {
              "Content-Type": "application/json",
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            var f_name = data.data.f_name;
            $('#pdf_gen_contractor_name').text(f_name);
            var firm_address = data.data.f_address;
            $('#pdf_gen_contractor_address').text(firm_address);
        })
        setTimeout(function()
        {
            var document_generated  = $("#notice_award_pdf_content").html();
            var document_path       = 'uploads/notice_award/';
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
                    $("#upload_doc_id").val(data.description);
                    console.log('document upload');
                    // $('#cmd').hide();
                    // $("#submit_new_btn").show();
                    setTimeout(function()
                    {
                        add_notice_award_data();
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
        },3000)
    });
    var project_add_impvtype_on_fly = jQuery.inArray("project_add_impvtype_on_fly", check_user_access );
    if(project_add_impvtype_on_fly>0 || role=="owner"){
        $('.add-impvtypes').click(function(){
            $('#add-impvtypes').modal('show');
        }) 
    }else{
        $('.add-impvtypes').hide();
    }

    function add_notice_award_data(){
        var check_award_type        = $("input[name='check_award_type']:checked"). val();
        var company_name            = $('#company_name').val();
        var bid_amount              = $('#bid_amount').val();
        var award_date              = $('#award_date').val();
        var upload_doc_id           = $('#upload_doc_id').val();
        var project_id              = $('#upload_project_id').val();
        var token                   = localStorage.getItem('u_token');
        var project_type_dropdown_new = $('#project_type_dropdown_new').val();
        var project_type_dropdown_old = $('#project_type_dropdown_old').val();
        if($('input:radio[name=contractor_bond_required]:checked').val()!="")
            var contractor_bond_required = $('input:radio[name=contractor_bond_required]:checked').val();
        else
            var contractor_bond_required = "no";
        //alert(contractor_bond_required);return false;
        if(contractor_bond_required=="yes")
        {
            $('.extra_doc_name').html('Performance Bond, Payment Bond and ');
        }
        var html;
        var is_error = false;
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
        
        //alert(company_name);return false;
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
        signatory_arr = [];
        for (i = 0; i < signatory_email.length; i++) {
            signatory_arr.push({
		            "signatory_name" 		:   item['signatory_name'][i],
		            "signatory_email" 		:   item['signatory_email'][i],
                            "noa_company_name"          :   $('#pdf_gen_contractor_name').html(),
                            "noa_company_address"       :   $('#pdf_gen_contractor_address').html(),
                            "noa_project_name"          :   $('#pdf_gen_project_name').html(),
                            "noa_improvement_type"      :   $('#pdf_gen_project_type').html(),
                            "noa_date"                  :   $('#pdf_gen_noa_date').html(),
                            "noa_bid_advertisement_date":   $('#pdf_gen_bid_advertisement_date').html(),
                            "noa_bid_amount"            :   $('#pdf_gen_contract_amount').html(),
		        });
        }
        if(project_type_dropdown_new){
            var improvement_type    = project_type_dropdown_new;
        }
        else if(project_type_dropdown_old) {
            var improvement_type    = project_type_dropdown_old;
        }else{
            var improvement_type = improvement_type_array.join(',')
        }
        //alert(improvement_type_array);return false;
        if($('#company_name').val()=="")
        {
            html += '<li>Contractor name is required.</li>';
            is_error = true;
        }
        html += '</ul></div>';
        console.log(improvement_type);
        if(is_error == true){
            $("#alert_message").html(html);
            $("#alert_message").show();
            $('.loading-submit').hide();
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            setTimeout(function(){
                $("#alert_message").hide();
                return false;
            },5000)
            return;
        }
        else {
        jQuery.ajax({
            url: baseUrl + "notice-award/add",
            type: "POST",
            data: {
                "notice_status"         : check_award_type,
                "improvement_type"      : improvement_type,
                "contactor_name"        : company_name,
                "contact_amount"        : bid_amount,
                "award_date"            : award_date,
                "notice_path"           : upload_doc_id,
                "project_id"            : project_id,
                "signatory_arr"         : signatory_arr,
                "contractor_bond_required": contractor_bond_required,
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
        .done(function(data, textStatus, jqXHR){
            $('.loading-submit').hide();
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").show();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New notice of award added successfully!</div></div>';
            $("#alert_message").html(html);
            $('#upload_doc_id').removeAttr('value');
            $('#bid_amount').removeAttr('value');
            $('#award_date').removeAttr('value');
            $('input[name^=signatory_name],input[name^=signatory_email]').each(function(){$(this).val('');});
            $("#company_name").val('');
            $(".submit_notice_add_form").text('Save Another');
            $("#cmd").text('Save Another');
            $("#project_type_dropdown_new").val('');
            $(".remove_file_drop").trigger("click");
            // $('#cmd').show();
            // $("#submit_new_btn").hide();
            setTimeout(function()
            {
                $("#alert_message").hide();
            },5000)
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            $('.loading-submit').hide();
            console.log("HTTP Request Failed");
            var responseText, html;
            responseText = JSON.parse(jqXHR.responseText);
            console.log(responseText.data);
            console.log(jqXHR.responseText);
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").show();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-error"><ul>';
            if(responseText.data.notice_status != null){
                html += '<li>The notice type is required.</li>';
            }
            if(responseText.data.contactor_name != null){
                html += '<li>The contractor name is required.</li>';
            }
            if(responseText.data.contact_amount != null){
                html += '<li>The contract amount is required.</li>';
            }
            if(responseText.data.award_date != null){
                html += '<li>The notice of award date is required.</li>';
            }
            if(responseText.data.notice_path != null){
                html += '<li>The upload file is required.</li>';
            }
            if(responseText.data.project_id != null){
                html += '<li>The project id field is required.</li>';
            }
            if(responseText.data.docusign != null){
                html += '<li>'+responseText.data.description+'</li>';
            }
            html += '</ul></div>';
            $("#alert_message").html(html);
            setTimeout(function()
            {
                $("#alert_message").hide();
            },5000);
        })
        }
    }
    
    $('#company_name').change(function(){
        var company = $(this).val();
        if(company=="Add New Contractor" || company=="Add New Company")
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
    
    
