   <div class="modal-header">
          <h5 class="modal-title">Add New Quotation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="row text-left">

            <div id="smartwizard" style="border: none; height: auto;">

              <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="#step-1">
                    <div class="num">1</div>
                    Customer
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#step-2">
                    <span class="num">2</span>
                    Services
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#step-3">
                    <span class="num">3</span>
                    Address
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " href="#step-4">
                    <span class="num">4</span>
                    Preview
                  </a>
                </li>
                
              </ul>

              <div class="tab-content mt-3" style="border: none;">
                <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
                  <div class="row">
                    <div class="col-md-3">
                      
                      <ul class="nav nav-pills nav-pills-primary" data-bs-toggle="tabs" role="tablist">
                        <li class="nav-item me-2" role="presentation">
                          <a href="#residential-view" id="residential-view-table" class="nav-link active"
                            data-bs-toggle="tab" aria-selected="true" role="tab">Residential</a>
                        </li>
                        <li class="nav-item me-2" role="presentation">
                          <a href="#commercial-view" class="nav-link" id="commercial-view-table" data-bs-toggle="tab"
                            aria-selected="false" role="tab" tabindex="-1">Commercial</a>
                        </li>


                      </ul>

                      <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="residential-view" role="tabpanel">
                          
                          <div class="mb-3">
                            <label class="form-label">Search By</label>
                            <div class="input-icon mb-3">
                              <input type="text" value="" class="form-control" placeholder="Search…" id="residential" onkeypress="Search(1)">
                              <span class="input-icon-addon">
                                <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                  viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                  stroke-linecap="round" stroke-linejoin="round">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                  <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                  <path d="M21 21l-6 -6"></path>
                                </svg>
                              </span>
                            </div>

                          </div>
                          <div class="mb-3" id="residential_list">
                            <!-- <div class="card card-active">
                              <div class="ribbon bg-yellow">Residential</div>
                              <div class="card-body d-flex justify-content-between">
                                <div class="my-auto">
                                  <label class="mb-0 text-black fw-bold " style="font-size: 14px">Will Smith</label>
                                  <p class="m-0">Tel - +91 9825804569</p>
                                </div>

                              </div>
                            </div> -->
                          </div>
                        </div>
                        <div class="tab-pane fade" id="commercial-view" role="tabpanel">
                          <div class="mb-3">
                            <label class="form-label">Search By</label>
                            <div class="input-icon mb-3">
                              <input type="text" value="" class="form-control" placeholder="Search…" id="commercial" onkeypress="Search(0)">
                              <span class="input-icon-addon">
                                <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                  viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                  stroke-linecap="round" stroke-linejoin="round">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                  <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                  <path d="M21 21l-6 -6"></path>
                                </svg>
                              </span>
                            </div>

                          </div>
                          <div class="mb-3" id="commercial_list">
                            <!-- <div class="card card-active">
                              <div class="ribbon bg-red">Commercial</div>
                              <div class="card-body d-flex justify-content-between">
                                <div class="my-auto">
                                  <label class="mb-0 text-black fw-bold " style="font-size: 14px">Will Smith</label>
                                  <p class="m-0">Tel - +91 9825804569</p>
                                </div>

                              </div>
                            </div> -->
                          </div>
                        </div>

                      </div>


                    </div>
                    <div class="col-md-9">
                      <div class="d-flex" style="justify-content: space-between; align-items: center;">
                        <h5 class="modal-title">Customer Details</h5>


                        <a href="#" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#add-customer">
                          <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 5l0 14"></path>
                            <path d="M5 12l14 0"></path>
                          </svg>
                          Add New
                        </a>


                      </div>
                      <div class="card mt-3" id="residential-card">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-md-3">
                              <label class="mb-0" for=""> <b>Customer Name</b> </label>
                              <p class="m-0">Mr. Jhone Doe</p>
                            </div>
                            <div class="col-md-3">
                              <label class="mb-0" for=""><b>Contact No.</b> </label>
                              <p class="m-0">+91-2589631470</p>
                            </div>
                            <div class="col-md-3">
                              <label class="mb-0" for=""> <b>Email</b></label>
                              <p class="m-0">abc@gmail.com</p>
                            </div>
                            <div class="col-md-3">
                              <label class="mb-0" for=""> <b>Territory</b></label>
                              <p class="m-0">one</p>
                            </div>
                          </div>
                          <div class="row mt-3">
                  
                            
                            <div class="col-md-3">
                              <label class="mb-0" for=""><b>Status</b> </label>
                              <p><span class="badge bg-red">Pending</span></p>
                            </div>
                            <div class="col-md-3">
                              <label class="mb-0" for=""><b>Outstanding Amount</b> </label>
                              <p class="m-0">$ 2000</p>
                            </div>
                          </div>

                        </div>
                      </div>
                      <div class="card mt-3" id="commercial-card" style="display: none;">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-md-3 mb-3">
                              <label class="mb-0" for=""> <b>UEN</b></label>
                              <p class="m-0">123456</p>
                            </div>
                            <div class="col-md-3 mb-3">
                              <label class="mb-0" for=""> <b>Customer Name</b> </label>
                              <p class="m-0">ABC Group Of Companies</p>
                            </div>
                            <div class="col-md-3 mb-3">
                              <label class="mb-0" for=""><b>Contact No.</b> </label>
                              <p class="m-0">+91 9758697820</p>
                            </div>
                            <div class="col-md-3 mb-3">
                              <label class="mb-0" for=""> <b>Email</b></label>
                              <p class="m-0">abc@gmail.com</p>
                            </div>
                            <div class="col-md-3 mb-3">
                              <label class="mb-0" for=""> <b>Territory</b></label>
                              <p class="m-0">one</p>
                            </div>
                            <div class="col-md-3 ">
                              <label class="mb-0" for=""><b>Status</b> </label>
                              <p class="m-0"><span class="badge bg-red">Pending</span></p>
                            </div>
                            <div class="col-md-6">
                              <label class="mb-0" for=""><b>Outstanding Amount</b> </label>
                              <p class="m-0">$ 2000</p>
                            </div>
                            

                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
                  <div class="row">
                    <div class="col-md-4 ">
                      <div class="card">
                        <div class="card-body">


                          <div class="row">
                            <div class="col-md-12">
                              
                              <div class="mb-3">
                                <label class="form-label">Select Company<span class="text-danger">*</span></label>
                                <select type="text" class="form-select" value="">
                                  <option value="a">Aircon</option>
                                  <option value="b">part time</option>
                                  <option value="c">maid</option>
                                  <option value="d">carpet</option>
                                  <option value="e">absolute cleaning</option>
                                  <option value="f">auntie cleaner</option>
                                </select>
                              </div>
                           
                              <div class="">
                                <label class="form-label">Search By</label>
                                <div class="input-icon mb-3">
                                  <input type="text" value="" class="form-control" placeholder="Search…">
                                  <span class="input-icon-addon">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                      viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                      stroke-linecap="round" stroke-linejoin="round">
                                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                      <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                      <path d="M21 21l-6 -6"></path>
                                    </svg>
                                  </span>
                                </div>

                              </div>
                            </div>
                            <div class="col-md-12">
                              <ul class="nav nav-pills nav-pills-success mt-3" id="pills-tab" role="tablist"
                                style="border: none;">
                                <li class="nav-item me-3">
                                  <a class="nav-link" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home"
                                    role="tab" aria-controls="pills-home" aria-selected="true">Services</a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#pills-profile"
                                    role="tab" aria-controls="pills-profile" aria-selected="false">Packages</a>
                                </li>

                              </ul>
                              <div class="tab-content p-0" id="pills-tabContent" style="border: none;">
                                <div class="tab-pane fade" id="pills-home" role="tabpanel"
                                  aria-labelledby="pills-home-tab">
                                  <div class="mt-3">
                                    <div class="row" id="productsubcat">
                                      <div class="col-md-4 text-center">
                                        <button type="button" class="productsub btn btn-inverse-primary btn-sm">Floor
                                          Cleaning</button>

                                      </div>
                                      <div class="col-md-4 text-center">
                                        <button type="button" class="productsub btn btn-inverse-secondary btn-sm">Home
                                          Cleaning</button>

                                      </div>
                                      <div class="col-md-4 text-center">
                                        <button type="button" class="productsub btn btn-inverse-warning btn-sm">Office
                                          Cleaning</button>
                                      </div>
                                    </div>

                                    <div class="productsubshow mt-3" style="display: none;">

                                      <div class="table-responsive">
                                        <table
                                          class="table card-table table-vcenter text-center text-nowrap table-transparent">
                                          <thead>
                                            <tr>
                                              <th>SL NO</th>
                                            <!-- <th>Image</th> -->
                                            <th>Item</th>
                                            <th>Unit Price</th>

                                              <th>Action</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <tr>
                                              <td>1</td>
                                              <!-- <td><span class="avatar avatar-sm"
                                                  style="background-image: url(./static/avatars/000m.jpg)"></span></td> -->
                                              <td>Floor Cleaning</td>
                                              <td>$308.00</td>
                                              <td>
                                                <button class="btn btn-primary   ripple" type="button">
                                                  <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M12 5l0 14"></path>
                                                    <path d="M5 12l14 0"></path>
                                                  </svg>
                                                </button>
                                              </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </div>

                                    </div>

                                  </div>
                                </div>
                                <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                  aria-labelledby="pills-profile-tab">
                                  <div class="mt-3">
                                    <div class="row" id="packagesubcat">
                                      <div class="col-md-4">
                                        <button type="button" class="packagesub btn btn-inverse-primary btn-sm">Floor
                                          Cleaning</button>

                                      </div>
                                      <div class="col-md-4">
                                        <button type="button" class="packagesub btn btn-inverse-secondary btn-sm">Home
                                          Cleaning</button>

                                      </div>
                                      <div class="col-md-4">
                                        <button type="button" class="packagesub btn btn-inverse-warning btn-sm">Office
                                          Cleaning</button>
                                      </div>
                                    </div>

                                    <div class="table-responsive">
                                      <table
                                        class="table card-table table-vcenter text-center text-nowrap table-transparent">
                                        <thead>
                                          <tr>
                                            <th>SL NO</th>
                                            <!-- <th>Image</th> -->
                                            <th>Item</th>
                                            <th>Unit Price</th>

                                            <th>Action</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>1</td>
                                            <!-- <td><span class="avatar avatar-sm"
                                                style="background-image: url(./static/avatars/000m.jpg)"></span></td> -->
                                            <td>Floor Cleaning</td>
                                            <td>$308.00</td>
                                            <td>
                                              <button class="btn btn-primary ripple" type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24"
                                                  height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                  fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                  <path d="M12 5l0 14"></path>
                                                  <path d="M5 12l14 0"></path>
                                                </svg>
                                              </button>
                                            </td>
                                          </tr>
                                        </tbody>
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
                    <div class="col-md-8 pe-0">
                      <div id="service-table">
                        <div class="card">
                          <div class="card-body p-0">
                            <div class="table-responsive">
                              <table class="table card-table table-vcenter text-center text-nowrap" id=""
                                style="width:100%">
                                <thead>
                                  <tr>
                                    <th>SL NO</th>
                                    <th>Item</th>
                                    <th>Unit Price</th>
                                    <th>Qty</th>
                                    <th>Discount (%)</th>
                                    <th>Gross Amt ($)</th>
                                    <th>Tax</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>1</td>
                                    <td>Floor Cleaning</td>
                                    
                                    <td><input type="number" class="form-control"></td>
                                    <td class="p-0"><input type="number" class="form-control"></td>
                                    <td>5%</td>
                                    <td>$543</td>
                                    <td>18%</td>
                              
                                    <td>
                                      <button class="btn btn-danger ripple" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                          class="icon icon-tabler icon-tabler-playstation-x m-0" width="24" height="24"
                                          viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                          stroke-linecap="round" stroke-linejoin="round">
                                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                          <path d="M12 21a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9a9 9 0 0 0 -9 9a9 9 0 0 0 9 9z">
                                          </path>
                                          <path d="M8.5 8.5l7 7"></path>
                                          <path d="M8.5 15.5l7 -7"></path>
                                        </svg>
                                      </button>
                                    </td>
                                  </tr>

                                </tbody>
                                <thead>
                                  <tr>
                                    <th colspan="7" style="text-align: end;">Total discount </th>
                                    <th colspan="2">5%</th>
                                  </tr>
                                  <tr>
                                    <th colspan="7" style="text-align: end;">Total tax </th>
                                    <th colspan="2">18%</th>
                                  </tr>
                                  <tr>
                                    <th colspan="7" style="text-align: end;">Grand total</th>
                                    <th colspan="2">$ 616.00</th>
                                  </tr>
                                </thead>
                                <thead id="package-total" style="display: none;">
                                  <tr>
                                    <th colspan="7" style="text-align: end;">Package Amount</th>
                                    <th colspan="2"><input type="text" class="form-control"></th>
                                  </tr>
                                </thead>
                              </table>
                            </div>

                          </div>
                        </div>
                      </div>
                      <div id="package-table" style="display: none;">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="mb-3">
                              <label class="form-label">Package Name</label>

                              <input type="text" value="" class="form-control w-50" placeholder="Enter Package Name">



                            </div>
                          </div>
                        </div>
                        <div class="card">
                          <div class="card-body p-0">


                            <div class="table-responsive">

                              <table class="table card-table table-vcenter text-center text-nowrap" id=""
                                style="width:100%">
                                <thead>
                                  <tr>
                                    <th>SL NO</th>
                                    <th>Item</th>
                                    <th>Item Discription</th>
                                    <th>Categoery</th>
                                    <th>Unit Price</th>
                                    <th>Qty</th>
                                    <th>Discount (%)</th>
                                    <th>Gross Amt ($)</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>1</td>
                                    <td>Floor Cleaning</td>
                                    <td>Floor-1</td>
                                    <td><textarea class="form-control" name="example-textarea-input" rows="3"
                                        placeholder="Enter Descrption">

                                              </textarea></td>
                                    <td><input type="number" class="form-control"></td>
                                    <td class="p-0"><input type="number" class="form-control"></td>
                                    <td>0</td>
                                    <td>$308.00</td>

                                    <td>
                                      <button class="btn btn-danger ripple" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                          class="icon icon-tabler icon-tabler-playstation-x m-0" width="24" height="24"
                                          viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                          stroke-linecap="round" stroke-linejoin="round">
                                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                          <path d="M12 21a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9a9 9 0 0 0 -9 9a9 9 0 0 0 9 9z">
                                          </path>
                                          <path d="M8.5 8.5l7 7"></path>
                                          <path d="M8.5 15.5l7 -7"></path>
                                        </svg>
                                      </button>
                                    </td>
                                  </tr>

                                </tbody>
                                <thead>
                                  <tr>
                                    <th colspan="7" style="text-align: end;">TOTAL DISCOUNT</th>
                                    <th colspan="2">$ 616.00</th>
                                  </tr>
                                  <tr>
                                    <th colspan="7" style="text-align: end;">Total tax </th>
                                    <th colspan="2">18%</th>
                                  </tr>
                                </thead>
                                <thead>
                                  <tr>
                                    <th colspan="7" style="text-align: end;">Package Amount</th>
                                    <th colspan="2"><input type="text" class="form-control"></th>
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
                <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="card card-link card-link-pop">
                        <div class="card-status-start bg-primary"></div>
                        <div class="card-stamp">
                          <div class="card-stamp-icon bg-white text-primary">
                            <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                              viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                              stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                              <path
                                d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                              </path>
                            </svg>
                          </div>
                        </div>
                        <div class="card-body">
                          <h3 class="card-title " style="color: #1F3BB3;"><b class="me-2">ABC Pvt.
                              Lte.</b><span class="badge bg-red">Residential</span>
                          </h3>

                          <p class="card-p d-flex align-items-center mb-2 ">
                            <i class="fa-solid fa-phone me-2" style="font-size: 14px;"></i>+91
                            9758697820
                          </p>
                          <p class="card-p  d-flex align-items-center mb-2">
                            <i class="fa-solid fa-envelope me-2" style="font-size: 14px;"></i>abc@pvtltd.com
                          </p>
                          <!-- <p class="card-p d-flex mb-2">
                                  <i class="fa-solid fa-map-pin me-2 pt-1" style="font-size: 14px;"></i>103
                                  Rasadhi
                                  Appartment Wadaj Ahmedabad 380004.
      
                                </p> -->

                          <hr class="my-3">
                          <h3 class="card-title mb-1" style="color: #1F3BB3;">

                            <b>Service Details</b>
                          </h3>
                          <div class="amount">
                            <p class="m-0 card-p">Floor Cleaning(5)</p>
                            <p class="m-0 card-p">Home Cleaning(2)</p>


                          </div>
                          <hr class="my-3">
                          <div class="driver mt-2">
                            <h3 class="card-title mb-1" style="color: #1F3BB3;">

                              <b>Amount Details</b>
                            </h3>
                            <div class="row">
                              <div class="col-md-7">
                                <p class="m-0"> Total:</p>
                              </div>
                              <div class="col-md-5">
                                <p class="m-0">$200.00</p>
                              </div>
                            </div>
                          </div>



                        </div>
                      </div>
                    </div>
                    <div class="col-md-9">
                      <div class="card">
                        <div class="card-body">
                          <ul class="nav nav-pills nav-pills-primary" data-bs-toggle="tabs" role="tablist">
                            <li class="nav-item me-2" role="presentation">
                              <a href="#tab-one" class="nav-link active" data-bs-toggle="tab" aria-selected="true"
                                role="tab">Service Address</a>
                            </li>
                            <li class="nav-item me-2" role="presentation">
                              <a href="#tab-two" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab"
                                tabindex="-1">Billing
                                Address</a>
                            </li>
                            <li class="nav-item me-2" role="presentation">
                              <a href="#tab-three" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                                role="tab" tabindex="-1">Additional Info</a>
                            </li>


                          </ul>
                          <div class="tab-content">
                            <div class="tab-pane active show" id="tab-one" role="tabpanel">
                              <div class="row my-3">
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                  <label for="radio-card-1" class="radio-card">
                                    <input type="radio" name="radio-card" id="radio-card-1" checked />
                                    <div class="card-content-wrapper">
                                      <span class="check-icon"></span>
                                      <div class="card-content">
                                        <h4>Sky Enterprice</h4>
                                        <p class="mb-1"> <strong>Contact No:</strong>1234567890</p>
                                        <p class="mb-1"> <strong>Email ID:</strong>ABC@gmail.com</p>

                                        <p class="mb-1"><strong>Address:</strong>8 Shopping Centre, 9 Bishan Place,
                                          Singapore 579837
                                        </p>
                                        <p class="mb-1"><strong>Unit No:</strong>12345h</p>
                                        <p class="mb-1"><strong>Zone:</strong>South</p>
                                        <div class="form-check">
                                          <input class="form-check-input" type="radio" name="flexRadioDefault"
                                            id="flexRadioDefault2" checked>
                                          <label class="form-check-label" for="flexRadioDefault2">
                                            Default Address
                                          </label>
                                        </div>
                                      </div>
                                    </div>
                                  </label>

                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12 my-3">
                                  <button type="button" class="btn btn-blue add_btn">+ Add Address</button>
                                </div>
                                <div class="col-md-12 add_address" style="display: none;">



                                  <div class="row my-3">
                                    <div class="col-md-4">
                                      <div class="form-group mb-3">
                                        <label for="name">Person Incharge Name</label>

                                        <input type="text" placeholder="Enter Name" name="name" class="form-control"
                                          required="">
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group mb-3">
                                        <label for="name">Contact No</label>
                                        <input type="text" placeholder="Enter Number" name="name" class="form-control"
                                          required="">
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group mb-3">
                                        <label for="name">Email Id</label>
                                        <input type="text" placeholder="Enter Email" name="name" class="form-control"
                                          required="">
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group mb-3">
                                        <label for="name">Postal Code</label>
                                        <input type="text" placeholder="Enter Code" name="name" class="form-control"
                                          required="">
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group mb-3">
                                        <label for="name">Zone</label>
                                        <input type="text" placeholder="Enter Zone" name="name" class="form-control"
                                          required="">
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group mb-3">
                                        <label for="name">Address</label>
                                        <input type="text" placeholder="Enter Address" name="name" class="form-control"
                                          required="">
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group mb-3">
                                        <label for="name">Country</label>
                                        <select type="text" class="form-select" value="">
                                          <option value="11">India</option>
                                          <option value="39">Singapore</option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group mb-3">
                                        <label for="name">Unit No</label>
                                        <input type="text" placeholder="Enter Unit No." name="name" class="form-control"
                                          required="">
                                      </div>
                                    </div>
                                    <div class="col-md-4 my-auto">

                                      <button type="button" class="btn btn-blue add-row" id="rowAdder">+</button>
                                    </div>

                                    <!-- <div class="col-md-1" style="display: flex; align-items: center;">

                                            <button type="button" class="btn btn-danger" id="DeleteRow">-</button>
                                          </div> -->
                                  </div>
                                  <div id="newinput"></div>
                                  <div class="row">
                                    <div class="col-md-12">
                                      <button type="button" class="btn btn-primary">save</button>
                                    </div>
                                  </div>
                                </div>

                              </div>


                            </div>
                            <div class="tab-pane" id="tab-two" role="tabpanel">
                              <div class="row my-3">
                                <div class="col-md-12">
                                  <div class="my-3">
                                    <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input">
                                      Same as Service Address
                                      <i class="input-helper"></i></label>
                                  </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                  <label for="radio-card-222333" class="radio-card">
                                    <input type="radio" name="radio-card" id="radio-card-222333" checked />
                                    <div class="card-content-wrapper">
                                      <span class="check-icon"></span>
                                      <div class="card-content">
                                        <h4>Jhone Doe</h4>
                                        <p class="mb-1"><strong>Address:</strong>8 Shopping Centre, 9 Bishan Place,
                                          Singapore 579837
                                        </p>
                                        <p class="mb-1"><strong>Unit No:</strong>12345h</p>
                                        <div class="form-check">
                                          <input class="form-check-input" type="radio" name="flexRadioDefault"
                                            id="flexRadioDefault22" checked>
                                          <label class="form-check-label" for="flexRadioDefault22">
                                            Default Address
                                          </label>
                                        </div>
                                      </div>
                                    </div>
                                  </label>

                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12 my-3">
                                  <button type="button" class="btn btn-blue add_btn_2">+ Add Address</button>
                                </div>
                                <!-- <div class="col-md-12">
                                          <div class="my-3">
                                              <label class="form-check-label">
                                                  <input type="checkbox" class="form-check-input">
                                                  Same as Service Address
                                                  <i class="input-helper"></i></label>
                                          </div>
                                          <div class="mb-3">
                                              <label class="form-label">Zone</label>
                                              <input type="text" class="form-control w-50"
                                                  name="example-text-input" placeholder="Zone">
                                          </div>
                                      </div> -->

                                <div class="col-md-12 add_address_2" style="display: none;">

                                  <div class="table-responsive mb-3">
                                    <table class="table card-table table-vcenter text-nowrap table-transparent"
                                      id="billing_address_add_lead">
                                      <thead>
                                        <tr>
                                          <th>Postal Code</th>
                                          <th>Address</th>
                                          <th>Country</th>
                                          <th>
                                            <button type="button" class="btn btn-blue add-row">+</button>

                                          </th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <td>
                                            <input class="form-control" type="text" placeholder="Enter Code" />
                                          </td>
                                          <td>
                                            <input class="form-control" type="text" placeholder="Address" />
                                          </td>
                                          <td>
                                            <select type="text" class="form-select" value="">
                                              <option value="111">Singaore</option>
                                              <option value="329">India</option>
                                            </select>
                                          </td>
                                          <td>
                                            <button type="button" class="btn btn-danger delete-row">-</button>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>

                                  <div class="text-end">
                                    <button type="button" class="btn btn-blue">save</button>
                                  </div>
                                </div>

                              </div>
                            </div>
                            <div class="tab-pane" id="tab-three" role="tabpanel">
                              <div class="row mt-3">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 text-start">
                                  <label for="message-text" class="col-form-label">Deposite Type</label>
                                  <select class="form-control">
                                    <option>Select Option</option>
                                    <option>$50</option>
                                    <option>waive</option>
                                    <option>Don’t need</option>
                                  </select>
                                </div>


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 text-start">
                                  <label for="message-text" class="col-form-label">Date Of Cleaning</label>
                                  <input class="form-control" placeholder="dd/mm/yyyy">
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 text-start">
                                  <label for="message-text" class="col-form-label">Time of Cleaning</label>
                                  <input type="text" class="form-control" placeholder="Time of Cleaning">
                                </div>


                              </div>
                            </div>
                          </div>

                        </div>

                      </div>

                    </div>
                  </div>

                </div>


                <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="card card-link card-link-pop">
                        <div class="card-status-start bg-primary"></div>
                        <div class="card-stamp">
                          <div class="card-stamp-icon bg-white text-primary">
                            <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-pin"
                              width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                              fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                              <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                              <path
                                d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                              </path>
                            </svg>
                          </div>
                        </div>
                        <div class="card-body">

                          <h3 class="card-title mb-1" style="color: #1F3BB3;">

                            <b>Service Address</b>
                          </h3>


                          <p class="m-0">BLK 3017 BEDOK NORTH STREET 5
                            #01-22 GOURMET EAST KITCHEN
                            SINGAPORE 486121</p>
                          <hr class="my-3">
                          <h3 class="card-title mb-1" style="color: #1F3BB3;">

                            <b>Billing Address</b>
                          </h3>


                          <p class="m-0">BLK 3017 BEDOK NORTH STREET 5
                            #01-22 GOURMET EAST KITCHEN
                            SINGAPORE 486121</p>
                          <hr class="my-3">
                          <h3 class="card-title mb-1" style="color: #1F3BB3;">

                            <b>Cleaning Details</b>
                          </h3>


                          <p class="m-0">Deposite Type : Wave</p>
                          <p class="m-0">Date Of Cleaning : 25/04/2023</p>
                          <p class="m-0">Time Of Cleaning : 05:47 PM</p>


                        </div>
                      </div>
                    </div>
                    <div class="col-md-9">
                      <div class="row">
                        <div class="col-md-8">
                          <div class="card">
                            <div class="card-body p-0">
                              <div class="table-responsive">
                                <table class="table card-table table-vcenter text-center text-nowrap datatable">
                                  <thead>
                                    <tr>
                                      <th>SL NO</th>
                                      <th>Item</th>
                                      <th>Unit Price</th>
                                      <th>Quantity</th>
                                      <th>Gross Amount ($)</th>
                                      <th>Discount (%)</th>
                                     

                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>1</td>
                                      <td>Floor Cleaning</td>
                                      
                                      <td>$308.00</td>
                                      <td>2</td>
                                      
                                      <td>$308.00</td>
                                      <td>8%</td>
                                    </tr>

                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>

                        </div>
                        <div class="col-md-4">
                          <div class="card card-link card-link-pop">
                            <div class="card-status-start bg-primary"></div>
                            <div class="card-stamp">
                              <div class="card-stamp-icon bg-white text-primary">
                                <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                  viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                  stroke-linecap="round" stroke-linejoin="round">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                  <path
                                    d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                  </path>
                                </svg>
                              </div>
                            </div>
                            <div class="card-body">
                              <h3 class="card-title mb-1" style="color: #1F3BB3;"><b class="me-2">Customer Details</b>
                              </h3>

                              <p class="m-0">
                                <i class="fa-solid fa-user me-2 pt-1" style="font-size: 14px;"></i>
                                Jhone Doe
                              </p>
                              <p class="m-0">
                                <i class="fa-solid fa-phone me-2 pt-1" style="font-size: 14px;"></i>
                                +91-9737155901
                              </p>
                              <!-- <p class="m-0">
                                      <i class="fa-solid fa-map-pin me-2 pt-1" style="font-size: 14px;"></i>
                                      103 Rasadhi Appartment Wadaj Ahmedabad
                                      380004.
                                    </p> -->

                              <hr class="my-3">
                              <h3 class="card-title mb-1" style="color: #1F3BB3;"><b class="me-2">Amount Details</b>
                              </h3>

                              <div class="row">
                                <div class="col-md-7">
                                  <p class="m-0">Total (before tax):</p>
                                  <p class="m-0">Total Tax:</p>
                                  <p class="m-0">Total Discount:</p>
                                  <h3>Grand Total:</h6>
                                </div>
                                <div class="col-md-5">
                                  <p class="m-0">$200.00</p>
                                  <p class="m-0">$0.00</p>
                                  <p class="m-0">$0.00</p>
                                  <h3>$200.00</h6>
                                </div>
                              </div>
                              <button type="button" class="btn btn-info w-100 mt-3"
                                data-dismiss="modal">Confirm</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>

              <!-- Include optional progressbar HTML -->
              <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                  aria-valuemax="100"></div>
              </div>
            </div>

          </form>

        </div>
       
            <script>
    $('#smartwizard').smartWizard({
      transition: {
        animation: 'slideHorizontal', 
      }
    });
  
  </script>
       
<script>
function Search(type) {
    var search;
    var searchBox;
   
    if (type == '1') {
        searchBox = $('#residential_list');
        search = $('#residential').val();
    } else {
        searchBox = $('#commercial_list');
        search = $('#commercial').val();
    }
    if (!search.trim()) {
        searchBox.empty().hide();
        return;
    }
   
    $.ajax({
        url: '{{ route('quotataion.customer.search') }}',
        type: 'POST',
        data: {
            type: type,
            search: search,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            $('#residential_Search').empty().hide();
            $('#commercialList').empty().hide();
   
            response.forEach(function (item) {
                if (item.customer_type === 'residential_customer_type') {
                    // Append residential search result
                    var residentialList = `
                        <a type="button" style="display: block;" class="mb-3" onclick="displayCustomerDetails(${item.id}, 'residential_customer_type')">
                            <div class="card card-active">
                                <div class="card">
                                    <div class="ribbon bg-yellow">Residential</div>
                                    <div class="card-body d-flex justify-content-between">
                                        <div class="my-auto">
                                            <label class="mb-0 text-black fw-bold" style="font-size: 14px">${item.customer_name}</label>
                                            <p class="m-0">Tel - +${item.mobile_number}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>`;
                    searchBox.append(residentialList);
                } else {
                    // Append commercial search result
                    var commercialList = `
                        <a type="button" style="display: block;" class="mb-3" onclick="displayCustomerDetails(${item.id}, 'commercial_customer_type')">
                            <div class="card card-active">
                                <div class="card">
                                    <div class="ribbon bg-red">Commercial</div>
                                    <div class="card-body d-flex justify-content-between">
                                        <div class="my-auto">
                                            <label class="mb-0 text-black fw-bold" style="font-size: 14px">${item.customer_name}</label>
                                            <p class="m-0">Tel - +${item.mobile_number}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>`;
                    searchBox.append(commercialList);
                }
            });
            searchBox.show();
        },
    });
}
</script>

   