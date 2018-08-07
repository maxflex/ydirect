<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Strategies\{Strategy, StrategyMode};

class StrategiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        Strategy::truncate();
        StrategyMode::truncate();

        $strategy = Strategy::create(['name' => 'желаемая позиция']);
        StrategyMode::create([
            'name' => 'лёгкий',
            'strategy_id' => $strategy->id
        ]);


        Schema::enableForeignKeyConstraints();
    }
}
