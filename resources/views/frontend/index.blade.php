<!doctype html>

<html lang="en" class="light-style layout-navbar-fixed layout-wide" dir="ltr" data-theme="theme-default"
    data-assets-path="/assets/" data-template="front-pages" data-style="dark">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Tour Packages</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo/logo-tourpackage.svg') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />

    <!-- Core CSS -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css" class="template-customizer-core-css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/front-page.css') }}" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/nouislider/nouislider.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}" />

    <!-- Page CSS -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/front-page-landing.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/front-config.js') }}"></script>
</head>

<body>
    <script src="{{ asset('assets/vendor/js/dropdown-hover.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/mega-dropdown.js') }}"></script>

    <!-- Navbar: Start -->
    <nav class="py-0 shadow-none layout-navbar">
        <div class="container">
            <div class="px-3 navbar navbar-expand-lg landing-navbar px-md-8">
                <!-- Menu logo wrapper: Start -->
                <div class="py-0 navbar-brand app-brand demo d-flex py-lg-2 me-4 me-xl-8">
                    <!-- Mobile menu toggle: Start-->
                    <button class="px-0 border-0 navbar-toggler me-4" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <i class="align-middle ti ti-menu-2 ti-lg text-heading fw-medium"></i>
                    </button>
                    <!-- Mobile menu toggle: End-->
                    <a href="{{ url('/') }}" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <img width="38" height="38" src="{{ asset('assets/img/logo/logo-tourpackage.svg') }}" alt="Tour Pack" class="logo-icon me-2" />
                        </span>
                        <span class="app-brand-text demo menu-text fw-bold ms-2 ps-1">Tour Package</span>
                    </a>
                </div>
                <!-- Menu logo wrapper: End -->
                <!-- Menu wrapper: Start -->
                <div class="collapse navbar-collapse landing-nav-menu" id="navbarSupportedContent">
                    <button class="top-0 border-0 navbar-toggler text-heading position-absolute end-0 scaleX-n1-rtl"
                        type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="ti ti-x ti-lg"></i>
                    </button>
                    <ul class="navbar-nav me-auto">
                    </ul>
                </div>
                <div class="landing-menu-overlay d-lg-none"></div>
                <!-- Menu wrapper: End -->
                <!-- Toolbar: Start -->
                <ul class="flex-row navbar-nav align-items-center ms-auto">
                    <!-- Style Switcher -->
                    <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-1">
                        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                            data-bs-toggle="dropdown">
                            <i class="ti ti-lg"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                            <li>
                                <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                                    <span class="align-middle"><i class="ti ti-sun me-3"></i>Light</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                                    <span class="align-middle"><i class="ti ti-moon-stars me-3"></i>Dark</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                                    <span class="align-middle"><i
                                            class="ti ti-device-desktop-analytics me-3"></i>System</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- / Style Switcher-->
                    <!-- Navbar Button: Start -->
                    <li>
                        @if (Auth::check())
                        @if (Auth::user()->role === 'admin')
                        <a href="{{ url('/admin/dashboard') }}" class="btn btn-primary">
                            <span class="tf-icons ti ti-dashboard scaleX-n1-rtl me-md-1"></span>
                            <span class="d-none d-md-block">Admin Dashboard</span>
                        </a>
                        @elseif (Auth::user()->role === 'agen')
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                            <span class="tf-icons ti ti-dashboard scaleX-n1-rtl me-md-1"></span>
                            <span class="d-none d-md-block">Agen Dashboard</span>
                        </a>
                        @endif
                        @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <span class="tf-icons ti ti-login scaleX-n1-rtl me-md-1"></span>
                            <span class="d-none d-md-block">Login</span>
                        </a>
                        @endif
                    </li>
                    <!-- Navbar Button: End -->

                </ul>
                <!-- Toolbar: End -->
            </div>
        </div>
    </nav>
    <!-- Navbar: End -->

    <!-- Sections:Start -->

    <div data-bs-spy="scroll" class="scrollspy-example">
        <!-- Hero: Start -->
        <section id="hero-animation">
            <div id="landingHero" class="section-py landing-hero position-relative">
                <img src="{{ asset('assets/img/front-pages/backgrounds/hero-bg.png') }}" alt="hero background"
                    class="top-0 position-absolute start-50 translate-middle-x object-fit-cover w-100 h-100"
                    data-speed="1" />
                <div class="container">
                    <div class="text-center hero-text-box position-relative">
                        <h1 class="text-primary hero-title display-5 fw-extrabold">
                            Aplication For Manage Tour Package
                        </h1>
                        <h2 class="mb-6 hero-sub-title h6">
                            Production-ready & easy to use
                        </h2>
                    </div>
                    <div id="heroDashboardAnimation" class="hero-animation-img">
                        <a href="../vertical-menu-template/app-ecommerce-dashboard.html" target="_blank">
                            <div id="heroAnimationImg" class="position-relative hero-dashboard-img">
                                <img src="{{ asset('assets/img/front-pages/landing-page/hero-dashboard-light.png') }}"
                                    alt="hero dashboard" class="animation-img"
                                    data-app-light-img="front-pages/landing-page/hero-dashboard-light.png"
                                    data-app-dark-img="front-pages/landing-page/hero-dashboard-dark.png" />
                                <img src="{{ asset('assets/img/front-pages/landing-page/hero-elements-light.png') }}"
                                    alt="hero elements"
                                    class="top-0 position-absolute hero-elements-img animation-img start-0"
                                    data-app-light-img="front-pages/landing-page/hero-elements-light.png"
                                    data-app-dark-img="front-pages/landing-page/hero-elements-dark.png" />
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="landing-hero-blank"></div>
        </section>
        <!-- Hero: End -->
    </div>

    <!-- / Sections:End -->

    <!-- Footer: Start -->
    <footer class="landing-footer bg-body footer-text">
        <div class="py-3 footer-bottom py-md-5">
            <div
                class="container flex-wrap text-center d-flex justify-content-between flex-md-row flex-column text-md-start">
                <div class="mb-2 mb-md-0">
                    <span class="footer-bottom-text"><span class="footer-bottom-text">All Right Reserved </span>©
                        <script>
                            document.write(new Date().getFullYear());
                        </script>
                    </span>
                    <a href="" target="_blank" class="text-white fw-medium"> Tour Packages</a>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer: End -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/nouislider/nouislider.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/front-main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/front-page-landing.js') }}"></script>
</body>

</html>
