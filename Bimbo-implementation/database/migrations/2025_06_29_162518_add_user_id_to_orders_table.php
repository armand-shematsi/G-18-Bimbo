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
            if (!Schema::hasColumn('orders', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            }
            if (Schema::hasColumn('orders', 'supplier_id')) {
                $table->dropColumn('supplier_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Ensure user_id column exists
            if (!Schema::hasColumn('orders', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
            }
            // Add the foreign key if it doesn't exist
            try { $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); } catch (\Exception $e) {}
            // Now drop the foreign key
            try { $table->dropForeign(['user_id']); } catch (\Exception $e) {}
            // Drop the column
            if (Schema::hasColumn('orders', 'user_id')) {
                $table->dropColumn('user_id');
            }
            if (!Schema::hasColumn('orders', 'supplier_id')) {
                $table->unsignedBigInteger('supplier_id')->nullable();
            }
        });
    }
};
