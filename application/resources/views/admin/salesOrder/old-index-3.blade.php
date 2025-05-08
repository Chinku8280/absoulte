@extends('theme.default')

@section('custom_css')
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
    <style>
        /* #order_table th {
            text-align: center;
        } */
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

    <style>
        .select2-container {
            width: 100% !important;
        }

        .narrow-column {
            width: 100px;
            max-width: 100px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>

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
                    
                    <div class="col-auto ms-auto">
                        <a href="{{route('sales-order.create')}}" class="btn btn-primary">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Add New
                        </a>
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
                                    <div class="card-body">
                                        <div class="table-responsive" style="min-height: 500px;">
                                            {{-- <table id="order_table" class="table card-table table-vcenter text-center text-nowrap datatable" style="width: 100%;"> --}}
                                            <table id="order_table" class="table text-left" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th class="w-1">No.</th>
                                                        <th>Invoice No.</th>
                                                        <th>SO No.</th>
                                                        <th>Customer Name</th>
                                                        <th>Service Address</th>
                                                        <th>Remarks</th>
                                                        <th class="narrow-column" title="Total Amount after GST">Total Amount after GST</th>
                                                        <th>Unassigned Date</th>
                                                        <th>Created By</th>
                                                        <th>Status</th>
                                                        <th>Job Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($salesOrder as $key => $item)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{ $item->invoice_no }}</td>
                                                            <td>{{ $item->id }}</td>
                                                            <td>
                                                                @if ($item->customer_type == "residential_customer_type")
                                                                    {{ $item->customer_name }}
                                                                @else
                                                                    {{ $item->individual_company_name }}
                                                                @endif                                                          
                                                            </td>
                                                            <td>{{ $item->service_address }}</td>
                                                            <td>{{ $item->remarks }}</td>
                                                            <td class="narrow-column">${{ $item->total_amount }}</td>
                                                            <td>{{($item->unassigned_date) ? date('d-m-Y', strtotime($item->unassigned_date)) : ''}}</td>
                                                            <td>{{ $item->created_by }}</td>
                                                            <td>
                                                                @if ($item->cleaner_assigned_status == 1)
                                                                    <span class="badge bg-success">Assigned</span>
                                                                @elseif ($item->cleaner_assigned_status == 2)
                                                                    <span class="badge bg-yellow">Partial</span>
                                                                @elseif ($item->cleaner_assigned_status == 0)
                                                                    <span class="badge bg-red">Unassigned</span>
                                                                @else
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($item->job_status == 0)
                                                                    <span class="badge bg-blue">Pending</span>
                                                                @elseif ($item->job_status == 1)
                                                                    <span class="badge bg-yellow">Work in Progress</span>
                                                                @elseif ($item->job_status == 2)
                                                                    <span class="badge bg-success">Completed</span>
                                                                @elseif ($item->job_status == 3)
                                                                    <span class="badge bg-red">Cancelled</span>
                                                                @else
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

                                                                        @if ($item->job_status == 0 || $item->job_status == 1)     
                                                                            <a href="{{route('sales-order.edit', $item->id)}}" class="dropdown-item">
                                                                                <i class="fa-solid fa-edit me-2 text-blue"></i>
                                                                                Edit
                                                                            </a>
                                                                        @endif
                                                                      
                                                                        @if ($item->status == 1 || $item->status == 2)
                                                                            @if ($item->job_status == 0 || $item->job_status == 1)                             
                                                                                <a class="dropdown-item" href="#"
                                                                                    {{-- onclick="editCleaner('{{ $item->tble_schedule_id }}')"> --}}
                                                                                    onclick="editCleaner('{{ $item->id }}')">
                                                                                    <i
                                                                                        class="fa-solid fa-pencil me-2 text-yellow"></i>
                                                                                    Edit Cleaners
                                                                                </a>                                                             
                                                                            @endif
                                                                            @if ($item->job_status == 0)
                                                                                <a class="dropdown-item unassign_btn" href="#" data-sales_order_id="{{ $item->id }}">
                                                                                    <i
                                                                                        class="fa fa-times-circle me-2 text-red"></i>
                                                                                    Unassign
                                                                                </a>
                                                                            @endif

                                                                            <a href="#" class="dropdown-item"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#view_assign_cleaner"
                                                                                onclick="view_assign_cleaner({{ $item->id }})">
                                                                                <i class="fa-solid fa-eye me-2 text-blue"></i>
                                                                                View Assign Cleaner
                                                                            </a>
                                                                        @else
                                                                            {{-- <a class="dropdown-item" href="#"
                                                                                data-bs-toggle="modal"
                                                                                data-id="{{ $item->id }}"
                                                                                data-bs-target="#detailsDialog"
                                                                                onclick="assign_cleaner({{ $item->id }})">
                                                                                <i
                                                                                    class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                                Assigning Cleaners
                                                                            </a>  --}}
                                                                            
                                                                            <a class="dropdown-item" href="javascript:void(0);"
                                                                                data-id="{{ $item->id }}"
                                                                                onclick="openModalInNewTab({{ $item->id }})">
                                                                                <i class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                                Assigning Cleaners
                                                                            </a>
                                                                        @endif
                                                                   
                                                                        @if ($item->renewal == 1)
                                                                            <a class="dropdown-item renewal_btn" href="#" data-sales_order_id="{{ $item->id }}" data-pending_invoice_limit="{{$item->pending_invoice_limit}}" data-total_pending_invoice="{{$item->total_pending_invoice}}">
                                                                                <i
                                                                                    class="fa fa-refresh me-2 text-success"></i>
                                                                                Renewal
                                                                            </a> 
                                                                        @endif

                                                                        <a class="dropdown-item" href="{{route('sales-order.log-report', $item->id)}}">
                                                                            <i
                                                                                class="fa fa-file me-2 text-blue"></i>
                                                                            Log Report
                                                                        </a>
                                                                                                          
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
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

    {{-- view sales order --}}
    <div class="modal modal-blur fade" id="view-sales-order" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document" id="viewSalesModal">

        </div>
    </div>

    {{-- assign cleaner --}}
    <div class="modal modal-blur fade" id="detailsDialog" role="dialog" aria-hidden="true" data-flag="assign">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cleaner Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('schedule.create') }}" method="POST" id="detailsForm" name="detailsForm">
                        @csrf
                        <input type="hidden" class="hidden_schedule_flag" name="hidden_schedule_flag" id="hidden_schedule_flag" value="add">
                        <input type="hidden" class="sales_order_id" name="sales_order_id" id="sales_order_id">
                        <input type="hidden" class="sales_order_no" name="sales_order_no" id="sales_order_no">
                        <input type="hidden" name="customer_id" id="customer_id">

                        <input type="hidden" name="db_get_hour" id="db_get_hour" class="db_get_hour">

                        <div class="mb-3">
                            <label for="address">Invoice No:</label>
                            <input type="text" class="form-control" id="invoice_no" name="invoice_no" readonly>
                        </div>

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
                                <label for="weekly_freq">Weekly Freq:</label>
                                <input type="text" class="form-control" id="weekly_freq" name="weekly_freq">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="man_power">Man Power Required:</label>
                                <input type="text" class="form-control man_power" id="man_power" name="man_power">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="startTime">Start Time:</label>
                                <input type="time" class="form-control startTime" id="startTime" name="startTime" required>
                            </div>
                            <div class="col-md-6">
                                <label for="endTime">End Time:</label>
                                <input type="time" class="form-control endTime" id="endTime" name="endTime" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="invoice_amount">Total Invoice Amount:</label>
                                <input type="number" class="form-control" id="invoice_amount" name="invoice_amount" step="0.01" min="0" required>
                            </div>

                            <div class="col-md-6">
                                <label for="total_pay_amount">Total Payable Amount:</label>
                                <input type="number" class="form-control total_pay_amount" id="total_pay_amount" name="total_pay_amount" step="0.01" min="0" required>
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

                        <div class="calender" style="display: none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="text" name="datepick" id="datePick" class="form-control" />
                                </div>
                            </div>
                        </div>
                        
                        <br>

                        <div class="row" id="set_details_row_group" style="display: none;">
                            
                        </div>

                        <div class="mb-3">
                            <label for="remarks">Remark:</label>
                            <input type="text" class="form-control" id="remarks" name="remarks">
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

    {{-- edit cleaner --}}
    <div class="modal modal-blur fade" id="edit_cleaner_modal" tabindex="-1" role="dialog" aria-hidden="true" data-flag="edit">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" id="edit_cleaner_modal_content">

            </div>
        </div>
    </div>

    {{-- confirmation modal unassign or not --}}
    <div class="modal modal-blur fade" id="unassign_modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="unassign_form" method="POST">
                    @csrf
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-status bg-danger"></div>
                    <div class="modal-body text-center py-4">
                        <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 9v2m0 4v.01"></path>
                            <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75">
                            </path>
                        </svg>
                        <h3>Are you sure?</h3>
                        <div class="text-muted">Do you really want to unassign?</div>                                        
                        <div class="mt-3">
                            <input type="hidden" name="unassign_sales_order_id" id="unassign_sales_order_id">
                        </div>                      
                    </div>
                    <div class="modal-footer">
                        <div class="w-100">
                            <div class="row">
                                <div class="col">
                                    <a href="#" class="btn w-100" data-bs-dismiss="modal" data-bs-dismiss="modal">
                                        Cancel
                                    </a>
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-danger w-100" id="unassign_confirm_btn">
                                        Confirm
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- confirm assign --}}
    <div class="modal modal-blur fade" id="confirmation_assign_modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                    <svg  xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-success icon-lg" width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>
                    <h3>Are you sure?</h3>
                    <div class="text-muted">Do you really want to assign?</div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <a href="#" class="btn w-100" data-bs-dismiss="modal" data-bs-dismiss="modal">
                                    Cancel
                                </a>
                            </div>
                            <div class="col">
                                <a href="#" class="btn btn-success w-100" id="confirmation_assign_modal_btn">
                                    Confirm
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- view assign cleaner --}}
    <div class="modal modal-blur fade" id="view_assign_cleaner_modal" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" id="view_assign_cleaner_modal_content">
                
            </div>
        </div>
    </div>

    {{-- renewal modal --}}
    <div class="modal modal-blur fade" id="renewal_modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="renewal_form" method="POST">
                    @csrf
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-status bg-danger"></div>
                    <div class="modal-body text-center py-4">
                        <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 9v2m0 4v.01"></path>
                            <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75">
                            </path>
                        </svg>
                        <h3>Are you sure?</h3>
                        <div class="text-muted" id="renewal_confirmation_msg">Do you really want to Renew this Sales Order?</div>                                        
                        <div class="mt-3">
                            <input type="hidden" name="renewal_sales_order_id" id="renewal_sales_order_id">

                            <textarea class="form-control" name="renewal_remarks" id="renewal_remarks" cols="30" rows="5"
                                placeholder="Remarks" style="display: none;"></textarea>
                        </div>                      
                    </div>
                    <div class="modal-footer">
                        <div class="w-100">
                            <div class="row">
                                <div class="col">
                                    <a href="#" class="btn w-100" data-bs-dismiss="modal" data-bs-dismiss="modal">
                                        Cancel
                                    </a>
                                </div>
                                <div class="col">                                   
                                    <button type="submit" class="btn btn-danger w-100" id="renewal_confirm_btn">
                                        Confirm
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('javascript')

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-multidatespicker/1.6.6/jquery-ui.multidatespicker.js"></script>

    <script>
        
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                iziToast.error({
                    message: "{{$error}}",
                    position: 'topRight'
                });
            @endforeach
        @endif

        @if(Session::has('status'))
            @if (Session::get('status') == 'success')
                iziToast.success({
                    message: "{{Session::get('message')}}",
                    position: 'topRight'
                });
            @elseif (Session::get('status') == 'failed')
                iziToast.error({
                    message: "{{Session::get('message')}}",
                    position: 'topRight'
                });
            @endif
        @endif

        $(document).ready(function () {
            var successMessage = localStorage.getItem('successMessage');
            if (successMessage) {
                iziToast.success({
                    message: successMessage,
                    position: 'topRight',
                    timeout: false,   // disables auto-close
                    close: true       // adds a close (×) button
                });

                // Remove the message so it doesn't show again on the next refresh
                localStorage.removeItem('successMessage');
            }
        });
        

        // document.getElementById('team_id').addEventListener('change', function() {

        //     var selectedTeamId = this.value;

        //     // var employeeNames = <?php echo json_encode($employeeNames); ?>;

        //     var employeeNames = @php echo json_encode($employeeNames); @endphp;

        //     var employeeTextarea = document.getElementById('employee_names');
        //     employeeTextarea.value = '';

        //     if (employeeNames[selectedTeamId]) {
        //         var temp_employeeNames = employeeNames[selectedTeamId];

        //         temp_employeeNames.forEach(function(employeeName) {
        //             employeeTextarea.value += employeeName + '\n';
        //         });
        //     }
        // });


        // var cleanerTypeRadioButtons = document.querySelectorAll('.cleaner_type');
        // cleanerTypeRadioButtons.forEach(function(radioButton) {
        //     radioButton.addEventListener('change', function() {

        //         var employeeTextarea = document.getElementById('employee_names');
        //         var employeeFormGroup = document.querySelector('.employee');

        //         if (this.value === 'team') {

        //             employeeFormGroup.style.display = 'block';
        //         } else {

        //             employeeFormGroup.style.display = 'none';
        //         }
        //     });
        // });

        // Trigger the change event on the initially checked radio button to ensure the textarea is displayed on modal open
        // document.querySelector('.cleaner_type:checked').dispatchEvent(new Event('change'));

        $(document).ready(function() {
            // $('.datatable').DataTable();

            $('#order_table').DataTable({
                "lengthChange": false,
                "pageLength": 30,
                'columnDefs': [{
                    'targets': [0],        // Targets the first column (index 0)
                    'orderable': false,    // Disables sorting on this column
                }]
            }).on('order.dt search.dt', function() {
                var table = $('#order_table').DataTable();
                table.column(0, {order:'applied'}).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;  // Update the serial number
                });
            }).draw();
            
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

        function assign_cleaner(id) 
        {
            $.ajax({
                url: '{{ route('get.cleaner.service.address', ['id' => ':id']) }}'.replace(':id', id),
                method: 'GET',
                success: function(data) {

                    $("#detailsForm")[0].reset();
                    $("#set_details_row_group").html("");             

                    if(data.status == "success")
                    {                  
                        // var startTime = new Date('1970-01-01T' + data.time_of_cleaning);
                        // var getHourInSeconds = data.get_hour * 60 * 60;
                        // var endTime = new Date(startTime.getTime() + getHourInSeconds * 1000);

                        // var formattedEndTime = endTime.toTimeString().substring(0, 5);

                        var formattedEndTime = setEndTime(data.time_of_cleaning, data.get_hour);

                        $("#db_get_hour").val(data.get_hour);
                        
                        $('#address').val(data.address);
                        $('#postalCode').val(data.postal_code);
                        $('#unitNo').val(data.unit_number);
                        $('#customer_id').val(data.customer_id);
                        $('#sales_order_id').val(data.sales_order_id);
                        $('#sales_order_no').val(data.sales_order_no);
                        $('#startDate').val(data.schedule_date);
                        $('#startTime').val(data.time_of_cleaning);
                        $('#total_session').val(data.get_total_session);
                        $('#weekly_freq').val(data.weekly_freq);
                        $('#get_hour').val(data.get_hour);
                        $('#man_power').val(data.man_power);
                        $('#endTime').val(formattedEndTime);
                        $('#remarks').val(data.remarks);
                        $("#invoice_amount").val(parseFloat(data.invoice_amount).toFixed(2));
                        $("#total_pay_amount").val(parseFloat(data.balance_amount).toFixed(2));

                        $('.dayslist').prop('checked', false);

                        $('.dayslist').prop('disabled', false);     

                        $('#invoice_no').val(data.invoice_no);
                    }    
                    else
                    {
                        iziToast.error({
                            message: data.message,
                            position: 'topRight'
                        });
                    }          
                },
                error: function(result) {
                    console.log(result);
                }
            });
        }

        function updateCheckboxState() {
            var frequency = parseInt($('#weekly_freq').val());
            var checkedCheckboxes = $('.dayslist:checked');

            // console.log(frequency);

            if (checkedCheckboxes.length >= frequency) 
            {
                $('.dayslist:not(:checked)').prop('disabled', true);
            } 
            else 
            {
                $('.dayslist').prop('disabled', false);
            }

            setEndDates();
        }


        function setEndDates()
        {
            if($('#startDate').val() && $('#startTime').val() && $('#endTime').val())
            {
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


                if(endDates.length > 0)
                {
                    $('#datePick').val(endDates.map(date => formatDateForDisplay(date)).join(', '));          

                    var lastDate = endDates[endDates.length - 1];

                    $('#endDate').val(formatDateForInput(lastDate));
                }

                // console.log(endDates);

                set_date_details_table(endDates);
            }
            else
            {
                iziToast.error({
                    message: 'Select Start Date or Time',
                    position: 'topRight'
                });
            }
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

        function editCleaner(id) 
        {
            $.ajax({
                url: "{{ route('cleaner.edit', ['id' => ':id']) }}".replace(':id', id),
                type: "GET",
                success: function(response) {
                    //  console.log(response);
                    $('#edit_cleaner_modal').modal('show');
                    $('#edit_cleaner_modal_content').html(response);

                },
                error: function() {
                    console.log('Error occurred while loading the edit modal content.');
                }
            });
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

        function set_date_details_table(endDates)
        {
            // console.log(endDates);

            if(endDates.length > 0)
            {
                $("#set_details_table").html("");

                var sch_date = [];

                $.each(endDates, function (key, value) { 
                    sch_date.push(formatDateForInput(value));               
                });

                var sales_order_id = $("#sales_order_id").val();
                var sales_order_no = $("#sales_order_no").val();
                var cleaner_type = $('#detailsForm input[name="cleaner_type"]:checked').val();
                var startTime = $('#startTime').val();
                var endTime = $('#endTime').val();

                $.ajax({
                    type: "get",
                    url: "{{route('sales-order.schedule-date-table-details')}}",
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

                        $("#set_details_row_group").html(result);                    
                    },
                    error: function (result) {
                        console.log(result);
                    }
                });

                $("#set_details_row_group").show();
            }
            else
            {
                $("#set_details_table").html("");
                $("#set_details_row_group").hide();
            }
        }

        // view assign cleaner

        function view_assign_cleaner(id)
        {
            $.ajax({
                type: "get",
                url: "{{route('sales-order.view-assign-cleaner')}}",
                data: {sales_order_id : id},
                success: function (result) {
                    console.log(result);

                    $('#view_assign_cleaner_modal').modal('show');
                    $('#view_assign_cleaner_modal_content').html(result);
                },
                error: function (result) {
                    console.log(result);
                }
            });
        }

        // check cleaner exists for each schedule date

        function check_cleaner_exists(el)
        {
            var table_startTime = el.parents('tr').find('.table_startTime').val();
            var table_endTime = el.parents('tr').find('.table_endTime').val();
            var table_schedule_date = el.parents('tr').find('.table_schedule_date').val();
            var sales_order_id = el.parents('form').find(".sales_order_id").val();
            var sales_order_no = el.parents('form').find(".sales_order_no").val();
            var cleaner_type = el.parents('form').find('input[name="cleaner_type"]:checked').val();

            $.ajax({
                type: "get",
                url: "{{route('schedule-date-check-cleaner-exists')}}",
                data: {
                    table_schedule_date: table_schedule_date,
                    sales_order_id: sales_order_id,
                    sales_order_no: sales_order_no,
                    cleaner_type: cleaner_type,
                    table_startTime: table_startTime,
                    table_endTime: table_endTime
                },
                success: function (result) {
                    console.log(result);    
                    
                    if(result.cleaner_type == "individual")
                    {
                        el.parents('tr').find('.table_cleaner_id').html("");                      
                        el.parents('tr').find(".table_superviser_emp_id").html("");
                        el.parents('tr').find(".table_superviser_emp_id").html("<option value=''>Select</option>");

                        $.each(result.users, function (key, value) { 
                            if(result.sch_indv_emp.includes(value.user_id) == false)
                            {                          
                                var html = `<option value="${value.user_id}" style="background-color: ${value.zone_color}" data-color="${value.zone_color}">${value.full_name}</option>`;                        
                            }
                            
                            el.parents('tr').find(".table_cleaner_id").append(html);
                        });
                    }
                    else if(result.cleaner_type == "team")
                    {
                        el.parents('tr').find(".table_team_id").html("");
                        el.parents('tr').find(".table_team_id").html("<option value=''>Select Team</option>");                     
                        el.parents('tr').find(".table_employee_names").val("");

                        $.each(result.get_team, function (key, value) { 
                            if(result.sch_team_emp.includes(value.team_id) == false)
                            {                          
                                var html = `<option value="${value.team_id}">${value.team_name}</option>`;                        
                            }
                            
                            el.parents('tr').find(".table_team_id").append(html);
                        });
                    }
                },
                error: function (result) {
                    console.log(result);
                }
            });
        }

        // check cleaner exists for whole table

        function set_change_full_cleaner_table(el, table_id)
        {
            var sales_order_id = el.parents('form').find(".sales_order_id").val();
            var sales_order_no = el.parents('form').find(".sales_order_no").val();
            var cleaner_type = el.parents('form').find('input[name="cleaner_type"]:checked').val();

            $('#'+table_id+' tbody tr').each(function() {
                var table_startTime = $(this).find('.table_startTime').val();
                var table_endTime = $(this).find('.table_endTime').val();
                var table_schedule_date = $(this).find('.table_schedule_date').val();

                var el_2 = $(this);

                $.ajax({
                    type: "get",
                    url: "{{route('schedule-date-check-cleaner-exists')}}",
                    data: {
                        table_schedule_date: table_schedule_date,
                        sales_order_id: sales_order_id,
                        sales_order_no: sales_order_no,
                        cleaner_type: cleaner_type,
                        table_startTime: table_startTime,
                        table_endTime: table_endTime
                    },
                    success: function (result) {
                        console.log(result);    
                        
                        if(result.cleaner_type == "individual")
                        {
                            el_2.find('.table_cleaner_id').html("");
                            el_2.find(".table_superviser_emp_id").html("");
                            el_2.find(".table_superviser_emp_id").html("<option value=''>Select</option>");

                            $.each(result.users, function (key, value) { 
                                if(result.sch_indv_emp.includes(value.user_id) == false)
                                {                          
                                    var html = `<option value="${value.user_id}" style="background-color: ${value.zone_color}" data-color="${value.zone_color}">${value.full_name}</option>`;                        
                                }
                                
                                el_2.find(".table_cleaner_id").append(html);
                            });
                        }
                        else if(result.cleaner_type == "team")
                        {
                            el_2.find(".table_team_id").html("");
                            el_2.find(".table_team_id").html("<option value=''>Select Team</option>");
                            el_2.find(".table_employee_names").val("");

                            $.each(result.get_team, function (key, value) { 
                                if(result.sch_team_emp.includes(value.team_id) == false)
                                {                          
                                    var html = `<option value="${value.team_id}">${value.team_name}</option>`;                        
                                }
                                
                                el_2.find(".table_team_id").append(html);
                            });
                        }
                    },
                    error: function (result) {
                        console.log(result);
                    }
                });
            });
        }

        // check driver exists for each delivery date

        function check_driver_exists(el)
        {
            var table_delivery_time = el.parents('tr').find('.table_delivery_time').val();
            var table_delivery_date = el.parents('tr').find('.table_delivery_date').val();
            var sales_order_id = el.parents('form').find(".sales_order_id").val();
            var sales_order_no = el.parents('form').find(".sales_order_no").val();

            $.ajax({
                type: "get",
                url: "{{route('delivery-date-check-driver-exists')}}",
                data: {
                    table_delivery_date: table_delivery_date,
                    table_delivery_time: table_delivery_time,
                    sales_order_id: sales_order_id,
                    sales_order_no: sales_order_no             
                },
                success: function (result) {
                    console.log(result);    
                    
                    el.parents('tr').find('.table_driver_emp_id').html("");                      

                    $.each(result.users, function (key, value) { 
                        if(result.driver_emp.includes(value.user_id) == false)
                        {                          
                            var html = `<option value="${value.user_id}" style="background-color: ${value.zone_color}" data-color="${value.zone_color}">${value.full_name}</option>`;                        
                        }
                        else
                        {
                            var html = `<option value="${value.user_id}" style="background-color: ${value.zone_color}" data-color="${value.zone_color}" disabled>${value.full_name}</option>`;                        
                        }
                        
                        el.parents('tr').find(".table_driver_emp_id").append(html);
                    });
                },
                error: function (result) {
                    console.log(result);
                }
            });
        }

        $(document).ready(function () {
            
            $('.dayslist').on('click', function() {
                updateCheckboxState();
            });

            $('#weekly_freq').on('input', function() {
                updateCheckboxState();
            });

            // setEndDates();

            // start

            $('body').on('click', '.cleaner_type', function(){

                // console.log($(this).val());

                // form
                var hidden_schedule_flag = $(this).parents('form').find('#hidden_schedule_flag').val();

                if ($(this).val() == "team") 
                {
                    $(this).parents('form').find(".table_individual").hide();
                    $(this).parents('form').find('.table_superviser').hide(); 
                    $(this).parents('form').find(".table_team").show();
                    $(this).parents('form').find(".table_employee").show();     
                    
                    // css
                    $(this).parents('form').find(".table_delivery_date_group").css({"margin-top": "133px"});
                    $(this).parents('form').find(".table_delivery_time_group").css({"margin-top": "133px"});

                    if(hidden_schedule_flag == "edit")
                    {
                        $(this).parents('form').find(".table_delivery_remarks_group").css({"margin-top": "60px"});
                    } 
                    else
                    {
                        $(this).parents('form').find(".table_delivery_remarks_group").css({"margin-top": "100px"});
                    } 
                } 
                else 
                {
                    $(this).parents('form').find(".table_individual").show();
                    $(this).parents('form').find('.table_superviser').show(); 
                    $(this).parents('form').find(".table_team").hide();
                    $(this).parents('form').find(".table_employee").hide();   
                    
                    // css
                    $(this).parents('form').find(".table_delivery_date_group").css({"margin-top": "70px"});
                    $(this).parents('form').find(".table_delivery_time_group").css({"margin-top": "70px"});

                    if(hidden_schedule_flag == "edit")
                    {
                        $(this).parents('form').find(".table_delivery_remarks_group").css({"margin-top": "0px"});
                    } 
                    else
                    {
                        $(this).parents('form').find(".table_delivery_remarks_group").css({"margin-top": "35px"});
                    } 
                }

            });

            $('body').on('change', '.table_team_id', function(){

                var selectedTeamId = $(this).find(':selected').val();

                var employeeNames = @php echo json_encode($employeeNames); @endphp;

                var temp = "";

                if (employeeNames[selectedTeamId]) 
                {
                    var temp_employeeNames = employeeNames[selectedTeamId];

                    temp_employeeNames.forEach(function(employeeName) {
                        temp += employeeName + '\n';
                    });
                }

                $(this).parents('tr').find(".table_employee_names").val(temp);

            });

            $('body').on('change', '.startTime', function(){

                var start_time = $(this).val();
                var db_get_hour = $(this).parents('form').find('.db_get_hour').val();

                var end_time = setEndTime(start_time, db_get_hour);

                $(this).parents('form').find('.endTime').val(end_time);
                $(this).parents('form').find('.table_startTime').val(start_time);
                $(this).parents('form').find('.table_endTime').val(end_time);

                var modal_flag = $(this).parents('.modal').data('flag');

                if(modal_flag == "edit")
                {
                    set_change_full_cleaner_table($(this), 'edit_set_details_table');
                }
                else
                {
                    // setEndDates();
                    set_change_full_cleaner_table($(this), 'set_details_table');
                }             

            });

            $('body').on('change', '.endTime', function(){

                var end_time = $(this).val();

                $(this).parents('form').find('.table_endTime').val(end_time);

                var modal_flag = $(this).parents('.modal').data('flag');

                if(modal_flag == "edit")
                {
                    set_change_full_cleaner_table($(this), 'edit_set_details_table');
                }
                else
                {
                    // setEndDates();
                    set_change_full_cleaner_table($(this), 'set_details_table');
                }    

            });

            $('body').on('change', '.table_startTime', function(){

                var start_time = $(this).val();
                var db_get_hour =  $(this).parents('form').find('.db_get_hour').val();

                var end_time = setEndTime(start_time, db_get_hour);

                $(this).parents('tr').find('.table_endTime').val(end_time);

                check_cleaner_exists($(this));

            });

            $('body').on('change', '.table_endTime', function(){

                check_cleaner_exists($(this));

            });

            $('body').on('change', '.table_schedule_date', function(){

                check_cleaner_exists($(this));

            });

            // end

            // unassign start

            $('body').on('click', '.unassign_btn', function(){

                var sales_order_id = $(this).data('sales_order_id');
                $("#unassign_sales_order_id").val(sales_order_id);
                $("#unassign_modal").modal('show');

            });

            $('body').on('submit', '#unassign_form', function(e){

                e.preventDefault();

                $.ajax({
                    type: "post",
                    url: "{{route('sales-order.unassign')}}",
                    data: $(this).serialize(),
                    success: function (result) {
                        console.log(result);

                        if(result.status == "success")
                        {
                            iziToast.success({
                                message: result.message,
                                position: 'topRight',
                            });

                            location.reload();
                        }
                        else
                        {
                            iziToast.error({
                                message: result.message,
                                position: 'topRight',
                            });
                        }
                    },
                    error: function (result) {
                        console.log(result);
                    }
                });

            });

            // unassign end

            // save assign cleaner

            $('body').on('submit', '#detailsForm', function(e){

                e.preventDefault();

                var assign_confirm = true;

                // check start time start

                var table_startTime_arr = $(this).find('.table_startTime').map(function(){
                    return $(this).val()
                }).get();                

                // console.log(table_startTime_arr);

                $.each(table_startTime_arr, function (key, value) { 
                     
                    var timeComponents = value.split(":");
                    var hours = parseInt(timeComponents[0], 10);
                    // console.log(hours);

                    if (hours >= 21 || hours < 7) 
                    {
                        console.log("start-time");
                        assign_confirm = false;
                    }

                });

                // check start time end

                // check end time start

                var table_endTime_arr = $(this).find('.table_endTime').map(function(){
                    return $(this).val()
                }).get();                

                // console.log(table_startTime_arr);

                $.each(table_endTime_arr, function (key, value) { 
                     
                    var timeComponents = value.split(":");
                    var hours = parseInt(timeComponents[0], 10);
                    // console.log(hours);

                    if (hours >= 21 || hours < 7) 
                    {
                        console.log("end-time");
                        assign_confirm = false;                       
                    }

                });

                // check end time end

                if(assign_confirm == true)
                {
                    $.ajax({
                        type: "post",
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        success: function (result) {
                            console.log(result);

                            if(result.status == "error")
                            {
                                $.each(result.errors, function (key, value) { 
                                    
                                    iziToast.error({
                                        message: value,
                                        position: 'topRight'
                                    });

                                });
                            }
                            else if(result.status == "success")
                            {
                                iziToast.success({
                                    message: result.message,
                                    position: 'topRight'
                                });
                            
                                $("#detailsDialog").modal('hide');
                                location.reload();
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
                }
                else
                {
                    $("#confirmation_assign_modal_btn").data('form_data', $(this).serialize());
                    $("#confirmation_assign_modal_btn").data('form_action', $(this).attr('action'));
                    $("#confirmation_assign_modal").modal('show');
                }

            });           

            // update assign cleaner

            $('body').on('submit', '#edit_cleaner_form', function(e){

                e.preventDefault();

                var assign_confirm = true;

                // check start time start

                var table_startTime_arr = $(this).find('.table_startTime').map(function(){
                    return $(this).val()
                }).get();                

                // console.log(table_startTime_arr);

                $.each(table_startTime_arr, function (key, value) { 
                     
                    var timeComponents = value.split(":");
                    var hours = parseInt(timeComponents[0], 10);
                    // console.log(hours);

                    if (hours >= 21 || hours < 7) 
                    {
                        console.log("start-time");
                        assign_confirm = false;
                    }

                });

                // check start time end

                // check end time start

                var table_endTime_arr = $(this).find('.table_endTime').map(function(){
                    return $(this).val()
                }).get();                

                // console.log(table_startTime_arr);

                $.each(table_endTime_arr, function (key, value) { 
                     
                    var timeComponents = value.split(":");
                    var hours = parseInt(timeComponents[0], 10);
                    // console.log(hours);

                    if (hours >= 21 || hours < 7) 
                    {
                        console.log("end-time");
                        assign_confirm = false;                       
                    }

                });

                // check end time end

                if(assign_confirm == true)
                {
                    $.ajax({
                        type: "post",
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        success: function (result) {
                            console.log(result);

                            if(result.status == "error")
                            {
                                $.each(result.errors, function (key, value) { 
                                    
                                    iziToast.error({
                                        message: value,
                                        position: 'topRight'
                                    });

                                });
                            }
                            else if(result.status == "success")
                            {
                                iziToast.success({
                                    message: result.message,
                                    position: 'topRight'
                                });
                            
                                $("#edit_cleaner_modal").modal('hide');
                                location.reload();
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
                }
                else
                {
                    $("#confirmation_assign_modal_btn").data('form_data', $(this).serialize());
                    $("#confirmation_assign_modal_btn").data('form_action', $(this).attr('action'));
                    $("#confirmation_assign_modal").modal('show');
                }               

            });

            // confirmation assign / edit cleaner

            $('body').on('click', '#confirmation_assign_modal_btn', function(){

                var form_data = $(this).data('form_data');
                var form_action = $(this).data('form_action');

                $.ajax({
                    type: "post",
                    url: form_action,
                    data: form_data,
                    success: function (result) {
                        console.log(result);

                        if(result.status == "error")
                        {
                            $.each(result.errors, function (key, value) { 
                                
                                iziToast.error({
                                    message: value,
                                    position: 'topRight'
                                });

                            });
                        }
                        else if(result.status == "success")
                        {
                            iziToast.success({
                                message: result.message,
                                position: 'topRight'
                            });
                        
                            $("#confirmation_assign_modal").modal('hide');
                            $("#detailsDialog").modal('hide');
                            location.reload();
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

            // renewal start

            // $('body').on('click', '.renewal_btn', function(){

            //     var sales_order_id = $(this).data('sales_order_id');
            //     $("#renewal_sales_order_id").val(sales_order_id);
            //     $("#renewal_modal").modal('show');

            // });

            $('body').on('click', '.renewal_btn', function(){

                var sales_order_id = $(this).data('sales_order_id');
                var flag = false;
                
                $.ajax({
                    type: "get",
                    url: "{{route('sales-order.check-renewal')}}",
                    data: {sales_order_id: sales_order_id},
                    success: function (result) {
                        console.log(result);

                        if(result.status == 'success')
                        {                    
                            $("#renewal_sales_order_id").val(sales_order_id);

                            if(result.customer_pending_invoice_limit != null || result.customer_pending_invoice_limit != 0)
                            {
                                if(result.customer_pending_invoice_limit <= result.total_pending_invoice)
                                {
                                    var flag = true; 
                                }
                            }

                            if(flag == true)
                            {
                                var renewal_confirmation_msg = "Pending Invoice limit is over. so, you still want to Renew this Sales Order?";
                                $("#renewal_remarks").show();
                            }
                            else
                            {
                                var renewal_confirmation_msg = "Do you really want to Renew this Sales Order?";
                                $("#renewal_remarks").hide();
                            }

                            $("#renewal_confirmation_msg").text(renewal_confirmation_msg);
                            $("#renewal_modal").modal('show');
                        }
                        else
                        {
                            iziToast.error({
                                message: result.message,
                                position: 'topRight',
                            });
                        }
                    },
                    error: function (result) {
                        console.log(result);
                    }
                });

            });

            $('body').on('submit', '#renewal_form', function(e){

                e.preventDefault();

                $.ajax({
                    type: "post",
                    url: "{{route('sales-order.renewal')}}",
                    data: $(this).serialize(),
                    success: function (result) {
                        console.log(result);

                        if(result.status == "success")
                        {
                            // iziToast.success({
                            //     message: result.message,
                            //     position: 'topRight',
                            // });

                            // Store the success message in localStorage
                            localStorage.setItem('successMessage', result.message);

                            // Open new tab for editing the sales order
                            var editUrl = "{{ route('sales-order.edit', ':id') }}".replace(':id', result.sales_order_id);
                            window.open(editUrl, '_blank');

                            // Reload the page
                            location.reload();
                        }
                        else
                        {
                            iziToast.error({
                                message: result.message,
                                position: 'topRight',
                            });
                        }
                    },
                    error: function (result) {
                        console.log(result);
                    }
                });

            });

            // renewal end

            // superviser start

            $('body').on('change', '.table_cleaner_id', function () {
                var el = $(this);
                var emp_id = el.val();

                $.ajax({
                    type: "get",
                    url: "{{route('team.get-superviser')}}",
                    data: {emp_id: emp_id},
                    success: function (result) {
                        // console.log(result);

                        if(result.xin_employees.length > 0)
                        {
                            el.parents('tr').find(".table_superviser_emp_id").html("");

                            $.each(result.xin_employees, function (key, value) { 
                                var html = `<option value="${value.user_id}">${value.first_name} ${value.last_name} (${value.zipcode})</option>`;
                            
                                el.parents('tr').find(".table_superviser_emp_id").append(html);
                            });                       
                        }
                        else
                        {
                            el.parents('tr').find(".table_superviser_emp_id").html("<option value=''>Select</option>");
                        }
                    },
                    error: function (result) {
                        console.log(result);
                    },
                });
            });

            // superviser end       
            
            // driver start

            $('body').on('click', '.table_add_driver_btn', function () {

                var el = $(this);

                el.parents('tr').find(".table_delivery_date_group").show();
                el.parents('tr').find(".table_delivery_time_group").show();
                el.parents('tr').find(".table_driver").show();  
                el.parents('tr').find(".table_delivery_remarks_group").show();                            
                el.parents('tr').find(".table_remove_driver_btn").show();
                el.parents('tr').find(".table_add_driver_btn").hide();

                // cleaner type
                var cleaner_type = el.parents('form').find('input[name="cleaner_type"]:checked').val();

                // form
                var hidden_schedule_flag = el.parents('form').find('#hidden_schedule_flag').val();

                if(cleaner_type == "team")
                {
                    // css
                    el.parents('tr').find(".table_delivery_date_group").css({"margin-top": "133px"});
                    el.parents('tr').find(".table_delivery_time_group").css({"margin-top": "133px"});

                    if(hidden_schedule_flag == "edit")
                    {
                        el.parents('tr').find(".table_delivery_remarks_group").css({"margin-top": "60px"});
                    }   
                    else
                    {
                        el.parents('tr').find(".table_delivery_remarks_group").css({"margin-top": "100px"});
                    }          
                }
                else if(cleaner_type == "individual")
                {
                    // css
                    el.parents('tr').find(".table_delivery_date_group").css({"margin-top": "70px"});
                    el.parents('tr').find(".table_delivery_time_group").css({"margin-top": "70px"});

                    if(hidden_schedule_flag == "edit")
                    {
                        el.parents('tr').find(".table_delivery_remarks_group").css({"margin-top": "0px"});
                    } 
                    else
                    {
                        el.parents('tr').find(".table_delivery_remarks_group").css({"margin-top": "35px"});
                    } 
                }

            });

            $('body').on('click', '.table_remove_driver_btn', function () {

                var el = $(this);

                el.parents('tr').find(".table_delivery_date_group").hide();
                el.parents('tr').find(".table_delivery_date").val("");
         
                el.parents('tr').find(".table_delivery_time_group").hide();
                el.parents('tr').find(".table_delivery_time").val("08:00");

                el.parents('tr').find(".table_driver").hide();   
                el.parents('tr').find(".table_driver_emp_id").val("");                 

                el.parents('tr').find(".table_delivery_remarks_group").hide(); 
                el.parents('tr').find(".table_delivery_remarks").val(""); 
                          
                el.parents('tr').find(".table_remove_driver_btn").hide();
                el.parents('tr').find(".table_add_driver_btn").show();

            });

            $('body').on('change', '.table_delivery_time', function(){

                check_driver_exists($(this));

            });

            $('body').on('change', '.table_delivery_date', function(){

                check_driver_exists($(this));

            });

            // driver end
            
        });

        // after click on assign cleaner it will open modal on new tab start

        function openModalInNewTab(itemId) 
        {
            // Get current URL without hash or existing query params
            let baseUrl = window.location.href.split('#')[0].split('?')[0];

            // Construct new URL with only the desired query param
            let newUrl = `${baseUrl}?id=${itemId}`;

            // Open in new tab
            window.open(newUrl, '_blank');

            // let newTab = window.open(window.location.href + '?id=' + itemId, '_blank');
        }

        document.addEventListener("DOMContentLoaded", function () {
            let urlParams = new URLSearchParams(window.location.search);
            let itemId = urlParams.get('id');

            if (itemId) {
                let modal = new bootstrap.Modal(document.getElementById('detailsDialog'));
                modal.show();

                // Call assign_cleaner function if needed
                assign_cleaner(itemId);

                // Move focus to the modal after opening
                setTimeout(() => {
                    document.getElementById('detailsDialog').focus();
                }, 300);

                // Remove ?id=5 from URL without reloading
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });

        // after click on assign cleaner it will open modal on new tab end
        
    </script>
@endsection
