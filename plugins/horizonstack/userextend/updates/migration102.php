<?php namespace Horizonstack\Userextend\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration102 extends Migration
{
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->text('unique_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('sub_department_id')->nullable();
            $table->text('job')->nullable();
            $table->integer('job_level_id')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->double('latitude', 10, 0)->nullable();
            $table->double('longitude', 10, 0)->nullable();
            $table->integer('user_type')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('unique_id');
            $table->dropColumn('department_id');
            $table->dropColumn('sub_department_id');
            $table->dropColumn('job');
            $table->dropColumn('job_level_id');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('phone');
            $table->dropColumn('address');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('user_type');
        });
    }
}