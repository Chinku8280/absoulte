  @extends('theme.default')
  @section('content')
      <div class="page-wrapper">
          <!-- Page header -->
          <div class="page-header d-print-none">
              <div class="container-xl">
                  <div class="row g-2 align-items-center">
                      <div class="col">
                          <h2 class="page-title">
                              Terms & Condition
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
                          <div class="card">
                              {{-- <div class="card-header">
                              </div> --}}
                              <div class="card-body">
                                  <div class="tab-content">
                                      <div class="tab-pane active show" id="" role="tabpanel">
                                          <div class="row g-2 align-items-center w-100">
                                              <div class="col-auto ms-auto d-print-none mb-3">

                                                  <a href="#" class="btn btn-primary m-0"
                                                      data-bs-toggle="modal" data-bs-target="#add-term"
                                                      onclick="showFormModal()">
                                                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                          height="24" viewBox="0 0 24 24" stroke-width="2"
                                                          stroke="currentColor" fill="none" stroke-linecap="round"
                                                          stroke-linejoin="round">
                                                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                          <path d="M12 5l0 14"></path>
                                                          <path d="M5 12l14 0"></path>
                                                      </svg>
                                                      Add New Terms & Condition
                                                  </a>
                                              </div>
                                          </div>
                                          <div class="card">


                                              <div class="table-responsive">
                                                  <table id="term-table"
                                                      class="table card-table table-vcenter text-center text-nowrap datatable">
                                                      <thead>
                                                          <tr>
                                                              <th class="w-1">No.</th>
                                                              <th>Terms & Condition</th>
                                                              <th>Action</th>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                        @foreach($terms as $key => $term)
                                                          <tr>
                                                              <td class="w-1">{{$key+1}}</td>
                                                              <td>{{$term->term_condition}}</td>
                                                              <td>
                                                                <a href="#" class="btn btn-success" onclick="showFormModal()"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                                <a href="{{route('term.condition.delete',$term->id)}}" onclick="alert('Are You Sure')" class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i></a>
                                                              </td>
                                                          </tr>
                                                        @endforeach
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

      </div>
<!-- MODEL -->
<div class="modal fade" id="add-term" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{route('term.condition.store')}}" method="post">
            @csrf
            <div class="mb-3">
                <label for="company_id" class="form-label">Company Name</label>
                <select name="company_id" id="" class="form-control">
                    <option value="">Select Company Name</option>
                    @foreach ($company as $item)
                        <option value="{{$item->id}}">{{$item->company_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="term_condition" class="form-label">Term & Condition</label>
                <input type="text" name="term_condition" class="form-control">
            </div>
            {{-- <input type="hidden" name="id" value="{{$data->id}}"> --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

<script>
    function showFormModal(){
        $('#add-term').modal('show');
    }
</script>
  @endsection
