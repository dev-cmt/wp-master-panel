<x-backend-layout title="Dashboard">
    <!-- Start::page-header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">Welcome back, {{ Auth::user()->name }}!</p>
            <span class="fs-semibold text-muted">Track your sales activity, leads and deals here.</span>
        </div>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboards</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Start::row-1: Revenues / Staff / Customers / Products -->
    <div class="row">
        @php
            $totalRevenues  = DB::table('wp_orders')->sum('total');
            $totalStaffs    = DB::table('users')->where('status', true)->count();
            $totalCustomers = DB::table('users')->where('status', false)->count();
            $totalProducts  = DB::table('products')->count();

            $cards = [
                [
                    'title' => 'Total Revenues',
                    'value' => '৳ ' . number_format($totalRevenues),
                    'icon'  => 'bi-currency-dollar',
                    'bg'    => 'bg-primary-transparent text-primary'
                ],
                [
                    'title' => 'Total Staffs',
                    'value' => $totalStaffs,
                    'icon'  => 'bi-people-fill',
                    'bg'    => 'bg-secondary-transparent text-secondary'
                ],
                [
                    'title' => 'Total Customers',
                    'value' => $totalCustomers,
                    'icon'  => 'bi-person-lines-fill',
                    'bg'    => 'bg-success-transparent text-success'
                ],
                [
                    'title' => 'Total Products',
                    'value' => $totalProducts,
                    'icon'  => 'bi-box-seam',
                    'bg'    => 'bg-info-transparent text-info'
                ],
            ];
        @endphp

        @foreach($cards as $card)
            <div class="col-xl-3 col-md-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-top justify-content-between">
                            <div class="flex-fill">
                                <p class="mb-0 text-muted">{{ $card['title'] }}</p>
                                <span class="fs-5 fw-semibold">{{ $card['value'] }}</span>
                            </div>
                            <div>
                                <span class="avatar avatar-md avatar-rounded {{ $card['bg'] }} fs-18">
                                    <i class="bi {{ $card['icon'] }} fs-16"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <!-- End::row-1 -->

    <!-- Start::row-2: Orders as col-2 cards with icons -->
    <div class="row">
        @php
            // Get counts grouped by numeric status
            $orderCounts = DB::table('wp_orders')
                ->select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            // Status mapping with icon & color
            $statuses = [
                0 => ['title' => 'Hold', 'icon' => 'bi-pause-circle', 'color' => 'bg-info'],
                1 => ['title' => 'Delivered', 'icon' => 'bi-check2-circle', 'color' => 'bg-success'],
                2 => ['title' => 'Processing', 'icon' => 'bi-arrow-repeat', 'color' => 'bg-secondary'],
                3 => ['title' => 'Pending Payment', 'icon' => 'bi-clock', 'color' => 'bg-warning'],
                4 => ['title' => 'Cancelled', 'icon' => 'bi-x-circle', 'color' => 'bg-danger'],
                5 => ['title' => 'Pending Invoice', 'icon' => 'bi-file-earmark-text', 'color' => 'bg-primary'],
                6 => ['title' => 'On Delivery', 'icon' => 'bi-bicycle', 'color' => 'bg-primary'],
                7 => ['title' => 'Pending Return', 'icon' => 'bi-arrow-counterclockwise', 'color' => 'bg-warning'],
                8 => ['title' => 'Courier', 'icon' => 'bi-truck', 'color' => 'bg-info'],
                9 => ['title' => 'No Response', 'icon' => 'bi-question-circle', 'color' => 'bg-success'],
                10 => ['title' => 'Invoiced', 'icon' => 'bi-file-earmark-check', 'color' => 'bg-success'],
                11 => ['title' => 'Return', 'icon' => 'bi-reply-all', 'color' => 'bg-danger'],
                12 => ['title' => 'Incomplete', 'icon' => 'bi-dash-circle', 'color' => 'bg-warning'],
                13 => ['title' => 'Confirmed', 'icon' => 'bi-check-circle', 'color' => 'bg-success'],
                14 => ['title' => 'Stock Out', 'icon' => 'bi-box-seam', 'color' => 'bg-danger'],
                15 => ['title' => 'Partial Delivery', 'icon' => 'bi-arrow-right-square', 'color' => 'bg-info'],
                16 => ['title' => 'Lost', 'icon' => 'bi-exclamation-circle', 'color' => 'bg-secondary'],
            ];

            $totalOrders = DB::table('wp_orders')->count();
        @endphp

        {{-- Total Orders Card --}}
        <div class="col-md-2">
            <a href="{{ route('orders.index') }}" class="text-decoration-none text-dark">
                <div class="card custom-card mb-2">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-lg bg-primary">
                                    <i class="bi bi-stack fs-16"></i>
                                </span>
                            </div>
                            <div>
                                <p class="mb-1 text-muted">Total Orders</p>
                                <h5 class="fw-semibold">{{ $totalOrders }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Status-wise Cards --}}
        @foreach($statuses as $status => $data)
            <div class="col-md-2">
                <a href="{{ route('orders.index', ['status' => $status]) }}" class="text-decoration-none text-dark">
                    <div class="card custom-card mb-2">
                        <div class="card-body">
                            <div class="d-flex align-items-top">
                                <div class="me-3">
                                    <span class="avatar avatar-lg {{ $data['color'] }}">
                                        <i class="bi {{ $data['icon'] }} fs-16"></i>
                                    </span>
                                </div>
                                <div>
                                    <p class="mb-1 text-muted">{{ $data['title'] }}</p>
                                    <h5 class="fw-semibold">{{ $orderCounts[$status] ?? 0 }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
    <!-- End::row-2 -->
</x-backend-layout>
