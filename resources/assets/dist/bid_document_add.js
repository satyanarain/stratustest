$(document).ready(function() { 
    // Get login user profile data
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3 ]; // projects
    console.log(project_id);
    
    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("bid_document_add", check_user_access );
    console.log(check_permission);
    if(check_permission < 1){
        window.location.href = baseUrl + "403";
    }
    else {
        console.log('Yes Permission');
        $('.body-content .wrapper').show();
    }

    $("#s2id_company_name").hide();
    $("#s2id_company_name_two").hide();
    $("#s2id_project_type_dropdown").hide();
    $('#upload_doc_id').removeAttr('value');
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    jQuery.ajax({
    url: baseUrl+project_id+"/company_name_user_agency",
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
                $("#agency_name").append(
                    '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                )
                
            }else {

            }
        });
        var add_company_on_fly_permission = jQuery.inArray("project_add_company_on_fly", check_user_access );
        if(add_company_on_fly_permission>0 || role=="owner"){
        $("#agency_name").append(
            '<option style="font-weight:bold;">Add New Agency</option>'
        );
        
        }
        // $( "h2" ).appendTo( $( ".container" ) );
       
        // $(".loading_data").remove();
        $("#s2id_company_name").show();
        $("#s2id_company_name_two").show();
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
            $("#agency_name").show();
            // window.location.href = baseUrl + "404";
        }
        else {
            window.location.href = baseUrl + "500";
        }
    });
    
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
                
                 $("#company_name_two").append(
                    '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                )
            }else {

            }
        });
        var add_company_on_fly_permission = jQuery.inArray("project_add_company_on_fly", check_user_access );
        if(add_company_on_fly_permission>0 || role=="owner"){
        $("#company_name_two").append(
            '<option style="font-weight:bold;">Add New Company</option>'
        )
        }
        // $( "h2" ).appendTo( $( ".container" ) );
       
        // $(".loading_data").remove();
        $("#s2id_company_name").show();
        $("#s2id_company_name_two").show();
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
            $("#agency_name").show();
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
        if(data.data.f_name)
        {
            $("#company_name_lead").append(
                '<span class="label label-inverse" style="display: inline-block; font-size: 14px; margin: 10px 15px 10px 0px; padding: 5px 15px;">'+data.data.f_name+'</span>'
            );
        }
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
        window.improvement_type_array = [];
        // Foreach Loop 
//        $("#project_type").append(
//            '<option value="">Select Improvement Types</option>'
//        )
        jQuery.each(data.data, function( i, val ) {
            if(val.pt_status == 'active'){
                $("#project_type").append(
                    '<span class="label label-inverse" style="display: inline-block; font-size: 14px; margin: 10px 15px 10px 0px; padding: 5px 15px;">'+val.pt_name+'</span>'
                    // '<li class="select2-search-choice"><div>'+val.pt_name+'</div><a href="#" onclick="return false;" class="select2-search-choice-close" tabindex="-1"></a></li>'
                )
                improvement_type_array.push(val.pt_id);
            }else {

            }
        });
        
        // $("#project_type").append(
        //     '<li class="select2-search-field"><input autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input select2-default" id="s2id_autogen3" style="width: 453px;" type="text"></li>'
        // )
        // $( "h2" ).appendTo( $( ".container" ) );
        // console.log(improvement_type_array);
        $(".loading_data").remove();
        $("#s2id_project_type").show();
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        console.log(response);
        if(response == 403){
            window.location.href = baseUrl + "403";
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
        jQuery.each(data.data, function( i, val ) {
            if(val.pt_status == 'active'){
                $("#project_type_dropdown").append(
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

//        jQuery.ajax({
//        url: baseUrl + "/"+project_id+"/default_contractor",
//            type: "GET",
//            headers: {
//              "Content-Type": "application/json",
//              "x-access-token": token
//            },
//            contentType: "application/json",
//            cache: false
//        })
//        .done(function(data, textStatus, jqXHR) {
//            $("#company_name_lead").append(
//                '<span class="label label-inverse" style="display: inline-block; font-size: 14px; margin: 10px 15px 10px 0px; padding: 5px 15px;">'+data.data[0].agency_name+'</span>'
//            )   
//         })
//        .fail(function(jqXHR, textStatus, errorThrown) {
//            console.log("HTTP Request Failed");
//            var response = jqXHR.responseJSON.code;
//            if(response == 403){
//                // window.location.href = baseUrl + "403";
//                console.log("403");
//            }
//            else if(response == 404){
//                console.log("404");
//                // window.location.href = baseUrl + "404";
//            }
//            else {
//                // console.log("500");
//                window.location.href = baseUrl + "500";
//            }
//        }) 

    var project_add_impvtype_on_fly = jQuery.inArray("project_add_impvtype_on_fly", check_user_access );
    if(project_add_impvtype_on_fly>0 || role=="owner"){
        $('.add-impvtypes').click(function(){
            $('#add-impvtypes').modal('show');
        }) 
    }else{
        $('.add-impvtypes').hide();
    }
}); 

    $('#bid_advertisement_yes').change(function() {
        if($(this).is(":checked")) {
     //      var bid_advertisement_yes       =  'yes';
           $('#bid_advertisement_yes').val('yes');
     //      alert('yes');
        }
        else {
     //      var bid_advertisement_yes       =  'no';
           $('#bid_advertisement_yes').val('no');
     //      alert('no');
        }        
    });

    $('#notice_invite_bid_yes').change(function() {
        if($(this).is(":checked")) {
       //    var notice_invite_bid_yes       =  'yes';
           $('#notice_invite_bid_yes').val('yes');
        }
        else {
        //   var notice_invite_bid_yes       =  'no';
           $('#notice_invite_bid_yes').val('no');
        }        
    });

    

    $('#add_bond_form').click(function(e) {
        e.preventDefault();
        $('.loading-submit').show();
        var project_type                        = $('#project_type_dropdown').val();
        var company_name                        = $('#agency_name').val();
        var bid_advertisement_date              = $('#bid_advertisement_date').val();
        var upload_doc_id_adv_bid               = $('#upload_doc_id_adv_bid').val();
        var bid_advertisement_yes               = $('#bid_advertisement_yes').val();

        var notice_invite_bid_date              = $('#notice_invite_bid_date').val();
        var notice_invite_bid_yes               = $('#notice_invite_bid_yes').val();
        var upload_doc_id_notice_invite         = $('#upload_doc_id_notice_invite').val();
        
        var date_of_bid_opening                 = $('#date_of_bid_opening').val();

        var upload_doc_id_bid_result            = $('#upload_doc_id_bid_result').val();
        var low_bidder_name                     = $('#company_name_two').val();
        var upload_doc_id_success_bidder        = $('#upload_doc_id_success_bidder').val();
      

        var project_id                          = $('#upload_project_id').val();
	    var token                               = localStorage.getItem('u_token');
        project_type = JSON.stringify(project_type);
        console.log(project_type);

        // Validation Certificate
        var html;
        var is_error = false;
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';

        var bid_advertisement_yes = document.getElementById("bid_advertisement_yes").checked;
        //alert(bid_advertisement_yes);return false;
        if(bid_advertisement_yes == true){
            var bid_advertisement_yes = "yes";
            
        }
        else {
            var bid_advertisement_yes = 'no';
            if(bid_advertisement_date == ''){
                html += '<li>Bid advertisement date is required.</li>';
                is_error = true;
            }
            if(upload_doc_id_adv_bid == ''){
                html += '<li>Bid advertisement document is required.</li>';
                is_error = true;
            }
            
        }

        var notice_invite_bid_yes = document.getElementById("notice_invite_bid_yes").checked;
        if(notice_invite_bid_yes == true){
            var notice_invite_bid_yes = "yes";
            
        }
        else {
            var notice_invite_bid_yes = 'no';
            if(notice_invite_bid_date == ''){
                html += '<li>Notice invite bid date is required.</li>';
                is_error = true;
            }
            if(upload_doc_id_notice_invite == ''){
                html += '<li>Notice invite bid document is required.</li>';
                is_error = true;
            }
            
        }

        if(date_of_bid_opening == ''){
            html += '<li>Date of bid opening is required.</li>';
            is_error = true;
        }
        if(upload_doc_id_bid_result == ''){
            html += '<li>Detailed bid results by item document is required.</li>';
            is_error = true;
        }
        if(upload_doc_id_success_bidder == ''){
            html += '<li>Successful bidder’s proposal document is required.</li>';
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
            },3000)
            return false;
        }


        jQuery.ajax({
            url: baseUrl + "bid-documents/add",
            type: "POST",
            data: {
                "type_of_improvement"           : project_type,
                "lead_agency"                   : company_name,
                "bid_advertisement_date"        : bid_advertisement_date,
                "add_applicable"                : bid_advertisement_yes,
                "addvertisement_of_bid_path"    : upload_doc_id_adv_bid,
                "invite_date"                   : notice_invite_bid_date,
                "invite_applicable"             : notice_invite_bid_yes,
                "notice_invite_bid_path"        : upload_doc_id_notice_invite,
                "date_of_opening"               : date_of_bid_opening,
                "detail_result_path"            : upload_doc_id_bid_result,
                "low_bidder_name"               : low_bidder_name,
                "sucessful_bidder_proposal_path": upload_doc_id_success_bidder,
                "project_id"                    : project_id
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            $('.loading-submit').hide();
            console.log(data.description);
            // $('.bid_document_form').hide();
            // $('.add_addendum').show();
            console.log(data);
            // html = '<div class="alert alert-block alert-success fade in">Add successfully Bid Documentation kindly upload addendum</div>';
            // $("#alert_message").html(html);
            // $("#addendum_bid_id").val(data.description);

           window.location.href = baseUrl + "dashboard/"+project_id+"/bid_documents/"+data.description+"/add-addendum";
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            $('.loading-submit').hide();
            console.log("HTTP Request Failed");
            var responseText, html;
            responseText = JSON.parse(jqXHR.responseText);
            console.log(responseText.data);
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").show();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-error"><ul>';
           if(responseText.data.lead_agency != null){
            	html += '<li>The lead agency field is required.</li>';
            }
            if(responseText.data.low_bidder_name != null){
                html += '<li>The low bidder name field is required.</li>';
            }
            if(responseText.data.bid_advertisement_date != null){
                html += '<li>The bid advertisement date field is required.</li>';
            }
            if(responseText.data.addvertisement_of_bid_path != null){
                html += '<li>The advertisement for bids document field is required.</li>';
            }
            if(responseText.data.invite_date != null){
                html += '<li>The notice invite bid date field is required.</li>';
            }
            if(responseText.data.notice_invite_bid_path != null){
                html += '<li>The notice invite bid document field is required.</li>';
            }
            if(responseText.data.date_of_opening != null){
                html += '<li>The date of bid opening field is required.</li>';
            }
            if(responseText.data.detail_result_path != null){
                html += '<li>The detailed bid results by item document field is required.</li>';
            }
            if(responseText.data.low_bidder_name != null){
                html += '<li>The successful bidder’s company name field is required.</li>';
            }
            if(responseText.data.sucessful_bidder_proposal_path != null){
                html += '<li>The successful bidder’s proposal field is required.</li>';
            }
            if(responseText.data.project_id != null){
                html += '<li>The project id field is required.</li>';
            }
            html += '</ul></div></div>';


            $("#alert_message").html(html);
            setTimeout(function(){
                $("#alert_message").hide();
            },5000)
        })
    });



    $('#add_addendum').click(function(e) {
        e.preventDefault();
        $('.loading-submit').show();
        var addendum_bid_id                     = $('#addendum_bid_id').val();
        var addendum_number                     = $('#demo2').val();
        var addendum_date                       = $('#addendum_date').val();
        var addendum_upload                     = $('#upload_doc_id').val();
        var addendum_project_id                 = $('#upload_project_id').val();

        var token                               = localStorage.getItem('u_token');
        project_type = JSON.stringify(project_type);
        console.log(project_type);
        jQuery.ajax({
            url: baseUrl + "bid-documents/add-addendum",
            type: "POST",
            data: {
                "bid_document_id"           : addendum_bid_id,
                "bid_addendum_date"         : addendum_date,
                "bid_doc_path"              : addendum_upload,
                "bid_addendum_number"       : addendum_number,
                "bid_addendum_project_id"   : addendum_project_id,
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            $('.loading-submit').hide();
            console.log(data.description);
            $('#addendum_date').val("");
            $('#upload_doc_id').val("");
            $('#demo2').val("0");

            $("#alert_message").show();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New bid document added successfully!</div></div>';
            $("#alert_message").html(html);
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

            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").show();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-error"><ul>';
            if(responseText.data.bid_addendum_date != null){
                html += '<li>The addendum date field is required.</li>';
            }
            if(responseText.data.bid_doc_path != null){
                html += '<li>The document field is required.</li>';
            }
            if(responseText.data.project_id != null){
                html += '<li>The project id field is required.</li>';
            }
            html += '</ul></div></div>';
            $("#alert_message").html(html);
            setTimeout(function(){
                $("#alert_message").hide();
            },5000)
        })
    });
    
    $('#agency_name,#company_name_two').change(function(){
        var company = $(this).val();
        if(company=="Add New Company" || company=="Add New Agency")
        {
            $('#add-company').modal('show');
            $('#add-company').on('shown.bs.modal',function(){
                google.maps.event.trigger(map, "resize");
              });
            
        }
    })
    
    
       