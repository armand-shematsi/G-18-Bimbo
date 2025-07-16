<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('supply_centers', function (Blueprint $table) {
            $table->string('shift_time')->nullable();
        });
    }
    public function down()
    {
        Schema::table('supply_centers', function (Blueprint $table) {
            $table->dropColumn('shift_time');
        });
    }
};
