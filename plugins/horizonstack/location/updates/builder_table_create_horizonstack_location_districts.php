<?php namespace Horizonstack\Location\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHorizonstackLocationDistricts extends Migration
{
    public function up()
    {
        Schema::create('horizonstack_location_districts', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigInteger('id');
            $table->string('name');
            $table->bigInteger('city_id')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->primary(['id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('horizonstack_location_districts');
    }
}