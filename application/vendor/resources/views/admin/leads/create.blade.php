<style>
    /* Truncate the text to 100 characters with ellipsis */
    .description-cell {
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ui-datepicker {
        border: 1px solid #e6e7e9;
        border-radius: 8px 8px 0px 0px;
    }

    #popover {
        position: relative;
        background-color: white;
        border: 1px solid #ccc;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
        z-index: 2;
        padding: 10px;
    }

    .hidden {
        display: none;
    }

    .highlighted-date {
        background-color: #ffcc00;
        /* Yellow background */
        color: #000;
        /* Black text color */
        font-weight: bold;
    }

    /* Style for the tooltip (label) */
    .highlighted-date:hover:after {
        content: attr(data-tooltip);
        background-color: #333;
        /* Tooltip background color */
        color: #fff;
        /* Tooltip text color */
        padding: 5px 10px;
        border-radius: 5px;
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        white-space: nowrap;
        opacity: 0;
        transition: opacity 0.3s;
    }

    /* Show tooltip on hover */
    .highlighted-date:hover:after {
        opacity: 1;
    }
</style>
<div class="modal-header">
    <h5 class="modal-title">Add New Lead</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form class="row text-left" id="lead_form" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="customer_id_lead" name="customer_id">
        <input type="hidden" id="service_id_lead" name="service_address">
        <input type="hidden" id="billing_id_lead" name="billing_address">
        <input type="hidden" id="selected_date" name="schedule_date">
        <input type="hidden" name="total_amount_val" class="total-gross-amount-input">
        <input type="hidden" name="tax_percent" class="tax_percent">

        <input type="hidden" name="tax" class="total-tax-input">
        <input type="hidden" name="grand_total_amount" class="total-amount-input">

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
                {{-- <li class="nav-item">
               <a class="nav-link" href="#step-7">
               <span class="num">6</span>
               Payment
               </a>
            </li> --}}
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
                                                placeholder="Search…" onkeypress="Search('1')" id="residential">
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
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="commercial-view" role="tabpanel">
                                    <div class="mb-3">
                                        <label class="form-label">Search By</label>
                                        <div class="input-icon mb-3">
                                            <input type="text" value="" class="form-control"
                                                placeholder="Search…." onkeypress="Search('0')" id="commercial">
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
                                            <label class="mb-0" for=""> <b>Customer Name</b> </label>
                                            <p class="m-0"></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="mb-0" for=""><b>Mobile Number</b> </label>
                                            <p class="m-0"></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="mb-0" for=""> <b>Email</b></label>
                                            <p class="m-0"></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="mb-0" for=""><b>Language Spoken</b> </label>
                                            <p class="m-0"></p>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label class="mb-0" for=""> <b>Select Type</b> </label>
                                            <p class="m-0"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="mb-0" for=""><b>Customer Remark</b> </label>
                                            <p class="m-0">
                                            </p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="mb-0" for=""><b>Status</b> </label>
                                            <p></p>
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
                                                        <option value="{{ $list->id }}">{{ $list->company_name }}</option>
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
                                                        <!-- <div class="row" id="productsubcat">
                                             <div class="col-md-4 text-center">
                                                <button type="button"
                                                   class="productsub btn btn-inverse-primary btn-sm">Floor
                                                Cleaning</button>
                                             </div>
                                             <div class="col-md-4 text-center">
                                                <button type="button"
                                                   class="productsub btn btn-inverse-secondary btn-sm">Home
                                                Cleaning</button>
                                             </div>
                                             <div class="col-md-4 text-center">
                                                <button type="button"
                                                   class="productsub btn btn-inverse-warning btn-sm">Office
                                                Cleaning</button>
                                             </div>
                                             </div> -->
                                                        <div class="productsubshow mt-3">
                                                            <div class="table-responsive">
                                                                <table
                                                                    class="table card-table table-vcenter text-center text-nowrap table-transparent"
                                                                    id="service-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Service Id</th>
                                                                            <th>Image</th>
                                                                            <th>Service Name</th>
                                                                            <th>Price</th>
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
                                                <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                                    aria-labelledby="pills-profile-tab">
                                                    <div class="mt-3">
                                                        <div class="row" id="packagesubcat">
                                                            <div class="col-md-4">
                                                                <button type="button"
                                                                    class="packagesub btn btn-inverse-primary btn-sm">Floor
                                                                    Cleaning</button>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button type="button"
                                                                    class="packagesub btn btn-inverse-secondary btn-sm">Home
                                                                    Cleaning</button>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button type="button"
                                                                    class="packagesub btn btn-inverse-warning btn-sm">Office
                                                                    Cleaning</button>
                                                            </div>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table
                                                                class="table card-table table-vcenter text-center text-nowrap table-transparent">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Service Id</th>
                                                                        <th>Image</th>
                                                                        <th>Service Name</th>
                                                                        <th>Unit Price</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td><span class="avatar avatar-sm"
                                                                                style="background-image: url(theme/static/avatars/000m.jpg)"></span>
                                                                        </td>
                                                                        <td>Floor Cleaning</td>
                                                                        <td>$308.00</td>
                                                                        <td>
                                                                            <button class="btn btn-primary ripple"
                                                                                type="button">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    class="icon m-0" width="24"
                                                                                    height="24" viewBox="0 0 24 24"
                                                                                    stroke-width="2"
                                                                                    stroke="currentColor"
                                                                                    fill="none"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round">
                                                                                    <path stroke="none"
                                                                                        d="M0 0h24v24H0z"
                                                                                        fill="none"></path>
                                                                                    <path d="M12 5l0 14"></path>
                                                                                    <path d="M5 12l14 0"></path>
                                                                                </svg>
                                                                            </button>
                                                                        </td>
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
                                            <tbody>
                                            </tbody>
                                            <thead>
                                                <tr>
                                                    <th colspan="5" style="text-align: end;"> Total</th>
                                                    <th class="total-gross-amount" colspan="2">$0.00</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" style="text-align: end;">Tax</th>
                                                    <th><input type="number" class="form-control service-tax" /></th>

                                                    <th colspan="1" style="text-align: end;">Tax Amount</th>
                                                    <th class="total-tax" colspan="2"></th>
                                                </tr>
                                                <tr>
                                                    <th colspan="5" style="text-align: end;">Grand Total</th>
                                                    <th class="total-tax-amount" colspan="2"></th>
                                                </tr>
                                            </thead>
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
                                    <label for="message-text" id="date_of_cleaning" name="date_of_cleaning"
                                        class="col-form-label">Date Of Cleaning</label>
                                    <input type="date" class="form-control" placeholder="dd/mm/yyyy">
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
                                                class="me-2">Customer Details added</b>
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
                        {{-- <div class="col-md-3">
                     <div class="card">
                        <div class="card card-link card-link-pop">
                           <div class="card-status-start bg-primary"></div>
                           <div class="card-stamp">
                              <div class="card-stamp-icon bg-white text-primary">
                                 <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                 <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                       d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                    </path>
                                 </svg>
                              </div>
                           </div>
                           <div class="customer-card-slide3">
                              <div class="card-body">
                                 <h3 class="card-title mb-1" style="color: #1F3BB3;"><b class="me-2">Customer Details added</b>
                                 </h3>
                                 <p class="card-p d-flex align-items-center mb-2 ">
                                    <i class="fa-solid fa-phone me-2" style="font-size: 14px;"></i>+91
                                    9758697820
                                 </p>
                                 <p class="card-p  d-flex align-items-center mb-2">
                                    <i class="fa-solid fa-envelope me-2" style="font-size: 14px;"></i>abc@pvtltd.com
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
                  </div> --}}
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    {{-- <div class="tab-pane" id="tab-three" role="tabpanel">
                                        <div class="row mt-3">
                                            <div class="form-group col-lg-6 col-md-6 col-sm-12 text-start">
                                                <label for="message-text" class="col-form-label">Deposite Type</label>
                                                <select class="form-control" name="deposite_type" id="deposite_type">
                                                <option>Select Option</option>
                                                <option value="$50">$50</option>
                                                <option value="waive">waive</option>
                                                <option value="">waive</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-6 col-md-6 col-sm-12 text-start">
                                                <label for="message-text" class="col-form-label">Date Of Cleaning</label>
                                                <input type="date" class="form-control" placeholder="dd/mm/yyyy" name="cleaning_date" id="cleaning_date">
                                            </div>
                                            <div class="form-group col-lg-6 col-md-6 col-sm-12 text-start">
                                                <label for="message-text" class="col-form-label">Time of Cleaning</label>
                                                <input type="time" class="form-control" placeholder="Time of Cleaning" name="cleaning_time" id="cleaning_time">
                                            </div>
                                        </div>
                                    </div> --}}
                                    
                                    <div class="col-md-3 accordion pull-right" id="accordionExample1">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <a href="#collapseTwo" class="btn btn-light" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                    aria-expanded="false" aria-controls="collapseTwo" >
                                                    Add Discount
                                                </a>
                                            </h2>
                                            <div id="collapseTwo" class="accordion-collapse collapse "
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                {{-- <div class="col-md-4"> --}}
                                                <div class="accordion-body">
                                                    <form action="">
                                                        <div class="form-check">
                                                           <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1" checked>
                                                           <label class="form-check-label" for="exampleRadios1">
                                                              Persentage Discount
                                                           </label>
                                                        </div>
                                                        <div class="form-check">
                                                           <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="option2">
                                                           <label class="form-check-label" for="exampleRadios2">
                                                              Amount Discount
                                                           </label>
                                                        </div><br>
                                                         <label for="">Persentage Discount(%)</label><br><br>
                                                        <input type="text" name="" class="form-control">
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
                                    <table class="table">
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
                                            @foreach ($leadprice as $key => $price)
                                                <tr>
                                                    <th scope="row">{{ $key + 1 }}</th>
                                                    <td>{{ $price->products }}</td>
                                                    <td><input value="{{ $price->unit_price }}"></td>
                                                    <td><input value="{{ $price->qty }}"></td>
                                                    <td><input value="{{ $price->sub_total }}"></td>
                                                    <td>{{ $price->discount }}%</td>
                                                    <td>{{ $price->net_total }}</td>
                                                    <td>
                                                        <a href="{{route('lead.delete.priceinfo',$price->id)}}" class="btn btn-danger"><i
                                                                class="fa fa-times" aria-hidden="true"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div><br>
                            <div class="mb-3 ml-4" style="float:right; margin-left:20px;">
                                {{-- <lable>Sub Total : $${{array_sum($leadprice['sub_total'])}}</label><br> --}}
                                <lable>Sub Total : $$5</label><br>
                                {{-- <lable>Tax Total : $$0.00</label><br> --}}
                                <lable>Net Total : $$26</label><br>
                                <lable>Persentage Discount(%) : 13%</label><br>
                                {{-- <lable>Dilivery Charges : $$0.00</label><br> --}}
                                <lable>Grand Total : </label><br>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div id="step-7" class="tab-pane" role="tabpanel" aria-labelledby="step-7">
               <div class="row">
                  <div class="col-md-3">
                     <div class="card card-link card-link-pop">
                        <div class="card-status-start bg-primary"></div>
                        <div class="card-stamp">
                           <div class="card-stamp-icon bg-white text-primary">
                              <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                 <path
                                    d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                 </path>
                              </svg>
                           </div>
                        </div>
                        <div class="customer-card-slide3">
                           <div class="card-body">
                              <h3 class="card-title mb-1" style="color: #1F3BB3;"><b class="me-2">Customer Details added</b>
                              </h3>
                              <p class="card-p d-flex align-items-center mb-2 ">
                                 <i class="fa-solid fa-phone me-2" style="font-size: 14px;"></i>+91
                                 9758697820
                              </p>
                              <p class="card-p  d-flex align-items-center mb-2">
                                 <i class="fa-solid fa-envelope me-2" style="font-size: 14px;"></i>abc@pvtltd.com
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
                  <div class="col-md-6">
                     <div class="card">
                        <div class="card-body">
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_option" value="advance" id="payment_advance">
                                    <label class="form-check-label" for="payment_advance">
                                    Advance Payment
                                    </label>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_option" value="full" id="payment_full">
                                    <label class="form-check-label" for="payment_full">
                                    Full Payment
                                    </label>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="card">
                        <div class="card-body">
                           <div id="advance_payment_fields" style="display: none;">
                              <label for="advance_amount" class="col-form-label">Advance Amount:</label>
                              <input type="number" name="advance_amount" id="advance_amount" class="form-control">
                              <label for="advance_amount" class="col-form-label">Upload File:</label>
                              <input type="file" name="payment_file" id="advance_amount" class="form-control">
                           </div>

                           <div id="full_payment_fields" style="display: none;">
                              <label for="total_amount" class="col-form-label">Total Amount:</label>
                              <input type="number" name="total_amount" id="total_amount" class="form-control">
                              <label for="upload" class="col-form-label">Upload File:</label>
                              <input type="file" name="full_payment_file" id="advance_amount" class="form-control">
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div> --}}
                <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">
                    
                </div>
            </div>
            <!-- Include optional progressbar HTML -->
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                    aria-valuemax="100"></div>
            </div>
            <!-- <div class="modal-footer">
            <button type="button" class="btn me-auto sw-btn-prev sw-btn">Previous</button>
            <button type="button" class="btn btn-primary next-btn" >Next</button>
            </div> -->
        </div>
    </form>
    <form id="service_address_form" method="POST">
        @csrf
        <div class="col-md-12 add_address" style="display: none;">
            <div class="row my-3">
                <input type="hidden" id="customer_id_service" name="customer_id" value="">
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
                            onchange="handlePostalCodeLookup(this)" class="form-control postal_code">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="">Zone</label>
                        <input type="text" placeholder="Enter Zone" name="zone[]" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="">Address</label>
                        <input type="text" placeholder="Enter Address" name="address[]"
                            class="form-control address">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="">Unit No</label>
                        <input type="text" placeholder="Enter Unit No." name="unit_number[]"
                            class="form-control">
                    </div>
                </div>
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
    <form id="billing_address_form" method="POST">
        @csrf
        <input type="hidden" id="customer_id_billing" name="customer_id" value="">
        <table class="table card-table table-vcenter text-nowrap table-transparent" id="billing_address">
            <thead>
                <tr>
                    <th>Postal Code</th>
                    <th>Address</th>
                    <th>Unit No</th>
                    <th>
                        <button type="button" class="btn btn-blue billing-add-row">+</button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input class="form-control postal_code" type="text" placeholder="Enter Code"
                            name="b_postal_code[]" onchange="handlePostalCodeLookup(this)" />
                    </td>
                    <td>
                        <input class="form-control address" type="text" placeholder="Address"
                            name="b_address[]" />
                    </td>
                    <td>
                        <input class="form-control" type="text" placeholder="Enter Unit No"
                            name="b_unit_number[]" />
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
</div>

<div class="modal fade" id="schedulemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Service Details</h5>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            {{-- <form action="{{route("lead.schedule")}}" method="POST">
         @csrf --}}
            <div class="modal-body">
                <label for="">Service Name</label>
                <select name="service" class="form-control" id="service_name">
                    <option value=""></option>
                    @foreach ($service as $item)
                        <option value="{{ $item->id }}">{{ $item->service_name }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="service_date" id="service_date">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="setSchedule()">Save changes</button>
            </div>
            {{-- </form> --}}
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="add-crm-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 800px;">
        <div class="modal-content" id="add-crm-model-content">
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="priceFeild" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Feild</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form action="{{ route('lead.price') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-mb-3">
                                <label for="product" class="form-label">Products/Description</label>
                                <input type="text" class="form-control" id="product" name="products">
                            </div>
                            <div class="col-mb-3">
                                <label for="unit_price" class="form-label">Unit Price</label>
                                <input type="number" class="form-control" id="unit_price" name="unit_price">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="qty" class="form-label">Qty</label>
                            <input type="number" class="form-control" id="qty" name="qty">
                        </div>
                        <div class="mb-3">
                            <label for="sub_total" class="form-label">Sub Total(SGD)</label>
                            <input type="text" class="form-control" id="sub_total" name="sub_total">
                        </div>
                        <div class="mb-3">
                            <label for="discount" class="form-label">Discount(%)</label>
                            <input type="number" class="form-control" id="discount" name="discount">
                        </div>
                        <div class="mb-3">
                            <label for="net_total" class="form-label">Net Total(SGD)</label>
                            <input type="text" class="form-control" id="net_total" name="net_total">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#service_address_form").insertAfter("#step-3 .service_address_row");
        $("#service_address_form").show();
        $("#billing_address_form").insertAfter("#step-3 .billing_address_row");
        $("#billing_address_form").show();
    });

    function addPriceFeild() {
        // $('#priceFeild').modal("show");
        var field = document.getElementById('addNewFeild');
        var newField = document.createElement("tr");

        newField.innerHTML = `
        <tr>
            <td></td>
            <td><input type="text" class="form-control" name=""></td>
            <td><input type="text" class="form-control" name=""></td>
            <td><input type="number" class="form-control"name=""></td>
            <td><input type="number" class="form-control"name=""></td>
            <td><input type="number" class="form-control"name=""></td>
            <td><input type="number" class="form-control"name=""></td>
            <td><a href="#" class="btn btn-danger" onclick="alert('Are You Sure')"><i class="fa fa-times" aria-hidden="true"></i></a></td>
        </tr>
                         `;

        field.appendChild(newField);
    }
</script>

<script>
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
                _token: '{{ csrf_token() }}'
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
                              
                            </div>
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
                            alert(`Selected Billing ID: ${selectedAddressId}`);
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
</script>
<script>
    $(document).on('click', '.add-service-btn', function() {
        const serviceId = $(this).data('service-id');
        const serviceName = $(this).data('service-name');
        const quantity = parseInt($(this).closest('tr').find('.quantity-input').val(), 10) || 0;
        const row = ``;

        $('#selected-services-table tbody').append(row);
        updateAmountDiv();
    });
    $(document).on('click', '.remove-service-btn', function() {
        const serviceIdToRemove = $(this).closest('tr').find('td:first-child').text().trim();
        $(this).closest('tr').remove();
        updateAmountDiv();
    });
    $(document).on('change', '.quantity-input', function() {
        updateAmountDiv();
    });

    function updateAmountDiv() {
        let amountHTML = '';
        $('#selected-services-table tbody tr').each(function() {
            const serviceName = $(this).find('td:eq(1)').text();
            const quantity = parseInt($(this).find('.quantity-input').val(), 10);
            amountHTML += `<p class="m-0 card-p">${serviceName}(${quantity})</p>`;
        });

        $('.amount').html(amountHTML);
    }
</script>
<script>
    var company_id = $('#company-select').val();

    function searchService() {
        var searchValue = $('#service-search').val().trim();
        var selectedCompanyId = $('#company-select').val();
        console.log(selectedCompanyId);
        if (!searchValue) {
            $('.productsubshow').hide();
            return;
        }

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
    <tr>
    <td>${service.id}</td>
    <td><span class="avatar avatar-sm" style="background-image: url(${service.image_url})"></span></td>
    <td>${service.service_name}</td>
    <td>${service.price}</td>
    <td>
       <button class="btn btn-primary ripple add-service-btn" type="button"
      data-service-id="${service.id}"
      data-service-name="${service.service_name}"
      data-service-description="${service.description}"
      data-service-price="${service.price}"
      data-service-discount="${service.discount}"
      data-service-quantity="${service.quantity}"

       >
          <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24"
          stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
          <path d="M12 5l0 14"></path>
          <path d="M5 12l14 0"></path>
          </svg>
       </button>
    </td>
    </tr>
    `;
                    tableBody.append(row);
                });

                // Show the table with search results
                $('.productsubshow').show();
            },
        });
    }
    $(document).on('input', '.quantity-input', function() {
        const enteredQuantity = parseInt($(this).val(), 10) || 0;

        const quantities = $('.quantity-input').map(function() {
            return parseInt($(this).val(), 10) || 0;
        }).get();

        $('.quantity-input-j').val(quantities.join(', '));
    });
    $(document).on('input', '.discount-input', function() {
        const enteredDiscount = parseInt($(this).val(), 10) || 0;
        const discounts = $('.discount-input').map(function() {
            return parseInt($(this).val(), 10) || 0;
        }).get();
        $('.discount-input-j').val(discounts.join(', '));
    });
    $(document).on('click', '.add-service-btn', function() {
        const serviceId = $(this).data('service-id');
        const serviceName = $(this).data('service-name');
        const serviceDescription = $(this).data('service-description');
        const servicePrice = $(this).data('service-price');
        const quantityInput = $(this).closest('tr').find('.quantity-input');
        const quantity = parseInt(quantityInput.val(), 10) || 0;
        //   if ($('#selected-services-table tbody tr[data-service-id="' + serviceId + '"]').length > 0) {
        //     alert('This service is already added.');
        //     return;
        //   }
        const row = `

     <tr>
     <input type="text" class="form-control price" value="${serviceName}" name="service_name[]">
       <td>${serviceId}</td>
       <td>${serviceName}</td>
      <td class="description-cell" data-full-text="${serviceDescription}">${serviceDescription}</td>
   
      
       <td><input type="number" class="form-control price" value="${servicePrice}"></td>
       <td class="p-0"><input type="number" class="form-control quantity-input" placeholder="quantity" value="${quantity}"></td>

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
        $('#selected-services-table tbody').append(row);
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
    });
    updateGrossAmounts();
    $(document).on('click', '.remove-service-btn', function() {
        const row = $(this).closest('tr');
        row.remove();
        updateGrossAmounts();
    });
    $(document).on('change', '.quantity-input, .discount-input, .service-tax', function() {
        updateGrossAmounts();
    });

    function updateGrossAmounts() {
        let totalDiscountedAmount = 0;
        $('#selected-services-table tbody tr').each(function() {
            const quantity = parseInt($(this).find('.quantity-input').val(), 10);

            const discount = parseInt($(this).find('.discount-input').val(), 10) || 0;
            const unitPrice = parseFloat($(this).find('.price').val(), 10);

            const grossAmount = (unitPrice * quantity * (100 - discount) / 100).toFixed(2);

            $(this).find('td:nth-child(7)').text(`$${grossAmount}`);
            let totalGrossAmount = 0;
            $('#selected-services-table tbody tr').each(function() {
                const grossAmount = parseFloat($(this).find('td:nth-child(7)').text().replace('$', ''));
                totalGrossAmount += grossAmount;
            });
            const discountedAmount = (unitPrice * quantity * discount / 100).toFixed(2);
            totalDiscountedAmount += parseFloat(discountedAmount);
            $('.total-gross-amount').text(`$${totalGrossAmount.toFixed(2)}`);
            $('.total-discount').text(`$${totalDiscountedAmount.toFixed(2)}`);

        });

        const tax = parseFloat($('.service-tax').val(), 10) || 0;
        let totalGrossAmount = 0;
        $('#selected-services-table tbody tr').each(function() {
            const grossAmount = parseFloat($(this).find('td:nth-child(7)').text().replace('$', ''));
            totalGrossAmount += grossAmount;
        });
        const totalTaxAmount = (totalGrossAmount * (tax / 100)).toFixed(2);
        const totalAmountWithTax = (totalGrossAmount + parseFloat(totalTaxAmount)).toFixed(2);
        $('.total-gross-amount').text(`$${totalGrossAmount.toFixed(2)}`);
        $('.total-tax-amount').text(`$${totalAmountWithTax}`);
        $('#total_amount').text(`$${totalGrossAmount.toFixed(2)}`);
        $('.total-tax').text(`$${totalTaxAmount}`);
        $('.total-gross-amount-input').val(totalGrossAmount.toFixed(2));
        $('.total-amount-input').val(totalAmountWithTax);
        $('.total-tax-input').val(totalTaxAmount);
        $('.tax_percent').val(tax);

    }
</script>
<script>
    $(document).ready(function() {

        $(document).on('click', '.add-service-btn', function() {
            const serviceId = $(this).data('service-id');
            const serviceName = $(this).data('service-name');
            const serviceDescription = $(this).data('service-description');
            const unitPrice = parseFloat($(this).data('service-price'));
            const discount = parseFloat($(this).data('service-discount'));

            const row = `
         <tr data-service-id="${serviceId}" data-service-discount="${discount}">
            <td>${serviceId}</td>
            <td>${serviceName}</td>
            <td class="description-cell">${serviceDescription}</td>
            <td>${unitPrice.toFixed(2)}</td>
            <td class="quantity-value"></td>
            <td class="discount-value">${discount.toFixed(2)}</td>
            <td class="total-gross-amount-input"></td>
         </tr>
      `;

            $('#final-services-list tbody').append(row);


            const newRow = $('#final-services-list tbody tr:last');
            newRow.find('.quantity-value').text(0);
            newRow.find('.discount-value').text(discount.toFixed(2));
            newRow.find('.total-gross-amount-input').text(0);

            updateGrossAmountFinal(newRow);
        });

        function updateGrossAmountFinal(row) {
            const quantityInput = row.find('.quantity-input');
            const discountInput = row.find('.discount-input');

            // Get the input values
            const quantity = parseFloat(quantityInput.val()) || 0;
            const unitPrice = parseFloat(row.find('td:eq(3)').text()) || 0;
            const discount = parseFloat(discountInput.val()) || 0;
            const grossAmount = (quantity * unitPrice) * (1 - discount / 100);
            row.find('.quantity-value').text(quantity.toFixed(2));
            row.find('.discount-value').text(discount.toFixed(2));
            row.find('.total-gross-amount-input').text(grossAmount.toFixed(2));
        }
        $(document).on('change', '.quantity-input, .discount-input', function() {

            updateGrossAmountFinal($(this).closest('tr'));
        });
    });
</script>

<script>
    // Function to check if the description needs truncation
    function isDescriptionTruncated(description) {
        const maxChars = 15;
        return description.length > maxChars;
    }

    // Toggle full text on click if needed
    $(".description-cell").click(function() {
        const $this = $(this);
        const fullText = $this.data('full-text');
        if (isDescriptionTruncated($this.text())) {
            $this.text(fullText);
        } else {
            $this.text(truncateDescription(fullText));
        }
    });
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
<script>
    $(document).ready(function() {
        // Toggles paragraphs display
        $(".add_btn").click(function() {
            $(".add_address").toggle();
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
            '<div class="col-md-3"><div class="form-group mb-3"> <label for="name">Unit No</label><input type="text" placeholder="Enter Unit No." name="name" class="form-control" required=""> </div> </div>' +
            '<div class="col-md-1" style="display: flex; align-items: center;">  <button type="button" class="btn btn-danger" id="DeleteRow">-</button></div></div>';

        $('#newinput').append(newRowAdd);
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
<script type="text/javascript">
    $("#rowAdder_add_lead").click(function() {
        newRowAdd =
            '<div class="row my-3" id="row">  <div class="col-md-4">' +
            '<div class="form-group mb-3">' +
            ' <label for="name">Person Incharge Name</label><input type="text" placeholder="Enter Name" name="person_incharge_name[]" class="form-control"/></div></div>' +
            '<div class="col-md-4"><div class="form-group mb-3"> <label for="">Contact No</label><input type="text" placeholder="Enter Number" name="contact_no[]" class="form-control"> </div> </div>' +
            '<div class="col-md-4"><div class="form-group mb-3"><label for="">Email Id</label><input type="text" placeholder="Enter Email" name="email_id[]" class="form-control"></div> </div>' +
            '<div class="col-md-4"><div class="form-group mb-3"><label for="">Postal Code</label><input type="text" placeholder="Enter Code" name="postal_code[]" onchange="handlePostalCodeLookup(this)" class="form-control postal_code"></div> </div>' +
            '<div class="col-md-4"><div class="form-group mb-3"><label for="">Zone</label><input type="text" placeholder="Enter Zone" name="zone[]" class="form-control"></div> </div>' +
            '<div class="col-md-4"><div class="form-group mb-3"><label for="">Address</label><input type="text" placeholder="Enter Address" name="address[]" class="form-control address"> </div> </div>' +
            '<div class="col-md-3"><div class="form-group mb-3"> <label for="">Unit No</label><input type="text" placeholder="Enter Unit No." name="unit_number[]" class="form-control"> </div> </div>' +
            '<div class="col-md-1" style="display: flex; align-items: center;">  <button type="button" class="btn btn-danger" id="DeleteRow">-</button></div></div>';

        $('#newinput_add_lead').append(newRowAdd);
    });

    $("body").on("click", "#DeleteRow", function() {
        $(this).parents("#row").remove();
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
    $('#smartwizard').smartWizard({
        transition: {
            animation: 'slideHorizontal',
        }
    });

    $('#smartwizard').on('leaveStep', function(e, anchorObject, stepNumber, stepDirection) {
        // console.log("stepDirection", stepDirection);
        console.log("company_id",company_id);
        if (stepDirection == 5) {
            $.ajax({
                url: '{{ route('get.lead.preview') }}',
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    'company_id': company_id
                },
                success: function(response) {
                    $('#step-4').html(response);
                    console.log(company_id);
                },
            })
        }
    });
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
    $(function() {
        $('#billing_address .billing-add-row').click(function() {
            var template =
                '<tr><td><input class="form-control postal_code" type="text" placeholder="Enter Code p" name="b_postal_code[]" onchange="handlePostalCodeLookup(this)"/></td><td><input class="form-control address" type="text" placeholder="Address" name="b_address[]"/></td><td><input class="form-control" type="text" placeholder="Enter Unit No" name="b_unit_number[]"/></td><td><button type="button" class="btn btn-danger delete-row">-</button></td></tr>';
            $('#billing_address tbody').append(template);
        });
        $('#billing_address').on('click', '.delete-row', function() {
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

    // <!-- BILLING ADDRESS STORE -->

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
                    $('#billing_address_form').hide();
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
</script>
<script>
    function confirm_btn() {
        // $("#lead_btn").click(function(e) {
        console.log('hello');
        // e.preventDefault();
        let form = $('#lead_form')[0];
        let data = new FormData(form);
        data.append('_token', '{{ csrf_token() }}');
        $.ajax({
            url: "{{ route('lead.store') }}",
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
<script>
    function handlePostalCodeLookup(element) {
        var postalCode = $(element).val();
        var addressField = $(element).closest('.row').find('.address');

        $.ajax({
            url: 'get-address',
            method: 'GET',
            data: {
                postal_code: postalCode
            },
            success: function(response) {
                addressField.val(response.address);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    }
</script>
<script>
    $(document).ready(function() {
        $('input[name="payment_option"]').change(function() {
            var selectedOption = $('input[name="payment_option"]:checked').val();
            if (selectedOption === "advance") {
                $("#advance_payment_fields").show();
                $("#full_payment_fields").hide();
            } else if (selectedOption === "full") {
                $("#advance_payment_fields").hide();
                $("#full_payment_fields").show();
            } else {
                // Handle other cases here
            }
        });
    });
</script>
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
                const today = new Date();

                const selectedDay = selectedDate.getDate();
                console.log(selectedDay);
                const selectedMonth = selectedDate.getMonth() + 1;
                const selectedYear = selectedDate.getFullYear();
                const selectedDayName = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][
                    selectedDate.getDay()
                ];

                console.log('Selected Date:', selectedDay + '/' + selectedMonth + '/' +
                    selectedYear);
                console.log('Selected Day Name:', selectedDayName);
                $('#selected_date').val(selectedDay + '/' + selectedMonth + '/' + selectedYear);
                $('#service_date').val(selectedDay + '/' + selectedMonth + '/' + selectedYear);
                // showPopover(selectedDay + '/' + selectedMonth + '/' + selectedYear);
                // $('#schedulemodal').modal('show');
            }
        });
    });

    // function showPopover(date) {
    //    $('#popover').removeClass('hidden').tooltip({
    //       content: function () {
    //             return $(this).find('.').val();
    //       },
    //      position: {
    //          my: 'top',  // Position of the tooltip
    //          at: 'top'      // Position of the element it's attached to (the input field)
    //      }
    //    });

    //    // Set the input field value
    //    $('#selected_date').val(date);
    // }


    // Event listener to hide the popover when clicking outside
    // $(document).on('click', function (event) {
    //    if (!$(event.target).closest('#popover').length && !$(event.target).is('#datepicker')) {
    //       hidePopover();
    //    }
    // });

    // Function to hide the popover
    function hidePopover() {
        $('#popover').addClass('hidden').tooltip('hide');
    }


    function setSchedule() {
        var service = document.getElementById('service_name').value;
        var service_date = document.getElementById('service_date').value;
        console.log(service);

        $.ajax({
            url: '{{ route('lead.schedule') }}',
            type: "post",
            data: {
                '_token': '{{ csrf_token() }}',
                'service': service,
                'service_date': service_date
            },
            success: function(response) {
                $('#schedulemodal').modal('hide');
                location.reload();
            }
        })

    }
</script>
<script>
    const depositeTypeSelect = document.getElementById('deposite_type');
    const cleaningDateInput = document.getElementById('cleaning_date');
    const cleaningTimeInput = document.getElementById('cleaning_time');

    depositeTypeSelect.addEventListener('change', updateCleaningDetails);
    cleaningDateInput.addEventListener('change', updateCleaningDetails);
    cleaningTimeInput.addEventListener('change', updateCleaningDetails);

    function updateCleaningDetails() {
        const depositeType = depositeTypeSelect.value || 'N/A';
        const cleaningDate = cleaningDateInput.value || 'N/A';
        const cleaningTime = cleaningTimeInput.value || 'N/A';

        const depositeTypeOutput = document.getElementById('deposite_type_output');
        const cleaningDateOutput = document.getElementById('cleaning_date_output');
        const cleaningTimeOutput = document.getElementById('cleaning_time_output');

        depositeTypeOutput.textContent = `Deposite Type: ${depositeType}`;
        cleaningDateOutput.textContent = `Date Of Cleaning: ${cleaningDate}`;
        cleaningTimeOutput.textContent = `Time Of Cleaning: ${cleaningTime}`;
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"
    integrity="sha512-2rNj2KJ+D8s1ceNasTIex6z4HWyOnEYLVC3FigGOmyQCZc2eBXKgOxQmo3oKLHyfcj53uz4QMsRCWNbLd32Q1g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
