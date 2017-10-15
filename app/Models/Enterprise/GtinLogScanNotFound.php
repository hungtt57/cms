<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;

class GtinLogScanNotFound extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'gtin_log_scan_not_found';
    protected $fillable = [
        'gtin_code','score'
    ];
    public $timestamps = false;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
