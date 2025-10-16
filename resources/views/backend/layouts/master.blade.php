<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="dark" data-toggled="close">
<head>
    <!-- Meta Data -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' | ' : '' }}{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset($settings ? $settings->favicon : '') }}" type="image/x-icon">
    <!-- Choices JS -->
    <script src="{{ asset('backend') }}/libs/choices.js/public/assets/scripts/choices.min.js"></script>
    <!-- Main Theme Js -->
    <script src="{{ asset('backend') }}/js/main.js"></script>
    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('backend') }}/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" >
    <!-- Style Css -->
    <link href="{{ asset('backend') }}/css/styles.min.css" rel="stylesheet" >
    <!-- Icons Css -->
    <link href="{{ asset('backend') }}/css/icons.css" rel="stylesheet" >
    <!-- Node Waves Css -->
    <link href="{{ asset('backend') }}/libs/node-waves/waves.min.css" rel="stylesheet" >
    <!-- Simplebar Css -->
    <link href="{{ asset('backend') }}/libs/simplebar/simplebar.min.css" rel="stylesheet" >
    <!-- Color Picker Css -->
    <link rel="stylesheet" href="{{ asset('backend') }}/libs/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset('backend') }}/libs/@simonwep/pickr/themes/nano.min.css">
    <!-- Choices Css -->
    <link rel="stylesheet" href="{{ asset('backend') }}/libs/choices.js/public/assets/styles/choices.min.css">
    <link rel="stylesheet" href="{{ asset('backend') }}/libs/jsvectormap/css/jsvectormap.min.css">
    <link rel="stylesheet" href="{{ asset('backend') }}/libs/swiper/swiper-bundle.min.css">
    <!-- Sweetalert-2 Css -->
    <link rel="stylesheet" href="{{ asset('backend/libs/sweetalert2/sweetalert2.min.css') }}">

    @stack('css')
</head>
<body>

    @include('backend.partials.theme')

    <div class="page">
        <!-- app-header -->
        @include('backend.partials.header')
        <!-- /app-header -->
        <!-- Start::app-sidebar -->
        @include('backend.partials.sidebar')
        <!-- End::app-sidebar -->

        <!-- Start::app-content -->
        <div class="main-content app-content">
            <div class="container-fluid">

                {{$slot}}

            </div>
        </div>
        <!-- End::app-content -->

        <!-- Footer Start -->
        @include('backend.partials.footer')
        <!-- Footer End -->

    </div>


    <!-- Scroll To Top -->
    <div class="scrollToTop">
        <span class="arrow"><i class="ri-arrow-up-s-fill fs-20"></i></span>
    </div>
    <div id="responsive-overlay"></div>
    <!-- Scroll To Top -->

    <!-- Popper JS -->
    <script src="{{ asset('backend')}}/libs/@popperjs/core/umd/popper.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('backend')}}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Defaultmenu JS -->
    <script src="{{ asset('backend')}}/js/defaultmenu.min.js"></script>
    <!-- Node Waves JS-->
    <script src="{{ asset('backend')}}/libs/node-waves/waves.min.js"></script>
    <!-- Sticky JS -->
    <script src="{{ asset('backend')}}/js/sticky.js"></script>
    <!-- Simplebar JS -->
    <script src="{{ asset('backend')}}/libs/simplebar/simplebar.min.js"></script>
    <script src="{{ asset('backend')}}/js/simplebar.js"></script>
    <!-- Color Picker JS -->
    <script src="{{ asset('backend')}}/libs/@simonwep/pickr/pickr.es5.min.js"></script>
    <!-- JSVector Maps JS -->
    <script src="{{ asset('backend')}}/libs/jsvectormap/js/jsvectormap.min.js"></script>
    <!-- JSVector Maps MapsJS -->
    <script src="{{ asset('backend')}}/libs/jsvectormap/maps/world-merc.js"></script>
    <!-- Apex Charts JS -->
    <script src="{{ asset('backend')}}/libs/apexcharts/apexcharts.min.js"></script>
    <!-- Chartjs Chart JS -->
    <script src="{{ asset('backend')}}/libs/chart.js/chart.min.js"></script>
    <!-- CRM-Dashboard -->
    <script src="{{ asset('backend')}}/js/crm-dashboard.js"></script>
    <!-- Custom-Switcher JS -->
    <script src="{{ asset('backend')}}/js/custom-switcher.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('backend')}}/js/custom.js"></script>


    <!-- Jquer JS -->
    <script src="{{ asset('backend/js/jquery.js') }}"></script>
    <!-- Sweetalert-2 JS -->
    <script src="{{ asset('backend') }}/libs/sweetalert2/sweetalert2.min.js"></script>

    <script>
        @if (session()->has('success'))
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 1500
            });
        @endif
    </script>

    @stack('js')

</body>
</html>
