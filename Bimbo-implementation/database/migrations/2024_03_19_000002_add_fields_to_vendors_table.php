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
            $table->string('city')->after('address');
            $table->string('state')->after('city');
            $table->string('zip_code')->after('state');
            $table->string('business_type')->after('zip_code');
            $table->string('tax_id')->after('business_type');
            $table->string('business_license')->after('tax_id');
            $table->string('status')->default('pending')->change();
            $table->decimal('sales', 15, 2)->after('status')->default(0);
            $table->decimal('annual_revenue', 15, 2)->after('sales')->default(0);
            $table->integer('years_in_business')->after('annual_revenue')->default(0);
            $table->string('regulatory_certification')->after('years_in_business')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $columns = [
                'city',
                'state',
                'zip_code',
                'business_type',
                'tax_id',
                'business_license',
                'sales',
                'annual_revenue',
                'years_in_business',
                'regulatory_certification'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('vendors', $column)) {
                    $table->dropColumn($column);
                }
            }
            $table->string('status')->default('active')->change();
        });
    }
};
