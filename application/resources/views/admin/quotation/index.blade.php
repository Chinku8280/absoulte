@extends('theme.default')
{{-- <style>
    .dropdown-menu.show {
        display: block !important;
        position: absolute !important;
        inset: 0px 0px auto auto !important;
        transform: translate(0px, 39px) !important;
    }
</style> --}}

@section('custom_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">

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
        
        .highlighted-date {
            background-color: #ffcc00;
            /* Yellow background */
            color: #000;
            /* Black text color */
            font-weight: bold;
        }

        /* table tr th
        {
            text-align: center !important;
        } */
    </style>

@endsection

@section('content')
    <input type="hidden" name="quantities" class="quantity-input-j" placeholder="quantity">
    <input type="hidden" name="discounts" class="discount-input-j" placeholder="discount">
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            Quotation
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
                        <div class="row g-2 align-items-center">
                            <div class="col">
                                <ul class="nav nav-pills nav-pills-primary" data-bs-toggle="tabs" role="tablist">
                                    @foreach ($companyList as $companyId => $companyName)
                                        <li class="nav-item me-2" role="presentation">
                                            <a href="#company-{{ $companyId }}"
                                                class="nav-link" data-bs-toggle="tab"
                                                aria-selected="{{ $loop->first ? 'true' : 'false' }}" role="tab" onclick="getCustomerDetails({{$companyName->id}})">
                                                {{ $companyName->company_name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                {{-- <div class="tab-content">
                                    @foreach ($companyList as $companyId => $companyName)
                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                            id="company-{{ $companyId }}" role="tabpanel">
                                            <!-- Content for Company {{ $companyName }} tab -->
                                        </div>
                                    @endforeach
                                </div> --}}

                            </div>
                            <div class="col-auto ms-auto d-print-none">
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#add-quotation" onclick="addQuotation()">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Add New
                                </a>
                            </div>


                        </div>

                        <div class="tab-content mt-3">
                            <div class="tab-pane active show" id="company-l" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <ul class="nav nav-pills nav-pills-primary" data-bs-toggle="tabs" role="tablist">
                                            <li class="nav-item me-2" role="presentation">
                                                <a href="#residential" class="nav-link active" data-bs-toggle="tab"
                                                    aria-selected="true" role="tab">Residential</a>
                                            </li>
                                            <li class="nav-item me-2" role="presentation">
                                                <a href="#commercial" class="nav-link" data-bs-toggle="tab"
                                                    aria-selected="false" role="tab" tabindex="-1">Commercial</a>
                                            </li>
                                        </ul>
                                        <div class="card-actions">

                                        </div>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane active show" id="residential" role="tabpanel">
                                            {{-- <div class="card-body border-bottom py-3">
                                                <div class="d-flex">
                                                    <div class="text-muted">
                                                        Show
                                                        <div class="mx-2 d-inline-block">
                                                            <input type="text" class="form-control form-control-sm"
                                                                value="" size="3" aria-label="Invoices count">
                                                        </div>
                                                        entries
                                                    </div>

                                                    <div class="ms-auto text-muted">
                                                        Search:
                                                        <div class="ms-2 d-inline-block">
                                                            <input type="text" id="search-quotation" class="form-control form-control-sm"
                                                                aria-label="Search quotation" onkeypress="searchResidentialCustomer()" onkeydown="searchResidentialCustomer()">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div> --}}

                                            <div class="card-body">
                                                <div class="table-responsive" style="min-height: 500px;">
                                                    <table class="table card-table table-vcenter text-left text-nowrap datatable" id="quotation-residantial" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="w-1">No.</th>
                                                                <th>Quotation No.</th>
                                                                <th>Service Type</th>
                                                                <th>Expiration Date</th>
                                                                <th>Total Amount</th>
                                                                <th>Created By</th>
                                                                <th>Stage</th>
                                                                <th>Payment Advice</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $i=0;
                                                            @endphp

                                                            @foreach ($quotation as $key => $item)
                                                                @if ($item->customer_type == 'residential_customer_type')
                                                                    <tr>
                                                                        <td>{{ $i + 1 }}</td>
                                                                        <td><span
                                                                                class="text-muted">{{ $item->quotation_no }}</span>
                                                                        </td>
                                                                        <td>{{$item->company_name}}</td>
                                                                        <td>
                                                                            {{ date('d-m-Y', strtotime($item->created_at. ' + 14 days'))}}
                                                                        </td>
                                                                        <td>${{ $item->grand_total }}</td>
                                                                        <td>{{$item->created_by_name}}</td>                                                                  

                                                                        <td>
                                                                            @if(isset($item))
                                                                                @if($item->quotation_status == 1)
                                                                                    <span class="badge bg-yellow">Pending</span>
                                                                                @elseif($item->quotation_status == 2)
                                                                                    <span class="badge bg-red">Pending Customer Approval</span>
                                                                                @elseif($item->quotation_status == 3)
                                                                                    <span class="badge bg-red">Pending Payment</span>
                                                                                @elseif($item->quotation_status == 4)                      
                                                                                {{-- <span class="badge bg-green">{{ucfirst(str_replace("_", " ", $item->payment_status))}}</span>                                                        --}}
                                                                                <span class="badge bg-green">Approved</span>
                                                                                @elseif($item->quotation_status == 5)
                                                                                    <span class="badge bg-red">Rejected</span>                                                                   
                                                                                @endif
                                                                            @else
                                                                                <!-- Handle the case when $item is not defined (no data in the table) -->
                                                                                <span class="badge bg-gray">No Data</span>
                                                                            @endif
                                                                        </td>

                                                                        <td>
                                                                            @if ($item->payment_advice == 1)
                                                                                <span class="badge bg-red">
                                                                                    {{-- Not Sent --}}
                                                                                    Pending
                                                                                </span>
                                                                            @elseif($item->payment_advice == 2)
                                                                                <span class="badge bg-green">
                                                                                    {{-- Sent --}}
                                                                                    Received
                                                                                </span>
                                                                            @else
                                                                            @endif
                                                                        </td>

                                                                        <td class="text-center">
                                                                            <input type="hidden" name="customer_id" value="{{$item->customer_id}}" id="quotation_customer_id">
                                                                            <div class="dropdown">
                                                                                <button
                                                                                    class="btn dropdown-toggle align-text-top t-btn"
                                                                                    data-bs-toggle="dropdown"
                                                                                    aria-expanded="true">
                                                                                    Actions
                                                                                </button>
                                                                                <div class="dropdown-menu dropdown-menu-end d-menu"
                                                                                    style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 39px);"
                                                                                    data-popper-placement="bottom-end">

                                                                                    <a class="dropdown-item" href="#"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#view-quotation" onclick="viewQuotation( {{$item->id}} )">
                                                                                        <i
                                                                                            class="fa-solid fa-eye me-2 text-blue"></i>
                                                                                        View
                                                                                    </a>                                         

                                                                                    @if($item->quotation_status != 5)
                                                                                        <a class="dropdown-item" href="{{route('quotation.download', $item->id)}}">
                                                                                            <i class="fa-solid fa-download me-2 text-blue"></i>
                                                                                            Download
                                                                                        </a>
                                                                                    @endif

                                                                                    @if($item->quotation_status == 1 || $item->quotation_status == 2)
                                                                                        <a class="dropdown-item" href="#"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#edit-quotation" onclick="editQuotation({{$item->id}})">
                                                                                            <i
                                                                                                class="fa-solid fa-pencil me-2 text-yellow"></i>
                                                                                            Edit
                                                                                        </a>
                                                                                    @endif
                                                                                                                        
                                                                                    @if($item->quotation_status != 5 && $item->quotation_status != 4 && $item->payment_advice == 1)
                                                                                        <a class="dropdown-item" href="#quotation-payment"  onclick="paymentsProcess({{ $item->id }})">
                                                                                            <i class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                                            Send Payment Advice
                                                                                        </a>                        
                                                                                    @elseif($item->quotation_status == 3 && $item->payment_advice == 2)
                                                                                        <a class="dropdown-item"
                                                                                        href="#" onclick="received_payment({{ $item->id }})">
                                                                                            <i
                                                                                                class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                                                Payment
                                                                                        </a>
                                                                                    @endif
                                                                                    
                                                                                    @if($item->quotation_status == 5)
                                                                                        <a class="dropdown-item" onclick="change_status({{ $item->id }})" href="#">
                                                                                            <i class="fa-solid fa-edit me-2 text-green"></i>
                                                                                            Change Rejected Quotation Status
                                                                                        </a>    
                                                                                    @endif

                                                                                    {{-- @if($item->quotation_status == 3) --}}
                                                                                        <a class="dropdown-item" onclick="duplicate({{ $item->id }})" href="#">
                                                                                            <i class="fa-solid fa-copy me-2 text-yellow"></i>
                                                                                            Duplicate
                                                                                        </a>   
                                                                                    {{-- @endif --}}

                                                                                    <a class="dropdown-item" onclick="delete_quotation({{ $item->id }})" href="#">
                                                                                        <i class="fa-solid fa-trash me-2 text-red"></i>
                                                                                        Delete
                                                                                    </a>

                                                                                    <a class="dropdown-item" href="{{route('quotation.log-report', $item->id)}}">
                                                                                        <i class="fa-solid fa-file me-2 text-blue"></i>
                                                                                        Log Report
                                                                                    </a>

                                                                                    
                                                                                    <a class="dropdown-item send_mail_btn" href="#" title="Send Email" data-quotation_id="{{$item->id}}" data-invoice_no="{{$item->invoice_no}}" data-customer_id="{{$item->customer_id}}" data-schedule_date="{{$item->schedule_date}}" data-quotation_no="{{$item->quotation_no}}">
                                                                                        <i class="fa fa-envelope me-2 text-blue"></i>
                                                                                        Send Email
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>

                                                                    @php
                                                                        $i+=1;
                                                                    @endphp
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            
                                            {{-- <div class="card-footer d-flex align-items-center">
                                                <p class="m-0 text-muted">Showing <span>1</span> to <span>1</span> of
                                                    <span>1</span> entries
                                                </p>
                                                <ul class="pagination m-0 ms-auto">

                                                </ul>
                                            </div> --}}
                                        </div>
                                        <div class="tab-pane" id="commercial" role="tabpanel">
                                            {{-- <div class="card-body border-bottom py-3">
                                                <div class="d-flex">
                                                    <div class="text-muted">
                                                       Show
                                                        <div class="mx-2 d-inline-block">
                                                            <input type="text" class="form-control form-control-sm"
                                                                value="" size="3"
                                                                aria-label="Invoices count">
                                                        </div>
                                                        entries
                                                    </div>

                                                    <div class="ms-auto text-muted">
                                                        Search:
                                                        <div class="ms-2 d-inline-block">
                                                            <input type="text" class="form-control form-control-sm"
                                                                aria-label="Search invoice">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div> --}}

                                            <div class="card-body">
                                                <div class="table-responsive" style="min-height: 500px;">
                                                    <table class="table card-table table-vcenter text-left text-nowrap datatable" id="commercial-table" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="w-1">No.</th>
                                                                <th>Quotation No.</th>
                                                                <th>Service Type</th>
                                                                <th>Expiration Date</th>
                                                                <th>Total Amount</th>
                                                                <th>Created By</th>
                                                                <th>Stage</th>
                                                                <th>Payment Advice</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $i=0;
                                                            @endphp
                                                            @foreach ($quotation as $key => $item)
                                                                @if ($item->customer_type == 'commercial_customer_type')
                                                                    <tr>
                                                                        <td>{{ $i + 1 }}</td>
                                                                        <td><span
                                                                                class="text-muted">{{ $item->quotation_no }}</span>
                                                                        </td>
                                                                        <td>{{$item->company_name}}</td>
                                                                        <td>
                                                                            {{ date('d-m-Y', strtotime($item->created_at. ' + 14 days'))}}
                                                                        </td>
                                                                        <td>${{ $item->grand_total }}</td>
                                                                        <td>{{$item->created_by_name}}</td>
                                                                        
                                                                        <td>
                                                                            @if(isset($item))
                                                                                @if($item->quotation_status == 1)
                                                                                    <span class="badge bg-yellow">Pending</span>
                                                                                @elseif($item->quotation_status == 2)
                                                                                    <span class="badge bg-red">Pending Customer Approval</span>
                                                                                @elseif($item->quotation_status == 3)
                                                                                    <span class="badge bg-red">Pending Payment</span>
                                                                                @elseif($item->quotation_status == 4)                      
                                                                                    {{-- <span class="badge bg-green">{{ucfirst(str_replace("_", " ", $item->payment_status))}}</span>                                                                           --}}
                                                                                    <span class="badge bg-green">Approved</span>
                                                                                @elseif($item->quotation_status == 5)
                                                                                    <span class="badge bg-red">Rejected</span>                                                                  
                                                                                @endif
                                                                            @else
                                                                                <!-- Handle the case when $item is not defined (no data in the table) -->
                                                                                <span class="badge bg-gray">No Data</span>
                                                                            @endif
                                                                        </td>

                                                                        <td>
                                                                            @if ($item->payment_advice == 1)
                                                                                <span class="badge bg-red">
                                                                                    {{-- Not Sent --}}
                                                                                    Pending
                                                                                </span>
                                                                            @elseif($item->payment_advice == 2)
                                                                                <span class="badge bg-green">
                                                                                    {{-- Sent --}}
                                                                                    Received
                                                                                </span>
                                                                            @else
                                                                            @endif
                                                                        </td>

                                                                        <td class="text-center">
                                                                            <div class="dropdown">
                                                                                <button
                                                                                    class="btn dropdown-toggle align-text-top t-btn"
                                                                                    data-bs-toggle="dropdown"
                                                                                    aria-expanded="true">
                                                                                    Actions
                                                                                </button>
                                                                                <div class="dropdown-menu dropdown-menu-end d-menu"
                                                                                    style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 39px);"
                                                                                    data-popper-placement="bottom-end">
                                                                                    <a class="dropdown-item" href="#"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#view-quotation" onclick="viewQuotation( {{$item->id}} )">
                                                                                        <i
                                                                                            class="fa-solid fa-eye me-2 text-blue"></i>
                                                                                        View
                                                                                    </a>

                                                                                    @if($item->quotation_status == 1 || $item->quotation_status == 2)
                                                                                        <a class="dropdown-item" href="#"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#edit-quotation" onclick="editQuotation({{$item->id}})">
                                                                                            <i
                                                                                                class="fa-solid fa-pencil me-2 text-yellow"></i>
                                                                                            Edit
                                                                                        </a>
                                                                                    @endif
                                                                                
                                                                                    @if($item->quotation_status != 5)
                                                                                        <a class="dropdown-item" href="{{route('quotation.download', $item->id)}}">
                                                                                            <i class="fa-solid fa-download me-2 text-blue"></i>
                                                                                            Download
                                                                                        </a>
                                                                                    @endif
                                                                                                                                                        
                                                                                    @if($item->quotation_status != 5 && $item->quotation_status != 4 && $item->payment_advice == 1)
                                                                                        <a class="dropdown-item" href="#quotation-payment"  onclick="paymentsProcess({{ $item->id }})">
                                                                                            <i class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                                            Send Payment Advice
                                                                                        </a>                      
                                                                                    @elseif($item->quotation_status == 3 && $item->payment_advice == 2)
                                                                                        <a class="dropdown-item"
                                                                                        href="#" onclick="received_payment({{ $item->id }})">
                                                                                            <i
                                                                                                class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                                                Payment
                                                                                        </a>
                                                                                    @endif

                                                                                    @if($item->quotation_status == 5)
                                                                                        <a class="dropdown-item" onclick="change_status({{ $item->id }})" href="#">
                                                                                            <i class="fa-solid fa-edit me-2 text-green"></i>
                                                                                            Change Rejected Quotation Status
                                                                                        </a>    
                                                                                    @endif

                                                                                    {{-- @if($item->quotation_status == 3) --}}
                                                                                        <a class="dropdown-item" onclick="duplicate({{ $item->id }})" href="#">
                                                                                            <i class="fa-solid fa-copy me-2 text-yellow"></i>
                                                                                            Duplicate
                                                                                        </a>   
                                                                                    {{-- @endif --}}

                                                                                    <a class="dropdown-item" onclick="delete_quotation({{ $item->id }})" href="#">
                                                                                        <i class="fa-solid fa-trash me-2 text-red"></i>
                                                                                        Delete
                                                                                    </a>

                                                                                    <a class="dropdown-item" href="{{route('quotation.log-report', $item->id)}}">
                                                                                        <i class="fa-solid fa-file me-2 text-blue"></i>
                                                                                        Log Report
                                                                                    </a>

                                                                                    <a class="dropdown-item send_mail_btn" href="#" title="Send Email" data-quotation_id="{{$item->id}}" data-invoice_no="{{$item->invoice_no}}" data-customer_id="{{$item->customer_id}}" data-schedule_date="{{$item->schedule_date}}" data-quotation_no="{{$item->quotation_no}}">
                                                                                        <i class="fa fa-envelope me-2 text-blue"></i>
                                                                                        Send Email
                                                                                    </a>

                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>

                                                                    @php
                                                                        $i+=1;
                                                                    @endphp
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            {{-- <div class="card-footer d-flex align-items-center">
                                                <p class="m-0 text-muted">Showing <span>1</span> to <span>1</span> of
                                                    <span>1</span> entries
                                                </p>
                                                <ul class="pagination m-0 ms-auto">

                                                </ul>
                                            </div> --}}


                                        </div>

                                    </div>

                                </div>
                            </div>
                            
                        </div>




                    </div>
                </div>
            </div>
        </div>
        <footer class="footer footer-transparent d-print-none">

        </footer>
    </div>
    </div>

    <div class="modal modal-blur fade" id="add-quotation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document">
            <div class="modal-content" id="add-quotation-content">

                <!-- <div class="modal-footer">
                    <button type="button" class="btn me-auto sw-btn-prev sw-btn">Previous</button>
                    <button type="button" class="btn btn-primary next-btn" >Next</button>
                </div> -->
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="edit-quotation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document">
            <div class="modal-content" id="editQoutationModal">
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="view-quotation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document" id="viewModalQoutation">

        </div>
    </div>

    {{-- -------send payment-------------------------- --}}
    <div class="modal modal-blur fade" id="quotation-payment" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document">
            <div class="modal-content" id="quotation-payment-content">

            </div>
        </div>
    </div>

     {{-- -------received payment-------------------------- --}}
     <div class="modal modal-blur fade" id="quotation_received_payment" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document">
            <div class="modal-content" id="quotation_received_payment_content">

            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="add-customer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 800px;">
            <div class="modal-content" id="add-customer-content">

            </div>
        </div>
    </div>

    {{-- <div class="modal modal-blur fade" id="send-quotation" tabindex="-1" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Quotation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="smartwizard3" style="border: none;" dir=""
                        class="sw sw-theme-basic sw-justified">
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
                                            <select class="form-select" aria-label="Default select example"
                                                id="emailTemplateOption" onchange="findTemplateId()">
                                                @foreach ($emailTemplates as $emailTemplate)
                                                    <option value="{{ $emailTemplate->id }}">
                                                        {{ $emailTemplate->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @foreach ($emailTemplates as $emailTemplate)
                                                <input type="hidden" value="{{ $emailTemplate->id }}"
                                                    id="emailTemplateId">
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div id="send-step-3" class="tab-pane" role="tabpanel" aria-labelledby="send-step-3" style="display: none;">

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
    </div> --}}

    <div class="modal modal-blur fade" id="send-email" tabindex="-1" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                      @csrf
                          <div class="col-md-12 mb-4">
                              <label for=""><b>To</b></label><br><br>
                              <input type="text" class="form-control" name="title" placeholder="Enter Title">
                          </div>
                          <div class="col-md-12 mb-4">
                              <label for=""><b>CC</b></label><br><br>
                              <input type="text" class="form-control" name="title" placeholder="Enter Title">
                          </div>
                          <div class="col-md-12 mb-4">
                              <label for=""><b>BCC</b></label><br><br>
                              <input type="text" class="form-control" name="title" placeholder="Enter Title">
                          </div>
                          <div class="col-md-12 mb-4">
                              <label for=""><b>Subject</b></label><br><br>
                              <input type="text" class="form-control" name="subject" placeholder="Enter Subject">
                          </div>
                          <div class="col-md-12">
                              <label for=""><b>Body</b></label><br><br>
                              <textarea name="body" id="body" cols="30" rows="10"></textarea>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button type="submit" class="btn btn-info">send</button>
                      </div>
                    </form>
                  </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="add-branch" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">UEN <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="example-text-input"
                                    placeholder="Enter Id Number">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">Branch Name</label>
                                <input type="text" class="form-control" name="example-text-input"
                                    placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">Person Incharge Name </label>
                                <input type="text" class="form-control" name="example-text-input"
                                    placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">Nickname</label>
                                <input type="text" class="form-control" name="example-text-input"
                                    placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" class="form-control" name="example-text-input"
                                    placeholder="Enter Number">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">Fax Number</label>
                                <input type="text" class="form-control" name="example-text-input"
                                    placeholder="Enter Number">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" name="example-text-input"
                                    placeholder="Enter Email">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="example-text-input"
                                    placeholder="Enter Address">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">Postal Code</label>
                                <input type="text" class="form-control" name="example-text-input"
                                    placeholder="Enter Code">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">Country</label>
                                <select class="form-select">
                                    <option selected="">India</option>
                                    <option value="One">Singapore</option>

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Cancel
                    </a>
                    <a href="#" class="btn btn-primary ms-auto" data-bs-dismiss="modal">

                        Save
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Change rejected quotation status --}}
    <div class="modal modal-blur fade" id="change_status_modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                    <svg  xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-success icon-lg" width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>
                    <h3>Are you sure?</h3>
                    <div class="text-muted">Do you really want to change the status?</div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <a href="#" class="btn w-100" data-bs-dismiss="modal" data-bs-dismiss="modal">
                                    Cancel
                                </a>
                            </div>
                            <div class="col">
                                <a href="#" class="btn btn-success w-100" id="change_status_modal_btn">
                                    Confirm
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- delete quotation --}}
    <div class="modal modal-blur fade" id="delete_modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M12 9v2m0 4v.01"></path>
                        <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75">
                        </path>
                    </svg>
                    <h3>Are you sure?</h3>
                    <div class="text-muted">Do you really want to delete this quotation?</div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <a href="#" class="btn w-100" data-bs-dismiss="modal" data-bs-dismiss="modal">
                                    Cancel
                                </a>
                            </div>
                            <div class="col">
                                <a href="#" class="btn btn-danger w-100" id="delete_modal_btn">
                                    Delete
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- duplicate quotation --}}
    <div class="modal modal-blur fade" id="duplicate_modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-success"></div>
                <div class="modal-body text-center py-4">
                    <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                    <svg  xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-success icon-lg" width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>
                    <h3>Are you sure?</h3>
                    <div class="text-muted">Do you really want to duplicate this quotation?</div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <a href="#" class="btn w-100" data-bs-dismiss="modal" data-bs-dismiss="modal">
                                    Cancel
                                </a>
                            </div>
                            <div class="col">
                                <a href="#" class="btn btn-success w-100" id="duplicate_modal_btn">
                                    Duplicate
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- send email --}}
    <div class="modal modal-blur fade" id="send_email_modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="smartwizard3" style="border: none;" dir="" class="sw sw-theme-basic sw-justified">
                        <ul class="nav d-none" style="">
                            <li class="nav-item">
                                <a class="nav-link default" href="#send-email-step-1">
                                    <div class="num">1</div>
                                    1
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link default" href="#send-email-step-2">
                                    <span class="num">2</span>
                                    2
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content p-0" style="border: none; height: 260px;">
                            <div id="send-email-step-1" class="tab-pane" role="tabpanel" aria-labelledby="send-email-step-1"
                                style="display: none;">
                                <div class="row">
                                    <div class="mb-3">
                                        <label class="form-label">Select Email Template</label>
                                        <div class="row g-2">
                                            <select class="form-select select2" aria-label="Default select example"
                                                id="send_email_template_id" onchange="quotation_findTemplateId()">
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
    
                            <div id="send-email-step-2" class="tab-pane" role="tabpanel" aria-labelledby="send-email-step-2"
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

    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script> --}}

    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

    <script>
        $(document).ready(function() {

            var successMessage = localStorage.getItem('successMessage');
            if (successMessage) {
                iziToast.success({
                    message: successMessage,
                    position: 'topRight',
                    timeout: false,   // disables auto-close
                    close: true       // adds a close (×) button
                });

                // Remove the message so it doesn't show again on the next refresh
                localStorage.removeItem('successMessage');
            }

            var quotation_residantial = $("#quotation-residantial").DataTable({
                "lengthChange": false,
                "pageLength": 30,
                'columnDefs': [{
                    'targets': [0],        // Targets the first column (index 0)
                    'orderable': false,    // Disables sorting on this column
                }]
            }).on('order.dt search.dt', function() {
                var table = $('#quotation-residantial').DataTable();
                table.column(0, {order:'applied'}).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;  // Update the serial number
                });
            }).draw();

            var commercial_table = $("#commercial-table").DataTable({
                "lengthChange": false,
                "pageLength": 30,
                'columnDefs': [{
                    'targets': [0],        // Targets the first column (index 0)
                    'orderable': false,    // Disables sorting on this column
                }]
            }).on('order.dt search.dt', function() {
                var table = $('#commercial-table').DataTable();
                table.column(0, {order:'applied'}).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;  // Update the serial number
                });
            }).draw();

            paymentsProcess = function(quotationId) {
                // console.log('hello');
                $.ajax({
                    url: "{{ route('quotation.send-payment') }}?quotation_id=" + quotationId,
                    type: "GET",
                    success: function(response) {
                       //  console.log(hello);
                        $('#quotation-payment').modal('show');
                        $('#quotation-payment-content').html(response);
                        // initializeModalAndSmartWizard(response, leadId);
                    },
                    error: function(response) {
                        console.log(response);
                        console.log('Error occurred while loading the modal content.');
                    }
                });
            }

        });

       ClassicEditor
             .create(document.querySelector('#body'))
             .catch(error => {
                console.error(error);
             });

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

        function addQuotation() {
            $.ajax({
                url: "{{ route('quotation.create') }}",
                type: 'GET',
                success: function(response) {
                    $('#add-quotation').modal('show');
                    $('#add-quotation-content').html(response);

                },
                error: function() {
                    console.log('Error occurred while loading the modal content.');
                }
            });
        }

        function getCustomerDetails(companyId){
            // console.log(companyId);
            $.ajax({
                url: '{{route('get.residential.data')}}',
                type: 'POST',
                data: {
                    company_id: companyId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    // console.log(response);
                    
                    $("#quotation-residantial").DataTable().destroy();

                    $('#quotation-residantial tbody').html(response);

                    $("#quotation-residantial").DataTable({
                        "lengthChange": false,
                        "pageLength": 30,
                        'columnDefs': [{
                            'targets': [0],        // Targets the first column (index 0)
                            'orderable': false,    // Disables sorting on this column
                        }]
                    }).on('order.dt search.dt', function() {
                        var table = $('#quotation-residantial').DataTable();
                        table.column(0, {order:'applied'}).nodes().each(function(cell, i) {
                            cell.innerHTML = i + 1;  // Update the serial number
                        });
                    }).draw();
                },
            });

            $.ajax({
                url: '{{route('get.commercial.data')}}',
                type: 'POST',
                data: {
                    company_id: companyId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    // console.log(response);

                    $("#commercial-table").DataTable().destroy();

                    $('#commercial-table tbody').html(response);

                    $("#commercial-table").DataTable({
                        "lengthChange": false,
                        "pageLength": 30,
                        'columnDefs': [{
                            'targets': [0],        // Targets the first column (index 0)
                            'orderable': false,    // Disables sorting on this column
                        }]
                    }).on('order.dt search.dt', function() {
                        var table = $('#commercial-table').DataTable();
                        table.column(0, {order:'applied'}).nodes().each(function(cell, i) {
                            cell.innerHTML = i + 1;  // Update the serial number
                        });
                    }).draw();
                },
            });
        }

        function viewQuotation(quotation_id){
            // console.log(quotation_id);

            $.ajax({
                type: 'GET',
                url: '{{route('quotation.view')}}',
                data: { quotationId : quotation_id },
                success: function(response) {
                    // $('#view-quotation').modal('show');
                    $('#viewModalQoutation').html(response);

                },
            });

        }

        function editQuotation(quotationId){
            // console.log(quotation_id);

            $.ajax({
                type: 'GET',
                url: '{{route('quotation.edit')}}',
                data: { quotationId : quotationId },
                success: function(response) {
                    // $('#view-quotation').modal('show');
                    $('#editQoutationModal').html(response);

                },
            });

        }

        var customerId = $('#quotation_customer_id').val();        
        // var templateId = $('#emailTemplateId').val();

        // function findTemplateId() {
    
        //     $.ajax({
        //         url: '{{ route('get.quotation.email') }}',
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
        //     })

        // }


        function searchResidentialCustomer(){
            var searchValue = $('#search-quotation').val();
            // console.log(text);
            // if(searchValue != ''){
                $.ajax({
                    url:'{{route('search.residential.quotation')}}',
                    type:'post',
                    data:{
                        _token: '{{ csrf_token() }}',
                        search_value:searchValue
                    },
                    success: function(response){
                        // console.log(response);
                        $('#quotation-residantial tbody').empty();
                        $('#quotation-residantial tbody').html(response);
                    }
                })
            // }

        }

        function received_payment(quotationId)
        {
            $.ajax({
                url: "{{ route('quotation.received-payment') }}",
                type: "GET",
                data: {quotation_id: quotationId},
                success: function(response) {
                    // console.log(response);
                    $('#quotation_received_payment').modal('show');
                    $('#quotation_received_payment_content').html(response);
                },
                error: function(response) {
                    console.log(response);
                    console.log('Error occurred while loading the modal content.');
                }
            });
        }

        // change rejected quotation status start

        function change_status(quotation_id)
        {       
            $("#change_status_modal_btn").data('quotation_id', quotation_id);
            $("#change_status_modal").modal('show');
        }

        $(document).ready(function () {
            
            $('body').on('click', '#change_status_modal_btn', function(){

                var quotation_id = $("#change_status_modal_btn").data('quotation_id');

                $.ajax({
                    type: "post",
                    url: "{{route('quotation.change-status')}}",
                    data: {quotation_id: quotation_id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (result) {
                        $('#change_status_modal').modal('hide');

                        if(result.status == "success")
                        {
                            iziToast.success({
                                message: result.message,
                                position: 'topRight'
                            });
                        }
                        else
                        {
                            iziToast.error({
                                message: result.message,
                                position: 'topRight'
                            });
                        }
                
                        location.reload();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });

            });

        });

        // change rejected quotation status end

        function showCRMModal() 
        {
            $.ajax({
                url: "{{ route('createCustomer.create') }}",
                type: "GET",
                success: function(response) {
                    $('#add-customer').modal('show');
                    $('#add-customer-content').html(response);
                },
                error: function() {
                    console.log('Error occurred while loading the modal content.');
                }
            });
        }

        // received payment start

        $(document).ready(function () {         

            $('body').on('submit', '#quotation_received_payment_form', function(e){

                e.preventDefault();

                var data = new FormData($(this)[0]);

                $.ajax({
                    url: "{{ route('quotation.received-payment.store') }}",
                    method: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $("#submit_btn").attr('disabled', true);
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

                            // Store the success message in localStorage
                            localStorage.setItem('successMessage', response.message);

                            $('#quotation_received_payment').modal('hide');
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
                        $("#submit_btn").attr('disabled', false);
                    },

                });

            });

        });

        // received payment end

        // delete quotation start

        function delete_quotation(quotation_id)
        {       
            $("#delete_modal_btn").data('quotation_id', quotation_id);
            $("#delete_modal").modal('show');
        }

        $(document).ready(function () {
            
            $('body').on('click', '#delete_modal_btn', function(){

                var quotation_id = $("#delete_modal_btn").data('quotation_id');

                $.ajax({
                    type: "post",
                    url: "{{route('quotation.delete')}}",
                    data: {quotation_id: quotation_id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (result) {
                        $('#delete_modal').modal('hide');

                        if(result.status == "success")
                        {
                            iziToast.success({
                                message: result.message,
                                position: 'topRight'
                            });
                        }
                        else
                        {
                            iziToast.error({
                                message: result.message,
                                position: 'topRight'
                            });
                        }
                
                        location.reload();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });

            });

        });

        // delete quotation end

        // duplicate quotation start

        function duplicate(quotation_id)
        {
            $("#duplicate_modal_btn").data('quotation_id', quotation_id);
            $("#duplicate_modal").modal('show');
        }

        $(document).ready(function () {
            
            $('body').on('click', '#duplicate_modal_btn', function(){

                var quotation_id = $("#duplicate_modal_btn").data('quotation_id');

                $.ajax({
                    type: "post",
                    url: "{{route('quotation.duplicate')}}",
                    data: {quotation_id: quotation_id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (result) {
                        $('#duplicate_modal').modal('hide');

                        if(result.status == "success")
                        {
                            iziToast.success({
                                message: result.message,
                                position: 'topRight'
                            });
                        }
                        else
                        {
                            iziToast.error({
                                message: result.message,
                                position: 'topRight'
                            });
                        }
                
                        location.reload();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });

            });

        });

        // duplicate quotation end

        // send mail start

        function quotation_findTemplateId()
        {
            var new_schedule_date = $('#send_email_modal').data('schedule_date');
            var quotation_id = $('#send_email_modal').data('quotation_id');
            var temp_invoice_no = $('#send_email_modal').data('invoice_no');
            var customerId = $('#send_email_modal').data('customer_id');
            var quotation_no = $('#send_email_modal').data('quotation_no');

            var templateId = $('#send_email_modal').find('#send_email_template_id').val();

            // console.log(quotation_no);
            
            if(templateId)
            {
                $.ajax({
                    url: "{{ route('quotation.get-email-data') }}",
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'template_id': templateId,
                        'customer_id': customerId,
                        'quotation_id': quotation_id,
                        'temp_invoice_no': temp_invoice_no,
                        'quotation_no': quotation_no,
                    },
                    success: function(response) {
                        // console.log(response);
                        $('#send_email_modal').find('#send-email-step-2').html(response);

                        // var email_cc = document.getElementById('send_email_cc');
                        var email_cc = $("#send_email_modal").find("#send_email_cc")[0];
                        new Tagify(email_cc);

                        // ClassicEditor
                        //             .create(document.querySelector('#email_body'))
                        //             .catch(error => {
                        //                 console.error(error);
                        //             });

                        // Initialize ClassicEditor for the email body
                        ClassicEditor.create(document.querySelector('#send_email_modal #send_email_body'))
                                .then(editor => {
                                    // Assign the editor instance globally if needed
                                    window.editor = editor;
                                })
                                .catch(error => {
                                    console.error(error);
                                });

                        // subject
                        var email_subject = $('#send_email_modal').find("#send_email_subject").val();
                        email_subject = email_subject.replace("##INVOICE_NO##", temp_invoice_no); 
                        email_subject = email_subject.replace("##JOB_DATE##", new_schedule_date);    
                        email_subject = email_subject.replace("##QUOTATION_NO##", quotation_no);                
                        $('#send_email_modal').find("#send_email_subject").val(email_subject);

                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            }
        }

        function quotation_emailSend(event) 
        {
            event.preventDefault;

            // Ensure the email body is updated with the editor content before submitting
            if (window.editor) {
                // Update the hidden textarea value with the content of the editor
                $('#send_email_modal').find('#send_email_body').val(window.editor.getData());
            }

            var email_template_id = $('#send_email_modal').find('#send_email_template_id').val();
            var email_to = $('#send_email_modal').find('#send_email_to').val();
            var email_cc = $('#send_email_modal').find('#send_email_cc').val();
            var email_bcc = $('#send_email_modal').find('#send_email_bcc').val();
            var email_subject = $('#send_email_modal').find('#send_email_subject').val();
            var email_body = $('#send_email_modal').find('#send_email_body').val();

            var quotation_id = $('#send_email_modal').data('quotation_id');

            $.ajax({
                url: "{{ route('quotation.send-email') }}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    email_template_id: email_template_id,
                    email_to: email_to,
                    email_cc: email_cc,
                    email_bcc: email_bcc,
                    email_subject: email_subject,
                    email_body: email_body,
                    quotation_id: quotation_id
                },
                beforeSend: function() {
                    $('#send_email_modal').find("#send_email_confirm_btn").attr('disabled', true);
                },
                success: function(result) {
                    console.log(result);

                    if(result.status == "error")
                    {
                        $.each(result.error, function (key, value) { 

                            iziToast.error({
                                message: value,
                                position: 'topRight',
                            });                
                                                    
                        });
                    }                        
                    else if(result.status == "success")
                    {
                        iziToast.success({
                            message: result.message,
                            position: 'topRight',
                        });

                        location.href = "{{route('quotation')}}";
                    }
                    else
                    {
                        iziToast.error({
                            message: result.message,
                            position: 'topRight',
                        });
                    }    
                },
                error: function(result){
                    console.log(result);
                },
                complete: function() {
                    $('#send_email_modal').find("#send_email_confirm_btn").attr('disabled', false);
                },

            });
        }

        $(document).ready(function () {
            
            // select2

            $('#send_email_modal').find('.select2').select2({
                dropdownParent: $("#send_email_modal")
            });

            $('#smartwizard3').smartWizard({
                transition: {
                    animation: 'slideHorizontal', // Effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
                }
            });

            // close send email modal

            $('#send_email_modal').on('hidden.bs.modal', function () {
                $(this).find('#send-email-step-2').html("");
                $(this).find('#smartwizard3').smartWizard("reset"); 
                $(this).find(".select2").val("").trigger('change');
            });

            $('body').on('click', '.send_mail_btn', function(){

                var invoice_no = $(this).data('invoice_no');
                var customer_id = $(this).data('customer_id');
                var quotation_id = $(this).data('quotation_id');
                var schedule_date = $(this).data('schedule_date');
                var quotation_no = $(this).data('quotation_no');

                // console.log(invoice_no);

                $('#send_email_modal').data('invoice_no', invoice_no);
                $('#send_email_modal').data('customer_id', customer_id);
                $('#send_email_modal').data('quotation_id', quotation_id);
                $('#send_email_modal').data('schedule_date', schedule_date);
                $('#send_email_modal').data('quotation_no', quotation_no);

                // $('#send_email_modal').data('flag', "send_email");

                $('#send_email_modal').modal('show');

            });

            // $('body').on('change', '#send_email_template_id', function () {
            //     // Retrieve the function name set on the modal
            //     var flag = $('#send_email_modal').data('flag');

            //     console.log(flag);

            //     if(flag == "send_email")
            //     {
            //         quotation_findTemplateId();
            //     }
            //     else if(flag == "edit")
            //     {
            //         findTemplateId();
            //     }
            // });

        });

        // send mail end

    </script>
@endsection
