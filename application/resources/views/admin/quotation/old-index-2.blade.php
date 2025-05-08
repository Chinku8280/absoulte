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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" /> 
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
                                            <div class="card-body border-bottom py-3">
                                                <div class="d-flex">
                                                    {{-- <div class="text-muted">
                                                        Show
                                                        <div class="mx-2 d-inline-block">
                                                            <input type="text" class="form-control form-control-sm"
                                                                value="" size="3" aria-label="Invoices count">
                                                        </div>
                                                        entries
                                                    </div> --}}
                                                    <div class="ms-auto text-muted">
                                                        Search:
                                                        <div class="ms-2 d-inline-block">
                                                            <input type="text" id="search-quotation" class="form-control form-control-sm"
                                                                aria-label="Search quotation" onkeypress="searchResidentialCustomer()" onkeydown="searchResidentialCustomer()">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="">
                                                <table
                                                    class="table card-table table-vcenter text-center text-nowrap datatable" id="quotation-residantial">
                                                    <thead>
                                                        <tr>
                                                            <th class="w-1">Sr No.</th>
                                                            <th>Quotation No.</th>
                                                            <th>Customer Name</th>
                                                            <th>Email</th>
                                                            <th>Contact Number</th>
                                                            <th>Created on</th>
                                                            <th>Status</th>
                                                            <th>Payment Advice</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($quotation as $key => $item)
                                                            @if ($item->customer_type == 'residential_customer_type')
                                                                <tr>
                                                                    <td>{{ $key + 1 }}</td>
                                                                    <td><span
                                                                            class="text-muted">{{ $item->quotation_no }}</span>
                                                                    </td>
                                                                    <td>{{ $item->customer_name }}</td>
                                                                    <td>
                                                                        {{ $item->email }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $item->mobile_number }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $item->created_at->format('d M Y') }}
                                                                    </td>
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
                                                                            <span class="badge bg-red">Not Sent</span>
                                                                        @elseif($item->payment_advice == 2)
                                                                            <span class="badge bg-green">Sent</span>
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
                                                                                @if($item->quotation_status == 1 || $item->quotation_status == 2)
                                                                                    <a class="dropdown-item" href="#"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#edit-quotation" onclick="editQuotation({{$item->id}})">
                                                                                        <i
                                                                                            class="fa-solid fa-pencil me-2 text-yellow"></i>
                                                                                        Edit
                                                                                    </a>
                                                                                @endif
                                                                                <!-- <a class="dropdown-item border-bottom"
                                                                                    href="{{route('quotation.delete',$item->id)}}">
                                                                                    <i
                                                                                        class="fa-solid fa-trash me-2 text-red"></i>
                                                                                    Reject
                                                                                </a> -->
                                                                                <!-- <a class="dropdown-item" href="#"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#send-quotation" onclick="sendQuotationModal({{$item->id}})">
                                                                                    <i
                                                                                        class="fa-solid fa-envelope me-2 text-info"></i>
                                                                                    Send Quotation
                                                                                </a>
                                                                                <a class="dropdown-item" href="#"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#send-email">
                                                                                    <i
                                                                                        class="fa-solid fa-envelope me-2 text-success"></i>
                                                                                    Send Email
                                                                                </a> -->
                                                                                {{-- @if($item->quotation_status == 1)
                                                                                <a class="dropdown-item"
                                                                                href="#" onclick="confirmQuotation({{$item->id}})">
                                                                                    <i
                                                                                        class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                                    Confirm Quotation
                                                                                </a>
                                                                                @endif

                                                                                @if($item->payment_status == "unpaid")
                                                                                    <a class="dropdown-item" href="{{route('quotation.payment', $item->id)}}">
                                                                                        <i class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                                        Payment
                                                                                    </a>
                                                                                @endif --}}

                                                                                @if(isset($item))
                                                                                    @if($item->quotation_status != 5 && $item->payment_advice == 1)
                                                                                        <a class="dropdown-item" href="#quotation-payment"  onclick="paymentsProcess({{ $item->id }})">
                                                                                            <i class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                                            Send Payment Advice
                                                                                        </a>                        
                                                                                    @elseif($item->quotation_status == 3 && $item->payment_advice == 2)
                                                                                        <a class="dropdown-item"
                                                                                        href="#" onclick="received_payment({{ $item->id }})">
                                                                                            <i
                                                                                                class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                                                Received Payment
                                                                                        </a>
                                                                                    @endif
                                                                                @endif

                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
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
                                            <div class="card-body border-bottom py-3">
                                                <div class="d-flex">
                                                    {{-- <div class="text-muted">
                                                       Show
                                                        <div class="mx-2 d-inline-block">
                                                            <input type="text" class="form-control form-control-sm"
                                                                value="" size="3"
                                                                aria-label="Invoices count">
                                                        </div>
                                                        entries
                                                    </div> --}}
                                                    <div class="ms-auto text-muted">
                                                        Search:
                                                        <div class="ms-2 d-inline-block">
                                                            <input type="text" class="form-control form-control-sm"
                                                                aria-label="Search invoice">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="">
                                                <table
                                                    class="table card-table table-vcenter text-center text-nowrap datatable" id="commercial-table">
                                                    <thead>
                                                        <tr>
                                                            <th class="w-1">Sr No.</th>
                                                            <th>Quotation No.</th>
                                                            <th>Company Name</th>
                                                            <th>Email</th>
                                                            <th>Contact Number</th>
                                                            <th>Created on</th>
                                                            <th>Status</th>
                                                            <th>Payment Advice</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($quotation as $key => $item)
                                                            @if ($item->customer_type == 'commercial_customer_type')
                                                                <tr>
                                                                    <td>{{ $key + 1 }}</td>
                                                                    <td><span
                                                                            class="text-muted">{{ $item->quotation_no }}</span>
                                                                    </td>
                                                                    {{-- <td>{{ $item->customer_name }}</td> --}}
                                                                    <td>{{ $item->individual_company_name }}</td>
                                                                    <td>
                                                                        {{ $item->email }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $item->mobile_number }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $item->created_at->format('d M Y') }}
                                                                    </td>
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
                                                                            <span class="badge bg-red">Not Sent</span>
                                                                        @elseif($item->payment_advice == 2)
                                                                            <span class="badge bg-green">Sent</span>
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
                                                                            <!-- <a class="dropdown-item border-bottom"
                                                                                href="{{route('quotation.delete',$item->id)}}">
                                                                                <i
                                                                                    class="fa-solid fa-trash me-2 text-red"></i>
                                                                                Reject
                                                                            </a> -->
                                                                            <!-- <a class="dropdown-item" href="#"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#send-quotation" onclick="sendQuotationModal({{$item->id}})">
                                                                                <i
                                                                                    class="fa-solid fa-envelope me-2 text-info"></i>
                                                                                Send Quotation
                                                                            </a>
                                                                            <a class="dropdown-item" href="#"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#send-email">
                                                                                <i
                                                                                    class="fa-solid fa-envelope me-2 text-success"></i>
                                                                                Send Email
                                                                            </a> -->
                                                                            {{-- @if($item->quotation_status == 1)
                                                                            <a class="dropdown-item"
                                                                            href="#" onclick="confirmQuotation({{$item->id}})">
                                                                                <i
                                                                                    class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                                Confirm Quotation
                                                                            </a>
                                                                            @endif

                                                                            @if($item->payment_status == "unpaid")
                                                                                <a class="dropdown-item" href="{{route('quotation.payment', $item->id)}}">
                                                                                    <i class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                                    Payment
                                                                                </a>
                                                                            @endif --}}
                                                                            
                                                                            @if(isset($item))
                                                                                @if($item->quotation_status != 5 && $item->payment_advice == 1)
                                                                                    <a class="dropdown-item" href="#quotation-payment"  onclick="paymentsProcess({{ $item->id }})">
                                                                                        <i class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                                        Send Payment Advice
                                                                                    </a>                      
                                                                                @elseif($item->quotation_status == 3 && $item->payment_advice == 2)
                                                                                    <a class="dropdown-item"
                                                                                    href="#" onclick="received_payment({{ $item->id }})">
                                                                                        <i
                                                                                            class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                                            Received Payment
                                                                                    </a>
                                                                                @endif
                                                                            @endif

                                                                        </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
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
                            
                            {{-- <div class="tab-pane" id="company-l" role="tabpanel">
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
                                            <div class="card-body border-bottom py-3">
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
                                            </div>
                                            <div class="">
                                                <table
                                                    class="table card-table table-vcenter text-center text-nowrap datatable">
                                                    <thead>
                                                        <tr>
                                                            <th class="w-1">Sr No.</th>
                                                            <th>Quotation No.</th>

                                                            <th>Customer Name</th>
                                                            <th>Email</th>
                                                            <th>Contact Number</th>
                                                            <th>Created on</th>
                                                            <th>Status</th>
                                                            <th></th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>01</td>
                                                            <td><span class="text-muted">001401</span></td>
                                                            <td>Jhone Doe</td>
                                                            <td>
                                                                jhondoe@gmail.com
                                                            </td>
                                                            <td>
                                                                87956621
                                                            </td>
                                                            <td>
                                                                15 Dec 2017
                                                            </td>
                                                            <td>
                                                                @if(isset($item))
                                                                    @if($item->quotation_status == 2)
                                                                        <span class="badge bg-yellow">Pending Payment</span>
                                                                    @elseif($item->quotation_status == 1)
                                                                        <span class="badge bg-red">Pending</span>
                                                                    @elseif($item->quotation_status == 3)
                                                                        <span class="badge bg-green">Confirmed</span>
                                                                    @elseif($item->quotation_status == 4)
                                                                        <span class="badge bg-green">Paid</span>
                                                                    @endif
                                                                @else
                                                                    <!-- Handle the case when $item is not defined (no data in the table) -->
                                                                    <span class="badge bg-gray">No Data</span>
                                                                @endif
                                                            </td>


                                                            <td class="text-center">
                                                                <div class="dropdown">
                                                                    <button class="btn dropdown-toggle align-text-top show"
                                                                        data-bs-toggle="dropdown" aria-expanded="true">
                                                                        Actions
                                                                    </button>
                                                                    <div class="dropdown-menu dropdown-menu-end"
                                                                        style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 39px);"
                                                                        data-popper-placement="bottom-end">
                                                                        <a class="dropdown-item" href="#">
                                                                            <i class="fa-solid fa-eye me-2 text-blue"></i>
                                                                            View
                                                                        </a>
                                                                        <a class="dropdown-item" href="#">
                                                                            <i
                                                                                class="fa-solid fa-pencil me-2 text-yellow"></i>
                                                                            Edit
                                                                        </a>
                                                                        <!-- <a class="dropdown-item border-bottom"
                                                                            href="#">
                                                                            <i class="fa-solid fa-trash me-2 text-red"></i>
                                                                            Delete
                                                                        </a> -->
                                                                        <!-- <a class="dropdown-item" href="#"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#send-quotation">
                                                                            <i
                                                                                class="fa-solid fa-envelope me-2 text-info"></i>
                                                                            Send Quotation
                                                                        </a>
                                                                        <a class="dropdown-item" href="#"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#confirm-quotation">
                                                                            <i
                                                                                class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                            Confirm Quotation
                                                                        </a> -->
                                                                        @if(isset($item))
                                                                        @if($item->payment_status == "unpaid")
                                                                        <a class="dropdown-item" href="#quotation-payment"  onclick="paymentsProcess({{ $item->id }})">
                                                                            <i class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                            Send Payment Advice
                                                                        </a>
                                                                        @endif
                                                                        
                                                                        <a class="dropdown-item"
                                                                        href="#" onclick="received_payment({{ $item->id }})">
                                                                            <i
                                                                                class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                                Received Payment
                                                                        </a>
                                                                        @endif
                                                                        
                                                                    </div>
                                                                </div>

                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="card-footer d-flex align-items-center">
                                                <p class="m-0 text-muted">Showing <span>1</span> to <span>1</span> of
                                                    <span>1</span> entries
                                                </p>
                                                <ul class="pagination m-0 ms-auto">

                                                </ul>
                                            </div>


                                        </div>
                                        <div class="tab-pane" id="commercial" role="tabpanel">
                                            <div class="card-body border-bottom py-3">
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
                                            </div>
                                            <div class="">
                                                <table
                                                    class="table card-table table-vcenter text-center text-nowrap datatable">
                                                    <thead>
                                                        <tr>
                                                            <th class="w-1">Sr No.</th>
                                                            <th>Quotation No.</th>

                                                            <th>Customer Name</th>
                                                            <th>Email</th>
                                                            <th>Contact Number</th>
                                                            <th>Created on</th>
                                                            <th>Status</th>
                                                            <th></th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>01</td>
                                                            <td><span class="text-muted">001401</span></td>
                                                            <td>Jhone Doe</td>
                                                            <td>
                                                                jhondoe@gmail.com
                                                            </td>
                                                            <td>
                                                                87956621
                                                            </td>
                                                            <td>
                                                                15 Dec 2017
                                                            </td>
                                                            <td>
                                                                @if(isset($item))
                                                                    @if($item->quotation_status == 2)
                                                                        <span class="badge bg-yellow">Pending Payment</span>
                                                                    @elseif($item->quotation_status == 1)
                                                                        <span class="badge bg-red">Pending</span>
                                                                    @elseif($item->quotation_status == 3)
                                                                        <span class="badge bg-green">Confirmed</span>
                                                                    @elseif($item->quotation_status == 4)
                                                                        <span class="badge bg-green">Paid</span>
                                                                    @endif
                                                                @else
                                                                    <!-- Handle the case when $item is not defined (no data in the table) -->
                                                                    <span class="badge bg-gray">No Data</span>
                                                                @endif
                                                            </td>


                                                            <td class="text-center">
                                                                <div class="dropdown">
                                                                    <button class="btn dropdown-toggle align-text-top show"
                                                                        data-bs-toggle="dropdown" aria-expanded="true">
                                                                        Actions
                                                                    </button>
                                                                    <div class="dropdown-menu dropdown-menu-end"
                                                                        style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 39px);"
                                                                        data-popper-placement="bottom-end">
                                                                        <a class="dropdown-item" href="#">
                                                                            <i class="fa-solid fa-eye me-2 text-blue"></i>
                                                                            View
                                                                        </a>
                                                                        <a class="dropdown-item" href="#">
                                                                            <i
                                                                                class="fa-solid fa-pencil me-2 text-yellow"></i>
                                                                            Edit
                                                                        </a>
                                                                        <a class="dropdown-item border-bottom"
                                                                            href="#">
                                                                            <i class="fa-solid fa-trash me-2 text-red"></i>
                                                                            Delete
                                                                        </a>
                                                                        <!-- <a class="dropdown-item" href="#"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#send-quotation">
                                                                            <i
                                                                                class="fa-solid fa-envelope me-2 text-info"></i>
                                                                            Send Quotation
                                                                        </a> -->
                                                                        @if(isset($item))
                                                                        @if($item->payment_status == "unpaid")
                                                                            <a class="dropdown-item" href="#quotation-payment"  onclick="paymentsProcess({{ $item->id }})">
                                                                                <i class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                                Send Payment Advice
                                                                            </a>
                                                                            @endif
                                                                            
                                                                            <a class="dropdown-item"
                                                                            href="#" onclick="received_payment({{ $item->id }})">
                                                                                <i
                                                                                    class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                                    Received Payment
                                                                            </a>
                                                                            @endif
                                                                            
                                                                    </div>
                                                                </div>

                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="card-footer d-flex align-items-center">
                                                <p class="m-0 text-muted">Showing <span>1</span> to <span>1</span> of
                                                    <span>1</span> entries
                                                </p>
                                                <ul class="pagination m-0 ms-auto">

                                                </ul>
                                            </div>


                                        </div>

                                    </div>

                                </div>
                            </div> --}}
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
        <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document" id="editQoutationModal">

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
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Customer Type</label>
                        <select class="form-select" id="myselection">
                            <option selected="">Select Option</option>
                            <option value="One">Residential</option>
                            <option value="Two">Commercial</option>
                        </select>
                    </div>
                    <div id="showOne" class="row myDiv" style="display: none;">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-1">
                                <!-- <div class="mb-3">
                                                  <label class="form-label">Person Incharge Name </label>
                                                  <input type="text" class="form-control" name="example-text-input"
                                                      placeholder="Enter Name">
                                              </div> -->
                                <div class="mb-3">
                                    <label class="form-label">Person Incharge Name</label>
                                    <div class="input-group">


                                        <select class="form-select" style="padding: 0.4375rem 1rem 0.4375rem 0.75rem;">
                                            <option value="1">Mr</option>
                                            <option value="2">Miss</option>
                                        </select>
                                        <input type="text" class="form-control w-50" placeholder="Enter Name">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-1">
                                <div class="mb-3">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="text" class="form-control" name="example-text-input"
                                        placeholder="Enter Number">
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-1">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="text" class="form-control" name="example-text-input"
                                        placeholder="Enter Email">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-1">
                                <div class="mb-3">
                                    <label class="form-label">Language Spoken</label>
                                    <select type="text" class="form-select" id="select-countries" value="">
                                        <option value="pl">English</option>
                                        <option value="de">Hindi</option>
                                        <option value="cz">German</option>
                                        <option value="br">Spanish</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-1">
                                <div class="mb-3">
                                    <label class="form-label">Customer Remark</label>
                                    <input type="text" class="form-control" name="example-text-input"
                                        placeholder="Enter Remarks">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <div class="form-label">Select Type</div>
                                    <div class="dropdown" data-control="checkbox-dropdown">
                                        <label class="dropdown-label">Select</label>

                                        <div class="dropdown-list">
                                            <a href="#" data-toggle="check-all"
                                                class="dropdown-option border-bottom text-blue">
                                                Check All
                                            </a>

                                            <label class="dropdown-option">
                                                <input type="checkbox" name="dropdown-group" value="Selection 1" />
                                                Floor Cleaning
                                            </label>

                                            <label class="dropdown-option">
                                                <input type="checkbox" name="dropdown-group" value="Selection 2" />
                                                Home Cleaning
                                            </label>

                                            <label class="dropdown-option">
                                                <input type="checkbox" name="dropdown-group" value="Selection 3" />
                                                Office Cleaning
                                            </label>


                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs"
                                            role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a href="#tabs-1" class="nav-link active" data-bs-toggle="tab"
                                                    aria-selected="true" role="tab">Additional Contact
                                                </a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a href="#tabs-2" class="nav-link" data-bs-toggle="tab"
                                                    aria-selected="false" tabindex="-1" role="tab">Additional
                                                    Info</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a href="#tabs-3" class="nav-link" data-bs-toggle="tab"
                                                    aria-selected="false" tabindex="-1" role="tab">Service
                                                    Address</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a href="#tabs-4" class="nav-link" data-bs-toggle="tab"
                                                    aria-selected="false" tabindex="-1" role="tab">Billing
                                                    Address </a>
                                            </li>


                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane active show" id="tabs-1" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Contact Name </label>
                                                            <input type="text" class="form-control"
                                                                name="example-text-input" placeholder="Enter Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Mobile Number</label>
                                                            <input type="text" class="form-control"
                                                                name="example-text-input" placeholder="Enter Number">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label class="form-label"
                                                            style="visibility: hidden;">Add</label>
                                                        <a href="#" class="btn btn-primary">
                                                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                stroke-width="2" stroke="currentColor" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                </path>
                                                                <path d="M12 5l0 14"></path>
                                                                <path d="M5 12l14 0"></path>
                                                            </svg>

                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tabs-2" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Credit limit</label>
                                                                    <input type="number" class="form-control"
                                                                        name="example-text-input" placeholder="0">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Payment Terms</label>
                                                                    <select class="form-select">
                                                                        <option value="1" selected="">Private
                                                                        </option>
                                                                        <option value="2">6 Month</option>
                                                                        <option value="3">12 Month</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 text-end">
                                                                <a href="#" class="btn btn-primary ms-auto"
                                                                    data-bs-dismiss="modal">
                                                                    Save
                                                                </a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Remark</label>
                                                                    <input type="number" class="form-control"
                                                                        name="example-text-input"
                                                                        placeholder="Enter Remarks">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Status</label>
                                                                    <select class="form-select">
                                                                        <option value="1" selected="">Private
                                                                        </option>
                                                                        <option value="2">Green</option>
                                                                        <option value="3">Red</option>
                                                                        <option value="4">orange</option>
                                                                        <option value="4">Black</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 text-end">
                                                                <a href="#" class="btn btn-primary ms-auto"
                                                                    data-bs-dismiss="modal">
                                                                    Save
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tabs-3" role="tabpanel">

                                                <button type="button" class="btn btn-blue add-row" id="rowAdder_22">+
                                                    Add Address</button>
                                                <div id="newinput_22"></div>
                                                <div class="row my-3">
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="name">Person Incharge Name</label>

                                                            <input type="text" placeholder="Enter Name"
                                                                name="name" class="form-control" required="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="name">Contact No</label>
                                                            <input type="text" placeholder="Enter Number"
                                                                name="name" class="form-control" required="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="name">Email Id</label>
                                                            <input type="text" placeholder="Enter Email"
                                                                name="name" class="form-control" required="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="name">Postal Code</label>
                                                            <input type="text" placeholder="Enter Code"
                                                                name="name" class="form-control" required="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="name">Zone</label>
                                                            <input type="text" placeholder="Enter Zone"
                                                                name="name" class="form-control" required="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="name">Address</label>
                                                            <input type="text" placeholder="Enter Address"
                                                                name="name" class="form-control" required="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="name">Unit No</label>
                                                            <input type="text" placeholder="Enter Unit No."
                                                                name="name" class="form-control" required="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-primary">save</button>
                                                    </div>
                                                    <!-- <div class="col-md-1" style="display: flex; align-items: center;">

                                                              <button type="button" class="btn btn-danger" id="DeleteRow">-</button>
                                                            </div> -->
                                                </div>

                                            </div>
                                            <div class="tab-pane" id="tabs-4" role="tabpanel">
                                                <div class="row">

                                                    <div class="col-md-12">

                                                        <div class="table-responsive mb-3">
                                                            <table
                                                                class="table card-table table-vcenter text-nowrap table-transparent"
                                                                id="billing_address_33">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Postal Code</th>
                                                                        <th>Address</th>
                                                                        <th>Unit No</th>
                                                                        <th>
                                                                            <button type="button"
                                                                                class="btn btn-blue add-row">+</button>

                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <input class="form-control" type="text"
                                                                                placeholder="Enter Code" />
                                                                        </td>
                                                                        <td>
                                                                            <input class="form-control" type="text"
                                                                                placeholder="Address" />
                                                                        </td>
                                                                        <td>
                                                                            <input class="form-control" type="text"
                                                                                placeholder="Enter Unit No" />
                                                                        </td>
                                                                        <td>
                                                                            <button type="button"
                                                                                class="btn btn-danger delete-row">-</button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>


                                                    </div>
                                                    <div class="col-md-12 text-end">
                                                        <button type="button" class="btn btn-blue">save</button>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="showTwo" class="myDiv" style="display: none;">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-1">
                                <div class="mb-3">
                                    <label class="form-label">UEN</label>
                                    <input type="text" class="form-control" name="example-text-input"
                                        placeholder="Enter Id Number">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-1">
                                <div class="mb-3">
                                    <label class="form-label">Group Company Name</label>
                                    <input type="text" class="form-control" name="example-text-input"
                                        placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-1">
                                <div class="mb-3">
                                    <label class="form-label">Individual Company Name</label>
                                    <input type="text" class="form-control" name="example-text-input"
                                        placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-1">
                                <div class="mb-3">
                                    <label class="form-label">Language Spoken</label>
                                    <select type="text" class="form-select" id="select-countries" value="">
                                        <option value="pl">English</option>
                                        <option value="de">Hindi</option>
                                        <option value="cz">German</option>
                                        <option value="br">Spanish</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-1">
                                <div class="mb-3">
                                    <label class="form-label">Customer Remark</label>
                                    <input type="text" class="form-control" name="example-text-input"
                                        placeholder="Enter Remarks">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-1">
                                <div class="mb-3">
                                    <label class="form-label">Branch</label>
                                    <select class="form-select">
                                        <option selected="">1</option>
                                        <option value="One">2</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label" style="visibility: hidden;">Add</label>
                                    <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#add-branch">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M12 5l0 14"></path>
                                            <path d="M5 12l14 0"></path>
                                        </svg>

                                    </a>
                                </div>

                            </div>
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs"
                                            role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a href="#tabs-00" class="nav-link active" data-bs-toggle="tab"
                                                    aria-selected="true" role="tab">Company Info
                                                </a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a href="#tabs-01" class="nav-link" data-bs-toggle="tab"
                                                    aria-selected="true" role="tab">Additional Contact
                                                </a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a href="#tabs-02" class="nav-link" data-bs-toggle="tab"
                                                    aria-selected="false" tabindex="-1" role="tab">Additional
                                                    Info</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a href="#tabs-03" class="nav-link" data-bs-toggle="tab"
                                                    aria-selected="false" tabindex="-1" role="tab">Service
                                                    Address</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a href="#tabs-04" class="nav-link" data-bs-toggle="tab"
                                                    aria-selected="false" tabindex="-1" role="tab">Billing
                                                    Address </a>
                                            </li>


                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane active show" id="tabs-00" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-6 col-sm-1">
                                                        <div class="mb-3">
                                                            <label class="form-label">Contact Name </label>
                                                            <input type="text" class="form-control"
                                                                name="example-text-input" placeholder="Enter Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 col-sm-1">
                                                        <div class="mb-3">
                                                            <label class="form-label">Mobile Number</label>
                                                            <input type="text" class="form-control"
                                                                name="example-text-input" placeholder="Enter Number">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 col-sm-1">
                                                        <div class="mb-3">
                                                            <label class="form-label">Fax Number</label>
                                                            <input type="text" class="form-control"
                                                                name="example-text-input" placeholder="Enter Number">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 col-sm-1">
                                                        <div class="mb-3">
                                                            <label class="form-label">Email</label>
                                                            <input type="text" class="form-control"
                                                                name="example-text-input" placeholder="Enter Email">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tabs-01" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Contact Name </label>
                                                            <input type="text" class="form-control"
                                                                name="example-text-input" placeholder="Enter Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Mobile Number</label>
                                                            <input type="text" class="form-control"
                                                                name="example-text-input" placeholder="Enter Number">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label class="form-label"
                                                            style="visibility: hidden;">Add</label>
                                                        <a href="#" class="btn btn-primary">
                                                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                stroke-width="2" stroke="currentColor" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                </path>
                                                                <path d="M12 5l0 14"></path>
                                                                <path d="M5 12l14 0"></path>
                                                            </svg>

                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tabs-02" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Credit limit</label>
                                                                    <input type="number" class="form-control"
                                                                        name="example-text-input" placeholder="0">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Payment Terms</label>
                                                                    <select class="form-select">
                                                                        <option value="1" selected="">Private
                                                                        </option>
                                                                        <option value="2">6 Month</option>
                                                                        <option value="3">12 Month</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 text-end">
                                                                <a href="#" class="btn btn-primary ms-auto"
                                                                    data-bs-dismiss="modal">
                                                                    Save
                                                                </a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Remark</label>
                                                                    <input type="number" class="form-control"
                                                                        name="example-text-input"
                                                                        placeholder="Enter Remarks">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Status</label>
                                                                    <select class="form-select">
                                                                        <option value="1" selected="">Private
                                                                        </option>
                                                                        <option value="2">Green</option>
                                                                        <option value="3">Red</option>
                                                                        <option value="4">orange</option>
                                                                        <option value="4">Black</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 text-end">
                                                                <a href="#" class="btn btn-primary ms-auto"
                                                                    data-bs-dismiss="modal">
                                                                    Save
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tabs-03" role="tabpanel">

                                                <button type="button" class="btn btn-blue add-row" id="rowAdder-2">+
                                                    Add Address</button>
                                                <div id="newinput-2"></div>
                                                <div class="row my-3">
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="name">Person Incharge Name</label>

                                                            <input type="text" placeholder="Enter Name"
                                                                name="name" class="form-control" required="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="name">Contact No</label>
                                                            <input type="text" placeholder="Enter Number"
                                                                name="name" class="form-control" required="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="name">Email Id</label>
                                                            <input type="text" placeholder="Enter Email"
                                                                name="name" class="form-control" required="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="name">Postal Code</label>
                                                            <input type="text" placeholder="Enter Code"
                                                                name="name" class="form-control" required="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="name">Zone</label>
                                                            <input type="text" placeholder="Enter Zone"
                                                                name="name" class="form-control" required="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="name">Address</label>
                                                            <input type="text" placeholder="Enter Address"
                                                                name="name" class="form-control" required="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="name">Unit No</label>
                                                            <input type="text" placeholder="Enter Unit No."
                                                                name="name" class="form-control" required="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-primary">save</button>
                                                    </div>
                                                    <!-- <div class="col-md-1" style="display: flex; align-items: center;">

                                                              <button type="button" class="btn btn-danger" id="DeleteRow">-</button>
                                                            </div> -->
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tabs-04" role="tabpanel">

                                                <div class="row">

                                                    <div class="col-md-12">

                                                        <div class="table-responsive mb-3">
                                                            <table
                                                                class="table card-table table-vcenter text-nowrap table-transparent"
                                                                id="billing_address_2">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Postal Code</th>
                                                                        <th>Address</th>
                                                                        <th>Unit No</th>
                                                                        <th>
                                                                            <button type="button"
                                                                                class="btn btn-blue add-row-2">+</button>

                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <input class="form-control" type="text"
                                                                                placeholder="Enter Code" />
                                                                        </td>
                                                                        <td>
                                                                            <input class="form-control" type="text"
                                                                                placeholder="Address" />
                                                                        </td>
                                                                        <td>
                                                                            <input class="form-control" type="text"
                                                                                placeholder="Enter Unit No" />
                                                                        </td>
                                                                        <td>
                                                                            <button type="button"
                                                                                class="btn btn-danger delete-row-2">-</button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>


                                                    </div>
                                                    <div class="col-md-12 text-end">
                                                        <button type="button" class="btn btn-blue">save</button>
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

                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="send-quotation" tabindex="-1" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Quotation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="smartwizard2" style="border: none;" dir=""
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
                                            {{-- <select class="form-select" aria-label="Default select example" id="emailTemplateOption">
                                                <option selected>Select Email Template</option>
                                                @foreach($emailTemplates as $emailTemplate)
                                                    <option value="{{$emailTemplate->id}}" id="templateOption">{{$emailTemplate->title}}</option>
                                                @endforeach
                                            </select> --}}
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
                                        <div class="email-attachment">
                                            <div class="file-info">
                                                <div class="file-size">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
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

@endsection

@section('javascript')

    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>

    <script>
        $(document).ready(function() {

            // $('.datatable').DataTable({
            //     "lengthChange": false,
            //     "pageLength": 30,
            // });

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
    </script>
    <script>
       ClassicEditor
             .create(document.querySelector('#body'))
             .catch(error => {
                console.error(error);
             });
    </script>

    <script>
        $(function() {
            $('#billing_address_33 .add-row').click(function() {
                var template =
                    '<tr><td><input class="form-control" type="text" placeholder="Enter Code" /></td><td><input class="form-control" type="text" placeholder="Address"/></td><td><input class="form-control" type="text" placeholder="Enter Unit No"/></td><td><button type="button" class="btn btn-danger delete-row">-</button></td></tr>';
                $('#billing_address_33 tbody').append(template);
            });
            $('#billing_address_33').on('click', '.delete-row', function() {
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
        $('#smartwizard').smartWizard({
            transition: {
                animation: 'slideHorizontal', // Effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
            }
        });
        $('#smartwizard-edit').smartWizard({
            transition: {
                animation: 'slideHorizontal', // Effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
            }
        });
        $('#smartwizard2').smartWizard({
            transition: {
                animation: 'slideHorizontal', // Effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
            }
        });
        $('#smartwizard-3').smartWizard({
            transition: {
                animation: 'slideHorizontal', // Effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
            }
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
        $("#rowAdder_22").click(function() {
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

            $('#newinput_22').append(newRowAdd);
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
    <script>
        $(function() {
            $('#billing_address_add_lead .add-row').click(function() {
                var template =
                    '<tr><td><input class="form-control" type="text" placeholder="Enter Code" /></td><td><input class="form-control" type="text" placeholder="Address"/></td><td><select type="text" class="form-select" value=""><option value="121">Singapore</option><option value="329">India</option></select></td><td><button type="button" class="btn btn-danger delete-row">-</button></td></tr>';
                $('#billing_address_add_lead tbody').append(template);
            });
            $('#billing_address_add_lead').on('click', '.delete-row', function() {
                $(this).parent().parent().remove();
            });
        })
    </script>
    <script>
        $(function() {
            $('#billing_address_add_quot .add-row-edit').click(function() {
                var template =
                    '<tr><td><input class="form-control" type="text" placeholder="Enter Code" /></td><td><input class="form-control" type="text" placeholder="Address"/></td><td><select type="text" class="form-select" value=""><option value="121">Singapore</option><option value="329">India</option></select></td><td><button type="button" class="btn btn-danger delete-row-edit">-</button></td></tr>';
                $('#billing_address_add_quot tbody').append(template);
            });
            $('#billing_address_add_quot').on('click', '.delete-row-edit', function() {
                $(this).parent().parent().remove();
            });
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
        $(document).ready(function() {
            $('#myselection').on('change', function() {
                var demovalue = $(this).val();
                $("div.myDiv").hide();
                $("#show" + demovalue).show();
            });
        });
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
                '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Country</label><select type="text" class="form-select" value=""><option value="11">India</option><option value="39">Singaore</option></select> </div> </div>' +
                '<div class="col-md-3"><div class="form-group mb-3"> <label for="name">Unit No</label><input type="text" placeholder="Enter Unit No." name="name" class="form-control" required=""> </div> </div>' +
                '<div class="col-md-1" style="display: flex; align-items: center;">  <button type="button" class="btn btn-danger" id="DeleteRow">-</button></div></div>';

            $('#newinput').append(newRowAdd);
        });

        $("body").on("click", "#DeleteRow", function() {
            $(this).parents("#row").remove();
        })
    </script>
    <script type="text/javascript">
        $("#rowAdder-edit").click(function() {
            newRowAdd =
                '<div class="row my-3" id="row">  <div class="col-md-4">' +
                '<div class="form-group mb-3">' +
                ' <label for="name">Person Incharge Name</label><input type="text" placeholder="Enter Name" name="name" class="form-control" required=""></div></div>' +
                '<div class="col-md-4"><div class="form-group mb-3"> <label for="name">Contact No</label><input type="text" placeholder="Enter Number" name="name" class="form-control" required=""> </div> </div>' +
                '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Email Id</label><input type="text" placeholder="Enter Email" name="name" class="form-control" required=""></div> </div>' +
                '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Postal Code</label><input type="text" placeholder="Enter Code" name="name" class="form-control" required=""></div> </div>' +
                '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Zone</label><input type="text" placeholder="Enter Zone" name="name" class="form-control" required=""></div> </div>' +
                '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Address</label><input type="text" placeholder="Enter Address" name="name" class="form-control" required=""> </div> </div>' +
                '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Country</label><select type="text" class="form-select" value=""><option value="11">India</option><option value="39">Singaore</option></select> </div> </div>' +
                '<div class="col-md-3"><div class="form-group mb-3"> <label for="name">Unit No</label><input type="text" placeholder="Enter Unit No." name="name" class="form-control" required=""> </div> </div>' +
                '<div class="col-md-1" style="display: flex; align-items: center;">  <button type="button" class="btn btn-danger" id="DeleteRow-edit">-</button></div></div>';

            $('#newinput-edit').append(newRowAdd);
        });

        $("body").on("click", "#DeleteRow-edit", function() {
            $(this).parents("#row").remove();
        })
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
            $(".productsubedit").click(function() {
                $(".productsubshowedit").show();
            });
            $(".packagesubedit").click(function() {
                $(".packagesubshowedit").show();
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
        $(document).ready(function() {
            $("#pills-profile-tab").click(function() {
                $("#package-table").show();
                $("#service-table").hide();

            });
            $("#pills-home-tab").click(function() {
                $("#package-table").hide();
                $("#service-table").show();
            });
        });
        $(document).ready(function() {
            $("#pills-profile-tab-edit").click(function() {
                $("#package-table-edit").show();
                $("#service-table-edit").hide();

            });
            $("#pills-home-tab-edit").click(function() {
                $("#package-table-edit").hide();
                $("#service-table-edit").show();
            });
        });
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
        $(document).ready(function() {



            $("#commercial-edit-table").click(function() {
                $("#residential-card-edit").hide();
                $("#commercial-card-edit").show();

            });
            $("#residential-edit-table").click(function() {

                $("#commercial-card-edit").hide();
                $("#residential-card-edit").show();
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
        $(document).ready(function() {
            // Toggles paragraphs display
            $(".add_btn_edit").click(function() {
                $(".add_address_edit").toggle();
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
        $(document).ready(function() {
            // Toggles paragraphs display
            $(".add_btn_2edit").click(function() {
                $(".add_address_2edit").toggle();
            });
        });
    </script>
    <script>
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
        function closeModal(){

            console.log($('#selected-services-table-tbody').html());
            $('#selected-services-table-tbody').empty();
            $('#add-quotation').modal('hide');
        }
    </script>
    {{-- <script>
        $(document).ready(function() {
            $(".t-btn").click(function() {
                $(".d-menu").toggleClass("show");
            });
        });
    </script> --}}
    <script>
        function Search(type) {
            var search = '';
            var searchBox = '';

            if (type == '1') {
                searchBox = $('#residential_Search');
                search = $('#residential_customer').val();

            } else {
                searchBox = $('#commercialList');
                search = $('#commercial_customer').val();
            }
            // console.log(type);
            console.log(search);
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

                    // console.log(typeof(response));
                    response.forEach(function(item) {
                        console.log('hello');
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
                                        <div class ="card card-active">

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
        // console.log(customerId);
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
                    $('#residential-card-edit').html(`
                    <div class="card-body">
                        <div class="row">
                        <div class="col-md-3">
                            <label class="mb-0" for=""> <b>Customer Name</b> </label>
                            <p class="m-0">${customer.customer_name}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="mb-0" for=""><b>Mobile Number</b> </label>
                            <p class="m-0">${customer.mobile_number}</p>
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
                            $('#commercial-card-edit').hide();
                        } else if (type === 'commercial_customer_type') {
                            $('#commercial-card-edit').html(`
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
                        <p class="m-0"><i class="fa-solid fa-phone me-2 pt-1" style="font-size: 14px;"></i>${customer.mobile_number}</p>
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
                              <div class="row">
                                 <div class="col-md-7">
                                    <p class="m-0"> Total:</p>
                                 </div>
                                 <div class="col-md-5">
                                    <p class="m-0 total-tax-amount"></p>
                                 </div>
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
               ${customer.mobile_number}
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
    var company_id = $('#company-select').val();
    function searchServices() {
        var searchValue = $('#service-search').val();
        var selectedCompanyId = $('#company-select').val();
        console.log('searchValue:', searchValue);
        if (!searchValue) {
            $('.productsubshowedit').hide();
            return;
        }

        $.ajax({
            url: '{{ route('search.service') }}',
            type: 'POST',
            data: {
                search: searchValue,
                company_id: selectedCompanyId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
            console.log('hello');
                var tableBody = $('#serviceTable tbody');
                console.log(tableBody);
                tableBody.empty();

                response.forEach(function(service) {
                    var row = `
                            <tr>
                                <td>${service.id}</td>
                                <td><span class="avatar avatar-sm" style="background-image: url(${service.image_url})"></span></td>
                                <td>${service.service_name}</td>
                                <td>${service.price}</td>
                                <td>
                                    <button class="btn btn-primary ripple add-service-btn" type="button"
                                        data-service-id="${service.id}"
                                        data-service-name="${service.service_name}"
                                        data-service-description="${service.description}"
                                        data-service-price="${service.price}"
                                        data-service-discount="${service.discount}"
                                        data-service-quantity="${service.quantity}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M12 5l0 14"></path>
                                        <path d="M5 12l14 0"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr> `;
                    tableBody.append(row);
                });

                // Show the table with search results
                $('.productsubshowedit').show();
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
    // updateGrossAmounts();
    // $(document).on('click', '.remove-service-btn', function() {
    //     const row = $(this).closest('tr');
    //     row.remove();
    //     updateGrossAmounts();
    // });
    // $(document).on('change', '.quantity-input, .discount-input, .service-tax', function() {
    //     updateGrossAmounts();
    // });

    // function updateGrossAmounts() {
    //     let totalDiscountedAmount = 0;
    //     $('#selected-services-table tbody tr').each(function() {
    //         const quantity = parseInt($(this).find('.quantity-input').val(), 10);

    //         const discount = parseInt($(this).find('.discount-input').val(), 10) || 0;
    //         const unitPrice = parseFloat($(this).find('.price').val(), 10);

    //         const grossAmount = (unitPrice * quantity * (100 - discount) / 100).toFixed(2);

    //         $(this).find('td:nth-child(7)').text(`$${grossAmount}`);
    //         let totalGrossAmount = 0;
    //         $('#selected-services-table tbody tr').each(function() {
    //             const grossAmount = parseFloat($(this).find('td:nth-child(7)').text().replace('$', ''));
    //             totalGrossAmount += grossAmount;
    //         });
    //         const discountedAmount = (unitPrice * quantity * discount / 100).toFixed(2);
    //         totalDiscountedAmount += parseFloat(discountedAmount);
    //         $('.total-gross-amount').text(`$${totalGrossAmount.toFixed(2)}`);
    //         $('.total-discount').text(`$${totalDiscountedAmount.toFixed(2)}`);

    //     });

    //     const tax = parseFloat($('.service-tax').val(), 10) || 0;
    //     let totalGrossAmount = 0;
    //     $('#selected-services-table tbody tr').each(function() {
    //         const grossAmount = parseFloat($(this).find('td:nth-child(7)').text().replace('$', ''));
    //         totalGrossAmount += grossAmount;
    //     });
    //     const totalTaxAmount = (totalGrossAmount * (tax / 100)).toFixed(2);
    //     const totalAmountWithTax = (totalGrossAmount + parseFloat(totalTaxAmount)).toFixed(2);
    //     $('.total-gross-amount').text(`$${totalGrossAmount.toFixed(2)}`);
    //     $('.total-tax-amount').text(`$${totalAmountWithTax}`);
    //     $('#total_amount').text(`$${totalGrossAmount.toFixed(2)}`);
    //     $('.total-tax').text(`$${totalTaxAmount}`);
    //     $('.total-gross-amount-input').val(totalGrossAmount.toFixed(2));
    //     $('.total-amount-input').val(totalAmountWithTax);
    //     $('.total-tax-input').val(totalTaxAmount);
    //     $('.tax_percent').val(tax);

    // }

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
                    console.log(response);
                    $('#quotation-residantial tbody').html(response);
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
                    console.log(response);
                    $('#commercial-table tbody').html(response);
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
</script>
<script>
    var customerId = $('#quotation_customer_id').val()
    var templateId = $('#emailTemplateId').val()
    function findTemplateId() {
    // $('#emailTemplateOption').click(function(){
        console.log('templateId:',templateId);
        $.ajax({
            url: '{{ route('get.quotation.email') }}',
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                'template_id': templateId,
                'customer_id': customerId,
            },
            success: function(response) {
                console.log(response);
                $('#send-step-3').append(response)
            },
        })
    // })
    }

    function sendQuotationModal(customerId){
        // console.log(customerId);
        var customerId = customerId;
        $('#send-quotation').modal('show')
    }

    function emailSend(event) {
        event.preventDefault;
        var company_id = $('#company-select').val()
        // var email_template_id = templateId;
        // var customer_id = customerId;
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
        console.log(event);

        $.ajax({
            url: '{{ route('quotation.send.mail') }}',
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                'company_id': company_id,
                'customer_id': customerId,
                'service_id': service_id,
                'email_template_id': templateId,
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
        $('#send-quotation').modal('hide')
    }

    function confirmQuotation(quotationId){
       // console.log(quotationId);

        $.ajax({
            type:'POST',
            url:"{{route('confirm.quotation')}}",
            data:{
                "_token": "{{ csrf_token() }}",
                'id':quotationId
            },
            success: function(response){
                if (response.redirect) {
                window.location.href = response.redirect;
            }
            }
        });
    }

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

</script>
@endsection
