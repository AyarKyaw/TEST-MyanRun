<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dinner_tickets', function (Blueprint $table) {
            // We add quantity, defaulting to 1
            $table->integer('quantity')->default(1)->after('price');
        });
    }

    public function down()
    {
        Schema::table('dinner_tickets', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};
