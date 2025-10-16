<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use Carbon\Carbon;

class WebhookController extends Controller
{
    public function orderStore(Request $request)
    {
        $data = $request->all();

        // Safely get domain from payload
        $wp_base_url = $data['_links']['collection'][0]['href'] ?? null;
        $domain = $wp_base_url ? parse_url($wp_base_url, PHP_URL_HOST) : null; // e.g. skytechsolve.com

        $store = Store::where('base_url', 'like', "%$domain%")->first();

        if (!$store) {
            Log::warning('Store not found for domain: ' . $domain);
            return response()->json(['success' => false, 'message' => 'Store not found'], 404);
        }

        /**-----------------------------------------------------
         * Security Perpose Use This Code
         * -----------------------------------------------------
         */
        // Verify signature
        // $secret = $request->header('x-wc-webhook-signature');
        // $payload = $request->getContent();
        // $webhookSecret = $store->custom_secret ?? 'wC4x8pR9qT2vS6mY3nL7kZ1bD5fG8hJ0';

        // $hash = base64_encode(hash_hmac('sha256', $payload, $webhookSecret, true));

        // if (!hash_equals($hash, $secret)) {
        //     Log::error('Invalid webhook signature');
        //     return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
        // }

        DB::beginTransaction();
        try {
            if (empty($data['number'])) {
                Log::warning('Order number missing', ['payload' => $data]);
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Order number missing'], 400);
            }

            $invoiceId = ($store->prefix ?? '') . '-' . ($data['number'] ?? 'UNKNOWN');
            // Create order
            $order = Order::create([
                'store_id'      => $store->id ?? null,
                'invoice_no'    => $invoiceId,
                'order_date'    => Carbon::parse($data['date_created'] ?? now())->format('Y-m-d H:i:s'),
                'customer_name' => trim(($data['billing']['first_name'] ?? '') . ' ' . ($data['billing']['last_name'] ?? '')),
                'email'         => $data['billing']['email'] ?? null,
                'phone'         => $data['billing']['phone'] ?? null,
                'total'         => $data['total'] ?? 0,
                'source'        => 'Wordpress',
                'shipping'      => $data['shipping'] ?? [],
                'order_data'    => $data,
                'status'        => 3,
            ]);

            // Create order items
            if (isset($data['line_items']) && is_array($data['line_items'])) {
                foreach ($data['line_items'] as $item) {
                    $product = Product::where('sku', $item['sku'] ?? null)->first();

                    if (!$product) {
                        $product = Product::create([
                            'product_name' => $item['name'] ?? 'Unnamed Product',
                            'sku'          => $item['sku'] ?? null,
                            'description'  => 'Imported from WooCommerce',
                            'price'        => $item['price'] ?? 0,
                            'stock_qty'    => 0,
                            'image'        => $item['image']['src'] ?? null,
                            'attribute'    => json_encode($item['attributes'] ?? []),
                            'status'       => true,
                        ]);
                    }

                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $product->id,
                        'quantity'   => $item['quantity'] ?? 0,
                        'price'      => $item['price'] ?? 0,
                        'subtotal'   => $item['subtotal'] ?? 0,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Order processed']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order processing failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Processing failed'], 500);
        }
    }
}
