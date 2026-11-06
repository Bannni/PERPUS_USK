<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'stock_deducted')) {
                $table->boolean('stock_deducted')->default(false)->after('status');
            }
        });

        // Set existing completed/processing orders as stock_deducted = true
        DB::table('orders')
            ->whereIn('status', ['completed', 'processing'])
            ->update(['stock_deducted' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'stock_deducted')) {
                $table->dropColumn('stock_deducted');
            }
        });
    }
};
