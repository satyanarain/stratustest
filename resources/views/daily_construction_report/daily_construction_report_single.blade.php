
        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >
            <?php $project_id = Request::segment(2); ?>
            <?php $report_id = Request::segment(4); ?>
          @include('include/top_bar')

            <!--body wrapper start-->
            <div class="wrapper" style="display: none;">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="m-b-less project_name" id="project_name_title"></h3><br/>
                        <h2>Daily Construction Management Report # <?php echo $report_id; ?> <span id="status" style="float: right; margin-bottom: 10px;"></span></h2>
                    </div>

                    <div class="col-sm-12">
                        <section class="panel">
                            <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th width="25%">Daily Report :</th>
                                        <td width="25%"># <?php echo $report_id; ?></td>
                                        <th width="25%">Date of Report: </th>
                                        <td width="25%" id="report_date"></td>
                                    </tr>
                                    <tr>
                                        <th>Temperature</th>
                                        <td id="report_weather_detail"></td>
                                        <th></th>
                                        <td id="report_custom_detail"></td>
                                    </tr>
                                    <tr class="info">
                                        <th colspan="4">Did the General Contractor perform work this day?</th>
                                    </tr>
                                    <tr>
                                        <td colspan="4" id="report_work_day"></td>
                                    </tr>
                                    <tr class="info">
                                       <th colspan="4">Did Subcontractors perform any work this day?</th> 
                                    </tr>
                                    <tr>
                                        <td colspan="4" id="subcontractor_work_day"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" id="subcontractor_work_detail"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" id="subcontractor_work_detail_comment"></td>
                                    </tr>
                                    <!-- <tr>
                                        <th colspan="4">What Contract Items were worked on?</th>
                                    </tr>
                                    <tr>
                                        <td colspan="4" id="contract_item"></td>
                                    </tr> -->
                                    <tr class="info">
                                        <th colspan="4">How many quantities were completed for each?</th>
                                    </tr>
                                    <tr>
                                        <th>Bid Item #</th>
                                        <th>Description</th>
                                        <th>Qty Completed This Day</th>
                                        <th>Location/Additional Information</th>
                                    </tr>
                                    <tr>
                                            <tbody id="contract_item_qty">
                                            </tbody>
                                        
                                    </tr>
                                    <tr class="info">
                                        <th colspan="4">What Resources Were Used for Each Contract Item This Day?</th>
                                    </tr>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Resource</th>
                                        <th>Time</th>
                                        <th>Time type</th>
                                    </tr>
                                    <tr>
                                        <tbody id="contract_item_resource">
                                        </tbody>
                                    </tr>
                                    <tr class="info">
                                        <th colspan="4">Was any material delivered to the Site?</th>
                                    </tr>
                                    <tr>
                                        <td colspan="4" id="report_material_delivered"></td>
                                    </tr>
                                    <tr>
                                        <th>What type of material was delivered?</th>
                                        <th>How many units?</th>
                                        <th>What type of units?</th>
                                        <th>Delivery Ticket</th>
                                    </tr>
                                    <tr>
                                        <tbody id="material_type">
                                        </tbody>
                                    </tr>
                                    <tr class="info">
                                        <th colspan="4">Were any Schedule Milestones Completed this Day?</th>
                                    </tr>
                                    <tr>
                                        <td colspan="4" id="milestone">Yes</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" id="milestone_detail"></td>
                                    </tr>
                                    <tr class="info">
                                        <th colspan="4">Did any of the following events occur this day?</th>
                                    </tr>
                                    <tr>
                                        <td colspan="4" id="report_occur_type"></td>
                                    </tr>
                                    <tr class="info"> 
                                        <th colspan="4">General Notes Section</th>
                                    </tr>
                                    <tr>
                                        <td colspan="4" id="report_general_note"></td>
                                    </tr>
                                    <tr class="info">
                                        <th colspan="4">Were any photos or videos taken this day?</th>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Photo / Video Description</th>
                                        <th colspan="2">Photo / Video </th>
                                    </tr>
                                    <tr>
                                        <tbody id="daily_photo_video">
                                        </tbody>
                                    </tr>
                                    
                                    <tr>
                                        <td colspan="4">
                                            <a href="{{ url('/dashboard/'.$project_id.'/daily_construction_report') }}" class="btn btn-info pull-right">Back</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </section>
                    </div>
                </div>
            </div>
            <!--body wrapper end-->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/daily_construction_report_single.js') }}"></script>
@include('include/footer')