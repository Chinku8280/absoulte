@extends('theme.default')
@section('content')


          <div class="page-wrapper">
            <!-- Page header -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <h2 class="page-title">
                                Territory
                            </h2>


                        </div>
                        <!-- Page title actions -->
                        <div class="col-auto ms-auto d-print-none">
                            <div class="btn-list">

                               <a href="{{route('territory.create')}}" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                                    data-bs-target="#add-customer" onclick="showFormModal()">
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
           
            <div class="page-body">
                <div class="container-xl">
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="card">
                            
                                <div class="tab-content">
                                    <div class="tab-pane active show" id="residential" role="tabpanel">
                                        <div class="card-body border-bottom py-3">
                                            <!-- <div class="d-flex">
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
                                            </div> -->
                                        </div>
                                        <div class="table-responsive">
                                            <table id="territory-table" class="table card-table table-vcenter text-center text-nowrap datatable">
                                                <thead>
                                                    <tr>
                                                   <th>{{('S.no')}}</th>
                                                        <th>{{('Name')}}</th>
                                                        <th>{{('Action')}}</th>

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
            <footer class="footer footer-transparent d-print-none">

            </footer>
        </div>
    </div>

  
  <div class="modal modal-blur fade" id="add-territory" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 800px;">
            <div class="modal-content" id="model-show-data">

        </div>
    </div>
</div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
var jq = jQuery.noConflict();
var territoryTable = jq(document).ready(function() {
  jq('#territory-table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
    paging: false,
        ajax:{
            url:'{{ route("territory.showData") }}',
            type: 'GET',

        },
         columns: [
            { data: null, title: 'S.No' },
            { data: 'territory_name', title: 'Territory Name' },
            { data: 'action', title: 'Action' }
        ],
        initComplete: function() {
           
            var table = this.api();
            var rows = table.rows().nodes();
            jq.each(rows, function(index, row) {
                jq(row).find('td:first-child').text(index + 1);
            });
        } 
       
    });
});

function showFormModal() {
    // console.log('cghjk');
    $.ajax({
        url: "{{ route('territory.create') }}",
        type: "GET",
        success: function(response) {
            $('#add-territory').modal('show');
            $('#add-territory .modal-content').html(response);
        },
        error: function() {
            console.log('Error occurred while loading the modal content.');
        }
    });
}
 jq('#territory-table').on('click', '.delete-btn', function(event) {
    event.preventDefault();

    var deleteForm = $(this).closest('.delete-form');
    var url = deleteForm.attr('action');
    var method = deleteForm.attr('data-method');

    $.ajax({
      url: url,
      type: 'DELETE',
      data: deleteForm.serialize(),
      success: function(response) {
        iziToast.success({
          message: response.success,
          position: 'topRight'
        });

       
 window.location.reload(); 
     },
      error: function(xhr, status, error) {
      
        iziToast.error({
          message: 'An error occurred: ' + error,
          position: 'topRight'
        });
      }
    });
  });
 function edit_territory_modal(id){
                jQuery.ajax({
                url: "{{ route('territory.edit', ['id' => ':id']) }}".replace(':id', id),
                type: "get",
            
                success: function(response) {
                    $('#add-territory').modal('show');
                    $('#model-show-data').html(response);
                }
            });
        }
</script>

 

@endsection