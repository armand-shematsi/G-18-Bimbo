<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('supply_center_id')->nullable()->after('role');
            $table->foreign('supply_center_id')->references('id')->on('supply_centers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['supply_center_id']);
            $table->dropColumn('supply_center_id');
        });
    }
}; 