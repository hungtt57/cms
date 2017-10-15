<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;
use App\Models\Enterprise\Role;

class RolePermission extends Model
{
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    public $timestamps = false;

    protected $table = 'role_permission';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['role_id', 'permission_id','value'];

    /**
     * Get all of the tags for the post.
     */
}
