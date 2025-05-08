@extends('theme.default')

@section('custom_css')

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
                        <h2 class="page-title">
                            Invoice
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">
                <div class="row">
                    <div class="col-lg-12">
                        <ul id="myTab" class="nav nav-pills nav-pills-primary" data-bs-toggle="tabs" role="tablist">
                            @foreach ($company as $key => $item)                          
                                @if ($key == 0)
                                    @php
                                        $tab_active = "active";
                                    @endphp
                                @else
                                    @php
                                        $tab_active = "";
                                    @endphp
                                @endif

                                <li class="nav-item me-2" role="presentation">
                                    <a href="#company_{{ $item->id }}" data-company_id="{{$item->id}}" class="nav-link {{$tab_active}}" data-bs-toggle="tab" aria-selected="true" role="tab" onclick="get_table_data({{$item->id}})">
                                        {{ $item->company_name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content mt-3">
                            @foreach ($company as $key => $item)
                                <div class="tab-pane fade {{ $key == 0 ? 'active show' : '' }}" id="company_{{ $item->id }}" role="tabpanel">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive" style="min-height: 500px;">
                                                <table class="table card-table table-vcenter text-left text-nowrap datatable finance_table" id="finance_table_{{$item->id}}" style="width: 100%;">
                                                {{-- <table class="table text-center" id="finance_table"> --}}
                                                    <thead>
                                                        <tr>
                                                            <th class="w-1">No.</th>
                                                            <th>Invoice No.</th>
                                                            <th>Invoice Date</th>
                                                            <th>Service Date</th>
                                                            <th>Customer Name</th>
                                                            <th>Total</th>
                                                            <th>GST</th>
                                                            <th>Grand Total</th>
                                                            <th>Overdue Amount</th>
                                                            <th>Created By</th>
                                                            <th>Payment Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal modal-blur fade" id="send_email_modal" tabindex="-1" style="display: none;" aria-hidden="true">
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
                                <a class="nav-link default" href="#send-step-1">
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

    <script type="text/javascript">

        window.addEventListener('load', function () {
            if (window.location.hash) {
                history.replaceState(null, null, window.location.href.split('#')[0]);
            }
        });

        var company_id = $("#myTab").find(".active").data('company_id');
        var loadedCompanies = []; // Store company IDs that have been loaded
        var finance_table;

        function get_table_data(company_id)
        {
            var table_id = '#finance_table_'+company_id;
            
            // If already loaded, just skip
            if (loadedCompanies.includes(company_id)) {
                return;
            }

            // If already initialized, destroy it
            if ($.fn.DataTable.isDataTable(table_id)) {
                $(table_id).DataTable().clear().destroy();
            }

            // console.log(company_id);

            finance_table = $(table_id).DataTable({
                "lengthChange": false,
                "pageLength": 30,
                ajax: {
                    url: "{{ route('finance.get-table-data-by-company') }}",
                    type: 'GET',
                    data : function(data){
                        data.company_id = company_id
                    }    
                },
                'columnDefs': [{
                    'targets': [0],        // Targets the first column (index 0)
                    'orderable': false,    // Disables sorting on this column
                }]
            }).on('order.dt search.dt', function() {
                var table = $(table_id).DataTable();
                table.column(0, {order:'applied'}).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;  // Update the serial number
                });
            }).draw();

            // Mark this company_id as loaded
            loadedCompanies.push(company_id);
        }

        get_table_data(company_id);

        function findTemplateId()
        {
            var new_schedule_date = $('#send_email_modal').data('schedule_date');
            var quotation_id = $('#send_email_modal').data('quotation_id');
            var temp_invoice_no = $('#send_email_modal').data('invoice_no');
            var customerId = $('#send_email_modal').data('customer_id');
            var templateId = $('#emailTemplateOption').val();

            if(templateId)
            {
                $.ajax({
                    url: "{{ route('finance.get-email-data') }}",
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'template_id': templateId,
                        'customer_id': customerId,
                        'quotation_id': quotation_id,
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
                        email_subject = email_subject.replace("##JOB_DATE##", new_schedule_date);              
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

            var quotation_id = $('#send_email_modal').data('quotation_id');

            $.ajax({
                url: "{{ route('finance.send-email') }}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    email_template_id: email_template_id,
                    email_to: email_to,
                    email_cc: email_cc,
                    email_bcc: email_bcc,
                    email_subject: email_subject,
                    email_body: email_body,
                    quotation_id: quotation_id
                },
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

        $(document).ready(function () {

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

            // send mail start

            $('body').on('click', '.send_mail_btn', function(){

                var invoice_no = $(this).data('invoice_no');
                var customer_id = $(this).data('customer_id');
                var quotation_id = $(this).data('quotation_id');
                var schedule_date = $(this).data('schedule_date');

                $('#send_email_modal').data('invoice_no', invoice_no);
                $('#send_email_modal').data('customer_id', customer_id);
                $('#send_email_modal').data('quotation_id', quotation_id);
                $('#send_email_modal').data('schedule_date', schedule_date);
                $('#send_email_modal').modal('show');

            });

            // select2

            $('.select2').select2({
                dropdownParent: $("#send_email_modal")
            });

            $('#smartwizard2').smartWizard({
                transition: {
                    animation: 'slideHorizontal', // Effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
                }
            });

            // close send invoice modal

            $('#send_email_modal').on('hidden.bs.modal', function () {
                $('#send-step-2').html("");
                $('#smartwizard2').smartWizard("reset"); 
                $(".select2").val("").trigger('change');
            });

            // send mail end

        });
        
    </script>

@endsection
