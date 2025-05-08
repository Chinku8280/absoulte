<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
 <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Leads</title>
  <!-- CSS files -->
  <link href="{{asset('public/theme/dist/css/tabler.min.css')}}" rel="stylesheet" />
  <link href="{{asset('public/theme/dist/css/tabler-vendors.min.css')}}" rel="stylesheet" />
  <link href="{{asset('public/theme/dist/css/demo.min.css')}}" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
    integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/css/smart_wizard_all.min.css" rel="stylesheet"
    type="text/css" />
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
  <style>
    @import url('https://rsms.me/inter/inter.css');

    :root {
      --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      --sw-progress-color: #206bc4;
      --sw-anchor-active-primary-color: #206bc4;
      --sw-anchor-done-primary-color: #64abff;
    }

    body {
      font-feature-settings: "cv03", "cv04", "cv11";
    }
    .nav-link {
      font-size: 12px;
    }
    .dropdown {
            position: relative;
            font-size: 14px;
            color: #182433;
        }

        .dropdown .dropdown-list {
            padding: 0;
            background: #ffffff;
            position: absolute;
            top: 30px;
            left: 2px;
            right: 2px;
            z-index: 1000;
            border: 1px solid rgba(4, 32, 69, 0.14);
            border-radius: 4px;
            box-shadow: 0px 16px 24px 2px rgba(0, 0, 0, 0.07), 0px 6px 30px 5px rgba(0, 0, 0, 0.06), 0px 8px 10px -5px rgba(0, 0, 0, 0.1);
            transform-origin: 50% 0;
            transform: scale(1, 0);
            transition: transform 0.15s ease-in-out 0.15s;
            max-height: 66vh;
            overflow-y: scroll;
        }

        .dropdown .dropdown-option {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            opacity: 0;
            transition: opacity 0.15s ease-in-out;
        }

        .dropdown .dropdown-label {
            display: block;
            background: #fff;
            font-family: var(--tblr-font-sans-serif);
            font-size: .875rem;
            font-weight: 400;
            line-height: 1.4285714286 !important;
            padding: 0.4375rem 0.75rem !important;

            cursor: pointer;
            border: 1px solid #dadfe5;
            border-radius: 4px;
        }

        .dropdown .dropdown-label:before {
            font-family: "FontAwesome";
            content: "\f078";
            color: #a5a9b1;
            float: right;
        }

        .dropdown.on .dropdown-list {
            transform: scale(1, 1);
            transition-delay: 0s;
        }

        .dropdown.on .dropdown-list .dropdown-option {
            opacity: 1;
            transition-delay: 0.2s;
            color: #182433;
        }

        .dropdown.on .dropdown-label:before {
            font-family: "FontAwesome";
            content: "\f077";
            color: #a5a9b1;
        }

        .dropdown [type="checkbox"] {
            position: relative;
            top: -1px;
            margin-right: 4px;
        }
    .sw>.tab-content {
    height: auto !important;
}
.ui-datepicker-header{
  background-color: #2196f3;
  color:white;
  text-align:center;
  font-family:'Roboto';
  padding:10px;
  height:40px;
  border-radius:8px 8px 0px 0px;
}

.ui-datepicker-prev span, 
.ui-datepicker-next span{
  display:none;
}

.ui-datepicker-prev:after{
  content:"<";
  float:left;
  margin-left:10px;
  cursor:pointer;
   color: #fff;
}


.ui-datepicker-next:after{
  content:">";
  float:right;
  margin-right:10px;
  cursor:pointer;
  color: #fff;
}
.ui-datepicker-calendar th{
  padding:10px;
  color:#2196f3;
}

.ui-datepicker-calendar {
  width: 100%;
    text-align:center;
    margin:0 auto;
    padding:8px;
}


.ui-datepicker-calendar td {
  padding:4px 0px;
}

.ui-datepicker-calendar .ui-state-default {
  text-decoration:none;
  color:black;
}

.ui-datepicker-calendar .ui-state-active {
    color: #2196f3;
}
  </style>
</head>

<body>
  <script src="{{('theme/dist/js/demo-theme.min.js')}}"></script>
  <div class="page">
    <!-- Navbar -->
        @include('leadTheme.mainHeader')
        @include('leadTheme.header')
   
    <div class="page-wrapper">
      <!-- Page header -->
     @yield('content')

        @include('leadTheme.footer')
  
   
     <!-- FOOTER -->
    </div>
  </div>

 
  
  

  <!-- Tabler Core -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>

  <script src="{{('public/theme/dist/js/smart-wizaed.js')}}" type="text/javascript"></script>

  
</script>
  <script>
    $('#smartwizard').smartWizard({
      transition: {
        animation: 'slideHorizontal', // Effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
      }
    });
    $('#smartwizard2').smartWizard({
      transition: {
        animation: 'slideHorizontal', // Effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
      }
    });

  </script>
 


  <script src="{{asset('public/theme/dist/js/tabler.min.js')}}" defer></script>
  <script src="{{asset('public/theme/dist/js/demo.min.js')}}" defer></script>
</body>

</html>