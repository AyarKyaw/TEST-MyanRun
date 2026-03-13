<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dinners', function (Blueprint $table) {
            // Adding the column after image_path for better organization
            $table->string('info_image')->nullable()->after('image_path');
        });
    }

    public function down(): void
    {
        Schema::table('dinners', function (Blueprint $table) {
            $table->dropColumn('info_image');
        });
    }
};