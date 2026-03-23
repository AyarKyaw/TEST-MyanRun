<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('athletes', function (Blueprint $blueprint) {
            // Adding 'state' after 'nat_type' (or wherever you prefer)
            $blueprint->string('state')->nullable()->after('nat_type');
        });
    }

    public function down(): void
    {
        Schema::table('athletes', function (Blueprint $blueprint) {
            $blueprint->dropColumn('state');
        });
    }
};