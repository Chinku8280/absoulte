@extends('theme.default')

@section('custom_css')
    
@endsection

@section('content')
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            Log Report
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">
                <div class="row">
                    <div class="col-md-12">     
                        <div class="card">
                            <div class="card-header">
                                Quotation No : {{$quotation->quotation_no}}
                            </div>

                            <div class="card-body">

                                @if ($log_details->isEmpty())
                                    <div class="text-center">Data Not Found</div>
                                @else
                                
                                    <ol class="list-group list-group-numbered">
                                        @foreach ($log_details as $key => $item)
                                            <li class="list-group-item">
                                                {{$item->activity}} on {{date('d-M-Y h:i A', strtotime($item->created_at))}} by {{$item->created_by_name}}
                                            </li>
                                        @endforeach
                                    </ol> 

                                @endif    

                            </div>

                            <div class="card-footer">
                                <div class="d-flex">
                                    {{ $log_details->links() }}
                                </div>
                            </div>
                        </div>                                                                                            
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')

@endsection
