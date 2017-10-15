<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;

class CollaboratorHistoryMoney extends Model
{

    protected $table = 'collaborator_history_money';
    protected $fillable = [
        'collaborator_id','money','group_id','gtin','date'
    ];
    public function collaborator()
    {
        return $this->belongsTo(Collaborator::class, 'collaborator_id');
    }

}
