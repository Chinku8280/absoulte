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
        <p><?php echo $body ?></p>
        <p>Make the Payment of ${{number_format($payment_amount, 2)}} to us</p>
    
        <div class="mb-2" style="display: flex;">
            <div class="logo p-0 text-center" style="margin-right: 10px;">
                @if ($company->image_path != "")
                    <img src="{{ $message->embed(public_path($company->image_path)) }}" alt="" class="img-fluid" style="height: 100px;">
                @else
                    <img src="" alt="" class="img-fluid" style="height: 100px;">
                @endif
            </div>
            <div class="company-dece pe-0 ps-3">
                <h1 class="title" style="font-size: 26px;"><b>{{$company->company_name ?? ''}}</b></h1>
                <p class="m-0 fs-12 lh-13">{{$company->company_address ?? ''}}
                </p>
                <p class="m-0 fs-12 lh-13">Tel: +65 {{$company->telephone ?? ''}} Fax: +65 {{$company->fax ?? ''}} Phone: +65 {{$company->contact_number ?? ''}}</p>
                <p class="m-0 fs-12 lh-13">Website: {{$company->website ?? ''}}</p>
                <p class="m-0 fs-12 lh-13">Co. Reg No: {{$company->co_register_no ?? ''}}
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GST Reg No.
                    {{$company->gst_register_no ?? ''}}</p>
            </div>
        </div>
    
        <br>
    
        <p>{{ $company->company_name }} - Payment mode and details</p>
        <div>
            <table border="1" cellspacing="0" cellpadding="10">
                <tbody>
                    @if (isset($payment_link))
                        <tr>
                            <td>Visa Link</td>
                            <td>
                                {{-- <a href="{{ $payment_link->getOriginalContent()['payment_link'] }}">Click here to make the payment</a> --}}
                                <a href="{{ $payment_link->getOriginalContent()['payment_link'] }}">{{$payment_link->getOriginalContent()['payment_link']}}</a>
                            </td>
                        </tr>
                    @endif
    
                    <tr>
                        <td>Paynow</td>
                        <td style="text-align: center;">
                            @if ($company->qr_code_path != "")
                                <img src="{{ $message->embed(public_path($company->qr_code_path)) }}" alt="image" width="100" height="100">
                            @else
                                <img src="" alt="image" width="100" height="100">
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Unique Company Number (UEN) No</td>
                        <td>{{$company->uen_no}}</td>
                    </tr>
                    <tr>
                        <td>Bank Transfer</td>
                        <td>
                            {{$company->bank_name}}: {{$company->ac_number}} <br>
                            Bank Code: {{$company->bank_code}} <br>
                            Payee name: {{$company->company_name}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    
        <div>
            <span>Please indicate invoice number to</span> <br>
            <span>Email : {{$company->email_id}}</span> <br>
            <span>Whatsapp Only : {{$company->contact_number}}</span>
        </div>
    
        <br>
    
        <p style="font-weight: bold;">{{ $company->company_name }}</p>
    </div>

</body>

</html>
