<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

class ReportController extends Controller
{
    public function employeeOrders(Request $request)
    {
        $stores = Store::where('status', true)->get();
        $employees = User::where('status', true)->get();

        $query = Order::query();

        $query->whereHas('assigns.employee', function ($q) use ($request) {
            $q->where('id', $request->employee_id);
        });

        // 🧩 Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🧩 Filter by store
        if ($request->filled('storeId')) {
            $query->where('store_id', $request->storeId);
        }

        // 🧩 Handle date filters
        $now = now();

        if ($request->filled('custom_range')) {
            switch ($request->custom_range) {
                case 'today':
                    $query->whereDate('order_date', $now->toDateString());
                    break;
                case 'yesterday':
                    $query->whereDate('order_date', $now->copy()->subDay()->toDateString());
                    break;
                case 'last_7_days':
                    $query->where('order_date', '>=', $now->copy()->subDays(7));
                    break;
                case 'this_month':
                    $query->whereYear('order_date', $now->year)
                        ->whereMonth('order_date', $now->month);
                    break;
                case 'last_month':
                    $query->whereYear('order_date', $now->copy()->subMonth()->year)
                        ->whereMonth('order_date', $now->copy()->subMonth()->month);
                    break;
                case 'last_6_months':
                    $query->where('order_date', '>=', $now->copy()->subMonths(6));
                    break;
            }
        }

        // 🧩 Custom start and end date range
        if ($request->filled('start_date')) {
            $query->where('order_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('order_date', '<=', $request->end_date);
        }

        // 🧩 Search filter (invoice, customer, phone, or product)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                ->orWhere('customer_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhereHas('items.product', function ($q2) use ($search) {
                    $q2->where('product_name', 'like', "%{$search}%");
                });
            });
        }

        // 🧩 Get total orders for current filter set
        $totalOrders = (clone $query)->count();

        // 🧩 Orders count by status
        $orderCounts = (clone $query)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->map(fn($count) => (int) $count)
            ->toArray();

        // 🧩 Final paginated results with relations (and keep query string)
        $orders = $query->with([
                'store',
                'assigns.employee',
                'items.product'
            ])
            ->orderBy('order_date', 'desc')
            ->paginate(10)
            ->withQueryString(); // ✅ Keep filters on pagination

        // 🧩 Return view
        return view('backend.reports.employee-orders', compact(
            'orders',
            'stores',
            'employees',
            'totalOrders',
            'orderCounts'
        ));
    }

    public function orderStatusP()
    {
        // Fetch all products
        $products = Product::all();

        $data = [];

        foreach ($products as $product) {
            $orders = Order::whereHas('items', function($q) use ($product) {
                $q->where('product_id', $product->id);
            });

            $data[] = [
                'name' => $product->product_name,
                'total_orders' => $orders->count(),
                'active' => $orders->whereIn('status', [1,2,3])->count(),
                'processing' => $orders->where('status', 2)->count(),
                'nr1' => $orders->where('status', 5)->count(),
                'nr2' => $orders->where('status', 8)->count(),
                'hold' => $orders->where('status', 0)->count(),
                'canceled' => $orders->where('status', 4)->count(),
                'pending_payment' => $orders->where('status', 3)->count(),
                'pending_delivery' => $orders->where('status', 5)->count(),
                'on_delivery' => $orders->where('status', 6)->count(),
                'courier_hold' => $orders->where('status', 8)->count(),
                'returned' => $orders->where('status', 7)->count(),
                'delivered' => $orders->where('status', 1)->count(),
            ];
        }

        // Sort products by total_orders descending
        $data = collect($data)->sortByDesc('total_orders')->values()->all();

        return view('backend.reports.orders-status-product', [
            'products' => $data,
        ]);
    }

    public function ordersProduct(Request $request)
    {
        $stores = Store::where('status', true)->get();
        $employees = User::where('status', true)->get();
        $products = Product::where('status', true)->get();

        $query = Order::query();

        // 🧩 Filter by product
        $query->whereHas('items.product', function ($q) use ($request) {
            $q->where('id', $request->product_id);
        });

        // 🧩 Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🧩 Filter by store
        if ($request->filled('storeId')) {
            $query->where('store_id', $request->storeId);
        }

        // 🧩 Handle date filters
        $now = now();

        if ($request->filled('custom_range')) {
            switch ($request->custom_range) {
                case 'today':
                    $query->whereDate('order_date', $now->toDateString());
                    break;
                case 'yesterday':
                    $query->whereDate('order_date', $now->copy()->subDay()->toDateString());
                    break;
                case 'last_7_days':
                    $query->where('order_date', '>=', $now->copy()->subDays(7));
                    break;
                case 'this_month':
                    $query->whereYear('order_date', $now->year)
                        ->whereMonth('order_date', $now->month);
                    break;
                case 'last_month':
                    $query->whereYear('order_date', $now->copy()->subMonth()->year)
                        ->whereMonth('order_date', $now->copy()->subMonth()->month);
                    break;
                case 'last_6_months':
                    $query->where('order_date', '>=', $now->copy()->subMonths(6));
                    break;
            }
        }

        // 🧩 Custom start and end date range
        if ($request->filled('start_date')) {
            $query->where('order_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('order_date', '<=', $request->end_date);
        }

        // 🧩 Search filter (invoice, customer, phone, or product)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                ->orWhere('customer_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhereHas('items.product', function ($q2) use ($search) {
                    $q2->where('product_name', 'like', "%{$search}%");
                });
            });
        }

        // 🧩 Get total orders for current filter set
        $totalOrders = (clone $query)->count();

        // 🧩 Orders count by status
        $orderCounts = (clone $query)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->map(fn($count) => (int) $count)
            ->toArray();

        // 🧩 Final paginated results with relations (and keep query string)
        $orders = $query->with([
                'store',
                'assigns.employee',
                'items.product'
            ])
            ->orderBy('order_date', 'desc')
            ->paginate(10)
            ->withQueryString(); // ✅ Keep filters on pagination

        // 🧩 Return view
        return view('backend.reports.orders-product', compact(
            'orders',
            'stores',
            'employees',
            'products',
            'totalOrders',
            'orderCounts'
        ));
    }
}
