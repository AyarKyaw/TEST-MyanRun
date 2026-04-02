<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('event_ticket_types', function (Blueprint $table) {
            $table->dropColumn('image');

            $table->string('national_image')->nullable();
            $table->string('foreign_image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('event_ticket_types', function (Blueprint $table) {
            $table->string('image')->nullable();

            $table->dropColumn('national_image');
            $table->dropColumn('foreign_image');
        });
    }
};
