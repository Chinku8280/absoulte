@extends('theme.default')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" /> -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.23/sweetalert2.all.min.js"></script>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://jqueryui.com/resources/demos/style.css">

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>


<div class="page-wrapper">
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center mb-3">
          <div class="col">
              <h2 class="page-title">
                  Schedule
              </h2>


          </div>
          <!-- Page title actions -->
        
      </div>
        <div class="row g-2 align-items-center">
          <div class="col">
            <!-- Page pre-title -->

            <ul class="nav nav-pills nav-pills-primary" data-bs-toggle="tabs" role="tablist">


                @foreach (App\Models\Company::all() as $val)
                    

              <li class="nav-item me-2" role="presentation">
                <a href="#north-zone" class="nav-link " data-bs-toggle="tab" aria-selected="true"
                  role="tab">{{$val->company_name}}</a>
              </li>

              @endforeach


              {{-- <li class="nav-item me-2" role="presentation">
                <a href="#south-zone" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab"
                  tabindex="-1">South Zone</a>
              </li>
              <li class="nav-item me-2" role="presentation">
                <a href="#east-zone" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab"
                  tabindex="-1">East Zone</a>
              </li>
              <li class="nav-item me-2" role="presentation">
                <a href="#west-zone" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab"
                  tabindex="-1">West Zone</a>
              </li>
              <li class="nav-item me-2" role="presentation">
                <a href="#central" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab"
                  tabindex="-1">Central Zone</a>
              </li>
              <li class="nav-item me-2" role="presentation">
                <a href="#city" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab"
                  tabindex="-1">City</a>
              </li> --}}




            </ul>
          </div>


        </div>
      </div>
    </div>

  <div class="page-body">
    <div class="container-xl">
      <div class="row">
        <div class="col-lg-12">
            
          <div id='detail_calendar'></div>


        </div>
      </div>
    </div>
  </div>


  <script>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            events: '/events',
            // Other FullCalendar options...
        });
    });
</script>


  <script>





$('#detail_calendar').fullCalendar({
  header: {
    left: 'today',
    center: 'prev, title, next',
    right: 'month,agendaWeek,agendaDay,listWeek'
  },
  views: {
    listDay: {
      buttonText: 'list day'
    },
    listWeek: {
      buttonText: 'list week'
    }
  },
  events: [{
    title: 'Demo title',
    start: '2023-08-22',
    end: '2023-08-22'
  }],
  // theme: true,
  selectable: true,
  // selectHelper: true,
  // droppable: true,
  // Additional options can be added here
});



















  </script>








@endsection