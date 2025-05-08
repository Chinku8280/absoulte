<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">View Quatation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-link card-link-pop">
                    <div class="card-status-start bg-primary"></div>
                    <div class="card-stamp">
                        <div class="card-stamp-icon bg-white text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-pin"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                                                <b>{{ $company ? $company->company_name : '' }}</b></h1>
                                            <p class="m-0 fs-12 lh-13">{{ $company ? $company->company_address : '' }}</p>
                                            <p class="m-0 fs-12 lh-13">Tel: {{ $company ? $company->contact_number : '' }}
                                                Fax: {{ $company ? $company->contact_number : '' }} Phone:
                                                {{ $company ? $company->contact_number : '' }}</p>
                                            <p class="m-0 fs-12 lh-13">Website: {{ $company ? $company->website : '' }}
                                            </p>
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
                                            {{-- @if (!empty($quotation->invoice_no))
                                                <h3>Tax Invoice</h3>
                                            @else --}}
                                                <h3>QUOTATION</h3>
                                            {{-- @endif --}}
                                        </div>
                                    </div>
                                    <div class="row mb-3">

                                        <div class="col-1 col-sm-1 text-center">
                                            <h4 class="mb-3 fs-12 lh-13"><b>To:</b></h4>
                                        </div>
                                        <div class="col-4 col-sm-4 ps-0">
                                            <div class="fs-12 lh-13"> {{ $customer->individual_company_name }}</div>
                                            <div class="fs-12 lh-13"> {{ $customer->customer_name }}</div>
                                            <div class="fs-12 lh-13"> {{ $quotation->full_service_address }}</div>
                                            <div class="fs-12 lh-13">+65 {{ $customer->mobile_number }}</div>
                                            <div class="fs-12 lh-13"> {{ $customer->email }}</div>
                                        </div>

                                        <div class="col-7 col-sm-7">
                                            {{-- @if (!empty($quotation->invoice_no))
                                                <div class="row">
                                                    <div class="col-8 col-md-8 text-end">
                                                        <div class="fs-12 lh-13"><b>Invoice No:</b></div>
                                                    </div>
                                                    <div class="col-4 col-md-4 text-start">
                                                        <div class="fs-12 lh-13">{{ $quotation->invoice_no }}</div>
                                                    </div>
                                                </div>
                                            @else --}}
                                                <div class="row">
                                                    <div class="col-8 col-md-8 text-end">
                                                        <div class="fs-12 lh-13"><b>Quotation No:</b></div>
                                                    </div>
                                                    <div class="col-4 col-md-4 text-start">
                                                        <div class="fs-12 lh-13">{{ $quotation->quotation_no ?? '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            {{-- @endif --}}

                                            <div class="row">
                                                <div class="col-8 col-md-8 text-end">
                                                    <div class="fs-12 lh-13"><b>Issued Date:</b></div>
                                                </div>
                                                <div class="col-4 col-md-4 text-start">
                                                    <div class="fs-12 lh-13">
                                                        {{ date('d-m-Y', strtotime($quotation->created_at)) }}</div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-8 col-md-8 text-end">
                                                    <div class="fs-12 lh-13"><b>Issued By:</b></div>
                                                </div>
                                                <div class="col-4 col-md-4 text-start">
                                                    <div class="fs-12 lh-13">{{ $quotation->created_by_name }}</div>
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
                                                        <div class="fs-12 lh-13">
                                                            {{ $quotation->schedule_date ? date('d-m-Y', strtotime($quotation->schedule_date)) : '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- @if (empty($quotation->invoice_no)) --}}
                                                <div class="row">
                                                    <div class="col-8 col-md-8 text-end">
                                                        <div class="fs-12 lh-13"><b>Expiry Date:
                                                            </b></div>
                                                    </div>
                                                    <div class="col-4 col-md-4 text-start">
                                                        <div class="fs-12 lh-13">
                                                            {{ date('d-m-Y', strtotime($quotation->created_at. ' + 14 days')) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            {{-- @endif --}}

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
                                                                    $description_arr = explode(PHP_EOL, $item->description);
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
                                                        {{-- {{$customer->customer_remark}} --}}
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
                                                                $quotation->remarks,
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
                                                    <div class="fs-10 lh-13">{{ $customer->payment_terms_value }}</div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-3 col-md-3 text-start">
                                                    <div class="fs-10 lh-13"><b>Bank Detail:</b></div>
                                                </div>
                                                <div class="col-9 col-md-9 text-start">
                                                    <div class="fs-10 lh-13">{{ $company ? $company->bank_name : '' }}
                                                        Current: {{ $company ? $company->ac_number : '' }}</div>
                                                    <div class="fs-10 lh-13">Bank Code:
                                                        {{ $company ? $company->bank_code : '' }} / Branch Code:
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
                                                    <div class="fs-10 lh-13" style="text-decoration: underline;">
                                                        {{-- <span>PayNow</span> --}}
                                                        Unique Company Number(UEN) No:
                                                        {{ $company ? $company->uen_no : '' }}
                                                    </div>
                                                    <div class="fs-10 lh-13">All cheques are to be crossed and made
                                                        payable to
                                                    </div>
                                                    <div class="fs-10 lh-13" style="text-decoration: underline;">
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
                                                        <div class="d-flex justify-content-between fs-10 lh-13">
                                                            $<div>{{ number_format($quotation->amount, 2) }}</div>
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
                                                        <div class="d-flex justify-content-between fs-10 lh-13">
                                                            $<div>{{ number_format($quotation->discount_amt, 2) }}</div>
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
                                                        <div class="d-flex justify-content-between fs-10 lh-13">
                                                            $<div>{{ number_format($quotation->total, 2) }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-8 col-md-8 text-end">
                                                    <div class="fs-10 lh-13">GST @ <span
                                                            id="preview_tax">{{ $quotation->tax_percent }}</span>%:
                                                    </div>
                                                </div>
                                                <div class="col-4 col-md-4 text-end">
                                                    <div>
                                                        <div class="d-flex justify-content-between fs-10 lh-13">
                                                            $<div id="preview_tax_amt">{{ number_format($quotation->tax, 2) }}</div>
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
                                                        <div class="d-flex justify-content-between fs-10 lh-13">
                                                            $<div id="preview_grand_total">
                                                                {{ number_format($quotation->grand_total, 2) }}</div>
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

                                    @if (!empty($quotation->reject_remarks))
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-md-12">
                                                <h3>Reject Remarks</h3>
                                                <div>{{ $quotation->reject_remarks }}</div>
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
                                                                <td class="text-center">{{ $item->new_payment_method }}</td>
                                                                <td class="text-center">${{ number_format($item->payment_amount, 2) }}</td>
                                                                <td class="text-center">
                                                                    @foreach ($item->payment_proof_details as $list)
                                                                        @if (!empty($list->payment_proof))
                                                                            <a data-fancybox="gallery" href="{{ asset('application/public/uploads/payment_proof/' . $list->payment_proof) }}">
                                                                                <img src="{{ asset('application/public/uploads/payment_proof/' . $list->payment_proof) }}"
                                                                                    alt="" width="100px;">
                                                                            </a>
                                                                        @endif
                                                                    @endforeach
                                                                </td>
                                                                <td class="text-center">
                                                                    @if($item->payment_status == 2)
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
    <div class="modal-footer">
        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
    </div>
</div>
