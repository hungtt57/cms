<?php
namespace App\GALib;

use Carbon\Carbon;
use Spatie\Analytics\Analytics;

use Spatie\Analytics\Period;
use App\GALib\AnalyticsClientLib;
use Illuminate\Support\Facades\Log;

class AnalyticsLib extends Analytics
{

    public function __construct()
    {

        $new = new AnalyticsClientLib();
        $key = env('ANALYTICS_VIEW_ID');
        parent::__construct($new, $key);
    }

    public function performRealTimeQuery(string $metrics, array $others = [])
    {
        $response = $this->client->performRealTimeQuery(
            $this->viewId,
            $metrics,
            $others
        );

        if (empty($response['row'])) {
            return 0;
        } else {
            return (int)$response['row'];
        }
    }

    public function getRealActiveUser()
    {
        return $this->performRealTimeQuery('rt:activeVisitors');
    }

    public function getNewUser(Period $period)
    {
        $response = $this->performQuery(
            $period,
            'ga:newUsers',
            [
                'dimensions' => 'ga:date',

            ]
        );

        return collect($response['rows'] ?? [])
            ->map(function (array $pageRow) {
                return [
                    'time' => Carbon::createFromTimestamp(strtotime($pageRow[0])),
                    'total' => (int)$pageRow[1],
                ];
            });
    }


    public function getPeakCCU($time)
    {
        $period = Period::create($time, $time);
        $response = $this->performQuery(
            $period,
            'ga:users',
            [
                'dimensions' => 'ga:hour',
                'sort' => '-ga:users'

            ]
        );
        return [
            'time' => collect($response['rows'])[0][0],
            'total' => (int)collect($response['rows'])[0][1]

        ];

    }

    public function query(Period $period, string $metrics, array $others = [])
    {

        return $this->client->query(
            $this->viewId,
            $period->startDate,
            $period->endDate,
            $metrics,
            $others
        );
    }

    public function getGtinCode($startDate, $endDate, $gtin_code)
    {

        $period = Period::create($startDate, $endDate);
        $metrics = 'ga:totalEvents';
        $response = $this->query($period, $metrics, [
            'dimensions' => 'ga:eventAction,ga:dimension1,ga:date',
            'filters' => 'ga:dimension1==' . $gtin_code,
        ]);


        return collect($response['rows'] ?? [])->map(function (array $Row) {

            return [
                'event' => $Row[0],
                'date' => Carbon::createFromFormat('Ymd', $Row[2])->startOfDay(),
                'totalEvent' => (int)$Row[3],
            ];
        });
    }

    public function getAllGtinCode($startDate, $endDate, $list_gtin)
    {
        $period = Period::create($startDate, $endDate);
        $metrics = 'ga:totalEvents';
        $filter = null;
        $count = 0;
        $list_filter = [];
        $i = 0;

        foreach ($list_gtin as $key => $gtin) {

            if ($count == 0) {
                $filter = 'ga:dimension1==' . $gtin;
                $list_filter[$i] = $filter;
            } else {
                $filter = $filter . ',' . 'ga:dimension1==' . $gtin;
                $list_filter[$i] = $filter;
            }
            if ($count == 100) {
                $i++;
                $count = -1;
            }
            $count++;
        }

        $chartData = [];

        foreach ($list_filter as $filter) {
            $response = $this->query($period, $metrics, [
                'dimensions' => 'ga:date',
                'filters' => $filter,
            ]);

            foreach ($response['rows'] as $row) {
                if (isset($chartData[Carbon::createFromFormat('Ymd', $row[0])->startOfDay()->getTimestamp()])) {
                    $chartData[Carbon::createFromFormat('Ymd', $row[0])->startOfDay()->getTimestamp() * 1000] = $chartData[Carbon::createFromFormat('Ymd', $row[0])->startOfDay()->getTimestamp() * 1000] + intval($row[1]);

                } else {
                    $chartData[Carbon::createFromFormat('Ymd', $row[0])->startOfDay()->getTimestamp() * 1000] = intval($row[1]);
                }

            }

        }

        $new_chart = [];
        foreach ($chartData as $key => $c) {
            $new_chart[] = [$key, $c];
        }

        return $new_chart;

    }


    public function getInfo($startDate, $endDate, $list_gtin)
    {
        $filter = null;
        $count = 0;

        foreach ($list_gtin as $key => $gtin) {

            if ($count == 0) {
                $filter = 'ga:dimension1==' . $key;
            } else {
                $filter = $filter . ',' . 'ga:dimension1==' . $key;
            }
            $count++;
        }

        $filter = 'ga:eventAction==pro_show,ga:eventAction==pro_scan,ga:eventAction==pro_like,ga:eventAction==pro_comment;' . $filter;

        $period = Period::create($startDate, $endDate);
        $metrics = 'ga:totalEvents';
        try {
            $response = $this->query($period, $metrics, [
                'dimensions' => 'ga:eventAction,ga:dimension1,ga:dimension2',
                'filters' => $filter,
            ]);
        } catch (\Exception $ex) {
            $array = [];
            return $array;
        }
        $result = [];
        if (!empty($response['rows'])) {
            foreach ($response['rows'] as $row) {
                $result[$row[1]][$row[0]] = $row[3];
                $result[$row[1]]['name'] = $row[2];
            }
        }

        return $result;

    }

    public function getInfoCategory($startDate, $endDate, $list_gtin)
    {
        $period = Period::create($startDate, $endDate);

        $metrics = 'ga:totalEvents';
        $filter = null;
        $count = 0;
        $list_filter = [];
        $i = 0;

        foreach ($list_gtin as $key => $gtin) {

            if ($count == 0) {
                $filter = 'ga:dimension1==' . $gtin;
                $list_filter[$i] = $filter;
            } else {
                $filter = $filter . ',' . 'ga:dimension1==' . $gtin;
                $list_filter[$i] = $filter;
            }
            if ($count == 100) {
                $i++;
                $count = -1;
            }
            $count++;
        }

        $chartData = [];

        foreach ($list_filter as $filter) {
            $filter = 'ga:eventAction==pro_scan;' . $filter;
            $response = $this->query($period, $metrics, [
                'dimensions' => 'ga:date',
                'filters' => $filter,
            ]);
            foreach ($response['rows'] as $row) {
                if (isset($chartData[Carbon::createFromFormat('Ymd', $row[0])->startOfDay()->getTimestamp()])) {
                    $chartData[Carbon::createFromFormat('Ymd', $row[0])->startOfDay()->getTimestamp()] = $chartData[Carbon::createFromFormat('Ymd', $row[0])->startOfDay()->getTimestamp()] + intval($row[1]);

                } else {
                    $chartData[Carbon::createFromFormat('Ymd', $row[0])->startOfDay()->getTimestamp()] = intval($row[1]);
                }

            }

        }

        return $chartData;


    }

    public function getDataByCronJob($startDate, $endDate, $list_gtin)
    {
        $period = Period::create($startDate, $endDate);
        $metrics = 'ga:totalEvents';
        $filter = null;
        $count = 0;
        $list_filter = [];
        $i = 0;

        foreach ($list_gtin as $key => $gtin) {

            if ($count == 0) {
                $filter = 'ga:dimension1==' . $gtin;
                $list_filter[$i] = $filter;
            } else {
                $filter = $filter . ',' . 'ga:dimension1==' . $gtin;
                $list_filter[$i] = $filter;
            }
            if ($count == 100) {
                $i++;
                $count = -1;
            }
            $count++;
        }

        $total = 0;
        foreach ($list_filter as $filter) {
            try {
                $response = $this->query($period, $metrics, [
                    'dimensions' => 'ga:date',
                    'filters' => $filter,
                ]);

            } catch (\Exception $ex) {

                return [];
            }

            if ($response) {
                foreach ($response['rows'] as $row) {
                    $total = $total + intval($row[1]);

                }
            }


        }
        return $total;

    }


    public function getDataBusinessCategory($startDate, $endDate, $list_gtin)
    {
        $period = Period::create($startDate, $endDate);
        $metrics = 'ga:totalEvents';
        $filter = null;
        $count = 0;
        $list_filter = [];
        $i = 0;

        foreach ($list_gtin as $key => $gtin) {

            if ($count == 0) {
                $filter = 'ga:dimension1==' . $gtin;
                $list_filter[$i] = $filter;
            } else {
                $filter = $filter . ',' . 'ga:dimension1==' . $gtin;
                $list_filter[$i] = $filter;
            }
            if ($count == 100) {
                $i++;
                $count = -1;
            }
            $count++;
        }
        $total = [];
        $total['pro_scan'] = 0;
        $total['pro_show'] = 0;
        $total['pro_comment'] = 0;
        $total['pro_like'] = 0;

        foreach ($list_filter as $filter) {
            $filter = 'ga:eventAction==pro_show,ga:eventAction==pro_scan,ga:eventAction==pro_like,ga:eventAction==pro_comment;' . $filter;
            try {
                $response = $this->query($period, $metrics, [
                    'dimensions' => 'ga:eventAction,ga:date',
                    'filters' => $filter,
                ]);

                if ($response['rows']) {
                    foreach ($response['rows'] as $row) {
                        if ($row[0] == 'pro_scan') {
                            $total['pro_scan'] = $total['pro_scan'] + intval($row['2']);
                        }
                        if ($row[0] == 'pro_show') {
                            $total['pro_show'] = $total['pro_show'] + intval($row['2']);
                        }
                        if ($row[0] == 'pro_comment') {
                            $total['pro_comment'] = $total['pro_comment'] + intval($row['2']);
                        }
                        if ($row[0] == 'pro_like') {
                            $total['pro_like'] = $total['pro_like'] + intval($row['2']);
                        }
                    }
                }
            } catch (\Exception $ex) {

                 return [];
            }


        }
        return $total;

    }

    public function getBusinessAge($startDate, $endDate, $list_gtin)
    {
        $period = Period::create($startDate, $endDate);
        $metrics = 'ga:totalEvents';
        $filter = null;
        $count = 0;
        $list_filter = [];
        $i = 0;

        foreach ($list_gtin as $key => $gtin) {

            if ($count == 0) {
                $filter = 'ga:dimension1==' . $gtin;
                $list_filter[$i] = $filter;
            } else {
                $filter = $filter . ',' . 'ga:dimension1==' . $gtin;
                $list_filter[$i] = $filter;
            }
            if ($count == 50) {
                $i++;
                $count = -1;
            }
            $count++;
        }

        $total = [];
        $total['18-24'] = 0;
        $total['25-34'] = 0;
        $total['35-44'] = 0;
        $total['45-54'] = 0;
        $total['55-64'] = 0;
        $total['65+'] = 0;

        foreach ($list_filter as $filter) {
            try {
                $filter = 'ga:eventAction==pro_scan;'.$filter;
                $response = $this->query($period, $metrics, [
                    'dimensions' => 'ga:date,ga:userAgeBracket',
                    'filters' => $filter,
                ]);

                if($response['rows']){
                    foreach ($response['rows'] as $row) {
                        if ($row[1] == '18-24') {
                            $total['18-24'] = $total['18-24'] + intval($row['2']);
                        }
                        if ($row[1] == '25-34') {
                            $total['25-34'] = $total['25-34'] + intval($row['2']);
                        }
                        if ($row[1] == '35-44') {
                            $total['35-44'] = $total['35-44'] + intval($row['2']);
                        }
                        if ($row[1] == '45-54') {
                            $total['45-54'] = $total['45-54'] + intval($row['2']);
                        }
                        if ($row[1] == '55-64') {
                            $total['55-64'] = $total['55-64'] + intval($row['2']);
                        }
                        if ($row[1] == '65+') {
                            $total['65+'] = $total['65+'] + intval($row['2']);
                        }
                    }
                }

            } catch (\Exception $ex) {
                dd($ex->getMessage());
              return [];
            }


        }
        return $total;

    }


    public function getBusinessLocation($startDate, $endDate, $list_gtin)
    {
        $period = Period::create($startDate, $endDate);
        $metrics = 'ga:totalEvents';
        $filter = null;
        $count = 0;
        $list_filter = [];
        $i = 0;

        foreach ($list_gtin as $key => $gtin) {

            if ($count == 0) {
                $filter = 'ga:dimension1==' . $gtin;
                $list_filter[$i] = $filter;
            } else {
                $filter = $filter . ',' . 'ga:dimension1==' . $gtin;
                $list_filter[$i] = $filter;
            }
            if ($count == 50) {
                $i++;
                $count = -1;
            }
            $count++;
        }

        $total = [];


        foreach ($list_filter as $filter) {
            try {
                $filter = 'ga:country==Vietnam;ga:eventAction==pro_show,ga:eventAction==pro_scan,ga:eventAction==pro_like,ga:eventAction==pro_comment;'.$filter;
                $response = $this->query($period, $metrics, [
                    'dimensions' => 'ga:region,ga:eventAction',
                    'filters' => $filter,
                ]);
                foreach ($response['rows'] as $row) {
                    if($row[1] == 'pro_show'){
                        if(isset($total[$row[0]]['pro_show'])){
                            $total[$row[0]]['pro_show'] = $total[$row[0]]['pro_show'] + intval($row[2]);
                        }else{
                            $total[$row[0]]['pro_show'] =intval($row[2]);
                        }
                    }

                    if($row[1] == 'pro_scan'){
                        if(isset($total[$row[0]]['pro_scan'])){
                            $total[$row[0]]['pro_scan'] = $total[$row[0]]['pro_scan'] + intval($row[2]);
                        }else{
                            $total[$row[0]]['pro_scan'] =intval($row[2]);
                        }
                    }

                    if($row[1] == 'pro_like'){
                        if(isset($total[$row[0]]['pro_like'])){
                            $total[$row[0]]['pro_like'] = $total[$row[0]]['pro_like'] + intval($row[2]);
                        }else{
                            $total[$row[0]]['pro_like'] =intval($row[2]);
                        }
                    }

                    if($row[1] == 'pro_comment'){
                        if(isset($total[$row[0]]['pro_comment'])){
                            $total[$row[0]]['pro_comment'] = $total[$row[0]]['pro_comment'] + intval($row[2]);
                        }else{
                            $total[$row[0]]['pro_comment'] =intval($row[2]);
                        }
                    }


                }

            } catch (\Exception $ex) {
                return [];
            }


        }
        return $total;

    }

    // report CMS

    public function getDataCategory($startDate, $endDate, $list_gtin)
    {
        $period = Period::create($startDate, $endDate);
        $metrics = 'ga:totalEvents';
        $filter = null;
        $count = 0;
        $list_filter = [];
        $i = 0;

        foreach ($list_gtin as $key => $gtin) {

            if ($count == 0) {
                $filter = 'ga:dimension1==' . $gtin;
                $list_filter[$i] = $filter;
            } else {
                $filter = $filter . ',' . 'ga:dimension1==' . $gtin;
                $list_filter[$i] = $filter;
            }
            if ($count == 100) {
                $i++;
                $count = -1;
            }
            $count++;
        }
        $total = 0;
        $result = [];
        foreach ($list_filter as $filter) {
            $filter = 'ga:eventAction==pro_show,ga:eventAction==pro_scan,ga:eventAction==pro_like,ga:eventAction==pro_comment;' . $filter;
            try {
                $response = $this->query($period, $metrics, [
                    'dimensions' => 'ga:eventAction',
                    'filters' => $filter,
                ]);
                if ($response['rows']) {
                    foreach ($response['rows'] as $row) {
                        if (!isset($result[$row[0]])) {
                            $result[$row[0]] = intval($row[1]);
                        } else {
                            $result[$row[0]] = $result[$row[0]] + intval($row[1]);
                        }
                    }
                }
            } catch (\Exception $ex) {


            }


        }
        return $result;

    }

    public function getReportProduct($startDate, $endDate, $gtin_code)
    {

        $period = Period::create($startDate, $endDate);
        $metrics = 'ga:totalEvents';
        $response = $this->query($period, $metrics, [
            'dimensions' => 'ga:eventAction,ga:dimension1,ga:date',
            'filters' => 'ga:dimension1==' . $gtin_code,
        ]);


        return collect($response['rows'] ?? [])->map(function (array $Row) {

            return [
                'event' => $Row[0],
                'date' => Carbon::createFromFormat('Ymd', $Row[2])->startOfDay(),
                'totalEvent' => (int)$Row[3],
            ];
        });
    }


    public function getInfoReportCategory($startDate, $endDate, $list_category)
    {
        $period = Period::create($startDate, $endDate);
        $metrics = 'ga:totalEvents';
        $filter = null;

        foreach ($list_category as $key => $gtin) {

            if ($key == 0) {
                $filter = 'ga:dimension4==' . $gtin->id;
            } else {
                $filter = $filter . ',' . 'ga:dimension4==' . $gtin->id;
            }
        }
        $filter = 'ga:eventAction==pro_show,ga:eventAction==pro_scan,ga:eventAction==pro_like,ga:eventAction==pro_comment;' . $filter;
        try {
            $response = $this->query($period, $metrics, [
                'dimensions' => 'ga:eventAction,ga:dimension4',
                'filters' => $filter,
            ]);
        } catch (\Exception $ex) {
            $array = [];
            return $array;
        }
        $result = [];
        if (!empty($response['rows'])) {
            foreach ($response['rows'] as $row) {
                $result[$row[1]][$row[0]] = intval($row[2]);
            }
        }
        return $result;

    }

    public function getInfoReportCategoryDetail($startDate, $endDate, $id)
    {
        $period = Period::create($startDate, $endDate);
        $metrics = 'ga:totalEvents';
        $filter = 'ga:eventAction==pro_show,ga:eventAction==pro_scan,ga:eventAction==pro_like,ga:eventAction==pro_comment;ga:dimension4==' . $id;
        try {
            $response = $this->query($period, $metrics, [
                'dimensions' => 'ga:date,ga:eventAction,ga:dimension4',
                'filters' => $filter,
            ]);
        } catch (\Exception $ex) {
            $array = [];
            return $array;
        }
        return collect($response['rows'] ?? [])->map(function (array $Row) {

            return [
                'event' => $Row[1],
                'date' => Carbon::createFromFormat('Ymd', $Row[0])->startOfDay(),
                'totalEvent' => (int)$Row[3],
            ];
        });

    }


    public function getInfoReportVendor($startDate, $endDate, $list_vendor)
    {
        $period = Period::create($startDate, $endDate);
        $metrics = 'ga:totalEvents';
        $filter = null;

        foreach ($list_vendor as $key => $gtin) {

            if ($key == 0) {
                $filter = 'ga:dimension3==' . $gtin->id;
            } else {
                $filter = $filter . ',' . 'ga:dimension3==' . $gtin->id;
            }
        }
        $filter = 'ga:eventAction==pro_show,ga:eventAction==pro_scan,ga:eventAction==pro_like,ga:eventAction==pro_comment,ga:eventAction==pro_click,ga:eventAction==pro_chat;' . $filter;

        try {
            $response = $this->query($period, $metrics, [
                'dimensions' => 'ga:eventAction,ga:dimension3',
                'filters' => $filter,
            ]);
        } catch (\Exception $ex) {
            $array = [];
            return $array;
        }
        $result = [];
        if (!empty($response['rows'])) {
            foreach ($response['rows'] as $row) {
                $result[$row[1]][$row[0]] = intval($row[2]);
            }
        }
        return $result;

    }


    public function getInfoProduct($startDate, $endDate, $list_gtin)
    {
        $filter = null;
        $count = 0;
        foreach ($list_gtin as $key => $gtin) {

            if ($count == 0) {
                $filter = 'ga:dimension1==' . $gtin;
            } else {
                $filter = $filter . ',' . 'ga:dimension1==' . $gtin;
            }
            $count++;
        }

        $filter = 'ga:eventAction==pro_show,ga:eventAction==pro_scan,ga:eventAction==pro_like,ga:eventAction==pro_comment;' . $filter;

        $period = Period::create($startDate, $endDate);
        $metrics = 'ga:totalEvents';
        try {
            $response = $this->query($period, $metrics, [
                'dimensions' => 'ga:eventAction,ga:dimension1,ga:dimension2',
                'filters' => $filter,
            ]);
        } catch (\Exception $ex) {
            $array = [];
            return $array;
        }

        $result = [];

        if (!empty($response['rows'])) {
            foreach ($response['rows'] as $row) {
                $result[$row[1]][$row[0]] = $row[3];
                $result[$row[1]]['name'] = $row[2];
            }
        }

        return $result;

    }
    //end report CMS


}

?>