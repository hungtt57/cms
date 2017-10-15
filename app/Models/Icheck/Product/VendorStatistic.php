<?php

namespace App\Models\Icheck\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Icheck\Product\Country;
use App\Models\Icheck\Product\Vendor;
use App\Models\Enterprise\GLN;

class VendorStatistic extends Model
{
    protected $connection = 'icheck_product';

    protected $table = 'vendor_statistic';

    protected $fillable = [
        'gln_code','name','scan','view','like','vote_good','vote_normal','vote_bad','signed'
    ];

    public function gln()
    {
        return $this->hasOne(GLN::class,'gln','gln_code');
    }
    public function vendor(){
        return $this->hasOne(Vendor::class,'gln_code','gln_code');
    }
    public $timestamps = false;



}
