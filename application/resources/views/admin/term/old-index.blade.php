@extends('theme.default')

@section('custom_css')
    <style>
        #term-table th {
            text-align: center;
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


                                            <div class="table-responsive">
                                                <table id="term-table"
                                                    class="table card-table table-vcenter text-center text-nowrap datatable">
                                                    <thead>
                                                        <tr>
                                                            <th class="w-1">No.</th>
                                                            <th>Terms & Condition</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($terms as $key => $term)
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

    <!-- MODEL -->
    <div class="modal fade" id="add-term" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Terms & Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('term.condition.store') }}" method="post">
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
                            <input type="text" name="term_condition" class="form-control">
                        </div>
                        {{-- <input type="hidden" name="id" value="{{$data->id}}"> --}}
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-term" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="edit-term-content">

            </div>
        </div>
    </div>
@endsection

@section('javascript')

    @if ($errors->has('term_condition'))
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
    @endif

    <script>
        $(document).ready(function() {
            $('#term-table').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
            });
        });
    </script>
    <script>
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

        function showaddFormModal() {
            $('#add-term').modal('show');
        }
    </script>
@endsection
