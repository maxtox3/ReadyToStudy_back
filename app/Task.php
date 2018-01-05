<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name', 'first_var', 'second_var', 'third_var', 'fourth_var', 'right_var'
    ];
}
