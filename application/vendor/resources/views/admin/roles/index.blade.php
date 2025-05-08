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
                  <a href="{{route('roles.create')}}" class="btn btn-primary d-none d-sm-inline-block" >
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
        <h3 class="card-title">Roles</h3>

    </div>
    <div class="card-body border-bottom py-3">
        <div class="d-flex">
            <div class="text-muted">
                Show
                <div class="mx-2 d-inline-block">
                    <input type="text" class="form-control form-control-sm" value="8" size="3"
                        aria-label="Invoices count">
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
        <table class="table card-table table-vcenter text-center text-nowrap datatable">
            <thead>
                <tr>
                    <th class="w-1">No.</th>
                    <th>Name</th>
                    <th class="text-end w-25">Action</th>
                </tr>
                <?php $i = 1;?>
                @foreach ($roles as $key => $role)

                <tr>

                    <td>{{ $i++ }}</td>
                    <td>{{ $role->name }}</td>
                    <td class="text-end">
                        <a href="{{ route('roles.show',$role->id) }}" class="me-2"><i class="fas fa-eye"></i></a>
                        <!-- <a class="btn btn-primary" href="{{ route('roles.edit',$role->id) }}">Edit</a>
                                             {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                                                     {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                                 {!! Form::close() !!} -->
                        @can('role-edit')
                        <a href="{{ route('roles.edit',$role->id) }}" class="me-2"><i class="fas fa-edit"></i></a>
                        @endcan
                        @can('role-delete')
                      <form method="post" action="{{route('roles.destroy',$role->id)}}" style="display: inline-block;">
                            @method('delete')
                            @csrf
                            <button style="none"><a class="me-2"><i class="fas fa-trash"></i></a></button>
                        </form>


                        @endcan
                    </td>
                </tr>
                @endforeach
            </thead>
            <tbody>
            </tbody>
        </table>
      
    </div>
    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-muted">Showing <span>1</span> to <span>1</span> of
           <span>1</span> entries
        </p>
        <ul class="pagination m-0 ms-auto">
        </ul>
     </div>
</div>
                  </div>
                  <div class="tab-pane" id="commercial" role="tabpanel">
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
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <footer class="footer footer-transparent d-print-none">
   </footer>
</div>
@endsection