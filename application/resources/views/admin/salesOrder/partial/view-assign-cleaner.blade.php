<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">View Cleaner Schedule</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form action="#" method="POST" id="view_cleaner_form" name="view_cleaner_form">  
        <div class="mb-3">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="cleaner_type" id="view_team"
                    value="team" readonly {{ $ScheduleModel->cleaner_type == 'team' ? 'checked' : '' }}>
                <label class="form-check-label" for="team">Team</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="cleaner_type" id="view_individual"
                    value="individual" readonly {{ $ScheduleModel->cleaner_type == 'individual' ? 'checked' : '' }}>
                <label class="form-check-label" for="individual">Individual</label>
            </div>
        </div>

        <div class="row mb-3">
            <!-- Start Date and End Date in the same row -->
            <div class="col-md-6">
                <label for="startDate">Start Date:</label>
                <input type="date" class="form-control startDate" id="view_startDate" name="startDate"
                    value="{{ $ScheduleModel->startDate }}" readonly>
            </div>
            <div class="col-md-6">
                <label for ="endDate">End Date:</label>
                <input type="date" class="form-control endDate" id="view_endDate" value="{{ $ScheduleModel->endDate }}"
                    name="endDate" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <!-- Postal Code and Unit No in the same row -->
            <div class="col-md-6">
                <label for="postalCode">Postal Code:</label>
                <input type="text" class="form-control" id="view_postalCode" name="postalCode"
                    value="{{ $ScheduleModel->postalCode }}" readonly>
            </div>
            <div class="col-md-6">
                <label for="unitNo">Unit No:</label>
                <input type="text" class="form-control" id="view_unitNo" value="{{ $ScheduleModel->unitNo }}"
                    name="unitNo" readonly>
            </div>
        </div>

        <div class="mb-3">
            <label for="address">Address:</label>
            <input type="text" class="form-control" id="view_address" name="address"
                value="{{ $ScheduleModel->address }}" readonly>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="total_session">Total Session:</label>
                <input type="text" class="form-control total_session" id="view_total_session" name="total_session"
                    value="{{ $ScheduleModel->total_session }}" readonly>
            </div>
            <div class="col-md-6">
                <label for="frequency">Weekly Freq:</label>
                <input type="text" class="form-control weekly_freq" id="view_weekly_freq" name="weekly_freq"
                    value="{{ $ScheduleModel->weekly_freq }}" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <label for="edit_man_power">Man Power Required:</label>
                <input type="text" class="form-control" id="view_man_power" name="man_power" value="{{ $ScheduleModel->man_power }}" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="startTime">Start Time:</label>
                <input type="time" class="form-control startTime" id="view_startTime" name="startTime"
                    value="{{ $ScheduleModel->startTime }}" readonly>
            </div>
            <div class="col-md-6">
                <label for="endTime">End Time:</label>
                <input type="time" class="form-control endTime" id="view_endTime" name="endTime"
                    value="{{ $ScheduleModel->endTime }}" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="edit_invoice_amount">Total Invoice Amount:</label>
                <input type="number" class="form-control" id="view_invoice_amount" name="invoice_amount" step="0.01" min="0" value="{{ $ScheduleModel->invoice_amount }}" readonly>
            </div>

            <div class="col-md-6">
                <label for="edit_invoice_amount">Total Payable Amount:</label>
                <input type="number" class="form-control" id="view_total_pay_amount" name="total_pay_amount" step="0.01" min="0" value="{{ $ScheduleModel->balance_amount }}" readonly>
            </div>
        </div>

        <div class="mb-3">
            <div class="form-check form-check-inline">
                <input class="form-check-input dayslist" type="checkbox" id="dayMonday" name="days[]"
                    value="Monday" {{ in_array('Monday', $ScheduleModel->selected_days) ? 'checked' : '' }} readonly>
                <label class="form-check-label" for="dayMonday">Mon</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input dayslist" type="checkbox" id="dayTuesday" name="days[]"
                    value="Tuesday" {{ in_array('Tuesday', $ScheduleModel->selected_days) ? 'checked' : '' }} readonly>
                <label class="form-check-label" for="dayTuesday">Tue</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input dayslist" type="checkbox" id="dayWednesday" name="days[]"
                    value="Wednesday" {{ in_array('Wednesday', $ScheduleModel->selected_days) ? 'checked' : '' }} readonly>
                <label class="form-check-label" for="dayWednesday">Wed</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input dayslist" type="checkbox" id="dayThursday" name="days[]"
                    value="Thursday" {{ in_array('Thursday', $ScheduleModel->selected_days) ? 'checked' : '' }} readonly>
                <label class="form-check-label" for="dayThursday">Thu</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input dayslist" type="checkbox" id="dayFriday" name="days[]"
                    value="Friday" {{ in_array('Friday', $ScheduleModel->selected_days) ? 'checked' : '' }} readonly>
                <label class="form-check-label" for="dayFriday">Fri</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input dayslist" type="checkbox" id="daySaturday" name="days[]"
                    value="Saturday" {{ in_array('Saturday', $ScheduleModel->selected_days) ? 'checked' : '' }} readonly>
                <label class="form-check-label" for="daySaturday">Sat</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input dayslist" type="checkbox" id="daySunday" name="days[]"
                    value="Sunday" {{ in_array('Sunday', $ScheduleModel->selected_days) ? 'checked' : '' }} readonly>
                <label class="form-check-label" for="daySunday">Sun</label>
            </div>
        </div>

        <div class="calender" style="display: none;">
            <div class="row">
                <div class="col-md-12">
                    <input type="text" name="datepick" id="edit_datePick" class="form-control datePick"
                        value="{{ $ScheduleModel->days }}" readonly/>
                </div>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-md-12">
                <table class="table" id="edit_set_details_table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Helpers</th>
                            <th>Payable Amount</th>
                            <th>Remarks / Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ScheduleDetails as $key => $item)                                                 
                            <tr>                                                                     
                                <td style="width: 15%;">     
                                    <input type="date" class="form-control" name="table_schedule_date[]"
                                        value="{{ $item->schedule_date ?? ''}}" readonly>

                                    @if ($item->driver_display == true)
                                        <div class="table_delivery_date_group" style="{{($item->cleaner_type == 'team' ? 'margin-top: 133px;' : 'margin-top: 85px;')}}">
                                            <label for="">Delivery Date</label>
                                            <input type="date" class="form-control table_delivery_date" name="table_delivery_date[]" value="{{ $item->delivery_date ?? '' }}" readonly>
                                        </div> 
                                    @endif                                  
                                </td>
                                <td style="width: 15%;">
                                    <input type="time" class="form-control" name="table_startTime[]"
                                        value="{{ $item->startTime ?? ''}}" readonly>

                                    @if ($item->driver_display == true)
                                        <div class="table_delivery_time_group" style="{{($item->cleaner_type == 'team' ? 'margin-top: 133px;' : 'margin-top: 85px;')}}">
                                            <label for="">Delivery Time</label>
                                            <input type="time" class="form-control table_delivery_time" name="table_delivery_time[]" value="{{ $item->delivery_time ?? '' }}" readonly>
                                        </div> 
                                    @endif
                                </td>
                                <td style="width: 15%;">
                                    <input type="time" class="form-control" name="table_endTime[]"
                                        value="{{ $item->endTime ?? ''}}" readonly>
                                </td>
                                <td style="width: 25%;">  

                                    @if ($item->cleaner_type == "team")
                                        <div class="mb-3 table_team">
                                            <input type="text" class="form-control" value="{{$item->team_name}}" readonly>                           
                                        </div>
                
                                        <div class="mb-3 table_employee">
                                            <label>Employee List:</label>
                                            <textarea class="form-control" rows="4" readonly>{{$item->employee_name}}</textarea>
                                        </div>
                                    @else
                                        <div class="mb-3 table_individual">
                                            <input type="text" class="form-control" value="{{$item->employee_name}}" readonly>                           
                                        </div>

                                        <div class="mb-3 table_superviser">
                                            <label>Superviser</label>
                                            <input type="text" class="form-control" value="{{$item->super_viser_employee_name}}" readonly>     
                                        </div>
                                    @endif

                                    @if ($item->driver_display == true)
                                        <div class="mb-3 table_driver" style="">
                                            <label>Driver</label>
                                            <input type="text" class="form-control" value="{{$item->driver_employee_name}}" readonly>                                             
                                        </div>      
                                    @endif

                                </td>
                                <td style="width: 15%;">
                                    <input type="number" class="form-control" min="0" step="0.01" value="{{ $item->pay_amount }}" readonly>    
                                </td>
                                <td style="width: 15%;">
                                    <div class="mb-3">
                                        <textarea class="form-control" name="table_remarks[]" cols="30" rows="3" readonly>{{$item->remarks}}</textarea>
                                    </div>

                                    @if ($item->job_status == 3)
                                        <div class="mb-3">
                                            <span class="badge bg-danger">Cancelled</span>
                                        </div>
                                    @elseif($item->job_status == 2)
                                        <div class="mb-3">
                                            <span class="badge bg-success">Completed</span>
                                        </div>
                                    @elseif($item->job_status == 1)
                                        <div class="mb-3">
                                            <span class="badge bg-warning">Work In Progress</span>
                                        </div>
                                    @endif   
                                    
                                    @if ($item->driver_display == true)
                                        <div class="mb-3 table_delivery_remarks_group" style="{{ ($item->job_status == 0) ? 'margin-top: 50px;' : '' }}">
                                            <label for="">Delivery Remarks</label>
                                            <textarea class="form-control table_delivery_remarks" name="table_delivery_remarks[]" cols="30" rows="3" readonly>{{$item->delivery_remarks ?? ''}}</textarea>
                                        </div>    
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mb-3">
            <label for="addrcustomer_remarkess">Remark:</label>
            <input type="text" class="form-control" id="edit_remark" name="edit_remark"
                value="{{ $ScheduleModel->remarks }}" readonly>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </form>
</div>
