@extends('theme.default')

@section('custom_css')



@endsection

@section('content')
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            Send Payment Advice
                        </h2>
                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">                   
                        <button type="button" class="btn btn-primary" id="print-btn" onclick="window.print()">
                            <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
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
                            Print Invoice
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">
                <div class="card card-lg">

                    <div class="card-body">
                        <div class="d-flex mb-2">
                            <div class="logo p-0 text-center">
                                @if ($company->image_path != "")
                                    <img src="{{ asset($company->image_path) }}" alt="" class="img-fluid" style="height: 100px;">
                                @else
                                    <img src="" alt="" class="img-fluid" style="height: 100px;">
                                @endif
                            </div>
                            <div class="company-dece pe-0 ps-3">
                                <h1 class="title" style="font-size: 26px;"><b>{{$company->company_name ?? ''}}</b></h1>
                                <p class="m-0 fs-12 lh-13">{{$company->company_address ?? ''}}
                                </p>
                                <p class="m-0 fs-12 lh-13">Tel: +65 {{$company->telephone ?? ''}} Fax: +65 {{$company->fax ?? ''}} Phone: +65 {{$company->contact_number ?? ''}}</p>
                                <p class="m-0 fs-12 lh-13">Website: {{$company->website ?? ''}}</p>
                                <p class="m-0 fs-12 lh-13">Co. Reg No: {{$company->co_register_no ?? ''}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GST Reg No.
                                    {{$company->gst_register_no ?? ''}}</p>
                            </div>
                        </div>

                        <br>

                        <div class="text-center" style="font-weight: bold;">
                            {{ $company->company_name }} - Payment mode and details
                        </div>
                        
                        <br>

                        <div>
                            <table class="table table-bordered">

                                <tbody>

                                    @if (isset($payment_link))
                                        <tr>
                                            <td>Visa Link</td>
                                            <td>
                                                <a href="{{ $payment_link }}" target="_blank">{{ $payment_link }}</a>
                                            </td>
                                        </tr>
                                    @endif
                    
                                    <tr>
                                        <td>Paynow</td>
                                        <td>
                                            @if ($company->qr_code_path != "")
                                                <img src="{{ asset($company->qr_code_path) }}" alt="image" width="100" height="100">
                                            @else
                                                <img src="" alt="image" width="100" height="100">
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Unique Company Number (UEN) No</td>
                                        <td>{{$company->uen_no}}</td>
                                    </tr>
                                    <tr>
                                        <td>Bank Transfer</td>
                                        <td>
                                            {{$company->bank_name}}: {{$company->ac_number}} <br>
                                            Bank Code: {{$company->bank_code}} <br>
                                            Payee name: {{$company->company_name}}
                                        </td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>

                        <div>
                            <span>Please indicate invoice number to</span> <br>
                            <span>Email : {{$company->email_id}}</span> <br>
                            <span>Whatsapp Only : {{$company->contact_number}}</span>
                        </div>
                    
                        <br><br>

                        <div style="font-weight: bold;">{{ $company->company_name }}</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
