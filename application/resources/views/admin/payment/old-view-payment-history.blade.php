@extends('theme.default')
@section('content')
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">Received Payment</h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container mt-3">                            
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Payment Proof</label><br>
                                <img src="{{asset('application/public/uploads/payment_proof/'.$payment_history->payment_proof)}}" width="100px;">       
                            </div>
                            <div class="col-md-6">
                                <label for="">Amount</label>
                                <input type="number" id="totalamount_1" readonly placeholder="0.00" class="form-control" value="{{$payment_history->total_amount}}" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="col-md-3">
                            Payment Details
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table table-responsive">
                            <table class="table caption-top" id="payment_list_table">
                                <caption>All Payment List</caption>
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Quotation Id</th>
                                        <th scope="col">Invoice no</th>
                                        <th scope="col">Original amount</th>
                                        <th scope="col">Payment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payment_history_details as $key => $item)        
                                        <tr>
                                            <td scope="row"></td>
                                            <td>{{$item->quotation_id}}</td>
                                            <td>{{$item->invoice_no}}</td>
                                            <td>${{$item->grand_total}}</td>
                                            <td>${{$item->payment_amount}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td>${{$payment_history->total_amount}}</td>
                                    </tr>                                        
                                </tfoot>
                            </table>                           
                        </div>
                    </div>
                </div>           
            </div>
        </div>
    </div>
@endsection
