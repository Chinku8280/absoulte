<div class="modal-header">
                    <h5 class="modal-title">Add Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="branch_form" method="POST">
                    @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">UEN <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="uen"
                                    placeholder="Enter Id Number">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">Branch Name</label>
                                <input type="text" class="form-control" name="branch_name"
                                    placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">Person Incharge Name </label>
                                <input type="text" class="form-control" name="personan_incharge_name"
                                    placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">Nickname</label>
                                <input type="text" class="form-control" name="nick_name"
                                    placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" class="form-control" name="mobile_number"
                                    placeholder="Enter Number">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">Fax Number</label>
                                <input type="text" class="form-control" name="fax_number"
                                    placeholder="Enter Number">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" name="email"
                                    placeholder="Enter Email">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="address"
                                    placeholder="Enter Address">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">Postal Code</label>
                                <input type="text" class="form-control" name="postal_code"
                                    placeholder="Enter Code">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">Country</label>
                                <select class="form-select" name= "country">
                                    <option selected="">India</option>
                                    <option value="One">Singapore</option>

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary ms-auto" id="branch_form_btn" >
                        Save
                    </a>
                </div>
                </form>
                <script>

                    
$("#branch_form_btn").click(function(e){
     e.preventDefault();
     let form = $('#branch_form')[0];
     let data = new FormData(form);
    
      $.ajax({
        url: "{{ route('branch.store') }}",
        type: "POST",
        data : data,
        dataType:"JSON",
        processData : false,
        contentType:false,
        
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
               $('#add-branch').modal('hide');
                
        }
    },
    error: function(xhr, status, error) {
        // Display an error message
        iziToast.error({
            message: 'An error occurred: ' + error,
            position: 'topRight'
        });
    }
 
      });
   
})
                </script>