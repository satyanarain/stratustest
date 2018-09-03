        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
    
          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">Notice of Award</h3>
                <?php $project_id = Request::segment(2); ?>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="panel-body">
                                <div class="upload_doc_panel_performance">    
                                    <header class="panel-heading">Would you like to create a notice of award or has one already been created?</header>
                                    <div class="col-md-6 nopadleft">
                                        <div class="form-group">
                                            <div class="col-lg-10">
                                                <label class="checkbox-custom check-success">
                                                    <input type="checkbox" value=" " id="check1">
                                                    <label for="check1">Create Notice of Award</label>
                                                </label>
                                                <label class="checkbox-custom check-success">
                                                    <input type="checkbox" value=" " id="check2">
                                                    <label for="check2">Already have Notice of Award</label>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="name_of_report" style="padding-top: 15px;">Contractor’s Name</label>
                                            <input type="text" class="form-control" id="performance_bond_amount">
                                        </div>
                                        
                                        <div class="form-group col-md-12">
                                            <label>Bid/Contract Amount</label>
                                            <input type="text" class="form-control" id="performance_bond_amount">
                                        </div>
                                        
                                        <div class="form-group col-md-12">
                                            <label>Notice of Award Date</label>
                                            <input type="text" class="form-control" id="performance_bond_number">
                                        </div>

                                        
                                    </div>

                                    <div class="col-md-6">
                                        <section class="panel upload_doc_panel_payment" id="upload_payment">
                                            <div class="panel-body form-group">
                                            <label>Upload your PDF file</label>
                                                <form id="my-awesome-dropzone1" action="{{ url('/document/uploadFiles') }}" class="dropzone">
                                                    <input type="hidden" class="certificate_work_compensation" name="document_path" value="/uploads/bond/">
                                                </form> 
                                                <input type="hidden" name="standard_doc_id" id="upload_doc_id_work" value="">
                                            </div>
                                        </section>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <button class="btn btn-info sub-btn">Create Notice of Award</button>
                                    </div>
                                    
                                </div><!-- upload_doc_panel_performance close -->
                            </div>
                        </section>
                    </div><!-- Col 6 Close -->
                </div>
            </div>

                   
        </div>
            <!--body wrapper end-->
<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>

<script type="text/javascript">

$("input[name='upload']").click(function(){
        // alert($('input:radio[name=upload]:checked').val());
    if($('input:radio[name=upload]:checked').val() == "no"){
        $('.upload_doc_panel').css("display", "none");
        $('#upload_doc_id').removeAttr('value');
        $("#upload_warning").fadeIn(1000).fadeOut(7000);
    }
    else {
        $('.upload_doc_panel').css("display", "block");   
    }
});

$("input[name='upload_work']").click(function(){
    if($('input:radio[name=upload_work]:checked').val() == "no"){
        $('.upload_doc_panel_work').css("display", "none");
        $('#upload_doc_id').removeAttr('value');
        $("#upload_warning").fadeIn(1000).fadeOut(7000);
    }
    else {
        $('.upload_doc_panel_work').css("display", "block");   
    }
});
$("input[name='upload_work_above']").click(function(){
    if($('input:radio[name=upload_work_above]:checked').val() == "yes"){
        $('.upload_doc_panel_work').css("display", "none");
        $('.upload_work').css("display", "none");
        $('#upload_doc_id').removeAttr('value');
        // $("#upload_warning").fadeIn(1000).fadeOut(7000);
    }
    else {
        $('.upload_doc_panel_work').css("display", "block");   
        $('.upload_work').css("display", "block");
    }
});

$("input[name='upload_auto']").click(function(){
    if($('input:radio[name=upload_auto]:checked').val() == "no"){
        $('.upload_doc_panel_auto').css("display", "none");
        $('#upload_doc_id').removeAttr('value');
        $("#upload_warning").fadeIn(1000).fadeOut(7000);
    }
    else {
        $('.upload_doc_panel_auto').css("display", "block");   
    }
});
$("input[name='upload_auto_above']").click(function(){
    if($('input:radio[name=upload_auto_above]:checked').val() == "yes"){
        $('.upload_doc_panel_auto').css("display", "none");
        $('.upload_auto').css("display", "none");
        $('#upload_doc_id').removeAttr('value');
        // $("#upload_warning").fadeIn(1000).fadeOut(7000);
    }
    else {
        $('.upload_doc_panel_auto').css("display", "block");   
        $('.upload_auto').css("display", "block");
    }
});

$("#my-awesome-dropzone").click(function() {
  $("#upload_type").val("certificate_general_libility");
});
$("#my-awesome-dropzone").on("drop", function(event) {
    event.preventDefault();  
    event.stopPropagation();
    $("#upload_type").val("certificate_general_libility");
});
$("#my-awesome-dropzone1").click(function() {
  $("#upload_type").val("certificate_work_compensation");
});
$("#my-awesome-dropzone1").on("drop", function(event) {
    event.preventDefault();  
    event.stopPropagation();
    $("#upload_type").val("certificate_work_compensation");
});
$("#my-awesome-dropzone2").click(function() {
  $("#upload_type").val("certificate_auto_liability");
});
$("#my-awesome-dropzone2").on("drop", function(event) {
    event.preventDefault();  
    event.stopPropagation();
    $("#upload_type").val("certificate_auto_liability");
});
</script>

<script src="{{ url('/resources/assets/dist/certificate_add.js') }}"></script>
@include('include/footer')