<div class="row">
    <div class="col-md-12">
        <div class="card card-link card-link-pop">
            <div class="card-status-start bg-primary d-print-none"></div>
            <div class="card-stamp d-print-none">
                <div class="card-stamp-icon bg-white text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-pin" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                        <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="page-header d-print-none">
                {{-- <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col-auto ms-auto d-print-none">
                            <button type="button" class="btn btn-primary" id="print-btn" onclick="printQuotation()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2">
                                    </path>
                                    <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"></path>
                                    <path
                                        d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z">
                                    </path>
                                </svg>
                                Print Quotation
                            </button>
                            <a class="btn btn-primary" onclick="downloadQuotation()">
                                Download Quotation
                            </a>
                        </div>
                    </div>
                </div> --}}
            </div>
            <div class="page-body">
                <div class="container-xl">
                    <div class="card card-lg" id="invoice-card">

                        <div class="card-body">
                            <div class="d-flex mb-2">
                                <div class="logo p-0 text-center">
                                    {{-- <img src="{{$imagePath}}" alt="" class="img-fluid"
                                        style="height: 100px;"> --}}
                                    <div class="img">
                                        <img src="{{ asset($imagePath) }}" alt="logo"
                                            style="background-color:black; max-width:100px; height: 100px;">
                                    </div>
                                </div>
                                <div class="company-dece pe-0 ps-3">
                                    <h1 class="title" style="font-size: 26px;">
                                        <b>{{ $company ? $company->company_name : '' }}</b></h1>
                                    <p class="m-0 fs-12 lh-13">{{ $company ? $company->company_address : '' }}</p>
                                    <p class="m-0 fs-12 lh-13">Tel: {{ $company ? $company->contact_number : '' }} Fax:
                                        {{ $company ? $company->contact_number : '' }} Phone:
                                        {{ $company ? $company->contact_number : '' }}</p>
                                    <p class="m-0 fs-12 lh-13">Website: {{ $company ? $company->website : '' }}</p>
                                    <p class="m-0 fs-12 lh-13">
                                        Co. Reg No: {{ $company ? $company->co_register_no : '' }}
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        GST Reg No. {{ $company ? $company->gst_register_no : '' }}
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8"></div>
                                <div class="col-4 text-center" style="margin-left: 68%;">
                                    <h3>QUOTATION</h3>
                                </div>
                            </div>
                            <div class="row mb-3">

                                <div class="col-1 col-sm-1 text-center">
                                    <h4 class="mb-3 fs-12 lh-13"><b>To:</b></h4>
                                </div>
                                <div class="col-4 col-sm-4 ps-0">
                                    <div class="fs-12 lh-13"> {{ $customer->individual_company_name }}</div>
                                    <div class="fs-12 lh-13"> {{ $customer->customer_name }}</div>
                                    <div class="fs-12 lh-13"> {{ $service_address }}</div>
                                    <div class="fs-12 lh-13">+65 {{ $customer->mobile_number }}</div>
                                    <div class="fs-12 lh-13"> {{ $customer->email }}</div>
                                </div>

                                <div class="col-7 col-sm-7">
                                    <div class="row">
                                        <div class="col-8 col-md-8 text-end">
                                            <div class="fs-12 lh-13"><b>Quotation No:</b></div>
                                        </div>
                                        <div class="col-4 col-md-4 text-start">
                                            <div class="fs-12 lh-13 print_file_name preview_quotation_no" id="preview_quotation_no">{{ $lead->quotation_no ?? '' }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-8 col-md-8 text-end">
                                            <div class="fs-12 lh-13"><b>Issued Date:</b></div>
                                        </div>
                                        <div class="col-md-4 text-start">
                                            <div class="fs-12 lh-13">{{ date('d-m-Y') }}</div>
                                        </div>
                                    </div>
                                    {{-- <div class="row">
                                        <div class="col-8 col-md-8 text-end">
                                            <div class="fs-12 lh-13"><b>Expiry Date</b></div>
                                        </div>
                                        <div class="col-4 col-md-4 text-end">
                                            <div class="fs-12 lh-13">22 Feb 2023</div>
                                        </div>
                                    </div> --}}
                                    <div class="row">
                                        <div class="col-8 col-md-8 text-end">
                                            <div class="fs-12 lh-13"><b>Issue By:</b></div>
                                        </div>
                                        <div class="col-md-4 text-start">
                                            <div class="fs-12 lh-13">{{ $issue_by }}</div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="table-responsive-sm">
                                <table class="quotation-table table">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th hidden>PRODUCT CODE</th>
                                            <th>SERVICE</th>
                                            <th>DESCRIPTION</th>
                                            <th>QTY</th>
                                            <th>UNIT PRICE</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="quotation-table-content">

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
                                            <div id="preview_remarks">
                                                {{-- {{$customer->customer_remark}} --}}
                                                {{-- @php
                                                    $cust_remark_arr = explode(PHP_EOL, $customer->customer_remark);
                                                @endphp

                                                @foreach ($cust_remark_arr as $list)
                                                    {{ $list }} <br>
                                                @endforeach --}}
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
                                            <div class="fs-10 lh-13">{{ $company ? $company->bank_name : '' }} Current:
                                                {{ $company ? $company->ac_number : '' }}</div>
                                            <div class="fs-10 lh-13">Bank Code: {{ $company ? $company->bank_code : '' }}
                                                / Branch Code: {{ $company ? $company->branch_code : '' }}</div>
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
                                            <div class="fs-10 lh-13">Bank Code: 7339 / Branch Code: 695</div>
                                        </div>
                                    </div> --}}
                                    <div class="row mb-2">
                                        <div class="col-3 col-md-3 text-start">
                                            <div class="fs-10 lh-13"><b>Payment Method:</b></div>
                                        </div>
                                        <div class="col-9 col-md-9 text-start">
                                            <div class="fs-10 lh-13" style="text-decoration: underline;">
                                                {{-- <span>PayNow</span> --}}
                                                Unique Company Number(UEN) No: {{ $company ? $company->uen_no : '' }}
                                            </div>
                                            <div class="fs-10 lh-13">All cheques are to be crossed and made payable to
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
                                    <div class="row" style="display: none;">
                                        <div class="col-8 col-md-8 text-end">
                                            <div class="fs-10 lh-13">Sub Total:</div>
                                        </div>
                                        <div class="col-4 col-md-4 text-end">
                                            <div>
                                                <div class="d-flex justify-content-between fs-10 lh-13">
                                                    $<div class="subTotal" id="preview_subTotal"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8 col-md-8 text-end">
                                            <div class="fs-10 lh-13">Sub Total:</div>
                                        </div>
                                        <div class="col-4 col-md-4 text-end">
                                            <div>
                                                <div class="d-flex justify-content-between fs-10 lh-13">
                                                    $<div class="netTotal" id="preview_netTotal"> </div>
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
                                                    $<div id="preview_discount"></div>
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
                                                    $<div id="preview_total"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8 col-md-8 text-end">
                                            <div class="fs-10 lh-13">GST @ <span id="preview_tax"></span>%:</div>
                                        </div>
                                        <div class="col-4 col-md-4 text-end">
                                            <div>
                                                <div class="d-flex justify-content-between fs-10 lh-13">
                                                    $<div id="preview_tax_amt"></div>
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
                                                    $<div class="grandTotal" id="preview_grandTotal"></div>
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
                            <div class="row">

                                <div class="col-5">
                                    <div class="signature-container">
                                        <canvas id="signatureCanvas"></canvas>
                                        <div class="signature-line"></div>
                                        <div class="signature-details">
                                            <span class="name fs-12 lh-13">Customers Acknowledgement </span><br>
                                            <span class="time fs-10 lh-13">Designation:</span><br>
                                            <span class="time fs-10 lh-13">Company Stamp:</span><br>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">

                                </div>
                                <div class="col-5">
                                    <div class="signature-container">
                                        <canvas id="signatureCanvas"></canvas>
                                        <div class="signature-line"></div>
                                        <div class="signature-details">
                                            <span
                                                class="name fs-12 lh-13">{{ $company ? $company->company_name : '' }}</span><br>
                                            <span class="time fs-10 lh-13">Designation : Sales Coordinator</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="row">
                                <div class="col-sm-12">
                                    <h3><b>This is a computer generated invoice therefore no signature required.</b>
                                    </h3>
                                    <div class="d-flex footer-logo justify-content-between">
                                        <div class="img">
                                            <img src="{{$imagePath}}" alt="logo" style="background-color:black; max-width:100px; height: 100px;">
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex">
        <div>
            <button type="button" onclick="sendByEmail()" class="btn btn-info mt-3 d-print-none"
                data-dismiss="modal" style="width: 150px;">Send By mail</button>
        </div>

        <div style="width: 100%; text-align: end;">
            {{-- <button type="button" class="btn btn-info mt-3 d-print-none" style="width: 150px;"
                onclick="print_page()">Download</button> --}}
            <button type="button" class="btn btn-info mt-3 d-print-none" style="width: 150px;"
                onclick="view_download_quotation_pdf(event)">Download</button>
            <button type="button" class="btn btn-info mt-3 d-print-none" data-dismiss="modal" onclick="save_btn()"
                style="width: 150px;">Save</button>
            <button type="button" class="btn btn-info mt-3 d-print-none" data-dismiss="modal"
                onclick="confirm_btn()" style="width: 150px;">Confirm</button>
        </div>
    </div>

    {{-- <button type="button" onclick="sendByEmail()" class="btn btn-info w-100 mt-3 d-print-none"
    data-dismiss="modal" style="width: 150px !important;">Send By mail</button>
    
    <button type="button" class="btn btn-info w-100 mt-3 d-print-none" style="width: 150px !important; margin-left:auto;" onclick="window.print()">Download</button>    

    <button type="button"  class="btn btn-info w-100 mt-3 d-print-none"
                data-dismiss="modal"  onclick="save_btn()" style="width: 150px !important; margin-left:auto;">Save</button> --}}

</div>
