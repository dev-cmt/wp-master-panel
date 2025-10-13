<x-backend-layout title="Orders">

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Woo Commerce Live</h1>
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
        <div class="card-body p-0">
            <div class="card-header justify-content-between">
                <div class="card-title">Order List</div>
                <button type="button" id="sync-btn" class="btn btn-primary btn-sm">
                    <i class="fa fa-sync-alt me-1"></i> Sync Now
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
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
                            <tr>
                                <td>
                                    <strong>#{{ $order['number'] ?? $order['id'] }}</strong><br>
                                    <small class="text-muted">ID: {{ $order['id'] }}</small>
                                </td>
                                <td>
                                    {{ $order['billing']['first_name'] }} {{ $order['billing']['last_name'] }}<br>
                                    <small class="text-muted"><i class="fa fa-phone me-1"></i>{{ $order['billing']['phone'] ?? 'N/A' }}</small><br>
                                    <small class="text-muted"><i class="fa fa-envelope me-1"></i>{{ $order['billing']['email'] ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($order['date_created'])->format('M d, Y') }}<br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($order['date_created'])->format('g:i A') }}</small>
                                </td>
                                <td>
                                    <strong class="text-success">${{ number_format($order['total'], 2) }}</strong><br>
                                    <small class="text-muted">{{ strtoupper($order['currency']) }}</small>
                                </td>
                                <td>
                                    @php
                                        $color = match($order['status']) {
                                            'completed' => 'success',
                                            'processing' => 'info',
                                            'pending' => 'warning',
                                            'cancelled' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ ucfirst($order['status']) }}</span>
                                </td>
                                <td>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($order['line_items'] as $item)
                                            <li><i class="fa fa-cube me-1"></i>{{ $item['name'] }} × {{ $item['quantity'] }}</li>
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
            @include('backend.pagination.paginate', ['paginator' => $orders])
        </div>
    </div>

    @push('js')
    <script>
        document.getElementById('sync-btn').addEventListener('click', function() {
            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Syncing...';

            fetch('{{ route('wp.orders-sync') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                location.reload();
            })
            .catch(err => alert('Error: ' + err.message))
            .finally(() => {
                this.disabled = false;
                this.innerHTML = '<i class="fa fa-sync-alt me-1"></i> Sync Now';
            });
        });
    </script>
    @endpush

</x-backend-layout>
