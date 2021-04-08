<?php namespace Horizonstack\Investment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration1014 extends Migration
{
    public function up()
    {
        Schema::table('horizonstack_investment_investments', function ($table) {
            $table->integer('property_type_id')->nullable();
            $table->dropColumn('is_study_documents');
        });
    }

    public function down()
    {
        Schema::table('horizonstack_investment_investments', function ($table) {
            $table->dropColumn('property_type_id');
            $table->boolean('is_study_documents')->default(0);
        });
    }
}