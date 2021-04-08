<?php namespace Horizonstack\Investment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHorizonstackInvestmentInvestmentInterests extends Migration
{
    public function up()
    {
        Schema::create('horizonstack_investment_investment_interests', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->integer('investment_id')->nullable();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('organization_name')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('horizonstack_investment_investment_interests');
    }
}
