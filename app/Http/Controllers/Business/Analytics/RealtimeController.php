<?php

namespace App\Http\Controllers\Business\Analytics;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RealtimeController extends Controller
{
    public function overview()
    {
        return view('business.analytics.realtime.overview');
    }
}
