<?php namespace Horizonstack\Userextend\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration106 extends Migration
{
    public function up()
    {
        if (Schema::hasColumns('users', ['is_profile_completed'])) {
            return;
        }

        Schema::table('users', function ($table) {
            $table->boolean('is_profile_completed')->default(0)->nullable();
        });
    }

    public function down()
    {
        if (Schema::hasTable('is_profile_completed')) {
            Schema::table('users', function ($table) {
                $table->dropColumn('is_profile_completed');
            });
        }
    }
}