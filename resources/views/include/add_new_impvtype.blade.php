<!-- page head start-->
<div class="page-head">
    <h3 class="m-b-less">Add Improvement Type</h3>
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
                    <form role="form">
                        <div class="row">
                        <div class="form-group col-md-6">
                            <label for="firm_name">Improvement Type Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="impv_type_name">
                        </div>
                        <div class="form-group col-md-12">
                            <!-- <a href="{{ url('/dashboard/firms') }}" class="btn btn-info sub-btn">Back</a> -->

                            <button class="close-add-firm-btn btn btn-info sub-btn back_button modal_btn_back" onclick="return checkFormFilled('modal_btn_back')">Back</button>

                            <button class="add_impvtype_form btn btn-info sub-btn first_button1">Submit</button>
                            
                        </div>

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
    $('.add_impvtype_form').click(function(e) {
        e.preventDefault();
        var token = localStorage.getItem('u_token');
        var impv_type_name = $('#impv_type_name').val();
        
        jQuery.ajax({
            url: baseUrl + "improvement-type/add",
            type: "POST",
            data:
            {
                "improvement_type"     : impv_type_name
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
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-success">New improvement type added successfully.!</div></div>';
            $("#alert_message").html(html);
            setTimeout(function()
            {
                $("#toast-container").fadeOut(1000);
            },5000)
            $("#toast-container").fadeOut(10000);
            $("#impv_type_name").removeAttr('value');
            $('.add_impvtype_form').text('Add Another');
            get_improvement_project();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(responseText.data);
                $("#alert_message").fadeIn(1000);
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-error"><ul>';
               if(responseText.data.improvement_type != null){
                    html += '<li>'+responseText.data.improvement_type+'</li>';
                }
                html += '</ul></div></div>';
                $("#alert_message").html(html);
                $("#toast-container").fadeOut(10000);

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
</script>

