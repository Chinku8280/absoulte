@extends('theme.default')

@section('custom_css')

    <style>
        #commercial-customer-table {
            width: -webkit-fill-available !important;
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
                            CRM
                        </h2>
                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            <a href="{{ asset('application/public/download/crm_bulk_sample_file.xlsx') }}" class="btn btn-primary m-0" download>
                                Download Sample File
                            </a>
                        
                            <button class="btn btn-primary m-0" data-bs-toggle="modal" data-bs-target="#upload_crm_modal">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                    </path>
                                    <path d="M12 5l0 14"></path>
                                    <path d="M5 12l14 0"></path>
                                </svg>
                                Upload Bulk Import
                            </button>

                            <a href="{{ route('crm.create') }}" class="btn btn-primary d-none d-sm-inline-block"
                                data-bs-toggle="modal" data-bs-target="#add-crm-modal" onclick="showFormModal()">
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
                </div>
                <div class="row g-2 align-items-center">
                    <div class="col">
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-pills nav-pills-primary" data-bs-toggle="tabs" role="tablist"
                                    id="customerTabs">
                                    <li class="nav-item me-2" role="presentation">
                                        <a href="#residential" class="nav-link active" data-bs-toggle="tab"
                                            aria-selected="true" role="tab">Residential</a>
                                    </li>
                                    <li class="nav-item me-2" role="presentation">
                                        <a href="#commercial" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                                            role="tab" tabindex="-1">Commercial</a>
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
                                                        value="8" size="3" aria-label="Invoices count">
                                                </div>
                                                entries
                                            </div>
                                            <div class="ms-auto text-muted">
                                                Search:
                                                <div class="ms-2 d-inline-block">
                                                    <input type="text" class="form-control form-control-sm"
                                                        aria-label="Search invoice" name="search_customer" id="search_customer" onkeypress="searchResidentialCustomer()" onkeydown="searchResidentialCustomer()">
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="card-body">
                                        <div class="table-responsive mt-4">
                                            <table id="residential-customer-table"
                                                class="table card-table table-vcenter text-left text-nowrap datatable">
                                                <thead>
                                                    <tr>
                                                        <th class="w-1">No.</th>
                                                        <th>Status</th>
                                                        <th>Customer ID</th>
                                                        <th>Customer name</th>
                                                        <th>Contact Number</th>
                                                        <th>Contact Address</th>
                                                        <th>Postal Code</th>
                                                        <th>Outstanding Amount</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="residential-customer-tbody">

                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                    <!-- <div class="card-footer d-flex align-items-center">
                                        <p class="m-0 text-muted">Showing <span>1</span> to <span>1</span> of
                                            <span>1</span> entries
                                        </p>
                                        <ul class="pagination m-0 ms-auto">
                                        </ul>
                                        </div> -->
                                </div>
                                <div class="tab-pane" id="commercial" role="tabpanel">
                                    {{-- <div class="card-body border-bottom py-3">
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
                                                        aria-label="Search invoice" id="commercial_search" onkeypress="searchCommercialCustomer()" onkeydown="searchCommercialCustomer()">
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="card-body">
                                        <div class="table-responsive mt-4">
                                            <table id="commercial-customer-table"
                                                class="table card-table table-vcenter text-left text-nowrap datatable">
                                                <thead>
                                                    <tr>
                                                        <th class="w-1">No</th>
                                                        <th>Status</th>
                                                        <th>Customer ID</th>
                                                        <th>Company name</th>
                                                        <th>Contact Number</th>
                                                        <th>Contact Address</th>
                                                        <th>Postal Code</th>
                                                        <th>Outstanding Amount</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>

                                            </table>
                                        </div>
                                    </div>
                                    <!-- <div class="card-footer d-flex align-items-center">
                                        <p class="m-0 text-muted">Showing <span>1</span> to <span>1</span> of
                                            <span>1</span> entries
                                        </p>
                                        <ul class="pagination m-0 ms-auto">
                                        </ul>
                                        </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- add --}}
    <div class="modal modal-blur fade" id="add-crm-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 800px;">
            <div class="modal-content" id="add-crm-model-content">
            </div>
        </div>
    </div>

    {{-- edit --}}
    <div class="modal modal-blur fade" id="edit-crm-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 800px;">
            <div class="modal-content" id="edit-crm-model-content">
            </div>
        </div>
    </div>

    {{-- view --}}
    <div class="modal modal-blur fade" id="view-crm-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 800px;">
            <div class="modal-content" id="view-crm-model-content">
            </div>
        </div>
    </div>

    <!-- Modal for uploading crm -->
    <div class="modal fade" id="upload_crm_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload CRM File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="upload_crm_form" action="#" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- Add any content or instructions for the user -->
                        <p>Choose a file to upload:</p>
                        <input type="file" name="crm_file" id="crm_file" accept=".xls, .xlsx">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script>
        function showFormModal() {
            $.ajax({
                url: "{{ route('crm.create') }}",
                type: "GET",
                success: function(response) {
                    // console.log(response);
                    $('#add-crm-modal').modal('show');
                    $('#add-crm-model-content').html(response);
                },
                error: function() {
                    console.log('Error occurred while loading the modal content.');
                }
            });
        }

        function edit_crm_modal(id) {
            $.ajax({
                url: "{{ route('crm.edit', ['id' => ':id']) }}".replace(':id', id),
                type: "get",
                success: function(response) {
                    // console.log(response);
                    $('#edit-crm-modal').modal('show');
                    $('#edit-crm-model-content').html(response);
                },
                error: function(response) {
                    console.log(response);
                    console.log('Error occurred while loading the edit modal content.');
                }
            });
        }

        function view_crm_modal(id) {
            $.ajax({
                url: "{{ route('crm.view', ['id' => ':id']) }}".replace(':id', id),
                type: "get",
                success: function(response) {
                    $('#edit-crm-modal').modal('show');
                    $('#edit-crm-model-content').html(response);
                },
                error: function() {
                    console.log('Error occurred while loading the edit modal content.');
                }
            });
        }

        function searchResidentialCustomer(){
            var searchValue = $('#search_customer').val();
            // console.log(text);
            // if(searchValue != ''){
                $.ajax({
                    url:'{{route('search.residential.customer')}}',
                    type:'post',
                    data:{
                        _token: '{{ csrf_token() }}',
                        search_value:searchValue
                    },
                    success: function(response){
                        // console.log(response);
                        $('#residential-customer-table tbody').empty();
                        $('#residential-customer-table tbody').html(response);
                    }
                })
            // }

        }
        function searchCommercialCustomer(){
            var searchValue = $('#commercial_search').val();
            // console.log(text);
            // if(searchValue != ''){
                $.ajax({
                    url:'{{route('search.commercial.customer')}}',
                    type:'post',
                    data:{
                        "_token": "{{ csrf_token() }}",
                        'search_value':searchValue
                    },
                    success: function(response){
                        // console.log(response);
                        $('#commercial-customer-table tbody').empty();
                        $('#commercial-customer-table tbody').html(response);
                    }
                })
            // }

        }

        $(document).ready(function() {
            $('body').on('click', '.btn_delete_crm_commercial', function() {
                var customerId = $(this).data('customer-id');

                if (confirm('Are you sure you want to delete this commercial customer?')) {
                    $.ajax({
                        url: '{{route("delete-commercial")}}',
                        type: 'POST',
                        data: {
                            id: customerId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            alert('Customer deleted successfully');
                            $('#commercial-customer-table').DataTable().ajax.reload();
                        },
                        // error: function(xhr) {
                        //     alert('Error: ' + xhr.responseText);
                        // }
                    });
                }
            });

            // var commercial_crm_table = $('#commercial-customer-table').DataTable({
            //     //processing: true,
            //     // serverSide: true,
            //     // searching: false,
            //     paging: true,
            //     order: [[1, 'asc']],
            //     "lengthChange": false,
            //     "pageLength": 30,
            //     ajax: '{{ route('customers.commercial') }}',
            //     columns: [{
            //             data: 'sno',
            //             name: 'No'
            //         },
            //         {
            //             data: 'status',
            //             name: 'Status'
            //         },
            //         {
            //             data: 'id',
            //             name: 'id'
            //         },
            //         {
            //             data: 'individual_company_name',
            //             name: 'Company name'
            //         },
            //         {
            //             data: 'mobile_number',
            //             name: 'Contact Number'
            //         },
            //         {
            //             data: 'email',
            //             name: 'Contact Address'
            //         },
            //         {
            //             data: 'postal_code',
            //             name: 'Postal Code'
            //         },
            //         {
            //             data: 'outstanding_Amount',
            //             name: 'Outstanding Amount'
            //         },
            //         {
            //             data: 'action',
            //             title: 'Action'
            //         }

            //     ]
            // });

            var commercial_crm_table = $('#commercial-customer-table').DataTable({
                //processing: true,
                // serverSide: true,
                // searching: false,
                paging: true,
                order: [[1, 'asc']],
                "lengthChange": false,
                "pageLength": 30,
                ajax: {
                    url: "{{ route('customers.commercial') }}",
                    type: 'GET',
                    data: function(data) {
                        data.status = "{{$status ?? ''}}"
                    }
                },
                'columnDefs': [{
                    'targets': [0],        // Targets the first column (index 0)
                    'orderable': false,    // Disables sorting on this column
                }]
            }).on('order.dt search.dt', function() {
                var table = $('#commercial-customer-table').DataTable();
                table.column(0, {order:'applied'}).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;  // Update the serial number
                });
            }).draw();

            $('body').on('click', '.btn_delete_crm', function() {
                var customerId = $(this).data('customer-id');

                if (confirm('Are you sure you want to delete this customer?')) {
                    $.ajax({
                        url: '{{route("delete-residential")}}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: customerId
                        },
                        success: function(response) {
                            alert('Customer deleted successfully');
                            $('#residential-customer-table').DataTable().ajax.reload();
                        },
                        // error: function(xhr) {
                        //     alert('Error: ' + xhr.responseText);
                        // }
                    });
                }
            });

            // DataTable initialization
            // var residential_crm_table = $('#residential-customer-table').DataTable({
            //     processing: true,
            //     // serverSide: true,
            //     // searching: false,
            //     paging: true,
            //     order: [[1, 'asc']],
            //     "lengthChange": false,
            //     "pageLength": 30,
            //     ajax: '{{ route('customers.residential') }}',
            //     columns: [{
            //             data: 'sno',
            //             name: 'No'
            //         },
            //         {
            //             data: 'status',
            //             name: 'Status'
            //         },
            //         {
            //             data: 'id',
            //             name: 'id'
            //         },
            //         {
            //             data: 'customer_name',
            //             name: 'Customer name'
            //         },
            //         {
            //             data: 'mobile_number',
            //             name: 'Contact Number'
            //         },
            //         {
            //             data: 'email',
            //             name: 'Contact Address'
            //         },
            //         {
            //             data: 'postal_code',
            //             name: 'Postal Code'
            //         },
            //         {
            //             data: 'outstanding_Amount',
            //             name: 'Outstanding Amount'
            //         },
            //         {
            //             data: 'action',
            //             title: 'Action'
            //         }
            //     ]
            // });            

            var residential_crm_table = $('#residential-customer-table').DataTable({
                processing: true,
                // serverSide: true,
                // searching: false,
                paging: true,
                order: [[1, 'asc']],
                "lengthChange": false,
                "pageLength": 30,
                ajax: {
                    url: "{{ route('customers.residential') }}",
                    type: 'GET',
                    data: function(data) {
                        data.status = "{{$status ?? ''}}"
                    }
                },
                'columnDefs': [{
                    'targets': [0],        // Targets the first column (index 0)
                    'orderable': false,    // Disables sorting on this column
                }]
            }).on('order.dt search.dt', function() {
                var table = $('#residential-customer-table').DataTable();
                table.column(0, {order:'applied'}).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;  // Update the serial number
                });
            }).draw();      

            // crm bulk upload

            $('body').on('submit', '#upload_crm_form', function(e) {

                e.preventDefault();

                var formData = new FormData($(this)[0]);

                $.ajax({
                    url: "{{ route('crm.bulk-upload') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(result) {

                        console.log(result);

                        if (result.status == "errors") {
                            var errorMsg = '';
                            $.each(result.errors, function(field, errors) {
                                $.each(errors, function(index, error) {
                                    errorMsg += error + '<br>';
                                });
                            });
                            iziToast.error({
                                message: errorMsg,
                                position: 'topRight'
                            });

                        } else {
                            iziToast.success({
                                message: result.message,
                                position: 'topRight'
                            });

                            $('#upload_crm_form')[0].reset();
                            $('#upload_crm_modal').modal('hide');
                            residential_crm_table.ajax.reload();
                            commercial_crm_table.ajax.reload();
                        }

                    },
                    error: function(response){
                        console.log(response);
                    }
                });

            });

        });

    </script>
@endsection

