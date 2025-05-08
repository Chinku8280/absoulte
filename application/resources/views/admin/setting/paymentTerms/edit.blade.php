<div class="modal-header">
   <h5 class="modal-title">Edit Payment Name</h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
   <div class="add-zone my-3">
      <form id="language_form">
         @csrf
         <input type="hidden" name="id" class="form-control" value="{{$data->id}}">

         <div class="row">
            <div class="col-md-12">
               <div class="mb-3">
                  <label class="form-label">Payment Terms:</label>
                  <input type="text" name="payment_terms" class="form-control" value="{{$data->payment_terms}}" required>
               </div>
            </div>
                  
            <div class="col-md-12">
              <div class="mb-3">
                 <label class="form-label">Payment Terms Value:</label>
                 <input type="number" name="payment_terms_value" class="form-control" value="{{$data->payment_terms_value}}" min="0" required>
              </div>
           </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="language_form_btn">Update</button>
         </div>
   </div>
   </form>
</div>
</div>
<script>
   $("#language_form_btn").click(function(e){
   e.preventDefault();
   let form = $("#language_form")[0];
   let data = new FormData(form);
   $.ajax({
       url : "{{route('paymentTerms.store')}}",
       type: "POST",
       data:data,
       dataType:"JSON",
       processData:false,
       contentType:false,
       success:function(response){
           if(response.errors){
               var errorMsg = '';
             $.each(response.errors, function (field, errors) {
               $.each(errors, function (index, error) {
                 errorMsg += error + '<br>';
               });
             });
             iziToast.error({
               message: errorMsg,
               position: 'topRight'
             });
           }else {
             iziToast.success({
               message: response.success,
               position: 'topRight'
             });
             var myModal = new bootstrap.Modal(document.getElementById('add-payment'));
            myModal.hide();
             window.location.reload();

           }
       },
           error: function (xhr, status, error) {
           // Display an error message
           iziToast.error({
             message: 'An error occurred: ' + error,
             position: 'topRight'
           });
         }
   })
   });

</script>
