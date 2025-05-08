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
        Schema::table('customers', function (Blueprint $table) {
           $table->string('saluation')->nullable();
            $table->string('created_by')->nullable();
            $table->string('territory')->nullable(); 
            $table->string('language_spoken')->nullable(); 
            $table->string('cleaning_type')->nullable(); 
            $table->string('customer_remark')->nullable(); 
            $table->string('additional_info_status')->nullable(); 


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
