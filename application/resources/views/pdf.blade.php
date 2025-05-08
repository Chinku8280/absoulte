<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('public/css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    
{{-- <style type="text/css" media="print">
    @import url('https://rsms.me/inter/inter.css');

    :root {
        --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }

    body {
        font-feature-settings: "cv03", "cv04", "cv11";
        color: #000;
        margin: 0;
        padding: 0;
    }

    .invoice-box .title {
        font-size: 2rem;
        color: #000;
        font-weight: bolder;
    }

    .footer-logo .img img {
        width: 80px;
    }

    .card-lg>.card-body {
        padding: 1.5rem;
        margin: 0;

    }

    #invoice-card {
        margin: 0 !important;
        position: relative;
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

    .invoice-table {
        height: 300px;
        border-bottom: 1px solid #000;
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
    }

    .terms-row {
        font-size: 10px;
        line-height: 1.2;
    }

    .custom-list {
        list-style-type: none;
        counter-reset: custom-counter;
        padding: 0;
    }

    .custom-list li {
        counter-increment: custom-counter;
    }

    .custom-list li:before {
        content: counter(custom-counter, decimal-leading-zero) ") ";
        margin-right: 5px;
    }
    
</style> --}}
<style>
    body {
        font-family: 'Arial', sans-serif;
    }
</style>
</head>

<body>
    <div class="page-body">
        <div class="container-xl">
            <div class="card card-lg" id="invoice-card">
    
                <div class="card-body">
                    <div class="d-flex mb-2">
                        <div class="logo p-0 text-center">
                            <img src="{{asset('public/company_logos/'.$company->company_logo)}}" style="height: 100px; width:100px; background:black" alt="Company Logo" class="img-fluid">
                        </div>
                        <div class="company-dece pe-0 ps-3">
                            <h1 class="title" style="font-size: 26px;"><b>{{$company['company_name']}}</b></h1>
                            <p class="m-0 fs-12 lh-13">{{$company['company_address']}}
                            </p>
                            <p class="m-0 fs-12 lh-13">Tel:  Fax:  Phone: </p>
                                <p class="m-0 fs-12 lh-13">Website: </p>
                                <p class="m-0 fs-12 lh-13">Co. Reg No: 201317775M 
                            </p>
                        </div>
                    </div>
    
                    <div class="row">
                        <div class="col-8"></div>
                        <div class="col-4 text-end">
                            <h3>QUOTATION</h3>
                        </div>
                    </div>
                    <div class="row mb-3">
    
                        <div class="col-1 col-sm-1 col-md-6">
                            <h4 class="mb-3 fs-12 lh-13"><b>To:</b></h4>
                        </div>
                        <div class="col-4 col-sm-4 ps-0">
                            <div class="fs-12 lh-13">Schaeffler (Singapore) Pte Ltd
                            </div>
                            <div class="fs-12 lh-13">Ms Oliveiro Majella
                            </div>
                            <div class="fs-12 lh-13">18 Tai Seng Street #09-07/083</div>
                            <div class="fs-12 lh-13">18 Tai Seng Singapore 539775</div>
                            <div class="fs-12 lh-13">+6565408649</div>
                            <div class="fs-12 lh-13">OLIVEMJE@schaeffler.com</div>
                        </div>
    
                        <div class="col-7 col-sm-7 col-md-6">
                            <div class="row">
                                <div class="col-8 col-md-8 text-end">
                                    <div class="fs-12 lh-13"><b>Quotation No:</b></div>
                                </div>
                                <div class="col-4 col-md-4 text-end">
                                    <div class="fs-12 lh-13">HCQ-23-000007
    
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-8 col-md-8 text-end">
                                    <div class="fs-12 lh-13"><b>Issued Date:</b></div>
                                </div>
                                <div class="col-4 col-md-4 text-end">
                                    <div class="fs-12 lh-13">07 Feb 2023</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8 col-md-8 text-end">
                                    <div class="fs-12 lh-13"><b>Expiry Date</b></div>
                                </div>
                                <div class="col-4 col-md-4 text-end">
                                    <div class="fs-12 lh-13">21 Feb 2023
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8 col-md-8 text-end">
                                    <div class="fs-12 lh-13"><b>Issue By:
                                        </b></div>
                                </div>
                                <div class="col-4 col-md-4 text-end">
                                    <div class="fs-12 lh-13">Lubie
                                    </div>
                                </div>
                            </div>
                        </div>
    
                    </div>
    
                    <div class="table-responsive-sm">
                        <table class="invoice-table table">
                            <thead>
                                <tr>
    
                                    <th>PRODUCT</th>
                                    <th>QTY</th>
                                    <th>UNIT PRICE</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($service as $item)
                                    <tr>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td>{{$item->unit_price}}</td>
                                        <td>{{$item->quantity * $item->unit_price}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row mb-2">
                        <div class="col-7 col-sm-7">
                            <div class="row mb-2">
                                <div class="col-3 col-md-3 text-start">
                                    <div class="fs-12 lh-13"><b>Remarks:</b></div>
                                </div>
                                <div class="col-8 col-md-8 text-start">
                                    <div></div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-3 col-md-3 text-start">
                                    <div class="fs-10 lh-13"><b>Payment Term:</b></div>
                                </div>
                                <div class="col-9 col-md-9 text-start">
                                    <div class="fs-10 lh-13">C.O.D</div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-3 col-md-3 text-start">
                                    <div class="fs-10 lh-13"><b>Bank Detail:</b></div>
                                </div>
                                <div class="col-9 col-md-9 text-start">
                                    <div class="fs-10 lh-13">DBS Current: 017-904549-5</div>
                                    <div class="fs-10 lh-13">Bank Code: 7171 / Branch Code: 017</div>
                                    <div class="fs-10 lh-13">OCBC Current: 641-331392-001
                                    </div>
                                    <div class="fs-10 lh-13">Bank Code: 7339 / Branch Code: 641</div>
                                </div>
                            </div>
                           
                            <div class="row mb-2">
                                <div class="col-3 col-md-3 text-start">
                                    <div class="fs-10 lh-13"><b>Payment Method:</b></div>
                                </div>
                                <div class="col-9 col-md-9 text-start">
                                    <div class="fs-10 lh-13" style="text-decoration: underline;">-
                                    </div>
                                    <div class="fs-10 lh-13">All cheques are to be crossed and made payable to
                                    </div>
                                    <div class="fs-10 lh-13" style="text-decoration: underline;"><b>"{{$company['company_name']}}"</b></div>
                                </div>
                            </div>
    
                        </div>
                        <div class="col-2 col-sm-2">
                           
                        </div>
                        <div class="col-3 col-sm-3">
                            <div class="row">
                                <div class="col-8 col-md-8 text-end">
                                    <div class="fs-10 lh-13">Sub Total:</div>
                                </div>
                                <div class="col-4 col-md-4 text-end">
                                    <div>
                                        <div class="d-flex justify-content-between fs-10 lh-13">
                                            $<div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8 col-md-8 text-end">
                                    <div class="fs-10 lh-13">
                                        Discount:
                                    </div>
                                </div>
                                <div class="col-4 col-md-4 text-end">
                                    <div>
                                        <div class="d-flex justify-content-between fs-10 lh-13">
                                            $<div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8 col-md-8 text-end">
                                    <div class="fs-10 lh-13">Grand Total:</div>
                                </div>
                                <div class="col-4 col-md-4 text-end">
                                    <div>
                                        <div class="d-flex justify-content-between fs-10 lh-13">
                                            $<div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row  terms-row">
                        <h3><b>Terms and Conditions</b></h3>
                        <ol class="ps-4">
                            @foreach ($termCondition as $item)
                            <li>
                                {{$item->term_condition}}
                            </li>
                            @endforeach
                        </ol>
    
                    </div>
                    <div class="row">
                      
                        <div class="col-5">
                            <div class="signature-container">
    
                                <canvas id="signatureCanvas"></canvas>
                                <div class="signature-line"></div>
                                <div class="signature-details">
                                    <span class="name fs-12 lh-13">Customer's Acknowledgement</span>
                                    <span class="time fs-10 lh-13">Designation:</span>
                                    <span class="time fs-10 lh-13">Company Stamp:</span>
                                </div>
    
                            </div>
                        </div>
                        <div class="col-2">
    
                        </div>
                        <div class="col-5">
                            <div class="signature-container">
    
                                <canvas id="signatureCanvas"></canvas>
                                <div class="signature-line"></div>
                                <div class="signature-details">
                                    <span class="name fs-12 lh-13">Singapore Carpet Cleaning Pte Ltd</span>
                                    <span class="time fs-10 lh-13">Designation : Sales Coordinator</span>
                                </div>
    
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <h3><b>This is a computer generated invoice therefore no signature required.</b>
                            </h3>
                            <div class="d-flex footer-logo justify-content-between">
                                 <img src="{{asset('public/company_logos/'.$company->company_logo)}}" style="height: 100px; width:100px; background:black" alt="Company Logo" class="img-fluid">
                            </div>
                        </div>
    
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

