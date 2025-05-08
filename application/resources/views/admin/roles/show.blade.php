@extends('theme.default')
@section('content')
 <div class="row my-3">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <!-- <h2> Show Role</h2> -->
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('roles.index') }}"> Back </a>
        </div>
    </div>
</div> 
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="card">
           <div class="card-header">
            <h3 class="card-title">{{ $role->name }} Role Details</h3>
            <div class="card-actions">
              <!-- <a class="btn btn-primary" href="{{ route('roles.index') }}"> Back </a> -->
            </div>
          </div>
            <div class="card-body">
          <div class="form-group mb-3">
            <strong>Name:</strong>
            {{ $role->name }}
        </div>
         <div class="form-group mb-3">
            <strong>Permissions:</strong>
            @if(!empty($rolePermissions))
                @foreach($rolePermissions as $v)
                <span class="badge"><label class="label label-success">{{ $v->name }}</label></span>
                @endforeach
            @endif
        </div>
            </div>
        </div>
      
    </div>
   
</div>
@endsection