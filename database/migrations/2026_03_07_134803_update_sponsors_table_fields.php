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
        Schema::table('sponsors', function (Blueprint $table) {
            // 1. RENAME: name -> company
            if (Schema::hasColumn('sponsors', 'name') && !Schema::hasColumn('sponsors', 'company')) {
                $table->renameColumn('name', 'company');
            }

            // 2. DROP: status and transaction_date (or 'date')
            // Check for 'date' or 'transaction_date' depending on your exact column name
            if (Schema::hasColumn('sponsors', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('sponsors', 'transaction_date')) {
                $table->dropColumn('transaction_date');
            }

            // 3. ADD: Missing fields
            if (!Schema::hasColumn('sponsors', 'contact_name')) {
                $table->string('contact_name')->nullable()->after('id'); 
            }
            if (!Schema::hasColumn('sponsors', 'viber')) {
                $table->string('viber')->nullable();
            }
            if (!Schema::hasColumn('sponsors', 'quantity')) {
                $table->integer('quantity')->default(0);
            }
            // Note: phone and email were already in your list, 
            // but if they don't exist, we add them here:
            if (!Schema::hasColumn('sponsors', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('sponsors', 'email')) {
                $table->string('email')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('sponsors', function (Blueprint $table) {
            $table->dropColumn(['contact_name', 'phone', 'email', 'viber', 'quantity']);
            $table->renameColumn('company', 'name');
        });
    }
};
