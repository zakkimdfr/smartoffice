<?php namespace Horizonstack\Investment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHorizonstackInvestmentSchemes extends Migration
{
    public function up()
    {
        Schema::create('horizonstack_investment_schemes', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('horizonstack_investment_schemes');
    }
}
