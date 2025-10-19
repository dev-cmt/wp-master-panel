<x-backend-layout title="Orders">
    <!-- Start::page-header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">Order Status By Product</p>
        </div>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboards</a></li>
                    <li class="breadcrumb-item" aria-current="page">Order Status</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Orders Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body table-responsive">
                    <table class="table table-hover table-bordered text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>SL.</th>
                                <th>Product Name</th>
                                <th>Total Orders</th>
                                <th class="text-success">Active</th>
                                <th class="text-info">Processing</th>
                                <th class="text-warning">NR1</th>
                                <th class="text-warning">NR2</th>
                                <th class="text-warning">Hold</th>
                                <th class="text-danger">Canceled</th>
                                <th class="text-secondary">Pending Payment</th>
                                <th class="text-primary">Pending Delivery</th>
                                <th class="text-primary">On Delivery</th>
                                <th class="text-warning">Courier Hold</th>
                                <th class="text-danger">Returned</th>
                                <th class="text-success">Delivered</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product['name'] }}</td>
                                <td>{{ $product['total_orders'] }}</td>
                                <td>{{ $product['active'] }}</td>
                                <td>{{ $product['processing'] }}</td>
                                <td>{{ $product['nr1'] }}</td>
                                <td>{{ $product['nr2'] }}</td>
                                <td>{{ $product['hold'] }}</td>
                                <td>{{ $product['canceled'] }}</td>
                                <td>{{ $product['pending_payment'] }}</td>
                                <td>{{ $product['pending_delivery'] }}</td>
                                <td>{{ $product['on_delivery'] }}</td>
                                <td>{{ $product['courier_hold'] }}</td>
                                <td>{{ $product['returned'] }}</td>
                                <td>{{ $product['delivered'] }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="15" class="text-center text-danger font-weight-bold">
                                    No Data Found!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</x-backend-layout>
