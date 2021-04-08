<?php namespace Horizonstack\Investment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHorizonstackInvestmentInvestmentScheme extends Migration
{
    public function up()
    {
        Schema::create('horizonstack_investment_investment_scheme', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->integer('investment_id');
            $table->integer('scheme_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('horizonstack_investment_investment_scheme');
    }
}
