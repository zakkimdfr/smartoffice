<?php namespace Horizonstack\Guestbook\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHorizonstackGuestbookGuests extends Migration
{
    public function up()
    {
        Schema::create('horizonstack_guestbook_guests', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('phone')->nullable();
            $table->integer('job_id')->nullable();
            $table->string('organization')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->integer('event_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('horizonstack_guestbook_guests');
    }
}
