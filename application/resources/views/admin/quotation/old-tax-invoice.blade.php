<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>invoice</title>
    <style>
        body {
            font-family: 'Inter Var';
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            font-size: 12px;
        }

        th,
        td {
            border: none;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        p {
            font-size: 12px;
            margin: 0;
        }

        table:first-child span {
            /* color: red; */
        }

        .logo img {
            height: 100px;
        }

        .company-dece {
            padding-left: 15px;
        }

        .p-0 {
            padding: 0;
        }

        .h-100 {
            height: 100%;
        }

        .checkbox-container {
            display: block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
        }

        .checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: transparent;
            border: 1px solid #000;
        }

        .checkbox-container input:checked~.checkmark:after {
            content: "X";
            position: absolute;
            display: block;
            top: 2px;
            left: 6px;
            width: 12px;
            height: 12px;
            font-size: 12px;
            /* color: red; */
            font-weight: bold;
        }

        .fs-10 {
            font-size: 10px;
        }

        .fs-12 {
            font-size: 12px;
        }

        .lh-13 {
            line-height: 1.2;
        }

        .m-0 {
            margin: 0;
        }

        .invoice-table {
            height: 200px;
            border-bottom: 1px solid #000;
        }

        .invoice-table tfoot {
            border-top: 1px solid #000;
        }

        .invoice-table thead th {
            background-color: transparent;
            font-size: 12px;
            line-height: 1.2;
            font-weight: bold !important;
            color: #000;

            border-bottom: 1px solid #000;
        }

        .invoice-table tbody tr td {
            border-bottom: none !important;
            font-size: 10px;
            line-height: 1.2;
            border: none !important;
        }

        .text-center {
            text-align: center;
        }

        #signatureCanvas {
            width: 100%;
            height: 70px;
            margin-bottom: 10px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            margin-bottom: 10px;
        }

        .signature-details {
            text-align: center;
        }

        .name,
        .time {
            display: block;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .footer-logo .img img {
            width: 80px;
            height: 40px;
        }

        ol li {
            font-size: 10px;
        }
    </style>
    <style>
        img {
            max-width: 100%;
            height: auto;
        }

        .invoice-table {
            height: 200px;
            border-bottom: 1px solid #000 !important;
        }

        #signatureCanvas {
            width: 100%;
            height: 70px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <table style="width: 100%;">
        <tr>
            <td style="width: 20%; vertical-align: middle;">
                <div class="logo-container">
                    @if ($company->image_path != '')
                        <img src="{{ public_path($company->image_path) }}" alt="" class="img-fluid">
                    @else
                        <img src="" alt="" class="img-fluid">
                    @endif
                </div>
            </td>
            <td style="width: 80%; vertical-align: middle;">
                <h1 style="font-size: 24px; margin-bottom: 0.5rem;"><b>{{ $company->company_name }}</b></h1>
                <p class="fs-12 lh-13">{{ $company->company_address }}</p>
                <p class="fs-12 lh-13">Tel: +65 {{ $company->telephone }} Fax: +65 {{ $company->fax }} Phone: +65
                    {{ $company->contact_number }}</p>
                <p class="fs-12 lh-13">Website: {{ $company->website }}</p>
                <p class="fs-12 lh-13">Co. Reg No: {{ $company->co_register_no }}
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GST Reg No.
                    {{ $company->gst_register_no }}</p>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width: 33.33%;">
                <b>To:</b><br>
                {{ $customer->individual_company_name }}<br>
                {{ $customer->customer_name }}<br>
                {{ $quotation->service_address_details }}, {{ $quotation->service_address_unit_number }}<br>
                +65 {{ $customer->mobile_number }}<br>
                {{ $customer->email }}<br>
            </td>
            <td style="width: 33.33%;"></td>
            <td style="width: 33.33%;">
                <table>
                    <tr>
                        <td style="text-align: end; font-size: 16px;"><b>TAX INVOICE</b></td>
                    </tr>
                    <tr>
                        <td class="p-0"><b>Issued By:</b></td>
                        <td class="p-0">{{ $quotation->created_by_name }}</td>
                    </tr>
                    <tr>
                        <td class="p-0"><b>Invoice No:</b></td>
                        <td class="p-0">{{ $quotation->invoice_no }}</td>
                    </tr>
                    <tr>
                        <td class="p-0"><b>Issued Date:</b></td>
                        <td class="p-0">{{ date('d-m-Y', strtotime($quotation->created_at)) }}</td>
                    </tr>

                    @if (!empty($quotation->schedule_date))
                        <tr>
                            <td class="p-0"><b>Service Date:</b></td>
                            <td class="p-0">
                                {{ $quotation->schedule_date ? date('d-m-Y', strtotime($quotation->schedule_date)) : '' }}
                            </td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    <table class="invoice-table table" style="border-bottom: 1px solid #000;">
        <thead>
            <tr>
                {{-- <th>PRODUCT CODE</th> --}}
                <th>Sl. No.</th>
                <th>SERVICE</th>
                <th>DESCRIPTION</th>
                <th>QTY</th>
                <th>UNIT PRICE</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quotation_details as $key => $item)
                <tr>
                    {{-- <td>{{ $item->product_code }}</td> --}}
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>
                        @php
                            $description_arr = explode(PHP_EOL, $item->description);
                        @endphp

                        @foreach ($description_arr as $list)
                            {{ $list }} <br>
                        @endforeach
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>
                        <div class="d-flex justify-content-between">
                            $&nbsp;{{ $item->unit_price }}
                        </div>
                    </td>
                    <td>
                        <div class="d-flex justify-content-between">
                            $&nbsp;{{ $item->gross_amount }}
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table>
        <tr>
            <td style="width: 50%;">
                <table>
                    <tr>
                        <td style="width: 40%; text-align: start; font-size: 10px; line-height: 1.2;">Remarks:</td>
                        <td style="width: 60%; text-align: start; font-size: 10px; line-height: 1.2;">
                            {{-- {{$customer->customer_remark}} --}}

                            @php
                                $cust_remark_arr = explode(PHP_EOL, $customer->customer_remark);
                            @endphp

                            @foreach ($cust_remark_arr as $list)
                                {{ $list }} <br>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 40%; text-align: start; font-size: 10px; line-height: 1.2;">Payment Term:</td>
                        <td style="width: 60%; text-align: start; font-size: 10px; line-height: 1.2;">
                            {{ $customer->payment_terms_value }}</td>
                    </tr>
                    <tr>
                        <td style="width: 40%; text-align: start; font-size: 10px; line-height: 1.2;">Bank Detail:</td>
                        <td style="width: 60%; text-align: start; font-size: 10px; line-height: 1.2;">
                            {{ $company ? $company->bank_name : '' }} Current:
                            {{ $company ? $company->ac_number : '' }}<br>
                            Bank Code: {{ $company ? $company->bank_code : '' }} / Branch Code:
                            {{ $company ? $company->branch_code : '' }} <br>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 40%; text-align: start; font-size: 10px; line-height: 1.2;">Payment Method:
                        </td>
                        <td style="width: 60%; text-align: start; font-size: 10px; line-height: 1.2;">
                            {{-- <span>PayNow</span>  --}}
                            <span>Unique Company Number(UEN) No: {{ $company ? $company->uen_no : '' }}</span> <br>
                            All cheques are to be crossed and made payable to <br>
                            {{ $company ? $company->company_name : '' }}
                        </td>
                    </tr>
                </table>
            </td>

            <td style="width: 20%; text-align: center;">
                {{-- <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAclBMVEXMzMzQ0NDT09PLy8sAAADIyMh+fn6Li4u1tbV7e3uCgoLAwMDFxcVFRUVZWVm9vb2ioqJUVFScnJxOTk6VlZU8PDxpaWmxsbEjIyOKioooKChlZWVwcHB1dXVBQUGRkZE2NjYNDQ1JSUkvLy8cHBwTExNimPTCAAAEAElEQVR4nO3YWZuiOBiGYQJSIolhCYsKgi3V//8vTlgLl2uqZ6Zb5+C5j8DWt/NlhXIcAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA/L+I0e3dcu/c3v1yzn8I+t3csg0/wm0w3sl0O8qisVkqO4ZhG3zfstscJ8rGnGMmxyA5BEWvLzHa5UYXVb5v/b4dcZ7vB1U5tCXQnS5016TuP8pxRHoZg5pi7J0yudigg4m/C/rdRFzFQSRlcD6lQ8NObTCI1PDveRNHMiqrQ/z3nX+X44i2S6egoefkxQxByaV89Sj6clgbwjON31eYl6vl49afZX8lvEPyTd/f5jhi2wSrhedVl2AIig7FqwdxJo4bO2oi69Z97G7O452If8SrLePx6i7HEWG+XrveJpzustM3s+GPmSu8rhom0s1cr3c4z5+rdP5QlKl6nmMrTFabijh+Tr+xgxi+q8LwRz9Ls30gfOVNzQl/zlPKT/R8GWlTjleluT5ssmNOX6F0lD8FuXU+/1pe6zdNU0+bvsI2ibNd3ZbDALjV0jCvNsuJGSXJUGKZJI+b/5jjiF0VZ+dzW8ohyCzLWFXFe8ZQxId+D/Q/PvdJUdidvR8cN9kvFYZ7uXw3sCUKYQt8PCanHEcVhzGo6DvBzZcZ4J/1w8x+BSH18B97aZ1GwlWx7ofH3SfPKuxLNEFgnhU45TheW8d2d5Wpqezv3NOyg6o3Vag+mnFtKTW0WpRdayu8Ph3DYaIa82SKfuXYNTgGpV1qgzr97gqzU+vdfCD12bOzdFmHTmjW9bjxZvPs6eQhRwQmtEGX5GuWVu9Yh/EpvG2Y4+90ZHeaw9IwrVcFiWBvzP5xkj7m2AVZSLvTLJNBmjfspSLe1PeN9cNC9efhPDX9S/XVMBE1jVJNczdNn+U4fr3z7XnYqfmnl93Lx9A2LLzvVhFd+4PZ3Wynk1HNV/1N2RglhDLNzTPmsxz73TyzX/I32RQUfGYvrlB423XDxPyU1mXDcXEZzmwhdP61P5Qm6U85IZP56H/MWYKyrn9K869mDPKTxP+j9TySRzs6/sDe+WVpR0f48b7f5G2Pd0VkGyuzTbr0vCymXdTuqIV8nuOoMuiDVNrUQ9eU3dkeHkIef7z6sVSEm0O4G5ztO29UN3WbZefT9HroxLk5Zll1WA2PiuctRgSxep4jgmpvg9q6m14PvfSit1mmT+3L3w/TMPwY7WzLvKA9a52EmZx7uvyodPVx8wL87N3iLsdOhrbWWoepmr7ixzutq/DlL8CrP6eMrRXCk1Hkrl4LHBmpX/jzyl1OHxT9qyAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAC8yV8YEDZOb4QEjAAAAABJRU5ErkJggg=="
                    alt=""> --}}
            </td>

            <td style="width: 30%;">
                <table>
                    <tr>
                        <td style="width: 66.666%; text-align: end; font-size: 10px; line-height: 1.2;">Sub Total:</td>
                        <td style="width: 33.333%; text-align: end; font-size: 10px; line-height: 1.2;">
                            $&nbsp;{{ $quotation->amount }}</td>
                    </tr>
                    <tr>
                        <td style="width: 66.666%; text-align: end; font-size: 10px; line-height: 1.2;">Discount:</td>
                        <td style="width: 33.333%; text-align: end; font-size: 10px; line-height: 1.2;">
                            $&nbsp;{{ $quotation->discount_amt }}</td>
                    </tr>
                    <tr>
                        <td style="width: 66.666%; text-align: end; font-size: 10px; line-height: 1.2;">Total:</td>
                        <td style="width: 33.333%; text-align: end; font-size: 10px; line-height: 1.2;">
                            $&nbsp;{{ $quotation->total }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 66.666%; text-align: end; font-size: 10px; line-height: 1.2;">GST @
                            {{ $quotation->tax_percent }}%:</td>
                        <td style="width: 33.333%; text-align: end; font-size: 10px; line-height: 1.2;">
                            $&nbsp;{{ $quotation->tax }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 66.666%; text-align: end; font-size: 10px; line-height: 1.2;">Grand Total:
                        </td>
                        <td style="width: 33.333%; text-align: end; font-size: 10px; line-height: 1.2;">
                            $&nbsp;{{ $quotation->grand_total }}
                        </td>
                    </tr>
                    {{-- <tr>
                        <td style="width: 66.666%; text-align: end; font-size: 10px; line-height: 1.2;">Deposit:
                        </td>
                        <td style="width: 33.333%; text-align: end; font-size: 10px; line-height: 1.2;">
                            $&nbsp;{{ $quotation->deposit }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 66.666%; text-align: end; font-size: 10px; line-height: 1.2;">Balance:
                        </td>
                        <td style="width: 33.333%; text-align: end; font-size: 10px; line-height: 1.2;">
                            $&nbsp;{{ $quotation->balance }}
                        </td>
                    </tr> --}}
                </table>
            </td>
        </tr>
    </table>

    <div class="terms-and-conditions">
        <!-- Terms and Conditions -->
        <h3 style="font-size: 1rem; line-height: 1.2; margin: 0;"><b>Terms and Conditions</b></h3>
        <ol style="padding-left: 1.25rem; font-size: 10px; line-height: 1.2; margin:0 ; list-style-type: none;">
            @foreach ($term_condition as $item)
                @php
                    $term_arr = explode(PHP_EOL, $item->term_condition);
                @endphp

                @foreach ($term_arr as $list)
                    <li>{{ $list }}</li>
                @endforeach
            @endforeach
        </ol>
    </div>

    {{-- <table>
        <tr>
            <td style="width: 33.333%;">
                <div class="signature-container">
                    <div style="height: 100px;"></div>
                    <canvas id="signatureCanvas"></canvas>
                    <div class="signature-line"></div>
                    <div class="signature-details">
                        <span class="name fs-12 lh-13">Customer's Acknowledgement</span>
                        <span class="time fs-10 lh-13">Designation:</span>
                        <span class="time fs-10 lh-13">Company Stamp:</span>
                    </div>
                </div>
            </td>
            <td style="width: 33.333%;"></td>
            <td style="width: 33.333%;">
                <div class="signature-container">
                    <div style="height: 100px;"></div>
                    <canvas id="signatureCanvas"></canvas>
                    <div class="signature-line"></div>
                    <div class="signature-details">
                        <span class="name fs-12 lh-13">Singapore Carpet Cleaning Pte Ltd</span>
                        <span class="time fs-10 lh-13">Designation : Sales Coordinator</span>
                    </div>
                </div>
            </td>
        </tr>
    </table> --}}

    <table style="width: 100%;">
        <tbody>
            <tr>
                <td style="width: 100%; padding: 0;">
                    <div style="margin-bottom: 0;">
                        <div style="margin-bottom: 0.5rem;">
                            <h3 style="font-size: 1rem; line-height: 1.2; margin: 0;"><b>This is a computer-generated
                                    invoice; therefore, no signature required.</b></h3>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>
