<div class="col-md-12">
    <table class="table schedule_set_details_table" id="edit_set_details_table">
        <thead>
            <tr>
                <th onclick="sortTable(0)" style="cursor: pointer;">Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Helpers</th>
                <th>Payable Amount</th>
                <th>Remarks / Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sch_date as $key => $item)
                @php

                    $Schedule_details = App\Models\ScheduleDetails::where('sales_order_id', $sales_order_id)
                                                                    ->whereDate('schedule_date', $item)
                                                                    ->first();                  
                        
                    $schedule_employee_details = DB::table('tble_schedule_employee')
                                                    ->where('sales_order_id', $sales_order_id)
                                                    ->whereDate('schedule_date', $item)
                                                    ->get();

                    $schedule_employee_arr = [];
                    $emp_name_arr = [];

                    if ($cleaner_type == 'team') 
                    {
                        if (!empty($Schedule_details->employee_id)) {
                            if (array_key_exists($Schedule_details->employee_id, $employeeNames))
                            {
                                $emp_name_arr = $employeeNames[$Schedule_details->employee_id];
                            }                                                   
                        }                     
                    } 
                    else 
                    {               
                        if(!empty($Schedule_details->employee_id))
                        {
                            $schedule_employee_arr = explode(',', $Schedule_details->employee_id);
                        }
                    }

                    $emp_name = implode("\n", $emp_name_arr);

                    $readonly = "";
                    $schedule_details_id = "";

                    if(isset($Schedule_details->job_status))
                    {
                        if($Schedule_details->job_status == 2 || $Schedule_details->job_status == 1 || $Schedule_details->job_status == 3)
                        {
                            $readonly = "readonly";
                            $schedule_details_id = $Schedule_details->id;
                        }
                    }

                    // driver start

                    if (isset($Schedule_details->delivery_date) || isset($Schedule_details->delivery_time) || isset($Schedule_details->driver) || isset($Schedule_details->delivery_remarks)) 
                    {
                        $driver_display = "";
                        $add_btn_driver_display = "display: none;";              
                    }
                    else
                    {
                        $driver_display = "display: none;";
                        $add_btn_driver_display = "";
                    }            

                    // driver end


                    // start

                    // $edit_sch_team_emp = [];
                    // $edit_sch_indv_emp = [];
                                                                
                    // $edit_sch_team_emp = App\Models\ScheduleDetails::where('tble_schedule_details.sales_order_id', '!=', $sales_order_id)
                    //                         ->whereDate('tble_schedule_details.schedule_date', $item)
                    //                         ->where(function ($query) use ($Schedule_details, $startTime, $endTime) {
                    //                             $query->where(function ($query1) use ($Schedule_details, $startTime) {
                    //                                 $query1->whereTime('tble_schedule_details.startTime', '<=', $Schedule_details->startTime ?? $startTime);
                    //                                 $query1->whereTime('tble_schedule_details.endTime', '>=', $Schedule_details->startTime ?? $startTime);
                    //                             })
                    //                             ->orWhere(function ($query2) use ($Schedule_details, $endTime) {
                    //                                 $query2->whereTime('tble_schedule_details.startTime', '<=', $Schedule_details->endTime ?? $endTime);
                    //                                 $query2->whereTime('tble_schedule_details.endTime', '>=', $Schedule_details->endTime ?? $endTime);
                    //                             }) ;
                    //                         })                         
                    //                         ->where('tble_schedule_details.job_status', 0)                                                      
                    //                         ->join('tble_schedule_employee', 'tble_schedule_employee.tble_schedule_details_id', '=', 'tble_schedule_details.id')
                    //                         ->where('tble_schedule_details.cleaner_type', 'team')
                    //                         ->pluck('tble_schedule_employee.employee_id')
                    //                         ->toArray();

                    // $edit_sch_indv_emp = App\Models\ScheduleDetails::where('tble_schedule_details.sales_order_id', '!=', $sales_order_id)
                    //                         ->whereDate('tble_schedule_details.schedule_date', $item)
                    //                         ->where(function ($query) use ($Schedule_details, $startTime, $endTime) {
                    //                             $query->where(function ($query1) use ($Schedule_details, $startTime) {
                    //                                 $query1->whereTime('tble_schedule_details.startTime', '<=', $Schedule_details->startTime ?? $startTime);
                    //                                 $query1->whereTime('tble_schedule_details.endTime', '>=', $Schedule_details->startTime ?? $startTime);
                    //                             })
                    //                             ->orWhere(function ($query2) use ($Schedule_details, $endTime) {
                    //                                 $query2->whereTime('tble_schedule_details.startTime', '<=', $Schedule_details->endTime ?? $endTime);
                    //                                 $query2->whereTime('tble_schedule_details.endTime', '>=', $Schedule_details->endTime ?? $endTime);
                    //                             }) ;
                    //                         })                         
                    //                         ->where('tble_schedule_details.job_status', 0)                                                      
                    //                         ->join('tble_schedule_employee', 'tble_schedule_employee.tble_schedule_details_id', '=', 'tble_schedule_details.id')
                    //                         ->where('tble_schedule_details.cleaner_type', 'individual') 
                    //                         ->pluck('tble_schedule_employee.employee_id')
                    //                         ->toArray();

                    // echo "<pre>";
                    // print_r($edit_sch_indv_emp->toSql());
                    // print_r($edit_sch_indv_emp->getBindings());
                    // die;

                    // end
                
                @endphp
           
                <tr>     
                    @if(isset($Schedule_details->job_status))
                        @if ($Schedule_details->job_status == 1 || $Schedule_details->job_status == 2 || $Schedule_details->job_status == 3)
                            <input type="hidden" name="schedule_details_id[]" value="{{$Schedule_details->id}}">
                        @endif    
                    @endif     
                            
                    <td style="width: 15%;">                  
                        <input type="date" class="form-control table_schedule_date" name="table_schedule_date[]"
                            value="{{ $item }}" {{$readonly ?? ''}}>

                        <input type="hidden" name="table_schedule_details_id[]" value="{{$schedule_details_id ?? ''}}">

                        <div class="table_delivery_date_group" style="{{$driver_display ?? ''}} {{($cleaner_type == 'team' ? 'margin-top: 133px;' : 'margin-top: 70px;')}}">
                            <label for="">Delivery Date</label>
                            <input type="date" class="form-control table_delivery_date" name="table_delivery_date[]" value="{{ $Schedule_details->delivery_date ?? '' }}" {{$readonly ?? ''}}>
                        </div>   
                    </td>
                    <td style="width: 15%;">
                        <input type="time" class="form-control table_startTime" name="table_startTime[]"
                            value="{{ $Schedule_details->startTime ?? $startTime }}" {{$readonly ?? ''}}>

                        <div class="table_delivery_time_group" style="{{$driver_display ?? ''}} {{($cleaner_type == 'team' ? 'margin-top: 133px;' : 'margin-top: 70px;')}}">
                            <label for="">Delivery Time</label>
                            <input type="time" class="form-control table_delivery_time" name="table_delivery_time[]" value="{{ $Schedule_details->delivery_time ?? '08:00' }}" {{$readonly ?? ''}}>
                        </div> 
                    </td>
                    <td style="width: 15%;">
                        <input type="time" class="form-control table_endTime" name="table_endTime[]"
                            value="{{ $Schedule_details->endTime ?? $endTime }}" {{$readonly ?? ''}}>
                    </td>
                    <td style="width: 25%;">

                        @php
                            if ($cleaner_type == 'team') {
                                $team_display = '';
                                $individual_display = 'display: none';
                            } else {
                                $team_display = 'display: none';
                                $individual_display = '';
                            }
                        @endphp

                        <div class="mb-3 table_team" style="{{ $team_display }}">
                            <select class="form-control select2 table_team_id" name="table_team_id[]">
                                <option value="">Select Team</option>

                                @if ($cleaner_type == 'team')
                                    @foreach ($get_team as $team)
                                        @if (!in_array($team->team_id, $sch_team_emp[$key]))  
                                            <option value="{{ $team->team_id }}"
                                                {{ ($Schedule_details->employee_id ?? '') == $team->team_id ? 'selected' : '' }}>
                                                {{ $team->team_name }}</option>
                                        @endif
                                    @endforeach
                                @else
                                    @foreach ($get_team as $team)
                                        @if (!in_array($team->team_id, $sch_team_emp[$key]))      
                                            <option value="{{ $team->team_id }}">{{ $team->team_name }}</option>
                                        @endif
                                    @endforeach
                                @endif

                            </select>
                        </div>

                        <div class="mb-3 table_employee" style="{{ $team_display }}">
                            <label>Employee List:</label>
                            <textarea class="form-control table_employee_names" name="table_employee_names[]" rows="4" readonly>{{ $emp_name ?? '' }}</textarea>
                        </div>

                        <div class="mb-3 table_individual" style="{{ $individual_display }}">
                            <select class="form-control table_cleaner_id" name="table_cleaner_id[{{$key}}][]" multiple>
                                @if ($cleaner_type == 'individual')
                                    @foreach ($users as $user)
                                        @if (!in_array($user->user_id, $sch_indv_emp[$key]))     
                                            @php
                                                $selected = '';
                                            @endphp
                                            @foreach ($schedule_employee_details as $emp)
                                                @if ($user->user_id == $emp->employee_id)
                                                    @php
                                                        $selected = 'selected';
                                                        break;
                                                    @endphp
                                                @endif
                                            @endforeach
                                
                                            <option value="{{ $user->user_id }}" {{ $selected }}
                                                style="background-color: {{ $user->zone_color }}" data-color="{{ $user->zone_color }}">{{ $user->full_name . " (" . $user->zipcode .")" }}
                                            </option>
                                        @endif
                                    @endforeach
                                @else
                                    @foreach ($users as $user)
                                        @if (!in_array($user->user_id, $sch_indv_emp[$key]))              
                                            <option value="{{ $user->user_id }}"
                                                style="background-color: {{ $user->zone_color }}" data-color="{{ $user->zone_color }}">{{ $user->full_name . " (" . $user->zipcode .")" }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endif

                            </select>
                        </div>

                        <div class="mb-3 table_superviser" style="{{$individual_display}}">
                            <label>Supervisor</label>
                            <select name="table_superviser_emp_id[]" class="form-select select2 table_superviser_emp_id" style="width: 100%;">
                                {{-- <option value="">Select</option> --}}
                                @if (!empty($Schedule_details->superviser_emp_id))
                                    @foreach ($users as $user)
                                        @if (in_array($user->user_id, $schedule_employee_arr))
                                            @if (($Schedule_details->superviser_emp_id ?? '') == $user->user_id)
                                                <option value="{{ $user->user_id }}" {{ ($Schedule_details->superviser_emp_id ?? '') == $user->user_id ? 'selected' : '' }}>{{ $user->full_name . " (" . $user->zipcode .")" }}</option>                             
                                            @else
                                                <option value="{{ $user->user_id }}">{{ $user->full_name . " (" . $user->zipcode .")" }}</option>                             
                                            @endif                                 
                                        @endif                                                                  
                                    @endforeach
                                @else
                                    <option value="">Select</option>
                                @endif                              
                            </select>
                        </div>

                        <div class="mb-3 table_driver" style="{{$driver_display ?? ''}}">
                            <label>Driver</label>
                            <select name="table_driver_emp_id[]" class="form-select select2 table_driver_emp_id" style="width: 100%;">
                                <option value="">Select</option>
                                @foreach ($users as $user)         
                                    @if (!in_array($user->user_id, $sch_driver_emp[$key]))                                                                                                                                                                                            
                                        @if (($Schedule_details->driver_emp_id ?? '') == $user->user_id)
                                            <option value="{{ $user->user_id }}" {{ ($Schedule_details->driver_emp_id ?? '') == $user->user_id ? 'selected' : '' }} style="background-color: {{ $user->zone_color }}" data-color="{{ $user->zone_color }}">{{ $user->full_name . " (" . $user->zipcode .")" }}</option>                             
                                        @else
                                            <option value="{{ $user->user_id }}" style="background-color: {{ $user->zone_color }}" data-color="{{ $user->zone_color }}">{{ $user->full_name . " (" . $user->zipcode .")" }}</option>                             
                                        @endif 
                                    @else
                                        <option value="{{ $user->user_id }}" disabled style="background-color: {{ $user->zone_color }}" data-color="{{ $user->zone_color }}">{{ $user->full_name . " (" . $user->zipcode .")" }}</option>                                                        
                                    @endif  
                                @endforeach
                            </select>
                        </div>                    

                    </td>
                    <td style="width: 15%;">
                        <input type="number" class="form-control table_pay_amount" name="table_pay_amount[]" min="0" step="0.01" value="{{ (($Schedule_details->pay_amount ?? 0) != 0 && ($Schedule_details->pay_amount ?? 0) != "" && ($Schedule_details->pay_amount ?? 0) != null) ? ($Schedule_details->pay_amount ?? 0) : '' }}" {{$readonly ?? ''}}>    
                    </td>
                    <td style="width: 15%;">
                        <div class="mb-3">
                            <textarea class="form-control table_remarks" name="table_remarks[]" cols="30" rows="3" {{$readonly ?? ''}}>{{$Schedule_details->remarks ?? ''}}</textarea>
                        </div>

                        @if (!empty($Schedule_details))
                            @if ($Schedule_details->job_status == 0)
                                <div class="mb-3">
                                    <button type="button" class="btn btn-orange btn-sm table_cancel_btn" data-schedule_details_id="{{$Schedule_details->id}}">Cancel</button>
                                    <button type="button" class="btn btn-blue btn-sm table_complete_btn" data-schedule_details_id="{{$Schedule_details->id}}">Complete</button>
                                </div>
                            @elseif($Schedule_details->job_status == 3)
                                <div class="mb-3">
                                    <span class="badge bg-danger">Cancelled</span>
                                    <button type="button" class="btn btn-yellow btn-sm table_reset_btn" data-schedule_details_id="{{$Schedule_details->id}}" data-action_type="cancel_reset">Reset</button>
                                </div>
                            @elseif($Schedule_details->job_status == 2)
                                <div class="mb-3">
                                    <span class="badge bg-success">Completed</span>

                                    @if ($Schedule_details->manually_completed == 1)
                                        <button type="button" class="btn btn-yellow btn-sm table_reset_btn" data-schedule_details_id="{{$Schedule_details->id}}" data-action_type="complete_reset">Reset</button>
                                    @endif
                                </div>
                            @elseif($Schedule_details->job_status == 1)
                                <div class="mb-3">
                                    <span class="badge bg-warning">Work In Progress</span>
                                </div>
                            @endif                 
                        @endif   
                       
                        <div class="mb-3 table_delivery_remarks_group" style="{{$driver_display ?? ''}} {{($cleaner_type == 'team' ? 'margin-top: 60px;' : 'margin-top: 0px;')}}">
                            <label for="">Delivery Remarks</label>
                            <textarea class="form-control table_delivery_remarks" name="table_delivery_remarks[]" cols="30" rows="3" {{$readonly ?? ''}}>{{$Schedule_details->delivery_remarks ?? ''}}</textarea>
                        </div>

                        <div>
                            <button type="button" class="btn btn-green btn-sm table_add_driver_btn" style="{{!empty($readonly) ? 'display:none' : ($add_btn_driver_display ?? '')}}">Add Driver</button>
                            <button type="button" class="btn btn-red btn-sm table_remove_driver_btn" style="{{!empty($readonly) ? 'display:none' : ($driver_display ?? '')}}">Remove Driver</button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"></td>
                <td class="text-end">Balance Amount:</td>
                <td>
                    <input type="number" class="form-control total_balance_amount" name="total_balance_amount" id="edit_total_balance_amount" min="0" step="0.01" readonly>    
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    $(document).ready(function () {

        $('.select2').each(function() { 
            $(this).select2({ 
                dropdownParent: $(this).parent(),
                templateResult: formatOption
            });
        });    
        
        table_cleaner_id_select2(parseInt($("#edit_set_details_table").parents('form').find('.man_power').val()));
          
        $(".man_power").on('blur', function(){
            $(this).parents('form').find('.table_cleaner_id').select2('destroy');

            table_cleaner_id_select2(parseInt($(this).val()));
        });

        // calculate balance amount after input payable abmount start

        $('.table_pay_amount').on('blur', function () {

            var el = $(this);
            calculate_balance_amount(el);

        });

        // calculate balance amount after input payable abmount end

        // cancel schedule details / job

        $('.table_cancel_btn').on('click', function () {

            var schedule_details_id = $(this).data('schedule_details_id');

            $.ajax({
                type: "post",
                url: "{{route('schedule.cancel-job')}}",
                data: {schedule_details_id: schedule_details_id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    console.log(result);

                    if(result.status == 'success')
                    {                       
                        edit_set_date_details_table([]);

                        iziToast.success({
                            message: result.message,
                            position: 'topRight'
                        });

                        // location.reload();
                    }
                    else
                    {
                        iziToast.error({
                            message: result.message,
                            position: 'topRight'
                        });
                    }     
                },
                error: function (result) {
                    console.log(result);
                }
            });

        });

        // reset schedule details / job

        $('.table_reset_btn').on('click', function () {

            var schedule_details_id = $(this).data('schedule_details_id');
            var action_type = $(this).data('action_type');

            $.ajax({
                type: "post",
                url: "{{route('schedule.reset-job')}}",
                data: {schedule_details_id: schedule_details_id, action_type: action_type},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    console.log(result);

                    if(result.status == 'success')
                    {                       
                        edit_set_date_details_table([]);

                        iziToast.success({
                            message: result.message,
                            position: 'topRight'
                        });

                        // location.reload();
                    }
                    else
                    {
                        iziToast.error({
                            message: result.message,
                            position: 'topRight'
                        });
                    }     
                },
                error: function (result) {
                    console.log(result);
                }
            });

        });

        // complete schedule details / job

        $('.table_complete_btn').on('click', function () {

            var schedule_details_id = $(this).data('schedule_details_id');

            $.ajax({
                type: "post",
                url: "{{route('schedule.complete-job')}}",
                data: {schedule_details_id: schedule_details_id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    console.log(result);

                    if(result.status == 'success')
                    {                       
                        edit_set_date_details_table([]);

                        iziToast.success({
                            message: result.message,
                            position: 'topRight'
                        });

                        // location.reload();
                    }
                    else
                    {
                        iziToast.error({
                            message: result.message,
                            position: 'topRight'
                        });
                    }     
                },
                error: function (result) {
                    console.log(result);
                }
            });

        });

    });

    function table_cleaner_id_select2(man_power)
    {
        if(man_power)
        {
            $('.table_cleaner_id').each(function() { 
                $(this).select2({ 
                    dropdownParent: $(this).parent(),
                    templateResult: formatOption,
                    maximumSelectionLength: man_power,
                });
            });    
        }
        else
        {
            $('.table_cleaner_id').each(function() { 
                $(this).select2({ 
                    dropdownParent: $(this).parent(),
                    templateResult: formatOption,
                });
            });    
        }      
    }

    function formatOption(option) {
        if (!option.id) {
            return option.text;
        }

        var $option = $(
            '<div style="width: 100%; background-color: ' + $(option.element).data('color') + ';">' + option.text + '</div>'
        );

        return $option;
    }

    // sort the date

    function sortTable(n) 
    {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("edit_set_details_table");
        switching = true;
        // Set the sorting direction to ascending:
        dir = "asc";
        /* Make a loop that will continue until
        no switching has been done: */
        while (switching) {
            // Start by saying: no switching is done:
            switching = false;
            rows = table.rows;
            /* Loop through all table rows (except the
            first, which contains table headers): */
            for (i = 1; i < (rows.length - 2); i++) {
                // Start by saying there should be no switching:
                shouldSwitch = false;
                /* Get the two elements you want to compare,
                one from current row and one from the next: */
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                /* Check if the two rows should switch place,
                based on the direction, asc or desc: */
                if (dir == "asc") {
                    if (x.children[0].value.toLowerCase() > y.children[0].value.toLowerCase()) {
                        // If so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (x.children[0].value.toLowerCase() < y.children[0].value.toLowerCase()) {
                        // If so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                /* If a switch has been marked, make the switch
                and mark that a switch has been done: */
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                // Each time a switch is done, increase this count by 1:
                switchcount++;
            } else {
                /* If no switching has been done AND the direction is "asc",
                set the direction to "desc" and run the while loop again. */
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }

    function calculate_balance_amount(el)
    {
        var table_selector = el.parents('.schedule_set_details_table');
        var total_pay_amount = parseFloat(el.parents('form').find('.total_pay_amount').val() || 0);

        var total_table_pay_amount = 0;
        
        $(table_selector.find('tbody tr')).each(function (index, element) {
            
            var table_pay_amount = parseFloat($(this).find('.table_pay_amount').val() || 0);

            total_table_pay_amount += table_pay_amount;
            
        });

        var total_balance_amount = parseFloat(total_pay_amount) - parseFloat(total_table_pay_amount);

        el.parents('form').find('.total_balance_amount').val(parseFloat(total_balance_amount).toFixed(2));
    }

    calculate_balance_amount($('#edit_set_details_table').find('.table_pay_amount'));

</script>
