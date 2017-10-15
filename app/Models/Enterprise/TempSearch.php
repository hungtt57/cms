<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;

class TempSearch extends Model
{
    protected $table = 'temp_search';
    protected $fillable = [
        'gtin_code',
    ];

}
