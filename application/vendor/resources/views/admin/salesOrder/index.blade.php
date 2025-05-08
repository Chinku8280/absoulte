 @extends('theme.default')
 <style>
  .dropdown-menu.show {
      display: block !important;
      position: absolute !important;
      inset: 0px 0px auto auto !important;
      transform: translate(0px, 39px) !important;
  }
</style>
 @section('content')
     <div class="page-wrapper">
         <!-- Page header -->
         <div class="page-header d-print-none">
             <div class="container-xl">
                 <div class="row g-2 align-items-center">
                     <div class="col">
                         <h2 class="page-title">
                             Sales Order
                         </h2>


                     </div>



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
                                             <input type="text" class="form-control form-control-sm" value="8"
                                                 size="3" aria-label="Invoices count">
                                         </div>
                                         entries
                                     </div>
                                     <div class="ms-auto text-muted">
                                         Search:
                                         <div class="ms-2 d-inline-block">
                                             <input type="text" class="form-control form-control-sm"
                                                 aria-label="Search invoice">
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             <div class="">
                                 <table class="table card-table table-vcenter text-center text-nowrap datatable">
                                     <thead>
                                         <tr>
                                             <th class="w-1">Sr No.</th>
                                             <th>sales order No.</th>

                                             <th>Customer Name</th>
                                             <th>Email</th>
                                             <th>Contact Number</th>
                                             <th>Created on</th>
                                             <th>Status</th>
                                             <th></th>

                                         </tr>
                                     </thead>
                                     <tbody>
                                         @foreach ($quotation as $item)
                                             <tr>
                                                 <td>01</td>
                                                 <td><span class="text-muted">1401</span></td>

                                                 <td><a href="#" class="text-reset" tabindex="-1"
                                                         data-bs-toggle="modal"
                                                         data-bs-target="#view-sales-order">{{ $item->customer_name }}</a>
                                                 </td>
                                                 <td>
                                                     {{ $item->email }}
                                                 </td>
                                                 <td>
                                                     +91-{{ $item->mobile_number }}
                                                 </td>
                                                 <td>
                                                     {{ $item->created_at->format('d,M,Y') }}
                                                 </td>
                                                 <td>
                                                     <span class="badge bg-red">Pending</span>
                                                 </td>

                                                 <td class="text-end">
                                                     <div class="dropdown">
                                                         <button class="btn dropdown-toggle align-text-top t-btn"
                                                             data-bs-toggle="dropdown" aria-expanded="false">
                                                             Actions
                                                         </button>
                                                         <div class="dropdown-menu dropdown-menu-end d-menu" style="">

                                                             <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                                 data-bs-target="#edit-sales-order">
                                                                 <i class="fa-solid fa-pencil me-2 text-yellow"></i> Edit
                                                             </a>
                                                             <a class="dropdown-item border-bottom" href="#">
                                                                 <i class="fa-solid fa-paper-plane me-2 text-red"></i> Send
                                                                 Deposite Link
                                                             </a>
                                                             <!-- <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#send-quotation">
                                                    <i class="fa-solid fa-envelope me-2 text-info"></i> Send Quotation
                                                  </a> -->
                                                             <a class="dropdown-item" href="#">
                                                                 <i class="fa-solid fa-circle-check me-2 text-green"></i>
                                                                 Assigning Cleaners
                                                             </a>
                                                         </div>
                                                     </div>

                                                 </td>
                                             </tr>
                                         @endforeach

                                     </tbody>
                                 </table>
                             </div>
                             <div class="card-footer d-flex align-items-center">
                                 <p class="m-0 text-muted">Showing <span>1</span> to <span>8</span> of <span>16</span>
                                     entries</p>
                                 <ul class="pagination m-0 ms-auto">
                                     <li class="page-item disabled">
                                         <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                             <!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                                             <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                 height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                 fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                 <path d="M15 6l-6 6l6 6"></path>
                                             </svg>
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
                                             <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                 height="24" viewBox="0 0 24 24" stroke-width="2"
                                                 stroke="currentColor" fill="none" stroke-linecap="round"
                                                 stroke-linejoin="round">
                                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                 <path d="M9 6l6 6l-6 6"></path>
                                             </svg>
                                         </a>
                                     </li>
                                 </ul>
                             </div>
                         </div>


                     </div>
                 </div>
             </div>
         </div>
         <div class="modal modal-blur fade" id="confirm-quotation" tabindex="-1" style="display: none;"
             aria-hidden="true">
             <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                 <div class="modal-content">
                     <div class="modal-header">
                         <h5 class="modal-title">Confirm Sales Order</h5>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="modal-body">
                         <div id="smartwizard-3" style="border: none;" dir=""
                             class="sw sw-theme-basic sw-justified">
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

                                 <div id="update-step-22" class="tab-pane" role="tabpanel"
                                     aria-labelledby="update-step-22" style="display: none;">
                                     <div class="row">
                                         <div class="mb-3">
                                             <label class="form-label">Select Email Template</label>
                                             <div class="row g-2">
                                                 <div class="col-6 col-sm-4">
                                                     <label class="form-imagecheck mb-2">
                                                         <input name="form-imagecheck-radio" type="radio"
                                                             value="1" class="form-imagecheck-input">
                                                         <span class="form-imagecheck-figure">
                                                             <img src="./dist/img/main-template.jpeg"
                                                                 alt="Group of people sightseeing in the city"
                                                                 class="form-imagecheck-image">
                                                         </span>
                                                     </label>
                                                 </div>
                                                 <div class="col-6 col-sm-4">
                                                     <label class="form-imagecheck mb-2">
                                                         <input name="form-imagecheck-radio" type="radio"
                                                             value="2" class="form-imagecheck-input" checked="">
                                                         <span class="form-imagecheck-figure">
                                                             <img src="./dist/img/main-template.jpeg"
                                                                 alt="Color Palette Guide. Sample Colors Catalog."
                                                                 class="form-imagecheck-image">
                                                         </span>
                                                     </label>
                                                 </div>
                                                 <div class="col-6 col-sm-4">
                                                     <label class="form-imagecheck mb-2">
                                                         <input name="form-imagecheck-radio" type="radio"
                                                             value="3" class="form-imagecheck-input">
                                                         <span class="form-imagecheck-figure">
                                                             <img src="./dist/img/main-template.jpeg"
                                                                 alt="Stylish workplace with computer at home"
                                                                 class="form-imagecheck-image">
                                                         </span>
                                                     </label>
                                                 </div>
                                                 <div class="col-6 col-sm-4">
                                                     <label class="form-imagecheck mb-2">
                                                         <input name="form-imagecheck-radio" type="radio"
                                                             value="4" class="form-imagecheck-input" checked="">
                                                         <span class="form-imagecheck-figure">
                                                             <img src="./dist/img/main-template.jpeg"
                                                                 alt="Pink desk in the home office"
                                                                 class="form-imagecheck-image">
                                                         </span>
                                                     </label>
                                                 </div>
                                                 <div class="col-6 col-sm-4">
                                                     <label class="form-imagecheck mb-2">
                                                         <input name="form-imagecheck-radio" type="radio"
                                                             value="5" class="form-imagecheck-input">
                                                         <span class="form-imagecheck-figure">
                                                             <img src="./dist/img/main-template.jpeg"
                                                                 alt="Young woman sitting on the sofa and working on her laptop"
                                                                 class="form-imagecheck-image">
                                                         </span>
                                                     </label>
                                                 </div>
                                                 <div class="col-6 col-sm-4">
                                                     <label class="form-imagecheck mb-2">
                                                         <input name="form-imagecheck-radio" type="radio"
                                                             value="6" class="form-imagecheck-input">
                                                         <span class="form-imagecheck-figure">
                                                             <img src="./dist/img/main-template.jpeg"
                                                                 alt="Coffee on a table with other items"
                                                                 class="form-imagecheck-image">
                                                         </span>
                                                     </label>
                                                 </div>
                                             </div>
                                         </div>

                                     </div>
                                 </div>
                                 <div id="update-step-33" class="tab-pane" role="tabpanel"
                                     aria-labelledby="update-step-33" style="display: none;">
                                     <div class="row">
                                         <div class="col-md-12">
                                             <form class="form-horizontal" role="form">
                                                 <div class="mb-3">
                                                     <label class="form-label">To:</label>
                                                     <input type="text" class="form-control" name="example-text-input"
                                                         placeholder="Type email">
                                                 </div>
                                                 <div class="mb-3">
                                                     <label class="form-label">CC:</label>
                                                     <input type="text" class="form-control" name="example-text-input"
                                                         placeholder="Type email">
                                                 </div>
                                                 <div class="mb-3">
                                                     <label class="form-label">BCC:</label>
                                                     <input type="text" class="form-control" name="example-text-input"
                                                         placeholder="Type email">
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
                                                             <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                 height="24" viewBox="0 0 24 24" fill="none"
                                                                 stroke="currentColor" stroke-width="2"
                                                                 stroke-linecap="round" stroke-linejoin="round"
                                                                 class="feather feather-paperclip">
                                                                 <path
                                                                     d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48">
                                                                 </path>
                                                             </svg>
                                                             <span>Attachment (2 MB)</span>
                                                         </div>
                                                         <button class="btn btn-sm btn-outline-primary me-2">View
                                                             All</button>
                                                         <button class="btn btn-sm btn-outline-success">Download
                                                             All</button>
                                                     </div>

                                                     <ul class="attachment-list">
                                                         <li class="attachment-list-item">
                                                             <img src="./dist/img/template.jpg"" alt=" Showcase"
                                                                 title="Showcase">
                                                         </li>
                                                         <li class="attachment-list-item">
                                                             <img src="./dist/img/main-template.jpeg" alt="Showcase"
                                                                 title="Showcase">
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
                             <div class="sw-toolbar-elm justify-content-between toolbar toolbar-bottom" role="toolbar">
                                 <button class="btn sw-btn-prev disabled" type="button">Previous</button><button
                                     class="btn btn-primary" type="button">Next</button></div>

                             <!-- Include optional progressbar HTML -->
                             <div class="progress">
                                 <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0"
                                     aria-valuemin="0" aria-valuemax="100"></div>
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
         <footer class="footer footer-transparent d-print-none">

         </footer>
     </div>
     </div>
     <div class="modal modal-blur fade" id="view-sales-order" tabindex="-1" role="dialog" aria-hidden="true">
         <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title">View Sales Order</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                     <form class="row text-left">

                         <div id="smartwizard" style="border: none; height: auto;">

                             <ul class="nav">
                                 <li class="nav-item">
                                     <a class="nav-link" href="#step-1">
                                         <div class="num">1</div>
                                         Customer-Details
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
                                         sceduling
                                     </a>
                                 </li>
                                 <li class="nav-item">
                                     <a class="nav-link " href="#step-4">
                                         <span class="num">4</span>
                                         sceduling
                                     </a>
                                 </li>

                             </ul>

                             <div class="tab-content mt-3" style="border: none;">
                                 <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
                                     <div class="row">

                                         <div class="col-md-9">
                                             <div class="d-flex"
                                                 style="justify-content: space-between; align-items: center;">
                                                 <h5 class="modal-title mb-0">Customer Details</h5>



                                             </div>
                                             <div class="card mt-3 card-active">
                                                 <div class="card-body">
                                                     <div class="row">
                                                         <div class="col-md-3 mb-3">
                                                             <label class="mb-0" for=""> <b>Customer Type </b>
                                                             </label>
                                                             <p class="m-0"><span
                                                                     class="badge bg-blue">Residential</span></p>
                                                         </div>
                                                         <div class="col-md-3 mb-3">
                                                             <label class="mb-0" for=""> <b>Customer Name</b>
                                                             </label>
                                                             <p class="m-0">Mr. Jhone Doe</p>
                                                         </div>

                                                         <div class="col-md-3 mb-3">
                                                             <label class="mb-0" for=""><b>Contact No.</b>
                                                             </label>
                                                             <p class="m-0">+91-2589631470</p>
                                                         </div>
                                                         <div class="col-md-3 mb-3">
                                                             <label class="mb-0" for=""> <b>Email</b></label>
                                                             <p class="m-0">abc@gmail.com</p>
                                                         </div>



                                                     </div>
                                                     <div class="row">
                                                         <div class="col-md-3">
                                                             <label class="mb-0" for=""> <b>Territory</b>
                                                             </label>
                                                             <p class="m-0">one</p>
                                                         </div>
                                                         <div class="col-md-3">
                                                             <label class="mb-0" for=""> <b>Language Spoken</b>
                                                             </label>
                                                             <p class="m-0">English</p>
                                                         </div>

                                                         <div class="col-md-3">
                                                             <label class="mb-0" for=""><b>Status</b> </label>
                                                             <p><span class="badge bg-red">Pending</span></p>
                                                         </div>
                                                         <div class="col-md-3">
                                                             <label class="mb-0" for=""> <b>Outstanding
                                                                     Amount</b></label>
                                                             <p class="m-0">$ 2000</p>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>

                                         </div>
                                         <div class="col-md-3">
                                             <h5 class="modal-title mb-0">Address</h5>
                                             <ul class="nav nav-pills nav-pills-primary mt-3" data-bs-toggle="tabs"
                                                 role="tablist">
                                                 <li class="nav-item me-2" role="presentation">
                                                     <a href="#tab-one" class="nav-link active" data-bs-toggle="tab"
                                                         aria-selected="true" role="tab">Service Address</a>
                                                 </li>
                                                 <li class="nav-item me-2" role="presentation">
                                                     <a href="#tab-two" class="nav-link" data-bs-toggle="tab"
                                                         aria-selected="false" role="tab" tabindex="-1">Billing
                                                         Address</a>
                                                 </li>
                                             </ul>
                                             <div class="tab-content">
                                                 <div class="tab-pane active show" id="tab-one" role="tabpanel">
                                                     <div class="row my-3">
                                                         <div class="col-lg-12">
                                                             <label for="radio-card-1059" class="radio-card">
                                                                 <input type="radio" name="radio-card"
                                                                     id="radio-card-1059" checked />
                                                                 <div class="card-content-wrapper">
                                                                     <span class="check-icon"></span>
                                                                     <div class="card-content">
                                                                         <h4>Sky Enterprice</h4>
                                                                         <p class="mb-1"> <strong>Contact
                                                                                 No:</strong>1234567890</p>
                                                                         <p class="mb-1"> <strong>Email
                                                                                 ID:</strong>ABC@gmail.com</p>

                                                                         <p class="mb-1"><strong>Address:</strong>8
                                                                             Shopping Centre, 9 Bishan Place,
                                                                             Singapore 579837
                                                                         </p>
                                                                         <p class="mb-1"><strong>Unit No:</strong>12345h
                                                                         </p>
                                                                         <p class="mb-1"><strong>Zone:</strong>South</p>
                                                                         <div class="form-check">
                                                                             <input class="form-check-input"
                                                                                 type="radio" name="flexRadioDefault"
                                                                                 id="flexRadioDefault2059" checked>
                                                                             <label class="form-check-label"
                                                                                 for="flexRadioDefault2059">
                                                                                 Default Address
                                                                             </label>
                                                                         </div>
                                                                     </div>
                                                                 </div>
                                                             </label>

                                                         </div>
                                                     </div>



                                                 </div>
                                                 <div class="tab-pane" id="tab-two" role="tabpanel">
                                                     <div class="row my-3">

                                                         <div class="col-lg-12">
                                                             <label for="radio-card-222333" class="radio-card">
                                                                 <input type="radio" name="radio-card"
                                                                     id="radio-card-222333" checked />
                                                                 <div class="card-content-wrapper">
                                                                     <span class="check-icon"></span>
                                                                     <div class="card-content">
                                                                         <h4>Jhone Doe</h4>
                                                                         <p class="mb-1"><strong>Address:</strong>8
                                                                             Shopping Centre, 9 Bishan Place,
                                                                             Singapore 579837
                                                                         </p>
                                                                         <p class="mb-1"><strong>Unit No:</strong>12345h
                                                                         </p>
                                                                         <div class="form-check">
                                                                             <input class="form-check-input"
                                                                                 type="radio" name="flexRadioDefault"
                                                                                 id="flexRadioDefault22" checked>
                                                                             <label class="form-check-label"
                                                                                 for="flexRadioDefault22">
                                                                                 Default Address
                                                                             </label>
                                                                         </div>
                                                                     </div>
                                                                 </div>
                                                             </label>

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
                                                                 <label class="form-label">Select Company<span
                                                                         class="text-danger">*</span></label>
                                                                 <select type="text" class="form-select"
                                                                     value="">
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
                                                                     <input type="text" value=""
                                                                         class="form-control" placeholder="Search…">
                                                                     <span class="input-icon-addon">
                                                                         <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                                                         <svg xmlns="http://www.w3.org/2000/svg"
                                                                             class="icon" width="24" height="24"
                                                                             viewBox="0 0 24 24" stroke-width="2"
                                                                             stroke="currentColor" fill="none"
                                                                             stroke-linecap="round"
                                                                             stroke-linejoin="round">
                                                                             <path stroke="none" d="M0 0h24v24H0z"
                                                                                 fill="none"></path>
                                                                             <path
                                                                                 d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0">
                                                                             </path>
                                                                             <path d="M21 21l-6 -6"></path>
                                                                         </svg>
                                                                     </span>
                                                                 </div>

                                                             </div>
                                                         </div>
                                                         <div class="col-md-12">
                                                             <ul class="nav nav-pills nav-pills-success mt-3"
                                                                 id="pills-tab" role="tablist" style="border: none;">
                                                                 <li class="nav-item me-3">
                                                                     <a class="nav-link" id="pills-home-tab"
                                                                         data-bs-toggle="pill" href="#pills-home"
                                                                         role="tab" aria-controls="pills-home"
                                                                         aria-selected="true">Services</a>
                                                                 </li>
                                                                 <li class="nav-item">
                                                                     <a class="nav-link" id="pills-profile-tab"
                                                                         data-bs-toggle="pill" href="#pills-profile"
                                                                         role="tab" aria-controls="pills-profile"
                                                                         aria-selected="false">Packages</a>
                                                                 </li>

                                                             </ul>
                                                             <div class="tab-content p-0" id="pills-tabContent"
                                                                 style="border: none;">
                                                                 <div class="tab-pane fade" id="pills-home"
                                                                     role="tabpanel" aria-labelledby="pills-home-tab">
                                                                     <div class="mt-3">
                                                                         <div class="row" id="productsubcat">
                                                                             <div class="col-md-4 text-center">
                                                                                 <button type="button"
                                                                                     class="productsub btn btn-inverse-primary btn-sm">Floor
                                                                                     Cleaning</button>

                                                                             </div>
                                                                             <div class="col-md-4 text-center">
                                                                                 <button type="button"
                                                                                     class="productsub btn btn-inverse-secondary btn-sm">Home
                                                                                     Cleaning</button>

                                                                             </div>
                                                                             <div class="col-md-4 text-center">
                                                                                 <button type="button"
                                                                                     class="productsub btn btn-inverse-warning btn-sm">Office
                                                                                     Cleaning</button>
                                                                             </div>
                                                                         </div>

                                                                         <div class="productsubshow mt-3"
                                                                             style="display: none;">

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
                                                                                                 <button
                                                                                                     class="btn btn-primary   ripple"
                                                                                                     type="button">
                                                                                                     <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                         class="icon m-0"
                                                                                                         width="24"
                                                                                                         height="24"
                                                                                                         viewBox="0 0 24 24"
                                                                                                         stroke-width="2"
                                                                                                         stroke="currentColor"
                                                                                                         fill="none"
                                                                                                         stroke-linecap="round"
                                                                                                         stroke-linejoin="round">
                                                                                                         <path
                                                                                                             stroke="none"
                                                                                                             d="M0 0h24v24H0z"
                                                                                                             fill="none">
                                                                                                         </path>
                                                                                                         <path
                                                                                                             d="M12 5l0 14">
                                                                                                         </path>
                                                                                                         <path
                                                                                                             d="M5 12l14 0">
                                                                                                         </path>
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
                                                                 <div class="tab-pane fade" id="pills-profile"
                                                                     role="tabpanel" aria-labelledby="pills-profile-tab">
                                                                     <div class="mt-3">
                                                                         <div class="row" id="packagesubcat">
                                                                             <div class="col-md-4">
                                                                                 <button type="button"
                                                                                     class="packagesub btn btn-inverse-primary btn-sm">Floor
                                                                                     Cleaning</button>

                                                                             </div>
                                                                             <div class="col-md-4">
                                                                                 <button type="button"
                                                                                     class="packagesub btn btn-inverse-secondary btn-sm">Home
                                                                                     Cleaning</button>

                                                                             </div>
                                                                             <div class="col-md-4">
                                                                                 <button type="button"
                                                                                     class="packagesub btn btn-inverse-warning btn-sm">Office
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
                                                                                             <button
                                                                                                 class="btn btn-primary ripple"
                                                                                                 type="button">
                                                                                                 <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                     class="icon m-0"
                                                                                                     width="24"
                                                                                                     height="24"
                                                                                                     viewBox="0 0 24 24"
                                                                                                     stroke-width="2"
                                                                                                     stroke="currentColor"
                                                                                                     fill="none"
                                                                                                     stroke-linecap="round"
                                                                                                     stroke-linejoin="round">
                                                                                                     <path stroke="none"
                                                                                                         d="M0 0h24v24H0z"
                                                                                                         fill="none">
                                                                                                     </path>
                                                                                                     <path d="M12 5l0 14">
                                                                                                     </path>
                                                                                                     <path d="M5 12l14 0">
                                                                                                     </path>
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
                                                             <table
                                                                 class="table card-table table-vcenter text-center text-nowrap"
                                                                 id="" style="width:100%">
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

                                                                         <td><input type="number" class="form-control">
                                                                         </td>
                                                                         <td class="p-0"><input type="number"
                                                                                 class="form-control"></td>
                                                                         <td>5%</td>
                                                                         <td>$543</td>
                                                                         <td>18%</td>

                                                                         <td>
                                                                             <button class="btn btn-danger ripple"
                                                                                 type="button">
                                                                                 <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     class="icon icon-tabler icon-tabler-playstation-x m-0"
                                                                                     width="24" height="24"
                                                                                     viewBox="0 0 24 24" stroke-width="2"
                                                                                     stroke="currentColor" fill="none"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round">
                                                                                     <path stroke="none"
                                                                                         d="M0 0h24v24H0z" fill="none">
                                                                                     </path>
                                                                                     <path
                                                                                         d="M12 21a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9a9 9 0 0 0 -9 9a9 9 0 0 0 9 9z">
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
                                                                         <th colspan="7" style="text-align: end;">Total
                                                                             discount </th>
                                                                         <th colspan="2">5%</th>
                                                                     </tr>
                                                                     <tr>
                                                                         <th colspan="7" style="text-align: end;">Total
                                                                             tax </th>
                                                                         <th colspan="2">18%</th>
                                                                     </tr>
                                                                     <tr>
                                                                         <th colspan="7" style="text-align: end;">Grand
                                                                             total</th>
                                                                         <th colspan="2">$ 616.00</th>
                                                                     </tr>
                                                                 </thead>
                                                                 <thead id="package-total" style="display: none;">
                                                                     <tr>
                                                                         <th colspan="7" style="text-align: end;">
                                                                             Package Amount</th>
                                                                         <th colspan="2"><input type="text"
                                                                                 class="form-control"></th>
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

                                                             <input type="text" value=""
                                                                 class="form-control w-50"
                                                                 placeholder="Enter Package Name">



                                                         </div>
                                                     </div>
                                                 </div>
                                                 <div class="card">
                                                     <div class="card-body p-0">


                                                         <div class="table-responsive">

                                                             <table
                                                                 class="table card-table table-vcenter text-center text-nowrap"
                                                                 id="" style="width:100%">
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
                                                                         <td>
                                                                             <textarea class="form-control" name="example-textarea-input" rows="3" placeholder="Enter Descrption">

                                                </textarea>
                                                                         </td>
                                                                         <td><input type="number" class="form-control">
                                                                         </td>
                                                                         <td class="p-0"><input type="number"
                                                                                 class="form-control"></td>
                                                                         <td>0</td>
                                                                         <td>$308.00</td>

                                                                         <td>
                                                                             <button class="btn btn-danger ripple"
                                                                                 type="button">
                                                                                 <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     class="icon icon-tabler icon-tabler-playstation-x m-0"
                                                                                     width="24" height="24"
                                                                                     viewBox="0 0 24 24" stroke-width="2"
                                                                                     stroke="currentColor" fill="none"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round">
                                                                                     <path stroke="none"
                                                                                         d="M0 0h24v24H0z" fill="none">
                                                                                     </path>
                                                                                     <path
                                                                                         d="M12 21a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9a9 9 0 0 0 -9 9a9 9 0 0 0 9 9z">
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
                                                                         <th colspan="7" style="text-align: end;">TOTAL
                                                                             DISCOUNT</th>
                                                                         <th colspan="2">$ 616.00</th>
                                                                     </tr>
                                                                     <tr>
                                                                         <th colspan="7" style="text-align: end;">Total
                                                                             tax </th>
                                                                         <th colspan="2">18%</th>
                                                                     </tr>
                                                                 </thead>
                                                                 <thead>
                                                                     <tr>
                                                                         <th colspan="7" style="text-align: end;">
                                                                             Package Amount</th>
                                                                         <th colspan="2"><input type="text"
                                                                                 class="form-control"></th>
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

                                         <div class="col-lg-4 col-md-6 col-sm-1">
                                             <div class="mb-3">
                                                 <label class="form-label">Territory<span
                                                         class="text-danger">*</span></label>
                                                 <select type="text" class="form-select" value="">
                                                     <option value="33">one</option>
                                                     <option value="34">two</option>
                                                 </select>
                                             </div>
                                         </div>

                                         <div class="col-lg-4">
                                             <div class="mb-3">
                                                 <div class="form-label">Assign Cleaners<span class="text-danger">*</span>
                                                 </div>
                                                 <div class="dropdown" data-control="checkbox-dropdown">
                                                     <label class="dropdown-label">Select Options</label>

                                                     <div class="dropdown-list">
                                                         <a href="#" data-toggle="check-all"
                                                             class="dropdown-option border-bottom text-blue">Check
                                                             All</a>

                                                         <label class="dropdown-option">
                                                             <input type="checkbox" name="dropdown-group"
                                                                 value="Selection 1">
                                                             Floor Cleaning
                                                         </label>

                                                         <label class="dropdown-option">
                                                             <input type="checkbox" name="dropdown-group"
                                                                 value="Selection 2">
                                                             Home Cleaning
                                                         </label>

                                                         <label class="dropdown-option">
                                                             <input type="checkbox" name="dropdown-group"
                                                                 value="Selection 3">
                                                             Office Cleaning
                                                         </label>


                                                     </div>
                                                 </div>
                                             </div>

                                         </div>
                                         <div class="col-lg-4">
                                             <div class="mb-3">
                                                 <label class="form-label">Select Date</label>


                                                 <div class="input-icon">
                                                     <span
                                                         class="input-icon-addon"><!-- Download SVG icon from http://tabler-icons.io/i/calendar -->
                                                         <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                             width="24" height="24" viewBox="0 0 24 24"
                                                             stroke-width="2" stroke="currentColor" fill="none"
                                                             stroke-linecap="round" stroke-linejoin="round">
                                                             <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                             </path>
                                                             <path
                                                                 d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z">
                                                             </path>
                                                             <path d="M16 3v4"></path>
                                                             <path d="M8 3v4"></path>
                                                             <path d="M4 11h16"></path>
                                                             <path d="M11 15h1"></path>
                                                             <path d="M12 15v3"></path>
                                                         </svg>
                                                     </span>
                                                     <input class="form-control" placeholder="Select a date"
                                                         id="datepicker-icon-prepend" value="2020-06-20">
                                                 </div>
                                             </div>
                                         </div>
                                         <div class="col-lg-4">
                                             <label class="form-label">Select Time</label>
                                             <div class="cs-form">
                                                 <input type="time" class="form-control" value="10:05 AM" />
                                             </div>
                                         </div>
                                     </div>

                                 </div>
                                 <style>
                                     .invoice-box .title {
                                         font-size: 2rem;
                                         color: #000;
                                         font-weight: bolder;
                                     }

                                     .footer-logo .img img {
                                         width: 80px;
                                     }
                                 </style>

                                 <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">
                                     <div class="invoice-box container-fluid mt-100 mb-100">
                                         <div id="ui-view">
                                             <div>
                                                 <div class="card">
                                                     <div class="card-header border-0">
                                                         <div class="row w-100">
                                                             <div class="col-md-3">
                                                                 <img src="dist/img/auntie-cleaner-logo.webp"
                                                                     alt="" class="img-fluid">
                                                             </div>
                                                             <div class="col-md-9">
                                                                 <h1 class="title"><b>Auntie Cleaner Pte Ltd</b></h1>
                                                                 <p class="m-0">61 Kaki Bukit Ave 1 #03-05 Shun Li
                                                                     Industrial Park Singapore 417943</p>
                                                                 <p class="m-0">Tel: +65 6844 8444 Fax: +65 6844 3422
                                                                     Phone: +65 8488 8444</p>
                                                                 <p class="m-0">Website: www.absolutesolutions.com.sg
                                                                 </p>
                                                                 <p class="m-0">Co. Reg No: 201524788N
                                                                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GST Reg No.
                                                                     201524788N</p>
                                                             </div>
                                                         </div>



                                                     </div>
                                                     <div class="card-body">
                                                         <div class="row mb-4">
                                                             <div class="col-sm-1">
                                                                 <h4 class="mb-3"><b>To:</b></h4>
                                                             </div>
                                                             <div class="col-sm-5">
                                                                 <div>Ms Anna</div>
                                                                 <div>2 Bedok Rise #02-06</div>
                                                                 <div>The Glades Singapore 469597</div>
                                                             </div>

                                                             <div class="col-sm-6">
                                                                 <div class="row">
                                                                     <div class="col-md-8 text-end">
                                                                         <div><b>Issued By:</b></div>
                                                                     </div>
                                                                     <div class="col-md-4 text-end">
                                                                         <div>Lubie</div>
                                                                     </div>
                                                                 </div>
                                                                 <div class="row">
                                                                     <div class="col-md-8 text-end">
                                                                         <div><b>Invoice No:</b></div>
                                                                     </div>
                                                                     <div class="col-md-4 text-end">
                                                                         <div>HAI-23-000296</div>
                                                                     </div>
                                                                 </div>
                                                                 <div class="row">
                                                                     <div class="col-md-8 text-end">
                                                                         <div><b>Issued Date:</b></div>
                                                                     </div>
                                                                     <div class="col-md-4 text-end">
                                                                         <div>08-Feb-2023</div>
                                                                     </div>
                                                                 </div>
                                                                 <div class="row">
                                                                     <div class="col-md-8 text-end">
                                                                         <div><b>Commence Date:</b></div>
                                                                     </div>
                                                                     <div class="col-md-4 text-end">
                                                                         <div>10-Feb-2023</div>
                                                                     </div>
                                                                 </div>
                                                                 <div class="row">
                                                                     <div class="col-md-8 text-end">
                                                                         <div><b>Contact No:</b></div>
                                                                     </div>
                                                                     <div class="col-md-4 text-end">
                                                                         <div>82986884</div>
                                                                     </div>
                                                                 </div>

                                                             </div>

                                                         </div>
                                                         <style>
                                                             .invoice-table {
                                                                 height: 450px;
                                                                 border-bottom: 2px solid #000;
                                                             }

                                                             .invoice-table thead th {
                                                                 background-color: transparent;
                                                                 font-size: 0.8rem;
                                                                 font-weight: bold !important;
                                                                 color: #000;

                                                                 border-bottom: 2px solid #000;
                                                             }

                                                             .invoice-table tbody tr td {
                                                                 border-bottom: none !important;
                                                             }
                                                         </style>
                                                         <div class="table-responsive-sm">
                                                             <table class="invoice-table table">
                                                                 <thead>
                                                                     <tr>

                                                                         <th>PRODUCT</th>
                                                                         <th>DESCRIPTION</th>
                                                                         <th>QTY</th>
                                                                         <th>UNIT PRICE</th>
                                                                         <th>Total</th>
                                                                     </tr>
                                                                 </thead>
                                                                 <tbody>
                                                                     <tr>
                                                                         <td>HCRP-400</td>
                                                                         <td>
                                                                             <div>
                                                                                 4 Hours Residential Cleaning Package For 1
                                                                                 Session a Week (4 sessions)
                                                                             </div>
                                                                             <div>
                                                                                 Time : 830AM to 1230PM
                                                                             </div>
                                                                             <div>
                                                                                 No of Cleaners : 1
                                                                             </div>
                                                                             <div>
                                                                                 Every : Friday
                                                                             </div>
                                                                             <div>
                                                                                 Cleaning Dates : 10/02, 17/02, 24/02 &
                                                                                 03/03
                                                                             </div>

                                                                         </td>
                                                                         <td>1.00</td>
                                                                         <td>
                                                                             <div class="d-flex justify-content-between">

                                                                                 $

                                                                                 <div>417.00</div>

                                                                             </div>
                                                                         </td>
                                                                         <td>
                                                                             <div class="d-flex justify-content-between">

                                                                                 $

                                                                                 <div>417.00</div>

                                                                             </div>
                                                                         </td>
                                                                     </tr>

                                                                 </tbody>
                                                             </table>
                                                         </div>
                                                         <div class="row mb-3">
                                                             <div class="col-sm-6">
                                                                 <div class="row mb-2">
                                                                     <div class="col-md-4 text-start">
                                                                         <div><b>Remarks:</b></div>
                                                                     </div>
                                                                     <div class="col-md-8 text-start">
                                                                         <div></div>
                                                                     </div>
                                                                 </div>
                                                                 <div class="row mb-2">
                                                                     <div class="col-md-4 text-start">
                                                                         <div><b>Payment Term:</b></div>
                                                                     </div>
                                                                     <div class="col-md-8 text-start">
                                                                         <div>C.O.D</div>
                                                                     </div>
                                                                 </div>
                                                                 <div class="row mb-2">
                                                                     <div class="col-md-4 text-start">
                                                                         <div><b>Bank Detail:</b></div>
                                                                     </div>
                                                                     <div class="col-md-8 text-start">
                                                                         <div>08-Feb-2023</div>
                                                                     </div>
                                                                 </div>
                                                                 <div class="row mb-2">
                                                                     <div class="col-md-4 text-start">
                                                                         <div><b>Commence Date:</b></div>
                                                                     </div>
                                                                     <div class="col-md-8 text-start">
                                                                         <div>OCBC Current: 695-163-311-001
                                                                         </div>
                                                                         <div>Bank Code: 7339 / Branch Code: 695</div>
                                                                     </div>
                                                                 </div>
                                                                 <div class="row mb-2">
                                                                     <div class="col-md-4 text-start">
                                                                         <div><b>Payment Method:</b></div>
                                                                     </div>
                                                                     <div class="col-md-8 text-start">
                                                                         <div
                                                                             style="text-decoration: underline; font-size: 12px;">
                                                                             "PayNow Unique Company Number(UEN) No:
                                                                             201524788N"
                                                                         </div>
                                                                         <div>All cheques are to be crossed and made payable
                                                                             to</div>
                                                                         <div><b>"Auntie Cleaner Pte Ltd"</b></div>
                                                                     </div>
                                                                 </div>

                                                             </div>
                                                             <div class="col-sm-2">
                                                                 <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAclBMVEXMzMzQ0NDT09PLy8sAAADIyMh+fn6Li4u1tbV7e3uCgoLAwMDFxcVFRUVZWVm9vb2ioqJUVFScnJxOTk6VlZU8PDxpaWmxsbEjIyOKioooKChlZWVwcHB1dXVBQUGRkZE2NjYNDQ1JSUkvLy8cHBwTExNimPTCAAAEAElEQVR4nO3YWZuiOBiGYQJSIolhCYsKgi3V//8vTlgLl2uqZ6Zb5+C5j8DWt/NlhXIcAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA/L+I0e3dcu/c3v1yzn8I+t3csg0/wm0w3sl0O8qisVkqO4ZhG3zfstscJ8rGnGMmxyA5BEWvLzHa5UYXVb5v/b4dcZ7vB1U5tCXQnS5016TuP8pxRHoZg5pi7J0yudigg4m/C/rdRFzFQSRlcD6lQ8NObTCI1PDveRNHMiqrQ/z3nX+X44i2S6egoefkxQxByaV89Sj6clgbwjON31eYl6vl49afZX8lvEPyTd/f5jhi2wSrhedVl2AIig7FqwdxJo4bO2oi69Z97G7O452If8SrLePx6i7HEWG+XrveJpzustM3s+GPmSu8rhom0s1cr3c4z5+rdP5QlKl6nmMrTFabijh+Tr+xgxi+q8LwRz9Ls30gfOVNzQl/zlPKT/R8GWlTjleluT5ssmNOX6F0lD8FuXU+/1pe6zdNU0+bvsI2ibNd3ZbDALjV0jCvNsuJGSXJUGKZJI+b/5jjiF0VZ+dzW8ohyCzLWFXFe8ZQxId+D/Q/PvdJUdidvR8cN9kvFYZ7uXw3sCUKYQt8PCanHEcVhzGo6DvBzZcZ4J/1w8x+BSH18B97aZ1GwlWx7ofH3SfPKuxLNEFgnhU45TheW8d2d5Wpqezv3NOyg6o3Vag+mnFtKTW0WpRdayu8Ph3DYaIa82SKfuXYNTgGpV1qgzr97gqzU+vdfCD12bOzdFmHTmjW9bjxZvPs6eQhRwQmtEGX5GuWVu9Yh/EpvG2Y4+90ZHeaw9IwrVcFiWBvzP5xkj7m2AVZSLvTLJNBmjfspSLe1PeN9cNC9efhPDX9S/XVMBE1jVJNczdNn+U4fr3z7XnYqfmnl93Lx9A2LLzvVhFd+4PZ3Wynk1HNV/1N2RglhDLNzTPmsxz73TyzX/I32RQUfGYvrlB423XDxPyU1mXDcXEZzmwhdP61P5Qm6U85IZP56H/MWYKyrn9K869mDPKTxP+j9TySRzs6/sDe+WVpR0f48b7f5G2Pd0VkGyuzTbr0vCymXdTuqIV8nuOoMuiDVNrUQ9eU3dkeHkIef7z6sVSEm0O4G5ztO29UN3WbZefT9HroxLk5Zll1WA2PiuctRgSxep4jgmpvg9q6m14PvfSit1mmT+3L3w/TMPwY7WzLvKA9a52EmZx7uvyodPVx8wL87N3iLsdOhrbWWoepmr7ixzutq/DlL8CrP6eMrRXCk1Hkrl4LHBmpX/jzyl1OHxT9qyAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAC8yV8YEDZOb4QEjAAAAABJRU5ErkJggg=="
                                                                     alt="">
                                                             </div>
                                                             <div class="col-sm-4">
                                                                 <div class="row">
                                                                     <div class="col-md-8 text-end">
                                                                         <div>Sub Total:</div>
                                                                     </div>
                                                                     <div class="col-md-4 text-end">
                                                                         <div>
                                                                             <div class="d-flex justify-content-between">
                                                                                 $<div>417.00</div>
                                                                             </div>
                                                                         </div>
                                                                     </div>
                                                                 </div>
                                                                 <div class="row">
                                                                     <div class="col-md-8 text-end">
                                                                         <div>
                                                                             Discount:
                                                                         </div>
                                                                     </div>
                                                                     <div class="col-md-4 text-end">
                                                                         <div>
                                                                             <div class="d-flex justify-content-between">
                                                                                 $<div>417.00</div>
                                                                             </div>
                                                                         </div>
                                                                     </div>
                                                                 </div>
                                                                 <div class="row">
                                                                     <div class="col-md-8 text-end">
                                                                         <div>Total:</div>
                                                                     </div>
                                                                     <div class="col-md-4 text-end">
                                                                         <div>
                                                                             <div class="d-flex justify-content-between">
                                                                                 $<div>417.00</div>
                                                                             </div>
                                                                         </div>
                                                                     </div>
                                                                 </div>
                                                                 <div class="row">
                                                                     <div class="col-md-8 text-end">
                                                                         <div>GST @ 8%:
                                                                         </div>
                                                                     </div>
                                                                     <div class="col-md-4 text-end">
                                                                         <div>
                                                                             <div class="d-flex justify-content-between">
                                                                                 $<div>33.36</div>
                                                                             </div>
                                                                         </div>
                                                                     </div>
                                                                 </div>
                                                                 <div class="row">
                                                                     <div class="col-md-8 text-end">
                                                                         <div>Grand Total:</div>
                                                                     </div>
                                                                     <div class="col-md-4 text-end">
                                                                         <div>
                                                                             <div class="d-flex justify-content-between">
                                                                                 $<div>450.36</div>
                                                                             </div>
                                                                         </div>
                                                                     </div>
                                                                 </div>
                                                                 <div class="row">
                                                                     <div class="col-md-8 text-end">
                                                                         <div>Deposit:</div>
                                                                     </div>
                                                                     <div class="col-md-4 text-end">
                                                                         <div>
                                                                             <div class="d-flex justify-content-between">
                                                                                 $<div>0.00</div>
                                                                             </div>
                                                                         </div>
                                                                     </div>
                                                                 </div>
                                                                 <div class="row">
                                                                     <div class="col-md-8 text-end">
                                                                         <div>Balance:</div>
                                                                     </div>
                                                                     <div class="col-md-4 text-end">
                                                                         <div>
                                                                             <div class="d-flex justify-content-between">
                                                                                 $<div>450.36</div>
                                                                             </div>
                                                                         </div>
                                                                     </div>
                                                                 </div>

                                                             </div>
                                                         </div>
                                                         <div class="row mb-3">
                                                             <h3><b>Terms and Conditions</b></h3>
                                                             <ol class="ps-4">
                                                                 <li>
                                                                     Goods or services sold are not refundable
                                                                 </li>
                                                                 <li>
                                                                     Prices, Terms and Conditions are subjected to
                                                                     alteration without prior notice
                                                                 </li>
                                                                 <li>
                                                                     Deposits paid are not refundable or exchangeable upon
                                                                     cancellation
                                                                 </li>
                                                                 <li>
                                                                     Minimum of 4 hours per cleaning visit applies
                                                                 </li>
                                                                 <li>
                                                                     Clients are to provide all cleaning materials and
                                                                     products
                                                                 </li>
                                                                 <li>
                                                                     Any additional cleaning required will be subjected to
                                                                     charges of $30 per hour for each cleaner
                                                                 </li>
                                                                 <li>
                                                                     For any additional cleaning that requires an extension
                                                                     in the number of cleaning hours will be subjected to
                                                                     cleaner’s availability
                                                                 </li>
                                                                 <li>
                                                                     Our liability of loss or damage if any shall not exceed
                                                                     $200 or 50% of the cost price, whichever is lower
                                                                 </li>
                                                                 <li>
                                                                     All invoices are to be settled within 30days, otherwise
                                                                     a monthly interest of 5% on the invoice value will be
                                                                     levied on the said overdue account
                                                                 </li>
                                                                 <li>
                                                                     Cancellation must be made at least 3 working days in
                                                                     advance, any last minute cancellation will result in
                                                                     one session being forfeited.
                                                                 </li>
                                                                 <li>
                                                                     It is the client’s responsibility to ensure that
                                                                     valuables are locked before the cleaning session
                                                                     commences
                                                                 </li>
                                                                 <li>
                                                                     Please inform us within 24 hours should there be any
                                                                     concerns with regards to our services
                                                                 </li>
                                                             </ol>
                                                             <p>We thank you for choosing Auntie Cleaner Pte Ltd.</p>
                                                         </div>
                                                         <div class="row">
                                                             <div class="col-sm-12">
                                                                 <h3><b>This is a computer generated invoice therefore no
                                                                         signature required.</b></h3>
                                                                 <div class="d-flex footer-logo justify-content-between">
                                                                     <div class="img">
                                                                         <img src="dist/img/logo.png" alt="logo">
                                                                     </div>
                                                                     <div class="img">
                                                                         <img src="dist/img/invoice-logo/logo-1.png"
                                                                             alt="logo">
                                                                     </div>
                                                                     <div class="img">
                                                                         <img src="dist/img/invoice-logo/logo-2.png"
                                                                             alt="logo">
                                                                     </div>
                                                                     <div class="img">
                                                                         <img src="dist/img/invoice-logo/logo-3.png"
                                                                             alt="logo">
                                                                     </div>
                                                                     <div class="img">
                                                                         <img src="dist/img/invoice-logo/logo-4.jpg"
                                                                             alt="logo">
                                                                     </div>
                                                                     <div class="img">
                                                                         <img src="dist/img/invoice-logo/logo-5.png"
                                                                             alt="logo">
                                                                     </div>
                                                                     <div class="img">
                                                                         <img src="dist/img/invoice-logo/logo-6.png"
                                                                             alt="logo">
                                                                     </div>
                                                                     <div class="img">
                                                                         <img src="dist/img/invoice-logo/logo-7.png"
                                                                             alt="logo">
                                                                     </div>
                                                                     <div class="img">
                                                                         <img src="dist/img/invoice-logo/logo-8.png"
                                                                             alt="logo">
                                                                     </div>
                                                                 </div>
                                                             </div>

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
                                 <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0"
                                     aria-valuemin="0" aria-valuemax="100"></div>
                             </div>
                         </div>

                     </form>

                 </div>

                 <!-- <div class="modal-footer">
                              <button type="button" class="btn me-auto sw-btn-prev sw-btn">Previous</button>
                              <button type="button" class="btn btn-primary next-btn" >Next</button>
                          </div> -->
             </div>
         </div>
     </div>

     <div class="modal modal-blur fade" id="edit-sales-order" tabindex="-1" role="dialog" aria-hidden="true">
         <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title">Edit Sales order</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                     <form class="row text-left">

                         <div id="smartwizard-edit" style="border: none; height: auto;">

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

                                             <ul class="nav nav-pills nav-pills-primary" data-bs-toggle="tabs"
                                                 role="tablist">
                                                 <li class="nav-item me-2" role="presentation">
                                                     <a href="#residential-edit" id="residential-edit-table"
                                                         class="nav-link active" data-bs-toggle="tab"
                                                         aria-selected="true" role="tab">Residential</a>
                                                 </li>
                                                 <li class="nav-item me-2" role="presentation">
                                                     <a href="#commercial-edit" class="nav-link"
                                                         id="commercial-edit-table" data-bs-toggle="tab"
                                                         aria-selected="false" role="tab"
                                                         tabindex="-1">Commercial</a>
                                                 </li>


                                             </ul>

                                             <div class="tab-content mt-3">
                                                 <div class="tab-pane fade show active" id="residential-edit"
                                                     role="tabpanel">

                                                     <div class="mb-3">
                                                         <label class="form-label">Search By</label>
                                                         <div class="input-icon mb-3">
                                                             <input type="text" value="" class="form-control"
                                                                 placeholder="Search…">
                                                             <span class="input-icon-addon">
                                                                 <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                                                 <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                                     width="24" height="24" viewBox="0 0 24 24"
                                                                     stroke-width="2" stroke="currentColor"
                                                                     fill="none" stroke-linecap="round"
                                                                     stroke-linejoin="round">
                                                                     <path stroke="none" d="M0 0h24v24H0z"
                                                                         fill="none"></path>
                                                                     <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0">
                                                                     </path>
                                                                     <path d="M21 21l-6 -6"></path>
                                                                 </svg>
                                                             </span>
                                                         </div>

                                                     </div>
                                                     <div class="mb-3">
                                                         <div class="card card-active">
                                                             <div class="ribbon bg-yellow">Residential</div>
                                                             <div class="card-body d-flex justify-content-between">
                                                                 <div class="my-auto">
                                                                     <label class="mb-0 text-black fw-bold "
                                                                         style="font-size: 14px">{{$data->customer_name}}</label>
                                                                     <p class="m-0">Tel - +91 {{$data->mobile_number}}</p>
                                                                 </div>

                                                             </div>
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <div class="tab-pane fade" id="commercial-edit" role="tabpanel">
                                                     <div class="mb-3">
                                                         <label class="form-label">Search By</label>
                                                         <div class="input-icon mb-3">
                                                             <input type="text" value="" class="form-control"
                                                                 placeholder="Search…">
                                                             <span class="input-icon-addon">
                                                                 <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                                                 <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                                     width="24" height="24" viewBox="0 0 24 24"
                                                                     stroke-width="2" stroke="currentColor"
                                                                     fill="none" stroke-linecap="round"
                                                                     stroke-linejoin="round">
                                                                     <path stroke="none" d="M0 0h24v24H0z"
                                                                         fill="none"></path>
                                                                     <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0">
                                                                     </path>
                                                                     <path d="M21 21l-6 -6"></path>
                                                                 </svg>
                                                             </span>
                                                         </div>

                                                     </div>
                                                     <div class="mb-3">
                                                         <div class="card card-active">
                                                             <div class="ribbon bg-red">Commercial</div>
                                                             <div class="card-body d-flex justify-content-between">
                                                                 {{-- <div class="my-auto">
                                                                     <label class="mb-0 text-black fw-bold "
                                                                         style="font-size: 14px">Will Smith</label>
                                                                     <p class="m-0">Tel - +91 9825804569</p>
                                                                 </div> --}}

                                                             </div>
                                                         </div>
                                                     </div>
                                                 </div>

                                             </div>


                                         </div>
                                         <div class="col-md-9">
                                             <div class="d-flex"
                                                 style="justify-content: space-between; align-items: center;">
                                                 <h5 class="modal-title">Customer Details</h5>


                                                 <a href="#" class="btn btn-info" data-bs-toggle="modal"
                                                     data-bs-target="#add-customer">
                                                     <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                                     <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                         width="24" height="24" viewBox="0 0 24 24"
                                                         stroke-width="2" stroke="currentColor" fill="none"
                                                         stroke-linecap="round" stroke-linejoin="round">
                                                         <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                         <path d="M12 5l0 14"></path>
                                                         <path d="M5 12l14 0"></path>
                                                     </svg>
                                                     Add New
                                                 </a>


                                             </div>
                                             <div class="card mt-3" id="residential-card-edit">
                                                 <div class="card-body">
                                                     <div class="row">
                                                         <div class="col-md-3">
                                                             <label class="mb-0" for=""> <b>Customer Name</b>
                                                             </label>
                                                             <p class="m-0">{{$data->customer_name}}</p>
                                                         </div>
                                                         <div class="col-md-3">
                                                             <label class="mb-0" for=""><b>Contact No.</b>
                                                             </label>
                                                             <p class="m-0">+91-{{$data->mobile_number}}</p>
                                                         </div>
                                                         <div class="col-md-3">
                                                             <label class="mb-0" for=""> <b>Email</b></label>
                                                             <p class="m-0">{{$data->email}}</p>
                                                         </div>
                                                         <div class="col-md-3">
                                                             <label class="mb-0" for="">
                                                                 <b>Territory</b></label>
                                                             <p class="m-0">{{$data->territory}}</p>
                                                         </div>
                                                     </div>
                                                     <div class="row mt-3">


                                                         <div class="col-md-3">
                                                             <label class="mb-0" for=""><b>Status</b> </label>
                                                             <p><span class="badge bg-red">Pending</span></p>
                                                         </div>
                                                         <div class="col-md-3">
                                                             <label class="mb-0" for=""><b>Outstanding
                                                                     Amount</b> </label>
                                                             <p class="m-0">$ 2000</p>
                                                         </div>
                                                     </div>

                                                 </div>
                                             </div>
                                             <div class="card mt-3" id="commercial-card-edit" style="display: none;">
                                                 <div class="card-body">
                                                     <div class="row">
                                                         <div class="col-md-3 mb-3">
                                                             <label class="mb-0" for=""> <b>UEN</b></label>
                                                             <p class="m-0">123456</p>
                                                         </div>
                                                         <div class="col-md-3 mb-3">
                                                             <label class="mb-0" for=""> <b>Customer Name</b>
                                                             </label>
                                                             <p class="m-0">ABC Group Of Companies</p>
                                                         </div>
                                                         <div class="col-md-3 mb-3">
                                                             <label class="mb-0" for=""><b>Contact No.</b>
                                                             </label>
                                                             <p class="m-0">+91 9758697820</p>
                                                         </div>
                                                         <div class="col-md-3 mb-3">
                                                             <label class="mb-0" for=""> <b>Email</b></label>
                                                             <p class="m-0">abc@gmail.com</p>
                                                         </div>
                                                         <div class="col-md-3 mb-3">
                                                             <label class="mb-0" for="">
                                                                 <b>Territory</b></label>
                                                             <p class="m-0">one</p>
                                                         </div>
                                                         <div class="col-md-3 ">
                                                             <label class="mb-0" for=""><b>Status</b> </label>
                                                             <p class="m-0"><span class="badge bg-red">Pending</span>
                                                             </p>
                                                         </div>
                                                         <div class="col-md-6">
                                                             <label class="mb-0" for=""><b>Outstanding
                                                                     Amount</b> </label>
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
                                                                 <label class="form-label">Select Company<span
                                                                         class="text-danger">*</span></label>
                                                                 <select type="text" class="form-select"
                                                                     value="">
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
                                                                     <input type="text" value=""
                                                                         class="form-control" placeholder="Search…">
                                                                     <span class="input-icon-addon">
                                                                         <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                                                         <svg xmlns="http://www.w3.org/2000/svg"
                                                                             class="icon" width="24"
                                                                             height="24" viewBox="0 0 24 24"
                                                                             stroke-width="2" stroke="currentColor"
                                                                             fill="none" stroke-linecap="round"
                                                                             stroke-linejoin="round">
                                                                             <path stroke="none" d="M0 0h24v24H0z"
                                                                                 fill="none"></path>
                                                                             <path
                                                                                 d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0">
                                                                             </path>
                                                                             <path d="M21 21l-6 -6"></path>
                                                                         </svg>
                                                                     </span>
                                                                 </div>

                                                             </div>
                                                         </div>
                                                         <div class="col-md-12">
                                                             <ul class="nav nav-pills nav-pills-success mt-3"
                                                                 id="pills-tab" role="tablist" style="border: none;">
                                                                 <li class="nav-item me-3">
                                                                     <a class="nav-link" id="pills-home-tab-edit"
                                                                         data-bs-toggle="pill" href="#pills-home-edit"
                                                                         role="tab" aria-controls="pills-home"
                                                                         aria-selected="true">Services</a>
                                                                 </li>
                                                                 <li class="nav-item">
                                                                     <a class="nav-link" id="pills-profile-tab-edit"
                                                                         data-bs-toggle="pill"
                                                                         href="#pills-profile-edit" role="tab"
                                                                         aria-controls="pills-profile"
                                                                         aria-selected="false">Packages</a>
                                                                 </li>

                                                             </ul>
                                                             <div class="tab-content p-0" id="pills-tabContent"
                                                                 style="border: none;">
                                                                 <div class="tab-pane fade" id="pills-home-edit"
                                                                     role="tabpanel" aria-labelledby="pills-home-tab">
                                                                     <div class="mt-3">
                                                                         <div class="row" id="productsubcat">
                                                                             <div class="col-md-4 text-center">
                                                                                 <button type="button"
                                                                                     class="productsubedit btn btn-inverse-primary btn-sm">Floor
                                                                                     Cleaning</button>

                                                                             </div>
                                                                             <div class="col-md-4 text-center">
                                                                                 <button type="button"
                                                                                     class="productsubedit btn btn-inverse-secondary btn-sm">Home
                                                                                     Cleaning</button>

                                                                             </div>
                                                                             <div class="col-md-4 text-center">
                                                                                 <button type="button"
                                                                                     class="productsubedit btn btn-inverse-warning btn-sm">Office
                                                                                     Cleaning</button>
                                                                             </div>
                                                                         </div>

                                                                         <div class="productsubshowedit mt-3"
                                                                             style="display: none;">

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
                                                                                                 <button
                                                                                                     class="btn btn-primary   ripple"
                                                                                                     type="button">
                                                                                                     <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                         class="icon m-0"
                                                                                                         width="24"
                                                                                                         height="24"
                                                                                                         viewBox="0 0 24 24"
                                                                                                         stroke-width="2"
                                                                                                         stroke="currentColor"
                                                                                                         fill="none"
                                                                                                         stroke-linecap="round"
                                                                                                         stroke-linejoin="round">
                                                                                                         <path
                                                                                                             stroke="none"
                                                                                                             d="M0 0h24v24H0z"
                                                                                                             fill="none">
                                                                                                         </path>
                                                                                                         <path
                                                                                                             d="M12 5l0 14">
                                                                                                         </path>
                                                                                                         <path
                                                                                                             d="M5 12l14 0">
                                                                                                         </path>
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
                                                                 <div class="tab-pane fade" id="pills-profile-edit"
                                                                     role="tabpanel"
                                                                     aria-labelledby="pills-profile-tab">
                                                                     <div class="mt-3">
                                                                         <div class="row" id="packagesubcat">
                                                                             <div class="col-md-4">
                                                                                 <button type="button"
                                                                                     class="packagesubedit btn btn-inverse-primary btn-sm">Floor
                                                                                     Cleaning</button>

                                                                             </div>
                                                                             <div class="col-md-4">
                                                                                 <button type="button"
                                                                                     class="packagesubedit btn btn-inverse-secondary btn-sm">Home
                                                                                     Cleaning</button>

                                                                             </div>
                                                                             <div class="col-md-4">
                                                                                 <button type="button"
                                                                                     class="packagesubedit btn btn-inverse-warning btn-sm">Office
                                                                                     Cleaning</button>
                                                                             </div>
                                                                         </div>

                                                                         <div class="packagesubshowedit mt-3"
                                                                             style="display: none;">

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
                                                                                                 <button
                                                                                                     class="btn btn-primary   ripple"
                                                                                                     type="button">
                                                                                                     <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                         class="icon m-0"
                                                                                                         width="24"
                                                                                                         height="24"
                                                                                                         viewBox="0 0 24 24"
                                                                                                         stroke-width="2"
                                                                                                         stroke="currentColor"
                                                                                                         fill="none"
                                                                                                         stroke-linecap="round"
                                                                                                         stroke-linejoin="round">
                                                                                                         <path
                                                                                                             stroke="none"
                                                                                                             d="M0 0h24v24H0z"
                                                                                                             fill="none">
                                                                                                         </path>
                                                                                                         <path
                                                                                                             d="M12 5l0 14">
                                                                                                         </path>
                                                                                                         <path
                                                                                                             d="M5 12l14 0">
                                                                                                         </path>
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
                                         </div>
                                         <div class="col-md-8 pe-0">
                                             <div id="service-table-edit">
                                                 <div class="card">
                                                     <div class="card-body p-0">
                                                         <div class="table-responsive">
                                                             <table
                                                                 class="table card-table table-vcenter text-center text-nowrap"
                                                                 id="" style="width:100%">
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

                                                                         <td><input type="number" class="form-control">
                                                                         </td>
                                                                         <td class="p-0"><input type="number"
                                                                                 class="form-control"></td>
                                                                         <td>5%</td>
                                                                         <td>$543</td>
                                                                         <td>18%</td>

                                                                         <td>
                                                                             <button class="btn btn-danger ripple"
                                                                                 type="button">
                                                                                 <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     class="icon icon-tabler icon-tabler-playstation-x m-0"
                                                                                     width="24" height="24"
                                                                                     viewBox="0 0 24 24"
                                                                                     stroke-width="2"
                                                                                     stroke="currentColor"
                                                                                     fill="none"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round">
                                                                                     <path stroke="none"
                                                                                         d="M0 0h24v24H0z"
                                                                                         fill="none"></path>
                                                                                     <path
                                                                                         d="M12 21a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9a9 9 0 0 0 -9 9a9 9 0 0 0 9 9z">
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
                                                                         <th colspan="7" style="text-align: end;">
                                                                             Total discount </th>
                                                                         <th colspan="2">5%</th>
                                                                     </tr>
                                                                     <tr>
                                                                         <th colspan="7" style="text-align: end;">
                                                                             Total tax </th>
                                                                         <th colspan="2">18%</th>
                                                                     </tr>
                                                                     <tr>
                                                                         <th colspan="7" style="text-align: end;">
                                                                             Grand total</th>
                                                                         <th colspan="2">$ 616.00</th>
                                                                     </tr>
                                                                 </thead>
                                                                 <thead id="package-total" style="display: none;">
                                                                     <tr>
                                                                         <th colspan="7" style="text-align: end;">
                                                                             Package Amount</th>
                                                                         <th colspan="2"><input type="text"
                                                                                 class="form-control"></th>
                                                                     </tr>
                                                                 </thead>
                                                             </table>
                                                         </div>

                                                     </div>
                                                 </div>
                                             </div>
                                             <div id="package-table-edit" style="display: none;">
                                                 <div class="row">
                                                     <div class="col-md-12">
                                                         <div class="mb-3">
                                                             <label class="form-label">Package Name</label>

                                                             <input type="text" value=""
                                                                 class="form-control w-50"
                                                                 placeholder="Enter Package Name">



                                                         </div>
                                                     </div>
                                                 </div>
                                                 <div class="card">
                                                     <div class="card-body p-0">


                                                         <div class="table-responsive">

                                                             <table
                                                                 class="table card-table table-vcenter text-center text-nowrap"
                                                                 id="" style="width:100%">
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
                                                                         <td>
                                                                             <textarea class="form-control" name="example-textarea-input" rows="3" placeholder="Enter Descrption">

                                                </textarea>
                                                                         </td>
                                                                         <td><input type="number" class="form-control">
                                                                         </td>
                                                                         <td class="p-0"><input type="number"
                                                                                 class="form-control"></td>
                                                                         <td>0</td>
                                                                         <td>$308.00</td>

                                                                         <td>
                                                                             <button class="btn btn-danger ripple"
                                                                                 type="button">
                                                                                 <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     class="icon icon-tabler icon-tabler-playstation-x m-0"
                                                                                     width="24" height="24"
                                                                                     viewBox="0 0 24 24"
                                                                                     stroke-width="2"
                                                                                     stroke="currentColor"
                                                                                     fill="none"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round">
                                                                                     <path stroke="none"
                                                                                         d="M0 0h24v24H0z"
                                                                                         fill="none"></path>
                                                                                     <path
                                                                                         d="M12 21a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9a9 9 0 0 0 -9 9a9 9 0 0 0 9 9z">
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
                                                                         <th colspan="7" style="text-align: end;">
                                                                             TOTAL DISCOUNT</th>
                                                                         <th colspan="2">$ 616.00</th>
                                                                     </tr>
                                                                     <tr>
                                                                         <th colspan="7" style="text-align: end;">
                                                                             Total tax </th>
                                                                         <th colspan="2">18%</th>
                                                                     </tr>
                                                                 </thead>
                                                                 <thead>
                                                                     <tr>
                                                                         <th colspan="7" style="text-align: end;">
                                                                             Package Amount</th>
                                                                         <th colspan="2"><input type="text"
                                                                                 class="form-control"></th>
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
                                                         <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                             width="24" height="24" viewBox="0 0 24 24"
                                                             stroke-width="2" stroke="currentColor" fill="none"
                                                             stroke-linecap="round" stroke-linejoin="round">
                                                             <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                             </path>
                                                             <path
                                                                 d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                                             </path>
                                                         </svg>
                                                     </div>
                                                 </div>
                                                 <div class="card-body">
                                                     <h3 class="card-title " style="color: #1F3BB3;"><b
                                                             class="me-2">ABC Pvt.
                                                             Lte.</b><span class="badge bg-red">Residential</span>
                                                     </h3>

                                                     <p class="card-p d-flex align-items-center mb-2 ">
                                                         <i class="fa-solid fa-phone me-2"
                                                             style="font-size: 14px;"></i>+91
                                                         9758697820
                                                     </p>
                                                     <p class="card-p  d-flex align-items-center mb-2">
                                                         <i class="fa-solid fa-envelope me-2"
                                                             style="font-size: 14px;"></i>abc@pvtltd.com
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
                                                     <ul class="nav nav-pills nav-pills-primary" data-bs-toggle="tabs"
                                                         role="tablist">
                                                         <li class="nav-item me-2" role="presentation">
                                                             <a href="#tab-one-edit" class="nav-link active"
                                                                 data-bs-toggle="tab" aria-selected="true"
                                                                 role="tab">Service Address</a>
                                                         </li>
                                                         <li class="nav-item me-2" role="presentation">
                                                             <a href="#tab-two-edit" class="nav-link"
                                                                 data-bs-toggle="tab" aria-selected="false"
                                                                 role="tab" tabindex="-1">Billing
                                                                 Address</a>
                                                         </li>
                                                         <li class="nav-item me-2" role="presentation">
                                                             <a href="#tab-three-edit" class="nav-link"
                                                                 data-bs-toggle="tab" aria-selected="false"
                                                                 role="tab" tabindex="-1">Additional Info</a>
                                                         </li>


                                                     </ul>
                                                     <div class="tab-content">
                                                         <div class="tab-pane active show" id="tab-one-edit"
                                                             role="tabpanel">
                                                             <div class="row my-3">
                                                                 <div class="col-lg-4 col-md-4 col-sm-12">
                                                                     <label for="radio-card-112" class="radio-card">
                                                                         <input type="radio" name="radio-card"
                                                                             id="radio-card-112" checked />
                                                                         <div class="card-content-wrapper">
                                                                             <span class="check-icon"></span>
                                                                             <div class="card-content">
                                                                                 <h4>Sky Enterprice</h4>
                                                                                 <p class="mb-1"> <strong>Contact
                                                                                         No:</strong>1234567890</p>
                                                                                 <p class="mb-1"> <strong>Email
                                                                                         ID:</strong>ABC@gmail.com</p>

                                                                                 <p class="mb-1">
                                                                                     <strong>Address:</strong>8 Shopping
                                                                                     Centre, 9 Bishan Place,
                                                                                     Singapore 579837
                                                                                 </p>
                                                                                 <p class="mb-1"><strong>Unit
                                                                                         No:</strong>12345h</p>
                                                                                 <p class="mb-1">
                                                                                     <strong>Zone:</strong>South</p>
                                                                                 <div class="form-check">
                                                                                     <input class="form-check-input"
                                                                                         type="radio"
                                                                                         name="flexRadioDefault"
                                                                                         id="flexRadioDefault2" checked>
                                                                                     <label class="form-check-label"
                                                                                         for="flexRadioDefault2">
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
                                                                     <button type="button"
                                                                         class="btn btn-blue add_btn_edit">+ Add
                                                                         Address</button>
                                                                 </div>
                                                                 <div class="col-md-12 add_address_edit"
                                                                     style="display: none;">



                                                                     <div class="row my-3">
                                                                         <div class="col-md-4">
                                                                             <div class="form-group mb-3">
                                                                                 <label for="name">Person Incharge
                                                                                     Name</label>

                                                                                 <input type="text"
                                                                                     placeholder="Enter Name"
                                                                                     name="name" class="form-control"
                                                                                     required="">
                                                                             </div>
                                                                         </div>
                                                                         <div class="col-md-4">
                                                                             <div class="form-group mb-3">
                                                                                 <label for="name">Contact No</label>
                                                                                 <input type="text"
                                                                                     placeholder="Enter Number"
                                                                                     name="name" class="form-control"
                                                                                     required="">
                                                                             </div>
                                                                         </div>
                                                                         <div class="col-md-4">
                                                                             <div class="form-group mb-3">
                                                                                 <label for="name">Email Id</label>
                                                                                 <input type="text"
                                                                                     placeholder="Enter Email"
                                                                                     name="name" class="form-control"
                                                                                     required="">
                                                                             </div>
                                                                         </div>
                                                                         <div class="col-md-4">
                                                                             <div class="form-group mb-3">
                                                                                 <label for="name">Postal Code</label>
                                                                                 <input type="text"
                                                                                     placeholder="Enter Code"
                                                                                     name="name" class="form-control"
                                                                                     required="">
                                                                             </div>
                                                                         </div>
                                                                         <div class="col-md-4">
                                                                             <div class="form-group mb-3">
                                                                                 <label for="name">Zone</label>
                                                                                 <input type="text"
                                                                                     placeholder="Enter Zone"
                                                                                     name="name" class="form-control"
                                                                                     required="">
                                                                             </div>
                                                                         </div>
                                                                         <div class="col-md-4">
                                                                             <div class="form-group mb-3">
                                                                                 <label for="name">Address</label>
                                                                                 <input type="text"
                                                                                     placeholder="Enter Address"
                                                                                     name="name" class="form-control"
                                                                                     required="">
                                                                             </div>
                                                                         </div>
                                                                         <div class="col-md-4">
                                                                             <div class="form-group mb-3">
                                                                                 <label for="name">Country</label>
                                                                                 <select type="text"
                                                                                     class="form-select" value="">
                                                                                     <option value="11">India</option>
                                                                                     <option value="39">Singapore
                                                                                     </option>
                                                                                 </select>
                                                                             </div>
                                                                         </div>
                                                                         <div class="col-md-4">
                                                                             <div class="form-group mb-3">
                                                                                 <label for="name">Unit No</label>
                                                                                 <input type="text"
                                                                                     placeholder="Enter Unit No."
                                                                                     name="name" class="form-control"
                                                                                     required="">
                                                                             </div>
                                                                         </div>
                                                                         <div class="col-md-4 my-auto">

                                                                             <button type="button"
                                                                                 class="btn btn-blue add-row-edit"
                                                                                 id="rowAdder-edit">+</button>
                                                                         </div>

                                                                         <!-- <div class="col-md-1" style="display: flex; align-items: center;">
      
                                                  <button type="button" class="btn btn-danger" id="DeleteRow">-</button>
                                                </div> -->
                                                                     </div>
                                                                     <div id="newinput-edit"></div>
                                                                     <div class="row">
                                                                         <div class="col-md-12">
                                                                             <button type="button"
                                                                                 class="btn btn-primary">save</button>
                                                                         </div>
                                                                     </div>
                                                                 </div>

                                                             </div>


                                                         </div>
                                                         <div class="tab-pane" id="tab-two-edit" role="tabpanel">
                                                             <div class="row my-3">
                                                                 <div class="col-md-12">
                                                                     <div class="my-3">
                                                                         <label class="form-check-label">
                                                                             <input type="checkbox"
                                                                                 class="form-check-input">
                                                                             Same as Service Address
                                                                             <i class="input-helper"></i></label>
                                                                     </div>
                                                                 </div>
                                                                 <div class="col-lg-4 col-md-4 col-sm-12">
                                                                     <label for="radio-card-4545" class="radio-card">
                                                                         <input type="radio" name="radio-card"
                                                                             id="radio-card-4545" checked />
                                                                         <div class="card-content-wrapper">
                                                                             <span class="check-icon"></span>
                                                                             <div class="card-content">
                                                                                 <h4>Jhone Doe</h4>
                                                                                 <p class="mb-1">
                                                                                     <strong>Address:</strong>8 Shopping
                                                                                     Centre, 9 Bishan Place,
                                                                                     Singapore 579837
                                                                                 </p>
                                                                                 <p class="mb-1"><strong>Unit
                                                                                         No:</strong>12345h</p>
                                                                                 <div class="form-check">
                                                                                     <input class="form-check-input"
                                                                                         type="radio"
                                                                                         name="flexRadioDefault"
                                                                                         id="flexRadioDefault22" checked>
                                                                                     <label class="form-check-label"
                                                                                         for="flexRadioDefault22">
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
                                                                     <button type="button"
                                                                         class="btn btn-blue add_btn_2edit">+ Add
                                                                         Address</button>
                                                                 </div>

                                                                 <div class="col-md-12 add_address_2edit"
                                                                     style="display: none;">

                                                                     <div class="table-responsive mb-3">
                                                                         <table
                                                                             class="table card-table table-vcenter text-nowrap table-transparent"
                                                                             id="billing_address_add_quot">
                                                                             <thead>
                                                                                 <tr>
                                                                                     <th>Postal Code</th>
                                                                                     <th>Address</th>
                                                                                     <th>Country</th>
                                                                                     <th>
                                                                                         <button type="button"
                                                                                             class="btn btn-blue add-row-edit">+</button>

                                                                                     </th>
                                                                                 </tr>
                                                                             </thead>
                                                                             <tbody>
                                                                                 <tr>
                                                                                     <td>
                                                                                         <input class="form-control"
                                                                                             type="text"
                                                                                             placeholder="Enter Code" />
                                                                                     </td>
                                                                                     <td>
                                                                                         <input class="form-control"
                                                                                             type="text"
                                                                                             placeholder="Address" />
                                                                                     </td>
                                                                                     <td>
                                                                                         <select type="text"
                                                                                             class="form-select"
                                                                                             value="">
                                                                                             <option value="111">
                                                                                                 Singaore</option>
                                                                                             <option value="329">India
                                                                                             </option>
                                                                                         </select>
                                                                                     </td>
                                                                                     <td>
                                                                                         <button type="button"
                                                                                             class="btn btn-danger delete-row-edit">-</button>
                                                                                     </td>
                                                                                 </tr>
                                                                             </tbody>
                                                                         </table>
                                                                     </div>

                                                                     <div class="text-end">
                                                                         <button type="button"
                                                                             class="btn btn-blue">save</button>
                                                                     </div>
                                                                 </div>

                                                             </div>
                                                         </div>
                                                         <div class="tab-pane" id="tab-three-edit" role="tabpanel">
                                                             <div class="row mt-3">
                                                                 <div
                                                                     class="form-group col-lg-6 col-md-6 col-sm-12 text-start">
                                                                     <label for="message-text"
                                                                         class="col-form-label">Deposite Type</label>
                                                                     <select class="form-control">
                                                                         <option>Select Option</option>
                                                                         <option>$50</option>
                                                                         <option>waive</option>
                                                                         <option>Don’t need</option>
                                                                     </select>
                                                                 </div>


                                                                 <div
                                                                     class="form-group col-lg-6 col-md-6 col-sm-12 text-start">
                                                                     <label for="message-text"
                                                                         class="col-form-label">Date Of Cleaning</label>
                                                                     <input class="form-control"
                                                                         placeholder="dd/mm/yyyy">
                                                                 </div>
                                                                 <div
                                                                     class="form-group col-lg-6 col-md-6 col-sm-12 text-start">
                                                                     <label for="message-text"
                                                                         class="col-form-label">Time of Cleaning</label>
                                                                     <input type="text" class="form-control"
                                                                         placeholder="Time of Cleaning">
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
                                                         <svg xmlns="http://www.w3.org/2000/svg"
                                                             class="icon icon-tabler icon-tabler-map-pin" width="24"
                                                             height="24" viewBox="0 0 24 24" stroke-width="2"
                                                             stroke="currentColor" fill="none"
                                                             stroke-linecap="round" stroke-linejoin="round">
                                                             <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                             </path>
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
                                                                 <table
                                                                     class="table card-table table-vcenter text-center text-nowrap datatable">
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
                                                                 <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                                     width="24" height="24" viewBox="0 0 24 24"
                                                                     stroke-width="2" stroke="currentColor"
                                                                     fill="none" stroke-linecap="round"
                                                                     stroke-linejoin="round">
                                                                     <path stroke="none" d="M0 0h24v24H0z"
                                                                         fill="none"></path>
                                                                     <path
                                                                         d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                                                     </path>
                                                                 </svg>
                                                             </div>
                                                         </div>
                                                         <div class="card-body">
                                                             <h3 class="card-title mb-1" style="color: #1F3BB3;"><b
                                                                     class="me-2">Customer Details</b>
                                                             </h3>

                                                             <p class="m-0">
                                                                 <i class="fa-solid fa-user me-2 pt-1"
                                                                     style="font-size: 14px;"></i>
                                                                 Jhone Doe
                                                             </p>
                                                             <p class="m-0">
                                                                 <i class="fa-solid fa-phone me-2 pt-1"
                                                                     style="font-size: 14px;"></i>
                                                                 +91-9737155901
                                                             </p>
                                                             <!-- <p class="m-0">
                                            <i class="fa-solid fa-map-pin me-2 pt-1" style="font-size: 14px;"></i>
                                            103 Rasadhi Appartment Wadaj Ahmedabad
                                            380004.
                                          </p> -->

                                                             <hr class="my-3">
                                                             <h3 class="card-title mb-1" style="color: #1F3BB3;"><b
                                                                     class="me-2">Amount Details</b>
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
                                 <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0"
                                     aria-valuemin="0" aria-valuemax="100"></div>
                             </div>
                         </div>

                     </form>

                 </div>

                 <!-- <div class="modal-footer">
                              <button type="button" class="btn me-auto sw-btn-prev sw-btn">Previous</button>
                              <button type="button" class="btn btn-primary next-btn" >Next</button>
                          </div> -->
             </div>
         </div>
     </div>
     <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
     <script src="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js"></script>
     <script src="{{ asset('public/theme/dist/js/smart-wizaed.js') }}" type="text/javascript"></script>

     <script>
         $(function() {
             $('#billing_address_33 .add-row').click(function() {
                 var template =
                     '<tr><td><input class="form-control" type="text" placeholder="Enter Code" /></td><td><input class="form-control" type="text" placeholder="Address"/></td><td><input class="form-control" type="text" placeholder="Enter Unit No"/></td><td><button type="button" class="btn btn-danger delete-row">-</button></td></tr>';
                 $('#billing_address_33 tbody').append(template);
             });
             $('#billing_address_33').on('click', '.delete-row', function() {
                 $(this).parent().parent().remove();
             });
         })
     </script>
     <script>
         $(function() {
             $('#service_address .add-row').click(function() {
                 var template =
                     '<tr><td><input class="form-control" type="text" placeholder="Enter Code" /></td><td><input class="form-control" type="text" placeholder="Address"/></td><td><input class="form-control" type="text" placeholder="Enter Unit No"/></td><td><button type="button" class="btn btn-danger delete-row">-</button></td></tr>';
                 $('#service_address tbody').append(template);
             });
             $('#service_address').on('click', '.delete-row', function() {
                 $(this).parent().parent().remove();
             });
         })
     </script>
     <script>
         $('#smartwizard').smartWizard({
             transition: {
                 animation: 'slideHorizontal', // Effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
             }
         });
         $('#smartwizard-edit').smartWizard({
             transition: {
                 animation: 'slideHorizontal', // Effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
             }
         });
         $('#smartwizard2').smartWizard({
             transition: {
                 animation: 'slideHorizontal', // Effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
             }
         });
         $('#smartwizard-3').smartWizard({
             transition: {
                 animation: 'slideHorizontal', // Effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
             }
         });
     </script>
     <script>
         (function($) {
             var CheckboxDropdown = function(el) {
                 var _this = this;
                 this.isOpen = false;
                 this.areAllChecked = false;
                 this.$el = $(el);
                 this.$label = this.$el.find('.dropdown-label');
                 this.$checkAll = this.$el.find('[data-toggle="check-all"]').first();
                 this.$inputs = this.$el.find('[type="checkbox"]');

                 this.onCheckBox();

                 this.$label.on('click', function(e) {
                     e.preventDefault();
                     _this.toggleOpen();
                 });

                 this.$checkAll.on('click', function(e) {
                     e.preventDefault();
                     _this.onCheckAll();
                 });

                 this.$inputs.on('change', function(e) {
                     _this.onCheckBox();
                 });
             };

             CheckboxDropdown.prototype.onCheckBox = function() {
                 this.updateStatus();
             };

             CheckboxDropdown.prototype.updateStatus = function() {
                 var checked = this.$el.find(':checked');

                 this.areAllChecked = false;
                 this.$checkAll.html('Check All');

                 if (checked.length <= 0) {
                     this.$label.html('Select Options');
                 } else if (checked.length === 1) {
                     this.$label.html(checked.parent('label').text());
                 } else if (checked.length === this.$inputs.length) {
                     this.$label.html('All Selected');
                     this.areAllChecked = true;
                     this.$checkAll.html('Uncheck All');
                 } else {
                     this.$label.html(checked.length + ' Selected');
                 }
             };

             CheckboxDropdown.prototype.onCheckAll = function(checkAll) {
                 if (!this.areAllChecked || checkAll) {
                     this.areAllChecked = true;
                     this.$checkAll.html('Uncheck All');
                     this.$inputs.prop('checked', true);
                 } else {
                     this.areAllChecked = false;
                     this.$checkAll.html('Check All');
                     this.$inputs.prop('checked', false);
                 }

                 this.updateStatus();
             };

             CheckboxDropdown.prototype.toggleOpen = function(forceOpen) {
                 var _this = this;

                 if (!this.isOpen || forceOpen) {
                     this.isOpen = true;
                     this.$el.addClass('on');
                     $(document).on('click', function(e) {
                         if (!$(e.target).closest('[data-control]').length) {
                             _this.toggleOpen();
                         }
                     });
                 } else {
                     this.isOpen = false;
                     this.$el.removeClass('on');
                     $(document).off('click');
                 }
             };

             var checkboxesDropdowns = document.querySelectorAll('[data-control="checkbox-dropdown"]');
             for (var i = 0, length = checkboxesDropdowns.length; i < length; i++) {
                 new CheckboxDropdown(checkboxesDropdowns[i]);
             }
         })(jQuery);
     </script>
     <script type="text/javascript">
         $("#rowAdder_22").click(function() {
             newRowAdd =
                 '<div class="row my-3" id="row">  <div class="col-md-4">' +
                 '<div class="form-group mb-3">' +
                 ' <label for="name">Person Incharge Name</label><input type="text" placeholder="Enter Name" name="name" class="form-control" required=""></div></div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"> <label for="name">Contact No</label><input type="text" placeholder="Enter Number" name="name" class="form-control" required=""> </div> </div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Email Id</label><input type="text" placeholder="Enter Email" name="name" class="form-control" required=""></div> </div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Postal Code</label><input type="text" placeholder="Enter Code" name="name" class="form-control" required=""></div> </div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Zone</label><input type="text" placeholder="Enter Zone" name="name" class="form-control" required=""></div> </div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Address</label><input type="text" placeholder="Enter Address" name="name" class="form-control" required=""> </div> </div>' +
                 '<div class="col-md-3"><div class="form-group mb-3"> <label for="name">Unit No</label><input type="text" placeholder="Enter Unit No." name="name" class="form-control" required=""> </div> </div>' +
                 '<div class="col-md-1" style="display: flex; align-items: center;">  <button type="button" class="btn btn-danger" id="DeleteRow">-</button></div></div>';

             $('#newinput_22').append(newRowAdd);
         });

         $("body").on("click", "#DeleteRow", function() {
             $(this).parents("#row").remove();
         })
     </script>

     <script type="text/javascript">
         $("#rowAdder-2").click(function() {
             newRowAdd =
                 '<div class="row my-3" id="row">  <div class="col-md-4">' +
                 '<div class="form-group mb-3">' +
                 ' <label for="name">Person Incharge Name</label><input type="text" placeholder="Enter Name" name="name" class="form-control" required=""></div></div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"> <label for="name">Contact No</label><input type="text" placeholder="Enter Number" name="name" class="form-control" required=""> </div> </div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Email Id</label><input type="text" placeholder="Enter Email" name="name" class="form-control" required=""></div> </div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Postal Code</label><input type="text" placeholder="Enter Code" name="name" class="form-control" required=""></div> </div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Zone</label><input type="text" placeholder="Enter Zone" name="name" class="form-control" required=""></div> </div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Address</label><input type="text" placeholder="Enter Address" name="name" class="form-control" required=""> </div> </div>' +
                 '<div class="col-md-3"><div class="form-group mb-3"> <label for="name">Unit No</label><input type="text" placeholder="Enter Unit No." name="name" class="form-control" required=""> </div> </div>' +
                 '<div class="col-md-1" style="display: flex; align-items: center;">  <button type="button" class="btn btn-danger" id="DeleteRow-2">-</button></div></div>';

             $('#newinput-2').append(newRowAdd);
         });

         $("body").on("click", "#DeleteRow-2", function() {
             $(this).parents("#row").remove();
         })
     </script>
     <script>
         $(function() {
             $('#billing_address_add_lead .add-row').click(function() {
                 var template =
                     '<tr><td><input class="form-control" type="text" placeholder="Enter Code" /></td><td><input class="form-control" type="text" placeholder="Address"/></td><td><select type="text" class="form-select" value=""><option value="121">Singapore</option><option value="329">India</option></select></td><td><button type="button" class="btn btn-danger delete-row">-</button></td></tr>';
                 $('#billing_address_add_lead tbody').append(template);
             });
             $('#billing_address_add_lead').on('click', '.delete-row', function() {
                 $(this).parent().parent().remove();
             });
         })
     </script>
     <script>
         $(function() {
             $('#billing_address_add_quot .add-row-edit').click(function() {
                 var template =
                     '<tr><td><input class="form-control" type="text" placeholder="Enter Code" /></td><td><input class="form-control" type="text" placeholder="Address"/></td><td><select type="text" class="form-select" value=""><option value="121">Singapore</option><option value="329">India</option></select></td><td><button type="button" class="btn btn-danger delete-row-edit">-</button></td></tr>';
                 $('#billing_address_add_quot tbody').append(template);
             });
             $('#billing_address_add_quot').on('click', '.delete-row-edit', function() {
                 $(this).parent().parent().remove();
             });
         })
     </script>
     <script>
         $(function() {
             $('#billing_address_34 .add-row').click(function() {
                 var template =
                     '<tr><td><input class="form-control" type="text" placeholder="Enter Code" /></td><td><input class="form-control" type="text" placeholder="Address"/></td><td><input class="form-control" type="text" placeholder="Enter Unit No"/></td><td><button type="button" class="btn btn-danger delete-row">-</button></td></tr>';
                 $('#billing_address_34 tbody').append(template);
             });
             $('#billing_address_34').on('click', '.delete-row', function() {
                 $(this).parent().parent().remove();
             });
         })
     </script>
     <script>
         $(function() {
             $('#billing_address_2 .add-row-2').click(function() {
                 var template =
                     '<tr><td><input class="form-control" type="text" placeholder="Enter Code" /></td><td><input class="form-control" type="text" placeholder="Address"/></td><td><input class="form-control" type="text" placeholder="Enter Unit No"/></td><td><button type="button" class="btn btn-danger delete-row-2">-</button></td></tr>';
                 $('#billing_address_2 tbody').append(template);
             });
             $('#billing_address_2').on('click', '.delete-row-2', function() {
                 $(this).parent().parent().remove();
             });
         })
     </script>
     <script>
         $(document).ready(function() {
             $('#myselection').on('change', function() {
                 var demovalue = $(this).val();
                 $("div.myDiv").hide();
                 $("#show" + demovalue).show();
             });
         });
     </script>
     <script type="text/javascript">
         $("#rowAdder").click(function() {
             newRowAdd =
                 '<div class="row my-3" id="row">  <div class="col-md-4">' +
                 '<div class="form-group mb-3">' +
                 ' <label for="name">Person Incharge Name</label><input type="text" placeholder="Enter Name" name="name" class="form-control" required=""></div></div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"> <label for="name">Contact No</label><input type="text" placeholder="Enter Number" name="name" class="form-control" required=""> </div> </div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Email Id</label><input type="text" placeholder="Enter Email" name="name" class="form-control" required=""></div> </div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Postal Code</label><input type="text" placeholder="Enter Code" name="name" class="form-control" required=""></div> </div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Zone</label><input type="text" placeholder="Enter Zone" name="name" class="form-control" required=""></div> </div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Address</label><input type="text" placeholder="Enter Address" name="name" class="form-control" required=""> </div> </div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Country</label><select type="text" class="form-select" value=""><option value="11">India</option><option value="39">Singaore</option></select> </div> </div>' +
                 '<div class="col-md-3"><div class="form-group mb-3"> <label for="name">Unit No</label><input type="text" placeholder="Enter Unit No." name="name" class="form-control" required=""> </div> </div>' +
                 '<div class="col-md-1" style="display: flex; align-items: center;">  <button type="button" class="btn btn-danger" id="DeleteRow">-</button></div></div>';

             $('#newinput').append(newRowAdd);
         });

         $("body").on("click", "#DeleteRow", function() {
             $(this).parents("#row").remove();
         })
     </script>
     <script type="text/javascript">
         $("#rowAdder-edit").click(function() {
             newRowAdd =
                 '<div class="row my-3" id="row">  <div class="col-md-4">' +
                 '<div class="form-group mb-3">' +
                 ' <label for="name">Person Incharge Name</label><input type="text" placeholder="Enter Name" name="name" class="form-control" required=""></div></div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"> <label for="name">Contact No</label><input type="text" placeholder="Enter Number" name="name" class="form-control" required=""> </div> </div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Email Id</label><input type="text" placeholder="Enter Email" name="name" class="form-control" required=""></div> </div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Postal Code</label><input type="text" placeholder="Enter Code" name="name" class="form-control" required=""></div> </div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Zone</label><input type="text" placeholder="Enter Zone" name="name" class="form-control" required=""></div> </div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Address</label><input type="text" placeholder="Enter Address" name="name" class="form-control" required=""> </div> </div>' +
                 '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Country</label><select type="text" class="form-select" value=""><option value="11">India</option><option value="39">Singaore</option></select> </div> </div>' +
                 '<div class="col-md-3"><div class="form-group mb-3"> <label for="name">Unit No</label><input type="text" placeholder="Enter Unit No." name="name" class="form-control" required=""> </div> </div>' +
                 '<div class="col-md-1" style="display: flex; align-items: center;">  <button type="button" class="btn btn-danger" id="DeleteRow-edit">-</button></div></div>';

             $('#newinput-edit').append(newRowAdd);
         });

         $("body").on("click", "#DeleteRow-edit", function() {
             $(this).parents("#row").remove();
         })
     </script>
     <script>
         $(document).ready(function() {
             $(".productshow").click(function() {
                 $("#productcat").hide();
             });
             $(".productshow").click(function() {
                 $(".allproduct").show();
             });

             $("#back").click(function() {
                 $(".allproduct").hide();
             });
             $("#back").click(function() {
                 $("#productcat").show();
             });

             $(".productsub").click(function() {
                 $(".productsubshow").show();
             });
             $(".packagesub").click(function() {
                 $(".packagesubshow").show();
             });
             $(".productsubedit").click(function() {
                 $(".productsubshowedit").show();
             });
             $(".packagesubedit").click(function() {
                 $(".packagesubshowedit").show();
             });

         });
     </script>
     <script>
         function toggler(divId) {
             $("#" + divId).toggle();
         }

         function addBtn() {
             toggler('div');

         }
     </script>
     <script>
         function toggler(divId) {
             $("#" + divId).toggle();
         }

         function addBtn2() {
             toggler('div2');

         }
     </script>
     <script>
         $(document).ready(function() {
             $("#pills-profile-tab").click(function() {
                 $("#package-table").show();
                 $("#service-table").hide();

             });
             $("#pills-home-tab").click(function() {
                 $("#package-table").hide();
                 $("#service-table").show();
             });
         });
         $(document).ready(function() {
             $("#pills-profile-tab-edit").click(function() {
                 $("#package-table-edit").show();
                 $("#service-table-edit").hide();

             });
             $("#pills-home-tab-edit").click(function() {
                 $("#package-table-edit").hide();
                 $("#service-table-edit").show();
             });
         });
     </script>
     <script>
         $(document).ready(function() {



             $("#commercial-view-table").click(function() {
                 $("#residential-card").hide();
                 $("#commercial-card").show();

             });
             $("#residential-view-table").click(function() {

                 $("#commercial-card").hide();
                 $("#residential-card").show();
             });
         });
     </script>
     <script>
         $(document).ready(function() {



             $("#commercial-edit-table").click(function() {
                 $("#residential-card-edit").hide();
                 $("#commercial-card-edit").show();

             });
             $("#residential-edit-table").click(function() {

                 $("#commercial-card-edit").hide();
                 $("#residential-card-edit").show();
             });
         });
     </script>
     <script>
         $(document).ready(function() {
             // Toggles paragraphs display
             $(".add_btn").click(function() {
                 $(".add_address").toggle();
             });
         });
         $(document).ready(function() {
             // Toggles paragraphs display
             $(".add_btn_edit").click(function() {
                 $(".add_address_edit").toggle();
             });
         });
     </script>
     <script>
         $(document).ready(function() {
             // Toggles paragraphs display
             $(".add_btn_2").click(function() {
                 $(".add_address_2").toggle();
             });
         });
     </script>
     <script>
         $(document).ready(function() {
             // Toggles paragraphs display
             $(".add_btn_2edit").click(function() {
                 $(".add_address_2edit").toggle();
             });
         });
     </script>
     <script src="{{ asset('theme/dist/libs/litepicker/dist/litepicker.js?1685976846') }}" defer></script>
     <!-- Tabler Core -->


     <script>
         // @formatter:off
         document.addEventListener("DOMContentLoaded", function() {
             window.Litepicker && (new Litepicker({
                 element: document.getElementById('datepicker-icon-prepend'),
                 buttonText: {
                     previousMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>`,
                     nextMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>`,
                 },
             }));
         });
         // @formatter:on
     </script>


     <script>
         $("input[name=time]").clockpicker({
             placement: 'bottom',
             align: 'left',
             autoclose: true,
             default: 'now',
             donetext: "Select",
             init: function() {
                 console.log("colorpicker initiated");
             },
             beforeShow: function() {
                 console.log("before show");
             },
             afterShow: function() {
                 console.log("after show");
             },
             beforeHide: function() {
                 console.log("before hide");
             },
             afterHide: function() {
                 console.log("after hide");
             },
             beforeHourSelect: function() {
                 console.log("before hour selected");
             },
             afterHourSelect: function() {
                 console.log("after hour selected");
             },
             beforeDone: function() {
                 console.log("before done");
             },
             afterDone: function() {
                 console.log("after done");
             }
         });
     </script>
     <script>
      $(document).ready(function() {
          $(".t-btn").click(function() {
              $(".d-menu").toggleClass("show");
          });
      });
  </script>
 @endsection
