<?php

namespace App\Http\Controllers\Business\Analytics;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Social\Vendor;
use App\Models\Social\Product;
use Auth;
use Carbon\Carbon;

class ScanController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->has('date_range')) {
            $endDate = Carbon::now()->endOfDay();
            $startDate = Carbon::now()->subDays(29)->startOfDay();
        } else {
            $dateRange = explode('_', $request->input('date_range'));
            $startDate = Carbon::createFromTimestamp($dateRange[0]);
            $endDate = Carbon::createFromTimestamp($dateRange[1]);
        }

        $gln = Auth::user()->gln()->orderBy('created_at', 'desc')->get();
        $products = Product::whereHas('vendor', function ($query) use ($gln) {
            $query->whereIn('gln_code', $gln->lists('gln')->toArray());
        })->get();

        if (!$request->has('gln')) {
            $selectedGln = $gln->lists('gln')->toArray();
        } else {
            $selectedGln = $request->input('gln');
        }

        if (!$request->has('gtin')) {
            $selectedGtin = $products->lists('gtin_code')->toArray();
        } else {
            $selectedGtin = $request->input('gtin');
        }

        $chartData = [];
        $tableData = [];

        if ($request->input('group_by') == 'hour') {
            for ($current = $startDate->copy(); $current->lte($endDate); $current->addDay()) {
                $chartData[$current->getTimestamp() * 1000] = 0;

                for ($currentH = $current->copy(); $currentH->diffInDays($current) == 0; $currentH->addHour()) {
                    $chartData[$currentH->getTimestamp() * 1000] = 0;
                }

                foreach ($selectedGln as $number) {
                    $dataFile = config('analytic.data_dir') . '/' . $number . '_actions_' . $current->format('Y-m-d') . '.csv';

                    if (!is_file($dataFile)) {
                        continue;
                    }

                    $header = false;

                    if (($handle = fopen($dataFile, 'r')) !== false) {
                        while (($row = fgetcsv($handle)) !== false) {
                            if (!$header) {
                                $header = true;
                                continue;
                            }

                            if (!in_array($row[0], $selectedGtin)) {
                                continue;
                            }

                            $hour = Carbon::createFromFormat(DATE_ISO8601, $row[8])->minute(0)->second(0);

                            if ($row[6] == 'scan') {
                                $chartData[$hour->getTimestamp() * 1000] += 1;
                                $tableData[] = [
                                    'gtin' => $row[0],
                                    'product_name' => $row[1],
                                    'user_id' => $row[2],
                                    'user_name' => $row[3],
                                    'scan_times' => $row[7],
                                    'time' => Carbon::createFromFormat(DATE_ISO8601, $row[8]),
                                ];
                            }
                        }

                        fclose($handle);
                    }
                }
            }
        } else {
            for ($current = $startDate->copy(); $current->lte($endDate); $current->addDay()) {
                $chartData[$current->getTimestamp() * 1000] = 0;

                foreach ($selectedGln as $number) {
                    $dataFile = config('analytic.data_dir') . '/' . $number . '_actions_' . $current->format('Y-m-d') . '.csv';

                    if (!is_file($dataFile)) {
                        continue;
                    }

                    $header = false;

                    if (($handle = fopen($dataFile, 'r')) !== false) {
                        while (($row = fgetcsv($handle)) !== false) {
                            if (!$header) {
                                $header = true;
                                continue;
                            }

                            if (!in_array($row[0], $selectedGtin)) {
                                continue;
                            }

                            $day = Carbon::createFromFormat(DATE_ISO8601, $row[8])->startOfDay();

                            if ($day->lt($startDate) or $day->gt($endDate)) {
                                break;
                            }

                            if ($row[6] == 'scan') {
                                $chartData[$day->getTimestamp() * 1000] += 1;
                                $tableData[] = [
                                    'gtin' => $row[0],
                                    'product_name' => $row[1],
                                    'user_id' => $row[2],
                                    'user_name' => $row[3],
                                    'scan_times' => $row[7],
                                    'time' => Carbon::createFromFormat(DATE_ISO8601, $row[8]),
                                ];
                            }
                        }

                        fclose($handle);
                    }
                }
            }
        }

        $newChartData = [];

        foreach ($chartData as $key => $value) {
            $newChartData[] = [$key, $value];
        }

        $chartData = $newChartData;
        $tableData = collect($tableData)->sortByDesc(function ($row) {
            return $row['time']->getTimestamp();
        });

        return view('business.analytics.scan', compact('startDate', 'endDate', 'gln', 'products', 'chartData', 'tableData', 'selectedGln', 'selectedGtin'));
    }
}
