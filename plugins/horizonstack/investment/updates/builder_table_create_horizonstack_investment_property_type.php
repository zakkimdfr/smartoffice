<?php namespace Horizonstack\Investment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHorizonstackInvestmentPropertyType extends Migration
{
    public function up()
    {
        Schema::create('horizonstack_investment_property_type', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('investment_id');
            $table->integer('property_type_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('horizonstack_investment_property_type');
    }
}
