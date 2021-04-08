<?php namespace Horizonstack\Guestbook\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration108 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_guestbook_guests', function ($table) {
            $table->integer('organization_id')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->integer('status')->nullable();
        });
    }

    public function down()
    {
        Schema::table('horizonstack_guestbook_guests', function ($table) {
            $table->dropColumn('organization_id');
            $table->dropColumn('city_id');
            $table->dropColumn('status');
        });
    }
}