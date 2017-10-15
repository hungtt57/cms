<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;
use App\Models\Enterprise\Role;

class UserRole extends Model
{
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    public $timestamps = false;

    protected $table = 'user_role';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'role_id'];

    /**
     * Get all of the tags for the post.
     */
}
