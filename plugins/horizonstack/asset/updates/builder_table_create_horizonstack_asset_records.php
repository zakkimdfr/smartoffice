<?php namespace Horizonstack\Asset\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHorizonstackAssetRecords extends Migration
{
    public function up()
    {
        Schema::create('horizonstack_asset_records', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('activity')->nullable();
            $table->string('slug')->nullable();
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->integer('asset_id')->nullable();
            $table->text('asset_conditions')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('status')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('horizonstack_asset_records');
    }
}
