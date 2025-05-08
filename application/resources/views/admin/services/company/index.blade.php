@extends('theme.default')
@section('content')
    <style>
        #service-table {
            width: -webkit-fill-available !important;
        }

        .custom-modal-dialog {
            max-width: 50%;
            /* You can adjust the percentage based on your preference */
            margin: auto;
        }

        #service-table th {
            /* text-align: center !important; */
        }

        #company-table th {
            /* text-align: center !important; */
        }
        #service-table .text-left
        {
            text-align: left !important;
        }
    </style>

    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            Services
                        </h2>
                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
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
                                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a href="#category" class="nav-link active" data-bs-toggle="tab"
                                            aria-selected="true"
                                            role="tab"><!-- Download SVG icon from http://tabler-icons.io/i/home -->

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon me-2 icon-tabler icon-tabler-apps" width="44" height="44"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <rect x="4" y="4" width="6" height="6" rx="1" />
                                                <rect x="4" y="14" width="6" height="6" rx="1" />
                                                <rect x="14" y="14" width="6" height="6" rx="1" />
                                                <line x1="14" y1="7" x2="20" y2="7" />
                                                <line x1="17" y1="4" x2="17" y2="10" />
                                            </svg>
                                            Company</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#service" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                                            tabindex="-1"
                                            role="tab"><!-- Download SVG icon from http://tabler-icons.io/i/user -->

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon me-2 icon-tabler icon-tabler-tool" width="44" height="44"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path
                                                    d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5">
                                                </path>
                                            </svg>
                                            Service</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active show" id="category" role="tabpanel">
                                        <div class="row g-2 align-items-center w-100">
                                            <div class="col-auto ms-auto d-print-none">

                                                <a href="{{ route('company.create') }}" class="btn btn-primary m-0"
                                                    data-bs-toggle="modal" data-bs-target="#add-company"
                                                    onclick="showFormModal()">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M12 5l0 14"></path>
                                                        <path d="M5 12l14 0"></path>
                                                    </svg>
                                                    Add New Company
                                                </a>

                                            </div>
                                        </div>
                                        <br>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="company-table"
                                                        class="datatable table card-table table-vcenter text-left text-nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th class="w-1">No.</th>
                                                                <th>Company Id</th>
                                                                <th>Company Name</th>
                                                                <th>Description</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="service" role="tabpanel">
                                        <div class="row w-100">
                                            <div class="col-auto d-flex d-print-none">
                                                <a href="{{ route('service.create') }}" class="btn btn-primary m-0 ms-2"
                                                    data-bs-toggle="modal" data-bs-target="#add-service"
                                                    onclick="showServiceModal()">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M12 5l0 14"></path>
                                                        <path d="M5 12l14 0"></path>
                                                    </svg>
                                                    Add New Service
                                                </a>
                                            </div>
                                            <div class="col-auto ms-auto d-flex d-print-none">
                                                <a href="{{ route('download.sample.file') }}" class="btn btn-primary m-0">
                                                    Download Sample File
                                                </a>
                                                &nbsp;
                                                <!-- File Upload Form -->
                                                {{-- <form id="uploadForm" action="{{ route('upload.bulk.service') }}"
                                                    method="post" enctype="multipart/form-data">
                                                    @csrf --}}
                                                <button class="btn btn-primary m-0" data-bs-toggle="modal"
                                                    data-bs-target="#uploadServiceModal">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                        </path>
                                                        <path d="M12 5l0 14"></path>
                                                        <path d="M5 12l14 0"></path>
                                                    </svg>
                                                    Upload Bulk Service
                                                </button>
                                                {{-- </form> --}}
                                            </div>
                                        </div>

                                        <br>

                                        <div class="card">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="service-table"
                                                        class="datatable table card-table table-vcenter text-left text-nowrap ">
                                                        <thead>
                                                            <tr>
                                                                <th class="w-1">No.</th>
                                                                <th>Company</th>
                                                                <th>Service Name</th>
                                                                <th>Description</th>
                                                                <th>Price</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
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
    <!-- MODEL -->
    <div class="modal modal-blur fade" id="add-company" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg custom-modal-dialog" role="document">
            <div class="modal-content" id="add-company-model">

            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="edit-company" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg custom-modal-dialog" role="document">
            <div class="modal-content" id="edit-company-model">

            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="add-service" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content" id="add-service-model">

            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="edit-service" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content" id="edit-service-model">

            </div>
        </div>
    </div>
    <!-- Modal for uploading service -->
    <div class="modal fade" id="uploadServiceModal" tabindex="-1" role="dialog"
        aria-labelledby="uploadServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadServiceModalLabel">Upload Service File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="uploadForm" action="{{ route('upload.bulk.service') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- Add any content or instructions for the user -->
                        <p>Choose a file to upload:</p>
                        <input type="file" name="serviceFile" id="serviceFile" accept=".xls, .xlsx">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="submitUploadForm(event)">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@section('javascript')
    <script>
        function submitUploadForm(e) {

            e.preventDefault();

            var formData = new FormData($('#uploadForm')[0]);
            formData.append('serviceFile', $('#serviceFile')[0].files[0]);

            $.ajax({
                url: $('#uploadForm').attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {

                    console.log(response);

                    if (response.errors) {
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

                    } else {
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        $('#uploadForm')[0].reset();
                        $('#add-service').modal('hide');
                        $('#service-table').DataTable().ajax.reload();
                    }

                },
                error: function(response) {
                    console.log(response);
                }
            });

            $('#uploadServiceModal').modal('hide');
            // window.location.reload();
        }


        $(document).ready(function() {
            var companyTable = $('#company-table').DataTable({
                serverSide: true,
                ajax: {
                    url: '{{ route('company.data') }}',
                    data: function(d) {
                        d.draw = d.draw || 1;
                        d.start = d.start || 0;
                        d.length = d.length || 10;
                        d.search = d.search || {};
                        d.search.value = d.search.value || '';

                        return d;
                    }
                },
                columns: [{
                        data: 'sno',
                        name: 'No'
                    },
                    {
                        data: 'company_id',
                        name: 'Company Id'
                    },
                    {
                        data: 'company_name',
                        name: 'Company Name'
                    },
                    {
                        data: 'description',
                        name: 'Description'
                    },
                    {
                        data: 'action',
                        title: 'Action'
                    }
                ]
            });
        });

        $(document).on('click', '.btn_delete_crm', function() {
            var customerId = $(this).data('company-id');
            if (confirm('Are you sure you want to delete this Company?')) {
                $.ajax({
                    url: "{{ route('delete-company', ['id' => ':id']) }}".replace(':id',
                        customerId),
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {

                        if (response.errors) {
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

                        } else {
                            iziToast.success({
                                message: response.success,
                                position: 'topRight'

                            });
                        }
                        $('#company-table').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseText);
                    }
                });
            }
        });

        function showFormModal() {
            $.ajax({
                url: "{{ route('company.create') }}",
                type: "GET",
                success: function(response) {

                    $('#add-company').modal('show');
                    $('#add-company-model').html(response);


                },
                error: function() {
                    console.log('Error occurred while loading the modal content.');

                }
            });
        }

        function edit_company_modal(id) {
            $.ajax({
                url: "{{ route('company.edit', ['id' => ':id']) }}".replace(':id', id),
                type: "get",
                success: function(response) {
                    $('#edit-company').modal('show');
                    $('#edit-company-model').html(response);
                },
                error: function() {
                    console.log('Error occurred while loading the edit modal content.');
                }
            });
        }
    </script>
    <script>
        function showServiceModal() {
            $.ajax({
                url: "{{ route('service.create') }}",
                type: "GET",
                success: function(response) {

                    $('#add-service').modal('show');
                    $('#add-service-model').html(response);


                },
                error: function() {
                    console.log('Error occurred while loading the modal content.');

                }
            });
        }

        function edit_service_modal(id) {
            $.ajax({
                url: "{{ route('service.edit', ['id' => ':id']) }}".replace(':id', id),
                type: "get",
                success: function(response) {
                    $('#edit-service').modal('show');
                    $('#edit-service-model').html(response);
                },
                error: function() {
                    console.log('Error occurred while loading the edit modal content.');
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            var serviceTable = $('#service-table').DataTable({
                serverSide: true,
                ajax: '{{ route('service.data') }}',
                columns: [{
                        data: 'sno',
                        name: 'No'
                    },
                    {
                        data: 'company',
                        name: 'Company Name'
                    },
                    {
                        data: 'service_name',
                        name: 'Service Name',
                        className:"text-left"
                    },
                    {
                        data: 'service_description',
                        name: 'Description'
                    },
                    {
                        data: 'price',
                        name: 'Price'
                    },
                    {
                        data: 'action',
                        title: 'Action'
                    }

                ]
            });
            $(document).on('click', '.btn_delete_service', function() {
                var serviceId = $(this).data('service-id');

                if (confirm('Are you sure you want to delete this Service?')) {
                    $.ajax({
                        url: "{{ route('delete-service', ['id' => ':id']) }}".replace(':id',
                            serviceId),
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.errors) {
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

                            } else {
                                iziToast.success({
                                    message: response.success,
                                    position: 'topRight'

                                });

                            }
                            $('#service-table').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            alert('Error: ' + xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
@endsection
@endsection
