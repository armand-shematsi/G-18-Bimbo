<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_demand_forecasts', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('product_type');
            $table->integer('predicted_quantity');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_demand_forecasts');
    }
};
