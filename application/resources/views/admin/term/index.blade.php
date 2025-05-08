@extends('theme.default')

@section('custom_css')
    <style>
        #term-table th {
            /* text-align: center; */
        }

        #term-table tbody tr>td:last-child
        {
            white-space: nowrap;
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
                            Terms & Conditions
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
                        <div class="card">
                            {{-- <div class="card-header">
                              </div> --}}
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active show" id="" role="tabpanel">
                                        <div class="row g-2 align-items-center w-100">
                                            <div class="col-auto ms-auto d-print-none mb-3">

                                                <a href="#" class="btn btn-primary m-0" data-bs-toggle="modal"
                                                    data-bs-target="#add-term" onclick="showaddFormModal()">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M12 5l0 14"></path>
                                                        <path d="M5 12l14 0"></path>
                                                    </svg>
                                                    Add New Terms & Condition
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    {{-- <table id="term-table" class="table card-table table-vcenter text-nowrap text-center datatable"> --}}
                                                    <table id="term-table" class="table card-table datatable text-left" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="w-1">No.</th>
                                                                <th>Terms & Condition</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            {{-- @foreach ($terms as $key => $term)
                                                                <tr>
                                                                    <td class="w-1">{{ $key + 1 }}</td>
                                                                    <td style="text-align: left;">{{ $term->term_condition }}
                                                                    </td>
                                                                    <td>
                                                                        <a href="#" class="btn btn-primary"
                                                                            onclick="showFormModal({{ $term->id }})"><i
                                                                                class="fa fa-pencil" aria-hidden="true"></i></a>
                                                                        <a href="{{ route('term.condition.delete', $term->id) }}"
                                                                            onclick="alert('Are You Sure')"
                                                                            class="btn btn-danger"><i class="fa fa-trash"
                                                                                aria-hidden="true"></i></a>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- add MODEL -->
    <div class="modal fade" id="add-term" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Terms & Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="term_conditions_form">
                        @csrf
                        <div class="mb-3">
                            <label for="company_id" class="form-label">Company Name</label>
                            <select name="company_id" id="" class="form-control">
                                <option value="">Select Company Name</option>
                                @foreach ($company as $item)
                                    <option value="{{ $item->id }}">{{ $item->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="term_condition" class="form-label">Term & Condition</label>
                            {{-- <input type="text" name="term_condition" class="form-control"> --}}

                            <textarea name="term_condition" class="form-control" cols="30" rows="10"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- edit modal --}}
    <div class="modal fade" id="edit-term" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="edit-term-content">

            </div>
        </div>
    </div>

    {{-- delete modal --}}
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
                    <div class="text-muted">Do you really want to remove this Terms and Condition?</div>
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
                                <a href="#" class="btn btn-danger w-100" id="confirm_delete_btn">
                                    Delete
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')

    {{-- @if ($errors->has('term_condition'))
        <script>
            // Wait for the document to be ready
            document.addEventListener("DOMContentLoaded", function() {
                // Get the error message from the server-side validation
                var errorMessage = "{!! $errors->first('term_condition') !!}";

                // Display the error message using iziToast
                if (errorMessage) {
                    iziToast.error({
                        title: 'Error :',
                        message: errorMessage,
                        position: 'topRight', // Display at the top-right corner
                        timeout: 5000 // Disappear after 5 seconds
                    });
                }
            });
        </script>
    @endif --}}

    <script>
        $(document).ready(function() {
            // $('#term-table').DataTable({
            //     "paging": true,
            //     "searching": true,
            //     "ordering": true,
            // });

            var term_table = $('#term-table').DataTable({
                "lengthChange": false,
                "pageLength": 10,
                ajax: {
                    url: "{{ route('term-condition.get-table-data') }}",
                    type: 'GET'
                },
                'columnDefs': [{
                    'targets': [0],        // Targets the first column (index 0)
                    'orderable': false,    // Disables sorting on this column
                }]
            }).on('order.dt search.dt', function() {
                var table = $('#term-table').DataTable();
                table.column(0, {order:'applied'}).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;  // Update the serial number
                });
            }).draw();

            // store

            $('body').on('submit', '#term_conditions_form', function (e) {

                e.preventDefault();

                $.ajax({
                    type: "post",
                    url: "{{ route('term.condition.store') }}",
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
                        else
                        {
                            iziToast.success({
                                message: result.message,
                                position: 'topRight'
                            });

                            term_table.ajax.reload();
                            $("#add-term").modal('hide');
                            $("#term_conditions_form")[0].reset();
                        }
                    },
                    error: function (result) {
                        console.log(rseult);
                    }
                });

            });

            // update

            $('body').on('submit', '#edit_term_conditions_form', function (e) {

                e.preventDefault();

                $.ajax({
                    type: "post",
                    url: "{{ route('term.condition.update') }}",
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
                        else
                        {
                            iziToast.success({
                                message: result.message,
                                position: 'topRight'
                            });

                            term_table.ajax.reload();
                            $("#edit-term").modal('hide');
                            $("#edit_term_conditions_form")[0].reset();
                        }
                    },
                    error: function (result) {
                        console.log(rseult);
                    }
                });

            });

            // delete

            $('body').on('click', '.delete_btn', function () {
                var term_id = $(this).data('term_id');
                $('#delete_modal').data('term_id', term_id);
                $('#delete_modal').modal('show');
            });

            $('body').on('click', '#confirm_delete_btn', function() {
                var term_id = $('#delete_modal').data('term_id');

                $.ajax({
                    url: "{{ route('term.condition.delete') }}",
                    type: 'get',
                    data: {
                        term_id: term_id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        console.log(result);

                        if(result.status == "success")
                        {
                            iziToast.success({
                                message: result.message,
                                position: 'topRight'
                            });

                            $('#delete_modal').modal('hide');
                            term_table.ajax.reload();                
                        }
                        else
                        {
                            iziToast.error({
                                message: result.message,
                                position: 'topRight'
                            });

                            $('#delete_modal').modal('hide');
                        }                      
                    },
                    error: function(error) {
                        console.log(error);
                        console.log('Delete request error:', error);
                    }
                });

            });

        });

        // edit

        function showFormModal(termId) {
            $.ajax({
                url: '{{ route('term.condition.edit') }}?id=' + termId,
                type: "GET",
                data: {
                    _token: "{{ csrf_token() }}",
                    'termId': termId
                },
                success: function(response) {
                    $('#edit-term').modal('show');
                    $('#edit-term-content').html(response);
                },
                error: function() {
                    console.log('Error occurred while loading the modal content.');
                }
            })
        }

        // add

        function showaddFormModal() {
            $('#add-term').modal('show');
        }
    </script>
@endsection
