<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bulk Label Print</title>
<style>
    body { font-family:sans-serif; font-size:14px; margin:0; }
    @media print { body { zoom:85%; } .pagebreak { page-break-after: always; } }
    .label-card { border:1px solid #000; border-radius:6px; margin:10px auto; width:94%; padding:10px 15px; }
    .header { display:flex; justify-content:space-between; border-bottom:1px solid #000; padding-bottom:5px; margin-bottom:8px; }
    .header .left p { margin:2px 0; font-size:13px; }
    .header .right { text-align:right; }
    .product-table { width:100%; border-collapse:collapse; font-size:13px; margin-top:10px; }
    .product-table th, .product-table td { border:1px solid #000; padding:5px; }
    .product-table th { background:#f8f8f8; }
    .summary td { text-align:right; padding:4px 8px; }
    .barcode { margin-top:10px; text-align:center; }
</style>
</head>
<body>
@php $count = count($orders); $i=1; @endphp

@foreach($orders as $order)
    @php $shipping = is_array($order->shipping) ? (object)$order->shipping : json_decode($order->shipping); @endphp

    <p style="margin-left:20px; margin-bottom:0;">{{ $i++ }} / {{ $count }}</p>

    <div class="label-card">
        <div class="header">
            <div class="left">
                <p><strong>Name:</strong> {{ $shipping->first_name ?? '' }} {{ $shipping->last_name ?? '' }}</p>
                <p><strong>Phone:</strong> {{ !empty($shipping->phone) ? $shipping->phone : $order->phone }} </p>
                <p><strong>Address:</strong>
                    {{ $shipping->address_1 ?? '' }},
                    {{ $shipping->city ?? '' }},
                    {{ $shipping->state ?? '' }},
                    {{ $shipping->postcode ?? '' }},
                    {{ $shipping->country ?? '' }}
                </p>
            </div>
            <div class="right">
                <p><strong>Invoice:</strong> #{{ $order->invoice_no }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('d M, Y') }}</p>
                <p><strong>Status:</strong>
                    @if($order->status == 'paid') <span style="color:green">Paid</span>
                    @elseif($order->status == 'unpaid') <span style="color:red">Unpaid</span>
                    @else <span style="color:orange">{{ ucfirst($order->status) }}</span>
                    @endif
                </p>
            </div>
        </div>

        <table class="product-table">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th style="text-align:right;">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $key => $item)
                    <tr>
                        <td style="text-align:center">{{ $key+1 }}</td>
                        <td>{{ $item->product->product_name ?? 'N/A' }}</td>
                        <td style="text-align:center">{{ $item->quantity }}</td>
                        <td style="text-align:right">{{ number_format($item->price,2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="summary">
                <tr>
                    <td colspan="3"><strong>Sub Total</strong></td>
                    <td>{{ number_format($order->total,2) }}</td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Paid</strong></td>
                    <td>{{ number_format($order->paid,2) }}</td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Due</strong></td>
                    <td style="color:#d00;font-weight:bold;">{{ number_format($order->due,2) }}</td>
                </tr>
            </tfoot>
        </table>

        @if(class_exists('Milon\Barcode\DNS1D'))
            <div class="barcode">
                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($order->invoice_no, 'C128') }}" alt="barcode" height="40">
            </div>
        @endif
    </div>

    <div class="pagebreak"></div>
@endforeach

    <script>
        window.onload = () => {
            window.print();
            window.close();
        };
    </script>
</body>
</html>
