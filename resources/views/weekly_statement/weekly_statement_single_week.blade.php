
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
                        <h2 style="float: left; margin-top: 0px;">Weekly Statement of Contract Days # <?php echo $report_id; ?> <span id="status" style="float: right; margin-bottom: 10px;"></span></h2>
                        
                    </div>

                    <div class="col-sm-12">
                        <section class="panel">
                            <!-- <div class="loading_data" style="text-align: center;">
                               <img src="{{ url('/resources/assets/img/loading1.gif') }}" alt="" />
                            </div> -->
                                <div class="col-md-6">
                                            <!-- <img src="{{ url('/resources/assets/img/login_logo.png') }}" alt=""> -->
                                        </div>
                                        <div class="col-md-6">
                                            <h4 class="pull-right">WORKING DAYS</h4>
                                        </div>
                                        <div class="col-md-12">
                                            <h3 style="text-align:center;">WEEKLY STATEMENT OF CONTRACT DAYS</h3>
                                        </div>

    <div class="col-md-6">
        <p style="margin: 0px;"><strong>Project:</strong> <span id="project_name"></span></p>
        <p><strong>Contractor:</strong> <span id="contractor_name"></span></p>
    </div>
    <div class="col-md-6">
        <p style="margin: 0px;"><strong>Report No.:</strong> <span id="report_id"></span></p>
        <p><strong>Week Ending:</strong> <span id="week_ending"></span></p>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th style="text-align:center;background: #c5c5c5;vertical-align: middle;">Day / Date</th>
            <th style="text-align:center;background: #c5c5c5;vertical-align: middle;">Weather | <br/>Conditions<sup>1</sup></th>
            <th style="text-align:center;background: #c5c5c5;vertical-align: middle;">Approved Calendar<br/>Days Charged</th>

            <th  style="text-align:center;background: #c5c5c5;vertical-align: middle;">Rain Days | <br/>Weather <sup>3</sup></th>
        </tr>
        </thead>
        <tbody id="calendar_week_days">
        <!-- 
        <tr>
            <td style="vertical-align: middle;">Monday, 5 January 2015</td>
            <td><input type="text" class="form-control" id=""></td>
            <td><input type="text" class="form-control" id=""></td>
            <td><input type="text" class="form-control" id=""></td>
            <td><input type="text" class="form-control" id=""></td>
        </tr> -->
        </tbody>
        <tbody>
        <tr>
            <td colspan="2" style="text-align: right; vertical-align: middle;">Working Days on this Report:</td>
            <td id="calendar_days_app_calender" style="text-align: center; font-weight: 600; vertical-align: middle;">0</td>
          
            <td id="" style="text-align: center; font-weight: 600; vertical-align: middle;"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: right; vertical-align: middle;">Working Days Previously Reported::</td>
            <td id="calendar_previous_days_app_calender" style="text-align: center; font-weight: 600; vertical-align: middle;">0</td>
            
            <td id="" style="text-align: center; font-weight: 600; vertical-align: middle;"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: right; vertical-align: middle;">Total Working Days to Date:</td>
            <td id="calendar_total_days_app_calender" style="text-align: center; font-weight: 600; vertical-align: middle;">0</td>
            <td id="" style="text-align: center; font-weight: 600; vertical-align: middle;"></td>
        </tr>

         <tr>
            <td colspan="2" style="text-align: right; vertical-align: middle;">Total Rain Delay Days to Date:</td>
            <td id="calendar_days_app_raily_day" style="text-align: center; font-weight: 600; vertical-align: middle;">0</td>
          
            <td id="" style="text-align: center; font-weight: 600; vertical-align: middle;"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: right; vertical-align: middle;">Total Change Order Request Days to Date:</td>
            <td id="calendar_previous_days_app_raily_day" style="text-align: center; font-weight: 600; vertical-align: middle;">0</td>
            
            <td id="" style="text-align: center; font-weight: 600; vertical-align: middle;"></td>
        </tr>
          <tr>
            <td colspan="2" style="text-align: right; vertical-align: middle;">Total Rainy Days to Date:</td>
            <td id="calendar_total_days_app_raily_day" style="text-align: center; font-weight: 600; vertical-align: middle;">0</td>
            <td id="" style="text-align: center; font-weight: 600; vertical-align: middle;"></td>
        </tr>
        <tr>
        <tr>
            <td colspan="5" style="text-align:center;background: #c5c5c5;padding: 12px;font-size: 16px;">Computation for Completion Date</td>
        </tr>
         <tr>
            <td colspan="2" style="vertical-align: middle;">1. First Working Day:</td>
            <td id="notice_to_proceed_start_date" style="text-align: center; font-weight: 600; vertical-align: middle;"></td>
            <td></td>
            <td></td>
        </tr>
       <tr>
            <td colspan="2" style="vertical-align: middle;">2. Original Contract Completion Date:</td>
            <td id="computed_completion_date" style="text-align: center; font-weight: 600; vertical-align: middle;"></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2" style="vertical-align: middle;">3. Total Working Days Specified in Contract:</td>
            <td id="notice_to_proceed_duration_day" style="text-align: center; font-weight: 600; vertical-align: middle;">0</td>
            <td></td>
            <td></td>
        </tr>

            <tr>
            <td colspan="2" style="vertical-align: middle;">4.  Delay Days to Date: </td> 
            <td id="day_due_to_rain" style="text-align: center; font-weight: 600; vertical-align: middle;">0</td>
            <td></td>
            <td ></td>
        </tr>

        <tr>
            <td colspan="2" style="vertical-align: middle;">5.  Revised Contract Working Days: </td> 
            <td id="revised_contract_working_days" style="text-align: center; font-weight: 600; vertical-align: middle;">0</td>
            <td></td>
            <td ></td>
        </tr>
 
        <tr style="display: none">
            <td colspan="2" style="vertical-align: middle;">5. <strong>Add:</strong> Time Extension / Change Orders <sup>4</sup> <span style="float: right;">Co No.:</span></td>
            <td><input type="text" class="form-control" id="time_extension"></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2" style="vertical-align: middle;">6. Days this Report (i.e. change order days, etc.):</td>
            <td  style="text-align: center; font-weight: 600; vertical-align: middle;"><input readonly type="number" min="0" max="1" id="days_this_report_app_calender" name="days_this_report_app_calender" class="form-control" required="required"></td>
            <td style="text-align: center; font-weight: 600; vertical-align: middle;"><input readonly type="number" min="0" max="1" id="days_this_report_app_non_calender" name="days_this_report_app_non_calender" class="form-control"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2" style="vertical-align: middle;">7. Days Previously Reported:</td>
            <td style="text-align: center; font-weight: 600; vertical-align: middle;"><input readonly type="number" min="0" max="1" id="days_previous_report_app_calender" name="days_previous_report_app_calender" class="form-control" required="required"></td>
            <td id="" style="text-align: center; font-weight: 600; vertical-align: middle;"><input readonly type="number" min="0" max="1" id="days_previous_report_app_non_calender" name="days_previous_report_app_non_calender" class="form-control" required="required"></td>
            <td></td>
        </tr>
        <tr style="display: none">
            <td colspan="2" style="vertical-align: middle;">8. Total days approved by Change Order(s):</td>
            <td id="total_day_approved_app_calender" style="text-align: center; font-weight: 600; vertical-align: middle;">0</td>
            <td id="total_day_approved_app_non_calender" style="text-align: center; font-weight: 600; vertical-align: middle;">0</td>
            <td></td>
        </tr>
        <tr style="display: none">
            <td colspan="2" style="vertical-align: middle;">9. Revised Total Calendar Days for Contract :</td>
            <td id="revised_total_calender" style="text-align: center; font-weight: 600; vertical-align: middle;">0</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2" style="vertical-align: middle;">8.Total Working Days to Date:</td>
            <td id="calendar_day_charged_app_calender" style="text-align: center; font-weight: 600; vertical-align: middle;">0</td>
            <td style="display: none;" id="calendar_day_charged_app_non_calender" style="text-align: center; font-weight: 600; vertical-align: middle;">0</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2" style="vertical-align: middle;">9. Remaining Working Days:</td>
            <td id="revised_calendar_day_remaining" style="text-align: center; font-weight: 600; vertical-align: middle;">0</td>
            <td></td>
            <td></td>
        </tr>
    
        <tr style="display: none">
            <td colspan="2" style="vertical-align: middle;">13. Revised Computed Completion Date:</td>
            <td id="revised_completion_date" style="text-align: center; font-weight: 600; vertical-align: middle;"></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2" style="vertical-align: middle;">Remarks / Controlling Operations / Notes:</td>
            <td colspan="3" style="vertical-align: middle;" id="remark_report"></td>
        </tr>
        </tbody>
    </table>                                        
    
 
    <div class="col-sm-12">
        <p><em>Distribution: Original (Contractor) | Copies (Agency, Owner and Murow CM)</em></p>
        <p><em><strong>Footnotes:</strong></em></p>
        <p><em>* Do not count a "Calendar" day for the Notice to Proceed ("NTP") date.  Holiday's are counted as a Calendar day on the "Calendar Statement" </em></p>
        <p><em>1. When recording nonworking days due to weather, state the reason why the day is unworkable when the weather description itself does not adequately describe conditions.  For example, "clear-wet grade" to describe conditions when the weather is clear, but the grade is too wet to work.  Do not list days merely as "unworkable", but describe thoroughly (i.e. "muddy and wet conditions".  Weather days are noted with a "0" in this column and entered as a "1" in the "Rain Days | Weather" column.</em></p>
        <p><em>2. Enter days on which no productive work has been performed on the controlling operation(s) for reasons other than weather.  Noted with a "1" for a qualified non-working day or a "0" if it does not.</em></p>
        <p><em>3. Type in a "1" for any and all days that are due to weather conditions (i.e. "rain" days).  These days do not get added to the total "Calendar" days for the scope of work being performed, but only extends the completion date by said number of "Rain days | Weather".</em></p>
        <p><em>4. List the numbered change order that is providing the time extension(s) (i.e. if change order number 1, type in "1.0").</em></p>
    </div>
       <div class="col-sm-12">
        <p>The Contractor will be allowed <strong>fifteen (15) calendar days</strong> in which to protest in writing the correctness of the statement; otherwise the statement shall be deemed to have been accepted by the Contractor as correct:</p>
    </div>
    <div class="col-sm-1">
        <p style="text-align: center; margin: 50px 0px 10px 0px;"><strong>By</strong></p>
    </div>
    <div class="col-sm-4">
        <p style="text-align: left; margin: 50px 0px 10px 0px; font-weight: 600;
    text-decoration: underline;" id="type_name"></p>
    </div>
<!--    <div class="col-sm-4">
        <p style="text-align: left; margin: 50px 0px 10px 0px;"><strong>Signature:</strong></p>
    </div>-->
    <div class="col-sm-7">
        <p style="text-align: right; margin: 50px 0px 10px 0px;"><strong>Date:</strong> <span id="sign_date" ></span></p>
    </div>
    
                        </section>
                        <div class="state-information" style="float: left;">
                            <a href="{{ url('/dashboard/'.$project_id.'/weekly_statement') }}" class="btn btn-info">Back</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--body wrapper end-->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/js/moment.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/weekly_report_single_week.js') }}"></script>
@include('include/footer')