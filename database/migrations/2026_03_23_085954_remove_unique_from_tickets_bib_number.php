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
    Schema::table('tickets', function (Blueprint $table) {
        // 'tickets_bib_number_unique' is the standard Laravel naming convention
        $table->dropUnique(['bib_number']); 
    });
}

public function down(): void
{
    Schema::table('tickets', function (Blueprint $table) {
        $table->unique('bib_number');
    });
}
};
