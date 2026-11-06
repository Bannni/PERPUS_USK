<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index()
    {
        return redirect()->route('admin.dashboard', ['tab' => 'orders']);
    }

    /**
     * Update order status (BNSP Requirement: completed, cancelled, processing, pending).
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,processing,completed,cancelled'],
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->route('admin.dashboard', ['tab' => 'orders'])->with('success', "Status pesanan #{$order->order_code} berhasil diperbarui menjadi " . strtoupper($order->status) . "!");
    }
}
