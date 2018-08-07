<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeywordStrategiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keyword_strategies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('keyword_id');
            $table->integer('strategy_mode_id')->unsigned();
            $table->foreign('strategy_mode_id')->references('id')->on('strategy_modes');
            $table->string('param_1')->nullable();
            $table->string('param_2')->nullable();
            $table->string('param_3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keyword_strategies');
    }
}
