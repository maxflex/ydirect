<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\Yandex\Direct;

class DirectController extends Controller
{
    public function keywords($campaign_id)
    {
        return response()->json(Direct::keywords($campaign_id));
    }
}
