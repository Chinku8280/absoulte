@extends('theme.default')

@section('custom_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')
    <!-- Sidebar -->
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">Report</h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="nav nav-pills nav-pills-primary" data-bs-toggle="tabs" role="tablist">
                            <li class="nav-item me-2" role="presentation">
                                <a href="#invoice_report_tab" class="nav-link active" data-bs-toggle="tab" aria-selected="true"
                                    role="tab">
                                    Invoice
                                </a>
                            </li>
                            <li class="nav-item me-2" role="presentation">
                                <a href="#sales_order_report_tab" class="nav-link" data-bs-toggle="tab" aria-selected="true"
                                    role="tab">
                                    Sales Order
                                </a>
                            </li>
                            <li class="nav-item me-2" role="presentation">
                                <a href="#job_order_report_tab" class="nav-link" data-bs-toggle="tab" aria-selected="true"
                                    role="tab">
                                    Job Order
                                </a>
                            </li>
                            <li class="nav-item me-2" role="presentation">
                                <a href="#log_report_tab" class="nav-link" data-bs-toggle="tab" aria-selected="true"
                                    role="tab">
                                    Log Report
                                </a>
                            </li>
                            <li class="nav-item me-2" role="presentation">
                                <a href="#reminder_log_report_tab" class="nav-link" data-bs-toggle="tab" aria-selected="true"
                                    role="tab">
                                    Reminder log
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content mt-3">
                            <div class="tab-pane active show" id="invoice_report_tab" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            {{-- <div class="col-lg-4 col-md-12 mb-3">
                                                <label for="">Invoice No</label>
                                                <input type="text" class="form-control" placeholder="Enter Invoice No" name="invoice_report_filter_invoice_no" id="invoice_report_filter_invoice_no">
                                            </div> --}}

                                            <div class="col-lg-5 col-md-12 mb-3">
                                                <label for="">Invoice No</label>
                                                <div class="d-flex align-items-center">
                                                    <span style="margin-right: 5px;">From</span>
                                                    <input type="text" class="form-control" placeholder="Enter Invoice No" name="invoice_report_filter_invoice_no_from" id="invoice_report_filter_invoice_no_from" style="margin-right: 5px;">
                                                
                                                    <span style="margin-right: 5px;">To</span>
                                                    <input type="text" class="form-control" placeholder="Enter Invoice No" name="invoice_report_filter_invoice_no_to" id="invoice_report_filter_invoice_no_to">
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-12 mb-3">
                                                <label for="">Customer Name</label>
                                                <input type="text" class="form-control" placeholder="Enter Customer Name" name="invoice_report_filter_customer_name" id="invoice_report_filter_customer_name">
                                            </div>
                                            <div class="col-lg-4 col-md-12 mb-3">
                                                <label for="">Invoice Date Range</label>
                                                <input type="text" class="form-control daterange" placeholder="Enter Invoice Date" name="invoice_report_filter_invoice_date" id="invoice_report_filter_invoice_date" value="">
                                            </div>
                                            <div class="col-lg-4 col-md-12 mb-3">
                                                <label for="">Company</label>
                                                <select class="form-select" name="invoice_report_filter_service_type" id="invoice_report_filter_service_type">
                                                    <option value="">Select</option>
                                                    @foreach ($company as $item)
                                                        <option value="{{$item->id}}">{{$item->company_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-12 mb-3">
                                                <button type="button" class="btn btn-primary" id="invoice_report_filter_btn" style="margin-top: 20px;">Filter</button>
                                                <button type="button" class="btn btn-danger" id="invoice_report_clear_btn" style="margin-top: 20px;">Clear</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table card-table table-vcenter text-left text-nowrap datatable" id="invoice_report_table" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th class="w-1 text-left">No.</th>
                                                        <th class="text-left">Invoice No.</th>
                                                        <th class="text-left">Invoice Date</th>
                                                        <th class="text-left">Service Type</th>
                                                        <th class="text-left">Service Date</th>
                                                        <th class="text-left">Customer Name</th>
                                                        <th class="text-left">Total</th>
                                                        <th class="text-left">GST</th>
                                                        <th class="text-left">Grand Total</th>
                                                        <th class="text-left">Overdue Amount</th>
                                                        <th class="text-left">Created By</th>
                                                        <th class="text-left">Payment Status</th>
                                                        <th class="text-left">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="sales_order_report_tab" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            {{-- <div class="col-lg-4 col-md-12 mb-3">
                                                <label for="">Invoice No</label>
                                                <input type="text" class="form-control" placeholder="Enter Invoice No" name="sales_order_report_filter_invoice_no" id="sales_order_report_filter_invoice_no">
                                            </div> --}}

                                            <div class="col-lg-5 col-md-12 mb-3">
                                                <label for="">Invoice No</label>
                                                <div class="d-flex align-items-center">
                                                    <span style="margin-right: 5px;">From</span>
                                                    <input type="text" class="form-control" placeholder="Enter Invoice No" name="sales_order_report_filter_invoice_no_from" id="sales_order_report_filter_invoice_no_from" style="margin-right: 5px;">
                                                
                                                    <span style="margin-right: 5px;">To</span>
                                                    <input type="text" class="form-control" placeholder="Enter Invoice No" name="sales_order_report_filter_invoice_no_to" id="sales_order_report_filter_invoice_no_to">
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-12 mb-3">
                                                <label for="">Customer Name</label>
                                                <input type="text" class="form-control" placeholder="Enter Customer Name" name="sales_order_report_filter_customer_name" id="sales_order_report_filter_customer_name">
                                            </div>
                                            <div class="col-lg-4 col-md-12 mb-3">
                                                <label for="">Invoice Date Range</label>
                                                <input type="text" class="form-control daterange" placeholder="Enter Invoice Date" name="sales_order_report_filter_invoice_date" id="sales_order_report_filter_invoice_date">
                                            </div>
                                            <div class="col-lg-4 col-md-12 mb-3">
                                                <label for="">Company</label>
                                                <select class="form-select" name="sales_order_report_filter_service_type" id="sales_order_report_filter_service_type">
                                                    <option value="">Select</option>
                                                    @foreach ($company as $item)
                                                        <option value="{{$item->id}}">{{$item->company_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-12 mb-3">
                                                <button type="button" class="btn btn-primary" id="sales_order_report_filter_btn" style="margin-top: 20px;">Filter</button>
                                                <button type="button" class="btn btn-danger" id="sales_order_report_clear_btn" style="margin-top: 20px;">Clear</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table card-table table-vcenter text-left text-nowrap datatable" id="sales_order_report_table" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th class="w-1 text-left">No.</th>
                                                        <th class="text-left">Invoice No</th>  
                                                        <th class="text-left">Status</th>
                                                        <th class="text-left">Service Date</th>
                                                        <th class="text-left">Service End date</th>
                                                        <th class="text-left">Customer</th>
                                                        <th class="text-left">Sales Order Id</th>
                                                        {{-- <th class="text-left">Team / Cleaner</th> --}}                                                       
                                                        <th class="text-left">Services Category</th>                                                      
                                                        <th class="text-left">Contact Person</th>
                                                        <th class="text-left">Phone Number</th>
                                                        <th class="text-left">E-mail</th>  
                                                        <th class="text-left">Billing Address</th>
                                                        <th class="text-left">Service Address</th>
                                                        <th class="text-left">Zone</th>
                                                        <th class="text-left">House Type</th>
                                                        <th class="text-left">Payment Mode</th>
                                                        <th class="text-left">Product Description</th>
                                                        <th class="text-left">Quantity</th>
                                                        <th class="text-left">Discount</th>
                                                        <th class="text-left">Total</th>
                                                        <th class="text-left">GST Amount</th>  
                                                        <th class="text-left">Grand Total</th>
                                                        <th class="text-left">Assigned To</th>                                                                                                          
                                                        {{-- <th class="text-left">Job Status</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="job_order_report_tab" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            {{-- <div class="col-lg-4 col-md-12 mb-3">
                                                <label for="">Job Order Id</label>
                                                <input type="text" class="form-control" placeholder="Enter Job Order Id" name="job_order_report_filter_job_order_id" id="job_order_report_filter_job_order_id">
                                            </div>        --}}

                                            <div class="col-lg-4 col-md-12 mb-3">
                                                <label for="">Job Order Id</label>
                                                <div class="d-flex align-items-center">
                                                    <span style="margin-right: 5px;">From</span>
                                                    <input type="text" class="form-control" placeholder="Enter Job Order Id" name="job_order_report_filter_job_order_id_from" id="job_order_report_filter_job_order_id_from" style="margin-right: 5px;">
                                                
                                                    <span style="margin-right: 5px;">To</span>
                                                    <input type="text" class="form-control" placeholder="Enter Job Order Id" name="job_order_report_filter_job_order_id_to" id="job_order_report_filter_job_order_id_to">
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-12 mb-3">
                                                <label for="">Company</label>
                                                <select class="form-select" name="job_order_report_filter_service_type" id="job_order_report_filter_service_type">
                                                    <option value="">Select</option>
                                                    @foreach ($company as $item)
                                                        <option value="{{$item->id}}">{{$item->company_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- <div class="col-lg-4 col-md-12 mb-3">
                                                <label for="">Service Category</label>
                                                <select class="form-select" name="job_order_report_filter_service_category" id="job_order_report_filter_service_category">
                                                    <option value="">Select</option>                                                   
                                                </select>
                                            </div> --}}
                                            <div class="col-lg-4 col-md-12 mb-3">
                                                <label for="">Service Date Range</label>
                                                <input type="text" class="form-control daterange" placeholder="Enter Service Date" name="job_order_report_filter_service_date" id="job_order_report_filter_service_date">
                                            </div>
                                            <div class="col-lg-4 col-md-12 mb-3">
                                                <label for="">Customer Name</label>
                                                <input type="text" class="form-control" placeholder="Enter Customer Name" name="job_order_report_filter_customer_name" id="job_order_report_filter_customer_name">
                                            </div>    
                                            <div class="col-lg-3 col-md-12 mb-3">
                                                <label for="">Job Status</label>
                                                <select class="form-select" name="job_order_report_filter_job_status" id="job_order_report_filter_job_status">
                                                    <option value="">Select</option>       
                                                    <option value="0">Pending</option>  
                                                    <option value="1">Work In Progress</option>       
                                                    <option value="2">Completed</option>  
                                                    <option value="3">Cancelled</option>                                                   
                                                </select>
                                            </div>   
                                            <div class="col-lg-2 col-md-12 mb-3">
                                                <label for="">Stage</label>
                                                <select class="form-select" name="job_order_report_filter_stage" id="job_order_report_filter_stage">
                                                    <option value="">Select</option>       
                                                    <option value="0">Unassigned</option>  
                                                    <option value="1">Assigned</option>       
                                                    <option value="2">Partial</option>           
                                                    <option value="3">Cancelled</option>                                                   
                                                </select>
                                            </div>                                           
                                            <div class="col-lg-3 col-md-12 mb-3">
                                                <button type="button" class="btn btn-primary" id="job_order_report_filter_btn" style="margin-top: 20px;">Filter</button>
                                                <button type="button" class="btn btn-danger" id="job_order_report_clear_btn" style="margin-top: 20px;">Clear</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table card-table table-vcenter text-left datatable" id="job_order_report_table" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th class="w-1">No.</th>
                                                        {{-- <th class="text-left">Job Order Id</th> --}}
                                                        <th class="text-left">Session</th>
                                                        <th class="text-left">Schedule Date</th>
                                                        <th class="text-left">Schedule Time</th>
                                                        <th class="text-left">Stage</th>  
                                                        <th class="text-left">Customer</th>
                                                        <th class="text-left">Service Address</th>
                                                        <th class="text-left">Invoice</th>
                                                        <th class="text-left">Product Description</th>
                                                        <th class="text-left">Remarks</th>
                                                        <th class="text-left">Total</th>
                                                        <th class="text-left">GST Amount</th>
                                                        <th class="text-left">Grand Total</th>
                                                        <th class="text-left">Service Type</th>
                                                        <th class="text-left">Helper</th>
                                                        <th class="text-left">Job Status</th> 
                                                        <th class="text-left">Helper Type</th>
                                                        <th class="text-left">Sales Order</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="log_report_tab" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">

                                    </div>

                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table card-table table-vcenter text-left text-nowrap datatable" id="log_report_table" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th class="w-1 text-left">No.</th>
                                                        <th class="text-left">Event</th>
                                                        <th class="text-left">Message</th>
                                                        <th class="text-left">Ref. No.</th>
                                                        <th class="text-left">Action By</th>
                                                        <th class="text-left">Time Stamp</th> 
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="reminder_log_report_tab" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">

                                    </div>

                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table card-table table-vcenter text-left text-nowrap datatable" id="reminder_log_report_table" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th class="w-1 text-left">No.</th>
                                                        <th class="text-left">Customer Name</th>
                                                        <th class="text-left">Customer Email</th>
                                                        <th class="text-left">Schedule Date</th>
                                                        <th class="text-left">Date & Time</th>
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
        </div>
    </div>
@endsection

@section('javascript')

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    
    <script type="text/javascript">

        $(document).ready(function () {
            
            $(".select2").select2();

            $('.daterange').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD-MM-YYYY'
                }
            });

            $('.daterange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
            });

            $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            // invoice report start
                
            var invoice_report_table = $('#invoice_report_table').DataTable({
                "lengthChange": false,
                "pageLength": 30,
                dom: 'Bfrtip',    
                buttons: [
                    // 'copy', 'csv', 'excel', 'pdf', 'print',
                    {
                        extend: 'copy',                  
                        title: 'Invoice Report',     
                        exportOptions: {
                            columns: ':not(:last-child)',
                        }
                    },
                    {
                        extend: 'csv',                   
                        title: 'Invoice Report',    
                        exportOptions: {
                            columns: ':not(:last-child)',
                        }
                    },
                    {
                        extend: 'excel',                 
                        title: 'Invoice Report',      
                        exportOptions: {
                            columns: ':not(:last-child)',
                        }
                    },
                    {
                        extend: 'pdf',                   
                        title: 'Invoice Report',    
                        exportOptions: {
                            columns: ':not(:last-child)',
                        }
                    },
                    {
                        extend: 'print',                 
                        title: 'Invoice Report',      
                        exportOptions: {
                            columns: ':not(:last-child)',
                        }
                    }                                 
                ],  
                ajax: {
                    url: "{{ route('report.invoice-table-data') }}",
                    type: 'GET',
                    data: function(data) {
                        data.filter_invoice_no_from = $("#invoice_report_filter_invoice_no_from").val(),
                        data.filter_invoice_no_to = $("#invoice_report_filter_invoice_no_to").val(),
                        data.filter_customer_name = $("#invoice_report_filter_customer_name").val(),
                        data.filter_invoice_date = $("#invoice_report_filter_invoice_date").val(),
                        data.filter_service_type = $("#invoice_report_filter_service_type").find(':selected').val()
                    }
                },
                'columnDefs': [{
                    'targets': [0],        // Targets the first column (index 0)
                    'orderable': false,    // Disables sorting on this column
                }]
            }).on('order.dt search.dt', function() {
                var table = $('#invoice_report_table').DataTable();
                table.column(0, {order:'applied'}).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;  // Update the serial number
                });
            }).draw();

            $('body').on('click', '#invoice_report_filter_btn', function(){

                invoice_report_table.ajax.reload();

            });

            $('body').on('click', '#invoice_report_clear_btn', function(){

                $("#invoice_report_tab input, #invoice_report_tab select").val("");

                invoice_report_table.ajax.reload();

            });

            // invoice report end

            // sales order report start

            var sales_order_report_table = $('#sales_order_report_table').DataTable({
                "lengthChange": false,
                "pageLength": 30,
                dom: 'Bfrtip',    
                buttons: [
                    // 'copy', 'csv', 'excel', 'pdf', 'print',
                    {
                        extend: 'copy',                  
                        title: 'Sales Order Report',     
                    },
                    {
                        extend: 'csv',                   
                        title: 'Sales Order Report',    
                    },
                    {
                        extend: 'excel',                 
                        title: 'Sales Order Report',      
                    },
                    {
                        extend: 'pdf',                   
                        title: 'Sales Order Report',    
                    },
                    {
                        extend: 'print',                 
                        title: 'Sales Order Report',      
                    }                                 
                ], 
                ajax: {
                    url: "{{ route('report.sales-order-table-data') }}",
                    type: 'GET',
                    data: function(data) {
                        data.filter_invoice_no_from = $("#sales_order_report_filter_invoice_no_from").val(),
                        data.filter_invoice_no_to = $("#sales_order_report_filter_invoice_no_to").val(),
                        data.filter_customer_name = $("#sales_order_report_filter_customer_name").val(),
                        data.filter_invoice_date = $("#sales_order_report_filter_invoice_date").val(),
                        data.filter_service_type = $("#sales_order_report_filter_service_type").find(':selected').val()
                    }
                },
                'columnDefs': [{
                    'targets': [0],        // Targets the first column (index 0)
                    'orderable': false,    // Disables sorting on this column
                }]
            }).on('order.dt search.dt', function() {
                var table = $('#sales_order_report_table').DataTable();
                table.column(0, {order:'applied'}).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;  // Update the serial number
                });
            }).draw();

            $('body').on('click', '#sales_order_report_filter_btn', function(){

                sales_order_report_table.ajax.reload();

            });

            $('body').on('click', '#sales_order_report_clear_btn', function(){

                $("#sales_order_report_tab input, #sales_order_report_tab select").val("");

                sales_order_report_table.ajax.reload();

            });

            // sales order report end

            // job order report start

            var job_order_report_table = $('#job_order_report_table').DataTable({
                "lengthChange": false,
                "pageLength": 30,
                dom: 'Bfrtip',    
                buttons: [
                    // 'copy', 'csv', 'excel', 'pdf', 'print',
                    {
                        extend: 'copy',                  
                        title: 'Job Order Details Report',     
                    },
                    {
                        extend: 'csv',                   
                        title: 'Job Order Details Report',    
                    },
                    {
                        extend: 'excel',                 
                        title: 'Job Order Details Report',      
                    },
                    {
                        extend: 'pdf',                   
                        title: 'Job Order Details Report',    
                    },
                    {
                        extend: 'print',                 
                        title: 'Job Order Details Report',      
                    }                                 
                ], 
                ajax: {
                    url: "{{ route('report.job-order-details-table-data') }}",
                    type: 'GET',
                    data: function(data) {
                        data.filter_job_order_id_from = $("#job_order_report_filter_job_order_id_from").val(),
                        data.filter_job_order_id_to = $("#job_order_report_filter_job_order_id_to").val(),
                        data.filter_service_type = $("#job_order_report_filter_service_type").find(':selected').val(),
                        data.filter_service_date = $("#job_order_report_filter_service_date").val(),
                        data.filter_customer_name = $("#job_order_report_filter_customer_name").val(),                    
                        data.filter_job_status = $("#job_order_report_filter_job_status").find(':selected').val(),
                        data.filter_stage = $("#job_order_report_filter_stage").find(':selected').val()
                    }
                },
                'columnDefs': [{
                    'targets': [0],        // Targets the first column (index 0)
                    'orderable': false,    // Disables sorting on this column
                }]
            }).on('order.dt search.dt', function() {
                var table = $('#job_order_report_table').DataTable();
                table.column(0, {order:'applied'}).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;  // Update the serial number
                });
            }).draw();

            $('body').on('click', '#job_order_report_filter_btn', function(){

                job_order_report_table.ajax.reload();

            });

            $('body').on('click', '#job_order_report_clear_btn', function(){

                $("#job_order_report_tab input, #job_order_report_tab select").val("");

                job_order_report_table.ajax.reload();

            });

            // job order report end

            // log report start

            var log_report_table = $('#log_report_table').DataTable({
                "lengthChange": false,
                "pageLength": 30,
                dom: 'Bfrtip',    
                buttons: [
                    // 'copy', 'csv', 'excel', 'pdf', 'print',
                    {
                        extend: 'copy',                  
                        title: 'Log Report',     
                    },
                    {
                        extend: 'csv',                   
                        title: 'Log Report',    
                    },
                    {
                        extend: 'excel',                 
                        title: 'Log Report',      
                    },
                    {
                        extend: 'pdf',                   
                        title: 'Log Report',    
                    },
                    {
                        extend: 'print',                 
                        title: 'Log Report',      
                    }                                 
                ], 
                ajax: {
                    url: "{{ route('report.log-details-table-data') }}",
                    type: 'GET',
                },
                'columnDefs': [{
                    'targets': [0],        // Targets the first column (index 0)
                    'orderable': false,    // Disables sorting on this column
                }]
            }).on('order.dt search.dt', function() {
                var table = $('#log_report_table').DataTable();
                table.column(0, {order:'applied'}).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;  // Update the serial number
                });
            }).draw();

            // log report end

            // log report start

            var reminder_log_report_table = $('#reminder_log_report_table').DataTable({
                "lengthChange": false,
                "pageLength": 30,
                dom: 'Bfrtip',    
                buttons: [
                    // 'copy', 'csv', 'excel', 'pdf', 'print',
                    {
                        extend: 'copy',                  
                        title: 'Log Report',     
                    },
                    {
                        extend: 'csv',                   
                        title: 'Log Report',    
                    },
                    {
                        extend: 'excel',                 
                        title: 'Log Report',      
                    },
                    {
                        extend: 'pdf',                   
                        title: 'Log Report',    
                    },
                    {
                        extend: 'print',                 
                        title: 'Log Report',      
                    }                                 
                ], 
                ajax: {
                    url: "{{ route('report.reminder-log-report-table-data') }}",
                    type: 'GET',
                },
                'columnDefs': [{
                    'targets': [0],        // Targets the first column (index 0)
                    'orderable': false,    // Disables sorting on this column
                }]
            }).on('order.dt search.dt', function() {
                var table = $('#reminder_log_report_table').DataTable();
                table.column(0, {order:'applied'}).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;  // Update the serial number
                });
            }).draw();

            // log report end

        });
        
    </script>

@endsection