  
  @extends('theme.default')
@section('content')
  <div class="page-wrapper">
            <!-- Page header -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                  <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                           Services
                        </h2>


                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                     
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
                                  <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                      <a href="#category" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                       
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 icon-tabler icon-tabler-apps" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <rect x="4" y="4" width="6" height="6" rx="1" />
                                            <rect x="4" y="14" width="6" height="6" rx="1" />
                                            <rect x="14" y="14" width="6" height="6" rx="1" />
                                            <line x1="14" y1="7" x2="20" y2="7" />
                                            <line x1="17" y1="4" x2="17" y2="10" />
                                          </svg>
                                        Company</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                      <a href="#service" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab"><!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                    
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 icon-tabler icon-tabler-tool" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5"></path>
                                          </svg>
                                        Service</a>
                                    </li>
                                  </ul>
                                </div>
                                <div class="card-body">
                                  <div class="tab-content">
                                    <div class="tab-pane active show" id="category" role="tabpanel">
                                        <div class="row g-2 align-items-center w-100">
                                            <div class="col-3">
                                                <div class="mb-3">
                                                  <form action="./" method="get" autocomplete="off" novalidate="">
                                                    <div class="input-icon">
                                                        <span class="input-icon-addon">
                                                            <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                                <path d="M21 21l-6 -6"></path>
                                                            </svg>
                                                        </span>
                                                        <input type="text" value="" class="form-control" placeholder="Categoery Name..." aria-label="Search in website">
                                                    </div>
                                                </form>
                                                  </div>
                                            </div>
                                            <div class="col-auto ms-auto d-print-none">
                                              
                                                <a href="{{route('company.create')}}" class="btn btn-primary m-0" data-bs-toggle="modal" data-bs-target="#add-company" onclick="showFormModal()">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M12 5l0 14"></path>
                                                        <path d="M5 12l14 0"></path>
                                                    </svg>
                                                    Add New Company
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card">
                                            
                                           
                                                <div class="table-responsive">
                                                    <table id="company-table" class="table card-table table-vcenter text-center text-nowrap datatable">
                                                        <thead>
                                                            <tr>
                                                                <th class="w-1">No.</th>
                                                                <th>Company Name</th>
                                                                <th>Description</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                    
                                                    </table>
                                                </div>
                                           
    
                                           
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="service" role="tabpanel">
                                       
                                       
                                            <div class="row w-100">
                                                <div class="col-3">
                                                    <div class="mb-3">
                                                    
                                                        <select class="form-select">
                                                            <option value="0">Select Company</option>
                                                          <option value="1">Floor Cleaning</option>
                                                          <option value="2">Home Cleaning</option>
                                                          <option value="3">Office Cleaning</option>
                                                        </select>
                                                      </div>
                                                </div>
                                             
                                                <div class="col-3">
                                                    <div class="mb-3">
                                                      <form action="./" method="get" autocomplete="off" novalidate="">
                                                        <div class="input-icon">
                                                            <span class="input-icon-addon">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                                    <path d="M21 21l-6 -6"></path>
                                                                </svg>
                                                            </span>
                                                            <input type="text" value="" class="form-control" placeholder="Service Name..." aria-label="Search in website">
                                                        </div>
                                                    </form>
                                                      </div>
                                                </div>
                                                <div class="col-auto ms-auto d-print-none">
                                                    <a href="{{route('service.create')}}" class="btn btn-primary m-0" data-bs-toggle="modal" data-bs-target="#add-service" onclick="showServiceModal()">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path d="M12 5l0 14"></path>
                                                            <path d="M5 12l14 0"></path>
                                                        </svg>
                                                        Add New
                                                    </a>
                                                </div>
                                            </div>
                                                <div class="table-responsive">
                                                    <table id="service-table" class="table card-table table-vcenter text-center text-nowrap datatable">
                                                        <thead>
                                                            <tr>
                                                                <th class="w-1">No.</th>
                                                                <th>Company</th>
                                                                <th>Service Name</th>
                                                                <th>Price</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
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
        <!-- MODEL -->
        <div class="modal modal-blur fade" id="add-company" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content" id="add-company-model">
              
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="edit-company" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content" id="edit-company-model">
              
            </div>
        </div>
    </div>

     <div class="modal modal-blur fade" id="add-service" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content" id="add-service-model">
              
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="edit-service" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content" id="edit-service-model">
              
            </div>
        </div>
    </div>
    @section('javascript')
        <script>
            function showFormModal(){
                $.ajax({
                    url:"{{route('company.create')}}",
                    type:"GET",
                    success:function(response){
                        
                    $('#add-company').modal('show');
                    $('#add-company-model').html(response);
                   
                    
                    },
                    error:function(){
                      console.log('Error occurred while loading the modal content.');

                    }
                });
            }
               function edit_company_modal(id) {
                    $.ajax({
                        url: "{{ route('company.edit', ['id' => ':id']) }}".replace(':id', id),
                        type: "get",
                        success: function (response) {
                        $('#edit-company').modal('show');
                                    $('#edit-company-model').html(response);
                        },
                        error: function() {
                            console.log('Error occurred while loading the edit modal content.');
                        }
                    });
                    }
       
            $(document).ready(function() {
           var companyTable =  $('#company-table').DataTable({
            "aaSorting": [],
             rowReorder: {
        selector: 'td:nth-child(2)'
      },
                  processing: true,
                    serverSide: true,
                    searching: false,
                  paging: false,
                    ajax: '{{ route('company.data') }}',
                    columns: [
                        {data:'sno', name: 'No' },
                        { data: 'company_name', name: 'Company Name' },   
                        { data: 'description', name: 'Description' },              

                        { data: 'action', title: 'Action' }
                    
            ]
                });
                $(document).on('click', '.btn_delete_crm', function() {
        var customerId = $(this).data('company-id');
        // alert(customerId)
        if (confirm('Are you sure you want to delete this Company?')) {
            $.ajax({
                url: '/delete-company/' + customerId,
                type: 'POST', 
                data: {
                    _method: 'DELETE', 
                    _token: '{{ csrf_token() }}' 
                },
                success: function(response) {
                   if (response.errors) {
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
            
                } else {
                iziToast.success({
                message: response.success,
                position: 'topRight'
           
                 });
                   
        }
                    $('#company-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        }
    });
            });
        </script>
        <script>
            function showServiceModal(){
                $.ajax({
                    url:"{{route('service.create')}}",
                    type:"GET",
                    success:function(response){
                        
                    $('#add-service').modal('show');
                    $('#add-service-model').html(response);
                   
                    
                    },
                    error:function(){
                      console.log('Error occurred while loading the modal content.');

                    }
                });
            }
               function edit_service_modal(id) {
    $.ajax({
        url: "{{ route('service.edit', ['id' => ':id']) }}".replace(':id', id),
        type: "get",
        success: function (response) {
         $('#edit-service').modal('show');
                    $('#edit-service-model').html(response);
        },
        error: function() {
            console.log('Error occurred while loading the edit modal content.');
        }
    });
}
     
        </script>
        <script>
             $(document).ready(function() {
           var companyTable =  $('#service-table').DataTable({
                  processing: true,
                    serverSide: true,
                    searching: false,
                  paging: false,
                    ajax: '{{ route('service.data') }}',
                    columns: [
                        {data:'sno', name: 'No' },
                        { data: 'company', name: 'Company Name' },    
                        { data: 'service_name', name: 'Service Name' },              
                        { data: 'price', name: 'Price' },              
                        { data: 'action', title: 'Action' }
                    
            ]
                });
                  $(document).on('click', '.btn_delete_service', function() {
        var serviceId = $(this).data('service-id');
        
        if (confirm('Are you sure you want to delete this Service?')) {
            $.ajax({
                url: '/delete-service/' + serviceId,
                type: 'POST', 
                data: {
                    _method: 'DELETE', 
                    _token: '{{ csrf_token() }}' 
                },
                success: function(response) {
                    if (response.errors) {
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
            
                } else {
                iziToast.success({
                message: response.success,
                position: 'topRight'
           
                 });
                   
        }
                    $('#service-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        }
    });
                });

        </script>
        @endsection
        @endsection
 