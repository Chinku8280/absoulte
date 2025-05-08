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
        Schema::create('service_address', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id',11)->nullable(); 
            $table->string('postal_code')->nullable();
            $table->string('address')->nullable();
            $table->string('unit_number')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_address');
    }
};
