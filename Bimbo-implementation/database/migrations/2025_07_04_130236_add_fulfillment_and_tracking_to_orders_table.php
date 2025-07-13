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
        Schema::table('orders', function (Blueprint $table) {
            // $table->string('fulfillment_type')->nullable();
            // $table->string('tracking_number')->nullable();
            // $table->string('delivery_option')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // $table->dropColumn(['fulfillment_type', 'tracking_number', 'delivery_option']);
        });
    }
};

// Migration: create_supply_centers_table.php
return new class extends Migration {
    public function up()
    {
        Schema::create('supply_centers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('supply_centers');
    }
};

// Migration: create_staff_supply_center_assignments_table.php
return new class extends Migration {
    public function up()
    {
        Schema::create('staff_supply_center_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('supply_center_id')->constrained()->onDelete('cascade');
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->onDelete('set null');
            $table->string('status')->default('assigned'); // assigned, on_shift, completed, etc.
            $table->date('assigned_date');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('staff_supply_center_assignments');
    }
};
