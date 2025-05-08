@extends('theme.default')
@section('content')
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">All Payment Details</h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container">
                    <div class="table table-responsive">
                        <table class="table card-table table-vcenter text-left text-nowrap datatable" id="all_payments_table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th scope="col" class="">Invoice No</th>
                                    <th scope="col" class="">Customer</th>
                                    <th scope="col" class="">Company Name</th>
                                    <th scope="col" class="">Payment Amount</th>
                                    <th scope="col" class="">Payment Method</th>
                                    <th scope="col" class="">Payment Date</th>
                                    <th scope="col" class="">Payment Remarks</th>
                                    <th scope="col" class="">Created By</th>
                                    <th scope="col" class="">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>

    {{-- payment proof modal --}}
    <div class="modal" id="payment_proof_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Payment Proof</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body" id="payment_proof_modal_body">
                    
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('javascript')

<script type="text/javascript">
        
    var all_payments_table = $('#all_payments_table').DataTable({
        "lengthChange": false,
        "pageLength": 30,
        ajax: {
            url: "{{ route('all-payments.get-table-data') }}",
            type: 'GET'
        }
    });

    $(document).ready(function () {
        
        $('body').on('click', '.view_payment_proof_btn', function() {

            var id = $(this).data('id');

            $.ajax({
                type: "get",
                url: "{{route('all-payments.view-payment-proof')}}",
                data: {
                    id: id
                },
                success: function(result) {
                    console.log(result);

                    $("#payment_proof_modal_body").html("");

                    if (result.lead_offline_payment_details.length > 0) 
                    {                  
                        $.each(result.lead_offline_payment_details, function (key, value) { 

                            if(value.payment_proof_url)
                            {
                                var html = `<img src="${value.payment_proof_url}" alt="" width="100px;" style="margin-right: 10px;">`;

                                $("#payment_proof_modal_body").append(html);
                            }
                                                 
                        });                        
                    }

                    $("#payment_proof_modal").modal('show');
                },
                error: function(result) {
                    console.log(result);
                }
            });

        });

    });
    
</script>

@endsection