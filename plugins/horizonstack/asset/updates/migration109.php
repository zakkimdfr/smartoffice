<?php namespace Horizonstack\Asset\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration109 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_asset_records', function ($table) {
            $table->date('expected_return_date')->nullable();
        });
    }

    public function down()
    {
        Schema::table('horizonstack_asset_records', function ($table) {
            $table->dropColumn('expected_return_date');
        });
    }
}