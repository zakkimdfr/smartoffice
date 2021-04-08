<?php namespace Horizonstack\Guestbook\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration109 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_guestbook_guests', function ($table) {
            $table->integer('invitation_confirmation')->nullable()->default(1);
            $table->string('invitation_code')->nullable();
            $table->timestamp('invitation_responded_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('horizonstack_guestbook_guests', function ($table) {
            $table->dropColumn('invitation_confirmation');
            $table->dropColumn('invitation_code');
            $table->dropColumn('invitation_responded_at');
        });
    }
}