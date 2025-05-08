@extends('theme.default')
@section('content')
 <div class="row my-3">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <!-- <h2> Show Role</h2> -->
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('users.index') }}"> Back </a>
        </div>
    </div>
</div> 
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="card">
           <div class="card-header">
            <h3 class="card-title text-primary">{{ucfirst($user->name)}} Detail </h3>
            <div class="card-actions">
              <!-- <a class="btn btn-primary" href="{{ route('roles.index') }}"> Back </a> -->
            </div>
          </div>
            <div class="card-body">
          <div class="form-group mb-3">
            <strong>Name:</strong>
            {{ $user->name }}
        </div>
        <div class="form-group mb-3">
            <strong>Email:</strong>
            {{ $user->email }}
        </div>
         <div class="form-group mb-3">
            <strong>Permissions:</strong>
           @if(!empty($user->getRoleNames()))
                @foreach($user->getRoleNames() as $v)
                    <label class="badge badge-success">{{ $v }}</label>
                @endforeach
            @endif
        </div>
            </div>
        </div>
      
    </div>
   
</div>
@endsection


























