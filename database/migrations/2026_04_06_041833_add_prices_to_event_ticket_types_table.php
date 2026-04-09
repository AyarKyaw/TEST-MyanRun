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
        Schema::table('event_ticket_types', function (Blueprint $table) {
            if (!Schema::hasColumn('event_ticket_types', 'national_price')) {
                $table->decimal('national_price', 10, 2)->after('type')->default(0);
            }
            if (!Schema::hasColumn('event_ticket_types', 'foreign_price')) {
                $table->decimal('foreign_price', 10, 2)->after('national_price')->default(0);
            }
        });
    }

public function down()
{
    Schema::table('event_ticket_types', function (Blueprint $table) {
        $table->dropColumn(['national_price', 'foreign_price']);
    });
}
};
