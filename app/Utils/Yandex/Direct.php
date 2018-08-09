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

    /**
     * $DateRangeType – если не установлен, слова возвращаются без позиций
     */
    public static function keywords($campaign_id, $DateRangeType = DateRangeTypes::LAST_7_DAYS)
    {
        $params = self::params('get', [
            'SelectionCriteria' => [
                'CampaignIds' => [$campaign_id]
            ],
            'FieldNames' => ["Id", "Keyword", "Status", "Bid",  "StatisticsSearch"]
        ]);

        $keywords = self::call(self::SERVICE_KEYWORDS, $params)->Keywords;

        $positions = $DateRangeType ? collect(self::keywordsAvgPosition($campaign_id, $DateRangeType)) : null;

        return collect($keywords)->map(function($keyword) use ($positions) {
            $keyword->Keyword = implode(' ', array_filter(explode(' ', $keyword->Keyword), function($word) {
                return strpos($word, '-') !== 0;
            }));
            if ($positions) {
                $keyword->position = $positions->firstWhere('keyword_id', $keyword->Id)['position'];
            }
            $keyword->Bid         = $keyword->Bid / 1000000;
            $keyword->Impressions = $keyword->StatisticsSearch->Impressions;
            $keyword->Clicks      = $keyword->StatisticsSearch->Clicks;
            $keyword->params      = KeywordStrategy::get($keyword->Id);
            unset($keyword->StatisticsSearch);
            return $keyword;
        })->all();
    }

    public static function keyword($keyword_id, $DateRangeType = DateRangeTypes::LAST_7_DAYS)
    {
        $params = self::params('get', [
            'SelectionCriteria' => [
                'Ids' => [$keyword_id]
            ],
            'FieldNames' => ["Id", "Bid", "CampaignId"]
        ]);
        $keyword = self::call(self::SERVICE_KEYWORDS, $params)->Keywords[0];
        $keyword->Bid = $keyword->Bid / 1000000;
        $positions = collect(self::keywordsAvgPosition($keyword->CampaignId, $DateRangeType));
        $keyword->position = $positions->firstWhere('keyword_id', $keyword->Id)['position'];
        return $keyword;
    }

    public static function keywordsAvgPosition($campaign_id, $DateRangeType = DateRangeTypes::LAST_7_DAYS)
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
            "DateRangeType" => $DateRangeType,
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
