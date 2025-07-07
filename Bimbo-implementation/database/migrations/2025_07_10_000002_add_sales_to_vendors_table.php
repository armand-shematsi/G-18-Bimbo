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
                $table->decimal('sales', 15, 2)->default(0)->after('status');
            }
            if (!Schema::hasColumn('vendors', 'annual_revenue')) {
                $table->decimal('annual_revenue', 15, 2)->default(0)->after('sales');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (Schema::hasColumn('vendors', 'annual_revenue')) {
                $table->dropColumn('annual_revenue');
            }
            if (Schema::hasColumn('vendors', 'sales')) {
                $table->dropColumn('sales');
            }
        });
    }
};
