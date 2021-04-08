<?php namespace Horizonstack\Investment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration1015 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_investment_investments', function ($table) {
            $table->text('description')->nullable();
            $table->string('target_operation')->nullable();
            $table->double('land_size', 10, 0)->nullable();
            $table->text('land_allocation')->nullable();
            $table->string('irr')->nullable();
            $table->double('npv', 10, 0)->nullable();
            $table->string('bcr')->nullable();
            $table->string('payback_period')->nullable();
            $table->string('return_of_investment')->nullable();
            $table->double('break_event_point', 10, 0)->nullable();
            $table->string('profitability_index')->nullable();
        });
    }

    public function down()
    {
        Schema::table('horizonstack_investment_investments', function ($table) {
            $table->dropColumn('description');
            $table->dropColumn('target_operation');
            $table->dropColumn('land_size');
            $table->dropColumn('land_allocation');
            $table->dropColumn('irr');
            $table->dropColumn('npv');
            $table->dropColumn('bcr');
            $table->dropColumn('payback_period');
            $table->dropColumn('return_of_investment');
            $table->dropColumn('break_event_point');
            $table->dropColumn('profitability_index');
        });
    }
}