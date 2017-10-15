<?php

namespace App\Models\Icheck\User;

use Illuminate\Database\Eloquent\Model;

class UserPointHistory extends Model
{
    protected $connection = 'icheck_user';

    protected $table = 'user_point_history';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    protected $fillable = ['icheck_id','point','source','action','object_type','object_id'];
    public function account(){
        return $this->hasOne(Account::class,'icheck_id','icheck_id');
    }
}
