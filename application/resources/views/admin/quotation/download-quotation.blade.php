@extends('theme.default')

@section('custom_css')
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
            color: #000;
            margin: 0;
            padding: 0;
        }

        .invoice-box .title {
            font-size: 2rem;
            color: #000;
            font-weight: bolder;
        }

        .footer-logo .img img {
            width: 80px;
        }

        .card-lg>.card-body {
            padding: 1.5rem;
            margin: 0;

        }

        #invoice-card {
            margin: 0 !important;
        }

        .fs-10 {
            font-size: 10px;
        }

        .fs-12 {
            font-size: 12px;
        }

        .lh-13 {
            line-height: 1.2;
        }

        .invoice-table {
            /* height: 350px; */
            border-bottom: 1px solid #000;
        }

        .invoice-table thead th {
            background-color: transparent;
            font-size: 12px;
            line-height: 1.2;
            font-weight: bold !important;
            color: #000;

            border-bottom: 1px solid #000;
        }

        .invoice-table tbody tr td {
            border-bottom: none !important;
            font-size: 10px;
            line-height: 1.2;
        }

        .terms-row {
            font-size: 10px;
            line-height: 1.2;
        }

        @media print{
            .footer-t {
                position: fixed;
                bottom: 2%;
                width: 90%;
                /* margin-left: 5%;
                margin-right: 5%; */
                height: auto;
                visibility: visible;
            }
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
                            {{-- @if (!empty($quotation->invoice_no))
                                Tax Invoice
                            @else --}}
                                QUOTATION
                            {{-- @endif --}}
                        </h2>
                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                        <a href="{{ url()->previous() }}" class="btn btn-primary" role="button">
                            <i class="fa fa-arrow-left" aria-hidden="true" style="margin-right: 5px;"></i>
                            Back
                        </a>

                        <button type="button" class="btn btn-primary" id="print-btn" onclick="print_page()">
                            <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2">
                                </path>
                                <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"></path>
                                <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z">
                                </path>
                            </svg>
                            Print Invoice
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">
                <div class="card card-lg" id="invoice-card">

                    <div class="card-body">
                        <div class="d-flex mb-2">
                            <div class="logo p-0 text-center">
                                @if ($company->image_path != '')
                                    <img src="{{ asset($company->image_path) }}" alt="" class="img-fluid"
                                        style="height: 100px;">
                                @else
                                    <img src="" alt="" class="img-fluid" style="height: 100px;">
                                @endif
                            </div>
                            <div class="company-dece pe-0 ps-3">
                                <h1 class="title" style="font-size: 26px;"><b>{{ $company->company_name }}</b></h1>
                                <p class="m-0 fs-12 lh-13">{{ $company->company_address }}
                                </p>
                                <p class="m-0 fs-12 lh-13">
                                    Tel: +65 {{ $company->telephone }} 
                                    @if(!empty($company->fax))
                                        Fax: +65 {{ $company->fax }}
                                    @endif  
                                    Phone: +65 {{ $company->contact_number }}
                                </p>
                                <p class="m-0 fs-12 lh-13">Website: {{ $company->website }}</p>
                                <p class="m-0 fs-12 lh-13">Co. Reg No: {{ $company->co_register_no }}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GST Reg No.
                                    {{ $company->gst_register_no }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-8"></div>
                            <div class="col-4 text-center">
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
                                <div class="fs-12 lh-13">{{ $customer->individual_company_name }}</div>
                                <div class="fs-12 lh-13">{{ $customer->customer_name }}</div>
                                <div class="fs-12 lh-13">{{ $quotation->service_address_details }},
                                    {{ $quotation->service_address_unit_number }}</div>
                                <div class="fs-12 lh-13">+65 {{ $customer->mobile_number }}</div>
                                <div class="fs-12 lh-13">{{ $customer->email }}</div>
                            </div>

                            <div class="col-7 col-sm-7">
                                {{-- @if (!empty($quotation->invoice_no))
                                    <div class="row">
                                        <div class="col-8 col-md-8 text-end">
                                            <div class="fs-12 lh-13"><b>Invoice No:</b></div>
                                        </div>
                                        <div class="col-4 col-md-4 text-start">
                                            <div class="fs-12 lh-13 print_file_name">{{ $quotation->invoice_no }}</div>
                                        </div>
                                    </div>
                                @else --}}
                                    <div class="row">
                                        <div class="col-8 col-md-8 text-end">
                                            <div class="fs-12 lh-13"><b>Quotation No:</b></div>
                                        </div>
                                        <div class="col-4 col-md-4 text-start">
                                            <div class="fs-12 lh-13 print_file_name">{{ $quotation->quotation_no ?? '' }}</div>
                                        </div>
                                    </div>
                                {{-- @endif --}}

                                <div class="row">
                                    <div class="col-8 col-md-8 text-end">
                                        <div class="fs-12 lh-13"><b>Issued By:</b></div>
                                    </div>
                                    <div class="col-4 col-md-4 text-start">
                                        <div class="fs-12 lh-13">{{ $quotation->created_by_name }}</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-8 col-md-8 text-end">
                                        <div class="fs-12 lh-13"><b>Issued Date:</b></div>
                                    </div>
                                    <div class="col-4 col-md-4 text-start">
                                        <div class="fs-12 lh-13">{{ date('d-m-Y', strtotime($quotation->created_at)) }}
                                        </div>
                                    </div>
                                </div>

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
                                        <th>Sl No.</th>
                                        <th>SERVICE</th>
                                        <th>DESCRIPTION</th>
                                        <th>QTY</th>
                                        <th>UNIT PRICE</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotation_details as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
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
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    $&nbsp;{{ number_format($item->unit_price, 2) }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    $&nbsp;{{ number_format($item->gross_amount, 2)}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6 col-sm-6">
                                <div class="row mb-2">
                                    <div class="col-3 col-md-3 text-start">
                                        <div class="fs-12 lh-13"><b>Remarks:</b></div>
                                    </div>
                                    <div class="col-8 col-md-8 text-start">
                                        <div>
                                            {{-- {{$customer->customer_remark}} --}}
                                            {{-- @php
                                                $cust_remark_arr = explode(PHP_EOL, $customer->customer_remark);
                                            @endphp

                                            @foreach ($cust_remark_arr as $list)
                                                {{ $list }} <br>
                                            @endforeach --}}

                                            @php
                                                $remark_arr = explode(PHP_EOL, $quotation->remarks);
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
                                        <div class="fs-10 lh-13">{{ $company ? $company->bank_name : '' }} Current:
                                            {{ $company ? $company->ac_number : '' }}</div>
                                        <div class="fs-10 lh-13">Bank Code: {{ $company ? $company->bank_code : '' }} /
                                            Branch Code: {{ $company ? $company->branch_code : '' }}</div>
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
                                            {{-- <span>PayNow</span>  --}}
                                            <span>Unique Company Number(UEN) No:
                                                {{ $company ? $company->uen_no : '' }}</span>
                                        </div>
                                        <div class="fs-10 lh-13">All cheques are to be crossed and made payable to
                                        </div>
                                        <div class="fs-10 lh-13" style="text-decoration: underline;">
                                            <b>{{ $company ? $company->company_name : '' }}</b></div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-2 col-sm-2">
                                @if ($company->qr_code_path != "")
                                    <img src="{{ asset($company->qr_code_path) }}" alt="" style="width: 100px; height: 100px;">
                                @else
                                    <img src="" alt="" style="width: 100px; height: 100px;">
                                @endif
                                {{-- <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAclBMVEXMzMzQ0NDT09PLy8sAAADIyMh+fn6Li4u1tbV7e3uCgoLAwMDFxcVFRUVZWVm9vb2ioqJUVFScnJxOTk6VlZU8PDxpaWmxsbEjIyOKioooKChlZWVwcHB1dXVBQUGRkZE2NjYNDQ1JSUkvLy8cHBwTExNimPTCAAAEAElEQVR4nO3YWZuiOBiGYQJSIolhCYsKgi3V//8vTlgLl2uqZ6Zb5+C5j8DWt/NlhXIcAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA/L+I0e3dcu/c3v1yzn8I+t3csg0/wm0w3sl0O8qisVkqO4ZhG3zfstscJ8rGnGMmxyA5BEWvLzHa5UYXVb5v/b4dcZ7vB1U5tCXQnS5016TuP8pxRHoZg5pi7J0yudigg4m/C/rdRFzFQSRlcD6lQ8NObTCI1PDveRNHMiqrQ/z3nX+X44i2S6egoefkxQxByaV89Sj6clgbwjON31eYl6vl49afZX8lvEPyTd/f5jhi2wSrhedVl2AIig7FqwdxJo4bO2oi69Z97G7O452If8SrLePx6i7HEWG+XrveJpzustM3s+GPmSu8rhom0s1cr3c4z5+rdP5QlKl6nmMrTFabijh+Tr+xgxi+q8LwRz9Ls30gfOVNzQl/zlPKT/R8GWlTjleluT5ssmNOX6F0lD8FuXU+/1pe6zdNU0+bvsI2ibNd3ZbDALjV0jCvNsuJGSXJUGKZJI+b/5jjiF0VZ+dzW8ohyCzLWFXFe8ZQxId+D/Q/PvdJUdidvR8cN9kvFYZ7uXw3sCUKYQt8PCanHEcVhzGo6DvBzZcZ4J/1w8x+BSH18B97aZ1GwlWx7ofH3SfPKuxLNEFgnhU45TheW8d2d5Wpqezv3NOyg6o3Vag+mnFtKTW0WpRdayu8Ph3DYaIa82SKfuXYNTgGpV1qgzr97gqzU+vdfCD12bOzdFmHTmjW9bjxZvPs6eQhRwQmtEGX5GuWVu9Yh/EpvG2Y4+90ZHeaw9IwrVcFiWBvzP5xkj7m2AVZSLvTLJNBmjfspSLe1PeN9cNC9efhPDX9S/XVMBE1jVJNczdNn+U4fr3z7XnYqfmnl93Lx9A2LLzvVhFd+4PZ3Wynk1HNV/1N2RglhDLNzTPmsxz73TyzX/I32RQUfGYvrlB423XDxPyU1mXDcXEZzmwhdP61P5Qm6U85IZP56H/MWYKyrn9K869mDPKTxP+j9TySRzs6/sDe+WVpR0f48b7f5G2Pd0VkGyuzTbr0vCymXdTuqIV8nuOoMuiDVNrUQ9eU3dkeHkIef7z6sVSEm0O4G5ztO29UN3WbZefT9HroxLk5Zll1WA2PiuctRgSxep4jgmpvg9q6m14PvfSit1mmT+3L3w/TMPwY7WzLvKA9a52EmZx7uvyodPVx8wL87N3iLsdOhrbWWoepmr7ixzutq/DlL8CrP6eMrRXCk1Hkrl4LHBmpX/jzyl1OHxT9qyAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAC8yV8YEDZOb4QEjAAAAABJRU5ErkJggg=="
                                    alt="" style="height: 100px; width: 100px;"> --}}
                            </div>
                            <div class="col-4 col-sm-4">
                                <div class="row">
                                    <div class="col-8 col-md-8 text-end">
                                        <div class="fs-10 lh-13">Sub Total:</div>
                                    </div>
                                    <div class="col-4 col-md-4 text-start">
                                        <div class="fs-10 lh-13">
                                            $&nbsp;{{ number_format($quotation->amount, 2) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8 col-md-8 text-end">
                                        <div class="fs-10 lh-13">
                                            Discount:
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-4 text-start">
                                        <div class="fs-10 lh-13">
                                            $&nbsp;{{ number_format($quotation->discount_amt, 2) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8 col-md-8 text-end">
                                        <div class="fs-10 lh-13">Total:</div>
                                    </div>
                                    <div class="col-4 col-md-4 text-start">
                                        <div>
                                            <div class="fs-10 lh-13">
                                                $&nbsp;{{ number_format($quotation->total, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8 col-md-8 text-end">
                                        <div class="fs-10 lh-13">GST @ {{ $quotation->tax_percent }}%:
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-4 text-start">
                                        <div>
                                            <div class="fs-10 lh-13">
                                                $&nbsp;{{ number_format($quotation->tax, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8 col-md-8 text-end">
                                        <div class="fs-10 lh-13">Grand Total:</div>
                                    </div>
                                    <div class="col-4 col-md-4 text-start">
                                        <div>
                                            <div class="fs-10 lh-13">
                                                $&nbsp;{{ number_format($quotation->grand_total, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row">
                                    <div class="col-6 col-md-6 text-end">
                                        <div class="fs-10 lh-13">Deposit:</div>
                                    </div>
                                    <div class="col-6 col-md-6 text-start">
                                        <div>
                                            <div class="fs-10 lh-13">
                                                $&nbsp;{{ $quotation->deposit }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-6 text-end">
                                        <div class="fs-10 lh-13">Balance:</div>
                                    </div>
                                    <div class="col-6 col-md-6 text-start">
                                        <div>
                                            <div class="fs-10 lh-13">
                                                $&nbsp;{{ $quotation->balance }}
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>

                        <div class="row terms-row">
                            <h3><b>Terms and Conditions</b></h3>
                            <ol class="ps-4" style="list-style-type: none;">
                                @foreach ($term_condition as $item)
                                    @php
                                        $term_arr = explode(PHP_EOL, $item->term_condition);
                                    @endphp

                                    @foreach ($term_arr as $list)
                                        <li>{{ $list }}</li>
                                    @endforeach
                                @endforeach
                            </ol>
                        </div>

                        {{-- <div class="row">
                            <div class="col-sm-4">
                                <div class="signature-container">
                                    <div style="height: 20px;"></div>
                                    <canvas id="signatureCanvas" style="width: 100%; height: 70px; margin-bottom: 10px;"></canvas>
                                    <div class="signature-line" style="border-bottom: 1px solid #000; margin-bottom: 10px;"></div>
                                    <div class="signature-details" style="text-align: center;">
                                        <span class="name fs-12 lh-13">Customer's Acknowledgement</span>
                                        <span class="time fs-10 lh-13">Designation:</span>
                                        <span class="time fs-10 lh-13">Company Stamp:</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4"></div>

                            <div class="col-sm-4">
                                <div class="signature-container">
                                    <div style="height: 20px;"></div>
                                    <canvas id="signatureCanvas" style="width: 100%; height: 70px; margin-bottom: 10px;"></canvas>
                                    <div class="signature-line" style="border-bottom: 1px solid #000; margin-bottom: 10px;"></div>
                                    <div class="signature-details" style="text-align: center;">
                                        <div class="name fs-12 lh-13">{{ $company ? $company->company_name : '' }}</div>
                                        <div class="time fs-10 lh-13">Designation : Sales Coordinator</div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        <div class="row">
                            <div class="col-sm-12">
                                <h3><b>This is a computer generated invoice therefore no signature required.</b></h3>

                                <div class="footer-t">
                                    @if (!$company->company_invoice_footer_logo->isEmpty())                                                                  
                                        <div class="d-flex footer-logo justify-content-between align-items-center">
                                            @foreach ($company->company_invoice_footer_logo as $item) 
                                                <div class="img">
                                                    <img src="{{ asset($item->invoice_footer_logo_path) }}" alt="logo">
                                                </div>   
                                            @endforeach                                   
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
@endsection
