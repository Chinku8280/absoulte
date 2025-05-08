    <style>
        .sw>.tab-content
        {
            height: auto !important;
            position: relative;
            overflow: initial !important;
        }

    </style>

<div class="modal-header">
    <h5 class="modal-title">Quotation Send Payment Advice</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form class="row text-left" id="quotation_form" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="quotationId" id="quotationId" value="{{ $quotationId }}">
        <input type="hidden" id="customer_id_quotation" name="customer_id" value="{{ $quotation->customer_id }}">
        <input type="hidden" id="service_id_quotation" name="service_address" value="{{ $quotation->service_address }}">
        <input type="hidden" id="billing_id_quotation" name="billing_address" value="{{ $quotation->billing_address }}">
        <input type="hidden" id="selected_date" name="schedule_date" value="{{ $quotation->schedule_date }}">
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
                        <span class="num">1</span>
                        Payment
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="#step-2">
                        <span class="num">2</span>
                        Preview
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-3" style="border: none;">
                <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-7">
                    <div class="row">

                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Payment Amount</h3>
                                </div>
                                <div class="card-body">
                                    <div class="col-md-6">
                                        <label class="form-label" for="totalAmount" style="font-size: 20px;">Total Payble Amount($): <span
                                                id="totalAmount">{{$quotation->grand_total}}</span></label>
                                        <input type="hidden" value="{{$quotation->grand_total}}" name="total_amount">
                                        <input type="hidden" value="{{$quotation->grand_total}}" id="payment_amount" name="payment_amount">
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

                                    {{-- <div class="responsive-table">
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
                                                                <label class="form-check-label" for="">
                                                                    {{ $options->payment_option }}
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                        <td><input class="form-control" type="number"
                                                                id="payableAmount" name="payment_amount" readonly>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div> --}}
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
                                                style="font-size: 14px;"></i>
                                                {{($customer->customer_type == "residential_customer_type")?$customer->customer_name:$customer->individual_company_name}}
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

                            <div class="card" id="attach_invoice_group">
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

                <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-4">

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
                                                        <h2>{{ucfirst($quotation->payment_status)}}</h2>
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
                                                            <div class="col-4 col-md-4 text-start">
                                                                <div class="fs-12 lh-13">{{Auth::user()->first_name}}</div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-8 col-md-8 text-end">
                                                                <div class="fs-12 lh-13"><b>Invoice No:</b></div>
                                                            </div>
                                                            <div class="col-4 col-md-4 text-start">
                                                                <div class="fs-12 lh-13">{{$quotation->temp_invoice_no}}</div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-8 col-md-8 text-end">
                                                                <div class="fs-12 lh-13"><b>Issued Date:</b></div>
                                                            </div>
                                                            <div class="col-4 col-md-4 text-start">
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

                                                        @if (!empty($quotation->schedule_date))                                                                                                                
                                                            <div class="row">
                                                                <div class="col-8 col-md-8 text-end">
                                                                    <div class="fs-12 lh-13"><b>Service Date:
                                                                        </b></div>
                                                                </div>
                                                                <div class="col-4 col-md-4 text-start">
                                                                    <div class="fs-12 lh-13">{{ ($quotation->schedule_date)?date('d-m-Y', strtotime($quotation->schedule_date)):'' }}</div>
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
                                                                <th hidden>PRODUCT CODE</th>
                                                                <th>Sl No</th>
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
                                                                    <td>{{$key+1}}</td>
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
                                                                <div>
                                                                    {{-- {{$customer->customer_remark}} --}}

                                                                    @php
                                                                        $remark_arr = explode(PHP_EOL, $quotation->remarks)                                                  
                                                                    @endphp

                                                                    @foreach ($remark_arr as $list)
                                                                        {{$list}} <br>
                                                                    @endforeach
                                                                </div>
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
                                                                        $<div>{{number_format($quotation->amount, 2)}}</div>
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
                                                                        $<div>{{number_format($quotation->discount_amt, 2)}}</div>
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
                                                                        $<div>{{number_format($quotation->total, 2)}}</div>
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
                                                                        $<div id="preview_tax_amt">{{number_format($quotation->tax, 2)}}</div>
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
                                                                        $<div id="preview_grand_total" data-preview_grand_total="{{$quotation->grand_total}}">{{number_format($quotation->grand_total, 2)}}</div>
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
                                                                        $<div id="preview_deposit_amount">0.00</div>
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
                                                                        $<div id="preview_balance_amount">{{number_format($quotation->grand_total, 2)}}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row  terms-row">
                                                    <h3><b>Terms and Conditions</b></h3>
                                                    <ul style="list-style-type: none;">
                                                        @foreach ($term_condition as $item)
                                                            @php
                                                                $term_arr = explode(PHP_EOL, $item->term_condition)
                                                            @endphp
                    
                                                            @foreach ($term_arr as $list)
                                                                <li>{{$list}}</li>
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
                        
                        <div class="col-md-12" style="text-align: end;">                                                                                                       
                            <button type="button" class="btn btn-info w-100 mt-3" data-dismiss="modal"
                                onclick="view_download_payment_advice_pdf(event)"
                                style="width: 150px !important;">Download</button>  

                            <button type="button" onclick="confirm_payment_advice(event)" class="btn btn-info w-100 mt-3"
                                data-dismiss="modal" style="width: 150px !important;">Confirm</button>

                            <button type="button" onclick="sendInvoiceEmail()" class="btn btn-info w-100 mt-3"
                                data-dismiss="modal" style="width: 150px !important;">Send By
                                mail</button>   
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
    </form>
</div>

<div class="modal modal-blur fade" id="send-invoice" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
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

        $('#send-invoice').find('.select2').select2({
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

    function emailSend(event) 
    {
        event.preventDefault;

        // Ensure the email body is updated with the editor content before submitting
        if (window.editor) {
            // Update the hidden textarea value with the content of the editor
            $("#send-invoice").find('#email_body').val(window.editor.getData());
        }

        var payment_type = $("#quotation_form").find(".payment_option:checked").val();

        var email_template_id = $("#send-invoice").find('#emailTemplateOption').val();
        var email_to = $("#send-invoice").find('#email_to').val();
        var email_cc = $("#send-invoice").find('#email_cc').val();
        var email_bcc = $("#send-invoice").find('#email_bcc').val();
        var email_subject = $("#send-invoice").find('#email_subject').val();
        var email_body = $("#send-invoice").find('#email_body').val();

        $("#quotation_form").find('#form_email_template_id').val(email_template_id);
        $("#quotation_form").find('#form_email_to').val(email_to);
        $("#quotation_form").find('#form_email_cc').val(email_cc);
        $("#quotation_form").find('#form_email_bcc').val(email_bcc);
        $("#quotation_form").find('#form_email_subject').val(email_subject);
        $("#quotation_form").find('#form_email_body').val(email_body);

        if(payment_type == "Asia Pay")
        {
            $.ajax({
                url: '{{ route('quotation.process.payment') }}',
                method: 'POST',
                data: $("#quotation_form").serialize(),
                beforeSend: function() {
                    $("#send-invoice").find("#email_confirm_btn").attr('disabled', true);
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
                        $('#quotation-payment').modal('hide');
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
                    $("#send-invoice").find("#email_confirm_btn").attr('disabled', false);
                },
            });
        }
        else
        {
            var data = new FormData($('#quotation_form')[0]);

            $.ajax({
                url: "{{ route('quotation.send-payment.offline') }}",
                method: 'POST',
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $("#send-invoice").find("#email_confirm_btn").attr('disabled', true);
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
                        $('#quotation-payment').modal('hide');
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
                    $("#send-invoice").find("#email_confirm_btn").attr('disabled', false);
                },
            });
        }
    }

    function calculateAmount() {

        var totalAmount = parseFloat(document.getElementById('totalAmount').innerText) || 0;

        var percentage = parseFloat(document.getElementById('persentage').value) || 0;

        var calculatedAmount = (percentage / 100) * totalAmount;

        document.getElementById('amount').value = calculatedAmount.toFixed(2);
        document.getElementById('payment_amount').value = calculatedAmount.toFixed(2);

    }

    function calculatepercentage()
    {
        var totalAmount = $("#totalAmount").text() || 0;
        totalAmount = parseFloat(totalAmount);

        var amount = $("#amount").val() || 0;
        amount = parseFloat(amount);

        var calculated_percentage = parseFloat((amount/totalAmount) * 100);

        $("#persentage").val(calculated_percentage.toFixed(2));
        $("#payment_amount").val(amount.toFixed(2));
    }

    function findTemplateId() 
    {
        var customerId = $("#quotation_form").find('#customer_id_quotation').val()
        var templateId = $("#send-invoice").find('#emailTemplateOption').val();

        var invoice_no = "{{$quotation->temp_invoice_no}}";
        var new_schedule_date = "{{ $quotation->schedule_date ? date('d-m-Y', strtotime($quotation->schedule_date)) : ''}}";
        var quotation_no = "{{$quotation->quotation_no}}";

        if(templateId)
        {
            $.ajax({
                url: '{{ route('get.email.data') }}',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'template_id': templateId,
                    'customer_id': customerId,
                    'service_address_id': $("#quotation_form").find('input[name="service_address"]').val(),
                    'billing_address_id': $("#quotation_form").find('input[name="billing_address"]').val(),
                    'quotation_id': $("#quotation_form").find("#quotationId").val(),
                    'payment_amount' : $("#quotation_form").find("#payment_amount").val(),
                    'payment_option' : $("#quotation_form").find('input[name="payment_option"]:checked').val(),
                },
                success: function(response) {
                    // console.log(response);
                    $("#send-invoice").find('#send-step-3').html(response);

                    // var email_cc = document.getElementById('email_cc');
                    var email_cc = $("#send-invoice").find("#email_cc")[0];
                    new Tagify(email_cc);

                    // ClassicEditor
                    //             .create(document.querySelector('#email_body'))
                    //             .catch(error => {
                    //                 console.error(error);
                    //             });

                    // Initialize ClassicEditor for the email body
                    ClassicEditor.create(document.querySelector('#send-invoice #email_body'))
                        .then(editor => {
                            // Assign the editor instance globally if needed
                            window.editor = editor;
                        })
                        .catch(error => {
                            console.error(error);
                        });

                    // subject
                    var email_subject = $("#send-invoice").find("#email_subject").val();
                    email_subject = email_subject.replace("##INVOICE_NO##", invoice_no);               
                    email_subject = email_subject.replace("##JOB_DATE##", new_schedule_date);
                    email_subject = email_subject.replace("##QUOTATION_NO##", quotation_no);      
                    $("#send-invoice").find("#email_subject").val(email_subject);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }
    }


    function sendInvoiceEmail() {
       // console.log(company_id);
        $('#send-invoice').modal('show');

    }

    function view_download_payment_advice_pdf(event) 
    {
        event.preventDefault;

        var payment_type = $(".payment_option:checked").val();

        $.ajax({
            url: "{{ route('quotation.payment-advice.view-pdf') }}",
            method: 'POST',
            data: $("#quotation_form").serialize(),
            success: function(response) {
                console.log(response);

                if (response.status == "error")
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
                    location.href = response.route;
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
        });
    }   
    
    function confirm_payment_advice(event)
    {
        event.preventDefault;

        $.ajax({
            url: "{{ route('quotation.send-payment-advice.confirm') }}",
            method: 'POST',
            data: $("#quotation_form").serialize(),
            success: function(result) {
                console.log(result);

                if (result.status == "success") {
                    iziToast.success({
                        message: result.message,
                        position: 'topRight'
                    });
                } else {
                    iziToast.error({
                        message: result.message,
                        position: 'topRight'
                    });
                }

                window.location.reload();
            },
            error: function(result){
                console.log(result);
            },
        });
    }

    $(document).ready(function() {
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

        $('#smartwizard2').smartWizard("reset"); 

        $('#payment_full').click(function() {
            var totalAmount = parseFloat($('#totalAmount').text()) || 0;
            $('#payment_amount').val(totalAmount.toFixed(2));

            // Add 'selected-option' class to the clicked button
            $(this).addClass('selected-option');

            // Remove 'selected-option' class from the other button
            $('#payment_advance').removeClass('selected-option');
        });

        $('#payment_advance').click(function() {
            $('#payment_amount').val('');

            // Add 'selected-option' class to the clicked button
            $(this).addClass('selected-option');

            // Remove 'selected-option' class from the other button
            $('#payment_full').removeClass('selected-option');
        });

        $('input[name="payment_option"]').change(function() {
            var selectedOption = $('input[name="payment_option"]:checked').val();
           // console.log(selectedOption);
            if (selectedOption === "Asia Pay") {
                $("#asia_pay_option").show();
                $("#offline_pay_option").hide();
            }
            else if (selectedOption === "Offline") {
                $("#asia_pay_option").hide();
                $("#offline_pay_option").show();
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

            if (stepDirection == 1)
            {
                // var preview_grand_total = parseFloat($("#preview_grand_total").data('preview_grand_total'));

                // var payment_type = $('input[name="payment_option"]:checked').val();

                // // console.log(payment_type);

                // var payment_amount = 0;

                // payment_amount = parseFloat($("#payment_amount").val());

                // // console.log(payment_amount);

                // var preview_balance_amount = 0;

                // if(preview_grand_total == payment_amount)
                // {
                //     preview_balance_amount = 0;
                // }
                // else
                // {
                //     preview_balance_amount = preview_grand_total - payment_amount;
                // }

                // preview_balance_amount = parseFloat(preview_balance_amount);

                // $("#preview_deposit_amount").text(payment_amount.toFixed(2));
                // $("#preview_balance_amount").text(preview_balance_amount.toFixed(2));

                // var payment_type = $(".payment_option:checked").val();
              
                $("#preview_deposit_amount_group").show();
                $("#preview_balance_amount_group").show();        
            }
        });

        // close send invoice modal
        
        $('#send-invoice').on('hidden.bs.modal', function () {
            $(this).find('#send-step-3').html("");
            $(this).find('#smartwizard2').smartWizard("reset"); 
            $(this).find(".select2").val("").trigger('change');          
        });

    });
</script>
