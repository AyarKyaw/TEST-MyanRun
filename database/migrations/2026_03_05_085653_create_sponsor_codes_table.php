<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('sponsor_codes', function (Blueprint $table) {
            $table->id();
            // Link to the sponsor contact info
            $table->foreignId('sponsor_id')->constrained()->onDelete('cascade');
            
            // The shared code (e.g., 'KBZ-DINNER-2026')
            $table->string('code')->unique();
            
            // The value: 10, 50, or 100 (%)
            $table->integer('discount')->default(100);
            
            // The Quota Logic
            $table->integer('max_uses')->default(1); // How many people can use it
            $table->integer('used_count')->default(0); // How many have used it so far
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sponsor_codes');
    }
};