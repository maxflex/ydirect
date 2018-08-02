<?php

namespace App\Utils\Yandex;

use GuzzleHttp\Client;

class Api
{
    const BASE_URI = 'https://api.direct.yandex.com/json/v5/';

    public static $client = null;

    private function __construct()
    {

    }

    public static function client()
    {
        if (self::$client === null) {
            self::$client = new Client([
                'base_uri' => self::BASE_URI,
                'headers' => [
                    'Authorization'   => 'Bearer ' . config('direct.token'),
                    'Content-Type'    => 'application/json',
                    'Accept-Language' => 'ru'
                ]
            ]);
        }
        return self::$client;
    }
}
