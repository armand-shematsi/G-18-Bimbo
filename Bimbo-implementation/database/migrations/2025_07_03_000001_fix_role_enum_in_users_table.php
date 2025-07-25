<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'supplier', 'bakery_manager', 'distributor', 'retail_manager', 'pending', 'rejected', 'customer', 'staff'])
                ->default('pending')
                ->after('email');
        });
    }
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'supplier', 'bakery_manager', 'distributor', 'retail_manager', 'pending', 'rejected', 'customer', 'staff'])
                ->default('pending')
                ->after('email');
        });
    }
};
