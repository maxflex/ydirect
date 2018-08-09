<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Strategies\KeywordStrategy;
use App\Utils\Yandex\Direct;

class SetManualStrategies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manual:set';

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
        $this->info("Getting keywords...");
        $keywords = Direct::keywords(35878143);

        $this->info("Creating strategies...");
        $bar = $this->output->createProgressBar(count($keywords));
        foreach($keywords as $keyword) {
            KeywordStrategy::create([
                'keyword_id' => $keyword->Id,
                'strategy_mode_id' => 2,
                'param_1' => '1.5'
            ]);
            $bar->advance();
        }
        $bar->finish();
    }
}
