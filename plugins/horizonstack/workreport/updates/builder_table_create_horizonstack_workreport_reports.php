<?php namespace Horizonstack\Workreport\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHorizonstackWorkreportReports extends Migration
{
    public function up()
    {
        Schema::create('horizonstack_workreport_reports', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('attn_to')->nullable();
            $table->integer('from')->nullable();
            $table->integer('cc')->nullable();
            $table->date('date')->nullable();
            $table->string('report_number')->nullable();
            $table->integer('urgency_id')->nullable();
            $table->string('attachment')->nullable();
            $table->text('subject')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('horizonstack_workreport_reports');
    }
}
