@extends('theme.default')
@section('content')
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">Payment History</h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container">
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-center text-nowrap datatable" id="payment_history_table">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Customer</th>
                                <th scope="col" class="text-center">Total Amount</th>
                                <th scope="col" class="text-center">Payment Method</th>
                                <th scope="col" class="text-center">Date</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')

<script type="text/javascript">
        
    var payment_history_table = $('#payment_history_table').DataTable({
        "lengthChange": false,
        "pageLength": 30,
        ajax: {
            url: "{{ route('payment-history.get-table-data') }}",
            type: 'GET'
        }
    });
    
</script>

@endsection