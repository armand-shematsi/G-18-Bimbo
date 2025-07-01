<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Drop the existing foreign key if it exists
            // try {
            //     $table->dropForeign(['product_id']);
            // } catch (\Exception $e) {}
            $table->unsignedBigInteger('product_id')->nullable()->change();
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // $table->dropForeign(['product_id']);
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
};
