 <header class="navbar-expand-md">
     <div class="collapse navbar-collapse" id="navbar-menu">
         <div class="navbar navbar-light">
             <div class="container-fluid">
                 <ul class="navbar-nav">
                     <li class="nav-item">
                         <a class="nav-link" href="{{ route('dashboard') }}">
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
                         <a class="nav-link" href="{{ route('crm') }}">
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
                         <a class="nav-link" href="{{ route('leads') }}">
                             <span class="nav-link-icon d-md-none d-lg-inline-block">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-plus"
                                     width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                     stroke="currentColor" fill="none" stroke-linecap="round"
                                     stroke-linejoin="round">
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
                         <a class="nav-link" href="{{ route('quotation') }}">
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
                         <a class="nav-link" href="{{ route('salesOrder') }}">
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
                                 Sales Order
                             </span>
                         </a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link" href="{{route('finance')}}">
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
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calculator"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M4 3m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z">
                                    </path>
                                    <path d="M8 7m0 1a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v1a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1z">
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
                                Payment
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('payment.index') }}" style="color:rgb(0, 0, 0)">
                                Payment
                            </a>
                            
                            <a class="dropdown-item" href="{{ route('payment-approve.index') }}" style="color:rgb(0, 0, 0)">
                                Payment Approval
                            </a>

                            <a class="dropdown-item" href="{{ route('payment-history.index') }}" style="color:rgb(0, 0, 0)">
                                Payment History
                            </a>
                            {{-- <a class="dropdown-item" href="{{ route('all-payments') }}" style="color:rgb(0, 0, 0)">
                                All Payment Details
                            </a> --}}
                       </div>
                    </li>
                     <li class="nav-item">
                         <a class="nav-link" href="{{ route('services') }}">
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
                        <a class="nav-link" href="{{ route('cleaners') }}">
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
                                Helpers
                            </span>
                        </a>
                    </li>
                     <li class="nav-item">
                         <a class="nav-link" href="{{ route('schedule') }}">
                             <span class="nav-link-icon d-md-none d-lg-inline-block">
                                 <svg xmlns="http://www.w3.org/2000/svg"
                                     class="icon icon-tabler icon-tabler-calendar-check" width="24"
                                     height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                     fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                         <a class="nav-link" href="{{ route('report') }}">
                             <span class="nav-link-icon d-md-none d-lg-inline-block">
                                 <svg xmlns="http://www.w3.org/2000/svg"
                                     class="icon icon-tabler icon-tabler-chart-infographic" width="24"
                                     height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                     fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                         <a class="nav-link" href="{{ route('setting') }}">
                             <span class="nav-link-icon d-md-none d-lg-inline-block">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings"
                                     width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5"
                                     stroke="currentColor" fill="none" stroke-linecap="round"
                                     stroke-linejoin="round">
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
                         <a class="nav-link" href="hrms/admin/dashboard">
                             <span class="nav-link-icon d-md-none d-lg-inline-block">
                                 <svg xmlns="http://www.w3.org/2000/svg"
                                     class="icon icon-tabler icon-tabler-layout-dashboard" width="24"
                                     height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                     fill="none" stroke-linecap="round" stroke-linejoin="round">
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

                     <li class="nav-item">
                         <a class="nav-link" href="{{ route('emailtemplate.index') }}">
                             <span class="nav-link-icon d-md-none d-lg-inline-block">
                                 <svg xmlns="http://www.w3.org/2000/svg"
                                     class="icon icon-tabler icon-tabler-layout-dashboard" width="24"
                                     height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                     fill="none" stroke-linecap="round" stroke-linejoin="round">
                                     <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                     <path d="M4 4h6v8h-6z"></path>
                                     <path d="M4 16h6v4h-6z"></path>
                                     <path d="M14 12h6v8h-6z"></path>
                                     <path d="M14 4h6v4h-6z"></path>
                                 </svg>
                             </span>
                             <span class="nav-link-title">
                                 Email Template
                             </span>
                         </a>
                     </li>

                     <li class="nav-item">
                         <a class="nav-link" href="{{ route('term.condition.index') }}">
                             <span class="nav-link-icon d-md-none d-lg-inline-block">
                                 <svg xmlns="http://www.w3.org/2000/svg"
                                     class="icon icon-tabler icon-tabler-layout-dashboard" width="24"
                                     height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                     fill="none" stroke-linecap="round" stroke-linejoin="round">
                                     <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                     <path d="M4 4h6v8h-6z"></path>
                                     <path d="M4 16h6v4h-6z"></path>
                                     <path d="M14 12h6v8h-6z"></path>
                                     <path d="M14 4h6v4h-6z"></path>
                                 </svg>
                             </span>
                             <span class="nav-link-title">
                                 Terms & Conditions
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
                                 Configuration
                             </span>
                         </a>
                         <div class="dropdown-menu">
                             <a class="dropdown-item" href="{{ route('territory') }}" style="color:rgb(0, 0, 0)">
                                 Territory
                             </a>
                             <a class="dropdown-item" href="{{ route('languageSpoken') }}" style="color:rgb(0, 0, 0)">
                                 Language Spoken
                             </a>
                             <a class="dropdown-item" href="{{ route('paymentTerms') }}" style="color:rgb(0, 0, 0)">
                                 Payment Terms
                             </a>

                         </div>
                     </li>

                     {{-- <li class="nav-item dropdown">
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
                             <a class="dropdown-item" href="#">
                                 Sign in
                             </a>
                             <a class="dropdown-item" href="#">
                                 Sign up
                             </a>
                             <a class="dropdown-item" href="#" target="_blank" rel="noopener">
                                 Forgot password
                             </a>

                         </div>
                     </li> --}}

                 </ul>

             </div>
         </div>
     </div>
 </header>
