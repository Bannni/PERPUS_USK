<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * Update order status with Concurrency Locking & Stock ACC Management.
     * Rule: Stock only decrements when order is ACC'd ('processing' or 'completed').
     * If order is cancelled, deducted stock is automatically restored.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,processing,completed,success,cancelled'],
        ]);

        $newStatus = $validated['status'];
        if ($newStatus === 'success') {
            $newStatus = 'completed';
        }

        try {
            DB::beginTransaction();

            $order = Order::with(['items.book.stock', 'book.stock'])
                ->lockForUpdate()
                ->findOrFail($id);

            $oldStatus = $order->status;
            $isApprovedStatus = in_array($newStatus, ['processing', 'completed', 'success']);
            $isCancelledOrPending = in_array($newStatus, ['cancelled', 'pending']);

            // Helper to get all order items
            $items = $order->items;
            if ($items->isEmpty() && $order->book_id) {
                // Fallback for single-book order without order_items records
                $items = collect([
                    (object) [
                        'book_id' => $order->book_id,
                        'quantity' => $order->quantity,
                        'book' => $order->book,
                    ]
                ]);
            }

            // Case 1: Order is being ACC'd (approved) and stock has NOT been deducted yet
            if ($isApprovedStatus && !$order->stock_deducted) {
                // Validate and decrement stock with pessimistic lock
                foreach ($items as $item) {
                    $stock = Stock::where('book_id', $item->book_id)
                        ->lockForUpdate()
                        ->first();

                    $availableStock = $stock ? $stock->quantity : 0;
                    $bookTitle = $item->book ? $item->book->title : "ID #{$item->book_id}";

                    if ($availableStock < $item->quantity) {
                        DB::rollBack();
                        return redirect()->route('admin.dashboard', ['tab' => 'orders'])
                            ->with('error', "Gagal ACC pesanan #{$order->order_code}! Stok buku '{$bookTitle}' tidak mencukupi (Tersisa: {$availableStock}, Dibutuhkan: {$item->quantity}).");
                    }

                    $stock->decrement('quantity', $item->quantity);
                }

                $order->stock_deducted = true;
            }

            // Case 2: Order was previously ACC'd, but now cancelled or set back to pending -> RESTORE STOCK
            elseif ($isCancelledOrPending && $order->stock_deducted) {
                foreach ($items as $item) {
                    $stock = Stock::where('book_id', $item->book_id)
                        ->lockForUpdate()
                        ->first();

                    if ($stock) {
                        $stock->increment('quantity', $item->quantity);
                    }
                }

                $order->stock_deducted = false;
            }

            // Update order status
            $order->status = $newStatus;
            $order->save();

            DB::commit();

            $statusLabels = [
                'pending' => 'PENDING (Menunggu ACC)',
                'processing' => 'PROCESSING (ACC / Sedang Diproses)',
                'completed' => 'COMPLETED (Selesai)',
                'cancelled' => 'CANCELLED (Dibatalkan)',
            ];

            $msg = "Status pesanan #{$order->order_code} berhasil diperbarui menjadi " . ($statusLabels[$newStatus] ?? strtoupper($newStatus)) . "!";
            if ($isApprovedStatus && $order->stock_deducted) {
                $msg .= " Kuantitas stok buku telah resmi dikurangi.";
            } elseif ($newStatus === 'cancelled') {
                $msg .= " Kuantitas stok buku telah dikembalikan ke inventaris.";
            }

            return redirect()->route('admin.dashboard', ['tab' => 'orders'])->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.dashboard', ['tab' => 'orders'])
                ->with('error', 'Terjadi kesalahan saat memperbarui status pesanan: ' . $e->getMessage());
        }
    }
}

