<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('visit_scheduled')->nullable();
        });
    }
    public function down()
    {
        if (Schema::hasColumn('vendors', 'visit_scheduled')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->dropColumn('visit_scheduled');
            });
        }
    }
};
