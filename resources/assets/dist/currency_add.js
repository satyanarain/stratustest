    $('#add_currency_form').submit(function(e) {
        e.preventDefault();
        var currency_name = $('#currency_name').val();
        var currency_symbol = $('#currency_symbol').val();
	    var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "currency/add",
            type: "POST",
            data: {
                "currency_name" : currency_name,
                "currency_symbol" : currency_symbol
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
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New currency added successfully!</div></div>';
                $("#alert_message").html(html);
                $("#currency_name").removeAttr('value');
                $("#currency_symbol").removeAttr('value');
                setTimeout(function()
                {
                    $("#alert_message").fadeOut(1000);
                },5000)

            // window.location.href = baseUrl + "dashboard/currency";
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                // console.log(responseText.data.currency_name);
                $("#alert_message").show();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                if(responseText.data.currency_name != null){
                	html += '<li>'+responseText.data.currency_name+'</li>';
                }
                if(responseText.data.currency_symbol != null){
                    html += '<li>'+responseText.data.currency_symbol+'</li>';
                }
                html += '</ul></div></div>';
                $("#alert_message").html(html);
                setTimeout(function(){
                    $("#alert_message").hide();
                },5000)
        })
    });