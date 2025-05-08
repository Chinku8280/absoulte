<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            /* margin: 2%; */
            font-size: 10px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .no-mp {
            margin: 0;
            padding-top: 1px;
            padding-bottom: 1px;
        }


        table {
            width: 100%;
        }

        table td {
            border: none;
        }

        .plr {
            padding-right: 7px;
            white-space: nowrap;
        }

        .footer-t {
            position: fixed;
            bottom: 5%;
            width: 100%;
            /* margin-left: 5%;
            margin-right: 5%; */
            height: auto;
            visibility: visible;
        }

        .img-dt{
            width: 11%;
        }

    </style>
</head>

<body>
    <table>
        <tbody>
            <tr>
                <td style="width: 18%;">
                    @if ($company->image_path != "")
                        <img src="{{ public_path($company->image_path) }}" alt="" style="width: 110px; height: 110px;">
                    @else
                        <img src="" alt="" style="width: 110px;height: 110px;">
                    @endif
                </td>
                <td style="text-align: left; margin-left: 0; padding-left: 0;">
                    <h2 style="margin-bottom: 7px; margin-top: 0; font-size: 20px;">{{$company->company_name}}</h2>
                    <p class="no-mp" style="font-size: 12px;">{{$company->company_address}}</p>
                    <p class="no-mp" style="font-size: 12px;">
                        Tel: +65 {{$company->telephone}} 
                        @if(!empty($company->fax))
                            <span style="margin-left: 10px;">Fax: +65 {{$company->fax}}</span> 
                        @endif                        
                        <span style="margin-left: 10px;">Phone: +65 {{$company->contact_number}}</span></p>
                    <p class="no-mp" style="font-size: 12px;">Website: {{$company->website}}</p>
                    <p class="no-mp" style="font-size: 12px;">Co. Reg No: {{$company->co_register_no}} <span
                            style="margin-left: 10px;">GST Reg No.
                            {{$company->gst_register_no}}</span></p>
                </td>
            </tr>
        </tbody>
    </table>

    <table>
        <tbody>
            <tr>
                <td style="width: 35%;">
                    <table>
                        <tbody>
                            <tr>
                                <td style="font-weight: bold;">To:</td>
                                <td>
                                    <p style="margin-left: 10px;">{{ $customer->individual_company_name }}<br/>
                                        {{ $customer->customer_name }}<br />
                                        {{ $quotation->service_address_details }}, {{ $quotation->service_address_unit_number }}<br />
                                        +65 {{ $customer->mobile_number }}<br>
                                        {{ $customer->email }}<br>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width: 25%;"></td>
                <td style="width: 40%;">
                    <table>
                        <thead>
                            <tr>
                                <td colspan="2"
                                    style="text-align: center;font-weight: bold; font-size: 12px; transform: translateX(25px);">
                                    TAX INVOICE</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: right; font-weight: bold;">
                                    <p class="no-mp">Issued By:</p>
                                    <p class="no-mp">Invoice No:</p>
                                    <p class="no-mp">Issued Date:</p>

                                    @if (!empty($quotation->schedule_date))
                                        <p class="no-mp">Service Date:</p>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <p class="no-mp">{{ $quotation->created_by_name }}</p>
                                    <p class="no-mp">{{ $quotation->invoice_no }}</p>
                                    <p class="no-mp">{{ date('d-m-Y', strtotime($quotation->created_at)) }}</p>

                                    @if (!empty($quotation->schedule_date))
                                        <p class="no-mp">{{ ($quotation->schedule_date)?date('d-m-Y', strtotime($quotation->schedule_date)):'' }}</p>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <table style="border-collapse: collapse; margin-top: 15px;">
        <thead style="border-bottom: 2px solid #000;">
            <tr>
                <th class="plr" style="padding-bottom: 3px;text-align: left;">SL NO.</td>
                <th class="plr" style="padding-bottom: 3px;text-align: left;">SERVICE</td>
                <th class="plr" style="padding-bottom: 3px;text-align: left;">DESCRIPTION</td>
                <th class="plr" style="padding-bottom: 3px;text-align: left;">QTY</td>
                <th class="plr" style="padding-bottom: 3px;text-align: left;">UNIT PRICE</td>
                <th class="plr" style="padding-bottom: 3px;text-align: left;">TOTAL</td>
            </tr>
        </thead>
        <tbody style=" vertical-align: top;border-bottom: 2px solid #000;">
            @foreach ($quotation_details as $key => $item)
                <tr>
                    <td class="plr">
                        <p>{{ $key+1 }}</p>
                    </td>
                    <td>
                        <p>{{ $item->name }}</p>
                    </td>
                    <td>
                        <p>
                            @if ($key == 0)          
                                @if (!empty($item->description))
                                    @php
                                        $description_arr = explode(PHP_EOL, $item->description)
                                    @endphp

                                    @foreach ($description_arr as $list)
                                        {{$list}} <br>
                                    @endforeach  
                                @endif

                                @if ($schedule)
                                    Time: {{$schedule->cleaning_time ?? ''}}</br>
                                    
                                    @if ($schedule->cleaner_type == "individual")
                                        No of Cleaners: {{$schedule->no_of_cleaners ?? ''}}</br>
                                    @endif
                                    
                                    Every: {{$schedule->new_selected_days ?? ''}}</br>
                                    Date: {{$schedule->new_cleaning_dates ?? ''}}</br>
                                    Cleaners: {{$schedule->cleaner_name ?? ''}}</br>
                                @endif
                            @endif
                        </p>
                    </td>
                    <td>
                        <p>{{ $item->quantity }}</p>
                    </td>
                    <td>
                        <p>
                            $&nbsp;{{ number_format($item->unit_price, 2) }}
                        </p>
                    </td>
                    <td>
                        <p>
                            $&nbsp;{{ number_format($item->gross_amount, 2) }}
                        </p>
                    </td>
                </tr>
            @endforeach            
        </tbody>
    </table>

    <table style="margin-top: 10px;">
        <tbody>
            <tr>
                <td style="width: 57%;">
                    <table>
                        <tbody style="vertical-align: top;">
                            <tr>
                                <td style="width: 30%; font-weight: bold;">Remarks :</td>
                                <td>
                                    {{-- @php
                                        $cust_remark_arr = explode(PHP_EOL, $customer->customer_remark)                                                  
                                    @endphp

                                    @foreach ($cust_remark_arr as $list)
                                        {{$list}} <br>
                                    @endforeach --}}

                                    @php
                                        $remark_arr = explode(PHP_EOL, $quotation->remarks);
                                    @endphp

                                    @foreach ($remark_arr as $list)
                                        {{ $list }} <br>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Payment term:</td>
                                <td>{{$customer->payment_terms_value}}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Bank Detail:</td>
                                <td>
                                    {{ $company ? $company->bank_name : '' }} Current:
                                    {{ $company ? $company->ac_number : '' }}<br>
                                    Bank Code: {{ $company ? $company->bank_code : '' }} / Branch Code:
                                    {{ $company ? $company->branch_code : '' }} <br>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Payment Method:</td>
                                <td>
                                    <span style="text-decoration: underline;">"PayNow Unique Company Number (UEN) No:
                                    {{ $company ? $company->uen_no : '' }}</span><br />
                                    All cheques are to be crossed and made payable to<br />
                                    <span style="text-decoration: underline;font-weight: bold;">{{ $company ? $company->company_name : '' }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width: 43%; vertical-align: top;">
                    <table>
                        <tbody>
                            <tr style="vertical-align: top;">
                                <td style="width: 30%; text-align: right;">
                                    @if ($company->qr_code_path != "")
                                        <img src="{{ public_path($company->qr_code_path) }}" alt="" style="width: 70px; height: 70px;">
                                    @else
                                        <img src="" alt="" style="width: 70px; height: 70px;">
                                    @endif

                                    {{-- <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAclBMVEXMzMzQ0NDT09PLy8sAAADIyMh+fn6Li4u1tbV7e3uCgoLAwMDFxcVFRUVZWVm9vb2ioqJUVFScnJxOTk6VlZU8PDxpaWmxsbEjIyOKioooKChlZWVwcHB1dXVBQUGRkZE2NjYNDQ1JSUkvLy8cHBwTExNimPTCAAAEAElEQVR4nO3YWZuiOBiGYQJSIolhCYsKgi3V//8vTlgLl2uqZ6Zb5+C5j8DWt/NlhXIcAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA/L+I0e3dcu/c3v1yzn8I+t3csg0/wm0w3sl0O8qisVkqO4ZhG3zfstscJ8rGnGMmxyA5BEWvLzHa5UYXVb5v/b4dcZ7vB1U5tCXQnS5016TuP8pxRHoZg5pi7J0yudigg4m/C/rdRFzFQSRlcD6lQ8NObTCI1PDveRNHMiqrQ/z3nX+X44i2S6egoefkxQxByaV89Sj6clgbwjON31eYl6vl49afZX8lvEPyTd/f5jhi2wSrhedVl2AIig7FqwdxJo4bO2oi69Z97G7O452If8SrLePx6i7HEWG+XrveJpzustM3s+GPmSu8rhom0s1cr3c4z5+rdP5QlKl6nmMrTFabijh+Tr+xgxi+q8LwRz9Ls30gfOVNzQl/zlPKT/R8GWlTjleluT5ssmNOX6F0lD8FuXU+/1pe6zdNU0+bvsI2ibNd3ZbDALjV0jCvNsuJGSXJUGKZJI+b/5jjiF0VZ+dzW8ohyCzLWFXFe8ZQxId+D/Q/PvdJUdidvR8cN9kvFYZ7uXw3sCUKYQt8PCanHEcVhzGo6DvBzZcZ4J/1w8x+BSH18B97aZ1GwlWx7ofH3SfPKuxLNEFgnhU45TheW8d2d5Wpqezv3NOyg6o3Vag+mnFtKTW0WpRdayu8Ph3DYaIa82SKfuXYNTgGpV1qgzr97gqzU+vdfCD12bOzdFmHTmjW9bjxZvPs6eQhRwQmtEGX5GuWVu9Yh/EpvG2Y4+90ZHeaw9IwrVcFiWBvzP5xkj7m2AVZSLvTLJNBmjfspSLe1PeN9cNC9efhPDX9S/XVMBE1jVJNczdNn+U4fr3z7XnYqfmnl93Lx9A2LLzvVhFd+4PZ3Wynk1HNV/1N2RglhDLNzTPmsxz73TyzX/I32RQUfGYvrlB423XDxPyU1mXDcXEZzmwhdP61P5Qm6U85IZP56H/MWYKyrn9K869mDPKTxP+j9TySRzs6/sDe+WVpR0f48b7f5G2Pd0VkGyuzTbr0vCymXdTuqIV8nuOoMuiDVNrUQ9eU3dkeHkIef7z6sVSEm0O4G5ztO29UN3WbZefT9HroxLk5Zll1WA2PiuctRgSxep4jgmpvg9q6m14PvfSit1mmT+3L3w/TMPwY7WzLvKA9a52EmZx7uvyodPVx8wL87N3iLsdOhrbWWoepmr7ixzutq/DlL8CrP6eMrRXCk1Hkrl4LHBmpX/jzyl1OHxT9qyAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAC8yV8YEDZOb4QEjAAAAABJRU5ErkJggg==" alt="" style="width: 70px; height: 70px;"> --}}
                                </td>
                                <td style="width: 70%;">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td style="text-align: right; padding-right: 10px;">Sub Total:</td>
                                                <td>$</td>
                                                <td style="text-align: right;">{{ number_format($quotation->amount, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: right; padding-right: 10px;">Discount:</td>
                                                <td>$</td>
                                                <td style="text-align: right;">{{ number_format($quotation->discount_amt, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: right; padding-right: 10px;">Total:</td>
                                                <td>$</td>
                                                <td style="text-align: right;">{{ number_format($quotation->total, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: right; padding-right: 10px;">GST @ {{ $quotation->tax_percent }}%:</td>
                                                <td>$</td>
                                                <td style="text-align: right;">{{ number_format($quotation->tax, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: right; padding-right: 10px;">Grand Total:</td>
                                                <td>$</td>
                                                <td style="text-align: right;">{{ number_format($quotation->grand_total, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: right; padding-right: 10px;">Deposit:</td>
                                                <td>$</td>
                                                <td style="text-align: right;">{{ number_format($quotation->deposit, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: right; padding-right: 10px;">Balance:</td>
                                                <td>$</td>
                                                <td style="text-align: right;">{{ number_format($quotation->balance, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <table style="margin-top: 20px;">
        <thead>
            <tr>
                <td style="font-size: 12px; font-weight: bold;">Terms and Conditions</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <p>
                        @foreach ($term_condition as $item)
                            @php
                                $term_arr = explode(PHP_EOL, $item->term_condition)
                            @endphp
            
                            @foreach ($term_arr as $list)
                                {{$list}} <br>
                            @endforeach
                        @endforeach
                    </p>
                </td>
            </tr>
        </tbody>
    </table>

    <table>
        <tbody>
            <tr>
                <td style="font-weight: bold; font-size: 12px;">This is a computer generated invoice therefore no
                    signature required.</td>
            </tr>
        </tbody>
    </table>

    @if (!$company->company_invoice_footer_logo->isEmpty())           
        <table class="footer-t">
            <tbody>
                <tr>                                  
                    @foreach ($company->company_invoice_footer_logo as $item) 
                        <td class="img-dt">      
                            <img src="{{ public_path($item->invoice_footer_logo_path) }}" alt="" style="width:100%;"/>
                        </td>    
                    @endforeach                          
                </tr>
            </tbody>
        </table>
    @endif
</body>

</html>
