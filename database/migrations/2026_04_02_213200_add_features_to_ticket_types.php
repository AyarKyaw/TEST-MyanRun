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
        Schema::table('event_ticket_types', function (Blueprint $table) {
            $table->string('ticket_png')->nullable()->after('foreign_image');
            $table->boolean('has_gender_bib')->default(false);
            $table->integer('early_bird_limit')->nullable();
            $table->decimal('early_bird_discount', 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_ticket_types', function (Blueprint $table) {
            //
        });
    }
};
