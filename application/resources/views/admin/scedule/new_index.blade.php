@extends('theme.default')
@section('content')  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
    integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.5.1/main.min.css">
  @vite(['resources/js/main.min.css'])
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <style>
    @import url('https://rsms.me/inter/inter.css');

    :root {
      --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }

    body {
      font-feature-settings: "cv03", "cv04", "cv11";
    }

    html,
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
      font-size: 14px;
    }

    .fc-license-message {
      display: none !important;
    }
  </style>
  <style>
    html,
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
      font-size: 14px;
    }

    #external-events {
      padding: 0 10px;
      border: 1px solid #ccc;
      background: #eee;
    }

    #external-events .fc-event {
      margin: 1em 0;
      cursor: move;
      display: inline-block;
    }

    #calendar-container {
      position: relative;
      z-index: 1;
      /* margin-left: 200px; */
    }

    #calendar {
      /* max-width: 1100px; */
      margin: 20px auto;
    }

    .fc-toolbar-chunk:nth-child(2)>div {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .fc .fc-button-primary {
      border-radius: 50%;
      color: #fff !important;
      border-color: #206bc4 !important;
      background-color: #206bc4 !important;
    }

    .fc-todayButton-button {
      border-radius: 4px !important;
    }

    .fc-customSelect-button {
      background-color: transparent !important;
      border: none !important;
      color: transparent !important;
    }

    #roster-select {
      color: #000000;
    }
    .fc-date-picker-button {
      display: none !important;
    }
  </style>


    <div class="page-wrapper">
      <!-- Page header -->
      <div class="page-header d-print-none">
        <div class="container-xl">
          <div class="row g-2 align-items-center">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                  <!-- <div id='external-events'>
                        <h3 class="mb-0">
                            <strong>Draggable Services</strong>
                        </h3>
                        <div class='fc-event fc-h-event fc-timeline-event'>Floor Cleaning</div>
                        <div class='fc-event fc-h-event fc-timeline-event'>Office Cleaning</div>
                        <div class='fc-event fc-h-event fc-timeline-event'>Home Cleaning</div>
                       
                    </div> -->

                  <div id='calendar-container'>
                    <div id='calendar'></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <!-- Content here -->
      </div>
    </div>
    <footer class="footer footer-transparent d-print-none">
      <div class="container-xl">
        <div class="row text-center align-items-center flex-row-reverse">

          <div class="col-12 mt-3 mt-lg-0">
            <ul class="list-inline list-inline-dots mb-0">
              <li class="list-inline-item">
                Copyright &copy; 2023
                <a href="#" class="link-secondary">Absolute</a>.
                All rights reserved.
              </li>

            </ul>
          </div>
        </div>
      </div>
    </footer>
  </div>
  </div>
  <!-- Libs JS -->
  <!-- Tabler Core -->
 <script>
    document.addEventListener('DOMContentLoaded', function () {
      var Calendar = FullCalendar.Calendar;
      var Draggable = FullCalendar.Draggable;
  
      var containerEl = document.getElementById('external-events');
      var calendarEl = document.getElementById('calendar');
      var checkbox = document.getElementById('drop-remove');
  
      var calendar = new Calendar(calendarEl, {
        timeZone: 'UTC',
        initialView: 'resourceTimelineDay',
        aspectRatio: 1.5,
        headerToolbar: {
          left: 'date-picker',
          center: 'prev,title,next',
          right: 'customRefreshButton customSelect todayButton'
        },
        resourceAreaHeaderContent: 'Team Members',
        resources: [
          { id: '1', title: 'John Doe' },
          { id: '2', title: 'Jane Smith' },
          { id: '3', title: 'Alice Johnson' },
          { id: '4', title: 'Bob Brown' },
          { id: '5', title: 'Eva White' },
          { id: '6', title: 'David Lee' },
          { id: '7', title: 'Grace Davis' },
          { id: '8', title: 'Michael Jackson' },
          { id: '9', title: 'Sophia Wilson' },
          { id: '10', title: 'William Adams' },
        ],
        customButtons: {
          customRefreshButton: {
            text: '',
            click: function () {
              calendar.refetchEvents();
            }
          },
          customSelect: {
            text: '',
            click: function () {
              var selectedOption = document.getElementById('roster-select').value;
              console.log('Selected Option: ' + selectedOption);
            }
          }
        },
        editable: true,
        droppable: true,
        drop: function (info) {
          if (checkbox.checked) {
            info.draggedEl.parentNode.removeChild(info.draggedEl);
          }
        },
        eventRender: function (info) {
          var title = info.event.title.replace(/\n/g, '<br>');
          info.el.querySelector('.fc-title').innerHTML = title;
        },
      });
  
      var events = [
        {
          title: 'Meeting with Client',
          start: '2023-10-11T10:45:00',
          end: '2023-10-11T14:59:00',
          resourceId: '1',
          eventColor: 'blue'
        },
        {
          title: 'Team Meeting',
          start: '2023-10-11T14:00:00',
          resourceId: '2',
        },
        {
          title: 'Project Kick-off',
          start: '2023-10-11T09:00:00',
          end: '2023-10-11T10:30:00',
          resourceId: '3',
        },
        {
          title: 'Training Session',
          start: '2023-10-11T11:00:00',
          end: '2023-10-11T12:30:00',
          resourceId: '4',
        },
        // Add more events as needed
      ];
  
      calendar.addEventSource(events);
  
      calendar.render();
  
      var refreshButton = document.querySelector('.fc-customRefreshButton-button');
      if (refreshButton) {
        refreshButton.innerHTML = '<i class="fas fa-rotate-right"></i>';
      }
  
      var todayButton = document.querySelector('.fc-todayButton-button');
      if (todayButton) {
        todayButton.innerHTML = '<i class="far fa-calendar"></i> Today';
      }
  
      var selectInput = document.createElement('select');
      selectInput.id = 'roster-select';
      selectInput.classList.add('form-select');
  
      var options = ['Option 1', 'Option 2', 'Option 3'];
      for (var i = 0; i < options.length; i++) {
        var option = document.createElement('option');
        option.value = options[i];
        option.text = options[i];
        selectInput.appendChild(option);
      }
  
      var customSelectButton = document.querySelector('.fc-customSelect-button');
      if (customSelectButton) {
        customSelectButton.appendChild(selectInput);
        customSelectButton.classList.remove('fc-button', 'fc-button-primary');
      }
  
      var firstToolbarChunk = document.querySelector('.fc-toolbar .fc-toolbar-chunk');
  
      var datePickerInput = document.createElement('input');
      datePickerInput.type = 'date';
      datePickerInput.classList.add('form-control');
      datePickerInput.id = 'date-picker-input';
  
      firstToolbarChunk.appendChild(datePickerInput);
  
      datePickerInput.addEventListener('change', function () {
        var selectedDate = new Date(datePickerInput.value);
        calendar.gotoDate(selectedDate);
      });
    });
</script>

@endsection
