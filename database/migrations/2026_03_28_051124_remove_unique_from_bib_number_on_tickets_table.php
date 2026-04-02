<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // This puts it back if you rollback
            $table->unique('bib_number');
        });
    }
};