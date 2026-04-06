<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('event_ticket_types', function (Blueprint $table) {
            // Check if the old column exists before trying to drop it
            if (Schema::hasColumn('event_ticket_types', 'image')) {
                $table->dropColumn('image');
            }

            $table->string('national_image')->nullable();
            $table->string('foreign_image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('event_ticket_types', function (Blueprint $table) {
            $table->string('image')->nullable();

            // Safe drop for the new columns as well
            $table->dropColumn(['national_image', 'foreign_image']);
        });
    }
};