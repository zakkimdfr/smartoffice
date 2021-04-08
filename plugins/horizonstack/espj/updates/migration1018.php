<?php namespace Horizonstack\eSPJ\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration1018 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_espj_trips', function ($table) {
            $table->string('days')->nullable();
            $table->string('nights')->nullable();
        });
    }

    public function down()
    {
        Schema::table('horizonstack_espj_trips', function ($table) {
            $table->dropColumn('days');
            $table->dropColumn('nights');
        });
    }
}