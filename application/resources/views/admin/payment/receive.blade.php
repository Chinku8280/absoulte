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
                        <h2 class="page-title">Received Payment</h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container mt-3">
                <form action="{{ route('payment-history.store') }}" method="post" id="payment_form" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="customer_id" id="customer_id" value="{{ $lead[0]->customer_id }}"> 

                    <div class="card mb-3">
                        <div class="card-header">
                            <a href="{{route('payment.index')}}">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </a>
                            &nbsp;
                            <div class="col-md-3">
                                Customer Details
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    Customer Name :
                                    @if ($lead[0]->customer_type == "residential_customer_type")
                                        {{$lead[0]->customer_name ?? ''}}
                                    @else
                                        {{$lead[0]->individual_company_name ?? ''}}
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    Customer Mo. no. : +65-{{$lead[0]->mobile_number}}
                                </div>
                                <div class="col-md-4">
                                    Email : {{$lead[0]->email}} 
                                </div>   
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    {{-- <label for="">Upload Proof</label><br>
                                    <input type="file" id="formFile" name="payment_proof"> --}}
                                </div>
                                <div class="col-md-6">
                                    <label for="">Amount</label>
                                    <input type="number" name="total_amount" id="totalamount_1" readonly placeholder="0.00" class="form-control" value="0" step="0.01" min="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="col-md-3">
                                Payment Details
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table caption-top" id="payment_list_table">
                                    <caption>All Payment List</caption>
                                    <thead>
                                        <tr>
                                            <th scope="col">S/N</th>
                                            <th scope="col">Quotation Id</th>
                                            <th scope="col">Invoice no</th>
                                            <th scope="col">Original amount</th>
                                            <th scope="col">Open amount</th>                                                                                      
                                            <th scope="col">Payment Method</th>
                                            <th scope="col">Payment Proof</th>
                                            <th scope="col">Payment Remarks</th>
                                            <th scope="col">Payment</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lead as $key => $item)        
                                            <tr id="row_{{$item->id}}">
                                                <td scope="row">{{$key+1}}</td>
                                                <td>{{$item->id}}</td>
                                                <td>{{$item->invoice_no}}</td>
                                                <td>${{number_format($item->grand_total, 2)}}</td>
                                                <td>${{number_format($item->open_amount, 2)}}</td>                            
                                                <td>
                                                    <select name="payment_method[]" class="form-select payment_method">
                                                        @foreach ($offline_payment_method as $list)
                                                            <option value="{{$list->payment_option}}">{{$list->payment_option}}</option>                                          
                                                        @endforeach
                                                        <option value="Asia Pay">Asia Pay</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="file" name="payment_proof[]" class="form-control payment_proof">
                                                </td>
                                                <td>
                                                    <input type="text" name="payment_remarks[]" class="form-control payment_remarks">
                                                </td>
                                                <td>
                                                    <input type="hidden" name="invoice_no[]" class="invoice_no" value="{{ $item->invoice_no }}">
                                                    <input type="hidden" name="service_address[]" class="service_address" value="{{ $item->service_address }}">
                                                    <input type="hidden" name="billing_address[]" class="billing_address" value="{{ $item->billing_address }}">
                                                    <input type="hidden" name="lead_id[]" class="lead_id" value="{{ $item->lead_id }}">
                                                    <input type="hidden" name="quotation_id[]" class="quotation_id" value="{{ $item->id }}"> 
                                                    <input type="number" name="pay_amount[]" class="pay_amount form-control" value="0" min="0" max="{{$item->open_amount}}" step="0.01" class="form-control pay_amount" required>
                                                </td>
                                                <td>
                                                    <button type="button" onclick="send_received_payment_mail(this, {{ $item->id }})" class="btn btn-info">Send By mail</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="8"></td>
                                            <td>
                                                <input type="number" class="form-control" name="total_amount" id="totalamount_2" value="0" readonly/>                                          
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="8"></td>
                                            <td style="text-align: right;">                                            
                                                <button class="btn btn-info" id="submit_btn">Submit</button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>                           
                            </div>
                        </div>
                    </div>                   
                </form>

                <div class="card">
                    <div class="card-header">
                        <div class="col-md-3">
                            Payment History Details
                        </div>
                    </div>
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
<script>
    $(document).ready(function () {
        
        
        $('body').on('blur', '.pay_amount', function (){

            var total_amount = 0;

            $("#payment_list_table tbody tr").each(function() {

                total_amount += parseFloat($(this).find('.pay_amount').val());

            });

            $("#totalamount_1").val(total_amount);
            $("#totalamount_2").val(total_amount);

        });

        $('body').on('submit', '#payment_form', function(e){

            e.preventDefault();

            var data = new FormData($('#payment_form')[0]);
            
            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $("#submit_btn").attr('disabled', true);
                },
                success: function (result) {
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

                        // Store the success message in localStorage
                        localStorage.setItem('successMessage', result.message);

                        $("#receive_payment_send_email_modal").modal('hide');
                        location.href = "{{route('payment.index')}}";
                    }
                    else
                    {
                        iziToast.error({
                            message: result.message,
                            position: 'topRight',
                        });
                    }    
                },
                error: function (result) {
                    console.log(result);
                },
                complete: function() {
                    $("#submit_btn").attr('disabled', false);
                },
            });

        });

        var all_payments_table = $('#all_payments_table').DataTable({
            "lengthChange": false,
            "pageLength": 30,
            ajax: {
                url: "{{ route('payment-history.view-get-table-data') }}",
                type: 'GET',
                data: {
                    customer_id : "{{$customer_id}}"
                }
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

                    location.reload();
                },
                error: function(result) {
                    console.log(result);
                }
            });

        });

        // reject payment end

        // select2

        $('.select2').select2({
            dropdownParent: $("#receive_payment_send_email_modal")
        });

        $('#smartwizard2').smartWizard({
            transition: {
                animation: 'slideHorizontal', // Effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
            }
        });

        $('#smartwizard2').smartWizard("reset"); 

        // close send invoice modal

        $('#receive_payment_send_email_modal').on('hidden.bs.modal', function () {
            $('#send-step-2').html("");
            $('#smartwizard2').smartWizard("reset"); 
            $(".select2").val("").trigger('change');
        });

    });

    function send_received_payment_mail(el, quotation_id) 
    {  
        var table_row_id = $(el).parents('tr').attr('id');
        $('#receive_payment_send_email_modal').data('row_id', table_row_id);
        $('#receive_payment_send_email_modal').modal('show');
    }

    function findTemplateId()
    {
        var row_id = $('#receive_payment_send_email_modal').data('row_id');

        var customerId = $('#customer_id').val();
        var templateId = $('#emailTemplateOption').val();

        var temp_invoice_no = $('#payment_list_table tbody').find("#"+row_id).find('.invoice_no').val() || "";
        var service_address = $('#payment_list_table tbody').find("#"+row_id).find('.service_address').val() || "";
        var billing_address = $('#payment_list_table tbody').find("#"+row_id).find('.billing_address').val() || "";
        var quotation_id = $('#payment_list_table tbody').find("#"+row_id).find('.quotation_id').val() || "";
        var received_payment_amount = $('#payment_list_table tbody').find("#"+row_id).find('.pay_amount').val() || 0;

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

        var row_id = $('#receive_payment_send_email_modal').data('row_id');

        var email_template_id = $('#emailTemplateOption').val();
        var email_to = $('#email_to').val();
        var email_cc = $('#email_cc').val();
        var email_bcc = $('#email_bcc').val();
        var email_subject = $('#email_subject').val();
        var email_body = $('#email_body').val();

        var payment_method = $('#payment_list_table tbody').find("#"+row_id).find('.payment_method').val() || "";
        var payment_proof = $('#payment_list_table tbody').find("#"+row_id).find('.payment_proof').prop("files")[0] || "";
        var payment_remarks = $('#payment_list_table tbody').find("#"+row_id).find('.payment_remarks').val() || "";
        // var temp_invoice_no = $('#payment_list_table tbody').find("#"+row_id).find('.invoice_no').val() || "";
        // var service_address = $('#payment_list_table tbody').find("#"+row_id).find('.service_address').val() || "";
        // var billing_address = $('#payment_list_table tbody').find("#"+row_id).find('.billing_address').val() || "";
        var quotation_id = $('#payment_list_table tbody').find("#"+row_id).find('.quotation_id').val() || "";
        var lead_id = $('#payment_list_table tbody').find("#"+row_id).find('.lead_id').val() || "";
        var received_payment_amount = $('#payment_list_table tbody').find("#"+row_id).find('.pay_amount').val() || 0;

        var customerId = $('#customer_id').val();

        var myFormData = new FormData();

        myFormData.append('payment_method', payment_method);
        myFormData.append('payment_proof', payment_proof);
        myFormData.append('payment_remarks', payment_remarks);
        myFormData.append('quotation_id', quotation_id);
        myFormData.append('lead_id', lead_id);
        myFormData.append('pay_amount', received_payment_amount);

        myFormData.append('email_template_id', email_template_id);
        myFormData.append('email_to', email_to);
        myFormData.append('email_cc', email_cc);
        myFormData.append('email_bcc', email_bcc);
        myFormData.append('email_subject', email_subject);
        myFormData.append('email_body', email_body);

        $.ajax({
            url: "{{ route('payment-history.send-email') }}",
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

                    // Store the success message in localStorage
                    localStorage.setItem('successMessage', result.message);

                    location.href = "{{route('payment.index')}}";
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
    
</script>
    
@endsection