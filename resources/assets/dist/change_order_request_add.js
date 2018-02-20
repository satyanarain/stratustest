$(document).ready(function() {
    // Get login user profile data
    $('#upload_doc_id').removeAttr('value');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3]; // projects
    // console.log(project_id);

    var role = localStorage.getItem('u_role');
    var token  = localStorage.getItem('u_token');

    // Check View All Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray( "cor_add", check_user_access );
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
            window.agency_id = data.data[0].ps_agency_name;
            $("#company_name").val(parseInt(agency_id));
            // Select Company Detail for PDF
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
                $('.loading_data').hide();
                var f_name = data.data.f_name;
                $('#contractor_name').text(f_name);
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
                // alert('faizan');
                // window.location.href = baseUrl + "404";
                $(".loading_data").hide();
            }
            else {
                window.location.href = baseUrl + "500";
            }
        });

        get_new_cor_data();


        jQuery.ajax({
        url: baseUrl +project_id+"/request-information",
            type: "GET",
            headers: {
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            console.log(data.data);
            jQuery.each(data.data, function( i, val ) {
                if(val.ri_request_status == 'active'){
                    $("#rfi_add_div_1").append(
                        // '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                        // '<label class="checkbox-custom check-success">'+
                            '<input type="checkbox" value="'+val.ri_id+'"  name="rfi_detail[]">'+
                            '<label for="">RFI # '+val.ri_id+' : '+val.ri_question_request+'</label><br/>'
                        // '</label>'
                    )
                }
            });
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            console.log(response);
        });
});

    $('body').delegate( '.rfi_yes', 'change', function () {
        var id = $(this).find(".rfi_add_div:first").attr("id");
        console.log(id);
        // $('#'+id).change(function() {
            $('#'+id).show();
            if($('#'+id).is(":checked")) {
                $('#'+id).show();
            }
            else {
               $(id).hide();
            }
        // });
        return;
    });


    $('body').delegate( '.upload_doc_panel', 'click', function () {
        var id = $(this).find(".upload_doc_id:first").attr("id");
        // console.log(id);
        window.localStorage.setItem("upload_doc_id", id);
        console.log(localStorage.getItem("upload_doc_id"));
        return;
    });

    $('body').delegate('.remove_item', 'click', function (e) {
        e.preventDefault();
        $(this).parent().parent().remove();
    });

    setTimeout(function(){
        $('body').delegate( '#add_more', 'click', function () {
            var randum_number = Math.floor(Math.random()*(99-11+1)+11);
            var html = '<div class="col-md-12 nopadleft material_delivered_detail add_extra_panel">'+
                        '<div class="col-sm-12">'+
                            '<a class="btn btn-danger remove_item" style="float: right;"> Remove Item</a>'+
                        '</div>'+
                        '<div class="col-md-6 nopadleft">'+
                            '<div class="form-group clearfix">'+
                                '<label class="nopadleft col-sm-6 control-label mt-6">Description</label>'+
                                '<div class="col-sm-6 nopadleft">'+
                                    '<input type="text" class="form-control" name="item_description[]" placeholder="">'+
                                '</div>'+
                            '</div> '+
                            '<div class="form-group clearfix">'+
                                '<label class="nopadleft col-sm-12 control-label mt-6">Total Requested Costs</label>'+
                                '<div class="col-lg-6 nopadleft">'+
                                    '<div class="check-box">'+
                                        '<label>'+
                                            '<input type="radio" name="select_type" class="item_price_'+randum_number+'" id="price" value="price">'+
                                            'Enter Price'+
                                        '</label>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="col-lg-6 nopadleft">'+
                                    '<div class="check-box">'+
                                        '<label>'+
                                            '<input type="radio" name="select_type" class="item_price_'+randum_number+'" id="unit" value="unit">'+
                                            'Enter Unit'+
                                        '</label>'+
                                    '</div>'+
                                '</div>'+
                                '<div id="item_price_'+randum_number+'">'+
                                    '<div class="item_price" style="display: none;">'+
                                        '<div class="col-lg-12 nopadleft">'+
                                            '<input type="text" class="form-control m-b-10" name="item_price[]" placeholder="$X"  onkeypress="return isNumber(event)">'+
                                        '</div>'+
                                    '</div>'+
                                    //<!-- <span class="nopadleft col-lg-1 control-label mt-6 text-center">OR</span> -->
                                    '<div class="item_unit" style="display: none;">'+
                                        '<div class="col-lg-5 nopadleft">'+
                                            '<input type="text" class="form-control m-b-10" name="item_unit_quantity[]" placeholder="# of Units"  onkeypress="return isNumber(event)">'+
                                        '</div>'+
                                        '<span class="nopadleft col-lg-2 control-label mt-6 text-center">at</span>'+
                                        '<div class="col-lg-5 nopadleft">'+
                                            '<input type="text" class="form-control" name="item_unit_price[]" placeholder="$/Unit"  onkeypress="return isNumber(event)">'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                // '<div class="col-lg-3 nopadleft">'+
                                //     '<input type="text" class="form-control m-b-10" name="item_price[]" placeholder="$X" onkeypress="return isNumber(event)">'+
                                // '</div>'+
                                // '<span class="nopadleft col-lg-1 control-label mt-6 text-center">OR</span>'+
                                // '<div class="col-lg-3 nopadleft">'+
                                //     '<input type="text" class="form-control m-b-10" name="item_unit_quantity[]" placeholder="# of Units" onkeypress="return isNumber(event)">'+
                                // '</div>'+
                                // '<span class="nopadleft col-lg-1 control-label mt-6 text-center">at</span>'+
                                // '<div class="col-lg-4 nopadleft">'+
                                //     '<input type="text" class="form-control" name="item_unit_price[]" placeholder="$/Unit" onkeypress="return isNumber(event)">'+
                                // '</div>'+
                            '</div> '+
                            '<div class="form-group clearfix m-b-40">'+
                                '<label class="nopadleft col-sm-6 control-label mt-6">Total Requested Time in Days</label>'+
                                '<div class="col-sm-6 nopadleft">'+
                                    '<input type="text" class="form-control" name="item_request_time[]" placeholder="Days" onkeypress="return isNumber(event)">'+
                                '</div>'+
                            '</div>'+
            '<div class="form-group ">'+
                '<div class="col-sm-12 rfi_yes">'+
                    '<label class="checkbox-custom check-success">'+
                        '<input type="checkbox" class="rfi_add" value=" " id="rfi_add_'+randum_number+'">'+
                        '<label for="rfi_add_'+randum_number+'"> Additional costs for RFIs will be stored here</label>'+
                        '</label>'+
                    '<div class="col-sm-12 form-group rfi_add_div" id="rfi_add_div_'+randum_number+'" style="display: none;">'+
                    '</div>'+
                '</div>'+
            '</div>'+
                        '</div>'+
                        '<div class="col-sm-6 m-b-30">'+
                            '<label>Upload Change Order Request  <span class="text-danger">*</span></label>'+
                            '<section class="panel upload_doc_panel" id="upload_div">'+
                                '<div class="panel-body" style="padding: 0px;">'+
                                    '<form id="my-awesome-dropzone" action="'+baseUrl+'group_doc/index.php" class="dropzone">'+
                                        '<input type="hidden" name="document_path" value="/uploads/cor/">'+
                                    '</form> '+
                                    '<input type="hidden" name="upload_doc_id[]" class="upload_doc_id" id="upload_doc_id_'+randum_number+'" value="">'+
                                    '<input type="hidden" name="upload_type" id="upload_type" value="multiple_upload">'+
                                '</div>'+
                            '</section>'+
                        '</div>'+
                    '</div>';
                $(".material_delivered_all").append(html);
                $('.material_delivered_detail:last .upload_doc_panel .panel-body form').dropzone({url: baseUrl+'group_doc/index.php'});

                jQuery.ajax({
                url: baseUrl +project_id+"/request-information",
                    type: "GET",
                    headers: {
                      "x-access-token": token
                    },
                    contentType: "application/json",
                    cache: false
                })
                .done(function(data, textStatus, jqXHR) {
                    console.log(data.data);
                    jQuery.each(data.data, function( i, val ) {
                        if(val.ri_request_status == 'active'){
                            $('#rfi_add_div_'+randum_number).append(
                                // '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                                // '<label class="checkbox-custom check-success">'+
                                    '<input type="checkbox" value="'+val.ri_id+'" name="rfi_detail[]">'+
                                    '<label>RFI # '+val.ri_id+' : '+val.ri_question_request+'</label><br/>'
                                // '</label>'
                            )
                        }
                    });
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.log("HTTP Request Failed");
                    var response = jqXHR.responseJSON.code;
                    console.log(response);
                });

            return;
        });
    }, 5000);


    function get_new_cor_data(){
        // Select Rejected Submittal Name
        jQuery.ajax({
        url: baseUrl +"/"+project_id+"/change_order_request_new_number",
            type: "GET",
            headers: {
              "Content-Type": "application/json",
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            console.log(data.data.pco_number);
            var new_cor_num = parseInt(data.data.pco_number)+1;
            // var new_cor_num = .val(parseInt(data.data.pco_number+1));
            $("#cor_new_number").val(new_cor_num);
            $(".cor_new_number").text("# "+ new_cor_num);
            $(".loading_data").hide();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            console.log(response);
            if(response == 403){
                window.location.href = baseUrl + "403";
            }
            else if(response == 404){
                $("#cor_new_number").val('1');
                $(".cor_new_number").text('1');
                // window.location.href = baseUrl + "404";
                // $(".loading_data").hide();
            }
            else {
                window.location.href = baseUrl + "500";
            }
        });
    }

    $('.add_cor').click(function(e) {
      $('.loading-submit').show();
        e.preventDefault();
        console.log('form submit click');

        // Validation Certificate
        var html;
        var is_error = false;
        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';

        // FOr Custom Certificate
        var is_error_description = false;
        $('input[name^=item_description]').each(function(){
            if($(this).val() == '')
            {
                is_error_description = true;
                is_error = true;
            }
        });
        if(is_error_description == true){
            html += '<li> Item description field is invalid </li>';
        }

        // var is_error_price = false;
        // $('input[name^=item_price]').each(function(){
        //     if($(this).val() == '')
        //     {
        //         is_error_price = true;
        //         is_error = true;
        //     }
        // });
        // if(is_error_price == true){
        //     html += '<li> Item price field is invalid </li>';
        // }

        // var is_error_unit_quantity = false;
        // $('input[name^=item_unit_quantity]').each(function(){
        //     if($(this).val() == '')
        //     {
        //         is_error_unit_quantity = true;
        //         is_error = true;
        //     }
        // });
        // if(is_error_unit_quantity == true){
        //     html += '<li> Item number of quantity field is invalid </li>';
        // }

        // var is_error_unit_price = false;
        // $('input[name^=item_unit_price]').each(function(){
        //     if($(this).val() == '')
        //     {
        //         is_error_unit_price = true;
        //         is_error = true;
        //     }
        // });
        // if(is_error_unit_price == true){
        //     html += '<li> Item unit price field is invalid </li>';
        // }

        var is_error_unit_time = false;
        $('input[name^=item_request_time]').each(function(){
            if($(this).val() == '')
            {
                is_error_unit_time = true;
                is_error = true;
            }
        });
        if(is_error_unit_time == true){
            html += '<li> Requested time field is invalid </li>';
        }

        var is_error_upload_doc_id = false;
        $('input[name^=upload_doc_id]').each(function(){
            if($(this).val() == '')
            {
                is_error_upload_doc_id = true;
                is_error = true;
            }
        });
        if(is_error_upload_doc_id == true){
            html += '<li> Document field is invalid </li>';
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
                return true;
            },3000)
        }
        else {

            // Check Item in Loop
            var item_description = [];
            $('input[name^=item_description]').each(function(){
                item_description.push($(this).val());
            });
            // console.log(item_description);

            var item_price = [];
            $('input[name^=item_price]').each(function(){
                item_price.push($(this).val());
            });
            // console.log(item_price);

            var item_unit_price = [];
            $('input[name^=item_unit_price]').each(function(){
                item_unit_price.push($(this).val());
            });
            // console.log(item_unit_price);

            var item_unit_quantity = [];
            $('input[name^=item_unit_quantity]').each(function(){
                item_unit_quantity.push($(this).val());
            });
            // console.log(item_unit_quantity);

            var item_request_time = [];
            $('input[name^=item_request_time]').each(function(){
                item_request_time.push($(this).val());
            });
            // console.log(item_request_time);

            var upload_doc_id = [];
            $('input[name^=upload_doc_id]').each(function(){
                upload_doc_id.push($(this).val());
            });
            // console.log(upload_doc_id);


            var rfi_id = [];
            $(".rfi_add_div").each(function(){
               rfi_id.push($(this).attr('id'));
            });
            // console.log(rfi_id);

            var rfi_item = {};
            rfi_item['rfi_item']       = rfi_id;

            rfi_id_final = [];
            // console.log(rfi_id.length);
            for (i = 0; i < rfi_id.length; i++) {
                rfi_id_final.push({
                    "rfi_item"     :  rfi_item['rfi_item'][i]
                });
            }
            // console.log(rfi_id_final);

            var rfi_detail = [];
            var rfi_details = [];

            jQuery.each(rfi_id_final, function(i, val) {
                // console.log(val.rfi_item)
                var rfi_div_detail             = val.rfi_item;
                $('#'+rfi_div_detail+' input[name^=rfi_detail]').each(function(){
                    // console.log($(this).context.checked);
                    if($(this).context.checked == true) {
                        // console.log($(this).val() + 'Yes');
                        rfi_detail.push($(this).val());
                    }
                    else {
                        // console.log($(this).val() + 'No');
                    }
                });
                // console.log(rfi_detail);

                rfi_details.push({
                    "rfi_detail_item"     :  JSON.stringify(rfi_detail)
                });
                rfi_detail = [];

                // console.log(rfi_details);
            });


            var upload_project_id           = $('#upload_project_id').val();
            var cor_new_number              = $('#cor_new_number').val();
            var cor_date                    = $('#cor_date_today').val();
            var order_contractor_name       = agency_id;
            var token                       = localStorage.getItem('u_token');

            var item = {};
            item['order_description']       = item_description;
            item['order_price']             = item_price;
            item['order_unit_price']        = item_unit_price;
            item['order_unit_number']       = item_unit_quantity;
            item['order_days']              = item_request_time;
            item['order_file_path']         = upload_doc_id;
            item['order_rfi_details']       = rfi_details;

            var item_lenght                 = $(item_description).length;
            // console.log(item_lenght);
            // console.log(item);
            item_final = [];
            for (i = 0; i < item_description.length; i++) {
                item_final.push({
                    "order_description"     :  item['order_description'][i],
                    "order_price"           :  item['order_price'][i],
                    "order_unit_price"      :  item['order_unit_price'][i],
                    "order_unit_number"     :  item['order_unit_number'][i],
                    "order_days"            :  item['order_days'][i],
                    "order_file_path"       :  item['order_file_path'][i],
                    "order_rfi_details"     :  item['order_rfi_details'][i]['rfi_detail_item'],
                    "order_parent_cor"      :  cor_new_number,
                    "order_project_id"      :  upload_project_id
                });
            }
            console.log(item_final);


            console.log(cor_new_number);
            console.log(cor_date);
            console.log(order_contractor_name);
            console.log(upload_project_id);


            jQuery.ajax({
                url: baseUrl+"change_order_request/add",
                type: "POST",
                data: {
                    "order_number"              : cor_new_number,
                    "order_date"                : cor_date,
                    "order_contractor_name"     : order_contractor_name,
                    "order_project_id"          : upload_project_id
                },
                headers: {
                  "x-access-token": token
                },
                contentType: "application/x-www-form-urlencoded",
                cache: false
            })
            .done(function(data, textStatus, jqXHR){
                console.log(data);
                var token                       = localStorage.getItem('u_token');

                jQuery.each(item_final, function(i, val) {
                    console.log(val);
                    jQuery.ajax({
                        url: baseUrl+"change_order_request_item/add",
                        type: "POST",
                        data: {
                            "order_description"     : val.order_description,
                            "order_price"           : val.order_price,
                            "order_unit_price"      : val.order_unit_price,
                            "order_unit_number"     : val.order_unit_number,
                            "order_days"            : val.order_days,
                            "order_file_path"       : val.order_file_path,
                            "order_rfi"             : val.order_rfi_details,
                            "order_parent_cor"      : data.change_order_id,
                            "order_project_id"      : val.order_project_id
                        },
                        headers: {
                          "x-access-token": token
                        },
                        contentType: "application/x-www-form-urlencoded",
                        cache: false
                    })
                    .done(function(data, textStatus, jqXHR) {
                        console.log(data);

                        $("#alert_message").show();
                        $('.loading-submit').hide();
                        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New change order request added successfully!</div></div>';
                        $("#alert_message").html(html);
                        $('input[name="item_description[]"]').removeAttr('value');
                        $('input[name="item_price[]"]').removeAttr('value');
                        $('input[name="item_unit_quantity[]"]').removeAttr('value');
                        $('input[name="item_unit_price[]"]').removeAttr('value');
                        $('input[name="item_request_time[]"]').removeAttr('value');
                        $('input[name="upload_doc_id[]"]').removeAttr('value');
                        $('input[type="checkbox"]').attr('checked', false);
                        $(".remove_file_drop").trigger("click");
                        $(".first_button").hide();
                        $(".another_button").show();
                        $('.add_extra_panel').remove();

                        $('html, body').animate({
                            scrollTop: $(".page-head").offset().top
                        }, 'fast')
                        setTimeout(function(){
                            $("#alert_message").hide()
                        },5000)
                        get_new_cor_data();
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        console.log("HTTP Request Failed");
                        var responseText, html;
                        responseText = JSON.parse(jqXHR.responseText);
                        console.log(responseText);
                    })
                });
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(responseText);
            })
            return;

        }
});
