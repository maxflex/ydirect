<?php

namespace App\Utils\Yandex;

class Direct {

    const SERVICE_KEYWORDS = 'keywords';

    public static function call($method, $params)
    {
        $response = Api::client()->post($method, [
            'json' => $params
        ]);
        return json_decode($response->getBody()->getContents())->result;
    }

    public static function keywords($campaign_id)
    {
        $params = self::params('get', [
            'SelectionCriteria' => [
                'CampaignIds' => [$campaign_id]
            ],
            'FieldNames' => ["Id", "Keyword", "Status", "Bid", "ServingStatus"]
        ]);

        $keywords = self::call(self::SERVICE_KEYWORDS, $params)->Keywords;

        return collect($keywords)->map(function($keyword) {
            $keyword->Bid = $keyword->Bid / 1000000;
            return $keyword;
        })->all();
    }

    private static function params($method, $params)
    {
        return compact('method', 'params');
    }
}
