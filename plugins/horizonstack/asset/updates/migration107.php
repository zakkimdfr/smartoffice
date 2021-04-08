<?php namespace Horizonstack\Asset\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration107 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_asset_records', function ($table) {
            $table->integer('task')->nullable();
        });
    }

    public function down()
    {
        Schema::table('horizonstack_asset_records', function ($table) {
            $table->dropColumn('task');
        });
    }
}