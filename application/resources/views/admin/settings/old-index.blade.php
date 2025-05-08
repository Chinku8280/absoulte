@extends('theme.default')
<style>
  #salutation-table th {
        text-align: center;
    }
    #zone-table th {
        text-align: center;
    }
    #tax-table th {
        text-align: center;
    }
    #source-table th {
        text-align: center;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@section('content')
    @if(session('error_message'))
        <script>
            iziToast.error({
                title: 'Error',
                message: '{{ session('error_message') }}',
                position: 'topRight',
            });
        </script>
    @endif
    
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title"> Settings </h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="#constants-settings" class="nav-link active" data-bs-toggle="tab"
                                    aria-selected="true" role="tab">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 8h4v4h-4z" />
                                        <path d="M6 4l0 4" />
                                        <path d="M6 12l0 8" />
                                        <path d="M10 14h4v4h-4z" />
                                        <path d="M12 4l0 10" />
                                        <path d="M12 18l0 2" />
                                        <path d="M16 5h4v4h-4z" />
                                        <path d="M18 4l0 1" />
                                        <path d="M18 9l0 11" />
                                    </svg> Constants
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#zone-settings" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                                    tabindex="-1" role="tab">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                        <path
                                            d="M12.005 21.485a1.994 1.994 0 0 1 -1.418 -.585l-4.244 -4.243a8 8 0 1 1 13.634 -5.05" />
                                        <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M19.001 15.5v1.5" />
                                        <path d="M19.001 21v1.5" />
                                        <path d="M22.032 17.25l-1.299 .75" />
                                        <path d="M17.27 20l-1.3 .75" />
                                        <path d="M15.97 17.25l1.3 .75" />
                                        <path d="M20.733 20l1.3 .75" />
                                    </svg> Zone Settings
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#payment-method" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                                    tabindex="-1" role="tab">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                        <path
                                            d="M12.005 21.485a1.994 1.994 0 0 1 -1.418 -.585l-4.244 -4.243a8 8 0 1 1 13.634 -5.05" />
                                        <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M19.001 15.5v1.5" />
                                        <path d="M19.001 21v1.5" />
                                        <path d="M22.032 17.25l-1.299 .75" />
                                        <path d="M17.27 20l-1.3 .75" />
                                        <path d="M15.97 17.25l1.3 .75" />
                                        <path d="M20.733 20l1.3 .75" />
                                    </svg> Payment Method
                                </a>
                            </li>

                            <li class="nav-item" role="presentation">
                                <a href="#tax_tab" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                                    tabindex="-1" role="tab">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                        <path
                                            d="M12.005 21.485a1.994 1.994 0 0 1 -1.418 -.585l-4.244 -4.243a8 8 0 1 1 13.634 -5.05" />
                                        <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M19.001 15.5v1.5" />
                                        <path d="M19.001 21v1.5" />
                                        <path d="M22.032 17.25l-1.299 .75" />
                                        <path d="M17.27 20l-1.3 .75" />
                                        <path d="M15.97 17.25l1.3 .75" />
                                        <path d="M20.733 20l1.3 .75" />
                                    </svg> Tax
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#source_tab" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                                    tabindex="-1" role="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M16 21v-4a4 4 0 0 0 -8 0v4h-3v-4a7 7 0 1 1 14 0v4z" />
                                        <line x1="12" y1="11" x2="12" y2="17" />
                                        <line x1="12" y1="17" x2="18" y2="17" />
                                    </svg>
                                     Source
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active show" id="constants-settings" role="tabpanel">
                                <div class="card">
                                    <div class="row g-0">
                                        <div class="col-3 d-none d-md-block border-end">
                                            <div class="card-body py-0">
                                                <!-- <h4 class="subheader">constants settings</h4> -->
                                                <div class="list-group list-group-transparent tab">
                                                    <a href="javascript:void(0);"
                                                        class="tablinks list-group-item list-group-item-action d-flex align-items-center active"
                                                        onclick="opensettings(event, 'one')">Salutation</a>
                                                    <!-- <a href="javascript:void(0);"
                                                                         class="tablinks list-group-item list-group-item-action d-flex align-items-center"
                                                                         onclick="opensettings(event, 'two')">two</a><a href="javascript:void(0);"
                                                                         class="tablinks list-group-item list-group-item-action d-flex align-items-center"
                                                                         onclick="opensettings(event, 'three')">three</a><a href="javascript:void(0);"
                                                                         class="tablinks list-group-item list-group-item-action d-flex align-items-center"
                                                                         onclick="opensettings(event, 'four')">four</a><a href="javascript:void(0);"
                                                                         class="tablinks list-group-item list-group-item-action d-flex align-items-center"
                                                                         onclick="opensettings(event, 'five')">five</a> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col d-flex flex-column" >
                                            <div id="one" class="tabcontent" >
                                                <div class="card-body">
                                                    <h5 class="modal-title">Add Salutation</h5>
                                                    <div class="add-form my-3">
                                                        <form id="salutation-form" method="POST" action="{{route('setting.store')}}">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-auto">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Salutation Name</label>
                                                                        <input type="text" name="name"
                                                                            class="form-control" value=""
                                                                            required="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <label class="form-label"
                                                                        style="visibility: hidden;">Salutation Name</label>
                                                                    <button type="submit"
                                                                        class="btn btn-primary save-btn">Save</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="add-form-table">
                                                        <div class="table-responsive">
                                                            <table id="salutation-table"
                                                                class="table card-table table-vcenter text-center text-nowrap datatable data-table">
                                                                <thead>

                                                                    <th>Name</th>
                                                                    <th>Status</th>
                                                                    <th width="200px">Action</th>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($salutation_data as $key => $value)
                                                                    <tr>

                                                                        <td> {{ $value->salutation_name }} </td>
                                                                        <td>
                                                                            @if ($value->status == 1)
                                                                            <span class="badge bg-green">Active</span>
                                                                        @else
                                                                            <span class="badge bg-red">Deactive</span>
                                                                        @endif
                                                                        </td>
                                                                        <td>
                                                                            {{-- <i
                                                                                class='fa-solid fa-eye cursor-pointer me-2 text-blue btn-edit'></i> --}}
                                                                                <i class='btn btn-primary fa-solid fa-pencil cursor-pointer me-2 text-white btn-edit' data-id="{{ $value->id }}"></i>

                                                                            <i class='btn btn-danger fa-solid cursor-pointer fa-trash me-2 text-white btn-delete' data-id="{{ $value->id }}"></i>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="card-footer bg-transparent mt-auto">
                                                    <div class="btn-list justify-content-end">
                                                        <a href="#" class="btn"> Cancel </a>
                                                        <a href="#" class="btn btn-primary"> Submit </a>
                                                    </div>
                                                </div> --}}
                                            </div>
                                            <!-- <div id="two" class="tabcontent" style="display: none;"><div class="card-body"><h4>content-2</h4></div><div class="card-footer bg-transparent mt-auto"><div class="btn-list justify-content-end"><a href="#" class="btn">
                                                                             Cancel
                                                                         </a><a href="#" class="btn btn-primary">
                                                                             Submit
                                                                         </a></div></div></div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="zone-settings" role="tabpanel">
                                <div class="row g-2 align-items-center mb-3">
                                    <div class="col">
                                        <h5 class="modal-title">Add Zone</h5>
                                    </div>
                                    <div class="col-auto ms-auto d-print-none">
                                        <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#modal-report">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M12 5l0 14"></path>
                                                <path d="M5 12l14 0"></path>
                                            </svg> Add New
                                        </a>
                                    </div>
                                </div>
                                <div class="add-zone-table">
                                    <div class="table-responsive">
                                        <table id="zone-table"
                                            class="table card-table table-vcenter text-center text-nowrap datatable zone-table">
                                            <thead>
                                                <th>Zone Name</th>
                                                <th>P/C Begins With</th>
                                                <th>Zone Color</th>
                                                <th>Status</th>
                                                <th width="200px">Action</th>
                                            </thead>
                                            <tbody>
                                                @foreach($zone_data as $key => $zone)

                                                <tr>
                                                    <td> {{ $zone->zone_name }} </td>
                                                    <td>{{ $zone->postal_code }}</td>
                                                    <td>{{ $zone->zone_color }}</td>
                                                    <td>
                                                        @if ($zone->status == 1)
                                                        <span class="badge bg-green">Active</span>
                                                    @else
                                                        <span class="badge bg-red">Deactive</span>
                                                    @endif
                                                    </td>
                                                    <td>
                                                        {{-- <i
                                                            class='fa-solid fa-eye cursor-pointer me-2 text-blue btn-edit'></i> --}}

                                                            <a href="#" class="btn btn-edit_crm btn btn-primary" onclick="edit_zone_modal('{{ $zone->id }}')">
                                                                <i class="fa fa-pencil" aria-hidden="true"></i></i>
                                                            </a>

                                                            <a href="#" class="btn-delete-zone btn btn-danger" data-id="{{ $zone->id }}">
                                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                            </a>

                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="payment-method" role="tabpanel">
                                <div class="row g-2 align-items-center mb-3">
                                    <div class="col">
                                        <h5 class="modal-title">Payment Method</h5>
                                    </div>
                                    <div class="col-auto ms-auto d-print-none">
                                        <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#modal-payment">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M12 5l0 14"></path>
                                                <path d="M5 12l14 0"></path>
                                            </svg> Add New
                                        </a>
                                    </div>
                                </div>
                                <div class="add-zone-table">
                                    <div class="table-responsive">
                                        <table id="zone-table"
                                            class="table card-table table-vcenter text-center text-nowrap datatable zone-table">
                                            <thead>
                                                <th>Payment Mode</th>
                                                <th>Options</th>
                                                <th width="200px">Action</th>
                                            </thead>
                                            <tbody>
                                                @foreach($paymentMethod as $key => $payment)
                                                    <tr>
                                                        <td>{{ $payment->payment_method }}</td>
                                                        <td>{{ $payment->payment_option }}</td>
                                                        <td>

                                                                <a href="#" class="btn btn-edit_crm btn btn-primary" onclick="edit_payment_modal('{{ $payment->id }}')">
                                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                </a>
                                                                <a href="{{route('payment.delete',$payment->id)}}" class="btn btn-danger" >
                                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                                </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tax_tab" role="tabpanel">
                                <div class="card">
                                    <div class="row g-0">
                                        <div class="col-md-12">
                                            <div class="card-body">
                                                <h5 class="modal-title">Add Tax</h5>
                                                <div class="add-form my-3">
                                                    <form id="tax_form" data-table-id="tax-table"  method="POST" action="{{ route('setting.tax-store') }}">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tax Name</label>
                                                                    <input type="text" name="tax_name" class="form-control" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tax (%)</label>
                                                                    <input type="number" name="tax" class="form-control" step="0.01" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="mb-3">
                                                                    <label class="form-label">From Date</label>
                                                                    <input type="date" name="from_date" class="form-control" value="" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="mb-3">
                                                                    <label class="form-label">To Date</label>
                                                                    <input type="date" name="to_date" class="form-control" value="" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row justify-content-end">
                                                            <div class="col-auto">
                                                                <button type="submit" class="btn btn-primary save-btn">Save</button>
                                                            </div>
                                                        </div>

                                                    </form>
                                                </div>
                                            </div>
                                            <div class="add-form-table">
                                                <div class="table-responsive">
                                                    <table id="tax-table"
                                                        class="table card-table table-vcenter text-center text-nowrap datatable data-table" style="width: 100%;">
                                                        <thead>
                                                            <th>Name</th>
                                                            <th>Tax</th>
                                                            <th>From Date</th>
                                                            <th>To Date</th>
                                                            <th>Action</th>
                                                        </thead>
                                                        <tbody>
                                                            {{-- @foreach($tax as $value)
                                                            <tr>
                                                                <td>{{ $value->tax_name }} </td>
                                                                <td>{{ $value->tax }}</td>
                                                                <td>{{ $value->from_date }}</td>
                                                                <td>{{ $value->to_date }}</td>
                                                                <td>                                                                                                                                      
                                                                    <i class='btn btn-danger fa-solid cursor-pointer fa-trash me-2 text-white btn-tax-delete' data-id="{{ $value->id }}"></i>
                                                                </td>
                                                            </tr>
                                                            @endforeach --}}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="source_tab" role="tabpanel">
                                <div class="card">
                                    <div class="row g-0">
                                        <div class="col d-flex flex-column" >
                                            <div  class="tabcontent" >
                                                <div class="card-body">
                                                    <h5 class="modal-title">Add Source</h5>
                                                    <div class="add-form my-3">
                                                        <form id="source-form" method="POST" action="{{route('source.store')}}">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-auto">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Source Name</label>
                                                                        <input type="text" name="source_name"
                                                                            class="form-control" value=""
                                                                            required="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <label class="form-label"
                                                                        style="visibility: hidden;">Source Name</label>
                                                                    <button type="submit"
                                                                        class="btn btn-primary save-btn">Save</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="add-form-tables">
                                                        <div class="table-responsive">
                                                            <table id="source-table"
                                                                class="table card-table table-vcenter text-center text-nowrap datatable data-table">
                                                                <thead>
                                                                    <th>Name</th>
                                                                    <th>Action</th>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($sourceData as $key => $value)
                                                                    <tr>

                                                                        <td> {{ $value->source_name }} </td>
                                                                        <td>
                                                                           <i class='btn btn-primary fa-solid fa-pencil cursor-pointer me-2 text-white btn-source-edit' data-id="{{ $value->id }}"></i>

                                                                            <i class='btn btn-danger fa-solid cursor-pointer fa-trash me-2 text-white btn-source-delete' data-id="{{ $value->id }}"></i>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
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
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="modal modal-blur fade" id="modal-report" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="add-zone my-3">
                        <form id="zone_form" method="post" name="zone_form" action="{{ route('zonesettings.store') }}" >
                            @csrf
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label class="form-label"> Zone Name:</label>
                                        <input type="text" name="zone_name" id="zone_name" class="form-control" value="" required="">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label class="form-label">P/C Begins With:</label>
                                        <input type="text" name="zone_number[]" id="zone_number" class="form-control zoneNumber"
                                        value="" placeholder="380004" required="" style="width: 250px; min-height: 36px; height: 17px;" data-role="tagsinput">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label class="form-label">Color:</label>
                                        <input type="color" name="zone_color" id="zone_color" class="form-control form-control-color"
                                        value="" title="Choose your color">
                                    </div>
                                </div>
                            </div>
                             <div class="col-md-5">
                                    <div class="mb-3">
                                        <label class="form-label">Status:</label>
                                        <select name="zone_status" id="zone_status" class="form-control" required>
                                            <option value="1">Active</option>
                                            <option value="0">Deactive</option>
                                        </select>
                                    </div>
                             </div>

                             <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="saveButton">Save  </button>

                             </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="modal-payment" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="">
                        <form id="payment_method" method="post" name="payment_method" action="{{ route('payment.method.store') }}" >
                            @csrf
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label"> Payment Method:</label>
                                        <select name="payment_method" id="" class="form-control">
                                            <option value="">Select payment mode</option>
                                            <option value="Asia Pay">Asia Pay</option>
                                            <option value="Offline">Offline</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Payment Option:</label>
                                        <input type="text" name="payment_option" id="payment_option" class="form-control" placeholder="Enter payment option">
                                    </div>
                                </div>
                            </div>
                             <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="saveButton">Save  </button>
                             </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal modal-blur fade" id="edit-service" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="edit-service-model">

            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="edit-payment" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="edit-payment-model">

            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="tax_edit_modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tax</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">                   
                    <form id="tax_edit_form" method="post">
                        @csrf

                        <input type="hidden" name="tax_id" id="edit_tax_id">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Tax Name</label>
                                    <input type="text" name="tax_name" id="edit_tax_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Tax (%)</label>
                                    <input type="number" name="tax" id="edit_tax" class="form-control" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">From Date</label>
                                    <input type="date" name="from_date" id="edit_from_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">To Date</label>
                                    <input type="date" name="to_date" id="edit_to_date" class="form-control">
                                </div>
                            </div>
                        </div>
                    
                        <div class="row">
                            <div class="col-md-12" style="text-align: right;">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>              
                </div>
            </div>
        </div>
    </div>

        <script>
            function opensettings(evt, tabno) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(tabno).style.display = "block";
                evt.currentTarget.className += " active";
            }

            // Get the element with id="defaultOpen" and click on it
            document.getElementById("defaultOpen").click();
        </script>

        <script type="text/javascript">
            $("#zone_form").submit(function(e) {
               // e.preventDefault();

                var name = $("input[name='zone_name']").val();
                var number = $("#zone_number").val();
                var color = $("input[name='zone_color']").val();
                var status = $("select[name='zone_status']").val();

                var data = {
                    zone_name: name,
                    zone_number: number,
                    zone_color: color,
                    zone_status: status
                };

                $.ajax({
                    type: "POST",
                    url: '{{ route('zonesettings.store') }}',
                    data: data,
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     },
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            });


            function edit_zone_modal(id) {
                $.ajax({
                    url: "{{ route('zone.fetch', ['id' => ':id']) }}".replace(':id', id),
                    type: "get",
                    success: function (response) {
                       // console.log(response);
                        $('#edit-service').modal('show');
                        $('#edit-service-model').html(response);

                        // $('#zone_number').tagsInput();
                        // var existingTags = [response.postal_code];
                        // $('#zone_number').importTags(existingTags.join(','));
                    },
                    error: function() {
                        console.log('Error occurred while loading the edit modal content.');
                    }
                });
            }

            function edit_payment_modal(id) {
                $.ajax({
                    url: "{{ route('payment.method.edit', ['id' => ':id']) }}".replace(':id', id),
                    type: "get",
                    success: function (response) {
                       // console.log(response);
                        $('#edit-payment').modal('show');
                        $('#edit-payment-model').html(response);
                    },
                    error: function() {
                        console.log('Error occurred while loading the edit modal content.');
                    }
                });
            }

            // function edit_zone_modal(id) {
            //     $.ajax({
            //         url: "{{ route('zone.fetch', ['id' => ':id']) }}".replace(':id', id),
            //         type: "get",
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //             },
            //         success: function (response) {
            //             $('#zone_number').tagsInput();
            //             var existingTags = [response.postal_code];
            //             $('#zone_number').importTags(existingTags.join(','));

            //             $('#zone_number').val(response.postal_code);
            //             $('#zone_name').val(response.zone_name);
            //             $('#zone_color').val(response.zone_color);
            //             $('#zone_status').val(response.status);

            //             $('#modal-report').modal('show');


            //         },
            //         error: function() {
            //             console.log('Error occurred while loading the edit modal content.');
            //         }
            //     });

            // }



        </script>
        <script>
           $(document).ready(function() {

            var table = $('#zone-table').DataTable({
                "pageLength": 10,
            });

            $('.btn-delete-zone').on('click', function(e) {
                e.preventDefault();
                var $button = $(this);
                var zoneId = $(this).data('id');

                $.ajax({
                    type: 'DELETE',
                    url:"{{route('zone.delete', '')}}/"+zoneId,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": zoneId,
                        },
                        success: function(callback) {
                            table.row( $button.parents('tr') ).remove().draw();
                    },
                    error: function(status)
                    {
                        console.log(status);
                    }
                });
            });
        });
        </script>

        <script type="text/javascript">
          $("#salutation-form").submit(function(e) {
                e.preventDefault();
                var name = $("input[name='name']").val();
                //var email = '<span class="badge bg-green">Active</span>';

                // Perform AJAX request
                $.ajax({
                    url: '{{route('setting.store')}}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        name: name
                    },
                    success: function(response) {
                        // Check if the response has a 'message' property
                        if (response.message) {
                            console.log(response.message);
                            // Append row to the table
                            $(".data-table tbody").append("<tr data-name='" + name + "' data-email='" + email + "'><td>" + name +
                                "</td><td>" + email +
                                "</td><td><i class='btn btn-primary fas fa-pencil btn-edit me-2'></i><i class='btn btn-danger fas fa-trash-alt btn-delete'></i></td></tr>"
                            );
                            // Clear the input field
                            $("input[name='name']").val('');
                        }
                    },
                    error: function(error) {
                        // Check if the response has 'errors' property
                        if (error.responseJSON && error.responseJSON.errors && error.responseJSON.errors.name) {
                            // Display iziToast error message
                            iziToast.error({
                                message: error.responseJSON.errors.name[0],
                                position: 'topRight',
                            });
                        }
                        console.error(error);
                    }
                });
            });
            $("#source-form").submit(function(e) {
                e.preventDefault();
                var name = $("input[name='source_name']").val();

                $.ajax({
                    url: '{{ route('source.store') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        name: name
                    },
                    success: function(response) {
                        if (response.message) {
                            $(".data-table tbody").append("<tr data-name='" + name + "'><td>" + name +
                                "</td><td><i class='btn btn-primary fas fa-pencil btn-edit me-2'></i>" +
                                "<i class='btn btn-danger fas fa-trash-alt btn-delete'></i></td></tr>"
                            );
                            $("input[name='source_name']").val('');
                        }
                    },
                    error: function(error) {
                        if (error.responseJSON && error.responseJSON.errors && error.responseJSON.errors.name) {
                            iziToast.error({
                                message: error.responseJSON.errors.name[0],
                                position: 'topRight',
                            });
                        }
                        console.error(error);
                    }
                });
            });


            $(document).ready(function() {

                var tax_table = $('#tax-table').DataTable({
                    "lengthChange": false,
                    "pageLength": 10,
                    ajax: {
                        url: "{{ route('setting.get-tax-table-data') }}",
                        type: 'GET'
                    }
                });

                // tax add

                $("#tax_form").submit(function(e) {
                    e.preventDefault();

                    // Retrieve form data
                    var tax_name = $("input[name='tax_name']").val();
                    var tax = $("input[name='tax']").val();
                    var from_date = $("input[name='from_date']").val();
                    var to_date = $("input[name='to_date']").val();

                    // Perform AJAX request
                    $.ajax({
                        url: "{{ route('setting.tax-store') }}",
                        method: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            tax_name: tax_name,
                            tax: tax,
                            from_date: from_date,
                            to_date: to_date
                        },
                        success: function(response) {
                            if (response.status === 'success') {

                                // var tableId = 'tax-table';
                                // var table = $('#' + tableId + ' tbody');
                                // table.empty();

                                // $.each(response.data, function(index, value) {
                                //     var toDateValue = (value.to_date !== null) ? value.to_date : '';
                                //     var newRow = '<tr>' +
                                //         '<td>' + value.tax_name + '</td>' +
                                //         '<td>' + value.tax + '</td>' +
                                //         '<td>' + value.from_date + '</td>' +
                                //         '<td>' + toDateValue + '</td>' +
                                //         '<td>' +
                                //         '<i class="btn btn-danger fa-solid cursor-pointer fa-trash me-2 text-white btn-tax-delete" data-id="' + value.id + '"></i>' +
                                //         '</td>' +
                                //         '</tr>';

                                //         table.prepend(newRow);
                                // });
                                // window.location.href = window.location.href.split('#')[0] + '#tax_tab';
                                // location.reload();

                                $('#tax_form')[0].reset();

                                tax_table.ajax.reload();
                            } 
                            else {
                                console.error(response.message);
                            }
                        },
                        error: function(error) {
                            console.error(error);
                        }
                    });
                });

                // tax edit

                $('body').on('click', '.btn_tax_edit', function() {

                    var tax_id = $(this).data('id');

                    $.ajax({
                        type: 'get',
                        url: "{{route('setting.tax-edit')}}",
                        data: {tax_id: tax_id},
                        success: function(result) {
                            console.log(result);       
                            
                            $("#edit_tax_id").val(result.tax.id);
                            $("#edit_tax_name").val(result.tax.tax_name);
                            $("#edit_tax").val(result.tax.tax);
                            $("#edit_from_date").val(result.tax.from_date);
                            $("#edit_to_date").val(result.tax.to_date);                          
                            
                            $("#tax_edit_modal").modal('show');
                        },
                        error: function(result){
                            console.log(result);
                        }
                    });
                });

                // tax update

                $('body').on('submit', '#tax_edit_form', function(e){

                    e.preventDefault();

                    $.ajax({
                        type: "post",
                        url: "{{route('setting.tax-update')}}",
                        data: $(this).serialize(),
                        success: function (result) {
                            console.log(result);

                            if(result.status == "error")
                            {
                                $.each(result.errors, function (key, value) { 
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
                                
                                tax_table.ajax.reload();
                                $("#tax_edit_form")[0].reset();
                                $("#tax_edit_modal").modal('hide');
                            }
                            else
                            {
                                iziToast.error({
                                    message: result.message,
                                    position: 'topRight',
                                });   
                            }
                        },
                        error: function (result) {
                            console.log(result);
                        }
                    });
                });

                // tax delete

                $('body').on('click', '.btn-tax-delete', function(e) {
                    e.preventDefault();
                    var $button = $(this);
                    var taxId = $(this).data('id');

                    $.ajax({
                        type: 'DELETE',
                        url:"{{route('setting.tax.delete', '')}}/"+taxId,
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": taxId,
                            },
                        success: function(response) {
                            // table.row($button.parents('tr')).remove().draw();
                            tax_table.ajax.reload();
                        },
                        error: function(response){
                            console.log(response);
                        }
                    });
                });

                // default tax

                $('body').on('click', '.btn_tax_default', function() {

                    var taxId = $(this).data('id');

                    $.ajax({
                        type: 'get',
                        url:"{{route('setting.tax.set-default')}}",
                        data: {
                            "taxId": taxId,
                        },
                        success: function(response) {
                            console.log(response);

                            if(response.status == "success")
                            {                             
                                iziToast.success({
                                    message: response.message,
                                    position: 'topRight',
                                });                                 
                            }
                            else
                            {
                                iziToast.error({
                                    message: response.message,
                                    position: 'topRight',
                                });   
                            }

                            tax_table.ajax.reload();
                        },
                        error: function(response){
                            console.log(response);
                        }
                    });
                });

            });


            $("#constants-settings").on("click", ".btn-delete", function() {
                var row = $(this).closest("tr");
                $(this).parents("tr").remove();
                var id =  $(this).data('id');
                $.ajax({
                url:"{{route('salutation.delete', '')}}/"+id,
                method: 'DELETE',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {

                    console.log(response.message);
                    row.remove();
                },
                error: function(error) {

                    console.error(error);
                }
            });
            });

            $(document).ready(function() {

            var table = $('#source-table').DataTable({
                "pageLength": 10,
            });

            $('.btn-source-delete').on('click', function(e) {
                e.preventDefault();
                var $button = $(this);
                var sourceId = $(this).data('id');

                $.ajax({
                    type: 'DELETE',
                    url:"{{route('source.delete', '')}}/"+sourceId,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": sourceId,
                        },
                        success: function(callback) {
                            table.row( $button.parents('tr') ).remove().draw();
                    },
                    error: function(status)
                    {
                        console.log(status);
                    }
                });
            });
            });

        $("#constants-settings").on("click", ".btn-edit", function() {
            // Find the relevant row and retrieve data for editing
            var row = $(this).parents("tr");
            var id = $(this).data('id');

            // Extract the data you want to edit
            var name = row.find("td:eq(0)").text(); // Assuming the name is in the first <td> element

            // Replace the data with input fields for editing
            row.find("td:eq(0)").html('<input name="name" class="form-control" value="' + name + '">');

            // Add "Update" and "Cancel" buttons
            row.find("td:eq(2)").prepend(
                "<i class='fa-solid cursor-pointer fa-check me-2 text-green btn-update'></i><i class='fa-solid cursor-pointer fa-xmark me-2 text-yellow btn-cancel'></i>"
            );

            // Hide the "Edit" button
            $(this).hide();

            // Add a click event for the "Update" button
            row.on("click", ".btn-update", function() {
                // Retrieve the edited value
                var editedName = row.find("input[name='name']").val();

                // Send an AJAX request to update the data
                $.ajax({
                    url: "{{ route('salutation.update', '') }}/" + id,
                    method: 'post',
                    data: {
                        name: editedName, // Include any other data you want to update
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // Handle the response, e.g., display a success message
                        console.log(response.message);
                        row.find("td:eq(0)").html(editedName);

                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            });

            // Add a click event for the "Cancel" button to revert the changes
            row.on("click", ".btn-cancel", function() {
                // Revert to the original data
                row.find("td:eq(0)").html(name);

            // Remove "Update" and "Cancel" buttons
            row.find(".btn-update, .btn-cancel").remove();

            // Show the "Edit" button
            row.find(".btn-edit").show();
            });
        });



            $("#constants-settings").on("click", ".btn-cancel", function() {
                var name = $(this).parents("tr").attr('data-name');
                $(this).parents("tr").find("td:eq(0)").text(name);
                $(this).parents("tr").find("td:eq(1)").text(email);

                $(this).parents("tr").find(".btn-edit").show();
                $(this).parents("tr").find(".btn-update").remove();
                $(this).parents("tr").find(".btn-cancel").remove();
            });

            $("#constants-settings").on("click", ".btn-update", function() {
                var name = $(this).parents("tr").find("input[name='edit_name']").val();

                $(this).parents("tr").find("td:eq(0)").text(name);

                $(this).parents("tr").find(".btn-edit").show();
                $(this).parents("tr").find(".btn-cancel").remove();
                $(this).parents("tr").find(".btn-update").remove();
            });

            $("#source_tab").on("click", ".btn-source-edit", function() {

                var row = $(this).parents("tr");
                var id = $(this).data('id');

                var name = row.find("td:eq(0)").text();


                row.find("td:eq(0)").html('<input name="source_name" class="form-control" value="' + name + '">');


                row.find("td:eq(1)").prepend(
                    "<i class='fa-solid cursor-pointer fa-check me-2 text-green btn-update'></i><i class='fa-solid cursor-pointer fa-xmark me-2 text-yellow btn-cancel'></i>"
                );


                $(this).hide();


                row.on("click", ".btn-update", function() {
                    var editedName = row.find("input[name='source_name']").val();

                    $.ajax({
                        url: "{{ route('source.update', '') }}/" + id,
                        method: 'post',
                        data: {
                            source_name: editedName, // Include any other data you want to update
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            // Handle the response, e.g., display a success message
                        // console.log(response.message);
                            row.find("td:eq(0)").html(editedName);

                        },
                        error: function(error) {
                            console.error(error);
                        }
                    });
                });

                // Add a click event for the "Cancel" button to revert the changes
                row.on("click", ".btn-cancel", function() {
                    // Revert to the original data
                    row.find("td:eq(0)").html(name);

                // Remove "Update" and "Cancel" buttons
                row.find(".btn-update, .btn-cancel").remove();

                // Show the "Edit" button
                row.find(".btn-source-edit").show();
                });
                });


                $("#source_tab").on("click", ".btn-cancel", function() {
                var name = $(this).parents("tr").attr('data-name');
                $(this).parents("tr").find("td:eq(0)").text(name);
                $(this).parents("tr").find("td:eq(1)").text(email);

                $(this).parents("tr").find(".btn-source-edit").show();
                $(this).parents("tr").find(".btn-update").remove();
                $(this).parents("tr").find(".btn-cancel").remove();
            });

             $("#source_tab").on("click", ".btn-update", function() {
                var name = $(this).parents("tr").find("input[name='edit_name']").val();

                $(this).parents("tr").find("td:eq(0)").text(name);

                $(this).parents("tr").find(".btn-source-edit").show();
                $(this).parents("tr").find(".btn-cancel").remove();
                $(this).parents("tr").find(".btn-update").remove();
            });
        </script>


            {{-- <script type="text/javascript">
                $("#zone-form").submit(function(e) {
                    e.preventDefault();
                    var name = $("input[name='zone_name']").val();
                    var number = $("input[name='zone_number']").val();
                    var color = $("input[name='zone_color']").val();
                    var status = '<span class="badge bg-red">Deactive</span>';
                    $(".zone-table tbody").append("<tr data-name='" + name + "' data-number='" + number + "' data-color='" +
                        color + "'><td>" + name + "</td><td>" + number + "</td><td>" + color + "</td><td>" + status +
                        "</td><td><i class='fa-solid fa-pencil cursor-pointer me-2 text-yellow btn-edit'></i><i class='fa-solid cursor-pointer fa-trash me-2 text-red btn-delete'></i></td></tr>"
                    );
                    $("input[name='zone-name']").val('');
                    $("input[name='zone-number']").val('');
                    $("input[name='zone-color']").val('');

                });

                $("#zone-settings").on("click", ".btn-delete", function() {
                    $(this).parents("tr").remove();
                });

                $("#zone-settings").on("click", ".btn-edit", function() {
                    var name = $(this).parents("tr").attr('data-name');
                    var number = $(this).parents("tr").attr('data-number');
                    var color = $(this).parents("tr").attr('data-color');

                    $(this).parents("tr").find("td:eq(0)").html('<input type="text" name="edit_name" value="' + name +
                        '">');
                    $(this).parents("tr").find("td:eq(1)").html('<input type="number" name="edit_number" value="' + number +
                        '">');
                    $(this).parents("tr").find("td:eq(2)").html('<input type="color" name="edit_color" value="' + color +
                        '">');

                    $(this).parents("tr").find("td:eq(4)").prepend(
                        "<i class='fa-solid cursor-pointer fa-check me-2 text-green btn-update'></i><i class='fa-solid cursor-pointer fa-xmark me-2 text-yellow btn-cancel'></i>"
                    )
                    $(this).hide();
                });

                $("#zone-settings").on("click", ".btn-cancel", function() {
                    var name = $(this).parents("tr").attr('data-name');
                    var number = $(this).parents("tr").attr('data-number');
                    var color = $(this).parents("tr").attr('data-color');

                    $(this).parents("tr").find("td:eq(0)").text(name);
                    $(this).parents("tr").find("td:eq(1)").text(number);
                    $(this).parents("tr").find("td:eq(2)").text(color);

                    $(this).parents("tr").find(".btn-edit").show();
                    $(this).parents("tr").find(".btn-update").remove();
                    $(this).parents("tr").find(".btn-cancel").remove();
                });

                $("#zone-settings").on("click", ".btn-update", function() {
                    var name = $(this).parents("tr").find("input[name='edit_name']").val();
                    var number = $(this).parents("tr").find("input[name='edit_number']").val();
                    var color = $(this).parents("tr").find("input[name='edit_color']").val();

                    $(this).parents("tr").find("td:eq(0)").text(name);
                    $(this).parents("tr").find("td:eq(1)").text(number);
                    $(this).parents("tr").find("td:eq(2)").text(color);

                    $(this).parents("tr").attr('data-name', name);
                    $(this).parents("tr").attr('data-number', number);
                    $(this).parents("tr").attr('data-color', color);

                    $(this).parents("tr").find(".btn-edit").show();
                    $(this).parents("tr").find(".btn-cancel").remove();
                    $(this).parents("tr").find(".btn-update").remove();
                });
            </script> --}}
        <script>
           // $('#salutation-table').DataTable();
           // $('#zone-table').DataTable();
        </script>

        <script >
            $(document).ready(function() {
                $('#zone_number').tagsInput({
                   'defaultText': 'Add a P/C',
                    'maxChars': 5,
                    'onChange': function() {
                        // Handle changes here
                    }
                });
            });
        </script>


    @endsection
