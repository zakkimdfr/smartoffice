<?php namespace Horizonstack\eSPJ\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration1016 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_espj_trips', function ($table) {
            $table->date('signature_date')->nullable();
        });
    }

    public function down()
    {
        Schema::table('horizonstack_espj_trips', function ($table) {
            $table->dropColumn('signature_date');
        });
    }
}