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

<div class="page-body">
    <div class="container-xl">
        <div class="card card-lg" id="invoice-card">

            <div class="card-body">
                <div class="d-flex mb-2">
                    <div class="logo p-0 text-center">
                        {{-- <img src="{{'public/company_logos/'.$company_logo}}" alt="" class="img-fluid"
                            style="height: 100px; background-color:black;"> --}}
                    </div>
                    <div class="company-dece pe-0 ps-3">
                        {{-- <h1 class="title" style="font-size: 26px;"><b>{{$company_name}}</b></h1>
                        <p class="m-0 fs-12 lh-13">{{$company_address}} --}}
                        </p>
                        <p class="m-0 fs-12 lh-13">Tel:  Fax:  Phone: </p>
                            <p class="m-0 fs-12 lh-13">Website: </p>
                            <p class="m-0 fs-12 lh-13">Co. Reg No: 201317775M 
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-8"></div>
                    <div class="col-4 text-center">
                        <h3>Tax Invoice</h3>
                    </div>
                </div>
                <div class="row mb-3">

                    <div class="col-1 col-sm-1 text-center">
                        <h4 class="mb-3 fs-12 lh-13"><b>To:</b></h4>
                    </div>
                    <div class="col-4 col-sm-4 ps-0">
                        <div class="fs-12 lh-13">Mr Soo Chee Yei</div>
                        <div class="fs-12 lh-13">6 Holland Close #14-34</div>
                        <div class="fs-12 lh-13">Singapore 271006</div>
                    </div>

                    <div class="col-7 col-sm-7">
                    <div class="row">

                        <div class="col-8 col-md-8 text-end">
                            <div class="fs-12 lh-13"><b>Issued By:</b></div>
                        </div>
                        <div class="col-4 col-md-4 text-end">
                            <div class="fs-12 lh-13">Leau</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8 col-md-8 text-end">
                            <div class="fs-12 lh-13"><b>Invoice No:</b></div>
                        </div>
                        <div class="col-4 col-md-4 text-end">
                            <div class="fs-12 lh-13">ACI-22-001840</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8 col-md-8 text-end">
                            <div class="fs-12 lh-13"><b>Issued Date:</b></div>
                        </div>
                        <div class="col-4 col-md-4 text-end">
                            <div class="fs-12 lh-13">24-Aug-2022</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8 col-md-8 text-end">
                            <div class="fs-12 lh-13"><b>Service Sheet No</b></div>
                        </div>
                        <div class="col-4 col-md-4 text-end">
                            <div class="fs-12 lh-13">AC-SC-0120455</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8 col-md-8 text-end">
                            <div class="fs-12 lh-13"><b>Service Date:
                            </b></div>
                        </div>
                        <div class="col-4 col-md-4 text-end">
                            <div class="fs-12 lh-13">24-Aug-2022</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8 col-md-8 text-end">
                            <div class="fs-12 lh-13"><b>Team:
                            </b></div>
                        </div>
                        <div class="col-4 col-md-4 text-end">
                            <div class="fs-12 lh-13">AC 2, Lin Lin</div>
                        </div>
                    </div>

                </div>

                </div>

                <div class="table-responsive-sm">
                    <table class="invoice-table table">
                        <thead>
                            <tr>

                                <th>PRODUCT</th>
                                <th>DESCRIPTION</th>
                                <th>QTY</th>
                                <th>UNIT PRICE</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>CCCS-100</td>
                                <td>
                                    <div>
                                        HCRP-601
                                    </div>
                                    <div>
                                        6 Hours Office Cleaning Package For 2 sessions a week (8 sessions) - January 
                                        2023
                                        
                                    </div>
                                    <div>
                                        Time: 9am to 3pm (6hrs)
                                    </div>
                                    <div>
                                        No of Cleaners : 1
                                    </div>
                                    <div>
                                        Every: Monday & Tuesday
                                    </div>
                                    <br>
                                    <div class="details">
                                        <div>
                                            Scope of work:
                                        </div>
                                        <div class="fs-10 lh-13">
                                            - Washing of all toilets
                                        </div>
                                        <div class="fs-10 lh-13">
                                            - Vacuuming and mopping of floors
                                        </div>
                                        <div class="fs-10 lh-13">
                                            - Clean tiles and partition walls
                                        </div>
                                        <div class="fs-10 lh-13">
                                            - Clean furniture and pantry
                                        </div>
                                        <div class="fs-10 lh-13">
                                            - Clean all windows and grilles (on a rotating basis)
                                        </div>
                                        <div class="fs-10 lh-13">
                                            - Empty Bins
                                        </div>
                                        <div class="fs-10 lh-13">
                                            *Clients are to provide all cleaning materials
                                        </div>
                                    
                                    </div>
                                    <br>
                                    <div class="mb-3">
                                        Note: 12 sessions in a month ($1,251.10 before GST per month);
                                    </div>  


                                </td>
                                <td>1Unit(s)</td>
                                <td>
                                    <div class="d-flex justify-content-between">

                                        $

                                        <div>575.00</div>

                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-between">

                                        $

                                        <div>575.00</div>

                                    </div>
                                </td>
                            </tr>

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
                                <div class="fs-10 lh-13" style="text-decoration: underline;"><b>"@bsolute Cleaning Pte Ltd"</b></div>
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
                                        $<div>15,013.20</div>
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
                                        $<div>0.00</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 col-md-8 text-end">
                                <div class="fs-10 lh-13">Total:</div>
                            </div>
                            <div class="col-4 col-md-4 text-end">
                                <div>
                                    <div class="d-flex justify-content-between fs-10 lh-13">
                                        $<div>15,013.20</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 col-md-8 text-end">
                                <div class="fs-10 lh-13">GST @ 8%:
                                </div>
                            </div>
                            <div class="col-4 col-md-4 text-end">
                                <div>
                                    <div class="d-flex justify-content-between fs-10 lh-13">
                                        $<div>1201.08</div>
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
                                        $<div>16,214.28</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        

                    </div>
                </div>
                <div class="row  terms-row">
                    <h3><b>Terms and Conditions</b></h3>
                    <ol class="ps-4">
                        <li>
                            Goods or services sold are not refundable
                        </li>
                        <li>
                            Prices, Terms and Conditions are subjected to alteration without prior notice
                        </li>
                        <li>
                            Deposits paid are not refundable or exchangeable upon cancellation
                        </li>
                        <li>
                            A monthly interest of 3% shall be levied on overdue accounts
                        </li>
                        <li>
                            Client should ensure that the servicing area is free from home décor blockage,
                            we shall not be liable for any damages/costs incurred.
                        </li>
                        <li>
                            Our liability of loss or damage if any shall not exceed this invoice amount and
                            claims with supporting evidences must be made within one week from service date
                        </li>
                        <li>
                            Warranty shall be void if third party vendor(s) is/are engaged
                        </li>
                    </ol>

                </div>
                <br><br>
                <div class="row">
                    <div class="col-sm-12">
                        <h3><b>This is a computer generated invoice therefore no signature required.</b>
                        </h3>
                        <div class="d-flex footer-logo justify-content-between">
                            <div class="img">
                                <img src="dist/img/logo.png" alt="logo">
                            </div>
                            <div class="img">
                                <img src="dist/img/invoice-logo/logo-1.png" alt="logo">
                            </div>
                            <div class="img">
                                <img src="dist/img/invoice-logo/logo-2.png" alt="logo">
                            </div>
                            <div class="img">
                                <img src="dist/img/invoice-logo/logo-3.png" alt="logo">
                            </div>
                            <div class="img">
                                <img src="dist/img/invoice-logo/logo-4.jpg" alt="logo">
                            </div>
                            <div class="img">
                                <img src="dist/img/invoice-logo/logo-5.png" alt="logo">
                            </div>
                            <div class="img">
                                <img src="dist/img/invoice-logo/logo-9.webp" alt="logo">
                            </div>
                            <div class="img">
                                <img src="dist/img/invoice-logo/logo-7.png" alt="logo">
                            </div>
                            <div class="img">
                                <img src="dist/img/invoice-logo/logo-8.png" alt="logo">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>