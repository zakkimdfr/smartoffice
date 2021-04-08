<?php namespace ThePhuc\Tunsurvey\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateThephucTunsurveyQuestions extends Migration
{
    public function up()
    {
        Schema::create('thephuc_tunsurvey_questions', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('survey_id')->unsigned();
            $table->text('question');
            $table->string('code', 100)->nullable();
            $table->integer('sort_order')->default(0);
            $table->integer('control')->unsigned();
            $table->boolean('is_optional')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('thephuc_tunsurvey_questions');
    }
}
