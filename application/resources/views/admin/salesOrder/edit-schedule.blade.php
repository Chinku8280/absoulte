<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Update Cleaner Schedule</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form action="{{ route('cleaner.update', $cleaner_data->id) }}" method="POST" id="edit_cleaner_form" name="edit_cleaner_form">
        @csrf
        <input type="hidden" class="hidden_schedule_flag" name="hidden_schedule_flag" id="hidden_schedule_flag" value="edit">
        <input type="hidden" class="sales_order_no" name="sales_order_no" id="edit_sales_order_no" value="{{ $cleaner_data->sales_order_no }}">
        <input type="hidden" class="sales_order_id" name="sales_order_id" id="edit_sales_order_id" value="{{ $cleaner_data->sales_order_id }}">
        <input type="hidden" name="customer_id" id="edit_customer_id" value="{{ $cleaner_data->customer_id }}">

        <input type="hidden" name="db_get_hour" id="edit_db_get_hour" class="db_get_hour" value="{{$cleaner_data->hour_session}}">
        <input type="hidden" name="db_sch_dates" id="edit_db_sch_dates" value="{{$cleaner_data->days}}">

        <div class="mb-3">
            <label for="address">Invoice No:</label>
            <input type="text" class="form-control" id="edit_invoice_no" name="invoice_no" value="{{$cleaner_data->invoice_no ?? ''}}" readonly>
        </div>

        <div class="mb-3">
            <div class="form-check form-check-inline">
                <input class="form-check-input cleaner_type" type="radio" name="cleaner_type" id="team"
                    value="team" required {{ $cleaner_data->cleaner_type == 'team' ? 'checked' : '' }}>
                <label class="form-check-label" for="team">Team</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input cleaner_type" type="radio" name="cleaner_type" id="individual"
                    value="individual" required {{ $cleaner_data->cleaner_type == 'individual' ? 'checked' : '' }}>
                <label class="form-check-label" for="individual">Individual</label>
            </div>
        </div>

        {{-- <div class="mb-3 team" style="display: {{ $cleaner_data->cleaner_type == 'team' ? '' : 'none' }}">
            <select class="form-control" id="team_id" name="team_id">
                <option value="" disabled selected>Select Team</option>
                @foreach ($get_team as $team)
                    <option value="{{ $team->team_id }}"
                        {{ $cleaner_data->employee_id == $team->team_id ? 'selected' : '' }}>{{ $team->team_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 employee" style="display: none;">
            <label for="employee_name">Employee List:</label>
            <textarea class="form-control" id="employee_names" name="employee_names" rows="4" readonly></textarea>
        </div>

        <div class="mb-3 individual" style="display: {{ $cleaner_data->cleaner_type == 'individual' ? '' : 'none' }}">
            <select class="form-control" id="cleaner_id" name="cleaner_id">
                <option value="">Select Cleaner</option>
                @foreach ($users as $user)
                    <option value="{{ $user->user_id }}"
                        {{ $cleaner_data->employee_id == $user->user_id ? 'selected' : '' }}
                        style="background-color: {{ $user->zone_color }}">{{ $user->full_name }}</option>
                @endforeach
            </select>
        </div> --}}

        <div class="row mb-3">
            <!-- Start Date and End Date in the same row -->
            <div class="col-md-6">
                <label for="startDate">Start Date:</label>
                <input type="date" class="form-control startDate" id="edit_startDate" name="startDate"
                    value="{{ $cleaner_data->startDate }}" required>
            </div>
            <div class="col-md-6">
                <label for ="endDate">End Date:</label>
                <input type="date" class="form-control endDate" id="edit_endDate" value="{{ $cleaner_data->endDate }}"
                    name="endDate" required>
            </div>
        </div>

        <div class="row mb-3">
            <!-- Postal Code and Unit No in the same row -->
            <div class="col-md-6">
                <label for="postalCode">Postal Code:</label>
                <input type="text" class="form-control" id="edit_postalCode" name="postalCode"
                    value="{{ $cleaner_data->postalCode }}" required>
            </div>
            <div class="col-md-6">
                <label for="unitNo">Unit No:</label>
                <input type="text" class="form-control" id="edit_unitNo" value="{{ $cleaner_data->unitNo }}"
                    name="unitNo">
            </div>
        </div>

        <div class="mb-3">
            <label for="address">Address:</label>
            <input type="text" class="form-control" id="edit_address" name="address"
                value="{{ $cleaner_data->address }}" required>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="total_session">Total Session:</label>
                <input type="text" class="form-control total_session" id="edit_total_session" name="total_session"
                    value="{{ $cleaner_data->total_session }}">
                <input type="hidden" id="get_hour" name="get_hour">
            </div>
            <div class="col-md-6">
                <label for="frequency">Weekly Freq:</label>
                <input type="text" class="form-control weekly_freq" id="edit_weekly_freq" name="weekly_freq"
                    value="{{ $cleaner_data->weekly_freq }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <label for="edit_man_power">Man Power Required:</label>
                <input type="text" class="form-control man_power" id="edit_man_power" name="man_power" value="{{ $cleaner_data->man_power }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="startTime">Start Time:</label>
                <input type="time" class="form-control startTime" id="edit_startTime" name="startTime"
                    value="{{ $cleaner_data->startTime }}" required>
            </div>
            <div class="col-md-6">
                <label for="endTime">End Time:</label>
                <input type="time" class="form-control endTime" id="edit_endTime" name="endTime"
                    value="{{ $cleaner_data->endTime }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="edit_invoice_amount">Total Invoice Amount:</label>
                <input type="number" class="form-control" id="edit_invoice_amount" name="invoice_amount" step="0.01" min="0" value="{{ $cleaner_data->invoice_amount }}" required>
            </div>

            <div class="col-md-6">
                <label for="edit_total_pay_amount">Total Payable Amount:</label>
                <input type="number" class="form-control total_pay_amount" id="edit_total_pay_amount" name="total_pay_amount" step="0.01" min="0" value="{{ $cleaner_data->balance_amount }}" required>
            </div>
        </div>

        <div class="mb-3">
            <div class="form-check form-check-inline">
                <input class="form-check-input edit_dayslist" type="checkbox" id="dayMonday" name="days[]"
                    value="Monday" {{ in_array('Monday', $cleaner_data->selected_days) ? 'checked' : '' }}>
                <label class="form-check-label" for="dayMonday">Mon</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input edit_dayslist" type="checkbox" id="dayTuesday" name="days[]"
                    value="Tuesday" {{ in_array('Tuesday', $cleaner_data->selected_days) ? 'checked' : '' }}>
                <label class="form-check-label" for="dayTuesday">Tue</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input edit_dayslist" type="checkbox" id="dayWednesday" name="days[]"
                    value="Wednesday" {{ in_array('Wednesday', $cleaner_data->selected_days) ? 'checked' : '' }}>
                <label class="form-check-label" for="dayWednesday">Wed</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input edit_dayslist" type="checkbox" id="dayThursday" name="days[]"
                    value="Thursday" {{ in_array('Thursday', $cleaner_data->selected_days) ? 'checked' : '' }}>
                <label class="form-check-label" for="dayThursday">Thu</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input edit_dayslist" type="checkbox" id="dayFriday" name="days[]"
                    value="Friday" {{ in_array('Friday', $cleaner_data->selected_days) ? 'checked' : '' }}>
                <label class="form-check-label" for="dayFriday">Fri</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input edit_dayslist" type="checkbox" id="daySaturday" name="days[]"
                    value="Saturday" {{ in_array('Saturday', $cleaner_data->selected_days) ? 'checked' : '' }}>
                <label class="form-check-label" for="daySaturday">Sat</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input edit_dayslist" type="checkbox" id="daySunday" name="days[]"
                    value="Sunday" {{ in_array('Sunday', $cleaner_data->selected_days) ? 'checked' : '' }}>
                <label class="form-check-label" for="daySunday">Sun</label>
            </div>
        </div>

        <div class="calender" style="display: none;">
            <div class="row">
                <div class="col-md-12">
                    <input type="text" name="datepick" id="edit_datePick" class="form-control datePick"
                        value="{{ $cleaner_data->days }}" />
                </div>
            </div>
        </div>

        <br>

        <div class="row" id="edit_set_details_row_group" style="display: none;">
            
        </div>

        <div class="mb-3">
            <label for="addrcustomer_remarkess">Remark:</label>
            <input type="text" class="form-control" id="edit_remarks" name="edit_remarks"
                value="{{ $cleaner_data->remarks }}">
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="updateButton">Update</button>
        </div>
    </form>
</div>

<script>

    $(document).ready(function() {

        $('.edit_dayslist').on('click', function() {
            edit_updateCheckboxState();
        });

        $('#edit_weekly_freq').on('input', function() {
            edit_updateCheckboxState();
        });

    });

    function edit_updateCheckboxState() {

        var frequency = parseInt($('.weekly_freq').val());
        // console.log(frequency);

        var checkedCheckboxes = $('.edit_dayslist:checked');

        if (checkedCheckboxes.length >= frequency) 
        {
            $('.edit_dayslist:not(:checked)').prop('disabled', true);
        } 
        else 
        {
            $('.edit_dayslist').prop('disabled', false);
        }

        edit_setEndDates();
    }

    function edit_setEndDates()
    {
        console.log("object");
        if($('#edit_startDate').val() && $('#edit_startTime').val() && $('#edit_endTime').val())
        {
            var startDate = new Date($('.startDate').val());
            var totalSession = parseInt($('.total_session').val());

            var endDates = [];
            var checkedCheckboxes = [];

            $('.edit_dayslist:checked').each(function() {
                var checkbox = $(this);
                checkedCheckboxes.push(checkbox.val());
            });

            for (var i = 0; i < totalSession; i++) {
                for (var j = 0; j < checkedCheckboxes.length; j++) {
                    var currentDay = edit_getDayNumber(checkedCheckboxes[j]);
                    var currentDate = new Date(startDate.getTime());


                    currentDate.setDate(currentDate.getDate() + (7 * i));

                    while (currentDate.getDay() !== currentDay) {
                        currentDate.setDate(currentDate.getDate() + 1);
                    }


                    if (currentDate >= startDate) {
                        endDates.push(currentDate);
                    }


                    if (endDates.length >= totalSession) {
                        break;
                    }
                }


                if (endDates.length >= totalSession) {
                    break;
                }
            }

            endDates.sort(function(a, b) {
                return a - b;
            });

            if(endDates.length > 0)
            {
                $('.datePick').val(endDates.map(date => edit_formatDateForDisplay(date)).join(', '));


                var lastDate = endDates[endDates.length - 1];
                $('.endDate').val(edit_formatDateForInput(lastDate));
            }

            edit_set_date_details_table(endDates);
        }
        else
        {
            iziToast.error({
                message: 'Select Start Date or Time',
                position: 'topRight'
            });
        }
    }


    function edit_getDayNumber(dayName) {
        var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday',
            'Saturday'
        ];
        return days.indexOf(dayName);
    }


    function edit_formatDateForDisplay(date) {
        var month = (date.getMonth() + 1).toString().padStart(2, '0');
        var day = date.getDate().toString().padStart(2, '0');
        var year = date.getFullYear();
        return day + '/' + month + '/' + year;
    }


    function edit_formatDateForInput(date) {
        var month = (date.getMonth() + 1).toString().padStart(2, '0');
        var day = date.getDate().toString().padStart(2, '0');
        var year = date.getFullYear();
        return year + '-' + month + '-' + day;
    }

    function setEndTime(startTime, get_hour)
    {
        var startTime = new Date('1970-01-01T' + startTime);
        var getHourInSeconds = get_hour * 60 * 60;
        var endTime = new Date(startTime.getTime() + getHourInSeconds * 1000);

        var formattedEndTime = endTime.toTimeString().substring(0, 5);

        return formattedEndTime;
    }

    // set schedule date details in table

    function edit_set_date_details_table(endDates)
    {
        // console.log(endDates);

        if(endDates.length > 0)
        {
            $("#edit_set_details_table").html("");

            var sch_date = [];

            $.each(endDates, function (key, value) { 
                sch_date.push(edit_formatDateForInput(value));               
            });

            var sales_order_id = $("#edit_sales_order_id").val();
            var sales_order_no = $("#edit_sales_order_no").val();
            var cleaner_type = $('#edit_cleaner_form input[name="cleaner_type"]:checked').val();
            var startTime = $('#edit_startTime').val();
            var endTime = $('#edit_endTime').val();

            $.ajax({
                type: "get",
                url: "{{route('sales-order.edit-schedule-date-table-details')}}",
                data: {
                    sch_date: sch_date,
                    sales_order_id: sales_order_id,
                    sales_order_no: sales_order_no,
                    cleaner_type: cleaner_type,
                    startTime: startTime,
                    endTime: endTime
                },
                success: function (result) {
                    // console.log(result);

                    $("#edit_set_details_row_group").html(result);                    
                },
                error: function (result) {
                    console.log(result);
                }
            });

            $("#edit_set_details_row_group").show();
        }
        else
        {
            var temp_sch_date = $("#edit_db_sch_dates").val();
            var sales_order_id = $("#edit_sales_order_id").val();
            var sales_order_no = $("#edit_sales_order_no").val();
            var cleaner_type = $('#edit_cleaner_form input[name="cleaner_type"]:checked').val();
            var startTime = $('#edit_startTime').val();
            var endTime = $('#edit_endTime').val();

            var sch_date = [];

            sch_date = temp_sch_date.split(", ");

            $.ajax({
                type: "get",
                url: "{{route('sales-order.edit-schedule-date-table-details')}}",
                data: {
                    sch_date: sch_date,
                    sales_order_id: sales_order_id,
                    sales_order_no: sales_order_no,
                    cleaner_type: cleaner_type,
                    startTime: startTime,
                    endTime: endTime
                },
                success: function (result) {
                    // console.log(result);

                    $("#edit_set_details_row_group").html(result);
                },
                error: function (result) {
                    console.log(result);
                }
            });

            $("#edit_set_details_row_group").show();
        }
    }

    edit_set_date_details_table([]);

</script>
