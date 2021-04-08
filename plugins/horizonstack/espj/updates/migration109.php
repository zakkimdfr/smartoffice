<?php namespace Horizonstack\eSPJ\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration109 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_espj_trips', function ($table) {
            $table->text('remarks')->nullable();
        });
    }

    public function down()
    {
        Schema::table('horizonstack_espj_trips', function ($table) {
            $table->dropColumn('remarks');
        });
    }
}