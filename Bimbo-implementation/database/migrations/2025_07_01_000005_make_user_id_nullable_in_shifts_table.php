<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    public function down()
    {
        // Set all NULL user_id values to a valid default before making NOT NULL
        \DB::table('shifts')->whereNull('user_id')->update(['user_id' => 1]); // Change 1 to a valid user ID if needed
        Schema::table('shifts', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
