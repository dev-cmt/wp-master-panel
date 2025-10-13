<aside class="app-sidebar sticky" id="sidebar">
    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        {{-- <a href="index.html" class="header-logo">
            <img src="../assets/images/brand-logos/desktop-logo.png" alt="logo" class="desktop-logo">
            <img src="../assets/images/brand-logos/toggle-logo.png" alt="logo" class="toggle-logo">
            <img src="../assets/images/brand-logos/desktop-dark.png" alt="logo" class="desktop-dark">
            <img src="../assets/images/brand-logos/toggle-dark.png" alt="logo" class="toggle-dark">
            <img src="../assets/images/brand-logos/desktop-white.png" alt="logo" class="desktop-white">
            <img src="../assets/images/brand-logos/toggle-white.png" alt="logo" class="toggle-white">
        </a> --}}
        <a href="{{ route('dashboard') }}" class="header-logo">
            <img src="{{ asset($settings ? $settings->logo : '') }}" alt="logo">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">

        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path> </svg>
            </div>

            <ul class="main-menu">

                <!-- Dashboard -->
                <li class="slide">
                    <a href="{{ route('dashboard') }}"
                        class="side-menu__item {{ Request::is('dashboard') ? 'active' : '' }}">
                        <i class="bx bxs-dashboard side-menu__icon"></i>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>

                <!-- Orders -->
                <li class="slide">
                    <a href="{{ route('orders.index') }}"
                        class="side-menu__item {{ Request::is('orders*') ? 'active' : '' }}">
                        <i class="bx bx-cart-alt side-menu__icon"></i>
                        <span class="side-menu__label">Orders</span>
                    </a>
                </li>

                <!-- Wordpress Orders -->
                <li class="slide">
                    <a href="{{ route('wp.orders-live') }}"
                        class="side-menu__item {{ Request::is('wp.orders-live*') ? 'active' : '' }}">
                        <i class="bx bxl-wordpress side-menu__icon"></i>
                        <span class="side-menu__label">WP-Orders</span>
                    </a>
                </li>

                <!-- Store -->
                <li class="slide">
                    <a href="{{ route('stores.index') }}"
                        class="side-menu__item {{ Request::is('stores*') ? 'active' : '' }}">
                        <i class="bx bx-store side-menu__icon"></i>
                        <span class="side-menu__label">Store</span>
                    </a>
                </li>






                <!-- Developer API -->
                @can('view developer api none')
                <li class="slide">
                    <a href="{{ route('developer-api.index') }}"
                        class="side-menu__item {{ Request::is('developer-api*') ? 'active' : '' }}">
                        <i class="bx bx-code-alt side-menu__icon"></i>
                        <span class="side-menu__label">Developer Api</span>
                    </a>
                </li>
                @endcan


                <!-- SEO Settings -->
                @can('view seo none')
                <li class="slide">
                    <a href="{{ route('settings.seo.index') }}" class="side-menu__item {{ Request::is('seo-pages*') ? 'active' : '' }}">
                        <i class="bx bx-search-alt-2 side-menu__icon"></i>
                        <span class="side-menu__label">SEO Settings</span>
                    </a>
                </li>
                @endcan


                <!-- Authentication - Only for admin -->
                @canany(['view roles', 'view users'])
                <li class="slide has-sub {{ Request::is('roles*') || Request::is('users*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ Request::is('roles*') || Request::is('users*') ? 'active' : '' }}">
                        <i class="bx bx-fingerprint side-menu__icon"></i>
                        <span class="side-menu__label">Authentication</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        @can('view roles')
                        <li class="slide">
                            <a href="{{ route('roles.index') }}" class="side-menu__item {{ Request::is('roles*') ? 'active' : '' }}">
                                Role & Permission
                            </a>
                        </li>
                        @endcan
                        @can('view users')
                        <li class="slide">
                            <a href="{{ route('users.index') }}" class="side-menu__item {{ Request::is('users*') ? 'active' : '' }}">
                                Users Manage
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany


                <!-- Settings -->
                @can('view settings')
                <li class="slide">
                    <a href="{{ route('setting.index') }}" class="side-menu__item {{ Request::is('setting*') ? 'active' : '' }}">
                        <i class="bx bxs-cog side-menu__icon"></i>
                        <span class="side-menu__label">Web Settings</span>
                    </a>
                </li>
                @endcan

            </ul>
            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                    viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg>
            </div>
        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside>
