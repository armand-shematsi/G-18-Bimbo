<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        // Drop foreign keys referencing products
        Schema::table('inventories', function (Blueprint $table) {
            try { $table->dropForeign(['product_id']); } catch (\Exception $e) {}
        });
        Schema::table('order_items', function (Blueprint $table) {
            try { $table->dropForeign(['product_id']); } catch (\Exception $e) {}
        });
        Schema::dropIfExists('products');
    }
};
