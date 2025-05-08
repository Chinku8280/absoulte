@extends('theme.default') 
@section('content') 
<div class="page-wrapper">
  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">
          <div class="btn-list">
            <a href="{{route('users.index')}}" class="btn btn-primary d-none d-sm-inline-block">
              <!-- Download SVG icon from http://tabler-icons.io/i/plus --> Back
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="page-body">
    <div class="container-xl">
      
      <div class="row"></div> @if (count($errors) > 0) <div class="alert alert-danger">
        <strong>Whoops!</strong> Something went wrong. <br>
        <br>
        <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
      </div> @endif <div class="page-body">
        <div class="container-xl">
          <div class="row">
            <div class="col-lg-12">
              <div class="tab-content">
                <div class="tab-pane active show" id="residential" role="tabpanel">
                  <div class="card">
                    <div class="card-header">
                      <div class="text-muted">
                        <div class="col-lg-12 margin-tb">
                          <div class="pull-left">
                            <h2>Create New User</h2>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                     <form action="{{route('users.store')}}" method="post">
                       @csrf
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="form-group mb-3">
                            <strong>Name:</strong> 
            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                          </div>
                        </div>
                         <div class="col-sm-12">
                          <div class="form-group mb-3">
                            <strong>Email:</strong> 
            {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                          </div>
                        </div>
                         <div class="col-sm-12">
                          <div class="form-group mb-3">
                            <strong>Password:</strong> 
            {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
                          </div>
                        </div>
                       
                          <div class="col-sm-12">
                          <div class="form-group mb-3">
                            <strong>Confirm Password:</strong> 
            {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                          </div>
                        </div>
                       
                          <div class="col-sm-12">
                          <div class="form-group mb-3">
                            <strong>Role:</strong> 
            {!! Form::select('roles[]', $roles,[], array('class' => 'form-control')) !!}
                          </div>
                        </div>
                       
                         <div class="col-xs-12 col-sm-12 col-md-12 mt-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                          </div>
                       
                      </div>
                    </div>
                  </div>
                 </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
 @endsection























