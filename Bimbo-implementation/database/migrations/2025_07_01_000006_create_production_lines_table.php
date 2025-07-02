<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('production_lines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status', ['Running', 'Maintenance', 'Stopped']);
            $table->string('current_product')->nullable();
            $table->integer('output')->default(0);
            $table->integer('efficiency')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('production_lines');
    }
};
