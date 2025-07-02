<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStockInsToUseUserId extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stock_ins', function (Blueprint $table) {
            // Drop the old foreign key and column if they exist
            if (Schema::hasColumn('stock_ins', 'supplier_id')) {
                try {
                    $table->dropForeign(['supplier_id']);
                } catch (\Exception $e) {}
                $table->dropColumn('supplier_id');
            }

            // Add the new user_id column if it doesn't exist
            if (!Schema::hasColumn('stock_ins', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_ins', function (Blueprint $table) {
            if (Schema::hasColumn('stock_ins', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            // Optionally, you can add back supplier_id here if needed
        });
    }
}
