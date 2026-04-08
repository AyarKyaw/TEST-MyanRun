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
            Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('athlete_id')->constrained();
            
            // New fields from your code:
            $table->string('bib_name')->nullable();
            $table->string('bib_number')->nullable();
            $table->string('category');
            $table->integer('price'); // Changed to integer for your (int) cast
            $table->string('t_shirt_size')->default('M');
            $table->string('experience_level')->default('Beginner');
            
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
