<?php

namespace App\Models\Strategies;

use Illuminate\Database\Eloquent\Model;

class StrategyMode extends Model
{
    protected $fillable = ['name', 'strategy_id'];
    public $timestamps = false;
}
