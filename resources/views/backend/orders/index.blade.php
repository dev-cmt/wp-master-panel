<x-backend-layout title="Orders">

    @php
        // Status name mapping
        $statusLabels = [
            0 => 'Hold',
            1 => 'Delivered',
            2 => 'Processing',
            3 => 'Pending Payment',
            4 => 'Cancelled',
            5 => 'Pending Invoice',
            6 => 'On Delivery',
            7 => 'Pending Return',
            8 => 'Courier',
            9 => 'No Response',
            10 => 'Invoiced',
            11 => 'Return',
            12 => 'Incomplete',
            13 => 'Confirmed',
            14 => 'Stock Out',
            15 => 'Partial Delivery',
            16 => 'Lost',
        ];

        // Badge colors for status dropdown and dashboard
        $statusColors = [
            0 => 'secondary',
            1 => 'success',
            2 => 'info',
            3 => 'warning',
            4 => 'danger',
            5 => 'secondary',
            6 => 'primary',
            7 => 'secondary',
            8 => 'info',
            9 => 'secondary',
            10 => 'success',
            11 => 'secondary',
            12 => 'secondary',
            13 => 'info',
            14 => 'danger',
            15 => 'primary',
            16 => 'danger',
        ];
    @endphp

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Orders Management</h1>
        <div class="col-md-2 col-12">
            <form action="{{ route('orders.index') }}" method="GET">
                <select class="form-control" name="storeId" id="storeId" onchange="this.form.submit()">
                    <option value="">All Store</option>
                    @foreach ($stores as $store)
                        <option value="{{ $store->id }}" {{ request('storeId') == $store->id ? 'selected' : '' }}>
                            {{ $store->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="row">
        @php
            $statusCards = [
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
        @endphp

        <!-- Total Orders Card -->
        <div class="col-lg-2 col-md-3 col-sm-4 px-1">
            <a href="{{ route('orders.index') }}" class="text-decoration-none text-dark">
                <div class="card custom-card mb-2">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-lg bg-primary">
                                    <i class="bi bi-stack fs-16"></i>
                                </span>
                            </div>
                            <div>
                                <p class="mb-1 text-muted">Total Orders</p>
                                <h5 class="mb-0 fw-semibold">{{ $totalOrders }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Status-wise Cards -->
        @foreach($statusCards as $status => $data)
            <div class="col-lg-2 col-md-3 col-sm-4 px-1">
                <a href="{{ route('orders.index', ['status' => $status]) }}" class="text-decoration-none text-dark">
                    <div class="card custom-card mb-2">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-top">
                                <div class="me-3">
                                    <span class="avatar avatar-lg {{ $data['color'] }}">
                                        <i class="bi {{ $data['icon'] }} fs-16"></i>
                                    </span>
                                </div>
                                <div>
                                    <p class="mb-1 text-muted">{{ $data['title'] }}</p>
                                    <h5 class="mb-0 fw-semibold">{{ $orderCounts[$status] ?? 0 }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <!-- Orders Table -->
    <div class="card custom-card">
        <div class="card-header justify-content-between py-2">
            <div class="card-title">Order List</div>
            <div class="d-flex gap-2">
                <button class="btn btn-success-light btn-border-start btn-sm">
                    <i class="ri-add-line fw-semibold align-middle me-1"></i> Create Order
                </button>
                <button type="button" id="sync-btn" class="btn btn-primary-light btn-border-start btn-sm">
                    <i class="bx bxl-wordpress me-1"></i> Sync Now
                </button>
                <a href="#" class="btn btn-info-light btn-border-start btn-sm"
                    onclick="return $('#storeId').val() ? (window.location='{{ route('wp.orders-live') }}?store_id='+$('#storeId').val()) : (alert('Please select a store first!'), $('#storeId').focus(), false);">
                    <i class="bx bxl-wordpress me-1"></i> Live Order List
                </a>
            </div>
        </div>

        <div class="card-body py-0">
            <div class="table-responsive">
                <table class="table text-nowrap">
                    <thead class="table-primary">
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th class="text-center">Status</th>
                            <th>Items</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            @php
                                $currentStatus = $statusLabels[$order->status] ?? 'Unknown';
                                $color = $statusColors[$order->status] ?? 'secondary';

                                $badgeColors = ['primary','success','warning','danger','info','secondary','dark'];
                                // Dynamic store badge color
                                $storeColor = $badgeColors[$order->store?->id % count($badgeColors) ?? 0] ?? 'secondary';
                                $storeName = $order->store?->name ?? 'Unknown';

                                // Dynamic source badge color
                                $sourceText = ucfirst($order->source ?? 'Unknown');
                                $hash = crc32($sourceText);
                                $sourceColor = $badgeColors[$hash % count($badgeColors)];
                            @endphp
                            <tr>
                                <td>
                                    <strong>#{{ $order->invoice_no ?? $order->id }}</strong><br>
                                    <span class="badge bg-{{ $storeColor }}">{{ $storeName }}</span>
                                </td>
                                <td>
                                    <strong>Name:</strong> {{ $order->customer_name ?? '' }}<br>
                                    <strong>Phone:</strong><small class="text-muted"><i class="fa fa-phone me-1"></i>{{ $order->phone ?? 'N/A' }}</small><br>
                                    <strong>Source:</strong><span class="badge bg-{{ $sourceColor }}">{{ $sourceText }}</span>
                                </td>
                                <td>
                                    {{ $order->order_date->format('M d, Y') }}<br>
                                    <small class="text-muted">{{ $order->order_date->format('g:i A') }}</small>
                                </td>
                                <td>
                                    <strong class="text-success">৳{{ number_format($order->total, 2) }}</strong><br>
                                    <small class="text-muted">BDT</small>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('orders.update-status', $order->id) }}" method="POST">
                                        @csrf
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-{{ $color }} dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                {{ $currentStatus }}
                                            </button>
                                            <ul class="dropdown-menu">
                                                @foreach ($statusLabels as $key => $label)
                                                    @if ($key != $order->status)
                                                        <li>
                                                            <button type="submit" name="status" value="{{ $key }}" class="dropdown-item">{{ $label }}</button>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($order->items as $item)
                                            <li class="d-flex justify-content-start align-items-center mb-1">
                                                <span class="avatar avatar-sm">
                                                    <img src="{{$item->product->image ?? null }}" alt="">
                                                </span>
                                                {{ $item->quantity }} × {{ $item->product->product_name }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <div class="hstack gap-2 fs-15">
                                        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-success-transparent rounded-pill"><i class="ri-download-2-line"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-info-transparent rounded-pill"><i class="ri-edit-line"></i></a>
                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fa fa-inbox fa-2x text-muted mb-2"></i>
                                    <div>No orders found</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="card-footer d-flex justify-content-center">
            {{ $orders->links('backend.pagination.custom') }}
        </div>
    </div>

    @push('js')
    <script>
        $(document).ready(function() {
            $('#sync-btn').on('click', function() {
                const btn = $(this);
                const storeId = $('#storeId').val();
                if (!storeId) { alert('Please select a store first!'); $('#storeId').focus(); return; }

                btn.prop('disabled', true).html('<span class="spinner-grow spinner-grow-sm align-middle" role="status"></span> Syncing...');
                $.ajax({
                    url: '{{ route("wp.orders-sync") }}',
                    type: 'POST',
                    data: {_token: '{{ csrf_token() }}', store_id: storeId},
                    success: function(response) { alert(response.message); location.reload(); },
                    error: function(xhr) { alert('Error: ' + xhr.responseText); },
                    complete: function() { btn.prop('disabled', false).html('<i class="fa fa-sync-alt me-1"></i> Sync Now'); }
                });
            });
        });
    </script>
    @endpush

</x-backend-layout>
