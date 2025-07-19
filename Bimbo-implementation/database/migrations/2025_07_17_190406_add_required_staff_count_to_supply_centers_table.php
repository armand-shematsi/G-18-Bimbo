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
        Schema::table('supply_centers', function (Blueprint $table) {
            if (!Schema::hasColumn('supply_centers', 'required_staff_count')) {
                $table->integer('required_staff_count')->default(0);
            }
            if (!Schema::hasColumn('supply_centers', 'type')) {
                $table->string('type')->default('production');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supply_centers', function (Blueprint $table) {
            $table->dropColumn('required_staff_count');
        });
    }
};
