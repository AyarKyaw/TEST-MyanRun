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
            // We add sponsor_id and link it to the sponsors table
            $table->foreignId('sponsor_id')->nullable()->after('dinner_id')->constrained()->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('dinner_tickets', function (Blueprint $table) {
            $table->dropForeign(['sponsor_id']);
            $table->dropColumn('sponsor_id');
        });
    }
};
