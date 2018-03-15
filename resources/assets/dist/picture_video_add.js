$(document).ready(function() {
    // Get login user profile data
    $("#company_name").hide();
    $('#upload_doc_id').removeAttr('value');
    var role = localStorage.getItem('u_role');
    var token = localStorage.getItem('u_token');
    var url = $(location).attr('href').split( '/' );
        project_id = url[ url.length - 2 ]; // projects

    // Check View All Permission
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    var check_permission = jQuery.inArray( "project_picture_video_view_all", check_user_access );
    console.log(check_permission);
    if(check_permission < 1){
        window.location.href = baseUrl + "403";
    }
    else {
        console.log('Yes Permission');
        $('.body-content .wrapper').show();
    }

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
        var project_name = data.data.p_name;
        $('#project_name_title').text("Project: " + project_name);
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("HTTP Request Failed");
        var response = jqXHR.responseJSON.code;
        if(response == 403){
            // window.location.href = baseUrl + "403";
            console.log("403");
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

    // jQuery.ajax({
    //     url: baseUrl + "firm-name",
    //     type: "GET",
    //     headers: {
    //         "x-access-token": token
    //     },
    //     contentType: "application/json",
    //     cache: false
    // })
    //     .done(function(data, textStatus, jqXHR) {
    //         // console.log(data.data);
    //         // Foreach Loop
    //         jQuery.each(data.data, function( i, val ) {
    //             if(val.f_status == 'active'){
    //                 $("#company_name").append(
    //                     '<option value="'+val.f_id+'">'+val.f_name+'</option>'
    //                 )
    //             }else {

    //             }
    //         });
    //         // $( "h2" ).appendTo( $( ".container" ) );

    //         $(".loading_data").remove();
    //         $("#company_name").show();
    //     })
    //     .fail(function(jqXHR, textStatus, errorThrown) {
    //         console.log("HTTP Request Failed");
    //         var response = jqXHR.responseJSON.code;
    //         console.log(response);
    //         if(response == 403){
    //             window.location.href = baseUrl + "403";
    //         }
    //         else if(response == 404){
    //             window.location.href = baseUrl + "404";
    //         }
    //         else {
    //             window.location.href = baseUrl + "500";
    //         }
    //     });

        // Get Selected Agency
        jQuery.ajax({
            url: baseUrl + "/"+project_id+"/default_contractor",
            type: "GET",
            headers: {
              "x-access-token": token
            },
            contentType: "application/json",
            cache: false
        })
        .done(function(data, textStatus, jqXHR) {
            // console.log(data.data);
            window.agency_id = data.data[0].pna_contactor_name;
            // console.log(agency_id);
            $("#company_name").val(parseInt(agency_id));

            // Select Company Detail
            jQuery.ajax({
            url: baseUrl + "firm-name/"+agency_id,
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
                var f_name = data.data.f_name;
                $('#contractor_name').text(f_name);
            })
        })

        // Select Project name
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
            var project_name = data.data.p_name;
            $('#project_name').text(project_name);
            $('#project_name1').text(project_name);
        })

        get_all_picture_count();
        get_all_video_count(); 
        get_all_picture();

        setTimeout(function()
        {
            // Check Add Permission
            var check_permission = jQuery.inArray( "project_picture_video_add", check_user_access );
            console.log(check_permission);
            if(check_permission < 1){
                $('.hide_add_permission').remove();
            }
            else {
                $('.hide_add_permission').show();
            }
        },1000)

    // $('#show_only_video').click(function(e) {
    //     e.preventDefault();
    //     alert('faizan');
    //     $('#add_gallery_grid tr').hide();
    //     $('#add_gallery_grid .image').hide();
    //     $('#add_gallery_grid .video').show();
    // });

    // $('#show_only_images').click(function(e) {
    //     e.preventDefault();
    //     alert('show images');
    //     $('#add_gallery_grid tr').hide();
    //     $('#add_gallery_grid .video').hide();
    //     $('#add_gallery_grid .image').show();
    // });

    // $('#show_all').click(function(e) {
    //     e.preventDefault();
    //     alert('show all');
    //     $('#add_gallery_grid tr').show();
    //     $('#add_gallery_grid .image').show();
    //     $('#add_gallery_grid tr td .video').show();
    // });

    setTimeout(function(){
        $('body').delegate('.user_suspend', 'click', function (e) {
            e.preventDefault();
            var token  = localStorage.getItem('u_token');
            var id = $(this).attr("id");
            console.log(id);
            var r = confirm("Are you sure to delete this Picture/Video?");
            if (r == true) {
                jQuery.ajax({
                url: baseUrl + "picture/"+id+"/update",
                    type: "GET",
                    headers: {
                      "x-access-token": token
                    },
                    contentType: "application/json",
                    cache: false
                })
                .done(function(data, textStatus, jqXHR) {
                    console.log(data);
                    $('#'+id).closest("tr").hide();

                    get_all_picture_count();
                    get_all_video_count(); 
                    // window.location.reload();   
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.log("HTTP Request Failed");
                    // var response = jqXHR.responseJSON.code;
                    console.log(jqXHR.responseJSON);
                }); 
            } else {
                return false;
            }
        });

        // $("body .user_suspend").on('click', function(e){
        //     e.preventDefault();
        //     var token  = localStorage.getItem('u_token');
        //     var id = $(this).attr("id");
        //     console.log(id);
        //     alert(id);
        //     var r = confirm("Are you sure to delete this Picture/Video?");
        //     if (r == true) {
        //         jQuery.ajax({
        //         url: baseUrl + "picture/"+id+"/update",
        //             type: "GET",
        //             headers: {
        //               "x-access-token": token
        //             },
        //             contentType: "application/json",
        //             cache: false
        //         })
        //         .done(function(data, textStatus, jqXHR) {
        //             console.log(data);
        //             $('#'+id).closest("tr").hide();
        //             // window.location.reload();   
        //         })
        //         .fail(function(jqXHR, textStatus, errorThrown) {
        //             console.log("HTTP Request Failed");
        //             // var response = jqXHR.responseJSON.code;
        //             console.log(jqXHR.responseJSON);
        //         }); 
        //     } else {
        //         return false;
        //     }
        // });
    },2000) 

});


$('#upload_image_submit').click(function(e) {
  $('.loading-submit').show();
    e.preventDefault();
    var taken_by                = $('#taken_by').val();
    var description             = $('#description').val();
    var upload_doc_id           = $('#upload_doc_id').val();
    var project_id              = $('#upload_project_id').val();
    var ppv_taken_on            = $('#ppv_taken_on').val();
    var pic_type                = localStorage.getItem('upload_type');
    var token                   = localStorage.getItem('u_token');
    jQuery.ajax({
        url: baseUrl + "picture/add",
        type: "POST",
        data: {
            "taken_by"              : taken_by,
            "pic_type"              : pic_type,
            "doc_id"                : upload_doc_id,
            "description"           : description,
            "project_id"            : project_id,
            "ppv_taken_on"          : ppv_taken_on,
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
            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-success">New photo / video added successfully!</div></div>';
            $("#alert_message").html(html);
            $("#upload_doc_id").removeAttr('value');
            $("#taken_by").removeAttr('value');
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1; //January is 0!
            var yyyy = today.getFullYear();
            $("#ppv_taken_on").val(yyyy+'-'+mm+'-'+dd);
            $("#description").removeAttr('value');
            $(".remove_file_drop").trigger("click");
            setTimeout(function()
            {
                $("#alert_message").hide();
            },5000)
            $('#add_gallery_grid tbody tr').remove();
            get_all_picture_count();
            get_all_video_count(); 
            get_all_picture();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("HTTP Request Failed");
            var responseText, html;
            responseText = JSON.parse(jqXHR.responseText);
            // console.log(responseText.data);

            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast')
            $("#alert_message").show();
            $('.loading-submit').hide();
            html = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert" style="margin-top:50px;"><div class="toast toast-error"><ul>';
            if(responseText.data.doc_id != null){
                html += '<li>Document is invalid.</li>';
            }
            if(responseText.data.taken_by != null){
                html += '<li>Taken by field is invalid.</li>';
            }
            if(responseText.data.description != null){
                html += '<li>Description field is invalid.</li>';
            }
            if(responseText.data.ppv_taken_on != null){
                html += '<li>Taken on field is invalid.</li>';
            }
            if(responseText.data.project_id != null){
                html += '<li>Project id field is invalid.</li>';
            }
            html += '</ul></div>';
            $("#alert_message").html(html);
            setTimeout(function(){
                $("#alert_message").hide();
            },5000)
        })
});
//$("#add_gallery_grid").dataTable().fnClearTable();
$("#ppv_sort_by").change(function(){
    var table = $("#add_gallery_grid").DataTable();
    table.clear().destroy();
    table1 = $('#add_gallery_grid').DataTable({
                    searching: true,
                    paging: true,
                    bLengthChange: true,
                    ordering: false
                });
            var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
            jQuery.ajax({
    url: baseUrl +project_id+"/picture",
        type: "GET",
        data:{
               "ppv_sort_by"   :$("#ppv_sort_by").val(),
                    },
        headers: {
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
            .done(function(data, textStatus, jqXHR) {
            var doc_type = '';
            jQuery.each(data.data, function( i, val ) {
                //alert('ddd');
                var check_permission = jQuery.inArray( "project_picture_video_remove", check_user_access );
                if(check_permission < 1){
                    var remove_permission = '';
                }
                else {
                    var remove_permission = '<a href="" id="'+val.ppv_id+'" class="btn btn-danger btn-xs tooltips user_suspend hide_remove_permission" data-placement="top" data-toggle="tooltip" data-original-title="Suspend" style="position: absolute;right: 0px;top: 0px;"><i class="fa fa-times"></i> Remove </a>';
                }

                doc_type = val.ppv_type;
                if(doc_type == 'image/png' || doc_type == 'image/jpg' || doc_type == 'image/jpeg'){
                  var doc_type_pic = '<img src="'+baseUrl+val.doc_path+'" alt="'+val.ppv_name+'"/>';
                  var doc_type_type = 'image';
                }
                else {
                  var doc_type_pic = '<img src="'+baseUrl+'resources/assets/img/video_icon.jpg" alt="'+val.ppv_name+'"/>';
                  var doc_type_type = 'video';
                }

               //alert(val.ppv_taken_on);
               
               
               
               
               
                table1.row.add([
                   '<span class="'+doc_type_type+'" style="position: relative;display: block;">'+remove_permission+'<a href="'+baseUrl+'dashboard/'+project_id+'/picture_video/'+val.ppv_id+'">'+doc_type_pic+'</a><p><strong class="pic-name">'+val.ppv_name+'</strong></p><p><strong class="takenby">Taken By: </strong>'+val.ppv_taken_by+'</p><p><strong class="takenby">Taken On: </strong>'+val.ppv_taken_on+'</p><p class="picture-video-add"><strong class="desc" style="float:left;">Description: </strong><span class=desc-text>'+val.ppv_description+'</span></p></span>'
                ]).draw();
                //alert('<span class="'+doc_type_type+'" style="position: relative;display: block;">'+remove_permission+'<a href="'+baseUrl+'dashboard/'+project_id+'/picture_video/'+val.ppv_id+'">'+doc_type_pic+'</a><p><strong class="pic-name">'+val.ppv_name+'</strong></p><p><strong class="takenby">Taken By: </strong>'+val.ppv_taken_by+'</p><p><strong class="takenby">Taken On: </strong>'+val.ppv_taken_on+'</p><p class="picture-video-add"><strong class="desc" style="float:left;">Description: </strong><span class=desc-text>'+val.ppv_description+'</span></p></span>');
                $("#view_users_table_wrapper").show();
                $(".loading_data").hide();
            });
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
});


function get_all_picture(){
    // console.log(project_id);
    var check_user_access = JSON.parse(localStorage.getItem("access_permission"));
    jQuery.ajax({
    url: baseUrl +project_id+"/picture",
        type: "GET",
        headers: {
          "x-access-token": token
        },
        contentType: "application/json",
        cache: false
    })
    .done(function(data, textStatus, jqXHR) {
        var table = $('#add_gallery_grid').DataTable();
        table.clear().draw();
        // console.log(data.data);
        // Foreach Loop
        var doc_type = '';
        jQuery.each(data.data, function( i, val ) {

            // Check Remove Permission
            var check_permission = jQuery.inArray( "project_picture_video_remove", check_user_access );
            console.log(check_permission);
            if(check_permission < 1){
                var remove_permission = '';
            }
            else {
                var remove_permission = '<a href="" id="'+val.ppv_id+'" class="btn btn-danger btn-xs tooltips user_suspend hide_remove_permission" data-placement="top" data-toggle="tooltip" data-original-title="Suspend" style="position: absolute;right: 0px;top: 0px;"><i class="fa fa-times"></i> Remove </a>';
            }

            doc_type = val.ppv_type;
            if(doc_type == 'image/png' || doc_type == 'image/jpg' || doc_type == 'image/jpeg'){
              var doc_type_pic = '<img src="'+baseUrl+val.doc_path+'" alt="'+val.ppv_name+'"/>';
              var doc_type_type = 'image';
            }
            else {
              var doc_type_pic = '<img src="'+baseUrl+'resources/assets/img/video_icon.jpg" alt="'+val.ppv_name+'"/>';
              var doc_type_type = 'video';
            }

            // $('#add_gallery_grid').append(
            //     '<li>'+
            //         '<a href="'+baseUrl+'dashboard/'+project_id+'/picture_video/'+val.ppv_id+'">'+
            //             doc_type_pic+
            //         '</a>'+
            //         '<p><strong class="pic-name">'+val.ppv_name+'</strong></p>'+
            //         '<p><strong class="takenby">Taken By: </strong>'+val.ppv_taken_by+'</p>'+
            //         '<p><strong class="desc">Description: </strong>'+val.ppv_description+'</p>'+
            //     '</li>'
            // );

            var t = $('#add_gallery_grid').DataTable();
            t.row.add([
               '<span class="'+doc_type_type+'" style="position: relative;display: block;">'+remove_permission+'<a href="'+baseUrl+'dashboard/'+project_id+'/picture_video/'+val.ppv_id+'">'+doc_type_pic+'</a><p><strong class="pic-name">'+val.ppv_name+'</strong></p><p><strong class="takenby">Taken By: </strong>'+val.ppv_taken_by+'</p><p><strong class="takenby">Taken On: </strong>'+val.ppv_taken_on+'</p><p class="picture-video-add"><strong class="desc" style="float:left;">Description: </strong><span class=desc-text>'+val.ppv_description+'</span></p></span>'
            ]).draw( false );
            $("#view_users_table_wrapper").show();
            $(".loading_data").hide();
        });
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
}


function get_all_picture_count(){
    // No of Picture Count
    jQuery.ajax({
    url: baseUrl + "/"+project_id+"/picture/picture_count",
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
        var no_of_picture = data.data;
        $('#no_of_picture').text(no_of_picture);
    })
}


function get_all_video_count(){
   // No of Video Count
    jQuery.ajax({
    url: baseUrl + "/"+project_id+"/picture/video_count",
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
        var no_of_video = data.data;
        $('#no_of_video').text(no_of_video);
    })
}
