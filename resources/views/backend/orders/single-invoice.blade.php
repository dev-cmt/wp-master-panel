<!DOCTYPE html>
<html lang="bn, en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->invoice_no }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td, .header-table th,
        .info-table td, .info-table th,
        .products-table td, .products-table th,
        .totals-table td {
            padding: 5px;
        }
        .products-table th {
            background-color: #2c3e50;
            color: #fff;
        }
        .products-table tr:nth-child(even) td {
            background-color: #f8f9fa;
        }
        .totals-table {
            width: 300px;
            margin-left: auto;
        }
        .totals-table td {
            border-top: 1px solid #ccc;
        }
        .totals-table tr:last-child td {
            font-weight: bold;
            background-color: #ecf0f1;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }
        .section-title {
            font-weight: bold;
            color: #2c3e50;
            margin-top: 15px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
@php
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
@endphp

<!-- Header -->
<table class="header-table">
    <tr>
        <td>
            @if(!empty($settings->logo))
                <img src="{{ public_path($settings->logo) }}" alt="Logo" style="max-width:180px;">
            @else
                <h2>{{ $settings['company_name'] ?? 'Your Company' }}</h2>
            @endif
            <p>{{ $settings['address'] ?? '' }}</p>
            <p>Phone: {{ $settings['phone'] ?? '' }}</p>
            <p>Email: {{ $settings['email'] ?? '' }}</p>
        </td>
        <td class="text-right">
            <div class="invoice-title">INVOICE</div>
            <p>#{{ $order->invoice_no }}</p>
            <p>Order Date: {{ \Carbon\Carbon::parse($order->order_date)->format('F d, Y') }}</p>
            <p>Due Date: {{ \Carbon\Carbon::parse($order->order_date)->addDays(7)->format('F d, Y') }}</p>
            @if($order->get_courier)
            <p>Courier: {{ $order->get_courier->courier_name }}</p>
            @endif
            @if($order->courier_inv_no)
            <p>Tracking No: {{ $order->courier_inv_no }}</p>
            @endif
        </td>
    </tr>
</table>

<!-- Customer & Order Info -->
<table class="info-table" border="0" style="margin-top:20px;">
    <tr>
        <td>
            <strong class="section-title">Bill To</strong><br>
            @php $shipping = is_array($order->shipping) ? (object)$order->shipping : (object)($order->shipping ?? []); @endphp
            {{ $shipping->first_name ?? $order->customer_name }} {{ $shipping->last_name ?? '' }}<br>
            Phone: {{ $shipping->phone ?? $order->phone ?? 'N/A' }}<br>
            Email: {{ $shipping->email ?? $order->email ?? 'N/A' }}<br>
            Address: {{ $shipping->address_1 ?? $order->customer_address ?? 'N/A' }}<br>
            {{ $shipping->city ?? '' }} {{ $shipping->state ?? '' }} {{ $shipping->postcode ?? '' }} {{ $shipping->country ?? '' }}
        </td>
        <td class="text-right">
            <strong class="section-title">Order Details</strong><br>
            Status: {{ $statusLabels[$order->status] ?? ucfirst($order->status ?? 'N/A') }}<br>
            Payment Method: {{ $order->payment_method ?? 'N/A' }}<br>
            Items Count: {{ $order->items->count() }}<br>
            Shipping Method: {{ $order->shipping_method ?? 'Standard' }}
        </td>
    </tr>
</table>

<!-- Products -->
<p class="section-title">Order Items</p>
<table class="products-table" border="1">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th>Product Description</th>
            <th class="text-center">Quantity</th>
            <th class="text-center">Unit Price</th>
            <th class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>
        @php
            $subtotal = 0;
            $counter = 1;
        @endphp
        @foreach($order->items as $item)
            @php
                $itemTotal = $item->quantity * $item->price;
                $subtotal += $itemTotal;
            @endphp
        <tr>
            <td class="text-center">{{ $counter++ }}</td>
            <td>
                <strong>{{ $item->product->product_name ?? 'N/A' }}</strong>
                @if($item->attributes)
                    <br><small>
                        @foreach(json_decode($item->attributes, true) as $key => $attr)
                            {{ $key }}: {{ $attr }}{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    </small>
                @endif
            </td>
            <td class="text-center">{{ $item->quantity }}</td>
            <td class="text-center">Tk {{ number_format($item->price,2) }}</td>
            <td class="text-right">Tk {{ number_format($itemTotal,2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Totals -->
<table class="totals-table">
    <tr>
        <td>Subtotal:</td>
        <td class="text-right">Tk {{ number_format($subtotal,2) }}</td>
    </tr>
    <tr>
        <td>Shipping:</td>
        <td class="text-right">Tk {{ number_format($order->shipping_cost ?? 0,2) }}</td>
    </tr>
    @if($order->discount > 0)
    <tr>
        <td>Discount:</td>
        <td class="text-right">- Tk {{ number_format($order->discount ?? 0,2) }}</td>
    </tr>
    @endif
    @if($order->tax > 0)
    <tr>
        <td>Tax:</td>
        <td class="text-right">Tk {{ number_format($order->tax ?? 0,2) }}</td>
    </tr>
    @endif
    <tr>
        <td>Grand Total:</td>
        <td class="text-right">Tk {{ number_format($order->total,2) }}</td>
    </tr>
    @if($order->paid > 0)
    <tr>
        <td>Amount Paid:</td>
        <td class="text-right">Tk {{ number_format($order->paid,2) }}</td>
    </tr>
    <tr>
        <td>Balance Due:</td>
        <td class="text-right">Tk {{ number_format($order->due,2) }}</td>
    </tr>
    @endif
</table>

<!-- Notes -->
@if(!empty($order->order_note))
<p class="section-title">Order Notes</p>
<p>{{ $order->order_note }}</p>
@endif

<!-- Footer -->
<p style="text-align:center; margin-top:30px;">
    Thank you for your business!<br>
    <strong>{{ $settings['company_name'] ?? 'Your Company' }}</strong> |
    {{ $settings['phone'] ?? '' }} |
    {{ $settings['email'] ?? '' }}
</p>

</body>
</html>
