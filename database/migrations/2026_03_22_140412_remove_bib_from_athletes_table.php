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
        Schema::table('athletes', function (Blueprint $table) {
            // Removing these because they change per event
            $table->dropColumn(['bib_number']);
        });

        Schema::table('tickets', function (Blueprint $table) {
            // Link to the athlete
            $table->foreignId('athlete_id')->after('id')->constrained()->onDelete('cascade');
            
            // Event identification (Max 20 chars for BIB Name as requested)
            $table->string('bib_number')->after('athlete_id')->unique();
            $table->string('bib_name', 20)->after('bib_number');
            
            // Race specifics
            $table->string('t_shirt_size')->after('bib_name');
            $table->string('category_name')->after('t_shirt_size'); // e.g., "10KM", "21KM"
            
            // Payment tracking
            $table->decimal('price', 12, 2)->after('category_name');
            $table->string('status')->default('pending')->after('price'); // pending, paid, expired
            $table->string('payment_method')->nullable()->after('status');
            $table->string('transaction_id')->nullable()->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->string('bib_number')->nullable();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['athlete_id']);
            $table->dropColumn([
                'athlete_id', 'bib_number', 'bib_name', 
                't_shirt_size', 'category_name', 'price', 
                'status', 'payment_method', 'transaction_id'
            ]);
        });
    }
};
