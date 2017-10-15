<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;
use App\Models\Icheck\Product\Product;

class DNAnswerQuestion extends Model
{

    const ANSWER_BY_BUSINESS = 0;
    const ANSWER_BY_ICHECK = 1;



    protected $table = 'dn_answer_questions';

    protected $fillable = ['question_id', 'content','answerBy',
    ];


}
