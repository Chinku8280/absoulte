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
        Schema::create('lead_prices', function (Blueprint $table) {
            $table->id();
            $table->string('products');
            $table->string('unit_price');
            $table->string('qty');
            $table->string('sub_total');
            $table->string('discount');
            $table->string('net_total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_prices');
    }
};
