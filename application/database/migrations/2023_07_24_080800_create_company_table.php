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
        Schema::create('company', function (Blueprint $table) {
            $table->id();
             $table->string('company_name')->nullable(); 
             $table->string('person_incharge_name')->nullable();
             $table->string('contact_number')->nullable();
             $table->string('email_id');
             $table->string('quotation_templete')->nullable();
             $table->string('status')->nullable();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company');
    }
};
