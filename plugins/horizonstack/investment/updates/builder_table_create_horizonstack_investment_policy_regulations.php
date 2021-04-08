<?php namespace Horizonstack\Investment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHorizonstackInvestmentPolicyRegulations extends Migration
{
    public function up()
    {
        Schema::create('horizonstack_investment_policy_regulations', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->text('name')->nullable();
            $table->integer('investment_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('horizonstack_investment_policy_regulations');
    }
}