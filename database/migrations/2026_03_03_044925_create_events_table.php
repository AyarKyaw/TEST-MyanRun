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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('company');      // e.g., "MyanRun Co., Ltd"
            $table->string('name');         // e.g., "Yangon Marathon 2026"
            $table->string('image_path');   // Store the path to the event banner/logo
            $table->date('date');           // The date of the event
            $table->boolean('is_active')->default(true); // Useful for showing/hiding events
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
