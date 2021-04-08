<?php namespace Horizonstack\eSPJ\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHorizonstackEspjTrips extends Migration
{
    public function up()
    {
        Schema::create('horizonstack_espj_trips', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->text('name');
            $table->text('slug')->nullable();
            $table->integer('user_id')->nullable();
            $table->double('budget', 10, 0)->nullable();
            $table->date('start_at')->nullable();
            $table->date('return_at')->nullable();
            $table->bigInteger('origin_city_id')->nullable();
            $table->bigInteger('return_city_id')->nullable();
            $table->text('mission')->nullable();
            $table->integer('signature_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('horizonstack_espj_trips');
    }
}
