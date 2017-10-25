    $('#add_improvement_form').submit(function(e) {
        e.preventDefault();
        var imp_type = $('#imp_type').val();
	    var token = localStorage.getItem('u_token');
        jQuery.ajax({
            url: baseUrl + "improvement-type/add",
            type: "POST",
            data: {
                "improvement_type" : imp_type,
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
            .done(function(data, textStatus, jqXHR) {
            console.log(data.description);
            // html = '<div class="alert alert-block alert-success fade in">'+data.description+'</div>';
            // $("#alert_message").html(html);
            $("#alert_message").fadeIn(1000);
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New improvement type added successfully!</div></div>';
            $("#alert_message").html(html);
            $("#alert_message").fadeOut(5000);
            $("#imp_type").removeAttr('value');
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                // console.log(responseText.data.username);
                $("#alert_message").fadeIn(1000);
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                if(responseText.data.improvement_type != null){
                	html += '<li>'+responseText.data.improvement_type+'</li>';
                }
                html += '</ul></div>';
                $("#alert_message").html(html);
                $("#alert_message").fadeOut(5000);
        })
    });