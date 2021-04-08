<?php namespace Horizonstack\Investment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration1017 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_investment_investments', function ($table) {
            $table->float('profitability_index', 10, 0)->nullable()->unsigned(false)->default(null)->change();
        });
    }

    public function down()
    {
        Schema::table('horizonstack_investment_investments', function ($table) {
            $table->string('profitability_index', 191)->nullable()->unsigned(false)->default(null)->change();
        });
    }
}