<?php namespace Horizonstack\Investment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHorizonstackInvestmentInvestmentInfrastructure extends Migration
{
    public function up()
    {
        Schema::create('horizonstack_investment_investment_infrastructure', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('investment_id');
            $table->integer('infrastructure_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('horizonstack_investment_investment_infrastructure');
    }
}
