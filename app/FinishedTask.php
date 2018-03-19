<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinishedTask extends Model
{
    protected $fillable = [
        'test_id', 'user_id', 'right_answers_count', 'bad_answers_count', 'time_spent',
    ];
}
