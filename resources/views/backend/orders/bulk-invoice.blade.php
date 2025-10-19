<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bulk Invoice Print</title>
<style>
    body { font-family: sans-serif; font-size: 14px; margin: 0; }
    @media print { body { zoom: 80%; } .pagebreak { page-break-after: always; } }

    .invoice-card { border:1px solid #000; border-radius:5px; margin:10px auto; padding:10px 15px; width:95%; }
    .header { display:flex; justify-content:space-between; border-bottom:1px solid #000; padding-bottom:10px; margin-bottom:10px; }
    .left-header, .middle-header, .right-header { padding:10px; }
    .left-header { width:30%; border-right:1px solid #000; }
    .middle-header { width:35%; border-right:1px solid #000; }
    .right-header { width:34%; text-align:left; }
    .product-table { width:100%; border-collapse:collapse; margin-top:10px; }
    .product-table th, .product-table td { border:1px solid #000; padding:5px; }
    .product-table th { background:#f8f8f8; }
    .note { margin-top:5px; font-style:italic; }
</style>
</head>
<body>
@php
    $count = count($orders);
    $i = 1;
    $perPage = 3;
    $k = 0;
@endphp

@foreach($orders as $order)
    @php
        $shipping = is_array($order->shipping) ? (object)$order->shipping : (object)($order->shipping ?? []);
    @endphp

    <p style="margin-left:15px; margin-bottom:0;">{{ $i++ }} / {{ $count }}</p>

    <div class="invoice-card">
        <div class="header">
            <div class="left-header">
                @if(!empty($settings->logo))
                    <img src="{{ asset($settings->logo) }}" alt="Logo" style="max-width:150px;">
                @endif
                <p style="margin:5px 0;">{{ $settings['address'] ?? '' }}</p>
                <p style="margin:0;"><strong>Mobile:</strong> {{ $settings['phone'] ?? '' }}</p>
                <p style="margin:0;"><strong>Email:</strong> {{ $settings['email'] ?? '' }}</p>
            </div>

            <div class="middle-header">
                <h3 style="margin:0 0 5px 0; text-decoration:underline;">Customer Info</h3>
                <p><strong>Name:</strong> {{ $shipping->first_name ?? $order->customer_name }} {{ $shipping->last_name ?? '' }}</p>
                <p><strong>Phone:</strong> {{ !empty($shipping->phone) ? $shipping->phone : $order->phone }} </p>
                <p><strong>Address:</strong>
                    {{ $shipping->address_1 ?? $order->customer_address }}{{ $shipping->city ? ', '.$shipping->city : '' }}
                    {{ $shipping->state ? ', '.$shipping->state : '' }}{{ $shipping->postcode ? ', '.$shipping->postcode : '' }}
                    {{ $shipping->country ? ', '.$shipping->country : '' }}
                </p>
            </div>

            <div class="right-header">
                <h3>Invoice #{{ $order->invoice_no }}</h3>
                <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('d M, Y') }}</p>
                @if($order->get_courier)
                    <p><strong>Courier:</strong> {{ $order->get_courier->courier_name }}</p>
                @endif
                @if($order->courier_inv_no)
                    <p><strong>Courier Inv.:</strong> {{ $order->courier_inv_no }}</p>
                @endif
            </div>
        </div>

        <table class="product-table">
            <thead>
                <tr>
                    <th>SL #</th>
                    <th>Product(s)</th>
                    <th>Qty</th>
                    <th style="text-align:right;">Price</th>
                </tr>
            </thead>
            <tbody>
                @php $ii = 1; @endphp
                @foreach($order->items as $item)
                    <tr>
                        <td style="text-align:center;">{{ $ii++ }}</td>
                        <td>{{ $item->product->product_name ?? 'N/A' }}</td>
                        <td style="text-align:center;">{{ $item->quantity }}</td>
                        <td style="text-align:right;">{{ $settings['currency_symbol'] ?? '৳' }} {{ number_format($item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if(!empty($order->order_note))
            <p class="note">Note: {{ $order->order_note }}</p>
        @endif
    </div>

    <hr style="margin:15px 0; border:1px dashed red;">
    @php $k++; @endphp
    @if($k % $perPage == 0)
        <div class="pagebreak"></div>
    @endif
@endforeach

<script>
    window.onload = function() {
        window.print();
        window.close();
    }
</script>
</body>
</html>
