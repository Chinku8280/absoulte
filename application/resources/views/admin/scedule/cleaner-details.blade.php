@extends('theme.default')
@section('content')
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">View Helper Details</h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container mt-3">                            
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="col-md-3">
                            Helper Details
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($cleaner_type == "team")
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">Team Id</label>
                                    <input type="text" class="form-control" value="{{$team->team_id}}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Team Name</label>
                                    <input type="text" class="form-control" value="{{$team->team_name}}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Employee Name</label>
                                    <input type="text" class="form-control" value="{{$team->employee_name}}" readonly>
                                </div>
                            </div>
                        @elseif($cleaner_type == "individual")
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">Employee Id</label>
                                    <input type="text" class="form-control" value="{{$employee->employee_id}}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" value="{{$employee->name}}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Email</label>
                                    <input type="text" class="form-control" value="{{$employee->email}}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Contact No</label>
                                    <input type="text" class="form-control" value="{{$employee->contact_no}}" readonly>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <ul class="nav nav-pills" style="margin-left: 10px; margin-bottom: 10px;">
                    <li class="nav-item" role="presentation" style="margin-right: 5px;">
                        <a href="#upcoming_schedule" class="nav-link active" data-bs-toggle="pill">
                            Upcoming
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#past_schedule" class="nav-link" data-bs-toggle="pill">
                            Past
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane container active" id="upcoming_schedule" role="tabpanel">

                        <div class="card">
                            <div class="card-header">
                                <div class="col-md-3">
                                    Upcoming Schedule Details
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="upcoming_schedule_table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="col">Sales Order Id</th>
                                                <th scope="col">Invoice No</th>
                                                <th scope="col">Customer Name</th>
                                                <th scope="col">Company Name</th>
                                                <th scope="col">Address</th>
                                                <th scope="col">Total Session</th>
                                                <th scope="col">Weekly Freq</th>
                                                <th scope="col">Schedule Date</th>
                                                <th scope="col">Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>                           
                                </div>
                            </div>
                        </div>   

                    </div>

                    <div class="tab-pane container fade" id="past_schedule" role="tabpanel">

                        <div class="card">
                            <div class="card-header">
                                <div class="col-md-3">
                                    Past Schedule Details
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="past_schedule_table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="col">Sales Order Id</th>
                                                <th scope="col">Invoice No</th>
                                                <th scope="col">Customer Name</th>
                                                <th scope="col">Company Name</th>
                                                <th scope="col">Address</th>
                                                <th scope="col">Total Session</th>
                                                <th scope="col">Weekly Freq</th>
                                                <th scope="col">Schedule Date</th>
                                                <th scope="col">Time</th>
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
@endsection

@section('javascript')

<script type="text/javascript">

    $(document).ready(function () {
        
        var upcoming_schedule_table = $('#upcoming_schedule_table').DataTable({
            "lengthChange": false,
            "pageLength": 30,
            ajax: {
                url: "{{ route('schedule.cleaner-upcoming-schedule-get-table-data') }}",
                type: 'GET',
                data: {
                    cleaner_type: "{{$cleaner_type}}",
                    cleaner_id: "{{$cleaner_id}}"
                }
            }
        });

        var past_schedule_table = $('#past_schedule_table').DataTable({
            "lengthChange": false,
            "pageLength": 30,
            ajax: {
                url: "{{ route('schedule.cleaner-past-schedule-get-table-data') }}",
                type: 'GET',
                data: {
                    cleaner_type: "{{$cleaner_type}}",
                    cleaner_id: "{{$cleaner_id}}"
                }
            }
        });

    });
    
</script>

@endsection