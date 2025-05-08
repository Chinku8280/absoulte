@extends('theme.default')

@section('custom_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        .text-table {
            color: #454545 !important;
            font-size: small;
            text-transform: uppercase;
        }

        .no-pm {
            margin: 0;
            padding: 0;
        }

        .bg-theme {
            background-color: #424874;
            color: #ffffff;
        }

        .text-theme {
            color: #282d57;
        }

        .theme-shadow {
            box-shadow: 1px 0px 30px 0px #00000013;
        }

        body {
            background-color: #f0f1ff;
        }

        canvas {
            width: 100%;
            padding: 2%;
        }

        .btn-group {
            height: 27px;
        }

        .btn-group .btn {
            font-size: 12px;
        }

        .btn-check:checked+.btn-outline-secondary {
            background-color: #282d57;
            color: white;
            border-color: #282d57;
        }

        .sortable {
            cursor: pointer;
        }

        .sortable:after {
            content: "\f0dc";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            margin-left: 10px;
        }

        .sortable.asc:after {
            content: "\f0de";
        }

        .sortable.desc:after {
            content: "\f0dd";
        }

        .dashboard_main h5
        {
            font-size: 1.25rem;
            margin-top: 0;
            margin-bottom: .5rem;
            font-weight: 500;
            line-height: 1.2;
        }
    </style>

    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            border: 1px solid #e6e7e9 !important;
            padding: 0.4375rem 2.25rem 0.4375rem 0.75rem !important;
            height: 36px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1d273b !important;
            font-size: .875rem !important;
            font-weight: 400 !important;
            line-height: normal !important;
            padding-left: 0 !important;
            padding-right: 0 !important;

        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 26px;
            position: absolute;
            top: 3px !important;
            right: 5px !important;
            width: 20px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #206bc4 !important;
            color: white;
        }
    </style>
@endsection

@section('content')

    <main class="my-3 dashboard_main">

        <section class="container mb-3">
            <h5 class="text-theme" style="font-weight: bold; line-height: 1.75rem;">Dashboard</h5>
        </section>

        <!--Company Logo & Date-->
        {{-- <section class="container">
            <div class="d-flex justify-content-between align-items-center p-4">
                <img src="https://absolute.braincave.work/public/theme/dist/img/logo.png" class="img-fluid" alt=""
                    style="height: 70px;">
                <div class="text-end">
                    <p class="no-pm" style="font-size: 15px; font-weight: bold;">13th Jun 2024</p>
                    <p class="no-pm" style="font-size: 15px; font-weight: bold;">09:26</p>
                </div>
            </div>
        </section> --}}

        <!--Sales Metrics-->
        <section class="container">
            <div class="row">
                <div class="col-lg-4">
                    <h5 class="text-theme">Sales Metrics</h5>
                    <div style="height: 500px;">
                        <a href="{{route('sales-order.filter', 'today')}}" style="text-decoration: none;">
                            <div class="px-2 py-2 text-start rounded-3 text-theme my-2"
                                style="background-color: #ffffff; height: 23%;">
                                <div class="d-flex justify-content-start align-items-center" style="opacity: 0.78;">
                                    <!-- <i class="bi bi-currency-dollar text-center" style="font-size: 15px; height: 25px; width: 25px; background-color: #7e84ac;padding: 2px;"></i> -->
                                    <h5 class="mx-0 px-0 pt-2 ms-1" style="font-size: 15px;">Today's Sales</h5>
                                </div>
                                <p class="no-pm fw-bold" style="font-size: 28px;"><span
                                        class="bi bi-currency-dollar">{{number_format($todays_total_amount, 2)}}</span></p>
                            </div>
                        </a>

                        <a href="{{route('sales-order.filter', 'this-month')}}" style="text-decoration: none;">
                            <div class="px-2 py-2 text-start rounded-3 text-theme my-2"
                                style="background-color: #ffffff; height: 23%;">
                                <div class="d-flex justify-content-start align-items-center" style="opacity: 0.78;">
                                    <!-- <i class="bi bi-currency-dollar text-center" style="font-size: 15px;"></i> -->
                                    <h5 class="mx-0 px-0 pt-2 ms-1" style="font-size: 15px;">This Month's Sales</h5>
                                </div>
                                <p class="no-pm fw-bold" style="font-size: 28px;"><span
                                        class="bi bi-currency-dollar">{{number_format($this_month_total_amount, 2)}}</span></p>
                            </div>
                        </a>

                        <div class="w-100 rounded-3 px-2 pt-2 pb-3 mx-0 mt-1"
                            style="background-color: #ffffff;height: 48%;">
                            <div class="d-flex justify-content-between align-items-center" style="opacity: 0.78;">
                                <h5 class="mx-0 px-0 pt-2 ms-1" style="font-size: 15px;">Yearly Sales</h5>
                                <select name="chart_filter" id="chart_filter" class="form-select" style="width: 90px; height: 30px; font-size: 12px;">
                                    @foreach ($year_data as $item)
                                        @if ($item == date('Y'))
                                            <option value="{{$item}}" selected>{{$item}}</option>
                                        @else
                                            <option value="{{$item}}">{{$item}}</option>
                                        @endif                                               
                                    @endforeach
                                </select>
                            </div>
                            <canvas id="sales_order_chart"></canvas>
                        </div>
                    </div>
                </div>

                <!--Customer Metrics-->
                <div class="col-lg-4">
                    <h5 class="text-theme">Customers Metrics</h5>
                    <div style="height: 500px;">
                        <a href="{{route('crm')}}" style="text-decoration: none;">
                            <div class="p-4 text-start rounded-3 text-theme my-2 d-flex"
                                style="background-color: #ffffff; height: 23%;">                               
                                <div class="d-flex justify-content-start align-items-center my-auto">
                                    <div class="d-flex text-center rounded-2 text-white my-auto"
                                        style="height: 45px; width: 45px; background-color: #FF6384;">
                                        <span class="bi bi-person my-auto mx-auto" style="font-size: 25px;">
                                        </span>
                                    </div>
                                    <div class="px-3 my-auto">
                                        <h5 class="no-pm" style="font-size: 15px;opacity: 0.75; line-height: 17px;">
                                            Total
                                            Customers</h5>
                                        <p class="no-pm fw-bold" style="font-size: 28px; line-height: 30px;">{{$total_customers}}</p>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="{{route('crm-active', 'active')}}" style="text-decoration: none;">
                            <div class="p-4 text-start rounded-3 text-theme my-2 d-flex"
                                style="background-color: #ffffff; height: 23%;">
                                <div class="d-flex justify-content-start align-items-center my-auto">
                                    <div class="d-flex text-center rounded-2 text-white my-auto"
                                        style="height: 45px; width: 45px; background-color: #63e5ff;">
                                        <span class="bi bi-person my-auto mx-auto" style="font-size: 25px;">
                                        </span>
                                    </div>
                                    <div class="px-3 my-auto">
                                        <h5 class="no-pm" style="font-size: 15px;opacity: 0.75; line-height: 17px;">
                                            Total
                                            Active
                                            Customers</h5>
                                        <p class="no-pm fw-bold" style="font-size: 28px; line-height: 30px;">{{$total_active_customers}}</p>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="{{route('crm-residential-tab')}}" style="text-decoration: none;">
                            <div class="p-4 text-start rounded-3 text-theme my-2 d-flex"
                                style="background-color: #ffffff; height: 23%;">
                                <div class="d-flex justify-content-start align-items-center my-auto">
                                    <div class="d-flex text-center rounded-2 text-white my-auto"
                                        style="height: 45px; width: 45px; background-color: #ff63e0;">
                                        <span class="bi bi-person my-auto mx-auto" style="font-size: 25px;">
                                        </span>
                                    </div>
                                    <div class="px-3 my-auto">
                                        <h5 class="no-pm" style="font-size: 15px;opacity: 0.75; line-height: 17px;">
                                            Total
                                            Residential Customers</h5>
                                        <p class="no-pm fw-bold" style="font-size: 28px; line-height: 30px;">{{$total_residential_customers}}</p>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="{{route('crm-commercial-tab')}}" style="text-decoration: none;">
                            <div class="p-4 text-start rounded-3 text-theme my-2 d-flex"
                                style="background-color: #ffffff; height: 23%;">
                                <div class="d-flex justify-content-start align-items-center my-auto">
                                    <div class="d-flex text-center rounded-2 text-white my-auto"
                                        style="height: 45px; width: 45px; background-color: #206bc4;">
                                        <span class="bi bi-person my-auto mx-auto" style="font-size: 25px;">
                                        </span>
                                    </div>
                                    <div class="px-3 my-auto">
                                        <h5 class="no-pm" style="font-size: 15px;opacity: 0.75; line-height: 17px;">
                                            Total
                                            Commercial Customers</h5>
                                        <p class="no-pm fw-bold" style="font-size: 28px; line-height: 30px;">{{$total_commercial_customers}}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!--Order and Service Metrics-->
                <div class="col-lg-4">
                    <h5 class="text-theme">Order and Service Metrics</h5>
                    <div style="height: 500px;">

                        <a href="{{route('salesOrder')}}" style="text-decoration: none;">
                            <div class="p-4 text-start rounded-3 text-theme my-2 d-flex"
                                style="background-color: #ffffff; height: 23%;">
                                <div class="d-flex justify-content-start align-items-center my-auto">
                                    <div class="d-flex text-center rounded-2 text-white my-auto"
                                        style="height: 45px; width: 45px; background-color: #77ff46;">
                                        <span class="bi bi-currency-dollar my-auto mx-auto" style="font-size: 25px;">
                                        </span>
                                    </div>
                                    <div class="px-3 my-auto">
                                        <h5 class="no-pm" style="font-size: 15px;opacity: 0.75; line-height: 17px;">
                                            Total
                                            Sales
                                            Orders</h5>
                                        <p class="no-pm fw-bold" style="font-size: 28px; line-height: 30px;">{{$total_sales_order}}</p>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="{{route('services-tab')}}" style="text-decoration: none;">
                            <div class="p-4 text-start rounded-3 text-theme my-2 d-flex"
                                style="background-color: #ffffff; height: 23%;">
                                <div class="d-flex justify-content-start align-items-center my-auto">
                                    <div class="d-flex text-center rounded-2 text-white my-auto"
                                        style="height: 45px; width: 45px; background-color: #e49834;">
                                        <span class="bi bi-cart3 my-auto mx-auto" style="font-size: 25px;">
                                        </span>
                                    </div>
                                    <div class="px-3 my-auto">
                                        <h5 class="no-pm" style="font-size: 15px;opacity: 0.75; line-height: 17px;">
                                            Total
                                            Services</h5>
                                        <p class="no-pm fw-bold" style="font-size: 28px; line-height: 30px;">{{$total_services}}</p>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <div class="p-3 text-start rounded-3 text-theme my-2"
                            style="background-color: #ffffff; height: 48%;overflow: hidden;">
                            <h5 class="no-pm" style="font-size: 15px; line-height: 17px; opacity: 0.75;">Pending
                                Leads
                            </h5>

                            <div class="table-responsive">
                                <table class="table table-sortable text-nowrap" id="pending_leads_table" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-table sortable" data-column="0">Sl No</th>
                                            <th scope="col" class="text-table sortable" data-column="1">Customer Name</th>
                                            <th scope="col" class="text-table sortable" data-column="2">Company Name</th>
                                            <th scope="col" class="text-table sortable" data-column="3">Mobile</th>
                                            <th scope="col" class="text-table sortable" data-column="4">Schedule Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pending_leads as $key => $item)                                                     
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $item->customer_name }}</td>
                                                <td>{{ $item->individual_company_name }}</td>
                                                <td>+65 {{ $item->mobile_number }}</td>
                                                <td>{{ ($item->schedule_date)?date('d-m-Y', strtotime($item->schedule_date)):'' }}</td>
                                            </tr>                                                     
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <!--Operational Metrics-->
        <section class="container">
            <h5 class="text-theme mb-0 mt-2">Operational Metrics</h5>
            <div class="row">
                <div class="col-lg-4 p-2 ">
                    <div class="px-3 py-2  bg-white rounded-2">
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <h5 class="no-pm text-theme" style="font-size: 17px;opacity: 0.75; line-height: 17px;">
                                Scheduled Jobs</h5>
                            <div class="btn-group btn-group-sm" role="group"
                                aria-label="Basic radio toggle button group;">
                                <input type="radio" class="btn-check schedule_jobs_btn" name="btnradio" id="day_btnradio"
                                    autocomplete="off" checked value="day">
                                <label class="btn btn-outline-secondary" for="day_btnradio">Day</label>

                                <input type="radio" class="btn-check schedule_jobs_btn" name="btnradio" id="week_btnradio"
                                    autocomplete="off" value="week">
                                <label class="btn btn-outline-secondary" for="week_btnradio">Week</label>

                                <input type="radio" class="btn-check schedule_jobs_btn" name="btnradio" id="month_btnradio"
                                    autocomplete="off" value="month">
                                <label class="btn btn-outline-secondary" for="month_btnradio">Month</label>
                            </div>
                        </div>
                        <p class="text-theme no-pm" style="font-size: 30px; font-weight: bold;" id="total_scheduled_jobs">{{$today_total_schedule_job}}</p>
                    </div>
                </div>
                <div class="col-lg-4 p-2 ">
                    <div class="px-3 py-3  bg-white rounded-2 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="no-pm text-theme" style="font-size: 17px;opacity: 0.75; line-height: 17px;">
                                Completed Jobs</h5>
                        </div>
                        <p class="text-theme no-pm" style="font-size: 30px; font-weight: bold;" id="total_completed_jobs">{{$today_total_completed_schedule_job}}</p>
                    </div>
                </div>
                <div class="col-lg-4 p-2 ">
                    <div class="px-3 py-3  bg-white rounded-2 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="no-pm text-theme" style="font-size: 17px;opacity: 0.75; line-height: 17px;">
                                Pending Jobs</h5>
                        </div>
                        <p class="text-theme no-pm" style="font-size: 30px; font-weight: bold;" id="total_pending_jobs">{{$today_total_pending_schedule_job}}</p>
                    </div>
                </div>
            </div>
        </section>

        <!--Financial Metrics-->
        <section class="container">
            <h5 class="text-theme mb-0 mt-2">Financial Metrics</h5>
            <div class="row">
                <div class="col-lg-4 p-2">
                    <div class="px-2 py-2 text-start rounded-3 text-theme my-2 bg-white" style="height: 31%;">
                        <div class="d-flex justify-content-start align-items-center" style="opacity: 0.78;">
                            <h5 class="mx-0 px-0 pt-2 ms-1" style="font-size: 15px;">Outstanding Payments</h5>
                        </div>
                        <p class="no-pm fw-bold" style="font-size: 28px;"><span
                                class="bi bi-currency-dollar">{{number_format($outstanding_amount, 2)}}</span></p>
                    </div>

                    <a href="{{route('finance')}}" style="text-decoration: none;">
                        <div class="px-2 py-2 text-start rounded-3 text-theme my-2 bg-white" style="height: 31%;">
                            <div class="d-flex justify-content-start align-items-center" style="opacity: 0.78;">
                                <h5 class="mx-0 px-0 pt-2 ms-1" style="font-size: 15px;">Total Invoices</h5>
                            </div>
                            <p class="m-0 py-0 px-1 fw-bold" style="font-size: 28px;">{{$total_invoice}}</p>
                        </div>
                    </a>

                    <div class="px-2 py-2 text-start rounded-3 text-theme my-2 bg-white" style="height: 31%;">
                        <div class="d-flex justify-content-start align-items-center" style="opacity: 0.78;">
                            <h5 class="mx-0 px-0 pt-2 ms-1" style="font-size: 15px;">Payments Received</h5>
                        </div>
                        <p class="no-pm fw-bold" style="font-size: 28px;"><span
                                class="bi bi-currency-dollar">{{number_format($received_payment, 2)}}</span></p>
                    </div>
                </div>
                <div class="col-lg-8 p-2">
                    <div class="row ps-lg-2">
                        <div class="col-lg-6 no-pm">
                            <div class="px-2 py-2 text-start rounded-3 text-theme my-2 bg-white">
                                <div class="d-flex justify-content-start align-items-center" style="opacity: 0.78;">
                                    <h5 class="mx-0 px-0 pt-2 ms-1" style="font-size: 15px;">Due for Payment</h5>
                                </div>
                                <p class="no-pm fw-bold" style="font-size: 28px;">{{$due_payment}}</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="px-2 py-2 text-start rounded-3 text-theme my-2 bg-white">
                                <div class="d-flex justify-content-start align-items-center" style="opacity: 0.78;">
                                    <h5 class="mx-0 px-0 pt-2 ms-1" style="font-size: 15px;">Pending for Payment
                                    </h5>
                                </div>
                                <p class="no-pm fw-bold" style="font-size: 28px;">{{$pending_payment}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 p-2 rounded-3 bg-white table-responsive mx-0">
                        <table class="table table-sortable text-nowrap" id="due_pending_payment_sales_order">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-table sortable" data-column="0">Sl No</th>
                                    <th scope="col" class="text-table sortable" data-column="1">Customer Name</th>
                                    <th scope="col" class="text-table sortable" data-column="1">Sales Order Id</th>
                                    <th scope="col" class="text-table sortable" data-column="1">Invoice No</th>
                                    <th scope="col" class="text-table sortable" data-column="2">Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($due_pending_payment_sales_order as $key=>$item)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>
                                            @if ($item->customer_type == "residential_customer_type")
                                                {{$item->customer_name}}
                                            @elseif($item->customer_type == "commercial_customer_type")
                                                {{$item->individual_company_name}}
                                            @endif
                                        </td>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->invoice_no}}</td>
                                        <td>
                                            @if (!empty($item->due_date))
                                                {{date('d-m-Y', strtotime($item->due_date))}}
                                            @endif                                          
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Order Management -->
        <section class="container">
            <div class="row">
                <div class="col-lg-6 p-2">
                    <h5 class="text-theme mb-1 mt-2">Order Management</h5>
                    <div class="bg-white rounded-3 p-3 h-100">
                        <div class="d-flex">                            
                            <div class="p-2 rounded-3" style="background-color: #f0f1ff90; width: 48%; margin: 1%;">
                                <a href="{{route('sales-order.filter', ['unassigned', date('Y')])}}" class="text-theme" style="text-decoration: none;" id="unassigned_sales_order_filter">
                                    <p class=" no-pm"
                                        style="font-size: 15px; opacity: 0.8;font-weight: 500;font-size: small;">
                                        Unassigned Sales Orders/Job Orders
                                    </p>
                                    <p class="text-theme no-pm"
                                        style="font-size: 30px; font-weight: bold;color: #006eff;" id="total_unassigned_sales_order">
                                        {{$total_unassigned_sales_order}}</p>
                                </a>
                            </div>

                            <div class="p-2 rounded-3" style="background-color: #f0f1ff90;width: 48%; margin: 1%">
                                <a href="{{route('sales-order.filter', ['partially-assigned', date('Y')])}}" class="text-theme" style="text-decoration: none;" id="partial_assigned_sales_order_filter">
                                    <p class="text-theme no-pm"
                                        style="font-size: 15px; opacity: 0.8;font-weight: 500;font-size: small;">
                                        Partially Assigned Sales Orders/Job Orders</p>
                                    <p class="text-theme no-pm"
                                        style="font-size: 30px; font-weight: bold; color: #36a3eb;" id="total_partially_assigned_sales_order">
                                        {{$total_partially_assigned_sales_order}}</p>
                                </a>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center" style="opacity: 0.78;">
                            <h5 class="mx-0 px-0 pt-2 ms-1" style="font-size: 15px;">Yearly Job Orders</h5>
                            <select name="job_order_chart_filter" id="job_order_chart_filter" class="form-select" style="width: 90px; height: 30px; font-size: 12px;">
                                @foreach ($year_data as $item)
                                    @if ($item == date('Y'))
                                        <option value="{{$item}}" selected>{{$item}}</option>
                                    @else
                                        <option value="{{$item}}">{{$item}}</option>
                                    @endif                                               
                                @endforeach
                            </select>
                        </div>

                        <div style="width: 100%;">
                            <canvas id="ordersChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-lg-6 p-2">
                    <h5 class="text-theme mb-1 mt-2">Renewal Management</h5>
                    <div class="bg-white rounded-3 p-3 h-100">
                        <div class="d-flex">
                            <div class="p-2 rounded-3" style="background-color: #f0f1ff90; width: 48%; margin: 1%;">
                                <p class=" no-pm" style="font-size: 15px; opacity: 0.8;font-weight: 500;">
                                    Fully Utilized Sessions
                                </p>
                                <p class="text-theme no-pm" style="font-size: 30px; font-weight: bold;">
                                    07</p>
                            </div>

                            <div class="p-2 rounded-3" style="background-color: #f0f1ff90;width: 48%; margin: 1%">
                                <p class="text-theme no-pm" style="font-size: 15px; opacity: 0.8;font-weight: 500;">
                                    Auto Renew</p>
                                <p class="text-theme no-pm" style="font-size: 30px; font-weight: bold">
                                    03</p>
                            </div>
                        </div>
                        <div class="table-responsive pt-4">
                            <table class="table table-sortable">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-table sortable" data-column="0">Sl No</th>
                                        <th scope="col" class="text-table sortable" data-column="1">Customer Name
                                        </th>
                                        <th scope="col" class="text-table sortable" data-column="2">Fully Utilized
                                            Sessions</th>
                                        <th scope="col" class="text-table sortable" data-column="3">Auto Renew</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: medium;">
                                    <tr>
                                        <td>1</td>
                                        <td>John Doe</td>
                                        <td>7</td>
                                        <td>Yes</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Jane Smith</td>
                                        <td>7</td>
                                        <td>No</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Emily Johnson</td>
                                        <td>7</td>
                                        <td>Yes</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Michael Brown</td>
                                        <td>7</td>
                                        <td>No</td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Sarah Davis</td>
                                        <td>7</td>
                                        <td>Yes</td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>David Wilson</td>
                                        <td>7</td>
                                        <td>No</td>
                                    </tr>
                                    <tr>
                                        <td>7</td>
                                        <td>Linda Martinez</td>
                                        <td>7</td>
                                        <td>No</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> --}}

                <div class="col-lg-6 p-2">
                    <h5 class="text-theme mb-1 mt-2">Unassign</h5>
                    <div class="bg-white rounded-3 p-3 h-100">   
                        <div class="table-responsive pt-2">
                            <table class="table table-sortable" id="sales_order_table">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-table sortable" data-column="0">No.</th>
                                        <th scope="col" class="text-table sortable" data-column="1">Invoice No</th>
                                        <th scope="col" class="text-table sortable" data-column="2">Sales Order No</th> 
                                        <th scope="col" class="text-table sortable" data-column="3">Customer Name</th>
                                        <th scope="col" class="text-table sortable" data-column="4">Unassigned Date</th>                                    
                                    </tr>
                                </thead>
                                <tbody style="font-size: medium;">  
                                    @php
                                        $i = 0;
                                    @endphp   
                                                                 
                                    @foreach ($sales_order as $key => $item)
                                        @if (!empty($item->unassigned_date))                                                                                                                                                            
                                            <tr>
                                                <td>{{$i+1}}</td>
                                                <td>{{$item->invoice_no}}</td>
                                                <td>{{ $item->id }}</td>
                                                <td>
                                                    @if ($item->customer_type == "residential_customer_type")
                                                        {{ $item->customer_name }}
                                                    @else
                                                        {{ $item->individual_company_name }}
                                                    @endif                                                          
                                                </td>
                                                <td>{{($item->unassigned_date) ? date('d-m-Y', strtotime($item->unassigned_date)) : ''}}</td>
                                            </tr>      
                                            
                                            @php
                                                $i +=1;
                                            @endphp
                                        @endif   
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>                    
                </div>
            </div>
        </section>

        <!--Sales Funnel Metrics-->
        <section class="container">
            <h5 class="text-theme mb-0 mt-2">Sales Funnel Metrics</h5>
            <div class="row">
                <div class="col-lg-6 p-2">
                    <div class="bg-white rounded-3 p-3 h-100">
                        <h5 class="text-theme no-pm"><span class="bi bi-graph-up me-2 text-primary"></span>New Leads
                        </h5>
                        <hr style="color: #474747;">

                        <div class="d-flex">
                            <div class="p-2 rounded-3" style="background-color: #f0f1ff90; width: 48%; margin: 1%;">
                                <p class=" no-pm" style="font-size: 15px; opacity: 0.8;font-weight: 500;">
                                    Drafts
                                </p>
                                <p class="text-theme no-pm"
                                    style="font-size: 30px; font-weight: bold;color: #006eff;" id="total_draft_leads">
                                    {{$total_draft_leads}}</p>
                            </div>

                            <div class="p-2 rounded-3" style="background-color: #f0f1ff90;width: 48%; margin: 1%">
                                <p class="text-theme no-pm" style="font-size: 15px; opacity: 0.8;font-weight: 500;">
                                    Account
                                    Already Created</p>
                                <p class="text-theme no-pm"
                                    style="font-size: 30px; font-weight: bold; color: #36a3eb;" id="total_leads">
                                    {{$total_leads}}</p>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center" style="opacity: 0.78;">
                            <h5 class="mx-0 px-0 pt-2 ms-1" style="font-size: 15px;">Yearly Leads</h5>
                            <select name="lead_chart_filter" id="lead_chart_filter" class="form-select" style="width: 90px; height: 30px; font-size: 12px;">
                                @foreach ($year_data as $item)
                                    @if ($item == date('Y'))
                                        <option value="{{$item}}" selected>{{$item}}</option>
                                    @else
                                        <option value="{{$item}}">{{$item}}</option>
                                    @endif                                               
                                @endforeach
                            </select>
                        </div>

                        <div style="height: 70%;">
                            <canvas id="leadsChart" style="width: 100%; height: auto;"></canvas>
                        </div>

                    </div>
                </div>

                <div class="col-lg-6 p-2 h-100">
                    <div class="bg-white rounded-3 p-3">
                        <h5 class="text-theme no-pm"><span class="bi bi-graph-up me-2 text-primary"></span>Quotation
                        </h5>
                        <hr style="color: #474747;">

                        <div class="d-flex">
                            <div class="p-2 rounded-3" style="background-color: #f0f1ff90;width: 100%; margin: 1%">
                                <p class="text-theme no-pm" style="font-size: 15px; opacity: 0.8;font-weight: 500;">
                                    Sent Quotation</p>
                                <p class="text-theme no-pm"
                                    style="font-size: 30px; font-weight: bold; color: #366ceb;" id="total_sent_quotation">
                                    {{$total_sent_quotation}}</p>
                            </div>
                            <div class="p-2 rounded-3"
                                style="background-color: #f0f1ff90; width: 100%; margin: 1%;">
                                <p class=" no-pm" style="font-size: 15px; opacity: 0.8;font-weight: 500;">
                                    In Progress Quotation
                                </p>
                                <p class="text-theme no-pm"
                                    style="font-size: 30px; font-weight: bold;color: #00c8ff;" id="total_progress_quotation">
                                    {{$total_progress_quotation}}</p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="p-2 rounded-3" style="background-color: #f0f1ff90;width: 100%; margin: 1%">
                                <p class="text-theme no-pm" style="font-size: 15px; opacity: 0.8;font-weight: 500;">
                                    Expiring Quotation</p>
                                <p class="text-theme no-pm"
                                    style="font-size: 30px; font-weight: bold; color: #eb365a;" id="total_expired_quotation">
                                    {{$total_expired_quotation}}</p>
                            </div>
                            <div class="p-2 rounded-3" style="background-color: #f0f1ff90;width: 100%; margin: 1%">
                                <p class="text-theme no-pm" style="font-size: 15px; opacity: 0.8;font-weight: 500;">
                                    Approved Quotation</p>
                                <p class="text-theme no-pm"
                                    style="font-size: 30px; font-weight: bold; color: #36eb7e;" id="total_approved_quotation">
                                    {{$total_approved_quotation}}</p>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center" style="opacity: 0.78;">
                            <h5 class="mx-0 px-0 pt-2 ms-1" style="font-size: 15px;">Yearly Quotations</h5>
                            <select name="quotation_chart_filter" id="quotation_chart_filter" class="form-select" style="width: 90px; height: 30px; font-size: 12px;">
                                @foreach ($year_data as $item)
                                    @if ($item == date('Y'))
                                        <option value="{{$item}}" selected>{{$item}}</option>
                                    @else
                                        <option value="{{$item}}">{{$item}}</option>
                                    @endif                                               
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-lg-5">                           
                            <canvas id="quotationsChart"></canvas>
                        </div>

                    </div>

                    <div class="mt-2 p-2 rounded-3 bg-white table-responsive">
                        <table class="table table-sortable text-nowrap" id="pending_quotation_table">
                            <thead>
                                <tr>
                                    <th scope="col" class="sortable text-table" data-column="0">Sl No</th>
                                    <th scope="col" class="sortable text-table" data-column="0">Quotation No</th>
                                    <th scope="col" class="sortable text-table" data-column="1">Customer Name</th>
                                    <th scope="col" class="sortable text-table" data-column="2">Quotation Date</th>
                                    <th scope="col" class="sortable text-table" data-column="3">Expiry Date</th>
                                    <th scope="col" class="text-table">Actions</th>
                                </tr>
                            </thead>
                            <tbody>                         
                                @foreach ($pending_quotation as $key => $item)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$item->quotation_no}}</td>
                                        <td>
                                            @if ($item->customer_type == "residential_customer_type")
                                                {{$item->customer_name}}
                                            @elseif($item->customer_type == "commercial_customer_type")
                                                {{$item->individual_company_name}}
                                            @endif
                                        </td>
                                        <td>{{date('d-m-Y', strtotime($item->created_at))}}</td>
                                        <td>{{date('d-m-Y', strtotime($item->expire_date))}}</td>
                                        <td>
                                            <a href="#" class="btn btn-outline-primary btn-sm" title="Send Payment Advice" href="#quotation-payment"  onclick="paymentsProcess({{ $item->id }})">
                                                <span class="bi bi-envelope"></span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        {{-- Completing Jobs table start --}}

        <section class="container">
            <h5 class="text-theme mb-0 mt-2">Completing Jobs</h5>
            <div class="row">
                <div class="col-lg-6 p-2">                  
                    <div class="bg-white rounded-3 p-3 h-100">   
                        <div class="table-responsive pt-2">
                            <table class="table table-sortable" id="completing_jobs_table">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-table sortable" data-column="0">Sl No</th>
                                        <th scope="col" class="text-table sortable" data-column="1">Invoice No</th>
                                        <th scope="col" class="text-table sortable" data-column="2">Customer</th> 
                                        <th scope="col" class="text-table sortable" data-column="1">Sales Order Id</th>                                    
                                    </tr>
                                </thead>
                                <tbody style="font-size: medium;">
                                    @php
                                        $i = 0;
                                    @endphp
                                    
                                    @foreach ($completing_jobs as $item)
                                        @if ($item->balance_job == 1)                                           
                                            <tr>
                                                <td>{{$i+1}}</td>
                                                <td>{{$item->invoice_no}}</td>
                                                <td>
                                                    @if ($item->customer_type == "residential_customer_type")
                                                        {{ $item->customer_name }}
                                                    @else
                                                        {{ $item->individual_company_name }}
                                                    @endif 
                                                </td>
                                                <td>{{$item->id}}</td>
                                            </tr>

                                            @php
                                                $i +=1;
                                            @endphp
                                        @endif                                     
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Completing Jobs table end --}}

    </main>


    {{-- -------send payment advice-------------------------- --}}
    <div class="modal modal-blur fade" id="quotation-payment" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document">
            <div class="modal-content" id="quotation-payment-content">

            </div>
        </div>
    </div>

@endsection

@section('javascript')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>

        // sales order chart start

        var sales_order_chart;

        function get_sales_order_chart_data()
        {
            var chart_filter = $("#chart_filter").find(":selected").val();
            
            // console.log(chart_filter);

            $.ajax({
                type: "get",
                url: "{{route('dashboard.get-sales-order-chart-data')}}",
                data: {chart_filter: chart_filter},
                success: function (result) {
                    console.log(result);

                    create_sales_order_chart(result.data, result.key);
                },
                error: function (result) {
                    console.log(result);
                }
            });
        }

        function create_sales_order_chart(sales_data, key)
        {
            var salesCtx = document.getElementById('sales_order_chart').getContext('2d');

            var salesGradient = salesCtx.createLinearGradient(0, 0, 0, 400);
            salesGradient.addColorStop(0, 'rgba(54, 162, 235, 1)');
            salesGradient.addColorStop(1, 'rgba(9, 9, 121, 1)');

            var options = {
                type: 'bar',
                data: {
                    labels: key,
                    datasets: [{
                        label: 'Yearly Sales',
                        data: sales_data,
                        backgroundColor: salesGradient,
                        borderColor: '#23232300',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#42487400'
                            }
                        },
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: '#42487400'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            };

            if(sales_order_chart)
            {
                sales_order_chart.destroy();
            }

            sales_order_chart = new Chart(salesCtx, options);
        }

        // sales order chart end

        // leads chart start

        var leads_chart;

        function get_leads_chart_data()
        {
            var lead_chart_filter = $("#lead_chart_filter").find(":selected").val();
            
            // console.log(lead_chart_filter);

            $.ajax({
                type: "get",
                url: "{{route('dashboard.get-leads-chart-data')}}",
                data: {chart_filter: lead_chart_filter},
                success: function (result) {
                    console.log(result);

                    $("#total_draft_leads").text(result.total_draft_leads);
                    $("#total_leads").text(result.total_leads);

                    create_leads_chart(result.total_draft_leads, result.total_leads);
                },
                error: function (result) {
                    console.log(result);
                }
            });
        }

        function create_leads_chart(total_draft_leads, total_leads)
        {
            var leadsCtx = document.getElementById('leadsChart').getContext('2d');

            var leadsData = {
                labels: ['Draft', 'Account Already Created'],
                datasets: [{
                    data: [total_draft_leads, total_leads],
                    backgroundColor: ['#006eff', '#36a3eb'],
                    // hoverBackgroundColor: ['#FF6384', '#36A2EB']
                }]
            };

            var leadsOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                title: {
                    display: true,
                    text: 'Distribution of New Leads'
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            };

            if(leads_chart)
            {
                leads_chart.destroy();
            }

            leads_chart = new Chart(leadsCtx, {
                type: 'pie',
                data: leadsData,
                options: leadsOptions
            });
        }

        // leads chart end

        // quotations chart start

        var quotations_chart;

        function get_quotations_chart_data()
        {
            var quotation_chart_filter = $("#quotation_chart_filter").find(":selected").val();
            
            // console.log(quotation_chart_filter);

            $.ajax({
                type: "get",
                url: "{{route('dashboard.get-quotation-chart-data')}}",
                data: {chart_filter: quotation_chart_filter},
                success: function (result) {
                    console.log(result);

                    $("#total_sent_quotation").text(result.total_sent_quotation);
                    $("#total_progress_quotation").text(result.total_progress_quotation);
                    $("#total_expired_quotation").text(result.total_expired_quotation);
                    $("#total_approved_quotation").text(result.total_approved_quotation);

                    create_quotation_chart(result.total_sent_quotation, result.total_progress_quotation, result.total_expired_quotation, result.total_approved_quotation);
                },
                error: function (result) {
                    console.log(result);
                }
            });
        }

        function create_quotation_chart(total_sent_quotation, total_progress_quotation, total_expired_quotation, total_approved_quotation)
        {
            var quotation_ctx = document.getElementById('quotationsChart').getContext('2d');

            var options = {
                type: 'bar',
                data: {
                    labels: ['Sent', 'In Progress', 'Expiring', 'Approved'],
                    datasets: [{
                        label: 'Quotations',
                        data: [
                            total_sent_quotation,
                            total_progress_quotation,
                            total_expired_quotation,
                            total_approved_quotation
                        ],
                        backgroundColor: [
                            '#366ceb',
                            '#00c8ff',
                            '#eb365a',
                            '#36eb7e'
                        ],
                        borderWidth: 1,
                        barPercentage: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                // text: 'Number of Quotations'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                // text: 'Status'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            };

            if(quotations_chart)
            {
                quotations_chart.destroy();
            }

            quotations_chart = new Chart(quotation_ctx, options);
        }     

        // quotations chart end

        // job order chart start

        var job_orders_chart;

        function get_job_orders_chart_data()
        {
            var job_order_chart_filter = $("#job_order_chart_filter").find(":selected").val();
            
            // console.log(job_order_chart_filter);

            $.ajax({
                type: "get",
                url: "{{route('dashboard.get-job-order-chart-data')}}",
                data: {chart_filter: job_order_chart_filter},
                success: function (result) {
                    console.log(result);

                    $("#total_unassigned_sales_order").text(result.total_unassigned_sales_order);
                    $("#total_partially_assigned_sales_order").text(result.total_partially_assigned_sales_order);

                    create_job_orders_chart(result.month_key, result.monthly_unassigned_sales_order_data, result.monthly_partially_assigned_sales_order_data);
                },
                error: function (result) {
                    console.log(result);
                }
            });
        }

        function create_job_orders_chart(month_key, monthly_unassigned_sales_order_data, monthly_partially_assigned_sales_order_data)
        {
            var job_orders_ctx = document.getElementById('ordersChart').getContext('2d');

            var job_data = {
                labels: month_key,
                datasets: [
                    {
                        label: 'Unassigned Orders',
                        data: monthly_unassigned_sales_order_data,
                        backgroundColor: '#006eff',
                        borderColor: '#006eff',
                        fill: false,
                        tension: 0.1
                    },
                    {
                        label: 'Partially Assigned Orders',
                        data: monthly_partially_assigned_sales_order_data,
                        backgroundColor: '#36a3eb',
                        borderColor: '#36a3eb',
                        fill: false,
                        tension: 0.1
                    }
                ]
            };

            var job_options = {
                type: 'line',
                data: job_data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            display: false
                        },
                        title: {
                            display: false,
                            text: 'Trend of Orders Over Time'
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: false,
                                text: 'Time'
                            }
                        },
                        y: {
                            title: {
                                display: false,
                                text: 'Number of Orders'
                            },
                            beginAtZero: true
                        }
                    }
                },
            };

            if(job_orders_chart)
            {
                job_orders_chart.destroy();
            }

            job_orders_chart = new Chart(job_orders_ctx, job_options);
        }

        // job order chart end

        // send payment advice

        function paymentsProcess (quotationId) 
        {
            $.ajax({
                url: "{{ route('quotation.send-payment') }}?quotation_id=" + quotationId,
                type: "GET",
                success: function(response) {
                    //  console.log(hello);
                    $('#quotation-payment').modal('show');
                    $('#quotation-payment-content').html(response);
                },
                error: function(response) {
                    console.log(response);
                    console.log('Error occurred while loading the modal content.');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {

            // sales order chart
            
            get_sales_order_chart_data();

            // leads chart

            get_leads_chart_data();

            // quotation chart

            get_quotations_chart_data();

            // job order chart

            get_job_orders_chart_data();

        });

        document.addEventListener('DOMContentLoaded', function () {
            const tables = document.querySelectorAll('.table-sortable');

            tables.forEach(table => {
                const headers = table.querySelectorAll('th.sortable');
                headers.forEach((header, index) => {
                    header.addEventListener('click', () => {
                        const order = header.getAttribute('data-order') || 'asc';
                        const column = header.getAttribute('data-column');

                        // Toggle order
                        const newOrder = order === 'asc' ? 'desc' : 'asc';
                        header.setAttribute('data-order', newOrder);

                        sortTableByColumn(table, parseInt(column), newOrder);
                        headers.forEach(h => {
                            if (h !== header) {
                                h.setAttribute('data-order', '');
                            }
                            h.classList.remove('asc', 'desc');
                        });
                        header.classList.add(newOrder === 'asc' ? 'asc' : 'desc');
                    });
                });
            });

            function sortTableByColumn(table, column, order) {
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));

                rows.sort((rowA, rowB) => {
                    const cellA = rowA.cells[column].textContent.trim();
                    const cellB = rowB.cells[column].textContent.trim();

                    if (isNaN(cellA) || isNaN(cellB)) {
                        return order === 'asc' ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
                    } else {
                        return order === 'asc' ? parseFloat(cellA) - parseFloat(cellB) : parseFloat(cellB) - parseFloat(cellA);
                    }
                });

                while (tbody.firstChild) {
                    tbody.removeChild(tbody.firstChild);
                }

                tbody.append(...rows);
            }
        });

        $(document).ready(function () {

            $('#pending_leads_table, #due_pending_payment_sales_order, #pending_quotation_table, #completing_jobs_table, #sales_order_table').DataTable({
                searching: false,
                paging: true,
                "lengthChange": false,
                "pageLength": 10,
            });

            $('body').on('change', '#chart_filter', function() {

                get_sales_order_chart_data();

            });

            $('body').on('change', '.schedule_jobs_btn', function() {

                var schedule_jobs_btn_value = $(this).val();

                $.ajax({
                    type: "get",
                    url: "{{route('dashboard.get-schedule-jobs-count')}}",
                    data: {schedule_jobs_btn_value: schedule_jobs_btn_value},
                    success: function (result) {
                        console.log(result);
                        
                        $("#total_scheduled_jobs").text(result.total_schedule_job);
                        $("#total_completed_jobs").text(result.total_completed_schedule_job);
                        $("#total_pending_jobs").text(result.total_pending_schedule_job);
                    },
                    error: function (result) {
                        console.log(result);
                    }
                });

            });

            $('body').on('change', '#lead_chart_filter', function() {

                get_leads_chart_data();

            });

            $('body').on('change', '#quotation_chart_filter', function() {

                get_quotations_chart_data();

            });

            $('body').on('change', '#job_order_chart_filter', function() {

                var job_order_chart_filter = $("#job_order_chart_filter").find(":selected").val();

                var route1 = "{{route('sales-order.filter', ['unassigned', ':year'])}}";
                var route2 = "{{route('sales-order.filter', ['partially-assigned', ':year'])}}";

                route1 = route1.replace(":year", job_order_chart_filter);
                route2 = route2.replace(":year", job_order_chart_filter);

                $("#unassigned_sales_order_filter").attr('href', route1);
                $("#partial_assigned_sales_order_filter").attr('href', route2);

                get_job_orders_chart_data();

            });

        });
    </script>

@endsection
