<?php namespace Horizonstack\Guestbook\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHorizonstackGuestbookEvents extends Migration
{
    public function up()
    {
        Schema::create('horizonstack_guestbook_events', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->date('date')->nullable();
            $table->text('location')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('horizonstack_guestbook_events');
    }
}