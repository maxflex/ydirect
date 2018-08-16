<?php

namespace App\Models\Strategies;

use Illuminate\Database\Eloquent\Model;
use App\Utils\Yandex\DateRangeTypes;

class StrategyMode extends Model
{
    protected $fillable = ['name', 'strategy_id'];
    public $timestamps = false;

    public function getSettingsAttribute()
    {
        switch($this->id) {
            case 1:
                return (object)[
                    'DateRangeType' => DateRangeTypes::LAST_7_DAYS,
                    'coeff' => 1,
                ];
            case 2:
                return (object)[
                    'DateRangeType' => DateRangeTypes::LAST_3_DAYS,
                    'coeff' => 5,
                ];
        }
    }
}
