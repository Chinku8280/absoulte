<div class="modal-header">
    <h5 class="modal-title">Create Team</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="team_form" method="POST">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="mb-3">
                    <label class="form-label">Team Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="team_name" placeholder="Enter Team Name">
                </div>

                <div class="mb-3">
                    <label class="form-label">Employee <span class="text-danger">*</span></label>
                    {{-- <select name="employee_id[]" id="select2-demo-6" class="form-control" data-plugin="select_hrm"
                        multiple>
                        @foreach($all_employees as $employee)
                            <option value="{{ $employee->user_id }}">{{ $employee->first_name . ' ' . $employee->last_name }}
                            </option>
                        @endforeach
                    </select> --}}

                    <select name="employee_id[]" id="add_emp_id" class="form-select select2 employee_id_class" multiple style="width: 100%;">
                        @foreach($all_employees as $employee)
                            <option value="{{ $employee->user_id }}">{{ $employee->first_name . ' ' . $employee->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Superviser <span class="text-danger">*</span></label>
                    <select name="superviser_emp_id" id="add_superviser_emp_id" class="form-select select2 superviser_emp_id" style="width: 100%;">
                        {{-- @foreach($all_employees as $employee)
                            <option value="{{ $employee->user_id }}">{{ $employee->first_name . ' ' . $employee->last_name }}</option>
                        @endforeach --}}
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
        <button type="submit" class="btn btn-primary ms-auto" id="team_form_btn">Save</button>
    </div>
</form>
<script>
    // select2

    $('.select2').select2({
        dropdownParent: $("#add-team")
    });

    $("#team_form_btn").click(function(e) {
        e.preventDefault();
        let form = $('#team_form')[0];
        let data = new FormData(form);

        $.ajax({
            url: "{{ route('team.store') }}",
            type: "POST",
            data: data,
            dataType: "JSON",
            processData: false,
            contentType: false,

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
                    $('#team_form')[0].reset();
                    $('#add-team').modal('hide');
                    $('#team-table').DataTable().ajax.reload();
                    // window.location.reload();
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
</script>
