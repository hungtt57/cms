<?php

namespace App\Models\Icheck\User;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    const COLLABORATOR = 1;
    protected $connection = 'icheck_user';

    protected $table = 'account';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
                                                                      
}
