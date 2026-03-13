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
    Schema::table('sponsors', function (Blueprint $table) {
        $table->unsignedBigInteger('dinner_id')->nullable()->after('id');
        $table->foreign('dinner_id')->references('id')->on( 'dinners')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sponsors', function (Blueprint $table) {
            //
        });
    }
};
