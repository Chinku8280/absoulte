<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

    @if (isset($body))
        <p><?php echo $body; ?></p>
    @else
        <p style="font-weight: bold;">Dear {{$customer->customer_name ?? ''}},</p>

        <p>This is to acknowledge receipt of your payment of ${{number_format($received_payment_amount ?? 0, 2)}} for Invoice # {{$db_quotation->invoice_no}} Balance
        ${{number_format($balance_amount ?? 0, 2)}}. Thank you for your payment.</p>

        <div>
            <div>We are your one-stop home and office solution provider, offering the following services:</<div>
            <div>- Disinfection, Fogging & Sanitization</div>
            <div>- Aircon Installation, Repair & Servicing</div>
            <div>- Carpet Cleaning/Sofa/Mattress/Chair Cleaning & On-The-Spot Curtain Cleaning</div>
        </div>

        <p>Should you need any of these services, please let us know and our team will be delighted to assist you.</p>

        <p>Could you spare a minute to give us 5 stars on our Google & Facebook page? Our team will really appreciate your
        kind words and encouragement!</p>

        <div>
            <div style="font-weight: bold;">Our links for @bsolute Cleaning:</div>
            <div><span style="font-weight: bold;">- @bsolute Cleaning Google</span>: <a href="http://www.absolute.sg/cleaning-google-review">Google Review</a></div>
            <div><span style="font-weight: bold;">- @bsolute Cleaning Facebook</span>: <a href="http://www.absolute.sg/cleaning-facebook-review">Facebook Review</a></div>
        </div>

        <p>Thank you for choosing @bsolute as your partner in House Cleaning Services. We look forward to continuous
        service with you.</p>

        <p>---</p>

        <p style="font-weight: bold;">Warmest Regards,</p>

        <div>
            <div style="font-weight: bold;">Sales Coordinator</div>
            <div><span style="font-weight: bold;">Tel</span>: +65 68448444 (8 Lines)</div>
            <div><span style="font-weight: bold;">HP</span>: 84888444(Whatsapp Only)</div>
            <div><span style="font-weight: bold;">Email</span>: <a href="mailto:support@ehomeservices.com.sg">support@ehomeservices.com.sg</a></div>
            <div><span style="font-weight: bold;">Facebook</span>: <a href="http://www.facebook.com/absoluteservicesingapore/">Absolute Services Singapore</a></div>
            <div><span style="font-weight: bold;">Visit us</span>: <a href="http://absolute.sg">absolute.sg</a></div>
        </div>

        <p>---</p>

        <div>
            <div style="font-weight: bold;">@bsolute Group of Companies (One-Stop Home/Office Services Provider)</div>
            <div>61, Kaki Bukit Avenue 1 #03-05 Shun Li Industrial Park S(417943)</div>
            <div>Operating Hours (Mon-Sun): 8.00am – 8.00pm"</div>
        </div>
    @endif

</body>

</html>
