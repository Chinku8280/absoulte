@extends('theme.default')
@section('content')
<div class="page-wrapper">
   <!-- Page header -->
   <div class="page-header d-print-none">
      <div class="container-xl">
         <div class="row g-2 align-items-center">
            <!-- Page title actions -->
            <div class="col-auto ms-auto d-print-none">
               <div class="btn-list">
                  <a href="{{route('products.create')}}" class="btn btn-primary d-none d-sm-inline-block" >
                     <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
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
      </div>
   </div>
   @if ($message = Session::get('success'))
   <div class="alert alert-success">
      <p>{{ $message }}</p>
   </div>
   @endif
   <!-- Page body -->
   <div class="page-body">
      <div class="container-xl">
         <div class="row">
            <div class="col-lg-12">
               <div class="tab-content">
                  <div class="tab-pane active show" id="residential" role="tabpanel">
                     <div class="card">
                        <div class="card-header">
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
                        <div class="card-body p-0">
                           <div class="table-responsive">
                              <table
                                 class="table card-table table-vcenter text-center text-nowrap datatable">
                                 <thead>
                                    <tr>
                                       <th class="w-1">No.</th>
                                       <th>Name</th>
                                       <th>Description</th>
                                       <th>Action</th>
                                    </tr>
                                    @foreach ($products as $product)
                                    <tr>
                                       <td>1</td>
                                       <td>{{ $product->name }}</td>
                                       <td>{{ $product->description }}</td>
                                       <td>
                                          <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                                             <a class="btn btn-info" href="{{ route('products.show',$product->id) }}">Show</a>
                                             <a class="btn btn-primary" href="{{ route('products.edit',$product->id) }}">Edit</a>
                                             @csrf
                                             @method('DELETE')
                                             <button type="submit" class="btn btn-danger">Delete</button>
                                          </form>
                                       </td>
                                    </tr>
                                    @endforeach       
                                 </thead>
                                 <tbody>
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
@endsection