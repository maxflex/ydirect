<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStrategyModesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('strategy_modes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('strategy_id')->unsigned();
            $table->foreign('strategy_id')->references('id')->on('strategies');
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('strategy_modes');
    }
}
