<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            // Check if medical_details exists; if not, create it first
            if (!Schema::hasColumn('athletes', 'medical_details')) {
                $table->text('medical_details')->nullable()->after('id'); // or after another existing column
            } else {
                $table->text('medical_details')->nullable()->change();
            }

            $table->boolean('has_itra')->default(false)->after('medical_details');
            $table->string('itra_details')->nullable()->after('has_itra');
        });
    }

    public function down(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->dropColumn(['has_itra', 'itra_details']);
        });
    }
};
