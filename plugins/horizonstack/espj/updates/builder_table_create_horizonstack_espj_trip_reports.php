<?php namespace Horizonstack\eSPJ\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHorizonstackEspjTripReports extends Migration
{
    public function up()
    {
        Schema::create('horizonstack_espj_trip_reports', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('trip_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('attn_to')->nullable();
            $table->integer('from')->nullable();
            $table->integer('cc')->nullable();
            $table->date('date')->nullable();
            $table->string('report_number')->nullable();
            $table->integer('urgency_id')->nullable();
            $table->string('attachment')->nullable();
            $table->text('subject')->nullable();
            $table->longText('description')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('horizonstack_espj_trip_reports');
    }
}