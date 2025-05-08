<div class="modal-header">
    <h5 class="modal-title">Update New Lead</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form class="row text-left" id="lead_edit_form" method="POST" enctype="multipart/form-data">
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

        <input type="hidden" name="unit_price" value="">
        <input type="hidden" name="quantities" class="quantity-input-j" placeholder="quantity">
        <input type="hidden" name="discounts" class="discount-input-j" placeholder="discount">

        <div id="smartwizard" style="border: none; height: auto;">
            <ul class="nav">
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
                                    <a href="#residential-view" id="residential-view-table" class="nav-link active"
                                        data-bs-toggle="tab" aria-selected="true" role="tab">Residential</a>
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
                                                placeholder="Search…" onkeypress="Search('1')" onkeydown="Search('1')" id="residential">
                                            <span class="input-icon-addon">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                    <path d="M21 21l-6 -6"></path>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-3" id="residential_Search">
                                        <div class="card">
                                            @foreach ($customersResidential as $item)
                                                <a type="button" style="display: block" class="mb-3"
                                                    onclick="displayCustomerDetails({{ $item->id }}, 'residential_customer_type')">
                                                    <div class="card card-active">
                                                        <div class="card">
                                                            <div class="ribbon bg-yellow">Residential</div>
                                                            <div class="card-body d-flex justify-content-between">
                                                                <div class="my-auto">
                                                                    <label class="mb-0 text-black fw-bold"
                                                                        style="font-size: 14px">{{ $item->customer_name }}</label>
                                                                    <p class="m-0">Tel - {{ $item->mobile_number }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="mb-3" id="residential_customer">
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="commercial-view" role="tabpanel">
                                    <div class="mb-3">
                                        <label class="form-label">Search By</label>
                                        <div class="input-icon mb-3">
                                            <input type="text" value="" class="form-control"
                                                placeholder="Search…." onkeypress="Search('0')"  onkeydown="Search('0')" id="commercial">
                                            <span class="input-icon-addon">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                    <path d="M21 21l-6 -6"></path>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-3" id="commercialList" style="display: block">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="d-flex" style="justify-content: space-between; align-items: center;">
                                <h5 class="modal-title">Customer Details</h5>
                                <a href="#" class="btn btn-info" data-bs-toggle="modal"
                                    data-bs-target="#add-customer" onclick="showCRMModal()">
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
                            <div class="card mt-3 customer-card residential-card" id="residential-card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="mb-0" for=""> <b>Customer Name</b></label>
                                            <p class="m-0"> {{$customer->customer_name}} </p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="mb-0" for=""><b>Mobile Number</b></label>
                                            <p class="m-0"> {{$customer->mobile_number}} </p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="mb-0" for=""> <b>Email</b></label>
                                            <p class="m-0"> {{$customer->email}} </p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="mb-0" for=""><b>Language Spoken</b> </label>
                                            <p class="m-0"> {{$customer->language_name}}</p>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label class="mb-0" for=""> <b>Select Type</b></label>
                                            <p class="m-0">{{$customer->customer_type}}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="mb-0" for=""><b>Customer Remark</b> </label>
                                            <p class="m-0">
                                                {{$customer->customer_remark}}
                                            </p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="mb-0" for=""><b>Status</b> </label>
                                            @if($customer->status == 1)
                                                <p><span class="badge bg-green"> Active</span></p>
                                            @elseif($customer->status == 2)
                                                <p><span class="badge bg-red"> InActive</span></p>
                                            @else
                                                <p><span class="badge bg-grey"> Blocked</span></p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <table
                                    class="table card-table table-vcenter text-center text-nowrap table-transparent">
                                    <thead>
                                        <tr>

                                            <th>Past Transaction</th>
                                            <th>Packages</th>


                                        </tr>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                    </thead>

                                </table>
                            </div>

                            <div class="card mt-3 customer-card commercial-card" id="commercial-card"
                                style="display: none;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="mb-0" for=""> <b>Customer Name</b></label>
                                            <p class="m-0"></p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="mb-0" for=""> <b>UEN</b></label>
                                            <p class="m-0"></p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="mb-0" for=""> <b>Group Company Name</b> </label>
                                            <p class="m-0"></p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="mb-0" for=""><b>Individual Company Name</b>
                                            </label>
                                            <p class="m-0"></p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="mb-0" for=""><b>Language Spoken</b> </label>
                                            <p class="m-0"></p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="mb-0" for=""><b>Person Incharge</b> </label>
                                            <p class="m-0"></p>
                                        </div>
                                        <div class="col-md-3 ">
                                            <label class="mb-0" for=""> <b>Phone No</b> </label>
                                            <p class="m-0"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="mb-0" for=""><b>Customer Remark</b> </label>
                                            <p class="m-0"></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="mb-0" for=""><b>Status</b> </label>
                                            <p></p>
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
                                                <select type="text" class="form-select" id="company-select">
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
                                                            <div class="table-responsive">
                                                                <table
                                                                    class="table card-table table-vcenter text-center text-nowrap table-transparent"
                                                                    id="service-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Service Id</th>
                                                                            <th>Service Name</th>
                                                                            <th>Price</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
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
                                                    <th>SERVICE ID</th>
                                                    <th>Service</th>
                                                    <th>SERVICE DESCRIPTION</th>
                                                    <th>Unit Price</th>
                                                    <th>Qty</th>
                                                    {{-- <th>Discount (%)</th> --}}
                                                    {{-- <th>Gross Amt ($)</th> --}}
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
                                                class="me-2">Customer
                                                Details added</b>
                                        </h3>
                                        <p class="card-p d-flex align-items-center mb-2 ">
                                            <i class="fa-solid fa-phone me-2" style="font-size: 14px;"></i>+91
                                            9758697820
                                        </p>
                                        <p class="card-p  d-flex align-items-center mb-2">
                                            <i class="fa-solid fa-envelope me-2"
                                                style="font-size: 14px;"></i>abc@pvtltd.com
                                        </p>
                                        <p class="card-p d-flex mb-2">
                                            <i class="fa-solid fa-map-pin me-2 pt-1" style="font-size: 14px;"></i>103
                                            Rasadhi
                                            Appartment Wadaj Ahmedabad 380004.
                                        </p>
                                        <hr class="my-3">
                                        <h3 class="card-title mb-1" style="color: #1F3BB3;">
                                            <b>Service Details</b>
                                        </h3>
                                        <div class="amount">
                                            <p class="m-0 card-p">Floor Cleaning(5)</p>
                                            <p class="m-0 card-p">Home Cleaning(2)</p>
                                        </div>
                                        <hr class="my-3">
                                        <div class="driver mt-2">
                                            <h3 class="card-title mb-1" style="color: #1F3BB3;">
                                                <b>Amount Details</b>
                                            </h3>
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <p class="m-0"> Total:</p>
                                                </div>
                                                <div class="col-md-5">
                                                    <p class="m-0">$200.00</p>
                                                </div>
                                            </div>
                                        </div>
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
                                    <label for="message-text" id="time_of_cleaning" name="time_of_cleaning"
                                        class="col-form-label">Time of Cleaning</label>
                                    <input type="time" class="form-control" placeholder="Time of Cleaning">
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
                                                class="me-2">Customer
                                                Details added</b>
                                        </h3>
                                        <p class="card-p d-flex align-items-center mb-2 ">
                                            <i class="fa-solid fa-phone me-2" style="font-size: 14px;"></i>+91
                                            9758697820
                                        </p>
                                        <p class="card-p  d-flex align-items-center mb-2">
                                            <i class="fa-solid fa-envelope me-2"
                                                style="font-size: 14px;"></i>abc@pvtltd.com
                                        </p>
                                        <p class="card-p d-flex mb-2">
                                            <i class="fa-solid fa-map-pin me-2 pt-1" style="font-size: 14px;"></i>103
                                            Rasadhi
                                            Appartment Wadaj Ahmedabad 380004.
                                        </p>
                                        <hr class="my-3">
                                        <h3 class="card-title mb-1" style="color: #1F3BB3;">
                                            <b>Service Details</b>
                                        </h3>
                                        <div class="amount">
                                            <p class="m-0 card-p">Floor Cleaning(5)</p>
                                            <p class="m-0 card-p">Home Cleaning(2)</p>
                                        </div>
                                        <hr class="my-3">
                                        <div class="driver mt-2">
                                            <h3 class="card-title mb-1" style="color: #1F3BB3;">
                                                <b>Amount Details</b>
                                            </h3>
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <p class="m-0"> Total:</p>
                                                </div>
                                                <div class="col-md-5">
                                                    <p class="m-0">$200.00</p>
                                                </div>
                                            </div>
                                        </div>
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
                                                <div class="col-lg-4 col-md-4 col-sm-12">
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
                                                </div>
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
                                                <div class="col-md-12">
                                                    <div class="my-3">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input">
                                                            Same as Service Address
                                                            <i class="input-helper"></i>
                                                        </label>
                                                    </div>
                                                </div>
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
                                        <div class="tab-pane" id="tab-three" role="tabpanel">
                                            <div class="row mt-3">
                                                <div class="form-group col-lg-6 col-md-6 col-sm-12 text-start">
                                                    <label for="message-text" class="col-form-label">Deposite
                                                        Type</label>
                                                    <select class="form-control">
                                                        <option>Select Option</option>
                                                        <option>$50</option>
                                                        <option>waive</option>
                                                        <option>Don’t need</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6 col-md-6 col-sm-12 text-start">
                                                    <label for="message-text" class="col-form-label">Date Of
                                                        Cleaning</label>
                                                    <input type="date" class="form-control"
                                                        placeholder="dd/mm/yyyy">
                                                </div>
                                                <div class="form-group col-lg-6 col-md-6 col-sm-12 text-start">
                                                    <label for="message-text" class="col-form-label">Time of
                                                        Cleaning</label>
                                                    <input type="time" class="form-control"
                                                        placeholder="Time of Cleaning">
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
                                                            <input class="form-check-input" type="radio"
                                                                name="exampleRadios" id="persentage_discount"
                                                                value="option1" checked>
                                                            <label class="form-check-label" for="persentage_discount">
                                                                Persentage Discount
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="exampleRadios" id="amount_discount"
                                                                value="option2">
                                                            <label class="form-check-label" for="amount_discount">
                                                                Amount Discount
                                                            </label>
                                                        </div><br>
                                                        <div class="" style=""
                                                            id="persent_discount_feild">
                                                            <label for="persentage_discount">Persentage
                                                                Discount(%)</label><br><br>
                                                            <input type="text" name="persentage_discount"
                                                                class="form-control">
                                                        </div>
                                                        <div class="" style="display:none;"
                                                            id="amount_discount_feild">
                                                            <label for="amount_discount">Amount
                                                                Discount</label><br><br>
                                                            <input type="number" name="amount_discount"
                                                                class="form-control">
                                                        </div>
                                                    </form>
                                                </div>
                                                {{-- </div> --}}
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-3 accordion" id="accordionExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <a href="#collapseOne" class="btn btn-light" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                        aria-expanded="false" aria-controls="collapseOne">
                                                        Urgent Delivery
                                                    </a>
                                                </h2>
                                                <div id="collapseOne" class="accordion-collapse collapse "
                                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <form action="">
                                                            <input type="checkbox">
                                                            <label for="checkbox">Urgent Delevery</label><br><br>

                                                            <label for="">Urgent Delivery Charge($)</label><br>
                                                            <input type="text" name="" class="form-control">

                                                            <label for="">Delivery Date</label><br>
                                                            <input type="date" name="" class="form-control">
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                    {{-- <div class="mb-3" style="float:right">
                                            <label>Amounts are: </label><input type="text" value="Non-Gst-0%">
                                        </div><br> --}}

                                    <div class="mb-3">
                                        <button type="button" class="btn btn-dark" style="float: right;"
                                            onclick="addPriceFeild()">Add</button><br>
                                    </div>
                                    <table class="table" id="priceInfoTable">
                                        <thead>
                                            <tr>
                                                <th scope="col">sl.no.</th>
                                                <th scope="col">service/Products</th>
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
                            </div><br>
                            <div class="mb-3 ml-4 calculation-price" style="float:right; margin-left:20px;">
                                {{-- <lable>Sub Total :  $$0.00</label><br>
                                <lable>Net Total : $$0.00</label><br>
                                <lable>Persentage Discount(%) : 13%</label><br>
                                <lable>Grand Total : </label><br> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">
                </div>
            </div>
            <!-- Include optional progressbar HTML -->
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                    aria-valuemax="100"></div>
            </div>
    </form>
</div>
<script>

$(document).ready(function() {
        // var selected_date = '';
        $('#calendar').datepicker({

            inline: true,
            firstDay: 1,
            showOtherMonths: true,
            dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            minDate: 0,
            beforeShowDay: function(date) {
                var highlightDate = "{{ $dates }}";
                const decodedJsonString = highlightDate.replace(/&quot;/g, '"');
                const dataObj = JSON.parse(decodedJsonString);
                var jsonObject = Object.values(dataObj);

                // var dateString = date.toDateString();
                // for (var i = 0; i < jsonObject.length; i++) {
                //    var new_date = jsonObject[i].date;
                //    if (dateString == jsonObject[i].date) {
                //          return [true, 'highlighted-date', jsonObject[i].service];
                //    }
                // }

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
    });
    $('#smartwizard').smartWizard({
        transition: {
            animation: 'slideHorizontal',

        }
    });

    $('#smartwizard').on('leaveStep', function(e, anchorObject, stepNumber, stepDirection) {
        // console.log("stepDirection", stepDirection);
        // console.log("company_id",company_id);
        var companyId = $('#company-select').val()
        var customer_id = $('#customer_id_lead').val()

        if (stepDirection == 5) {
            $.ajax({
                url: '{{ route('get.lead.preview') }}',
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    'company_id': companyId,
                    'customer_id': customer_id
                },
                success: function(response) {
                    $('#step-4').html(response);
                    $('.quotation-table tbody').append(selectedRow);
                    $('.subTotal').append(totalServicePrice);
                    $('.grandTotal').append(subtotal);
                    $('#discount').append(discount);
                },
            })
        }
    });

    var company_id = $('#company-select').val();
    $(document).ready(function() {
        $('#step-1').on('click', function() {
            service(type);
        });

        $('#pills-home-tab').on('click', function() {
            showTableData();
        });

        $('.productsubshow').hide();
    });

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
                            data-service-quantity="${service.quantity}" onclick="addService(this)">
                        <td>${service.id}</td>
                        <td>
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

    function confirmBtn() {
        // e.preventDefault();
        let form = $('#lead_edit_form')[0];
        let data = new FormData(form);
        data.append('_token', '{{ csrf_token() }}');
        $.ajax({
            url: "{{ route('lead.update') }}",
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

                } else {
                    iziToast.success({
                        message: response.success,
                        position: 'topRight',
                    });

                    $('#add-lead').modal('hide');
                    window.location.reload();
                }
            },
            error: function(xhr, status, error) {
                iziToast.error({
                    message: 'An error occurred: ' + error,
                    position: 'topRight'
                });
            }

        });
        // });
    }

    function addService(thiss) {
        // console.log(thiss);
        const serviceId = $(thiss).data('service-id');
        const serviceName = $(thiss).data('service-name');
        const serviceNetTotal = $(thiss).data('service-net-total');
        const serviceDescription = $(thiss).data('service-description');
        const servicePrice = $(thiss).data('service-price');
        const quantityInput = $(thiss).closest('tr').find('.quantity-input');
        const quantity = parseInt(quantityInput.val(), 10) || 1;


        //   if ($('#selected-services-table tbody tr[data-service-id="' + serviceId + '"]').length > 0) {
        //     alert('This service is already added.');
        //     return;
        //   }
        // console.log("servicePrice:", servicePrice);
        // console.log("quantity:", quantity);

        const subTotal = (quantity * servicePrice);
        const netTotal = subTotal;
        

        var row = `

            <tr>
            <input type="text" class="form-control price" value="${serviceName}" name="service_name[]">
            <td id="servicesId">${serviceId}</td>
            <td>${serviceName}</td>
            <td class="description-cell" data-full-text="${serviceDescription}">${serviceDescription}</td>
        
            
            <td><input type="number" class="form-control price" value="${servicePrice}"></td>
            <td class="p-0" id="quantitys"><input type="number" class="form-control quantity-input qty${serviceId}" placeholder="quantity" value="${quantity ? quantity : 1}"></td>

            <td>
                <button class="btn btn-danger ripple remove-service-btn" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cross m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="white" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="currentColor" d="M6 6l12 12" />
                    <path stroke="currentColor" d="M6 18L18 6" />
                    </svg>

                </button>
            </td>
            </tr>
            `;

        const priceRow = `
            <tr class='checkedRow'>
            <input type="hidden" class="form-control price" value="${serviceId}" name="selected_service_id" id="selected_service_id">
            <td>
                <div class="form-check">
                <input class="form-check-input checkbox-row" type="checkbox" value="${serviceId}" id="flexCheckChecked">
                </div>
            </td>
            <td id="serviceName${serviceId}">${serviceName}</td>
            <td><input type="number" class="form-control price" id="unitPrice${serviceId}" value="${servicePrice}" onchange="quantityUpdate(${serviceId})"></td>
            <td><input type="number" class="form-control quantity-input qty${serviceId}" id="quantityInput${serviceId}" value="${quantity ? quantity : 1}" onchange="quantityUpdate(${serviceId})"></td>
            <td><input type="number" class="form-control sub-total-input" id="sub-total-tr${serviceId}" value="${subTotal}"></td>
            <td><input type="number" class="form-control discount-input" id="discountInput${serviceId}" value="" onchange="updateNettotal(${serviceId})"></td>
            <td><input type="number" class="form-control net-total-input" id="nettotaltd${serviceId}" placeholder="" value="${netTotal}"></td>

            <td>
                <button class="btn btn-danger ripple remove-service-btn" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cross m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="white" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="currentColor" d="M6 6l12 12" />
                    <path stroke="currentColor" d="M6 18L18 6" />
                    </svg>

                </button>
            </td>
            </tr>
            `;
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
        addItem(serviceId,row,priceRow);
        updateAmountDiv();

        $('.service-id').val(serviceId);
        const serviceIdsInput = $('input[name="service_ids"]');
        const serviceNamesInput = $('input[name="service_names"]');
        const serviceDescriptionsInput = $('input[name="service_descriptions"]');

        const unitPriceInput = $('input[name="unit_price"]');
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

    var selectedRow = '';
    var grandTotal = '';
    var discount = '';
    $(document).ready(function() {

        $(document).on('click', '.checkbox-row', function () {
            if ($(this).is(':checked')) {
                var checkboxValue = $(this).val();
                 var serviceName = $('#serviceName'+checkboxValue).text();
                 var unitPrice = $('#unitPrice'+checkboxValue).val();
                 var qty = $('#quantityInput'+checkboxValue).val();
                 var total = unitPrice * qty ;
                 selectedRow += `<tr>
                            <td>${serviceName}</td>
                            <td>${qty}</td>
                            <td>
                                <div class="d-flex justify-content-between">

                                    <div>$${unitPrice}</div>

                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-between">
                                    <div>${total}<div>
                                </div>
                            </td>
                        </tr>`;

                totalServicePrice += total;
                discount = $('#discountInput' + checkboxValue).val() || 0; 
                // console.log(discount);
                grandTotal += total * (1 - discount / 100);
                
            }
        });
    });

    var itemsArray = [];

    function addItem(id,row,priceRow) {
        // Check if the item with the given id already exists in the array
        const existingItem = itemsArray.find(item => item.id === id);

        if (existingItem) {
            existingItem.qty += 1;
            $('.qty'+existingItem.id).val(existingItem.qty);

            var quantity = $('#quantityInput'+id).val();
            var unitPrice = $('#unitPrice'+id).val()
            var subtotal = quantity * unitPrice;
            $('#sub-total-tr'+id).val(subtotal);
            $('#nettotaltd'+id).val(subtotal);
        } else {
            // If the item doesn't exist, add a new object to the array
            itemsArray.push({ id: id, qty: 1 });
            $('#selected-services-table tbody').append(row);
            $('#priceInfoTable tbody').append(priceRow);
        }
    }

    function updateAmountDiv() {
        let amountHTML = '';
        let amountDetailHTML = '';
        let totalAmount = '';
        $('#selected-services-table tbody tr').each(function() {
            // console.log(this);
            const serviceName = $(this).find('td:eq(1)').text();
            const quantity = parseInt($(this).find('.quantity-input').val(), 10);
            // const servicePrice = parseInt($(this).find('.price').val());
            const servicePrice = $(this).find('.price').val();
             totalAmount  += servicePrice *  quantity;    
            //  console.log(totalAmount);
             amountHTML += `<p class="m-0 card-p">${serviceName}(${quantity})</p>`;
             amountDetailHTML += `<div class="col-md-7">
                                     <p class="m-0"> Total:</p>
                                 </div>
                                 <div class="col-md-5">
                                     <p class="m-0">$${totalAmount}</p>
                                 </div>`;
            });

        $('.amount').html(amountHTML);
        $('.amount_details').html(amountDetailHTML);
    }

    function Search(type) {
        var search;
        var searchBox;

        if (type == '1') {
            searchBox = $('#residential_Search');
            console.log(searchBox);
            search = $('#residential').val();
            // console.log(search);

        } else {
            searchBox = $('#commercialList');
            search = $('#commercial').val();

        }
        if (!search.trim()) {
            searchBox.empty().hide();
            return;
        }

        $.ajax({
            url: '{{ route('lead.customer.search') }}',
            type: 'POST',
            data: {
                type: type,
                search: search,
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                $('#residential_Search').empty().hide();

                $('#commercialList').empty().hide();

                response.forEach(function(item) {
                    if (item.customer_type === 'residential_customer_type') {
                        // Append residential search result

                        var residentialList = `
                            <a type="button" style="display: block;" class="mb-3" onclick="displayCustomerDetails(${item.id}, 'residential_customer_type')">
                            <div class="card card-active">
                            
                            <div class="card">
                                <div class="ribbon bg-yellow">Residential</div>
                            <div class="card-body d-flex justify-content-between">
                            <div class="my-auto">
                                <label class="mb-0 text-black fw-bold" style="font-size: 14px">${item.customer_name}</label>
                                <p class="m-0">Tel - +${item.mobile_number}</p>
                            </div>
                            </div>
                            </div>
                            </div>
                            </a>

                            `;
                        searchBox.append(residentialList);
                    } else {
                        // Append commercial search result
                        var commercialList = `
                                    <a type="button" style="display: block;" class="mb-3" onclick="displayCustomerDetails(${item.id}, 'commercial_customer_type')">
                                    <div class="card card-active">

                                    <div class="card">
                                        <div class="ribbon bg-red">Commercial</div>

                                    <div class="card-body d-flex justify-content-between">
                                    <div class="my-auto">
                                        <label class="mb-0 text-black fw-bold" style="font-size: 14px">${item.customer_name}</label>
                                        <p class="m-0">Tel - +${item.mobile_number}</p>
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
    $(document).on('click', '#residential_Search a, #commercialList a', function() {
        // Hide the search results container when a customer is selected
        $('#residential_Search').empty().hide();
        $('#commercialList').empty().hide();
    });

    function displayCustomerDetails(customerId, type) {

        $.ajax({
            url: '{{ route('lead.customer.details') }}',
            type: 'POST',
            data: {
                id: customerId,
                _token: '{{ csrf_token() }}'
            },
            success: function(customer) {
                // alert(customerId)
                $('#customer_id_service').val(customerId);
                $('#customer_id_billing').val(customerId);
                $('#customer_id_lead').val(customerId);

                if (type === 'residential_customer_type') {
                    $('#residential-card').html(`
           <div class="card-body">
             <div class="row">
               <div class="col-md-3">
                 <label class="mb-0" for=""> <b>Customer Name</b> </label>
                 <p class="m-0">${customer.customer_name}</p>
               </div>
               <div class="col-md-3">
                 <label class="mb-0" for=""><b>Mobile Number</b> </label>
                 <p class="m-0">+91-${customer.mobile_number}</p>
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
                 <p class="m-0">${customer.customer_remark}</p>
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
              <table class="table card-table table-vcenter text-center text-nowrap table-transparent">
                        <thead>
                           <tr>
                           
                           <th>Past Transaction</th>
                           <th>Packages</th>
                           
                        </tr>
                        </thead>
                        
                     </table>
             </div>

           `).show();
                    $('#commercial-card').hide();
                } else if (type === 'commercial_customer_type') {
                    $('#commercial-card').html(`
             <div class="card-body">
             <div class="row">
              <div class="col-md-3 mb-3">
                 <label class="mb-0" for=""> <b>Customer Name</b></label>
                 <p class="m-0">${customer.customer_name}</p>
               </div>
               <div class="col-md-3 mb-3">
                 <label class="mb-0" for=""> <b>UEN</b></label>
                 <p class="m-0">${customer.uen}</p>
               </div>
               <div class="col-md-3 mb-3">
                 <label class="mb-0" for=""> <b>Group Company Name</b> </label>
                 <p class="m-0">${customer.group_company_name}</p>
               </div>
               <div class="col-md-3 mb-3">
                 <label class="mb-0" for=""><b>Individual Company Name</b> </label>
                 <p class="m-0">${customer.individual_company_name}</p>
               </div>
               <div class="col-md-3 mb-3">
                 <label class="mb-0" for=""><b>Language Spoken</b> </label>
                 <p class="m-0">${customer.language_name}</p>
               </div>
               <div class="col-md-3 mb-3">
                 <label class="mb-0" for=""><b>Person Incharge</b> </label>
                 <p class="m-0">${customer.person_incharge}</p>
               </div>
               <div class="col-md-3 ">
                 <label class="mb-0" for=""> <b>Phone No</b> </label>
                 <p class="m-0">${customer.mobile_number}</p>
               </div>
               <div class="col-md-6">
                 <label class="mb-0" for=""><b>Customer Remark</b> </label>
                 <p class="m-0">${customer.customer_remark}</p>
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
           </div>
           `).show();
                    $('#residential-card').hide();
                }
                // DATA FOR THIRD SLIDE
                const customerCardBody = $('.customer-card-slide3 .card-body');
                customerCardBody.empty();

                customerCardBody.append(`
   
      
        <h3 class="card-title mb-1" style="color: #1F3BB3;"><b class="me-2">Customer Details</b></h3>
        <p class="m-0"><i class="fa-solid fa-user me-2 pt-1" style="font-size: 14px;"></i>${customer.customer_name}</p>
        <p class="m-0"><i class="fa-solid fa-phone me-2 pt-1" style="font-size: 14px;"></i>+91-${customer.mobile_number}</p>
        <p class="m-0"><i class="fa-solid fa-map-pin me-2 pt-1" style="font-size: 14px;"></i>${customer.default_address}</p>
        <hr class="my-3">
            <h3 class="card-title mb-1" style="color: #1F3BB3;">
                    <b>Services Details</b>
                </h3>
            <div class="amount">
            </div>
            <hr class="my-3">
            <div class="driver mt-2">
                <h3 class="card-title mb-1" style="color: #1F3BB3;">
                    <b>Amount Details</b>
                </h3>
                <div class="row">
                    <div class="col-md-7">
                    <p class="m-0"> Total:</p>
                    </div>
                    <div class="col-md-5">
                    <p class="m-0 total-tax-amount"></p>
                    </div>
                </div>
            </div>          
      `);
                const customerDetailsDiv = $('#customer_details');
                customerDetailsDiv.empty();
                customerDetailsDiv.append(`
            <h3 class="card-title mb-1" style="color: #1F3BB3;">
               <b class="me-2">Customer Details</b>
            </h3>
            <p class="m-0">
               <i class="fa-solid fa-user me-2 pt-1" style="font-size: 14px;"></i>
               ${customer.customer_name}
            </p>
            <p class="m-0">
               <i class="fa-solid fa-phone me-2 pt-1" style="font-size: 14px;"></i>
               +91-${customer.mobile_number}
            </p>
            <p class="m-0">
               <i class="fa-solid fa-map-pin me-2 pt-1" style="font-size: 14px;"></i>
               ${customer.default_address}
            </p>
            <hr class="my-3">
              `);


                fetchAndDisplayServiceAddresses(customerId);
                // FOR BILING ADDRESS
                fetchAndDisplayBillingAddresses(customerId)


            },
        });
    }
    $('a[data-bs-toggle="tab"]').on('click', function() {
        if ($(this).attr('href') === "#tab-two") {
            // Assuming customerId is already defined or fetched from somewhere
            const customerId = $('#customer_id_billing').val(); // Replace with the actual customer ID
            fetchAndDisplayBillingAddresses(customerId);
        }
    });

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
                const serviceAddressesDiv = $('.service_address_row');
                serviceAddressesDiv.empty();

                serviceAddresses.forEach((address, index) => {
                    const defaultChecked = index === 0 ? 'checked' : '';

                    serviceAddressesDiv.append(`
               <div class="col-lg-4 col-md-4 col-sm-12">
                  <div class="card-content-wrapper service_address">
                   <div class="card-content">
                   <label for="radio-card-${index}" class="radio-card">
                     <input type="radio" name="service-address-radio" id="radio-card-${index}" ${defaultChecked} class="check-icon"/>
                       
                  <div class="card-content">
                           <h4>Service Address ${index + 1}</h4>
                           <p class="mb-1"> <strong>Contact No:</strong>${address.contact_no}</p>
                           <p class="mb-1"> <strong>Email ID:</strong>${address.email_id}</p>
                           <p class="mb-1"><strong>Address:</strong> ${address.address}</p>
                           <p class="mb-1"><strong>Unit No:</strong> ${address.unit_number}</p>
                           <p class="mb-1"><strong>Zone:</strong> ${address.zone}</p>
                           <div class="form-check">
                              <input class="form-check-input" type="radio" name="default-address-radio" id="default-address-radio-${index}" ${defaultChecked} />
                              <label class="form-check-label" for="default-address-radio-${index}">
                                 Default Address
                              </label>
                           </div>
                           <hr class="my-3">
                        </div>
                     </div>
   
                  </label>
               </div>
                  </div>
            `);

                    $(`#radio-card-${index}`).on('click', function() {
                        if ($(this).prop('checked')) {

                            const selectedAddressId = address.id;

                            alert(`Selected Address ID: ${selectedAddressId}`);


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
                const billingAddressesDiv = $('#billing-addresses');
                billingAddressesDiv.empty();

                billingAddresses.forEach((address, index) => {
                    const defaultChecked = index === 0 ? 'checked' : '';

                    billingAddressesDiv.append(`
                        <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="card-content-wrapper billing_address">
                            <label for="radio-card-billing-${index}" class="radio-card">
                            <input type="radio" name="billing-address-radio" id="radio-card-billing-${index}" ${defaultChecked} class="check-icon"/>
                            <div class="card-content">
                                <h4>Billing Address ${index + 1}</h4>
                                <p class="mb-1"><strong>Address:</strong>${address.address}</p>
                                <p class="mb-1"><strong>Unit No:</strong>${address.unit_number}</p>
                                <p class="mb-1"><strong>Zone:</strong>${address.zone}</p>
                                <div class="form-check">
                                <input class="form-check-input" type="radio" name="default-address-radio" id="default-address-radio-${index}" ${defaultChecked} />
                                <label class="form-check-label" for="default-address-radio-${index}">
                                    Default Address
                                </label>
                                </div>
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

    function confirm_btn() {
        // $("#lead_btn").click(function(e) {
        // e.preventDefault();
        let form = $('#lead_edit_form')[0];
        let data = new FormData(form);
        data.append('_token', '{{ csrf_token() }}');
        $.ajax({
            url: "{{ route('lead.update') }}",
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

                } else {
                    iziToast.success({
                        message: response.success,
                        position: 'topRight',
                    });

                    $('#add-lead').modal('hide');
                    window.location.reload();
                }
            },
            error: function(xhr, status, error) {
                iziToast.error({
                    message: 'An error occurred: ' + error,
                    position: 'topRight'
                });
            }

        });
        // });
    }
</script>