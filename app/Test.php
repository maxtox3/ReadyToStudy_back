<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $fillable = [
        'name', 'description', 'theme_id'
    ];

    public function task()
    {
        return $this->hasMany(Task::class);
    }
}
