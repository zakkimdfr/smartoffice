<?php namespace Horizonstack\Asset\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration106 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_asset_assets', function ($table) {
            $table->double('value', 10, 0)->nullable();
        });
    }

    public function down()
    {
        Schema::table('horizonstack_asset_assets', function ($table) {
            $table->dropColumn('value');
        });
    }
}