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
        Schema::create('tble_schedule', function (Blueprint $table) {
            $table->id();
            $table->integer('quotation_id');
            $table->string('cleaner_type')->nullable();
            $table->string('name')->nullable();
            $table->date('startDate');
            $table->date('endDate');
            $table->string('postalCode');
            $table->integer('unitNo');
            $table->string('address');
            $table->integer('frequency')->nullable();
            $table->integer('indefinitely')->default(0);
            $table->time('startTime');
            $table->time('endTime');
            $table->text('days')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tble_schedule');
    }
};
