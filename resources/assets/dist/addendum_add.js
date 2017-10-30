$(document).ready(function() { 
    // Get login user profile data
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 3 ]; // projects
    console.log(project_id);
   
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
            $(".remove_file_drop").trigger("click");
            $("#alert_message").show();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New addendum added successfully!</div></div>';
            $("#alert_message").html(html);
            setTimeout(function()
            {
                $("#alert_message").hide();
            },3000)
            // location.reload();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            $('.loading-submit').hide();
            console.log("HTTP Request Failed");
            var responseText, html;
            responseText = JSON.parse(jqXHR.responseText);
            console.log(responseText.data);
            $("#alert_message").show();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
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
            setTimeout(function()
            {
                $("#alert_message").hide();
            },3000)
        })
    });