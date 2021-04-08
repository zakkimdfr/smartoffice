<?php namespace ThePhuc\Tunsurvey\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateThephucTunsurveyUseranswers extends Migration
{
    public function up()
    {
        Schema::create('thephuc_tunsurvey_useranswers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('survey_id')->unsigned();
            $table->text('content');
            $table->string('user_ip', 100);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('thephuc_tunsurvey_useranswers');
    }
}
