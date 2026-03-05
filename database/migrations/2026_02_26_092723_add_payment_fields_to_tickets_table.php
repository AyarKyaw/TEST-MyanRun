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
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->after('status'); // KBZ reference number
            $table->string('payment_method')->nullable()->after('transaction_id'); // e.g., 'kbz_pay'
            $table->text('qr_code_str')->nullable(); // Store the raw QR string if needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            //
        });
    }
};
