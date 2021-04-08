<?php namespace Horizonstack\Investment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration1018 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_investment_investments', function ($table) {
            $table->double('usd', 10, 0)->nullable();
        });
    }

    public function down()
    {
        Schema::table('horizonstack_investment_investments', function ($table) {
            $table->dropColumn('usd');
        });
    }
}