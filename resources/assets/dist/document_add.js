// window.onload = function() {
//   //Dropzone.js Options - Upload an image via AJAX.
//   Dropzone.options.myDropzone = {
//     uploadMultiple: false,
//     // previewTemplate: '',
//     addRemoveLinks: false,
//     // maxFiles: 1,
//     dictDefaultMessage: '',
//     init: function() {
//       this.on("addedfile", function(file) {
//         // console.log('addedfile...');
//       });
//       this.on("thumbnail", function(file, dataUrl) {
//         // console.log('thumbnail...');
//         $('.dz-image-preview').hide();
//         $('.dz-file-preview').hide();
//       });
//       this.on("success", function(file, res) {
        
//         console.log('upload success...');
//         $('#img-thumb').attr('src', res.path);
//         $('input[name="pic_url"]').val(res.path);
//       });
//     }
//   };
//   var myDropzone = new Dropzone("#my-dropzone");
 
//   $('#upload-submit').on('click', function(e) {
//     e.preventDefault();
//     //trigger file upload select
//     $("#my-dropzone").trigger('click');
//   });
 
// };
 
// //we want to manually init the dropzone.
// Dropzone.autoDiscover = false;




    // $('#add_document_form').submit(function(e) {
    //     e.preventDefault();
    //         // var file_data = $('#document_upload').prop('files')[0];   
    //         var formdata = new FormData();                  
    //         // form_data.append('file', file_data);
    //         // console.log(file_data); 
    //         // console.log(form_data); 
    //     // var image = $('#document_upload').val();
    //     // var document_path = $('#document_path').val();
    //     // var document_meta = $('#document_meta').val();
    //     // var project_id    = $('#project_id').val();

    //     var inputs = $("input[type=file]"),
    //     files = [];
    //     // jquery or javascript have a slightly different notation
    //     // it's either accessing functions () or arrays [] depending on which object you're holding at the moment
    //     for (var i = 0; i < inputs.length; i++){
    //         files.push(inputs.eq(i).prop("files")[0]);
    //         console.log(files); 
    //         //files.push(inputs[i].files[0]);
    //         //filename = inputs[i].files[0].name;
    //         //filesize = inputs[i].files[0].size;
    //     }

    //     // if (formdata) {
    //     //     // you can use the array notation of your input's `name` attribute here
    //     //     var form_data = formdata.append("document_upload", files);
    //     //     console.log(form_data); 
    //     // }

	   //  var token = localStorage.getItem('u_token');
    //     jQuery.ajax({
    //         proccessData: false,
    //         contentType: false,
    //         url: baseUrl + "document/uploadFiles",
    //         type: "POST",
    //         data: {
    //             // "document_path" : document_path,
    //             // "document_meta" : document_meta,
    //             // "project_id"    : project_id
    //             "image"    : files
    //         },
    //         headers: {
    //           "x-access-token": token
    //         },
    //         cache: false
    //     })
    //     .done(function(data, textStatus, jqXHR) {
    //         console.log(data.description);
    //         html = '<div class="alert alert-block alert-success fade in">'+data.description+'</div>';
    //         $("#alert_message").html(html);
    //     })
    //     .fail(function(jqXHR, textStatus, errorThrown) {
    //             console.log("HTTP Request Failed");
    //             var responseText, html;
    //             responseText = JSON.parse(jqXHR.responseText);
    //             // console.log(responseText.data.document_path);
    //             html = '<div class="alert alert-block alert-danger fade in"><ul>';
    //             // if(responseText.data.doc_path != null){
    //             // 	html += '<li>'+responseText.data.doc_path+'</li>';
    //             // }
    //             // if(responseText.data.doc_meta != null){
    //             //     html += '<li>'+responseText.data.doc_meta+'</li>';
    //             // }
    //             // if(responseText.data.project_id != null){
    //             //     html += '<li>'+responseText.data.project_id+'</li>';
    //             // }
    //             html += '</ul></div>';
    //             $("#alert_message").html(html);
    //     })
    // });