<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    // 1. UPDATE ATHLETES (Keep Blood Type here)
    Schema::table('athletes', function (Blueprint $table) {
        // Drop ONLY the event-specific columns
        $toDrop = ['bib_name', 'bib_number', 't_shirt_size', 'experience_level'];
        foreach ($toDrop as $col) {
            if (Schema::hasColumn('athletes', $col)) {
                $table->dropColumn($col);
            }
        }

        // Rename columns for clarity
        if (Schema::hasColumn('athletes', 'phone_2')) {
            $table->renameColumn('phone_2', 'contact');
        }
        if (Schema::hasColumn('athletes', 'social_account')) {
            $table->renameColumn('social_account', 'viber');
        }

        // Ensure blood_type exists (if it wasn't there already)
        if (!Schema::hasColumn('athletes', 'blood_type')) {
            $table->string('blood_type')->nullable()->after('gender');
        }

        // Add Medical fields
        if (!Schema::hasColumn('athletes', 'has_medical_condition')) {
            $table->boolean('has_medical_condition')->default(false)->after('blood_type');
        }
        if (!Schema::hasColumn('athletes', 'medical_details')) {
            $table->text('medical_details')->nullable()->after('has_medical_condition');
        }
    });

    // 2. UPDATE TICKETS (Event-Specific Data)
    Schema::table('tickets', function (Blueprint $table) {
        // Standardize runner_id to athlete_id
        if (Schema::hasColumn('tickets', 'runner_id')) {
            $table->renameColumn('runner_id', 'athlete_id');
        }

        // Add the fields that DO change per event
        if (!Schema::hasColumn('tickets', 'bib_number')) {
            $table->string('bib_number')->unique()->after('athlete_id');
        }
        
        // Max 20 chars for BIB Name as requested
        if (!Schema::hasColumn('tickets', 'bib_name')) {
            $table->string('bib_name', 20)->after('bib_number');
        }

        if (!Schema::hasColumn('tickets', 't_shirt_size')) {
            $table->string('t_shirt_size')->after('bib_name');
        }

        if (!Schema::hasColumn('tickets', 'experience_level')) {
            $table->string('experience_level')->nullable()->after('t_shirt_size');
        }
        
        // Remove unneeded column
        if (Schema::hasColumn('tickets', 'qr_code_str')) {
            $table->dropColumn('qr_code_str');
        }
    });
}
};
