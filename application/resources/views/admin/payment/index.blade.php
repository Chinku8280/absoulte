@extends('theme.default')
@section('custom_css')
    <style>
        /* table tr th
        {
            text-align: center !important;
        } */
    </style>
@endsection
@section('content')
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">All Payment List</h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container">
                <div class="card">
                    <div class="card-body">                   
                        <div class="table-responsive">
                            <table class="table text-left caption-top" id="payment_table">
                                <thead>
                                    <tr>
                                        {{-- <th scope="col"></th> --}}
                                        <th scope="col">Customer</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Pending Invoices</th>
                                        <th scope="col">Outstanding Balance</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer as $key => $item)
                                        @if($item->pending_invoice != 0)
                                            <tr>
                                                {{-- <td scope="row">
                                                    <div>
                                                        <input class="form-check-input" type="checkbox" id="checkboxNoLabel" value="{{$item->id}}">
                                                    </div>
                                                </td> --}}
                                                <td style="text-align: left;">
                                                    @if ($item->customer_type == "residential_customer_type")
                                                        {{$item->customer_name}}
                                                    @else
                                                        {{$item->individual_company_name}}
                                                    @endif            
                                                </td>
                                                <td>+65 {{$item->mobile_number}}</td>
                                                <td>{{$item->pending_invoice}}</td>
                                                <td>${{number_format($item->open_amount, 2)}}</td>
                                                <td>
                                                    <a href="{{route('payment.recieved-payment', $item->id)}}"><span class="badge bg-info">Payment</span></a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            
            $("#payment_table").DataTable({
                "lengthChange": false,
                "pageLength": 30,
            });

            var successMessage = localStorage.getItem('successMessage');
            if (successMessage) {
                iziToast.success({
                    message: successMessage,
                    position: 'topRight',
                    timeout: false,   // disables auto-close
                    close: true       // adds a close (×) button
                });

                // Remove the message so it doesn't show again on the next refresh
                localStorage.removeItem('successMessage');
            }

        });
    </script>
@endsection