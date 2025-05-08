   <div class="modal-header">
          <h5 class="modal-title">Update Status {{$lead->id}}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="smartwizard2" style="border: none;">
            <ul class="nav d-none">
              <li class="nav-item">
                <a class="nav-link" href="#update-step-1">
                  <div class="num">1</div>
                  1
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#update-step-2">
                  <span class="num">2</span>
                  2
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#update-step-3">
                  <span class="num">3</span>
                  3
                </a>
              </li>

            </ul>
        <form id="lead_update_status_form" method="POST">
            @csrf
            <input type="hidden" class="form-check-input" name="lead_id" value="{{$lead->id}}">

            <div class="tab-content p-0" style="border: none;">
              <div id="update-step-1" class="tab-pane py-0" role="tabpanel" aria-labelledby="update-step-1">
                <div class="row">
                  <div class="col-md-12">
                    <label class="form-label">Status <span style="color:red">*</span></label>
                    <div class="form-group mb-3">
                      <div class="form-check form-check-primary">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="status" id="1" value="New" {{ $leadStatusUpdateData && $leadStatusUpdateData->status ===
                           'New' ? 'checked' : '' }}">
                          New
                          <i class="input-helper"></i></label>
                      </div>
                      <div class="form-check form-check-primary">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="status" id="1" value="Follow Up / Under Review" {{ $leadStatusUpdateData && $leadStatusUpdateData->status ===
                         'Follow Up / Under Review' ? 'checked' : '' }}>
                          Follow Up / Under Review
                          <i class="input-helper"></i></label>
                      </div>
                      <div class="form-check form-check-primary">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="status" id="1" value="Negotiation" {{ $leadStatusUpdateData && $leadStatusUpdateData->status ===
                         'Negotiation' ? 'checked' : '' }}>
                          Negotiation
                          <i class="input-helper"></i></label>
                      </div>

                      <div class="form-check form-check-primary">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="status" id="1" value="Unqualified" {{ $leadStatusUpdateData && $leadStatusUpdateData->status ===
                         'Unqualified' ? 'checked' : '' }}>
                          Unqualified
                          <i class="input-helper"></i></label>
                      </div>
                      <div class="form-check form-check-primary">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="status" id="11" value="Share Quatation" {{ $leadStatusUpdateData && $leadStatusUpdateData->status ===
                         'Share Quatation' ? 'checked' : '' }}>
                          Share Quatation
                          <i class="input-helper"></i></label>
                      </div>
                    </div>
                    <label class="form-label">Comments (If any) <span style="color:red">*</span></label>
                    <textarea class="form-control" id="" cols="" rows="" name="comment">
                      @if ($leadStatusUpdateData)
                          {{$leadStatusUpdateData->comment}}
                      @endif</textarea>
                  </div>
                </div>
              </div>
              <div id="update-step-2" class="tab-pane" role="tabpanel" aria-labelledby="update-step-2">
                <div class="row">
                  <div class="mb-3">
                    <label class="form-label">Select Template</label>
                    <div class="row g-2">
                      <div class="col-6 col-sm-4">
                        <label class="form-imagecheck mb-2">
                          <input name="quotation_templete" type="radio" value="img/template.jpg" class="form-imagecheck-input">
                          <span class="form-imagecheck-figure">
                            <img src="{{asset('theme/dist/img/template.jpg')}}" alt="Group of people sightseeing in the city"
                              class="form-imagecheck-image">
                          </span>
                        </label>
                      </div>
                      <div class="col-6 col-sm-4">
                        <label class="form-imagecheck mb-2">
                          <input name="quotation_templete" type="radio" value="img/template.jpg" class="form-imagecheck-input"
                            checked="">
                          <span class="form-imagecheck-figure">
                            <img src="{{asset('theme/dist/img/template.jpg')}}" alt="Color Palette Guide. Sample Colors Catalog."
                              class="form-imagecheck-image">
                          </span>
                        </label>
                      </div>
                      <div class="col-6 col-sm-4">
                        <label class="form-imagecheck mb-2">
                          <input name="quotation_templete" type="radio" value="img/template.jpg" class="form-imagecheck-input">
                          <span class="form-imagecheck-figure">
                            <img src="{{asset('theme/dist/img/template.jpg')}}" alt="Stylish workplace with computer at home"
                              class="form-imagecheck-image">
                          </span>
                        </label>
                      </div>
                      <div class="col-6 col-sm-4">
                        <label class="form-imagecheck mb-2">
                          <input name="quotation_templete" type="radio" value="img/template.jpg" class="form-imagecheck-input"
          >
                          <span class="form-imagecheck-figure">
                            <img src="{{asset('theme/dist/img/template.jpg')}}" alt="Pink desk in the home office"
                              class="form-imagecheck-image">
                          </span>
                        </label>
                      </div>
                      <div class="col-6 col-sm-4">
                        <label class="form-imagecheck mb-2">
                          <input name="quotation_templete" type="radio" value="img/template.jpg" class="form-imagecheck-input">
                          <span class="form-imagecheck-figure">
                            <img src="{{asset('theme/dist/img/template.jpg')}}"
                              alt="Young woman sitting on the sofa and working on her laptop"
                              class="form-imagecheck-image">
                          </span>
                        </label>
                      </div>
                      <div class="col-6 col-sm-4">
                        <label class="form-imagecheck mb-2">
                          <input name="quotation_templete" type="radio" value="img/template.jpg" class="form-imagecheck-input">
                          <span class="form-imagecheck-figure">
                            <img src="{{asset('theme/dist/img/template.jpg')}}" alt="Coffee on a table with other items"
                              class="form-imagecheck-image">
                          </span>
                        </label>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
              <div id="update-step-3" class="tab-pane" role="tabpanel" aria-labelledby="update-step-3">

                <div class="row">
                  <div class="col-md-12">
                    <img src="{{asset('theme/dist/img/template.jpg')}}" width="100%" alt="Image 1">
                  </div>
                  <div class="col-md-12 text-end">
                    <button type="button" class="btn btn-info" id="lead_update_status_btn">Confirm</button>
                  </div>
                </div>
              </div>

            </div>
            </form>
            <!-- Include optional progressbar HTML -->
            <div class="progress">
              <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                aria-valuemax="100"></div>
            </div>
          </div>
        </div>
         <script>
    $('#smartwizard2').smartWizard({
      transition: {
        animation: 'slideHorizontal',
      }
    });

  </script>
  <script>
    $('#lead_update_status_btn').click(function(e){
        e.preventDefault();
    let form = $('#lead_update_status_form')[0];
    let data =  new FormData(form);
    
    $.ajax({
        url: "{{ route('updateStatus.store') }}",
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
                    position: 'topRight',
                });
            
                  $('#add-lead').modal('hide');
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
  </script>