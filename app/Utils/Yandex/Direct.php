<?php

namespace App\Utils\Yandex;

use App\Models\Strategies\KeywordStrategy;

class Direct {

    const SERVICE_KEYWORDS = 'keywords';
    const SERVICE_KEYWORD_BIDS = 'keywordbids';
    const SERVICE_REPORTS = 'reports';

    public static function call($method, $params, $json_response = true)
    {
        $response = Api::client()->post($method, [
            'json' => $params
        ]);
        $response = $response->getBody()->getContents();
        \Log::info($response);
        return $json_response ? json_decode($response)->result : $response;
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

        $positions = collect(self::keywordsAvgPosition($campaign_id));

        return collect($keywords)->map(function($keyword) use ($positions) {
            $keyword->Bid      = $keyword->Bid / 1000000;
            $keyword->params   = KeywordStrategy::get($keyword->Id);
            $keyword->position = $positions->firstWhere('keyword_id', $keyword->Id)['position'];
            return $keyword;
        })->all();
    }

    public static function keyword($keyword_id)
    {
        $params = self::params('get', [
            'SelectionCriteria' => [
                'Ids' => [$keyword_id]
            ],
            'FieldNames' => ["Id", "Bid", "CampaignId"]
        ]);
        $keyword = self::call(self::SERVICE_KEYWORDS, $params)->Keywords[0];
        $keyword->Bid = $keyword->Bid / 1000000;
        $positions = collect(self::keywordsAvgPosition($keyword->CampaignId));
        $keyword->position = $positions->firstWhere('keyword_id', $keyword->Id)['position'];
        return $keyword;
    }

    public static function keywordsAvgPosition($campaign_id)
    {
        $params = self::params('get', [
            "ReportName" => uniqid(),
            "ReportType" => "CUSTOM_REPORT",
            "SelectionCriteria" => [
                "Filter" => [[
                    "Field" => "CampaignId",
                    "Operator" => "EQUALS",
                    "Values" => [$campaign_id]
                ]]
            ],
            "FieldNames" => ["CriteriaId", "AvgClickPosition"],
            "DateRangeType" => "LAST_7_DAYS",
            "Format" => "TSV",
            "IncludeVAT" => "NO",
            "IncludeDiscount" => "NO"
        ]);

        $data = self::call(self::SERVICE_REPORTS, $params, false);
        $data = explode("\n", trim($data));

        $return = [];
        foreach($data as $d) {
            list($keyword_id, $position) = explode("\t", $d);
            $position = $position == '--' ? null : $position;
            $return[] = compact('keyword_id', 'position');
        }

        return $return;
    }

    public static function setKeywordBid($keyword_id, $bid)
    {
        $params = self::params('set', [
            'KeywordBids' => [
                [
                    'KeywordId' => $keyword_id,
                    'SearchBid' => round($bid) * 1000000
                ]
            ],
        ]);

        self::call(self::SERVICE_KEYWORD_BIDS, $params);
    }

    private static function params($method, $params)
    {
        return compact('method', 'params');
    }
}
