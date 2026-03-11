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
        Schema::create('sponsors', function (Blueprint $table) {
            $table->id();
            $table->string('name');            // Company / Organization Name
            $table->string('contact_name');    // Person to talk to
            $table->string('phone');           // Contact Number
            $table->string('email');           // Contact Email
            
            // Keeping status and date as they are useful for accounting
            $table->string('status')->default('active'); 
            $table->date('transaction_date')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sponsors');
    }
};