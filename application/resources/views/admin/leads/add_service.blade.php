     <form id="service_address_form" method="POST">
                                       @csrf
                                       
                                       <div class="col-md-12 add_address" style="display: none;">
                                       <div class="row my-3">
                                            <input type="text" id="customer_id_service" name="customer_id" value="">

                                          <div class="col-md-4">
                                             <div class="form-group mb-3">
                                                <label for="">Person Incharge Name sr</label>
                                                <input type="text" placeholder="Enter Name" name="person_incharge_name[]" class="form-control"
                                                  />
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group mb-3">
                                                <label for="">Contact No</label>
                                                <input type="text" placeholder="Enter Number" name="contact_no[]" class="form-control"
                                                  >
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group mb-3">
                                                <label for="">Email Id</label>
                                                <input type="text" placeholder="Enter Email" name="email_id[]" class="form-control"
                                                  >
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group mb-3">
                                                <label for="">Postal Code</label>
                                                <input type="text" placeholder="Enter Code" name="postal_code[]" class="form-control"
                                                  >
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group mb-3">
                                                <label for="">Zone</label>
                                                <input type="text" placeholder="Enter Zone" name="zone[]" class="form-control"
                                                  >
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group mb-3">
                                                <label for="">Address</label>
                                                <input type="text" placeholder="Enter Address" name="address[]" class="form-control"
                                                 >
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group mb-3">
                                                <label for="">Unit No</label>
                                                <input type="text" placeholder="Enter Unit No." name="unit_number[]" class="form-control"
                                                   >
                                             </div>
                                          </div>
                                          <div class="col-md-4 my-auto">
                                             <button type="button" class="btn btn-blue" id="rowAdder_add_lead">+</button>
                                          </div>
                                          <!-- <div class="col-md-1" style="display: flex; align-items: center;">
                                             <button type="button" class="btn btn-danger" id="DeleteRow">-</button>
                                             </div> -->
                                       </div>
                                       <div id="newinput_add_lead"></div>
                                       <div class="row">
                                          <div class="col-md-12">
                                             <button type="submit" class="btn btn-primary" id="service_address_btn">save</button>
                                          </div>
                                       </div>
                                    </div>
                                    </form>