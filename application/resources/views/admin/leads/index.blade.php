@extends('leadTheme.default')

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

        /* Style for the tooltip (label) */
        /* .highlighted-date:hover:after {
            content: attr(data-tooltip);
            background-color: #333;

            color: #fff;

            padding: 5px 10px;
            border-radius: 5px;
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.3s;
        } */

        /* Show tooltip on hover */
        /* .highlighted-date:hover:after {
            opacity: 1;
        } */
    </style>
@endsection

@section('content')
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                        <a href="{{ route('lead.create') }}" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#add-lead" onclick="showFormModal()">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 5l0 14"></path>
                                <path d="M5 12l14 0"></path>
                            </svg>
                            Add New
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">
                <div class="row">
                    {{-- new leads --}}
                    <div class="col-12 col-md-6 col-lg">
                        <h3 class="mb-3">New Leads({{ count($newLeads) }})</h3>
                        <div class="mb-4">
                            <div class="row row-cards">
                                {{-- draft leads start --}}

                                @foreach ($leads as $lead)
                                    @if ($lead->lead_status == 0)
                                        <div class="col-12">
                                            <div class="card card-sm card-link card-link-pop">
                                                <div class="card-status-top bg-yellow"></div>
                                                <div class="card-body">
                                                    @if ($lead->customer_type === 'residential_customer_type')
                                                        <h3 class="card-title mb-2">{{ ucfirst($lead->customer_name) }}
                                                            (Draft)</h3>
                                                        <div class="text-muted"> Residential</div>
                                                        <div class="text-muted"> +65 {{ $lead->mobile_number }}</div>
                                                    @elseif ($lead->customer_type === 'commercial_customer_type')
                                                        <h3 class="card-title mb-2">
                                                            {{ ucfirst($lead->individual_company_name) }} (Draft)</h3>
                                                        <div class="text-muted"> Commercial</div>
                                                        <div class="text-muted"> +65 {{ $lead->mobile_number }}
                                                        </div>
                                                    @endif
                                                    <div class="mt-3">
                                                        <div class="row">
                                                            <div class="col-8">
                                                                <a href="#" class="link-warning">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                                        width="24" height="24" viewBox="0 0 24 24"
                                                                        stroke-width="2" stroke="currentColor"
                                                                        fill="none" stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none">
                                                                        </path>
                                                                        <path
                                                                            d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z">
                                                                        </path>
                                                                        <path d="M16 3l0 4"></path>
                                                                        <path d="M8 3l0 4"></path>
                                                                        <path d="M4 11l16 0"></path>
                                                                        <path d="M11 15l1 0"></path>
                                                                        <path d="M12 15l0 3"></path>
                                                                    </svg>
                                                                    {{ $lead->schedule_date ? date('d-m-Y', strtotime($lead->schedule_date)) : '' }}
                                                                </a>
                                                            </div>
                                                            <div class="col-4 text-end text-muted">
                                                                <button class="switch-icon switch-icon-scale text-warning"
                                                                    data-bs-dismiss="modal" data-bs-target="#update-lead"
                                                                    onclick="updateLead('{{ $lead->id }}')"
                                                                    title="Edit">
                                                                    <span class="switch-icon-a text-success">

                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="icon icon-tabler icon-tabler-edit-circle"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" stroke-width="2"
                                                                            stroke="currentColor" fill="none"
                                                                            stroke-linecap="round" stroke-linejoin="round">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none"></path>
                                                                            <path
                                                                                d="M12 15l8.385 -8.415a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3z">
                                                                            </path>
                                                                            <path d="M16 5l3 3"></path>
                                                                            <path
                                                                                d="M9 7.07a7 7 0 0 0 1 13.93a7 7 0 0 0 6.929 -6">
                                                                            </path>
                                                                        </svg>
                                                                    </span>
                                                                </button>

                                                                <button class="switch-icon switch-icon-scale delete-button"
                                                                    data-lead-id="{{ $lead->id }}"
                                                                    data-bs-toggle="modal" data-bs-target="#delete-lead"
                                                                    title="Delete">

                                                                    <span class="switch-icon-a text-danger">
                                                                        <i class="fa-solid fa-trash me-2 text-blue"></i>
                                                                    </span>
                                                                </button>

                                                                <a href="{{route('lead.log-report', $lead->id)}}" class="switch-icon switch-icon-scale text-warning" title="Log Report">
                                                                    <span class="switch-icon-a text-success">
                                                                        <i class="fa-solid fa-file me-2 text-blue"></i>
                                                                    </span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                {{-- draft leads end --}}

                                {{-- new leads start --}}

                                @foreach ($leads as $lead)
                                    @if ($lead->lead_status == 1)
                                        <div class="col-12">
                                            <div class="card card-sm card-link card-link-pop">
                                                <div class="card-status-top bg-yellow"></div>
                                                <div class="card-body">
                                                    @if ($lead->customer_type === 'residential_customer_type')
                                                        <h3 class="card-title mb-2">{{ ucfirst($lead->customer_name) }}</h3>
                                                        <div class="text-muted"> Residential</div>
                                                        <div class="text-muted"> +65 {{ $lead->mobile_number }}</div>
                                                    @elseif ($lead->customer_type === 'commercial_customer_type')
                                                        <h3 class="card-title mb-2">
                                                            {{ ucfirst($lead->individual_company_name) }}</h3>
                                                        <div class="text-muted"> Commercial</div>
                                                        <div class="text-muted"> +65 {{ $lead->mobile_number }}</div>
                                                    @endif
                                                    <div class="mt-3">
                                                        <div class="row">
                                                            <div class="col-8">
                                                                <a href="#" class="link-warning">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                                        width="24" height="24" viewBox="0 0 24 24"
                                                                        stroke-width="2" stroke="currentColor"
                                                                        fill="none" stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none">
                                                                        </path>
                                                                        <path
                                                                            d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z">
                                                                        </path>
                                                                        <path d="M16 3l0 4"></path>
                                                                        <path d="M8 3l0 4"></path>
                                                                        <path d="M4 11l16 0"></path>
                                                                        <path d="M11 15l1 0"></path>
                                                                        <path d="M12 15l0 3"></path>
                                                                    </svg>
                                                                    {{ $lead->schedule_date ? date('d-m-Y', strtotime($lead->schedule_date)) : '' }}
                                                                </a>
                                                            </div>
                                                            <div class="col-4 text-end text-muted">
                                                                <button class="switch-icon switch-icon-scale text-warning"
                                                                    data-bs-dismiss="modal" data-bs-target="#update-lead"
                                                                    onclick="updateLead('{{ $lead->id }}')"
                                                                    title="Edit">
                                                                    <span class="switch-icon-a text-success">

                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="icon icon-tabler icon-tabler-edit-circle"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" stroke-width="2"
                                                                            stroke="currentColor" fill="none"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none"></path>
                                                                            <path
                                                                                d="M12 15l8.385 -8.415a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3z">
                                                                            </path>
                                                                            <path d="M16 5l3 3"></path>
                                                                            <path
                                                                                d="M9 7.07a7 7 0 0 0 1 13.93a7 7 0 0 0 6.929 -6">
                                                                            </path>
                                                                        </svg>
                                                                    </span>
                                                                </button>

                                                                <button class="switch-icon switch-icon-scale delete-button"
                                                                    data-lead-id="{{ $lead->id }}"
                                                                    data-bs-toggle="modal" data-bs-target="#delete-lead"
                                                                    title="Delete">

                                                                    <span class="switch-icon-a text-danger">
                                                                        <i class="fa-solid fa-trash me-2 text-blue"></i>
                                                                    </span>
                                                                </button>

                                                                <button
                                                                    class="switch-icon switch-icon-scale status_confirm_btn"
                                                                    data-lead-id="{{ $lead->id }}"
                                                                    data-bs-toggle="modal" data-bs-target="#confirm_lead"
                                                                    title="Confirm">

                                                                    <span class="switch-icon-a text-success">
                                                                        <i class="fa-solid fa-circle-check"></i>
                                                                    </span>
                                                                </button>

                                                                <a href="{{route('lead.log-report', $lead->id)}}" class="switch-icon switch-icon-scale text-warning" title="Log Report">
                                                                    <span class="switch-icon-a text-success">
                                                                        <i class="fa-solid fa-file me-2 text-blue"></i>
                                                                    </span>
                                                                </a>

                                                                {{-- <button class="switch-icon switch-icon-scale"
                                                                    data-bs-toggle="modal" data-bs-target="#update-status"
                                                                    onclick="updtaeStatus('{{ $lead->id }}')">
                                                                    <span class="switch-icon-a text-primary">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="icon icon-tabler icon-tabler-circle-chevron-right"
                                                                            width="24" height="24" viewBox="0 0 24 24"
                                                                            stroke-width="2" stroke="currentColor"
                                                                            fill="none" stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none"></path>
                                                                            <path d="M11 9l3 3l-3 3"></path>
                                                                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0z">
                                                                            </path>
                                                                        </svg>
                                                                    </span>
                                                                </button> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                {{-- new leads end --}}
                            </div>
                        </div>
                    </div>

                    {{-- pending customer approval leads --}}
                    <div class="col-12 col-md-6 col-lg">

                        <h3 class="mb-3">In Progress({{ $pendingCostomer->count() }})</h3>
                        <div class="mb-4">
                            {{-- <div class="row row-cards">
                                <div class="col-12">
                                    <div class="card card-sm card-link card-link-pop">
                                        <div class="card-status-top bg-purple"></div>
                                        <div class="card-body">
                                            <h3 class="card-title mb-2">Jet Lee</h3>
                                            <div class="text-muted">Commercial</div>


                                            <div class="mt-3">
                                                <div class="row">

                                                    <div class="col-8">
                                                        <a href="#" class="link-warning">
                                                            <!-- Download SVG icon from http://tabler-icons.io/i/calendar -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                stroke-width="2" stroke="currentColor" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                </path>
                                                                <path
                                                                    d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z">
                                                                </path>
                                                                <path d="M16 3l0 4"></path>
                                                                <path d="M8 3l0 4"></path>
                                                                <path d="M4 11l16 0"></path>
                                                                <path d="M11 15l1 0"></path>
                                                                <path d="M12 15l0 3"></path>
                                                            </svg>
                                                            10 Sep
                                                        </a>
                                                    </div>
                                                    <div class="col-4 text-end text-muted">
                                                        <button class="switch-icon switch-icon-scale text-warning">
                                                            <span class="switch-icon-a text-success">

                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="icon icon-tabler icon-tabler-edit-circle"
                                                                    width="24" height="24" viewBox="0 0 24 24"
                                                                    stroke-width="2" stroke="currentColor" fill="none"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none"></path>
                                                                    <path
                                                                        d="M12 15l8.385 -8.415a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3z">
                                                                    </path>
                                                                    <path d="M16 5l3 3"></path>
                                                                    <path d="M9 7.07a7 7 0 0 0 1 13.93a7 7 0 0 0 6.929 -6">
                                                                    </path>
                                                                </svg>
                                                            </span>
                                                        </button>
                                                        <button class="switch-icon switch-icon-scale"
                                                            data-bs-toggle="modal" data-bs-target="#update-status">
                                                            <span class="switch-icon-a text-primary">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="icon icon-tabler icon-tabler-circle-chevron-right"
                                                                    width="24" height="24" viewBox="0 0 24 24"
                                                                    stroke-width="2" stroke="currentColor" fill="none"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none"></path>
                                                                    <path d="M11 9l3 3l-3 3"></path>
                                                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0z"></path>
                                                                </svg>
                                                            </span>
                                                        </button>

                                                        <button class="switch-icon switch-icon-scale"
                                                            data-bs-toggle="modal" data-bs-target="#update-status">
                                                            <span class="switch-icon-a text-primary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                                                    viewBox="0 0 576 512">
                                                                    <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                                    <path
                                                                        d="M64 64C28.7 64 0 92.7 0 128V384c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H64zM272 192H496c8.8 0 16 7.2 16 16s-7.2 16-16 16H272c-8.8 0-16-7.2-16-16s7.2-16 16-16zM256 304c0-8.8 7.2-16 16-16H496c8.8 0 16 7.2 16 16s-7.2 16-16 16H272c-8.8 0-16-7.2-16-16zM164 152v13.9c7.5 1.2 14.6 2.9 21.1 4.7c10.7 2.8 17 13.8 14.2 24.5s-13.8 17-24.5 14.2c-11-2.9-21.6-5-31.2-5.2c-7.9-.1-16 1.8-21.5 5c-4.8 2.8-6.2 5.6-6.2 9.3c0 1.8 .1 3.5 5.3 6.7c6.3 3.8 15.5 6.7 28.3 10.5l.7 .2c11.2 3.4 25.6 7.7 37.1 15c12.9 8.1 24.3 21.3 24.6 41.6c.3 20.9-10.5 36.1-24.8 45c-7.2 4.5-15.2 7.3-23.2 9V360c0 11-9 20-20 20s-20-9-20-20V345.4c-10.3-2.2-20-5.5-28.2-8.4l0 0 0 0c-2.1-.7-4.1-1.4-6.1-2.1c-10.5-3.5-16.1-14.8-12.6-25.3s14.8-16.1 25.3-12.6c2.5 .8 4.9 1.7 7.2 2.4c13.6 4.6 24 8.1 35.1 8.5c8.6 .3 16.5-1.6 21.4-4.7c4.1-2.5 6-5.5 5.9-10.5c0-2.9-.8-5-5.9-8.2c-6.3-4-15.4-6.9-28-10.7l-1.7-.5c-10.9-3.3-24.6-7.4-35.6-14c-12.7-7.7-24.6-20.5-24.7-40.7c-.1-21.1 11.8-35.7 25.8-43.9c6.9-4.1 14.5-6.8 22.2-8.5V152c0-11 9-20 20-20s20 9 20 20z" />
                                                                </svg>
                                                            </span>
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div> --}}
                            <div class="row row-cards">
                                @foreach ($leads as $lead)
                                    @if ($lead->lead_status == 2)
                                        <div class="col-12">
                                            <div class="card card-sm card-link card-link-pop">
                                                <div class="card-status-top bg-yellow"></div>
                                                <div class="card-body">
                                                    @if ($lead->customer_type === 'residential_customer_type')
                                                        <h3 class="card-title mb-2">{{ ucfirst($lead->customer_name) }}
                                                        </h3>
                                                        <div class="text-muted"> Residential</div>
                                                        <div class="text-muted"> +65 {{ $lead->mobile_number }}</div>
                                                    @elseif ($lead->customer_type === 'commercial_customer_type')
                                                        <h3 class="card-title mb-2">
                                                            {{ ucfirst($lead->individual_company_name) }}</h3>
                                                        <div class="text-muted"> Commercial</div>
                                                        <div class="text-muted"> +65 {{ $lead->mobile_number }}</div>
                                                    @endif
                                                                                   
                                                    <div class="mt-3">
                                                        <div class="row">
                                                            <div class="col-8">
                                                                <a href="#" class="link-warning">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                                        width="24" height="24" viewBox="0 0 24 24"
                                                                        stroke-width="2" stroke="currentColor"
                                                                        fill="none" stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none">
                                                                        </path>
                                                                        <path
                                                                            d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z">
                                                                        </path>
                                                                        <path d="M16 3l0 4"></path>
                                                                        <path d="M8 3l0 4"></path>
                                                                        <path d="M4 11l16 0"></path>
                                                                        <path d="M11 15l1 0"></path>
                                                                        <path d="M12 15l0 3"></path>
                                                                    </svg>
                                                                    {{ $lead->schedule_date ? date('d-m-Y', strtotime($lead->schedule_date)) : '' }}
                                                                </a>

                                                                @if ($lead->pending_customer_approval_status == 2)
                                                                    <div class="text-muted mt-3">
                                                                        <span class="badge rounded-pill bg-success">Approved</span>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <div class="col-4 text-end text-muted">
                                                                <button class="switch-icon switch-icon-scale text-warning"
                                                                    data-bs-dismiss="modal" data-bs-target="#update-lead"
                                                                    onclick="updateLead('{{ $lead->id }}')"
                                                                    title="Edit">
                                                                    <span class="switch-icon-a text-success">

                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="icon icon-tabler icon-tabler-edit-circle"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" stroke-width="2"
                                                                            stroke="currentColor" fill="none"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none"></path>
                                                                            <path
                                                                                d="M12 15l8.385 -8.415a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3z">
                                                                            </path>
                                                                            <path d="M16 5l3 3"></path>
                                                                            <path
                                                                                d="M9 7.07a7 7 0 0 0 1 13.93a7 7 0 0 0 6.929 -6">
                                                                            </path>
                                                                        </svg>
                                                                    </span>
                                                                </button>

                                                                {{-- <a href="{{ route('lead.create') }}"
                                                                    class="switch-icon switch-icon-scale"
                                                                    data-bs-toggle="modal" data-bs-target="#update-status"
                                                                    onclick="updtaeStatus('{{ $lead->id }}')">
                                                                    <span class="switch-icon-a text-primary">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="icon icon-tabler icon-tabler-circle-chevron-right"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" stroke-width="2"
                                                                            stroke="currentColor" fill="none"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none"></path>
                                                                            <path d="M11 9l3 3l-3 3"></path>
                                                                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0z">
                                                                            </path>
                                                                        </svg>
                                                                    </span>
                                                                </a> --}}

                                                                <button
                                                                    class="switch-icon switch-icon-scale payment-button"
                                                                    data-lead-id="{{ $lead->id }}"
                                                                    data-bs-toggle="modal" data-bs-target="#lead-payment"
                                                                    onclick="paymentsProcess({{ $lead->id }})"
                                                                    id="paymentButton" title="Send Payment">
                                                                    <span class="switch-icon-a text-info">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="icon icon-tabler icon-tabler-playstation-x"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                            <rect x="1" y="4" width="22"
                                                                                height="16" rx="2"
                                                                                ry="2" />
                                                                            <line x1="1" y1="10"
                                                                                x2="23" y2="10" />
                                                                        </svg>
                                                                    </span>
                                                                </button>

                                                                <button class="switch-icon switch-icon-scale delete-button"
                                                                    data-lead-id="{{ $lead->id }}"
                                                                    data-bs-toggle="modal" data-bs-target="#delete-lead"
                                                                    title="Delete">

                                                                    <span class="switch-icon-a text-danger">
                                                                        {{-- <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="icon icon-tabler icon-tabler-playstation-x"
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
                                                                        </svg> --}}

                                                                        <i class="fa-solid fa-trash me-2 text-blue"></i>
                                                                    </span>
                                                                </button>

                                                                <button class="switch-icon switch-icon-scale reject-button"
                                                                    data-lead-id="{{ $lead->id }}"
                                                                    data-bs-toggle="modal" data-bs-target="#reject-lead"
                                                                    title="Reject">

                                                                    <span class="switch-icon-a text-danger">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="icon icon-tabler icon-tabler-playstation-x"
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
                                                                    </span>
                                                                </button>

                                                                <button
                                                                    class="switch-icon switch-icon-scale status_confirm_btn"
                                                                    data-lead-id="{{ $lead->id }}"
                                                                    data-bs-toggle="modal" data-bs-target="#confirm_lead"
                                                                    title="Confirm">

                                                                    <span class="switch-icon-a text-success">
                                                                        <i class="fa-solid fa-circle-check"></i>
                                                                    </span>
                                                                </button>

                                                                <a href="{{route('lead.log-report', $lead->id)}}" class="switch-icon switch-icon-scale text-warning" title="Log Report">
                                                                    <span class="switch-icon-a text-success">
                                                                        <i class="fa-solid fa-file me-2 text-blue"></i>
                                                                    </span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>                                                   
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- pending payment leads --}}
                    <div class="col-12 col-md-6 col-lg">
                        <h3 class="mb-3">Approved (Pending Deposit)({{ $pendingPayment->count() }})</h3>
                        <div class="mb-4">
                            <div class="row row-cards">
                                @foreach ($leads as $lead)
                                    @if ($lead->lead_status == 3)
                                        <div class="col-12">
                                            <div class="card card-sm card-link card-link-pop">
                                                <div class="card-status-top bg-red"></div>
                                                <div class="card-body">
                                                    @if ($lead->customer_type === 'residential_customer_type')
                                                        <h3 class="card-title mb-2">{{ ucfirst($lead->customer_name) }}
                                                        </h3>
                                                        <div class="text-muted"> Residential</div>
                                                        <div class="text-muted"> +65 {{ $lead->mobile_number }}</div>
                                                    @elseif ($lead->customer_type === 'commercial_customer_type')
                                                        <h3 class="card-title mb-2">
                                                            {{ ucfirst($lead->individual_company_name) }}</h3>
                                                        <div class="text-muted"> Commercial</div>
                                                        <div class="text-muted"> +65 {{ $lead->mobile_number }}</div>
                                                    @endif
                                                    <div class="mt-3">
                                                        <div class="row">

                                                            <div class="col-8">
                                                                <a href="#" class="link-warning">
                                                                    <!-- Download SVG icon from http://tabler-icons.io/i/calendar -->
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                                        width="24" height="24" viewBox="0 0 24 24"
                                                                        stroke-width="2" stroke="currentColor"
                                                                        fill="none" stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none"></path>
                                                                        <path
                                                                            d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z">
                                                                        </path>
                                                                        <path d="M16 3l0 4"></path>
                                                                        <path d="M8 3l0 4"></path>
                                                                        <path d="M4 11l16 0"></path>
                                                                        <path d="M11 15l1 0"></path>
                                                                        <path d="M12 15l0 3"></path>
                                                                    </svg>
                                                                    {{ $lead->schedule_date ? date('d-m-Y', strtotime($lead->schedule_date)) : '' }}

                                                                </a>
                                                            </div>
                                                            <div class="col-4 text-end text-muted">
                                                                <button
                                                                    class="switch-icon switch-icon-scale payment-button"
                                                                    data-lead-id="{{ $lead->id }}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#lead_received_payment"
                                                                    onclick="received_payment({{ $lead->id }})"
                                                                    title="Received Payment">
                                                                    <span class="switch-icon-a text-info">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="icon icon-tabler icon-tabler-playstation-x"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                            <rect x="1" y="4" width="22"
                                                                                height="16" rx="2"
                                                                                ry="2" />
                                                                            <line x1="1" y1="10"
                                                                                x2="23" y2="10" />
                                                                        </svg>
                                                                    </span>
                                                                </button>

                                                                <a href="{{route('lead.log-report', $lead->id)}}" class="switch-icon switch-icon-scale text-warning" title="Log Report">
                                                                    <span class="switch-icon-a text-success">
                                                                        <i class="fa-solid fa-file me-2 text-blue"></i>
                                                                    </span>
                                                                </a>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- approved leads --}}
                    <div class="col-12 col-md-6 col-lg">
                        <h3 class="mb-3">Approved Sales Order({{ $approvedLeads->count() }})</h3>
                        <div class="mb-4">
                            <div class="row row-cards">
                                @foreach ($leads as $lead)
                                    @if ($lead->lead_status == 4 && $lead->cleaner_assigned_status == 0)
                                        <div class="col-12">
                                            <div class="card card-sm card-link card-link-pop">
                                                <div class="card-status-top bg-yellow"></div>
                                                <div class="card-body">
                                                    @if ($lead->customer_type === 'residential_customer_type')
                                                        <h3 class="card-title mb-2">{{ ucfirst($lead->customer_name) }}
                                                        </h3>
                                                        <div class="text-muted"> Residential</div>
                                                        <div class="text-muted"> +65 {{ $lead->mobile_number }}</div>
                                                    @elseif ($lead->customer_type === 'commercial_customer_type')
                                                        <h3 class="card-title mb-2">
                                                            {{ ucfirst($lead->individual_company_name) }}</h3>
                                                        <div class="text-muted"> Commercial</div>
                                                        <div class="text-muted"> +65 {{ $lead->mobile_number }}</div>
                                                    @endif


                                                    <div class="mt-3">
                                                        <div class="row">

                                                            <div class="col-8">
                                                                <a href="#" class="link-warning">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                                        width="24" height="24" viewBox="0 0 24 24"
                                                                        stroke-width="2" stroke="currentColor"
                                                                        fill="none" stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none">
                                                                        </path>
                                                                        <path
                                                                            d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z">
                                                                        </path>
                                                                        <path d="M16 3l0 4"></path>
                                                                        <path d="M8 3l0 4"></path>
                                                                        <path d="M4 11l16 0"></path>
                                                                        <path d="M11 15l1 0"></path>
                                                                        <path d="M12 15l0 3"></path>
                                                                    </svg>
                                                                    {{ $lead->schedule_date ? date('d-m-Y', strtotime($lead->schedule_date)) : '' }}
                                                                </a>
                                                            </div>
                                                            <div class="col-4 text-end text-muted">
                                                                <button class="switch-icon switch-icon-scale text-warning"
                                                                    data-bs-dismiss="modal" data-bs-target="#view-lead"
                                                                    onclick="viewLead({{ $lead->id }})"
                                                                    title="View">
                                                                    <span class="switch-icon-a text-success">
                                                                        <i class="fa-solid fa-eye me-2 text-blue"></i>
                                                                    </span>
                                                                </button>

                                                                <a href="{{route('lead.log-report', $lead->id)}}" class="switch-icon switch-icon-scale text-warning" title="Log Report">
                                                                    <span class="switch-icon-a text-success">
                                                                        <i class="fa-solid fa-file me-2 text-blue"></i>
                                                                    </span>
                                                                </a>

                                                                {{-- <button class="switch-icon switch-icon-scale"
                                                                    data-bs-toggle="modal" data-bs-target="#update-status"
                                                                    onclick="updtaeStatus('{{ $lead->id }}')">
                                                                    <span class="switch-icon-a text-primary">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="icon icon-tabler icon-tabler-circle-chevron-right"
                                                                            width="24" height="24" viewBox="0 0 24 24"
                                                                            stroke-width="2" stroke="currentColor"
                                                                            fill="none" stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none"></path>
                                                                            <path d="M11 9l3 3l-3 3"></path>
                                                                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0z">
                                                                            </path>
                                                                        </svg>
                                                                    </span>
                                                                </button> --}}
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- reject leads --}}
                    {{-- <div class="col-12 col-md-6 col-lg">
                        <h3 class="mb-3">Expired/ Rejected({{count($expired_leads)}})</h3>
                        <div class="mb-4">
                            <div class="row row-cards">
                                <div class="col-12">
                                    @foreach ($leads as $lead)
                                        @if ($lead->lead_status == 5)
                                            <div class="col-12">
                                                <div class="card card-sm card-link card-link-pop">
                                                    <div class="card-status-top bg-yellow"></div>
                                                    <div class="card-body">
                                                        @if ($lead->customer_type === 'residential_customer_type')
                                                            <h3 class="card-title mb-2">{{ ucfirst($lead->customer_name) }}</h3>
                                                            <div class="text-muted"> Residential</div>
                                                            <div class="text-muted"> +65 {{$lead->mobile_number}}</div>
                                                        @elseif ($lead->customer_type === 'commercial_customer_type')
                                                            <h3 class="card-title mb-2">{{ ucfirst($lead->individual_company_name) }}</h3>
                                                            <div class="text-muted"> Commercial</div>
                                                            <div class="text-muted"> +65 {{$lead->mobile_number}}</div>
                                                        @endif
                                                        <div class="text-muted">
                                                            <!-- <span class="badge rounded-pill bg-success">Confirmed</span> -->
                                                        </div>
                                                        <div class="mt-3">
                                                            <div class="row">

                                                                <div class="col-8">
                                                                    <a href="#" class="link-warning">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                                            width="24" height="24" viewBox="0 0 24 24"
                                                                            stroke-width="2" stroke="currentColor"
                                                                            fill="none" stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none">
                                                                            </path>
                                                                            <path
                                                                                d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z">
                                                                            </path>
                                                                            <path d="M16 3l0 4"></path>
                                                                            <path d="M8 3l0 4"></path>
                                                                            <path d="M4 11l16 0"></path>
                                                                            <path d="M11 15l1 0"></path>
                                                                            <path d="M12 15l0 3"></path>
                                                                        </svg>
                                                                        {{ ($lead->schedule_date)?date('d-m-Y', strtotime($lead->schedule_date)):'' }}
                                                                    </a>
                                                                </div>

                                                                <div class="col-4 text-end text-muted">
                                                                    <button class="switch-icon switch-icon-scale text-warning"
                                                                        data-bs-dismiss="modal" data-bs-target="#view-lead"
                                                                        onclick="viewLead({{ $lead->id }})" title="View">
                                                                        <span class="switch-icon-a text-success">
                                                                            <i class="fa-solid fa-eye me-2 text-blue"></i>
                                                                        </span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    {{-- partial assigned sales order --}}
                    <div class="col-12 col-md-6 col-lg">
                        <h3 class="mb-3">Partial Assigned Sales Order({{ count($sales_order) }})</h3>
                        <div class="mb-4">
                            <div class="row row-cards">
                                <div class="col-12">
                                    @foreach ($sales_order as $item)
                                        <div class="col-12">
                                            <div class="card card-sm card-link card-link-pop">
                                                <div class="card-status-top bg-yellow"></div>
                                                <div class="card-body">
                                                    @if ($item->customer_type === 'residential_customer_type')
                                                        <h3 class="card-title mb-2">{{ ucfirst($item->customer_name) }}
                                                        </h3>
                                                        <div class="text-muted"> Residential</div>
                                                        <div class="text-muted"> +65 {{ $item->mobile_number }}</div>
                                                    @elseif ($item->customer_type === 'commercial_customer_type')
                                                        <h3 class="card-title mb-2">
                                                            {{ ucfirst($item->individual_company_name) }}</h3>
                                                        <div class="text-muted"> Commercial</div>
                                                        <div class="text-muted"> +65 {{ $item->mobile_number }}</div>
                                                    @endif
                                                    <div class="text-muted">
                                                        <!-- <span class="badge rounded-pill bg-success">Confirmed</span> -->
                                                    </div>
                                                    <div class="mt-3">
                                                        <div class="row">
                                                            <div class="col-8"></div>

                                                            <div class="col-4 text-end text-muted">
                                                                <button class="switch-icon switch-icon-scale text-warning"
                                                                    onclick="edit_cleaner({{ $item->id }})"
                                                                    title="Edit Cleaner">
                                                                    <span class="switch-icon-a text-success">
                                                                        <i class="fa-solid fa-edit me-2 text-green"></i>
                                                                    </span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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

    {{-- create lead --}}
    <div class="modal modal-blur fade" id="add-lead" tabindex="-1" role="dialog" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document">
            <div class="modal-content" id="add-lead-content">

            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="update-status" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content" id="update-status-content">



            </div>
        </div>
    </div>

    {{-- delete lead --}}
    <div class="modal modal-blur fade" id="delete-lead" tabindex="-1" style="display: none;" aria-hidden="true">
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
                    <div class="text-muted">Do you really want to remove this Lead?</div>
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
                                <a href="#" class="btn btn-danger w-100" id="confirm-delete">
                                    Delete
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- confirm-lead --}}
    <div class="modal modal-blur fade" id="confirm_lead" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-success icon-lg" width="24"
                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M9 12l2 2l4 -4" />
                    </svg>
                    <h3>Are you sure?</h3>
                    <div class="text-muted">Do you really want to confirm this Lead?</div>
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
                                <a href="#" class="btn btn-success w-100" id="confirm_modal_btn">
                                    Confirm
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="add-customer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 800px;">
            <div class="modal-content" id="add-customer-content">

            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="add-branch" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 800px;">

            <div class="modal-content" id="add-branch-content">
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="update-lead" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document">
            <div class="modal-content" id="update-lead-content">

            </div>
        </div>
    </div>

    {{-- -------send payment-------------------------- --}}
    <div class="modal modal-blur fade" id="lead-payment" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document">
            <div class="modal-content" id="lead-payment-content">

            </div>
        </div>
    </div>

    {{-- -------received payment-------------------------- --}}
    <div class="modal modal-blur fade" id="lead_received_payment" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document">
            <div class="modal-content" id="lead_received_payment_content">

            </div>
        </div>
    </div>

    {{-- view lead --}}
    <div class="modal modal-blur fade" id="view-lead" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document">
            <div class="modal-content" id="lead-view-content">

            </div>
        </div>
    </div>

    {{-- reject lead --}}
    <div class="modal modal-blur fade" id="reject-lead" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="reject_form" method="POST">
                    @csrf
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-status bg-danger"></div>
                    <div class="modal-body text-center py-4">
                        <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 9v2m0 4v.01"></path>
                            <path
                                d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75">
                            </path>
                        </svg>
                        <h3>Are you sure?</h3>
                        <div class="text-muted">Do you really want to reject this Lead?</div>
                        <div class="mt-3">
                            <input type="hidden" name="reject_lead_id" id="reject_lead_id">
                            <textarea class="form-control" name="reject_remarks" id="reject_remarks" cols="30" rows="5"
                                placeholder="Remarks"></textarea>
                        </div>
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
                                    <button type="submit" class="btn btn-danger w-100" id="confirm-reject">
                                        Reject
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- edit cleaner --}}
    <div class="modal modal-blur fade" id="edit_cleaner_modal" tabindex="-1" role="dialog" aria-hidden="true" data-flag="edit">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" id="edit_cleaner_modal_content">

            </div>
        </div>
    </div>


    {{-- confirm assign --}}
    <div class="modal modal-blur fade" id="confirmation_assign_modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                    <svg  xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-success icon-lg" width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>
                    <h3>Are you sure?</h3>
                    <div class="text-muted">Do you really want to assign?</div>
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
                                <a href="#" class="btn btn-success w-100" id="confirmation_assign_modal_btn">
                                    Confirm
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('custom_js')
    <!-- Tabler Core -->
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="{!! asset('public/theme/dist/js/smart-wizaed.js') !!}" type="text/javascript"></script> --}}

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
            
            $('#myselection').on('change', function() {
                var demovalue = $(this).val();
                $("div.myDiv").hide();
                $("#show" + demovalue).show();
            });
        });

        function toggler(divId) {
            $("#" + divId).toggle();
        }

        function addBtn2() {
            toggler('div2');

        }

        function showFormModal() {
            $.ajax({
                url: "{{ route('lead.create') }}",
                type: "GET",
                success: function(response) {
                    // console.log(response);
                    $('#add-lead').modal('show');
                    $('#add-lead-content').html(response);
                },
                error: function() {
                    console.log('Error occurred while loading the modal content.');
                }
            });
        }

        // $('.add-modal-close').click(function() {
        function closeModal() {
            $('#selected-services-table-tbody').empty();
            $('#quotation-table tbody').empty();
            $('#add-lead').modal('hide');
        }

        // })

        function showCRMModal() {
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

        function updtaeStatus(leadId) {
            $.ajax({
                url: "{{ route('updateStatus.create') }}?lead_id=" + leadId,
                type: "GET",
                success: function(response) {
                    $('#update-status').modal('show');
                    $('#update-status-content').html(response);
                },
                error: function() {
                    console.log('Error occurred while loading the modal content.');
                }
            });
        }

        function updateLead(leadId) {
            // alert(leadId)
            $.ajax({
                url: "{{ route('lead.edit') }}?lead_id=" + leadId,
                type: "GET",
                success: function(response) {
                    // console.log(response);
                    $('#update-lead').modal('show');
                    $('#update-lead-content').html(response);
                },
                error: function(response) {
                    console.log(response);
                    console.log('Error occurred while loading the modal content.');
                }
            })
        }

        $(document).ready(function() {

            // delete lead start

            $('.delete-button').click(function() {
                const leadId = $(this).data('lead-id');
                $('#delete-lead').data('lead-id', leadId);
            });

            $('#delete-lead').on('hidden.bs.modal', function() {
                $(this).removeData('lead-id');
            });

            $('#confirm-delete').click(function() {
                const leadId = $('#delete-lead').data('lead-id');

                $.ajax({
                    url: "{{ route('lead.delete') }}",
                    type: 'post',
                    data: {
                        leadId: leadId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response);
                        $('#delete-lead').modal('hide');
                        window.location.reload();
                    },
                    error: function(error) {
                        console.log(error);
                        console.log('Delete request error:', error);
                    }
                });

            });

            // delete laed end

            // reject lead start

            $('.reject-button').click(function() {
                const leadId = $(this).data('lead-id');
                // $('#reject-lead').data('lead-id', leadId);
                $("#reject-lead #reject_lead_id").val(leadId);
            });

            $('#reject-lead').on('hidden.bs.modal', function() {
                // $(this).removeData('lead-id');
                $("#reject-lead #reject_lead_id").val("");
            });

            // $('#confirm-reject').click(function() {
            //     const leadId = $('#reject-lead').data('lead-id');

            //     $.ajax({
            //         url: "{{ route('lead.reject') }}",
            //         type: 'post',
            //         data: {leadId: leadId},
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         success: function(response) {
            //             console.log(response);
            //             $('#reject-lead').modal('hide');
            //             window.location.reload();
            //         },
            //         error: function(error) {
            //             console.log(error);
            //             console.log('Reject request error:', error);
            //         }
            //     });

            // });

            $('body').on('submit', '#reject_form', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('lead.reject') }}",
                    type: 'post',
                    data: $(this).serialize(),
                    success: function(response) {
                        console.log(response);

                        $('#reject-lead').modal('hide');

                        if (response.status == "success") {
                            iziToast.success({
                                message: response.message,
                                position: 'topRight'
                            });
                        } else {
                            iziToast.error({
                                message: response.message,
                                position: 'topRight'
                            });
                        }

                        location.reload();
                    },
                    error: function(error) {
                        console.log(error);
                        console.log('Reject request error:', error);
                    }
                });

            });

            // reject lead end

            // confirm lead start

            $('body').on('click', '.status_confirm_btn', function() {

                var leadId = $(this).data('lead-id');
                $('#confirm_lead').data('lead_id', leadId);

            });

            $('body').on('click', '#confirm_modal_btn', function() {
                var leadId = $('#confirm_lead').data('lead_id');

                $.ajax({
                    url: "{{ route('lead.confirm') }}",
                    type: 'post',
                    data: {
                        leadId: leadId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        console.log(result);

                        $('#confirm_lead').modal('hide');

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
                    error: function(error) {
                        console.log(error);
                    }
                });

            });

            // confirm lead end
        });

        $(document).ready(function() {
            paymentsProcess = function(leadId) {
                // console.log('hello');
                $.ajax({
                    url: "{{ route('lead.send-payment') }}?lead_id=" + leadId,
                    type: "GET",
                    success: function(response) {
                        // console.log(response);
                        $('#lead-payment').modal('show');
                        $('#lead-payment-content').html(response);
                        // initializeModalAndSmartWizard(response, leadId);
                    },
                    error: function(response) {
                        console.log(response);
                        console.log('Error occurred while loading the modal content.');
                    }
                });
            }
        });

        function viewLead(leadId) {
            $.ajax({
                url: "{{ route('lead.view') }}",
                type: "GET",
                data: {
                    lead_id: leadId
                },
                success: function(response) {
                    // console.log(response);
                    $('#view-lead').modal('show');
                    $('#lead-view-content').html(response);
                },
                error: function(response) {
                    console.log(response);
                    console.log('Error occurred while loading the modal content.');
                }
            });
        }

        function received_payment(leadId) {
            $.ajax({
                url: "{{ route('lead.received-payment') }}",
                type: "GET",
                data: {
                    lead_id: leadId
                },
                success: function(response) {
                    // console.log(response);
                    $('#lead_received_payment').modal('show');
                    $('#lead_received_payment_content').html(response);
                },
                error: function(response) {
                    console.log(response);
                    console.log('Error occurred while loading the modal content.');
                }
            });
        }

        // edit cleaner start

        function edit_cleaner(id) {
            $.ajax({
                url: "{{ route('cleaner.edit', ['id' => ':id']) }}".replace(':id', id),
                type: "GET",
                success: function(response) {
                    // console.log(response);
                    $('#edit_cleaner_modal_content').html(response);
                    $('#edit_cleaner_modal').modal('show');
                },
                error: function() {
                    console.log('Error occurred while loading the edit modal content.');
                }
            });
        }

        function setEndTime(startTime, get_hour) {
            var startTime = new Date('1970-01-01T' + startTime);
            var getHourInSeconds = get_hour * 60 * 60;
            var endTime = new Date(startTime.getTime() + getHourInSeconds * 1000);

            var formattedEndTime = endTime.toTimeString().substring(0, 5);

            return formattedEndTime;
        }

        // check cleaner exists for each schedule date

        function check_cleaner_exists(el)
        {
            var table_startTime = el.parents('tr').find('.table_startTime').val();
            var table_endTime = el.parents('tr').find('.table_endTime').val();
            var table_schedule_date = el.parents('tr').find('.table_schedule_date').val();
            var sales_order_id = el.parents('form').find(".sales_order_id").val();
            var sales_order_no = el.parents('form').find(".sales_order_no").val();
            var cleaner_type = el.parents('form').find('input[name="cleaner_type"]:checked').val();

            $.ajax({
                type: "get",
                url: "{{route('schedule-date-check-cleaner-exists')}}",
                data: {
                    table_schedule_date: table_schedule_date,
                    sales_order_id: sales_order_id,
                    sales_order_no: sales_order_no,
                    cleaner_type: cleaner_type,
                    table_startTime: table_startTime,
                    table_endTime: table_endTime
                },
                success: function (result) {
                    console.log(result);    
                    
                    if(result.cleaner_type == "individual")
                    {
                        el.parents('tr').find('.table_cleaner_id').html("");
                        el.parents('tr').find(".table_superviser_emp_id").html("");
                        el.parents('tr').find(".table_superviser_emp_id").html("<option value=''>Select</option>");

                        $.each(result.users, function (key, value) { 
                            if(result.sch_indv_emp.includes(value.user_id) == false)
                            {                          
                                var html = `<option value="${value.user_id}" style="background-color: ${value.zone_color}" data-color="${value.zone_color}">${value.full_name}</option>`;                        
                            }
                            
                            el.parents('tr').find(".table_cleaner_id").append(html);
                        });
                    }
                    else if(result.cleaner_type == "team")
                    {
                        el.parents('tr').find(".table_team_id").html("");
                        el.parents('tr').find(".table_team_id").html("<option value=''>Select Team</option>");
                        el.parents('tr').find(".table_employee_names").val("");

                        $.each(result.get_team, function (key, value) { 
                            if(result.sch_team_emp.includes(value.team_id) == false)
                            {                          
                                var html = `<option value="${value.team_id}">${value.team_name}</option>`;                        
                            }
                            
                            el.parents('tr').find(".table_team_id").append(html);
                        });
                    }
                },
                error: function (result) {
                    console.log(result);
                }
            });
        }

        // check cleaner exists for whole table

        function set_change_full_cleaner_table(el, table_id)
        {
            var sales_order_id = el.parents('form').find(".sales_order_id").val();
            var sales_order_no = el.parents('form').find(".sales_order_no").val();
            var cleaner_type = el.parents('form').find('input[name="cleaner_type"]:checked').val();

            $('#'+table_id+' tbody tr').each(function() {
                var table_startTime = $(this).find('.table_startTime').val();
                var table_endTime = $(this).find('.table_endTime').val();
                var table_schedule_date = $(this).find('.table_schedule_date').val();

                var el_2 = $(this);

                $.ajax({
                    type: "get",
                    url: "{{route('schedule-date-check-cleaner-exists')}}",
                    data: {
                        table_schedule_date: table_schedule_date,
                        sales_order_id: sales_order_id,
                        sales_order_no: sales_order_no,
                        cleaner_type: cleaner_type,
                        table_startTime: table_startTime,
                        table_endTime: table_endTime
                    },
                    success: function (result) {
                        console.log(result);    
                        
                        if(result.cleaner_type == "individual")
                        {
                            el_2.find('.table_cleaner_id').html("");
                            el_2.find(".table_superviser_emp_id").html("");
                            el_2.find(".table_superviser_emp_id").html("<option value=''>Select</option>");

                            $.each(result.users, function (key, value) { 
                                if(result.sch_indv_emp.includes(value.user_id) == false)
                                {                          
                                    var html = `<option value="${value.user_id}" style="background-color: ${value.zone_color}" data-color="${value.zone_color}">${value.full_name}</option>`;                        
                                }
                                
                                el_2.find(".table_cleaner_id").append(html);
                            });
                        }
                        else if(result.cleaner_type == "team")
                        {
                            el_2.find(".table_team_id").html("");
                            el_2.find(".table_team_id").html("<option value=''>Select Team</option>");
                            el_2.find(".table_employee_names").val("");

                            $.each(result.get_team, function (key, value) { 
                                if(result.sch_team_emp.includes(value.team_id) == false)
                                {                          
                                    var html = `<option value="${value.team_id}">${value.team_name}</option>`;                        
                                }
                                
                                el_2.find(".table_team_id").append(html);
                            });
                        }
                    },
                    error: function (result) {
                        console.log(result);
                    }
                });
            });
        }

        // check driver exists for each delivery date

        function check_driver_exists(el)
        {
            var table_delivery_time = el.parents('tr').find('.table_delivery_time').val();
            var table_delivery_date = el.parents('tr').find('.table_delivery_date').val();
            var sales_order_id = el.parents('form').find(".sales_order_id").val();
            var sales_order_no = el.parents('form').find(".sales_order_no").val();

            $.ajax({
                type: "get",
                url: "{{route('delivery-date-check-driver-exists')}}",
                data: {
                    table_delivery_date: table_delivery_date,
                    table_delivery_time: table_delivery_time,
                    sales_order_id: sales_order_id,
                    sales_order_no: sales_order_no             
                },
                success: function (result) {
                    console.log(result);    
                    
                    el.parents('tr').find('.table_driver_emp_id').html("");                      

                    $.each(result.users, function (key, value) { 
                        if(result.driver_emp.includes(value.user_id) == false)
                        {                          
                            var html = `<option value="${value.user_id}" style="background-color: ${value.zone_color}" data-color="${value.zone_color}">${value.full_name}</option>`;                        
                        }
                        else
                        {
                            var html = `<option value="${value.user_id}" style="background-color: ${value.zone_color}" data-color="${value.zone_color}" disabled>${value.full_name}</option>`;                        
                        }
                        
                        el.parents('tr').find(".table_driver_emp_id").append(html);
                    });
                },
                error: function (result) {
                    console.log(result);
                }
            });
        }

        // edit cleaner end

        $(document).ready(function() {

            // received payment start           

            $('body').on('submit', '#lead_received_payment_form', function(e){

                e.preventDefault();

                var data = new FormData($(this)[0]);

                $.ajax({
                    url: "{{ route('lead.received-payment.store') }}",
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

                            $('#lead_received_payment').modal('hide');
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

            // received payment end

            // *** edit cleaner start ***

            $('body').on('click', '.cleaner_type', function() {

                // console.log($(this).val());

                // form
                var hidden_schedule_flag = $(this).parents('form').find('#hidden_schedule_flag').val();

                if ($(this).val() == "team") 
                {
                    $(this).parents('form').find(".table_individual").hide();
                    $(this).parents('form').find('.table_superviser').hide(); 
                    $(this).parents('form').find(".table_team").show();
                    $(this).parents('form').find(".table_employee").show();    
                    
                    // css
                    $(this).parents('form').find(".table_delivery_date_group").css({"margin-top": "133px"});
                    $(this).parents('form').find(".table_delivery_time_group").css({"margin-top": "133px"});

                    if(hidden_schedule_flag == "edit")
                    {
                        $(this).parents('form').find(".table_delivery_remarks_group").css({"margin-top": "60px"});
                    } 
                    else
                    {
                        $(this).parents('form').find(".table_delivery_remarks_group").css({"margin-top": "100px"});
                    } 
                } 
                else 
                {
                    $(this).parents('form').find(".table_individual").show();
                    $(this).parents('form').find('.table_superviser').show(); 
                    $(this).parents('form').find(".table_team").hide();
                    $(this).parents('form').find(".table_employee").hide();      
                    
                    // css
                    $(this).parents('form').find(".table_delivery_date_group").css({"margin-top": "70px"});
                    $(this).parents('form').find(".table_delivery_time_group").css({"margin-top": "70px"});

                    if(hidden_schedule_flag == "edit")
                    {
                        $(this).parents('form').find(".table_delivery_remarks_group").css({"margin-top": "0px"});
                    } 
                    else
                    {
                        $(this).parents('form').find(".table_delivery_remarks_group").css({"margin-top": "35px"});
                    } 
                }

            });

            $('body').on('change', '.table_team_id', function() {

                var selectedTeamId = $(this).find(':selected').val();

                var employeeNames = @php echo json_encode($employeeNames); @endphp;

                var temp = "";

                if (employeeNames[selectedTeamId]) {
                    var temp_employeeNames = employeeNames[selectedTeamId];

                    temp_employeeNames.forEach(function(employeeName) {
                        temp += employeeName + '\n';
                    });
                }

                $(this).parents('tr').find(".table_employee_names").val(temp);

            });

            $('body').on('change', '.startTime', function() {

                var start_time = $(this).val();
                var db_get_hour = $(this).parents('form').find('.db_get_hour').val();

                var end_time = setEndTime(start_time, db_get_hour);

                $(this).parents('form').find('.endTime').val(end_time);
                $(this).parents('form').find('.table_startTime').val(start_time);
                $(this).parents('form').find('.table_endTime').val(end_time);

                var modal_flag = $(this).parents('.modal').data('flag');

                if(modal_flag == "edit")
                {
                    set_change_full_cleaner_table($(this), 'edit_set_details_table');
                }
                else
                {
                    // setEndDates();
                    set_change_full_cleaner_table($(this), 'set_details_table');
                }      

            });

            $('body').on('change', '.endTime', function() {

                var end_time = $(this).val();

                $(this).parents('form').find('.table_endTime').val(end_time);

                var modal_flag = $(this).parents('.modal').data('flag');

                if(modal_flag == "edit")
                {
                    set_change_full_cleaner_table($(this), 'edit_set_details_table');
                }
                else
                {
                    // setEndDates();
                    set_change_full_cleaner_table($(this), 'set_details_table');
                }    

            });

            $('body').on('change', '.table_startTime', function() {

                var start_time = $(this).val();
                var db_get_hour = $(this).parents('form').find('.db_get_hour').val();

                var end_time = setEndTime(start_time, db_get_hour);

                $(this).parents('tr').find('.table_endTime').val(end_time);

                check_cleaner_exists($(this));

            });

            $('body').on('change', '.table_endTime', function(){

                check_cleaner_exists($(this));

            });

            $('body').on('change', '.table_schedule_date', function(){

                check_cleaner_exists($(this));

            });
            
            // update assign cleaner

            $('body').on('submit', '#edit_cleaner_form', function(e){

                e.preventDefault();

                var assign_confirm = true;

                // check start time start

                var table_startTime_arr = $(this).find('.table_startTime').map(function(){
                    return $(this).val()
                }).get();                

                // console.log(table_startTime_arr);

                $.each(table_startTime_arr, function (key, value) { 
                     
                    var timeComponents = value.split(":");
                    var hours = parseInt(timeComponents[0], 10);
                    // console.log(hours);

                    if (hours >= 21 || hours < 7) 
                    {
                        assign_confirm = false;
                    }

                });

                // check start time end

                // check end time start

                var table_endTime_arr = $(this).find('.table_endTime').map(function(){
                    return $(this).val()
                }).get();                

                // console.log(table_startTime_arr);

                $.each(table_endTime_arr, function (key, value) { 
                     
                    var timeComponents = value.split(":");
                    var hours = parseInt(timeComponents[0], 10);
                    // console.log(hours);

                    if (hours >= 21 || hours < 7) 
                    {
                        assign_confirm = false;                       
                    }

                });

                // check end time end

                if(assign_confirm == true)
                {
                    $.ajax({
                        type: "post",
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        success: function (result) {
                            console.log(result);

                            if(result.status == "error")
                            {
                                $.each(result.errors, function (key, value) { 
                                    
                                    iziToast.error({
                                        message: value,
                                        position: 'topRight'
                                    });

                                });
                            }
                            else if(result.status == "success")
                            {
                                iziToast.success({
                                    message: result.message,
                                    position: 'topRight'
                                });
                            
                                $("#edit_cleaner_modal").modal('hide');
                                location.reload();
                            }
                            else
                            {
                                iziToast.error({
                                    message: result.message,
                                    position: 'topRight'
                                });
                            }
                        },
                        error: function (result) {
                            console.log(result);
                        }
                    });
                }
                else
                {
                    $("#confirmation_assign_modal_btn").data('form_data', $(this).serialize());
                    $("#confirmation_assign_modal_btn").data('form_action', $(this).attr('action'));
                    $("#confirmation_assign_modal").modal('show');
                }               

            });

            // confirmation assign / edit cleaner

            $('body').on('click', '#confirmation_assign_modal_btn', function(){

                var form_data = $(this).data('form_data');
                var form_action = $(this).data('form_action');

                $.ajax({
                    type: "post",
                    url: form_action,
                    data: form_data,
                    success: function (result) {
                        console.log(result);

                        if(result.status == "error")
                        {
                            $.each(result.errors, function (key, value) { 
                                
                                iziToast.error({
                                    message: value,
                                    position: 'topRight'
                                });

                            });
                        }
                        else if(result.status == "success")
                        {
                            iziToast.success({
                                message: result.message,
                                position: 'topRight'
                            });
                        
                            $("#confirmation_assign_modal").modal('hide');
                            $("#detailsDialog").modal('hide');
                            location.reload();
                        }
                        else
                        {
                            iziToast.error({
                                message: result.message,
                                position: 'topRight'
                            });
                        }
                    },
                    error: function (result) {
                        console.log(result);
                    }
                });

            });

            // superviser start

            $('body').on('change', '.table_cleaner_id', function () {
                var el = $(this);
                var emp_id = el.val();

                $.ajax({
                    type: "get",
                    url: "{{route('team.get-superviser')}}",
                    data: {emp_id: emp_id},
                    success: function (result) {
                        // console.log(result);

                        if(result.xin_employees.length > 0)
                        {
                            el.parents('tr').find(".table_superviser_emp_id").html("");

                            $.each(result.xin_employees, function (key, value) { 
                                var html = `<option value="${value.user_id}">${value.first_name} ${value.last_name} (${value.zipcode})</option>`;
                            
                                el.parents('tr').find(".table_superviser_emp_id").append(html);
                            });                       
                        }
                        else
                        {
                            el.parents('tr').find(".table_superviser_emp_id").html("<option value=''>Select</option>");
                        }
                    },
                    error: function (result) {
                        console.log(result);
                    },
                });
            });

            // superviser end

            // driver start

            $('body').on('click', '.table_add_driver_btn', function () {

                var el = $(this);

                el.parents('tr').find(".table_delivery_date_group").show();
                el.parents('tr').find(".table_delivery_time_group").show();
                el.parents('tr').find(".table_driver").show();  
                el.parents('tr').find(".table_delivery_remarks_group").show();                            
                el.parents('tr').find(".table_remove_driver_btn").show();
                el.parents('tr').find(".table_add_driver_btn").hide();

                // cleaner type
                var cleaner_type = el.parents('form').find('input[name="cleaner_type"]:checked').val();

                // form
                var hidden_schedule_flag = el.parents('form').find('#hidden_schedule_flag').val();

                if(cleaner_type == "team")
                {
                    // css
                    el.parents('tr').find(".table_delivery_date_group").css({"margin-top": "133px"});
                    el.parents('tr').find(".table_delivery_time_group").css({"margin-top": "133px"});

                    if(hidden_schedule_flag == "edit")
                    {
                        el.parents('tr').find(".table_delivery_remarks_group").css({"margin-top": "60px"});
                    }   
                    else
                    {
                        el.parents('tr').find(".table_delivery_remarks_group").css({"margin-top": "100px"});
                    }          
                }
                else if(cleaner_type == "individual")
                {
                    // css
                    el.parents('tr').find(".table_delivery_date_group").css({"margin-top": "70px"});
                    el.parents('tr').find(".table_delivery_time_group").css({"margin-top": "70px"});

                    if(hidden_schedule_flag == "edit")
                    {
                        el.parents('tr').find(".table_delivery_remarks_group").css({"margin-top": "0px"});
                    } 
                    else
                    {
                        el.parents('tr').find(".table_delivery_remarks_group").css({"margin-top": "35px"});
                    } 
                }

            });

            $('body').on('click', '.table_remove_driver_btn', function () {

                var el = $(this);

                el.parents('tr').find(".table_delivery_date_group").hide();
                el.parents('tr').find(".table_delivery_date").val("");
         
                el.parents('tr').find(".table_delivery_time_group").hide();
                el.parents('tr').find(".table_delivery_time").val("08:00");

                el.parents('tr').find(".table_driver").hide();   
                el.parents('tr').find(".table_driver_emp_id").val("");                 

                el.parents('tr').find(".table_delivery_remarks_group").hide(); 
                el.parents('tr').find(".table_delivery_remarks").val(""); 
                          
                el.parents('tr').find(".table_remove_driver_btn").hide();
                el.parents('tr').find(".table_add_driver_btn").show();

            });

            $('body').on('change', '.table_delivery_time', function(){

                check_driver_exists($(this));

            });

            $('body').on('change', '.table_delivery_date', function(){

                check_driver_exists($(this));

            });

            // driver end

            // *** edit cleaner end ***

        });

    </script>
@endsection
