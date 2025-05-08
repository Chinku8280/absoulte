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
    <h5 class="modal-title">Lead Payment</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form class="row text-left" id="lead_form" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="leadId" value="{{ $leadId }}">
        <input type="hidden" id="customer_id_lead" name="customer_id" value="{{ $lead->customer_id }}">
        <input type="hidden" id="service_id_lead" name="service_address" value="{{ $lead->service_address }}">
        <input type="hidden" id="billing_id_lead" name="billing_address" value="{{ $lead->billing_address }}">
        <input type="hidden" id="selected_date" name="schedule_date" value="{{ $lead->schedule_date }}">
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

        <input type="hidden" name="company_id" id="company_id" value="{{ $lead->company_id }}">

        <input type="hidden" name="email_template_id" id="form_email_template_id">
        <input type="hidden" name="email_to" id="form_email_to">
        <input type="hidden" name="email_cc" id="form_email_cc">
        <input type="hidden" name="email_bcc" id="form_email_bcc">
        <input type="hidden" name="email_subject" id="form_email_subject">
        <input type="hidden" name="email_body" id="form_email_body">

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
                    <a class="nav-link" href="#step-7">
                        <span class="num">6</span>
                        Payment
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="#step-4">
                        <span class="num">7</span>
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
                                                <p class="m-0">{{ $customer->customer_remark }}
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
                                            <div class="col-md-3 mb-3">
                                                <label class="mb-0" for=""> <b>Group Company Name</b>
                                                </label>
                                                <p class="m-0">{{ $customer->group_company_name }}</p>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="mb-0" for=""><b>Individual Company Name</b>
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
                                                <p class="m-0">{{ $customer->customer_remark }}</p>
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
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($service as $value)
                                                    <tr>
                                                        <td hidden>{{ $value->service_id }}</td>
                                                        <td>{{ $value->product_code }}</td>
                                                        <td>{{ $value->name }}</td>
                                                        <td>{{ $value->description }}</td>
                                                        <td>${{ $value->unit_price }}</td>
                                                        <td>{{ $value->quantity }}</td>
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
                                        value="{{ date('d-m-Y', strtotime($lead->schedule_date)) }}" required>
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
                                                    <td>{{ $item->description }}</td>
                                                    <td>{{ $item->unit_price }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>{{ $sub_total }}</td>
                                                    <td>{{ $item->discount }}</td>
                                                    <td>{{ $net_total }}</td>
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
                                        <td id="pi_subtotal">${{ $lead->subtotal }}</td>
                                    </tr>

                                    <tr>
                                        <td style="width: 40%;">Net Total : </td>
                                        <td id="pi_nettotal">${{ $lead->amount }}</td>
                                    </tr>

                                    @if ($lead->discount_type == 'percentage')
                                        <tr>
                                            <td style="width: 40%;" id="pi_disc_label">Percentage Discount(%) :</td>
                                            <td id="pi_discount">{{ $lead->discount }}%</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td style="width: 40%;" id="pi_disc_label">Amount Discount :</td>
                                            <td id="pi_discount">{{ $lead->discount }}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td style="width: 40%;">Tax <span
                                                id="pi_tax">({{ $lead->tax_percent }}%)</span></td>
                                        <td id="pi_taxamt">${{ $lead->tax }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 40%;">Grand Total</td>
                                        <td id="pi_grandtotal">${{ $lead->grand_total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="step-7" class="tab-pane" role="tabpanel" aria-labelledby="step-7">
                    <div class="row">

                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Payment Amount</h3>
                                </div>
                                <div class="card-body">
                                    <div class="col-md-6">
                                        <label class="form-label" for="totalAmount">Total Payble Amount($): <span
                                                id="totalAmount">{{$lead->grand_total}}</span></label>
                                        <input type="hidden" value="{{$lead->grand_total}}" name="total_amount">
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <a href="#" class="btn btn-dark" id="payment_advance">
                                                <label class="form-check-label" for="payment_advance">
                                                    Advance Payment
                                                </label>
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="#" class="btn btn-success" id="payment_full">
                                                <label class="form-check-label" for="payment_full">
                                                    Full Payment
                                                </label>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row" style="display: none;" id="payment_advance_feild">
                                        <div class="col-md-3">
                                            <label class="form-label" for="persentage">Percentage(%)</label>
                                            <input class="form-control" type="text" name="persentage"
                                                id="persentage" oninput="calculateAmount()">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="amount">Amount($)</label>
                                            <input class="form-control" type="text" name="advance_amount"
                                                id="amount" oninput="calculatepercentage()">
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-3">
                                        <h5>Balance Amount : $0.00 </h5>
                                    </div> --}}
                                    <div class="col-md-9">
                                        <h5>
                                            Payment method :
                                        </h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input payment_option" type="radio"
                                                    name="payment_option" value="Asia Pay" id="asia_pay"
                                                    onchange="getAsiaOPtions()">
                                                <label class="form-check-label" for="asia_pay">
                                                    Asia Pay
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input payment_option" type="radio"
                                                    name="payment_option" value="Offline" id="offline_pay">
                                                <label class="form-check-label" for="offline_pay">
                                                    Offline
                                                </label>
                                            </div>
                                        </div>                                      
                                    </div>
                                    <div class="responsive-table">
                                        <table class="table table-success table-striped border">

                                            <tbody style="display: none;" id="offline_pay_option">
                                                @foreach ($offlineOptions as $options)
                                                    <tr>
                                                        <td>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="payment_option_checkbox[]"
                                                                    value="{{ $options->payment_option }}"
                                                                    id="{{ $options->payment_option }}"
                                                                    @if ($options->payment_option === 'Pay Now') checked @endif>
                                                                <label class="form-check-label"
                                                                    for="{{ $options->payment_option }}">
                                                                    {{ $options->payment_option }}
                                                                </label>
                                                            </div>

                                                        </td>
                                                        <td></td>
                                                        <td><input class="form-control" type="number"
                                                                name="payment_amount_checkbox[]"></td>
                                                        <td><input class="form-control" type="file"
                                                                name="payment_proof[]"></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tbody style="display: none;" id="asia_pay_option">
                                                @foreach ($asiaOptions as $options)
                                                    <tr>
                                                        <td>
                                                            <div class="form-check">
                                                                {{-- <input class="form-check-input" type="radio"
                                                                    name="payment_option[]"
                                                                    value="{{ $options->payment_option }}"
                                                                    id=""> --}}
                                                                <label class="form-check-label" for="">
                                                                    {{ $options->payment_option }}
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                        <td><input class="form-control" type="number"
                                                                id="payableAmount" name="payment_amount" readonly>
                                                        </td>
                                                        {{-- <td><input class="form-control" type="file"
                                                                name="payment_proof[]"></td> --}}
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                            <i class="fa-solid fa-user me-2"
                                                style="font-size: 14px;"></i>{{ $customer->customer_name }}
                                        </p>
                                        <p class="card-p d-flex align-items-center mb-2 ">
                                            <i class="fa-solid fa-phone me-2"
                                                style="font-size: 14px;"></i>{{ $customer->mobile_number }}
                                        </p>
                                        <p class="card-p  d-flex align-items-center mb-2">
                                            <i class="fa-solid fa-envelope me-2"
                                                style="font-size: 14px;"></i>{{ $customer->email }}
                                        </p>
                                        {{-- <p class="card-p d-flex mb-2">
                                            <i class="fa-solid fa-map-pin me-2 pt-1" style="font-size: 14px;"></i>
                                            103 Rasadhi Appartment Wadaj Ahmedabad 380004.
                                        </p> --}}
                                        {{-- <p class="card-p d-flex mb-2">
                                            Total Spend : $0.00
                                        </p> --}}
                                        <hr class="my-3">
                                    </div>
                                </div>
                            </div>

                            <div class="card" id="attach_invoice_group" style="display: none;">
                                <div class="card-body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            name="check_attach_invoice" value="yes" id="check_attach_invoice">
                                        <label class="form-check-label" for="check_attach_invoice">
                                            Attach Invoice
                                        </label>
                                    </div>
                                </div>
                            </div>
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
                                            class="icon icon-tabler icon-tabler-map-pin" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
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
                                                            <img src="{{asset($imagePath)}}" alt="logo"
                                                                style="background-color:black; max-width:100px; height: 100px;">
                                                        </div>
                                                    </div>
                                                    <div class="company-dece pe-0 ps-3">
                                                        <h1 class="title" style="font-size: 26px;"><b>{{($company)?$company->company_name:''}}</b></h1>
                                                        <p class="m-0 fs-12 lh-13">{{($company)?$company->company_address:''}}</p>
                                                        <p class="m-0 fs-12 lh-13">Tel: {{($company)?$company->contact_number:''}} Fax: {{($company)?$company->contact_number:''}} Phone: {{($company)?$company->contact_number:''}}</p>
                                                        <p class="m-0 fs-12 lh-13">Website: {{($company)?$company->website:''}}</p>
                                                        <p class="m-0 fs-12 lh-13">
                                                            Co. Reg No: {{($company)?$company->co_register_no:''}}
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            GST Reg No. {{($company)?$company->gst_register_no:''}}
                                                        </p>
                                                    </div>         
                                                </div>
                                                <div class="row">
                                                    <div class="col-8"></div>
                                                    <div class="col-4 text-center">
                                                        <h3>Tax Invoice</h3>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">

                                                    <div class="col-1 col-sm-1 text-center">
                                                        <h4 class="mb-3 fs-12 lh-13"><b>To:</b></h4>
                                                    </div>
                                                    <div class="col-4 col-sm-4 ps-0">
                                                        <div class="fs-12 lh-13"> {{$customer->individual_company_name}}</div>
                                                        <div class="fs-12 lh-13"> {{$customer->customer_name}}</div>
                                                        <div class="fs-12 lh-13"> {{$service_address->address}}</div>
                                                        <div class="fs-12 lh-13">+65 {{$customer->mobile_number}}</div>
                                                        <div class="fs-12 lh-13"> {{$customer->email}}</div>
                                                    </div>

                                                    <div class="col-7 col-sm-7">
                                                        <div class="row">
                                                            <div class="col-8 col-md-8 text-end">
                                                                <div class="fs-12 lh-13"><b>Issued By:</b></div>
                                                            </div>
                                                            <div class="col-4 col-md-4 text-end">
                                                                <div class="fs-12 lh-13">{{Auth::user()->first_name}}</div>
                                                            </div>
                                                        </div>
                                                        {{-- <div class="row">
                                                            <div class="col-8 col-md-8 text-end">
                                                                <div class="fs-12 lh-13"><b>Quotation No:</b></div>
                                                            </div>
                                                            <div class="col-4 col-md-4 text-end">
                                                                <div class="fs-12 lh-13">{{$lead->quotation_no}}</div>
                                                            </div>
                                                        </div> --}}
                                                        <div class="row">
                                                            <div class="col-8 col-md-8 text-end">
                                                                <div class="fs-12 lh-13"><b>Issued Date:</b></div>
                                                            </div>
                                                            <div class="col-4 col-md-4 text-end">
                                                                <div class="fs-12 lh-13">{{date('d-m-Y', strtotime($lead->created_at))}}</div>
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
                                                        <div class="row">
                                                            <div class="col-8 col-md-8 text-end">
                                                                <div class="fs-12 lh-13"><b>Service Date:
                                                                    </b></div>
                                                            </div>
                                                            <div class="col-4 col-md-4 text-end">
                                                                <div class="fs-12 lh-13">{{ date('d-m-Y', strtotime($lead->schedule_date)) }}</div>
                                                            </div>
                                                        </div>
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
                                                                <th hidden>PRODUCT CODE</th>
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
                                                                    <td hidden>{{ $item->product_code }}</td>
                                                                    <td>{{ $item->name }}</td>
                                                                    <td>{{ $item->description }}</td>                                                                    
                                                                    <td>{{ $item->quantity }}</td>
                                                                    <td>{{ $item->unit_price }}</td>
                                                                    <td>{{ $item->gross_amount }}</td>
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
                                                                <div>{{$customer->customer_remark}}</div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-3 col-md-3 text-start">
                                                                <div class="fs-10 lh-13"><b>Payment Term:</b></div>
                                                            </div>
                                                            <div class="col-9 col-md-9 text-start">
                                                                <div class="fs-10 lh-13">{{$customer->payment_terms_value}}</div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-3 col-md-3 text-start">
                                                                <div class="fs-10 lh-13"><b>Bank Detail:</b></div>
                                                            </div>
                                                            <div class="col-9 col-md-9 text-start">
                                                                <div class="fs-10 lh-13">{{($company)?$company->bank_name:''}} Current: {{($company)?$company->ac_number:''}}</div>
                                                                <div class="fs-10 lh-13">Bank Code: {{($company)?$company->bank_code:''}} / Branch Code: {{($company)?$company->branch_code:''}}</div>
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
                                                                <div class="fs-10 lh-13" style="text-decoration: underline;">
                                                                    {{-- <span>PayNow</span> --}}
                                                                    Unique Company Number(UEN) No: {{($company)?$company->uen_no:''}}
                                                                </div>
                                                                <div class="fs-10 lh-13">All cheques are to be crossed and made payable to
                                                                </div>
                                                                <div class="fs-10 lh-13" style="text-decoration: underline;">
                                                                    <b>{{($company)?$company->company_name:''}}</b>
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
                                                                        $<div>{{$lead->amount}}</div>
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
                                                                        $<div>{{$lead->discount_amt}}</div>
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
                                                                        $<div>{{$lead->total}}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-8 col-md-8 text-end">
                                                                <div class="fs-10 lh-13">GST @ <span id="preview_tax">{{$lead->tax_percent}}</span>%:</div>
                                                            </div>
                                                            <div class="col-4 col-md-4 text-end">
                                                                <div>
                                                                    <div class="d-flex justify-content-between fs-10 lh-13">
                                                                        $<div id="preview_tax_amt">{{$lead->tax}}</div>
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
                                                                        $<div id="preview_grand_total">{{$lead->grand_total}}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="display: none;" id="preview_deposit_amount_group">
                                                            <div class="col-8 col-md-8 text-end">
                                                                <div class="fs-10 lh-13">Deposit:</div>
                                                            </div>
                                                            <div class="col-4 col-md-4 text-end">
                                                                <div>
                                                                    <div
                                                                        class="d-flex justify-content-between fs-10 lh-13">
                                                                        $<div id="preview_deposit_amount"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="display: none;" id="preview_balance_amount_group">
                                                            <div class="col-8 col-md-8 text-end">
                                                                <div class="fs-10 lh-13">Balance:</div>
                                                            </div>
                                                            <div class="col-4 col-md-4 text-end">
                                                                <div>
                                                                    <div
                                                                        class="d-flex justify-content-between fs-10 lh-13">
                                                                        $<div id="preview_balance_amount"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row  terms-row">
                                                    <h3><b>Terms and Conditions</b></h3>
                                                    @foreach ($term_condition as $item)
                                                        <li>{{$item->term_condition }}</li>
                                                    @endforeach
                                                </div>
                                                <br><br>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <h3><b>This is a computer generated invoice therefore no
                                                                signature required.</b>
                                                        </h3>
                                                        <div class="d-flex footer-logo justify-content-between">
                                                            <div class="img">
                                                                <img src="{{asset($imagePath)}}" alt="logo"
                                                                    style="background-color:black; max-width:100px; height: 100px;">
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
                        <button type="button" onclick="sendInvoiceEmail()" class="btn btn-info w-100 mt-3"
                            data-dismiss="modal" style="width: 150px !important; margin-left: 85%;">Send By
                            mail</button>
                        {{-- <button type="button" class="btn btn-info w-100 mt-3" data-dismiss="modal"
                            onclick="confirm_btn()"
                            style="width: 150px !important; margin-left:auto;">Confirm</button> --}}
                    </div>

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


<div class="modal modal-blur fade" id="send-invoice" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Quotation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="smartwizard2" style="border: none;" dir="" class="sw sw-theme-basic sw-justified">
                    <ul class="nav d-none" style="">
                        <li class="nav-item">
                            <a class="nav-link default active" href="#send-step-1">
                                <div class="num">1</div>
                                1
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link default" href="#send-step-2">
                                <span class="num">2</span>
                                2
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link default" href="#send-step-3">
                                <span class="num">3</span>
                                3
                            </a>
                        </li>

                    </ul>
                    <div class="tab-content p-0" style="border: none; height: 260px;">
                        <div id="send-step-2" class="tab-pane" role="tabpanel" aria-labelledby="send-step-2"
                            style="display: none;">
                            <div class="row">
                                <div class="mb-3">
                                    <label class="form-label">Select Email Template</label>
                                    <div class="row g-2">
                                        {{-- @foreach ($emailTemplates as $emailTemplate)
                                            <div class="col-6 col-sm-4">
                                                <label class="form-imagecheck mb-2">
                                                    <input name="form-imagecheck-radio-emailtemplate" type="radio"
                                                        value="{{ $emailTemplate->id }}"
                                                        class="form-imagecheck-input"
                                                        onclick="findTemplateId({{ $emailTemplate->id }})">
                                                    <span class="form-imagecheck-figure">
                                                        <h4>{{ $emailTemplate->title }}</h4>
                                                    </span>
                                                </label>
                                            </div>
                                        @endforeach --}}
                                        {{-- <select class="form-select" aria-label="Default select example" id="emailTemplateOption">
                                            <option selected>Select Email Template</option>
                                            @foreach ($emailTemplates as $emailTemplate)
                                                 <option value="{{$emailTemplate->id}}" id="templateOption">{{$emailTemplate->title}}</option>
                                            @endforeach
                                        </select> --}}
                                        <select class="form-select select2" aria-label="Default select example"
                                            id="emailTemplateOption" onchange="findTemplateId()">
                                            <option value="">Select</option>
                                            @foreach ($emailTemplates as $emailTemplate)
                                                <option value="{{ $emailTemplate->id }}">
                                                    {{ $emailTemplate->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @foreach ($emailTemplates as $emailTemplate)
                                            <input type="hidden" value="{{ $emailTemplate->id }}"
                                                class="emailTemplateId">
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div id="send-step-3" class="tab-pane" role="tabpanel" aria-labelledby="send-step-3"
                            style="display: none;">
                            {{-- <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">To:</label>
                                        <input type="text" class="form-control" name="example-text-input"
                                            id="emailInput" placeholder="Type email">
                                    </div>
                                    <div class="mb-3">
                                        <textarea class="form-control" name="example-textarea-input" rows="6" placeholder="Content.."></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Attachment:</label>
                                        <input type="file" class="form-control" name="attachment"
                                            id="attachment">
                                    </div>
                                    <div class="email-attachment">
                                        <div class="file-info">
                                            <div class="file-size">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-paperclip">
                                                    <path
                                                        d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48">
                                                    </path>
                                                </svg>
                                                <span>Attachment</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 text-end">
                                <button class="btn btn-info" onclick="emailSend(event)">Confirm</button>
                            </div> --}}
                        </div>
                    </div>
                    <div class="sw-toolbar-elm justify-content-between toolbar toolbar-bottom" role="toolbar">
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
        </div>
    </div>
</div>



<script>

    $(document).ready(function () {
        // select2

        $('.select2').select2({
            dropdownParent: $("#send-invoice")
        });
    });

    function getAsiaOPtions() {
        var paymentOptions = document.getElementsByName('payment_option');

        for (var i = 0; i < paymentOptions.length; i++) {
            if (paymentOptions[i].checked) {
                // Add a class to the selected option
                paymentOptions[i].parentElement.parentElement.classList.add('selected-option');
            } else {
                // Remove the class from the unselected options
                paymentOptions[i].parentElement.parentElement.classList.remove('selected-option');
            }
        }
    }



    // function emailSend(event) {
    //     event.preventDefault;
    //     var company_id = $('#company-select').val()
    //     var email_template_id = $('#emailTemplateOption').val();
    //     var customer_id = $('#customer_id_lead').val()
    //     var service_id = $('#service_id_lead').val()
    //     var billing_address = $('#billing_id_lead').val()
    //     var selected_date = $('#selected_date').val()
    //     var total_amount_val = $('#total_amount_val').val()
    //     var tax = $('#tax').val()
    //     var grand_total_amount = $('#grand_total_amount').val()
    //     var tax_percent = $('#tax_percent').val()
    //     var time_of_cleaning = $('#time_of_cleaning').val()
    //     var date_of_cleaning = $('#date_of_cleaning').val()
    //     console.log(service_id);
    //     // console.log(email_template_id);

    //     $.ajax({
    //         url: '{{ route('process.payment') }}',
    //         method: 'POST',
    //         data: {
    //             "_token": "{{ csrf_token() }}",
    //             'company_id': company_id,
    //             'customer_id': customer_id,
    //             'service_id': service_id,
    //             'email_template_id': email_template_id,
    //             'billing_address': billing_address,
    //             'selected_date': selected_date,
    //             'total_amount_val': total_amount_val,
    //             'tax': tax,
    //             'grand_total_amount': grand_total_amount,
    //             'tax_percent': tax_percent,
    //             'date_of_cleaning': date_of_cleaning,
    //         },
    //         success: function(response) {
    //             console.log(response);
    //         },
    //     })
    //     $('#send-quotation').modal('hide')
    //     // $('#add-lead').modal('hide');
    //     // window.location.reload();
    // }

    function emailSend(event) {
        event.preventDefault;

        var payment_type = $(".payment_option:checked").val();

        var email_template_id = $('#emailTemplateOption').val();
        var email_to = $('#email_to').val();
        var email_cc = $('#email_cc').val();
        var email_bcc = $('#email_bcc').val();
        var email_subject = $('#email_subject').val();
        var email_body = $('#email_body').val();

        $('#form_email_template_id').val(email_template_id);
        $('#form_email_to').val(email_to);
        $('#form_email_cc').val(email_cc);
        $('#form_email_bcc').val(email_bcc);
        $('#form_email_subject').val(email_subject);
        $('#form_email_body').val(email_body);

        if(payment_type == "Asia Pay")
        {
            $.ajax({
                url: '{{ route('process.payment') }}',
                method: 'POST',
                data: $("#lead_form").serialize(),
                beforeSend: function() {
                    $("#email_confirm_btn").attr('disabled', true);
                },
                success: function(response) {
                    console.log(response);
                    
                    if (response.errors) 
                    {
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

                    } 
                    else if(response.status == "success")
                    {
                        iziToast.success({
                            message: response.message,
                            position: 'topRight',
                        });

                        $('#send-invoice').modal('hide');
                        $('#add-lead').modal('hide');
                        window.location.reload();
                    }
                    else
                    {
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
                    }
                },
                error: function(response){
                    console.log(response);
                },
                complete: function() {
                    $("#email_confirm_btn").attr('disabled', false);
                }, 
            });
        }
        else
        {
            var data = new FormData($('#lead_form')[0]);

            $.ajax({
                url: "{{ route('lead.payment.store') }}",
                method: 'POST',
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $("#email_confirm_btn").attr('disabled', true);
                },
                success: function(response) {
                    console.log(response);

                    if (response.errors) 
                    {
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

                    } 
                    else if(response.status == "success")
                    {
                        iziToast.success({
                            message: response.message,
                            position: 'topRight',
                        });

                        $('#send-invoice').modal('hide');
                        $('#add-lead').modal('hide');
                        window.location.reload();
                    }
                    else
                    {
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
                    }
                },
                error: function(response){
                    console.log(response);
                },
                complete: function() {
                    $("#email_confirm_btn").attr('disabled', false);
                }, 
            });
        }
    }

    $(document).ready(function() {
        $('#payment_full').click(function() {
            var totalAmount = parseFloat($('#totalAmount').text()) || 0;
            $('#payableAmount').val(totalAmount.toFixed(2));

            // Add 'selected-option' class to the clicked button
            $(this).addClass('selected-option');

            // Remove 'selected-option' class from the other button
            $('#payment_advance').removeClass('selected-option');
        });

        $('#payment_advance').click(function() {
            $('#payableAmount').val('');

            // Add 'selected-option' class to the clicked button
            $(this).addClass('selected-option');

            // Remove 'selected-option' class from the other button
            $('#payment_full').removeClass('selected-option');
        });
    });

    function calculateAmount() {

        var totalAmount = parseFloat(document.getElementById('totalAmount').innerText) || 0;

        var percentage = parseFloat(document.getElementById('persentage').value) || 0;

        var calculatedAmount = (percentage / 100) * totalAmount;

        document.getElementById('amount').value = calculatedAmount.toFixed(2);
        document.getElementById('payableAmount').value = calculatedAmount.toFixed(2);

    }

    function calculatepercentage()
    {
        var totalAmount = $("#totalAmount").text() || 0;
        totalAmount = parseFloat(totalAmount);

        var amount = $("#amount").val() || 0;
        amount = parseFloat(amount);

        var calculated_percentage = parseFloat((amount/totalAmount) * 100);

        $("#persentage").val(calculated_percentage.toFixed(2));
        $("#payableAmount").val(amount.toFixed(2));
    }
</script>
<script>
    // function findTemplateId(template_id) {
    //     // $('#emailTemplateOption').click(function(){

    //     var customerId = $('#customer_id_lead').val()
    //     // var templateId = $('#templateOption').val()
    //     var templateId = template_id;
    //     console.log('templateId:', templateId);
    //     $.ajax({
    //         url: '{{ route('get.email.data') }}',
    //         method: 'POST',
    //         data: {
    //             "_token": "{{ csrf_token() }}",
    //             'template_id': templateId,
    //             'customer_id': customerId,
    //         },
    //         success: function(response) {
    //             console.log(response);
    //             $('#send-step-3').append(response)
    //         },
    //         error: function(response) {
    //             console.log(response);
    //         }
    //     })
    //     // })
    // }

    function findTemplateId() {
        // $('#emailTemplateOption').click(function(){

        var customerId = $('#customer_id_lead').val()
        var templateId = $('#emailTemplateOption').val();

        $.ajax({
            url: '{{ route('get.email.data') }}',
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                'template_id': templateId,
                'customer_id': customerId,
            },
            success: function(response) {
                // console.log(response);
                $('#send-step-3').html(response);

                var email_cc = document.getElementById('email_cc');
                new Tagify(email_cc);
            },
            error: function(response) {
                console.log(response);
            }
        })
        // })
    }


    function invoiceEmailSend(event) {
        event.preventDefault;
        var company_id = $('#company-select').val()
        var payment_option = $('.payment_option').val()
        var email_template_id = templateId;
        var customer_id = $('#customer_id_lead').val()
        var service_id = $('#service_id_lead').val()
        var billing_address = $('#billing_id_lead').val()
        var selected_date = $('#selected_date').val()
        var total_amount_val = $('#total_amount_val').val()
        var tax = $('#tax').val()
        var grand_total_amount = $('#grand_total_amount').val()
        var tax_percent = $('#tax_percent').val()
        var time_of_cleaning = $('#time_of_cleaning').val()
        var date_of_cleaning = $('#date_of_cleaning').val()
        // console.log(service_id);
        console.log(customer_id);

        $.ajax({
            url: '{{ route('lead.send.invoice.mail') }}',
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                'company_id': company_id,
                'payment_option': payment_option,
                'customer_id': customer_id,
                'service_id': service_id,
                'email_template_id': email_template_id,
                'billing_address': billing_address,
                'selected_date': selected_date,
                'total_amount_val': total_amount_val,
                'tax': tax,
                'grand_total_amount': grand_total_amount,
                'tax_percent': tax_percent,
                'date_of_cleaning': date_of_cleaning,
            },
            success: function(response) {
                console.log(response);
            },
        })
        $('#send-invoice').modal('hide')
    }

    function getAsiaOPtions() {

    }
</script>
<script>
    var templateId = "";

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
            <td><input type="number" class="form-control" name="service_id" id="service_id"></td>
            <td><input type="text" class="form-control" name="service_name" id="service_name"></td>
            <td><input type="text" class="form-control" name="unit_price" id="unit_price"></td>
            <td><input type="number" class="form-control" name="qty" id="qty"></td>
            <td><input type="number" class="form-control" name="sub_total" id="sub_total"></td>
            <td><input type="number" class="form-control" name="discount" id="discount"></td>
            <td><input type="number" class="form-control" name="net_total" id="net_total"></td>
            <td>
                <div class="row">
                    <a href="#" class="btn btn-danger remove-price-info"><i class="fa fa-times" aria-hidden="true"></i></a>
                    <a href="#" class="btn btn-info save-price-info"><i class="fa fa-check" aria-hidden="true"></i></a>
                </div>
            </td>
        </tr>
                         `;

        field.appendChild(newField);
    }

    $(document).on('click', '.save-price-info', function() {
        var selected_service = $('#selected_service_id').val();
        // console.log(selected_service);
        var service_id = $('#service_id').val();
        var service_name = $('#service_name').val();
        var unit_price = $('#unit_price').val();
        var qty = $('#qty').val();
        var sub_total = $('#sub_total').val();
        var discount = $('#discount').val();
        var net_total = $('#net_total').val();
        var ServiceSubtotal = sub_total;
        var ServiceDiscount = discount;
        var ServiceNettotal = net_total;
        $.ajax({
            url: '{{ route('lead.price.info') }}',
            type: 'POST',
            data: {
                service_id: service_id,
                selected_service: selected_service,
                service_name: service_name,
                unit_price: unit_price,
                qty: qty,
                sub_total: sub_total,
                discount: discount,
                net_total: net_total,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // console.log(response);
                $('#priceInfoTable tbody').append(response);
            }
        });
        const priceCalculation = `
        <lable>Sub Total :  $${ServiceSubtotal}</label><br>
        <lable>Net Total : $${ServiceNettotal}</label><br>
        <lable>Percentage Discount(%) : ${ServiceDiscount}</label><br>
        <lable>Grand Total : ${ServiceNettotal}</label><br>
        `;

        $('.calculation-price').append(priceCalculation);
    });
    $(document).on('click', '.remove-price-info', function() {
        const row = $(this).closest('tr');
        row.remove();
    });
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
                              <div class="row amount_details" id="amount_details">
                                 <div class="">
                                    <p class="m-0"> Total:</p>
                                 </div>
                                    <p class="m-0 total-tax-amount"></p>
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
        const servicePrice = $(this).data('service-price');
        const quantity = parseInt($(this).closest('tr').find('.quantity-input').val(), 10) || 0;
        const row = ``;

        const priceRow = `
                <tr>
                <input type="hidden" class="form-control price" value="${serviceId}" name="selected_service_id" id="selected_service_id">
                <td>${serviceId}</td>
                <td>${serviceName}</td>
                <td><input type="number" class="form-control price" value="${servicePrice}"></td>
                <td><input type="number" class="form-control quantity-input" placeholder="" value="${quantity}"></td>
                <td><input type="number" class="form-control sub-total-input" placeholder="" value=""></td>
                <td><input type="number" class="form-control discount-input" placeholder="" value=""></td>
                <td><input type="number" class="form-control net-total-input" placeholder="" value=""></td>

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
        $('#priceInfoTable tbody').append(priceRow);
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
        let amountDetailHTML = '';
        let totalAmount = '';
        $('#selected-services-table tbody tr').each(function() {
            // console.log(this);
            const serviceName = $(this).find('td:eq(1)').text();
            const quantity = parseInt($(this).find('.quantity-input').val(), 10);
            // const servicePrice = parseInt($(this).find('.price').val());
            const servicePrice = $(this).find('.price').val();
            totalAmount += servicePrice * quantity;
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
</script>
<script>
    var company_id = $('#company-select').val();

    $(document).ready(function() {
        $('#residential-view-table').on('click', function() {
            Search();
            $('#residential_Search').show();

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
        // console.log(selectedCompanyId);
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

    // $(document).ready(function() {

    //     var serviceData = <?php echo json_encode($service->toArray()); ?>;

    //     var serviceIdCell = $('.service-id');
    //     var serviceNameCell = $('.service-name');
    //     var descriptionCell = $('.description-cell');
    //     var unitPriceCell = $('.unit-price');
    //     var quantityCell = $('.quantity-value');


    //     serviceIdCell.text(serviceData[0].service_id);
    //     serviceNameCell.text(serviceData[0].name);
    //     descriptionCell.text(serviceData[0].description);
    //     unitPriceCell.html('<input type="number" class="form-control price" value="' + serviceData[0]
    //         .unit_price + '">');
    //     quantityCell.html('<input type="number" class="form-control quantity-input qty5" value="' + serviceData[
    //         0].quantity + '">');
    // });
    // $(document).on('click', '.add-service-btn', function() {
    //     const serviceId = $(this).data('service-id');
    //     const serviceName = $(this).data('service-name');
    //     const serviceDescription = $(this).data('service-description');
    //     const servicePrice = $(this).data('service-price');
    //     const quantityInput = $(this).closest('tr').find('.quantity-input');
    //     const quantity = parseInt(quantityInput.val(), 10) || 0;
    //     //   if ($('#selected-services-table tbody tr[data-service-id="' + serviceId + '"]').length > 0) {
    //     //     alert('This service is already added.');
    //     //     return;
    //     //   }
    //     const row = `

    //  <tr>
    //  <input type="text" class="form-control price" value="${serviceName}" name="service_name[]">
    //    <td>${serviceId}</td>
    //    <td>${serviceName}</td>
    //   <td class="description-cell" data-full-text="${serviceDescription}">${serviceDescription}</td>


    //    <td><input type="number" class="form-control price" value="${servicePrice}"></td>
    //    <td class="p-0"><input type="number" class="form-control quantity-input" placeholder="quantity" value="${quantity}"></td>

    //    <td>
    //      <button class="btn btn-danger ripple remove-service-btn" type="button">
    //        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cross m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="white" fill="none" stroke-linecap="round" stroke-linejoin="round">
    //            <path stroke="currentColor" d="M6 6l12 12" />
    //            <path stroke="currentColor" d="M6 18L18 6" />
    //            </svg>

    //      </button>
    //    </td>
    //  </tr>
    //  `;
    //     $('#selected-services-table tbody').append(row);
    //     $('.service-id').val(serviceId);
    //     const serviceIdsInput = $('input[name="service_ids"]');
    //     const serviceNamesInput = $('input[name="service_names"]');
    //     const serviceDescriptionsInput = $('input[name="service_descriptions"]');

    //     const unitPriceInput = $('input[name="unit_price"]');
    //     const qty = $('input[name="quantities"]').val(quantity);
    //     const currentServiceIds = serviceIdsInput.val();
    //     const updatedServiceIds = currentServiceIds ? `${currentServiceIds},${serviceId}` : serviceId;
    //     serviceIdsInput.val(updatedServiceIds);

    //     const currentServiceNames = serviceNamesInput.val();
    //     const updatedServiceNames = currentServiceNames ? `${currentServiceNames},${serviceName}` : serviceName;
    //     serviceNamesInput.val(updatedServiceNames);

    //     const currentServiceDescription = serviceDescriptionsInput.val();
    //     const updatedServiceDescription = currentServiceDescription ?
    //         `${currentServiceDescription},${serviceDescription}` : serviceDescription;
    //     serviceDescriptionsInput.val(updatedServiceDescription);

    //     const currentServiceUnitPrice = unitPriceInput.val();
    //     const updatedServiceUnitPrice = currentServiceUnitPrice ? `${currentServiceUnitPrice},${servicePrice}` :
    //         servicePrice;
    //     unitPriceInput.val(updatedServiceUnitPrice);


    //     const selectedServicesInput = $('#selected-services-id');
    //     const currentSelectedServices = selectedServicesInput.val();
    //     const updatedSelectedServices = currentSelectedServices ? `${currentSelectedServices},${serviceId}` :
    //         serviceId;
    //     selectedServicesInput.val(updatedSelectedServices);
    //     $(document).on('change', '.quantity-input', function() {
    //         const quantity = parseInt($(this).val(), 10);
    //     });
    // });
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
    var quotationView = '';
    var totalServicePrice = 0;
    var totalDiscount = 0;

    function addService(thiss) {

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

        addItem(serviceId, row, priceRow);

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
    }
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

    // $('#smartwizard').on('leaveStep', function(e, anchorObject, stepNumber, stepDirection) {
    //     // console.log("stepDirection", stepDirection);
    //     console.log("e", e);
    //     //  $('#smartwizard').smartWizard(stepDirection , 6);
    //     var companyId = $('#company_id').val()
    //     var customer_id = $('#customer_id_lead').val()
    //     if (stepDirection == 5) {
    //         $.ajax({
    //             url: '{{ route('get.lead.payment.preview') }}',
    //             method: 'POST',
    //             data: {
    //                 _token: "{{ csrf_token() }}",
    //                 'company_id': companyId
    //             },
    //             success: function(response) {
    //                 $('#step-4').html(response);
    //             },
    //         })
    //     }
    // });

    $('#smartwizard').on('leaveStep', function(e, anchorObject, stepNumber, stepDirection) {
        // console.log(stepDirection);
        if (stepDirection == 6) 
        {
            var preview_grand_total = parseFloat($("#preview_grand_total").text());

            var payment_type = $('input[name="payment_option"]:checked').val();

            // console.log(payment_type);

            var payment_amount = 0;

            if(payment_type == "Asia Pay")
            {
                payment_amount = parseFloat($("#payableAmount").val());
            }
            else
            {
                $.each($('input[name="payment_amount_checkbox[]"]'), function() {
                    if($(this).val() != "" && $(this).val() != null)
                    {
                        payment_amount += parseFloat($(this).val());
                    }
                });
            }

            // console.log(payment_amount);

            var preview_balance_amount = 0;

            if(preview_grand_total == payment_amount)
            {
                preview_balance_amount = 0;
            }
            else
            {
                preview_balance_amount = preview_grand_total - payment_amount;
            }

            preview_balance_amount = parseFloat(preview_balance_amount);

            $("#preview_deposit_amount").text(payment_amount.toFixed(2));
            $("#preview_balance_amount").text(preview_balance_amount.toFixed(2));

            var payment_type = $(".payment_option:checked").val();

            if(payment_type == "Offline")
            {
                $("#preview_deposit_amount_group").show();
                $("#preview_balance_amount_group").show();
            }
            else
            {
                $("#preview_deposit_amount_group").hide();
                $("#preview_balance_amount_group").hide();
            }
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
        // e.preventDefault();
        let form = $('#lead_form')[0];
        let data = new FormData(form);
        data.append('_token', '{{ csrf_token() }}');
        $.ajax({
            url: "{{ route('lead.payment.store') }}",
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

                    $('#lead-payment').modal('hide');
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

    function sendInvoiceEmail() {
        console.log(company_id);
        $('#send-invoice').modal('show')

    }
    $('#smartwizard2').smartWizard({
        transition: {
            animation: 'slideHorizontal', // Effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
        }
    });
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
            console.log(selectedOption);
            if (selectedOption === "Asia Pay") {
                $("#asia_pay_option").show();
                $("#offline_pay_option").hide();

                $("#attach_invoice_group").show();
            } 
            else if (selectedOption === "Offline") {
                $("#asia_pay_option").hide();
                $("#offline_pay_option").show();
                
                $("#attach_invoice_group").hide();
            } 
            else {
                // Handle other cases here
            }
        });
    });

    $(document).ready(function() {
        $('#payment_advance').click(function() {
            $("#payment_advance_feild").show();
        });
        $('#payment_full').click(function() {
            $("#payment_advance_feild").hide();
        });
    });

    $(document).ready(function() {
        $('#persentage_discount').change(function() {
            $("#persent_discount_feild").show();
            $("#amount_discount_feild").hide();
        });
        $('#amount_discount').change(function() {
            $("#amount_discount_feild").show();
            $("#persent_discount_feild").hide();
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
                var highlightDate = "{{ $lead->schedule_date }}";
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

                const formattedDate = `${selectedDay}/${selectedMonth}/${selectedYear}`;

                $('#date_of_cleaning').val(formattedDate);
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
