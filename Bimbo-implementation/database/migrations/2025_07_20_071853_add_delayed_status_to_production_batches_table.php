<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('production_batches', function (Blueprint $table) {
            // Add 'delayed' to the existing status enum
            DB::statement("ALTER TABLE production_batches MODIFY COLUMN status ENUM('planned', 'active', 'completed', 'cancelled', 'delayed') DEFAULT 'planned'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_batches', function (Blueprint $table) {
            // Remove 'delayed' from the status enum
            DB::statement("ALTER TABLE production_batches MODIFY COLUMN status ENUM('planned', 'active', 'completed', 'cancelled') DEFAULT 'planned'");
        });
    }
};
