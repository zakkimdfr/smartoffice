<?php namespace Horizonstack\Asset\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHorizonstackAssetTypes extends Migration
{
    public function up()
    {
        Schema::create('horizonstack_asset_types', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->boolean('is_active')->default(1);
            $table->integer('category_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('horizonstack_asset_types');
    }
}
