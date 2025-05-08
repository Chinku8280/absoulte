@extends('theme.default')

@section('custom_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">

    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            border: 1px solid #e6e7e9 !important;
            padding: 0.4375rem 2.25rem 0.4375rem 0.75rem !important;
            height: 36px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1d273b !important;
            font-size: .875rem !important;
            font-weight: 400 !important;
            line-height: normal !important;
            padding-left: 0 !important;
            padding-right: 0 !important;

        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 26px;
            position: absolute;
            top: 3px !important;
            right: 5px !important;
            width: 20px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #206bc4 !important;
            color: white;
        }
        
        .highlighted-date {
            background-color: #ffcc00;
            /* Yellow background */
            color: #000;
            /* Black text color */
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            {{-- Transaction History --}}
                            Customer Record
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">
                <div class="row">
                    @if ($customer->customer_type == "residential_customer_type")
                        <div class="col-md-4">
                            Customer Name : {{$customer->customer_name}}
                        </div>
                        <div class="col-md-4">
                            Mobile no. : {{$customer->mobile_number}}
                        </div>
                        <div class="col-md-4">
                            Email : {{$customer->email}} 
                        </div>   
                    @elseif ($customer->customer_type == "commercial_customer_type")
                        <div class="col-md-3">
                            Customer Name : {{$customer->customer_name}}
                        </div>
                        <div class="col-md-3">
                            Company Name : {{$customer->individual_company_name}}
                        </div>
                        <div class="col-md-3">
                            Mobile no. : {{$customer->mobile_number}}
                        </div>
                        <div class="col-md-3">
                            Email : {{$customer->email}} 
                        </div>   
                    @endif           
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <ul class="nav nav-pills nav-pills-primary" data-bs-toggle="tabs" role="tablist">                           
                            <li class="nav-item me-2" role="presentation">
                                <a href="#quotation" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab">
                                    Quotation
                                </a>
                            </li>  
                            <li class="nav-item me-2" role="presentation">
                                <a href="#invoice" class="nav-link" data-bs-toggle="tab" aria-selected="true" role="tab">
                                    Invoice
                                </a>
                            </li> 
                            <li class="nav-item me-2" role="presentation">
                                <a href="#payment_history" class="nav-link" data-bs-toggle="tab" aria-selected="true" role="tab">
                                    Payment History
                                </a>
                            </li> 
                            <li class="nav-item me-2" role="presentation">
                                <a href="#sales_order" class="nav-link" data-bs-toggle="tab" aria-selected="true" role="tab">
                                    Sales Order
                                </a>
                            </li>    
                            <li class="nav-item me-2" role="presentation">
                                <a href="#session_details" class="nav-link" data-bs-toggle="tab" aria-selected="true" role="tab">
                                    Session
                                </a>
                            </li>                      
                        </ul>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="tab-content mt-3">
                            <div class="tab-pane active show" id="quotation" role="tabpanel">

                                <div class="row text-end mb-3">
                                    <div class="col-auto ms-auto">
                                        <a href="{{route('crm.quotation.create', $customer->id)}}" class="btn btn-primary">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M12 5l0 14" />
                                                <path d="M5 12l14 0" />
                                            </svg>
                                            Add New
                                        </a>
                                    </div>
                                </div>

                                <div class="table-responsive" style="min-height: 500px;">
                                    <table class="table card-table table-vcenter text-left text-nowrap datatable" id="quotation_table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="w-1">Sr No.</th>
                                                <th class="">Quotation No.</th>
                                                <th class="">Service Type</th>
                                                <th class="">Expiration Date</th>
                                                <th class="">Total Amount</th>
                                                <th class="">Created By</th>
                                                <th class="">Stage</th>
                                                <th class="">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane" id="invoice" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table card-table table-vcenter text-left text-nowrap datatable" id="invoice_table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="w-1 ">Sr No.</th>
                                                <th class="">Invoice No.</th>
                                                <th class="">Invoice Date</th>
                                                <th class="">Service Date</th>
                                                <th class="">Customer Name</th>
                                                {{-- <th class="">Billable To</th> --}}
                                                <th class="">Total</th>
                                                <th class="">GST</th>
                                                <th class="">Grand Total</th>
                                                <th class="">Overdue Amount</th>
                                                <th class="">Created By</th>
                                                <th class="">Payment Status</th>
                                                <th class="">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane" id="payment_history" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table card-table table-vcenter text-left text-nowrap datatable" id="payment_history_table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="w-1 ">Sr No.</th>
                                                <th class="">Invoice No.</th>
                                                <th class="">E-Payment</th>
                                                <th class="">Payment Date</th>
                                                <th class="">Amount</th>
                                                <th class="">Created By</th>
                                                <th class="">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane" id="sales_order" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table card-table table-vcenter text-left text-nowrap datatable" id="sales_order_table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="w-1 ">Sr No.</th>
                                                <th class="">Invoice No.</th>
                                                <th class="">Sales Order No.</th>
                                                <th class="">Customer</th>
                                                <th class="">Service Address</th>
                                                <th class="">Remarks</th>
                                                <th class="">Total Amount after GST</th>
                                                <th class="">Created By</th>
                                                <th class="">Status</th>                                           
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane" id="session_details" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table card-table table-vcenter text-left text-nowrap datatable" id="session_details_table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="w-1 ">Sr No.</th>
                                                <th class="">Invoice No.</th>
                                                <th class="">Sales Order No.</th>
                                                <th class="">Customer</th>
                                                <th class="">Schedule Date</th>
                                                <th class="">Schedule Time</th>
                                                <th class="">Helper Type</th>          
                                                <th class="">Helper Name</th>   
                                                <th class="">Status</th>       
                                                <th class="">Action</th>                                         
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


    {{-- view quotation modal --}}
    <div class="modal modal-blur fade" id="view-quotation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document" id="viewModalQoutation">

        </div>
    </div>

    {{-- edit quotation modal --}}
    <div class="modal modal-blur fade" id="edit-quotation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document">
            <div class="modal-content" id="editQoutationModal">
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

    {{-- session details modal --}}
    <div class="modal" id="session_details_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Session Details</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="">Before Cleaning Media</label>
                        <div id="session_details_modal_before_media">

                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="">Before Cleaning Remarks</label>
                        <textarea name="before_remarks" id="session_details_modal_before_remarks" class="form-control" cols="30" rows="4" readonly></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="">After Cleaning Media</label>
                        <div id="session_details_modal_after_media">

                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="">After Cleaning Remarks</label>
                        <textarea name="after_remarks" id="session_details_modal_after_remarks" class="form-control" cols="30" rows="4" readonly></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="">Damage</label>
                        <input type="text" name="damage" id="session_details_modal_damage" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="">Remarks</label>
                        <textarea name="remarks" id="session_details_modal_remarks" class="form-control" cols="30" rows="4" readonly></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="">Rating</label>

                        <span style="color: #FFC700;" id="session_details_modal_rating">
                            {{-- <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>   --}}
                        </span>               
                    </div>

                    <div class="mb-3">
                        <label for="">Comment</label>
                        <textarea name="comment" id="session_details_modal_comment" class="form-control" cols="30" rows="4" readonly></textarea>
                    </div>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

<script type="text/javascript">
        
    $(document).ready(function () {
          
        // quotation

        var quotation_table = $('#quotation_table').DataTable({
            "lengthChange": false,
            "pageLength": 30,
            ajax: {
                url: "{{ route('crm.get-quotation-table-data') }}",
                type: 'GET',
                data: {
                    customer_id: "{{$customer->id}}"
                }
            }
        });

        // invoice

        var invoice_table = $('#invoice_table').DataTable({
            "lengthChange": false,
            "pageLength": 30,
            ajax: {
                url: "{{ route('crm.get-invoice-table-data') }}",
                type: 'GET',
                data: {
                    customer_id: "{{$customer->id}}"
                }
            }
        });

        // payment history

        var payment_history_table = $('#payment_history_table').DataTable({
            "lengthChange": false,
            "pageLength": 30,
            ajax: {
                url: "{{ route('crm.get-payment-history-table-data') }}",
                type: 'GET',
                data: {
                    customer_id: "{{$customer->id}}"
                }
            }
        });

        // sales_order

        var sales_order_table = $('#sales_order_table').DataTable({
            "lengthChange": false,
            "pageLength": 30,
            ajax: {
                url: "{{ route('crm.get-sales-order-table-data') }}",
                type: 'GET',
                data: {
                    customer_id: "{{$customer->id}}"
                }
            }
        });

        // session details

        var session_details_table = $('#session_details_table').DataTable({
            "lengthChange": false,
            "pageLength": 30,
            ajax: {
                url: "{{ route('crm.get-session-details-table-data') }}",
                type: 'GET',
                data: {
                    customer_id: "{{$customer->id}}"
                }
            }
        });

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

        // view session details

        $('body').on('click', '.view_session_details_btn', function() {

            var sales_order_id = $(this).data('sales_order_id');
            var schedule_date = $(this).data('schedule_date');

            $.ajax({
                type: "get",
                url: "{{route('crm.view-session-details')}}",
                data: {
                    sales_order_id: sales_order_id,
                    schedule_date: schedule_date
                },
                success: function(result) {
                    console.log(result);

                    if(result.job_details)
                    {
                        $("#session_details_modal_before_remarks").val(result.job_details.before_remarks);
                        $("#session_details_modal_after_remarks").val(result.job_details.after_remarks);
                        $("#session_details_modal_damage").val(result.job_details.damage);
                        $("#session_details_modal_remarks").val(result.job_details.remarks);
                        $("#session_details_modal_comment").val(result.job_details.comment);

                        // rating

                        var rating = result.job_details.rating;

                        $("#session_details_modal_rating").html("");

                        for (let i = 1; i <= rating; i++) 
                        {
                            var html = `<i class="fa-solid fa-star"></i>`;       
                            
                            $("#session_details_modal_rating").append(html);
                        }

                        // before cleaning media

                        $("#session_details_modal_before_media").html("");

                        if (result.job_details.before_cleaning_photos.length > 0) 
                        {                                    
                            $.each(result.job_details.before_cleaning_photos, function (key, value) { 

                                if(value.before_photos_url)
                                {
                                    var html = `<a data-fancybox="gallery" href="${value.before_photos_url}">
                                                    <img src="${value.before_photos_url}" alt="" width="100px;" style="margin-right: 10px;">
                                                </a>`;

                                    $("#session_details_modal_before_media").append(html);
                                }
                                                    
                            });                        
                        }

                        // before cleaning media

                        $("#session_details_modal_after_media").html("");

                        if (result.job_details.after_cleaning_photos.length > 0) 
                        {                  
                            $.each(result.job_details.after_cleaning_photos, function (key, value) { 

                                if(value.after_photos_url)
                                {
                                    var html = `<a data-fancybox="gallery" href="${value.after_photos_url}">
                                                    <img src="${value.after_photos_url}" alt="" width="100px;" style="margin-right: 10px;">
                                                </a>`;

                                    $("#session_details_modal_after_media").append(html);
                                }
                                                    
                            });                        
                        }
                    }
                    else
                    {
                        $("#session_details_modal_before_remarks").val('');
                        $("#session_details_modal_after_remarks").val('');
                        $("#session_details_modal_damage").val('');
                        $("#session_details_modal_remarks").val('');
                        $("#session_details_modal_comment").val('');

                        $("#session_details_modal_rating").html("");
                        $("#session_details_modal_before_media").html("");
                        $("#session_details_modal_after_media").html("");
                    }
                    
                    $("#session_details_modal").modal('show');
                },
                error: function(result) {
                    console.log(result);
                }
            });

        });

    });

    // view quotation

    function viewQuotation(quotation_id)
    {
        // console.log(quotation_id);

        $.ajax({
            type: 'GET',
            url: "{{route('quotation.view')}}",
            data: { quotationId : quotation_id },
            success: function(response) {
                $('#view-quotation').modal('show');
                $('#viewModalQoutation').html(response);
            },
        });

    }

    // edit quotation

    function editQuotation(quotationId)
    {
        // console.log(quotation_id);

        $.ajax({
            type: 'GET',
            url: "{{route('quotation.edit')}}",
            data: { quotationId : quotationId },
            success: function(response) {
                $('#edit-quotation').modal('show');
                $('#editQoutationModal').html(response);
            },
        });

    }
    
</script>

@endsection
