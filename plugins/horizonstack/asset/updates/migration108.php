<?php namespace Horizonstack\Asset\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration108 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_asset_assets', function ($table) {
            $table->integer('status')->nullable();
        });
    }

    public function down()
    {
        Schema::table('horizonstack_asset_assets', function ($table) {
            $table->dropColumn('status');
        });
    }
}