<?php

namespace App\Models\Strategies;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Selectable;

class Strategy extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;

    public function modes()
    {
        return $this->hasMany(StrategyMode::class);
    }
}
