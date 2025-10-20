<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderAssign;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use PDF;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $stores = Store::where('status', true)->get();
        // Query orders
        $query = Order::query();

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        if ($request->filled('storeId') && is_numeric($request->storeId)) {
            $query->where('store_id', $request->storeId);
        }
        // Search by customer or product name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Search in Orders fields
                $q->where('invoice_no', 'like', "%{$search}%")
                ->orWhere('customer_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");

                // Search in related products
                $q->orWhereHas('items.product', function($q2) use ($search) {
                    $q2->where('product_name', 'like', "%{$search}%");
                });
            });
        }
        // Count total orders after filters
        $totalOrders = $query->count();
        // Get order counts by status after filters
        $orderCounts = (clone $query)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->map(fn($count) => (int) $count)
            ->toArray();

        $orders = $query->with('store')->orderBy('order_date', 'desc')->paginate(10);
        $employees = User::where('status', true)->get();

        return view('backend.orders.index', compact('orders', 'stores', 'orderCounts', 'totalOrders', 'employees'));
    }

    public function downloadInvoice(Order $order)
    {
        $order->load(['items.product']); // eager load relationships

        // If you want PDF download
        $pdf = PDF::loadView('backend.orders.single-invoice', compact('order'));
        return $pdf->download('Invoice-'.$order->invoice_no.'.pdf');

        // Or open in new tab for printing
        // return view('backend.orders.single-invoice', compact('order'));
    }
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|integer']);
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'Order status updated successfully!');
    }



    /**-------------------------------------------------------------
     * Bulk Update & Print
     * -------------------------------------------------------------
     */
    // Bulk Status Update
    public function statusBulkUpdate(Request $request)
    {
        $request->validate([
            'status' => 'required|integer',
            'order_ids' => 'required|array',
        ]);

        Order::whereIn('id', $request->order_ids)->update(['status' => $request->status]);

        return response()->json(['message' => 'Status updated successfully!']);
    }

    // Bulk Assign
    public function orderBulkAssign(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'order_ids' => 'required|array',
        ]);

        foreach ($request->order_ids as $orderId) {
            OrderAssign::updateOrCreate(
                ['order_id' => $orderId],
                ['employee_id' => $request->employee_id]
            );
        }

        return response()->json(['message' => 'Orders assigned successfully!']);
    }

    // Bulk Delete
    public function orderBulkDelete(Request $request)
    {
        $request->validate(['order_ids' => 'required|array']);
        Order::whereIn('id', $request->order_ids)->delete();

        return response()->json(['message' => 'Selected orders deleted successfully!']);
    }


    public function printBulkInvoice(Request $request)
    {
        $orderIds = $request->input('order_ids', []);
        if (is_string($orderIds)) {
            $orderIds = explode(',', $orderIds);
        }

        $orders = Order::with(['items.product'])->whereIn('id', $orderIds)->get();

        if ($orders->isEmpty()) {
            return response()->json(['error' => 'No orders found for invoice printing.'], 404);
        }

        // Render only the printable content (no layout)
        $html = view('backend.orders.bulk-invoice', compact('orders'))->render();

        return response($html);
    }


    public function printBulkLabel(Request $request)
    {
        $orderIds = $request->input('order_ids', []);
        if (is_string($orderIds)) {
            $orderIds = explode(',', $orderIds);
        }

        $orders = Order::with(['items.product'])->whereIn('id', $orderIds)->get();

        if ($orders->isEmpty()) {
            return response()->json(['error' => 'No orders found for label printing.'], 404);
        }

        $html = view('backend.orders.bulk-label', compact('orders'))->render();

        return response($html);
    }



    /**-------------------------------------------------------------
     * WOO-COMMERCE DATA
     * -------------------------------------------------------------
     */
    // Get Live Data
    public function wpOrderLive(Request $request)
    {
        $store = Store::where([['status', true], ['id', $request->store_id]])->first();

        if (!$store) {
            return response()->json(['error' => 'Store not found or inactive.'], 404);
        }

        try {
            $response = Http::withBasicAuth($store->api_key, $store->api_secret)
                ->get($store->base_url . '/wp-json/wc/v3/orders', [
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

    // Sync WooCommerce orders
    public function syncWpOrders(Request $request)
    {
        $store = Store::where([['status', true], ['id', $request->store_id]])->first();
        if (!$store) {
            return response()->json(['error' => 'Store not found or inactive.'], 404);
        }

        try {
            $page = 1;
            $perPage = 100; // WooCommerce max limit
            $syncedCount = 0;

            do {
                $response = Http::timeout(300) // increase timeout if 5 min
                    ->withBasicAuth($store->api_key, $store->api_secret)
                    ->get($store->base_url . '/wp-json/wc/v3/orders', [
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
                        $invoiceId = $store->prefix . '-' . $data['number'];
                        if (Order::where('invoice_no', $invoiceId)->exists()) {
                            continue;
                        }

                        $statusMap = [
                            'on-hold' => 0,
                            'completed' => 1,
                            'processing' => 2,
                            'pending' => 3,
                            'cancelled' => 4,
                            'checkout-draft' => 9,
                            'refunded' => 12,
                            'failed' => 16,
                        ];
                        $status = $statusMap[$data['status']] ?? 0;

                        $order = Order::create([
                            'store_id'      => $store->id ?? null,
                            'invoice_no'    => $invoiceId,
                            'order_date'    => Carbon::parse($data['date_created'] ?? now())->format('Y-m-d H:i:s'),
                            'customer_name' => trim(($data['billing']['first_name'] ?? '') . ' ' . ($data['billing']['last_name'] ?? '')),
                            'email'         => $data['billing']['email'] ?? null,
                            'phone'         => $data['billing']['phone'] ?? null,
                            'total'         => $data['total'] ?? 0,
                            'due'           => $data['total'] ?? 0,
                            'source'        => 'WP Direct',
                            'shipping'      => $data['shipping'] ?? [],
                            'order_data'    => $data,
                            'status'        => $status,
                        ]);

                        foreach ($data['line_items'] as $item) {
                            $product = Product::where('sku', $item['sku'] ?? null)->first();

                            if (!$product) {
                                $product = Product::create([
                                    'product_name' => $item['name'] ?? 'Unnamed Product',
                                    'sku'          => $item['sku'] ?? null,
                                    'description'  => 'Imported from WooCommerce',
                                    'price'        => $item['price'] ?? 0,
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
                        // Assign to a random active employee
                        $randomEmployee = User::where('status', true)->inRandomOrder()->first();

                        if ($randomEmployee) {
                            OrderAssign::create([
                                'order_id' => $order->id,
                                'employee_id' => $randomEmployee->id,
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
