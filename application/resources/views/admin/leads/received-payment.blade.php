<style>
    /* Truncate the text to 100 characters with ellipsis */
    .description-cell {
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ui-datepicker {
        border: 1px solid #e6e7e9;
        border-radius: 8px 8px 0px 0px;
    }

    #popover {
        position: relative;
        background-color: white;
        border: 1px solid #ccc;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
        z-index: 2;
        padding: 10px;
    }

    .hidden {
        display: none;
    }

    .highlighted-date {
        background-color: #ffcc00;
        /* Yellow background */
        color: #000;
        /* Black text color */
        font-weight: bold;
    }

    /* Style for the tooltip (label) */
    .highlighted-date:hover:after {
        content: attr(data-tooltip);
        background-color: #333;
        /* Tooltip background color */
        color: #fff;
        /* Tooltip text color */
        padding: 5px 10px;
        border-radius: 5px;
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        white-space: nowrap;
        opacity: 0;
        transition: opacity 0.3s;
    }

    /* Show tooltip on hover */
    .highlighted-date:hover:after {
        opacity: 1;
    }

    .btn.selected-option {
        border: 2px solid #053ef8;
    }
</style>
<div class="modal-header">
    <h5 class="modal-title">Lead Received Payment</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form class="row text-left" id="lead_received_payment_form" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="leadId" id="leadId" value="{{ $leadId }}">
        <input type="hidden" id="customer_id_lead" name="customer_id" value="{{ $lead->customer_id }}">
        <input type="hidden" id="service_id_lead" name="service_address" value="{{ $lead->service_address }}">
        <input type="hidden" id="billing_id_lead" name="billing_address" value="{{ $lead->billing_address }}">
        <input type="hidden" name="received_payment_amount" id="received_payment_amount">

        <input type="hidden" name="email_template_id" id="form_email_template_id">
        <input type="hidden" name="email_to" id="form_email_to">
        <input type="hidden" name="email_cc" id="form_email_cc">
        <input type="hidden" name="email_bcc" id="form_email_bcc">
        <input type="hidden" name="email_subject" id="form_email_subject">
        <input type="hidden" name="email_body" id="form_email_body">

        <div class="row">

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h3>Payment Amount</h3>
                    </div>
                    <div class="card-body">                
                        <div class="col-md-6">
                            <label class="form-label" for="totalAmount" style="font-size: 20px;">Total Payble Amount($):
                            <span id="totalAmount" style="font-size: 20px;">{{$lead->grand_total}}</span></label>
                            <input type="hidden" value="{{$lead->grand_total}}" name="total_amount">
                        </div>  
                        
                        <div class="row" style="margin-top: 15px;">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input payment_option" type="radio"
                                        name="payment_option" value="no_deposit" id="no_deposit_pay">
                                    <label class="form-check-label" for="no_deposit_pay">
                                        No Deposit
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input payment_option" type="radio"
                                        name="payment_option" value="Offline" id="offline_pay" checked>
                                    <label class="form-check-label" for="offline_pay">
                                        Offline :
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="responsive-table" style="margin-top: 15px;" id="offline_group">
                            <div class="col-md-12">
                                <h5>Offline Payment method :</h5>
                            </div>

                            <table class="table table-success table-striped border">

                                <tbody id="offline_pay_option">      
                                    @foreach ($offlineOptions as $options)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input payment_option_checkbox" type="checkbox"
                                                        name="payment_option_checkbox[]"
                                                        value="{{ $options->payment_option }}"
                                                        id="{{ $options->payment_option }}"
                                                        @if ($options->payment_option === 'Pay Now') checked @endif>
                                                    <label class="form-check-label"
                                                        for="{{ $options->payment_option }}">
                                                        {{ $options->payment_option }}
                                                    </label>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td><input class="form-control payment_amount_checkbox" type="number"
                                                    name="payment_amount_checkbox[]" step="any"></td>
                                            <td><input class="form-control" type="file"
                                                    name="payment_proof[]"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="">Remarks</label>
                                    <input type="text" name="payment_remarks" class="form-control" placeholder="Remarks">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-link card-link-pop">
                    <div class="card-status-start bg-primary"></div>
                    <div class="card-stamp">
                        <div class="card-stamp-icon bg-white text-primary">
                            <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path
                                    d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="customer-card-slide3">
                        <div class="card-body">
                            <h3 class="card-title mb-1" style="color: #1F3BB3;"><b
                                    class="me-2">Customer Details added</b>
                            </h3>
                            <p class="card-p d-flex align-items-center mb-2 ">
                                <i class="fa-solid fa-user me-2"
                                    style="font-size: 14px;"></i>
                                    {{($customer->customer_type == "residential_customer_type")?$customer->customer_name:$customer->individual_company_name}}
                            </p>
                            <p class="card-p d-flex align-items-center mb-2 ">
                                <i class="fa-solid fa-phone me-2"
                                    style="font-size: 14px;"></i>{{ $customer->mobile_number }}
                            </p>
                            <p class="card-p  d-flex align-items-center mb-2">
                                <i class="fa-solid fa-envelope me-2"
                                    style="font-size: 14px;"></i>{{ $customer->email }}
                            </p>
                            {{-- <p class="card-p d-flex mb-2">
                                <i class="fa-solid fa-map-pin me-2 pt-1" style="font-size: 14px;"></i>
                                103 Rasadhi Appartment Wadaj Ahmedabad 380004.
                            </p> --}}
                            {{-- <p class="card-p d-flex mb-2">
                                Total Spend : $0.00
                            </p> --}}
                            <hr class="my-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12" style="text-align: right;">
                <button type="submit" class="btn btn-info" id="submit_btn">Submit</button>
                <button type="button" onclick="send_received_payment_mail()" class="btn btn-info"
                    data-dismiss="modal" style="width: 150px !important;">Send By mail</button>   
            </div>
        </div>
    </form>
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

<script>

    $(document).ready(function () {

        $('.payment_option').on('change', function(){

            var payment_option = $(this).val();

            if(payment_option == "Offline")
            {
                $("#offline_group").show();
            }
            else
            {
                $("#offline_group").hide();
            }

            calculate_received_payment();

        });

        $('.payment_option_checkbox, .payment_amount_checkbox',).on('change', function(){
            calculate_received_payment();
        });

        // $('#lead_received_payment_form').on('submit', function(e){

        //     e.preventDefault();

        //     var data = new FormData($(this)[0]);

        //     $.ajax({
        //         url: "{{ route('lead.received-payment.store') }}",
        //         method: 'POST',
        //         data: data,
        //         processData: false,
        //         contentType: false,
        //         beforeSend: function() {
        //             $("#submit_btn").attr('disabled', true);
        //         },
        //         success: function(response) {
        //             console.log(response);

        //             if (response.errors)
        //             {
        //                 var errorMsg = '';
        //                 $.each(response.errors, function(field, errors) {
        //                     $.each(errors, function(index, error) {
        //                         errorMsg += error + '<br>';
        //                     });
        //                 });
        //                 iziToast.error({
        //                     message: errorMsg,
        //                     position: 'topRight'
        //                 });

        //             }
        //             else if(response.status == "success")
        //             {
        //                 iziToast.success({
        //                     message: response.message,
        //                     position: 'topRight',
        //                 });

        //                 $('#lead_received_payment').modal('hide');
        //                 window.location.reload();
        //             }
        //             else
        //             {
        //                 iziToast.error({
        //                     message: response.message,
        //                     position: 'topRight'
        //                 });
        //             }
        //         },
        //         error: function(response){
        //             console.log(response);
        //         },
        //         complete: function() {
        //             $("#submit_btn").attr('disabled', false);
        //         },

        //     });

        // });

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
        $('#receive_payment_send_email_modal').modal('show')
    }

    function findTemplateId() 
    {
        var customerId = $('#customer_id_lead').val();
        var templateId = $('#emailTemplateOption').val();

        var temp_invoice_no = "{{$lead->temp_invoice_no}}";

        var received_payment_amount = $("#received_payment_amount").val() || 0;

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
                    'lead_id': $("#leadId").val(),
                    'received_payment_amount' : $("#received_payment_amount").val(),
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

    function calculate_received_payment()
    {
        var payment_option = $(".payment_option:checked").val();

        var payment_amount = 0;

        if(payment_option == "Offline")
        {
            $('.payment_option_checkbox').each(function () {
                // check if the checkbox is checked
                if($(this).is(":checked")) 
                {
                    var payment_amount_checkbox = $(this).parents('tr').find('.payment_amount_checkbox').val() || 0;

                    payment_amount += parseFloat(payment_amount_checkbox);
                }
            });
        }

        $("#received_payment_amount").val(payment_amount);
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

        var data = new FormData($("#lead_received_payment_form")[0]);

        $.ajax({
            url: "{{ route('lead.received-payment.send-email') }}",
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $("#email_confirm_btn").attr('disabled', true);
            },
            success: function(response) {
                console.log(response);

                if (response.errors)
                {
                    var errorMsg = '';
                    $.each(response.errors, function(field, errors) {
                        $.each(errors, function(index, error) {
                            errorMsg += error + '<br>';
                        });
                    });
                    iziToast.error({
                        message: errorMsg,
                        position: 'topRight'
                    });

                }
                else if(response.status == "success")
                {
                    iziToast.success({
                        message: response.message,
                        position: 'topRight',
                    });

                    // Store the success message in localStorage
                    localStorage.setItem('successMessage', response.message);

                    $('#receive_payment_send_email_modal').modal('hide');
                    $('#lead_received_payment').modal('hide');
                    window.location.reload();
                }
                else
                {
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });
                }
            },
            error: function(response){
                console.log(response);
            },
            complete: function() {
                $("#email_confirm_btn").attr('disabled', false);
            },

        });
    }

</script>


