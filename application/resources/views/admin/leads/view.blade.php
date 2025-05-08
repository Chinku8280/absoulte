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

    .btn.selected-option {
        border: 2px solid #053ef8;
    }
</style>

<div class="modal-header">
    <h5 class="modal-title">View Lead</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="closeModal()" aria-label="Close"></button>
</div>
<div class="modal-body">
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
                    <div class="col-md-12">
                        <div class="d-flex" style="justify-content: space-between; align-items: center;">
                            <h5 class="modal-title">Customer Details</h5>
                        </div>

                        <div class="card mt-3" id="residential-card" id="residential-card">

                            @if ($customer->customer_type == 'residential_customer_type')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="mb-0" for=""> <b>Customer Name</b> </label>
                                            <p class="m-0">{{ $customer->customer_name }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="mb-0" for=""><b>Mobile Number</b> </label>
                                            <p class="m-0">{{ $customer->mobile_number }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="mb-0" for=""> <b>Email</b></label>
                                            <p class="m-0">{{ $customer->email }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="mb-0" for=""><b>Language Spoken</b> </label>
                                            <p class="m-0">{{ $customer->language_name }}</p>
                                        </div>

                                    </div>
                                    <div class="row mt-3">
                                        <!-- <div class="col-md-3">
                                            <label class="mb-0" for=""> <b>Select Type</b> </label>
                                            <p class="m-0">Floor Cleaning</p>
                                            </div> -->
                                        <div class="col-md-6">
                                            <label class="mb-0" for=""><b>Customer Remark</b> </label>
                                            <p class="m-0">
                                                {{-- {{ $customer->customer_remark }} --}}
                                                @php
                                                    $cust_remark_arr = explode(PHP_EOL, $customer->customer_remark);
                                                @endphp

                                                @foreach ($cust_remark_arr as $list)
                                                    {{ $list }} <br>
                                                @endforeach
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="mb-0" for=""><b>Status</b> </label>
                                            <p>
                                                @if ($customer->status == 0)
                                                    <span class="badge bg-red"> Blocked </span>
                                                @elseif ($customer->status == 1)
                                                    <span class="badge bg-green"> Active </span>
                                                @elseif ($customer->status == 2)
                                                    <span class="badge bg-yellow"> Inactive </span>
                                                @else
                                                    <span class="badge bg-grey"> Unknown </span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            @endif
                        </div>
                        <div class="card mt-3 customer-card commercial-card" id="commercial-card"
                            style="display: {{ $customer->customer_type === 'commercial_customer_type' ? 'block' : 'none' }};">

                            @if ($customer->customer_type === 'commercial_customer_type')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="mb-0" for=""> <b>Customer Name</b> </label>
                                            <p class="m-0">{{ $customer->customer_name }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="mb-0" for=""> <b>UEN</b></label>
                                            <p class="m-0">{{ $customer->uen }}</p>
                                        </div>
                                        {{-- <div class="col-md-3 mb-3">
                                            <label class="mb-0" for=""> <b>Group Company Name</b>
                                            </label>
                                            <p class="m-0">{{ $customer->group_company_name }}</p>
                                        </div> --}}
                                        <div class="col-md-3 mb-3">
                                            <label class="mb-0" for=""><b>Company Name</b>
                                            </label>
                                            <p class="m-0">{{ $customer->individual_company_name }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="mb-0" for=""><b>Language Spoken</b> </label>
                                            <p class="m-0">{{ $customer->language_name }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="mb-0" for=""><b>Person Incharge</b> </label>
                                            <p class="m-0">{{ $customer->person_incharge_name }}</p>
                                        </div>
                                        <div class="col-md-3 ">
                                            <label class="mb-0" for=""> <b>Phone No</b> </label>
                                            <p class="m-0">{{ $customer->mobile_number }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="mb-0" for=""><b>Customer Remark</b> </label>
                                            <p class="m-0">
                                                {{-- {{ $customer->customer_remark }} --}}
                                                @php
                                                    $cust_remark_arr = explode(PHP_EOL, $customer->customer_remark);
                                                @endphp

                                                @foreach ($cust_remark_arr as $list)
                                                    {{ $list }} <br>
                                                @endforeach
                                            </p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="mb-0" for=""><b>Status</b> </label>
                                            <p>
                                                @if ($customer->status == 0)
                                                    <span class="badge bg-red">Block </span>
                                                @elseif($customer->status == 1)
                                                    <span class="badge bg-green">Active </span>
                                                @elseif($customer->status == 2)
                                                    <span class="badge bg-yellow"> </span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
                <div class="row">
                    <div class="col-md-12 pe-0">
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
                                                <th>Unit Price</th>
                                                <th>Qty</th>
                                                <th>TOTAL SESSION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($service as $value)
                                                <tr>
                                                    <td hidden>{{ $value->service_id }}</td>
                                                    <td>{{ $value->product_code }}</td>
                                                    <td>{{ $value->name }}</td>
                                                    <td>
                                                        @php
                                                            $description_arr = explode(PHP_EOL, $value->description);
                                                        @endphp

                                                        @foreach ($description_arr as $list)
                                                            {{ $list }} <br>
                                                        @endforeach
                                                    </td>
                                                    <td>${{ number_format($value->unit_price, 2) }}</td>
                                                    <td>{{ $value->quantity }}</td>
                                                    <td>{{ $value->total_session }}</td>
                                                </tr>
                                            @endforeach
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
                    <div class="col-md-12">
                        <div class="row mt-3">
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 text-start">
                                <label for="message-text" class="col-form-label">Date Of Cleaning</label>
                                <input type="text" id="date_of_cleaning" name="date_of_cleaning"
                                    class="form-control"
                                    value="{{ $lead->schedule_date ? date('d-m-Y', strtotime($lead->schedule_date)) : '' }}"
                                    required>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 text-start">
                                <label for="message-text" id="time_of_cleaning" name="time_of_cleaning"
                                    class="col-form-label">Time of Cleaning</label>
                                <input type="time" class="form-control" value="{{ $lead->time_of_cleaning }}"
                                    readonly placeholder="Time of Cleaning">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
                <div class="row">
                    <div class="col-md-12 pe-0">
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
                                                <div class="card-content-wrapper service_address">
                                                    <span class="check-icon"></span>
                                                    <div class="card-content">
                                                        <h4>Service Address</h4>
                                                        <p class="mb-1"> <strong>Contact
                                                                No:</strong>{{ $service_address->contact_no }}</p>
                                                        <p class="mb-1"> <strong>Email
                                                                ID:</strong>{{ $service_address->email_id }}</p>
                                                        <p class="mb-1"><strong>Address:</strong>
                                                            {{ $service_address->address }}</p>
                                                        <p class="mb-1"><strong>Unit No:</strong>
                                                            {{ $service_address->unit_number }}</p>
                                                        <p class="mb-1"><strong>Zone:</strong>
                                                            {{ $service_address->zone }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-two" role="tabpanel">
                                        <div class="row my-3 billing_address_row">
                                            <div class="row" id="billing-addresses">
                                                <div class="col-lg-4 col-md-4 col-sm-12">
                                                    <div class="card-content-wrapper billing_address">
                                                        <div class="card-content">
                                                            <h4>Billing Address</h4>
                                                            <p class="mb-1">
                                                                <strong>Address:</strong>{{ $billing_address->address }}
                                                            </p>
                                                            <p class="mb-1"><strong>Unit
                                                                    No:</strong>{{ $billing_address->unit_number }}
                                                            </p>
                                                            <p class="mb-1">
                                                                <strong>Zone:</strong>{{ $billing_address->zone }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
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
                                <table class="table priceInfoTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">sl.no.</th>
                                            <th scope="col">PRODUCT CODE</th>
                                            <th scope="col">service/Products</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Unit Price</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">Sub Total(SGD)</th>
                                            <th scope="col">Discount(%)</th>
                                            <th scope="col">Net Total(SGD)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="addNewFeild">
                                        @foreach ($service as $key => $item)
                                            @php
                                                $sub_total = $item->unit_price * $item->quantity;
                                                $discount = $sub_total * ($item->discount / 100);
                                                $net_total = $sub_total - $discount;
                                            @endphp

                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $item->product_code }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>
                                                    @php
                                                        $description_arr = explode(PHP_EOL, $item->description);
                                                    @endphp

                                                    @foreach ($description_arr as $list)
                                                        {{ $list }} <br>
                                                    @endforeach
                                                </td>
                                                <td>{{ number_format($item->unit_price, 2) }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ number_format($sub_total, 2) }}</td>
                                                <td>{{ $item->discount }}</td>
                                                <td>{{ number_format($net_total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <table class="mt-3" style="width: 35%; float: right;">
                            <tbody>
                                <tr>
                                    <td style="width: 40%;">Sub Total :</td>
                                    <td id="pi_subtotal">${{ number_format($lead->subtotal, 2) }}</td>
                                </tr>

                                <tr>
                                    <td style="width: 40%;">Net Total : </td>
                                    <td id="pi_nettotal">${{ number_format($lead->amount, 2) }}</td>
                                </tr>

                                @if ($lead->discount_type == 'percentage')
                                    <tr>
                                        <td style="width: 40%;" id="pi_disc_label">Percentage Discount(%) :</td>
                                        <td id="pi_discount">{{ $lead->discount }}%</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td style="width: 40%;" id="pi_disc_label">Amount Discount :</td>
                                        <td id="pi_discount">{{ number_format($lead->discount, 2) }}</td>
                                    </tr>
                                @endif

                                <tr>
                                    <td style="width: 40%;">Tax <span
                                            id="pi_tax">({{ $lead->tax_percent }}%)</span></td>
                                    <td id="pi_taxamt">${{ number_format($lead->tax, 2) }}</td>
                                </tr>
                                <tr>
                                    <td style="width: 40%;">Grand Total</td>
                                    <td id="pi_grandtotal">${{ number_format($lead->grand_total, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-link card-link-pop">
                            <div class="card-status-start bg-primary"></div>
                            <div class="card-stamp">
                                <div class="card-stamp-icon bg-white text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-map-pin" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                        <path
                                            d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="page-body">
                                <div class="container-xl">
                                    <div class="card card-lg" id="invoice-card">

                                        <div class="card-body">
                                            <div class="d-flex mb-2">
                                                <div class="logo p-0 text-center">
                                                    {{-- <img src="{{$imagePath}}" alt=""
                                                        class="img-fluid" style="height: 100px;"> --}}
                                                    <div class="img">
                                                        <img src="{{ asset($imagePath) }}" alt="logo"
                                                            style="background-color:black; max-width:100px; height: 100px;">
                                                    </div>
                                                </div>
                                                <div class="company-dece pe-0 ps-3">
                                                    <h1 class="title" style="font-size: 26px;">
                                                        <b>{{ $company ? $company->company_name : '' }}</b>
                                                    </h1>
                                                    <p class="m-0 fs-12 lh-13">
                                                        {{ $company ? $company->company_address : '' }}</p>
                                                    <p class="m-0 fs-12 lh-13">Tel:
                                                        {{ $company ? $company->contact_number : '' }} Fax:
                                                        {{ $company ? $company->contact_number : '' }} Phone:
                                                        {{ $company ? $company->contact_number : '' }}</p>
                                                    <p class="m-0 fs-12 lh-13">Website:
                                                        {{ $company ? $company->website : '' }}</p>
                                                    <p class="m-0 fs-12 lh-13">
                                                        Co. Reg No: {{ $company ? $company->co_register_no : '' }}
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        GST Reg No. {{ $company ? $company->gst_register_no : '' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-8"></div>
                                                <div class="col-4 text-center margin-left: 68%;">
                                                    @if (!empty($lead->invoice_no))
                                                        <h3>Tax Invoice</h3>
                                                    @else
                                                        <h3>QUOTATION</h3>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mb-3">

                                                <div class="col-1 col-sm-1 text-center">
                                                    <h4 class="mb-3 fs-12 lh-13"><b>To:</b></h4>
                                                </div>
                                                <div class="col-4 col-sm-4 ps-0">
                                                    <div class="fs-12 lh-13"> {{ $customer->individual_company_name }}
                                                    </div>
                                                    <div class="fs-12 lh-13"> {{ $customer->customer_name }}</div>
                                                    <div class="fs-12 lh-13"> {{ $service_address->address }}</div>
                                                    <div class="fs-12 lh-13">+65 {{ $customer->mobile_number }}</div>
                                                    <div class="fs-12 lh-13"> {{ $customer->email }}</div>
                                                </div>

                                                <div class="col-7 col-sm-7">
                                                    @if (!empty($lead->invoice_no))
                                                        <div class="row">
                                                            <div class="col-8 col-md-8 text-end">
                                                                <div class="fs-12 lh-13"><b>Invoice No:</b></div>
                                                            </div>
                                                            <div class="col-4 col-md-4 text-start">
                                                                <div class="fs-12 lh-13">{{ $lead->invoice_no }}</div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="row">
                                                            <div class="col-8 col-md-8 text-end">
                                                                <div class="fs-12 lh-13"><b>Quotation No:</b></div>
                                                            </div>
                                                            <div class="col-4 col-md-4 text-start">
                                                                <div class="fs-12 lh-13">
                                                                    {{ $lead->quotation_no ?? '' }}</div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="row">
                                                        <div class="col-8 col-md-8 text-end">
                                                            <div class="fs-12 lh-13"><b>Issued Date:</b></div>
                                                        </div>
                                                        <div class="col-4 col-md-4 text-start">
                                                            <div class="fs-12 lh-13">
                                                                {{ date('d-m-Y', strtotime($lead->created_at)) }}</div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-8 col-md-8 text-end">
                                                            <div class="fs-12 lh-13"><b>Issued By:</b></div>
                                                        </div>
                                                        <div class="col-4 col-md-4 text-start">
                                                            <div class="fs-12 lh-13">{{ $lead->created_by_name }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- <div class="row">
                                                        <div class="col-8 col-md-8 text-end">
                                                            <div class="fs-12 lh-13"><b>Service Sheet No</b></div>
                                                        </div>
                                                        <div class="col-4 col-md-4 text-end">
                                                            <div class="fs-12 lh-13">AC-SC-0120455</div>
                                                        </div>
                                                    </div> --}}

                                                    @if (!empty($lead->schedule_date))
                                                        <div class="row">
                                                            <div class="col-8 col-md-8 text-end">
                                                                <div class="fs-12 lh-13"><b>Service Date:
                                                                    </b></div>
                                                            </div>
                                                            <div class="col-4 col-md-4 text-start">
                                                                <div class="fs-12 lh-13">
                                                                    {{ $lead->schedule_date ? date('d-m-Y', strtotime($lead->schedule_date)) : '' }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    {{-- <div class="row">
                                                        <div class="col-8 col-md-8 text-end">
                                                            <div class="fs-12 lh-13"><b>Team:
                                                                </b></div>
                                                        </div>
                                                        <div class="col-4 col-md-4 text-end">
                                                            <div class="fs-12 lh-13">AC 2, Lin Lin</div>
                                                        </div>
                                                    </div> --}}

                                                </div>

                                            </div>

                                            <div class="table-responsive-sm">
                                                <table class="invoice-table table">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl. No.</th>
                                                            <th>SERVICE</th>
                                                            <th>DESCRIPTION</th>
                                                            <th>QTY</th>
                                                            <th>UNIT PRICE</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($service as $key => $item)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <!-- Use $key + 1 for 1-based serial number -->
                                                                <td>{{ $item->name }}</td>
                                                                <td>
                                                                    @if ($key == 0)                                                                                                                                           
                                                                        @php
                                                                            $description_arr = explode(
                                                                                PHP_EOL,
                                                                                $item->description,
                                                                            );
                                                                        @endphp

                                                                        @foreach ($description_arr as $list)
                                                                            {{ $list }} <br>
                                                                        @endforeach
                                                                    @endif
                                                                </td>
                                                                <td>{{ $item->quantity }}</td>
                                                                <td>{{ number_format($item->unit_price, 2) }}</td>
                                                                <td>{{ number_format($item->gross_amount, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-7 col-sm-7">
                                                    <div class="row mb-2">
                                                        <div class="col-3 col-md-3 text-start">
                                                            <div class="fs-12 lh-13"><b>Remarks:</b></div>
                                                        </div>
                                                        <div class="col-8 col-md-8 text-start">
                                                            <div>
                                                                {{-- {{ $customer->customer_remark }} --}}
                                                                {{-- @php
                                                                    $cust_remark_arr = explode(
                                                                        PHP_EOL,
                                                                        $customer->customer_remark,
                                                                    );
                                                                @endphp

                                                                @foreach ($cust_remark_arr as $list)
                                                                    {{ $list }} <br>
                                                                @endforeach --}}

                                                                @php
                                                                    $remark_arr = explode(
                                                                        PHP_EOL,
                                                                        $lead->remarks,
                                                                    );
                                                                @endphp

                                                                @foreach ($remark_arr as $list)
                                                                    {{ $list }} <br>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-3 col-md-3 text-start">
                                                            <div class="fs-10 lh-13"><b>Payment Term:</b></div>
                                                        </div>
                                                        <div class="col-9 col-md-9 text-start">
                                                            <div class="fs-10 lh-13">
                                                                {{ $customer->payment_terms_value }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-3 col-md-3 text-start">
                                                            <div class="fs-10 lh-13"><b>Bank Detail:</b></div>
                                                        </div>
                                                        <div class="col-9 col-md-9 text-start">
                                                            <div class="fs-10 lh-13">
                                                                {{ $company ? $company->bank_name : '' }} Current:
                                                                {{ $company ? $company->ac_number : '' }}</div>
                                                            <div class="fs-10 lh-13">Bank Code:
                                                                {{ $company ? $company->bank_code : '' }} / Branch
                                                                Code:
                                                                {{ $company ? $company->branch_code : '' }}</div>
                                                            {{-- <div class="fs-10 lh-13">OCBC Current: 686-026980-001</div>
                                                            <div class="fs-10 lh-13">Bank Code: 7339 / Branch Code: 686</div> --}}
                                                        </div>
                                                    </div>
                                                    {{-- <div class="row mb-2">
                                                        <div class="col-3 col-md-3 text-start">
                                                            <div class="fs-10 lh-13"><b>Commence Date:</b></div>
                                                        </div>
                                                        <div class="col-9 col-md-9 text-start">
                                                            <div class="fs-10 lh-13">OCBC Current: 695-163-311-001
                                                            </div>
                                                            <div class="fs-10 lh-13">Bank Code: 7339 / Branch Code:
                                                                695</div>
                                                        </div>
                                                    </div> --}}
                                                    <div class="row mb-2">
                                                        <div class="col-3 col-md-3 text-start">
                                                            <div class="fs-10 lh-13"><b>Payment Method:</b></div>
                                                        </div>
                                                        <div class="col-9 col-md-9 text-start">
                                                            <div class="fs-10 lh-13"
                                                                style="text-decoration: underline;">
                                                                {{-- <span>PayNow</span> --}}
                                                                Unique Company Number(UEN) No:
                                                                {{ $company ? $company->uen_no : '' }}
                                                            </div>
                                                            <div class="fs-10 lh-13">All cheques are to be crossed and
                                                                made payable to
                                                            </div>
                                                            <div class="fs-10 lh-13"
                                                                style="text-decoration: underline;">
                                                                <b>{{ $company ? $company->company_name : '' }}</b>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-2 col-sm-2">

                                                </div>
                                                <div class="col-3 col-sm-3">
                                                    <div class="row">
                                                        <div class="col-8 col-md-8 text-end">
                                                            <div class="fs-10 lh-13">Sub Total:</div>
                                                        </div>
                                                        <div class="col-4 col-md-4 text-end">
                                                            <div>
                                                                <div
                                                                    class="d-flex justify-content-between fs-10 lh-13">
                                                                    $<div>{{ number_format($lead->amount, 2) }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-8 col-md-8 text-end">
                                                            <div class="fs-10 lh-13">
                                                                Discount:
                                                            </div>
                                                        </div>
                                                        <div class="col-4 col-md-4 text-end">
                                                            <div>
                                                                <div
                                                                    class="d-flex justify-content-between fs-10 lh-13">
                                                                    $<div>{{ number_format($lead->discount_amt, 2) }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-8 col-md-8 text-end">
                                                            <div class="fs-10 lh-13">Total:</div>
                                                        </div>
                                                        <div class="col-4 col-md-4 text-end">
                                                            <div>
                                                                <div
                                                                    class="d-flex justify-content-between fs-10 lh-13">
                                                                    $<div>{{ number_format($lead->total, 2) }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-8 col-md-8 text-end">
                                                            <div class="fs-10 lh-13">GST @ <span
                                                                    id="preview_tax">{{ $lead->tax_percent }}</span>%:
                                                            </div>
                                                        </div>
                                                        <div class="col-4 col-md-4 text-end">
                                                            <div>
                                                                <div
                                                                    class="d-flex justify-content-between fs-10 lh-13">
                                                                    $<div id="preview_tax_amt">{{ number_format($lead->tax, 2) }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-8 col-md-8 text-end">
                                                            <div class="fs-10 lh-13">Grand Total:</div>
                                                        </div>
                                                        <div class="col-4 col-md-4 text-end">
                                                            <div>
                                                                <div
                                                                    class="d-flex justify-content-between fs-10 lh-13">
                                                                    $<div id="preview_grand_total">
                                                                        {{ number_format($lead->grand_total, 2) }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="row" id="preview_deposit_amount_group">
                                                        <div class="col-8 col-md-8 text-end">
                                                            <div class="fs-10 lh-13">Deposit:</div>
                                                        </div>
                                                        <div class="col-4 col-md-4 text-end">
                                                            <div>
                                                                <div
                                                                    class="d-flex justify-content-between fs-10 lh-13">
                                                                    $<div id="preview_deposit_amount">{{$lead->deposit}}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" id="preview_balance_amount_group">
                                                        <div class="col-8 col-md-8 text-end">
                                                            <div class="fs-10 lh-13">Balance:</div>
                                                        </div>
                                                        <div class="col-4 col-md-4 text-end">
                                                            <div>
                                                                <div
                                                                    class="d-flex justify-content-between fs-10 lh-13">
                                                                    $<div id="preview_balance_amount">{{$lead->balance}}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                </div>
                                            </div>
                                            <div class="row  terms-row">
                                                <h3><b>Terms and Conditions</b></h3>
                                                <ul style="list-style-type: none;">
                                                    @foreach ($term_condition as $item)
                                                        @php
                                                            $term_arr = explode(PHP_EOL, $item->term_condition);
                                                        @endphp

                                                        @foreach ($term_arr as $list)
                                                            <li>{{ $list }}</li>
                                                        @endforeach
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <br><br>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h3><b>This is a computer generated invoice therefore no
                                                            signature required.</b>
                                                    </h3>
                                                    <div class="d-flex footer-logo justify-content-between">
                                                        <div class="img">
                                                            <img src="{{ asset($imagePath) }}" alt="logo"
                                                                style="background-color:black; max-width:100px; height: 100px;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if (!empty($lead->reject_remarks))
                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-md-12">
                                                        <h3>Reject Remarks</h3>
                                                        <div>{{ $lead->reject_remarks }}</div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if (!$lead_payment_detail->isEmpty())
                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-md-12">
                                                        <h3>Payment Details</h3>
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Payment Method</th>
                                                                    <th>Payment Amount</th>
                                                                    <th>Payment Proof</th>
                                                                    <th>Payment Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($lead_payment_detail as $item)
                                                                    <tr>
                                                                        <td>{{ $item->new_payment_method }}</td>
                                                                        <td>${{ number_format($item->payment_amount, 2) }}</td>
                                                                        <td>
                                                                            @foreach ($item->payment_proof_details as $list)
                                                                                @if (!empty($list->payment_proof))
                                                                                    <a data-fancybox="gallery" href="{{ asset('application/public/uploads/payment_proof/' . $list->payment_proof) }}">
                                                                                        <img src="{{ asset('application/public/uploads/payment_proof/' . $list->payment_proof) }}"
                                                                                            alt="" width="100px;">
                                                                                    </a>
                                                                                @endif
                                                                            @endforeach
                                                                        </td>
                                                                        <td>
                                                                            @if ($item->payment_status == 2)
                                                                                <span class="badge bg-danger">Rejected</span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            @endif
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
    <!-- Include optional progressbar HTML -->
    <div class="progress">
        <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0"
            aria-valuemax="100"></div>
    </div>
</div>

<script>
    $('#smartwizard').smartWizard({
        transition: {
            animation: 'slideHorizontal',
        }
    });

    function closeModal() {
        $("#smartwizard").smartWizard("reset");
    }
</script>
