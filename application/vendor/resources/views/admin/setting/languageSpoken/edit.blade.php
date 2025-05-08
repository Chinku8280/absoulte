<div class="modal-header">
   <h5 class="modal-title">Edit Language Name</h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
   <div class="add-zone my-3">
      <form id="language_form">
         @csrf
         <div class="row">
            <div class="col-md-5">
               <div class="mb-3">
                  <label class="form-label">Language Name:</label>
                  <input type="text" name="language_name" value= "{{$data->language_name}}" class="form-control">
               </div>
            </div>
            <input type="hidden" name="id" value="{{$data->id}}">
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
       url : "{{route('languageSpoken.store')}}",
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
             $('#add-language').modal('hide');
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