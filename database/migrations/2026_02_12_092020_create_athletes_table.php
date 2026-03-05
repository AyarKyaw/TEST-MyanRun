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
    Schema::create('athletes', function (Blueprint $table) {
        $table->id();
        
        // Link to User
        $table->string('runner_id')->unique();
        $table->foreign('runner_id')->references('runner_id')->on('users')->onDelete('cascade');

        // Missing Identity Columns
        $table->string('nat_type'); // 'national' or 'foreigner'
        $table->string('id_number'); // The NRC or Passport string
        
        // Personal Info
        $table->string('first_name');
        $table->string('middle_name')->nullable();
        $table->string('last_name');
        $table->string('father_name');
        $table->date('dob');
        $table->string('gender');
        
        // Contact & Misc
        $table->string('nationality')->default('Myanmar');
        $table->text('address')->nullable();
        $table->string('phone_2')->nullable();
        $table->string('social_account')->nullable();
        
        // The Face Image from the AI
        $table->string('face_image_path')->nullable();
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('athletes');
    }
};
