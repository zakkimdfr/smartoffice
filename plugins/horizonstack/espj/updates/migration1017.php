<?php namespace Horizonstack\eSPJ\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration1017 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_espj_signatures', function ($table) {
            $table->string('job_level')->nullable();
        });
    }

    public function down()
    {
        Schema::table('horizonstack_espj_signatures', function ($table) {
            $table->dropColumn('job_level');
        });
    }
}