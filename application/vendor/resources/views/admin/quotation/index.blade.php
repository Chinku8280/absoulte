@extends('theme.default')
<style>
    .dropdown-menu.show {
        display: block !important;
        position: absolute !important;
        inset: 0px 0px auto auto !important;
        transform: translate(0px, 39px) !important;
    }
</style>
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
                                                class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab"
                                                aria-selected="{{ $loop->first ? 'true' : 'false' }}" role="tab">
                                                {{ $companyName->company_name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="tab-content">
                                    @foreach ($companyList as $companyId => $companyName)
                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                            id="company-{{ $companyId }}" role="tabpanel">
                                            <!-- Content for Company {{ $companyName }} tab -->
                                        </div>
                                    @endforeach
                                </div>



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
                                                    <div class="text-muted">
                                                        Show
                                                        <div class="mx-2 d-inline-block">
                                                            <input type="text" class="form-control form-control-sm"
                                                                value="8" size="3" aria-label="Invoices count">
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
                                                        @foreach ($quotation as $key => $item)
                                                            @if ($item->customer_type == 'residential_customer_type')
                                                                <tr>
                                                                    <td>{{ $key + 1 }}</td>
                                                                    <td><span
                                                                            class="text-muted">00{{ $item->quotation_no }}</span>
                                                                    </td>
                                                                    <td>{{ $item->customer_name }}</td>
                                                                    <td>
                                                                        {{ $item->email }}
                                                                    </td>
                                                                    <td>
                                                                        +91-{{ $item->mobile_number }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $item->created_at->format('d M Y') }}
                                                                    </td>
                                                                    <td>
                                                                        <span class="badge bg-red">Pending</span>
                                                                    </td>

                                                                    <td class="text-end">
                                                                        <div class="dropdown">
                                                                            <button
                                                                                class="btn dropdown-toggle align-text-top show t-btn"
                                                                                data-bs-toggle="dropdown"
                                                                                aria-expanded="true">
                                                                                Actions
                                                                            </button>
                                                                            <div class="dropdown-menu dropdown-menu-end d-menu"
                                                                                style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 39px);"
                                                                                data-popper-placement="bottom-end">
                                                                                <a class="dropdown-item"
                                                                                    href="{{ route('quotation.view', $item->id) }}"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#view-quotation">
                                                                                    <i
                                                                                        class="fa-solid fa-eye me-2 text-blue"></i>
                                                                                    View
                                                                                </a>
                                                                                <a class="dropdown-item" href="#"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#edit-quotation">
                                                                                    <i
                                                                                        class="fa-solid fa-pencil me-2 text-yellow"></i>
                                                                                    Edit
                                                                                </a>
                                                                                <a class="dropdown-item border-bottom"
                                                                                    href="{{ route('quotation.delete', $item->id) }}"
                                                                                    onclick="alert('Are you sure')">
                                                                                    <i
                                                                                        class="fa-solid fa-trash me-2 text-red"></i>
                                                                                    Reject
                                                                                </a>
                                                                                <a class="dropdown-item" href="#"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#send-quotation">
                                                                                    <i
                                                                                        class="fa-solid fa-envelope me-2 text-info"></i>
                                                                                    Send Quotation
                                                                                </a>
                                                                                <a class="dropdown-item"
                                                                                    href="{{ route('salesOrder') }}">
                                                                                    <i
                                                                                        class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                                    Confirm Quotation
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                        {{-- <tr>
                                                            <td>01</td>
                                                            <td><span class="text-muted">001401</span></td>
                                                            <td>Jhone Doe</td>
                                                            <td>
                                                                jhondoe@gmail.com
                                                            </td>
                                                            <td>
                                                                +91-87956621
                                                            </td>
                                                            <td>
                                                                15 Dec 2017
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-red">Pending</span>
                                                            </td>

                                                            <td class="text-end">
                                                                <div class="dropdown">
                                                                    <button class="btn dropdown-toggle align-text-top show"
                                                                        data-bs-toggle="dropdown" aria-expanded="true">
                                                                        Actions
                                                                    </button>
                                                                    <div class="dropdown-menu dropdown-menu-end"
                                                                        style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 39px);"
                                                                        data-popper-placement="bottom-end">
                                                                        <a class="dropdown-item" href="#"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#view-quotation">
                                                                            <i class="fa-solid fa-eye me-2 text-blue"></i>
                                                                            View
                                                                        </a>
                                                                        <a class="dropdown-item" href="#"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#edit-quotation">
                                                                            <i
                                                                                class="fa-solid fa-pencil me-2 text-yellow"></i>
                                                                            Edit
                                                                        </a>
                                                                        <a class="dropdown-item border-bottom"
                                                                            href="#">
                                                                            <i class="fa-solid fa-trash me-2 text-red"></i>
                                                                            Reject
                                                                        </a>
                                                                        <a class="dropdown-item" href="#"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#send-quotation">
                                                                            <i
                                                                                class="fa-solid fa-envelope me-2 text-info"></i>
                                                                            Send Quotation
                                                                        </a>
                                                                        <a class="dropdown-item" href="sales_order.html">
                                                                            <i
                                                                                class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                            Confirm Quotation
                                                                        </a>
                                                                    </div>
                                                                </div>

                                                            </td>
                                                        </tr> --}}

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
                                                                value="8" size="3"
                                                                aria-label="Invoices count"> 40147963.
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
                                                        @foreach ($quotation as $key => $item)
                                                            @if ($item->customer_type == 'commercial_customer_type')
                                                                <tr>
                                                                    <td>{{ $key + 1 }}</td>
                                                                    <td><span
                                                                            class="text-muted">00{{ $item->quotation_no }}</span>
                                                                    </td>
                                                                    <td>{{ $item->customer_name }}</td>
                                                                    <td>
                                                                        {{ $item->email }}
                                                                    </td>
                                                                    <td>
                                                                        +91-{{ $item->mobile_number }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $item->created_at->format('d M Y') }}
                                                                    </td>
                                                                    <td>
                                                                        <span class="badge bg-red">Pending</span>
                                                                    </td>

                                                                    <td class="text-end">
                                                                        <div class="dropdown">
                                                                            <button
                                                                                class="btn dropdown-toggle align-text-top show t-btn"
                                                                                data-bs-toggle="dropdown"
                                                                                aria-expanded="true">
                                                                                Actions
                                                                            </button>
                                                                            <div class="dropdown-menu dropdown-menu-end d-menu"
                                                                                style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 39px);"
                                                                                data-popper-placement="bottom-end">
                                                                                <a class="dropdown-item" href="#"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#view-quotation">
                                                                                    <i
                                                                                        class="fa-solid fa-eye me-2 text-blue"></i>
                                                                                    View
                                                                                </a>
                                                                                <a class="dropdown-item" href="#"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#edit-quotation">
                                                                                    <i
                                                                                        class="fa-solid fa-pencil me-2 text-yellow"></i>
                                                                                    Edit
                                                                                </a>
                                                                                <a class="dropdown-item border-bottom"
                                                                                    href="#">
                                                                                    <i
                                                                                        class="fa-solid fa-trash me-2 text-red"></i>
                                                                                    Reject
                                                                                </a>
                                                                                <a class="dropdown-item" href="#"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#send-quotation">
                                                                                    <i
                                                                                        class="fa-solid fa-envelope me-2 text-info"></i>
                                                                                    Send Quotation
                                                                                </a>
                                                                                <a class="dropdown-item"
                                                                                    href="sales_order.html">
                                                                                    <i
                                                                                        class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                                    Confirm Quotation
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
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
                            </div>
                            <div class="tab-pane" id="company-l" role="tabpanel">
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
                                                                value="8" size="3"
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
                                                                +91-87956621
                                                            </td>
                                                            <td>
                                                                15 Dec 2017
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-red">Pending</span>
                                                            </td>

                                                            <td class="text-end">
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
                                                                        <a class="dropdown-item" href="#"
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
                                                                        </a>
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
                                                                value="8" size="3"
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
                                                                +91-87956621
                                                            </td>
                                                            <td>
                                                                15 Dec 2017
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-red">Pending</span>
                                                            </td>

                                                            <td class="text-end">
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
                                                                        <a class="dropdown-item" href="#"
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
                                                                        </a>
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
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Quotation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row text-left">

                        <div id="smartwizard-edit" style="border: none; height: auto;">

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
                                    <a class="nav-link" href="#step-3">
                                        <span class="num">3</span>
                                        Address
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " href="#step-4">
                                        <span class="num">4</span>
                                        Preview
                                    </a>
                                </li>

                            </ul>

                            <div class="tab-content mt-3" style="border: none;">
                                <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
                                    <div class="row">
                                        <div class="col-md-3">

                                            <ul class="nav nav-pills nav-pills-primary" data-bs-toggle="tabs"
                                                role="tablist">
                                                <li class="nav-item me-2" role="presentation">
                                                    <a href="#residential-edit" id="residential-edit-table"
                                                        class="nav-link active" data-bs-toggle="tab" aria-selected="true"
                                                        role="tab">Residential</a>
                                                </li>
                                                <li class="nav-item me-2" role="presentation">
                                                    <a href="#commercial-edit" class="nav-link"
                                                        id="commercial-edit-table" data-bs-toggle="tab"
                                                        aria-selected="false" role="tab"
                                                        tabindex="-1">Commercial</a>
                                                </li>


                                            </ul>

                                            <div class="tab-content mt-3">
                                                <div class="tab-pane fade show active" id="residential-edit"
                                                    role="tabpanel">

                                                    <div class="mb-3">
                                                        <label class="form-label">Search By</label>
                                                        <div class="input-icon mb-3">
                                                            <input type="text" value="" class="form-control"
                                                                placeholder="Search…" onkeypress="Search('1')" id="residential_customer">
                                                            <span class="input-icon-addon">
                                                                <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                                    width="24" height="24" viewBox="0 0 24 24"
                                                                    stroke-width="2" stroke="currentColor" fill="none"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none"></path>
                                                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0">
                                                                    </path>
                                                                    <path d="M21 21l-6 -6"></path>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3" id="residential_Search">
                                                        {{-- <div class="card card-active">
                                                            <div class="ribbon bg-yellow">Residential</div>
                                                            <div class="card-body d-flex justify-content-between">
                                                                <div class="my-auto" ">
                                                                    <label class="mb-0 text-black fw-bold "
                                                                        style="font-size: 14px">Will Smith</label>
                                                                    <p class="m-0">Tel - +91 9825804569</p>
                                                                </div>

                                                            </div>
                                                        </div> --}}
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="commercial-edit" role="tabpanel">
                                                    <div class="mb-3">
                                                        <label class="form-label">Search By</label>
                                                        <div class="input-icon mb-3">
                                                            <input type="text" value="" class="form-control"
                                                                placeholder="Search…." onkeypress="Search('0')" id="commercial_customer">
                                                            <span class="input-icon-addon">
                                                                <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                                    <path d="M21 21l-6 -6"></path>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3" id="commercialList" style="display: block">
                                                    </div>
                                                </div>

                                            </div>


                                        </div>
                                        <div class="col-md-9">
                                            <div class="d-flex"
                                                style="justify-content: space-between; align-items: center;">
                                                <h5 class="modal-title">Customer Details</h5>


                                                <a href="#" class="btn btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#add-customer">
                                                    <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M12 5l0 14"></path>
                                                        <path d="M5 12l14 0"></path>
                                                    </svg>
                                                    Add New
                                                </a>


                                            </div>
                                            <div class="card mt-3" id="residential-card-edit">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label class="mb-0" for=""> <b>Customer Name</b>
                                                            </label>
                                                            <p class="m-0">{{ $data->customer_name }}</p>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="mb-0" for=""><b>Contact No.</b>
                                                            </label>
                                                            <p class="m-0">+91-{{ $data->mobile_number }}</p>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="mb-0" for=""> <b>Email</b></label>
                                                            <p class="m-0">{{ $data->email }}</p>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="mb-0" for=""> <b>Territory</b></label>
                                                            <p class="m-0">{{ $data->territory }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">


                                                        <div class="col-md-3">
                                                            <label class="mb-0" for=""><b>Status</b> </label>
                                                            <p><span class="badge bg-red">Pending</span></p>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="mb-0" for=""><b>Outstanding Amount</b>
                                                            </label>
                                                            <p class="m-0">$ 2000</p>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="card mt-3" id="commercial-card-edit" style="display: none;">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-3 mb-3">
                                                            <label class="mb-0" for=""> <b>UEN</b></label>
                                                            <p class="m-0">123456</p>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label class="mb-0" for=""> <b>Customer Name</b>
                                                            </label>
                                                            <p class="m-0">ABC Group Of Companies</p>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label class="mb-0" for=""><b>Contact No.</b>
                                                            </label>
                                                            <p class="m-0">+91 9758697820</p>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label class="mb-0" for=""> <b>Email</b></label>
                                                            <p class="m-0">abc@gmail.com</p>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label class="mb-0" for=""> <b>Territory</b></label>
                                                            <p class="m-0">one</p>
                                                        </div>
                                                        <div class="col-md-3 ">
                                                            <label class="mb-0" for=""><b>Status</b> </label>
                                                            <p class="m-0"><span class="badge bg-red">Pending</span>
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="mb-0" for=""><b>Outstanding Amount</b>
                                                            </label>
                                                            <p class="m-0">$ 2000</p>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
                                    <div class="row">
                                        <div class="col-md-4 ">
                                            <div class="card">
                                                <div class="card-body">


                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Select Company<span
                                                                        class="text-danger">*</span></label>
                                                                <select type="text" class="form-select" value="" id="company-select">>
                                                                    @foreach($companyList as $item)
                                                                        <option value="{{$item->id}}">{{$item->company_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>


                                                            <div class="">
                                                                <label class="form-label">Search By</label>
                                                                <div class="input-icon mb-3">
                                                                    <input type="text" value="" class="form-control" placeholder="Search…S" oninput="searchServices()"
                                                                        id="service-search">
                                                                    <span class="input-icon-addon">
                                                                        <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="icon" width="24" height="24"
                                                                            viewBox="0 0 24 24" stroke-width="2"
                                                                            stroke="currentColor" fill="none"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none"></path>
                                                                            <path
                                                                                d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0">
                                                                            </path>
                                                                            <path d="M21 21l-6 -6"></path>
                                                                        </svg>
                                                                    </span>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <ul class="nav nav-pills nav-pills-success mt-3"
                                                                id="pills-tab" role="tablist" style="border: none;">
                                                                <li class="nav-item me-3">
                                                                    <a class="nav-link" id="pills-home-tab-edit"
                                                                        data-bs-toggle="pill" href="#pills-home-edit"
                                                                        role="tab" aria-controls="pills-home"
                                                                        aria-selected="true">Services</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" id="pills-profile-tab-edit"
                                                                        data-bs-toggle="pill" href="#pills-profile-edit"
                                                                        role="tab" aria-controls="pills-profile"
                                                                        aria-selected="false">Packages</a>
                                                                </li>

                                                            </ul>
                                                            <div class="tab-content p-0" id="pills-tabContent"
                                                                style="border: none;">
                                                                <div class="tab-pane fade" id="pills-home-edit"
                                                                    role="tabpanel" aria-labelledby="pills-home-tab">
                                                                    <div class="mt-3">
                                                                        <div class="row" id="productsubcat">
                                                                            <div class="col-md-4 text-center">
                                                                                <button type="button"
                                                                                    class="productsubedit btn btn-inverse-primary btn-sm">Floor
                                                                                    Cleaning</button>

                                                                            </div>
                                                                            <div class="col-md-4 text-center">
                                                                                <button type="button"
                                                                                    class="productsubedit btn btn-inverse-secondary btn-sm">Home
                                                                                    Cleaning</button>

                                                                            </div>
                                                                            <div class="col-md-4 text-center">
                                                                                <button type="button"
                                                                                    class="productsubedit btn btn-inverse-warning btn-sm">Office
                                                                                    Cleaning</button>
                                                                            </div>
                                                                        </div>

                                                                        <div class="productsubshowedit mt-3"
                                                                            style="display: none;">

                                                                            <div class="table-responsive">
                                                                                <table id="serviceTable"
                                                                                    class="table card-table table-vcenter text-center text-nowrap table-transparent">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>SL NO</th>
                                                                                            <!-- <th>Image</th> -->
                                                                                            <th>Item</th>
                                                                                            <th>Unit Price</th>

                                                                                            <th>Action</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td>1</td>
                                                                                            <!-- <td><span class="avatar avatar-sm"
                                                                                                    style="background-image: url(./static/avatars/000m.jpg)"></span></td> -->
                                                                                            <td>Floor Cleaning</td>
                                                                                            <td>$308.00</td>
                                                                                            <td>
                                                                                                <button
                                                                                                    class="btn btn-primary   ripple"
                                                                                                    type="button">
                                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                        class="icon m-0"
                                                                                                        width="24"
                                                                                                        height="24"
                                                                                                        viewBox="0 0 24 24"
                                                                                                        stroke-width="2"
                                                                                                        stroke="currentColor"
                                                                                                        fill="none"
                                                                                                        stroke-linecap="round"
                                                                                                        stroke-linejoin="round">
                                                                                                        <path
                                                                                                            stroke="none"
                                                                                                            d="M0 0h24v24H0z"
                                                                                                            fill="none">
                                                                                                        </path>
                                                                                                        <path
                                                                                                            d="M12 5l0 14">
                                                                                                        </path>
                                                                                                        <path
                                                                                                            d="M5 12l14 0">
                                                                                                        </path>
                                                                                                    </svg>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>

                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane fade" id="pills-profile-edit"
                                                                    role="tabpanel" aria-labelledby="pills-profile-tab">
                                                                    <div class="mt-3">
                                                                        <div class="row" id="packagesubcat">
                                                                            <div class="col-md-4">
                                                                                <button type="button"
                                                                                    class="packagesubedit btn btn-inverse-primary btn-sm">Floor
                                                                                    Cleaning</button>

                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <button type="button"
                                                                                    class="packagesubedit btn btn-inverse-secondary btn-sm">Home
                                                                                    Cleaning</button>

                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <button type="button"
                                                                                    class="packagesubedit btn btn-inverse-warning btn-sm">Office
                                                                                    Cleaning</button>
                                                                            </div>
                                                                        </div>

                                                                        <div class="packagesubshowedit mt-3"
                                                                            style="display: none;">

                                                                            <div class="table-responsive">
                                                                                <table
                                                                                    class="table card-table table-vcenter text-center text-nowrap table-transparent">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>SL NO</th>
                                                                                            <!-- <th>Image</th> -->
                                                                                            <th>Item</th>
                                                                                            <th>Unit Price</th>

                                                                                            <th>Action</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td>1</td>
                                                                                            <!-- <td><span class="avatar avatar-sm"
                                                                  style="background-image: url(./static/avatars/000m.jpg)"></span></td> -->
                                                                                            <td>Floor Cleaning</td>
                                                                                            <td>$308.00</td>
                                                                                            <td>
                                                                                                <button
                                                                                                    class="btn btn-primary   ripple"
                                                                                                    type="button">
                                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                        class="icon m-0"
                                                                                                        width="24"
                                                                                                        height="24"
                                                                                                        viewBox="0 0 24 24"
                                                                                                        stroke-width="2"
                                                                                                        stroke="currentColor"
                                                                                                        fill="none"
                                                                                                        stroke-linecap="round"
                                                                                                        stroke-linejoin="round">
                                                                                                        <path
                                                                                                            stroke="none"
                                                                                                            d="M0 0h24v24H0z"
                                                                                                            fill="none">
                                                                                                        </path>
                                                                                                        <path
                                                                                                            d="M12 5l0 14">
                                                                                                        </path>
                                                                                                        <path
                                                                                                            d="M5 12l14 0">
                                                                                                        </path>
                                                                                                    </svg>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
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
                                        <div class="col-md-8 pe-0">
                                            <div id="service-table-edit">
                                                <div class="card">
                                                    <div class="card-body p-0">
                                                        <div class="table-responsive">
                                                            <table
                                                                class="table card-table table-vcenter text-center text-nowrap"
                                                                id="" style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>SL NO</th>
                                                                        <th>Item</th>
                                                                        <th>Unit Price</th>
                                                                        <th>Qty</th>
                                                                        <th>Discount (%)</th>
                                                                        <th>Gross Amt ($)</th>
                                                                        <th>Tax</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td>Floor Cleaning</td>

                                                                        <td><input type="number" class="form-control">
                                                                        </td>
                                                                        <td class="p-0"><input type="number"
                                                                                class="form-control"></td>
                                                                        <td>5%</td>
                                                                        <td>$543</td>
                                                                        <td>18%</td>

                                                                        <td>
                                                                            <button class="btn btn-danger ripple"
                                                                                type="button">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    class="icon icon-tabler icon-tabler-playstation-x m-0"
                                                                                    width="24" height="24"
                                                                                    viewBox="0 0 24 24" stroke-width="2"
                                                                                    stroke="currentColor" fill="none"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round">
                                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                                        fill="none"></path>
                                                                                    <path
                                                                                        d="M12 21a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9a9 9 0 0 0 -9 9a9 9 0 0 0 9 9z">
                                                                                    </path>
                                                                                    <path d="M8.5 8.5l7 7"></path>
                                                                                    <path d="M8.5 15.5l7 -7"></path>
                                                                                </svg>
                                                                            </button>
                                                                        </td>
                                                                    </tr>

                                                                </tbody>
                                                                <thead>
                                                                    <tr>
                                                                        <th colspan="7" style="text-align: end;">Total
                                                                            discount </th>
                                                                        <th colspan="2">5%</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th colspan="7" style="text-align: end;">Total
                                                                            tax </th>
                                                                        <th colspan="2">18%</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th colspan="7" style="text-align: end;">Grand
                                                                            total</th>
                                                                        <th colspan="2">$ 616.00</th>
                                                                    </tr>
                                                                </thead>
                                                                <thead id="package-total" style="display: none;">
                                                                    <tr>
                                                                        <th colspan="7" style="text-align: end;">
                                                                            Package Amount</th>
                                                                        <th colspan="2"><input type="text"
                                                                                class="form-control"></th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div id="package-table-edit" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Package Name</label>

                                                            <input type="text" value=""
                                                                class="form-control w-50"
                                                                placeholder="Enter Package Name">



                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body p-0">


                                                        <div class="table-responsive">

                                                            <table
                                                                class="table card-table table-vcenter text-center text-nowrap"
                                                                id="" style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>SL NO</th>
                                                                        <th>Item</th>
                                                                        <th>Item Discription</th>
                                                                        <th>Categoery</th>
                                                                        <th>Unit Price</th>
                                                                        <th>Qty</th>
                                                                        <th>Discount (%)</th>
                                                                        <th>Gross Amt ($)</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td>Floor Cleaning</td>
                                                                        <td>Floor-1</td>
                                                                        <td>
                                                                            <textarea class="form-control" name="example-textarea-input" rows="3" placeholder="Enter Descrption"></textarea>
                                                                        </td>
                                                                        <td><input type="number" class="form-control">
                                                                        </td>
                                                                        <td class="p-0"><input type="number"
                                                                                class="form-control"></td>
                                                                        <td>0</td>
                                                                        <td>$308.00</td>

                                                                        <td>
                                                                            <button class="btn btn-danger ripple"
                                                                                type="button">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    class="icon icon-tabler icon-tabler-playstation-x m-0"
                                                                                    width="24" height="24"
                                                                                    viewBox="0 0 24 24" stroke-width="2"
                                                                                    stroke="currentColor" fill="none"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round">
                                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                                        fill="none"></path>
                                                                                    <path
                                                                                        d="M12 21a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9a9 9 0 0 0 -9 9a9 9 0 0 0 9 9z">
                                                                                    </path>
                                                                                    <path d="M8.5 8.5l7 7"></path>
                                                                                    <path d="M8.5 15.5l7 -7"></path>
                                                                                </svg>
                                                                            </button>
                                                                        </td>
                                                                    </tr>

                                                                </tbody>
                                                                <thead>
                                                                    <tr>
                                                                        <th colspan="7" style="text-align: end;">TOTAL
                                                                            DISCOUNT</th>
                                                                        <th colspan="2">$ 616.00</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th colspan="7" style="text-align: end;">Total
                                                                            tax </th>
                                                                        <th colspan="2">18%</th>
                                                                    </tr>
                                                                </thead>
                                                                <thead>
                                                                    <tr>
                                                                        <th colspan="7" style="text-align: end;">
                                                                            Package Amount</th>
                                                                        <th colspan="2"><input type="text"
                                                                                class="form-control"></th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="card card-link card-link-pop">
                                                <div class="card-status-start bg-primary"></div>
                                                <div class="card-stamp">
                                                    <div class="card-stamp-icon bg-white text-primary">
                                                        <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path
                                                                d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <h3 class="card-title " style="color: #1F3BB3;"><b class="me-2">ABC
                                                            Pvt.
                                                            Lte.</b><span class="badge bg-red">Residential</span>
                                                    </h3>

                                                    <p class="card-p d-flex align-items-center mb-2 ">
                                                        <i class="fa-solid fa-phone me-2" style="font-size: 14px;"></i>+91
                                                        9758697820
                                                    </p>
                                                    <p class="card-p  d-flex align-items-center mb-2">
                                                        <i class="fa-solid fa-envelope me-2"
                                                            style="font-size: 14px;"></i>abc@pvtltd.com
                                                    </p>
                                                    <!-- <p class="card-p d-flex mb-2">
                                                  <i class="fa-solid fa-map-pin me-2 pt-1" style="font-size: 14px;"></i>103
                                                  Rasadhi
                                                  Appartment Wadaj Ahmedabad 380004.
                      
                                                </p> -->

                                                    <hr class="my-3">
                                                    <h3 class="card-title mb-1" style="color: #1F3BB3;">

                                                        <b>Service Details</b>
                                                    </h3>
                                                    <div class="amount">
                                                        <p class="m-0 card-p">Floor Cleaning(5)</p>
                                                        <p class="m-0 card-p">Home Cleaning(2)</p>


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
                                                                <p class="m-0">$200.00</p>
                                                            </div>
                                                        </div>
                                                    </div>



                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="card">
                                                <div class="card-body">
                                                    <ul class="nav nav-pills nav-pills-primary" data-bs-toggle="tabs"
                                                        role="tablist">
                                                        <li class="nav-item me-2" role="presentation">
                                                            <a href="#tab-one-edit" class="nav-link active"
                                                                data-bs-toggle="tab" aria-selected="true"
                                                                role="tab">Service Address</a>
                                                        </li>
                                                        <li class="nav-item me-2" role="presentation">
                                                            <a href="#tab-two-edit" class="nav-link" data-bs-toggle="tab"
                                                                aria-selected="false" role="tab"
                                                                tabindex="-1">Billing
                                                                Address</a>
                                                        </li>
                                                        <li class="nav-item me-2" role="presentation">
                                                            <a href="#tab-three-edit" class="nav-link"
                                                                data-bs-toggle="tab" aria-selected="false" role="tab"
                                                                tabindex="-1">Additional Info</a>
                                                        </li>


                                                    </ul>
                                                    <div class="tab-content">
                                                        <div class="tab-pane active show" id="tab-one-edit"
                                                            role="tabpanel">
                                                            <div class="row my-3">
                                                                <div class="col-lg-4 col-md-4 col-sm-12">
                                                                    <label for="radio-card-112" class="radio-card">
                                                                        <input type="radio" name="radio-card"
                                                                            id="radio-card-112" checked />
                                                                        <div class="card-content-wrapper">
                                                                            <span class="check-icon"></span>
                                                                            <div class="card-content">
                                                                                <h4>Sky Enterprice</h4>
                                                                                <p class="mb-1"> <strong>Contact
                                                                                        No:</strong>1234567890</p>
                                                                                <p class="mb-1"> <strong>Email
                                                                                        ID:</strong>ABC@gmail.com</p>

                                                                                <p class="mb-1">
                                                                                    <strong>Address:</strong>8 Shopping
                                                                                    Centre, 9 Bishan Place,
                                                                                    Singapore 579837
                                                                                </p>
                                                                                <p class="mb-1"><strong>Unit
                                                                                        No:</strong>12345h</p>
                                                                                <p class="mb-1">
                                                                                    <strong>Zone:</strong>South
                                                                                </p>
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input"
                                                                                        type="radio"
                                                                                        name="flexRadioDefault"
                                                                                        id="flexRadioDefault2" checked>
                                                                                    <label class="form-check-label"
                                                                                        for="flexRadioDefault2">
                                                                                        Default Address
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </label>

                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 my-3">
                                                                    <button type="button"
                                                                        class="btn btn-blue add_btn_edit">+ Add
                                                                        Address</button>
                                                                </div>
                                                                <div class="col-md-12 add_address_edit"
                                                                    style="display: none;">



                                                                    <div class="row my-3">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label for="name">Person Incharge
                                                                                    Name</label>

                                                                                <input type="text"
                                                                                    placeholder="Enter Name"
                                                                                    name="name" class="form-control"
                                                                                    required="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label for="name">Contact No</label>
                                                                                <input type="text"
                                                                                    placeholder="Enter Number"
                                                                                    name="name" class="form-control"
                                                                                    required="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label for="name">Email Id</label>
                                                                                <input type="text"
                                                                                    placeholder="Enter Email"
                                                                                    name="name" class="form-control"
                                                                                    required="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label for="name">Postal Code</label>
                                                                                <input type="text"
                                                                                    placeholder="Enter Code"
                                                                                    name="name" class="form-control"
                                                                                    required="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label for="name">Zone</label>
                                                                                <input type="text"
                                                                                    placeholder="Enter Zone"
                                                                                    name="name" class="form-control"
                                                                                    required="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label for="name">Address</label>
                                                                                <input type="text"
                                                                                    placeholder="Enter Address"
                                                                                    name="name" class="form-control"
                                                                                    required="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label for="name">Country</label>
                                                                                <select type="text" class="form-select"
                                                                                    value="">
                                                                                    <option value="11">India</option>
                                                                                    <option value="39">Singapore
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label for="name">Unit No</label>
                                                                                <input type="text"
                                                                                    placeholder="Enter Unit No."
                                                                                    name="name" class="form-control"
                                                                                    required="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 my-auto">

                                                                            <button type="button"
                                                                                class="btn btn-blue add-row-edit"
                                                                                id="rowAdder-edit">+</button>
                                                                        </div>

                                                                        <!-- <div class="col-md-1" style="display: flex; align-items: center;">

                                                            <button type="button" class="btn btn-danger" id="DeleteRow">-</button>
                                                          </div> -->
                                                                    </div>
                                                                    <div id="newinput-edit"></div>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <button type="button"
                                                                                class="btn btn-primary">save</button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>


                                                        </div>
                                                        <div class="tab-pane" id="tab-two-edit" role="tabpanel">
                                                            <div class="row my-3">
                                                                <div class="col-md-12">
                                                                    <div class="my-3">
                                                                        <label class="form-check-label">
                                                                            <input type="checkbox"
                                                                                class="form-check-input">
                                                                            Same as Service Address
                                                                            <i class="input-helper"></i></label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-12">
                                                                    <label for="radio-card-4545" class="radio-card">
                                                                        <input type="radio" name="radio-card"
                                                                            id="radio-card-4545" checked />
                                                                        <div class="card-content-wrapper">
                                                                            <span class="check-icon"></span>
                                                                            <div class="card-content">
                                                                                <h4>Jhone Doe</h4>
                                                                                <p class="mb-1">
                                                                                    <strong>Address:</strong>8 Shopping
                                                                                    Centre, 9 Bishan Place,
                                                                                    Singapore 579837
                                                                                </p>
                                                                                <p class="mb-1"><strong>Unit
                                                                                        No:</strong>12345h</p>
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input"
                                                                                        type="radio"
                                                                                        name="flexRadioDefault"
                                                                                        id="flexRadioDefault22" checked>
                                                                                    <label class="form-check-label"
                                                                                        for="flexRadioDefault22">
                                                                                        Default Address
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </label>

                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 my-3">
                                                                    <button type="button"
                                                                        class="btn btn-blue add_btn_2edit">+ Add
                                                                        Address</button>
                                                                </div>

                                                                <div class="col-md-12 add_address_2edit"
                                                                    style="display: none;">

                                                                    <div class="table-responsive mb-3">
                                                                        <table
                                                                            class="table card-table table-vcenter text-nowrap table-transparent"
                                                                            id="billing_address_add_quot">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Postal Code</th>
                                                                                    <th>Address</th>
                                                                                    <th>Country</th>
                                                                                    <th>
                                                                                        <button type="button"
                                                                                            class="btn btn-blue add-row-edit">+</button>

                                                                                    </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>
                                                                                        <input class="form-control"
                                                                                            type="text"
                                                                                            placeholder="Enter Code" />
                                                                                    </td>
                                                                                    <td>
                                                                                        <input class="form-control"
                                                                                            type="text"
                                                                                            placeholder="Address" />
                                                                                    </td>
                                                                                    <td>
                                                                                        <select type="text"
                                                                                            class="form-select"
                                                                                            value="">
                                                                                            <option value="111">
                                                                                                Singaore</option>
                                                                                            <option value="329">India
                                                                                            </option>
                                                                                        </select>
                                                                                    </td>
                                                                                    <td>
                                                                                        <button type="button"
                                                                                            class="btn btn-danger delete-row-edit">-</button>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>

                                                                    <div class="text-end">
                                                                        <button type="button"
                                                                            class="btn btn-blue">save</button>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="tab-pane" id="tab-three-edit" role="tabpanel">
                                                            <div class="row mt-3">
                                                                <div
                                                                    class="form-group col-lg-6 col-md-6 col-sm-12 text-start">
                                                                    <label for="message-text"
                                                                        class="col-form-label">Deposite Type</label>
                                                                    <select class="form-control">
                                                                        <option>Select Option</option>
                                                                        <option>$50</option>
                                                                        <option>waive</option>
                                                                        <option>Don’t need</option>
                                                                    </select>
                                                                </div>


                                                                <div
                                                                    class="form-group col-lg-6 col-md-6 col-sm-12 text-start">
                                                                    <label for="message-text"
                                                                        class="col-form-label">Date Of Cleaning</label>
                                                                    <input class="form-control"
                                                                        placeholder="dd/mm/yyyy">
                                                                </div>
                                                                <div
                                                                    class="form-group col-lg-6 col-md-6 col-sm-12 text-start">
                                                                    <label for="message-text"
                                                                        class="col-form-label">Time of Cleaning</label>
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Time of Cleaning">
                                                                </div>


                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </div>


                                <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="card card-link card-link-pop">
                                                <div class="card-status-start bg-primary"></div>
                                                <div class="card-stamp">
                                                    <div class="card-stamp-icon bg-white text-primary">
                                                        <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-map-pin" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                                            <path
                                                                d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="card-body">

                                                    <h3 class="card-title mb-1" style="color: #1F3BB3;">

                                                        <b>Service Address</b>
                                                    </h3>


                                                    <p class="m-0">BLK 3017 BEDOK NORTH STREET 5
                                                        #01-22 GOURMET EAST KITCHEN
                                                        SINGAPORE 486121</p>
                                                    <hr class="my-3">
                                                    <h3 class="card-title mb-1" style="color: #1F3BB3;">

                                                        <b>Billing Address</b>
                                                    </h3>


                                                    <p class="m-0">BLK 3017 BEDOK NORTH STREET 5
                                                        #01-22 GOURMET EAST KITCHEN
                                                        SINGAPORE 486121</p>
                                                    <hr class="my-3">
                                                    <h3 class="card-title mb-1" style="color: #1F3BB3;">

                                                        <b>Cleaning Details</b>
                                                    </h3>


                                                    <p class="m-0">Deposite Type : Wave</p>
                                                    <p class="m-0">Date Of Cleaning : 25/04/2023</p>
                                                    <p class="m-0">Time Of Cleaning : 05:47 PM</p>


                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="card">
                                                        <div class="card-body p-0">
                                                            <div class="table-responsive">
                                                                <table
                                                                    class="table card-table table-vcenter text-center text-nowrap datatable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>SL NO</th>
                                                                            <th>Item</th>
                                                                            <th>Unit Price</th>
                                                                            <th>Quantity</th>
                                                                            <th>Gross Amount ($)</th>
                                                                            <th>Discount (%)</th>


                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>1</td>
                                                                            <td>Floor Cleaning</td>

                                                                            <td>$308.00</td>
                                                                            <td>2</td>

                                                                            <td>$308.00</td>
                                                                            <td>8%</td>
                                                                        </tr>

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <div class="card card-link card-link-pop">
                                                        <div class="card-status-start bg-primary"></div>
                                                        <div class="card-stamp">
                                                            <div class="card-stamp-icon bg-white text-primary">
                                                                <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                                    width="24" height="24" viewBox="0 0 24 24"
                                                                    stroke-width="2" stroke="currentColor"
                                                                    fill="none" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none"></path>
                                                                    <path
                                                                        d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <h3 class="card-title mb-1" style="color: #1F3BB3;"><b
                                                                    class="me-2">Customer Details</b>
                                                            </h3>

                                                            <p class="m-0">
                                                                <i class="fa-solid fa-user me-2 pt-1"
                                                                    style="font-size: 14px;"></i>
                                                                {{ $data->customer_name }}
                                                            </p>
                                                            <p class="m-0">
                                                                <i class="fa-solid fa-phone me-2 pt-1"
                                                                    style="font-size: 14px;"></i>
                                                                +91-{{ $data->mobile_number }}
                                                            </p>
                                                            <!-- <p class="m-0">
                                                                <i class="fa-solid fa-map-pin me-2 pt-1" style="font-size: 14px;"></i>
                                                                103 Rasadhi Appartment Wadaj Ahmedabad
                                                                380004.
                                                                </p> -->

                                                            <hr class="my-3">
                                                            <h3 class="card-title mb-1" style="color: #1F3BB3;"><b
                                                                    class="me-2">Amount Details</b>
                                                            </h3>

                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <p class="m-0">Total (before tax):</p>
                                                                    <p class="m-0">Total Tax:</p>
                                                                    <p class="m-0">Total Discount:</p>
                                                                    <h3>Grand Total:</h6>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <p class="m-0">$200.00</p>
                                                                    <p class="m-0">$0.00</p>
                                                                    <p class="m-0">$0.00</p>
                                                                    <h3>$200.00</h6>
                                                                </div>
                                                            </div>
                                                            <button type="button" class="btn btn-info w-100 mt-3"
                                                                data-dismiss="modal">Confirm</button>
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
                                <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                    </form>

                </div>

                <!-- <div class="modal-footer">
                                        <button type="button" class="btn me-auto sw-btn-prev sw-btn">Previous</button>
                                        <button type="button" class="btn btn-primary next-btn" >Next</button>
                                    </div> -->
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="view-quotation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Quatation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card card-link card-link-pop">
                                <div class="card-status-start bg-primary"></div>
                                <div class="card-stamp">
                                    <div class="card-stamp-icon bg-white text-primary">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-map-pin" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                            <path
                                                d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="card-body">

                                    <h3 class="card-title mb-1" style="color: #1F3BB3;">

                                        <b>Service Address</b>
                                    </h3>


                                    <p class="m-0">BLK 3017 BEDOK NORTH STREET 5
                                        #01-22 GOURMET EAST KITCHEN
                                        SINGAPORE 486121</p>
                                    <hr class="my-3">
                                    <h3 class="card-title mb-1" style="color: #1F3BB3;">

                                        <b>Billing Address</b>
                                    </h3>


                                    <p class="m-0">BLK 3017 BEDOK NORTH STREET 5
                                        #01-22 GOURMET EAST KITCHEN
                                        SINGAPORE 486121</p>
                                    <!-- <hr class="my-3">
                                  <h3 class="card-title mb-1" style="color: #1F3BB3;">

                                    <b>Cleaning Details</b>
                                  </h3>


                                  <p class="m-0">Deposite Type : Wave</p>
                                  <p class="m-0">Date Of Cleaning : 25/04/2023</p>
                                  <p class="m-0">Time Of Cleaning : 05:47 PM</p> -->


                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table
                                                    class="table card-table table-vcenter text-center text-nowrap datatable">
                                                    <thead>
                                                        <tr>
                                                            <th>SL NO</th>
                                                            <th>Item</th>
                                                            <th>Unit Price</th>
                                                            <th>Quantity</th>
                                                            <th>Gross Amount ($)</th>
                                                            <th>Discount (%)</th>


                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Floor Cleaning</td>

                                                            <td>$308.00</td>
                                                            <td>2</td>

                                                            <td>$308.00</td>
                                                            <td>8%</td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">
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
                                        <div class="card-body">
                                            <h3 class="card-title mb-1" style="color: #1F3BB3;"><b
                                                    class="me-2">Customer Details</b>
                                            </h3>

                                            <p class="m-0">
                                                <i class="fa-solid fa-user me-2 pt-1" style="font-size: 14px;"></i>
                                                {{ $data->customer_name }}
                                            </p>
                                            <p class="m-0">
                                                <i class="fa-solid fa-phone me-2 pt-1" style="font-size: 14px;"></i>
                                                +91-{{ $data->mobile_number }}
                                            </p>
                                            <!-- <p class="m-0">
                                              <i class="fa-solid fa-map-pin me-2 pt-1" style="font-size: 14px;"></i>
                                              103 Rasadhi Appartment Wadaj Ahmedabad
                                              380004.
                                            </p> -->

                                            <hr class="my-3">
                                            <h3 class="card-title mb-1" style="color: #1F3BB3;"><b
                                                    class="me-2">Amount Details</b>
                                            </h3>

                                            <div class="row">
                                                <div class="col-md-7">
                                                    <p class="m-0">Total (before tax):</p>
                                                    <p class="m-0">Total Tax:</p>
                                                    <p class="m-0">Total Discount:</p>
                                                    <h3>Grand Total:</h3>
                                                </div>
                                                <div class="col-md-5">
                                                    <p class="m-0">$200.00</p>
                                                    <p class="m-0">$0.00</p>
                                                    <p class="m-0">$0.00</p>
                                                    <h3>$200.00</h3>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-info w-100 mt-3"
                                                data-dismiss="modal">Confirm</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button> --}}
                </div>
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
                                <a class="nav-link default active" href="#update-step-1">
                                    <div class="num">1</div>
                                    1
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link default" href="#update-step-2">
                                    <span class="num">2</span>
                                    2
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link default" href="#update-step-3">
                                    <span class="num">3</span>
                                    3
                                </a>
                            </li>

                        </ul>

                        <div class="tab-content p-0" style="border: none; height: 260px;">
                            <div id="update-step-1" class="tab-pane py-0" role="tabpanel"
                                aria-labelledby="update-step-1" style="position: static; left: 0px; display: block;">
                                <div class="row">
                                    <div class="mb-3">
                                        <label class="form-label">Select Quotation Template</label>
                                        <div class="row g-2">
                                            <div class="col-6 col-sm-4">
                                                <label class="form-imagecheck mb-2">
                                                    <input name="form-imagecheck-radio" type="radio" value="1"
                                                        class="form-imagecheck-input">
                                                    <span class="form-imagecheck-figure">
                                                        <img src="{{ asset('theme/dist/img/template.jpg') }}"
                                                            alt="Group of people sightseeing in the city"
                                                            class="form-imagecheck-image">
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <label class="form-imagecheck mb-2">
                                                    <input name="form-imagecheck-radio" type="radio" value="2"
                                                        class="form-imagecheck-input" checked="">
                                                    <span class="form-imagecheck-figure">
                                                        <img src="{{ asset('theme/dist/img/template.jpg') }}"
                                                            alt="Color Palette Guide. Sample Colors Catalog."
                                                            class="form-imagecheck-image">
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <label class="form-imagecheck mb-2">
                                                    <input name="form-imagecheck-radio" type="radio" value="3"
                                                        class="form-imagecheck-input">
                                                    <span class="form-imagecheck-figure">
                                                        <img src="{{ asset('theme/dist/img/template.jpg') }}"
                                                            alt="Stylish workplace with computer at home"
                                                            class="form-imagecheck-image">
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <label class="form-imagecheck mb-2">
                                                    <input name="form-imagecheck-radio" type="radio" value="4"
                                                        class="form-imagecheck-input" checked="">
                                                    <span class="form-imagecheck-figure">
                                                        <img src="{{ asset('theme/dist/img/template.jpg') }}"
                                                            alt="Pink desk in the home office"
                                                            class="form-imagecheck-image">
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <label class="form-imagecheck mb-2">
                                                    <input name="form-imagecheck-radio" type="radio" value="5"
                                                        class="form-imagecheck-input">
                                                    <span class="form-imagecheck-figure">
                                                        <img src="{{ asset('theme/dist/img/template.jpg') }}"
                                                            alt="Young woman sitting on the sofa and working on her laptop"
                                                            class="form-imagecheck-image">
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <label class="form-imagecheck mb-2">
                                                    <input name="form-imagecheck-radio" type="radio" value="6"
                                                        class="form-imagecheck-input">
                                                    <span class="form-imagecheck-figure">
                                                        <img src="{{ asset('theme/dist/img/template.jpg') }}"
                                                            alt="Coffee on a table with other items"
                                                            class="form-imagecheck-image">
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div id="update-step-2" class="tab-pane" role="tabpanel" aria-labelledby="update-step-2"
                                style="display: none;">
                                <div class="row">
                                    <div class="mb-3">
                                        <label class="form-label">Select Email Template</label>
                                        <div class="row g-2">
                                            <div class="col-6 col-sm-4">
                                                <label class="form-imagecheck mb-2">
                                                    <input name="form-imagecheck-radio" type="radio" value="1"
                                                        class="form-imagecheck-input">
                                                    <span class="form-imagecheck-figure">
                                                        <img src="./dist/img/main-template.jpeg"
                                                            alt="Group of people sightseeing in the city"
                                                            class="form-imagecheck-image">
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <label class="form-imagecheck mb-2">
                                                    <input name="form-imagecheck-radio" type="radio" value="2"
                                                        class="form-imagecheck-input" checked="">
                                                    <span class="form-imagecheck-figure">
                                                        <img src="./dist/img/main-template.jpeg"
                                                            alt="Color Palette Guide. Sample Colors Catalog."
                                                            class="form-imagecheck-image">
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <label class="form-imagecheck mb-2">
                                                    <input name="form-imagecheck-radio" type="radio" value="3"
                                                        class="form-imagecheck-input">
                                                    <span class="form-imagecheck-figure">
                                                        <img src="./dist/img/main-template.jpeg"
                                                            alt="Stylish workplace with computer at home"
                                                            class="form-imagecheck-image">
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <label class="form-imagecheck mb-2">
                                                    <input name="form-imagecheck-radio" type="radio" value="4"
                                                        class="form-imagecheck-input" checked="">
                                                    <span class="form-imagecheck-figure">
                                                        <img src="./dist/img/main-template.jpeg"
                                                            alt="Pink desk in the home office"
                                                            class="form-imagecheck-image">
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <label class="form-imagecheck mb-2">
                                                    <input name="form-imagecheck-radio" type="radio" value="5"
                                                        class="form-imagecheck-input">
                                                    <span class="form-imagecheck-figure">
                                                        <img src="./dist/img/main-template.jpeg"
                                                            alt="Young woman sitting on the sofa and working on her laptop"
                                                            class="form-imagecheck-image">
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <label class="form-imagecheck mb-2">
                                                    <input name="form-imagecheck-radio" type="radio" value="6"
                                                        class="form-imagecheck-input">
                                                    <span class="form-imagecheck-figure">
                                                        <img src="./dist/img/main-template.jpeg"
                                                            alt="Coffee on a table with other items"
                                                            class="form-imagecheck-image">
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div id="update-step-3" class="tab-pane" role="tabpanel" aria-labelledby="update-step-3"
                                style="display: none;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form class="form-horizontal" role="form">
                                            <div class="mb-3">
                                                <label class="form-label">To:</label>
                                                <input type="text" class="form-control" name="example-text-input"
                                                    placeholder="Type email">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">CC:</label>
                                                <input type="text" class="form-control" name="example-text-input"
                                                    placeholder="Type email">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">BCC:</label>
                                                <input type="text" class="form-control" name="example-text-input"
                                                    placeholder="Type email">
                                            </div>
                                            <div class="mb-3">
                                                <div class="btn-toolbar " role="toolbar">
                                                    <div class="btn-group mb-3">
                                                        <button class="btn btn-default">
                                                            <span class="fa fa-bold"></span>
                                                        </button>
                                                        <button class="btn btn-default">
                                                            <span class="fa fa-italic"></span>
                                                        </button>
                                                        <button class="btn btn-default">
                                                            <span class="fa fa-underline"></span>
                                                        </button>
                                                    </div>
                                                    <div class="btn-group ms-3 mb-3">
                                                        <button class="btn btn-default">
                                                            <span class="fa fa-align-left"></span>
                                                        </button>
                                                        <button class="btn btn-default">
                                                            <span class="fa fa-align-right"></span>
                                                        </button>
                                                        <button class="btn btn-default">
                                                            <span class="fa fa-align-center"></span>
                                                        </button>
                                                        <button class="btn btn-default">
                                                            <span class="fa fa-align-justify"></span>
                                                        </button>
                                                    </div>
                                                    <div class="btn-group ms-3 mb-3">
                                                        <button class="btn btn-default">
                                                            <span class="fa fa-indent"></span>
                                                        </button>
                                                        <button class="btn btn-default">
                                                            <span class="fa fa-outdent"></span>
                                                        </button>
                                                    </div>
                                                    <div class="btn-group">
                                                        <button class="btn btn-default">
                                                            <span class="fa fa-list-ul"></span>
                                                        </button>
                                                        <button class="btn btn-default">
                                                            <span class="fa fa-list-ol"></span>
                                                        </button>
                                                    </div>
                                                    <div class="btn-group ms-3">
                                                        <button class="btn btn-default">
                                                            <span class="fa fa-trash-can"></span>
                                                        </button>
                                                        <button class="btn btn-default">
                                                            <span class="fa fa-paperclip"></span>
                                                        </button>

                                                    </div>

                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <textarea class="form-control" name="example-textarea-input" rows="6" placeholder="Content..">Oh! Come and see the violence inherent in the system! Help, help, I'm being repressed! We shall say 'Ni' again to you, if you do not appease us. I'm not a witch. I'm not a witch. Camelot!</textarea>
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
                                                        <span>Attachment (2 MB)</span>
                                                    </div>
                                                    <button class="btn btn-sm btn-outline-primary me-2">View All</button>
                                                    <button class="btn btn-sm btn-outline-success">Download All</button>
                                                </div>

                                                <ul class="attachment-list">
                                                    <li class="attachment-list-item">
                                                        <img src="./dist/img/template.jpg"" alt="Showcase"
                                                            title="Showcase">
                                                    </li>
                                                    <li class="attachment-list-item">
                                                        <img src="./dist/img/main-template.jpeg" alt="Showcase"
                                                            title="Showcase">
                                                    </li>


                                                </ul>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-12 text-end">
                                        <button type="button" class="btn btn-info">Confirm</button>
                                    </div>
                                </div>
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
                <!-- <div class="modal-footer">
                          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
                        </div> -->
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
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{!! asset('public/theme/dist/js/smart-wizaed.js') !!}" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous">
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
    </script>
    <script>
        $(document).ready(function() {
            $(".t-btn").click(function() {
                $(".d-menu").toggleClass("show");
            });
        });
    </script>


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
    $(document).on('click', '.add-service-btn', function() {
        const serviceId = $(this).data('service-id');
        const serviceName = $(this).data('service-name');
        const serviceDescription = $(this).data('service-description');
        const servicePrice = $(this).data('service-price');
        const quantityInput = $(this).closest('tr').find('.quantity-input');
        const quantity = parseInt(quantityInput.val(), 10) || 0;
        //   if ($('#selected-services-table tbody tr[data-service-id="' + serviceId + '"]').length > 0) {
        //     alert('This service is already added.');
        //     return;
        //   }
        const row = `

     <tr>
     <input type="text" class="form-control price" value="${serviceName}" name="service_name[]">
       <td>${serviceId}</td>
       <td>${serviceName}</td>
      <td class="description-cell" data-full-text="${serviceDescription}">${serviceDescription}</td>
   
      
       <td><input type="number" class="form-control price" value="${servicePrice}"></td>
       <td class="p-0"><input type="number" class="form-control quantity-input" placeholder="quantity" value="${quantity}"></td>

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
    });
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
@endsection
