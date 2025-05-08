@extends('theme.default')
@section('custom_css')
    <style>
        #language_table th {
            /* text-align: center; */
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
                            Language Spoken
                        </h2>
                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">

                            <a href="{{ route('languageSpoken.create') }}" class="btn btn-primary d-none d-sm-inline-block"
                                data-bs-toggle="modal" data-bs-target="#add-language" onclick="showFormModal()">
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

        <div class="page-body">
            <div class="container-xl">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">                    
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
                                                aria-label="Search invoice">
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="language_table"
                                        class="table card-table table-vcenter text-left text-nowrap datatable">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Action</th>
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
        <footer class="footer footer-transparent d-print-none">

        </footer>
    </div>



    <div class="modal modal-blur fade" id="add-language" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 800px;">
            <div class="modal-content" id="model-show-data">

            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script>
        var jq = jQuery.noConflict();
        jq(document).ready(function() {
            jq('#language_table').DataTable({
                serverSide: true,
                ajax: {
                    url: '{{ route('language.showData') }}',
                    type: 'GET',
                    dataType: 'json',
                    dataSrc: 'data', // Specify the data source property
                },
                columns: [{
                        data: 'language_name',
                        name: 'language_name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });
        });


        jq('#language_table').on('click', '.delete-btn-language', function(event) {
            event.preventDefault();

            var deleteForm = $(this).closest('.delete-form-language');
            var url = deleteForm.attr('action');
            var method = deleteForm.attr('data-method');

            $.ajax({
                url: url,
                type: 'DELETE',
                data: deleteForm.serialize(),
                success: function(response) {

                    if(response.status == "success")
                    {
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });
                        
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
                error: function(xhr, status, error) {
                    iziToast.error({
                        message: 'An error occurred: ' + error,
                        position: 'topRight'
                    });
                }
            });
        });




        function showFormModal() {
            $.ajax({
                url: "{{ route('languageSpoken.create') }}",
                type: "GET",
                success: function(response) {
                    // Set the HTML content of the modal
                    $('#add-language .modal-content').html(response);

                    // Open the modal
                    var myModal = new bootstrap.Modal(document.getElementById('add-language'));
                    myModal.show();
                },
                error: function() {
                    console.log('Error occurred while loading the modal content.');
                }
            });
        }


        function edit_language_modal(id) {
            jQuery.ajax({
                url: "{{ route('languageSpoken.edit', ['id' => ':id']) }}".replace(':id', id),
                type: "get",

                success: function(response) {

                    $('#add-language .modal-content').html(response);

                    var myModal = new bootstrap.Modal(document.getElementById('add-language'));
                    myModal.show();
                }
            });
        }
    </script>
@endsection
