<?php

namespace App\Models\Strategies;

use Illuminate\Database\Eloquent\Model;
use App\Utils\Yandex\{Direct, DateRangeTypes};

class KeywordStrategy extends Model
{
    protected $fillable = ['keyword_id', 'strategy_mode_id', 'param_1', 'param_2', 'param_3'];
    protected $appends = ['strategy'];
    protected $with = ['strategyMode'];
    public $timestamps = false;

    const EMPTY_STRATEGY = [
        'strategy' => [],
        'strategy_mode' => []
    ];

    public static function get($keyword_id)
    {
        $data = self::where('keyword_id', $keyword_id)->first();
        return $data ?? self::EMPTY_STRATEGY;
    }

    public function strategyMode()
    {
        return $this->belongsTo(StrategyMode::class);
    }

    public function getStrategyAttribute()
    {
        return Strategy::find($this->strategyMode()->value('strategy_id'));
    }

    public function run()
    {
        switch($this->strategy_mode_id) {
            case 1:
            case 2:
                $this->positionStrategy();
                break;
        }
    }

    public function positionStrategy()
    {
        $keyword = Direct::keyword($this->keyword_id, $this->strategyMode->settings->DateRangeType);
        if ($keyword->position) {
            $diff = (float)$keyword->position - (float)$this->param_1;
            $increase_bid_by = round($keyword->Bid * ($diff / 10) * $this->strategyMode->settings->coeff);
            Direct::setKeywordBid($this->keyword_id, $keyword->Bid + $increase_bid_by);
        }
    }
}
