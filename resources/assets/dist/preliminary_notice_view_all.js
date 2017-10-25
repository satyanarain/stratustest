$(document).ready(function() {
    // Get login user profile data
    $("#view_users_table_wrapper").hide();
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
    project_id = url[ url.length - 2 ]; // projects
    // console.log(project_id);
    type = url[ url.length - 1 ]; // projects
    // console.log(type);

    // Check View All Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray( "preliminary_view_all", check_user_access );
    console.log(check_permission);
    if(check_permission < 1){
        window.location.href = baseUrl + "403";
    }
    else {
        console.log('Yes Permission');
    }

    jQuery.ajax({
        url: baseUrl+project_id+"/preliminary-notices",
        type: "GET",
        headers: {
            "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
        .done(function(data, textStatus, jqXHR) {
            console.log(data.data);

            window.project_name = data.data[0].p_name;
            $('#project_name_title').text("Project: " + window.project_name);
            var specific_project_name = 'Preliminary Notice for Project: ' + window.project_name;
            console.log(specific_project_name);
            $('#view_users_table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    // {
                    //     extend: 'copyHtml5',
                    //     exportOptions: {
                    //         columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    //     },
                    //     message: specific_project_name,
                    // },
                    {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                        },
                        message: specific_project_name,
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                        },
                        message: specific_project_name,
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                        },
                        message: specific_project_name,
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                        },
                        message: specific_project_name,
                    }
                ]
            });

            $("#view_users_table_wrapper tbody tr").hide();
            // Foreach Loop
            var count = 1;
            jQuery.each( data.data, function( i, val ) {
                // Check Update Permission
                var check_permission = jQuery.inArray("preliminary_update", check_user_access );
                console.log(check_permission);
                if(check_permission < 1){
                    var update_permission = '';
                }
                else {
                    var update_permission = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/preliminary_notice/'+val.ppn_id+'/update" class="btn btn-primary btn-xs tooltips hide_update_permission" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
                }

                var pnp_path = val.doc_path;
                var pnp_path_value;
                if(pnp_path == null){
                    pnp_path_value = '-';
                }
                else {
                    if(val.status == 'active'){
                        pnp_path_value = '<a href="'+baseUrl+val.doc_path+'" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a>';
                    }
                    else {
                        pnp_path_value = '<td><a href="'+baseUrl+'404" target="_blank"><img src="'+baseUrl+'resources/assets/img/pdf_icon.png" width="40"/></a></td>';
                    }
                }

                var status = val.status;
                if(status == 'active'){
                    status = '<span class="label label-success">Active</span>';
                    var single_view = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/preliminary_notice/'+val.ppn_id+'" class="btn btn-info btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" style="margin-right:5px;"><i class="fa fa-search"></i></a>';
                }
                else {
                    status = '<span class="label label-danger">Inactive</span>';
                    var single_view = '<a href="'+baseUrl+'404" class="btn btn-info btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" style="margin-right:5px;"><i class="fa fa-search"></i></a>';
                }
                var notice_status = val.notice_status;
                if(notice_status == 'approve'){
                    notice_status = '<span class="label label-success">Approve</span>';
                    var single_view = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/lien_release/'+val.ppn_id+'" class="btn btn-info btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" style="margin-right:5px;"><i class="fa fa-search"></i></a>';
                }
                else {
                    notice_status = '<span class="label label-danger">Disapprove</span>';
                    var single_view = '<a href="'+baseUrl+'dashboard/'+val.p_id+'/lien_release/'+val.ppn_id+'" class="btn btn-info btn-xs tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" style="margin-right:5px;"><i class="fa fa-search"></i></a>';
                }
                var t = $('#view_users_table').DataTable();
                t.row.add([
                    count, // val.ppn_id,
                    val.preliminary_notice_firm,
                    val.undercontractwith,
                    val.currency_symbol +" "+ReplaceNumberWithCommas(val.amount),
                    val.direct_contractor_name,
                    val.date_of_notice_signed,
                    val.post_marked_date,
                    pnp_path_value,
                    status,
                    notice_status,
                    update_permission+single_view
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
                window.location.href = baseUrl + "403";
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

        setTimeout(function()
        {
            // Check Add Permission
            var check_permission = jQuery.inArray( "preliminary_add", check_user_access );
            console.log(check_permission);
            if(check_permission < 1){
                $('.hide_add_permission').remove();
            }
            else {
                $('.hide_add_permission').show();
            }
        },2000)

});