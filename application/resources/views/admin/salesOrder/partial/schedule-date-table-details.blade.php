<div class="col-md-12">
    <table class="table schedule_set_details_table" id="set_details_table">
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
                <tr>                   
                    <td style="width: 15%;">
                        <input type="date" class="form-control table_schedule_date" name="table_schedule_date[]" value="{{$item}}">
                    
                        <div class="table_delivery_date_group" style="display: none;">
                            <label for="">Delivery Date</label>
                            <input type="date" class="form-control table_delivery_date" name="table_delivery_date[]">
                        </div>                   
                    </td>
                    <td style="width: 15%;">
                        <input type="time" class="form-control table_startTime" name="table_startTime[]" value="{{$startTime}}">   
                        
                        <div class="table_delivery_time_group" style="display: none;">
                            <label for="">Delivery Time</label>
                            <input type="time" class="form-control table_delivery_time" name="table_delivery_time[]" value="08:00">
                        </div> 
                    </td>
                    <td style="width: 15%;">
                        <input type="time" class="form-control table_endTime" name="table_endTime[]" value="{{$endTime}}">    
                    </td>
                    <td style="width: 25%;">
                                             
                        @php
                            if($cleaner_type == "team")
                            {
                                $team_display = "";
                                $individual_display = "display: none";
                            }
                            else
                            {
                                $team_display = "display: none";
                                $individual_display = "";
                            }
                        @endphp

                        <div class="mb-3 table_team" style="{{$team_display}}">
                            <select class="form-select select2 table_team_id" name="table_team_id[]" id="team_employee_{{$key}}">
                                <option value="">Select Team</option>
                                @foreach ($get_team as $team)
                                    @if (!in_array($team->team_id, $sch_team_emp[$key]))      
                                        <option value="{{ $team->team_id }}">{{ $team->team_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 table_employee" style="{{$team_display}}">
                            <label>Employee List:</label>
                            <textarea class="form-control table_employee_names" name="table_employee_names[]" rows="4" readonly></textarea>
                        </div>

                        <div class="mb-3 table_individual" style="{{$individual_display}}">
                            <select class="form-select table_cleaner_id" name="table_cleaner_id[{{$key}}][]" id="individual_employee_{{$key}}" multiple>
                                {{-- <option value="">Select</option> --}}
                                @foreach ($users as $user)
                                    @if (!in_array($user->user_id, $sch_indv_emp[$key]))                                                                          
                                        <option value="{{ $user->user_id }}" style="background-color: {{ $user->zone_color }}" data-color="{{ $user->zone_color }}">{{ $user->full_name . " (" . $user->zipcode .")" }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 table_superviser" style="{{$individual_display}}">
                            <label>Supervisor</label>
                            <select name="table_superviser_emp_id[]" class="form-select select2 table_superviser_emp_id" style="width: 100%;">
                                <option value="">Select</option>
                            </select>
                        </div>

                        <div class="mb-3 table_driver" style="display: none;">
                            <label>Driver</label>
                            <select name="table_driver_emp_id[]" class="form-select select2 table_driver_emp_id" style="width: 100%;">
                                <option value="">Select</option>
                                @foreach ($users as $user)                                                                                                         
                                    <option value="{{ $user->user_id }}" style="background-color: {{ $user->zone_color }}" data-color="{{ $user->zone_color }}">{{ $user->full_name . " (" . $user->zipcode .")" }}</option>                    
                                @endforeach
                            </select>
                        </div>

                    </td>
                    <td style="width: 15%;">                      
                        <input type="number" class="form-control table_pay_amount" name="table_pay_amount[]" min="0" step="0.01">    
                    </td>
                    <td style="width: 15%;">
                        <div class="mb-3">
                            <textarea class="form-control table_remarks" name="table_remarks[]" cols="30" rows="3"></textarea>
                        </div>

                        <div class="mb-3 table_delivery_remarks_group" style="display: none;">
                            <label for="">Delivery Remarks</label>
                            <textarea class="form-control table_delivery_remarks" name="table_delivery_remarks[]" cols="30" rows="3"></textarea>
                        </div>

                        <div>
                            <button type="button" class="btn btn-green btn-sm table_add_driver_btn">Add Driver</button>
                            <button type="button" class="btn btn-red btn-sm table_remove_driver_btn" style="display: none;">Remove Driver</button>
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
                    <input type="number" class="form-control total_balance_amount" name="total_balance_amount" id="total_balance_amount" min="0" step="0.01" readonly>    
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    $(document).ready(function () {

        // $('.select2, .table_team_id').select2({
        //     dropdownParent: $("#detailsDialog .modal-content")
        // });

        $('.select2').each(function() { 
            $(this).select2({ 
                dropdownParent: $(this).parent(),
                templateResult: formatOption,
            });
        });      

        table_cleaner_id_select2(parseInt($("#set_details_table").parents('form').find('.man_power').val()));
          
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

    });

    function table_cleaner_id_select2(man_power)
    {
        if(man_power)
        {
            $('.table_cleaner_id').each(function() { 
                $(this).select2({ 
                    dropdownParent: $(this).parent(),
                    templateResult: formatOption,
                    maximumSelectionLength: man_power
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
        table = document.getElementById("set_details_table");
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
   
</script>