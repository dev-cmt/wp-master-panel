<header class="app-header">
    <!-- Start::main-header-container -->
    <div class="main-header-container container-fluid">

        <!-- Start::header-content-left -->
        <div class="header-content-left">

            <!-- Start::header-element -->
            <div class="header-element">
                <div class="horizontal-logo">
                    <a href="index.html" class="header-logo">
                        <img src="../assets/images/brand-logos/desktop-logo.png" alt="logo" class="desktop-logo">
                        <img src="../assets/images/brand-logos/toggle-logo.png" alt="logo" class="toggle-logo">
                        <img src="../assets/images/brand-logos/desktop-dark.png" alt="logo" class="desktop-dark">
                        <img src="../assets/images/brand-logos/toggle-dark.png" alt="logo" class="toggle-dark">
                        <img src="../assets/images/brand-logos/desktop-white.png" alt="logo" class="desktop-white">
                        <img src="../assets/images/brand-logos/toggle-white.png" alt="logo" class="toggle-white">
                    </a>
                </div>
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element">
                <!-- Start::header-link -->
                <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle" data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
                <!-- End::header-link -->
            </div>
            <!-- End::header-element -->

        </div>
        <!-- End::header-content-left -->

        <!-- Start::header-content-right -->
        <div class="header-content-right">

            <!-- Start::header-search -->
            {{-- <div class="header-element header-search">
                <a href="javascript:void(0);" class="header-link" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="bx bx-search-alt-2 header-link-icon"></i>
                </a>
            </div> --}}
            <!-- End::header-search -->

            <!-- Start::header-notifications -->
            {{-- <div class="header-element notifications-dropdown">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" id="messageDropdown" aria-expanded="false">
                    <i class="bx bx-bell header-link-icon"></i>
                    <span class="badge bg-secondary rounded-pill header-icon-badge pulse pulse-secondary" id="notification-icon-badge">5</span>
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <!-- Start::main-header-dropdown -->
                <div class="main-header-dropdown dropdown-menu dropdown-menu-end" data-popper-placement="none">
                    <div class="p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="mb-0 fs-17 fw-semibold">Notifications</p>
                            <span class="badge bg-secondary-transparent" id="notifiation-data">5 Unread</span>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <ul class="list-unstyled mb-0" id="header-notification-scroll">
                        <li class="dropdown-item">
                            <div class="d-flex align-items-start">
                                    <div class="pe-2">
                                        <span class="avatar avatar-md bg-primary-transparent avatar-rounded"><i class="ti ti-gift fs-18"></i></span>
                                    </div>
                                    <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold"><a href="notifications.html">Your Order Has Been Shipped</a></p>
                                        <span class="text-muted fw-normal fs-12 header-notification-text">Order No: 123456 Has Shipped To Your Delivery Address</span>
                                    </div>
                                    <div>
                                        <a href="javascript:void(0);" class="min-w-fit-content text-muted me-1 dropdown-item-close1"><i class="ti ti-x fs-16"></i></a>
                                    </div>
                                    </div>
                            </div>
                        </li>
                        <li class="dropdown-item">
                            <div class="d-flex align-items-start">
                                    <div class="pe-2">
                                        <span class="avatar avatar-md bg-secondary-transparent avatar-rounded"><i class="ti ti-discount-2 fs-18"></i></span>
                                    </div>
                                    <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold"><a href="notifications.html">Discount Available</a></p>
                                        <span class="text-muted fw-normal fs-12 header-notification-text">Discount Available On Selected Products</span>
                                    </div>
                                    <div>
                                        <a href="javascript:void(0);" class="min-w-fit-content text-muted me-1 dropdown-item-close1"><i class="ti ti-x fs-16"></i></a>
                                    </div>
                                    </div>
                            </div>
                        </li>
                        <li class="dropdown-item">
                            <div class="d-flex align-items-start">
                                    <div class="pe-2">
                                        <span class="avatar avatar-md bg-pink-transparent avatar-rounded"><i class="ti ti-user-check fs-18"></i></span>
                                    </div>
                                    <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold"><a href="notifications.html">Account Has Been Verified</a></p>
                                        <span class="text-muted fw-normal fs-12 header-notification-text">Your Account Has Been Verified Sucessfully</span>
                                    </div>
                                    <div>
                                        <a href="javascript:void(0);" class="min-w-fit-content text-muted me-1 dropdown-item-close1"><i class="ti ti-x fs-16"></i></a>
                                    </div>
                                    </div>
                            </div>
                        </li>
                        <li class="dropdown-item">
                            <div class="d-flex align-items-start">
                                    <div class="pe-2">
                                        <span class="avatar avatar-md bg-warning-transparent avatar-rounded"><i class="ti ti-circle-check fs-18"></i></span>
                                    </div>
                                    <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold"><a href="notifications.html">Order Placed <span class="text-warning">ID: #1116773</span></a></p>
                                        <span class="text-muted fw-normal fs-12 header-notification-text">Order Placed Successfully</span>
                                    </div>
                                    <div>
                                        <a href="javascript:void(0);" class="min-w-fit-content text-muted me-1 dropdown-item-close1"><i class="ti ti-x fs-16"></i></a>
                                    </div>
                                    </div>
                            </div>
                        </li>
                        <li class="dropdown-item">
                            <div class="d-flex align-items-start">
                                    <div class="pe-2">
                                        <span class="avatar avatar-md bg-success-transparent avatar-rounded"><i class="ti ti-clock fs-18"></i></span>
                                    </div>
                                    <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold"><a href="notifications.html">Order Delayed <span class="text-success">ID: 7731116</span></a></p>
                                        <span class="text-muted fw-normal fs-12 header-notification-text">Order Delayed Unfortunately</span>
                                    </div>
                                    <div>
                                        <a href="javascript:void(0);" class="min-w-fit-content text-muted me-1 dropdown-item-close1"><i class="ti ti-x fs-16"></i></a>
                                    </div>
                                    </div>
                            </div>
                        </li>
                    </ul>
                    <div class="p-3 empty-header-item1 border-top">
                        <div class="d-grid">
                            <a href="notifications.html" class="btn btn-primary">View All</a>
                        </div>
                    </div>
                    <div class="p-5 empty-item1 d-none">
                        <div class="text-center">
                            <span class="avatar avatar-xl avatar-rounded bg-secondary-transparent">
                                <i class="ri-notification-off-line fs-2"></i>
                            </span>
                            <h6 class="fw-semibold mt-3">No New Notifications</h6>
                        </div>
                    </div>
                </div>
                <!-- End::main-header-dropdown -->
            </div> --}}
            <!-- End::header-notifications -->

            <!-- Start::header-dark/light -->
            <div class="header-element header-theme-mode">
                <!-- Start::header-link|layout-setting -->
                <a href="javascript:void(0);" class="header-link layout-setting">
                    <span class="light-layout">
                        <!-- Start::header-link-icon -->
                    <i class="bx bx-moon header-link-icon"></i>
                        <!-- End::header-link-icon -->
                    </span>
                    <span class="dark-layout">
                        <!-- Start::header-link-icon -->
                    <i class="bx bx-sun header-link-icon"></i>
                        <!-- End::header-link-icon -->
                    </span>
                </a>
                <!-- End::header-link|layout-setting -->
            </div>
            <!-- End::header-dark/light -->


            <!-- Start::header-element -->
            <div class="header-element header-fullscreen">
                <!-- Start::header-link -->
                <a onclick="openFullscreenEdit();" href="#" class="header-link">
                    <i class="bx bx-fullscreen full-screen-open header-link-icon"></i>
                    <i class="bx bx-exit-fullscreen full-screen-close header-link-icon d-none"></i>
                </a>
                <!-- End::header-link -->
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="#" class="header-link dropdown-toggle" id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <div class="me-sm-2 me-0">
                            <img src="{{ Auth::user()->profile_photo_path ? asset(Auth::user()->profile_photo_path) : asset('images/profile-photo.jpg') }}" alt="img" width="32" height="32" class="rounded-circle object-fit-cover">
                        </div>
                        <div class="d-sm-block d-none">
                            <p class="fw-semibold mb-0 lh-1">{{Auth::user()->name}}</p>
                            <span class="op-7 fw-normal d-block fs-11">{{ ucfirst(Auth::user()->getRoleNames()->first()) }}</span>
                        </div>
                    </div>
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <ul class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end" aria-labelledby="mainHeaderProfile">
                    <li><a class="dropdown-item d-flex" href="{{ route('profile.edit') }}"><i class="ti ti-user-circle fs-18 me-2 op-7"></i>Profile</a></li>
                    <li><a class="dropdown-item d-flex" href="{{ route('password.change') }}"><i class="ri-shield-keyhole-line fs-18 me-2 op-7"></i>Password</a></li>
                    <!-- Settings -->
                    @can('view settings')
                        <li><a class="dropdown-item d-flex" href="{{ route('setting.index') }}"><i class="ti ti-adjustments-horizontal fs-18 me-2 op-7"></i>Settings</a></li>
                    @endcan
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex">
                                <i class="ti ti-logout fs-18 me-2 op-7"></i> Log Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element">
                <!-- Start::header-link|switcher-icon -->
                <a href="#" class="header-link switcher-icon" data-bs-toggle="offcanvas" data-bs-target="#switcher-canvas">
                    <i class="bx bx-cog header-link-icon"></i>
                </a>
                <!-- End::header-link|switcher-icon -->
            </div>
            <!-- End::header-element -->

        </div>
        <!-- End::header-content-right -->

    </div>
    <!-- End::main-header-container -->

</header>

<script>
    function openFullscreenEdit(){
    const e=document.documentElement,o=document.querySelector('.full-screen-open'),c=document.querySelector('.full-screen-close');
    document.fullscreenElement?(document.exitFullscreen(),o.classList.remove('d-none'),c.classList.add('d-none')):(e.requestFullscreen(),o.classList.add('d-none'),c.classList.remove('d-none'));
    }
</script>
