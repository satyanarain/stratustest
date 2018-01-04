  	$(document).ready(function() { 
      // Check User Email Verification or Not
      var unverified  = localStorage.getItem('u_status')
      var role = localStorage.getItem('u_role');
	  var token = localStorage.getItem('u_token');
	  var user_id = localStorage.getItem('u_id');

	  if(role == 'admin' || role == 'owner'){
	  	$('.hide_permission').show();
	  }

      if(unverified != 0){
       $("#email_unverified_error").hide();
      }
      else{
        $("#email_unverified_error").show();
      }

      	jQuery.ajax({
		url: baseUrl + "user_projects/"+user_id,
		    type: "GET",
		    headers: {
		      "x-access-token": token
		    },
		    contentType: "application/json",
		    cache: false
		})
		.done(function(data, textStatus, jqXHR) {
			$('#view_users_table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    // {
                    //     extend: 'copyHtml5',
                    //     message: specific_project_name,
                    // },
                    {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2 ]
                        },
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2 ]
                        },
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2 ]
                        },
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 0, 1, 2 ]
                        },
                    }
                ]
            });
			$("#view_users_table_wrapper tbody tr").hide();
			var count = 1;
			jQuery.each(data.data, function( i, val ) {
				var status = val.p_status;
				if(status == 'active'){
		    	status = '<span class="label label-success">Activated</span>';
			    }
			    else {
			    	status = '<span class="label label-danger">Deactivated</span>';
			    }
			    

			    var t = $('#view_users_table').DataTable();
			    if(role == 'admin'){
			    	t.row.add( [
			           	count, // val.p_id,
			           	'<a href="'+baseUrl+'dashboard/'+val.p_id+'/project">'+val.p_number+'<br/>'+val.p_name+'</a>',
			            val.user_name,
		           		status,
		           		'<a href="'+baseUrl+'dashboard/projects/'+val.p_id+'/update" class="btn btn-primary btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>'
			       	]).draw( false );	
			       	count++;
			    }
			    else {
			    	t.row.add( [
			           	count, // val.p_id,
			           	'<a href="'+baseUrl+'dashboard/'+val.p_id+'/project">'+val.p_number+'<br/>'+val.p_name+'</a>',
			           	status,
		           		'<a href="'+baseUrl+'dashboard/projects/'+val.p_id+'/update" class="btn btn-primary btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>'
			       	]).draw( false );
			       	count++;
			    }
				
			});
			$("#view_users_table").show();
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
		    	// window.location.href = baseUrl + "404";
		    	$("#view_users_table").show();
		    	$(".loading_data").hide();
		    }
		    else {
		    	window.location.href = baseUrl + "500";
		    }
		});
  	});

  	

  	 
  
	// Send User Email to verification
	$("#email_unverified_error a").click(function(event) {
	    event.preventDefault();
	      var id = localStorage.getItem('u_id');
	      jQuery.ajax({
	      url: baseUrl + "users/send-email-verification",
	            type: "POST",
	            data: {
	                "id": id
	            },
	            headers: {
	              "Content-Type": "application/x-www-form-urlencoded",
	              "x-access-token": token
	            },
	            contentType: "application/x-www-form-urlencoded",
	            cache: false
	        })
                .done(function(data, textStatus, jqXHR) {
	            console.log(data.data);
	              html = '<div class="alert alert-block alert-success fade in"><button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button><strong>Verification email sent! </strong>kindly check your email.</a></div>';
	            $("#email_unverified_error .alert").hide();
	            $("#email_unverified_error").html(html);
	        })
	        .fail(function(jqXHR, textStatus, errorThrown) {
	            console.log("HTTP Request Failed");
	            console.log(jqXHR.responseJSON);
	            html = '<div class="alert alert-block alert-danger fade in"><button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button><strong>Error! </strong>Please try again after a min.</a></div>';
	            $("#email_unverified_error .alert").hide();
	            $("#email_unverified_error").html(html);
	            // window.location.href = baseUrl + "404";
	        }) 
	});