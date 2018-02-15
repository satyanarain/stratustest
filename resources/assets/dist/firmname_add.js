    $(document).ready(function() { 
        // Get login user profile data
        $("#s2id_firm_type").hide();
        var role = localStorage.getItem('u_role');
        var token = localStorage.getItem('u_token');
        jQuery.ajax({
        url: baseUrl + "company-type",
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
            $("#firm_type").append(
                '<option value="">Select Company Type</option>'
            );
            jQuery.each(data.data, function( i, val ) {
                if(val.ct_status == 'active'){
                    $("#firm_type").append(
                        '<option value="'+val.ct_id+'">'+val.ct_name+'</option>'
                    )
                }else {

                }
            });
            // $( "h2" ).appendTo( $( ".container" ) );
            $(".loading_data").remove();
            $("#s2id_firm_type").show();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            console.log(response);
            if(response == 403){
                window.location.href = baseUrl + "403";
            }
            else if(response == 404){
                console.log('Company Type 404');
                alert("You can't add company, first add company type!");
                // window.location.href = baseUrl + "404";
            }
            else {
                window.location.href = baseUrl + "500";
            }
        }); 
    });

    $('.add_firm_form').click(function(e) {
        e.preventDefault();
        var token = localStorage.getItem('u_token');
        var firm_name = $('#firm_name').val();
        var firm_description = $('#firm_description').val();
        var firm_address = $('#firm_address').val();
        var firm_type = $('#firm_type').val();
        var lat =  $("#project_latitude").val();
        var long =  $("#project_longitude").val();
        var company_type = $("#company_type").val();
        jQuery.ajax({
            url: baseUrl + "firm-name/add",
            type: "POST",
            data:
            {
                "firm_name"     : firm_name,
                "firm_detail"   : firm_description,
                "firm_address"  : firm_address,
                "firm_type"     : firm_type,
                "project_long" :long,
                "project_lat":lat,
                "company_type":company_type
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
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-success">New firm added successfully!</div></div>';
            $("#alert_message").html(html);
            $(".first_button").hide();
            $(".another_button").show();
            setTimeout(function()
                {
                    $("#toast-container").fadeOut(1000);
                },5000)
            $("#toast-container").fadeOut(10000);
            $("#firm_name").removeAttr('value');
            $("#firm_description").removeAttr('value');
            $("#firm_address").removeAttr('value');
            $("#company_type").removeAttr('value');
            $("#firm_type").removeAttr('value');
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                console.log(responseText.data);
                $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
                }, 'fast')
                $("#alert_message").fadeIn(1000);
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-error"><ul>';
                if(responseText.data.firm_name != null){
                	html += '<li>Company name field is Invalid</li>';
                }
                if(responseText.data.firm_detail != null){
                    html += '<li>Company description field is Invalid</li>';
                }
                if(responseText.data.firm_address != null){
                    html += '<li>Company address field is Invalid</li>';
                }
                if(responseText.data.firm_type != null){
                    html += '<li>Company type field is Invalid</li>';
                }
                html += '</ul></div></div>';
                $("#alert_message").html(html);
                $("#toast-container").fadeOut(10000);

        })
    });