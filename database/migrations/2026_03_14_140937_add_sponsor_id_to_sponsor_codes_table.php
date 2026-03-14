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
    Schema::table('sponsor_codes', function (Blueprint $table) {
        // Add the missing column
        $table->unsignedBigInteger('sponsor_id')->after('id')->nullable();
        
        // Optional: Add a foreign key to keep the data clean
        // $table->foreign('sponsor_id')->references('id')->on('sponsors')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('sponsor_codes', function (Blueprint $table) {
        $table->dropColumn('sponsor_id');
    });
}
};
