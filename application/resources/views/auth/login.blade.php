<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Sign in</title>
    <!-- CSS files -->
    <link href="{!! asset('public/theme/dist/css/tabler.min.css') !!}" rel="stylesheet" />
    <link href="{!! asset('public/theme/dist/css/tabler-flags.min.css') !!}" rel="stylesheet" />
    <link href="{!! asset('public/theme/dist/css/tabler-payments.min.css') !!}" rel="stylesheet" />
    <link href="{!! asset('public/theme/dist/css/tabler-vendors.min.css') !!}" rel="stylesheet" />
    <link href="{!! asset('public/theme/dist/css/demo.min.css') !!}" rel="stylesheet" />
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

<body class=" d-flex flex-column">
    <script src="{!! asset('public/theme/dist/js/demo-theme.min.js') !!}"></script>
    <div class="page page-center">
        <div class="container container-normal py-4">
            <div class="row align-items-center g-4">
                <div class="col-lg">
                    <div class="container-tight">
                        <div class="text-center mb-4">
                            <a href="#" class="navbar-brand navbar-brand-autodark">
                                <img src="{!! asset('public/theme/dist/img/logo.png') !!}" alt="absolute" class="navbar-brand-image"
                                    style="height: 5rem; width: auto;"></a>
                        </div>
                        <div class="card card-md">
                            <div class="card-body">
                                <h2 class="h2 text-center mb-4">Login to your account</h2>
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Email address</label>
                                        <input type="email" class="form-control" name="email"
                                            placeholder="your@email.com" autocomplete="off">

                                        @if ($errors->has('email'))
                                          <label class="text-danger">{{ $errors->first('email') }}</label>
                                        @endif
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">
                                            Password
                                            <span class="form-label-description">
                                                {{-- <a href="forgot-password.html">I forgot password</a> --}}
                                                @if (Route::has('password.request'))
                                                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                        href="{{ route('password.request') }}">
                                                        {{ __('Forgot your password?') }}
                                                    </a>
                                                @endif
                                            </span>
                                        </label>
                                        <div class="input-group input-group-flat">
                                            <input type="password" class="form-control" name="password"
                                                placeholder="Your password" autocomplete="off" id="passwordInput">
                                            <span class="input-group-text">
                                                <a href="#" class="link-secondary" id="togglePassword"
                                                    title="Show password"
                                                    data-bs-toggle="tooltip"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                        width="24" height="24" viewBox="0 0 24 24"
                                                        stroke-width="2" stroke="currentColor" fill="none"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M12 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                        <path
                                                            d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                                                    </svg>
                                                </a>
                                            </span>                                            
                                        </div>

                                        @if ($errors->has('password'))
                                          <label class="text-danger">{{ $errors->first('password') }}</label>
                                        @endif
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-check">
                                            <input type="checkbox" class="form-check-input" name="remember" value="1"/>
                                            <span class="form-check-label">Remember me on this device</span>
                                        </label>
                                    </div>
                                    <div class="form-footer">
                                        <button type="submit" class="btn btn-primary w-100">Sign in</button>
                                    </div>
                                </form>
                            </div>
                            {{-- <div class="hr-text">or</div>
                <div class="card-body">
                  <div class="row">
                    <div class="col"><a href="#" class="btn w-100">
                        <!-- Download SVG icon from http://tabler-icons.io/i/brand-github -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-google" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                          <path d="M17.788 5.108a9 9 0 1 0 3.212 6.892h-8"></path>
                       </svg>
                        
                        Login with Google
                      </a></div>
                    <div class="col"><a href="#" class="btn w-100">
                        <!-- Download SVG icon from http://tabler-icons.io/i/brand-twitter -->
                        
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-facebook" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                          <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3"></path>
                       </svg>
                        Login with Facebook
                      </a></div>
                  </div>
                </div> --}}
                        </div>
                        <div class="text-center text-muted mt-3">
                            Don't have account yet? <a href="{{ route('register') }}" tabindex="-1">Sign up</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg d-none d-lg-block">
                    <img src="public/theme/static/illustrations/undraw_secure_login_pdn4.svg" height="300"
                        class="d-block mx-auto" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="{!! asset('public/theme/dist/js/tabler.min.js') !!}" defer></script>
    <script src="{!! asset('public/theme/dist/js/demo.min.js') !!}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var passwordInput = document.getElementById('passwordInput');
            var togglePassword = document.getElementById('togglePassword');

            togglePassword.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                } else {
                    passwordInput.type = 'password';
                }
            });

            // Initialize Bootstrap tooltip
            var tooltip = new bootstrap.Tooltip(togglePassword);
        });
    </script>
</body>

</html>
