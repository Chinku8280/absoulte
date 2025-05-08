<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">View Sales Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="closeModal()" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form class="row text-left">
            <div id="smartwizard" style="border: none; height: auto;">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#step-1">
                            <div class="num">1</div>
                            Customer-Details
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#step-2">
                            <span class="num">2</span>
                            Services
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="#step-3">
                            <span class="num">3</span>
                            sceduling
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#step-4">
                            <span class="num">4</span>
                            sceduling
                        </a>
                    </li> --}}
                </ul>
                <div class="tab-content mt-3" style="border: none;">
                    <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="d-flex" style="justify-content: space-between; align-items: center;">
                                    <h5 class="modal-title mb-0">Customer Details</h5>
                                </div>
                                <div class="card mt-3 card-active">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label class="mb-0" for=""> <b>Customer Type
                                                    </b>
                                                </label>
                                                @if ($salesOrder->customer_type == 'residential_customer_type')
                                                    <p class="m-0"><span class="badge bg-blue"> Residential </span>
                                                    </p>
                                                @else
                                                    <p class="m-0"><span class="badge bg-warning"> Commercial </span>
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="mb-0" for=""> <b>Customer Name</b>
                                                </label>
                                                <p class="m-0">                                                   
                                                    @if ($salesOrder->customer_type == 'residential_customer_type')
                                                        {{ $salesOrder->customer_name }}
                                                    @else
                                                        {{ $salesOrder->individual_company_name }}
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="mb-0" for=""><b>Contact No.</b>
                                                </label>
                                                <p class="m-0">+65-{{ $salesOrder->mobile_number }}</p>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="mb-0" for=""> <b>Email</b></label>
                                                <p class="m-0">{{ $salesOrder->email }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            {{-- <div class="col-md-3">
                                                <label class="mb-0" for=""> <b>Territory</b>
                                                </label>
                                                <p class="m-0">{{ $salesOrder->territory }}</p>
                                            </div> --}}
                                            <div class="col-md-3">
                                                <label class="mb-0" for=""> <b>Language Spoken</b>
                                                </label>
                                                <p class="m-0">{{$salesOrder->language_name}}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="mb-0" for=""><b>Status</b>
                                                </label>
                                                @if ($salesOrder->customer_status == 1)
                                                    <p><span class="badge bg-success">Active</span></p>
                                                @elseif ($salesOrder->customer_status == 2)
                                                    <p><span class="badge bg-secondary">In Active</span></p>
                                                @else
                                                    <p><span class="badge bg-red">Block</span></p>
                                                @endif
                                            </div>
                                            {{-- <div class="col-md-3">
                                                <label class="mb-0" for=""> <b>Outstanding
                                                        Amount</b></label>
                                                <p class="m-0">$ 0.00</p>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>

                                @if (!empty($salesOrder->renewal_remarks))
                                    <div class="mt-3">
                                        <div class="card-body">
                                            <label class="form-label">Renewal Remarks</label>
                                            <textarea class="form-control" cols="30" rows="5" readonly>{{$salesOrder->renewal_remarks}}</textarea>
                                        </div>
                                    </div>
                                @endif                               
                            </div>
                            <div class="col-md-3">
                                <h5 class="modal-title mb-0">Address</h5>
                                <ul class="nav nav-pills nav-pills-primary mt-3" data-bs-toggle="tabs" role="tablist">
                                    <li class="nav-item me-2" role="presentation">
                                        <a href="#tab-one" class="nav-link active" data-bs-toggle="tab"
                                            aria-selected="true" role="tab">Service Address</a>
                                    </li>
                                    <li class="nav-item me-2" role="presentation">
                                        <a href="#tab-two" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                                            role="tab" tabindex="-1">Billing
                                            Address</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active show" id="tab-one" role="tabpanel">
                                        <div class="row my-3">
                                            <div class="col-lg-12">
                                                <label for="service_address_radio_card" class="radio-card">
                                                   
                                                    <div class="card-content-wrapper">
                                                        <div class="card-content">
                                                            <h4>{{ $service_address->address ?? '' }}</h4>
                                                            <p class="mb-1"> <strong>Contact
                                                                    No:</strong>{{ $service_address->contact_no ?? ''}}
                                                            </p>
                                                            <p class="mb-1"> <strong>Email
                                                                    ID:</strong>{{ $service_address->email_id ?? '' }}
                                                            </p>
                                                            <p class="mb-1">
                                                                <strong>Address:</strong>{{ $service_address->address ?? ''}}
                                                            </p>
                                                            <p class="mb-1"><strong>Unit
                                                                    No:</strong>{{ $service_address->unit_number ?? ''}}
                                                            </p>
                                                            <p class="mb-1">
                                                                <strong>Zone:</strong>{{ $service_address->zone ?? ''}}
                                                            </p>
                                                            {{-- <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    name="flexRadioDefault" id="flexRadioDefault2059"
                                                                    checked>
                                                                <label class="form-check-label"
                                                                    for="flexRadioDefault2059">
                                                                    Default Address
                                                                </label>
                                                            </div> --}}
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-two" role="tabpanel">
                                        <div class="row my-3">
                                            <div class="col-lg-12">
                                                <label for="billing_address_radio_card" class="radio-card">
                                                    
                                                    <div class="card-content-wrapper">
                                                        <div class="card-content">
                                                            <h4>{{ $salesOrder->customer_name ?? ''}}</h4>
                                                            <p class="mb-1"> <strong>Email ID:</strong>{{ $billing_address->email ?? '' }}
                                                            </p>
                                                            <p class="mb-1"><strong>Address:</strong>
                                                                {{ $billing_address->address ?? '' }}
                                                            </p>
                                                            <p class="mb-1"><strong>Unit
                                                                    No:</strong>{{ $billing_address->unit_number ?? ''}}
                                                            </p>
                                                            {{-- <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    name="flexRadioDefault" id="flexRadioDefault22"
                                                                    checked>
                                                                <label class="form-check-label"
                                                                    for="flexRadioDefault22">
                                                                    Default Address
                                                                </label>
                                                            </div> --}}
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
                        <div class="row">
                            {{-- <div class="col-md-4 ">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Select Company<span
                                                            class="text-danger">*</span></label>
                                                    <select type="text" class="form-select" value="">
                                                        @foreach ($company as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="">
                                                    <label class="form-label">Search By</label>
                                                    <div class="input-icon mb-3">
                                                        <input type="text" value="" class="form-control"
                                                            placeholder="Search…">
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
                                                <div class="tab-content p-0" id="pills-tabContent"
                                                    style="border: none;">
                                                    <div class="tab-pane fade" id="pills-home" role="tabpanel"
                                                        aria-labelledby="pills-home-tab">
                                                        <div class="mt-3">
                                                            <div class="row" id="productsubcat">
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
                                                            </div>
                                                            <div class="productsubshow mt-3" style="display: none;">
                                                                <div class="table-responsive">
                                                                    <table
                                                                        class="table card-table table-vcenter text-center text-nowrap table-transparent">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>SL NO</th>
                                                                                <th>Image</th>
                                                                                <th>Item</th>
                                                                                <th>Unit Price</th>
                                                                                <th>Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>Floor Cleaning</td>
                                                                                <td>$308.00</td>
                                                                                <td>
                                                                                    <button
                                                                                        class="btn btn-primary   ripple"
                                                                                        type="button">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                                            class="icon m-0"
                                                                                            width="24"
                                                                                            height="24"
                                                                                            viewBox="0 0 24 24"
                                                                                            stroke-width="2"
                                                                                            stroke="currentColor"
                                                                                            fill="none"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round">
                                                                                            <path stroke="none"
                                                                                                d="M0 0h24v24H0z"
                                                                                                fill="none">
                                                                                            </path>
                                                                                            <path d="M12 5l0 14">
                                                                                            </path>
                                                                                            <path d="M5 12l14 0">
                                                                                            </path>
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
                                                    <div class="tab-pane fade" id="pills-profile"
                                                        role="tabpanel"
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
                                                                            <th>SL NO</th>
                                                                            <th>Image</th>
                                                                            <th>Item</th>
                                                                            <th>Unit Price</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>1</td>
                                                                            <td><span class="avatar avatar-sm"
                                                      style="background-image: url(./static/avatars/000m.jpg)"></span></td>
                                                                            <td>Floor Cleaning</td>
                                                                            <td>$308.00</td>
                                                                            <td>
                                                                                <button
                                                                                    class="btn btn-primary ripple"
                                                                                    type="button">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        class="icon m-0"
                                                                                        width="24"
                                                                                        height="24"
                                                                                        viewBox="0 0 24 24"
                                                                                        stroke-width="2"
                                                                                        stroke="currentColor"
                                                                                        fill="none"
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round">
                                                                                        <path
                                                                                            stroke="none"
                                                                                            d="M0 0h24v24H0z"
                                                                                            fill="none">
                                                                                        </path>
                                                                                        <path
                                                                                            d="M12 5l0 14">
                                                                                        </path>
                                                                                        <path
                                                                                            d="M5 12l14 0">
                                                                                        </path>
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
                            </div> --}}
                            <div class="col-md-12 pe-0">
                                <div id="service-table">
                                    <div class="card">
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table card-table table-vcenter text-center text-nowrap"
                                                    id="" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>SL NO</th>
                                                            <th>Item</th>
                                                            <th>Unit Price</th>
                                                            <th>Qty</th>
                                                            <th>Discount (%)</th>
                                                            <th>Gross Amt ($)</th>
                                                            <th>Total Session</th>
                                                            {{-- <th>Tax</th> --}}
                                                            {{-- <th>Action</th> --}}
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($serviceDetails as $key => $service)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>{{ $service->name }}</td>
                                                                <td class="p-0"><input type="number"
                                                                        class="form-control"
                                                                        value="{{ $service->unit_price }}" disabled>
                                                                </td>
                                                                <td class="p-0"><input type="number"
                                                                        class="form-control"
                                                                        value="{{ $service->quantity }}" disabled>
                                                                </td>
                                                                <td>{{ $service->discount }}</td>
                                                                <td>${{ $service->gross_amount }}</td>
                                                                <td>{{ $service->total_session }}</td>
                                                                {{-- <td>
                                                                    <button class="btn btn-danger ripple"
                                                                        type="button">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="icon icon-tabler icon-tabler-playstation-x m-0"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24"
                                                                            stroke-width="2"
                                                                            stroke="currentColor"
                                                                            fill="none"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                            <path stroke="none"
                                                                                d="M0 0h24v24H0z"
                                                                                fill="none">
                                                                            </path>
                                                                            <path
                                                                                d="M12 21a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9a9 9 0 0 0 -9 9a9 9 0 0 0 9 9z">
                                                                            </path>
                                                                            <path d="M8.5 8.5l7 7"></path>
                                                                            <path d="M8.5 15.5l7 -7"></path>
                                                                        </svg>
                                                                    </button>
                                                                </td> --}}
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    {{-- <thead>
                                                        <tr>
                                                            <th colspan="7" style="text-align: end;">
                                                                Total
                                                                discount
                                                            </th>
                                                            <th colspan="2">5%</th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="7" style="text-align: end;">
                                                                Total
                                                                tax
                                                            </th>
                                                            <th colspan="2">18%</th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="7" style="text-align: end;">
                                                                Grand
                                                                total
                                                            </th>
                                                            <th colspan="2">$ 616.00</th>
                                                        </tr>
                                                    </thead>
                                                    <thead id="package-total" style="display: none;">
                                                        <tr>
                                                            <th colspan="7" style="text-align: end;">
                                                                Package Amount
                                                            </th>
                                                            <th colspan="2"><input type="text"
                                                                    class="form-control"></th>
                                                        </tr>
                                                    </thead> --}}
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="package-table" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Package Name</label>
                                                <input type="text" value="" class="form-control w-50"
                                                    placeholder="Enter Package Name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table card-table table-vcenter text-center text-nowrap"
                                                    id="" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>SL NO</th>
                                                            <th>Item</th>
                                                            <th>Item Discription</th>
                                                            <th>Categoery</th>
                                                            <th>Unit Price</th>
                                                            <th>Qty</th>
                                                            <th>Discount (%)</th>
                                                            <th>Gross Amt ($)</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Floor Cleaning</td>
                                                            <td>Floor-1</td>
                                                            <td>
                                                                <textarea class="form-control" name="example-textarea-input" rows="3" placeholder="Enter Descrption">
                                      </textarea>
                                                            </td>
                                                            <td><input type="number" class="form-control">
                                                            </td>
                                                            <td class="p-0"><input type="number"
                                                                    class="form-control"></td>
                                                            <td>0</td>
                                                            <td>$308.00</td>
                                                            <td>
                                                                <button class="btn btn-danger ripple" type="button">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon icon-tabler icon-tabler-playstation-x m-0"
                                                                        width="24" height="24"
                                                                        viewBox="0 0 24 24" stroke-width="2"
                                                                        stroke="currentColor" fill="none"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none">
                                                                        </path>
                                                                        <path
                                                                            d="M12 21a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9a9 9 0 0 0 -9 9a9 9 0 0 0 9 9z">
                                                                        </path>
                                                                        <path d="M8.5 8.5l7 7"></path>
                                                                        <path d="M8.5 15.5l7 -7"></path>
                                                                    </svg>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <thead>
                                                        <tr>
                                                            <th colspan="7" style="text-align: end;">
                                                                TOTAL
                                                                DISCOUNT
                                                            </th>
                                                            <th colspan="2">$ 616.00</th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="7" style="text-align: end;">
                                                                Total
                                                                tax
                                                            </th>
                                                            <th colspan="2">18%</th>
                                                        </tr>
                                                    </thead>
                                                    <thead>
                                                        <tr>
                                                            <th colspan="7" style="text-align: end;">
                                                                Package Amount
                                                            </th>
                                                            <th colspan="2"><input type="text"
                                                                    class="form-control"></th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    
                    <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-1">
                                <div class="mb-3">
                                    <label class="form-label">Territory<span class="text-danger">*</span></label>
                                    <select type="text" class="form-select" value="">
                                        @foreach ($territory as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                {{-- <div class="mb-3">
                                    <div class="form-label">Assign Cleaners<span class="text-danger">*</span>
                                    </div>
                                    <div class="dropdown" data-control="checkbox-dropdown">
                                        <label class="dropdown-label">Select Options</label>
                                        <div class="dropdown-list">
                                            <a href="#" data-toggle="check-all"
                                                class="dropdown-option border-bottom text-blue">Check
                                                All</a>
                                            <label class="dropdown-option">
                                                <input type="checkbox" name="dropdown-group" value="Selection 1">
                                                Floor Cleaning
                                            </label>
                                            <label class="dropdown-option">
                                                <input type="checkbox" name="dropdown-group" value="Selection 2">
                                                Home Cleaning
                                            </label>
                                            <label class="dropdown-option">
                                                <input type="checkbox" name="dropdown-group" value="Selection 3">
                                                Office Cleaning
                                            </label>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="mb-3">
                                    <label class="form-label">Assign Cleaners<span
                                            class="text-danger">*</span></label>
                                    <select type="text" class="form-select" value="">
                                        @foreach ($assign_cleaner as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Select Date</label>
                                    <div class="input-icon">
                                        <input class="form-control" type="date" placeholder="Select a date"
                                            id="datepicker-icon-prepend"
                                            value="{{ date('Y-m-d', strtotime($salesOrder['created_at'])) }}">
                                        {{-- <input class="form-control" type="date" placeholder="Select a date"
                                            id="datepicker-icon-prepend" value="{{strtotime($salesOrder->created_at->format('d,M,Y'))}}"> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Select Time</label>
                                <div class="cs-form">
                                    <input type="time" class="form-control" value="10:05 AM" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <style>
                        .invoice-box .title {
                            font-size: 2rem;
                            color: #000;
                            font-weight: bolder;
                        }

                        .footer-logo .img img {
                            width: 80px;
                        }

                        .company-logo {
                            width: 90%;
                            height: 90%;
                            background-color: black;
                        }
                    </style>
                    <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">
                        <div class="invoice-box container-fluid mt-100 mb-100">
                            <div id="ui-view">
                                <div>
                                    <div class="card">
                                        <div class="card-header border-0">
                                            <div class="row w-100">
                                                <div class="col-md-3">
                                                    <img src=" {{ 'public/company_logos/' . $companyDetail->company_logo }}"
                                                        alt="" class="img-fluid company-logo">
                                                </div>
                                                <div class="col-md-9">
                                                    <h1 class="title"><b>{{ $companyDetail->company_name }}</b></h1>
                                                    <p class="m-0">{{ $companyDetail->company_address }}
                                                    </p>
                                                    <p class="m-0">Tel: {{ $companyDetail->contact_number }} Fax:
                                                        {{ $companyDetail->contact_number }}
                                                        Phone: {{ $companyDetail->contact_number }}
                                                    </p>
                                                    <p class="m-0">Website: {{ $companyDetail->website }}
                                                    </p>
                                                    <p class="m-0">Co. Reg No: 201524788N
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GST Reg No.
                                                        201524788N
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-4">
                                                <div class="col-sm-1">
                                                    <h4 class="mb-3"><b>To:</b></h4>
                                                </div>
                                                <div class="col-sm-5">
                                                    <div>Ms Anna</div>
                                                    <div>2 Bedok Rise #02-06</div>
                                                    <div>The Glades Singapore 469597</div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-md-8 text-end">
                                                            <div><b>Issued By:</b></div>
                                                        </div>
                                                        <div class="col-md-4 text-end">
                                                            <div>Lubie</div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8 text-end">
                                                            <div><b>Invoice No:</b></div>
                                                        </div>
                                                        <div class="col-md-4 text-end">
                                                            <div>HAI-23-000296</div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8 text-end">
                                                            <div><b>Issued Date:</b></div>
                                                        </div>
                                                        <div class="col-md-4 text-end">
                                                            <div>08-Feb-2023</div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8 text-end">
                                                            <div><b>Commence Date:</b></div>
                                                        </div>
                                                        <div class="col-md-4 text-end">
                                                            <div>10-Feb-2023</div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8 text-end">
                                                            <div><b>Contact No:</b></div>
                                                        </div>
                                                        <div class="col-md-4 text-end">
                                                            <div>82986884</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <style>
                                                .invoice-table {
                                                    height: 450px;
                                                    border-bottom: 2px solid #000;
                                                }

                                                .invoice-table thead th {
                                                    background-color: transparent;
                                                    font-size: 0.8rem;
                                                    font-weight: bold !important;
                                                    color: #000;
                                                    border-bottom: 2px solid #000;
                                                }

                                                .invoice-table tbody tr td {
                                                    border-bottom: none !important;
                                                }
                                            </style>
                                            <div class="table-responsive-sm">
                                                <table class="invoice-table table">
                                                    <thead>
                                                        <tr>
                                                            <th>PRODUCT</th>
                                                            {{-- <th>DESCRIPTION</th> --}}
                                                            <th>QTY</th>
                                                            <th>UNIT PRICE</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($priceInfo as $item)
                                                            <tr>
                                                                <td>{{ $item->service }}</td>
                                                                {{-- <td>{{$item->description}}</td> --}}
                                                                <td>{{ $item->qty }}</td>
                                                                <td>{{ $item->unit_price }}</td>
                                                                <td>{{ $item->net_total }}</td>
                                                            </tr>
                                                        @endforeach
                                                        {{-- <tr>
                                                            <td>HCRP-400</td>
                                                            <td>
                                                                <div>
                                                                    4 Hours Residential Cleaning Package For
                                                                    1
                                                                    Session a Week (4 sessions)
                                                                </div>
                                                                <div>
                                                                    Time : 830AM to 1230PM
                                                                </div>
                                                                <div>
                                                                    No of Cleaners : 1
                                                                </div>
                                                                <div>
                                                                    Every : Friday
                                                                </div>
                                                                <div>
                                                                    Cleaning Dates : 10/02, 17/02, 24/02 &
                                                                    03/03
                                                                </div>
                                                            </td>
                                                            <td>1.00</td>
                                                            <td>
                                                                <div class="d-flex justify-content-between">
                                                                    $
                                                                    <div>417.00</div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex justify-content-between">
                                                                    $
                                                                    <div>417.00</div>
                                                                </div>
                                                            </td>
                                                        </tr> --}}
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-6">
                                                    <div class="row mb-2">
                                                        <div class="col-md-4 text-start">
                                                            <div><b>Remarks:</b></div>
                                                        </div>
                                                        <div class="col-md-8 text-start">
                                                            <div></div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-md-4 text-start">
                                                            <div><b>Payment Term:</b></div>
                                                        </div>
                                                        <div class="col-md-8 text-start">
                                                            <div>C.O.D</div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-md-4 text-start">
                                                            <div><b>Bank Detail:</b></div>
                                                        </div>
                                                        <div class="col-md-8 text-start">
                                                            <div>08-Feb-2023</div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-md-4 text-start">
                                                            <div><b>Commence Date:</b></div>
                                                        </div>
                                                        <div class="col-md-8 text-start">
                                                            <div>OCBC Current: 695-163-311-001
                                                            </div>
                                                            <div>Bank Code: 7339 / Branch Code: 695</div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-md-4 text-start">
                                                            <div><b>Payment Method:</b></div>
                                                        </div>
                                                        <div class="col-md-8 text-start">
                                                            <div style="text-decoration: underline; font-size: 12px;">
                                                                "PayNow Unique Company Number(UEN) No:
                                                                201524788N"
                                                            </div>
                                                            <div>All cheques are to be crossed and made
                                                                payable
                                                                to
                                                            </div>
                                                            <div><b>"Auntie Cleaner Pte Ltd"</b></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-sm-2">
                                                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAclBMVEXMzMzQ0NDT09PLy8sAAADIyMh+fn6Li4u1tbV7e3uCgoLAwMDFxcVFRUVZWVm9vb2ioqJUVFScnJxOTk6VlZU8PDxpaWmxsbEjIyOKioooKChlZWVwcHB1dXVBQUGRkZE2NjYNDQ1JSUkvLy8cHBwTExNimPTCAAAEAElEQVR4nO3YWZuiOBiGYQJSIolhCYsKgi3V//8vTlgLl2uqZ6Zb5+C5j8DWt/NlhXIcAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA/L+I0e3dcu/c3v1yzn8I+t3csg0/wm0w3sl0O8qisVkqO4ZhG3zfstscJ8rGnGMmxyA5BEWvLzHa5UYXVb5v/b4dcZ7vB1U5tCXQnS5016TuP8pxRHoZg5pi7J0yudigg4m/C/rdRFzFQSRlcD6lQ8NObTCI1PDveRNHMiqrQ/z3nX+X44i2S6egoefkxQxByaV89Sj6clgbwjON31eYl6vl49afZX8lvEPyTd/f5jhi2wSrhedVl2AIig7FqwdxJo4bO2oi69Z97G7O452If8SrLePx6i7HEWG+XrveJpzustM3s+GPmSu8rhom0s1cr3c4z5+rdP5QlKl6nmMrTFabijh+Tr+xgxi+q8LwRz9Ls30gfOVNzQl/zlPKT/R8GWlTjleluT5ssmNOX6F0lD8FuXU+/1pe6zdNU0+bvsI2ibNd3ZbDALjV0jCvNsuJGSXJUGKZJI+b/5jjiF0VZ+dzW8ohyCzLWFXFe8ZQxId+D/Q/PvdJUdidvR8cN9kvFYZ7uXw3sCUKYQt8PCanHEcVhzGo6DvBzZcZ4J/1w8x+BSH18B97aZ1GwlWx7ofH3SfPKuxLNEFgnhU45TheW8d2d5Wpqezv3NOyg6o3Vag+mnFtKTW0WpRdayu8Ph3DYaIa82SKfuXYNTgGpV1qgzr97gqzU+vdfCD12bOzdFmHTmjW9bjxZvPs6eQhRwQmtEGX5GuWVu9Yh/EpvG2Y4+90ZHeaw9IwrVcFiWBvzP5xkj7m2AVZSLvTLJNBmjfspSLe1PeN9cNC9efhPDX9S/XVMBE1jVJNczdNn+U4fr3z7XnYqfmnl93Lx9A2LLzvVhFd+4PZ3Wynk1HNV/1N2RglhDLNzTPmsxz73TyzX/I32RQUfGYvrlB423XDxPyU1mXDcXEZzmwhdP61P5Qm6U85IZP56H/MWYKyrn9K869mDPKTxP+j9TySRzs6/sDe+WVpR0f48b7f5G2Pd0VkGyuzTbr0vCymXdTuqIV8nuOoMuiDVNrUQ9eU3dkeHkIef7z6sVSEm0O4G5ztO29UN3WbZefT9HroxLk5Zll1WA2PiuctRgSxep4jgmpvg9q6m14PvfSit1mmT+3L3w/TMPwY7WzLvKA9a52EmZx7uvyodPVx8wL87N3iLsdOhrbWWoepmr7ixzutq/DlL8CrP6eMrRXCk1Hkrl4LHBmpX/jzyl1OHxT9qyAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAC8yV8YEDZOb4QEjAAAAABJRU5ErkJggg=="
                                                        alt="">
                                                </div> --}}
                                                <div class="col-sm-4">
                                                    <div class="row">
                                                        <div class="col-md-8 text-end">
                                                            <div>Sub Total:</div>
                                                        </div>
                                                        <div class="col-md-4 text-end">
                                                            <div>
                                                                <div class="d-flex justify-content-between">
                                                                    $
                                                                    <div>0.00</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8 text-end">
                                                            <div>
                                                                Discount:
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 text-end">
                                                            <div>
                                                                <div class="d-flex justify-content-between">
                                                                    $
                                                                    <div>0.00</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8 text-end">
                                                            <div>Total:</div>
                                                        </div>
                                                        <div class="col-md-4 text-end">
                                                            <div>
                                                                <div class="d-flex justify-content-between">
                                                                    $
                                                                    <div>417.00</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8 text-end">
                                                            <div>GST @ 0%:
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 text-end">
                                                            <div>
                                                                <div class="d-flex justify-content-between">
                                                                    $
                                                                    <div>33.36</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8 text-end">
                                                            <div>Grand Total:</div>
                                                        </div>
                                                        <div class="col-md-4 text-end">
                                                            <div>
                                                                <div class="d-flex justify-content-between">
                                                                    $
                                                                    <div>0.00</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8 text-end">
                                                            <div>Deposit:</div>
                                                        </div>
                                                        <div class="col-md-4 text-end">
                                                            <div>
                                                                <div class="d-flex justify-content-between">
                                                                    $
                                                                    <div>0.00</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8 text-end">
                                                            <div>Balance:</div>
                                                        </div>
                                                        <div class="col-md-4 text-end">
                                                            <div>
                                                                <div class="d-flex justify-content-between">
                                                                    $
                                                                    <div>0.00</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <h3><b>Terms and Conditions</b></h3>
                                                <ol class="ps-4">
                                                    @foreach ($termCondition as $item)
                                                        <li>{{ $item->term_condition }}</li>
                                                    @endforeach
                                                    {{-- <li>
                                                        Goods or services sold are not refundable
                                                    </li>
                                                    <li>
                                                        Prices, Terms and Conditions are subjected to
                                                        alteration without prior notice
                                                    </li>
                                                    <li>
                                                        Deposits paid are not refundable or exchangeable
                                                        upon
                                                        cancellation
                                                    </li>
                                                    <li>
                                                        Minimum of 4 hours per cleaning visit applies
                                                    </li>
                                                    <li>
                                                        Clients are to provide all cleaning materials and
                                                        products
                                                    </li>
                                                    <li>
                                                        Any additional cleaning required will be subjected
                                                        to
                                                        charges of $30 per hour for each cleaner
                                                    </li>
                                                    <li>
                                                        For any additional cleaning that requires an
                                                        extension
                                                        in the number of cleaning hours will be subjected to
                                                        cleaner’s availability
                                                    </li>
                                                    <li>
                                                        Our liability of loss or damage if any shall not
                                                        exceed
                                                        $200 or 50% of the cost price, whichever is lower
                                                    </li>
                                                    <li>
                                                        All invoices are to be settled within 30days,
                                                        otherwise
                                                        a monthly interest of 5% on the invoice value will
                                                        be
                                                        levied on the said overdue account
                                                    </li>
                                                    <li>
                                                        Cancellation must be made at least 3 working days in
                                                        advance, any last minute cancellation will result in
                                                        one session being forfeited.
                                                    </li>
                                                    <li>
                                                        It is the client’s responsibility to ensure that
                                                        valuables are locked before the cleaning session
                                                        commences
                                                    </li>
                                                    <li>
                                                        Please inform us within 24 hours should there be any
                                                        concerns with regards to our services
                                                    </li> --}}
                                                </ol>
                                                <p>We thank you for choosing {{ $companyDetail->company_name }}.</p>
                                            </div>
                                            <div class="row">
                                                {{-- <div class="col-sm-12">
                                                    <h3><b>This is a computer generated invoice therefore no
                                                            signature required.</b>
                                                    </h3>
                                                    <div class="d-flex footer-logo justify-content-between">
                                                        <div class="img">
                                                            <img src="dist/img/logo.png" alt="logo">
                                                        </div>
                                                        <div class="img">
                                                            <img src="dist/img/invoice-logo/logo-1.png"
                                                                alt="logo">
                                                        </div>
                                                        <div class="img">
                                                            <img src="dist/img/invoice-logo/logo-2.png"
                                                                alt="logo">
                                                        </div>
                                                        <div class="img">
                                                            <img src="dist/img/invoice-logo/logo-3.png"
                                                                alt="logo">
                                                        </div>
                                                        <div class="img">
                                                            <img src="dist/img/invoice-logo/logo-4.jpg"
                                                                alt="logo">
                                                        </div>
                                                        <div class="img">
                                                            <img src="dist/img/invoice-logo/logo-5.png"
                                                                alt="logo">
                                                        </div>
                                                        <div class="img">
                                                            <img src="dist/img/invoice-logo/logo-6.png"
                                                                alt="logo">
                                                        </div>
                                                        <div class="img">
                                                            <img src="dist/img/invoice-logo/logo-7.png"
                                                                alt="logo">
                                                        </div>
                                                        <div class="img">
                                                            <img src="dist/img/invoice-logo/logo-8.png"
                                                                alt="logo">
                                                        </div>
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Include optional progressbar HTML -->
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </form>
    </div>
    <!-- <div class="modal-footer">
<button type="button" class="btn me-auto sw-btn-prev sw-btn">Previous</button>
<button type="button" class="btn btn-primary next-btn" >Next</button>
</div> -->
</div>

<script>
    $('#smartwizard').smartWizard({
        transition: {
            animation: 'slideHorizontal', // Effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
        }
    });

    function closeModal() {

        $("#smartwizard").smartWizard("reset");
    }
</script>
