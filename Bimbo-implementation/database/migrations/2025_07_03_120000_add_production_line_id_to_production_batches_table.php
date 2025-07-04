<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('production_batches', function (Blueprint $table) {
            $table->unsignedBigInteger('production_line_id')->nullable()->after('name');
            $table->foreign('production_line_id')->references('id')->on('production_lines')->onDelete('set null');
        });
    }
    public function down()
    {
        Schema::table('production_batches', function (Blueprint $table) {
            $table->dropForeign(['production_line_id']);
            $table->dropColumn('production_line_id');
        });
    }
}; 