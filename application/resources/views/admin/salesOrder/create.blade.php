@extends('theme.default')

@section('custom_css')
    <style>
        .sw>.tab-content {
            height: auto !important;
            position: relative;
            overflow: initial !important;
        }

        .highlighted-date {
            background-color: #ffcc00;
            /* Yellow background */
            color: #000;
            /* Black text color */
            font-weight: bold;
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
                        <h2 class="page-title">Add New Sales Order</h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">
                <div class="row">
                    <div class="col-lg-12">

                        <form class="row text-left" id="quotation_form" method="POST" enctype="multipart/form-data">
                            @csrf
                    
                            <input type="hidden" id="customer_id_lead" name="customer_id">
                            <input type="hidden" id="service_id_lead" name="service_address">
                            <input type="hidden" id="billing_id_lead" name="billing_address">
                            <input type="hidden" id="selected_date" name="schedule_date">
                            <input type="hidden" id="total_amount_val" name="total_amount_val" class="total-gross-amount-input">
                            <input type="hidden" id="tax_percent" name="tax_percent" class="tax_percent">
                    
                            <input type="hidden" id="tax" name="tax" class="total-tax-input">
                            <input type="hidden" id="grand_total_amount" name="grand_total_amount" class="total-amount-input">
                    
                            <input type="hidden" name="service_ids" value="">
                            <input type="hidden" name="service_names" value="">
                            <input type="hidden" name="service_descriptions" value="">
                    
                            <input type="hidden" name="form_unit_price" value="">
                            <input type="hidden" name="quantities" class="quantity-input-j" placeholder="quantity">
                            <input type="hidden" name="discounts" class="discount-input-j" placeholder="discount">
                    
                            <input type="hidden" name="tax" id="form_tax" value="{{ ($tax)?$tax->tax:0 }}">
                    
                            <div id="smartwizard" style="border: none; height: auto;">
                                <ul class="nav d-print-none">
                                    <li class="nav-item">
                                        <a class="nav-link" href="#step-1">
                                            <div class="num">1</div>
                                            Customer
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#step-2">
                                            <span class="num">2</span>
                                            Services
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#step-5">
                                            <span class="num">3</span>
                                            schedule
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#step-3">
                                            <span class="num">4</span>
                                            Address
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#step-6">
                                            <span class="num">5</span>
                                            Price Info
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " href="#step-4">
                                            <span class="num">6</span>
                                            Preview
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content mt-3" style="border: none;">
                                    <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <ul class="nav nav-pills nav-pills-primary" data-bs-toggle="tabs" role="tablist">
                                                    <li class="nav-item me-2" role="presentation">
                                                        <a href="#residential-view" id="residential-view-table"
                                                            class="nav-link active" data-bs-toggle="tab" aria-selected="true"
                                                            role="tab">Residential</a>
                                                    </li>
                                                    <li class="nav-item me-2" role="presentation">
                                                        <a href="#commercial-view" class="nav-link" id="commercial-view-table"
                                                            data-bs-toggle="tab" aria-selected="false" role="tab"
                                                            tabindex="-1">Commercial</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content mt-3">
                                                    <div class="tab-pane fade show active" id="residential-view" role="tabpanel">
                                                        <div class="mb-3">
                                                            <label class="form-label">Search By</label>
                                                            <div class="input-icon mb-3">
                                                                <input type="text" value="" class="form-control"
                                                                    placeholder="Search…" id="residentialSerch"
                                                                    onkeypress="SearchCustomer(1)">
                                                                <span class="input-icon-addon">
                                                                    <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                                        width="24" height="24" viewBox="0 0 24 24"
                                                                        stroke-width="2" stroke="currentColor" fill="none"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                                        <path d="M21 21l-6 -6"></path>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3" id="residential_list">
                                                            <div class="card">
                                                                @foreach ($customersResidential as $item)
                                                                    <a type="button" style="display: block" class="mb-3"
                                                                        onclick="displayCustomerDetails({{ $item->id }}, 'residential_customer_type')">
                                                                        <div class="card card-active">
                                                                            <div class="card">
                                                                                <div class="ribbon bg-yellow">Residential</div>
                                                                                {{-- <h1>background-color: {{ $item->color_code }}</h1> --}}
                                                                                <div class="card-body d-flex justify-content-between"
                                                                                    style="background-color: {{ $item->zone_color }}">
                                                                                    <div class="my-auto">
                                                                                        <label class="mb-0 text-black fw-bold"
                                                                                            style="font-size: 14px">{{ $item->customer_name }}</label>
                                                                                        <p class="m-0" style="color: #000">Tel -
                                                                                            {{ $item->mobile_number }}</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="commercial-view" role="tabpanel">
                                                        <div class="mb-3">
                                                            <label class="form-label">Search By</label>
                                                            <div class="input-icon mb-3">
                                                                <input type="text" value="" class="form-control"
                                                                    placeholder="Search…" id="commercial_search"
                                                                    onkeypress="SearchCustomer(0)">
                                                                <span class="input-icon-addon">
                                                                    <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                                        width="24" height="24" viewBox="0 0 24 24"
                                                                        stroke-width="2" stroke="currentColor" fill="none"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                                        <path d="M21 21l-6 -6"></path>
                                                                    </svg>
                                                                </span>
                                                            </div>
                 
                                                        </div>
                                                        <div class="mb-3" id="commercial_list">
                                                            <div class="card">
                                                                @foreach ($customersCommercial as $item)
                                                                    <a type="button" style="display: block" class="mb-3"
                                                                        onclick="displayCustomerDetails({{ $item->id }}, 'commercial_customer_type')">
                                                                        <div class="card card-active">
                                                                            <div class="card">
                                                                                <div class="ribbon bg-red">Commercial</div>
                                                                                {{-- <h1>background-color: {{ $item->color_code }}</h1> --}}
                                                                                <div class="card-body d-flex justify-content-between"
                                                                                    style="background-color: {{ $item->zone_color }}">
                                                                                    <div class="my-auto">
                                                                                        <label class="mb-0 text-black fw-bold"
                                                                                            style="font-size: 14px">{{ $item->individual_company_name }}</label>
                                                                                        <p class="m-0" style="color: #000">Tel -
                                                                                            {{ $item->mobile_number }}</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-9">
                                                <div class="d-flex" style="justify-content: space-between; align-items: center;">
                                                    <h5 class="modal-title">Customer Details</h5>
                                 
                                                    <a href="#" class="btn btn-info" data-bs-toggle="modal"
                                                        data-bs-target="#add-customer" onclick="showCRMModal()">
                                                        <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path d="M12 5l0 14"></path>
                                                            <path d="M5 12l14 0"></path>
                                                        </svg>
                                                        Add New
                                                    </a>                              
                                                </div>
                 
                                                <div class="card mt-3" id="residential-card">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label class="mb-0" for=""> <b>Customer Name</b> </label>
                                                                <p class="m-0"></p>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="mb-0" for=""><b>Contact No.</b> </label>
                                                                <p class="m-0"></p>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="mb-0" for=""> <b>Email</b></label>
                                                                <p class="m-0"></p>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="mb-0" for=""> <b>Territory</b></label>
                                                                <p class="m-0"></p>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-3">
                                                                <label class="mb-0" for=""><b>Status</b> </label>
                                                                <p><span class="badge bg-red">Pending</span></p>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="mb-0" for=""><b>Outstanding Amount</b> </label>
                                                                <p class="m-0"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                 
                                                <div class="card mt-3" id="commercial-card" style="display: none;">
                                                     <div class="card-body">
                                                         <div class="row">
                                                             <div class="col-md-3">
                                                                 <label class="mb-0" for=""> <b>Customer Name</b> </label>
                                                                 <p class="m-0"></p>
                                                             </div>
                                                             <div class="col-md-3">
                                                                 <label class="mb-0" for=""><b>Contact No.</b> </label>
                                                                 <p class="m-0"></p>
                                                             </div>
                                                             <div class="col-md-3">
                                                                 <label class="mb-0" for=""> <b>Email</b></label>
                                                                 <p class="m-0"></p>
                                                             </div>
                                                             <div class="col-md-3">
                                                                 <label class="mb-0" for=""> <b>Territory</b></label>
                                                                 <p class="m-0"></p>
                                                             </div>
                                                         </div>
                                                         <div class="row mt-3">
                                                             <div class="col-md-3">
                                                                 <label class="mb-0" for=""><b>Status</b> </label>
                                                                 <p><span class="badge bg-red">Pending</span></p>
                                                             </div>
                                                             <div class="col-md-3">
                                                                 <label class="mb-0" for=""><b>Outstanding Amount</b> </label>
                                                                 <p class="m-0"></p>
                                                             </div>
                                                         </div>
                                                     </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
                                        <div class="row">
                                            <div class="col-md-4 ">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Select Company<span
                                                                            class="text-danger">*</span></label>
                                                                    <select type="text" class="form-select" name="company_id" id="company-select">
                                                                        @foreach ($companyList as $list)
                                                                            <option value="{{ $list->id }}">{{ $list->company_name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <ul class="nav nav-pills nav-pills-success mt-3" id="pills-tab"
                                                                        role="tablist" style="border: none;">
                                                                        <li class="nav-item me-3">
                                                                            <a class="nav-link" id="pills-home-tab" data-bs-toggle="pill"
                                                                                href="#pills-home" role="tab"
                                                                                aria-controls="pills-home"
                                                                                aria-selected="true">Services</a>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <a class="nav-link" id="pills-profile-tab"
                                                                                data-bs-toggle="pill" href="#pills-profile"
                                                                                role="tab" aria-controls="pills-profile"
                                                                                aria-selected="false">Packages</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                    
                                                                <div class="">
                                                                    <label class="form-label"></label>
                                                                    <div class="input-icon mb-3">
                                                                        <input type="text" value="" class="form-control"
                                                                            placeholder="Search…S" oninput="searchService()"
                                                                            id="service-search">
                                                                        <span class="input-icon-addon">
                                                                            <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                                stroke-width="2" stroke="currentColor" fill="none"
                                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                                </path>
                                                                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0">
                                                                                </path>
                                                                                <path d="M21 21l-6 -6"></path>
                                                                            </svg>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-content p-0" id="pills-tabContent" style="border: none;">
                                                                    <div class="tab-pane fade" id="pills-home" role="tabpanel"
                                                                        aria-labelledby="pills-home-tab">
                                                                        <div class="mt-3">
                                                                            <div class="productsubshow mt-3">
                                                                                <div class="table-responsive" style="overflow-y: auto; max-height: 320px; height: auto;">
                                                                                    <table
                                                                                        class="table card-table table-vcenter text-center text-nowrap table-transparent"
                                                                                        id="service-table">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                {{-- <th>Service Id</th> --}}
                                                                                                <th>Product Code</th>
                                                                                                <th>Service Name</th>
                                                                                                <th>Price</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody style="cursor: pointer;">
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                                                        aria-labelledby="pills-profile-tab">
                                                                        <div class="mt-3">
                                                                            <div class="table-responsive">
                                                                                <table
                                                                                    class="table card-table table-vcenter text-center text-nowrap table-transparent">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Service Id</th>
                                                                                            <th>Service Name</th>
                                                                                            <th>Unit Price</th>
                                                                                            <th>Action</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                    
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8 pe-0">
                                                <div class="card">
                                                    <div class="card-body p-0">
                                                        <div class="table-responsive">
                                                            <table class="table card-table table-vcenter text-center text-nowrap"
                                                                id="selected-services-table" style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th hidden>SERVICE ID</th>
                                                                        <th>PRODUCT CODE</th>
                                                                        <th>Service</th>
                                                                        <th>SERVICE DESCRIPTION</th>
                                                                        <th hidden>Unit Price</th>
                                                                        <th>Hour/Session</th>
                                                                        <th>Total Sessions</th>
                                                                        <th>Weekly Freq</th>
                                                                        <th>Qty</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="selected-services-table-tbody">
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="step-5" class="tab-pane" role="tabpanel" aria-labelledby="step-5">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="card card-link card-link-pop">
                                                    <div class="card-status-start bg-primary"></div>
                                                    <div class="card-stamp">
                                                        <div class="card-stamp-icon bg-white text-primary">
                                                            <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <path
                                                                    d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="customer-card-slide3">
                                                        <div class="card-body">
                                                            <h3 class="card-title mb-1" style="color: #1F3BB3;"><b
                                                                    class="me-2">Customer Details added</b>
                                                            </h3>
                                                            <p class="card-p d-flex align-items-center mb-2 ">
                                                                <i class="fa-solid fa-phone me-2" style="font-size: 14px;"></i>
                                                                {{-- +65 9758697820 --}}
                                                            </p>
                                                            <p class="card-p  d-flex align-items-center mb-2">
                                                                <i class="fa-solid fa-envelope me-2" style="font-size: 14px;"></i>
                                                                {{-- abc@pvtltd.com --}}
                                                            </p>
                                                            <p class="card-p d-flex mb-2">
                                                                <i class="fa-solid fa-map-pin me-2 pt-1" style="font-size: 14px;"></i>
                                                                {{-- 103 Rasadhi Appartment Wadaj Ahmedabad 380004. --}}
                                                            </p>
                                                            <hr class="my-3">
                                                            <h3 class="card-title mb-1" style="color: #1F3BB3;">
                                                                <b>Service Details</b>
                                                            </h3>
                                                            <div class="amount" id="">
                                                                {{-- <p class="m-0 card-p">Floor Cleaning(5)</p>
                                                             <p class="m-0 card-p">Home Cleaning(2)</p> --}}
                                                            </div>
                                                            <hr class="my-3">
                 
                                                            {{-- <div class="driver mt-2">
                                                             <h3 class="card-title mb-1" style="color: #1F3BB3;">
                                                                 <b>Amount Details</b>
                                                             </h3>
                                                             <div class="row amount_details">
                                                                 <div class="col-md-7">
                                                                     <p class="m-0"> Total:</p>
                                                                 </div>
                                                                 <div class="col-md-5">
                                                                     <p class="m-0">$200.00</p>
                                                                 </div>
                                                             </div>
                                                         </div> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div id="calendar"></div>
                                                <br>
                                                <div class="row mt-3">
                    
                                                    <div class="form-group col-lg-6 col-md-6 col-sm-12 text-start">
                                                        <label for="message-text" class="col-form-label">Date Of Cleaning</label>
                                                        <input type="text" id="date_of_cleaning" name="date_of_cleaning"
                                                            class="form-control" placeholder="dd/mm/yyyy">
                                                    </div>
                                                    <div class="form-group col-lg-6 col-md-6 col-sm-12 text-start">
                                                        <label for="message-text" class="col-form-label">Time of Cleaning</label>
                                                        <input type="time" class="form-control" placeholder="Time of Cleaning"
                                                            id="time_of_cleaning" name="time_of_cleaning">
                                                    </div>
                                                </div>
                    
                                            </div>
                                        </div>
                                    </div>

                                    <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="card card-link card-link-pop">
                                                    <div class="card-status-start bg-primary"></div>
                                                    <div class="card-stamp">
                                                        <div class="card-stamp-icon bg-white text-primary">
                                                            <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <path
                                                                    d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="customer-card-slide3">
                                                        <div class="card-body">
                                                            <h3 class="card-title mb-1" style="color: #1F3BB3;"><b
                                                                    class="me-2">Customer Details added</b>
                                                            </h3>
                                                            <p class="card-p d-flex align-items-center mb-2 ">
                                                                <i class="fa-solid fa-phone me-2" style="font-size: 14px;"></i>
                                                                {{-- +65 9758697820 --}}
                                                            </p>
                                                            <p class="card-p  d-flex align-items-center mb-2">
                                                                <i class="fa-solid fa-envelope me-2" style="font-size: 14px;"></i>
                                                                {{-- abc@pvtltd.com --}}
                                                            </p>
                                                            <p class="card-p d-flex mb-2">
                                                                <i class="fa-solid fa-map-pin me-2 pt-1" style="font-size: 14px;"></i>
                                                                {{-- 103
                                                             Rasadhi
                                                             Appartment Wadaj Ahmedabad 380004. --}}
                                                            </p>
                                                            <hr class="my-3">
                                                            <h3 class="card-title mb-1" style="color: #1F3BB3;">
                                                                <b>Service Details</b>
                                                            </h3>
                                                            <div class="amount" id="">
                                                                {{-- <p class="m-0 card-p">Floor Cleaning(5)</p>
                                                             <p class="m-0 card-p">Home Cleaning(2)</p> --}}
                                                            </div>
                                                            <hr class="my-3">
                 
                                                            {{-- <div class="driver mt-2">
                                                             <h3 class="card-title mb-1" style="color: #1F3BB3;">
                                                                 <b>Amount Details</b>
                                                             </h3>
                                                             <div class="row amount_details">
                                                                 <div class="col-md-7">
                                                                     <p class="m-0"> Total:</p>
                                                                 </div>
                                                                 <div class="col-md-5">
                                                                     <p class="m-0">$200.00</p>
                                                                 </div>
                                                             </div>
                                                         </div> --}}
                 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-9 pe-0">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <ul class="nav nav-pills nav-pills-primary" data-bs-toggle="tabs" role="tablist">
                                                            <li class="nav-item me-2" role="presentation">
                                                                <a href="#tab-one" class="nav-link active" data-bs-toggle="tab"
                                                                    aria-selected="true" role="tab">Service Address</a>
                                                            </li>
                                                            <li class="nav-item me-2" role="presentation">
                                                                <a href="#tab-two" class="nav-link" data-bs-toggle="tab"
                                                                    aria-selected="false" role="tab" tabindex="-1">Billing
                                                                    Address</a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content">
                                                            <div class="tab-pane active show" id="tab-one" role="tabpanel">
                                                                <div class="row my-3 service_address_row">
                                                                    {{-- <div class="col-lg-4 col-md-4 col-sm-12">
                                                                        <label for="radio-card-1" class="radio-card">
                                                                            <input type="radio" name="radio-card" id="radio-card-1"
                                                                                checked />
                                                                            <div class="card-content-wrapper service_address">
                                                                                <span class="check-icon"></span>
                                                                                <div class="card-content">
                                                                                    <h4>Sky Enterprice de</h4>
                                                                                    <p class="mb-1"> <strong>Contact
                                                                                            No:</strong>1234567890</p>
                                                                                    <p class="mb-1"> <strong>Email
                                                                                            ID:</strong>ABC@gmail.com</p>
                                                                                    <p class="mb-1"><strong>Address:</strong>8 Shopping
                                                                                        Centre, 9 Bishan Place,
                                                                                        Singapore 579837
                                                                                    </p>
                                                                                    <p class="mb-1"><strong>Unit No:</strong>12345h</p>
                                                                                    <p class="mb-1"><strong>Zone:</strong>South</p>
                                                                                    <div class="form-check">
                                                                                        <input class="form-check-input" type="radio"
                                                                                            name="flexRadioDefault" id="flexRadioDefault2"
                                                                                            checked>
                                                                                        <label class="form-check-label"
                                                                                            for="flexRadioDefault2">
                                                                                            Default Address
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </label>
                                                                    </div> --}}
                                                                </div>
                                                                <div class="row" id="serviceAddressForm">
                                                                    <div class="col-md-12 my-3">
                                                                        <button type="button" class="btn btn-blue add_btn">+ Add
                                                                            Address</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane" id="tab-two" role="tabpanel">
                                                                <div class="row my-3 billing_address_row">
                                                                    {{-- <div class="col-md-12">
                                                                        <div class="my-3">
                                                                            <label class="form-check-label">
                                                                                <input type="checkbox" class="form-check-input"
                                                                                    onclick="getBillingAddress()">
                                                                                Same as Service Address
                                                                                <i class="input-helper"></i>
                                                                            </label>
                                                                        </div>
                                                                    </div> --}}

                                                                    <div class="row" id="billing-addresses">
                                                                        <!-- Billing addresses will be dynamically appended here -->
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <!-- <div class="col-md-12 my-3">
                                                                    <button type="button" class="btn btn-blue add_btn_2">+ Add Address</button>
                                                                    </div> -->
                                                                    <div class="col-md-12 add_address_2" style="display: none;">
                                                                        <div class="table-responsive mb-3">
                                                                            <!-- BILLING ADDRESS FORM -->
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

                                    <div id="step-6" class="tab-pane" role="tabpanel" aria-labelledby="step-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-3 accordion pull-right" id="accordionExample1">
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="headingOne">
                                                                        <a href="#collapseTwo" class="btn btn-light" type="button"
                                                                            data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                                            aria-expanded="false" aria-controls="collapseTwo">
                                                                            Add Discount
                                                                        </a>
                                                                    </h2>
                                                                    <div id="collapseTwo" class="accordion-collapse collapse "
                                                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                                        {{-- <div class="col-md-4"> --}}
                                                                        <div class="accordion-body">
                                                                            <form action="">
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input discount_type"
                                                                                        type="radio" name="discount_type"
                                                                                        id="persentage_discount" value="percentage"
                                                                                        checked>
                                                                                    <label class="form-check-label"
                                                                                        for="persentage_discount">
                                                                                        Percentage Discount
                                                                                    </label>
                                                                                </div>
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input discount_type"
                                                                                        type="radio" name="discount_type"
                                                                                        id="amount_discount" value="amount">
                                                                                    <label class="form-check-label" for="amount_discount">
                                                                                        Amount Discount
                                                                                    </label>
                                                                                </div><br>
                                                                                <div class="" style=""
                                                                                    id="persent_discount_feild">
                                                                                    <label for="persentage_discount">Percentage
                                                                                        Discount(%)</label><br><br>
                                                                                    <input type="number" name="persentage_discount"
                                                                                        id="persentage_discount_value"
                                                                                        class="form-control disc_field"
                                                                                        data-disc_type="percentage" value="0"
                                                                                        min="0" step="0.01">
                                                                                </div>
                                                                                <div class="" style="display:none;"
                                                                                    id="amount_discount_feild">
                                                                                    <label for="amount_discount">Amount
                                                                                        Discount</label><br><br>
                                                                                    <input type="number" name="amount_discount"
                                                                                        id="amount_discount_value"
                                                                                        class="form-control disc_field"
                                                                                        data-disc_type="amount" value="0"
                                                                                        min="0" step="0.01">
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                        {{-- </div> --}}
                                                                    </div>
                                                                </div>
                                                            </div>
                    
                                                            <div class="col-md-9" style="display: flex; justify-content: flex-end;">
                                                                <div class="form-check">
                                                                    <input type="checkbox" class="form-check-input" name="add_tax_check" id="add_tax_check" value="yes" checked>
                                                                    <label for="">Tax ({{ ($tax)?$tax->tax:0 }}%) Add</label>
                                                                </div>
                                                            </div>
                    
                                                        </div>
                    
                                                        <div class="mb-3">
                                                            <button type="button" class="btn btn-dark" style="float: right;"
                                                                onclick="addPriceFeild()">Add</button><br>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <table class="table" id="priceInfoTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th scope="col"></th>
                                                                            <th scope="col">Product Code</th>
                                                                            <th scope="col">service/Products</th>
                                                                            <th scope="col">Description</th>
                                                                            <th scope="col">Unit Price</th>
                                                                            <th scope="col">Qty</th>
                                                                            <th scope="col">Sub Total(SGD)</th>
                                                                            <th scope="col">Discount(%)</th>
                                                                            <th scope="col">Net Total(SGD)</th>
                                                                            <th scope="col">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="addNewFeild">
                            
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="mt-3" style="width: 50%; float: left;">
                                                                    <label for="">Remarks</label>
                                                                    <textarea class="form-control" name="quotation_remarks" id="quotation_remarks" cols="30" rows="5"></textarea>
                                                                </div>
                            
                                                                <table class="mt-3" style="width: 35%; float: right;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="width: 40%;">Sub Total :</td>
                                                                            <td id="pi_subtotal">$0.00</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width: 40%;">Net Total : </td>
                                                                            <td id="pi_nettotal">$0.00</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width: 40%;" id="pi_disc_label">Percentage Discount(%) :</td>
                                                                            <td id="pi_discount">0%</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width: 40%;">Tax <span id="pi_tax">(0%)</span></td>
                                                                            <td id="pi_taxamt">$0.00</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width: 40%;">Grand Total</td>
                                                                            <td id="pi_grandtotal">$0.00</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>    
                                                            </div>
                                                        </div>       
                                                    </div>
                                                </div>                                                   
                                            </div>
                                        </div>
                                    </div>

                                    <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">
                    
                                    </div>
                                </div>
                            </div>
                            <!-- Include optional progressbar HTML -->
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SERVICE ADDRESS FORM  -->
    <form id="service_address_form" method="POST" style="display: none;">
        @csrf
        <div class="col-md-12 add_address">
            <div class="row my-3 service_addr_group">
                <input type="hidden" id="customer_id_service" name="customer_id">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="">Person Incharge Name</label>
                        <input type="text" placeholder="Enter Name" name="person_incharge_name[]"
                            class="form-control" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="">Contact No</label>
                        <input type="text" placeholder="Enter Number" name="contact_no[]" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="">Email Id</label>
                        <input type="text" placeholder="Enter Email" name="email_id[]" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="">Postal Code</label>
                        <input type="text" placeholder="Enter Code" name="postal_code[]"
                            onchange="handlePostalCodeLookup_service(this)" class="form-control postal_code">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="">Zone</label>
                        <input type="text" placeholder="Enter Zone" name="zone[]" class="form-control zone">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="">Address</label>
                        <input type="text" placeholder="Enter Address" name="address[]" class="form-control address">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="">Unit No</label>
                        <input type="text" placeholder="Enter Unit No." name="unit_number[]" class="form-control">
                    </div>
                </div>

                <input type="hidden" name="territory[]" class="form-control territory">

                <div class="col-md-4 my-auto">
                    <button type="button" class="btn btn-blue" id="rowAdder_add_lead">+</button>
                </div>
            </div>
            <div id="newinput_add_lead"></div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary" id="service_address_btn">save</button>
                </div>
            </div>
        </div>
    </form>

    <!-- BILLING ADDRESS FORM  -->
    <form id="billing_address_form" method="POST" style="overflow: auto;">
        @csrf
        <input type="hidden" id="customer_id_billing" name="customer_id">
        <table class="table card-table table-vcenter text-nowrap table-transparent" id="billing_address">
            <thead>
                <tr>
                    <th>Person Incharge Name</th>
                    <th>Contact No</th>
                    <th>Email</th>
                    <th>Postal Code</th>
                    <th>Address</th>
                    <th>Unit No</th>
                    <th>
                        <button type="button" class="btn btn-blue billing-add-row">+</button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="bill_addr_group">
                    <td>
                        <input class="form-control" type="text" placeholder="Person Incharge name"
                            name="b_person_incharge_name[]" style="width: 200px;"/>
                    </td>
                    <td>
                        <input class="form-control" type="text" placeholder="Contact no" name="b_contact_no[]" style="width: 200px;"/>
                    </td>
                    <td>
                        <input class="form-control" type="email" placeholder="Email" name="b_email[]" style="width: 200px;"/>
                    </td>
                    <td>
                        <input class="form-control postal_code" type="text" placeholder="Enter Code"
                            name="b_postal_code[]" onchange="handlePostalCodeLookup_bill(this)" style="width: 200px;"/>
                    </td>
                    <td>
                        <input class="form-control address" type="text" placeholder="Address" name="b_address[]" style="width: 200px;"/>
                    </td>
                    <td>
                        <input class="form-control" type="text" placeholder="Enter Unit No" name="b_unit_number[]" style="width: 200px;"/>
                        <input type="hidden" name="b_zone[]" class="zone">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger delete-row">-</button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td>
                        <button type="submit" class="btn btn-primary" id="billing_address_btn">save</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </form>

    {{-- add customer modal --}}
    <div class="modal modal-blur fade" id="add-customer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 800px;">
            <div class="modal-content" id="add-customer-content">

            </div>
        </div>
    </div>

@endsection

@section('javascript')

    <script>
    
        function showTableData() {
            searchService();
            $('.productsubshow').show();
        }

        function searchService() {
            var searchValue = $('#service-search').val().trim();
            var selectedCompanyId = $('#company-select').val();

            // if (!searchValue) {
            //     $('.productsubshow').hide();
            //     return;
            // }

            $.ajax({
                url: '{{ route('search.service') }}',
                type: 'POST',
                data: {
                    search: searchValue,
                    company_id: selectedCompanyId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response);
                    var tableBody = $('#service-table tbody');
                    tableBody.empty();

                    response.forEach(function(service) {
                        var row = `
                        <tr class=" ripple add-service-btn"
                                data-service-id="${service.id}"
                                data-service-name="${service.service_name}"
                                data-service-description="${service.description}"
                                data-service-price="${service.price}"
                                data-service-discount="${service.discount}"
                                data-service-net-total="${service.net_total}"
                                data-service-quantity="${service.quantity}"
                                data-service_hour_session="${service.hour_session}"
                                data-service_total_session="${service.total_session}"
                                data-service_weekly_freq="${service.weekly_freq}"
                                data-service_product_code="${service.product_code}" onclick="addService(this)">
                            <td>${service.product_code}</td>
                            <td style="text-align: left;">
                                ${service.service_name}
                            </td>
                            <td>${service.price}</td>
                        </tr>
                        `;
                        
                        tableBody.append(row);
                    });

                    // Show the table with search results
                    $('.productsubshow').show();

                },
            });

        }

        function addService(thiss) {
            // console.log(thiss);
            const serviceId = $(thiss).data('service-id') || '';
            const serviceName = $(thiss).data('service-name') || '';
            const serviceNetTotal = $(thiss).data('service-net-total') || '';
            const serviceDescription = $(thiss).data('service-description') || '';
            const servicePrice = $(thiss).data('service-price') || '';
            const quantityInput = $(thiss).closest('tr').find('.quantity-input') || '';
            const quantity = parseInt(quantityInput.val(), 10) || 1;

            var hour_session = $(thiss).data('service_hour_session') || '';
            var total_session = $(thiss).data('service_total_session') || '';
            var weekly_freq = $(thiss).data('service_weekly_freq') || '';
            var service_product_code = $(thiss).data('service_product_code') || '';


            //   if ($('#selected-services-table tbody tr[data-service-id="' + serviceId + '"]').length > 0) {
            //     alert('This service is already added.');
            //     return;
            //   }
            // console.log("servicePrice:", servicePrice);
            // console.log("quantity:", quantity);

            const subTotal = (quantity * servicePrice);
            const netTotal = subTotal;


            var row = `<tr>                        
                        <td hidden id="servicesId" class="service_id">
                            ${serviceId}
                            <input type="hidden" class="form-control" value="${serviceId}" name="service_id[]">
                        </td>
                        <td class="service_product_code">${service_product_code}</td>
                        
                        <td style="text-align: left;">
                            <input type="text" class="form-control service_name change_service_name" value="${serviceName}" name="service_name[]" style="width: auto;">
                        </td>

                        <td class="description-cell" data-full-text="${serviceDescription}">
                            <textarea name="service_desc[]" rows="2" cols="40" class="form-control change_service_desc" style="width: auto;"  >${serviceDescription}</textarea>
                        </td>


                        <td>${hour_session}</td>
                        
                        <td>
                            <input type="number" name="service_total_session[]" class="form-control change_service_total_session" value="${total_session}" style="margin:auto; display: inline-block; width: 70px;">
                        </td>

                        <td>${weekly_freq}</td>

                        <td hidden><input type="number" class="form-control price" name="service_price[]" value="${servicePrice}"></td>
                        
                        <td class="p-0" id="quantitys">
                            <input type="number" name="service_qty[]" class="form-control change_service_qty quantity-input qty${serviceId}" placeholder="quantity"
                            value="${quantity ? quantity : 1}" style="margin:auto; display: inline-block; width: 70px;">
                        </td>


                        <td>
                            <button class="btn btn-danger ripple remove-service-btn" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cross m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="white" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="currentColor" d="M6 6l12 12" />
                                <path stroke="currentColor" d="M6 18L18 6" />
                                </svg>
                            </button>
                        </td>
                    </tr>`;

            const priceRow = `<tr class='checkedRow'>
                                <input type="hidden" class="form-control price" value="${serviceId}" name="selected_service_id" id="selected_service_id">
                                 <input type="hidden" class="form-control pi_service_total_session" value="${total_session}" id="pi_service_total_session${serviceId}">
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input checkbox-row" type="checkbox" value="${serviceId}" id="flexCheckChecked" checked>
                                    </div>
                                </td>
                                <td class="pi_service_product_code" id="pi_service_product_code${serviceId}">${service_product_code}</td>
                                <td>             
                                    <input type="text" id="serviceName${serviceId}" class="form-control" value="${serviceName}">
                                </td>
                                <td>
                                    <textarea id="service_desc${serviceId}" class="form-control" cols="30" rows="2">${serviceDescription}</textarea>
                                </td>
                                <td><input type="number" class="form-control price" id="unitPrice${serviceId}" value="${servicePrice}" onchange="quantityUpdate(${serviceId})"></td>
                                <td><input type="number" class="form-control quantity-input qty${serviceId}" id="quantityInput${serviceId}" value="${quantity ? quantity : 1}" onchange="quantityUpdate(${serviceId})"></td>
                                <td><input type="number" class="form-control sub-total-input" id="sub-total-tr${serviceId}" value="${subTotal}"></td>
                                <td><input type="number" class="form-control discount-input" id="discountInput${serviceId}" value="" onchange="updateNettotal(${serviceId})"></td>
                                <td><input type="number" class="form-control net-total-input" id="nettotaltd${serviceId}" placeholder="" value="${netTotal}"></td>

                                <td>
                                    <button class="btn btn-danger ripple remove_price_info_btn" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cross m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="white" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="currentColor" d="M6 6l12 12" />
                                        <path stroke="currentColor" d="M6 18L18 6" />
                                        </svg>

                                    </button>
                                </td>
                            </tr>`;

            // totalDiscount = $('#discountInput').val();
            // totalServicePrice += parseFloat(servicePrice);
            // quotationView += `<tr>
            //                     <td>${serviceName}</td>
            //                     <td>${quantity ? quantity : 1}</td>
            //                     <td>
            //                         <div class="d-flex justify-content-between">

            //                             <div>$${servicePrice}</div>

            //                         </div>
            //                     </td>
            //                     <td>
            //                         <div class="d-flex justify-content-between">
            //                             <div>${totalServicePrice}<div>
            //                         </div>
            //                     </td>
            //                 </tr>`;

            // $('#selected-services-table tbody').append(row);
            // $('#priceInfoTable tbody').append(priceRow);
            addItem(serviceId, row, priceRow);
            updateAmountDiv();

            $('.service-id').val(serviceId);
            const serviceIdsInput = $('input[name="service_ids"]');
            const serviceNamesInput = $('input[name="service_names"]');
            const serviceDescriptionsInput = $('input[name="service_descriptions"]');

            const unitPriceInput = $('input[name="form_unit_price"]');
            const qty = $('input[name="quantities"]').val(quantity);
            const currentServiceIds = serviceIdsInput.val();
            const updatedServiceIds = currentServiceIds ? `${currentServiceIds},${serviceId}` : serviceId;
            serviceIdsInput.val(updatedServiceIds);

            const currentServiceNames = serviceNamesInput.val();
            const updatedServiceNames = currentServiceNames ? `${currentServiceNames},${serviceName}` : serviceName;
            serviceNamesInput.val(updatedServiceNames);

            const currentServiceDescription = serviceDescriptionsInput.val();
            const updatedServiceDescription = currentServiceDescription ?
                `${currentServiceDescription},${serviceDescription}` : serviceDescription;
            serviceDescriptionsInput.val(updatedServiceDescription);

            const currentServiceUnitPrice = unitPriceInput.val();
            const updatedServiceUnitPrice = currentServiceUnitPrice ? `${currentServiceUnitPrice},${servicePrice}` :
                servicePrice;
            unitPriceInput.val(updatedServiceUnitPrice);


            const selectedServicesInput = $('#selected-services-id');
            const currentSelectedServices = selectedServicesInput.val();
            const updatedSelectedServices = currentSelectedServices ? `${currentSelectedServices},${serviceId}` :
                serviceId;
            selectedServicesInput.val(updatedSelectedServices);
            $(document).on('change', '.quantity-input', function() {
                const quantity = parseInt($(this).val(), 10);
            });

        }

        var itemsArray = [];

        function addItem(id, row, priceRow) {
            // Check if the item with the given id already exists in the array
            const existingItem = itemsArray.find(item => item.id === id);

            if (existingItem) {
                existingItem.qty += 1;
                $('.qty' + existingItem.id).val(existingItem.qty);

                var quantity = $('#quantityInput' + id).val();
                var unitPrice = $('#unitPrice' + id).val()
                var subtotal = quantity * unitPrice;
                $('#sub-total-tr' + id).val(subtotal);
                $('#nettotaltd' + id).val(subtotal);
            } else {
                // If the item doesn't exist, add a new object to the array
                itemsArray.push({
                    id: id,
                    qty: 1
                });
                $('#selected-services-table tbody').append(row);
                $('#priceInfoTable tbody').append(priceRow);
            }

            get_price_info_table();

            // console.log(itemsArray);
        }

        function updateAmountDiv() {
            let amountHTML = '';
            let amountDetailHTML = '';
            let totalAmount = '';

            // console.log(amountHTML);

            $('.amount').html("");

            $('#selected-services-table tbody tr').each(function() {
                // console.log(this);
                // const serviceName = $(this).find('td:eq(1)').text();
                const serviceName = $(this).find('.service_name').val();
                const quantity = parseInt($(this).find('.quantity-input').val(), 10);
                // const servicePrice = parseInt($(this).find('.price').val());
                const servicePrice = $(this).find('.price').val();
                totalAmount += servicePrice * quantity;
                //  console.log(totalAmount);

                amountHTML += `<p class="m-0 card-p">${serviceName}(${quantity})</p>`;

                // amountDetailHTML += `<div class="col-md-7">
                //                          <p class="m-0"> Total:</p>
                //                      </div>
                //                      <div class="col-md-5">
                //                          <p class="m-0">$${totalAmount}</p>
                //                      </div>`;
            });

            // console.log(amountHTML);

            $('.amount').html(amountHTML);

            // $('.amount_details').html(amountDetailHTML);
        }

        function get_price_info_table() {

            var html = "";

            $('#priceInfoTable tbody').html("");

            $('#selected-services-table tbody tr').each(function() {
                // console.log(this);

                var get_service_id = parseInt($(this).find(".service_id").text());
                var get_service_product_code = $(this).find(".service_product_code").text();
                var get_service_name = $(this).find(".service_name").val();
                var get_service_desc = $(this).find(".change_service_desc").val();
                var get_service_unit_price = parseFloat($(this).find(".price").val());
                var get_service_qty = parseInt($(this).find(".change_service_qty").val());
                var get_service_total_session = parseInt($(this).find(".change_service_total_session").val());

                var get_subTotal = (get_service_qty * get_service_unit_price);
                var get_netTotal = get_subTotal;

                var existingItem = itemsArray.find(item => item.id == get_service_id);
                if (existingItem) {
                    existingItem.qty = get_service_qty;
                }

                html += `<tr class='checkedRow'>
                            <input type="hidden" class="form-control price" value="${get_service_id}" name="selected_service_id" id="selected_service_id">
                            <input type="hidden" class="form-control pi_service_total_session" value="${get_service_total_session}" id="pi_service_total_session${get_service_id}">
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input checkbox-row" type="checkbox" value="${get_service_id}" id="flexCheckChecked" checked>
                                </div>
                            </td>
                            <td class="pi_service_product_code" id="pi_service_product_code${get_service_id}">${get_service_product_code}</td>
                            <td>
                                <input type="text" id="serviceName${get_service_id}" class="form-control" value="${get_service_name}" style="width:auto;">
                            </td>
                            <td>
                                <textarea id="service_desc${get_service_id}" class="form-control" cols="30" rows="2">${get_service_desc}</textarea>
                            </td>
                            <td><input type="number" class="form-control price" id="unitPrice${get_service_id}" value="${get_service_unit_price}" onchange="quantityUpdate(${get_service_id})"></td>
                            <td><input type="number" class="form-control quantity-input qty${get_service_id}" id="quantityInput${get_service_id}" value="${get_service_qty ? get_service_qty : 1}" onchange="quantityUpdate(${get_service_id})"></td>
                            <td><input type="number" class="form-control sub-total-input" id="sub-total-tr${get_service_id}" value="${get_subTotal}"></td>
                            <td><input type="number" class="form-control discount-input" id="discountInput${get_service_id}" value="" onchange="updateNettotal(${get_service_id})"></td>
                            <td><input type="number" class="form-control net-total-input" id="nettotaltd${get_service_id}" placeholder="" value="${get_netTotal}"></td>

                            <td>
                                <button class="btn btn-danger ripple remove_price_info_btn" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cross m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="white" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="currentColor" d="M6 6l12 12" />
                                    <path stroke="currentColor" d="M6 18L18 6" />
                                    </svg>

                                </button>
                            </td>
                        </tr>`;

            });

            $('#priceInfoTable tbody').html(html);

            calculate_price_info();
        }

        function calculate_price_info() {
            var pi_subtotal = 0;
            var pi_nettotal = 0;
            var pi_total_discount = 0;
            var pi_grand_total = 0;
            var pi_total = 0;
            var pi_tax_amt = 0;

            var pi_discount_type = $("input[name='discount_type']:checked").val();


            $('#priceInfoTable tbody tr').each(function() {
                // console.log(this);

                // service start

                var check_box = $(this).find('.checkbox-row');

                if (check_box.is(":checked")) {
                    // console.log(this);

                    var pi_price = parseFloat($(this).find('.sub-total-input').val());
                    var pi_gross_amt = parseFloat($(this).find('.net-total-input').val());

                    pi_subtotal += pi_price;
                    pi_nettotal += pi_gross_amt;
                }

                // service end

                // add ons service start

                var add_ons_checkbox = $(this).find('.add_ons_checkbox');

                if (add_ons_checkbox.is(":checked")) {
                    // console.log(this);

                    var pi_price = parseFloat($(this).find('.add_ons_sub_total').val());
                    var pi_gross_amt = parseFloat($(this).find('.add_ons_net_total').val());

                    pi_subtotal += pi_price;
                    pi_nettotal += pi_gross_amt;
                }

                // add ons service end

            });

            // discount staat

            if (pi_discount_type == "percentage") {
                pi_total_discount = parseFloat($("#persentage_discount_value").val());

                var pi_total_disc_amt = pi_nettotal * (pi_total_discount / 100);

                var disc_html1 = `Percentage Discount(%) :`;
                var disc_html2 = `${parseFloat(pi_total_discount).toFixed(2)}%`;

            }
            else {
                pi_total_discount = parseFloat($("#amount_discount_value").val());

                var pi_total_disc_amt = pi_total_discount;

                var disc_html1 = `Amount Discount :`;
                var disc_html2 = `$${parseFloat(pi_total_discount).toFixed(2)}`;
            }

            // discount end

            // tax start

            if($("#add_tax_check").is(":checked"))
            {
                var pi_tax = $("#form_tax").val() || 0;
                var pi_tax_type = "exclusive";
            }
            else
            {
                // var pi_tax = 0;
                var pi_tax = $("#form_tax").val() || 0;
                var pi_tax_type = "inclusive";
            }

            pi_tax = parseFloat(pi_tax);

            // tax end

            pi_total = pi_nettotal - pi_total_disc_amt;

            if(pi_tax_type == "exclusive")
            {
                pi_tax_amt = pi_total * pi_tax / 100;
                pi_grand_total = pi_total + pi_tax_amt;
            }
            else if(pi_tax_type == "inclusive")
            {
                pi_tax_amt = (pi_total / (100 + pi_tax)) * pi_tax;
                pi_grand_total = pi_total;
            }

            $('#pi_subtotal').text("$"+pi_subtotal.toFixed(2));
            $('#pi_nettotal').text("$"+pi_nettotal.toFixed(2));
            $("#pi_disc_label").text(disc_html1);
            $("#pi_discount").text(disc_html2);
            $('#pi_tax').text(`(${pi_tax}%)`);
            $('#pi_taxamt').text("$"+pi_tax_amt.toFixed(2));
            $('#pi_grandtotal').text("$"+pi_grand_total.toFixed(2));
        }

        function SearchCustomer(type) {
            var search;
            var searchBox;

            if (type == 1) {
                searchBox = $('#residential_list');
                search = $('#residentialSerch').val();
            } else {
                searchBox = $('#commercial_list');
                search = $('#commercial_search').val();
            }

            if (!search.trim()) {
                searchBox.empty().hide();
                return;
            }

            $.ajax({
                url: '{{ route('quotataion.customer.search') }}',
                type: 'POST',
                data: {
                    type: type,
                    search: search,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {

                    searchBox.empty().hide();

                    // $('#residential_list').empty().hide();
                    // $('#commercial_list').empty().hide();

                    response.forEach(function(item) {
                        if (item.customer_type === 'residential_customer_type') {
                            // Append residential search result
                            var residentialList = `
                            <a type="button" style="display: block;" class="mb-3" onclick="displayCustomerDetails(${item.id}, 'residential_customer_type')">
                            <div class="card card-active">

                            <div class="card">
                                <div class="ribbon bg-yellow">Residential</div>
                            <div class="card-body d-flex justify-content-between" style="background-color: ${item.zone_color}">
                            <div class="my-auto">
                                <label class="mb-0 text-black fw-bold" style="font-size: 14px">${item.customer_name}</label>
                                <p class="m-0" style="color: #000">Tel - +${item.mobile_number}</p>
                            </div>
                            </div>
                            </div>
                            </div>
                            </a>`;
                            searchBox.append(residentialList);
                        } else {
                            // Append commercial search result
                            var commercialList = `
                                    <a type="button" style="display: block;" class="mb-3" onclick="displayCustomerDetails(${item.id}, 'commercial_customer_type')">
                                    <div class="card card-active">

                                    <div class="card">
                                        <div class="ribbon bg-red">Commercial</div>

                                    <div class="card-body d-flex justify-content-between" style="background-color: ${item.zone_color}">
                                    <div class="my-auto">
                                        <label class="mb-0 text-black fw-bold" style="font-size: 14px">${item.individual_company_name}</label>
                                        <p class="m-0" style="color: #000">Tel - +${item.mobile_number}</p>
                                    </div>
                                    </div>
                                    </div>
                                    </div>
                                    </a>
                                    `;
                            searchBox.append(commercialList);
                        }
                    });
                    searchBox.show();
                },
            });
        }

        function displayCustomerDetails(customerId, type) 
        {
            $.ajax({
                url: '{{ route('lead.customer.details') }}',
                type: 'POST',
                data: {
                    id: customerId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(customer) {
                    // alert(customerId)

                    console.log(customer);

                    var new_customer_remark = "";

                    if(customer.customer_remark)
                    {
                        var temp_remark = customer.customer_remark.split("\n") || "";
                                                
                        $.each(temp_remark, function (key, value) { 

                            if(value != "" && value != null)
                            {
                                new_customer_remark += value + "<br>";
                            }

                        });

                        // console.log(temp_remark);                       
                    }

                    // console.log(new_customer_remark);

                    $('#customer_id_service').val(customerId);
                    $('#customer_id_billing').val(customerId);
                    $('#customer_id_lead').val(customerId);

                    if (type === 'residential_customer_type') 
                    {
                        $('#residential-card').html(`<div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label class="mb-0" for=""> <b>Customer Name</b> </label>
                                                                <p class="m-0">${customer.customer_name}</p>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="mb-0" for=""><b>Mobile Number</b> </label>
                                                                <p class="m-0">+65-${customer.mobile_number}</p>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="mb-0" for=""> <b>Email</b></label>
                                                                <p class="m-0">${customer.email}</p>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="mb-0" for=""><b>Language Spoken</b> </label>
                                                                <p class="m-0">${customer.language_name}</p>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-3">
                                                                <label class="mb-0" for=""> <b>Select Type</b> </label>
                                                                <p class="m-0">${customer.customer_type}</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="mb-0" for=""><b>Customer Remark</b> </label>
                                                                <p class="m-0">${new_customer_remark}</p>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="mb-0" for=""><b>Status</b> </label>
                                                                <p>
                                                                    <span class="badge ${customer.status === '1' ? 'bg-green' : customer.status === '2' ? 'bg-yellow' : 'bg-red'}">
                                                                    ${customer.status === '1' ? 'Active' : customer.status === '2' ? 'Inactive' : 'Blocked'}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        </div>                                                  
                                                    </div>`).show();

                        $('#commercial-card').hide();
                    } 
                    else if (type === 'commercial_customer_type') 
                    {
                        $('#commercial-card').html(`<div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-3 mb-3">
                                                                <label class="mb-0" for=""> <b>Customer Name</b></label>
                                                                <p class="m-0">${customer.customer_name}</p>
                                                            </div>
                                                            <div class="col-md-3 mb-3">
                                                                <label class="mb-0" for=""> <b>UEN</b></label>
                                                                <p class="m-0">${customer.uen || ''}</p>
                                                            </div>
                                                            <div class="col-md-3 mb-3">
                                                                <label class="mb-0" for=""><b>Company Name</b> </label>
                                                                <p class="m-0">${customer.individual_company_name}</p>
                                                            </div>
                                                            <div class="col-md-3 mb-3">
                                                                <label class="mb-0" for=""><b>Language Spoken</b> </label>
                                                                <p class="m-0">${customer.language_name}</p>
                                                            </div>
                                                            <div class="col-md-3 mb-3">
                                                                <label class="mb-0" for=""><b>Person Incharge</b> </label>
                                                                <p class="m-0">${customer.person_incharge_name || ''}</p>
                                                            </div>
                                                            <div class="col-md-3 ">
                                                                <label class="mb-0" for=""> <b>Phone No</b> </label>
                                                                <p class="m-0">${customer.mobile_number}</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="mb-0" for=""><b>Customer Remark</b> </label>
                                                                <p class="m-0">${new_customer_remark}</p>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="mb-0" for=""><b>Status</b> </label>
                                                                <p>
                                                                    <span class="badge ${customer.status === '1' ? 'bg-green' : customer.status === '2' ? 'bg-yellow' : 'bg-red'}">
                                                                    ${customer.status === '1' ? 'Active' : customer.status === '2' ? 'Inactive' : 'Blocked'}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>`).show();

                        $('#residential-card').hide();
                    }

                    // DATA FOR THIRD SLIDE
                    const customerCardBody = $('.customer-card-slide3 .card-body');
                    customerCardBody.empty();

                    if(customer.customer_type == "residential_customer_type")
                    {
                        var customer_name = customer.customer_name;
                    }
                    else if(customer.customer_type == "commercial_customer_type")
                    {
                        var customer_name = customer.individual_company_name;
                    }

                    customerCardBody.append(`<h3 class="card-title mb-1" style="color: #1F3BB3;"><b class="me-2">Customer Details</b></h3>
                                            <p class="m-0"><i class="fa-solid fa-user me-2 pt-1" style="font-size: 14px;"></i>${customer_name}</p>
                                            <p class="m-0"><i class="fa-solid fa-phone me-2 pt-1" style="font-size: 14px;"></i>+65-${customer.mobile_number}</p>
                                            <p class="m-0"><i class="fa-solid fa-envelope me-2 pt-1" style="font-size: 14px;"></i>${customer.email}</p>
                                            <hr class="my-3">
                                            <h3 class="card-title mb-1" style="color: #1F3BB3;">

                                            </div>
                                            <h3 class="card-title mb-1" style="color: #1F3BB3;">
                                                <b>Services Details</b>
                                            </h3>
                                            <div class="amount" id="">

                                        </div>
                                        <hr class="my-3">`);


                    const customerDetailsDiv = $('#customer_details');

                    customerDetailsDiv.empty();

                    customerDetailsDiv.append(`<h3 class="card-title mb-1" style="color: #1F3BB3;">
                                                    <b class="me-2">Customer Details</b>
                                                </h3>
                                                <p class="m-0">
                                                    <i class="fa-solid fa-user me-2 pt-1" style="font-size: 14px;"></i>
                                                    ${customer.customer_name}
                                                </p>
                                                <p class="m-0">
                                                    <i class="fa-solid fa-phone me-2 pt-1" style="font-size: 14px;"></i>
                                                    +65-${customer.mobile_number}
                                                </p>
                                                <p class="m-0">
                                                    <i class="fa-solid fa-map-pin me-2 pt-1" style="font-size: 14px;"></i>
                                                    ${customer.default_address}
                                                </p>
                                                    <hr class="my-3">`);


                    fetchAndDisplayServiceAddresses(customerId);
                    // FOR BILING ADDRESS
                    fetchAndDisplayBillingAddresses(customerId)
                },
            });
        }

        function fetchAndDisplayServiceAddresses(customerId) {
            // alert(customerId)
            $.ajax({
                url: "{{ route('get.service.address') }}",
                type: 'POST',
                data: {
                    customer_id: customerId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(serviceAddresses) {
                    console.log(serviceAddresses);
                    const serviceAddressesDiv = $('.service_address_row');
                    serviceAddressesDiv.empty();

                    serviceAddresses.forEach((address, index) => {
                        const defaultChecked = index === 0 ? 'checked' : '';

                        var default_addr_check = (address.default_address == 1) ? 'checked' : '';

                        serviceAddressesDiv.append(`<div class="col-lg-4 col-md-4 col-sm-12">
                                                        <div class="card-content-wrapper service_address">
                                                            <div class="card-content">
                                                                <input type="hidden" id="address_id" value="${address.id}">
                                                                <label for="radio-card-${index}" class="radio-card">
                                                                    <input type="radio" name="service-address-radio" id="radio-card-${index}" ${defaultChecked}
                                                                        class="check-icon" value="${address.id}"/>

                                                                    <div class="card-content">
                                                                        <h4>Service Address ${index + 1}</h4>
                                                                        <p class="mb-1"> <strong>Contact No:</strong>${address.contact_no}</p>
                                                                        <p class="mb-1"> <strong>Email ID:</strong>${address.email_id}</p>
                                                                        <p class="mb-1"><strong>Address:</strong> ${address.address}</p>
                                                                        <p class="mb-1"><strong>Unit No:</strong> ${address.unit_number}</p>
                                                                        <p class="mb-1"><strong>Zone:</strong> ${address.zone}</p>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="default-address-radio"
                                                                                id="default-address-radio-${index}" ${default_addr_check} />
                                                                            <label class="form-check-label" for="default-address-radio-${index}">
                                                                                Default Address
                                                                            </label>
                                                                        </div>
                                                                        <hr class="my-3">
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>`);

                        $(`#radio-card-${index}`).on('click', function() {
                            if ($(this).prop('checked')) {

                                const selectedAddressId = address.id;

                                // alert(`Selected Address ID: ${selectedAddressId}`);


                                $('#service_id_lead').val(address.id)
                                const serviceAddressInfo = `
                        ${address.address}
                        ${address.unit_number ? '#' + address.unit_number : ''}
                        ${address.zone ? address.zone : ''}
                        ${address.postal_code ? address.postal_code : ''}
                    `;
                                $('#service_address_info').text(serviceAddressInfo);
                            }
                        });
                    });
                },
                error: function(error) {
                    console.log('Error fetching service addresses:', error);
                }
            });
        }

        function fetchAndDisplayBillingAddresses(customerId) {
            $.ajax({
                url: "{{ route('get.billing.address') }}",
                type: 'POST',
                data: {
                    customer_id: customerId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(billingAddresses) {
                    console.log(billingAddresses);
                    const billingAddressesDiv = $('#billing-addresses');
                    billingAddressesDiv.empty();

                    billingAddresses.forEach((address, index) => {
                        const defaultChecked = index === 0 ? 'checked' : '';

                        billingAddressesDiv.append(`
                            <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="card-content-wrapper billing_address">
                                <label for="radio-card-billing-${index}" class="radio-card">
                                <input type="radio" name="billing-address-radio" id="radio-card-billing-${index}" ${defaultChecked} class="check-icon" value="${address.id}"/>
                                <div class="card-content">
                                    <h4>Billing Address ${index + 1}</h4>
                                    <p class="mb-1"> <strong>Email ID:</strong>${address.email}</p>
                                    <p class="mb-1"><strong>Address:</strong>${address.address}</p>
                                    <p class="mb-1"><strong>Unit No:</strong>${address.unit_number}</p>
                                    <p class="mb-1"><strong>Zone:</strong>${address.zone}</p>
                                    <hr class="my-3">
                                </div>
                                </label>
                            </div>
                            </div>
                        `);
                        $(`#radio-card-billing-${index}`).on('click', function() {
                            if ($(this).prop('checked')) {
                                // Retrieve the address ID from the selected div
                                const selectedAddressId = address.id;
                                // alert(`Selected Billing ID: ${selectedAddressId}`);
                                $('#billing_id_lead').val(address.id)
                                const billingAddressInfo = `
                        ${address.address}
                        ${address.unit_number ? '#' + address.unit_number : ''}
                        ${address.postal_code ? address.postal_code : ''}
                    `;
                                $('#billing_address_info').text(billingAddressInfo);
                            }
                        });
                    });
                },
            });
        }

        fetchAndDisplayServiceAddresses($("#customer_id_lead").val());
        fetchAndDisplayBillingAddresses($("#customer_id_lead").val());

        function handlePostalCodeLookup_service(element) {

            var get_postal = element.value;

            $.ajax({
                // url: "https://developers.onemap.sg/commonapi/search?searchVal=" + get_postal +
                //     "&returnGeom=Y&getAddrDetails=Y",

                url: "https://www.onemap.gov.sg/api/common/elastic/search?searchVal="+get_postal+"&returnGeom=Y&getAddrDetails=Y&pageNum=1",

                success: function(JSON) {

                    var address = JSON.results[0].ADDRESS;
                    var searchVal = JSON.results[0].ADDRESS || "";
                    var parts = searchVal.split(" ");
                    var territory = parts[parts.length - 2] || "";

                    $(element).parents('.service_addr_group').find('.address').val(address);
                    $(element).parents('.service_addr_group').find('.territory').val(territory);

                    var postalCode = get_postal.substring(0, 2);
                    $.ajax({

                        url: "{{ route('get.zone.name', ['postalCode' => '__postalCode__']) }}"
                            .replace('__postalCode__', postalCode),
                        method: 'GET',
                        success: function(response) {
                            console.log(response);
                            var zoneName = response.zone_name;
                            $(element).parents('.service_addr_group').find('.zone').val(zoneName);
                        },
                        error: function(error) {
                            console.error('Error fetching zone: ', error);
                        }
                    });
                }
            });
        }

        function handlePostalCodeLookup_bill(element) {

            // var get_postal = $("#postal_code_service").val();
            var get_postal = element.value;

            $.ajax({
                // url: "https://developers.onemap.sg/commonapi/search?searchVal=" + get_postal +
                //     "&returnGeom=Y&getAddrDetails=Y",

                url: "https://www.onemap.gov.sg/api/common/elastic/search?searchVal="+get_postal+"&returnGeom=Y&getAddrDetails=Y&pageNum=1",

                success: function(JSON) {
                    //console.log(JSON);
                    // $(".address").val(JSON.results[0].ADDRESS);
                    $(element).parents('.bill_addr_group').find('.address').val(JSON.results[0].ADDRESS);

                    var postalCode = get_postal.substring(0, 2);
                    $.ajax({

                        url: "{{ route('get.zone.name', ['postalCode' => '__postalCode__']) }}"
                            .replace('__postalCode__', postalCode),
                        method: 'GET',
                        success: function(response) {
                            console.log(response);
                            var zoneName = response.zone_name;
                            $(element).parents('.bill_addr_group').find('.zone').val(zoneName);
                        },
                        error: function(error) {
                            console.error('Error fetching zone: ', error);
                        }
                    });
                }
            });
        }

        function addPriceFeild() {
            // $('#priceFeild').modal("show");
            var field = document.getElementById('addNewFeild');
            var newField = document.createElement("tr");

            // newField.innerHTML = `<tr>
            //                         <td><input type="number" class="form-control" name="service_id" id="service_id"></td>
            //                         <td><input type="text" class="form-control" name="service_name" id="service_name"></td>
            //                         <td><input type="text" class="form-control" name="unit_price" id="unit_price" onchange="updateSubtotal()"></td>
            //                         <td><input type="number" class="form-control" name="qty" id="qty" onchange="updateSubtotal()"></td>
            //                         <td><input type="number" class="form-control" name="sub_total" id="sub_total"></td>
            //                         <td><input type="number" class="form-control" name="discount" id="discountInput" onchange="netTotalCalulation()"></td>
            //                         <td><input type="number" class="form-control" name="net_total" id="net_total"></td>
            //                         <td>
            //                             <div class="row">
            //                                 <a href="#" class="btn btn-danger remove-price-info"><i class="fa fa-times" aria-hidden="true"></i></a>
            //                                 <a href="#" class="btn btn-info save-price-info"><i class="fa fa-check" aria-hidden="true"></i></a>
            //                             </div>
            //                         </td>
            //                     </tr>`;

            newField.innerHTML = `<tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input add_ons_checkbox">
                                        </div>
                                    </td>
                                    <td><input type="text" class="form-control add_ons_product_code" name="add_ons_product_code" id="add_ons_product_code"></td>
                                    <td><input type="text" class="form-control add_ons_service_name" name="service_name" id="service_name"></td>
                                    <td>
                                        <textarea name="service_desc" id="service_desc" class="form-control add_ons_service_desc" cols="30" rows="2"></textarea>
                                    </td>
                                    <td><input type="text" class="form-control add_ons_unit_price" name="unit_price" id="unit_price" onchange="updateSubtotal(this)"></td>
                                    <td><input type="number" class="form-control add_ons_qty" name="qty" id="qty" onchange="updateSubtotal(this)"></td>
                                    <td><input type="number" class="form-control add_ons_sub_total" name="sub_total" id="sub_total"></td>
                                    <td><input type="number" class="form-control add_ons_discount" name="discount" id="discountInput" min="0" step="0.01" onchange="netTotalCalulation(this)"></td>
                                    <td><input type="number" class="form-control add_ons_net_total" name="net_total" id="net_total"></td>
                                    <td>
                                        <div class="row">
                                            <a href="#" class="btn btn-danger remove-price-info"><i class="fa fa-times" aria-hidden="true"></i></a>
                                        </div>
                                    </td>
                                </tr>`;



            field.appendChild(newField);
        }

        function quantityUpdate(serviceId) {
            var quantity = $('#quantityInput' + serviceId).val();
            var unitPrice = $('#unitPrice' + serviceId).val();
            var discount = $('#discountInput' + serviceId).val() || 0;
            var subtotal = quantity * unitPrice;
            var nettotal = subtotal - (subtotal * (discount/100));

            subtotal = parseFloat(subtotal);
            nettotal = parseFloat(nettotal);

            $('#sub-total-tr' + serviceId).val(subtotal.toFixed(2));
            $('#nettotaltd' + serviceId).val(nettotal.toFixed(2));

            calculate_price_info();
        }

        function updateNettotal(serviceId) {
            var quantity = $('#quantityInput' + serviceId).val();
            var unitPrice = $('#unitPrice' + serviceId).val();
            var discount = $('#discountInput' + serviceId).val() || 0;

            var nettotal = quantity * unitPrice * (1 - discount / 100);

            nettotal = parseFloat(nettotal);

            $('#nettotaltd' + serviceId).val(nettotal.toFixed(2));

            calculate_price_info();
        }

        function netTotalCalulation(el) {
            // var quantity = $('#qty').val();
            // var unitPrice = $('#unit_price').val();
            // var discount = $('#discountInput').val() || 0;

            // var subtotal = quantity * unitPrice * (1 - discount / 100);
            // $('#net_total').val(subtotal);

            var unitPrice = $(el).parents('tr').find('.add_ons_unit_price').val();
            var quantity = $(el).parents('tr').find('.add_ons_qty').val();
            var discount = $(el).parents('tr').find('.add_ons_discount').val() || 0;

            var subtotal = quantity * unitPrice;
            var nettotal = subtotal - (subtotal * (discount / 100));

            nettotal = parseFloat(nettotal);

            $(el).parents('tr').find('.add_ons_net_total').val(nettotal.toFixed(2));

            calculate_price_info();
        }

        function updateSubtotal(el) {
            // console.log(el);

            // var quantity = $('#qty').val();
            // var unitPrice = $('#unit_price').val();

            // var subtotal = quantity * unitPrice;

            // $('#sub_total').val(subtotal);
            // $('#net_total').val(subtotal);

            var unitPrice = $(el).parents('tr').find('.add_ons_unit_price').val();
            var quantity = $(el).parents('tr').find('.add_ons_qty').val();
            var discount = $(el).parents('tr').find('.add_ons_discount').val() || 0;

            var subtotal = quantity * unitPrice;
            var nettotal = subtotal - (subtotal * (discount / 100));

            subtotal = parseFloat(subtotal);
            nettotal = parseFloat(nettotal);

            $(el).parents('tr').find('.add_ons_sub_total').val(subtotal.toFixed(2));
            $(el).parents('tr').find('.add_ons_net_total').val(nettotal.toFixed(2));

            calculate_price_info();
        }

        function get_preview_data() 
        {
            selectedRow = '';
            totalServicePrice = 0;
            discount = 0;
            grandTotal = 0;
            var subtotal = 0;
            var nettotal = 0;
            var prv_total = 0;
            var prv_tax_amt = 0;

            var serialNumber = 0;

            // service start

            var size = $("#priceInfoTable .checkbox-row").get().length;

            if (size > 0) {
                for (var i = 0; i < size; i++) {
                    var check_box = $("#priceInfoTable .checkbox-row").eq(i);
                    
                    if (check_box.is(":checked")) {
                        var checkboxValue = check_box.val();
                        var service_product_code = $('#pi_service_product_code' + checkboxValue).text();
                        var serviceName = $('#serviceName' + checkboxValue).val();
                        var serviceDesc = $('#service_desc' + checkboxValue).val();
                        var unitPrice = parseFloat($('#unitPrice' + checkboxValue).val());
                        var qty = parseFloat($('#quantityInput' + checkboxValue).val());
                        var row_discount = $('#discountInput' + checkboxValue).val();
                        var serviceTotalSession = $('#pi_service_total_session' + checkboxValue).val();

                        if (row_discount == null || row_discount == "") {
                            row_discount = 0;
                        }

                        row_discount = parseFloat(row_discount);

                        var total = parseFloat(unitPrice * qty);
                        var new_disc = parseFloat(total * (row_discount / 100));
                        var new_total = parseFloat(total - new_disc);

                        var new_serviceDesc = "";

                        if(serviceDesc)
                        {
                            var temp_serviceDesc = serviceDesc.split("\n");
                                                    
                            $.each(temp_serviceDesc, function (key, value) { 

                                if(value != "" && value != null)
                                {
                                    new_serviceDesc += value + "<br>";
                                }

                            });                   
                        }

                        selectedRow += `<tr>
                                            <input type="hidden" name="preview_service_id[]" value="${checkboxValue}">
                                            <input type="hidden" name="preview_service_product_code[]" value="${service_product_code}">
                                            <input type="hidden" name="preview_service_name[]" value="${serviceName}">
                                            <input type="hidden" name="preview_service_desc[]" value="${serviceDesc}">
                                            <input type="hidden" name="preview_service_qty[]" value="${qty}">
                                            <input type="hidden" name="preview_service_unitPrice[]" value="${unitPrice}">
                                            <input type="hidden" name="preview_service_discount[]" value="${row_discount}">
                                            <input type="hidden" name="preview_service_total_session[]" value="${serviceTotalSession}">
                                            
                                            <td>${serialNumber + 1}</td>
                                            <td hidden>${service_product_code}</td>
                                            <td>${serviceName}</td>
                                            <td>${new_serviceDesc}</td>
                                            <td>${qty}</td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div>$${unitPrice}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div>$${new_total.toFixed(2)}<div>
                                                </div>
                                            </td>
                                        </tr>`;

                        subtotal += total;
                        discount += new_disc;
                        nettotal += new_total;

                        serialNumber++;
                    }
                }
            }

            // service end

            // add ons service start

            $('#priceInfoTable tbody tr').each(function() {
                // console.log(this);

                var add_ons_checkbox = $(this).find('.add_ons_checkbox');

                if (add_ons_checkbox.is(":checked")) {
                    var add_ons_product_code = $(this).find('.add_ons_product_code').val();
                    var serviceName = $(this).find('.add_ons_service_name').val();
                    var serviceDesc = $(this).find('.add_ons_service_desc').val();
                    var unitPrice = parseFloat($(this).find('.add_ons_unit_price').val());
                    var qty = parseFloat($(this).find('.add_ons_qty').val());
                    var row_discount = $(this).find('.add_ons_discount').val() || 0;

                    // console.log(row_discount);

                    if (row_discount == null || row_discount == "") {
                        row_discount = 0;
                    }

                    row_discount = parseFloat(row_discount);

                    var total = parseFloat(unitPrice * qty);
                    var new_disc = parseFloat(total * (row_discount / 100));
                    var new_total = parseFloat(total - new_disc);

                    var new_serviceDesc = "";

                    if(serviceDesc)
                    {
                        var temp_serviceDesc = serviceDesc.split("\n");
                                                
                        $.each(temp_serviceDesc, function (key, value) { 

                            if(value != "" && value != null)
                            {
                                new_serviceDesc += value + "<br>";
                            }

                        });                   
                    }

                    selectedRow += `<tr>
                                        <input type="hidden" name="preview_add_ons_product_code[]" value="${add_ons_product_code}">
                                        <input type="hidden" name="preview_add_ons_service_name[]" value="${serviceName}">
                                        <input type="hidden" name="preview_add_ons_service_desc[]" value="${serviceDesc}">
                                        <input type="hidden" name="preview_add_ons_service_qty[]" value="${qty}">
                                        <input type="hidden" name="preview_add_ons_service_unitPrice[]" value="${unitPrice}">
                                        <input type="hidden" name="preview_add_ons_service_discount[]" value="${row_discount}">

                                        <td>${serialNumber + 1}</td>
                                        <td hidden>${add_ons_product_code}</td>
                                        <td>${serviceName}</td>
                                        <td>${new_serviceDesc}</td>
                                        <td>${qty}</td>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <div>$${unitPrice}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <div>$${new_total.toFixed(2)}<div>
                                            </div>
                                        </td>
                                    </tr>`;

                    subtotal += total;
                    discount += new_disc;
                    nettotal += new_total;

                    serialNumber++;
                }

            });

            // add ons service end

            // discount start

            subtotal = parseFloat(subtotal);
            nettotal = parseFloat(nettotal);

            var prv_discount_type = $("input[name='discount_type']:checked").val();

            if (prv_discount_type == "percentage") {
                var prv_total_discount = $("#persentage_discount_value").val();

                var prv_total_disc_amt = nettotal * (prv_total_discount / 100);
            }
            else {
                var prv_total_discount = $("#amount_discount_value").val();

                var prv_total_disc_amt = prv_total_discount;
            }

            prv_total_disc_amt = parseFloat(prv_total_disc_amt);

            // discount end

            // tax start

            if($("#add_tax_check").is(":checked"))
            {
                var prv_tax = $("#form_tax").val() || 0;
                var prv_tax_type = "exclusive";
            }
            else
            {
                // var prv_tax = 0;
                var prv_tax = $("#form_tax").val() || 0;
                var prv_tax_type = "inclusive";
            }

            prv_tax = parseFloat(prv_tax);

            // tax end

            prv_total = nettotal - prv_total_disc_amt;

            if(prv_tax_type == "exclusive")
            {
                prv_tax_amt = prv_total * prv_tax / 100;
                grandTotal = prv_total + prv_tax_amt;
            }
            else if(prv_tax_type == "inclusive")
            {
                prv_tax_amt = (prv_total / (100 + prv_tax)) * prv_tax;
                grandTotal = prv_total;
            }

            prv_tax_amt = parseFloat(prv_tax_amt);
            grandTotal = parseFloat(grandTotal);

            $('.quotation-table tbody').append(selectedRow);

            $('#preview_subTotal').text(subtotal.toFixed(2));
            $('#preview_netTotal').text(nettotal.toFixed(2));
            $('#preview_discount').text(prv_total_disc_amt.toFixed(2));
            $('#preview_total').text(prv_total.toFixed(2));
            $('#preview_tax').text(prv_tax);
            $('#preview_tax_amt').text(prv_tax_amt.toFixed(2));
            $('#preview_grandTotal').text(grandTotal.toFixed(2));
        }

        function confirm_btn() {
            let form = $('#quotation_form')[0];
            let data = new FormData(form);
            data.append('_token', '{{ csrf_token() }}');
            $.ajax({
                url: "{{ route('sales-order.store') }}",
                type: "POST",
                data: data,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(response) {

                    if (response.status == "error") {
                        var errorMsg = '';
                        $.each(response.errors, function(field, errors) {
                            $.each(errors, function(index, error) {
                                errorMsg += error + '<br>';
                            });
                        });
                        iziToast.error({
                            message: errorMsg,
                            position: 'topRight'
                        });

                    } else {
                        iziToast.success({
                            message: response.message,
                            position: 'topRight',
                        });

                        // Open new tab for editing the sales order
                        var editUrl = "{{ route('sales-order.edit', ':id') }}".replace(':id', response.sales_order_id);
                        window.open(editUrl, '_blank');

                        location.href = "{{route('salesOrder')}}";
                    }
                },
                error: function(xhr, status, error) {
                    iziToast.error({
                        message: 'An error occurred: ' + error,
                        position: 'topRight'
                    });
                }

            });
        }

        function showCRMModal() {
            $.ajax({
                url: "{{ route('createCustomer.create') }}",
                type: "GET",
                success: function(response) {
                    $('#add-customer').modal('show');
                    $('#add-customer-content').html(response);
                },
                error: function() {
                    console.log('Error occurred while loading the modal content.');
                }
            });
        }

        $(document).ready(function() {

            $('#smartwizard').smartWizard({
                transition: {
                    animation: 'slideHorizontal',
                },
            });

            var holidays;
            var holidays_name;

            $.ajax({
                type: "get",
                url: "{{route('get-hoildays-list')}}",
                async: false,
                success: function (result) {
                    // console.log(result);

                    holidays = result.holidays_list;
                    holidays_name = result.holidays_name;

                    // console.log(holidays);
                },
                error: function (result) {
                    console.log(result);
                }
            });

            $('#calendar').datepicker({

                inline: true,
                firstDay: 1,
                showOtherMonths: true,
                dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                minDate: 0,
                beforeShowDay: function(date) {

                    if(holidays)
                    {
                        for (var i = 0; i < holidays.length; i++) {
                            if (new Date(holidays[i]).toString() == date.toString()) {
                                return [true, 'highlighted-date', holidays_name[i]];
                            }
                        }
                    }
                    
                    return [true, '', ''];

                },
                onSelect: function(dateText, inst) {
                    const selectedDate = new Date(dateText);

                    // Extract the year, month, and day components
                    const selectedYear = selectedDate.getFullYear();
                    const selectedMonth = String(selectedDate.getMonth() + 1).padStart(2, '0');
                    const selectedDay = String(selectedDate.getDate()).padStart(2, '0');

                    // Format the date as "dd/mm/yyyy"
                    const formattedDate = `${selectedDay}/${selectedMonth}/${selectedYear}`;
                    console.log('formattedDate:', formattedDate);
                    // Set the value of the text input
                    $('#date_of_cleaning').val(formattedDate);
                }

            });

            $('#pills-home-tab').on('click', function() {
                showTableData();
            });
            $('#company-select').on('click', function() {
                showTableData();
            });

            $('.productsubshow').hide();

            $('body').on('blur', '.change_service_qty', function() {
                get_price_info_table();
                updateAmountDiv();
            });

            $('body').on('blur', '.change_service_name', function() {
                get_price_info_table();
                updateAmountDiv();
            });

            $('body').on('blur', '.change_service_desc', function() {
                get_price_info_table();
            });

            $('body').on('blur', '.change_service_total_session', function() {
                get_price_info_table();
            });

            $("body").on('change', '#add_tax_check', function() {
                calculate_price_info();
            });

            $('body').on('click', '.remove_price_info_btn', function() {
                $(this).closest('tr').remove();
                calculate_price_info();
            });

            $(document).on('click', '.remove-service-btn', function() {
                // console.log("object1");

                const serviceIdToRemove = $(this).closest('tr').find('td:first-child').text().trim();
                $(this).closest('tr').remove();
                updateAmountDiv();

                $.each(itemsArray, function(i, el) {
                    if (this.id == serviceIdToRemove) {
                        itemsArray.splice(i, 1);
                    }
                });

                // console.log(itemsArray);

                get_price_info_table();
            });

            $("#service_address_form").insertAfter("#step-3 .service_address_row");
            // $("#service_address_form").show();
            $("#billing_address_form").insertAfter("#step-3 .billing_address_row");
            $("#billing_address_form").show();

            $(".add_btn").click(function() {
                $("#service_address_form").toggle();
            });

            $("#rowAdder_add_lead").click(function() {
                newRowAdd =
                    '<div class="row my-3 service_addr_group" id="row">  <div class="col-md-4">' +
                    '<div class="form-group mb-3">' +
                    ' <label for="name">Person Incharge Name</label><input type="text" placeholder="Enter Name" name="person_incharge_name[]" class="form-control"/></div></div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"> <label for="">Contact No</label><input type="text" placeholder="Enter Number" name="contact_no[]" class="form-control"> </div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="">Email Id</label><input type="text" placeholder="Enter Email" name="email_id[]" class="form-control"></div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="">Postal Code</label><input type="text" placeholder="Enter Code" name="postal_code[]" onchange="handlePostalCodeLookup_service(this)" class="form-control postal_code"></div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="">Zone</label><input type="text" placeholder="Enter Zone" name="zone[]" class="form-control zone"></div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"><label for="">Address</label><input type="text" placeholder="Enter Address" name="address[]" class="form-control address"> </div> </div>' +
                    '<div class="col-md-4"><div class="form-group mb-3"> <label for="">Unit No</label><input type="text" placeholder="Enter Unit No." name="unit_number[]" class="form-control"> </div> </div>' +
                    '<input type="hidden" name="territory[]" class="form-control territory">' +
                    '<div class="col-md-1" style="display: flex; align-items: center;">  <button type="button" class="btn btn-danger" id="DeleteRow">-</button></div></div>';

                $('#newinput_add_lead').append(newRowAdd);
            });

            $("body").on("click", "#DeleteRow", function() {
                $(this).parents("#row").remove();
            });

            $('#billing_address .billing-add-row').click(function() {
                var template =
                    '<tr class="bill_addr_group"><td><input class="form-control" type="text" placeholder="Person Incharge name" name="b_person_incharge_name[]" style="width: 200px;"/></td><td><input class="form-control" type="text" placeholder="Contact no" name="b_contact_no[]" style="width: 200px;"/></td><td><input class="form-control" type="email" placeholder="Email" name="b_email[]" style="width: 200px;"/></td><td><input class="form-control postal_code" type="text" placeholder="Enter Code" name="b_postal_code[]" onchange="handlePostalCodeLookup_bill(this)" style="width: 200px;"/></td><td><input class="form-control address" type="text" placeholder="Address" name="b_address[]" style="width: 200px;"/><input type="hidden" name="b_zone[]" class="zone"></td><td><input class="form-control" type="text" placeholder="Enter Unit No" name="b_unit_number[]" style="width: 200px;"/></td><td><button type="button" class="btn btn-danger delete-row">-</button></td></tr>';

                    $('#billing_address tbody').append(template);
            });
            $('#billing_address').on('click', '.delete-row', function() {
                $(this).parent().parent().remove();
            });

            // service address store

            $("#service_address_btn").click(function(e) {
                e.preventDefault();
                let form = $('#service_address_form')[0];
                let data = new FormData(form);

                data.append('_token', '{{ csrf_token() }}');
                $.ajax({
                    url: "{{ route('service.address.store') }}",
                    type: "POST",
                    data: data,
                    dataType: "JSON",
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.errors) {
                            var errorMsg = '';
                            $.each(response.errors, function(field, errors) {
                                $.each(errors, function(index, error) {
                                    errorMsg += error + '<br>';
                                });
                            });
                            iziToast.error({
                                message: errorMsg,
                                position: 'topRight'
                            });
                            $(".tab-pane").removeClass("serviceAddressForm");
                            $("#step-3").addClass("serviceAddressForm");

                            $('a[href="#step-3"]').trigger('click');
                        } else {
                            iziToast.success({
                                message: response.success,
                                position: 'topRight',
                            });
                            $(".tab-pane").removeClass("serviceAddressForm");
                            $("#step-3").addClass("serviceAddressForm");
                            $('a[href="#step-3"]').trigger('click');
                            $('#service_address_form')[0].reset();
                            $('#service_address_form').hide();
                            const customerId = $('#customer_id_service').val();

                            fetchAndDisplayServiceAddresses(customerId);
                        }
                    },
                    error: function(xhr, status, error) {
                        iziToast.error({
                            message: 'An error occurred: ' + error,
                            position: 'topRight'
                        });
                    }

                });
            });

            // BILLING ADDRESS STORE

            $("#billing_address_btn").click(function(e) {
                e.preventDefault();
                let form = $('#billing_address_form')[0];
                let data = new FormData(form);
                data.append('_token', '{{ csrf_token() }}');
                $.ajax({
                    url: "{{ route('billing.address.store') }}",
                    type: "POST",
                    data: data,
                    dataType: "JSON",
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.errors) {
                            var errorMsg = '';
                            $.each(response.errors, function(field, errors) {
                                $.each(errors, function(index, error) {
                                    errorMsg += error + '<br>';
                                });
                            });
                            iziToast.error({
                                message: errorMsg,
                                position: 'topRight'
                            });
                            $(".tab-pane").removeClass("billing-addresses");
                            $("#step-3").addClass("billing-addresses");

                            $('a[href="#step-3"]').trigger('click');
                        } else {
                            iziToast.success({
                                message: response.success,
                                position: 'topRight',
                            });
                            $(".tab-pane").removeClass("billing-addresses");
                            $("#step-3").addClass("billing-addresses");
                            $('a[href="#step-3"]').trigger('click');
                            $('#billing_address_form')[0].reset();
                            // $('#billing_address_form').hide();
                            const customerId = $('#customer_id_billing').val();

                            fetchAndDisplayBillingAddresses(customerId);
                        }
                    },
                    error: function(xhr, status, error) {
                        iziToast.error({
                            message: 'An error occurred: ' + error,
                            position: 'topRight'
                        });
                    }

                });
            });

            $('#persentage_discount').change(function() {
                $("#persent_discount_feild").show();
                $("#amount_discount_feild").hide();
            });
            $('#amount_discount').change(function() {
                $("#amount_discount_feild").show();
                $("#persent_discount_feild").hide();
            });


            $('body').on('blur', '.disc_field', function() {
                calculate_price_info();
            });

            $('body').on('change', '.discount_type', function() {
                calculate_price_info();
            });

            $(document).on('click', '.remove-price-info', function() {
                const row = $(this).closest('tr');
                row.remove();

                calculate_price_info();
            });

            $('#smartwizard').on('leaveStep', function(e, anchorObject, stepNumber, stepDirection) {
                // console.log("stepDirection", stepDirection);
                // console.log("company_id",company_id);

                var companyId = $('#company-select').val();
                var customer_id = $('#customer_id_lead').val();
                var remarks = $("#quotation_remarks").val();

                if (stepDirection == 5) {
                    $.ajax({
                        url: '{{ route('get.sales-order.preview') }}',
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            'company_id': companyId,
                            'customer_id': customer_id,
                            'service_address_id': $('input[name="service-address-radio"]:checked').val(),
                        },
                        success: function(response) {
                            // console.log(response);
                            $('#step-4').html(response);
                            $("#preview_remarks").html(remarks);

                            get_preview_data();

                            //console.log(subtotal);
                        },
                        error: function(response) {
                            //console.log(response);
                        }
                    })
                }
            });

        });

    </script>

@endsection