<?php namespace Horizonstack\Guestbook\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration1010 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_guestbook_guests', function ($table) {
            $table->text('job_title')->nullable();
        });
    }

    public function down()
    {
        Schema::table('horizonstack_guestbook_guests', function ($table) {
            $table->dropColumn('job_title');
        });
    }
}