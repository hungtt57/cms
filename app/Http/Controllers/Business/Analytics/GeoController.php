<?php

namespace App\Http\Controllers\Business\Analytics;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Social\Vendor;
use App\Models\Social\Product;
use Auth;
use Carbon\Carbon;

class GeoController extends Controller
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

        for ($current = $startDate->copy(); $current->lte($endDate); $current->addDay()) {
            $header = false;

            foreach ($selectedGln as $number) {
                $dataFile = config('analytic.data_dir') . '/' . $number . '_actions_' . $current->format('Y-m-d') . '.csv';

                if (!is_file($dataFile)) {
                    continue;
                }

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

                        if (!$row[10]) {
                            $city = 'undefined';
                        } else {
                            $city = $row[10];
                        }

                        if (!isset($chartData[$city])) {
                            $chartData[$city] = 0;
                        }

                        if (!isset($tableData[$city])) {
                            $tableData[$city] = 0;
                        }

                        $chartData[$city] += 1;
                        $tableData[$city] += 1;
                    }

                    fclose($handle);
                }
            }
        }

        $newChartData = [];

        foreach ($chartData as $key => $value) {
            $newChartData[] = ['name' => $key, 'y' => $value];
        }

        $chartData = $newChartData;
        $tableData = collect($tableData)->sortByDesc(function ($row) {
            return $row;
        });

        return view('business.analytics.geo', compact('startDate', 'endDate', 'gln', 'products', 'chartData', 'tableData', 'selectedGln', 'selectedGtin'));
    }
}
