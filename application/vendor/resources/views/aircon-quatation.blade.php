@extends('theme.default')
<!doctype html>

<html lang="en">

{{-- <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Absolute</title>
    <!-- CSS files -->
    <link href="./dist/css/tabler.min.css" rel="stylesheet" />
    <link href="./dist/css/tabler-vendors.min.css" rel="stylesheet" />
    <link href="./dist/css/demo.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
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
        
    </style>
    <style>
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
            margin-bottom: 10px;
            text-align: center;
        }

        .name,
        .time {
            display: block;
        }
    </style>
</head> --}}

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
        <header class="navbar navbar-expand-md navbar-light d-none d-lg-flex d-print-none">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                    aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <div>
                        <form action="./" method="get" autocomplete="off" novalidate="">
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                        <path d="M21 21l-6 -6"></path>
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" placeholder="Search…"
                                    aria-label="Search in website">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="navbar-nav flex-row order-md-last">
                    <div class="d-none d-md-flex">
                        <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" data-bs-toggle="tooltip"
                            data-bs-placement="bottom" aria-label="Enable dark mode"
                            data-bs-original-title="Enable dark mode">
                            <!-- Download SVG icon from http://tabler-icons.io/i/moon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path
                                    d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z">
                                </path>
                            </svg>
                        </a>
                        <a href="?theme=light" class="nav-link px-0 hide-theme-light" data-bs-toggle="tooltip"
                            data-bs-placement="bottom" aria-label="Enable light mode"
                            data-bs-original-title="Enable light mode">
                            <!-- Download SVG icon from http://tabler-icons.io/i/sun -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                                <path
                                    d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7">
                                </path>
                            </svg>
                        </a>
                        <div class="nav-item dropdown d-none d-md-flex me-3">
                            <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                                aria-label="Show notifications">
                                <!-- Download SVG icon from http://tabler-icons.io/i/bell -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6">
                                    </path>
                                    <path d="M9 17v1a3 3 0 0 0 6 0v-1"></path>
                                </svg>
                                <span class="badge bg-red"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Last updates</h3>
                                    </div>
                                    <div class="list-group list-group-flush list-group-hoverable">
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto"><span
                                                        class="status-dot status-dot-animated bg-red d-block"></span>
                                                </div>
                                                <div class="col text-truncate">
                                                    <a href="#" class="text-body d-block">Example 1</a>
                                                    <div class="d-block text-muted text-truncate mt-n1">
                                                        Change deprecated html tags to text decoration classes (#29604)
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="list-group-item-actions">
                                                        <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted"
                                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path
                                                                d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto"><span class="status-dot d-block"></span></div>
                                                <div class="col text-truncate">
                                                    <a href="#" class="text-body d-block">Example 2</a>
                                                    <div class="d-block text-muted text-truncate mt-n1">
                                                        justify-content:between ⇒ justify-content:space-between (#29734)
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="list-group-item-actions show">
                                                        <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-yellow"
                                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path
                                                                d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto"><span class="status-dot d-block"></span></div>
                                                <div class="col text-truncate">
                                                    <a href="#" class="text-body d-block">Example 3</a>
                                                    <div class="d-block text-muted text-truncate mt-n1">
                                                        Update change-version.js (#29736)
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="list-group-item-actions">
                                                        <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted"
                                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path
                                                                d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto"><span
                                                        class="status-dot status-dot-animated bg-green d-block"></span>
                                                </div>
                                                <div class="col text-truncate">
                                                    <a href="#" class="text-body d-block">Example 4</a>
                                                    <div class="d-block text-muted text-truncate mt-n1">
                                                        Regenerate package-lock.json (#29730)
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="list-group-item-actions">
                                                        <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted"
                                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path
                                                                d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-13 text-reset p-0" data-bs-toggle="dropdown"
                            aria-label="Open user menu">
                            <span class="avatar avatar-sm"
                                style="background-image: url(./static/avatars/000m.jpg)"></span>
                            <div class="d-none d-xl-block ps-2">
                                <div>Paweł Kuna</div>
                                <div class="mt-1 small text-muted">UI Designer</div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="#" class="dropdown-item">Status</a>
                            <a href="#" class="dropdown-item">Profile</a>
                            <a href="#" class="dropdown-item">Feedback</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">Settings</a>
                            <a href="#" class="dropdown-item">Logout</a>
                        </div>
                    </div>
                </div>

            </div>
        </header>
        <div class="page-wrapper">
            <!-- Page header -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <h2 class="page-title">
                                @bsolute Cleaning Pte Ltd
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
                                    <h1 class="title" style="font-size: 26px;"><b>@bsolute Cleaning Pte Ltd</b></h1>
                                    <p class="m-0 fs-12 lh-13">61 Kaki Bukit Ave 1 #03-05 Shun Li Industrial Park
                                        Singapore 417943
                                    </p>
                                    <p class="m-0 fs-12 lh-13">Tel: +65 6844 8444 Fax: +65 6844 3422 Phone: +65 8488
                                        8444</p>
                                        <p class="m-0 fs-12 lh-13">Website: www.absolutesolutions.com.sg</p>
                                        <p class="m-0 fs-12 lh-13">Co. Reg No: 201317775M 
                                           </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-8"></div>
                                <div class="col-4 text-center">
                                    <h3>QUOTATION</h3>
                                </div>
                            </div>
                            <div class="row mb-3">

                                <div class="col-1 col-sm-1 text-center">
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
                                    <div class="fs-12 lh-13">OLIVEMJE@schaeffler.com
                                        
                                        </div>
                                </div>

                                <div class="col-7 col-sm-7">
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


</html>