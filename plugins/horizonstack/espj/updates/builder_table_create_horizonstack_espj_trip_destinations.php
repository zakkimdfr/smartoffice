<?php namespace Horizonstack\eSPJ\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHorizonstackEspjTripDestinations extends Migration
{
    public function up()
    {
        Schema::create('horizonstack_espj_trip_destinations', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('trip_id')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('horizonstack_espj_trip_destinations');
    }
}