<?php namespace Horizonstack\eSPJ\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration1014 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_espj_trips', function ($table) {
            $table->bigInteger('destination_city_1')->nullable();
            $table->bigInteger('destination_city_2')->nullable();
        });
    }

    public function down()
    {
        Schema::table('horizonstack_espj_trips', function ($table) {
            $table->dropColumn('destination_city_1');
            $table->dropColumn('destination_city_2');
        });
    }
}