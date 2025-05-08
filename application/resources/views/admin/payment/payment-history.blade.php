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
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="">
                                    <table class="table card-table table-vcenter text-left text-nowrap datatable" id="payment_history_table">
                                        <thead>
                                            <tr>
                                                <th scope="col">No.</th>
                                                <th scope="col">Customer</th>
                                                <th scope="col">Phone</th>
                                                <th scope="col">No. of Payments</th>
                                                <th scope="col">Action</th>
                                            </tr>
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
@endsection

@section('javascript')

<script type="text/javascript">
        
    var payment_history_table = $('#payment_history_table').DataTable({
        "lengthChange": false,
        "pageLength": 30,
        ajax: {
            url: "{{ route('payment-history.get-table-data') }}",
            type: 'GET'
        },
        'columnDefs': [{
            'targets': [0],        // Targets the first column (index 0)
            'orderable': false,    // Disables sorting on this column
        }]
    }).on('order.dt search.dt', function() {
        var table = $('#payment_history_table').DataTable();
        table.column(0, {order:'applied'}).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;  // Update the serial number
        });
    }).draw();
    
</script>

@endsection