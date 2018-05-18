$(document).ready(function() {
    // Get login user profile data
    $("#view_users_table_wrapper").hide();
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3 ]; // projects
    console.log(project_id);
    report_id = url[ url.length - 1 ]; // Report_id
    console.log(report_id);

    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("labor_compliance_view_all", check_user_access );
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

    // // Get Selected Agency
    // jQuery.ajax({
    // url: baseUrl + "standards/"+project_id+"/standard",
    //     type: "GET",
    //     headers: {
    //       "x-access-token": token
    //     },
    //     contentType: "application/json",
    //     cache: false
    // })
    // .done(function(data, textStatus, jqXHR) {
    //     window.agency_id = data.data[0].ps_agency_name;
    //     $("#company_name").val(parseInt(agency_id));
    //     $(".loading_data").hide();
    //     jQuery.ajax({
    //         url: baseUrl + "firm-name/"+agency_id,
    //             type: "GET",
    //             headers: {
    //               "Content-Type": "application/json",
    //               "x-access-token": token
    //             },
    //             contentType: "application/json",
    //             cache: false
    //         })
    //     .done(function(data, textStatus, jqXHR) {
    //         // console.log(data.data.f_name);
    //         $('#contractor_name').text(data.data.f_name);
    //     })
    // })

    // Get Selected Agency
    jQuery.ajax({
    url: baseUrl + "labor_compliance/"+report_id,
        type: "GET",
        headers: {
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        console.log(data.data);

        $('#contractor_name').text(data.data.f_name);
        var doc_140 = data.data.doc_140;
        var doc_140_value;
        if(doc_140 == null){
            doc_140_value = ' --- ';
        }
        else {
            doc_140_value = '<a href="'+baseUrl+data.data.doc_140+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40" /></a>';
        }
        $('#doc_140').html(doc_140_value);

        if(data.data.plc_140_date == '0000-00-00' || data.data.plc_140_date == null){
            var plc_140_date = '';
        }
        else {
            var plc_140_date = data.data.plc_140_date;
        }
        $('#date_140').text(plc_140_date);

        var doc_142 = data.data.doc_142;
        var doc_142_value;
        if(doc_142 == null){
            doc_142_value = ' --- ';
        }
        else {
            doc_142_value = '<a href="'+baseUrl+data.data.doc_142+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40" /></a>';
        }
        $('#doc_142').html(doc_142_value);

        if(data.data.plc_142_date == '0000-00-00' || data.data.plc_142_date == null){
            var plc_142_date = '';
        }
        else {
            var plc_142_date = data.data.plc_142_date;
        }
        $('#date_142').text(plc_142_date);

        var doc_fringe = data.data.fringe_doc;
        var doc_fringe_value;
        if(doc_fringe == null){
            doc_fringe_value = ' --- ';
        }
        else {
            doc_fringe_value = '<a href="'+baseUrl+data.data.fringe_doc+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40" /></a>';
        }
        $('#doc_fringe').html(doc_fringe_value);
        if(data.data.plc_fringe_date == '0000-00-00' || data.data.plc_fringe_date == null){
            var plc_fringe_date = '';
        }
        else {
            var plc_fringe_date = data.data.plc_fringe_date;
        }
        $('#date_fringe').text(plc_fringe_date);

        var doc_cac2 = data.data.cac2_doc;
        var doc_cac2_value;
        if(doc_cac2 == null){
            doc_cac2_value = ' --- ';
        }
        else {
            doc_cac2_value = '<a href="'+baseUrl+data.data.cac2_doc+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40" /></a>';
        }
        $('#doc_cac2').html(doc_cac2_value);
        if(data.data.plc_cac2_date == '0000-00-00' || data.data.plc_cac2_date == null){
            var plc_cac2_date = '';
        }
        else {
            var plc_cac2_date = data.data.plc_cac2_date;
        }
        $('#date_cac2').text(plc_cac2_date);

        var doc_cpr = data.data.cpr_doc;
        var doc_cpr_value;
        if(doc_cpr == null && data.data.compliance==null){
            doc_cpr_value = ' --- ';
        }
        else if(data.data.cpr_doc){
            doc_cpr_value = '<a href="'+baseUrl+data.data.cpr_doc+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40" /></a>';
        }
        $('#doc_cpr').html(doc_cpr_value);
        if(data.data.plc_cpr_date == '0000-00-00' || data.data.plc_cpr_date == null){
            var plc_cpr_date = '';
        }
        else {
            var plc_cpr_date = data.data.plc_cpr_date;
        }
        $('#date_cpr').text(plc_cpr_date);

        var doc_compliance = data.data.compliance;
        var doc_compliance_value;
        if(doc_compliance == null && data.data.cpr_doc==null){
            doc_compliance_value = ' --- ';
        }
        else if(data.data.compliance){
            doc_compliance_value = '<a href="'+baseUrl+data.data.compliance+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40" /></a>';
        }
        $('#doc_compliance').html(doc_compliance_value);
        
        var doc_nonperformance_compliance = data.data.compliance_non_performance;
        var doc_nonperformance_compliance_value;
        if(doc_nonperformance_compliance == null){
            doc_nonperformance_compliance_value = ' --- ';
        }
        else {
            doc_nonperformance_compliance_value = '<a href="'+baseUrl+data.data.compliance_non_performance+'" target="_blank" ><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40" /></a>';
        }
        $('#doc_non_performance').html(doc_nonperformance_compliance_value);
        
        if(data.data.plc_compliance_date == '0000-00-00' || data.data.plc_compliance_date == null){
            var plc_compliance_date = '';
        }
        else {
            var plc_compliance_date = data.data.plc_compliance_date;
        }
        $('#date_compliance').text(plc_compliance_date);
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        console.log(response);
        if(response == 403){
            window.location.href = baseUrl + "403";
        }
        else if(response == 404){
            // window.location.href = baseUrl + "404";
            $("#view_users_table_wrapper").show();
            $(".loading_data").hide();
        }
        else {
            window.location.href = baseUrl + "500";
        }
    });

});