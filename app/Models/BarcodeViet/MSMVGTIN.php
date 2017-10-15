<?php

namespace App\Models\BarcodeViet;

use Illuminate\Database\Eloquent\Model;


class MSMVGTIN extends Model
{
    protected $connection = 'barcodeViet';

    protected $table = 'msmv_gtins';
    public $timestamps = false;
    protected $primaryKey = 'gtin_code';
    protected $fillable = [
        'gtin_code','company_name','company_address','company_contact'
    ];
}
