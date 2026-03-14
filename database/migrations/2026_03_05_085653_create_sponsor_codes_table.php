<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sponsor_codes', function (Blueprint $table) {
            $table->id();
            
            // The unique code string (e.g., SPN-X82A)
            $table->string('code')->unique();

            // Link to the Dinner Event
            $table->foreignId('dinner_id')->constrained()->onDelete('cascade');

            // Link to the specific seat/ticket
            $table->foreignId('dinner_ticket_id')->nullable()->constrained()->onDelete('set null');

            // Link to the Person who bought/used it
            $table->foreignId('dinner_register_id')->nullable()->constrained('dinner_registers')->onDelete('set null');

            // Usage tracking
            $table->integer('max_uses')->default(1);
            $table->integer('used_count')->default(0);
            
            // Status: 'active', 'used', 'expired'
            $table->string('status')->default('active');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sponsor_codes');
    }
};