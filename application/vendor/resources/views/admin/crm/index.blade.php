@extends('theme.default')
@section('content')
<div class="page-wrapper">
   <!-- Page header -->
   <div class="page-header d-print-none">
      <div class="container-xl">
         <div class="row g-2 align-items-center">
            <div class="col">
               <h2 class="page-title">
                  CRM
               </h2>
            </div>
            <!-- Page title actions -->
            <div class="col-auto ms-auto d-print-none">
               <div class="btn-list">
                  <a href="{{route('crm.create')}}" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                     data-bs-target="#add-crm-modal" onclick="showFormModal()">
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
         </div>
         <div class="row g-2 align-items-center">
            <div class="col">
            </div>
         </div>
      </div>
   </div>
   <!-- Page body -->
   <div class="page-body">
      <div class="container-xl">
         <div class="row">
            <div class="col-lg-12">
               <div class="card">
                  <div class="card-header">
                     <ul class="nav nav-pills nav-pills-primary" data-bs-toggle="tabs" role="tablist" id="customerTabs">
                        <li class="nav-item me-2" role="presentation">
                           <a href="#residential" class="nav-link active" data-bs-toggle="tab"
                              aria-selected="true" role="tab">Residential</a>
                        </li>
                        <li class="nav-item me-2" role="presentation">
                           <a href="#commercial" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                              role="tab" tabindex="-1">Commercial</a>
                        </li>
                     </ul>
                     <div class="card-actions">
                     </div>
                  </div>


                  <div class="tab-content">
                     <div class="tab-pane active show" id="residential" role="tabpanel">
                        <div class="card-body border-bottom py-3">
                           <div class="d-flex">
                              <div class="text-muted">
                                 Show
                                 <div class="mx-2 d-inline-block">
                                    <input type="text" class="form-control form-control-sm"
                                       value="8" size="3" aria-label="Invoices count">
                                 </div>
                                 entries
                              </div>
                              <div class="ms-auto text-muted">
                                 Search:
                                 <div class="ms-2 d-inline-block">
                                    <input type="text" class="form-control form-control-sm"
                                       aria-label="Search invoice">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="table-responsive">
                           <table id="residential-customer-table"
                              class="table card-table table-vcenter text-center text-nowrap datatable">
                              <thead>
                                 <tr>
                                    <th class="w-1">No.</th>
                                    <th>Status</th>
                                    <th>Customer ID</th>
                                    <th>Customer name</th>
                                    <th>Contact Number</th>
                                    <th>Contact Address</th>
                                    <th>Outstanding Amount</th>
                                    <th></th>
                                 </tr>
                              </thead>
                               
                           </table>
                        </div>
                        <!-- <div class="card-footer d-flex align-items-center">
                           <p class="m-0 text-muted">Showing <span>1</span> to <span>1</span> of
                              <span>1</span> entries
                           </p>
                           <ul class="pagination m-0 ms-auto">
                           </ul>
                        </div> -->
                     </div>
                     <div class="tab-pane" id="commercial" role="tabpanel">
                        <div class="card-body border-bottom py-3">
                           <div class="d-flex">
                              <div class="text-muted">
                                 Show
                                 <div class="mx-2 d-inline-block">
                                    <input type="text" class="form-control form-control-sm"
                                       value="8" size="3" aria-label="Invoices count">
                                 </div>
                                 entries
                              </div>
                              <div class="ms-auto text-muted">
                                 Search:
                                 <div class="ms-2 d-inline-block">
                                    <input type="text" class="form-control form-control-sm"
                                       aria-label="Search invoice">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="table-responsive">
                           <table id="commercial-customer-table"
                              class="table card-table table-vcenter text-center text-nowrap datatable">
                              <thead>
                                 <tr>
                                    <th class="w-1">No</th>
                                    <th>Status</th>
                                    <th>Customer ID</th>
                                    <th>Customer name</th>
                                    <th>Contact Number</th>
                                    <th>Contact Address</th>
                                    <th>Outstanding Amount</th>
                                   <th></th>
                                 </tr>
                              </thead>
                          
                           </table>
                        </div>
                        <!-- <div class="card-footer d-flex align-items-center">
                           <p class="m-0 text-muted">Showing <span>1</span> to <span>1</span> of
                              <span>1</span> entries
                           </p>
                           <ul class="pagination m-0 ms-auto">
                           </ul>
                        </div> -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<div class="modal modal-blur fade" id="add-crm-modal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 800px;">
      <div class="modal-content" id="add-crm-model-content">
      </div>
   </div>
</div>

<div class="modal modal-blur fade" id="edit-crm-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 800px;">
        <div class="modal-content" id="edit-crm-model-content">
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="view-crm-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 800px;">
        <div class="modal-content" id="view-crm-model-content">
        </div>
    </div>
</div>

@section('javascript')

<script>
  function showFormModal() {
   $.ajax({
       url: "{{ route('crm.create') }}",
       type: "GET",
       success: function(response) {
           $('#add-crm-modal').modal('show');
           $('#add-crm-model-content').html(response);
       },
       error: function() {
           console.log('Error occurred while loading the modal content.');
       }
   });
}

function edit_crm_modal(id) {
    $.ajax({
        url: "{{ route('crm.edit', ['id' => ':id']) }}".replace(':id', id),
        type: "get",
        success: function (response) {
            $('#edit-crm-modal').modal('show');
            $('#edit-crm-model-content').html(response);
        },
        error: function() {
            console.log('Error occurred while loading the edit modal content.');
        }
    });
}
function view_crm_modal(id) {
    $.ajax({
        url: "{{ route('crm.view', ['id' => ':id']) }}".replace(':id', id),
        type: "get",
        success: function (response) {
            $('#edit-crm-modal').modal('show');
            $('#edit-crm-model-content').html(response);
        },
        error: function() {
            console.log('Error occurred while loading the edit modal content.');
        }
    });
}
</script>
         <script>
            $(document).ready(function() {
                $(document).on('click', '.btn_delete_crm_commercial', function() {
               var customerId = $(this).data('customer-id');
  
        if (confirm('Are you sure you want to delete this commercial customer?')) {
            $.ajax({
                url: '/delete-commercial/' + customerId,
                type: 'POST', 
                data: {
                    _method: 'DELETE', 
                    _token: '{{ csrf_token() }}' 
                },
                success: function(response) {
                    alert('Customer deleted successfully');
                    $('#commercial-customer-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        }
    });

               $('#commercial-customer-table').DataTable({
                  processing: true,
                    serverSide: true,
                    searching: false,
                      paging: false,
                    ajax: '{{ route('customers.commercial') }}',
                    columns: [
                        {data:'sno', name: 'No' },
                         { data: 'status', name: 'Status' },
                         { data: 'id', name: 'id' },
                         {data: 'customer_name' , name:'Customer name'},
                        { data: 'mobile_number', name: 'Contact Number' },
                        { data: 'email', name: 'Contact Address' },
                        { data: 'outstanding_Amount', name: 'Outstanding Amount' },                     
                        { data: 'action', title: 'Action' }
                    
            ]
            });
            });
         


            </script>
        
        <script>
$(document).ready(function() {
    $(document).on('click', '.btn_delete_crm', function() {
        var customerId = $(this).data('customer-id');
        if (confirm('Are you sure you want to delete this customer?')) {
            $.ajax({
                url: '/delete-residential/' + customerId,
                type: 'POST', 
                data: {
                    _method: 'DELETE', 
                    _token: '{{ csrf_token() }}' 
                },
                success: function(response) {
                    alert('Customer deleted successfully');
                    $('#residential-customer-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        }
    });

    // DataTable initialization
    $('#residential-customer-table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        paging: false,
        ajax: '{{ route('customers.residential') }}',
        columns: [
            { data: 'sno', name: 'No' },
            { data: 'status', name: 'Status' },
            { data: 'id', name: 'id' },
            { data: 'customer_name', name: 'Customer name' },
            { data: 'mobile_number', name: 'Contact Number' },
            { data: 'email', name: 'Contact Address' },
            { data: 'outstanding_Amount', name: 'Outstanding Amount' },
            { data: 'action', title: 'Action' }
        ]
    });
});
</script>


@endsection
@endsection