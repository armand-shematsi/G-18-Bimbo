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
        // Schema::table('vendors', function (Blueprint $table) {
        //     if (!Schema::hasColumn('vendors', 'visit_scheduled')) {
        //         $table->date('visit_scheduled')->nullable();
        //     }
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (Schema::hasColumn('vendors', 'visit_scheduled')) {
                $table->dropColumn('visit_scheduled');
            }
        });
    }
};
