    $('#add_company_type_form').submit(function(e) {
        e.preventDefault();
        var company_type = $('#company_type').val();
       var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "company-type/add",
            type: "POST",
            data: {
                "company_type_name" : company_type
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
            .done(function(data, textStatus, jqXHR) {
            console.log(data.description);
                $("#alert_message").show();
                $("#company_type").removeAttr('value');
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New company type added successfully!</div></div>';
                  setTimeout(function()
                    {
                    $("#alert_message").fadeOut(1000);
                    },5000)
                $("#alert_message").html(html);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                // console.log(responseText.data.username);
                $("#alert_message").show();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                if(responseText.data.company_type_name != null){
                	html += '<li>'+responseText.data.company_type_name+'</li>';
                }
                html += '</ul></div>';
                $("#alert_message").html(html);
                setTimeout(function(){
                    $("#alert_message").fadeOut(1000);
                },5000)

        })
    });