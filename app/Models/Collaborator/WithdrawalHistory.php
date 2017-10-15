<?php

namespace App\Models\Collaborator;

use Jenssegers\Mongodb\Eloquent\Model;

class WithdrawalHistory extends Model
{
    /**
     * {@inheritdoc}
     */
    const CREATED_AT = 'createdAt';

    /**
     * {@inheritdoc}
     */
    const UPDATED_AT = 'updatedAt';

    protected $connection = 'collaborator_mongodb';

    protected $collection = 'withdrawal_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['collaborator_id', 'money','withdrawal_by'];
}
