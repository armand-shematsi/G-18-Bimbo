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
        Schema::table('vendors', function (Blueprint $table) {
            if (!Schema::hasColumn('vendors', 'sales')) {
                $table->decimal('sales', 15, 2)->default(0);
            }

            if (!Schema::hasColumn('vendors', 'sales')) {
                $table->decimal('sales', 15, 2)->default(0);
            }

//            $table->decimal('sales', 15, 2)->default(0);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (Schema::hasColumn('vendors', 'sales')) {
                $table->dropColumn('sales');
            }
        });
    }
};
