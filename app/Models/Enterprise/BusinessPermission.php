<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;
use App\Models\Enterprise\Role;

class BusinessPermission extends Model
{
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    public $timestamps = false;

    protected $table = 'business_permission';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['business_id', 'permission_id','value'];

    /**
     * Get all of the tags for the post.
     */
}
