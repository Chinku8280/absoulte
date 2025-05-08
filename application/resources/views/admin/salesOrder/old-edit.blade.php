<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Update Cleaner Schedule</h5>
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form action="{{ route('cleaner.update', $cleaner_data->id) }}" method="POST" id="" name="">
        @csrf
        <input type="hidden" name="sales_order_no" value="{{ $cleaner_data->sales_order_no }}">
        <input type="hidden" name="customer_id" id="customer_id" value="{{ $cleaner_data->customer_id }}">
        <div class="form-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input cleaner_type" type="radio" name="cleaner_type" id="team"
                    value="team" required checked>
                <label class="form-check-label" for="team">Team</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input cleaner_type" type="radio" name="cleaner_type" id="individual"
                    value="individual" required>
                <label class="form-check-label" for="individual">Individual</label>
            </div>
        </div>
        {{-- <div class="form-group team">
            <select class="form-control" id="team_id" name="team_id">
                <option value="">Select Team</option>
                @foreach ($get_team as $team)
                    <option value="{{ $team->team_name }}" {{ $team->team_name ? 'selected' : '' }}>
                        {{ $team->team_name }}</option>
                @endforeach
            </select>
        </div> --}}
        <div class="form-group team">
            <select class="form-control" id="team_id" name="team_id">
                <option value="" disabled selected>Select Team</option>
                @foreach ($get_team as $team)
                    <option value="{{ $team->team_id }}">{{ $team->team_name }}</option>
                    {{-- @if (!empty($employeeNames[$team->team_id]))
                        @foreach ($employeeNames[$team->team_id] as $employeeName)
                            <option value="{{ $team->team_id . '_' . $employeeName }}" disabled> - {{ $employeeName }}</option>
                        @endforeach
                    @endif --}}
                @endforeach
            </select>
        </div>
        <div class="form-group employee" style="display: none;">
            <label for="employee_name">Employee List:</label>
            <textarea class="form-control" id="employee_names" name="employee_names" rows="4" readonly></textarea>
        </div>
        <div class="form-group individual">
            <select class="form-control" id="cleaner_id" name="cleaner_id">
                <option value="">Select Cleaner</option>
                @foreach ($users as $user)
                    <option value="{{ $user->user_id }}" {{ $user->user_id ? 'selected' : '' }}
                        style="background-color: {{ $user->zone_color }}">{{ $user->full_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-row">
            <!-- Start Date and End Date in the same row -->
            <div class="form-group col-md-6">
                <label for="startDate">Start Date:</label>
                <input type="date" class="form-control startDate" id="startDate" name="startDate"
                    value="{{ $cleaner_data->startDate }}" required>
            </div>
            <div class="form-group col-md-6">
                <label for ="endDate">End Date:</label>
                <input type="date" class="form-control endDate" id="endDate" value="{{ $cleaner_data->endDate }}"
                    name="endDate" required>
            </div>
        </div>
        <div class="form-row">
            <!-- Postal Code and Unit No in the same row -->
            <div class="form-group col-md-6">
                <label for="postalCode">Postal Code:</label>
                <input type="text" class="form-control" id="postalCode" name="postalCode"
                    value="{{ $cleaner_data->postalCode }}" required>
            </div>
            <div class="form-group col-md-6">
                <label for="unitNo">Unit No:</label>
                <input type="text" class="form-control" id="unitNo" value="{{ $cleaner_data->unitNo }}"
                    name="unitNo">
            </div>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" class="form-control" id="address" name="address"
                value="{{ $cleaner_data->address }}" required>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="total_session">Total Session:</label>
                <input type="text" class="form-control total_session" id="total_session" name="total_session"
                    value="{{ $cleaner_data->total_session }}">
                <input type="hidden" id="get_hour" name="get_hour">
            </div>
            <div class="form-group col-md-6">
                <label for="frequency">Weekly Freq:</label>
                <input type="text" class="form-control weekly_freq" id="weekly_freq" name="weekly_freq"
                    value="{{ $cleaner_data->weekly_freq }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="startTime">Start Time:</label>
                <input type="time" class="form-control" id="startTime" name="startTime"
                    value="{{ $cleaner_data->startTime }}" required>
            </div>
            <div class="form-group col-md-6">
                <label for="endTime">End Time:</label>
                <input type="time" class="form-control" id="endTime" name="endTime"
                    value="{{ $cleaner_data->endTime }}" required>
            </div>
        </div>
        <div class="form-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input dayslist" type="checkbox" id="dayMonday" name="days[]"
                    value="Monday" {{ in_array('Monday', $cleaner_data->selected_days) ? 'checked' : '' }}>
                <label class="form-check-label" for="dayMonday">Mon</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input dayslist" type="checkbox" id="dayTuesday" name="days[]"
                    value="Tuesday" {{ in_array('Tuesday', $cleaner_data->selected_days) ? 'checked' : '' }}>
                <label class="form-check-label" for="dayTuesday">Tue</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input dayslist" type="checkbox" id="dayWednesday" name="days[]"
                    value="Wednesday" {{ in_array('Wednesday', $cleaner_data->selected_days) ? 'checked' : '' }}>
                <label class="form-check-label" for="dayWednesday">Wed</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input dayslist" type="checkbox" id="dayThursday" name="days[]"
                    value="Thursday" {{ in_array('Thursday', $cleaner_data->selected_days) ? 'checked' : '' }}>
                <label class="form-check-label" for="dayThursday">Thu</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input dayslist" type="checkbox" id="dayFriday" name="days[]"
                    value="Friday" {{ in_array('Friday', $cleaner_data->selected_days) ? 'checked' : '' }}>
                <label class="form-check-label" for="dayFriday">Fri</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input dayslist" type="checkbox" id="daySaturday" name="days[]"
                    value="Saturday" {{ in_array('Saturday', $cleaner_data->selected_days) ? 'checked' : '' }}>
                <label class="form-check-label" for="daySaturday">Sat</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input dayslist" type="checkbox" id="daySunday" name="days[]"
                    value="Sunday" {{ in_array('Sunday', $cleaner_data->selected_days) ? 'checked' : '' }}>
                <label class="form-check-label" for="daySunday">Sun</label>
            </div>
        </div>
        <div class="calender">
            <div class="row">
                <div class="col-md-12">
                    <input type="text" name="datepick" id="datePick" class="form-control datePick"
                        value="{{ $cleaner_data->days }}" />
                </div>
            </div>
        </div><br>
        <div class="form-group">
            <label for="addrcustomer_remarkess">Remark:</label>
            <input type="text" class="form-control" id="customer_remark" name="customer_remark"
                value="{{ $cleaner_data->customer_remark }}">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="saveButton">Update</button>
        </div>
    </form>
</div>
<script>
  window.addEventListener('load', function() {
        var employeeTextarea = document.getElementById('employee_names');
        var employeeFormGroup = document.querySelector('.employee');

        function updateEmployeeNames() {
            if (document.querySelector('.cleaner_type:checked').value === 'team') {
                // Show the employee textarea if 'team' is selected
                employeeFormGroup.style.display = 'block';

                // Get the selected team id
                var selectedTeamId = document.getElementById('team_id').value;

                // Get the corresponding employee names for the selected team from PHP (you need to pass this data to JavaScript)
                var employeeNames = <?php echo json_encode($employeeNames); ?>;

                // Populate the textarea with employee names based on the selected team
                employeeTextarea.value = '';

                if (employeeNames[selectedTeamId]) {
                    employeeNames[selectedTeamId].forEach(function(employeeName) {
                        employeeTextarea.value += employeeName + '\n';
                    });
                }
            } else {
                // Hide the employee textarea if 'individual' is selected
                employeeFormGroup.style.display = 'none';
            }
        }

        // Initial update when the page loads
        updateEmployeeNames();

        document.getElementById('team_id').addEventListener('change', updateEmployeeNames);

        // Add an event listener to the radio buttons
        var cleanerTypeRadioButtons = document.querySelectorAll('.cleaner_type');
        cleanerTypeRadioButtons.forEach(function(radioButton) {
            radioButton.addEventListener('change', updateEmployeeNames);
        });
    });

    $(document).ready(function() {

        $('#edit-cleaner').on('shown.bs.modal', function() {


            $(".individual").hide();

            $(".cleaner_type").click(function() {

                if ($(this).val() == "team") {
                    $(".individual").hide();

                    $(".team").show();

                } else {
                    $(".team").hide();

                    $(".individual").show();

                }
            });


            $('.dayslist').on('click', function() {

                updateCheckboxState();
            });

            $('#weekly_freq').on('input', function() {
                updateCheckboxState();
            });

            function updateCheckboxState() {

                var frequency = parseInt($('.weekly_freq').val());
                //  console.log(frequency);

                var checkedCheckboxes = $('.dayslist:checked');

                if (checkedCheckboxes.length >= frequency) {

                    $('.dayslist:not(:checked)').prop('disabled', true);
                } else {

                    $('.dayslist').prop('disabled', false);
                }

                setEndDates();
            }

            function setEndDates() {

                var startDate = new Date($('.startDate').val());
                var totalSession = parseInt($('.total_session').val());

                var endDates = [];
                var checkedCheckboxes = [];

                $('.dayslist:checked').each(function() {
                    var checkbox = $(this);
                    checkedCheckboxes.push(checkbox.val());
                });

                for (var i = 0; i < totalSession; i++) {
                    for (var j = 0; j < checkedCheckboxes.length; j++) {
                        var currentDay = getDayNumber(checkedCheckboxes[j]);
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


                $('.datePick').val(endDates.map(date => formatDateForDisplay(date)).join(', '));


                var lastDate = endDates[endDates.length - 1];
                $('.endDate').val(formatDateForInput(lastDate));
            }


            function getDayNumber(dayName) {
                var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday',
                    'Saturday'];
                return days.indexOf(dayName);
            }


            function formatDateForDisplay(date) {
                var month = (date.getMonth() + 1).toString().padStart(2, '0');
                var day = date.getDate().toString().padStart(2, '0');
                var year = date.getFullYear();
                return day + '/' + month + '/' + year;
            }


            function formatDateForInput(date) {
                var month = (date.getMonth() + 1).toString().padStart(2, '0');
                var day = date.getDate().toString().padStart(2, '0');
                var year = date.getFullYear();
                return year + '-' + month + '-' + day;
            }




        });
    });
</script>
