<?php namespace Horizonstack\Guestbook\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHorizonstackGuestbookEventOrganization extends Migration
{
    public function up()
    {
        Schema::create('horizonstack_guestbook_event_organization', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('event_id');
            $table->integer('organization_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('horizonstack_guestbook_event_organization');
    }
}
