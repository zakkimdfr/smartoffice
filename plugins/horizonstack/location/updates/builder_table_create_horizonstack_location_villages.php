<?php namespace Horizonstack\Location\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHorizonstackLocationVillages extends Migration
{
    public function up()
    {
        Schema::create('horizonstack_location_villages', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigInteger('id');
            $table->string('name');
            $table->boolean('is_active')->default(1);
            $table->bigInteger('district_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->primary(['id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('horizonstack_location_villages');
    }
}