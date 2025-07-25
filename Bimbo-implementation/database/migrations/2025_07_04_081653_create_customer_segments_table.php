<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_segments', function (Blueprint $table) {
            $table->id();
            $table->string('CustomerID')->unique();
            $table->string('Name');
            $table->integer('PurchaseFrequency');
            $table->integer('AvgSpending');
            $table->string('Location');
            $table->string('PreferredBreadType');
            $table->integer('Segment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_segments');
    }
};
