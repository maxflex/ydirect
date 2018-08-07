<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Strategies\KeywordStrategy;

class RunStrategies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:strategies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $keyword_strategies = KeywordStrategy::findAll();

        foreach($keyword_strategies as $keyword_strategy) {
            $keyword_strategy->run();
        }
    }
}
