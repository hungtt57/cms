<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;

class Brole extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'broles';
    protected $fillable = [
        'name',
        'quota'
    ];

    public function permissions()
    {
        return $this->belongsToMany(Bpermission::class,'brole_permission')->withPivot('value');
    }
}
