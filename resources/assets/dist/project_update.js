   $(document).ready(function() {

   // Get Project Type project_type_improvement table
    $("#project_type_dropdown").hide();
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 2 ]; // projects
    get_improvement_project();

       jQuery.ajax({
        url: baseUrl+"/agency-name",
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
        $("#project_lead_agency").append(
            '<option value="">Select Agency Name</option>'
        )
        jQuery.each(data.data, function( i, val ) {
            if(val.f_status == 'active'){
                $("#project_lead_agency").append(
                    '<option value="'+val.f_id+'">'+val.f_name+'</option>'
                )
            }else {

            }
        });
        $("#project_lead_agency").append(
            '<option style="font-weight:bold;">Add New Agency</option>'
        )
        // $( "h2" ).appendTo( $( ".container" ) );

        $(".loading_data").remove();
        $("#company_name").show();
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        console.log(response);
        if(response == 403){
            console.log('Company name 403');
            // window.location.href = baseUrl + "403";
        }
        else if(response == 404){
            $("#project_lead_agency").append(
                '<option value="">Select Agency Name</option>'
            )
            $("#project_lead_agency").append(
                '<option style="font-weight:bold;">Add New Agency</option>'
            )
            console.log('Company name 404');
            // window.location.href = baseUrl + "404";
        }
        else {
            window.location.href = baseUrl + "500";
        }
    });
       $('#project_lead_agency').change(function(){
        var company = $(this).val();
        if(company=="Add New Agency")
        {
            $('#add-agency').modal('show');
            $('#add-agency').on('shown.bs.modal',function(){
                google.maps.event.trigger(map, "resize");
                google.maps.event.trigger(map1, "resize");
              });
        }
    });
    setTimeout(function()
    {
    	// Get Single Project Detail
     	$("#update_project_form").hide();
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
		    // console.log(data);
		    var project_number = data.data.p_number;
		    $('#project_number').val(project_number);
		    var project_name = data.data.p_name;
		    $('#project_name').val(project_name);
		    var project_location = data.data.p_location;
		    $('#project_location').val(project_location);
		    var project_long = data.data.p_long;
		    $('#project_longitude').val(project_long);
		    var project_lat = data.data.p_lat;
		    $('#project_latitude').val(project_lat);
		    var project_type = data.data.p_type;
		    $('#project_type').val(project_type);
		    var project_description = data.data.p_description;
            $('#project_description').val(project_description);
            var project_wage_determination = data.data.p_wage_determination;
		    $('#project_wage_determination').val(project_wage_determination);

		    var status = data.data.p_status;
		    if(status == "active"){
		    	status = 'active';
		    }
		    else {
		    	status = "deactive";
		    }
		    $('#status').val(status);
		    $("#update_project_form").show();
		    $(".loading_data").remove();
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
		    console.log("HTTP Request Failed");
		    var response = jqXHR.responseJSON.code;
		    if(response == 403){
		    	window.location.href = baseUrl + "403";
		    	// console.log("403");
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



        // Selected Improvement Type
        jQuery.ajax({
        url: baseUrl +"/"+project_id+"/improvement-type",
            type: "GET",
            headers: {
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            // Foreach Loop
            jQuery.each(data.data, function( i, val ) {
                if(val.pt_status == 'active'){
                    $("#project_type_selected").append(
                        '<span class="label label-inverse" style="display: inline-block; font-size: 14px; margin: 0px 15px 0px 0px; padding: 5px 15px;">'+val.pt_name+'</span>'
                    )
                }
            });
            $(".loading_data").remove();
        })

	},1000)
});


    $('#update_project_form').click(function(e) {
      $('.loading-submit').show();
        e.preventDefault();
        // var project_number      = $('#project_number').val();

          var project_terms ='';
        var check_box = document.getElementById("project_terms").checked;
        if(check_box ==true){
            project_terms = "yes";
        }
        else if(check_box==false){
            project_terms = 'no';
        }

        var project_name        = $('#project_name').val();
        var project_location    = $('#project_location').val();
        var project_long        = $('#project_longitude').val();
        var project_lat         = $('#project_latitude').val();
        var project_type        = $('#project_type_dropdown').val();
        var project_description = $('#project_description').val();
        var project_wage_determination = $('#project_wage_determination').val();
        var project_status	 	= $('#status').val();
        var project_lead_agency         = $('#project_lead_agency').val();
        // var project_terms       = $("input[name='project_terms']:checked"). val();

        var token = localStorage.getItem('u_token');
        project_type = JSON.stringify(project_type);
        console.log(project_wage_determination);
       
        jQuery.ajax({
            url: baseUrl + "projects/"+project_id+"/update",
            type: "POST",
            data: {
                // "project_number" 		: project_number,
                "project_name" 			: project_name,
                "project_location" 		: project_location,
                "project_long" 			: project_long,
                "project_lat" 			: project_lat,
                "project_type" 			: project_type,
                "project_terms" : project_terms,
                // "project_description"    : project_description,
                "project_wage_determination" 	: project_wage_determination,
                "project_status" 		: project_status,
                "project_lead_agency" : project_lead_agency
            },
            headers: {
              "x-access-token": token
            },
            contentType: "application/x-www-form-urlencoded",
            cache: false
        })
            .done(function(data, textStatus, jqXHR)
            {
                $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
                }, 'fast')
                $("#alert_message").show();
                $('.loading-submit').hide();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">Project updated successfully!</div></div>';
                $("#alert_message").html(html);
                setTimeout(function()
                {
                    $("#alert_message").hide();
                },5000)

            })
        .fail(function(jqXHR, textStatus, errorThrown)
            {
                console.log(jqXHR);
                console.log("HTTP Request Failed");
                var responseText, html;
                responseText = JSON.parse(jqXHR.responseText);
                //console.log(responseText.data.currency_name);
                $('html, body').animate({
                    scrollTop: $(".page-head").offset().top
                }, 'fast')
                $("#alert_message").show();
                $('.loading-submit').hide();
                html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';

                  if(responseText.data.p_name != null){
                    html += '<li>The project name field is required.</li>';
                }
                if(responseText.data.p_type != null){
                    html += '<li>The project type field is required.</li>';
                }
                if(responseText.data.p_location != null){
                    html += '<li>The project location field is required.</li>';
                }
                if(responseText.data.p_status != null){
                    html += '<li>The project location field is required.</li>';
                }
                html += '</ul></div>';
                $("#alert_message").html(html);
                setTimeout(function(){
                    $("#alert_message").hide();
                },5000)
        })
    });



 function get_improvement_project()
 {
    $("#project_type_dropdown").empty();
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
             jQuery.each(data.data, function( i, val ) {
                 if(val.pt_status == 'active'){
                     $("#project_type_dropdown").append(
                         '<option value="'+val.pt_id+'">'+val.pt_name+'</option>'
                     )
                 }else {

                 }
             });
             // $( "h2" ).appendTo( $( ".container" ) );

             // $(".loading_data").remove();
             $("#project_type_dropdown").show();
         })
         .fail(function(jqXHR, textStatus, errorThrown) {
             console.log("HTTP Request Failed");
             var response = jqXHR.responseJSON.code;
             console.log(response);
             if(response == 403){
                 window.location.href = baseUrl + "403";
             }
             else if(response == 404){
                 console.log("4041");
                 // window.location.href = baseUrl + "404";
             }
             else {
                 window.location.href = baseUrl + "500";
             }
         });

 }






 $('#add_improvement_type').click(function(e)
 {
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
             $('.loading-submit').hide();
             html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">'+data.description+'</div></div>';
             $("#alert_message").html(html);
             $("#alert_message").fadeOut(3000);
             $("#imp_type").removeAttr('value');
             get_improvement_project();
             $('#diplay').hide();

         })
         .fail(function(jqXHR, textStatus, errorThrown) {
             console.log("HTTP Request Failed");
             var responseText, html;
             responseText = JSON.parse(jqXHR.responseText);
             // console.log(responseText.data.username);
             $("#alert_message").fadeIn(1000);
             $('.loading-submit').hide();
             html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
             if(responseText.data.improvement_type != null){
                 html += '<li>'+responseText.data.improvement_type+'</li>';
             }
             html += '</ul></div>';
             $("#alert_message").html(html);
             $("#alert_message").hide();
         })


     e.preventDefault();

 })




 $("#add_more").click(function(e){


     $('#diplay').show();
     e.preventDefault();
 });



