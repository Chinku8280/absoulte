<div class="modal-header">
    <h5 class="modal-title">Add Company</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="company_form" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-1">
                <div class="mb-3">
                    <label class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="company_name" placeholder="Enter Name">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-1">
                <div class="mb-3">
                    <label class="form-label">Person Incharge Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="person_incharge_name" placeholder="Enter Name">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-1">
                <div class="mb-3">
                    <label class="form-label">Contact Number<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="contact_number" placeholder="Enter Number">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-1">
                <div class="mb-3">
                    <label class="form-label">Email Id<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="email_id" placeholder="Enter Email Id">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="mb-3">
                    <label class="form-label">Description <span class="text-danger"></span></label>
                    <textarea name="description" cols="30" rows="4" class="form-control"></textarea>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="gst_required" id="gst_required" value="1"
                        checked="">
                    <label for="">Requires GST calculation</label>
                </div>
            </div>
            <h4 class="mt-3"><b>For Quatation Template</b></h4><br>
            <div class="col-lg-12">
                <div class="mb-3">
                    <label class="form-label">Company Address <span class="text-danger">*</span></label>
                    <textarea name="company_address" cols="30" rows="4" class="form-control"></textarea>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="mb-3">
                    <label class="form-label">Website</label>
                    <input type="text" class="form-control" name="website" placeholder="Enter Website url">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="mb-3">
                    <label class="form-label">Telephone</label>
                    <input type="text" class="form-control" name="telephone" placeholder="Enter Telephone Number">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="mb-3">
                    <label class="form-label">Fax</label>
                    <input type="text" class="form-control" name="fax" placeholder="Enter Fax Number">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="mb-3">
                    <label class="form-label">Company Register No:</label>
                    <input type="text" class="form-control" name="co_register_no">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="mb-3">
                    <label class="form-label">GST Register No:</label>
                    <input type="text" class="form-control" name="gst_register_no">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="mb-3">
                    <label class="form-label">Company Short Name</label>
                    <input type="text" class="form-control" name="short_name">
                </div>
            </div>
        </div>

        <h4><b>Bank Details</b></h4><br>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-1">
                <div class="mb-3">
                    <label class="form-label">Bank Name </label>
                    <input type="text" class="form-control" name="bank_name" placeholder="Enter Bank Name">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-1">
                <div class="mb-3">
                    <label class="form-label">Account Number </label>
                    <input type="text" class="form-control" name="ac_number" placeholder="Enter Account Number">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="mb-3">
                    <label class="form-label">Bank Code</label>
                    <input type="text" class="form-control" name="bank_code" placeholder="Enter Bank Code">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="mb-3">
                    <label class="form-label">Branch Code</label>
                    <input type="text" class="form-control" name="branch_code" placeholder="Enter Branch Code">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="mb-3">
                    <label class="form-label">UEN No</label>
                    <input type="text" class="form-control" name="uen_no" placeholder="Enter UEN No">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="mb-3">
                    <label class="form-label">Company Logo</label>
                    <input type="file" name="company_logo" id="company_logo" class="form-control">
                </div>
            </div>

            <div class="col-lg-12">
                <div class="mb-3">
                    <label class="form-label">Qr Code</label>
                    <input type="file" name="qr_code" id="qr_code" class="form-control">
                </div>
            </div>

            <div class="col-lg-12">
                <div class="mb-3">
                    <label class="form-label">Invoice Footer Logo</label>
                    <input type="file" name="invoice_footer_logo[]" id="invoice_footer_logo" class="form-control"
                        multiple>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="mb-3">
                    <label class="form-label">Stamp</label>
                    <input type="file" name="stamp" id="stamp" class="form-control">
                </div>
            </div>

            {{-- <div class="col-lg-12">
                <div class="mb-3">
                    <label class="form-label">Terms & Condition <span class="text-danger"></span></label>
                    <textarea name="term_condition" cols="30" rows="4" class="form-control"></textarea>
                </div>
            </div> --}}
        </div>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
            Cancel
        </a>
        <button type="submit" class="btn btn-primary ms-auto" id="company_form_btn">
            Save
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#company_form_btn").click(function(e) {
            e.preventDefault();
            let form = $('#company_form')[0];
            let data = new FormData(form);

            $.ajax({
                url: "{{ route('company.store') }}",
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
                        $('#company_form')[0].reset();
                        $('#add-company').modal('hide');
                        $('#company-table').DataTable().ajax.reload();
                        window.location.reload();
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
    });
</script>
