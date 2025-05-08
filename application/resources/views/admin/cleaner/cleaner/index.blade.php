@extends('theme.default')

@section('custom_css')

    <style>
        #team-table {
            width: -webkit-fill-available !important;
        }

        .custom-modal-dialog {
            max-width: 50%;
            /* You can adjust the percentage based on your preference */
            margin: auto;
        }

        #cleaner-table th {
            /* text-align: center; */
        }

        #team-table th {
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
                            Helpers / Teams
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
                                                class="icon me-2 icon-tabler icon-tabler-tool" width="44" height="44"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path
                                                    d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5">
                                                </path>
                                            </svg>

                                            Cleaners</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#team" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                                            tabindex="-1"
                                            role="tab"><!-- Download SVG icon from http://tabler-icons.io/i/user -->
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

                                            Teams</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active show" id="category" role="tabpanel">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="cleaner-table"
                                                        class="table card-table table-vcenter text-left text-nowrap datatable" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="w-1">No.</th>
                                                                <th>Employee Id</th>
                                                                <th>Postal Code</th>
                                                                <th>Zone</th>
                                                                <th>Zone Color</th>
                                                                <th>Company Name</th>
                                                                <th>Name</th>
                                                                <th>Email</th>
                                                                <th>Contact No.</th>
                                                                <th>Date of Joining</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="team" role="tabpanel">
                                        <div class="row w-100">
                                            <div class="col-auto ms-auto d-print-none">
                                                <a href="{{ route('team.create') }}" class="btn btn-primary m-0"
                                                    data-bs-toggle="modal" data-bs-target="#add-team"
                                                    onclick="showTeamModal()">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M12 5l0 14"></path>
                                                        <path d="M5 12l14 0"></path>
                                                    </svg>
                                                    Create Team
                                                </a>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="card">
                                            <div class="card-body">                                           
                                                <div class="table-responsive">
                                                    <table id="team-table"
                                                        class="table card-table table-vcenter text-left text-nowrap datatable" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="w-1">No.</th>
                                                                <th>Team Name</th>
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
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- add team MODEL -->
    <div class="modal modal-blur fade" id="add-team" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content" id="add-team-model">

            </div>
        </div>
    </div>

    <!-- edit team MODEL -->
    <div class="modal modal-blur fade" id="edit-team" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content" id="edit-team-model">

            </div>
        </div>
    </div>

    <!-- edit cleaner MODEL -->
    <div class="modal modal-blur fade" id="edit_cleaner_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Cleaner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="edit_cleaner_form" method="post">
                    @csrf

                    <input type="hidden" id="cleaner_id" name="cleaner_id">

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Employee Id <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="cleaner_emp_id" id="cleaner_emp_id" readonly>                            
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="cleaner_company_name" id="cleaner_company_name" readonly>                            
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="cleaner_name" id="cleaner_name" readonly>                            
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="cleaner_email" id="cleaner_email" readonly>                            
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contact No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="cleaner_contact_no" id="cleaner_contact_no" readonly>                            
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date of Joining <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="cleaner_doj" id="cleaner_doj" readonly>                            
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Remarks <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="cleaner_remarks" id="cleaner_remarks" cols="30" rows="10" required></textarea>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
                        <button type="submit" class="btn btn-primary ms-auto">Save</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- view cleaner MODEL -->
    <div class="modal modal-blur fade" id="view_cleaner_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Cleaner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>            

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Employee Id <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="cleaner_emp_id" id="view_cleaner_emp_id" readonly>                            
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="cleaner_company_name" id="view_cleaner_company_name" readonly>                            
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="cleaner_name" id="view_cleaner_name" readonly>                            
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="cleaner_email" id="view_cleaner_email" readonly>                            
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contact No. <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="cleaner_contact_no" id="view_cleaner_contact_no" readonly>                            
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date of Joining <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="cleaner_doj" id="view_cleaner_doj" readonly>                            
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Remarks <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="cleaner_remarks" id="view_cleaner_remarks" cols="30" rows="10" readonly></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Close</a>                  
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        function showTeamModal() {
            $.ajax({
                url: "{{ route('team.create') }}",
                type: "GET",
                success: function(response) {

                    $('#add-team').modal('show');
                    $('#add-team-model').html(response);


                },
                error: function() {
                    console.log('Error occurred while loading the modal content.');

                }
            });
        }

        function edit_team_modal(id) {
            $.ajax({
                url: "{{ route('team.edit', ['id' => ':id']) }}".replace(':id', id),
                type: "get",
                success: function(response) {
                    $('#edit-team').modal('show');
                    $('#edit-team-model').html(response);
                },
                error: function() {
                    console.log('Error occurred while loading the edit modal content.');
                }
            });
        }

        $(document).ready(function() {
            var cleanerTable = $('#cleaner-table').DataTable({
                serverSide: true,
                searching: true, // Enable searching
                ordering: true, // Enable sorting
                "aaSorting": [],
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                ajax: '{{ route('cleaners.data') }}',
                columns: [
                    {
                        data: 'sno',
                        name: 'No'
                    },
                    {
                        data: 'employee_id',
                        name: 'employee_id'
                    },
                    {
                        data: 'employee_zipcode',
                        name: 'employee_zipcode'
                    },
                    {
                        data: 'employee_zone_name',
                        name: 'employee_zone_name'
                    },
                    {
                        data: 'employee_zone_color',
                        name: 'employee_zone_color'
                    },
                    {
                        data: 'company_name',
                        name: 'company_name'
                    },
                    {
                        data: 'first_name',
                        name: 'first_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'contact_no',
                        name: 'contact_no'
                    },
                    {
                        data: 'date_of_joining',
                        name: 'date_of_joining'
                    },        
                    {
                        data: 'action',
                        title: 'Action'
                    }
                ]
            });

            var teamTable = $('#team-table').DataTable({
                serverSide: true,
                ajax: '{{ route('team.data') }}',
                columns: [{
                        data: 'sno',
                        name: 'No'
                    },
                    {
                        data: 'team_name',
                        name: 'Team Name'
                    },
                    {
                        data: 'action',
                        title: 'Action'
                    }
                ]
            });

            $(document).on('click', '.btn_delete_team', function() {
                var teamId = $(this).data('team-id');

                if (confirm('Are you sure you want to delete this Team?')) {
                    $.ajax({
                        url: "{{ route('delete-team', ['id' => ':id']) }}".replace(':id', teamId),
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
                            $('#team-table').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            alert('Error: ' + xhr.responseText);
                        }
                    });
                }
            });

            // edit cleaner

            $('body').on('click', '.edit_cleaner_btn', function() {

                var cleaner_id = $(this).data('id');

                $.ajax({
                    type: "get",
                    url: "{{route('cleaners.edit')}}",
                    data: {cleaner_id: cleaner_id},
                    success: function (result) {
                        console.log(result);

                        $("#cleaner_id").val(result.xin_employee.user_id);
                        $("#cleaner_company_name").val(result.xin_employee.company_name);
                        $("#cleaner_emp_id").val(result.xin_employee.employee_id);
                        $("#cleaner_name").val(result.xin_employee.fullName);
                        $("#cleaner_email").val(result.xin_employee.email);
                        $("#cleaner_contact_no").val(result.xin_employee.contact_no);
                        $("#cleaner_doj").val(result.xin_employee.date_of_joining);
                        $("#cleaner_remarks").val(result.xin_employee.remarks);

                        $("#edit_cleaner_modal").modal('show');
                    },
                    error: function (result) {
                        console.log(result);
                    },
                });

            });

            // update cleaner

            $('body').on('submit', '#edit_cleaner_form', function(e) {

                e.preventDefault();

                $.ajax({
                    type: "post",
                    url: "{{route('cleaners.update')}}",
                    data: $(this).serialize(),
                    success: function (result) {
                        console.log(result);

                        if(result.status == "error")
                        {
                            $.each(result.errors, function(key, value) {
                                iziToast.error({
                                    message: value,
                                    position: 'topRight'
                                });
                            });                       
                        }
                        else
                        {
                            $("#edit_cleaner_modal").modal('hide');

                            iziToast.success({
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

            // view cleaner

            $('body').on('click', '.view_cleaner_btn', function() {

                var cleaner_id = $(this).data('id');

                $.ajax({
                    type: "get",
                    url: "{{route('cleaners.edit')}}",
                    data: {cleaner_id: cleaner_id},
                    success: function (result) {
                        console.log(result);

                        $("#view_cleaner_emp_id").val(result.xin_employee.employee_id);
                        $("#view_cleaner_company_name").val(result.xin_employee.company_name);
                        $("#view_cleaner_name").val(result.xin_employee.fullName);
                        $("#view_cleaner_email").val(result.xin_employee.email);
                        $("#view_cleaner_contact_no").val(result.xin_employee.contact_no);
                        $("#view_cleaner_doj").val(result.xin_employee.date_of_joining);
                        $("#view_cleaner_remarks").val(result.xin_employee.remarks);

                        $("#view_cleaner_modal").modal('show');
                    },
                    error: function (result) {
                        console.log(result);
                    },
                });

            });

            // superviser start

            $('body').on('change', '.employee_id_class', function () {

                var emp_id = $(this).val();

                $.ajax({
                    type: "get",
                    url: "{{route('team.get-superviser')}}",
                    data: {emp_id: emp_id},
                    success: function (result) {
                        // console.log(result);

                        if(result.xin_employees.length > 0)
                        {
                            $(".superviser_emp_id").html("");

                            $.each(result.xin_employees, function (key, value) { 
                                var html = `<option value="${value.user_id}">${value.first_name} ${value.last_name}</option>`;
                            
                                $(".superviser_emp_id").append(html);
                            });                       
                        }
                        else
                        {
                            $(".superviser_emp_id").html("");
                        }
                    },
                    error: function (result) {
                        console.log(result);
                    },
                });

            });

            // superviser end

        });
    </script>
@endsection

