<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('races', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Cherry Trail Run 2026"
            $table->string('slug')->unique(); // e.g., "cherry-trail-run-2026"
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('race_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('race_id')->constrained()->onDelete('cascade');
            $table->string('title'); // e.g., "T-shirt Size Chart (Inches)"
            $table->string('image'); // Stored image filename path
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('race_cards');
        Schema::dropIfExists('races');
    }
};
