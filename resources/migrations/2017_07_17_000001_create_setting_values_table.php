<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('setting_definition_id')->unsigned();
            $table->string('identifier');
            $table->mediumText('value');
            $table->timestamps();

            $table->foreign('setting_definition_id')
                ->references('id')
                ->on('setting_definitions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('setting_values');
    }
}
