<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('supply_centers', function (Blueprint $table) {
            // $table->string('required_role')->nullable()->after('location');
        });
    }
    public function down()
    {
        Schema::table('supply_centers', function (Blueprint $table) {
            // $table->dropColumn('required_role');
        });
    }
}; 