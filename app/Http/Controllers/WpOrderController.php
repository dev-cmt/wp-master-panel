<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Services\WooService;
use App\Models\WpOrder;
use App\Models\WpOrderItem;
use App\Models\Store;

class WpOrderController extends Controller
{

    public function wpOrderLive(Request $request)
    {
        $store = Store::where([['status', true], ['id', 1]])->first();

        if (!$store) {
            return response()->json(['error' => 'Store not found or inactive.'], 404);
        }

        try {
            $response = Http::withBasicAuth($store->api_key, $store->api_secret)
                ->get($store->base_url . $store->ep_order_store, [
                    'per_page' => 50, // max per request
                    'orderby' => 'date',
                    'order' => 'desc',
                ]);

            if ($response->failed()) {
                return response()->json([
                    'error' => 'Failed to fetch orders from WooCommerce.',
                    'details' => $response->body(),
                ], 500);
            }

            $allOrders = $response->json(); // array of orders

            // Manual pagination
            $page = $request->get('page', 1);
            $perPage = 10;
            $currentOrders = array_slice($allOrders, ($page - 1) * $perPage, $perPage);

            $orders = new LengthAwarePaginator(
                $currentOrders,
                count($allOrders),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('backend.orders.wp-orders-live', compact('orders'));

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong while connecting to WooCommerce.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Sync WooCommerce orders into local DB
    public function syncOrders()
    {
        $store = Store::where([['status', true], ['id', 1]])->first();
        if (!$store) {
            return response()->json(['error' => 'Store not found or inactive.'], 404);
        }

        try {
            $page = 1;
            $perPage = 100; // WooCommerce max limit
            $syncedCount = 0;

            do {
                $response = Http::withBasicAuth($store->api_key, $store->api_secret)
                    ->get($store->base_url . $store->ep_order_store, [
                        'per_page' => $perPage,
                        'page'     => $page,
                        'orderby'  => 'date',
                        'order'    => 'asc',
                    ]);

                if ($response->failed()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'WooCommerce API request failed.'
                    ]);
                }

                $orders = $response->json();

                if (empty($orders)) {
                    break; // no more orders
                }

                DB::transaction(function () use ($orders, $store, &$syncedCount) {
                    foreach ($orders as $data) {
                        // Skip if order with the same invoice already exists
                        $localInvoiceId = $store->prefix . '-' . $data['number'];
                        if (WpOrder::where('invoice_no', $localInvoiceId)->exists()) {
                            continue;
                        }

                        // 🔁 WooCommerce → Local Status Mapping
                        $statusMap = [
                            'on-hold'         => 0,
                            'completed'       => 1,
                            'processing'      => 2,
                            'pending'         => 3,
                            'cancelled'       => 4,
                            'checkout-draft'  => 9,
                            'refunded'        => 12,
                            'failed'          => 16,
                        ];
                        $status = $statusMap[$data['status']] ?? 0;

                        // 🧾 Create new local order
                        $order = WpOrder::create([
                            'wp_order_id'   => $data['id'],
                            'invoice_no'    => $localInvoiceId,
                            'customer_name' => trim(($data['billing']['first_name'] ?? '') . ' ' . ($data['billing']['last_name'] ?? '')),
                            'email'         => $data['billing']['email'] ?? null,
                            'phone'         => $data['billing']['phone'] ?? null,
                            'total'         => $data['total'] ?? 0,
                            'billing'       => json_encode($data['billing']),
                            'shipping'      => json_encode($data['shipping']),
                            'order_data'    => json_encode($data),
                            'status'        => $status,
                        ]);

                        // 🧩 Insert order items
                        foreach ($data['line_items'] as $item) {
                            WpOrderItem::create([
                                'wp_order_id'  => $order->id,
                                'product_id'   => $item['product_id'],
                                'product_name' => $item['name'],
                                'sku'          => $item['sku'] ?? null,
                                'quantity'     => $item['quantity'] ?? 0,
                                'subtotal'     => $item['subtotal'] ?? 0,
                                'total'        => $item['total'] ?? 0,
                                'meta'         => json_encode($item['meta_data'] ?? []),
                            ]);
                        }

                        $syncedCount++;
                    }
                });

                $page++;
            } while (count($orders) === $perPage); // Fetch next page if full

            if ($syncedCount === 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'All orders already exist. Nothing to sync.'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Orders synced successfully! Total new orders: $syncedCount"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }




}
