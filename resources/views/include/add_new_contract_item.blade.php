<!-- page head start-->
<div class="page-head">
    <h3 class="m-b-less">Add Contract Item</h3>
</div>
<!-- page head end-->
<!--body wrapper start-->
<div class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <div id="alert_message"></div>
                <div class="loading_data" style="text-align: center;">
                   <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                </div>
                <div class="panel-body">
                    <form role="form" id="">
                        <div class="row">
                        <div class="form-group col-md-6">
                            <label for="firm_name">Item No.<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="item_no">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Unit of Measure</label>
                            <select class="form-control" id="item_unit">
                                <option value="">Select Unit of Measure</option>
                                <option value="EA">Each</option>
                                <option value="LS">Lump Sum</option>
                                <option value="SF">Square Footage</option>
                                <option value="LF">Linear Feet</option>
                                <option value="CY">Cubic Yard</option>
                                <option value="SY">Square Yard</option>
                                <option value="TN">Tons</option>
                                <option value="MO">Months</option>
                                <option value="YR">Year</option>
                                <option value="DA">Days</option>
                                <option value="LB">Pounds</option>
                                <option value="HR">Hourly</option>
                                <option value="AC">Acres</option>
                                <option value="YD">Yard</option>
                                <option value="GA">Gallon</option>
                                <option value="Other">Other</option>
                            </select>
                            <div class="other_option" style="display: none;">
                                <br/><input type="text" class="form-control" id="item_unit_other">
                             </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="item_description">Item Description <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="item_description">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="item_qty">Quantity <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="item_qty" onkeypress="return isNumber(event)">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="item_unit_price">Unit Price <span class="text-danger">*</span></label>
                            <!-- <input type="text" class="form-control" id="item_unit_price"> -->
                            <div class="input-group m-b-10">
                                <span class="input-group-addon project_currency"></span>
                                <input class="form-control" type="text" id="item_unit_price" onkeypress="return isPrice(event)" >
                            </div>
                            <input type="hidden" class="form-control" id="project_id" value="<?php echo $project_id; ?>">
                        </div>
                        <div class="form-group col-md-12">
                            <!-- <a href="{{ url('/dashboard/firms') }}" class="btn btn-info sub-btn">Back</a> -->

                            <button class="close-add-firm-btn btn btn-info sub-btn back_button modal_btn_back" onclick="return checkFormFilled('modal_btn_back')">Back</button>

                            <button id="add_contract_item_form" class="add_impvtype_form btn btn-info sub-btn first_button1">Submit</button>
                            
                        </div>
                            <p class="loading-submit1" style="display: none;">Loading<span>.</span><span>.</span><span>.</span></p>
                        </div>
                    </form>

                </div>
            </section>
        </div>
    </div>
</div>
            <!--body wrapper end-->
<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script>
$(document).ready(function() {
    var curr_url = $(location).attr('href')
    $(".close-add-firm-btn").attr("href",curr_url);
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
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
    
    $('#add_contract_item_form').click(function(e) {
        //alert("dd");return false;
        $('.loading-submit1').show();
        e.preventDefault();
        var item_description        = $('#item_description').val();
        var item_unit               = $('#item_unit').val();
        var item_unit_other         = $('#item_unit_other').val();
        var item_qty                = $('#item_qty').val();
        var item_unit_price         = $('#item_unit_price').val();
        var item_no                 = $('#item_no').val();
        var project_id              = $('#project_id').val();
        var token                   = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "bid-items/add",
            type: "POST",
            data: {
                "item_description"      : item_description,
                "item_unit"             : item_unit,
                "item_unit_other"       : item_unit_other,
                "item_qty"              : item_qty,
                "item_unit_price"       : item_unit_price,
                "item_no"               : item_no,
                "project_id"            : project_id
            },
            headers: {
                "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
                var html;
                $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
                }, 'fast')
                $("#alert_message").show();
                $('.loading-submit1').hide();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New contract item added successfully!</div></div>';
                $("#alert_message").html(html);

                setTimeout(function(){
                    $("#alert_message").hide();
                },5000)
                $("#item_description").removeAttr('value');
                $("#item_qty").removeAttr('value');
                $("#item_unit_other").removeAttr('value');
                $("#item_unit_price").removeAttr('value');
                $("#item_no").removeAttr('value');
                $("#item_unit").val('');
                $(".first_button").hide();
                $(".another_button").show();
                $("#contract_item_work").empty();
                // Fetch All bid item for given project_id
                    jQuery.ajax({
                        url: baseUrl+project_id+"/bid-items",
                        type: "GET",
                        headers: {
                          "x-access-token": token
                        },
                        contentType: "application/json",
                        cache: false
                    })
                    .done(function(data, textStatus, jqXHR) {
                        jQuery.each(data.data, function( i, val ) {
                            if(val.pbi_status == 'active'){
                                $("#contract_item_work").append(
                                    '<option value="'+val.pbi_id+'">'+val.pbi_item_description+'</option>'
                                )
                            }else {

                            }
                        });
                    });
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
                $('.loading-submit1').hide();
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
  })
  
  
function get_improvement_project()
{
    $(".project_type_dropdown").empty();
    jQuery.ajax({
        url: baseUrl + "improvement-type",
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
            '<option value="">Select Improvement Types</option>'
        )
        jQuery.each(data.data, function( i, val ) {
            if(val.pt_status == 'active'){
                $(".project_type_dropdown").append(
                    '<option value="'+val.pt_id+'">'+val.pt_name+'</option>'
                )
            }else {

            }
        });
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
            console.log('Improvement type 404');
            alert("You can't add project, first add improvement type!");
            // window.location.href = baseUrl + "404";
        }
        else {
            window.location.href = baseUrl + "500";
        }
    });
}
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
</script>

