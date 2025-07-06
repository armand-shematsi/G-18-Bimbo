<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'supplier', 'bakery_manager', 'distributor', 'retail_manager', 'pending', 'rejected', 'customer', 'staff'])
                ->default('pending')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'supplier', 'bakery_manager', 'distributor', 'retail_manager', 'pending', 'rejected', 'customer', 'staff'])
                ->default('pending')
                ->change();
        });
    }
};
