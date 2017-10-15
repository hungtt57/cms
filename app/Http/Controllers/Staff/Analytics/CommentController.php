<?php

namespace App\Http\Controllers\Staff\Analytics;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Icheck\Product\Vendor;
use App\Models\Icheck\Product\Product;
use Auth;
use Carbon\Carbon;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('analytics-comment')) {
            abort(403);
        }

        if (!$request->has('date_range')) {
            $endDate = Carbon::now()->endOfDay();
            $startDate = Carbon::now()->subDays(29)->startOfDay();
        } else {
            $dateRange = explode('_', $request->input('date_range'));
            $startDate = Carbon::createFromTimestamp($dateRange[0]);
            $endDate = Carbon::createFromTimestamp($dateRange[1]);
        }

        $selectedGln = $request->input('gln', []);
        $selectedGtin = $request->input('gtin', []);
        $chartData = [];
        $tableData = [];

        if ($selectedGtin) {
            $selectedGtin = Product::select(['gtin_code', 'vendor_id'])->whereIn('gtin_code', $selectedGtin)->get();
            $vendors = Vendor::select(['gln_code'])->whereIn('id', $selectedGtin->lists('vendor_id')->toArray())->get()->lists('gln_code')->toArray();
            $selectedGtin = $selectedGtin->lists('gtin_code')->toArray();
            $selectedGln = array_merge($selectedGln, $vendors);
        }

        if ($selectedGln) {
            $selectedGln = Vendor::whereIn('gln_code', $selectedGln)->get()->lists('gln_code')->toArray();
        }

        if ($selectedGln) {
            for ($current = $startDate->copy(); $current->lte($endDate); $current->addDay()) {
                $chartData[$current->getTimestamp() * 1000] = 0;

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

                            if (!empty($selectedGtin) and !in_array($row[0], $selectedGtin)) {
                                continue;
                            }

                            $day = Carbon::createFromFormat(DATE_ISO8601, $row[8])->startOfDay();

                            if ($day->lt($startDate) or $day->gt($endDate)) {
                                break;
                            }

                            if ($row[6] == 'comment') {
                                $chartData[$day->getTimestamp() * 1000] += 1;
                                $tableData[] = [
                                    'gtin' => $row[0],
                                    'product_name' => $row[1],
                                    'user_id' => $row[2],
                                    'user_name' => $row[3],
                                    'time' => Carbon::createFromFormat(DATE_ISO8601, $row[8]),
                                ];
                            }
                        }

                        fclose($handle);
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
        }

        return view('staff.analytics.comment', compact('startDate', 'endDate', 'gln', 'products', 'chartData', 'tableData', 'selectedGln', 'selectedGtin'));
    }
}
