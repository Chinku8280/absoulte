@extends('theme.default')

@section('custom_css')

<style>
    #residential-customer-table th {
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
                            Email Template
                        </h2>
                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            {{-- <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                                    data-bs-target="#add-email-modal" onclick="showFormModal()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Add New
                                    </button> --}}
                            <a class="btn btn-primary" data-bs-toggle="modal" href="#exampleModalToggle" role="button">
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
                            <div class="card-body">                          
                                <div class="table-responsive">
                                    <table id="residential-customer-table"
                                        class="table card-table table-vcenter text-left text-nowrap datatable">
                                        <thead>
                                            <tr>
                                                <th class="w-1">No.</th>
                                                <th>Title</th>
                                                <th>Subject</th>
                                                <th>CC</th>
                                                <th>BCC</th>
                                                {{-- <th>Body</th> --}}
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($templates as $key => $template)
                                                <tr>
                                                    <input type="hidden" name="id" value="{{ $template->id }}"
                                                        id="id">
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $template->title }}</td>
                                                    <td>{{ $template->subject }}</td>
                                                    <td>{{ $template->cc }}</td>
                                                    <td>{{ $template->bcc }}</td>
                                                    {{-- <td>{{ strip_tags($template->body) }}</td> --}}
                                                    <td>
                                                        {{-- <a href="{{route('template.edit',$template->id)}}" class="btn btn-info"><i class="fa fa-pencil" aria-hidden="true"></i></a> --}}
                                                        <a class="btn btn-primary" id="editTemplate"
                                                            onclick="editModal({{ $template->id }})"
                                                            data-bs-toggle="modal" role="button"><i
                                                                class="fa fa-pencil" aria-hidden="true"></i></a>
                                                        <a href="{{ route('template.delete', $template->id) }}"
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

    <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel">Add Email Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('emailtemplate.store') }}" method="post" id="add_email_template_form">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-12 mb-4">
                                <label for=""><b>Company</b></label><br><br>
                                <select name="company_id" id="company_id" class="form-control">
                                    <option value="">Select Company</option>
                                    @foreach ($company as $item)
                                        <option value="{{ $item->id }}">{{ $item->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for=""><b>Title</b></label><br><br>
                                <input type="text" class="form-control" name="title" placeholder="Enter Title"
                                    required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for=""><b>Subject</b></label><br><br>
                                <input type="text" class="form-control" name="subject" placeholder="Enter Subject"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for=""><b>CC</b></label><br><br>
                                <input type="text" class="form-control cc_input" name="cc" id="cc-create-input"
                                    placeholder="Enter CC">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for=""><b>BCC</b></label><br><br>
                                <input type="text" class="form-control" name="bcc" placeholder="Enter BCC">
                            </div>
                            <div class="mb-3">
                                <label for=""><b>Body</b></label><br><br>
                                <textarea name="body" id="body" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="edit-email-template" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" id="edit-template-content">
            </div>
        </div>
    </div>

@endsection

@section('javascript')

    <script>
        $(document).ready(function() {
            var input = document.getElementById('cc-create-input');
            new Tagify(input);
            $('#residential-customer-table').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
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
        });
    </script>
    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script> --}}
    <script>
        ClassicEditor
            .create(document.querySelector('#body'))
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        // $('#editTemplate').click(function(){
        function editModal(templateId) {
            $.ajax({
                url: '{{ route('template.edit') }}?id=' + id,
                type: "GET",
                data: {
                    _token: "{{ csrf_token() }}",
                    'template_id': templateId
                },
                success: function(response) {
                    // console.log(response);
                    $('#edit-email-template').modal('show');
                    $('#edit-template-content').html(response);
                },
                error: function() {
                    console.log('Error occurred while loading the modal content.');
                }
            })
        }
        // });


        $(document).ready(function() {

            $('body').on('submit', '#add_email_template_form', function(e) {

                e.preventDefault();

                $.ajax({
                    type: "post",
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function(result) {
                        console.log(result);

                        if (result.status == "error") {
                            $.each(result.errors, function(field, errors) {
                                iziToast.error({
                                    message: errors,
                                    position: 'topRight'
                                });
                            });
                        } else {
                            iziToast.success({
                                message: result.message,
                                position: 'topRight'
                            });

                            window.location.reload();
                        }
                    },
                    error: function(result) {
                        console.log(result);
                    }
                });

            });

        });
    </script>
@endsection
