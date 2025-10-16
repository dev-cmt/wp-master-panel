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

class OrderApiController extends Controller
{
    public function orderStore(Request $request)
    {
        $data = $request->all();

        // Optional: Get the sending store URL if provided
        $wp_base_url = $data['base_url'] ?? null;
        $domain = $wp_base_url ? parse_url($wp_base_url, PHP_URL_HOST) : null;

        $store = Store::where('base_url', 'like', "%$domain%")->first();

        if (!$store) {
            Log::warning('Store not found for domain: ' . $domain, $data);
            return response()->json(['success' => false, 'message' => 'Store not found'], 404);
        }

        DB::beginTransaction();
        try {
            // Generate invoice
            $invoiceId = ($store->prefix ?? '') . '-' . ($data['invoice_id'] ?? 'UNKNOWN');

            // Create order
            $order = Order::create([
                'store_id'      => $store->id,
                'invoice_no'    => $invoiceId,
                'order_date'    => Carbon::parse($data['order_date'] ?? now())->format('Y-m-d H:i:s'),
                'customer_name' => $data['customer_name'] ?? 'Unknown',
                'email'         => $data['customer_email'] ?? null,
                'phone'         => $data['customer_phone'] ?? null,
                'total'         => $data['total'] ?? 0,
                'source'        => $data['source'] ?? 'external',
                'shipping'      => $data['shipping'] ?? [],
                'order_data'    => $data,
                'status'        => $data['status'] ?? 3,
            ]);

            // Create order items
            if (!empty($data['get_products']) && is_array($data['get_products'])) {
                foreach ($data['get_products'] as $item) {
                    $productData = $item['get_product'] ?? [];

                    // Save product if it doesn't exist
                    $product = Product::firstOrCreate(
                        ['sku' => $productData['sku'] ?? null],
                        [
                            'product_name' => $productData['name'] ?? 'Unnamed',
                            'description'  => 'Imported from Laravel Application',
                            'price'        => $productData['price'] ?? 0,
                            'attribute'    => json_encode($item['get_attributes'] ?? []),
                            'image'        => $productData['product_image'] ?? null,
                            'status'       => true,
                        ]
                    );

                    // Create order item
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $product->id,
                        'quantity'   => $item['qty'] ?? 1,
                        'price'      => $item['price'] ?? 0,
                        'subtotal'   => ($item['price'] ?? 0) * ($item['qty'] ?? 1),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order received and stored successfully',
                'order_id' => $order->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order processing failed', ['error' => $e->getMessage(), 'payload' => $data]);
            return response()->json(['success' => false, 'message' => 'Processing failed'], 500);
        }
    }

}
