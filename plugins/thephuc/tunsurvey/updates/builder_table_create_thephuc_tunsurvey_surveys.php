<?php namespace ThePhuc\Tunsurvey\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateThephucTunsurveySurveys extends Migration
{
    public function up()
    {
        Schema::create('thephuc_tunsurvey_surveys', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 300);
            $table->string('code', 100)->nullable();
            $table->integer('status')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('thephuc_tunsurvey_surveys');
    }
}
