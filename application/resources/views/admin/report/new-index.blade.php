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

                        {{-- Company Tabs start --}}

                        <ul id="companyTab" class="nav nav-pills nav-pills-primary" data-bs-toggle="tabs" role="tablist">
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
                                    <a href="#company_{{ $item->id }}" data-company_id="{{$item->id}}" class="nav-link {{$tab_active}}" data-bs-toggle="tab" aria-selected="true" role="tab">
                                        {{ $item->company_name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Company Tabs end --}}

                        {{-- Company Tab Contents with Nested Report Tabs start --}}

                        <div class="tab-content mt-3">
                            @foreach ($company as $key => $item)
                                <div class="tab-pane fade {{ $key == 0 ? 'active show' : '' }}" id="company_{{ $item->id }}" role="tabpanel">
                                     
                                    <div class="card">
                                        <div class="card-header">
                                            {{-- Report Tabs for this company start --}}
                                            
                                            <ul class="nav nav-pills nav-pills-primary mb-3" data-bs-toggle="tabs" role="tablist">
                                                <li class="nav-item me-2" role="presentation">
                                                    <a href="#invoice_report_tab_{{ $item->id }}" class="nav-link active" data-bs-toggle="tab" role="tab">
                                                        Invoice
                                                    </a>
                                                </li>

                                                <li class="nav-item me-2" role="presentation">
                                                    <a href="#sales_order_report_tab_{{ $item->id }}" class="nav-link" data-bs-toggle="tab" role="tab">
                                                        Sales Order
                                                    </a>
                                                </li>

                                                <li class="nav-item me-2" role="presentation">
                                                    <a href="#job_order_report_tab_{{ $item->id }}" class="nav-link" data-bs-toggle="tab" role="tab">
                                                    Job Order
                                                    </a>
                                                </li>

                                                <li class="nav-item me-2" role="presentation">
                                                    <a href="#log_report_tab_{{ $item->id }}" class="nav-link" data-bs-toggle="tab" role="tab">
                                                    Log Report
                                                    </a>
                                                </li>

                                                <li class="nav-item me-2" role="presentation">
                                                    <a href="#reminder_log_report_tab_{{ $item->id }}" class="nav-link" data-bs-toggle="tab" role="tab">
                                                    Reminder Log
                                                    </a>
                                                </li>
                                            </ul>

                                            {{-- Report Tabs for this company end --}}
                                        </div>

                                        <div class="card-body">
                                            {{-- Report Tab Content for this company start --}}

                                            <div class="tab-content">
                                                <div class="tab-pane fade show active" id="invoice_report_tab_{{ $item->id }}">
                                                    
                                                    <div class="card-title">
                                                        Invoice Report Content for {{ $item->company_name }}
                                                    </div>

                                                    <div class="row">
                                                        {{-- <div class="col-lg-4 col-md-12 mb-3">
                                                            <label for="">Invoice No</label>
                                                            <input type="text" class="form-control" placeholder="Enter Invoice No" name="invoice_report_filter_invoice_no" id="invoice_report_filter_invoice_no">
                                                        </div> --}}
            
                                                        <div class="col-lg-5 col-md-12 mb-3">
                                                            <label for="">Invoice No</label>
                                                            <div class="d-flex align-items-center">
                                                                <span style="margin-right: 5px;">From</span>
                                                                <input type="text" class="form-control" placeholder="Enter Invoice No" name="invoice_report_filter_invoice_no_from" class="invoice_report_filter_invoice_no_from" style="margin-right: 5px;">
                                                            
                                                                <span style="margin-right: 5px;">To</span>
                                                                <input type="text" class="form-control" placeholder="Enter Invoice No" name="invoice_report_filter_invoice_no_to" class="invoice_report_filter_invoice_no_to">
                                                            </div>
                                                        </div>
            
                                                        <div class="col-lg-3 col-md-12 mb-3">
                                                            <label for="">Customer Name</label>
                                                            <input type="text" class="form-control" placeholder="Enter Customer Name" name="invoice_report_filter_customer_name" class="invoice_report_filter_customer_name">
                                                        </div>
                                                        <div class="col-lg-4 col-md-12 mb-3">
                                                            <label for="">Invoice Date Range</label>
                                                            <input type="text" class="form-control daterange" placeholder="Enter Invoice Date" name="invoice_report_filter_invoice_date" class="invoice_report_filter_invoice_date" value="">
                                                        </div>
                                                        <div class="col-lg-4 col-md-12 mb-3">
                                                            <label for="">Service Type</label>
                                                            <select class="form-select" name="invoice_report_filter_service_type" class="invoice_report_filter_service_type">
                                                                <option value="">Select</option>
                                                                @foreach ($company as $select_comp_item)
                                                                    <option value="{{$select_comp_item->id}}">{{$select_comp_item->company_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-4 col-md-12 mb-3">
                                                            <button type="button" class="btn btn-primary" class="invoice_report_filter_btn" style="margin-top: 20px;">Filter</button>
                                                            <button type="button" class="btn btn-danger" class="invoice_report_clear_btn" style="margin-top: 20px;">Clear</button>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="table-responsive">
                                                                <table class="table card-table table-vcenter text-left text-nowrap datatable invoice_report_table" id="invoice_report_table_{{$item->id}}" style="width: 100%;">
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

                                                <div class="tab-pane fade" id="sales_order_report_tab_{{ $item->id }}">
                                                    
                                                    <div class="card-title">
                                                        Sales Order Report Content for {{ $item->company_name }}
                                                    </div>

                                                    <div class="row">
                                                        {{-- <div class="col-lg-4 col-md-12 mb-3">
                                                            <label for="">Invoice No</label>
                                                            <input type="text" class="form-control" placeholder="Enter Invoice No" name="sales_order_report_filter_invoice_no" id="sales_order_report_filter_invoice_no">
                                                        </div> --}}
            
                                                        <div class="col-lg-5 col-md-12 mb-3">
                                                            <label for="">Invoice No</label>
                                                            <div class="d-flex align-items-center">
                                                                <span style="margin-right: 5px;">From</span>
                                                                <input type="text" class="form-control" placeholder="Enter Invoice No" name="sales_order_report_filter_invoice_no_from" class="sales_order_report_filter_invoice_no_from" style="margin-right: 5px;">
                                                            
                                                                <span style="margin-right: 5px;">To</span>
                                                                <input type="text" class="form-control" placeholder="Enter Invoice No" name="sales_order_report_filter_invoice_no_to" class="sales_order_report_filter_invoice_no_to">
                                                            </div>
                                                        </div>
            
                                                        <div class="col-lg-3 col-md-12 mb-3">
                                                            <label for="">Customer Name</label>
                                                            <input type="text" class="form-control" placeholder="Enter Customer Name" name="sales_order_report_filter_customer_name" class="sales_order_report_filter_customer_name">
                                                        </div>
                                                        <div class="col-lg-4 col-md-12 mb-3">
                                                            <label for="">Invoice Date Range</label>
                                                            <input type="text" class="form-control daterange" placeholder="Enter Invoice Date" name="sales_order_report_filter_invoice_date" class="sales_order_report_filter_invoice_date">
                                                        </div>
                                                        <div class="col-lg-4 col-md-12 mb-3">
                                                            <label for="">Service Type</label>
                                                            <select class="form-select" name="sales_order_report_filter_service_type" class="sales_order_report_filter_service_type">
                                                                <option value="">Select</option>
                                                                @foreach ($company as $select_comp_item)
                                                                    <option value="{{$select_comp_item->id}}">{{$select_comp_item->company_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-4 col-md-12 mb-3">
                                                            <button type="button" class="btn btn-primary" class="sales_order_report_filter_btn" style="margin-top: 20px;">Filter</button>
                                                            <button type="button" class="btn btn-danger" class="sales_order_report_clear_btn" style="margin-top: 20px;">Clear</button>
                                                        </div>
                                                    </div>
                                                    
                                                </div>

                                                <div class="tab-pane fade" id="job_order_report_tab_{{ $item->id }}">
                                                    
                                                    <div class="card-title">
                                                        Job Order Report Content for {{ $item->company_name }}
                                                    </div>

                                                    <div class="row">
                                                        {{-- <div class="col-lg-4 col-md-12 mb-3">
                                                            <label for="">Job Order Id</label>
                                                            <input type="text" class="form-control" placeholder="Enter Job Order Id" name="job_order_report_filter_job_order_id" id="job_order_report_filter_job_order_id">
                                                        </div>        --}}
            
                                                        <div class="col-lg-4 col-md-12 mb-3">
                                                            <label for="">Job Order Id</label>
                                                            <div class="d-flex align-items-center">
                                                                <span style="margin-right: 5px;">From</span>
                                                                <input type="text" class="form-control" placeholder="Enter Job Order Id" name="job_order_report_filter_job_order_id_from" class="job_order_report_filter_job_order_id_from" style="margin-right: 5px;">
                                                            
                                                                <span style="margin-right: 5px;">To</span>
                                                                <input type="text" class="form-control" placeholder="Enter Job Order Id" name="job_order_report_filter_job_order_id_to" class="job_order_report_filter_job_order_id_to">
                                                            </div>
                                                        </div>
            
                                                        <div class="col-lg-4 col-md-12 mb-3">
                                                            <label for="">Service Type</label>
                                                            <select class="form-select" name="job_order_report_filter_service_type" class="job_order_report_filter_service_type">
                                                                <option value="">Select</option>
                                                                @foreach ($company as $select_comp_item)
                                                                    <option value="{{$select_comp_item->id}}">{{$select_comp_item->company_name}}</option>
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
                                                            <input type="text" class="form-control daterange" placeholder="Enter Service Date" name="job_order_report_filter_service_date" class="job_order_report_filter_service_date">
                                                        </div>
                                                        <div class="col-lg-4 col-md-12 mb-3">
                                                            <label for="">Customer Name</label>
                                                            <input type="text" class="form-control" placeholder="Enter Customer Name" name="job_order_report_filter_customer_name" class="job_order_report_filter_customer_name">
                                                        </div>    
                                                        <div class="col-lg-3 col-md-12 mb-3">
                                                            <label for="">Job Status</label>
                                                            <select class="form-select" name="job_order_report_filter_job_status" class="job_order_report_filter_job_status">
                                                                <option value="">Select</option>       
                                                                <option value="0">Pending</option>  
                                                                <option value="1">Work In Progress</option>       
                                                                <option value="2">Completed</option>  
                                                                <option value="3">Cancelled</option>                                                   
                                                            </select>
                                                        </div>   
                                                        <div class="col-lg-2 col-md-12 mb-3">
                                                            <label for="">Stage</label>
                                                            <select class="form-select" name="job_order_report_filter_stage" class="job_order_report_filter_stage">
                                                                <option value="">Select</option>       
                                                                <option value="0">Unassigned</option>  
                                                                <option value="1">Assigned</option>       
                                                                <option value="2">Partial</option>           
                                                                <option value="3">Cancelled</option>                                                   
                                                            </select>
                                                        </div>                                           
                                                        <div class="col-lg-3 col-md-12 mb-3">
                                                            <button type="button" class="btn btn-primary" class="job_order_report_filter_btn" style="margin-top: 20px;">Filter</button>
                                                            <button type="button" class="btn btn-danger" class="job_order_report_clear_btn" style="margin-top: 20px;">Clear</button>
                                                        </div>
                                                    </div>
                                                    
                                                </div>

                                                <div class="tab-pane fade" id="log_report_tab_{{ $item->id }}">
                                                    
                                                    <div class="card-title">
                                                        Log Report Content for {{ $item->company_name }}
                                                    </div>
                                                    
                                                </div>

                                                <div class="tab-pane fade" id="reminder_log_report_tab_{{ $item->id }}">
                                                    
                                                    <div class="card-title">
                                                        Reminder Log Content for {{ $item->company_name }}
                                                    </div>
                                                    
                                                </div>
                                            </div>

                                            {{-- Report Tab Content for this company end --}}
                                        </div>
                                    </div>
        
                                </div>
                            @endforeach
                        </div>

                        {{-- Company Tab Contents with Nested Report Tabs end --}}

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

            var company_id = $("#companyTab").find(".active").data('company_id');

            // invoice report start
                
            var invoice_report_table;

            function get_invoice_report_table_data(company_id)
            {
                var table_id = '#invoice_report_table_'+company_id;
                
                // // If already loaded, just skip
                // if (loadedCompanies.includes(company_id)) {
                //     return;
                // }

                // If already initialized, destroy it
                if ($.fn.DataTable.isDataTable(table_id)) {
                    $(table_id).DataTable().clear().destroy();
                }

                // console.log(company_id);               

                var invoice_report_table = $(table_id).DataTable({
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
                            data.company_id = company_id,
                            data.filter_invoice_no_from = $(".invoice_report_filter_invoice_no_from").val(),
                            data.filter_invoice_no_to = $(".invoice_report_filter_invoice_no_to").val(),
                            data.filter_customer_name = $(".invoice_report_filter_customer_name").val(),
                            data.filter_invoice_date = $(".invoice_report_filter_invoice_date").val(),
                            data.filter_service_type = $(".invoice_report_filter_service_type").find(':selected').val()
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

                // // Mark this company_id as loaded
                // loadedCompanies.push(company_id);
            }

            get_invoice_report_table_data(company_id);


            $('body').on('click', '.invoice_report_filter_btn', function(){

                invoice_report_table.ajax.reload();

            });

            $('body').on('click', '.invoice_report_clear_btn', function(){

                $(".invoice_report_tab input, .invoice_report_tab select").val("");

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