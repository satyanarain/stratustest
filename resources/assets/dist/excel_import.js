$(document).ready(function() { 
   var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    project_id  = url[ url.length - 2 ]; // project_id
    console.log(project_id);

    // Check Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray("standard_add", check_user_access );
    if(check_permission < 1){
        window.location.href = baseUrl + "403";
    }
    else {
        console.log('Yes Permission');
        $('.body-content .wrapper').show();
    }
    var token = localStorage.getItem('u_token');
    console.log(token);
    $( '#document_upload' )
        .submit( function(e)
        {
                $(".loading_data_file").show();
                var import_file = $("#import_file").val();//return false;
                var html;
                var is_error = false;
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
                if(import_file == ''){
                    html += '<li>Please upload excel file.</li>';
                    is_error = true;
                }
                html += '</ul></div>';
                if(is_error == true){
                    $("#alert_message").html(html);
                    $("#alert_message").show();
                    $('.loading_data_file').hide();
                    $('html, body').animate({
                        scrollTop: $(".page-head").offset().top
                    }, 'fast')
                    setTimeout(function(){
                        $("#alert_message").hide();
                        return false;
                    },5000)
                    return false;
                }
                else 
                {
                    $.ajax(
                    {
                url: baseUrl + "dashboard/"+project_id+"/importExcel",
                type: 'POST',
                data: new FormData( this ),
                processData: false,
                contentType: false,
                headers: {
                    "x-access-token": token
                },
                cache: false
              })
                    .done(function(data, textStatus, jqXHR) {
                        $(".loading_data_file").hide();
                        $("#import_file").removeAttr('value');
                        $("#alert_message").show();
                        html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Contract items imported successfully!</div></div>';
                        $("#alert_message").html(html);
                        setTimeout(function()
                        {
                            $("#alert_message").hide();
                        },6000)
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                
            });
                }
        e.preventDefault();
        });
}); 

    

   
        
            
            
  
  
       
    
    
    