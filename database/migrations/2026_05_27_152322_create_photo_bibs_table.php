<?php

// xxxx_xx_xx_xxxxxx_create_photo_bibs_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('photo_bibs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photo_id')->constrained()->onDelete('cascade');
            
            // Using string type so leading zeros like BIB "0452" don't turn into "452"
            $table->string('bib_number'); 
            $table->timestamps();

            // CRITICAL: This index guarantees lightning-fast query speeds when searching hundreds of thousands of photos
            $table->index('bib_number'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photo_bibs');
    }
};