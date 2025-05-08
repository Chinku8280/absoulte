


 @extends('theme.default')
@section('content')

   
        <!-- Sidebar -->
    
      
        <div class="page-wrapper">
            <!-- Page header -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <h2 class="page-title">
                                Report
                            </h2>


                        </div>
                        <!-- <div class="col-auto ms-auto d-print-none">
                            <a href="#" class="btn btn-primary" >
                            
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Add New
                        </a>
                        </div> -->
                
        
                    </div>
                </div>
            </div>
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                               
                                <div class="card-body border-bottom py-3">
                                  <div class="d-flex">
                                    <div class="text-muted">
                                      Show
                                      <div class="mx-2 d-inline-block">
                                        <input type="text" class="form-control form-control-sm" value="8" size="3" aria-label="Invoices count">
                                      </div>
                                      entries
                                    </div>
                                    <div class="ms-auto text-muted">
                                      Search:
                                      <div class="ms-2 d-inline-block">
                                        <input type="text" class="form-control form-control-sm" aria-label="Search invoice">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="table-responsive">
                                  <table class="table table-bordered card-table table-vcenter text-center text-nowrap datatable">
                                    <thead>
                                        <tr>
                                            <th class="w-1">Date</th>
                                            <th>Day</th>
                                            
                                            <th>AM/PM</th>
                                            <th>Time</th>
                                            <th>Hr</th>
                                            <th>Job</th>
                                            <th>Address</th>
                                            <th>Customer</th>
                                            <th>Remark</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td rowspan="2">15 May</td>
                                        <td rowspan="2">Mon</td>
                                        <td>AM</td>
                                        <td>
                                          
                                        </td>
                                        <td></td>
                                        <td>
                                          
                                        </td>
                                        <td>
                                         
                                        </td>
                                        <td></td>
                                        <td>
                                           
                                        </td>
                                    
                                       
                                      </tr>
                                      <tr>
                                        <td>PM</td>
                                        <td>
                                          2:30 AM- 6:30 PM
                                        </td>
                                        <td>4</td>
                                        <td>
                                          House
                                        </td>
                                        <td>
                                          The Coast @ Sentosa Cove ,276 Ocean Drive #06-32 S098449(Press 06-32 inside the lift)
                                        </td>
                                        <td>Ms Sharmila</td>
                                        <td>
                                            <span class="badge bg-red">Replacement</span>
                                        </td>
                                      </tr>
                                    
                                    </tbody>
                                  </table>
                                </div>
                                <div class="card-footer d-flex align-items-center">
                                  <p class="m-0 text-muted">Showing <span>1</span> to <span>8</span> of <span>16</span> entries</p>
                                  <ul class="pagination m-0 ms-auto">
                                    <li class="page-item disabled">
                                      <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M15 6l-6 6l6 6"></path></svg>
                                        prev
                                      </a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item active"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                                    <li class="page-item">
                                      <a class="page-link" href="#">
                                        next <!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 6l6 6l-6 6"></path></svg>
                                      </a>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                      

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal modal-blur fade" id="confirm-quotation" tabindex="-1" style="display: none;" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Confirm Sales Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div id="smartwizard-3" style="border: none;" dir="" class="sw sw-theme-basic sw-justified">
                      <ul class="nav d-none" style="">
                        
                        <li class="nav-item">
                          <a class="nav-link default" href="#update-step-22">
                            <span class="num">2</span>
                            2
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link default" href="#update-step-33">
                            <span class="num">3</span>
                            3
                          </a>
                        </li>
          
                      </ul>
          
                      <div class="tab-content p-0" style="border: none; height: 260px;">
                        
                        <div id="update-step-22" class="tab-pane" role="tabpanel" aria-labelledby="update-step-22"
                          style="display: none;">
                          <div class="row">
                            <div class="mb-3">
                              <label class="form-label">Select Email Template</label>
                              <div class="row g-2">
                                <div class="col-6 col-sm-4">
                                  <label class="form-imagecheck mb-2">
                                    <input name="form-imagecheck-radio" type="radio" value="1" class="form-imagecheck-input">
                                    <span class="form-imagecheck-figure">
                                      <img src="./dist/img/main-template.jpeg" alt="Group of people sightseeing in the city"
                                        class="form-imagecheck-image">
                                    </span>
                                  </label>
                                </div>
                                <div class="col-6 col-sm-4">
                                  <label class="form-imagecheck mb-2">
                                    <input name="form-imagecheck-radio" type="radio" value="2" class="form-imagecheck-input"
                                      checked="">
                                    <span class="form-imagecheck-figure">
                                      <img src="./dist/img/main-template.jpeg" alt="Color Palette Guide. Sample Colors Catalog."
                                        class="form-imagecheck-image">
                                    </span>
                                  </label>
                                </div>
                                <div class="col-6 col-sm-4">
                                  <label class="form-imagecheck mb-2">
                                    <input name="form-imagecheck-radio" type="radio" value="3" class="form-imagecheck-input">
                                    <span class="form-imagecheck-figure">
                                      <img src="./dist/img/main-template.jpeg" alt="Stylish workplace with computer at home"
                                        class="form-imagecheck-image">
                                    </span>
                                  </label>
                                </div>
                                <div class="col-6 col-sm-4">
                                  <label class="form-imagecheck mb-2">
                                    <input name="form-imagecheck-radio" type="radio" value="4" class="form-imagecheck-input"
                                      checked="">
                                    <span class="form-imagecheck-figure">
                                      <img src="./dist/img/main-template.jpeg" alt="Pink desk in the home office"
                                        class="form-imagecheck-image">
                                    </span>
                                  </label>
                                </div>
                                <div class="col-6 col-sm-4">
                                  <label class="form-imagecheck mb-2">
                                    <input name="form-imagecheck-radio" type="radio" value="5" class="form-imagecheck-input">
                                    <span class="form-imagecheck-figure">
                                      <img src="./dist/img/main-template.jpeg"
                                        alt="Young woman sitting on the sofa and working on her laptop"
                                        class="form-imagecheck-image">
                                    </span>
                                  </label>
                                </div>
                                <div class="col-6 col-sm-4">
                                  <label class="form-imagecheck mb-2">
                                    <input name="form-imagecheck-radio" type="radio" value="6" class="form-imagecheck-input">
                                    <span class="form-imagecheck-figure">
                                      <img src="./dist/img/main-template.jpeg" alt="Coffee on a table with other items"
                                        class="form-imagecheck-image">
                                    </span>
                                  </label>
                                </div>
                              </div>
                            </div>
          
                          </div>
                        </div>
                        <div id="update-step-33" class="tab-pane" role="tabpanel" aria-labelledby="update-step-33" style="display: none;">
                          <div class="row">
                            <div class="col-md-12">
                              <form class="form-horizontal" role="form">
                                <div class="mb-3">
                                  <label class="form-label">To:</label>
                                  <input type="text" class="form-control" name="example-text-input" placeholder="Type email">
                                </div>
                                <div class="mb-3">
                                  <label class="form-label">CC:</label>
                                  <input type="text" class="form-control" name="example-text-input" placeholder="Type email">
                                </div>
                                <div class="mb-3">
                                  <label class="form-label">BCC:</label>
                                  <input type="text" class="form-control" name="example-text-input" placeholder="Type email">
                                </div>
                                <div class="mb-3">
                                  <div class="btn-toolbar " role="toolbar">
                                    <div class="btn-group mb-3">
                                      <button class="btn btn-default">
                                        <span class="fa fa-bold"></span>
                                      </button>
                                      <button class="btn btn-default">
                                        <span class="fa fa-italic"></span>
                                      </button>
                                      <button class="btn btn-default">
                                        <span class="fa fa-underline"></span>
                                      </button>
                                    </div>
                                    <div class="btn-group ms-3 mb-3">
                                      <button class="btn btn-default">
                                        <span class="fa fa-align-left"></span>
                                      </button>
                                      <button class="btn btn-default">
                                        <span class="fa fa-align-right"></span>
                                      </button>
                                      <button class="btn btn-default">
                                        <span class="fa fa-align-center"></span>
                                      </button>
                                      <button class="btn btn-default">
                                        <span class="fa fa-align-justify"></span>
                                      </button>
                                    </div>
                                    <div class="btn-group ms-3 mb-3">
                                      <button class="btn btn-default">
                                        <span class="fa fa-indent"></span>
                                      </button>
                                      <button class="btn btn-default">
                                        <span class="fa fa-outdent"></span>
                                      </button>
                                    </div>
                                    <div class="btn-group">
                                      <button class="btn btn-default">
                                        <span class="fa fa-list-ul"></span>
                                      </button>
                                      <button class="btn btn-default">
                                        <span class="fa fa-list-ol"></span>
                                      </button>
                                    </div>
                                    <div class="btn-group ms-3">
                                      <button class="btn btn-default">
                                        <span class="fa fa-trash-can"></span>
                                      </button>
                                      <button class="btn btn-default">
                                        <span class="fa fa-paperclip"></span>
                                      </button>
                                    
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="mb-3">
                              <textarea class="form-control" name="example-textarea-input" rows="6" placeholder="Content..">Oh! Come and see the violence inherent in the system! Help, help, I'm being repressed! We shall say 'Ni' again to you, if you do not appease us. I'm not a witch. I'm not a witch. Camelot!</textarea>
                            </div>
                            <div class="email-attachment">
                              <div class="file-info">
                                <div class="file-size">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-paperclip">
                                    <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                                  </svg>
                                  <span>Attachment (2 MB)</span>
                                </div>
                                <button class="btn btn-sm btn-outline-primary me-2">View All</button>
                                <button class="btn btn-sm btn-outline-success">Download All</button>
                              </div>
                             
                              <ul class="attachment-list">
                                <li class="attachment-list-item">
                                  <img src="./dist/img/template.jpg"" alt="Showcase" title="Showcase">
                                </li>
                                <li class="attachment-list-item">
                                  <img src="./dist/img/main-template.jpeg" alt="Showcase" title="Showcase">
                                </li>
                                
                               
                              </ul>
                            </div>
                            </form>
                          </div>
                          <div class="col-md-12 text-end">
                            <button type="button" class="btn btn-info">Confirm</button>
                          </div>
                        </div>
                        </div>
          
                      </div>
                      <div class="sw-toolbar-elm justify-content-between toolbar toolbar-bottom" role="toolbar"><button
                          class="btn sw-btn-prev disabled" type="button">Previous</button><button class="btn btn-primary"
                          type="button">Next</button></div>
          
                      <!-- Include optional progressbar HTML -->
                      <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                          aria-valuemax="100"></div>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
                  </div> -->
                </div>
              </div>
            </div>
          
        </div>

    




@endsection