<?php namespace Horizonstack\Guestbook\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration1012 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_guestbook_events', function ($table) {
            $table->boolean('is_public')->default(0);
        });
    }

    public function down()
    {
        Schema::table('horizonstack_guestbook_events', function ($table) {
            $table->dropColumn('is_public');
        });
    }
}