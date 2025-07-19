<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('supply_centers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('production'); // New: type of center
            $table->string('location')->nullable();
            $table->string('required_role')->nullable(); // e.g., 'driver', 'baker', etc.
            $table->string('shift_time')->nullable(); // Add this line
            $table->integer('required_staff_count')->default(1); // New column for required number of staffs
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('supply_centers');
    }
};
