<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_ins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('inventory_id');
            $table->integer('quantity_received');
            $table->dateTime('received_at');
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('inventory_id')->references('id')->on('inventories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_ins');
    }
};