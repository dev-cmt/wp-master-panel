<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::latest()->paginate(10);
        return view('backend.stores.index', compact('stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'base_url' => 'nullable|url',
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
            'ep_order_store' => 'nullable|string',
            'ep_order_update' => 'nullable|string',
            'ep_order_status' => 'nullable|string',
            'ep_order_delete' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        Store::create($request->all());

        return redirect()->route('stores.index')->with('success', 'Store created successfully!');
    }

    public function update(Request $request)
    {
        $store = Store::findOrFail($request->id);

        $request->validate([
            'name' => 'required|string|max:255',
            'base_url' => 'nullable|url',
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
            'ep_order_store' => 'nullable|string',
            'ep_order_update' => 'nullable|string',
            'ep_order_status' => 'nullable|string',
            'ep_order_delete' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $store->update($request->all());

        return redirect()->route('stores.index')->with('success', 'Store updated successfully!');
    }

    public function destroy($id)
    {
        $store = Store::findOrFail($id);
        $store->delete();

        return redirect()->route('stores.index')->with('success', 'Store deleted successfully!');
    }
}
