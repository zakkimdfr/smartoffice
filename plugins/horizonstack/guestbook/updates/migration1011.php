<?php namespace Horizonstack\Guestbook\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration1011 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_guestbook_guests', function ($table) {
            $table->boolean('is_souvenirs')->default(0);
        });
    }

    public function down()
    {
        Schema::table('horizonstack_guestbook_guests', function ($table) {
            $table->dropColumn('is_souvenirs');
        });
    }
}