<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('staff_supply_center_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('supply_center_id')->constrained()->onDelete('cascade');
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->onDelete('set null');
            $table->string('status')->default('assigned');
            $table->date('assigned_date');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('staff_supply_center_assignments');
    }
};
