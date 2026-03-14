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
        Schema::table('sponsor_codes', function (Blueprint $table) {
            // Add the missing column
            $table->string('used_by_name')->nullable()->after('dinner_register_id');
            
            // Also check if you are missing dinner_ticket_id or dinner_register_id
            if (!Schema::hasColumn('sponsor_codes', 'dinner_ticket_id')) {
                $table->foreignId('dinner_ticket_id')->nullable()->after('dinner_id');
            }
            if (!Schema::hasColumn('sponsor_codes', 'dinner_register_id')) {
                $table->foreignId('dinner_register_id')->nullable()->after('dinner_ticket_id');
            }
        });
    }

    public function down()
    {
        Schema::table('sponsor_codes', function (Blueprint $table) {
            $table->dropColumn(['used_by_name', 'dinner_ticket_id', 'dinner_register_id']);
        });
    }
};
