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
        Schema::table('orders', function (Blueprint $table) {
            // Add vendor_id if it doesn't exist
            if (!Schema::hasColumn('orders', 'vendor_id')) {
                $table->foreignId('vendor_id')->nullable()->constrained('vendors')->onDelete('cascade');
            }

            // Add customer_email if it doesn't exist
            if (!Schema::hasColumn('orders', 'customer_email')) {
                $table->string('customer_email')->nullable();
            }

            // Add notes if it doesn't exist
            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable();
            }

            // Add customer_name if it doesn't exist
            if (!Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);
            $table->dropColumn(['vendor_id', 'customer_email', 'notes', 'customer_name']);
        });
    }
};
