<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

    <p>Dear {{$customer->salutation_name ?? ''}} {{$customer->customer_name ?? ''}},</p>

    <p>This is a friendly reminder about your upcoming cleaning service appointment.</p>

    <p>Service Details:</p>

    <div>
        <div>Date: {{$schedule_date ?? ''}} ({{$schedule_day ?? ''}})</div>
        <div>Time: {{$schedule_time ?? ''}}</div>
        <div>Address: {{$service_address ?? ''}}</div>
        <div>Amount Payable: ${{number_format($amount_payable ?? 0, 2)}}</div>
        <div>Payment Method: Asia Pay / {{$payment_method}}</div>
        <div>Customer’s Request:</div>
        <div>
            @php
                $remark_arr = explode(PHP_EOL, $quotation->remarks);
            @endphp

            @foreach ($remark_arr as $list)
                {{ $list }} <br>
            @endforeach
        </div>
    </div>

    <p>Important Notes:</p>

    <p>
        Rust, moulds, stains will be addressed but unable to guarantee 100% removal. <br>
        Owner/agent/tenant must be present to do inspection upon the hours booked. <br>
        Cleaner’s overtime will be incurred and is chargeable due to lateness by owner/agent after a grace period of 15
        minutes. <br>
        We strongly encourage our customers to check the house before letting the cleaner/s leave so any concerns will be
        acted on immediately. <br>
        Allowing our cleaner/s to leave with or without inspection is considered that the job has been completed. Any
        feedback/issues raised after they left may be considered ineffectual. <br>
        The overtime rate is $34.88 per hour per cleaner. <br>
        Thank you for your attention to this matter. We look forward to providing you with excellent service.
    </p>

    <p>
        If you have any questions or need to make any changes to your appointment, please feel free to contact our customer
        support team at 6844-8444, available from 8 am to 7 pm daily.
    </p>

    <p>Best regards,</p>

    <p>{{$company->company_name ?? ''}}</p>

</body>

</html>
