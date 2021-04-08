<?php namespace ThePhuc\Tunsurvey\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateThephucTunsurveyAnswers extends Migration
{
    public function up()
    {
        Schema::create('thephuc_tunsurvey_answers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('code', 100)->nullable();
            $table->text('answer');
            $table->integer('question_id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('thephuc_tunsurvey_answers');
    }
}
