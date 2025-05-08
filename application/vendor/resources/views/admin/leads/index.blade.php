@extends('leadTheme.default')
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
                    <div class="col-12 col-md-6 col-lg">
                        <h3 class="mb-3">New Leads({{ count($leads) }})</h3>
                        <div class="mb-4">
                            <div class="row row-cards">
                                @foreach ($leads as $lead)
                                    <div class="col-12">
                                        <div class="card card-sm card-link card-link-pop">
                                            <div class="card-status-top bg-yellow"></div>
                                            <div class="card-body">
                                                <h3 class="card-title mb-2">{{ ucfirst($lead->customer_name) }}</h3>
                                                @if ($lead->customer_type === 'residential_customer_type')
                                                    <div class="text-muted"> Residential</div>
                                                @elseif ($lead->customer_type === 'commercial_customer_type')
                                                    <div class="text-muted"> Commercial</div>
                                                @endif


                                                <div class="mt-3">
                                                    <div class="row">

                                                        <div class="col-8">
                                                            <a href="#" class="link-warning">
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
                                                                {{ $lead->schedule_date }}
                                                            </a>
                                                        </div>
                                                        <div class="col-4 text-end text-muted">
                                                            <button class="switch-icon switch-icon-scale text-warning"
                                                                data-bs-dismiss="modal" data-bs-target="#update-lead"
                                                                onclick="updateLead('{{ $lead->id }}')">
                                                                <span class="switch-icon-a text-success">

                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon icon-tabler icon-tabler-edit-circle"
                                                                        width="24" height="24" viewBox="0 0 24 24"
                                                                        stroke-width="2" stroke="currentColor"
                                                                        fill="none" stroke-linecap="round"
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
                                                            <button class="switch-icon switch-icon-scale"
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
                    <div class="col-12 col-md-6 col-lg">
                        <h3 class="mb-3">Pending Customer Approval(1)</h3>
                        <div class="mb-4">
                            <div class="row row-cards">
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

                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg">
                        <!-- @php
                            $leadsCount = $leads->where('payment_type', 'advance');
                            
                        @endphp -->
                        <h3 class="mb-3">Pending Payment({{ $pendingLeads->count() }})</h3>
                        <div class="mb-4">
                            <div class="row row-cards">

                                @foreach ($leads as $lead)
                                    @if ($lead->payment_type === 'advance')
                                        <div class="col-12">
                                            <div class="card card-sm card-link card-link-pop">
                                                <div class="card-status-top bg-red"></div>
                                                <div class="card-body">
                                                    <h3 class="card-title mb-2">{{ ucfirst($lead->customer_name) }}</h3>
                                                    <!-- <h3 class="card-title mb-2">{{ ucfirst($lead->payment_type) }}</h3> -->

                                                    @if ($lead->customer_type === 'residential_customer_type')
                                                        <div class="text-muted"> Residential</div>
                                                    @elseif ($lead->customer_type === 'commercial_customer_type')
                                                        <div class="text-muted"> Commercial</div>
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
                                                                    {{ $lead->schedule_date }}

                                                                </a>
                                                            </div>
                                                            <div class="col-4 text-end text-muted">
                                                                <button class="switch-icon switch-icon-scale text-warning">
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
                                                                <button class="switch-icon switch-icon-scale"
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
                                                                            <path
                                                                                d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0z">
                                                                            </path>
                                                                        </svg>
                                                                    </span>
                                                                </button>
                                                                <button class="switch-icon switch-icon-scale delete-button"
                                                                    data-lead-id="{{ $lead->id }}"
                                                                    data-bs-toggle="modal" data-bs-target="#delete-lead">

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
                    <div class="col-12 col-md-6 col-lg">
                        <h3 class="mb-3">Approved({{ $approvedLeads->count() }})</h3>
                        <div class="mb-4">
                            <div class="row row-cards">
                                @foreach ($approvedLeads as $lead)
                                    <div class="col-12">
                                        <div class="card card-sm card-link card-link-pop">
                                            <div class="card-status-top bg-yellow"></div>
                                            <div class="card-body">
                                                <h3 class="card-title mb-2">{{ ucfirst($lead->customer_name) }}</h3>
                                                @if ($lead->customer_type === 'residential_customer_type')
                                                    <div class="text-muted"> Residential</div>
                                                @elseif ($lead->customer_type === 'commercial_customer_type')
                                                    <div class="text-muted"> Commercial</div>
                                                @endif


                                                <div class="mt-3">
                                                    <div class="row">

                                                        <div class="col-8">
                                                            <a href="#" class="link-warning">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                                    width="24" height="24" viewBox="0 0 24 24"
                                                                    stroke-width="2" stroke="currentColor" fill="none"
                                                                    stroke-linecap="round" stroke-linejoin="round">
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
                                                                {{ $lead->schedule_date }}
                                                            </a>
                                                        </div>
                                                        <div class="col-4 text-end text-muted">
                                                            <button class="switch-icon switch-icon-scale text-warning"
                                                                data-bs-dismiss="modal" data-bs-target="#update-lead"
                                                                onclick="updateLead('{{ $lead->id }}')">
                                                                <span class="switch-icon-a text-success">

                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon icon-tabler icon-tabler-edit-circle"
                                                                        width="24" height="24" viewBox="0 0 24 24"
                                                                        stroke-width="2" stroke="currentColor"
                                                                        fill="none" stroke-linecap="round"
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
                                                            <button class="switch-icon switch-icon-scale"
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
                    <div class="col-12 col-md-6 col-lg">
                        <h3 class="mb-3">Expired/ Rejected(1)</h3>
                        <div class="mb-4">
                            <div class="row row-cards">
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
            </div>
        </div>
        <footer class="footer footer-transparent d-print-none">

        </footer>
    </div>

    <div class="modal modal-blur fade" id="add-lead" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <path
                            d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75">
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

    <!-- Tabler Core -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myselection').on('change', function() {
                var demovalue = $(this).val();
                $("div.myDiv").hide();
                $("#show" + demovalue).show();
            });
        });
    </script>



    <script src="{!! asset('public/theme/dist/js/smart-wizaed.js') !!}" type="text/javascript"></script>



    <script>
        function toggler(divId) {
            $("#" + divId).toggle();
        }

        function addBtn2() {
            toggler('div2');

        }
    </script>





    <script>
        function showFormModal() {
            $.ajax({
                url: "{{ route('lead.create') }}",
                type: "GET",
                success: function(response) {
                    $('#add-lead').modal('show');
                    $('#add-lead-content').html(response);
                },
                error: function() {
                    console.log('Error occurred while loading the modal content.');
                }
            });
        }

        function showCRMModal() {
            // alert('hlo')
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
            alert(leadId)
            $.ajax({
                url: "{{ route('lead.edit') }}?lead_id=" + leadId,
                type: "GET",
                success: function(response) {
                    $('#update-lead').modal('show');
                    $('#update-lead-content').html(response);
                },
                error: function() {
                    console.log('Error occurred while loading the modal content.');
                }
            })
        }
    </script>
    <script>
        $(document).ready(function() {
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
                    url: '/leads/' + leadId,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#delete-lead').modal('hide');
                        window.location.reload();
                    },
                    error: function(error) {
                        console.log('Delete request error:', error);
                    }
                });
            });
        });
    </script>
@endsection
