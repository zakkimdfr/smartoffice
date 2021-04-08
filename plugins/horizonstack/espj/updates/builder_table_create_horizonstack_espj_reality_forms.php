<?php namespace Horizonstack\eSPJ\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHorizonstackEspjRealityForms extends Migration
{
    public function up()
    {
        Schema::create('horizonstack_espj_reality_forms', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->bigInteger('trip_id');
            $table->integer('user_id')->nullable();
            $table->text('mission')->nullable();
            $table->integer('transportation_id')->nullable();
            $table->string('budget_name')->nullable();
            $table->string('budget_code')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('signature_left_id')->nullable();
            $table->integer('signature_right_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('horizonstack_espj_reality_forms');
    }
}