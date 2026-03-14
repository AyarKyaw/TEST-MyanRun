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
        // Default to false so scanning is disabled until you're ready
        $table->boolean('is_scanning_open')->default(false);
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
