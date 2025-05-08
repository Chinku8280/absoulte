@extends('theme.default')

@section('custom_css')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" /> 
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

    <style>
        .sw>.tab-content 
        {
            height: auto !important;
            position: relative;
            overflow: initial !important;
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
                        Quotation Payment
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-lg-12">
                    
                    <form class="row text-left" id="lead_form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="quotation_id" value="{{ $quotation->id }}">
                        <input type="hidden" id="customer_id_lead" name="customer_id" value="{{ $quotation->customer_id }}">
                        <input type="hidden" name="company_id" id="company_id" value="{{ $quotation->company_id }}">
                
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
                                                        value="{{ date('d-m-Y', strtotime($quotation->schedule_date)) }}" required>
                                                </div>
                                                <div class="form-group col-lg-6 col-md-6 col-sm-12 text-start">
                                                    <label for="message-text" id="time_of_cleaning" name="time_of_cleaning"
                                                        class="col-form-label">Time of Cleaning</label>
                                                    <input type="time" class="form-control" value="{{ $quotation->time_of_cleaning }}"
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
                                                                <th scope="col">PRODUCT CODE.</th>
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
                                                        <td id="pi_subtotal">${{ $quotation->subtotal }}</td>
                                                    </tr>
                
                                                    <tr>
                                                        <td style="width: 40%;">Net Total : </td>
                                                        <td id="pi_nettotal">${{ $quotation->amount }}</td>
                                                    </tr>
                
                                                    @if ($quotation->discount_type == 'percentage')
                                                        <tr>
                                                            <td style="width: 40%;" id="pi_disc_label">Percentage Discount(%) :</td>
                                                            <td id="pi_discount">{{ $quotation->discount }}%</td>
                                                        </tr>
                                                    @else
                                                        <tr>
                                                            <td style="width: 40%;" id="pi_disc_label">Amount Discount :</td>
                                                            <td id="pi_discount">{{ $quotation->discount }}</td>
                                                        </tr>
                                                    @endif
                
                                                    <tr>
                                                        <td style="width: 40%;">Tax <span
                                                                id="pi_tax">({{ $quotation->tax_percent }}%)</span></td>
                                                        <td id="pi_taxamt">${{ $quotation->tax }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 40%;">Grand Total</td>
                                                        <td id="pi_grandtotal">${{ $quotation->grand_total }}</td>
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
                                                    <h3>Payment Ammount</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="totalAmount">Total Payble Amount($): <span
                                                                id="totalAmount">{{$quotation->grand_total}}</span></label>
                                                        <input type="hidden" value="{{$quotation->grand_total}}" name="total_amount">
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
                                                                                <div class="fs-12 lh-13">{{$quotation->quotation_no}}</div>
                                                                            </div>
                                                                        </div> --}}
                                                                        <div class="row">
                                                                            <div class="col-8 col-md-8 text-end">
                                                                                <div class="fs-12 lh-13"><b>Issued Date:</b></div>
                                                                            </div>
                                                                            <div class="col-4 col-md-4 text-end">
                                                                                <div class="fs-12 lh-13">{{date('d-m-Y', strtotime($quotation->created_at))}}</div>
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
                                                                                <div class="fs-12 lh-13">{{ date('d-m-Y', strtotime($quotation->schedule_date)) }}</div>
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
                                                                                        $<div>{{$quotation->amount}}</div>
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
                                                                                        $<div>{{$quotation->discount_amt}}</div>
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
                                                                                        $<div>{{$quotation->total}}</div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-8 col-md-8 text-end">
                                                                                <div class="fs-10 lh-13">GST @ <span id="preview_tax">{{$quotation->tax_percent}}</span>%:</div>
                                                                            </div>
                                                                            <div class="col-4 col-md-4 text-end">
                                                                                <div>
                                                                                    <div class="d-flex justify-content-between fs-10 lh-13">
                                                                                        $<div id="preview_tax_amt">{{$quotation->tax}}</div>
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
                                                                                        $<div id="preview_grand_total">{{$quotation->grand_total}}</div>
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
            </div>
        </div>
    </div>
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

@endsection

@section('javascript')

<script>
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
                url: "{{ route('quotation.online-payment') }}",
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
                        location.href = "{{route('quotation')}}";
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
                url: "{{ route('quotation.offline-payment') }}",
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
                        
                        location.href = "{{route('quotation')}}";
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

    function findTemplateId(template_id) {

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

                // email template cc

                var email_cc = document.getElementById('email_cc');
                new Tagify(email_cc);
            },
            error: function(response) {
                console.log(response);
            }
        });
    }

    function sendInvoiceEmail() {
        console.log(company_id);
        $('#send-invoice').modal('show')

    }

    $(document).ready(function() {
        $('#smartwizard2').smartWizard({
            transition: {
                animation: 'slideHorizontal', // Effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
            }
        });

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

        $('#payment_advance').click(function() {
            $("#payment_advance_feild").show();
        });

        $('#payment_full').click(function() {
            $("#payment_advance_feild").hide();
        });

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

        
        // select2

        $('.select2').select2({
            dropdownParent: $("#send-invoice")
        });
    });
</script>
    
@endsection
