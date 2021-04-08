<?php namespace Horizonstack\eSPJ\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration1010 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_espj_trips', function ($table) {
            $table->integer('transportation_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('horizonstack_espj_trips', function ($table) {
            $table->dropColumn('transportation_id');
        });
    }
}