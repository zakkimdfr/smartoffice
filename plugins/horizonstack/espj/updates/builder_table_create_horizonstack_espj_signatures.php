<?php namespace Horizonstack\eSPJ\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHorizonstackEspjSignatures extends Migration
{
    public function up()
    {
        Schema::create('horizonstack_espj_signatures', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('identity_number')->nullable();
            $table->text('job_position')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('horizonstack_espj_signatures');
    }
}
