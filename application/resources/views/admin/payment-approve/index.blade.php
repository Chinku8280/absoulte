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
    </style>
@endsection

@section('content')
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">Payment Approval</h2>
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
                                <div class="table-responsive" style="min-height: 500px;">
                                    <table class="table card-table table-vcenter text-left text-nowrap datatable" id="all_payments_table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th scope="col">No.</th>
                                                <th scope="col">Invoice No</th>
                                                <th scope="col">Sales Order Id</th>
                                                <th scope="col">Payment Amount</th>
                                                <th scope="col">Payment Method</th>
                                                <th scope="col">Payment Date</th>
                                                <th scope="col">Payment Remarks</th>
                                                <th scope="col">Created By</th>
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

    {{-- payment details modal --}}
    <div class="modal" id="view_payment_details_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Payment Deatils</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Invoice No</label>
                        <input type="text" class="form-control" name="view_invoice_no" id="view_invoice_no" readonly>                            
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sales Order Id</label>
                        <input type="text" class="form-control" name="view_sales_oder_id" id="view_sales_oder_id" readonly>                            
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Amount</label>
                        <input type="text" class="form-control" name="view_payment_amount" id="view_payment_amount" readonly>                            
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <input type="text" class="form-control" name="view_payment_method" id="view_payment_method" readonly>                            
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Date</label>
                        <input type="date" class="form-control" name="view_payment_date" id="view_payment_date" readonly>                            
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Remarks</label>
                        <textarea class="form-control" cols="30" rows="5" name="view_payment_remarks" id="view_payment_remarks" readonly></textarea>                           
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Proof</label>
                        <div id="view_payment_proof"></div>
                    </div>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    {{-- approve payment modal --}}
    <div class="modal" id="approve_payment_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Payment Deatils</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">

                    <input type="hidden" name="payment_id" id="payment_id">
                    <input type="hidden" name="quotation_id" id="quotation_id">

                    <div class="mb-3">
                        <label class="form-label">Invoice No</label>
                        <input type="text" class="form-control" name="invoice_no" id="invoice_no" readonly>                            
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sales Order Id</label>
                        <input type="text" class="form-control" name="sales_oder_id" id="sales_oder_id" readonly>                            
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Amount</label>
                        <input type="text" class="form-control" name="payment_amount" id="payment_amount" readonly>                            
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <input type="text" class="form-control" name="payment_method" id="payment_method" readonly>                            
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Date</label>
                        <input type="date" class="form-control" name="payment_date" id="payment_date" readonly>                            
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Remarks</label>
                        <textarea class="form-control" cols="30" rows="5" name="payment_remarks" id="payment_remarks" readonly></textarea>                           
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Proof</label>
                        <div id="payment_proof"></div>
                    </div>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info" id="approve_payment_modal_btn">Approve</button>
                    <button type="button" class="btn btn-info" id="send_received_payment_mail_btn">Send by mail</button>
                    <button type="button" class="btn btn-danger" id="reject_payment_modal_btn">Reject</button>
                </div>

            </div>
        </div>
    </div>

    {{-- approve payment modal --}}
    {{-- <div class="modal modal-blur fade" id="approve_payment_modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-success"></div>
                <div class="modal-body text-center py-4">
                    <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-success icon-lg" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M12 9v2m0 4v.01"></path>
                        <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75">
                        </path>
                    </svg>
                    <h3>Are you sure?</h3>
                    <div class="text-muted">Do you really want to Approve this Payment?</div>
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
                                <a href="#" class="btn btn-success w-100" id="approve_payment_modal_btn">
                                    Approve
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> --}}

    <div class="modal modal-blur fade" id="receive_payment_send_email_modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="smartwizard2" style="border: none;" dir="" class="sw sw-theme-basic sw-justified">
                        <ul class="nav d-none" style="">
                            <li class="nav-item">
                                <a class="nav-link default active" href="#send-step-1">
                                    <div class="num">1</div>
                                    1
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link default" href="#send-step-2">
                                    <span class="num">2</span>
                                    2
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content p-0" style="border: none; height: 260px;">
                            <div id="send-step-1" class="tab-pane" role="tabpanel" aria-labelledby="send-step-1"
                                style="display: none;">
                                <div class="row">
                                    <div class="mb-3">
                                        <label class="form-label">Select Email Template</label>
                                        <div class="row g-2">
                                            <select class="form-select select2" aria-label="Default select example"
                                                id="emailTemplateOption" onchange="findTemplateId()">
                                                <option value="">Select</option>
                                                @foreach ($emailTemplates as $emailTemplate)
                                                    <option value="{{ $emailTemplate->id }}">
                                                        {{ $emailTemplate->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @foreach ($emailTemplates as $emailTemplate)
                                                <input type="hidden" value="{{ $emailTemplate->id }}"
                                                    class="emailTemplateId">
                                            @endforeach
                                        </div>
                                    </div>
    
                                </div>
                            </div>
    
                            <div id="send-step-2" class="tab-pane" role="tabpanel" aria-labelledby="send-step-2"
                                style="display: none;">
                            </div>
                        </div>
                        <div class="sw-toolbar-elm justify-content-between toolbar toolbar-bottom" role="toolbar">
                            <button class="btn sw-btn-prev disabled" type="button">Previous</button><button
                                class="btn btn-primary" type="button">Next</button>
                        </div>
    
                        <!-- Include optional progressbar HTML -->
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0"
                                aria-valuemin="0" aria-valuemax="100"></div>
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

    $(document).ready(function () {

        var all_payments_table = $('#all_payments_table').DataTable({
            "lengthChange": false,
            "pageLength": 30,
            ajax: {
                url: "{{ route('payment-approve.get-table-data') }}",
                type: 'GET',
            },
            'columnDefs': [{
                'targets': [0],        // Targets the first column (index 0)
                'orderable': false,    // Disables sorting on this column
            }]
        }).on('order.dt search.dt', function() {
            var table = $('#all_payments_table').DataTable();
            table.column(0, {order:'applied'}).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;  // Update the serial number
            });
        }).draw();
        
        // view payment proof

        $('body').on('click', '.view_btn', function() {

            var id = $(this).data('id');

            $.ajax({
                type: "get",
                url: "{{route('payment-approve.get-payment-details')}}",
                data: {
                    payment_id: id
                },
                success: function(result) {
                    console.log(result);

                    $("#view_invoice_no").val(result.lead_payment_detail.invoice_no);
                    $("#view_sales_oder_id").val(result.lead_payment_detail.sales_order_id);
                    $("#view_payment_amount").val(result.lead_payment_detail.payment_amount);
                    $("#view_payment_method").val(result.lead_payment_detail.payment_method);
                    $("#view_payment_date").val(result.lead_payment_detail.payment_date);
                    $("#view_payment_remarks").val(result.lead_payment_detail.payment_remarks);

                    $("#view_payment_proof").html("");

                    if (result.payment_proof.length > 0) 
                    {                  
                        $.each(result.payment_proof, function (key, value) { 

                            if(value.payment_proof_url)
                            {
                                var html = `<a data-fancybox="gallery" href="${value.payment_proof_url}">
                                                <img src="${value.payment_proof_url}" alt="" width="100px;" style="margin-right: 10px;">
                                            </a>`;

                                $("#view_payment_proof").append(html);
                            }
                                                 
                        });                        
                    }

                    $("#view_payment_details_modal").modal('show');
                },
                error: function(result) {
                    console.log(result);
                }
            });

        });

        // approve payment start

        // $('body').on('click', '.approve_payment_btn', function() {

        //     var payment_id = $(this).data('id');
        //     $('#approve_payment_modal').data('payment_id', payment_id);
        //     $('#approve_payment_modal').modal('show');

        // });

        $('body').on('click', '.approve_payment_btn', function() {

            var payment_id = $(this).data('id');
            var quotation_id = $(this).data('quotation_id');

            $.ajax({
                type: "get",
                url: "{{route('payment-approve.get-payment-details')}}",
                data: {
                    payment_id: payment_id
                },
                success: function(result) {
                    console.log(result);

                    $("#payment_id").val(payment_id);
                    $("#quotation_id").val(quotation_id);
                    $("#invoice_no").val(result.lead_payment_detail.invoice_no);
                    $("#sales_oder_id").val(result.lead_payment_detail.sales_order_id);
                    $("#payment_amount").val(result.lead_payment_detail.payment_amount);
                    $("#payment_method").val(result.lead_payment_detail.payment_method);
                    $("#payment_date").val(result.lead_payment_detail.payment_date);
                    $("#payment_remarks").val(result.lead_payment_detail.payment_remarks);

                    $("#payment_proof").html("");

                    if (result.payment_proof.length > 0) 
                    {                  
                        $.each(result.payment_proof, function (key, value) { 

                            if(value.payment_proof_url)
                            {
                                var html = `<a data-fancybox="gallery" href="${value.payment_proof_url}">
                                                <img src="${value.payment_proof_url}" alt="" width="100px;" style="margin-right: 10px;">
                                            </a>`;

                                $("#payment_proof").append(html);
                            }
                                                 
                        });                        
                    }

                    $('#approve_payment_modal').data('payment_id', payment_id);
                    $('#approve_payment_modal').data('quotation_id', quotation_id);
                    $('#approve_payment_modal').modal('show');
                },
                error: function(result) {
                    console.log(result);
                }
            });

        });

        $('body').on('click', '#approve_payment_modal_btn', function() {

            var payment_id = $("#approve_payment_modal").data('payment_id');

            $.ajax({
                url: "{{ route('payment-approve.approve-payment') }}",
                type: 'post',
                data: {
                    payment_id: payment_id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    console.log(result);
                    if (result.status == "success") {                        
                        iziToast.success({
                            message: result.message,
                            position: 'topRight'
                        });

                        $('#approve_payment_modal').modal('hide');
                        all_payments_table.ajax.reload();
                    } 
                    else {
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

        // approve payment end

        // select2

        $('.select2').select2({
            dropdownParent: $("#receive_payment_send_email_modal")
        });

        $('#smartwizard2').smartWizard({
            transition: {
                animation: 'slideHorizontal', // Effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
            }
        });

        // close send invoice modal

        $('#receive_payment_send_email_modal').on('hidden.bs.modal', function () {
            $('#send-step-2').html("");
            $('#smartwizard2').smartWizard("reset"); 
            $(".select2").val("").trigger('change');
        });       
        
        // send by email start
        
        // $('body').on('click', '.send_received_payment_mail_btn', function() {

        //     var payment_id = $(this).data('payment_id');
        //     var quotation_id = $(this).data('quotation_id');

        //     $('#receive_payment_send_email_modal').data('payment_id', payment_id);
        //     $('#receive_payment_send_email_modal').data('quotation_id', quotation_id);

        //     $.ajax({
        //         type: "get",
        //         url: "{{route('payment-approve.get-quotation-data')}}",
        //         data: {quotation_id: quotation_id, payment_id: payment_id},
        //         success: function (result) {
        //             console.log(result);

        //             if(result.quotation && result.lead_payment_detail)
        //             {
        //                 $('#receive_payment_send_email_modal').data('customer_id', result.quotation.customer_id);
        //                 $('#receive_payment_send_email_modal').data('service_address', result.quotation.service_address);
        //                 $('#receive_payment_send_email_modal').data('billing_address', result.quotation.billing_address);
        //                 $('#receive_payment_send_email_modal').data('invoice_no', result.quotation.invoice_no);

        //                 $('#receive_payment_send_email_modal').data('received_payment_amount', result.lead_payment_detail.payment_amount);
                        
        //                 $('#receive_payment_send_email_modal').modal('show');
        //             }
        //         },
        //         error: function (result) {
        //             console.log(result);
        //         }
        //     });

        // });

        $('body').on('click', '#send_received_payment_mail_btn', function() {

            var payment_id = $("#approve_payment_modal").data('payment_id');
            var quotation_id = $("#approve_payment_modal").data('quotation_id');

            $('#receive_payment_send_email_modal').data('payment_id', payment_id);
            $('#receive_payment_send_email_modal').data('quotation_id', quotation_id);

            $.ajax({
                type: "get",
                url: "{{route('payment-approve.get-quotation-data')}}",
                data: {quotation_id: quotation_id, payment_id: payment_id},
                success: function (result) {
                    console.log(result);

                    if(result.quotation && result.lead_payment_detail)
                    {
                        $('#receive_payment_send_email_modal').data('customer_id', result.quotation.customer_id);
                        $('#receive_payment_send_email_modal').data('service_address', result.quotation.service_address);
                        $('#receive_payment_send_email_modal').data('billing_address', result.quotation.billing_address);
                        $('#receive_payment_send_email_modal').data('invoice_no', result.quotation.invoice_no);

                        $('#receive_payment_send_email_modal').data('received_payment_amount', result.lead_payment_detail.payment_amount);
                        
                        $('#receive_payment_send_email_modal').modal('show');
                    }
                },
                error: function (result) {
                    console.log(result);
                }
            });

        });

        // send by email end

        // reject payment start

        $('body').on('click', '#reject_payment_modal_btn', function () {

            var payment_id = $("#approve_payment_modal").data('payment_id');

            $.ajax({
                url: "{{ route('payment-approve.reject-payment') }}",
                type: 'post',
                data: {
                    payment_id: payment_id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    console.log(result);
                    if (result.status == "success") {                        
                        iziToast.success({
                            message: result.message,
                            position: 'topRight'
                        });

                        $('#approve_payment_modal').modal('hide');
                        all_payments_table.ajax.reload();
                    } 
                    else {
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

    // send by email start

    function findTemplateId()
    {
        var templateId = $('#emailTemplateOption').val();

        var customerId = $('#receive_payment_send_email_modal').data('customer_id') || "";
        var temp_invoice_no = $('#receive_payment_send_email_modal').data('invoice_no') || "";
        var service_address = $('#receive_payment_send_email_modal').data('service_address') || "";
        var billing_address = $('#receive_payment_send_email_modal').data('billing_address') || "";
        var quotation_id = $('#receive_payment_send_email_modal').data('quotation_id') || "";
        var received_payment_amount = $('#receive_payment_send_email_modal').data('received_payment_amount') || 0;

        if(templateId)
        {
            $.ajax({
                url: '{{ route('get.email.data') }}',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'template_id': templateId,
                    'customer_id': customerId,
                    'service_address_id': service_address,
                    'billing_address_id': billing_address,
                    'quotation_id': quotation_id,
                    'received_payment_amount' : received_payment_amount,
                    'temp_invoice_no': temp_invoice_no,
                },
                success: function(response) {
                    // console.log(response);
                    $('#send-step-2').html(response);

                    var email_cc = document.getElementById('email_cc');
                    new Tagify(email_cc);

                    // ClassicEditor
                    //             .create(document.querySelector('#email_body'))
                    //             .catch(error => {
                    //                 console.error(error);
                    //             });

                    // Initialize ClassicEditor for the email body
                    ClassicEditor.create(document.querySelector('#email_body'))
                                .then(editor => {
                                    // Assign the editor instance globally if needed
                                    window.editor = editor;
                                })
                                .catch(error => {
                                    console.error(error);
                                });

                    // subject
                    var email_subject = $("#email_subject").val();
                    email_subject = email_subject.replace("##INVOICE_NO##", temp_invoice_no);               
                    $("#email_subject").val(email_subject);

                },
                error: function(response) {
                    console.log(response);
                }
            });
        }
    }

    function emailSend(event) 
    {
        event.preventDefault;

        // Ensure the email body is updated with the editor content before submitting
        if (window.editor) {
            // Update the hidden textarea value with the content of the editor
            $('#email_body').val(window.editor.getData());
        }

        var email_template_id = $('#emailTemplateOption').val();
        var email_to = $('#email_to').val();
        var email_cc = $('#email_cc').val();
        var email_bcc = $('#email_bcc').val();
        var email_subject = $('#email_subject').val();
        var email_body = $('#email_body').val();

        var payment_id = $('#receive_payment_send_email_modal').data('payment_id');

        var myFormData = new FormData();

        myFormData.append('payment_id', payment_id);

        myFormData.append('email_template_id', email_template_id);
        myFormData.append('email_to', email_to);
        myFormData.append('email_cc', email_cc);
        myFormData.append('email_bcc', email_bcc);
        myFormData.append('email_subject', email_subject);
        myFormData.append('email_body', email_body);

        $.ajax({
            url: "{{ route('payment-approve.send-email') }}",
            method: 'POST',
            headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
            data: myFormData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $("#email_confirm_btn").attr('disabled', true);
            },
            success: function(result) {
                console.log(result);               

                if(result.status == "error")
                {
                    $.each(result.error, function (key, value) { 

                        iziToast.error({
                            message: value,
                            position: 'topRight',
                        });                
                                                
                    });
                }                        
                else if(result.status == "success")
                {
                    iziToast.success({
                        message: result.message,
                        position: 'topRight',
                    });

                    $('#receive_payment_send_email_modal').modal('hide');
                    $("#approve_payment_modal").modal('hide');
                    // all_payments_table.ajax.reload();
                    location.reload();
                }
                else
                {
                    iziToast.error({
                        message: result.message,
                        position: 'topRight',
                    });
                }    
            },
            error: function(result){
                console.log(result);
            },
            complete: function() {
                $("#email_confirm_btn").attr('disabled', false);
            },

        });
    }

    // send by email end
    
</script>

@endsection