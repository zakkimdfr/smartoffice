<?php namespace Horizonstack\eSPJ\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration1011 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_espj_reality_forms', function ($table) {
            $table->dropColumn('mission');
            $table->dropColumn('budget_name');
        });
    }

    public function down()
    {
        Schema::table('horizonstack_espj_reality_forms', function ($table) {
            $table->text('mission')->nullable();
            $table->string('budget_name', 191)->nullable();
        });
    }
}