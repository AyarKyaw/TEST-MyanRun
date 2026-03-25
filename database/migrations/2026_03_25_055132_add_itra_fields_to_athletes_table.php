<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            // Adding the ITRA toggle and details after medical_details
            $table->boolean('has_itra')->default(false)->after('medical_details');
            $table->string('itra_details')->nullable()->after('has_itra');
            
            // If you want to match your form exactly, you might want to 
            // ensure 'medical_details' can be null
            $table->text('medical_details')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->dropColumn(['has_itra', 'itra_details']);
        });
    }
};
