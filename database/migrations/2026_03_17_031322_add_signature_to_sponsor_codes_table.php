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
            // We store the 10-character hash here
            $table->string('signature', 10)->nullable()->after('code');
            
            // Indexing for faster offline lookup in AppSheet
            $table->index(['code', 'signature']); 
        });
    }

    public function down()
    {
        Schema::table('sponsor_codes', function (Blueprint $table) {
            $table->dropColumn('signature');
        });
    }
};
