@extends('theme.default')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                                        <table id="order_table" class="table card-table table-vcenter text-center text-nowrap datatable">
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
                                                                    {{-- <a class="dropdown-item" href="#"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#edit-sales-order"
                                                                        onclick="editSalesOrder({{ $item->id }})">
                                                                        <i class="fa-solid fa-pencil me-2 text-yellow"></i>
                                                                        Edit
                                                                    </a> --}}
                                                                    <!-- <a class="dropdown-item border-bottom" href="#">
                                                                        <i
                                                                            class="fa-solid fa-paper-plane me-2 text-red"></i>
                                                                        Send
                                                                        Deposite Link
                                                                    </a> -->
                                                                    <!-- <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#send-quotation">
                                                  <i class="fa-solid fa-envelope me-2 text-info"></i> Send Quotation
                                                  </a> -->
                                                      @if($item->status == 1)
                                                            <a class="dropdown-item" href="#" onclick="editCleaner('{{ $item->tble_schedule_id }}')" >
                                                                <i class="fa-solid fa-pencil me-2 text-yellow"></i> Edit Cleaners
                                                            </a>
                                                        @else
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                            data-id="{{ $item->id }}"
                                                            data-bs-target="#detailsDialog"
                                                            onclick="getAddress({{ $item->id }})">
                                                                <i class="fa-solid fa-circle-check me-2 text-green"></i> Assigning Cleaners
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
                <div class="modal modal-blur fade" id="confirm-quotation" tabindex="-1" style="display: none;"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Sales Order</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="smartwizard-3" style="border: none;" dir=""
                                    class="sw sw-theme-basic sw-justified">
                                    <ul class="nav d-none" style="">
                                        <li class="nav-item">
                                            <a class="nav-link default" href="#update-step-22">
                                                <span class="num">2</span>
                                                2
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link default" href="#update-step-33">
                                                <span class="num">3</span>
                                                3
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content p-0" style="border: none; height: 260px;">
                                        <div id="update-step-22" class="tab-pane" role="tabpanel"
                                            aria-labelledby="update-step-22" style="display: none;">
                                            <div class="row">
                                                <div class="mb-3">
                                                    <label class="form-label">Select Email Template</label>
                                                    <div class="row g-2">
                                                        <div class="col-6 col-sm-4">
                                                            <label class="form-imagecheck mb-2">
                                                                <input name="form-imagecheck-radio" type="radio"
                                                                    value="1" class="form-imagecheck-input">
                                                                <span class="form-imagecheck-figure">
                                                                    <img src="./dist/img/main-template.jpeg"
                                                                        alt="Group of people sightseeing in the city"
                                                                        class="form-imagecheck-image">
                                                                </span>
                                                            </label>
                                                        </div>
                                                        <div class="col-6 col-sm-4">
                                                            <label class="form-imagecheck mb-2">
                                                                <input name="form-imagecheck-radio" type="radio"
                                                                    value="2" class="form-imagecheck-input"
                                                                    checked="">
                                                                <span class="form-imagecheck-figure">
                                                                    <img src="./dist/img/main-template.jpeg"
                                                                        alt="Color Palette Guide. Sample Colors Catalog."
                                                                        class="form-imagecheck-image">
                                                                </span>
                                                            </label>
                                                        </div>
                                                        <div class="col-6 col-sm-4">
                                                            <label class="form-imagecheck mb-2">
                                                                <input name="form-imagecheck-radio" type="radio"
                                                                    value="3" class="form-imagecheck-input">
                                                                <span class="form-imagecheck-figure">
                                                                    <img src="./dist/img/main-template.jpeg"
                                                                        alt="Stylish workplace with computer at home"
                                                                        class="form-imagecheck-image">
                                                                </span>
                                                            </label>
                                                        </div>
                                                        <div class="col-6 col-sm-4">
                                                            <label class="form-imagecheck mb-2">
                                                                <input name="form-imagecheck-radio" type="radio"
                                                                    value="4" class="form-imagecheck-input"
                                                                    checked="">
                                                                <span class="form-imagecheck-figure">
                                                                    <img src="./dist/img/main-template.jpeg"
                                                                        alt="Pink desk in the home office"
                                                                        class="form-imagecheck-image">
                                                                </span>
                                                            </label>
                                                        </div>
                                                        <div class="col-6 col-sm-4">
                                                            <label class="form-imagecheck mb-2">
                                                                <input name="form-imagecheck-radio" type="radio"
                                                                    value="5" class="form-imagecheck-input">
                                                                <span class="form-imagecheck-figure">
                                                                    <img src="./dist/img/main-template.jpeg"
                                                                        alt="Young woman sitting on the sofa and working on her laptop"
                                                                        class="form-imagecheck-image">
                                                                </span>
                                                            </label>
                                                        </div>
                                                        <div class="col-6 col-sm-4">
                                                            <label class="form-imagecheck mb-2">
                                                                <input name="form-imagecheck-radio" type="radio"
                                                                    value="6" class="form-imagecheck-input">
                                                                <span class="form-imagecheck-figure">
                                                                    <img src="./dist/img/main-template.jpeg"
                                                                        alt="Coffee on a table with other items"
                                                                        class="form-imagecheck-image">
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="update-step-33" class="tab-pane" role="tabpanel"
                                            aria-labelledby="update-step-33" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <form class="form-horizontal" role="form">
                                                        <div class="mb-3">
                                                            <label class="form-label">To:</label>
                                                            <input type="text" class="form-control"
                                                                name="example-text-input" placeholder="Type email">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">CC:</label>
                                                            <input type="text" class="form-control"
                                                                name="example-text-input" placeholder="Type email">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">BCC:</label>
                                                            <input type="text" class="form-control"
                                                                name="example-text-input" placeholder="Type email">
                                                        </div>
                                                        <div class="mb-3">
                                                            <div class="btn-toolbar " role="toolbar">
                                                                <div class="btn-group mb-3">
                                                                    <button class="btn btn-default">
                                                                        <span class="fa fa-bold"></span>
                                                                    </button>
                                                                    <button class="btn btn-default">
                                                                        <span class="fa fa-italic"></span>
                                                                    </button>
                                                                    <button class="btn btn-default">
                                                                        <span class="fa fa-underline"></span>
                                                                    </button>
                                                                </div>
                                                                <div class="btn-group ms-3 mb-3">
                                                                    <button class="btn btn-default">
                                                                        <span class="fa fa-align-left"></span>
                                                                    </button>
                                                                    <button class="btn btn-default">
                                                                        <span class="fa fa-align-right"></span>
                                                                    </button>
                                                                    <button class="btn btn-default">
                                                                        <span class="fa fa-align-center"></span>
                                                                    </button>
                                                                    <button class="btn btn-default">
                                                                        <span class="fa fa-align-justify"></span>
                                                                    </button>
                                                                </div>
                                                                <div class="btn-group ms-3 mb-3">
                                                                    <button class="btn btn-default">
                                                                        <span class="fa fa-indent"></span>
                                                                    </button>
                                                                    <button class="btn btn-default">
                                                                        <span class="fa fa-outdent"></span>
                                                                    </button>
                                                                </div>
                                                                <div class="btn-group">
                                                                    <button class="btn btn-default">
                                                                        <span class="fa fa-list-ul"></span>
                                                                    </button>
                                                                    <button class="btn btn-default">
                                                                        <span class="fa fa-list-ol"></span>
                                                                    </button>
                                                                </div>
                                                                <div class="btn-group ms-3">
                                                                    <button class="btn btn-default">
                                                                        <span class="fa fa-trash-can"></span>
                                                                    </button>
                                                                    <button class="btn btn-default">
                                                                        <span class="fa fa-paperclip"></span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <textarea class="form-control" name="example-textarea-input" rows="6" placeholder="Content..">Oh! Come and see the violence inherent in the system! Help, help, I'm being repressed! We shall say 'Ni' again to you, if you do not appease us. I'm not a witch. I'm not a witch. Camelot!</textarea>
                                                        </div>
                                                        <div class="email-attachment">
                                                            <div class="file-info">
                                                                <div class="file-size">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="feather feather-paperclip">
                                                                        <path
                                                                            d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48">
                                                                        </path>
                                                                    </svg>
                                                                    <span>Attachment (2 MB)</span>
                                                                </div>
                                                                <button class="btn btn-sm btn-outline-primary me-2">View
                                                                    All</button>
                                                                <button class="btn btn-sm btn-outline-success">Download
                                                                    All</button>
                                                            </div>
                                                            <ul class="attachment-list">
                                                                <li class="attachment-list-item">
                                                                    <img src="./dist/img/template.jpg"" alt=" Showcase"
                                                                        title="Showcase">
                                                                </li>
                                                                <li class="attachment-list-item">
                                                                    <img src="./dist/img/main-template.jpeg"
                                                                        alt="Showcase" title="Showcase">
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-md-12 text-end">
                                                    <button type="button" class="btn btn-info">Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sw-toolbar-elm justify-content-between toolbar toolbar-bottom"
                                        role="toolbar">
                                        <button class="btn sw-btn-prev disabled" type="button">Previous</button><button
                                            class="btn btn-primary" type="button">Next</button>
                                    </div>
                                    <!-- Include optional progressbar HTML -->
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="modal-footer">
                       <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                       <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
                       </div> -->
                        </div>
                    </div>
                </div>
                <footer class="footer footer-transparent d-print-none">
                </footer>
            </div>
        </div>
        <div class="modal modal-blur fade" id="view-sales-order" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document" id="viewSalesModal">

            </div>
        </div>

        <div class="modal fade" id="detailsDialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Cleaner Schedule</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('schedule.create') }}" method="POST" id="detailsForm"
                            name="detailsForm">
                            @csrf
                            <input type="hidden" name="sales_order_no" id="sales_order_no">
                            <input type="hidden" name="customer_id" id="customer_id">
                            {{-- <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input service_type" type="radio" name="service_type"
                                        id="cleaning1" value="cleaning" checked>
                                    <label class="form-check-label" for="cleaning1">Cleaning</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input service_type" type="radio" name="service_type"
                                        id="airconCleaning" value="aircon">
                                    <label class="form-check-label" for="airconCleaning">Aircon & carpet</label>
                                </div>
                            </div> --}}
                            <div class="form-group">
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
                            <div class="form-group team">
                                <select class="form-control" id="team_id" name="team_id">
                                    <option value="" disabled selected>Select Team</option>
                                    @foreach ($get_team as $team)
                                        <option value="{{ $team->team_id }}">{{ $team->team_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group employee" style="display: none;">
                                <label for="employee_name">Employee List:</label>
                                <textarea class="form-control" id="employee_names" name="employee_names" rows="4" readonly></textarea>
                            </div>
                            <div class="form-group individual">
                                <select class="form-control" id="cleaner_id" name="cleaner_id">
                                    @foreach ($users as $user)
                                    <option value="{{ $user->user_id }}" {{ $user->user_id ? 'selected' : '' }} style="background-color: {{ $user->zone_color }}">{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-row">
                                <!-- Start Date and End Date in the same row -->
                                <div class="form-group col-md-6">
                                    <label for="startDate">Start Date:</label>
                                    <input type="date" class="form-control" id="startDate" name="startDate" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for ="endDate">End Date:</label>
                                    <input type="date" class="form-control" id="endDate" name="endDate" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <!-- Postal Code and Unit No in the same row -->
                                <div class="form-group col-md-6">
                                    <label for="postalCode">Postal Code:</label>
                                    <input type="text" class="form-control" id="postalCode" name="postalCode"
                                        required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="unitNo">Unit No:</label>
                                    <input type="text" class="form-control" id="unitNo" name="unitNo">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="total_session">Total Session:</label>
                                    <input type="text" class="form-control" id="total_session" name="total_session">
                                    <input type="hidden" id="get_hour" name="get_hour">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="frequency">Weekly Freq:</label>
                                    <input type="text" class="form-control" id="weekly_freq" name="weekly_freq">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="startTime">Start Time:</label>
                                    <input type="time" class="form-control" id="startTime" name="startTime" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="endTime">End Time:</label>
                                    <input type="time" class="form-control" id="endTime" name="endTime" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input dayslist" type="checkbox" id="dayMonday"
                                        name="days[]" value="Monday">
                                    <label class="form-check-label" for="dayMonday">Mon</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input dayslist" type="checkbox" id="dayTuesday"
                                        name="days[]" value="Tuesday">
                                    <label class="form-check-label" for="dayTuesday">Tue</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input dayslist" type="checkbox" id="dayWednesday"
                                        name="days[]" value="Wednesday">
                                    <label class="form-check-label" for="dayWednesday">Wed</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input dayslist" type="checkbox" id="dayThursday"
                                        name="days[]" value="Thursday">
                                    <label class="form-check-label" for="dayThursday">Thu</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input dayslist" type="checkbox" id="dayFriday"
                                        name="days[]" value="Friday">
                                    <label class="form-check-label" for="dayFriday">Fri</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input dayslist" type="checkbox" id="daySaturday"
                                        name="days[]" value="Saturday">
                                    <label class="form-check-label" for="daySaturday">Sat</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input dayslist" type="checkbox" id="daySunday"
                                        name="days[]" value="Sunday">
                                    <label class="form-check-label" for="daySunday">Sun</label>
                                </div>
                            </div>
                            <div class="calender">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" name="datepick" id="datePick" class="form-control" />
                                    </div>
                                </div>
                            </div><br>
                            <div class="form-group">
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
        <div class="modal modal-blur fade" id="edit-sales-order" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document" id="editSalesModal">

            </div>
        </div>
        <div class="modal modal-blur fade" id="edit-cleaner" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" id="edit-cleaner-model">

                </div>
            </div>
         </div>

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

        </script>

        <script>
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
        </script>
        <script>
            $(function() {
                $('#billing_address_33 .add-row').click(function() {
                    var template =
                        '<tr><td><input class="form-control" type="text" placeholder="Enter Code" /></td><td><input class="form-control" type="text" placeholder="Address"/></td><td><input class="form-control" type="text" placeholder="Enter Unit No"/></td><td><button type="button" class="btn btn-danger delete-row">-</button></td></tr>';
                    $('#billing_address_33 tbody').append(template);
                });
                $('#billing_address_33').on('click', '.delete-row', function() {
                    $(this).parent().parent().remove();
                });
            })
        </script>
        <script>
            $(function() {
                $('#service_address .add-row').click(function() {
                    var template =
                        '<tr><td><input class="form-control" type="text" placeholder="Enter Code" /></td><td><input class="form-control" type="text" placeholder="Address"/></td><td><input class="form-control" type="text" placeholder="Enter Unit No"/></td><td><button type="button" class="btn btn-danger delete-row">-</button></td></tr>';
                    $('#service_address tbody').append(template);
                });
                $('#service_address').on('click', '.delete-row', function() {
                    $(this).parent().parent().remove();
                });
            })
        </script>
        <script>
            $('#smartwizard').smartWizard({
                transition: {
                    animation: 'slideHorizontal', // Effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
                }
            });



            $('#smartwizard2').smartWizard({
                transition: {
                    animation: 'slideHorizontal', // Effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
                }
            });
            $('#smartwizard-3').smartWizard({
                transition: {
                    animation: 'slideHorizontal', // Effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
                }
            });
        </script>
        <script>
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
        </script>
        <script type="text/javascript">
            $("#rowAdder_22").click(function() {
                newRowAdd =
                    '<div class="row my-3" id="row">  <div class="col-md-4">' +
                    '<div class="form-group mb-3">' +
                    ' <label for="name">Person Incharge Name</label><input type="text" placeholder="Enter Name" name="name" class="form-control" required=""></div></div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"> <label for="name">Contact No</label><input type="text" placeholder="Enter Number" name="name" class="form-control" required=""> </div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Email Id</label><input type="text" placeholder="Enter Email" name="name" class="form-control" required=""></div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Postal Code</label><input type="text" placeholder="Enter Code" name="name" class="form-control" required=""></div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Zone</label><input type="text" placeholder="Enter Zone" name="name" class="form-control" required=""></div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Address</label><input type="text" placeholder="Enter Address" name="name" class="form-control" required=""> </div> </div>' +
                    '<div class="col-md-3"><div class="form-group mb-3"> <label for="name">Unit No</label><input type="text" placeholder="Enter Unit No." name="name" class="form-control" required=""> </div> </div>' +
                    '<div class="col-md-1" style="display: flex; align-items: center;">  <button type="button" class="btn btn-danger" id="DeleteRow">-</button></div></div>';

                $('#newinput_22').append(newRowAdd);
            });

            $("body").on("click", "#DeleteRow", function() {
                $(this).parents("#row").remove();
            })
        </script>
        <script type="text/javascript">
            $("#rowAdder-2").click(function() {
                newRowAdd =
                    '<div class="row my-3" id="row">  <div class="col-md-4">' +
                    '<div class="form-group mb-3">' +
                    ' <label for="name">Person Incharge Name</label><input type="text" placeholder="Enter Name" name="name" class="form-control" required=""></div></div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"> <label for="name">Contact No</label><input type="text" placeholder="Enter Number" name="name" class="form-control" required=""> </div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Email Id</label><input type="text" placeholder="Enter Email" name="name" class="form-control" required=""></div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Postal Code</label><input type="text" placeholder="Enter Code" name="name" class="form-control" required=""></div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Zone</label><input type="text" placeholder="Enter Zone" name="name" class="form-control" required=""></div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Address</label><input type="text" placeholder="Enter Address" name="name" class="form-control" required=""> </div> </div>' +
                    '<div class="col-md-3"><div class="form-group mb-3"> <label for="name">Unit No</label><input type="text" placeholder="Enter Unit No." name="name" class="form-control" required=""> </div> </div>' +
                    '<div class="col-md-1" style="display: flex; align-items: center;">  <button type="button" class="btn btn-danger" id="DeleteRow-2">-</button></div></div>';

                $('#newinput-2').append(newRowAdd);
            });

            $("body").on("click", "#DeleteRow-2", function() {
                $(this).parents("#row").remove();
            })
        </script>
        <script>
            $(function() {
                $('#billing_address_add_lead .add-row').click(function() {
                    var template =
                        '<tr><td><input class="form-control" type="text" placeholder="Enter Code" /></td><td><input class="form-control" type="text" placeholder="Address"/></td><td><select type="text" class="form-select" value=""><option value="121">Singapore</option><option value="329">India</option></select></td><td><button type="button" class="btn btn-danger delete-row">-</button></td></tr>';
                    $('#billing_address_add_lead tbody').append(template);
                });
                $('#billing_address_add_lead').on('click', '.delete-row', function() {
                    $(this).parent().parent().remove();
                });
            })
        </script>
        <script>
            $(function() {
                $('#billing_address_add_quot .add-row-edit').click(function() {
                    var template =
                        '<tr><td><input class="form-control" type="text" placeholder="Enter Code" /></td><td><input class="form-control" type="text" placeholder="Address"/></td><td><select type="text" class="form-select" value=""><option value="121">Singapore</option><option value="329">India</option></select></td><td><button type="button" class="btn btn-danger delete-row-edit">-</button></td></tr>';
                    $('#billing_address_add_quot tbody').append(template);
                });
                $('#billing_address_add_quot').on('click', '.delete-row-edit', function() {
                    $(this).parent().parent().remove();
                });
            })
        </script>
        <script>
            $(function() {
                $('#billing_address_34 .add-row').click(function() {
                    var template =
                        '<tr><td><input class="form-control" type="text" placeholder="Enter Code" /></td><td><input class="form-control" type="text" placeholder="Address"/></td><td><input class="form-control" type="text" placeholder="Enter Unit No"/></td><td><button type="button" class="btn btn-danger delete-row">-</button></td></tr>';
                    $('#billing_address_34 tbody').append(template);
                });
                $('#billing_address_34').on('click', '.delete-row', function() {
                    $(this).parent().parent().remove();
                });
            })
        </script>
        <script>
            $(function() {
                $('#billing_address_2 .add-row-2').click(function() {
                    var template =
                        '<tr><td><input class="form-control" type="text" placeholder="Enter Code" /></td><td><input class="form-control" type="text" placeholder="Address"/></td><td><input class="form-control" type="text" placeholder="Enter Unit No"/></td><td><button type="button" class="btn btn-danger delete-row-2">-</button></td></tr>';
                    $('#billing_address_2 tbody').append(template);
                });
                $('#billing_address_2').on('click', '.delete-row-2', function() {
                    $(this).parent().parent().remove();
                });
            })
        </script>
        <script>
            $(document).ready(function() {
                $('#myselection').on('change', function() {
                    var demovalue = $(this).val();
                    $("div.myDiv").hide();
                    $("#show" + demovalue).show();
                });
            });
        </script>
        <script type="text/javascript">
            $("#rowAdder").click(function() {
                newRowAdd =
                    '<div class="row my-3" id="row">  <div class="col-md-4">' +
                    '<div class="form-group mb-3">' +
                    ' <label for="name">Person Incharge Name</label><input type="text" placeholder="Enter Name" name="name" class="form-control" required=""></div></div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"> <label for="name">Contact No</label><input type="text" placeholder="Enter Number" name="name" class="form-control" required=""> </div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Email Id</label><input type="text" placeholder="Enter Email" name="name" class="form-control" required=""></div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Postal Code</label><input type="text" placeholder="Enter Code" name="name" class="form-control" required=""></div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Zone</label><input type="text" placeholder="Enter Zone" name="name" class="form-control" required=""></div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Address</label><input type="text" placeholder="Enter Address" name="name" class="form-control" required=""> </div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Country</label><select type="text" class="form-select" value=""><option value="11">India</option><option value="39">Singaore</option></select> </div> </div>' +
                    '<div class="col-md-3"><div class="form-group mb-3"> <label for="name">Unit No</label><input type="text" placeholder="Enter Unit No." name="name" class="form-control" required=""> </div> </div>' +
                    '<div class="col-md-1" style="display: flex; align-items: center;">  <button type="button" class="btn btn-danger" id="DeleteRow">-</button></div></div>';

                $('#newinput').append(newRowAdd);
            });

            $("body").on("click", "#DeleteRow", function() {
                $(this).parents("#row").remove();
            })
        </script>
        <script type="text/javascript">
            $("#rowAdder-edit").click(function() {
                newRowAdd =
                    '<div class="row my-3" id="row">  <div class="col-md-4">' +
                    '<div class="form-group mb-3">' +
                    ' <label for="name">Person Incharge Name</label><input type="text" placeholder="Enter Name" name="name" class="form-control" required=""></div></div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"> <label for="name">Contact No</label><input type="text" placeholder="Enter Number" name="name" class="form-control" required=""> </div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Email Id</label><input type="text" placeholder="Enter Email" name="name" class="form-control" required=""></div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Postal Code</label><input type="text" placeholder="Enter Code" name="name" class="form-control" required=""></div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Zone</label><input type="text" placeholder="Enter Zone" name="name" class="form-control" required=""></div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Address</label><input type="text" placeholder="Enter Address" name="name" class="form-control" required=""> </div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Country</label><select type="text" class="form-select" value=""><option value="11">India</option><option value="39">Singaore</option></select> </div> </div>' +
                    '<div class="col-md-3"><div class="form-group mb-3"> <label for="name">Unit No</label><input type="text" placeholder="Enter Unit No." name="name" class="form-control" required=""> </div> </div>' +
                    '<div class="col-md-1" style="display: flex; align-items: center;">  <button type="button" class="btn btn-danger" id="DeleteRow-edit">-</button></div></div>';

                $('#newinput-edit').append(newRowAdd);
            });

            $("body").on("click", "#DeleteRow-edit", function() {
                $(this).parents("#row").remove();
            })
        </script>
        <script>
            $(document).ready(function() {
                $(".productshow").click(function() {
                    $("#productcat").hide();
                });
                $(".productshow").click(function() {
                    $(".allproduct").show();
                });

                $("#back").click(function() {
                    $(".allproduct").hide();
                });
                $("#back").click(function() {
                    $("#productcat").show();
                });

                $(".productsub").click(function() {
                    $(".productsubshow").show();
                });
                $(".packagesub").click(function() {
                    $(".packagesubshow").show();
                });
                $(".productsubedit").click(function() {
                    $(".productsubshowedit").show();
                });
                $(".packagesubedit").click(function() {
                    $(".packagesubshowedit").show();
                });

            });
        </script>
        <script>
            function toggler(divId) {
                $("#" + divId).toggle();
            }

            function addBtn() {
                toggler('div');

            }
        </script>
        <script>
            function toggler(divId) {
                $("#" + divId).toggle();
            }

            function addBtn2() {
                toggler('div2');

            }
        </script>
        <script>
            $(document).ready(function() {
                $("#pills-profile-tab").click(function() {
                    $("#package-table").show();
                    $("#service-table").hide();

                });
                $("#pills-home-tab").click(function() {
                    $("#package-table").hide();
                    $("#service-table").show();
                });
            });
            $(document).ready(function() {
                $("#pills-profile-tab-edit").click(function() {
                    $("#package-table-edit").show();
                    $("#service-table-edit").hide();

                });
                $("#pills-home-tab-edit").click(function() {
                    $("#package-table-edit").hide();
                    $("#service-table-edit").show();
                });
            });
        </script>
        <script>
            $(document).ready(function() {



                $("#commercial-view-table").click(function() {
                    $("#residential-card").hide();
                    $("#commercial-card").show();

                });
                $("#residential-view-table").click(function() {

                    $("#commercial-card").hide();
                    $("#residential-card").show();
                });
            });
        </script>
        <script>
            $(document).ready(function() {



                $("#commercial-edit-table").click(function() {
                    $("#residential-card-edit").hide();
                    $("#commercial-card-edit").show();

                });
                $("#residential-edit-table").click(function() {

                    $("#commercial-card-edit").hide();
                    $("#residential-card-edit").show();
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                // Toggles paragraphs display
                $(".add_btn").click(function() {
                    $(".add_address").toggle();
                });
            });
            $(document).ready(function() {
                // Toggles paragraphs display
                $(".add_btn_edit").click(function() {
                    $(".add_address_edit").toggle();
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                // Toggles paragraphs display
                $(".add_btn_2").click(function() {
                    $(".add_address_2").toggle();
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                // Toggles paragraphs display
                $(".add_btn_2edit").click(function() {
                    $(".add_address_2edit").toggle();
                });
            });
        </script>
        {{-- <script src="{{ asset('theme/dist/libs/litepicker/dist/litepicker.js?1685976846') }}" defer></script> --}}
        <!-- Tabler Core -->
        <script>
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
        </script>
        <script>
            $("input[name=time]").clockpicker({
                placement: 'bottom',
                align: 'left',
                autoclose: true,
                default: 'now',
                donetext: "Select",
                init: function() {
                    console.log("colorpicker initiated");
                },
                beforeShow: function() {
                    console.log("before show");
                },
                afterShow: function() {
                    console.log("after show");
                },
                beforeHide: function() {
                    console.log("before hide");
                },
                afterHide: function() {
                    console.log("after hide");
                },
                beforeHourSelect: function() {
                    console.log("before hour selected");
                },
                afterHourSelect: function() {
                    console.log("after hour selected");
                },
                beforeDone: function() {
                    console.log("before done");
                },
                afterDone: function() {
                    console.log("after done");
                }
            });
        </script>

        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-multidatespicker/1.6.6/jquery-ui.multidatespicker.js">
        </script>
        <script>
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

            function editSalesOrder(salesOrderId) {
                // console.log(quotation_id);

                $.ajax({
                    type: 'GET',
                    url: '{{ route('sales.order.edit') }}',
                    data: {
                        salesOrderId: salesOrderId
                    },
                    success: function(response) {
                        // $('#view-quotation').modal('show');
                        $('#editSalesModal').html(response);

                    },
                });

            }

            function getAddress(id) {
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

                $('.dayslist:checked').each(function () {
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


                endDates.sort(function (a, b) {
                    return a - b;
                });


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
                    success: function (response) {
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
