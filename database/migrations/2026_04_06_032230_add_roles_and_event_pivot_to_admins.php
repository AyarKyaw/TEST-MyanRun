<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Add role to existing admins table
        Schema::table('admins', function (Blueprint $table) {
            // Options: super_admin, event_admin, finance_admin
            if (!Schema::hasColumn('admins', 'role')) {
                $table->string('role')->default('event_admin')->after('password');
            }
        });

        // 2. Create pivot table to assign Admins to Events
        // This allows one event to have multiple admins, and vice-versa
        if (!Schema::hasTable('admin_event')) {
            Schema::create('admin_event', function (Blueprint $table) {
                $table->id();
                $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
                $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_event');
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};