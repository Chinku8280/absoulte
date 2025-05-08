@extends('theme.default')
@section('custom_css')
    <style>
        #territory-table th {
            /* text-align: center; */
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
                            Territory
                        </h2>


                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">

                            <a href="{{ route('territory.create') }}" class="btn btn-primary d-none d-sm-inline-block"
                                data-bs-toggle="modal" data-bs-target="#add-territory" onclick="showFormModal()">
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
                            {{-- <div class="card-body border-bottom py-3">
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
                        </div> --}}

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="territory-table"
                                        class="table card-table table-vcenter text-left text-nowrap datatable">
                                        <thead>
                                            <tr>
                                                <th>{{ 'S.no' }}</th>
                                                <th>{{ 'Name' }}</th>
                                                <th>{{ 'Action' }}</th>
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
        <footer class="footer footer-transparent d-print-none">

        </footer>
    </div>

    <div class="modal modal-blur fade" id="add-territory" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 800px;">
            <div class="modal-content" id="add-territory-model">

            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        var jq = jQuery.noConflict();

        jq('#territory-table').DataTable({
            serverSide: true,
            paging: true,
            ajax: '{{ route('territory.showData') }}',
            type: 'GET',
            //serverSide: true,
            columns: [{
                    data: 'serial',
                    title: 'S.No'
                },
                {
                    data: 'territory_name',
                    title: 'Territory Name'
                },
                {
                    data: 'action',
                    title: 'Action'
                }
            ],
            initComplete: function() {

                var table = this.api();
                var rows = table.rows().nodes();
                jq.each(rows, function(index, row) {
                    jq(row).find('td:first-child').text(index + 1);
                });
            }

        });


        function showFormModal() {
            $.ajax({
                url: "{{ route('territory.create') }}",
                type: "GET",
                success: function(response) {
                    var modal = new bootstrap.Modal(document.getElementById('add-territory'), {});
                    $('#add-territory-model').html(response);
                    modal.show();
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
                    console.log(response);

                    if(response.status == "success")
                    {
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

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
                error: function(xhr, status, error) {
                    console.log(status);
                    iziToast.error({
                        message: 'An error occurred: ' + error,
                        position: 'topRight'
                    });
                }
            });
        });

        function edit_territory_modal(id) {
            $.ajax({
                url: "{{ route('territory.edit', ['id' => ':id']) }}".replace(':id', id),
                type: "get",
                success: function(response) {
                    // Set the HTML content of the modal
                    $('#add-territory-model').html(response);

                    // Open the modal
                    var myModal = new bootstrap.Modal(document.getElementById('add-territory'));
                    myModal.show();
                },
                error: function() {
                    console.log('Error occurred while loading the modal content.');
                }
            });
        }
    </script>
@endsection
