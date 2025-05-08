 <div class="modal-header">
     <h5 class="modal-title">Add Service</h5>
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
 </div>
 <form id="service_form" method="POST">
     @csrf
     <div class="modal-body">
         <div class="row">
             <div class="col-lg-12 col-md-6 col-sm-1">
                 <div class="mb-3">
                     <div class="form-label">Select Company</div>
                     <select class="form-select" name="company">
                         @foreach ($company_list as $list)
                             <option value="{{ $list->id }}">{{ $list->company_name }}</option>
                         @endforeach
                     </select>
                 </div>

                 <div class="mb-3">
                     <label class="form-label">Service Name <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" name="service_name" placeholder="Enter Name">
                 </div>
                 <div class="mb-3">
                     <label class="form-label">Man Power Required</label>
                     <input type="text" class="form-control" name="man_power_required" placeholder="Enter length">
                 </div>
                 <div class="mb-3">
                     <label class="form-label">Product Code</label>
                     <input type="text" class="form-control" name="product_code">
                 </div>
                 <div class="row">
                     <div class="col-md-6">
                         <div class="mb-3">
                             <label class="form-label">Hour/Session</label>
                             <input type="text" class="form-control" name="hour_session">
                         </div>
                     </div>

                     <div class="col-md-6">
                         <div class="mb-3">
                             <label class="form-label">Weekly Freq</label>
                             <input type="text" class="form-control" name="weekly_freq">
                         </div>
                     </div>
                 </div>
                 <div class="mb-3">
                     <label class="form-label">Total Sessions</label>
                     <input type="text" class="form-control" name="total_session">
                 </div>
                 <div class="mb-3">
                     <label class="form-label">Description <span class="text-danger"></span></label>
                     <textarea name="description" id="" cols="30" rows="10" class="form-control"></textarea>
                 </div>
                 <div class="mb-3">
                     <label class="form-label">Price <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" name="price" placeholder="Enter Price">
                 </div>
             </div>

         </div>

     </div>
     <div class="modal-footer">
         <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
             Cancel
         </a>
         <button type="submit" class="btn btn-primary ms-auto" id="service_form_btn">

             Save
         </button>
     </div>
 </form>
 <script>
     $("#service_form_btn").click(function(e) {
         e.preventDefault();
         let form = $('#service_form')[0];
         let data = new FormData(form);

         $.ajax({
             url: "{{ route('service.store') }}",
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
                     $('#service_form')[0].reset();
                     $('#add-service').modal('hide');
                     $('#service-table').DataTable().ajax.reload();
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
