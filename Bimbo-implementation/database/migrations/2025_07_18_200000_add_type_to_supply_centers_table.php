<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('supply_centers', function (Blueprint $table) {
            if (!Schema::hasColumn('supply_centers', 'type')) {
                $table->string('type')->default('production')->after('name');
            }
        });
    }
    public function down()
    {
        Schema::table('supply_centers', function (Blueprint $table) {
            if (Schema::hasColumn('supply_centers', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
