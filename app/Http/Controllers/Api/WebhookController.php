<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\WpOrder;
use App\Models\WpOrderItem;

class WebhookController extends Controller
{
    public function orderStore(Request $request)
    {
        // 1️⃣ Verify WooCommerce webhook signature
        $secret = $request->header('x-wc-webhook-signature');
        $payload = $request->getContent();
        $webhookSecret = env('WC_WEBHOOK_SECRET'); // set in .env

        $hash = base64_encode(hash_hmac('sha256', $payload, $webhookSecret, true));

        if (!hash_equals($hash, $secret)) {
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
        }

        $data = $request->all();

        // 2️⃣ Get active store
        $store = Store::where([['status', true], ['id', 1]])->first();
        if (!$store) {
            return response()->json(['success' => false, 'message' => 'Store not found or inactive.'], 404);
        }

        // 3️⃣ Generate local invoice ID
        $prefix = $store->prefix ?? 'STORE'; // fallback prefix
        $invoiceId = $prefix . '-' . ($data['number'] ?? $data['id']);

        // 4️⃣ Map WooCommerce status to your local numeric status
        $wcStatus = $data['status'] ?? 'pending';
        $statusMap = [
            'on-hold'        => 0,
            'completed'      => 1,
            'processing'     => 2,
            'pending'        => 3,
            'cancelled'      => 4,
            'checkout-draft' => 9,
            'refunded'       => 12,
            'failed'         => 16,
        ];
        $status = $statusMap[$wcStatus] ?? 3; // default: pending

        // 5️⃣ Create or update order
        $order = WpOrder::updateOrCreate(
            ['wp_order_id' => $data['id']], // prevent duplicate WooCommerce orders
            [
                'order_number'  => $data['number'] ?? null,
                'invoice_no'    => $invoiceId,
                'customer_name' => trim(data_get($data, 'billing.first_name', '') . ' ' . data_get($data, 'billing.last_name', '')),
                'email'         => data_get($data, 'billing.email', null),
                'phone'         => data_get($data, 'billing.phone', null),
                'total'         => $data['total'] ?? 0,
                'billing'       => json_encode($data['billing'] ?? []),
                'shipping'      => json_encode($data['shipping'] ?? []),
                'order_data'    => json_encode($data),
                'status'        => $status,
            ]
        );

        // 6️⃣ Insert or update order items
        foreach ($data['line_items'] ?? [] as $item) {
            WpOrderItem::updateOrCreate(
                [
                    'wp_order_id' => $order->id,
                    'product_id'  => $item['product_id'] ?? null,
                ],
                [
                    'product_name' => $item['name'] ?? null,
                    'sku'          => $item['sku'] ?? null,
                    'quantity'     => $item['quantity'] ?? 0,
                    'subtotal'     => $item['subtotal'] ?? 0,
                    'total'        => $item['total'] ?? 0,
                    'meta'         => json_encode($item['meta_data'] ?? []),
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Order processed successfully',
            'invoice_no' => $invoiceId,
        ]);
    }


}
