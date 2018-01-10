 <!--footer section start-->
 <div class="modal confrm-pop fade" id="confirm-continue" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-md">
         <div class="modal-content text-center">
             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
             <strong>Are you sure you want to leave this page?</strong>
             <p>Changes you made may not be saved.</p>
             <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
             <a href="" class="btn btn-success btn-ok confirm_next_button_alert">Yes</a>
         </div>
     </div>
 </div>
 <div class="modal confrm-pop fade" id="confirm-back" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 1051 !important;">
     <div class="modal-dialog modal-md">
         <div class="modal-content text-center">
             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
             <strong>Are you sure you want to leave this page?</strong>
             <p>Changes you made may not be saved.</p>
             <button type="button" class="btn btn-danger confirm_back_button_no" data-dismiss="modal">No</button>
             <a href="" class="btn btn-success btn-ok confirm_back_button_alert">Yes</a>
         </div>
     </div>
 </div>

 <div class="modal add-company fade" id="add-company" tabindex="-3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-md">
         <div class="modal-content text-center">
             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            @if(Route::getCurrentRoute()->getPath()=="dashboard/{project_id}/test_result/add" || Route::getCurrentRoute()->getPath()=="dashboard/{project_id}/standards/add" || Route::getCurrentRoute()->getPath()=="dashboard/{project_id}/specifications/add" || Route::getCurrentRoute()->getPath()=="dashboard/{project_id}/geo_reports/add" || Route::getCurrentRoute()->getPath()=="dashboard/{project_id}/swppp/add" || Route::getCurrentRoute()->getPath()=="dashboard/{project_id}/preliminary_notice/add" || Route::getCurrentRoute()->getPath()=="dashboard/{project_id}/unconditional_finals/add" || Route::getCurrentRoute()->getPath()=="users/add" || Route::getCurrentRoute()->getPath()=="dashboard/projects/add" || Route::getCurrentRoute()->getPath()=="dashboard/{project_id}/bond/add")
             @include('include/add_new_firm')
            @endif
         </div>
     </div>
 </div>
 
            <footer>
                <?php echo date("Y"); ?> &copy; StratusCM, LLC<?php //echo Route::getCurrentRoute()->getPath();?>
            </footer>
            <!--footer section end-->
        </div>
        <!-- body content end-->
    </section>



<!-- Placed js at the end of the document so the pages load faster -->
<!-- <script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script> -->
<!-- <script src="{{ url('/resources/assets/dist/dashboard.js') }}"></script> -->

<!--jquery-ui-->
<!-- <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script> -->

<script src="{{ url('/resources/assets/js/jquery-ui/jquery-ui-1.12.2.custom.min.js') }}" type="text/javascript"></script>
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->

<script src="{{ url('/resources/assets/dist/top_bar.js') }}"></script>

<script src="{{ url('/resources/assets/js/jquery.pulsate.min.js') }}"></script>
<script src="{{ url('/resources/assets/js/pulstate.js') }}"></script>

<script src="{{ url('/resources/assets/js/jquery-migrate.js') }}"></script>
<script src="{{ url('/resources/assets/js/bootstrap.min.js') }}"></script>
<script src="{{ url('/resources/assets/js/modernizr.min.js') }}"></script>

<!--Nice Scroll-->
<script src="{{ url('/resources/assets/js/jquery.nicescroll.js') }}" type="text/javascript"></script>

<!--right slidebar-->
<script src="{{ url('/resources/assets/js/slidebars.min.js') }}"></script>

<!--switchery-->
<script src="{{ url('/resources/assets/js/switchery/switchery.min.js') }}"></script>
<script src="{{ url('/resources/assets/js/switchery/switchery-init.js') }}"></script>

<!--dropzone-->
<!-- <script src="{{ url('/resources/assets/js/dropzone.js') }}"></script> -->
<!-- <script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/dropzone.js') }}"></script> -->

<!--flot chart -->
<script src="{{ url('/resources/assets/js/flot-chart/jquery.flot.js') }}"></script>
<script src="{{ url('/resources/assets/js/flot-chart/flot-spline.js') }}"></script>
<script src="{{ url('/resources/assets/js/flot-chart/jquery.flot.resize.js') }}"></script>
<script src="{{ url('/resources/assets/js/flot-chart/jquery.flot.tooltip.min.js') }}"></script>
<script src="{{ url('/resources/assets/js/flot-chart/jquery.flot.pie.js') }}"></script>
<script src="{{ url('/resources/assets/js/flot-chart/jquery.flot.selection.js') }}"></script>
<script src="{{ url('/resources/assets/js/flot-chart/jquery.flot.stack.js') }}"></script>
<script src="{{ url('/resources/assets/js/flot-chart/jquery.flot.crosshair.js') }}"></script>


<!--earning chart init-->
<script src="{{ url('/resources/assets/js/earning-chart-init.js') }}"></script>


<!--Sparkline Chart-->
<script src="{{ url('/resources/assets/js/sparkline/jquery.sparkline.js') }}"></script>
<script src="{{ url('/resources/assets/js/sparkline/sparkline-init.js') }}"></script>

<!--easy pie chart-->
<script src="{{ url('/resources/assets/js/jquery-easy-pie-chart/jquery.easy-pie-chart.js') }}"></script>
<script src="{{ url('/resources/assets/js/easy-pie-chart.js') }}"></script>


<!--vectormap-->
<script src="{{ url('/resources/assets/js/vector-map/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/js/vector-map/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ url('/resources/assets/js/dashboard-vmap-init.js') }}"></script>

<!--Icheck-->
<script src="{{ url('/resources/assets/js/icheck/skins/icheck.min.js') }}"></script>
<script src="{{ url('/resources/assets/js/todo-init.js') }}"></script>

<!--jquery countTo-->
<script src="{{ url('/resources/assets/js/jquery-countTo/jquery.countTo.js') }}"  type="text/javascript"></script>

<!--owl carousel-->
<script src="{{ url('/resources/assets/js/owl.carousel.js') }}"></script>

<!--touchspin spinner-->
<script src="{{ url('/resources/assets/js/touchspin.js') }}"></script>

<!--spinner init-->
<script src="{{ url('/resources/assets/js/spinner-init.js') }}"></script>

<!--select2-->
<script src="{{ url('/resources/assets/js/select2.js') }}"></script>
<!--select2 init-->
<script src="{{ url('/resources/assets/js/select2-init.js') }}"></script>

<!--Data Table-->
<!-- <script src="{{ url('/resources/assets/js/data-table/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('/resources/assets/js/data-table/js/dataTables.tableTools.min.js') }}"></script>
<script src="{{ url('/resources/assets/js/data-table/js/bootstrap-dataTable.js') }}"></script>
<script src="{{ url('/resources/assets/js/data-table/js/dataTables.colVis.min.js') }}"></script>
<script src="{{ url('/resources/assets/js/data-table/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('/resources/assets/js/data-table/js/dataTables.scroller.min.js') }}"></script> -->
<!--data table init-->
<!-- <script src="{{ url('/resources/assets/js/data-table-init.js') }}"></script> -->

<script src="{{ url('/resources/assets/js/data-table/new_script/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('/resources/assets/js/data-table/new_script/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('/resources/assets/js/data-table/new_script/buttons.flash.min.js') }}"></script>
<script src="{{ url('/resources/assets/js/data-table/new_script/jszip.min.js') }}"></script>
<script src="{{ url('/resources/assets/js/data-table/new_script/pdfmake.min.js') }}"></script>
<script src="{{ url('/resources/assets/js/data-table/new_script/vfs_fonts.js') }}"></script>
<script src="{{ url('/resources/assets/js/data-table/new_script/buttons.html5.min.js') }}"></script>
<script src="{{ url('/resources/assets/js/data-table/new_script/buttons.print.min.js') }}"></script>

<!--bootstrap-fileinput-master-->
<script type="text/javascript" src="{{ url('/resources/assets/js/bootstrap-fileinput-master/js/fileinput.js') }}"></script>
<script type="text/javascript" src="{{ url('/resources/assets/js/file-input-init.js') }}"></script>


<!--toastr-->
<script src="{{ url('/resources/assets/js/toastr-master/toastr.js') }}"></script>
<script src="{{ url('/resources/assets/js/toastr-init.js') }}"></script>

<!--common scripts for all pages-->

<script src="{{ url('/resources/assets/js/scripts.js') }}"></script>

<!--select2-->
<script src="{{ url('/resources/assets/js/select2.js') }}"></script>
<!--select2 init-->
<script src="{{ url('/resources/assets/js/select2-init.js') }}"></script>


<!--fuelux tree-->
<script src="{{ url('/resources/assets/js/fuelux/js/tree.min.js') }}"></script>
<script src="{{ url('/resources/assets/js/tree-init.js') }}"></script>

<!--bootstrap-wysihtml5-->
<!-- <script type="text/javascript" src="{{ url('/resources/assets/js/bootstrap-wysihtml5/wysihtml5-0.3.0.js') }}"></script>
<script type="text/javascript" src="{{ url('/resources/assets/js/bootstrap-wysihtml5/bootstrap-wysihtml5.js') }}"></script> -->

<!--summernote-->
<script src="{{ url('/resources/assets/js/summernote/dist/summernote.min.js') }}"></script>

<!--bootstrap picker-->
<script src="{{ url('/resources/assets/js/moment-with-locales.js') }}"></script>

<!-- <script type="text/javascript" src="{{ url('/resources/assets/js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js') }}"></script> -->
<script src="{{ url('/resources/assets/js/bootstrap-datepicker/js/bootstrap-datetimepicker_one.js') }}"></script>

<script type="text/javascript" src="{{ url('/resources/assets/js/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>


<script type="text/javascript" src="{{ url('/resources/assets/js/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<script type="text/javascript" src="{{ url('/resources/assets/js/bootstrap-colorpicker/js/bootstrap-colorpicker.js') }}"></script>

<script type="text/javascript" src="{{ url('/resources/assets/js/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>

<!--picker initialization-->
<script src="{{ url('/resources/assets/js/picker-init.js') }}"></script>

<!--custom scrollbar-->
<script src="{{ url('/resources/assets/js/jquery.jscrollpane.js') }}"></script>


<script type="text/javascript">
    $('body').on('focus',".paginate_button", function(){
        setTimeout(function(){
            $('html, body').animate({
                scrollTop: $(".page-head").offset().top
            }, 'fast');
        }, 100);
    });


    $(document).ready(function() {

        $(function()
            {
                $('.scroll-pane').jScrollPane();
            });

        //countTo

        $('.timer').countTo();

        //owl carousel

        $("#news-feed").owlCarousel({
            navigation : true,
            slideSpeed : 300,
            paginationSpeed : 400,
            singleItem : true,
            autoPlay:true
        });

        setTimeout(function(){
           // $('#view_users_table').wrap("<div class='tab-parent' style='overflow-x:scroll;'></div>");
           $('.table_scroll_x_axis').wrap("<div class='tab-parent' style='overflow-x:scroll;'></div>");
        }, 1000);

        // setTimeout(function(){
        //     $(".paginate_button").click(function() {
        //         console.log('faizan');
        //         $('html, body').animate({
        //             scrollTop: $(".page-head").offset().top
        //         }, 'fast');
        //     });
        // }, 1000);

        // $('body').html( $('body').html().replace('stratus//uploads','stratus/uploads') );
    });

    $(window).on("resize",function(){
        var owl = $("#news-feed").data("owlCarousel");
        // owl.reinit();
    });

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

//    $('.continue_button').click(function(e) {
//        e.preventDefault();
//        var href = $(this).attr('data-href');
//        $('.confirm_next_button_alert').attr('href', href);
//        // if($('.panel input').val()){
//        //     alert('yes');
//        // }
//        // else {
//        //     alert('no');
//        // }
//        // if($('.panel .date').val()){
//        //     alert('yes');
//        // }
//        // else {
//        //     alert('no');
//        // }
//    });

//    $('.back_button').click(function(e) {
//        e.preventDefault();
//        var href = $(this).attr('data-href');
//        $('.confirm_back_button_alert').attr('href', href);
//        // var r = confirm("Do you want to back this page? <br/>Changes you made may not be saved.");
//        // if (r == true) {
//        //     var href = $(this).attr('href');
//        //     window.location.assign(href);
//        //     return true;
//        // } else {
//        //     return false;
//        // }
//    });


    // $("#my-awesome-dropzone").click(function() {
    //   $(".first_button").prop('disabled', true);
    // });
var isFilled = false;
$(document).ready(function() {
    $(".btn_back").on('click',function(){
        checkFormFilled('btn_back');
    });
    $(".continue_button").on('click',function(){
        checkFormFilled('continue_button');
    });
    $(this).on('keypress', function(event) {
        //if (event.keyCode === 13) {
            //isFilled = true;
        //}
    })
    
    $(".confirm_back_button_alert").on('click',function(){
       $("#confirm-back").modal("hide"); 
    })
});
function checkFormFilled(classname)
{
    if($("#upload_doc_id").val() || $("#upload_doc_id_adv_bid").val() || $("#upload_doc_id_notice_invite").val() || $("#upload_doc_id_bid_result").val() || $("#upload_doc_id_success_bidder").val() || $("#upload_doc_id_general").val() || $("#upload_doc_id_work").val() || $("#upload_doc_id_auto").val() || $("#upload_doc_id_umbrella").val() || $("#upload_doc_id_certificate").val() || $("#upload_single_doc_id").val() || $(".upload_doc_id").val() || $("#upload_doc_id_work").val() || $("#upload_doc_id_auto").val() || $("#upload_doc_id_general").val() || $("#upload_doc_id_general").val() || $("#upload_doc_id_general").val() || $("#upload_doc_id_general").val() || $("#upload_doc_id_general").val() || $("#upload_doc_id_general").val() || $("#upload_doc_id_general").val())
    {
        isFilled = true;
    }
    //alert(isFilled);
    $("input[type=text],select").each(function(key,value) {
        //alert(key);alert(value);
        //var form_ele_name = '';
        //form_ele_name = $(this).attr('name');
        //alert(typeof form_ele_name);
        var exempted_val = ["Add New Company","Select Improvement Types","Add New Entity","Add New Agency"];
        var exempted_array = ["project_terms","umbrella_liability_cur_symbol","general_liability_cur_symbol","company_name_two","maintenance_bond_cur_symbol","payment_bond_cur_symbol","performance_bond_cur_symbol", 
            "demo2","auto_compensation_cur_symbol", "works_compensation_cur_symbol","contract_item_qty","maintenance_bond_amount",
        "performance_bond_amount","payment_bond_amount","company_name","project_type_dropdown","notice_award_improvement_type",
    "notice_award_project_type_dropdown","notice_award_company_name","notice_award_bid_amount","notice_award_date"];
        var exempted = jQuery.inArray($(this).attr('name'), exempted_array);
        var exempted1 = jQuery.inArray($(this).val(), exempted_val);
        //alert($(this).attr('name'));
        //alert(exempted);
        if(exempted >= 0)
        {
            //alert($(this).attr('name'));
            //alert('exempted');
        }else{
            if ($(this).val() && exempted1 < 0) {
                //var nam = element.getAttribute("name");
                //alert(nam);
                isFilled = true;
                console.log($(this));
                //alert($(this).attr('name'));
                //alert($(this).name);
                //alert('Type: ' + input.attr('type') + 'Name: ' + input.attr('name') + 'Value: ' + input.val());
                //alert($(this).val());
                //return false;
            }
        }
    });
    if($('input:checkbox').is(':checked'))
    {
        //alert('344');
        isFilled = true;
    }

    if(isFilled===true){
        if(classname=="modal_btn_back")
        {
            var href = $("."+classname).attr('href');
            $("#confirm-back").modal("show");
            $('.confirm_back_button_alert').attr('onclick', '$("#add-company").modal("hide");');
            $('.confirm_back_button_alert').attr('href', href);
            
            return false;
        }else{
            var href = $("."+classname).attr('href');
            $("#confirm-back").modal("show");
            $('.confirm_back_button_alert').attr('href', href);
            return false;
        }
    }else{
        return true;
    }
}
window.onbeforeunload = function(e){
    //e.preventDefault();
    //checkFormFilled('btn_back');
  //return false;
};
</script>
</body>
</html>