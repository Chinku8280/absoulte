@extends('theme.default')
@section('content')
    <style>
        .fc .fc-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: space-between;
            align-items: center;
        }

        .fc .fc-toolbar.fc-header-toolbar {
            margin-bottom: 1.5em;
        }

        .fc .fc-toolbar.fc-footer-toolbar {
            margin-top: 1.5em;
        }

        .fc .fc-toolbar-title {
            font-size: 1.75em;
            margin: 0;
        }

        @media screen and (max-width:768px) {
            .fc .fc-toolbar {
                flex-direction: column;
                gap: 1rem;
            }
        }

        @media screen and (max-width:768px) {
            .fc .fc-toolbar-title {
                font-size: 1.5em;
            }
        }

        html,
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
            font-size: 14px;
        }

        #external-events {
            padding: 0 10px;
            border: 1px solid #e6e7e9;
            background: #f1f5f9;
        }

        #external-events .fc-event {
            margin: 1em 0;
            cursor: move;
            padding: 0.5rem;
            border: none;

        }

        #calendar-container {
            position: relative;
            z-index: 1;
            /* margin-left: 200px; */
        }

        #calendar {
            /* max-width: 1100px; */
            margin: 20px auto;
        }

        .fc-event {
            position: relative;
            display: block !important;
            font-size: 1rem;
            font-weight: bolder;
            line-height: normal;
            border-radius: 3px;
            border: 1px solid #3788d8;

        }

        .fc-event,
        .fc-event-dot {
            background-color: #3788d8;
        }

        .fc-event,
        .fc-event:hover {
            color: #fff;
            text-decoration: none;
        }

        #external-events .fc-event {
            margin: 1em 0;
            cursor: move;
        }

        .fc-toolbar-chunk:nth-child(2)>div {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .fc .fc-button-primary {
            border-radius: 55%;
            color: #fff !important;
            border-color: #13AFC6 !important;
            background-color: #13AFC6 !important;
        }

        .fc-todayButton-button {
            border-radius: 4px !important;
        }

        .fc-customSelect-button {
            background-color: transparent !important;
            border: none !important;
            color: transparent !important;
        }

        #roster-select {
            color: #000000;
        }

        .fc-date-picker-button {
            display: none !important;
        }

        #event-card {
            position: fixed;
            background-color: #fff;
            box-shadow: 0px 4.4px 12px -1px rgba(19, 16, 34, 0.06), 0px 2px 6.4px -1px rgba(19, 16, 34, 0.03);
            border: 1px solid red;
            border-radius: 15px;
            padding: 10px;
            z-index: 999;
            width: 20em
                /* Adjust the initial width as needed for your design */
                opacity: 0;
            /* Start with zero opacity */
            overflow: hidden;
            transition: all 0.3s ease;
            /* Transition for a smooth effect */
            top: 0;
            /* Start at the top of the viewport */
            left: 0;
            /* Start at the left of the viewport */
        }

        #event-card.show {
            opacity: 1;
            /* Make the card visible with full opacity */
            width: 300px;
            /* Adjust the width as needed for your design */
            height: auto;
            /* Allow height to auto-expand based on content */
        }

        /* Optional styling for the card title and content */
        #event-card.show h2 {
            font-size: 16px;
            margin-bottom: 10px;
        }

        #event-card.show div {
            font-weight: bold;
            line-height: 1.25em;
            font-family: -apple-system, Segoe UI, Roboto, sans-serif;
            font-size: 1rem;
            /* border-left: 2px solid red; */
            padding-left: 10px;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc
        }

        .bg-div {
            background-color: red;
            border-radius: 0.125em;
            margin: 0 0.5em 0 -0.5em;
            display: inline-block;
            width: 0.25em;
            border-radius: 0.5rem;
        }

        #event-card.show div:last-child {
            margin-bottom: 0 !important;
            border-bottom: none !important
        }

        #event-card.show p {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .popover {
            display: none;
            position: absolute;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            z-index: 999;
            /* Ensure it appears above other elements */
        }

        /* Style for the content within the popover */
        .popover-content {
            color: #333;
        }

        .event-title,
        .event-contact-no,
        .event-schedule-date,
        .event-time-of-cleaning {
            font-weight: normal;
            font-size: small;
        }
    </style>

    <div class="page-wrapper">
        <div class="page-header d-print-none">
            <div class="container-fluid">
                <div class="row g-2 align-items-center mb-3">
                    <div class="col">
                        <h2 class="page-title">
                            Schedule
                        </h2>
                    </div>
                    <!-- Page title actions -->
                </div>
                {{-- <div class="row g-2 align-items-center">
                    <div class="col">

                        <ul class="nav nav-pills nav-pills-primary" data-bs-toggle="tabs" role="tablist">
                            @foreach (App\Models\Company::all() as $val)
                            <li class="nav-item me-2" role="presentation">
                                <a href="#north-zone" class="nav-link " data-bs-toggle="tab" aria-selected="true"
                                    role="tab">Cleaning</a>
                            </li>
                            <li class="nav-item me-2" role="presentation">
                                <a href="#north-zone" class="nav-link " data-bs-toggle="tab" aria-selected="true"
                                    role="tab">Aircon & Carpet</a>
                            </li>
                             @endforeach
                        </ul>
                    </div>
                </div> --}}
            </div>
        </div>
        <div class="page-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-9" id="calendar_group">
                        <div id='detail_calendar'></div>
                        <div id="event-card"></div>
                    </div>
                    <div class="col-md-3" id="unassign_job_group">
                        <div class="card">
                            <div class="card-header justify-content-center">
                                <h2 class="m-0" style="color: #13AFC6;"><b>UNASSIGN JOB</b></h2>
                            </div>
                            <div class="card-body">
                                <div id='external-events'
                                    data-event-id="{{ isset($events[0]['id']) ? $events[0]['id'] : '' }}">
                                    @foreach ($unassign_job as $event)
                                        <div class='fc-event card-click' onclick="ac_getAddress({{ $event['sales_order_id'] }})" 
                                            data-event-id="{{ $event['id'] }}" 
                                            data-service-details ="{{ $event['quotation_services_details'] }}"
                                            data-services="{{ $event['get_total_session_weekly_freq'] }}"
                                            data-customer_type="{{ $event['customer_type'] }}"
                                            data-name="{{ $event['customer_name'] }}" 
                                            data-company_name="{{ $event['individual_company_name'] }}"
                                            data-address="{{ $event['address'] }}"
                                            data-postalcode={{ $event['postal_code'] }}
                                            data-color="{{ $event['zone_color'] }}" data-email="{{ $event['email_id'] }}"
                                            data-mobile="{{ $event['contact_no'] }}"
                                            data-service="{{ $event['serviceName'] }}"
                                            data-quantity="{{ $event['quantity'] }}" data-price="{{ $event['price'] }}"
                                            data-cleaner-name="{{ $event['cleanerName'] }}"
                                            data-cleaning-date="{{ $event['cleaningDate'] }}"
                                            data-cleaning-time="{{ $event['cleaningTime'] }}"
                                            data-customer-id="{{ $event['customer_id'] }}"
                                            data-sales_order_id = "{{ $event['sales_order_id'] }}"
                                            style="background-color: {{ $event['zone_color'] }}">
                                            <div class="event-title">
                                                @if ($event['customer_name'] !== null)
                                                    @if ($event->customer_type == "residential_customer_type")
                                                        <strong>Customer Name: </strong>{{ $event['customer_name'] }}
                                                    @elseif ($event->customer_type == "commercial_customer_type")
                                                        <strong>Company Name: </strong>{{ $event['individual_company_name'] }}
                                                    @endif  
                                                @else
                                                    {{-- <strong>Name: </strong> {{ $event->cleaner_type }} --}}
                                                @endif
                                            </div>
                                            <div class="event-address" style="font-size: small;">Address :
                                                {{ $event->address }}</div>
                                            <div class="event-contact-no"><strong>Contact No :</strong>
                                                {{ $event->contact_no }}</div>
                                            <div class="event-schedule-date"><strong>Date : </strong>
                                                {{ $event->schedule_date }}</div>
                                            <div class="event-time-of-cleaning"><strong>Time :</strong>
                                                {{ $event->time_of_cleaning }}</div>
                                            <div class="event-time-of-cleaning">
                                                @if ($event->customer_type == "residential_customer_type")
                                                    <strong>Type of Job : Residential</strong>
                                                @elseif ($event->customer_type == "commercial_customer_type")
                                                    <strong>Type of Job : Commercial</strong>
                                                @endif                                             
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal modal-blur fade" id="eventDetailsModal" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" style="max-width: 1000px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Event Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <form id="eventDetailsForm" class="row">
                            <ul class="nav nav-tabs" id="detailsTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="customer-tab" data-bs-toggle="tab"
                                        href="#customer-details" role="tab" aria-controls="customer-details"
                                        aria-selected="true">Customer Details</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="cleaning-tab" data-bs-toggle="tab" href="#cleaning-details"
                                        role="tab" aria-controls="cleaning-details" aria-selected="false">Cleaning
                                        Details</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="detailsTabsContent">
                                <div class="tab-pane fade show active" id="customer-details" role="tabpanel"
                                    aria-labelledby="customer-tab">
                                    <!-- Existing Customer Details Fields -->
                                    <div class="row">
                                        <div class="col-md-4 form-group mb-3">
                                            <label class="form-label">Customer Name:</label>
                                            <input type="text" id="customerName" class="form-control" disabled>
                                        </div>
                                        <div class="col-md-4 form-group mb-3" id="companyName_group" style="display: none;">
                                            <label class="form-label">Company Name:</label>
                                            <input type="text" id="companyName" class="form-control" disabled>
                                        </div>
                                        <div class="col-md-4 form-group mb-3">
                                            <label class="form-label">Email ID</label>
                                            <input type="email" id="email" class="form-control" disabled>
                                        </div>
                                        <div class="col-md-4 form-group mb-3">
                                            <label class="form-label">Mobile Number</label>
                                            <input type="tel" id="mobileNumber" class="form-control" disabled>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <label class="form-label">Address</label>
                                            <textarea class="form-control" name="" id="address" cols="30" rows="3" disabled></textarea>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <h2 class="text-primary">Service Details</h2>
                                            <hr class="my-2">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="table-responsive">
                                                <table class="table w-100 text-center table-bordered border-primary">
                                                    <thead>
                                                        <tr>
                                                            <td>S.No</td>
                                                            <td>Service Name</td>
                                                            <td>Quantity</td>
                                                            <td>Price</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="serviceDetailsBody">
                                                        <!-- Populate service details dynamically using JavaScript -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-12 mb-2">
                                            <h2 class="text-primary">Cleaning Details</h2>
                                            <hr class="my-2">
                                        </div> --}}
                                        <div class="row">
                                            <div class="col-md-4 form-group mb-3">
                                                {{-- <label type="hidden" class="form-label">Cleaner Name:</label> --}}
                                                <input type="hidden" name="cleanerName" id="cleanerName"
                                                    class="form-control" disabled>
                                            </div>
                                            <div class="col-md-4 form-group mb-3">
                                                {{-- <label type="hidden" class="form-label">Cleaning Date</label> --}}
                                                <input type="hidden" name="cleaningDate" id="cleaningDate"
                                                    class="form-control" disabled>
                                            </div>
                                            <div class="col-md-4 form-group mb-3">
                                                {{-- <label type="hidden" class="form-label">Cleaning Time</label> --}}
                                                <input type="hidden" name="cleaningTime" id="cleaningTime"
                                                    class="form-control" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="cleaning-details" role="tabpanel"
                                    aria-labelledby="cleaning-tab">
                                    <!-- Additional Cleaning Details Fields -->
                                    <div class="modal-body">
                                        <form id="eventUpdateform" name="eventUpdateform" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="event_id" id="event_id">
                                            <input type="hidden" name="sales_order_no" id="sales_order_no"
                                                value="">
                                            <input type="hidden" name="customer_id" id="customer_id" value="">
                                            <input type="hidden" name="service_id" id="service_id" value="">
                                            <div class="form-group ">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input cleaner_type" type="radio"
                                                        name="cleaner_type" id="team" value="team" required
                                                        checked>
                                                    <label class="form-check-label" for="team">Team</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input cleaner_type" type="radio"
                                                        name="cleaner_type" id="individual" value="individual" required>
                                                    <label class="form-check-label" for="individual">Individual</label>
                                                </div>
                                            </div>
                                            <div class="form-group team mb-3">
                                                <select class="form-control" id="team_id" name="team_id">
                                                </select>
                                            </div>

                                            <div class="form-group individual mb-3">
                                                <select class="form-control" id="cleaner_id" name="cleaner_id">
                                                    {{-- Populate cleaner options dynamically --}}
                                                </select>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="form-group col-md-6">
                                                    <label for="startDate">Start Date:</label>
                                                    <input type="date" class="form-control startDate" id="startDate"
                                                        name="startDate" value="" required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="endDate">End Date:</label>
                                                    <input type="date" class="form-control endDate" id="endDate"
                                                        value="" name="endDate" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="form-group col-md-6">
                                                    <label for="postalCode">Postal Code:</label>
                                                    <input type="text" class="form-control" id="postalCode"
                                                        name="postalCode" value="" required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="unitNo">Unit No:</label>
                                                    <input type="text" class="form-control" id="unitNo"
                                                        value="" name="unitNo">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="form-group">
                                                    <label for="address">Address:</label>
                                                    <textarea type="text" class="form-control" id="cleaning_address" name="address" value="" required></textarea>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="form-group col-md-6">
                                                    <label for="total_session">Total Session:</label>
                                                    <input type="text" class="form-control total_session"
                                                        id="total_session" name="total_session" value="">
                                                    <input type="hidden" id="get_hour" name="get_hour">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="frequency">Weekly Freq:</label>
                                                    <input type="text" class="form-control weekly_freq"
                                                        id="weekly_freq" name="weekly_freq" value="">
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="form-group col-md-6">
                                                    <label for="startTime">Start Time:</label>
                                                    <input type="time" class="form-control" id="startTime"
                                                        name="startTime" value="" required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="endTime">End Time:</label>
                                                    <input type="time" class="form-control" id="endTime"
                                                        name="endTime" value="" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input dayslist" type="checkbox"
                                                        id="dayMonday" name="days[]" value="Monday">
                                                    <label class="form-check-label" for="dayMonday">Mon</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input dayslist" type="checkbox"
                                                        id="dayTuesday" name="days[]" value="Tuesday">
                                                    <label class="form-check-label" for="dayTuesday">Tue</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input dayslist" type="checkbox"
                                                        id="dayWednesday" name="days[]" value="Wednesday">
                                                    <label class="form-check-label" for="dayWednesday">Wed</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input dayslist" type="checkbox"
                                                        id="dayThursday" name="days[]" value="Thursday">
                                                    <label class="form-check-label" for="dayThursday">Thu</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input dayslist" type="checkbox"
                                                        id="dayFriday" name="days[]" value="Friday">
                                                    <label class="form-check-label" for="dayFriday">Fri</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input dayslist" type="checkbox"
                                                        id="daySaturday" name="days[]" value="Saturday">
                                                    <label class="form-check-label" for="daySaturday">Sat</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input dayslist" type="checkbox"
                                                        id="daySunday" name="days[]" value="Sunday">
                                                    <label class="form-check-label" for="daySunday">Sun</label>
                                                </div>
                                            </div>
                                            <div class="calender">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <textarea type="text" name="datepick" id="cleaning_datePick" class="form-control datePick" value=""></textarea>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="form-group">
                                                <label for="addrcustomer_remarkess">Remark:</label>
                                                <input type="text" class="form-control" id="customer_remark"
                                                    name="customer_remark" value=" ">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary"
                                                    id="saveButton">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="UnassigneventDetailsModal" tabindex="-1" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" style="max-width: 1000px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Event Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row">
                        <ul class="nav nav-tabs" id="detailsTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="unassign_customer_tab" data-bs-toggle="tab"
                                    href="#unassign_customer_details" role="tab" aria-controls="unassign_customer_details"
                                    aria-selected="true">Customer Details</a>
                            </li>

                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="assign_cleaner_tab" data-bs-toggle="tab"
                                    href="#assign_cleaner_details" role="tab" aria-controls="assign_cleaner_details"
                                    aria-selected="true">Assign Cleaner</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="UnassigneventContent">
                            <div class="tab-pane fade show active" id="unassign_customer_details" role="tabpanel"
                                aria-labelledby="unassign_customer_tab">
                                <!-- Existing Customer Details Fields -->
                                <div class="row">
                                    <div class="col-md-4 form-group mb-3">
                                        <label class="form-label">Customer Name:</label>
                                        <input type="text" id="UnassigncustomerName" class="form-control" disabled>
                                    </div>
                                    <div class="col-md-4 form-group mb-3" id="UnassigncompanyName_group" style="display: none;">
                                        <label class="form-label">Company Name:</label>
                                        <input type="text" id="UnassigncompanyName" class="form-control" disabled>
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                        <label class="form-label">Email ID</label>
                                        <input type="email" id="Unassignemail" class="form-control" disabled>
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                        <label class="form-label">Mobile Number</label>
                                        <input type="tel" id="UnassignmobileNumber" class="form-control" disabled>
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control" name="" id="Unassignaddress" cols="30" rows="3" disabled></textarea>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <h2 class="text-primary">Service Details</h2>
                                        <hr class="my-2">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="table-responsive">
                                            <table class="table w-100 text-center table-bordered border-primary">
                                                <thead>
                                                    <tr>
                                                        <td>S.No</td>
                                                        <td>Service Name</td>
                                                        <td>Quantity</td>
                                                        <td>Price</td>
                                                    </tr>
                                                </thead>
                                                <tbody id="UnassignserviceDetailsBody">
                                                    <!-- Populate service details dynamically using JavaScript -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="assign_cleaner_details" role="tabpanel"
                                aria-labelledby="assign_cleaner_tab">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form action="{{ route('schedule.create') }}" method="POST" id="detailsForm" name="detailsForm" class="mt-2">
                                            @csrf
                                            <input type="hidden" name="sales_order_no" id="ac_sales_order_no">
                                            <input type="hidden" name="customer_id" id="ac_customer_id">
    
                                            <div class="form-group">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input ac_cleaner_type" type="radio"
                                                        name="cleaner_type" id="ac_team" value="team" required checked>
                                                    <label class="form-check-label" for="ac_team">Team</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input ac_cleaner_type" type="radio"
                                                        name="cleaner_type" id="ac_individual" value="individual" required>
                                                    <label class="form-check-label" for="ac_individual">Individual</label>
                                                </div>
                                            </div>

                                            <div class="form-group ac_team mb-3">
                                                <select class="form-control" id="ac_team_id" name="team_id">
                                                    <option value="" disabled selected>Select Team</option>
                                                    @foreach ($ac_get_team as $team)
                                                        <option value="{{ $team->team_id }}">{{ $team->team_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group ac_employee" style="display: none;">
                                                <label for="ac_employee_names">Employee List:</label>
                                                <textarea class="form-control" id="ac_employee_names" name="employee_names" rows="4" readonly></textarea>
                                            </div>

                                            <div class="form-group ac_individual">
                                                <select class="form-control" id="ac_cleaner_id" name="cleaner_id">
                                                    @foreach ($ac_users as $user)
                                                        <option value="{{ $user->user_id }}"
                                                            {{ $user->user_id ? 'selected' : '' }}
                                                            style="background-color: {{ $user->zone_color }}">
                                                            {{ $user->full_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="row mb-3">
                                                <!-- Start Date and End Date in the same row -->
                                                <div class="form-group col-md-6">
                                                    <label for="ac_startDate">Start Date:</label>
                                                    <input type="date" class="form-control" id="ac_startDate"
                                                        name="startDate" required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for ="ac_endDate">End Date:</label>
                                                    <input type="date" class="form-control" id="ac_endDate" name="endDate"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <!-- Postal Code and Unit No in the same row -->
                                                <div class="form-group col-md-6">
                                                    <label for="ac_postalCode">Postal Code:</label>
                                                    <input type="text" class="form-control" id="ac_postalCode"
                                                        name="postalCode" required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="ac_unitNo">Unit No:</label>
                                                    <input type="text" class="form-control" id="ac_unitNo" name="unitNo">
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="form-group">
                                                    <label for="ac_address">Address:</label>
                                                    <input type="text" class="form-control" id="ac_address" name="address"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="form-group col-md-6">
                                                    <label for="ac_total_session">Total Session:</label>
                                                    <input type="text" class="form-control" id="ac_total_session"
                                                        name="total_session">
                                                    <input type="hidden" id="ac_get_hour" name="get_hour">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="ac_weekly_freq">Weekly Freq:</label>
                                                    <input type="text" class="form-control" id="ac_weekly_freq"
                                                        name="weekly_freq">
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="form-group col-md-6">
                                                    <label for="ac_startTime">Start Time:</label>
                                                    <input type="time" class="form-control" id="ac_startTime"
                                                        name="startTime" required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="ac_endTime">End Time:</label>
                                                    <input type="time" class="form-control" id="ac_endTime" name="endTime"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input ac_dayslist" type="checkbox" id="ac_dayMonday"
                                                        name="days[]" value="Monday">
                                                    <label class="form-check-label" for="dayMonday">Mon</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input ac_dayslist" type="checkbox" id="ac_dayTuesday"
                                                        name="days[]" value="Tuesday">
                                                    <label class="form-check-label" for="dayTuesday">Tue</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input ac_dayslist" type="checkbox"
                                                        id="ac_dayWednesday" name="days[]" value="Wednesday">
                                                    <label class="form-check-label" for="dayWednesday">Wed</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input ac_dayslist" type="checkbox" id="ac_dayThursday"
                                                        name="days[]" value="Thursday">
                                                    <label class="form-check-label" for="dayThursday">Thu</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input ac_dayslist" type="checkbox" id="ac_dayFriday"
                                                        name="days[]" value="Friday">
                                                    <label class="form-check-label" for="dayFriday">Fri</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input ac_dayslist" type="checkbox" id="ac_daySaturday"
                                                        name="days[]" value="Saturday">
                                                    <label class="form-check-label" for="daySaturday">Sat</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input ac_dayslist" type="checkbox" id="ac_daySunday"
                                                        name="days[]" value="Sunday">
                                                    <label class="form-check-label" for="daySunday">Sun</label>
                                                </div>
                                            </div>
                                            <div class="calender">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <input type="text" name="datepick" id="ac_datePick"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div><br>
                                            <div class="form-group">
                                                <label for="addrcustomer_remarkess">Remark:</label>
                                                <input type="text" class="form-control" id="ac_customer_remark"
                                                    name="customer_remark">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary" id="ac_saveButton">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.9/index.global.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>

    <script>
        $(document).ready(function() {

            $('#eventDetailsModal').on('shown.bs.modal', function() {
                // Trigger a click event on the 'Cleaning Details' tab
                $('#customer-tab').tab('show');
            });

            // $('#eventDetailsModal').on('shown.bs.modal', function() {

            //     $(".individual").hide();

            //     $(".cleaner_type").click(function() {
            //         if ($(this).val() == "team") {
            //             $(".individual").hide();

            //             $(".team").show();

            //         } else {
            //             $(".team").hide();

            //             $(".individual").show();

            //         }
            //     });

            // });
            $("#saveButton").click(function(e) {
                e.preventDefault();
                let id = $('#event_id').val();
                let data = new FormData();
                data.append('event_id', $('#event_id').val());
                data.append('sales_order_no', $('#sales_order_no').val());
                data.append('customer_id', $('#customer_id').val());
                data.append('service_id', $('#service_id').val());
                data.append('cleaner_type', $("input[name='cleaner_type']:checked").val());
                data.append('team_id', $('#team_id').val());
                let cleanerType = $("input[name='cleaner_type']:checked").val();
                if (cleanerType === 'team') {
                    data.append('cleaner_type', cleanerType);
                    data.append('team_id', $('#team_id').val());
                    data.append('cleaner_id', null); // Set cleaner_id to null for team
                } else if (cleanerType === 'individual') {
                    data.append('cleaner_type', cleanerType);
                    data.append('team_id', null); // Set team_id to null for individual
                    data.append('cleaner_id', $('#cleaner_id').val());
                }
                data.append('startDate', $('#startDate').val());
                data.append('endDate', $('#endDate').val());
                data.append('postalCode', $('#postalCode').val());
                data.append('unitNo', $('#unitNo').val());
                data.append('address', $('#cleaning_address').val());
                data.append('total_session', $('#total_session').val());
                data.append('get_hour', $('#get_hour').val());
                data.append('weekly_freq', $('#weekly_freq').val());
                data.append('startTime', $('#startTime').val());
                data.append('endTime', $('#endTime').val());
                data.append('cleaning_datePick', $('#cleaning_datePick').val());

                // Days Checkbox
                let days = [];
                $('.dayslist:checked').each(function() {
                    days.push($(this).val());
                });
                data.append('days', JSON.stringify(days));

                // Other Form Fields
                data.append('customer_remark', $('#customer_remark').val());

                $.ajax({
                    url: "{{ route('schedule.update', '') }}/" + id,
                    type: "POST",
                    data: data,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        $('#eventDetailsModal').modal('hide');
                        location.reload();
                    },
                    error: function(error) {
                        console.error('Ajax call failed:', error);
                        // Handle error
                    }
                });
            });

        });


        document.addEventListener('DOMContentLoaded', function() {

            var events = @json($events);
            var resources = @json($resources);
            // console.log(events);

            var Calendar = FullCalendar.Calendar;
            var Draggable = FullCalendar.Draggable;

            var containerEl = document.getElementById('external-events');
            var calendarEl = document.getElementById('detail_calendar');
            var checkbox = document.getElementById('drop-remove');
            var eventCardEl = document.getElementById('event-card');
            var currentEventId = null;

            var calendar = new Calendar(calendarEl, {
                timeZone: 'local',
                initialView: 'resourceTimelineDay',
                aspectRatio: 1.5,
                slotDuration: '00:30:00',
                editable: true,
                eventResourceEditable: true,
                headerToolbar: {
                    left: 'date-picker',
                    center: 'prev,title,next',
                    right: 'todayButton'
                },
                slotMinTime: '07:00:00',
                slotMaxTime: '18:00:00',
                eventDrop: function(info) {

                    if (info.oldResource !== info.newResource) {
                        info.revert();
                    }
                },
                resourceAreaHeaderContent: 'Team Members',
                resources: resources,
                resourceLabelContent: function(arg) {
                    var resource = arg.resource;
                    placeholder
                    var backgroundColor = resource.extendedProps.color;
                    return '<div style="background-color: ' + backgroundColor + ';">' + resource.title +
                        '</div>';
                },
                resourceLabelContent: function(arg) {

                    const customHtml = `<div class="fc-datagrid-cell-cushion fc-scrollgrid-sync-inner"
                                    data-flag="${arg.resource.extendedProps.flag}">
                                        <span class="fc-datagrid-expander fc-datagrid-expander-">
                                            <span class="fc-icon"></span>
                                        </span>
                                        <span class="fc-datagrid-cell-main">${arg.resource.title}</span>
                                    </div>`;

                    return {
                        html: customHtml,
                        style: 'background-color: ' + arg.resource.extendedProps.color +
                            '; font-weight: bold;',
                    };
                },

                resourceLabelDidMount: function(arg) {
                    var label = arg.el;
                    var resource = arg.resource;
                    var backgroundColor = resource.extendedProps.color;
                    label.style.backgroundColor = backgroundColor;
                    label.style.fontWeight = 'bold';

                },

                droppable: true,
                drop: function(info) {
                    // console.log(info);
                    var customerID = info.draggedEl.getAttribute('data-customer-id');
                    var Service_Details = info.draggedEl.getAttribute('data-services');
                    //  var Service_Details = info.draggedEl.getAttribute('data-service-details');
                    // console.log('Service Details:', Service_Details);

                    var title = info.resource.title;
                    var dateStr = info.dateStr;

                    var date = new Date(dateStr).toLocaleDateString();
                    var time = new Date(dateStr).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    var newdate = new Date(dateStr).toISOString().split('T')[0];

                    document.getElementById('cleanerName').value = title;
                    document.getElementById('cleaningDate').value = date;
                    document.getElementById('cleaningTime').value = time;


                    localStorage.setItem("title", title);
                    localStorage.setItem("date", date);
                    localStorage.setItem("time", time);

                    var empId = info.resource._resource.id;
                    var dataDate = info.resource._context.viewTitle;
                    var startTime = info.dateStr;
                    var resourceType = info.resource._resource.extendedProps.flag;

                    eventUpdate(customerID, empId, dataDate, startTime, resourceType, newdate,
                        Service_Details);

                    info.draggedEl.parentNode.removeChild(info.draggedEl);

                    setTimeout(function() {
                        location.reload();
                    }, 300);
                },

                eventClick: function(info) {
                    var event = info.event.extendedProps;
                    var event_id = info.event._def.publicId;
                    // console.log("event:", event);
                    // $('#eventDetailsModal').modal('show');
                    $('#customerName').val(event.customerName);
                    $('#email').val(event.email);
                    $('#mobileNumber').val(event.mobileNumber);
                    $('#address').val(event.address);
                    $('#event_id').val(event_id);
                    $('#cleanerName').val(localStorage.getItem("title"));
                    $('#cleaningDate').val(localStorage.getItem("date"));
                    $('#cleaningTime').val(localStorage.getItem("time"));

                    // Clear previous service details
                    $('#serviceDetailsBody').empty();

                    $.each(event.ServiceDetails, function(index, serviceDetail) {
                        var row = '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + (serviceDetail.name ? serviceDetail.name : '') + '</td>' +
                            '<td>' + (serviceDetail.quantity ? serviceDetail.quantity : '') +
                            '</td>' +
                            '<td>' + (serviceDetail.unit_price ? serviceDetail.unit_price :
                                '') + '</td>' +
                            '</tr>';
                        $('#serviceDetailsBody').append(row);
                    });

                    $('#eventDetailsModal').modal('show');
                    getDataFromSchedule(event_id);

                },

                eventContent: function(info) {
                    var title = info.event.title.replace(/\n/g, '<br>');
                    var startTime = moment(info.event.start).format('h:mma');

                    if(info.event.end)
                    {
                        var endTime = moment(info.event.end).format('h:mma');
                    }
                    else
                    {
                        var temp_start_dt = info.event.start;                  
                        var temp_end_dt = new Date(temp_start_dt.setTime(temp_start_dt.getTime() + 1 * 60 * 60 * 1000));

                        var endTime = moment(temp_end_dt).format('h:mma');
                    }

                    var postalCode = info.event.extendedProps.postal_code || info.event.extendedProps
                        .postalCode;
                    var eventContent = '<div style="font-weight: normal; font-size: 12px;">' +
                        startTime + ' - ' +
                        endTime + '</div>' + '<span style="font-weight: normal; font-size: 12px;">' +
                        title + '-' + postalCode + '</span>';

                    return {
                        html: eventContent
                    };
                },
                eventDragStop: function(info) {
                    var jsEvent = info.jsEvent;
                    var event = info.event;

                    if (jsEvent) {
                        if (isEventOverDiv(jsEvent.clientX, jsEvent.clientY)) {
                            event.remove();
                            var externalEventsContainer = document.getElementById('external-events');

                            var newEvent = document.createElement('div');
                            newEvent.className = 'fc-event';
                            newEvent.innerText = event.title;

                            newEvent.style.backgroundColor = event.backgroundColor;
                            newEvent.setAttribute('data-address', ''); // Set address as needed

                            newEvent.addEventListener('dragend', function(e) {
                                var offsetX = e.clientX - externalEventsContainer
                                    .getBoundingClientRect().left;
                                var offsetY = e.clientY - externalEventsContainer
                                    .getBoundingClientRect().top;
                                newEvent.style.left = offsetX + 'px';
                                newEvent.style.top = offsetY + 'px';
                                externalEventsContainer.appendChild(newEvent);
                            });

                            externalEventsContainer.appendChild(newEvent);
                            info.revert = true;
                        }
                    }
                },
                eventResize: function(info) {
                    //console.log(info);
                    var startTime = moment(info.event.start).format('h:mma');
                    var endTime = moment(info.event.end).format('h:mma');
                    var empId = info.el.closest('td').getAttribute('data-resource-id');

                    eventTimeUpdate(startTime, endTime, empId);

                },

            });

            // $('.fc-event').on('mouseenter', function(e) {
            //     var $this = $(this);
            //     var name = $this.data('name');
            //     var address = $this.data('address');
            //     var popover = $('<div class="popover"></div>');
            //     var popoverContent = $('<div class="popover-content"></div>');

            //     popoverContent.html('Name: ' + name + '<br>Address: ' + address);
            //     popover.append(popoverContent);


            //     var offset = $this.offset();
            //     var top = offset.top + $this.height() + 10;
            //     var left = offset.left;

            //     popover.css({
            //         top: top,
            //         left: left,
            //     });

            //     // Show the popover
            //     popover.appendTo('body').show();
            // });

            // $('.fc-event').on('mouseleave', function() {
            //     // Hide the popover
            //     $('.popover').remove();
            // });


            new Draggable(containerEl, {
                itemSelector: '.fc-event', // Specify the selector for draggable items
                eventData: function(eventEl) {
                    //console.log(eventEl);
                    return {
                        title: eventEl.getAttribute('data-name'),
                        customerName: eventEl.getAttribute('data-name'),
                        email: eventEl.getAttribute('data-email'),
                        mobileNumber: eventEl.getAttribute('data-mobile'),
                        address: eventEl.getAttribute('data-address'),
                        color: eventEl.getAttribute('data-color'),
                        EventId: eventEl.getAttribute('data-event-id'),
                        postalCode: eventEl.getAttribute('data-postalcode'),
                        cleanerName: eventEl.getAttribute('data-cleaner-name'),
                        cleaningDate: eventEl.getAttribute('data-cleaning-date'),
                        cleaningTime: eventEl.getAttribute('data-cleaning-time')

                    };
                },
                passive: true,
            });

            var cards = document.querySelectorAll('.card-click');
            cards.forEach(function(card) {
                card.addEventListener('click', function() {
                    openEventDetailsModal(card);
                });
            });

            function openEventDetailsModal(card) {
                // Extract data from the clicked card
                // console.log(card);
                var customerName = card.dataset.name;
                var companyName = card.dataset.company_name;
                var Email = card.dataset.email;
                var Mobile = card.dataset.mobile;
                var eventAddress = card.dataset.address;
                var eventServiceDetails = JSON.parse(card.dataset.serviceDetails); // Parse the JSON string

                // Update the modal input fields with the extracted data

                $('#UnassigncustomerName').val(customerName);
                $('#UnassigncompanyName').val(companyName);

                if(card.dataset.customer_type == "residential_customer_type")
                {                   
                    $("#UnassigncompanyName_group").hide();
                }
                else if(card.dataset.customer_type == "commercial_customer_type")
                {
                    $("#UnassigncompanyName_group").show();
                }

                $('#Unassignemail').val(Email);
                $('#UnassignmobileNumber').val(Mobile);
                $('#Unassignaddress').val(eventAddress);

                // Update the service details table
                var tableBody = document.getElementById('UnassignserviceDetailsBody');
                tableBody.innerHTML = ''; // Clear existing rows

                // Check if eventServiceDetails is defined and iterable
                if (eventServiceDetails && Array.isArray(eventServiceDetails)) {
                    // Iterate over service details and add rows to the table
                    eventServiceDetails.forEach(function(detail, index) {
                        var row = tableBody.insertRow(index);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        var cell3 = row.insertCell(2);
                        var cell4 = row.insertCell(3);

                        cell1.textContent = index + 1;
                        cell2.textContent = detail.name;
                        cell3.textContent = detail.quantity;
                        cell4.textContent = detail.unit_price;
                    });
                }

                // Show the modal
                $('#UnassigneventDetailsModal').modal('show');
            }


            calendar.addEventSource(events);

            calendar.render();

            function getDataFromSchedule(eventId) {
                $.ajax({
                    url: "{{ route('schedule.edit', ['id' => ':id']) }}".replace(':id', eventId),
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        var cleanerData = response.cleaner_data;
                        var getTeamData = response.get_team;
                        var employeeNames = response.employeeNames;
                        var users = response.users;

                        // console.log(response);

                        if (cleanerData.customer_type == "commercial_customer_type") {
                            $("#companyName").val(cleanerData.company_name);
                            $("#companyName_group").show();
                        } else {
                            $("#companyName").val(cleanerData.company_name);
                            $("#companyName_group").hide();
                        }

                        $('#event_id').val(cleanerData.id);
                        $('#sales_order_no').val(cleanerData.sales_order_no);
                        $('#customer_id').val(cleanerData.customer_id);
                        $('#cleaner_type' + cleanerData.cleaner_type).prop('checked', true);

                        if(cleanerData.cleaner_type == "team")
                        {
                            $('#team_id').val(cleanerData.employee_id);
                        }
                        else
                        {
                            $('#cleaner_id').val(cleanerData.employee_id);
                        }
                                             
                        $('#startDate').val(cleanerData.startDate);
                        $('#endDate').val(cleanerData.endDate);
                        $('#postalCode').val(cleanerData.postalCode);
                        $('#unitNo').val(cleanerData.unitNo);
                        $('#cleaning_address').val(cleanerData.address);
                        $('#total_session').val(cleanerData.total_session);
                        $('#get_hour').val(cleanerData.get_hour);
                        $('#weekly_freq').val(cleanerData.weekly_freq);
                        $('#startTime').val(cleanerData.startTime);
                        $('#customer_remark').val(cleanerData.customer_remark);
                        $('#cleaning_datePick').val(cleanerData.days);
                        $('#endTime').val(cleanerData.endTime);

                        $('.dayslist').prop('checked', false);
                        $.each(cleanerData.selected_days, function(index, day) {
                            $('#day' + day).prop('checked', true);
                        });

                        function populateTeamSelect() {
                            var teamSelect = $('#team_id');
                            teamSelect.empty(); // Clear existing options
                            $.each(getTeamData, function(index, teamData) {
                                teamSelect.append($('<option>', {
                                    value: teamData.team_id,
                                    text: teamData.team_name,
                                    selected: teamData.team_id === cleanerData
                                        .employee_id
                                }));
                            });
                        }

                        function populateCleanerSelect() {
                            var cleanerSelect = $('#cleaner_id');
                            cleanerSelect.empty();
                            $.each(users, function(index, user) {
                                cleanerSelect.append($('<option>', {
                                    value: user.user_id,
                                    text: user.full_name,
                                    // selected: user.full_name === cleanerData.name
                                    selected: user.user_id === cleanerData
                                        .employee_id
                                }));
                            });
                        }

                        function toggleCleanerSelectBoxes() {
                            var cleanerType = $('input[name="cleaner_type"]:checked').val();
                            var teamSelect = $('#team_id');
                            var cleanerSelect = $('#cleaner_id');

                            if (cleanerType === 'team') {
                                populateTeamSelect();
                                $('.team').show();
                                $('.individual').hide();
                            } else if (cleanerType === 'individual') {
                                populateCleanerSelect();
                                $('.team').hide();
                                $('.individual').show();
                            }

                            teamSelect.trigger('change');
                            cleanerSelect.trigger('change');
                        }

                        toggleCleanerSelectBoxes();

                        $('input[name="cleaner_type"]').on('change', toggleCleanerSelectBoxes);

                        var defaultCleanerType = cleanerData.cleaner_type || 'team';
                        $('#' + defaultCleanerType).prop('checked', true).trigger('change');


                        // Show the modal
                        $('#eventDetailsModal').modal('show');

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
                        console.log('Error occurred while loading the edit modal content.');
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


                $('#cleaning_datePick').val(endDates.map(date => formatDateForDisplay(date)).join(', '));


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


            function eventUpdate(customerID, empId, dataDate, startTime, resourceType, newdate, Service_Details) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('schedule.event.update') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        data: {
                            empId: empId,
                            dataDate: dataDate,
                            customerID: customerID,
                            startTime: startTime,
                            flag: 'eventUpdate',
                            resourceType: resourceType,
                            days: newdate,
                            Service_Details: Service_Details,
                        },
                    },
                    success: function(result) {
                        // Handle the successful response
                        // console.log(result);
                    },
                    error: function(error) {
                        // Handle errors
                        console.error('AJAX request failed:', error);
                    }
                });
            }

            function eventTimeUpdate(startTime, endTime, empId) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('schedule.event.update') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        data: {
                            empId: empId,
                            startTime: startTime,
                            endTime: endTime,
                            flag: 'updateTime',
                        },
                    },
                    success: function(response) {
                        // Handle the successful response
                        //console.log(response.message);
                    },
                    error: function(error) {
                        // Handle errors
                        console.error('AJAX request failed:', error);
                    }
                });
            }

            function isEventOverDiv(x, y) {
                var externalEvents = $('#external-events');
                var offset = externalEvents.offset();
                offset.right = externalEvents.width() + offset.left;
                offset.bottom = externalEvents.height() + offset.top;

                // Compare
                if (x >= offset.left && y >= offset.top && x <= offset.right && y <= offset.bottom) {
                    return true;
                }
                return false;
            }


            function displayEventDetails(eventDetails) {
                // Update the card view with event details
                // For example, display the event title and start date
                eventCardEl.innerHTML = `

                <!-- Add more event details here -->
            `;

                // Show the card view
                eventCardEl.style.display = 'block';
                eventCardEl.classList.add('show');
                currentEventId = eventDetails.id;
            }

            function closeEventCard() {
                // Close the card view
                eventCardEl.style.display = 'none';

                // Reset the current event ID
                currentEventId = null;
            }

            function positionEventCard(eventDetails, eventElement) {

                var eventElementRect = eventElement.getBoundingClientRect();

                // Position the card view below the event
                eventCardEl.style.top = eventElementRect.bottom + 'px';
                eventCardEl.style.left = eventElementRect.left + 'px';
            }

            var refreshButton = document.querySelector('.fc-customRefreshButton-button');
            if (refreshButton) {
                refreshButton.innerHTML = '<i class="fas fa-rotate-right"></i>';
            }
            var todayButton = document.querySelector('.fc-todayButton-button');

            if (todayButton) {
                todayButton.innerHTML = '<i class="far fa-calendar"></i> Today';

                // Add a click event listener to the "Today" button
                todayButton.addEventListener('click', function() {
                    var calendarEl = document.getElementById('detail_calendar');
                    var calendarApi = new FullCalendar.Calendar(calendarEl);

                    // Get the current date
                    var currentDate = new Date();

                    // Set the calendar's date to the current date using the 'gotoDate' method of 'calendarApi'
                    calendarApi.gotoDate(currentDate);
                });
            }

            // var selectInput = document.createElement('select');
            // selectInput.id = 'roster-select';
            // selectInput.classList.add('form-select');

            // var options = ['Option 1', 'Option 2', 'Option 3'];
            // for (var i = 0; i < options.length; i++) {
            //     var option = document.createElement('option');
            //     option.value = options[i];
            //     option.text = options[i];
            //     selectInput.appendChild(option);
            // }

            // var customSelectButton = document.querySelector('.fc-customSelect-button');
            // if (customSelectButton) {
            //     customSelectButton.appendChild(selectInput);
            //     customSelectButton.classList.remove('fc-button', 'fc-button-primary');
            // }

            var firstToolbarChunk = document.querySelector('.fc-toolbar .fc-toolbar-chunk');

            var datePickerInput = document.createElement('input');
            datePickerInput.type = 'date';
            datePickerInput.classList.add('form-control');
            datePickerInput.id = 'date-picker-input';

            firstToolbarChunk.appendChild(datePickerInput);

            datePickerInput.addEventListener('change', function() {
                var selectedDate = new Date(datePickerInput.value);
                calendar.gotoDate(selectedDate);
            });
        });
    </script>

    <script>
        function get_date(current_date) {
            // console.log(current_date);
            var temp_curent_date = new Date(current_date).toDateString();
            // console.log(temp_curent_date);

            var today = new Date().toDateString();

            // console.log(today);

            if (temp_curent_date == today) {
                // console.log("match");
                $("#unassign_job_group").hide();

                $("#unassign_job_group").prev('#calendar_group').removeClass('col-lg-9');
                $("#unassign_job_group").prev('#calendar_group').addClass('col-lg-12');
            } else {
                // console.log("not match");
                $("#unassign_job_group").show();

                $("#unassign_job_group").prev('#calendar_group').removeClass('col-lg-12');
                $("#unassign_job_group").prev('#calendar_group').addClass('col-lg-9');
            }
        }

        get_date(new Date().toDateString());

        $(document).ready(function() {

            $('body').on('click', '.fc-next-button', function() {
                var current_date = $(this).prev('.fc-toolbar-title').text();
                get_date(current_date);
            });

            $('body').on('click', '.fc-prev-button', function() {
                var current_date = $(this).next('.fc-toolbar-title').text();
                get_date(current_date);
            });

            $('body').on('change', '#date-picker-input', function() {
                get_date($(this).val())
            });

        });


        // for assign cleaner start       

        function ac_getAddress(id) 
        {
            $.ajax({
                url: '{{ route('get.cleaner.service.address', ['id' => ':id']) }}'.replace(':id', id),
                method: 'GET',
                success: function(data) {
                    var startTime = new Date('1970-01-01T' + data.time_of_cleaning);
                    var getHourInSeconds = data.get_hour * 60 * 60;
                    var endTime = new Date(startTime.getTime() + getHourInSeconds * 1000);

                    var formattedEndTime = endTime.toTimeString().substring(0, 5);

                    $('#ac_address').val(data.address);
                    $('#ac_postalCode').val(data.postal_code);
                    $('#ac_unitNo').val(data.unit_number);
                    $('#ac_customer_id').val(data.customer_id);
                    $('#ac_sales_order_no').val(data.sales_order_no);
                    $('#ac_startDate').val(data.schedule_date);
                    $('#ac_startTime').val(data.time_of_cleaning);
                    $('#ac_total_session').val(data.get_total_session);
                    $('#ac_weekly_freq').val(data.weekly_freq);
                    $('#ac_get_hour').val(data.get_hour);
                    $('#ac_endTime').val(formattedEndTime);
                    $('#ac_customer_remark').val(data.customer_remark);


                    $('.ac_dayslist').prop('checked', false);


                    $('.ac_dayslist').prop('disabled', false);

                    $('.ac_dayslist').on('click', function() {
                        ac_updateCheckboxState();
                    });

                    $('#weekly_freq').on('input', function() {
                        ac_updateCheckboxState();
                    });

                    ac_setEndDates();
                },
                error: function() {
                    $('#ac_address').val('Error fetching address');
                }
            });
        }

        function ac_updateCheckboxState() 
        {
            var frequency = parseInt($('#ac_weekly_freq').val());
            var checkedCheckboxes = $('.ac_dayslist:checked');

            if (checkedCheckboxes.length >= frequency) {

                $('.ac_dayslist:not(:checked)').prop('disabled', true);
            } else {

                $('.ac_dayslist').prop('disabled', false);
            }

            ac_setEndDates();
        }

        function ac_setEndDates() 
        {
            var startDate = new Date($('#ac_startDate').val());
            var totalSession = parseInt($('#ac_total_session').val());

            var endDates = [];
            var checkedCheckboxes = [];

            $('.ac_dayslist:checked').each(function () {
                var checkbox = $(this);
                checkedCheckboxes.push(checkbox.val());
            });

            for (var i = 0; i < totalSession; i++) {
                for (var j = 0; j < checkedCheckboxes.length; j++) {
                    var currentDay = ac_getDayNumber(checkedCheckboxes[j]);
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

            $('#ac_datePick').val(endDates.map(date => ac_formatDateForDisplay(date)).join(', '));

            var lastDate = endDates[endDates.length - 1];
            $('#ac_endDate').val(ac_formatDateForInput(lastDate));
        }


        function ac_getDayNumber(dayName) {
            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            return days.indexOf(dayName);
        }


        function ac_formatDateForDisplay(date) {
            var month = (date.getMonth() + 1).toString().padStart(2, '0');
            var day = date.getDate().toString().padStart(2, '0');
            var year = date.getFullYear();
            return day + '/' + month + '/' + year;
        }


        function ac_formatDateForInput(date) {
            var month = (date.getMonth() + 1).toString().padStart(2, '0');
            var day = date.getDate().toString().padStart(2, '0');
            var year = date.getFullYear();
            return year + '-' + month + '-' + day;
        }

        document.getElementById('ac_team_id').addEventListener('change', function() {

            var selectedTeamId = this.value;

            var employeeNames = @php echo json_encode($ac_employeeNames); @endphp;

            var employeeTextarea = document.getElementById('ac_employee_names');
            employeeTextarea.value = '';

            if (employeeNames[selectedTeamId]) {
                var temp_employeeNames = employeeNames[selectedTeamId];

                temp_employeeNames.forEach(function(employeeName) {
                    employeeTextarea.value += employeeName + '\n';
                });      
            }
        });

        var cleanerTypeRadioButtons = document.querySelectorAll('.ac_cleaner_type');
            cleanerTypeRadioButtons.forEach(function(radioButton) {
                radioButton.addEventListener('change', function() {

                    var employeeTextarea = document.getElementById('ac_employee_names');
                    var employeeFormGroup = document.querySelector('.ac_employee');

                    if (this.value === 'team') {

                        employeeFormGroup.style.display = 'block';
                    } else {

                        employeeFormGroup.style.display = 'none';
                    }
                });
            });

        // Trigger the change event on the initially checked radio button to ensure the textarea is displayed on modal open
        document.querySelector('.ac_cleaner_type:checked').dispatchEvent(new Event('change'));

        $(document).ready(function() {

            $('#UnassigneventDetailsModal').on('shown.bs.modal', function() {

                $(".ac_individual").hide();

                $(".ac_cleaner_type").click(function() {
                    if ($(this).val() == "team") {
                        $(".ac_individual").hide();

                        $(".ac_team").show();

                    } else {
                        $(".ac_team").hide();

                        $(".ac_individual").show();

                    }
                });

            });

        });

    </script>
@endsection
