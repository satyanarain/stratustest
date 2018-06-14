$(document).ready(function() {
    // Get login user profile data
    $("#view_users_table_wrapper").hide();
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 2 ]; // projects
    console.log(project_id);

    // Check View All Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray( "payment_quantity_verification_view_all", check_user_access );
    console.log(check_permission);
    if(check_permission < 1){
        window.location.href = baseUrl + "403";
    }
    else {
        console.log('Yes Permission');
        $('.body-content .wrapper').show();
    }


    jQuery.ajax({
        url: baseUrl+project_id+"/quantity-verification-report",
        type: "GET",
        headers: {
            "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
        .done(function(data, textStatus, jqXHR) {
            // console.log(data.data);

            window.project_name = data.data[0].p_name;
            $('#project_name_title').text("Project: " + window.project_name);
            var specific_project_name = 'Payment Quantity Verification Monthly Summary for Project: ' + window.project_name;
            console.log(specific_project_name);
            $('#view_users_table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    // {
                    //     extend: 'copyHtml5',
                    //     exportOptions: {
                    //         columns: [ 0, 1 ]
                    //     },
                    //     message: specific_project_name,
                    // },
                    {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1 ]
                        },
                        message: specific_project_name,
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1 ]
                        },
                        message: specific_project_name,
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1 ]
                        },
                        message: specific_project_name,
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 0, 1 ]
                        },
                        message: specific_project_name,
                    }
                ]
            });

            $("#view_users_table_wrapper tbody tr").hide();
            // Foreach Loop
            var count = 1;
            jQuery.each( data.data, function( i, val ) {
                var t = $('#view_users_table').DataTable();
                t.row.add([
                    count, //val.ppq_id,
                    val.ppq_month_name,
                    val.approval_status,
                    '<a href="'+baseUrl+'dashboard/'+val.ppq_project_id+'/payment_quantity_verification/'+val.ppq_id+'" class="btn btn-info btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" style="margin-right:5px;"><i class="fa fa-search"></i></a>'
                    +'<a href="'+baseUrl+'dashboard/'+val.ppq_project_id+'/payment_quantity_verification/'+val.ppq_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>'
                ]).draw( false );
                count++;
            });
            // $( "h2" ).appendTo( $( ".container" ) );

            $("#view_users_table_wrapper").show();
            $(".loading_data").hide();

        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var response = jqXHR.responseJSON.code;
            console.log(response);
            if(response == 403){
                // window.location.href = baseUrl + "403";
            }
            else if(response == 404){
                // window.location.href = baseUrl + "404";
                $("#view_users_table_wrapper").show();
                $(".loading_data").hide();
            }
            else {
                window.location.href = baseUrl + "500";
            }
        });

});