<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- <link href="{!! asset('public/theme/dist/css/tabler.min.css') !!}" rel="stylesheet" />
    <link href="{!! asset('public/theme/dist/css/tabler-flags.min.css') !!}" rel="stylesheet" />
    <link href="{!! asset('public/theme/dist/css/tabler-payments.min.css') !!}" rel="stylesheet" />
    <link href="{!! asset('public/theme/dist/css/tabler-vendors.min.css') !!}" rel="stylesheet" />
    <link href="{!! asset('public/theme/dist/css/demo.min.css') !!}" rel="stylesheet" />
    <link href="{!! asset('public/theme/dist/css/hierarchical-checkbox.css') !!}" rel="stylesheet" />
    <link href="{!! asset('public/datatables/css/jquery.dataTables.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('public/datatables/css/buttons.dataTables.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('public/datatables/css/dataTables.bootstrap.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('public/datatables/css/responsive.dataTables.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('public/datatables/css/rowReorder.dataTables.min.css') !!}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/css/smart_wizard_all.min.css" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css"> --}}

    <style>
        @import url('https://rsms.me/inter/inter.css');
    
        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }
    
        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>

<body>

    <div class="container">
        <div>
            <?php echo $body; ?>
        </div>
    
        {{-- <div>
            <a href="{{url('/leads/confirm-mail', $lead_id)}}" class="btn btn-info">Confirm</a>
            <a href="{{url('/leads/reject-mail', $lead_id)}}" class="btn btn-danger">Cancel</a>
        </div> --}}

        <div style="margin-bottom: 50px; margin-top: 50px;">
            <a href="{{url('/quotation/confirm-mail', $quotation_id)}}"
            style="margin-right: 50px; 
            font-weight: bold; 
            color: #fff; 
            background-color: #5cb85c; 
            border-color: #4cae4c;
            padding: .375rem .75rem;
            border-radius: .25rem;
            text-decoration: none;">Confirm</a>

            <a href="{{url('/quotation/reject-mail', $quotation_id)}}"
            style="font-weight: bold; 
            color: #fff; 
            background-color: #d9534f; 
            border-color: #d43f3a;
            padding: .375rem .75rem;
            border-radius: .25rem;
            text-decoration: none;">Cancel</a>
        </div>
    </div>

</body>

</html>
