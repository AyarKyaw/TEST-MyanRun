<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_ticket_types', function (Blueprint $table) {
            // Image
            $table->string('image')->nullable()->after('price');

            // Separate pricing
            $table->integer('national_price')->nullable()->after('image');
            $table->integer('foreign_price')->nullable()->after('national_price');

            // Optional: rename old price usage (if needed)
            // $table->dropColumn('price'); // only if you want to remove old one
        });
    }

    public function down(): void
    {
        Schema::table('event_ticket_types', function (Blueprint $table) {
            $table->dropColumn(['image', 'national_price', 'foreign_price']);
        });
    }
};