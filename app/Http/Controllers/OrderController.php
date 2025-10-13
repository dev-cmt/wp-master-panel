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

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = WpOrder::query();

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(20);

        return view('backend.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = WpOrder::find($id);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $order->status = $request->status;
        $order->save();

        return response()->json(['success' => true, 'message' => 'Order status updated successfully']);
    }


}
