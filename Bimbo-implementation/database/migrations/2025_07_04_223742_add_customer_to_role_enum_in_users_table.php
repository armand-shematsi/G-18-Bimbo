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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'supplier', 'bakery_manager', 'distributor', 'retail_manager', 'pending', 'rejected', 'staff', 'customer'])
                ->default('pending')
                ->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'supplier', 'bakery_manager', 'distributor', 'retail_manager', 'pending', 'rejected', 'staff'])
                ->default('pending')
                ->after('email');
        });
    }
};
