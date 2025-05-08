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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->enum('customer_type', ['1', '2'])->comment('1=residential,2=commercial')->nullable();
            $table->string('person_incharge_name')->nullable();
            $table->string('nick_name')->nullable();
            $table->string('mobile_number',20)->nullable(); 
            $table->string('fax_number')->nullable();
            $table->string('email')->nullable();
            $table->string('credit_limit')->nullable();
            $table->string('remark')->nullable();
            $table->string('payment_terms')->comment('1=private,2=6 months,3=12 months')->nullable();
            $table->string('status')->nullable();
            $table->string('uen')->nullable();
            $table->string('group_company_name')->nullable();
            $table->string('individual_company_name')->nullable();

               $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
