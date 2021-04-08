<?php namespace Horizonstack\eSPJ\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration1015 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_espj_trips', function ($table) {
            $table->string('budget_name')->nullable();
            $table->dropColumn('budget');
        });
    }

    public function down()
    {
        Schema::table('horizonstack_espj_trips', function ($table) {
            $table->dropColumn('budget_name');
            $table->double('budget', 10, 0)->nullable();
        });
    }
}