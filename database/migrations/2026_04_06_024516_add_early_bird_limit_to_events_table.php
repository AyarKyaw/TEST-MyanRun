<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // We use integer for the limit (number of people)
            // Adding after 'id' or a specific column to keep it organized
            $table->integer('early_bird_limit')->default(0)->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('early_bird_limit');
        });
    }
};