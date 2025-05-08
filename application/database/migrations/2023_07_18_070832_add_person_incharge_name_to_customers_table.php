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
        Schema::table('service_address', function (Blueprint $table) {
                      $table->string('person_incharge_name')->nullable();
                      $table->string('zone')->nullable();
                      $table->string('contact_no')->nullable();
                      $table->string('email_id')->nullable();



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_address', function (Blueprint $table) {
            //
        });
    }
};
