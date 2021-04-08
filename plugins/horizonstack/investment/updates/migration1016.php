<?php namespace Horizonstack\Investment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration1016 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_investment_investments', function ($table) {
            $table->text('remarks')->nullable();
            $table->text('period_of_development')->nullable();
            $table->double('human_resources', 10, 0)->nullable();
            $table->text('incentives')->nullable();
            $table->text('office_address')->nullable();
        });
    }

    public function down()
    {
        Schema::table('horizonstack_investment_investments', function ($table) {
            $table->dropColumn('remarks');
            $table->dropColumn('period_of_development');
            $table->dropColumn('human_resources');
            $table->dropColumn('incentives');
            $table->dropColumn('office_address');
        });
    }
}