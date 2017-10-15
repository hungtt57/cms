<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;

class CollaboratorGroup extends Model
{

    protected $table = 'collaborator_group';
    public $timestamps = false;
    protected $fillable = [
        'group_id'
    ];

}
