  <div class="modal-header">
                    <h5 class="modal-title">Add Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                 <form id="company_form" method="POST">
                    @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-1">
                            <div class="mb-3">
                                <label class="form-label">Company Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="company_name"
                                    placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                          <div class="mb-3">
                              <label class="form-label">Person Incharge Name <span class="text-danger">*</span></label>
                              <input type="text" class="form-control" name="person_incharge_name"
                                  placeholder="Enter Name">
                          </div>
                      </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                          <div class="mb-3">
                              <label class="form-label">Contact Number<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" name="contact_number"
                                  placeholder="Enter Number">
                          </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-1">
                        <div class="mb-3">
                            <label class="form-label">Email Id<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="email_id"
                                placeholder="Enter Email Id">
                        </div>
                    </div>
                       <div class="col-lg-12">
                          <div class="mb-3">
                                <label class="form-label">Description <span class="text-danger"></span></label>
                               <textarea name="description" cols="30" rows="4" class="form-control"></textarea>
                            </div>
                       </div>
                       <h4><b>For Quatation Template</b></h4><br>
                    </div>
                    {{--<div class="mb-3">
                      <label class="form-label">Upload Company Logo</label>
                       <div class="row g-2">
                        <div class="col-3">
                          <label class="form-imagecheck mb-2">
                            <input name="form-imagecheck-radio" type="radio" value="1" class="form-imagecheck-input">
                            <span class="form-imagecheck-figure">
                              <img src="{{asset('theme/dist/img/invoice-logo/logo-1.png')}}" height="100px" alt="Group of people sightseeing in the city" class="form-imagecheck-image">
                            </span>
                          </label>
                        </div>
                        <div class="col-3">
                          <label class="form-imagecheck mb-2">
                            <input name="form-imagecheck-radio" type="radio" value="2" class="form-imagecheck-input" checked="">
                            <span class="form-imagecheck-figure">
                              <img src="{{asset('theme/dist/img/invoice-logo/logo-2.png')}}" height="100px" alt="Color Palette Guide. Sample Colors Catalog." class="form-imagecheck-image">
                            </span>
                          </label>
                        </div>
                        <div class="col-3">
                          <label class="form-imagecheck mb-2">
                            <input name="form-imagecheck-radio" type="radio" value="3" class="form-imagecheck-input">
                            <span class="form-imagecheck-figure">
                              <img src="{{asset('theme/dist/img/invoice-logo/logo-3.png')}}" height="100px" alt="Stylish workplace with computer at home" class="form-imagecheck-image">
                            </span>
                          </label>
                        </div>
                        <div class="col-3">
                          <label class="form-imagecheck mb-2">
                            <input name="form-imagecheck-radio" type="radio" value="4" class="form-imagecheck-input" checked="">
                            <span class="form-imagecheck-figure">
                              <img src="{{asset('theme/dist/img/invoice-logo/logo-4.jpg')}}" height="100px" alt="Pink desk in the home office" class="form-imagecheck-image">
                            </span>
                          </label>
                        </div>
                        
                        <div class="col-3">
                          <label class="form-imagecheck mb-2">
                            <input name="form-imagecheck-radio" type="radio" value="6" class="form-imagecheck-input">
                            <span class="form-imagecheck-figure">
                              <img src="{{asset('theme/dist/img/invoice-logo/logo-7.png')}}" height="100px alt="Coffee on a table with other items" class="form-imagecheck-image">
                            </span>
                          </label>
                        </div>
                        
                        <div class="col-4">
                          <label class="form-imagecheck mb-2">
                            <input name="form-imagecheck-radio" type="radio" value="6" class="form-imagecheck-input">
                            <span class="form-imagecheck-figure">
                              <img src="{{asset('theme/dist/img/auntie-cleaner-logo.webp')}}" height="100px" alt="Coffee on a table with other items" class="form-imagecheck-image">
                            </span>
                          </label>
                        </div>
                        <div class="col-4">
                          <label class="form-imagecheck mb-2">
                            <input name="form-imagecheck-radio" type="radio" value="6" class="form-imagecheck-input">
                            <span class="form-imagecheck-figure">
                              <img src="{{asset('theme/dist/img/invoice-logo/logo-8.png')}}" height="100px" alt="Coffee on a table with other items" class="form-imagecheck-image">
                            </span>
                          </label>
                        </div>
                        <div class="col-4">
                          <label class="form-imagecheck mb-2">
                            <input name="form-imagecheck-radio" type="radio" value="5" class="form-imagecheck-input">
                            <span class="form-imagecheck-figure">
                              <img src="{{asset('theme/dist/img/invoice-logo/logo-5.png')}}" height="100px" alt="Young woman sitting on the sofa and working on her laptop" class="form-imagecheck-image">
                            </span>
                          </label>
                        </div>
                        <div class="col-4">
                          <label class="form-imagecheck mb-2">
                            <input name="form-imagecheck-radio" type="radio" value="6" class="form-imagecheck-input">
                            <span class="form-imagecheck-figure">
                              <img src="{{asset('theme/dist/img/invoice-logo/logo-6.png')}}" height="100px" alt="Coffee on a table with other items" class="form-imagecheck-image">
                            </span>
                          </label>
                        </div>
                      </div> 
                      <input type="file" name="company_logo" id="company_logo" class="form-control">
                    </div>--}}
                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-1">
                        <div class="mb-3">
                            <label class="form-label">Comapny Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="company_address"
                                placeholder="Enter Name">
                        </div>
                      </div>
                        <div class="col-lg-6 col-md-6 col-sm-1">
                          <div class="mb-3">
                              <label class="form-label">Website<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" name="company_website" placeholder="Enter Website url">
                          </div>
                      </div>
                    </div>
                    <div class="mb-3">
                      <label class="form-label"> Company Logo</label>
                      <input type="file" name="company_logo" id="" class="form-control">
                    </div>
                    <div class="col-lg-12">
                      {{-- <div class="mb-3">
                            <label class="form-label">Terms & Condition <span class="text-danger"></span></label>
                           <textarea name="term_condition" cols="30" rows="4" class="form-control"></textarea>
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

                    
$("#company_form_btn").click(function(e){
     e.preventDefault();
     let form = $('#company_form')[0];
     let data = new FormData(form);
    
      $.ajax({
        url: "{{ route('company.store') }}",
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
   
})