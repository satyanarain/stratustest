        @include('include/header')
        @include('include/sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">Add Document</h3>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="panel-body">
                                <div id="alert_message"></div>

                             
                    <div class="form-group col-md-12">
                        <!-- <form id="my-awesome-dropzone" action="{{ url('/document/uploadFiles') }}" class="dropzone"></form> -->
                    </div>
                                
                                <form role="form" role="form" method="POST" action="{{ url('/document/uploadFiles') }}" id="add_document_form" enctype="multipart/form-data">
                                    <div class="row">
                                    <div class="form-group col-md-6">
                                        <?php 
                                        if(isset($upload_path)){
                                            print_r($upload_path); 
                                        }
                                        ?>
                                        <label for="document_upload">Document Upload</label>
                                        <input type="file"  name="document_upload" id="document_upload">
                                    </div>
    
                                    <div class="form-group col-md-6">
                                        <label for="document_path">Document Path</label>
                                        <input type="text" class="form-control" name="document_path" id="document_path">
                                    </div>
                                    <!-- <div class="form-group col-md-6">
                                        <label for="document_meta">Document Meta</label>
                                        <input type="text" class="form-control" id="document_meta">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="project_id">Project ID</label>
                                        <input type="text" class="form-control" id="project_id">
                                    </div> -->
                                    <div class="form-group col-md-12">
                                        <button type="submit" class="btn btn-info sub-btn">Submit</button>
                                    </div>
                                    
                                    </div>
                                </form>

                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <!--body wrapper end-->
<!-- Placed js at the end of the document so the pages load faster -->
<!-- <script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script> -->
<script src="{{ url('http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/js/dropzone.js') }}"></script>

<script type="text/javascript">
// var form = document.querySelector('form');
// var request = XMLHttpRequest();

// // progress
// request.upload.addEventListener('progress', function(e)){
//     console.log(e.loaded/e.total*100 + 'percent');
// }, false);

// request.addEventListener('load', function(e)){
//     console.log(JSON.parse(e.target.responseText));
// }, false);

// form.addEventListener('e', function(e){
//     e.preventDefault();
//     var formdata = FormData(form);

//     request.open('open', 'submit');
//     request.send(formdata);
// }, false);


// function showRequest(formData, jqForm, options) { 
//     jQuery("#validation-errors").hide().empty();
//     jQuery("#output").css('display','none');
//     return true; 
// } 
// function showResponse(response, statusText, xhr, $form)  { 
//     if(response.success == false)
//     {
//         var arr = response.errors;
//         jQuery.each(arr, function(index, value)
//         {
//             if (value.length != 0)
//             {
//                 jQuery("#validation-errors").append('<div class="alert alert-error"><strong>'+ value +'</strong><div>');
//             }
//         });
//         jQuery("#validation-errors").show();
//     } else {
//          jQuery("#output").html("<img src='"+response.file+"' />");
//          jQuery("#output").css('display','block');
//     }
// }

// jQuery(document).ready(function() {
//     var options = { 
//                 beforeSubmit:  showRequest,
//         success:       showResponse,
//         dataType: 'json' 
//         }; 
//     jQuery('body').delegate('#image','change', function(){
//         jQuery('#kv_upload').ajaxForm(options).submit();        
//     }); 
// });

    // window.onload = function() {
    //     if ($('#my-awesome-dropzone').length) {
    //       var faizan = $("div#my-awesome-dropzone").dropzone({ url: "/dashboard/document/add" });
    //         console.log(faizan);
    //       // other code here
    //     }



    //     var baseUrl = "{{ url('/') }}";
    //     var token = localStorage.getItem('u_token');
    //     Dropzone.autoDiscover = false;
    //         var myDropzone = new Dropzone("div#my-awesome-dropzone", { 
    //         url: baseUrl+"/document/uploadFiles",

    //         params: {
    //             _token: token
    //         }
    //     });
    //         var image = $("#my-awesome-dropzone").val();
    //     console.log(image);
    //     console.log('faizan2');
    //     Dropzone.options.myAwesomeDropzone = {
    //         paramName: "file", // The name that will be used to transfer the file
    //         maxFilesize: 2, // MB
    //         addRemoveLinks: true,
    //         accept: function(file, done) {
    //             console.log('faizan3');
    //         },
    //     };
    // };
 </script>
<script src="{{ url('/resources/assets/dist/document_add.js') }}"></script>
@include('include/footer')