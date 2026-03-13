<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('dinners', function (Blueprint $table) {
            $table->integer('capacity')->default(0); // 0 could mean unlimited or set a specific number
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dinners', function (Blueprint $table) {
            //
        });
    }
};
