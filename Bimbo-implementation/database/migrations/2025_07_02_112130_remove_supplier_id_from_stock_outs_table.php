<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSupplierIdFromStockOutsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stock_outs', function (Blueprint $table) {
            if (Schema::hasColumn('stock_outs', 'supplier_id')) {
                try {
                    $table->dropForeign(['supplier_id']);
                } catch (\Exception $e) {}
                $table->dropColumn('supplier_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_outs', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_outs', 'supplier_id')) {
                $table->unsignedBigInteger('supplier_id')->nullable();
                // Optionally restore the foreign key:
                // $table->foreign('supplier_id')->references('id')->on('vendors')->onDelete('cascade');
            }
        });
    }
}
