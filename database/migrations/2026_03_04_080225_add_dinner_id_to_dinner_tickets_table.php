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
        Schema::table('dinner_tickets', function (Blueprint $table) {
            // Adds the column and links it to the dinners table
            $table->unsignedBigInteger('dinner_id')->nullable()->after('id');
            
            // Optional: Add a foreign key constraint for data integrity
            $table->foreign('dinner_id')->references('id')->on('dinners')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('dinner_tickets', function (Blueprint $table) {
            $table->dropForeign(['dinner_id']);
            $table->dropColumn('dinner_id');
        });
    }
};
