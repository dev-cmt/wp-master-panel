<x-backend-layout title="Orders">

    @php
        $statuses = [
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
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Orders</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">Order List</div>
            <button type="button" id="sync-btn" class="btn btn-primary btn-sm">
                <i class="fa fa-sync-alt me-1"></i> Sync Now
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table text-nowrap">
                    <thead class="table-primary">
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Items</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            @php
                                $billing = json_decode($order->billing, true) ?? [];
                                $items = $order->items ?? collect();
                                $currentStatus = $statuses[$order->status] ?? 'Unknown';
                                $color = $statusColors[$order->status] ?? 'secondary';
                            @endphp
                            <tr>
                                <td>
                                    <strong>#{{ $order->order_number ?? $order->id }}</strong><br>
                                    <small class="text-muted">ID: {{ $order->id }}</small>
                                </td>
                                <td>
                                    {{ $billing['first_name'] ?? '' }} {{ $billing['last_name'] ?? '' }}<br>
                                    <small class="text-muted"><i class="fa fa-phone me-1"></i>{{ $billing['phone'] ?? 'N/A' }}</small><br>
                                    <small class="text-muted"><i class="fa fa-envelope me-1"></i>{{ $billing['email'] ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    {{ $order->created_at->format('M d, Y') }}<br>
                                    <small class="text-muted">{{ $order->created_at->format('g:i A') }}</small>
                                </td>
                                <td>
                                    <strong class="text-success">${{ number_format($order->total, 2) }}</strong><br>
                                    <small class="text-muted">USD</small>
                                </td>

                                <!-- Status Dropdown -->
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-{{ $color }} dropdown-toggle"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                            {{ $currentStatus }}
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach ($statuses as $key => $label)
                                                @if ($key != $order->status)
                                                    <li>
                                                        <a class="dropdown-item change-status"
                                                           href="#"
                                                           data-id="{{ $order->id }}"
                                                           data-status="{{ $key }}"
                                                           data-route="{{ route('orders.update-status', $order->id) }}">
                                                            {{ $label }}
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>

                                <td>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($items as $item)
                                            @php
                                                $meta = json_decode($item->meta, true) ?? [];
                                                $productUrl = "https://wp.skytechsolve.com/?p=" . $item->product_id;
                                            @endphp
                                            <li>
                                                <i class="fa fa-cube me-1"></i>
                                                <a href="{{ $productUrl }}" target="_blank">{{ $item->product_name }}</a> × {{ $item->quantity }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>

                                <td>
                                    <div class="hstack gap-2 fs-15">
                                        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-success-transparent rounded-pill"><i class="ri-download-2-line"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-info-transparent rounded-pill"><i class="ri-edit-line"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill"><i class="ri-delete-bin-line"></i></a>
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

    <!-- Toast -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055">
        <div id="liveToast" class="toast align-items-center text-white bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
            <div class="d-flex flex-column">
                <div class="toast-header text-white">
                    <img src="{{ asset($settings ? $settings->logo : '') }}" class="rounded me-2" width="20" height="20" alt="...">
                    <strong class="me-auto">Frodly</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">Hello, world! This is a toast message.</div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-light" role="progressbar" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        function showToast(message = 'Copied to clipboard!') {
            const toastEl = document.getElementById('liveToast');
            toastEl.querySelector('.toast-body').textContent = message;

            const progressBar = toastEl.querySelector('.progress-bar');
            progressBar.style.width = '100%';
            let width = 100;
            const interval = setInterval(() => {
                width -= 5;
                progressBar.style.width = width + '%';
                if(width <= 0) clearInterval(interval);
            }, 100);

            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }

        $(document).ready(function() {
            // Status change
            $('.change-status').on('click', function(e) {
                e.preventDefault();
                const orderId = $(this).data('id');
                const newStatus = $(this).data('status');
                const url = $(this).data('route');

                if (!confirm('Are you sure you want to update the status?')) return;

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: newStatus
                    },
                    success: function(response) {
                        showToast(response.message, response.success ? 'success' : 'danger');
                        // if(response.success) location.reload();
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseText);
                    }
                });
            });

            // Sync button
            $('#sync-btn').on('click', function() {
                const btn = $(this);
                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Syncing...');

                $.ajax({
                    url: '{{ route("wp.orders-sync") }}',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        alert(response.message);
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseText);
                    },
                    complete: function() {
                        btn.prop('disabled', false).html('<i class="fa fa-sync-alt me-1"></i> Sync Now');
                    }
                });
            });
        });
    </script>
    @endpush

</x-backend-layout>
