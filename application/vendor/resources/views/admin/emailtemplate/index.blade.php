@extends('theme.default')
@section('content')
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
       <div class="container-xl">
          <div class="row g-2 align-items-center">
             <div class="col">
                <h2 class="page-title">
                   Email Template
                </h2>
             </div>
             <!-- Page title actions -->
             <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                   {{-- <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                      data-bs-target="#add-email-modal" onclick="showFormModal()">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                         stroke-linecap="round" stroke-linejoin="round">
                         <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                         <path d="M12 5l0 14" />
                         <path d="M5 12l14 0" />
                      </svg>
                      Add New
                    </button> --}}
                    <a class="btn btn-primary" data-bs-toggle="modal" href="#exampleModalToggle" role="button">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                         stroke-linecap="round" stroke-linejoin="round">
                         <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                         <path d="M12 5l0 14" />
                         <path d="M5 12l14 0" />
                      </svg>
                      Add New
                    </a>
                </div>
             </div>
          </div>
          <div class="row g-2 align-items-center">
             <div class="col">
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
                   <div class="tab-content">
                      <div class="tab-pane active show" id="residential" role="tabpanel">
                         <div class="card-body border-bottom py-3">
                            <div class="d-flex">
                               <div class="text-muted">
                                  Show
                                  <div class="mx-2 d-inline-block">
                                     <input type="text" class="form-control form-control-sm"
                                        value="8" size="3" aria-label="Invoices count">
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
                         <div class="table-responsive">
                            <table id="residential-customer-table"
                               class="table card-table table-vcenter text-center text-nowrap datatable">
                               <thead>
                                  <tr>
                                     <th class="w-1">No.</th>
                                     <th>Title</th>
                                     <th>Subject</th>
                                     <th>Body</th>
                                     <th>Action</th>
                                  </tr>
                               </thead>
                                <tbody>
                                 @foreach($templates as $key => $template)
                                 <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$template->title}}</td>
                                    <td>{{$template->subject}}</td>
                                    <td>{{$template->body}}</td>
                                    <td>
                                       <a href="" class="btn btn-info"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                       <a href="" class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i></a>
                                    </td>
                                 </tr>
                                 @endforeach
                                </tbody>
                            </table>
                         </div>
                         <!-- <div class="card-footer d-flex align-items-center">
                            <p class="m-0 text-muted">Showing <span>1</span> to <span>1</span> of
                               <span>1</span> entries
                            </p>
                            <ul class="pagination m-0 ms-auto">
                            </ul>
                         </div> -->
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </div>
 </div>
 </div>
 <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalToggleLabel">Add Email Template</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{route('emailtemplate.store')}}" method="post">
            @csrf
            <div class="row mb-4">
               <div class="col-md-12 mb-4">
                  <label for=""><b>Company</b></label><br><br>
                  <select name="company_id" id="company_id" class="form-control">
                     <option value="">Select Company</option>
                     @foreach ($company as $item)
                        <option value="{{$item->id}}">{{$item->company_name}}</option>
                     @endforeach
                  </select>
               </div>
                <div class="col-md-6">
                    <label for=""><b>Title</b></label><br><br>
                    <input type="text" class="form-control" name="title" placeholder="Enter Title">
                </div>
                <div class="col-md-6">
                    <label for=""><b>Subject</b></label><br><br>
                    <input type="text" class="form-control" name="subject" placeholder="Enter Subject">
                </div>
            </div>
            <div class="mb-3">
                <label for=""><b>Body</b></label><br><br>
                <textarea name="body" id="body" cols="30" rows="10"></textarea>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>
   <script>
      ClassicEditor
            .create(document.querySelector('#body'))
            .catch(error => {
               console.error(error);
            });
   </script>
@endsection