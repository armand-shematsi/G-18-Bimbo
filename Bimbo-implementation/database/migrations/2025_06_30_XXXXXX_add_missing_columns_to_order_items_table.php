<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'product_name')) {
                $table->string('product_name')->nullable();
            }
            if (!Schema::hasColumn('order_items', 'unit_price')) {
                $table->decimal('unit_price', 12, 2)->nullable();
            }
            if (!Schema::hasColumn('order_items', 'total_price')) {
                $table->decimal('total_price', 12, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'product_name')) {
                $table->dropColumn('product_name');
            }
            if (Schema::hasColumn('order_items', 'unit_price')) {
                $table->dropColumn('unit_price');
            }
            if (Schema::hasColumn('order_items', 'total_price')) {
                $table->dropColumn('total_price');
            }
        });
    }
};