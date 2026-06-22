<?php

// xxxx_xx_xx_xxxxxx_create_photos_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('filename');       // e.g., 'marathon_finish_0023.jpg'
            $table->string('storage_path');   // e.g., 'photos/events/1/marathon_finish_0023.jpg'
            $table->unsignedBigInteger('event_id')->nullable(); // Keeps photos isolated by race event
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};