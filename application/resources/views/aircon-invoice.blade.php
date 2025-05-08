@extends('theme.default')

<body>
    <script src="./dist/js/demo-theme.min.js"></script>
    <div class="page">
        <!-- Sidebar -->
        <aside class="navbar navbar-vertical navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
                    aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark">
                    <a href="#">
                        <img src="dist/img/logo-white.png" width="110" height="32" alt="Absolute" class="
                            navbar-brand-image">
                    </a>
                </h1>

                <div class="collapse navbar-collapse" id="sidebar-menu">
                    <ul class="navbar-nav pt-lg-3">
                        <li class="nav-item">
                            <a class="nav-link" href="index.html">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                        <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                        <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Home
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="crm.html">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    CRM
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="leads.html">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-user-plus" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                        <path d="M16 19h6"></path>
                                        <path d="M19 16v6"></path>
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Leads
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="quoatation.html">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-file-certificate" width="44" height="44"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M5 8v-3a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-5" />
                                        <circle cx="6" cy="14" r="3" />
                                        <path d="M4.5 17l-1.5 5l3 -1.5l3 1.5l-1.5 -5" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Quotation
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="sales_order.html">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-circle-check" width="44" height="44"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <circle cx="12" cy="12" r="9" />
                                        <path d="M9 12l2 2l4 -4" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Confirmed Sales Order
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-calculator" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path
                                            d="M4 3m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z">
                                        </path>
                                        <path
                                            d="M8 7m0 1a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v1a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1z">
                                        </path>
                                        <path d="M8 14l0 .01"></path>
                                        <path d="M12 14l0 .01"></path>
                                        <path d="M16 14l0 .01"></path>
                                        <path d="M8 17l0 .01"></path>
                                        <path d="M12 17l0 .01"></path>
                                        <path d="M16 17l0 .01"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Finance
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="service.html">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-tool"
                                        width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Services
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-adjustments-horizontal" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M14 6m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                        <path d="M4 6l8 0"></path>
                                        <path d="M16 6l4 0"></path>
                                        <path d="M8 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                        <path d="M4 12l2 0"></path>
                                        <path d="M10 12l10 0"></path>
                                        <path d="M17 18m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                        <path d="M4 18l11 0"></path>
                                        <path d="M19 18l1 0"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Service Managment
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="scedule.html">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-calendar-check" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M11.5 21h-5.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v6">
                                        </path>
                                        <path d="M16 3v4"></path>
                                        <path d="M8 3v4"></path>
                                        <path d="M4 11h16"></path>
                                        <path d="M15 19l2 2l4 -4"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Schedule
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="report.html">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-chart-infographic" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M7 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                                        <path d="M7 3v4h4"></path>
                                        <path d="M9 17l0 4"></path>
                                        <path d="M17 14l0 7"></path>
                                        <path d="M13 13l0 8"></path>
                                        <path d="M21 12l0 9"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Report
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="settings.html">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-settings" width="44" height="44"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Settings
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-layout-dashboard" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 4h6v8h-6z"></path>
                                        <path d="M4 16h6v4h-6z"></path>
                                        <path d="M14 12h6v8h-6z"></path>
                                        <path d="M14 4h6v4h-6z"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    HRMS
                                </span>
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown"
                                data-bs-auto-close="false" role="button" aria-expanded="false">
                                <span
                                    class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/lifebuoy -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-lock-check" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M11.5 21h-4.5a2 2 0 0 1 -2 -2v-6a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v.5">
                                        </path>
                                        <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0"></path>
                                        <path d="M8 11v-4a4 4 0 1 1 8 0v4"></path>
                                        <path d="M15 19l2 2l4 -4"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Authentication
                                </span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="Authentication/sign-in.html">
                                    Sign in
                                </a>
                                <a class="dropdown-item" href="Authentication/sign-up.html">
                                    Sign up
                                </a>
                                <a class="dropdown-item" href="Authentication/forgot-password.html" target="_blank"
                                    rel="noopener">
                                    Forgot password
                                </a>

                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </aside>
        <div class="page-wrapper">
            <!-- Page header -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <h2 class="page-title">
                                @bsolute Aircon Invoice
                            </h2>
                        </div>
                        <!-- Page title actions -->
                        <div class="col-auto ms-auto d-print-none">
                            <button type="button" class="btn btn-primary" id="print-btn">
                                <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2">
                                    </path>
                                    <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"></path>
                                    <path
                                        d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z">
                                    </path>
                                </svg>
                                Print Invoice
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    <div class="card card-lg" id="invoice-card">

                        <div class="card-body">
                            <div class="d-flex mb-2">
                                <div class="logo p-0 text-center">
                                    <img src="dist/img/invoice-logo/logo-1.png" alt="" class="img-fluid"
                                        style="height: 100px;">
                                </div>
                                <div class="company-dece pe-0 ps-3">
                                    <h1 class="title" style="font-size: 26px;"><b>@bsolute Aircon Pte Ltd</b></h1>
                                    <p class="m-0 fs-12 lh-13">61 Kaki Bukit Ave 1 #03-05 Shun Li Industrial Park
                                        Singapore 417943
                                    </p>
                                    <p class="m-0 fs-12 lh-13">Tel: +65 6844 8444 Fax: +65 6844 3422 Phone: +65 8488
                                        8444</p>
                                    <p class="m-0 fs-12 lh-13">Website: www.absolutesolutions.com.sg</p>
                                    <p class="m-0 fs-12 lh-13">Co. Reg No: 201524788N
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GST Reg
                                        No.
                                        201524788N</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-8"></div>
                                <div class="col-4 text-center">
                                    <h3>TAX INVOICE</h3>
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
                                            <td>ACCT-041 </td>
                                            <td>
                                                <div>
                                                    Bi Annual Maintenance Contract for 2 unit
                                                </div>
                                                

                                            </td>
                                            <td>1.00</td>
                                            <td>
                                                <div class="d-flex justify-content-between">

                                                    $

                                                    <div>126.00</div>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">

                                                    $

                                                    <div>126    .00</div>

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
                                            <div class="fs-10 lh-13">DBS Current: 017-904550-9</div>
                                            <div class="fs-10 lh-13">Bank Code: 7171 / Branch Code: 017</div>
                                            <div class="fs-10 lh-13">OCBC Current: 686-026980-001</div>
                                            <div class="fs-10 lh-13">Bank Code: 7339 / Branch Code: 686</div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-3 col-md-3 text-start">
                                            <div class="fs-10 lh-13"><b>Commence Date:</b></div>
                                        </div>
                                        <div class="col-9 col-md-9 text-start">
                                            <div class="fs-10 lh-13">OCBC Current: 695-163-311-001
                                            </div>
                                            <div class="fs-10 lh-13">Bank Code: 7339 / Branch Code: 695</div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-3 col-md-3 text-start">
                                            <div class="fs-10 lh-13"><b>Payment Method:</b></div>
                                        </div>
                                        <div class="col-9 col-md-9 text-start">
                                            <div class="fs-10 lh-13" style="text-decoration: underline;">"Bank Code: 7339 / Branch Code: 686"
                                            </div>
                                            <div class="fs-10 lh-13">All cheques are to be crossed and made payable to
                                            </div>
                                            <div class="fs-10 lh-13" style="text-decoration: underline;"><b>"@bsolute Aircon Pte Ltd"</b></div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-2 col-sm-2">
                                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAclBMVEXMzMzQ0NDT09PLy8sAAADIyMh+fn6Li4u1tbV7e3uCgoLAwMDFxcVFRUVZWVm9vb2ioqJUVFScnJxOTk6VlZU8PDxpaWmxsbEjIyOKioooKChlZWVwcHB1dXVBQUGRkZE2NjYNDQ1JSUkvLy8cHBwTExNimPTCAAAEAElEQVR4nO3YWZuiOBiGYQJSIolhCYsKgi3V//8vTlgLl2uqZ6Zb5+C5j8DWt/NlhXIcAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA/L+I0e3dcu/c3v1yzn8I+t3csg0/wm0w3sl0O8qisVkqO4ZhG3zfstscJ8rGnGMmxyA5BEWvLzHa5UYXVb5v/b4dcZ7vB1U5tCXQnS5016TuP8pxRHoZg5pi7J0yudigg4m/C/rdRFzFQSRlcD6lQ8NObTCI1PDveRNHMiqrQ/z3nX+X44i2S6egoefkxQxByaV89Sj6clgbwjON31eYl6vl49afZX8lvEPyTd/f5jhi2wSrhedVl2AIig7FqwdxJo4bO2oi69Z97G7O452If8SrLePx6i7HEWG+XrveJpzustM3s+GPmSu8rhom0s1cr3c4z5+rdP5QlKl6nmMrTFabijh+Tr+xgxi+q8LwRz9Ls30gfOVNzQl/zlPKT/R8GWlTjleluT5ssmNOX6F0lD8FuXU+/1pe6zdNU0+bvsI2ibNd3ZbDALjV0jCvNsuJGSXJUGKZJI+b/5jjiF0VZ+dzW8ohyCzLWFXFe8ZQxId+D/Q/PvdJUdidvR8cN9kvFYZ7uXw3sCUKYQt8PCanHEcVhzGo6DvBzZcZ4J/1w8x+BSH18B97aZ1GwlWx7ofH3SfPKuxLNEFgnhU45TheW8d2d5Wpqezv3NOyg6o3Vag+mnFtKTW0WpRdayu8Ph3DYaIa82SKfuXYNTgGpV1qgzr97gqzU+vdfCD12bOzdFmHTmjW9bjxZvPs6eQhRwQmtEGX5GuWVu9Yh/EpvG2Y4+90ZHeaw9IwrVcFiWBvzP5xkj7m2AVZSLvTLJNBmjfspSLe1PeN9cNC9efhPDX9S/XVMBE1jVJNczdNn+U4fr3z7XnYqfmnl93Lx9A2LLzvVhFd+4PZ3Wynk1HNV/1N2RglhDLNzTPmsxz73TyzX/I32RQUfGYvrlB423XDxPyU1mXDcXEZzmwhdP61P5Qm6U85IZP56H/MWYKyrn9K869mDPKTxP+j9TySRzs6/sDe+WVpR0f48b7f5G2Pd0VkGyuzTbr0vCymXdTuqIV8nuOoMuiDVNrUQ9eU3dkeHkIef7z6sVSEm0O4G5ztO29UN3WbZefT9HroxLk5Zll1WA2PiuctRgSxep4jgmpvg9q6m14PvfSit1mmT+3L3w/TMPwY7WzLvKA9a52EmZx7uvyodPVx8wL87N3iLsdOhrbWWoepmr7ixzutq/DlL8CrP6eMrRXCk1Hkrl4LHBmpX/jzyl1OHxT9qyAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAC8yV8YEDZOb4QEjAAAAABJRU5ErkJggg=="
                                        alt="" style="height: 100px; width: 100px;">
                                </div>
                                <div class="col-3 col-sm-3">
                                    <div class="row">
                                        <div class="col-8 col-md-8 text-end">
                                            <div class="fs-10 lh-13">Sub Total:</div>
                                        </div>
                                        <div class="col-4 col-md-4 text-end">
                                            <div>
                                                <div class="d-flex justify-content-between fs-10 lh-13">
                                                    $<div>417.00</div>
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
                                                    $<div>417.00</div>
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
                                                    $<div>417.00</div>
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
                                                    $<div>33.36</div>
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
                                                    $<div>450.36</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8 col-md-8 text-end">
                                            <div class="fs-10 lh-13">Deposit:</div>
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
                                            <div class="fs-10 lh-13">Balance:</div>
                                        </div>
                                        <div class="col-4 col-md-4 text-end">
                                            <div>
                                                <div class="d-flex justify-content-between fs-10 lh-13">
                                                    $<div>450.36</div>
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
                                        Client should ensure that the servicing area is free from home décor blockage, we shall not be liable for any damages/costs incurred.
                                    </li>
                                    <li>
                                         Our liability of loss or damage if any shall not exceed this invoice amount and claims with supporting evidences must be made within one week from service date
                                    </li>
                                    <li>
                                        Warranty shall be void if third party vendor(s) is/are engaged
                                    </li>
                                    
                                </ol>
                              
                            </div>
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
                                            <img src="dist/img/invoice-logo/logo-6.png" alt="logo">
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
            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">

                        <div class="col-12 mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    Copyright © 2023
                                    <a href="#" class="link-secondary">Absolute</a>.
                                    All rights reserved.
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Tabler Core -->
    <script src="./dist/js/tabler.min.js" defer></script>
    <script src="./dist/js/demo.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.3.3/jQuery.print.min.js"></script>
    <script type="text/javascript">
        $('#print-btn').on('click', function () {
            $.print("#invoice-card");
        });
    </script>
</body>