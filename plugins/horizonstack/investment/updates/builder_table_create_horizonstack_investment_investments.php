<?php namespace Horizonstack\Investment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHorizonstackInvestmentInvestments extends Migration
{
    public function up()
    {
        Schema::create('horizonstack_investment_investments', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('agent_name')->nullable();
            $table->string('agent_phone')->nullable();
            $table->string('agent_email')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->bigInteger('district_id')->nullable();
            $table->bigInteger('village_id')->nullable();
            $table->text('address')->nullable();
            $table->double('latitude', 10, 0)->nullable();
            $table->double('longitude', 10, 0)->nullable();
            $table->integer('ownership_id')->nullable();
            $table->integer('project_level_id')->nullable();
            $table->integer('organization_id')->nullable();
            $table->string('organization_name')->nullable();
            $table->double('investment_value')->nullable();
            $table->text('objectives')->nullable();
            $table->boolean('is_study_documents')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('horizonstack_investment_investments');
    }
}