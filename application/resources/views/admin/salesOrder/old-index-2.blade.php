@extends('theme.default')

@section('custom_css')
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
    <style>
        #order_table th {
            text-align: center;
        }
    </style>
    {{-- <style>
    .dropdown-menu.show {
    display: block !important;
    position: absolute !important;
    inset: 0px 0px auto auto !important;
    transform: translate(0px, 39px) !important;
    }
    </style> --}}
    {{-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script> --}}
@endsection

@section('content')
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            Sales Order
                        </h2>
                    </div>
                </div>
                <!-- Page body -->
                <div class="page-body">
                    <div class="container-xl">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    {{-- <div class="card-body border-bottom py-3">
                                        <div class="d-flex">
                                            <div class="text-muted">
                                                Show
                                                <div class="mx-2 d-inline-block">
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="8" size="3" aria-label="Invoices count">
                                                </div>
                                                entries
                                            </div>
                                            <div class="ms-auto text-muted">
                                                Search:
                                                <div class="ms-2 d-inline-block">
                                                    <input type="text" class="form-control form-control-sm"
                                                        aria-label="Search invoice">
                                                </div>
                                            </div>

                                        </div>
                                    </div> --}}
                                    <div class="">
                                        <table id="order_table"
                                            class="table card-table table-vcenter text-center text-nowrap datatable">
                                            <thead>
                                                <tr>
                                                    <th class="w-1">Sr No.</th>
                                                    <th>Invoice No.</th>
                                                    <th>Customer Name</th>
                                                    <th>Company Name</th>
                                                    <th>Email</th>
                                                    <th>Contact Number</th>
                                                    <th>Created on</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($salesOrder as $key => $item)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td><span class="text-muted">{{ $item->invoice_no }}</span></td>
                                                        <td>{{ $item->customer_name }}</td>
                                                        <td>{{ $item->individual_company_name }}</td>
                                                        <td>
                                                            {{ $item->email }}
                                                        </td>
                                                        <td>
                                                            {{ $item->mobile_number }}
                                                        </td>
                                                        <td>
                                                            {{ $item->created_at->format('d,M,Y') }}
                                                        </td>
                                                        <td>
                                                            @if ($item->status == 1)
                                                                <span class="badge bg-success">Assigned</span>
                                                            @else
                                                                <span class="badge bg-red">Pending</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-end">
                                                            <div class="dropdown">
                                                                <button class="btn dropdown-toggle align-text-top t-btn"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    Actions
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-end d-menu"
                                                                    style="">
                                                                    <a href="#" class="dropdown-item"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#view-sales-order"
                                                                        onclick="viewSalesOrder({{ $item->id }})">
                                                                        <i class="fa-solid fa-eye me-2 text-blue"></i>
                                                                        View
                                                                    </a>

                                                                    @if ($item->status == 1)
                                                                        <a class="dropdown-item" href="#"
                                                                            onclick="editCleaner('{{ $item->tble_schedule_id }}')">
                                                                            <i
                                                                                class="fa-solid fa-pencil me-2 text-yellow"></i>
                                                                            Edit Cleaners
                                                                        </a>
                                                                    @else
                                                                        <a class="dropdown-item" href="#"
                                                                            data-bs-toggle="modal"
                                                                            data-id="{{ $item->id }}"
                                                                            data-bs-target="#detailsDialog"
                                                                            onclick="assign_cleaner({{ $item->id }})">
                                                                            <i
                                                                                class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                            Assigning Cleaners
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- <div class="card-footer d-flex align-items-center">
                                        <p class="m-0 text-muted">Showing <span>1</span> to <span>8</span> of
                                            <span>16</span>
                                            entries
                                        </p>
                                        <ul class="pagination m-0 ms-auto">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                                    <!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M15 6l-6 6l6 6"></path>
                                                    </svg>
                                                    prev
                                                </a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item active"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item"><a class="page-link" href="#">4</a></li>
                                            <li class="page-item"><a class="page-link" href="#">5</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">
                                                    next
                                                    <!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M9 6l6 6l-6 6"></path>
                                                    </svg>
                                                </a>
                                            </li>
                                        </ul>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="footer footer-transparent d-print-none">
                </footer>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="view-sales-order" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document" id="viewSalesModal">

        </div>
    </div>

    <div class="modal modal-blur fade" id="detailsDialog" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cleaner Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('schedule.create') }}" method="POST" id="detailsForm" name="detailsForm">
                        @csrf
                        <input type="hidden" name="sales_order_no" id="sales_order_no">
                        <input type="hidden" name="customer_id" id="customer_id">

                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input cleaner_type" type="radio" name="cleaner_type"
                                    id="team" value="team" required checked>
                                <label class="form-check-label" for="team">Team</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input cleaner_type" type="radio" name="cleaner_type"
                                    id="individual" value="individual" required>
                                <label class="form-check-label" for="individual">Individual</label>
                            </div>
                        </div>

                        <div class="mb-3 team">
                            <select class="form-control" id="team_id" name="team_id">
                                <option value="" disabled selected>Select Team</option>
                                @foreach ($get_team as $team)
                                    <option value="{{ $team->team_id }}">{{ $team->team_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 employee" style="display: none;">
                            <label for="employee_name">Employee List:</label>
                            <textarea class="form-control" id="employee_names" name="employee_names" rows="4" readonly></textarea>
                        </div>

                        <div class="mb-3 individual">
                            <select class="form-control" id="cleaner_id" name="cleaner_id">
                                @foreach ($users as $user)
                                    <option value="{{ $user->user_id }}" {{ $user->user_id ? 'selected' : '' }}
                                        style="background-color: {{ $user->zone_color }}">{{ $user->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row mb-3">
                            <!-- Start Date and End Date in the same row -->
                            <div class="col-md-6">
                                <label for="startDate">Start Date:</label>
                                <input type="date" class="form-control" id="startDate" name="startDate" required>
                            </div>
                            <div class="col-md-6">
                                <label for ="endDate">End Date:</label>
                                <input type="date" class="form-control" id="endDate" name="endDate" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <!-- Postal Code and Unit No in the same row -->
                            <div class="col-md-6">
                                <label for="postalCode">Postal Code:</label>
                                <input type="text" class="form-control" id="postalCode" name="postalCode" required>
                            </div>
                            <div class="col-md-6">
                                <label for="unitNo">Unit No:</label>
                                <input type="text" class="form-control" id="unitNo" name="unitNo">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address">Address:</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="total_session">Total Session:</label>
                                <input type="text" class="form-control" id="total_session" name="total_session">
                                <input type="hidden" id="get_hour" name="get_hour">
                            </div>
                            <div class="col-md-6">
                                <label for="frequency">Weekly Freq:</label>
                                <input type="text" class="form-control" id="weekly_freq" name="weekly_freq">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="startTime">Start Time:</label>
                                <input type="time" class="form-control" id="startTime" name="startTime" required>
                            </div>
                            <div class="col-md-6">
                                <label for="endTime">End Time:</label>
                                <input type="time" class="form-control" id="endTime" name="endTime" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input dayslist" type="checkbox" id="dayMonday" name="days[]"
                                    value="Monday">
                                <label class="form-check-label" for="dayMonday">Mon</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input dayslist" type="checkbox" id="dayTuesday" name="days[]"
                                    value="Tuesday">
                                <label class="form-check-label" for="dayTuesday">Tue</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input dayslist" type="checkbox" id="dayWednesday"
                                    name="days[]" value="Wednesday">
                                <label class="form-check-label" for="dayWednesday">Wed</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input dayslist" type="checkbox" id="dayThursday" name="days[]"
                                    value="Thursday">
                                <label class="form-check-label" for="dayThursday">Thu</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input dayslist" type="checkbox" id="dayFriday" name="days[]"
                                    value="Friday">
                                <label class="form-check-label" for="dayFriday">Fri</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input dayslist" type="checkbox" id="daySaturday" name="days[]"
                                    value="Saturday">
                                <label class="form-check-label" for="daySaturday">Sat</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input dayslist" type="checkbox" id="daySunday" name="days[]"
                                    value="Sunday">
                                <label class="form-check-label" for="daySunday">Sun</label>
                            </div>
                        </div>

                        <div class="calender">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="text" name="datepick" id="datePick" class="form-control" />
                                </div>
                            </div>
                        </div>
                        
                        <br>

                        <div class="mb-3">
                            <label for="addrcustomer_remarkess">Remark:</label>
                            <input type="text" class="form-control" id="customer_remark" name="customer_remark">
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="saveButton">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="edit-cleaner" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="edit-cleaner-model">

            </div>
        </div>
    </div>
@endsection

@section('javascript')

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-multidatespicker/1.6.6/jquery-ui.multidatespicker.js">
    </script>

    <script>
        document.getElementById('team_id').addEventListener('change', function() {

            var selectedTeamId = this.value;

            // var employeeNames = <?php echo json_encode($employeeNames); ?>;

            var employeeNames = @php echo json_encode($employeeNames); @endphp;

            var employeeTextarea = document.getElementById('employee_names');
            employeeTextarea.value = '';

            if (employeeNames[selectedTeamId]) {
                var temp_employeeNames = employeeNames[selectedTeamId];

                temp_employeeNames.forEach(function(employeeName) {
                    employeeTextarea.value += employeeName + '\n';
                });
            }
        });


        var cleanerTypeRadioButtons = document.querySelectorAll('.cleaner_type');
        cleanerTypeRadioButtons.forEach(function(radioButton) {
            radioButton.addEventListener('change', function() {

                var employeeTextarea = document.getElementById('employee_names');
                var employeeFormGroup = document.querySelector('.employee');

                if (this.value === 'team') {

                    employeeFormGroup.style.display = 'block';
                } else {

                    employeeFormGroup.style.display = 'none';
                }
            });
        });

        // Trigger the change event on the initially checked radio button to ensure the textarea is displayed on modal open
        document.querySelector('.cleaner_type:checked').dispatchEvent(new Event('change'));

        $(document).ready(function() {
            // $('.datatable').DataTable();

            $('#order_table').DataTable({
                "lengthChange": false,
                "pageLength": 30,
            });
        });

        $(document).ready(function() {
            $('.details-link').click(function() {
                var quotationId = $(this).data('quotation-id');

                // Make an AJAX request to fetch the address
                $.ajax({
                    url: '/get-address/' + quotationId, // Update the URL according to your routes
                    type: 'GET',
                    success: function(data) {
                        // Update the address input with the fetched address
                        $('#address').val(data.address);
                    },
                    error: function(error) {
                        console.log('Error fetching address:', error);
                    }
                });
            });
        });

        (function($) {
            var CheckboxDropdown = function(el) {
                var _this = this;
                this.isOpen = false;
                this.areAllChecked = false;
                this.$el = $(el);
                this.$label = this.$el.find('.dropdown-label');
                this.$checkAll = this.$el.find('[data-toggle="check-all"]').first();
                this.$inputs = this.$el.find('[type="checkbox"]');

                this.onCheckBox();

                this.$label.on('click', function(e) {
                    e.preventDefault();
                    _this.toggleOpen();
                });

                this.$checkAll.on('click', function(e) {
                    e.preventDefault();
                    _this.onCheckAll();
                });

                this.$inputs.on('change', function(e) {
                    _this.onCheckBox();
                });
            };

            CheckboxDropdown.prototype.onCheckBox = function() {
                this.updateStatus();
            };

            CheckboxDropdown.prototype.updateStatus = function() {
                var checked = this.$el.find(':checked');

                this.areAllChecked = false;
                this.$checkAll.html('Check All');

                if (checked.length <= 0) {
                    this.$label.html('Select Options');
                } else if (checked.length === 1) {
                    this.$label.html(checked.parent('label').text());
                } else if (checked.length === this.$inputs.length) {
                    this.$label.html('All Selected');
                    this.areAllChecked = true;
                    this.$checkAll.html('Uncheck All');
                } else {
                    this.$label.html(checked.length + ' Selected');
                }
            };

            CheckboxDropdown.prototype.onCheckAll = function(checkAll) {
                if (!this.areAllChecked || checkAll) {
                    this.areAllChecked = true;
                    this.$checkAll.html('Uncheck All');
                    this.$inputs.prop('checked', true);
                } else {
                    this.areAllChecked = false;
                    this.$checkAll.html('Check All');
                    this.$inputs.prop('checked', false);
                }

                this.updateStatus();
            };

            CheckboxDropdown.prototype.toggleOpen = function(forceOpen) {
                var _this = this;

                if (!this.isOpen || forceOpen) {
                    this.isOpen = true;
                    this.$el.addClass('on');
                    $(document).on('click', function(e) {
                        if (!$(e.target).closest('[data-control]').length) {
                            _this.toggleOpen();
                        }
                    });
                } else {
                    this.isOpen = false;
                    this.$el.removeClass('on');
                    $(document).off('click');
                }
            };

            var checkboxesDropdowns = document.querySelectorAll('[data-control="checkbox-dropdown"]');
            for (var i = 0, length = checkboxesDropdowns.length; i < length; i++) {
                new CheckboxDropdown(checkboxesDropdowns[i]);
            }
        })(jQuery);


        // Tabler Core

        // @formatter:off
        document.addEventListener("DOMContentLoaded", function() {
            window.Litepicker && (new Litepicker({
                element: document.getElementById('datepicker-icon-prepend'),
                buttonText: {
                    previousMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>`,
                    nextMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>`,
                },
            }));
        });
        // @formatter:on

        $(document).ready(function() {

            $('#detailsDialog').on('shown.bs.modal', function() {

                $("#indefinitely").change(function() {
                    if (this.checked) {
                        $("#frequency").attr("disabled", "disabled");
                    } else {
                        $("#frequency").removeAttr("disabled");
                    }
                });

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

            });
        });


        function saveDetails() {
            $.ajax({
                type: 'POST',
                url: '{{ route('schedule.create') }}',
                data: $('#detailsForm').serialize(),
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    $('#success-message').text(response.message).show();
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        function viewSalesOrder(salesOrderId) {
            // console.log(quotation_id);

            $.ajax({
                type: 'GET',
                url: '{{ route('sales.order.view') }}',
                data: {
                    salesOrderId: salesOrderId
                },
                success: function(response) {
                    // $('#view-quotation').modal('show');
                    $('#viewSalesModal').html(response);

                },
            });

        }


        function assign_cleaner(id) {
            $.ajax({
                url: '{{ route('get.cleaner.service.address', ['id' => ':id']) }}'.replace(':id', id),
                method: 'GET',
                success: function(data) {
                    var startTime = new Date('1970-01-01T' + data.time_of_cleaning);
                    var getHourInSeconds = data.get_hour * 60 * 60;
                    var endTime = new Date(startTime.getTime() + getHourInSeconds * 1000);

                    var formattedEndTime = endTime.toTimeString().substring(0, 5);

                    $('#address').val(data.address);
                    $('#postalCode').val(data.postal_code);
                    $('#unitNo').val(data.unit_number);
                    $('#customer_id').val(data.customer_id);
                    $('#sales_order_no').val(data.sales_order_no);
                    $('#startDate').val(data.schedule_date);
                    $('#startTime').val(data.time_of_cleaning);
                    $('#total_session').val(data.get_total_session);
                    $('#weekly_freq').val(data.weekly_freq);
                    $('#get_hour').val(data.get_hour);
                    $('#endTime').val(formattedEndTime);
                    $('#customer_remark').val(data.customer_remark);


                    $('.dayslist').prop('checked', false);


                    $('.dayslist').prop('disabled', false);

                    $('.dayslist').on('click', function() {
                        updateCheckboxState();
                    });

                    $('#weekly_freq').on('input', function() {
                        updateCheckboxState();
                    });

                    setEndDates();
                },
                error: function() {
                    $('#address').val('Error fetching address');
                }
            });
        }

        function updateCheckboxState() {
            var frequency = parseInt($('#weekly_freq').val());
            var checkedCheckboxes = $('.dayslist:checked');

            if (checkedCheckboxes.length >= frequency) {

                $('.dayslist:not(:checked)').prop('disabled', true);
            } else {

                $('.dayslist').prop('disabled', false);
            }

            setEndDates();
        }


        function setEndDates() {
            var startDate = new Date($('#startDate').val());
            var totalSession = parseInt($('#total_session').val());

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

            console.log(endDates);


            $('#datePick').val(endDates.map(date => formatDateForDisplay(date)).join(', '));


            var lastDate = endDates[endDates.length - 1];
            $('#endDate').val(formatDateForInput(lastDate));
        }


        function getDayNumber(dayName) {
            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
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



        function editCleaner(id) {
            $.ajax({
                url: "{{ route('cleaner.edit', ['id' => ':id']) }}".replace(':id', id),
                type: "GET",
                success: function(response) {
                    //  console.log(response);
                    $('#edit-cleaner').modal('show');
                    $('#edit-cleaner-model').html(response);

                },
                error: function() {
                    console.log('Error occurred while loading the edit modal content.');
                }
            });
        }
    </script>
@endsection
