{{-- <h3>{{$title}}</h3>
<h4>{{$subject}}</h4>
<p>{{$body}}</p> --}}
<link href="{!! asset('public/theme/dist/css/tabler.min.css') !!}" rel="stylesheet" />
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment Link</title>

<style>
    @import url('https://rsms.me/inter/inter.css');

    :root {
        --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }

    body {
        font-feature-settings: "cv03", "cv04", "cv11";
    }

    .sw>.tab-content {
        position: relative;
        overflow: initial !important;
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
</style>

<div class="container">
    <h1>{{ $title }}</h1>
    <p>Hello there,{{ $company_name }}</p>
    <p><?php echo $body; ?></p>
    <a href="{{ $payment_link->getOriginalContent()['payment_link'] }}">Click here to make the payment</a>
    <p>Regards,{{ $company_name }}<br></p>
</div>
