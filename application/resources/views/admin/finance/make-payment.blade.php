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

                <form action="{{route('finance.make-payment.store')}}" method="post" id="payment_form" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="quotation_id" id="quotation_id" value="{{ $quotation->id }}"> 
                    <input type="hidden" id="customer_id_lead" name="customer_id" value="{{ $quotation->customer_id }}">
                    <input type="hidden" id="service_id_lead" name="service_address" value="{{ $quotation->service_address }}">
                    <input type="hidden" id="billing_id_lead" name="billing_address" value="{{ $quotation->billing_address }}">

                    <input type="hidden" name="email_template_id" id="form_email_template_id">
                    <input type="hidden" name="email_to" id="form_email_to">
                    <input type="hidden" name="email_cc" id="form_email_cc">
                    <input type="hidden" name="email_bcc" id="form_email_bcc">
                    <input type="hidden" name="email_subject" id="form_email_subject">
                    <input type="hidden" name="email_body" id="form_email_body">

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
                                <div class="col-md-4">
                                    Customer Name :
                                    @if ($quotation->customer_type == "residential_customer_type")
                                        {{$quotation->customer_name ?? ''}}
                                    @else
                                        {{$quotation->individual_company_name ?? ''}}
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    Customer Mo. no. : +65-{{$quotation->mobile_number}}
                                </div>
                                <div class="col-md-4">
                                    Email : {{$quotation->email}} 
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
                                        </tr>
                                    </thead>
                                    <tbody>                                           
                                        <tr>
                                            <td scope="row">1</td>
                                            <td>{{$quotation->id}}</td>
                                            <td>{{$quotation->invoice_no}}</td>
                                            <td>${{number_format($quotation->grand_total, 2)}}</td>
                                            <td>${{number_format($quotation->open_amount, 2)}}</td>                            
                                            <td>
                                                <select name="payment_method" class="form-select">                                                  
                                                    @foreach ($offline_payment_method as $list)
                                                        <option value="{{$list->payment_option}}">{{$list->payment_option}}</option>                                          
                                                    @endforeach
                                                    <option value="Asia Pay">Asia Pay</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="file" name="payment_proof" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="payment_remarks" class="form-control">
                                            </td>
                                            <td>
                                                <input type="hidden" name="lead_id" value="{{ $quotation->lead_id }}">
                                                <input type="hidden" name="quotation_id" value="{{ $quotation->id }}"> 
                                                <input type="number" name="pay_amount" value="0" min="0" max="{{$quotation->open_amount}}" step="0.01" class="form-control pay_amount" required>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="8"></td>
                                            <td>
                                                <input type="number" class="form-control" name="total_amount" id="totalamount_2" value="0" readonly/>                                          
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7"></td>
                                            <td class="text-end">
                                                <button type="button" onclick="send_received_payment_mail()" class="btn btn-info">Send By mail</button>   
                                            </td>
                                            <td style="text-align: start;">    
                                                <button class="btn btn-info" id="submit_btn">Submit</button>                                                                                                                                        
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>                           
                            </div>
                        </div>
                    </div>                   
                </form>

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

                        location.href = "{{route('finance')}}";
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

    function send_received_payment_mail() 
    {  
        $('#receive_payment_send_email_modal').modal('show');
    }

    function findTemplateId()
    {
        var customerId = $('#customer_id_lead').val();
        var templateId = $('#emailTemplateOption').val();

        var temp_invoice_no = "{{$quotation->invoice_no}}";
        // console.log(invoice_no);

        var received_payment_amount = $('input[name="pay_amount"]').val() || 0;

        if(templateId)
        {
            $.ajax({
                url: '{{ route('get.email.data') }}',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'template_id': templateId,
                    'customer_id': customerId,
                    'service_address_id': $('input[name="service_address"]').val(),
                    'billing_address_id': $('input[name="billing_address"]').val(),
                    'quotation_id': $("#quotation_id").val(),
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

        $('#form_email_template_id').val(email_template_id);
        $('#form_email_to').val(email_to);
        $('#form_email_cc').val(email_cc);
        $('#form_email_bcc').val(email_bcc);
        $('#form_email_subject').val(email_subject);
        $('#form_email_body').val(email_body);

        var data = new FormData($("#payment_form")[0]);

        $.ajax({
            url: "{{ route('finance.make-payment.send-email') }}",
            method: 'POST',
            data: data,
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

                    location.href = "{{route('finance')}}";
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