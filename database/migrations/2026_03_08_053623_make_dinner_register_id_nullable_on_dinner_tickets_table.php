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
        Schema::table('dinner_tickets', function (Blueprint $table) {
            // This allows the ticket to exist without being linked to a specific person yet
            $table->unsignedBigInteger('dinner_register_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('dinner_tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('dinner_register_id')->nullable(false)->change();
        });
    }
};
