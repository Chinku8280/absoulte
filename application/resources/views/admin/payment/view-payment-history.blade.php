@extends('theme.default')

@section('custom_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
@endsection

@section('content')
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">All Payment History Details</h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-3">
                            <div class="card-header">
                                <a href="{{ url()->previous() }}">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                </a>
                                &nbsp;
                                <div class="col-md-3">
                                    Customer Details
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        Customer Name : 
                                        @if ($customer->customer_type == "residential_customer_type")
                                            {{$customer->customer_name ?? ''}}
                                        @else
                                            {{$customer->individual_company_name ?? ''}}
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        Mobile no. : +65-{{$customer->mobile_number ?? ''}}
                                    </div>
                                    <div class="col-md-3">
                                        Email : {{$customer->email ?? ''}} 
                                    </div>   
                                    <div class="col-md-3">
                                        Total Outstanding : <span id="balance_amount">${{number_format($customer->balance_amount, 2) ?? ''}}</span>
                                    </div>   
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table card-table table-vcenter text-left text-nowrap datatable" id="all_payments_table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="">S/N</th>
                                                <th scope="col" class="">Invoice No</th>
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

    {{-- reject payment modal --}}
    <div class="modal modal-blur fade" id="reject_payment_modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M12 9v2m0 4v.01"></path>
                        <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75">
                        </path>
                    </svg>
                    <h3>Are you sure?</h3>
                    <div class="text-muted">Do you really want to reject this Payment?</div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <a href="#" class="btn w-100" data-bs-dismiss="modal" data-bs-dismiss="modal">
                                    Cancel
                                </a>
                            </div>
                            <div class="col">
                                <a href="#" class="btn btn-danger w-100" id="reject_payment_modal_btn">
                                    Reject
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('javascript')

<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<script type="text/javascript">
        
    var all_payments_table = $('#all_payments_table').DataTable({
        "lengthChange": false,
        "pageLength": 30,
        ajax: {
            url: "{{ route('payment-history.view-get-table-data') }}",
            type: 'GET',
            data: {
                customer_id : "{{$customer_id}}"
            }
        }
    });

    $(document).ready(function () {
        
        // view payment proof

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
                                var html = `<a data-fancybox="gallery" href="${value.payment_proof_url}">
                                                <img src="${value.payment_proof_url}" alt="" width="100px;" style="margin-right: 10px;">
                                            </a>`;

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

        // reject payment start

        $('body').on('click', '.reject_payment_btn', function() {

            var payment_id = $(this).data('id');
            $('#reject_payment_modal').data('payment_id', payment_id);
            $('#reject_payment_modal').modal('show');

        });

        $('body').on('click', '#reject_payment_modal_btn', function() {

            var payment_id = $("#reject_payment_modal").data('payment_id');

            $.ajax({
                url: "{{ route('payment-history.reject-payment') }}",
                type: 'post',
                data: {
                    payment_id: payment_id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    console.log(result);
                    $('#reject_payment_modal').modal('hide');
                    all_payments_table.ajax.reload();

                    if (result.status == "success") {
                        
                        $("#balance_amount").text("$"+result.data.balance_amount);

                        iziToast.success({
                            message: result.message,
                            position: 'topRight'
                        });
                    } else {
                        iziToast.error({
                            message: result.message,
                            position: 'topRight'
                        });
                    }
                },
                error: function(result) {
                    console.log(result);
                }
            });

        });

        // reject payment end

    });
    
</script>

@endsection